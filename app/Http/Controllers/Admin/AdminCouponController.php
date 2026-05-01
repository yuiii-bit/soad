<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class AdminCouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::orderBy('id', 'desc')->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'            => 'required|string|max:50|unique:coupons,code',
            'discount_type'   => 'required|in:fixed,percent',
            'discount_value'  => 'required|integer|min:1',
            'min_order_value' => 'required|integer|min:0',
            'quantity'        => 'required|integer|min:1',
            'expired_at'      => 'nullable|date|after:today',
        ]);

        Coupon::create([
            'code'            => strtoupper($request->code),
            'discount_type'   => $request->discount_type,
            'discount_value'  => $request->discount_value,
            'min_order_value' => $request->min_order_value,
            'quantity'        => $request->quantity,
            'expired_at'      => $request->expired_at,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Đã tạo voucher "' . strtoupper($request->code) . '" thành công!');
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Đã xóa voucher "' . $coupon->code . '".');
    }
}
