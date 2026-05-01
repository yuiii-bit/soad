<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $user = auth()->user();
        return view('shop.checkout', compact('cart', 'user'));
    }

    public function fastCheckout(Request $request) 
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        if ($request->quantity > $product->stock) {
            return redirect()->back()->with('error', "Rất tiếc! Chỉ còn {$product->stock} sản phẩm trong kho.");
        }

        $cart = [
            $product->id => [
                'name' => $product->name,
                'quantity' => $request->quantity,
                'price' => $product->discount_price ?? $product->price,
                'image' => current(json_decode($product->image_list ?? '[]')) ?: $product->image
            ]
        ];

        session()->put('cart', $cart);

        return redirect()->route('checkout.index');
    }

    public function process(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect()->route('cart.index');

        // Validate dữ liệu đầu vào
        $request->validate([
            'name'           => 'required|string|max:100',
            'phone'          => 'required|string|max:20',
            'email'          => 'required|email|max:100',
            'address'        => 'required|string|max:500',
            'payment_method' => 'required|in:cod,momo,bank',
        ], [
            'name.required'    => 'Vui lòng nhập họ và tên.',
            'phone.required'   => 'Vui lòng nhập số điện thoại.',
            'email.required'   => 'Vui lòng nhập email.',
            'email.email'      => 'Email không hợp lệ.',
            'address.required' => 'Vui lòng nhập địa chỉ giao hàng.',
        ]);

        DB::beginTransaction();

        try {
            $user_id = auth()->id();

            // Tính tổng tiền
            $subTotal = 0;
            foreach ($cart as $item) {
                $subTotal += $item['price'] * $item['quantity'];
            }

            // Xử lý mã giảm giá
            $discount = 0;
            $couponId = null;
            if (session('coupon')) {
                $coupon = session('coupon');
                if ($subTotal >= $coupon['min_order_value']) {
                    $discount = $coupon['discount'];
                    $couponId = collect(\App\Models\Coupon::where('code', $coupon['code'])->first())->get('id');
                    
                    // Giảm quantity của coupon
                    \App\Models\Coupon::where('code', $coupon['code'])->decrement('quantity');
                }
            }

            $totalAmount = max(0, $subTotal - $discount);

            // 1. Tạo Đơn Hàng
            $order = new Order();
            $order->user_id          = $user_id;
            $order->customer_name    = $request->name;
            $order->customer_phone   = $request->phone;
            $order->customer_email   = $request->email;
            $order->order_code       = 'ORD-' . strtoupper(Str::random(8));
            $order->total_amount     = $totalAmount;
            $order->status           = 'pending';
            $order->shipping_address = $request->address;
            $order->payment_method   = $request->payment_method ?? 'cod';
            // Lưu thông tin voucher nếu cần (cần add cột discount vào order migration sau, tạm thời ta tính thẳng vào total_amount)
            $order->save();

            // 2. Chi Tiết Đơn Hàng
            foreach ($cart as $product_id => $item) {
                $orderDetail = new OrderDetail();
                $orderDetail->order_id   = $order->id;
                $orderDetail->product_id = $product_id;
                $orderDetail->quantity   = $item['quantity'];
                $orderDetail->price      = $item['price'];
                $orderDetail->save();
            }

            DB::commit();
            session()->forget(['cart', 'coupon']);

            return redirect()->route('checkout.success')->with([
                'order_code'    => $order->order_code,
                'customer_name' => $order->customer_name,
                'total_amount'  => $totalAmount,
                'payment_method'=> $order->payment_method,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $code = strtoupper($request->code);

        $coupon = \App\Models\Coupon::where('code', $code)->first();

        if (!$coupon) {
            return redirect()->back()->with('error', 'Mã giảm giá không tồn tại.');
        }

        if (!$coupon->isValid()) {
            return redirect()->back()->with('error', 'Mã giảm giá đã hết hạn hoặc hết lượt sử dụng.');
        }

        // Tính tạm tính giỏ hàng
        $cart = session('cart', []);
        $subTotal = 0;
        foreach ($cart as $item) {
            $subTotal += $item['price'] * $item['quantity'];
        }

        if ($subTotal < $coupon->min_order_value) {
            return redirect()->back()->with('error', 'Đơn hàng chưa đạt mức tối thiểu (' . number_format($coupon->min_order_value,0,',','.') . '₫) để áp dụng mã này.');
        }

        // Tính số tiền giảm
        $discountLabel = '';
        $discountAmount = 0;

        if ($coupon->discount_type === 'percent') {
            $discountAmount = ($subTotal * $coupon->discount_value) / 100;
            $discountLabel = '-' . $coupon->discount_value . '% (' . number_format($discountAmount,0,',','.') . '₫)';
        } else {
            $discountAmount = $coupon->discount_value;
            // Đảm bảo không giảm lố giá trị đơn hàng
            if ($discountAmount > $subTotal) $discountAmount = $subTotal;
            $discountLabel = '-' . number_format($discountAmount,0,',','.') . '₫';
        }

        session()->put('coupon', [
            'code' => $coupon->code,
            'discount' => $discountAmount,
            'label' => $discountLabel,
            'min_order_value' => $coupon->min_order_value
        ]);

        return redirect()->back()->with('success', 'Áp dụng mã giảm giá thành công!');
    }

    public function success()
    {
        if (!session('order_code')) return redirect()->route('home');
        return view('shop.checkout-success');
    }
}

