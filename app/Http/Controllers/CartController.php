<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    // Xem trang giỏ hàng
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('shop.cart', compact('cart'));
    }

    // Thêm sản phẩm vào giỏ
    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        if(isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity ?? 1;
        } else {
            $price = $product->discount_price ?? $product->price;
            $cart[$product->id] = [
                "name"     => $product->name,
                "quantity" => $request->quantity ?? 1,
                "price"    => $price,
                "image"    => $product->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Đã thêm "' . $product->name . '" vào giỏ hàng!');
    }

    // Cập nhật số lượng sản phẩm
    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        $id  = $request->product_id;
        $qty = (int) $request->quantity;

        if (isset($cart[$id])) {
            if ($qty <= 0) {
                unset($cart[$id]);
            } else {
                $cart[$id]['quantity'] = $qty;
            }
        }

        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Đã cập nhật giỏ hàng!');
    }

    // Xóa một sản phẩm
    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        $id   = $request->product_id;

        if (isset($cart[$id])) {
            $name = $cart[$id]['name'];
            unset($cart[$id]);
            session()->put('cart', $cart);
            return redirect()->route('cart.index')->with('success', 'Đã xóa "' . $name . '" khỏi giỏ hàng!');
        }

        return redirect()->route('cart.index');
    }

    // Xóa toàn bộ giỏ hàng
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }

    // API: trả số lượng items trong giỏ
    public function count()
    {
        $cart  = session()->get('cart', []);
        $count = array_sum(array_column($cart, 'quantity'));
        return response()->json(['count' => $count]);
    }
}
