@extends('layouts.app')

@push('css')
<style>
.profile-wrap { max-width: 1200px; margin: 40px auto; padding: 0 20px; display: grid; grid-template-columns: 280px 1fr; gap: 40px; align-items: start; }

.profile-sidebar { background: white; border-radius: 20px; box-shadow: 0 5px 30px rgba(0,0,0,0.03); overflow: hidden; position: sticky; top: 100px; }
.profile-user { text-align: center; padding: 30px 20px; background: var(--dark); color: white; }
.profile-avatar { width: 80px; height: 80px; background: var(--primary); color: white; border-radius: 50%; font-size: 2.5rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-weight: 800; border: 3px solid white; box-shadow: 0 5px 15px rgba(0,0,0,0.2) }
.profile-menu { list-style: none; padding: 15px 0; margin: 0; }
.profile-menu li a { display: block; padding: 15px 25px; color: #555; text-decoration: none; font-weight: 600; border-left: 4px solid transparent; transition: 0.3s; font-size: 0.95rem; }
.profile-menu li a:hover, .profile-menu li a.active { color: var(--primary); background: #f8f9fa; border-left-color: var(--primary); }

.profile-content { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 5px 30px rgba(0,0,0,0.03); }
.profile-title { font-size: 1.6rem; font-weight: 800; margin-bottom: 25px; color: var(--dark); border-bottom: 2px solid #eee; padding-bottom: 15px; }

@media (max-width: 900px) {
    .profile-wrap { grid-template-columns: 1fr; }
    .profile-sidebar { position: static; }
}

/* Flash message inside content */
.alert-success { background: #d1e7dd; color: #0f5132; padding: 15px 20px; border-radius: 10px; font-weight: 600; margin-bottom: 25px; border: 1px solid #badbcc; }
.alert-danger { background: #f8d7da; color: #842029; padding: 15px 20px; border-radius: 10px; font-weight: 600; margin-bottom: 25px; border: 1px solid #f5c2c7; }
</style>
@stack('profile-css')
@endpush

@section('content')
<div class="profile-wrap">
    <!-- Sidebar -->
    <aside class="profile-sidebar">
        @php
            $currentUser = Auth::user();
        @endphp
        <div class="profile-user">
            <div class="profile-avatar">{{ strtoupper(substr($currentUser->name, 0, 1)) }}</div>
            <h3 style="font-size:1.3rem; margin-bottom:5px; margin-top:0">{{ $currentUser->name }}</h3>
            <div style="font-size:0.9rem; color:rgba(255,255,255,0.7)">{{ $currentUser->email }}</div>
        </div>
        <ul class="profile-menu">
            <li><a href="{{ route('profile.orders') }}" class="{{ request()->routeIs('profile') || request()->routeIs('profile.orders') ? 'active' : '' }}">📦 Lịch Sử Đơn Hàng</a></li>
            <li><a href="{{ route('profile.wishlist') }}" class="{{ request()->routeIs('profile.wishlist') ? 'active' : '' }}">❤️ Danh Sách Yêu Thích</a></li>
            <li><a href="{{ route('profile.settings') }}" class="{{ request()->routeIs('profile.settings') ? 'active' : '' }}">⚙️ Cài Đặt Tài Khoản</a></li>
            <li>
                <form action="{{ route('logout') }}" method="POST" style="padding:0">
                    @csrf
                    <button type="submit" style="background:transparent; border:none; color:#d9534f; font-weight:700; font-family:inherit; font-size:0.95rem; cursor:pointer; padding:15px 25px; width:100%; text-align:left; border-left: 4px solid transparent;">🚪 Đăng Xuất</button>
                </form>
            </li>
        </ul>
    </aside>

    <!-- Content -->
    <div class="profile-content">
        @if(session('success'))
            <div class="alert-success">✅ {{ session('success') }}</div>
        @endif
        
        @if($errors->any())
            <div class="alert-danger">
                ❌ Có lỗi xảy ra. Hãy kiểm tra lại thông tin!<br>
                <ul style="margin: 5px 0 0 20px; font-weight: 400; font-size: 0.9rem">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h2 class="profile-title">@yield('profile-title')</h2>
        
        @yield('profile-content')
    </div>
</div>
@endsection

@push('js')
    @stack('profile-js')
@endpush
