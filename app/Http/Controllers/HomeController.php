<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Coupon;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Lấy Banner đang active
        $banners = Banner::where('status', 1)->get();
        // Lấy 1 hero banner
        $heroBanner = Banner::where('position', 'hero')->where('status', 1)->first();

        // 2. Lấy Danh mục
        $categories = Category::all();

        // 3. Lấy Sản phẩm (có thể lấy top sản phẩm mới nhất hoặc hot)
        $products = Product::where('status', 1)->orderBy('id', 'desc')->take(6)->get();

        // 4. Lấy Coupon
        $coupons = Coupon::where('expired_at', '>=', now())->get();

        return view('welcome', compact('banners', 'heroBanner', 'categories', 'products', 'coupons'));
    }
}
