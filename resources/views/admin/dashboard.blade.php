@extends('admin.layouts.admin')
@section('title', 'Dashboard')
@section('page-title', '📊 Bảng Điều Khiển')

@push('css')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<style>
.chart-box { background: white; border-radius: 16px; box-shadow: 0 2px 15px rgba(0,0,0,0.04); padding: 28px; }
.quick-actions { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 28px; }
.qa-card { background: white; border-radius: 14px; padding: 18px 20px; text-decoration: none; display: flex; align-items: center; gap: 14px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); transition: 0.2s; border: 2px solid transparent; }
.qa-card:hover { transform: translateY(-2px); border-color: var(--primary); }
.qa-icon { font-size: 1.8rem; }
.qa-label { font-weight: 700; color: #1a1a2e; font-size: 0.9rem; }
.qa-sub { font-size: 0.78rem; color: #94a3b8; margin-top: 2px; }
.recent-badge { font-size: 0.78rem; font-weight: 700; padding: 3px 10px; border-radius: 50px; }
.status-pending   { background: #fff3ee; color: #ff6b35; }
.status-completed { background: #ecfdf5; color: #065f46; }
.status-confirmed { background: #eff6ff; color: #1e40af; }
.status-shipping  { background: #fefce8; color: #854d0e; }
.status-cancelled { background: #fef2f2; color: #991b1b; }
.top-product { display: flex; align-items: center; gap: 14px; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
.top-product:last-child { border: none; }
.top-product img { width: 48px; height: 48px; object-fit: contain; border-radius: 10px; background: #f4f4f4; padding: 4px; }
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
</style>
@endpush

@section('content')
{{-- STATS --}}
<div class="stat-grid">
    <div class="stat-card" style="border-left: 4px solid var(--primary)">
        <div class="stat-icon">💰</div>
        <div class="stat-label">Tổng Doanh Thu</div>
        <div class="stat-value" style="color:var(--primary)">{{ number_format($stats['total_sales'], 0, ',', '.') }}₫</div>
        <div class="stat-change">↑ Từ các đơn hoàn thành</div>
    </div>
    <div class="stat-card" style="border-left: 4px solid #3b82f6">
        <div class="stat-icon">📦</div>
        <div class="stat-label">Tổng Đơn Hàng</div>
        <div class="stat-value" style="color:#3b82f6">{{ $stats['total_orders'] }}</div>
        <div class="stat-change" style="color:#f59e0b">⏳ {{ $stats['pending_orders'] }} đang xử lý</div>
    </div>
    <div class="stat-card" style="border-left: 4px solid #8b5cf6">
        <div class="stat-icon">👟</div>
        <div class="stat-label">Sản Phẩm Đang Bán</div>
        <div class="stat-value" style="color:#8b5cf6">{{ $stats['total_products'] }}</div>
        <div class="stat-change" style="color:#94a3b8">mã sản phẩm</div>
    </div>
    <div class="stat-card" style="border-left: 4px solid #f59e0b">
        <div class="stat-icon">👤</div>
        <div class="stat-label">Khách Hàng</div>
        <div class="stat-value" style="color:#f59e0b">{{ $stats['total_users'] }}</div>
        <div class="stat-change" style="color:#94a3b8">tài khoản đã đăng ký</div>
    </div>
</div>

{{-- QUICK ACTIONS --}}
<div class="quick-actions">
    <a href="{{ route('admin.orders.index') }}" class="qa-card">
        <div class="qa-icon">📦</div>
        <div><div class="qa-label">Đơn Hàng</div><div class="qa-sub">Quản lý & cập nhật</div></div>
    </a>
    <a href="{{ route('admin.products.create') }}" class="qa-card">
        <div class="qa-icon">➕</div>
        <div><div class="qa-label">Thêm Sản Phẩm</div><div class="qa-sub">Đăng sản phẩm mới</div></div>
    </a>
    <a href="{{ route('admin.coupons.index') }}" class="qa-card">
        <div class="qa-icon">🎟️</div>
        <div><div class="qa-label">Voucher</div><div class="qa-sub">Tạo mã giảm giá</div></div>
    </a>
    <a href="{{ route('admin.users.index') }}" class="qa-card">
        <div class="qa-icon">👥</div>
        <div><div class="qa-label">Khách Hàng</div><div class="qa-sub">Danh sách tài khoản</div></div>
    </a>
</div>

<div class="grid-2">
    {{-- CHART --}}
    <div class="chart-box">
        <div style="font-size:1.05rem; font-weight:800; color:var(--dark); margin-bottom:20px">📈 Doanh Thu Theo Tháng</div>
        <canvas id="revenueChart" height="200"></canvas>
    </div>

    {{-- TOP PRODUCTS --}}
    <div class="chart-box">
        <div style="font-size:1.05rem; font-weight:800; color:var(--dark); margin-bottom:20px">🏆 Top Sản Phẩm Bán Chạy</div>
        @forelse($top_products as $tp)
        <div class="top-product">
            <img src="{{ asset($tp->image ?? 'images/hero.png') }}" alt="{{ $tp->name }}">
            <div style="flex:1">
                <div style="font-weight:700; font-size:0.9rem">{{ $tp->name }}</div>
                <div style="font-size:0.8rem; color:#94a3b8">Đã bán: <strong>{{ $tp->sold }}</strong> đơn</div>
            </div>
            <div style="font-weight:800; color:var(--primary); font-size:0.9rem">{{ number_format($tp->price, 0,',','.') }}₫</div>
        </div>
        @empty
        <div style="text-align:center;color:#94a3b8;padding:20px">Chưa có dữ liệu bán hàng</div>
        @endforelse
    </div>
</div>

{{-- RECENT ORDERS --}}
<div class="admin-card" style="margin-top:24px">
    <div class="admin-card-header">
        <div class="admin-card-title">📋 Đơn Hàng Mới Nhất</div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">Xem tất cả →</a>
    </div>
    <table class="admin-table">
        <thead><tr>
            <th>Mã Đơn</th><th>Khách Hàng</th><th>SĐT</th><th>Thành Tiền</th><th>Thanh Toán</th><th>Trạng Thái</th><th>Ngày Đặt</th><th></th>
        </tr></thead>
        <tbody>
            @foreach($recent_orders as $o)
            <tr>
                <td style="font-weight:800;color:var(--primary)">{{ $o->order_code }}</td>
                <td>{{ $o->customer_name ?? $o->user?->name ?? 'Khách' }}</td>
                <td>{{ $o->customer_phone ?? '—' }}</td>
                <td style="font-weight:700">{{ number_format($o->total_amount, 0,',','.') }}₫</td>
                <td>
                    @switch($o->payment_method)
                        @case('cod') 💵 COD @break
                        @case('bank') 🏦 Ngân hàng @break
                        @default {{ $o->payment_method }}
                    @endswitch
                </td>
                <td><span class="recent-badge status-{{ $o->status }}">
                    @switch($o->status)
                        @case('pending') ⏳ Chờ xử lý @break
                        @case('confirmed') ✅ Đã xác nhận @break
                        @case('shipping') 🚚 Đang giao @break
                        @case('completed') 🎉 Hoàn thành @break
                        @case('cancelled') ❌ Đã hủy @break
                    @endswitch
                </span></td>
                <td style="color:#94a3b8;font-size:0.82rem">{{ $o->created_at->format('d/m H:i') }}</td>
                <td><a href="{{ route('admin.orders.show', $o->id) }}" class="btn btn-sm btn-outline">Chi tiết</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('js')
<script>
const months = {!! json_encode($monthly_labels) !!};
const revenues = {!! json_encode($monthly_revenues) !!};

new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Doanh Thu (₫)',
            data: revenues,
            backgroundColor: 'rgba(0,168,120,0.15)',
            borderColor: '#00A878',
            borderWidth: 2,
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: val => (val/1000000).toFixed(1) + 'M'
                },
                grid: { color: '#f1f5f9' }
            },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush
