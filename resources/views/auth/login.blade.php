@extends('layouts.app')

@section('title', 'Đăng Nhập – CrocsVN')

@push('css')
<style>
.auth-wrapper { min-height: 80vh; display: flex; align-items: center; justify-content: center; background: #f4f7f6; padding: 40px 20px; }
.auth-card { background: white; width: 100%; max-width: 450px; border-radius: 24px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); padding: 40px; }
.auth-card h2 { font-size: 2rem; font-weight: 800; color: #1a1a2e; text-align: center; margin-bottom: 5px; }
.auth-card p { text-align: center; color: #666; margin-bottom: 30px; font-size: 0.95rem; }

.form-group { margin-bottom: 20px; }
.form-group label { display: block; font-weight: 600; margin-bottom: 8px; color: #444; font-size: 0.95rem; }
.form-control { width: 100%; padding: 15px; border-radius: 12px; border: 1px solid #ddd; font-family: inherit; font-size: 1rem; transition: 0.3s; box-sizing: border-box; }
.form-control:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 0 4px rgba(0,168,120,0.1); }

.options { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; font-size: 0.9rem; }
.options a { color: var(--primary); text-decoration: none; font-weight: 600; }

.btn-auth { width: 100%; padding: 16px; background: var(--dark); color: white; font-weight: 700; font-size: 1.1rem; border: none; border-radius: 50px; cursor: pointer; transition: 0.3s; }
.btn-auth:hover { background: var(--primary); transform: translateY(-2px); }

.divider { text-align: center; margin: 25px 0; position: relative; color: #999; font-size: 0.9rem; }
.divider::before, .divider::after { content: ""; position: absolute; top: 50%; width: 40%; height: 1px; background: #eee; }
.divider::before { left: 0; }
.divider::after { right: 0; }

.social-btn { display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 14px; background: white; border: 1px solid #ddd; border-radius: 50px; color: #444; font-weight: 600; cursor: pointer; transition: 0.3s; }
.social-btn:hover { background: #f8f9fa; border-color: #ccc; }

.auth-switch { text-align: center; margin-top: 20px; font-size: 0.95rem; color: #666; }
.auth-switch a { color: var(--primary); font-weight: 700; text-decoration: none; }
</style>
@endpush

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <h2>Chào Mừng Trở Lại</h2>
        <p>Đăng nhập để nhận ưu đãi dành riêng cho thành viên</p>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            
            @if($errors->any())
            <div style="background:#ffe6e6; color:#d9534f; padding:15px; border-radius:12px; margin-bottom:20px; font-weight:600; font-size:0.9rem">
                {{ $errors->first() }}
            </div>
            @endif

            <div class="form-group">
                <label>Địa chỉ Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="hello@crocsvn.vn" required>
            </div>
            
            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="options">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer">
                    <input type="checkbox" name="remember" style="accent-color:var(--primary)"> Ghi nhớ đăng nhập
                </label>
                <a href="#">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="btn-auth">Đăng Nhập 👉</button>
        </form>

        <div class="divider">Hoặc đăng nhập với</div>
        
        <button class="social-btn">
            <span style="color:#DB4437; font-weight:bold; font-size:1.2rem">G</span> Tiếp tục với Google
        </button>

        <div class="auth-switch">
            Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a>
        </div>
    </div>
</div>
@endsection
