<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Order;
use Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $user = Auth::user();
        
        // Verified Purchase Check
        // Kiểm tra xem user này đã từng mua product_id chưa, và đơn hàng đó phải 'completed'
        $hasPurchased = Order::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereHas('details', function($q) use ($request) {
                $q->where('product_id', $request->product_id);
            })->exists();

        if (!$hasPurchased) {
            return redirect()->back()->with('error', 'Bạn phải mua và nhận hàng thành công thì mới có thể đánh giá sản phẩm này.');
        }

        // Cập nhật hoặc tạo mới 1 đánh giá cho 1 sản phẩm
        Review::updateOrCreate(
            ['user_id' => $user->id, 'product_id' => $request->product_id],
            ['rating' => $request->rating, 'comment' => $request->comment]
        );

        return redirect()->back()->with('success', 'Đánh giá của bạn đã được ghi nhận. Cảm ơn bạn!');
    }
}
