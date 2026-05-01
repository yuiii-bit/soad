@extends('user.layout')
@section('title', 'Cài Đặt Tài Khoản')
@section('profile-title', '⚙️ Cài Đặt Tài Khoản')

@push('profile-css')
<style>
.settings-section { margin-bottom: 40px; }
.settings-title { font-size: 1.15rem; font-weight: 800; color: var(--dark); margin-bottom: 20px; border-left: 4px solid var(--primary); padding-left: 12px; }

.form-group { margin-bottom: 20px; }
.form-label { display: block; font-weight: 700; color: #444; margin-bottom: 8px; font-size: 0.95rem; }
.form-input { width: 100%; padding: 14px 18px; border: 1.5px solid #e5e5e5; border-radius: 12px; font-family: inherit; font-size: 0.95rem; color: #1a1a2e; outline: none; transition: 0.2s; box-sizing: border-box; }
.form-input:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(0,168,120,0.1); }
.form-input:disabled { background: #f4f4f4; color: #888; cursor: not-allowed; }

.btn-save { padding: 14px 25px; background: var(--primary); color: white; border: none; border-radius: 10px; font-weight: 700; font-family: inherit; font-size: 1rem; cursor: pointer; transition: 0.2s; box-shadow: 0 4px 15px rgba(0,168,120,0.3); }
.btn-save:hover { background: #008f65; transform: translateY(-1px); }
</style>
@endpush

@section('profile-content')

<div style="display:grid; grid-template-columns: 1fr 1fr; gap: 40px">
    <!-- Cột đổi thông tin CÁ NHÂN -->
    <div class="settings-section">
        <h3 class="settings-title">Thông Tin Cá Nhân</h3>
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Email (Không thể thay đổi)</label>
                <input type="email" class="form-input" value="{{ $user->email }}" disabled>
            </div>

            <div class="form-group">
                <label class="form-label">Họ và Tên</label>
                <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Số Điện Thoại</label>
                <input type="tel" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}" placeholder="VD: 0909123456">
            </div>

            <div class="form-group">
                <label class="form-label">Địa Chỉ Giao Hàng Mặc Định</label>
                <textarea name="address" class="form-input" rows="3" placeholder="Nhập địa chỉ của bạn">{{ old('address', $user->address) }}</textarea>
            </div>

            <button type="submit" class="btn-save">Lưu Thông Tin</button>
        </form>
    </div>

    <!-- Cột ĐỔI MẬT KHẨU -->
    <div class="settings-section">
        <h3 class="settings-title">Đổi Mật Khẩu</h3>
        <form action="{{ route('profile.password') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Mật Khẩu Cũ</label>
                <input type="password" name="current_password" class="form-input" required>
            </div>

            <div class="form-group">
                <label class="form-label">Mật Khẩu Mới</label>
                <input type="password" name="password" class="form-input" required placeholder="Ít nhất 8 ký tự">
            </div>

            <div class="form-group">
                <label class="form-label">Xác Nhận Mật Khẩu Mới</label>
                <input type="password" name="password_confirmation" class="form-input" required>
            </div>

            <button type="submit" class="btn-save" style="background:var(--dark); box-shadow:0 4px 15px rgba(0,0,0,0.3)">Cập Nhật Mật Khẩu</button>
        </form>
    </div>
</div>

@endsection
