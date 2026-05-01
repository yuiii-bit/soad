@extends('layouts.app')

@section('title', 'Đặt Hàng Thành Công – CrocsVN')

@push('css')
<style>
.success-page { min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px; background: #f8f9fa; }
.success-card { max-width: 600px; width: 100%; background: white; border-radius: 28px; box-shadow: 0 20px 60px rgba(0,0,0,0.08); overflow: hidden; }

/* Header */
.success-header { background: linear-gradient(135deg, #00A878, #00c896); padding: 50px 40px; text-align: center; }
.success-anim { width: 90px; height: 90px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 2.5rem; animation: popIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275); box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
@keyframes popIn { 0%{transform:scale(0) rotate(-180deg);opacity:0} 100%{transform:scale(1) rotate(0deg);opacity:1} }
.success-header h1 { font-size: 2rem; font-weight: 900; color: white; margin: 0 0 8px; }
.success-header p { color: rgba(255,255,255,0.85); font-size: 1rem; margin: 0; }

/* Body */
.success-body { padding: 35px 40px; }

/* Order Code Box */
.order-code-box { background: linear-gradient(135deg, #f8fffe, #f0fdf9); border: 2px solid #b2e8d8; border-radius: 16px; padding: 20px 25px; margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between; }
.order-code-label { font-size: 0.82rem; color: #888; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
.order-code-value { font-size: 1.6rem; font-weight: 900; color: var(--primary); letter-spacing: 3px; }
.copy-btn { background: var(--primary); color: white; border: none; padding: 10px 18px; border-radius: 10px; font-family: inherit; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: 0.2s; white-space: nowrap; }
.copy-btn:hover { background: #008f65; transform: scale(1.05); }

/* Details Grid */
.order-details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 25px; }
.detail-item { background: #f8f9fa; border-radius: 12px; padding: 15px 18px; }
.detail-item-label { font-size: 0.78rem; color: #aaa; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; }
.detail-item-value { font-weight: 700; color: #1a1a2e; font-size: 0.95rem; }

/* Info Message */
.info-msg { background: #fff3ee; border-radius: 12px; padding: 16px 20px; margin-bottom: 25px; display: flex; gap: 12px; align-items: flex-start; font-size: 0.88rem; color: #666; line-height: 1.5; }
.info-msg-icon { font-size: 1.3rem; flex-shrink: 0; margin-top: 1px; }

/* Actions */
.success-actions { display: flex; gap: 14px; }
.btn-action-primary { flex: 1; text-align: center; padding: 16px; background: var(--dark); color: white; text-decoration: none; border-radius: 50px; font-weight: 700; transition: 0.3s; }
.btn-action-primary:hover { background: var(--primary); transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,168,120,0.3); }
.btn-action-secondary { flex: 1; text-align: center; padding: 16px; background: white; color: var(--dark); text-decoration: none; border-radius: 50px; font-weight: 700; border: 2px solid #e5e5e5; transition: 0.3s; }
.btn-action-secondary:hover { border-color: var(--dark); transform: translateY(-2px); }

/* Footer */
.success-footer { background: #f8f9fa; padding: 20px 40px; text-align: center; border-top: 1px solid #eee; }
.success-footer p { font-size: 0.85rem; color: #aaa; margin: 0; }

@media (max-width: 600px) {
    .success-body { padding: 25px 20px; }
    .success-header { padding: 40px 20px; }
    .order-details-grid { grid-template-columns: 1fr; }
    .success-actions { flex-direction: column; }
    .order-code-box { flex-direction: column; gap: 12px; align-items: flex-start; }
}
</style>
@endpush

@section('content')
<div class="success-page">
    <div class="success-card">

        <div class="success-header">
            <div class="success-anim">✅</div>
            <h1>Đặt Hàng Thành Công!</h1>
            <p>Cảm ơn bạn đã tin tưởng mua sắm tại CrocsVN 🎉</p>
        </div>

        <div class="success-body">

            {{-- Order Code --}}
            <div class="order-code-box">
                <div>
                    <div class="order-code-label">Mã Đơn Hàng</div>
                    <div class="order-code-value" id="orderCodeText">{{ session('order_code') }}</div>
                </div>
                <button class="copy-btn" onclick="copyCode()">📋 Sao Chép</button>
            </div>

            {{-- Order Details --}}
            <div class="order-details-grid">
                @if(session('customer_name'))
                <div class="detail-item">
                    <div class="detail-item-label">👤 Người Nhận</div>
                    <div class="detail-item-value">{{ session('customer_name') }}</div>
                </div>
                @endif

                @if(session('total_amount'))
                <div class="detail-item">
                    <div class="detail-item-label">💰 Tổng Tiền</div>
                    <div class="detail-item-value" style="color:var(--primary)">{{ number_format(session('total_amount'), 0, ',', '.') }}₫</div>
                </div>
                @endif

                @if(session('payment_method'))
                <div class="detail-item">
                    <div class="detail-item-label">💳 Thanh Toán</div>
                    <div class="detail-item-value">
                        @switch(session('payment_method'))
                            @case('cod') 💵 Tiền mặt (COD) @break
                            @case('bank') 🏦 Chuyển khoản @break
                            @default {{ session('payment_method') }}
                        @endswitch
                    </div>
                </div>
                @endif

                <div class="detail-item">
                    <div class="detail-item-label">📦 Trạng Thái</div>
                    <div class="detail-item-value" style="color: #ff6b35">⏳ Đang xử lý</div>
                </div>
            </div>

            {{-- Info Message --}}
            <div class="info-msg">
                <div class="info-msg-icon">📧</div>
                <div>
                    Đơn hàng của bạn đã được tiếp nhận. Chúng tôi sẽ xác nhận và giao hàng trong <strong>2–5 ngày làm việc</strong>. Lưu mã đơn hàng để tra cứu trạng thái vận chuyển.
                </div>
            </div>

            {{-- Actions --}}
            <div class="success-actions">
                <a href="{{ route('shop') }}" class="btn-action-primary">🛍 Tiếp Tục Mua Sắm</a>
                <a href="{{ route('profile') }}" class="btn-action-secondary">📦 Xem Đơn Hàng</a>
            </div>

        </div>

        <div class="success-footer">
            <p>🔒 Mọi giao dịch được bảo mật SSL 256-bit • © 2025 CrocsVN</p>
        </div>

    </div>
</div>
@endsection

@push('js')
<script>
function copyCode() {
    const text = document.getElementById('orderCodeText').textContent.trim();
    navigator.clipboard.writeText(text).then(() => {
        showToast('✅ Đã sao chép mã đơn hàng: ' + text);
    }).catch(() => {
        // Fallback
        const el = document.createElement('textarea');
        el.value = text;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        showToast('✅ Đã sao chép mã đơn hàng!');
    });
}
</script>
@endpush
