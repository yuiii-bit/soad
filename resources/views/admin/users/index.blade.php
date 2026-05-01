@extends('admin.layouts.admin')
@section('title', 'Quản Lý Khách Hàng')
@section('page-title', '👥 Quản Lý Khách Hàng')

@section('content')
<div class="admin-card">
    <div style="display:flex; justify-content:space-between; margin-bottom:20px">
        <form method="GET" style="display:flex; gap:10px">
            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Tìm theo tên, email..." style="width:300px">
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            @if(request('search'))
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Xóa lọc</a>
            @endif
        </form>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Avatar</th>
                <th>Khách Hàng</th>
                <th>Email</th>
                <th>SĐT / Địa Chỉ</th>
                <th>Số Đơn Hàng</th>
                <th>Ngày Tham Gia</th>
                <th style="text-align:right">Hành Động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $u)
            <tr>
                <td>
                    <div style="width:40px; height:40px; border-radius:50%; background:var(--primary-light); color:var(--primary); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1.1rem">
                        {{ strtoupper(substr($u->name, 0, 1)) }}
                    </div>
                </td>
                <td style="font-weight:700">{{ $u->name }}</td>
                <td style="color:#64748b">{{ $u->email }}</td>
                <td>
                    <div style="font-weight:600">{{ $u->phone ?? '—' }}</div>
                    <div style="font-size:0.8rem; color:#94a3b8; max-width:150px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap">{{ $u->address ?? '—' }}</div>
                </td>
                <td><span class="badge badge-info">{{ $u->orders_count ?? 0 }} đơn</span></td>
                <td style="color:#64748b; font-size:0.85rem">{{ $u->created_at->format('d/m/Y') }}</td>
                <td style="text-align:right">
                    <form action="{{ route('admin.users.toggle', $u->id) }}" method="POST" style="display:inline-block">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline" style="color:var(--danger)">Khóa Tài Khoản</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center; padding:40px; color:#94a3b8">Không tìm thấy khách hàng nào.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-wrap">{{ $users->withQueryString()->links() }}</div>
</div>
@endsection
