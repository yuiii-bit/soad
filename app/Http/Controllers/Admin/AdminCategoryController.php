<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('name')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ]);
        Category::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
        ]);
        return redirect()->route('admin.categories.index')->with('success', 'Đã thêm danh mục "' . $request->name . '"!');
    }

    public function update(Request $request, $id)
    {
        $cat = Category::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $id,
        ]);
        $cat->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
        ]);
        return redirect()->route('admin.categories.index')->with('success', 'Đã cập nhật danh mục!');
    }

    public function destroy($id)
    {
        $cat = Category::findOrFail($id);
        if ($cat->products()->count() > 0) {
            return redirect()->route('admin.categories.index')->with('error', 'Không thể xóa danh mục đang có sản phẩm!');
        }
        $cat->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Đã xóa danh mục "' . $cat->name . '".');
    }
}
