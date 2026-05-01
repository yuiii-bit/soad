@extends('layouts.app')

@section('title', 'Giỏ Hàng & Thanh Toán – CrocsVN')

@push('css')
<link href="https://fonts.googleapis.com/css2?family=Pangolin&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
/* ===== SỬA LẠI THEO STYLE TỪ ẢNH ASM 2 ===== */
.cart-page-wrapper {
    font-family: 'Quicksand', sans-serif;
    color: #2F4F4F;
    background-color: #F8F9FA;
    padding: 60px 20px;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.cart-main-container {
    width: 100%;
    max-width: 1050px;
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    background: #fff;
    display: flex;
    box-shadow: 0 10px 40px rgba(0,0,0,0.04);
    overflow: hidden;
}

/* --- Left Panel --- */
.cart-left-panel {
    flex: 6;
    padding: 40px 50px;
    position: relative;
    border-right: 1px solid #EEF2F6;
}

/* Stepper */
.custom-stepper {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin-bottom: 50px;
}
.stepper-line {
    position: absolute;
    top: 15px; 
    left: 20px;
    right: 20px;
    height: 2px;
    background-color: #E2E8F0;
    z-index: 1;
}
.stepper-step {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
    background: #fff;
    padding: 0 10px;
}
.step-dot {
    width: 32px;
    height: 32px;
    border: 2px solid #E2E8F0;
    border-radius: 50%;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    font-weight: 700;
    color: #94A3B8;
    margin-bottom: 10px;
}
.stepper-step.active .step-dot {
    border-color: #1A4D2E;
    background: #1A4D2E;
    color: #fff;
}
.step-title {
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #64748B;
    letter-spacing: 0.5px;
}
.stepper-step.active .step-title {
    color: #1A4D2E;
}

/* Product Section */
.section-headline {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 25px;
    font-family: inherit;
    color: #1E293B;
}

.product-item-box {
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 15px 20px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: 0.3s;
}
.product-item-box:hover {
    border-color: #CBD5E1;
    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
}
.prod-img-wrap {
    width: 70px;
    height: 70px;
    background: #F8F9FA;
    border-radius: 8px;
    padding: 5px;
}
.prod-img-wrap img { width: 100%; height: 100%; object-fit: contain; }

.prod-details {
    flex: 1;
}
.prod-name {
    font-weight: 700;
    font-size: 1.05rem;
    color: #1E293B;
    margin-bottom: 5px;
}
.prod-meta {
    font-size: 0.9rem;
    color: #64748B;
}
.prod-price {
    font-weight: 700;
    color: #1A4D2E;
}

.qty-control {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #F1F5F9;
    padding: 5px;
    border-radius: 50px;
}
.qty-btn {
    width: 25px; height: 25px;
    border-radius: 50%;
    border: none;
    background: #fff;
    cursor: pointer;
    font-weight: 700;
    color: #1E293B;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}
.qty-input {
    width: 25px;
    border: none;
    background: transparent;
    text-align: center;
    font-weight: 700;
    font-size: 1rem;
    pointer-events: none;
}

/* Shipping / Extras Box */
.extra-options-wrapper { margin-top: 40px; }
.delivery-options { display: flex; gap: 15px; }
.delivery-card {
    flex: 1;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 15px;
    text-align: center;
    cursor: pointer;
    transition: 0.3s;
}
.delivery-card.active {
    border-color: #1A4D2E;
    background: #f0fdf4;
}
.delivery-icon {
    font-size: 1.8rem; margin-bottom: 8px;
}
.delivery-name { font-weight: 700; font-size: 0.95rem; }
.delivery-desc { font-size: 0.8rem; color: #64748B; margin-top: 5px; }


/* --- Right Panel (Form Checkout) --- */
.cart-right-panel {
    flex: 4;
    padding: 40px 40px;
    background: #FAFAFA;
    display: flex;
    flex-direction: column;
}
.checkout-form-title {
    text-align: center;
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 30px;
    color: #1A4D2E;
    font-family: 'Quicksand', sans-serif;
}

.custom-input-group {
    position: relative;
    margin-bottom: 20px;
}
.custom-input {
    width: 100%;
    padding: 14px 15px;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    font-family: 'Quicksand', sans-serif;
    font-size: 0.95rem;
    background: #fff;
    outline: none;
    transition: 0.3s;
    box-sizing: border-box;
}
.custom-input:focus { border-color: #1A4D2E; box-shadow: 0 0 0 3px rgba(26, 77, 46, 0.1); }

.voucher-group {
    display: flex;
    gap: 10px;
    margin-bottom: 30px;
}
.voucher-input {
    flex: 1;
    padding: 12px 15px;
    border: 1px dashed #94A3B8;
    border-radius: 12px;
    font-family: inherit;
    outline: none;
    background: #f8fafc;
}
.btn-apply-voucher {
    background: #1A4D2E;
    color: white;
    font-weight: 600;
    border: none;
    border-radius: 12px;
    padding: 0 20px;
    cursor: pointer;
}

/* Order Summary */
.summary-box {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    margin-bottom: 30px;
}
.summary-row {
    display: flex;
    justify-content: space-between;
    font-size: 0.95rem;
    margin-bottom: 12px;
    color: #64748B;
}
.summary-row.final-total {
    border-top: 1px dashed #E2E8F0;
    padding-top: 15px;
    margin-top: 5px;
    font-weight: 800;
    font-size: 1.2rem;
    color: #1A4D2E;
}

.btn-confirm-order {
    width: 100%;
    padding: 16px;
    background: #1A4D2E;
    color: white;
    font-size: 1.1rem;
    font-weight: 700;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-family: 'Quicksand', sans-serif;
    transition: 0.3s;
    box-shadow: 0 4px 15px rgba(26, 77, 46, 0.2);
}
.btn-confirm-order:hover {
    background: #133c23;
    transform: translateY(-2px);
}

.secure-icons {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 25px;
    color: #94A3B8;
    font-size: 1.2rem;
}

@media (max-width: 900px) {
    .cart-main-container { flex-direction: column; }
    .cart-left-panel { border-right: none; border-bottom: 1px solid #EEF2F6; }
}
</style>
@endpush

@section('content')
<div class="cart-page-wrapper">
    <!-- Main Box -->
    <form action="{{ route('checkout.process') }}" method="POST" class="cart-main-container">
        @csrf
        
        <!-- ================= LẼFT PANEL: GIỎ HÀNG ================= -->
        <div class="cart-left-panel">
            
            <!-- Process Stepper -->
            <div class="custom-stepper">
                <div class="stepper-line"></div>
                <div class="stepper-step active">
                    <div class="step-dot">1</div>
                    <span class="step-title">Giỏ Hàng</span>
                </div>
                <div class="stepper-step active">
                    <div class="step-dot">2</div>
                    <span class="step-title">Thông Tin</span>
                </div>
                <div class="stepper-step">
                    <div class="step-dot">3</div>
                    <span class="step-title">Giao Hàng</span>
                </div>
                <div class="stepper-step">
                    <div class="step-dot">4</div>
                    <span class="step-title">Hoàn Tất</span>
                </div>
            </div>

            <!-- Products List -->
            <div class="section-headline">Danh Sách Sản Phẩm</div>
            
            @php $total = 0 @endphp
            @if(isset($cart) && count($cart) > 0)
                @foreach($cart as $id => $item)
                    @php $total += $item['price'] * $item['quantity'] @endphp
                    <div class="product-item-box">
                        <div class="prod-img-wrap">
                            <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}">
                        </div>
                        <div class="prod-details">
                            <div class="prod-name">{{ $item['name'] }}</div>
                            <div class="prod-meta">{{ number_format($item['price'], 0, ',', '.') }} ₫ • Size: Mặc định</div>
                        </div>
                        <div class="qty-control">
                            <button type="button" class="qty-btn">-</button>
                            <input type="text" value="{{ $item['quantity'] }}" class="qty-input">
                            <button type="button" class="qty-btn">+</button>
                        </div>
                        <div class="prod-price">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} ₫</div>
                    </div>
                @endforeach
            @else
                <div style="text-align:center; padding: 40px; color:#94A3B8">
                    <div style="font-size:3rem; margin-bottom:15px">🛒</div>
                    <p>Giỏ hàng của bạn đang trống.</p>
                    <a href="{{ route('shop') }}" style="color:#1A4D2E; font-weight:700">Đi mua sắm ngay</a>
                </div>
            @endif

            <!-- Delivery Options -->
            @if(count($cart) > 0)
            <div class="extra-options-wrapper">
                <div class="section-headline">Phương thức Giao Hàng</div>
                <div class="delivery-options">
                    <label class="delivery-card active">
                        <input type="radio" name="delivery_method" value="standard" checked style="display:none">
                        <div class="delivery-icon">🚚</div>
                        <div class="delivery-name">Giao Tiêu Chuẩn</div>
                        <div class="delivery-desc">Miễn phí • 2 - 3 ngày làm việc</div>
                    </label>
                    <label class="delivery-card">
                        <input type="radio" name="delivery_method" value="express" style="display:none">
                        <div class="delivery-icon">⚡</div>
                        <div class="delivery-name">Giao Hoả Tốc</div>
                        <div class="delivery-desc">30.000 ₫ • Nhận trong 2 giờ</div>
                    </label>
                </div>
            </div>
            @endif

        </div>

        <!-- ================= RIGHT PANEL: THÔNG TIN BUYER ================= -->
        <div class="cart-right-panel">
            <div class="checkout-form-title">Thông Tin Đặt Hàng</div>

            <!-- Checkout Form Fields -->
            @php $user = Auth::user(); @endphp
            <div class="custom-input-group">
                <input type="text" name="name" class="custom-input" placeholder="Họ và tên của bạn *" value="{{ $user->name ?? '' }}" required {{ empty($cart) ? 'disabled' : '' }}>
            </div>
            
            <div class="custom-input-group">
                <input type="tel" name="phone" class="custom-input" placeholder="Số điện thoại liên hệ *" value="{{ $user->phone ?? '' }}" required {{ empty($cart) ? 'disabled' : '' }}>
            </div>

            <div class="custom-input-group">
                <input type="email" name="email" class="custom-input" placeholder="Email của bạn *" value="{{ $user->email ?? '' }}" required {{ empty($cart) ? 'disabled' : '' }}>
            </div>

            <div class="custom-input-group" style="margin-bottom: 30px">
                <input type="text" name="address" class="custom-input" placeholder="Địa chỉ giao hàng chi tiết *" value="{{ $user->address ?? '' }}" required {{ empty($cart) ? 'disabled' : '' }}>
            </div>

            {{-- Payment method mặc định COD (có thể mở rộng sau) --}}
            <input type="hidden" name="payment_method" value="cod">

            <div class="voucher-group">
                <input type="text" class="voucher-input" placeholder="Mã ưu đãi / Voucher" {{ empty($cart) ? 'disabled' : '' }}>
                <button type="button" class="btn-apply-voucher" {{ empty($cart) ? 'disabled' : '' }}>Áp dụng</button>
            </div>

            <!-- Order Summary -->
            <div class="summary-box">
                <div class="summary-row">
                    <span>Tổng tiền hàng:</span>
                    <span style="font-weight:600; color:#1E293B">{{ number_format($total, 0, ',', '.') }} ₫</span>
                </div>
                <div class="summary-row">
                    <span>Phí vận chuyển:</span>
                    <span>Miễn phí</span>
                </div>
                <div class="summary-row">
                    <span>Ưu đãi áp dụng:</span>
                    <span style="color:#1A4D2E">-0 ₫</span>
                </div>
                <div class="summary-row final-total">
                    <span>TỔNG CỘNG:</span>
                    <span>{{ number_format($total, 0, ',', '.') }} ₫</span>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-confirm-order" {{ empty($cart) ? 'disabled' : '' }}>XÁC NHẬN ĐẶT HÀNG</button>

            <div class="secure-icons">
                <span title="Thanh toán an toàn">🔒</span>
                <span title="Hỗ trợ 24/7">🎧</span>
                <span title="Giao hàng toàn quốc">📦</span>
                <span title="Đổi trả miễn phí">🔄</span>
            </div>
        </div>

    </form>
</div>

<script>
    // Logic đơn giản để chọn Phương thức giao hàng đổi màu sắc hiển thị
    document.querySelectorAll('.delivery-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.delivery-card').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            this.querySelector('input').checked = true;
        });
    });
</script>
@endsection
