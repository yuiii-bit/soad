@extends('admin.layouts.admin')
@section('title', 'Quản Lý Đơn Hàng')
@section('page-title', '📦 Quản Lý Đơn Hàng')

@push('css')
<style>
.filter-tabs { display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap; }
.filter-tab { padding: 8px 18px; border-radius: 50px; border: 1.5px solid #e5e7eb; font-weight: 700; font-size: 0.82rem; text-decoration: none; color: #555; transition: 0.2s; }
.filter-tab:hover { border-color: var(--primary); color: var(--primary); }
.filter-tab.active { background: var(--primary); color: white; border-color: var(--primary); }
.filter-tab .count { display: inline-block; background: rgba(255,255,255,0.3); border-radius: 50px; padding: 1px 7px; margin-left: 5px; font-size: 0.78rem; }
.filter-tab:not(.active) .count { background: #f1f5f9; color: #64748b; }
</style>
@endpush

@section('content')
<div class="admin-card">
    {{-- Search --}}
    <form method="GET" style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap">
        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Tìm theo mã đơn, tên, SĐT..." style="max-width:300px">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <button class="btn btn-primary" type="submit">🔍 Tìm kiếm</button>
        @if(request('search'))
            <a href="{{ route('admin.orders.index', ['status'=>request('status')]) }}" class="btn btn-outline">✕ Xóa</a>
        @endif
    </form>

    {{-- Status Filter Tabs --}}
    <div class="filter-tabs">
        @php
        $tabs = [
            'all'       => ['label'=>'Tất cả', 'icon'=>'📋'],
            'pending'   => ['label'=>'Chờ xử lý', 'icon'=>'⏳'],
            'confirmed' => ['label'=>'Đã xác nhận', 'icon'=>'✅'],
            'shipping'  => ['label'=>'Đang giao', 'icon'=>'🚚'],
            'completed' => ['label'=>'Hoàn thành', 'icon'=>'🎉'],
            'cancelled' => ['label'=>'Đã hủy', 'icon'=>'❌'],
        ];
        $curStatus = request('status', 'all');
        @endphp
        @foreach($tabs as $key => $tab)
        <a href="{{ route('admin.orders.index', ['status'=>$key, 'search'=>request('search')]) }}"
           class="filter-tab {{ $curStatus == $key ? 'active' : '' }}">
            {{ $tab['icon'] }} {{ $tab['label'] }}
            <span class="count">{{ $counts[$key] }}</span>
        </a>
        @endforeach
    </div>

    <table class="admin-table">
        <thead><tr>
            <th>Mã Đơn</th><th>Khách Hàng</th><th>SĐT</th><th>Địa Chỉ</th><th>Tổng Tiền</th><th>Thanh Toán</th><th>Trạng Thái</th><th>Ngày Đặt</th><th>Hành Động</th>
        </tr></thead>
        <tbody>
        @forelse($orders as $o)
        <tr>
            <td style="font-weight:800;color:var(--primary)">{{ $o->order_code }}</td>
            <td>
                <div style="font-weight:700">{{ $o->customer_name ?? $o->user?->name }}</div>
                <div style="font-size:0.78rem;color:#94a3b8">{{ $o->customer_email }}</div>
            </td>
            <td>{{ $o->customer_phone ?? '—' }}</td>
            <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:0.82rem;color:#64748b" title="{{ $o->shipping_address }}">{{ $o->shipping_address }}</td>
            <td style="font-weight:800">{{ number_format($o->total_amount,0,',','.') }}₫</td>
            <td>
                @switch($o->payment_method)
                    @case('cod')  💵 COD @break
                    @case('bank') 🏦 NH @break
                    @default {{ $o->payment_method }}
                @endswitch
            </td>
            <td>
                <span class="badge
                    @switch($o->status)
                        @case('pending')   badge-warning @break
                        @case('confirmed') badge-info    @break
                        @case('shipping')  badge-info    @break
                        @case('completed') badge-success @break
                        @case('cancelled') badge-danger  @break
                    @endswitch">
                    @switch($o->status)
                        @case('pending')   ⏳ Chờ xử lý  @break
                        @case('confirmed') ✅ Đã xác nhận @break
                        @case('shipping')  🚚 Đang giao   @break
                        @case('completed') 🎉 Hoàn thành  @break
                        @case('cancelled') ❌ Đã hủy       @break
                    @endswitch
                </span>
            </td>
            <td style="font-size:0.82rem;color:#94a3b8">{{ $o->created_at->format('d/m/y H:i') }}</td>
            <td>
                <a href="{{ route('admin.orders.show', $o->id) }}" class="btn btn-sm btn-outline">Chi tiết</a>
            </td>
        </tr>
        @empty
        <tr><td colspan="9" style="text-align:center;padding:40px;color:#94a3b8">Không có đơn hàng nào</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="pagination-wrap">{{ $orders->withQueryString()->links() }}</div>
</div>
@endsection
