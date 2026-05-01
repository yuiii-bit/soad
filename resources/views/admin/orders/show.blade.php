@extends('admin.layouts.admin')
@section('title', 'Chi Tiết Đơn Hàng #' . $order->order_code)
@section('page-title')
    <a href="{{ route('admin.orders.index') }}" style="text-decoration:none; color:#64748b; font-weight:600; font-size:1rem; margin-right:10px">← Quay lại</a>
    Chi Tiết Đơn Hàng #{{ $order->order_code }}
@endsection

@push('css')
<style>
.order-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start; }
.info-box { background: #f8fafc; border-radius: 12px; padding: 18px 20px; border: 1px solid #e2e8f0; }
.info-label { font-size: 0.78rem; text-transform: uppercase; font-weight: 700; color: #64748b; letter-spacing: 0.5px; margin-bottom: 8px; }
.info-value { font-weight: 600; color: #1e293b; font-size: 0.95rem; line-height: 1.5; }
.item-row { display: flex; align-items: center; justify-content: space-between; padding: 16px 0; border-bottom: 1px solid #f1f5f9; }
.item-row:last-child { border-bottom: none; }
.item-img { width: 64px; height: 64px; border-radius: 10px; background: #f1f5f9; padding: 6px; }
.item-img img { width: 100%; height: 100%; object-fit: contain; }
.item-info { flex: 1; margin: 0 16px; }
.item-name { font-weight: 700; color: #1e293b; font-size: 0.95rem; margin-bottom: 4px; }
.item-meta { font-size: 0.85rem; color: #64748b; }
.item-price { font-weight: 800; color: var(--primary); }
.summary-row { display: flex; justify-content: space-between; padding: 10px 0; font-size: 0.95rem; }
.summary-label { color: #64748b; font-weight: 600; }
.summary-val { font-weight: 700; color: #1e293b; }
</style>
@endpush

@section('content')
<div class="order-grid">

    {{-- TRÁI: Sản phẩm & Thông tin --}}
    <div>
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title">📦 Sản Phẩm Đã Đặt</div>
                <div class="badge badge-gray" style="font-size:0.85rem">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</div>
            </div>

            <div>
                @foreach($details as $item)
                <div class="item-row">
                    <div class="item-img"><img src="{{ asset($item->product->image ?? 'images/hero.png') }}"></div>
                    <div class="item-info">
                        <div class="item-name">{{ $item->product->name ?? 'Sản phẩm đã xóa' }}</div>
                        <div class="item-meta">Mã SP: #{{ $item->product_id }}</div>
                    </div>
                    <div>
                        <div style="font-weight:700; color:#475569; font-size:0.9rem">{{ number_format($item->price, 0,',','.') }}₫ × {{ $item->quantity }}</div>
                    </div>
                    <div class="item-price" style="width:100px; text-align:right">
                        {{ number_format($item->price * $item->quantity, 0,',','.') }}₫
                    </div>
                </div>
                @endforeach
            </div>

            <div style="border-top:2px dashed #e2e8f0; margin-top:16px; padding-top:16px">
                <div class="summary-row">
                    <span class="summary-label">Tạm tính</span>
                    <span class="summary-val">{{ number_format($order->total_amount, 0,',','.') }}₫</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Phí vận chuyển</span>
                    <span class="summary-val" style="color:var(--primary)">Miễn phí</span>
                </div>
                <div class="summary-row" style="margin-top:12px; padding-top:12px; border-top:1px solid #e2e8f0; font-size:1.1rem">
                    <span class="summary-label" style="font-weight:800; color:#1e293b">Tổng Cộng</span>
                    <span class="summary-val" style="font-size:1.4rem; color:var(--primary); font-weight:900">{{ number_format($order->total_amount, 0,',','.') }}₫</span>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-title" style="margin-bottom:20px">📍 Thông Tin Khách Hàng</div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px">
                <div class="info-box">
                    <div class="info-label">Người Nhận</div>
                    <div class="info-value">
                        {{ $order->customer_name ?? $order->user?->name }}<br>
                        📞 {{ $order->customer_phone ?? '—' }}<br>
                        ✉️ {{ $order->customer_email ?? $order->user?->email ?? '—' }}
                    </div>
                </div>
                <div class="info-box">
                    <div class="info-label">Giao Đến</div>
                    <div class="info-value" style="font-size:0.9rem">
                        {{ $order->shipping_address }}
                    </div>
                </div>
                <div class="info-box" style="grid-column: span 2">
                    <div class="info-label">Ghi Chú Của Khách</div>
                    <div class="info-value" style="font-weight:400; font-style:italic">
                        {{ $order->note ?? 'Không có ghi chú.' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- PHẢI: Trạng Thái & Thanh Toán --}}
    <div>
        <div class="admin-card">
            <div class="admin-card-title" style="margin-bottom:20px">Cập Nhật Trạng Thái</div>

            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Tình trạng đơn hàng</label>
                    <div style="display:flex; flex-direction:column; gap:10px">
                        
                        @if($order->status == 'pending')
                            <label style="display:flex; align-items:center; gap:10px; cursor:pointer; padding:12px 16px; border:1px solid #e2e8f0; border-radius:10px; background:#fff3ee">
                                <input type="radio" name="status" value="pending" checked>
                                <span style="font-weight:600; color:#ff6b35">⏳ Chờ xử lý (Hiện tại)</span>
                            </label>
                            <label style="display:flex; align-items:center; gap:10px; cursor:pointer; padding:12px 16px; border:1px solid #e2e8f0; border-radius:10px; background:white">
                                <input type="radio" name="status" value="confirmed">
                                <span style="font-weight:600; color:#1e40af">✅ Đã xác nhận</span>
                            </label>
                            <label style="display:flex; align-items:center; gap:10px; cursor:pointer; padding:12px 16px; border:1px solid #e2e8f0; border-radius:10px; background:white">
                                <input type="radio" name="status" value="cancelled">
                                <span style="font-weight:600; color:#991b1b">❌ Hủy đơn hàng</span>
                            </label>
                        @elseif($order->status == 'confirmed')
                            <label style="display:flex; align-items:center; gap:10px; cursor:pointer; padding:12px 16px; border:1px solid #e2e8f0; border-radius:10px; background:#eff6ff">
                                <input type="radio" name="status" value="confirmed" checked>
                                <span style="font-weight:600; color:#1e40af">✅ Đã xác nhận (Hiện tại)</span>
                            </label>
                            <label style="display:flex; align-items:center; gap:10px; cursor:pointer; padding:12px 16px; border:1px solid #e2e8f0; border-radius:10px; background:white">
                                <input type="radio" name="status" value="shipping">
                                <span style="font-weight:600; color:#856404">🚚 Đang giao hàng</span>
                            </label>
                            <label style="display:flex; align-items:center; gap:10px; cursor:pointer; padding:12px 16px; border:1px solid #e2e8f0; border-radius:10px; background:white">
                                <input type="radio" name="status" value="cancelled">
                                <span style="font-weight:600; color:#991b1b">❌ Hủy đơn hàng</span>
                            </label>
                        @elseif($order->status == 'shipping')
                            <label style="display:flex; align-items:center; gap:10px; cursor:pointer; padding:12px 16px; border:1px solid #e2e8f0; border-radius:10px; background:#fcf8e3">
                                <input type="radio" name="status" value="shipping" checked>
                                <span style="font-weight:600; color:#856404">🚚 Đang giao hàng (Hiện tại)</span>
                            </label>
                            <label style="display:flex; align-items:center; gap:10px; cursor:pointer; padding:12px 16px; border:1px solid #e2e8f0; border-radius:10px; background:white">
                                <input type="radio" name="status" value="completed">
                                <span style="font-weight:600; color:#065f46">🎉 Đã giao thành công</span>
                            </label>
                        @elseif($order->status == 'completed')
                            <div style="padding:15px; border-radius:10px; background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; font-weight:700; text-align:center">
                                🎉 Đơn hàng đã giao thành công!
                            </div>
                        @elseif($order->status == 'cancelled')
                            <div style="padding:15px; border-radius:10px; background:#fef2f2; border:1px solid #fecaca; color:#991b1b; font-weight:700; text-align:center">
                                ❌ Đơn hàng này đã bị hủy.
                            </div>
                        @endif

                    </div>
                </div>
                
                @if(!in_array($order->status, ['completed', 'cancelled']))
                    <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:12px; font-size:1rem">💾 Lưu Trạng Thái</button>
                @endif
            </form>
        </div>

        <div class="admin-card">
            <div class="admin-card-title" style="margin-bottom:20px">Thanh Toán</div>
            <div class="info-box">
                <div class="info-label">Phương Thức</div>
                <div class="info-value" style="display:flex; align-items:center; gap:10px; font-size:1.05rem">
                    @switch($order->payment_method)
                        @case('cod') 💵 Tiền mặt khi nhận hàng (COD) @break
                        @case('bank') 🏦 Chuyển khoản ngân hàng @break
                        @case('momo') 📱 Ví điện tử MoMo @break
                        @default {{ $order->payment_method }}
                    @endswitch
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
