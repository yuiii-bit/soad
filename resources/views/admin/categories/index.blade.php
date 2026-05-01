@extends('admin.layouts.admin')
@section('title', 'Quản Lý Danh Mục')
@section('page-title', '🏷️ Quản Lý Danh Mục')

@section('content')
<div style="display:grid; grid-template-columns: 1fr 2fr; gap: 30px; align-items: start">
    
    {{-- Form Thêm --}}
    <div class="admin-card">
        <div class="admin-card-title" style="margin-bottom:20px">Thêm Danh Mục Mới</div>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Tên danh mục *</label>
                <input type="text" name="name" class="form-control" required placeholder="Ví dụ: Giày Nam">
                @error('name') <div style="color:red;font-size:0.8rem;margin-top:5px">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Mô tả thêm</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Không bắt buộc"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center">➕ Thêm Danh Mục</button>
        </form>
    </div>

    {{-- Danh sách --}}
    <div class="admin-card">
        <div class="admin-card-title" style="margin-bottom:20px">Danh Sách Danh Mục</div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Danh Mục</th>
                    <th>Số Sản Phẩm</th>
                    <th style="text-align:right">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $cat)
                <tr>
                    <td style="color:#94a3b8">#{{ $cat->id }}</td>
                    <td style="font-weight:700; color:#1e293b">{{ $cat->name }}</td>
                    <td><span class="badge badge-info">{{ $cat->products_count }} sản phẩm</span></td>
                    <td style="text-align:right">
                        {{-- Nút xóa --}}
                        <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này? Các sản phẩm bên trong sẽ bị mất!')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline" style="color:var(--danger)">🗑 Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
