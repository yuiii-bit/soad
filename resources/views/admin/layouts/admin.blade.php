<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản Trị') – CrocsVN Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #00A878;
            --primary-light: #e0f7f1;
            --dark: #1a1a2e;
            --dark2: #16213e;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info:    #3b82f6;
            --bg: #f1f5f9;
            --sidebar-w: 260px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Outfit', sans-serif; background: var(--bg); color: #333; display: flex; min-height: 100vh; }

        /* ===== SIDEBAR ===== */
        .admin-sidebar {
            width: var(--sidebar-w); background: var(--dark);
            height: 100vh; position: fixed; top: 0; left: 0;
            display: flex; flex-direction: column; z-index: 100; overflow-y: auto;
        }
        .sidebar-logo {
            padding: 28px 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-logo-text {
            font-size: 1.6rem; font-weight: 900; color: var(--primary); display: flex; align-items: center; gap: 10px;
        }
        .sidebar-logo-sub { font-size: 0.75rem; color: rgba(255,255,255,0.4); margin-top: 3px; }

        .sidebar-section { padding: 16px 0 4px 24px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,0.3); }

        .sidebar-nav { list-style: none; padding: 8px 12px; flex: 1; }
        .sidebar-nav li { margin-bottom: 3px; }
        .sidebar-nav li a {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 14px; border-radius: 10px;
            color: rgba(255,255,255,0.65); text-decoration: none;
            font-weight: 600; font-size: 0.92rem; transition: all 0.2s;
        }
        .sidebar-nav li a:hover { background: rgba(255,255,255,0.08); color: white; }
        .sidebar-nav li a.active { background: var(--primary); color: white; box-shadow: 0 4px 15px rgba(0,168,120,0.3); }
        .sidebar-nav li a .nav-icon { font-size: 1.1rem; width: 22px; text-align: center; }

        .sidebar-bottom { padding: 16px 12px 24px; border-top: 1px solid rgba(255,255,255,0.08); }
        .sidebar-bottom a, .sidebar-bottom button {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 14px; border-radius: 10px; width: 100%;
            color: rgba(255,255,255,0.65); text-decoration: none;
            font-weight: 600; font-size: 0.92rem; transition: 0.2s;
            background: none; border: none; cursor: pointer; font-family: inherit;
        }
        .sidebar-bottom a:hover, .sidebar-bottom button:hover { background: rgba(255,255,255,0.08); color: white; }

        /* ===== MAIN ===== */
        .admin-main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

        /* Header */
        .admin-header {
            background: white; padding: 0 32px; height: 66px;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid #e5e7eb; position: sticky; top: 0; z-index: 50;
        }
        .admin-header-title { font-size: 1.3rem; font-weight: 800; color: var(--dark); }
        .admin-header-right { display: flex; align-items: center; gap: 16px; }
        .admin-avatar { width: 38px; height: 38px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; color: white; font-size: 1rem; }
        .admin-name { font-weight: 700; font-size: 0.9rem; }

        /* Content */
        .admin-content { padding: 32px; flex: 1; }

        /* ===== ALERT FLASH ===== */
        .flash { padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: 600; font-size: 0.92rem; display: flex; align-items: center; gap: 10px; }
        .flash-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .flash-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fca5a5; }

        /* ===== CARD ===== */
        .admin-card { background: white; border-radius: 16px; box-shadow: 0 2px 15px rgba(0,0,0,0.04); padding: 28px; margin-bottom: 28px; }
        .admin-card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; }
        .admin-card-title { font-size: 1.1rem; font-weight: 800; color: var(--dark); }

        /* ===== TABLE ===== */
        .admin-table { width: 100%; border-collapse: collapse; }
        .admin-table thead { background: #f8fafc; }
        .admin-table th { padding: 13px 16px; text-align: left; font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; white-space: nowrap; }
        .admin-table td { padding: 14px 16px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 0.92rem; }
        .admin-table tbody tr:last-child td { border-bottom: none; }
        .admin-table tbody tr:hover { background: #fafbfc; }

        /* ===== BADGES ===== */
        .badge { display: inline-block; padding: 4px 12px; border-radius: 50px; font-size: 0.78rem; font-weight: 700; }
        .badge-success { background: #ecfdf5; color: #065f46; }
        .badge-warning { background: #fffbeb; color: #92400e; }
        .badge-danger  { background: #fef2f2; color: #991b1b; }
        .badge-info    { background: #eff6ff; color: #1e40af; }
        .badge-gray    { background: #f1f5f9; color: #475569; }

        /* ===== BUTTONS ===== */
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 10px; font-family: inherit; font-weight: 700; font-size: 0.88rem; cursor: pointer; border: none; text-decoration: none; transition: all 0.2s; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: #008f65; transform: translateY(-1px); }
        .btn-dark    { background: var(--dark); color: white; }
        .btn-dark:hover { background: #2d2d5e; }
        .btn-danger  { background: var(--danger); color: white; }
        .btn-danger:hover { background: #dc2626; }
        .btn-outline { background: white; color: #555; border: 1.5px solid #e5e7eb; }
        .btn-outline:hover { border-color: #aaa; background: #f8fafc; }
        .btn-sm { padding: 6px 13px; font-size: 0.82rem; border-radius: 8px; }
        .btn-icon { padding: 7px 10px; border-radius: 8px; }

        /* ===== FORM ===== */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-weight: 700; color: #374151; margin-bottom: 7px; font-size: 0.88rem; }
        .form-control { width: 100%; padding: 11px 15px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-family: inherit; font-size: 0.92rem; color: #1a1a2e; outline: none; transition: 0.2s; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(0,168,120,0.1); }
        .form-row { display: grid; gap: 18px; }
        .form-row-2 { grid-template-columns: 1fr 1fr; }
        .form-row-3 { grid-template-columns: 1fr 1fr 1fr; }

        /* ===== STAT CARDS ===== */
        .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 28px; }
        .stat-card { background: white; padding: 24px; border-radius: 16px; box-shadow: 0 2px 15px rgba(0,0,0,0.04); }
        .stat-icon { font-size: 1.8rem; margin-bottom: 10px; }
        .stat-label { font-size: 0.82rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-value { font-size: 1.8rem; font-weight: 900; color: var(--dark); margin: 6px 0 4px; line-height: 1; }
        .stat-change { font-size: 0.82rem; color: var(--primary); font-weight: 600; }

        /* ===== PAGINATION ===== */
        .pagination-wrap { display: flex; justify-content: center; padding: 20px 0 0; }
        .pagination-wrap .pagination { display: flex; gap: 5px; }
        .pagination-wrap .page-link { padding: 8px 14px; border-radius: 8px; border: 1.5px solid #e5e7eb; color: #555; font-weight: 600; font-size: 0.88rem; text-decoration: none; transition: 0.2s; }
        .pagination-wrap .page-link:hover, .pagination-wrap .page-item.active .page-link { background: var(--primary); color: white; border-color: var(--primary); }

        @media (max-width: 1024px) {
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
            .form-row-3 { grid-template-columns: 1fr 1fr; }
        }
    </style>
    @stack('css')
</head>
<body>

{{-- ===== SIDEBAR ===== --}}
<aside class="admin-sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-text">🐊 Crocs Admin</div>
        <div class="sidebar-logo-sub">Quản trị hệ thống CrocsVN</div>
    </div>

    <ul class="sidebar-nav" style="padding-top:16px">
        <li class="sidebar-section-item" style="padding: 10px 14px 4px; font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:rgba(255,255,255,0.3)">CHÍNH</li>
        <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">📊</span> Dashboard
        </a></li>

        <li style="padding: 10px 14px 4px; font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:rgba(255,255,255,0.3)">QUẢN LÝ</li>
        <li><a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <span class="nav-icon">📦</span> Đơn Hàng
        </a></li>
        <li><a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <span class="nav-icon">👟</span> Sản Phẩm
        </a></li>
        <li><a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <span class="nav-icon">🏷️</span> Danh Mục
        </a></li>
        <li><a href="{{ route('admin.coupons.index') }}" class="{{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
            <span class="nav-icon">🎟️</span> Voucher / Coupon
        </a></li>
        <li><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <span class="nav-icon">👥</span> Khách Hàng
        </a></li>
    </ul>

    <div class="sidebar-bottom">
        <a href="{{ route('home') }}" target="_blank">
            <span class="nav-icon">🏪</span> Xem Cửa Hàng
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">
                <span class="nav-icon">🚪</span> Đăng Xuất
            </button>
        </form>
    </div>
</aside>

{{-- ===== MAIN ===== --}}
<div class="admin-main">
    <header class="admin-header">
        <div class="admin-header-title">@yield('page-title', 'Quản Trị')</div>
        <div class="admin-header-right">
            <div style="font-size:0.82rem; color:#64748b">Xin chào,</div>
            <div class="admin-name">{{ Auth::user()->name }}</div>
            <div class="admin-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
        </div>
    </header>

    <div class="admin-content">
        @if(session('success'))
        <div class="flash flash-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="flash flash-error">❌ {{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

@stack('js')
</body>
</html>
