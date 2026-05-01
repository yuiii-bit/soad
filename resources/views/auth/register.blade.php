@extends('layouts.app')

@section('title', 'Đăng Ký Tài Khoản – CrocsVN')

@push('css')
<style>
/* Sử dụng form chung form chung của auth */
.auth-wrapper { min-height: 80vh; display: flex; align-items: center; justify-content: center; background: #f4f7f6; padding: 40px 20px; }
.auth-card { background: white; width: 100%; max-width: 500px; border-radius: 24px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); padding: 40px; }
.auth-card h2 { font-size: 2rem; font-weight: 800; color: #1a1a2e; text-align: center; margin-bottom: 5px; }
.auth-card p { text-align: center; color: #666; margin-bottom: 30px; font-size: 0.95rem; }

.form-group { margin-bottom: 20px; }
.form-group label { display: block; font-weight: 600; margin-bottom: 8px; color: #444; font-size: 0.95rem; }
.form-control { width: 100%; padding: 15px; border-radius: 12px; border: 1px solid #ddd; font-family: inherit; font-size: 1rem; transition: 0.3s; box-sizing: border-box; }
.form-control:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 0 4px rgba(0,168,120,0.1); }

.btn-auth { width: 100%; padding: 16px; background: var(--dark); color: white; font-weight: 700; font-size: 1.1rem; border: none; border-radius: 50px; cursor: pointer; transition: 0.3s; margin-top:10px; }
.btn-auth:hover { background: var(--primary); transform: translateY(-2px); }

.auth-switch { text-align: center; margin-top: 25px; font-size: 0.95rem; color: #666; }
.auth-switch a { color: var(--primary); font-weight: 700; text-decoration: none; }
</style>
@endpush

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <h2>Tạo Tài Khoản Crocs</h2>
        <p>Tham gia ngay để luôn cập nhật mẫu mã mới mỗi ngày</p>

        <form action="{{ route('register.post') }}" method="POST">
            @csrf

            @if($errors->any())
            <div style="background:#ffe6e6; color:#d9534f; padding:15px; border-radius:12px; margin-bottom:20px; font-weight:600; font-size:0.9rem">
                {{ $errors->first() }}
            </div>
            @endif

            <div class="form-group">
                <label>Họ và Tên</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Nguyễn Văn A" required>
            </div>

            <div class="form-group">
                <label>Địa chỉ Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="nguyenvana@gmail.com" required>
            </div>
            
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px">
                <div class="form-group">
                    <label>Mật khẩu</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="form-group">
                    <label>Xác nhận MK</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <label style="display:flex; align-items:start; gap:10px; font-size:0.85rem; color:#666; margin-bottom:15px; cursor:pointer">
                <input type="checkbox" required style="accent-color:var(--primary); margin-top:3px">
                Tôi đồng ý với các Điều khoản Dịch vụ và Chính sách Bảo mật của hệ thống CrocsVN.
            </label>

            <button type="submit" class="btn-auth" id="registerBtn" onclick="this.innerHTML='Đang xử lý...'; this.style.opacity='0.7'; this.style.pointerEvents='none'; this.form.submit();">Đăng Ký Nhanh 🚀</button>
        </form>

        <div class="auth-switch">
            Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a>
        </div>
    </div>
</div>
@endsection
