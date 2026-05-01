@extends('admin.layouts.admin')
@section('title', 'Thêm Sản Phẩm Mới')
@section('page-title')
    <a href="{{ route('admin.products.index') }}" style="text-decoration:none; color:#64748b; margin-right:10px">← Quay lại</a>
    Thêm Sản Phẩm Mới
@endsection

@section('content')
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div style="display:grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start">
        
        {{-- Thông tin cơ bản --}}
        <div class="admin-card" style="margin-bottom:0">
            <div class="admin-card-title" style="margin-bottom: 20px">Thông Tin Cơ Bản</div>
            
            <div class="form-group">
                <label class="form-label">Tên sản phẩm *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name') <div style="color:red;font-size:0.8rem;margin-top:5px">{{ $message }}</div> @enderror
            </div>

            <div class="form-row form-row-2">
                <div class="form-group">
                    <label class="form-label">Giá gốc (₫) *</label>
                    <input type="number" name="price" class="form-control" value="{{ old('price') }}" required min="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Giá khuyến mãi (₫)</label>
                    <input type="number" name="discount_price" class="form-control" value="{{ old('discount_price') }}" min="0">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group" style="width: 50%;">
                    <label class="form-label">Khoá Flash Sale (Hết hạn lúc)</label>
                    <input type="datetime-local" name="flash_sale_end" class="form-control" value="{{ old('flash_sale_end') }}">
                    <div style="font-size:0.8rem; color:#64748b; margin-top:5px">Chỉ áp dụng nếu có Giá khuyến mãi. Quá hạn trang web tự gỡ thông báo Sale.</div>
                </div>
            </div>

            <div class="form-row form-row-2">
                <div class="form-group">
                    <label class="form-label">Danh mục *</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Chọn danh mục</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Thương hiệu *</label>
                    <select name="brand_id" class="form-control" required>
                        <option value="">Chọn thương hiệu</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Mô tả sản phẩm</label>
                <textarea name="description" class="form-control" rows="5">{{ old('description') }}</textarea>
            </div>
        </div>

        {{-- Cột Phải --}}
        <div style="display:flex; flex-direction:column; gap:24px">
            
            {{-- Ảnh đại diện --}}
            <div class="admin-card" style="margin:0">
                <div class="admin-card-title" style="margin-bottom: 20px">Hình Ảnh (Bắt buộc)</div>
                <div class="form-group">
                    <input type="file" name="image" class="form-control" accept="image/*" required id="imgInp" style="padding: 8px">
                    <div style="margin-top:15px; border:2px dashed #e2e8f0; border-radius:12px; padding:10px; text-align:center; background:#f8fafc">
                        <img id="imgPreview" src="" style="max-width:100%; max-height:200px; display:none; margin:0 auto; object-fit:contain">
                        <div id="imgPlaceholder" style="color:#94a3b8; font-size:0.9rem; padding:40px 0">Chưa chọn ảnh</div>
                    </div>
                </div>
            </div>

            {{-- Kho hàng --}}
            <div class="admin-card" style="margin:0">
                <div class="admin-card-title" style="margin-bottom: 20px">Kho & Trạng Thái</div>
                <div class="form-group">
                    <label class="form-label">Số lượng trong kho *</label>
                    <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}" required min="0">
                </div>
                <div class="form-group" style="margin-top:20px">
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer">
                        <input type="checkbox" name="status" value="1" checked style="width:18px;height:18px;accent-color:var(--primary)">
                        <span style="font-weight:600">Hiển thị sản phẩm trên web</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="justify-content:center; padding:14px; font-size:1.05rem">💾 Lưu Sản Phẩm</button>
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
