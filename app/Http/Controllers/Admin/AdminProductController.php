<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand'])->orderBy('created_at', 'desc');

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(12);
        $categories = Category::all();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:200',
            'category_id'   => 'required|exists:categories,id',
            'brand_id'      => 'required|exists:brands,id',
            'price'         => 'required|integer|min:0',
            'discount_price'=> 'nullable|integer|min:0',
            'flash_sale_end'=> 'nullable|date',
            'stock'         => 'required|integer|min:0',
            'image'         => 'required|image|max:3072',
            'description'   => 'nullable|string',
        ]);

        // Upload ảnh
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'products/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/products'), basename($filename));
            $imagePath = 'images/products/' . basename($filename);
        }

        $slug = Product::generateSlug($request->name);

        Product::create([
            'name'          => $request->name,
            'slug'          => $slug,
            'category_id'   => $request->category_id,
            'brand_id'      => $request->brand_id,
            'price'         => $request->price,
            'discount_price'=> $request->discount_price,
            'flash_sale_end'=> $request->flash_sale_end,
            'stock'         => $request->stock,
            'image'         => $imagePath,
            'description'   => $request->description,
            'status'        => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Đã thêm sản phẩm "' . $request->name . '" thành công!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name'          => 'required|string|max:200',
            'category_id'   => 'required|exists:categories,id',
            'brand_id'      => 'required|exists:brands,id',
            'price'         => 'required|integer|min:0',
            'discount_price'=> 'nullable|integer|min:0',
            'flash_sale_end'=> 'nullable|date',
            'stock'         => 'required|integer|min:0',
            'image'         => 'nullable|image|max:3072',
            'description'   => 'nullable|string',
        ]);

        $data = [
            'name'          => $request->name,
            'category_id'   => $request->category_id,
            'brand_id'      => $request->brand_id,
            'price'         => $request->price,
            'discount_price'=> $request->discount_price,
            'flash_sale_end'=> $request->flash_sale_end,
            'stock'         => $request->stock,
            'description'   => $request->description,
            'status'        => $request->has('status') ? 1 : 0,
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'images/products/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/products'), basename($filename));
            $data['image'] = $filename;
        }

        $product->update($data);
        return redirect()->route('admin.products.index')->with('success', 'Đã cập nhật sản phẩm thành công!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm "' . $product->name . '".');
    }
}
