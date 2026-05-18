<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderApiController extends Controller
{
    /**
     * Lab 7.2 - POST /api/orders
     * Đặt hàng qua API (yêu cầu token)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $user = auth()->user();
            $totalAmount = 0;

            // Tính tổng tiền và chuẩn bị chi tiết
            $orderDetails = [];
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $price = $product->discount_price ?: $product->price;
                $quantity = $item['quantity'];
                
                $totalAmount += $price * $quantity;
                
                $orderDetails[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ];
            }

            // Tạo order
            $order = Order::create([
                'user_id' => $user ? $user->id : null,
                'customer_name' => $request->name,
                'customer_phone' => $request->phone,
                'shipping_address' => $request->address,
                'order_code' => 'ORD-' . strtoupper(uniqid()),
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method ?? 'COD',
                'status' => 'pending'
            ]);

            // Thêm chi tiết
            foreach ($orderDetails as $detail) {
                $detail['order_id'] = $order->id;
                OrderDetail::create($detail);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Đặt hàng thành công',
                'data' => [
                    'order_id' => $order->id,
                    'total_amount' => $totalAmount,
                    'status' => $order->status
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
