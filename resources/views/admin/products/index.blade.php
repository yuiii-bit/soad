@extends('admin.layouts.admin')
@section('title', 'Quản Lý Sản Phẩm')
@section('page-title', '👟 Quản Lý Sản Phẩm')

@section('content')
<div class="admin-card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px">
        <form method="GET" style="display:flex; gap:10px">
            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Tìm tên sản phẩm..." style="width:250px">
            <select name="category_id" class="form-control" style="width:180px">
                <option value="">-- Tất cả danh mục --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-dark">Lọc</button>
            @if(request('search') || request('category_id'))
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Xóa</a>
            @endif
        </form>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">➕ Thêm Sản Phẩm</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Sản Phẩm</th>
                <th>Danh Mục</th>
                <th>Thương Hiệu</th>
                <th>Giá Bán</th>
                <th>Kho</th>
                <th>Trạng Thái</th>
                <th style="text-align:right">Hành Động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $p)
            <tr>
                <td>
                    <div style="display:flex; align-items:center; gap:12px">
                        <div style="width:50px; height:50px; border-radius:8px; background:#f1f5f9; padding:4px">
                            <img src="{{ asset($p->image ?? 'images/hero.png') }}" style="width:100%; height:100%; object-fit:contain">
                        </div>
                        <div>
                            <div style="font-weight:700; color:#1e293b">{{ $p->name }}</div>
                            <div style="font-size:0.8rem; color:#64748b">ID: #{{ $p->id }}</div>
                        </div>
                    </div>
                </td>
                <td style="font-weight:600">{{ $p->category->name ?? '—' }}</td>
                <td>{{ $p->brand->name ?? '—' }}</td>
                <td>
                    @if($p->discount_price)
                        <div style="font-weight:800; color:var(--primary)">{{ number_format($p->discount_price,0,',','.') }}₫</div>
                        <div style="font-size:0.8rem; text-decoration:line-through; color:#94a3b8">{{ number_format($p->price,0,',','.') }}₫</div>
                    @else
                        <div style="font-weight:800; color:var(--primary)">{{ number_format($p->price,0,',','.') }}₫</div>
                    @endif
                </td>
                <td>
                    <span class="badge {{ $p->stock > 0 ? 'badge-success' : 'badge-danger' }}">
                        {{ $p->stock > 0 ? $p->stock . ' cái' : 'Hết hàng' }}
                    </span>
                </td>
                <td>
                    <span class="badge {{ $p->status ? 'badge-info' : 'badge-gray' }}">
                        {{ $p->status ? 'Đang bán' : 'Đã ẩn' }}
                    </span>
                </td>
                <td style="text-align:right">
                    <a href="{{ route('admin.products.edit', $p->id) }}" class="btn btn-sm btn-outline">✏️ Sửa</a>
                    <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm" style="background:#fef2f2; border:1px solid #fca5a5; color:#dc2626; font-weight:700; font-family:inherit; border-radius:8px; padding:6px 13px; cursor:pointer">🗑</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center; padding:40px; color:#94a3b8">Không tìm thấy sản phẩm nào!</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-wrap">{{ $products->withQueryString()->links() }}</div>
</div>
@endsection
