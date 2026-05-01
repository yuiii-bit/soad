@extends('admin.layouts.admin')
@section('title', 'Cập Nhật Sản Phẩm')
@section('page-title')
    <a href="{{ route('admin.products.index') }}" style="text-decoration:none; color:#64748b; margin-right:10px">← Quay lại</a>
    Cập Nhật Sản Phẩm: {{ $product->name }}
@endsection

@section('content')
<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div style="display:grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start">
        
        {{-- Thông tin cơ bản --}}
        <div class="admin-card" style="margin-bottom:0">
            <div class="admin-card-title" style="margin-bottom: 20px">Thông Tin Cơ Bản</div>
            
            <div class="form-group">
                <label class="form-label">Tên sản phẩm *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                @error('name') <div style="color:red;font-size:0.8rem;margin-top:5px">{{ $message }}</div> @enderror
            </div>

            <div class="form-row form-row-2">
                <div class="form-group">
                    <label class="form-label">Giá gốc (₫) *</label>
                    <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" required min="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Giá khuyến mãi (₫)</label>
                    <input type="number" name="discount_price" class="form-control" value="{{ old('discount_price', $product->discount_price) }}" min="0">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group" style="width: 50%;">
                    <label class="form-label">Khoá Flash Sale (Hết hạn lúc)</label>
                    <input type="datetime-local" name="flash_sale_end" class="form-control" value="{{ old('flash_sale_end', $product->flash_sale_end ? $product->flash_sale_end->format('Y-m-d\TH:i') : '') }}">
                    <div style="font-size:0.8rem; color:#64748b; margin-top:5px">Chỉ áp dụng nếu có Giá khuyến mãi. Quá hạn trang web tự gỡ thông báo Sale.</div>
                </div>
            </div>

            <div class="form-row form-row-2">
                <div class="form-group">
                    <label class="form-label">Danh mục *</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Chọn danh mục</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Thương hiệu *</label>
                    <select name="brand_id" class="form-control" required>
                        <option value="">Chọn thương hiệu</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Mô tả sản phẩm</label>
                <textarea name="description" class="form-control" rows="5">{{ old('description', $product->description) }}</textarea>
            </div>
        </div>

        {{-- Cột Phải --}}
        <div style="display:flex; flex-direction:column; gap:24px">
            
            {{-- Ảnh đại diện --}}
            <div class="admin-card" style="margin:0">
                <div class="admin-card-title" style="margin-bottom: 20px">Hình Ảnh (Chỉ tải lên nếu muốn đổi)</div>
                <div class="form-group">
                    <input type="file" name="image" class="form-control" accept="image/*" id="imgInp" style="padding: 8px">
                    <div style="margin-top:15px; border:2px dashed #e2e8f0; border-radius:12px; padding:10px; text-align:center; background:#f8fafc">
                        <img id="imgPreview" src="{{ asset($product->image) }}" style="max-width:100%; max-height:200px; display:block; margin:0 auto; object-fit:contain">
                        <div id="imgPlaceholder" style="color:#94a3b8; font-size:0.9rem; padding:40px 0; display:none">Chưa chọn ảnh mới</div>
                    </div>
                </div>
            </div>

            {{-- Kho hàng --}}
            <div class="admin-card" style="margin:0">
                <div class="admin-card-title" style="margin-bottom: 20px">Kho & Trạng Thái</div>
                <div class="form-group">
                    <label class="form-label">Số lượng trong kho *</label>
                    <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required min="0">
                </div>
                <div class="form-group" style="margin-top:20px">
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer">
                        <input type="checkbox" name="status" value="1" {{ old('status', $product->status) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary)">
                        <span style="font-weight:600">Hiển thị sản phẩm trên web</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="justify-content:center; padding:14px; font-size:1.05rem">💾 Cập Nhật Sản Phẩm</button>
        </div>
    </div>
</form>
@endsection

@push('js')
<script>
// Preview Image
imgInp.onchange = evt => {
    const [file] = imgInp.files;
    if (file) {
        imgPreview.src = URL.createObjectURL(file);
        imgPreview.style.display = 'block';
        document.getElementById('imgPlaceholder').style.display = 'none';
    }
}
</script>
@endpush
