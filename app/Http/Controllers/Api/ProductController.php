<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Lab 7.1 - GET /api/products
     * Lấy danh sách toàn bộ sản phẩm
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand'])
            ->where('status', 1);

        // Thách thức: Lọc theo category_id nếu có query param
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo brand_id nếu có
        if ($request->has('brand_id') && $request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }

        // Tìm kiếm theo tên
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->get()->map(function ($product) {
            return $this->formatProduct($product);
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Lấy danh sách sản phẩm thành công',
            'total'   => $products->count(),
            'data'    => $products,
        ], 200);
    }

    /**
     * Lab 7.1 - GET /api/products/{id}
     * Lấy chi tiết một sản phẩm theo ID
     */
    public function show($id)
    {
        $product = Product::with(['category', 'brand', 'reviews.user'])->find($id);

        if (!$product) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Không tìm thấy sản phẩm',
            ], 404);
        }

        $data = $this->formatProduct($product);

        // Thêm reviews vào chi tiết sản phẩm
        $data['reviews'] = $product->reviews->map(function ($review) {
            return [
                'id'         => $review->id,
                'rating'     => $review->rating,
                'comment'    => $review->comment,
                'user_name'  => $review->user->name ?? 'Khách hàng',
                'created_at' => $review->created_at->format('d/m/Y'),
            ];
        });
        $data['avg_rating'] = $product->reviews->count() > 0
            ? round($product->reviews->avg('rating'), 1)
            : null;

        return response()->json([
            'status' => 'success',
            'data'   => $data,
        ], 200);
    }

    /**
     * Dùng Eloquent Resource pattern (Thách thức Lab 7.2)
     * Định dạng dữ liệu sản phẩm, loại bỏ các cột thừa
     */
    private function formatProduct($product)
    {
        return [
            'id'             => $product->id,
            'name'           => $product->name,
            'slug'           => $product->slug,
            'price'          => $product->price,
            'discount_price' => $product->discount_price,
            'flash_sale_end' => $product->flash_sale_end,
            'stock'          => $product->stock,
            'image'          => url($product->image),
            'description'    => $product->description,
            'category'       => $product->category ? [
                'id'   => $product->category->id,
                'name' => $product->category->name,
            ] : null,
            'brand' => $product->brand ? [
                'id'   => $product->brand->id,
                'name' => $product->brand->name,
            ] : null,
        ];
    }
}
