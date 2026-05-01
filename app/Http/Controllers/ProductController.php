<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    // Trang Shop (Tất cả sản phẩm)
    public function index(Request $request)
    {
        $query = Product::where('status', 1);

        // Lọc theo danh mục
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Tìm kiếm
        if ($request->has('keyword') && $request->keyword != '') {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(12);
        $categories = Category::all();

        return view('shop.index', compact('products', 'categories'));
    }

    // Trang Chi Tiết Sản Phẩm
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        
        $relatedProducts = Product::where('category_id', $product->category_id)
                                ->where('id', '!=', $product->id)
                                ->take(4)
                                ->get();

        // Lấy tất cả reviews
        $reviews = $product->reviews()->with('user')->latest()->get();
        $avgRating = $reviews->count() > 0 ? round($reviews->avg('rating'), 1) : 0;

        // Check xem user có quyền review không
        $canReview = false;
        if (\Auth::check()) {
            $canReview = \App\Models\Order::where('user_id', \Auth::id())
                ->where('status', 'completed')
                ->whereHas('details', function($q) use ($product) {
                    $q->where('product_id', $product->id);
                })->exists();
        }

        return view('shop.detail', compact('product', 'relatedProducts', 'reviews', 'avgRating', 'canReview'));
    }
}
