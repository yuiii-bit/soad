@extends('admin.layouts.admin')
@section('title', 'Quản Lý Voucher / Coupon')
@section('page-title', '🎟️ Quản Lý Mã Giảm Giá')

@section('content')
<div style="display:grid; grid-template-columns: 1fr 2.5fr; gap: 30px; align-items: start">
    
    {{-- Form Thêm --}}
    <div class="admin-card">
        <div class="admin-card-title" style="margin-bottom:20px">Tạo Mã Mới</div>
        <form action="{{ route('admin.coupons.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Mã Code * (Viết hoa, không dấu)</label>
                <input type="text" name="code" class="form-control" required placeholder="VD: SUMMER2024" style="text-transform:uppercase">
                @error('code') <div style="color:red;font-size:0.8rem;margin-top:5px">{{ $message }}</div> @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Loại giảm giá *</label>
                <select name="discount_type" class="form-control" required>
                    <option value="fixed">Giảm số tiền cố định (VNĐ)</option>
                    <option value="percent">Giảm theo phần trăm (%)</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Mức giảm *</label>
                <input type="number" name="discount_value" class="form-control" required min="1" placeholder="VD: 50000 hoặc 10">
            </div>

            <div class="form-group">
                <label class="form-label">Đơn hàng tối thiểu (VNĐ) *</label>
                <input type="number" name="min_order_value" class="form-control" required min="0" value="0">
            </div>

            <div class="form-group">
                <label class="form-label">Số lượng lượt dùng *</label>
                <input type="number" name="quantity" class="form-control" required min="1" value="100">
            </div>

            <div class="form-group">
                <label class="form-label">Ngày hết hạn (tùy chọn)</label>
                <input type="date" name="expired_at" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center">➕ Tạo Mã Giảm Giá</button>
        </form>
    </div>

    {{-- Danh sách --}}
    <div class="admin-card">
        <div class="admin-card-title" style="margin-bottom:20px">Danh Sách Mã Giảm Giá</div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Mã Code</th>
                    <th>Mức Giảm</th>
                    <th>ĐK Áp Dụng</th>
                    <th>Lượt Dùng</th>
                    <th>Hết Hạn</th>
                    <th>Phân Loại</th>
                    <th style="text-align:right">Xóa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coupons as $cp)
                <tr>
                    <td style="font-weight:900; color:var(--primary); font-size:1.1rem; letter-spacing:1px">{{ $cp->code }}</td>
                    <td style="font-weight:700">
                        @if($cp->discount_type == 'percent')
                            Giảm <span style="color:#ef4444">{{ $cp->discount_value }}%</span>
                        @else
                            Giảm <span style="color:#ef4444">-{{ number_format($cp->discount_value,0,',','.') }}đ</span>
                        @endif
                    </td>
                    <td style="font-size:0.85rem; color:#64748b">
                        Đơn từ {{ number_format($cp->min_order_value,0,',','.') }}đ
                    </td>
                    <td>
                        <span class="badge {{ $cp->quantity > 0 ? 'badge-info' : 'badge-danger' }}">
                            Còn {{ $cp->quantity }} lượt
                        </span>
                    </td>
                    <td>
                        @if(!$cp->expired_at)
                            <span style="color:#94a3b8; font-size:0.85rem">Không thời hạn</span>
                        @else
                            @if(\Carbon\Carbon::parse($cp->expired_at)->isPast())
                                <span style="color:#ef4444; font-size:0.85rem; font-weight:600">Đã hết hạn</span>
                            @else
                                <span style="color:#1e293b; font-size:0.85rem">{{ \Carbon\Carbon::parse($cp->expired_at)->format('d/m/Y') }}</span>
                            @endif
                        @endif
                    </td>
                    <td>
                        @if(!$cp->expired_at && $cp->quantity > 0 || ($cp->expired_at && !\Carbon\Carbon::parse($cp->expired_at)->isPast() && $cp->quantity > 0))
                             <span class="badge badge-success">Hợp Lệ</span>
                        @else
                             <span class="badge badge-danger">Hết Hạn</span>
                        @endif
                    </td>
                    <td style="text-align:right">
                        <form action="{{ route('admin.coupons.destroy', $cp->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa mã giảm giá này?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline" style="color:var(--danger)">🗑</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
