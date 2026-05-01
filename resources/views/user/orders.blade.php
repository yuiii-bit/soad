@extends('user.layout')
@section('title', 'Lịch Sử Đơn Hàng')
@section('profile-title', 'Lịch Sử Mua Hàng')

@push('profile-css')
<style>
.order-history-box { border: 1px solid #eee; border-radius: 12px; margin-bottom: 25px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
.order-history-header { background: #f8f9fa; padding: 18px 25px; display: flex; justify-content: space-between; align-items: center; font-weight: 600; border-bottom: 1px solid #eee; }
.order-status { display: inline-block; padding: 6px 14px; border-radius: 50px; font-size: 0.85rem; font-weight: 700; margin-bottom: 8px; }
.status-completed { background: #e8f8f3; color: #007a58; }
.status-pending { background: #fff3ee; color: #ff6b35; }
.status-shipping { background: #eff6ff; color: #1e40af; }
.status-cancelled { background: #fef2f2; color: #991b1b; }
.status-confirmed { background: #fcf8e3; color: #856404; }

.order-details { padding: 0 25px; }
.history-item { display:flex; gap:18px; padding:20px 0; border-bottom:1px dashed #eaeaec; align-items: start; }
.history-item:last-child { border: none; }
.history-img { width: 70px; height: 70px; background:#f4f4f4; border-radius:10px; padding:6px; flex-shrink: 0; }
.history-img img { width:100%; height:100%; object-fit:contain; }
.item-info { flex: 1; }
.item-name { font-weight: 700; color: #1a1a2e; margin-bottom: 6px; font-size: 0.95rem; }
.item-meta { font-size: 0.85rem; color: #888; }
.item-price { font-weight: 700; color: var(--primary); font-size: 1.05rem; }

.order-footer { background: #fafafa; padding: 15px 25px; display: flex; justify-content: space-between; font-size: 0.9rem; color: #666; border-top: 1px solid #eee; }
</style>
@endpush

@section('profile-content')

@forelse($orders as $order)
    <div class="order-history-box">
        <div class="order-history-header">
            <div>
                <div style="font-size:1.1rem">Mã Đơn: <span style="font-weight:800; color:var(--primary); letter-spacing:1px">{{ $order->order_code }}</span></div>
                <div style="font-size:0.85rem; color:#666; margin-top:6px; max-width:400px">
                    📍 Kính gửi: {{ $order->shipping_address }}
                </div>
            </div>
            <div style="text-align:right">
                <div class="order-status
                    @switch($order->status)
                        @case('pending') status-pending @break
                        @case('confirmed') status-confirmed @break
                        @case('shipping') status-shipping @break
                        @case('completed') status-completed @break
                        @case('cancelled') status-cancelled @break
                    @endswitch
                ">
                    @switch($order->status)
                        @case('pending') ⏳ Đang Xử Lý @break
                        @case('confirmed') ✅ Đã Xác Nhận @break
                        @case('shipping') 🚚 Đang Giao Hàng @break
                        @case('completed') 🎉 Giao Thành Công @break
                        @case('cancelled') ❌ Đơn Bị Hủy @break
                        @default {{ $order->status }}
                    @endswitch
                </div>
                <div style="font-size:1.3rem; font-weight:900; color:var(--dark);">
                    {{ number_format($order->total_amount, 0, ',', '.') }}₫
                </div>
            </div>
        </div>

        <div class="order-details">
            @php
                $details = \App\Models\OrderDetail::with('product')->where('order_id', $order->id)->get();
            @endphp
            @foreach($details as $detail)
            <div class="history-item">
                <div class="history-img"><img src="{{ asset($detail->product->image ?? 'images/hero.png') }}" alt="{{ $detail->product->name ?? 'Sản phẩm' }}"></div>
                <div class="item-info">
                    <div class="item-name">{{ $detail->product->name ?? 'Sản phẩm đã xóa khỏi hệ thống' }}</div>
                    <div class="item-meta">Số lượng: x{{ $detail->quantity }}</div>
                </div>
                <div class="item-price">{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}₫</div>
            </div>
            @endforeach
        </div>
        
        <div class="order-footer">
            <span>📅 Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</span>
            <span>💳 Phương thức: {{ strtoupper($order->payment_method) }}</span>
        </div>
    </div>
@empty
    <div style="text-align:center; padding:50px 20px; color:#666; background:#f8f9fa; border-radius:15px">
        <div style="font-size:4rem; margin-bottom:15px">🛒</div>
        <p style="font-size:1.1rem; font-weight:600; color:#333; margin-bottom:10px">Bạn chưa mua mặt hàng nào!</p>
        <p style="margin-bottom:20px; color:#888">Hãy dạo quanh cửa hàng và chọn cho mình những đôi dép Crocs yêu thích nhé.</p>
        <a href="{{ route('shop') }}" class="btn btn-primary" style="display:inline-block; padding:12px 25px; background:var(--primary); color:white; text-decoration:none; border-radius:50px; font-weight:700">MUA SẮM NGAY</a>
    </div>
@endforelse

@endsection
