<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;

use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminCouponController;
use App\Http\Controllers\Admin\AdminUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Trang Chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Mua sắm (Shop)
Route::get('/shop', [ProductController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.detail');

// Các trang thông tin (Pages)
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');

// Giỏ hàng (Cart) - Bất kỳ ai cũng thêm được
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// Mã giảm giá
Route::post('/coupon/apply', [CheckoutController::class, 'applyCoupon'])->name('coupon.apply');

/*
|--------------------------------------------------------------------------
| Hệ thống Xác Thực Người Dùng (Auth Routes)
|--------------------------------------------------------------------------
*/
// Dành cho Guest (Khách chưa đăng nhập)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'loginProcess'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'registerProcess'])->name('register.post');
});

// Dành cho User đã đăng nhập
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Lịch sử Đơn Hàng & Tài Khoản & Yêu Thích
    Route::get('/profile', [\App\Http\Controllers\UserProfileController::class, 'orders'])->name('profile');
    Route::get('/profile/orders', [\App\Http\Controllers\UserProfileController::class, 'orders'])->name('profile.orders');
    Route::get('/profile/settings', [\App\Http\Controllers\UserProfileController::class, 'settings'])->name('profile.settings');
    Route::post('/profile/update', [\App\Http\Controllers\UserProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [\App\Http\Controllers\UserProfileController::class, 'updatePassword'])->name('profile.password');
    Route::get('/profile/wishlist', [\App\Http\Controllers\UserProfileController::class, 'wishlist'])->name('profile.wishlist');
    
    // Đánh giá sản phẩm
    Route::post('/review/store', [\App\Http\Controllers\ReviewController::class, 'store'])->name('review.store');
    
    // Toggle Yêu thích qua AJAX (có thể dành cho user đã đăng nhập)
    Route::post('/wishlist/toggle', [\App\Http\Controllers\UserProfileController::class, 'toggleWishlist'])->name('wishlist.toggle');

    // Chức năng cần tài khoản mới được mua hàng:
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/fast', [CheckoutController::class, 'fastCheckout'])->name('checkout.fast');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
});

Route::get('/db-upgrade', function () {
    try {
        if (!\Illuminate\Support\Facades\Schema::hasColumn('products', 'flash_sale_end')) {
            \Illuminate\Support\Facades\Schema::table('products', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->dateTime('flash_sale_end')->nullable()->after('discount_price');
            });
            return "SUCCESS";
        }
        return "EXISTS";
    } catch (\Exception $e) {
        return "ERROR: " . $e->getMessage();
    }
});

/*
|--------------------------------------------------------------------------
| Khu vực dành riêng cho ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Quản lý đơn hàng
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

    // Quản lý sản phẩm, danh mục, voucher
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::resource('categories', AdminCategoryController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('coupons', AdminCouponController::class)->only(['index', 'store', 'destroy']);

    // Quản lý khách hàng
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('users.show');
    Route::post('/users/{id}/toggle', [AdminUserController::class, 'toggleStatus'])->name('users.toggle');
});
