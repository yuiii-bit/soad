@extends('layouts.app')

@section('title', 'Thanh Toán – CrocsVN')

@push('css')
<style>
/* ===== CHECKOUT PAGE ===== */
.checkout-hero { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); padding: 30px 20px; }
.checkout-steps { max-width: 600px; margin: 0 auto; display: flex; align-items: center; justify-content: center; gap: 0; }
.step { display: flex; flex-direction: column; align-items: center; gap: 6px; }
.step-circle { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem; }
.step-circle.done { background: var(--primary); color: white; }
.step-circle.active { background: white; color: var(--dark); }
.step-circle.todo { background: rgba(255,255,255,0.15); color: rgba(255,255,255,0.4); }
.step-label { font-size: 0.75rem; color: rgba(255,255,255,0.6); font-weight: 600; }
.step-label.active { color: white; }
.step-line { width: 80px; height: 2px; background: rgba(255,255,255,0.15); margin-bottom: 25px; }
.step-line.done { background: var(--primary); }

.checkout-page { max-width: 1200px; margin: 40px auto; padding: 0 20px; display: grid; grid-template-columns: 3fr 2fr; gap: 35px; align-items: start; }

/* === FORM BOX === */
.form-box { background: white; border-radius: 20px; box-shadow: 0 4px 30px rgba(0,0,0,0.05); padding: 35px; margin-bottom: 25px; }
.form-box-title { font-size: 1.3rem; font-weight: 800; color: var(--dark); margin-bottom: 25px; display: flex; align-items: center; gap: 10px; }
.form-group { margin-bottom: 22px; }
.form-group label { display: block; font-weight: 700; color: #444; margin-bottom: 8px; font-size: 0.9rem; }
.form-group label span.req { color: var(--primary); }
.form-input { width: 100%; padding: 14px 18px; border: 1.5px solid #e5e5e5; border-radius: 12px; font-family: inherit; font-size: 0.95rem; color: #1a1a2e; outline: none; transition: all 0.2s; box-sizing: border-box; }
.form-input:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(0,168,120,0.1); }
.form-input.error { border-color: #ef4444; background: #fff5f5; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
.field-error { color: #ef4444; font-size: 0.82rem; margin-top: 5px; font-weight: 600; }

/* === PAYMENT === */
.pay-option { display: flex; align-items: center; gap: 15px; padding: 18px 20px; border: 2px solid #e5e5e5; border-radius: 14px; cursor: pointer; transition: all 0.25s; margin-bottom: 12px; }
.pay-option:hover { border-color: #aaa; }
.pay-option:has(input:checked) { border-color: var(--primary); background: #f0fdf9; }
.pay-option input { width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer; }
.pay-icon-box { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0; }
.pay-info-title { font-weight: 700; font-size: 1rem; color: #1a1a2e; }
.pay-info-desc { font-size: 0.82rem; color: #888; margin-top: 3px; }

/* === ORDER SUMMARY === */
.order-box { background: white; border-radius: 20px; box-shadow: 0 4px 30px rgba(0,0,0,0.05); overflow: hidden; position: sticky; top: 90px; }
.order-box-header { background: var(--dark); padding: 22px 25px; }
.order-box-header h2 { font-size: 1.2rem; font-weight: 800; color: white; margin: 0; }
.order-items { padding: 20px 25px; }
.order-item { display: flex; justify-content: space-between; align-items: center; padding: 14px 0; border-bottom: 1px solid #f5f5f5; }
.order-item:last-child { border: none; }
.order-item-left { display: flex; align-items: center; gap: 14px; }
.order-item-img { width: 54px; height: 54px; background: #f4f4f4; border-radius: 10px; padding: 5px; }
.order-item-img img { width: 100%; height: 100%; object-fit: contain; }
.order-item-name { font-weight: 700; font-size: 0.92rem; color: #1a1a2e; margin-bottom: 3px; }
.order-item-qty { font-size: 0.82rem; color: #999; }
.order-item-price { font-weight: 800; color: var(--primary); font-size: 0.95rem; white-space: nowrap; }

.order-calc { padding: 0 25px 20px; }
.calc-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f5f5f5; }
.calc-row:last-child { border: none; }
.calc-label { color: #888; font-size: 0.9rem; }
.calc-value { font-weight: 700; font-size: 0.9rem; color: #1a1a2e; }
.calc-free { color: var(--primary); }

.order-total { padding: 20px 25px; background: #f8f9fa; border-top: 2px dashed #e5e5e5; display: flex; justify-content: space-between; align-items: center; }
.total-label { font-weight: 700; color: #555; }
.total-price { font-size: 2rem; font-weight: 900; color: var(--primary); }

.btn-place { display: block; width: 100%; padding: 20px; margin: 0; background: linear-gradient(135deg, #1a1a2e, #2d2d5e); color: white; font-family: inherit; font-size: 1.15rem; font-weight: 800; border: none; cursor: pointer; text-align: center; transition: all 0.3s; letter-spacing: 0.5px; }
.btn-place:hover { background: linear-gradient(135deg, var(--primary), #00c896); transform: none; box-shadow: 0 -4px 20px rgba(0,168,120,0.3); }
.btn-place:active { transform: scale(0.98); }
.order-secure { padding: 15px 25px; text-align: center; color: #bbb; font-size: 0.8rem; background: white; }

/* === GLOBAL ALERT === */
.page-error { background: #fee2e2; border: 1.5px solid #fca5a5; color: #dc2626; border-radius: 14px; padding: 16px 20px; font-weight: 600; margin-bottom: 25px; }

@media (max-width: 960px) {
    .checkout-page { grid-template-columns: 1fr; }
    .form-row { grid-template-columns: 1fr; }
    .step-line { width: 40px; }
    .order-box { position: static; }
}
</style>
@endpush

@section('content')

{{-- Progress Bar --}}
<div class="checkout-hero">
    <div class="checkout-steps">
        <div class="step">
            <div class="step-circle done">✓</div>
            <div class="step-label">Giỏ Hàng</div>
        </div>
        <div class="step-line done"></div>
        <div class="step">
            <div class="step-circle active">2</div>
            <div class="step-label active">Thanh Toán</div>
        </div>
        <div class="step-line"></div>
        <div class="step">
            <div class="step-circle todo">3</div>
            <div class="step-label">Hoàn Tất</div>
        </div>
    </div>
</div>

<form action="{{ route('coupon.apply') }}" method="POST" id="coupon-form">@csrf</form>
<form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
@csrf
<div class="checkout-page">

    {{-- === LEFT COLUMN === --}}
    <div>
        @if($errors->any())
        <div class="page-error">
            ❌ Vui lòng kiểm tra lại thông tin bên dưới:<br>
            <ul style="margin:8px 0 0 18px; font-size:0.9rem">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('error'))
        <div class="page-error">❌ {{ session('error') }}</div>
        @endif

        {{-- SHIPPING INFO --}}
        <div class="form-box">
            <div class="form-box-title">📍 Thông Tin Nhận Hàng</div>

            <div class="form-group">
                <label>Họ và Tên <span class="req">*</span></label>
                <input type="text" name="name" id="name" class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                    value="{{ old('name', $user->name ?? '') }}" placeholder="Nhập họ và tên đầy đủ" required>
                @error('name') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Số Điện Thoại <span class="req">*</span></label>
                    <input type="tel" name="phone" id="phone" class="form-input {{ $errors->has('phone') ? 'error' : '' }}"
                        value="{{ old('phone', $user->phone ?? '') }}" placeholder="0909 xxx xxx" required>
                    @error('phone') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label>Email <span class="req">*</span></label>
                    <input type="email" name="email" id="email" class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                        value="{{ old('email', $user->email ?? '') }}" placeholder="email@example.com" required>
                    @error('email') <div class="field-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label>Địa Chỉ Giao Hàng Chi Tiết <span class="req">*</span></label>
                <textarea name="address" id="address" class="form-input {{ $errors->has('address') ? 'error' : '' }}"
                    rows="3" placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố" required>{{ old('address', $user->address ?? '') }}</textarea>
                @error('address') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group" style="margin-bottom:0">
                <label>Ghi Chú Đơn Hàng</label>
                <textarea name="note" class="form-input" rows="2" placeholder="Ghi chú thêm cho người giao hàng (tuỳ chọn)...">{{ old('note') }}</textarea>
            </div>
        </div>

        {{-- PAYMENT METHODS --}}
        <div class="form-box">
            <div class="form-box-title">💳 Phương Thức Thanh Toán</div>

            <label class="pay-option">
                <input type="radio" name="payment_method" value="cod" {{ old('payment_method', 'cod') == 'cod' ? 'checked' : '' }}>
                <div class="pay-icon-box" style="background:#fff3ee">💵</div>
                <div>
                    <div class="pay-info-title">Thanh toán khi nhận hàng (COD)</div>
                    <div class="pay-info-desc">Trả tiền mặt khi nhận hàng, an toàn và tiện lợi.</div>
                </div>
            </label>


            <label class="pay-option">
                <input type="radio" name="payment_method" value="bank" {{ old('payment_method') == 'bank' ? 'checked' : '' }}>
                <div class="pay-icon-box" style="background:#e8f0ff">🏦</div>
                <div>
                    <div class="pay-info-title">Chuyển khoản ngân hàng</div>
                    <div class="pay-info-desc">Chuyển khoản trực tiếp – đơn hàng xác nhận sau 1-2 giờ.</div>
                </div>
            </label>

            @error('payment_method') <div class="field-error" style="margin-top:8px">{{ $message }}</div> @enderror
        </div>
    </div>

    {{-- === RIGHT COLUMN: ORDER SUMMARY === --}}
    <div>
        <div class="order-box">
            <div class="order-box-header">
                <h2>📦 Đơn Hàng Của Bạn</h2>
            </div>

            <div class="order-items">
                @php $total = 0 @endphp
                @foreach($cart as $id => $item)
                    @php $total += $item['price'] * $item['quantity'] @endphp
                    <div class="order-item">
                        <div class="order-item-left">
                            <div class="order-item-img">
                                <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}">
                            </div>
                            <div>
                                <div class="order-item-name">{{ $item['name'] }}</div>
                                <div class="order-item-qty">x{{ $item['quantity'] }}</div>
                            </div>
                        </div>
                        <div class="order-item-price">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}₫</div>
                    </div>
                @endforeach
            </div>

            <div style="padding: 15px 25px; border-bottom: 1px solid #f5f5f5">
                <div style="display:flex; gap:10px">
                    <input type="text" form="coupon-form" name="code" placeholder="Nhập mã giảm giá..." class="form-input" style="padding:10px 15px; border-radius:8px">
                    <button type="submit" form="coupon-form" class="btn-place" style="padding:10px 20px; border-radius:8px; width:auto; font-size:0.95rem">Áp dụng</button>
                </div>
                @if(session('success') && session('coupon'))
                    <div style="color:var(--primary); font-size:0.85rem; font-weight:700; margin-top:8px">🎉 {{ session('success') }}</div>
                @endif
            </div>

            <div class="order-calc">
                <div class="calc-row">
                    <span class="calc-label">Tạm tính</span>
                    <span class="calc-value">{{ number_format($total, 0, ',', '.') }}₫</span>
                </div>
                <div class="calc-row">
                    <span class="calc-label">Phí vận chuyển</span>
                    <span class="calc-free">🚚 Miễn phí</span>
                </div>
                @if(session('coupon'))
                <div class="calc-row">
                    <span class="calc-label">Mã giảm giá ({{ session('coupon')['code'] }})</span>
                    <span class="calc-value" style="color:#ef4444">{{ session('coupon')['label'] }}</span>
                </div>
                @php $total = max(0, $total - session('coupon')['discount']) @endphp
                @endif
            </div>

            <div class="order-total">
                <span class="total-label">Tổng Thanh Toán</span>
                <span class="total-price">{{ number_format($total, 0, ',', '.') }}₫</span>
            </div>

            <button type="submit" class="btn-place" id="placeOrderBtn">
                🔒 Hoàn Tất Đặt Hàng
            </button>
            <div class="order-secure">
                Bằng việc đặt hàng, bạn đồng ý với <strong>Điều Khoản Sử Dụng</strong> của CrocsVN.<br>
                🔐 Thông tin của bạn được mã hóa SSL 256-bit.
            </div>
        </div>

        <div style="margin-top: 20px; background: white; border-radius: 16px; padding: 20px 25px; box-shadow: 0 4px 30px rgba(0,0,0,0.04);">
            <div style="font-weight: 700; color: #555; margin-bottom: 12px; font-size: 0.9rem;">🌟 CAM KẾT CỦA CHÚNG TÔI</div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <div style="display:flex; gap:10px; align-items:center; font-size:0.88rem; color:#666"><span>✅</span> Hàng chính hãng 100%</div>
                <div style="display:flex; gap:10px; align-items:center; font-size:0.88rem; color:#666"><span>🚚</span> Giao hàng toàn quốc 2-5 ngày</div>
                <div style="display:flex; gap:10px; align-items:center; font-size:0.88rem; color:#666"><span>🔄</span> Đổi trả miễn phí trong 30 ngày</div>
                <div style="display:flex; gap:10px; align-items:center; font-size:0.88rem; color:#666"><span>📞</span> Hỗ trợ 24/7: 0909 999 888</div>
            </div>
        </div>
    </div>

</div>
</form>
@endsection

@push('js')
<script>
// Prevent double submit
document.getElementById('checkout-form').addEventListener('submit', function() {
    const btn = document.getElementById('placeOrderBtn');
    btn.disabled = true;
    btn.textContent = '⏳ Đang xử lý đơn hàng...';
    btn.style.background = '#888';
});
</script>
@endpush
