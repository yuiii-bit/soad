<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthApiController;

/*
|--------------------------------------------------------------------------
| API Routes - Lab 7.1 & Lab 7.2
|--------------------------------------------------------------------------
*/

// ============================================================
// Lab 7.1: API Sản phẩm (KHÔNG cần xác thực)
// ============================================================

// Lấy danh sách sản phẩm (hỗ trợ filter: ?category_id=1&search=croc)
Route::get('/products', [ProductController::class, 'index']);

// Lấy chi tiết một sản phẩm theo ID
Route::get('/products/{id}', [ProductController::class, 'show']);

// ============================================================
// Lab 7.2: Authentication với Sanctum
// ============================================================

// Đăng nhập → trả về Bearer Token
Route::post('/login', [AuthApiController::class, 'login']);

// Các route yêu cầu xác thực (cần Bearer Token trong Header)
Route::middleware('auth:sanctum')->group(function () {

    // Xem thông tin profile user đang đăng nhập
    Route::get('/user-profile', [AuthApiController::class, 'profile']);

    // Đăng xuất → thu hồi token hiện tại
    Route::post('/logout', [AuthApiController::class, 'logout']);

    // Đặt hàng qua API (bảo mật - chỉ ai có Token mới đặt được)
    // Route::post('/orders', [OrderApiController::class, 'store']);
});
