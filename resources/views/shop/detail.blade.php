@extends('layouts.app')

@section('title', $product->name . ' – CrocsVN')

@push('css')
<style>
.detail-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
.breadcrumb { font-size: 0.9rem; color: #666; margin-bottom: 2rem; }
.breadcrumb a { color: var(--primary); text-decoration: none; font-weight: 500; }
.product-hero { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; }
.product-gallery { display: flex; flex-direction: column; gap: 15px; }
.main-img-box { background: #f8f9fa; border-radius: 20px; padding: 40px; text-align: center; position: relative; }
.main-img-box img { max-width: 100%; height: auto; transform: scale(1.1); transition: transform 0.5s; }
.main-img-box:hover img { transform: scale(1.15) translateY(-10px); }
.thumb-list { display: flex; gap: 10px; }
.thumb-box { width: 80px; height: 80px; background: #f8f9fa; border-radius: 12px; padding: 10px; cursor: pointer; border: 2px solid transparent; }
.thumb-box.active, .thumb-box:hover { border-color: var(--primary); }
.thumb-box img { width: 100%; height: 100%; object-fit: contain; }

.product-meta h1 { font-size: 2.2rem; font-weight: 800; color: #1a1a2e; margin-bottom: 10px; }
.price-block { font-size: 2rem; color: var(--primary); font-weight: 800; margin: 20px 0; display:flex; align-items:center; gap:15px; }
.price-block .old { font-size: 1.2rem; color: #999; text-decoration: line-through; font-weight: 500; }

.option-group { margin-bottom: 1.5rem; }
.option-title { font-weight: 700; margin-bottom: 10px; display: block; }
.size-grid { display: flex; flex-wrap: wrap; gap: 10px; }
.size-btn { padding: 10px 20px; border: 1px solid #ddd; border-radius: 8px; background: white; cursor: pointer; font-family: inherit; font-weight: 600; transition: all 0.2s; }
.size-btn:hover, .size-btn.active { background: var(--dark); color: white; border-color: var(--dark); }

.action-btns { display: flex; gap: 15px; margin-top: 30px; }
.btn-large-cart { flex: 1; padding: 18px; border-radius: 50px; background: var(--primary); color: white; font-size: 1.1rem; font-weight: 700; border: none; cursor: pointer; box-shadow: 0 10px 20px rgba(0,168,120,0.3); transition: transform 0.3s;}
.btn-large-cart:hover { transform: translateY(-3px); }
.btn-wishlist { width: 60px; height: 60px; border-radius: 50%; background: #f1f1f1; border: none; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; cursor: pointer; transition: background 0.3s;}
.btn-wishlist:hover { background: #ffe6e6; }

.product-content { margin-top: 60px; padding-top: 40px; border-top: 1px solid #eee; line-height: 1.8; color: #555; }
.product-content h2 { margin-bottom: 1rem; color: #1a1a2e; }

@media (max-width: 768px) {
    .product-hero { grid-template-columns: 1fr; gap: 2rem; }
}
</style>
@endpush

@section('content')
<div class="detail-container">
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Trang Chủ</a> / 
        <a href="{{ route('shop') }}">Sản Phẩm</a> / 
        <span>{{ $product->name }}</span>
    </div>

    <div class="product-hero">
        <!-- Gallery -->
        <div class="product-gallery">
            <div class="main-img-box">
                @if($product->discount_price)
                    <span class="product-badge badge-sale" style="position:absolute; top:20px; left:20px; z-index:2">🏷️ Giảm Giá</span>
                @endif
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" id="mainImg">
            </div>
            @if($product->image_list)
            <div class="thumb-list">
                @foreach(json_decode($product->image_list) as $img)
                <div class="thumb-box active">
                    <img src="{{ asset($img) }}" alt="thumb" onclick="document.getElementById('mainImg').src=this.src">
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Info -->
        <div class="product-meta">
            <h1>{{ $product->name }}</h1>
            <div style="color: #666; display:flex; gap:15px; align-items:center">
                <span style="color:#FFD700; font-size:1.2rem">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= round($avgRating)) ★ @else ☆ @endif
                    @endfor
                </span>
                <span>({{ $avgRating }} / 5) - {{ $reviews->count() }} Đánh giá</span>
                <span style="color:var(--primary); font-weight:600">✓ Còn {{ $product->stock }} hàng</span>
            </div>

            @if($product->discount_price && $product->flash_sale_end && $product->flash_sale_end->isFuture())
            <div style="margin-top:20px; padding:15px; border-radius:12px; border:2px solid #ef4444; background:linear-gradient(90deg, #fffafa, #fff2f2); position:relative; overflow:hidden">
                <div style="position:absolute; top:0; right:0; background:#ef4444; color:white; font-size:0.8rem; font-weight:800; padding:5px 15px; border-bottom-left-radius:12px;">⚡ FLASH SALE</div>
                <div style="font-weight:700; color:#ef4444; font-size:1.1rem; margin-bottom:10px; display:flex; align-items:center; gap:10px">
                    <span>Kết thúc sau:</span>
                    <span id="countdown" style="background:#ef4444; color:white; padding:4px 10px; border-radius:6px; letter-spacing:1px; font-variant-numeric: tabular-nums">--:--:--</span>
                </div>
                
                <div class="price-block" style="margin:5px 0 0 0">
                    {{ number_format($product->discount_price, 0, ',', '.') }}₫
                    <span class="old">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                    <span style="font-size:0.95rem; background:#fee2e2; color:#dc2626; padding:4px 10px; border-radius:50px; font-weight:800; border:1px dashed #f87171">Tiết kiệm {{ number_format($product->price - $product->discount_price, 0, ',', '.') }}₫</span>
                </div>
                
                <div style="margin-top:10px; color:#1a1a2e; font-size:0.95rem; font-weight:600; display:flex; align-items:center; gap:8px">
                    <span class="pulse-dot" style="width:10px; height:10px; background:var(--primary); border-radius:50%; display:inline-block"></span>
                    🔥 Đang có <span id="fakeViewCount" style="color:var(--primary); font-weight:800; font-size:1.1rem">24</span> người đang xem sản phẩm này! Mua ngay kẻo hết.
                </div>
                <style>
                    @keyframes pulse { 0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(0, 168, 120, 0.7); } 70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(0, 168, 120, 0); } 100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(0, 168, 120, 0); } }
                    .pulse-dot { animation: pulse 2s infinite; }
                    .btn-large-buy { flex: 1; padding: 18px; border-radius: 50px; background: #ef4444; color: white; font-size: 1.1rem; font-weight: 800; border: none; cursor: pointer; box-shadow: 0 10px 20px rgba(239,68,68,0.3); transition: transform 0.3s;}
                    .btn-large-buy:hover { transform: translateY(-3px); background: #dc2626; }
                </style>
            </div>
            @else
            <div class="price-block" style="margin-top:20px;">
                {{ number_format($product->discount_price ?? $product->price, 0, ',', '.') }}₫
                @if($product->discount_price)
                <span class="old">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                <span style="font-size:0.95rem; background:#fee2e2; color:#dc2626; padding:4px 10px; border-radius:50px; font-weight:800; margin-left:10px; vertical-align:middle; display:inline-block">-{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%</span>
                @endif
                <style>
                    .btn-large-buy { flex: 1; padding: 18px; border-radius: 50px; background: #ef4444; color: white; font-size: 1.1rem; font-weight: 800; border: none; cursor: pointer; box-shadow: 0 10px 20px rgba(239,68,68,0.3); transition: transform 0.3s;}
                    .btn-large-buy:hover { transform: translateY(-3px); background: #dc2626; }
                </style>
            </div>
            @endif

            <p style="font-size:1.05rem; color:#666; margin-bottom:25px; margin-top:20px">{{ $product->description }}</p>

            <div class="option-group">
                <span class="option-title">Chọn Size (Bảng size US)</span>
                <div class="size-grid">
                    <button class="size-btn">M4 / W6</button>
                    <button class="size-btn active">M5 / W7</button>
                    <button class="size-btn">M6 / W8</button>
                    <button class="size-btn">M7 / W9</button>
                    <button class="size-btn">M8 / W10</button>
                </div>
                <a href="#size-guide" style="font-size:0.85rem; color:var(--primary); text-decoration:none; display:inline-block; margin-top:8px">📏 Xem hướng dẫn chọn size</a>
            </div>

            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div style="display:flex; gap:15px; margin-bottom:15px; align-items:center">
                    <span style="font-weight:700">Số lượng:</span>
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" style="width:70px; padding:10px; border-radius:12px; border:2px solid #eee; text-align:center; font-family:inherit; font-weight:bold; font-size:1.1rem; outline:none">
                </div>
                
                <div class="action-btns">
                    <button type="submit" class="btn-large-cart" style="flex:0.8; font-size:1rem; padding:15px">🛒 Thêm Giỏ</button>
                    <button type="submit" formaction="{{ route('checkout.fast') }}" class="btn-large-buy">🚀 MUA BẤT CHẤP</button>
                    @php
                        $isWish = false;
                        if(Auth::check()) {
                            $isWish = \App\Models\Wishlist::where('user_id', Auth::id())->where('product_id', $product->id)->exists();
                        }
                    @endphp
                    <button type="button" class="btn-wishlist" id="wishlistBtn" onclick="toggleWish({{ $product->id }})" style="color: {{ $isWish ? '#ef4444' : 'inherit' }}">
                        {!! $isWish ? '❤️' : '🤍' !!}
                    </button>
                </div>
            </form>
            
            @push('js')
            <script>
            // Flash Sale Countdown Logic
            function startCountdown() {
                var countdownEl = document.getElementById('countdown');
                if (!countdownEl) return; // Nếu form Flash sale ẩn thì dừng
                
                @if($product->flash_sale_end && $product->flash_sale_end->isFuture())
                var eod = new Date("{{ $product->flash_sale_end->format('Y-m-d\TH:i:s') }}");
                
                setInterval(function() {
                    var current = new Date();
                    var diff = Math.floor((eod - current) / 1000);
                    if(diff <= 0) {
                        countdownEl.innerText = "00:00:00";
                        location.reload(); // Tự load lại trang khi hết giờ Sale
                        return;
                    }
                    
                    var h = Math.floor(diff / 3600);
                    var m = Math.floor((diff % 3600) / 60);
                    var s = diff % 60;
                    countdownEl.innerText = 
                        (h < 10 ? "0"+h : h) + ":" + 
                        (m < 10 ? "0"+m : m) + ":" + 
                        (s < 10 ? "0"+s : s);
                }, 1000);
                @endif
            }
            // Fake Viewers Logic
            function startFakeViewers() {
                var el = document.getElementById('fakeViewCount');
                var count = Math.floor(Math.random() * (45 - 15 + 1)) + 15;
                el.innerText = count;
                
                setInterval(function() {
                    var diff = Math.random() > 0.5 ? 1 : -1;
                    var jump = Math.floor(Math.random() * 3) + 1;
                    count = count + (diff * jump);
                    if(count < 10) count = 12;
                    if(count > 80) count = 75;
                    el.innerText = count;
                }, 5000);
            }
            
            document.addEventListener("DOMContentLoaded", function() {
                startCountdown();
                startFakeViewers();
            });

            async function toggleWish(productId) {
                @if(!Auth::check())
                alert('Vui lòng đăng nhập để sử dụng tính năng này!');
                window.location.href = "{{ route('login') }}";
                return;
                @endif
                
                try {
                    const res = await fetch("{{ route('wishlist.toggle') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ product_id: productId })
                    });
                    const data = await res.json();
                    
                    const btn = document.getElementById('wishlistBtn');
                    if (data.status === 'added') {
                        btn.innerHTML = '❤️';
                        btn.style.color = '#ef4444';
                    } else {
                        btn.innerHTML = '🤍';
                        btn.style.color = 'inherit';
                    }
                } catch(e) {
                    console.error("Lỗi:", e);
                }
            }
            </script>
            @endpush
            
            <div style="margin-top:30px; padding:20px; background:#f8f9fa; border-radius:12px">
                <div style="font-weight:600; margin-bottom:10px">✅ Đặc quyền khi mua sắm:</div>
                <ul style="font-size:0.9rem; color:#666; padding-left:20px; line-height:1.6">
                    <li>Miễn phí giao hàng toàn quốc.</li>
                    <li>Đổi trả miễn phí 30 ngày (nếu không vừa size).</li>
                    <li>Bảo hành keo nhiệt trọn đời chân.</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="product-content">
        <h2>Thông Tin Chi Tiết</h2>
        <div>
            {!! nl2br(e($product->content)) !!}
        </div>
    </div>

    <!-- Reviews Section -->
    <div style="margin-top: 60px; padding-top: 40px; border-top: 2px solid #eee;">
        <h2 style="font-size:1.8rem; margin-bottom: 20px;">Đánh Giá Sản Phẩm</h2>

        @if(session('success'))
            <div style="padding:15px; background:#d1e7dd; color:#0f5132; border-radius:10px; margin-bottom:20px; font-weight:600">
                ✅ {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div style="padding:15px; background:#f8d7da; color:#842029; border-radius:10px; margin-bottom:20px; font-weight:600">
                ❌ {{ session('error') }}
            </div>
        @endif

        <div style="display:grid; grid-template-columns: 1fr 2fr; gap: 40px;">
            <!-- Cột trái: Form Đánh giá -->
            <div style="background:#f8f9fa; padding:25px; border-radius:16px;">
                <h3 style="font-size:1.2rem; margin-bottom:15px;">Gửi đánh giá của bạn</h3>
                @if($canReview)
                    <form action="{{ route('review.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div style="margin-bottom:15px;">
                            <label style="display:block; font-weight:600; margin-bottom:8px">Đánh giá sao:</label>
                            <div style="display:flex; gap:10px; font-size:1.5rem; flex-direction:row-reverse; justify-content:flex-end;" class="star-rating">
                                <input type="radio" name="rating" id="star5" value="5" checked style="display:none"><label for="star5" style="cursor:pointer; color:#FFD700">★</label>
                                <input type="radio" name="rating" id="star4" value="4" style="display:none"><label for="star4" style="cursor:pointer; color:#FFD700">★</label>
                                <input type="radio" name="rating" id="star3" value="3" style="display:none"><label for="star3" style="cursor:pointer; color:#FFD700">★</label>
                                <input type="radio" name="rating" id="star2" value="2" style="display:none"><label for="star2" style="cursor:pointer; color:#FFD700">★</label>
                                <input type="radio" name="rating" id="star1" value="1" style="display:none"><label for="star1" style="cursor:pointer; color:#FFD700">★</label>
                            </div>
                            <style>
                                .star-rating label:hover, .star-rating label:hover ~ label { color: #FF8C00 !important; }
                                .star-rating input:checked ~ label { color: #ccc !important; }
                                .star-rating input:checked + label, .star-rating input:checked + label ~ label { color: #FFD700 !important; }
                            </style>
                        </div>
                        
                        <div style="margin-bottom:15px;">
                            <textarea name="comment" rows="4" placeholder="Chia sẻ cảm nhận của bạn về sản phẩm này..." style="width:100%; border-radius:10px; border:1px solid #ddd; padding:12px; outline:none; font-family:inherit; box-sizing:border-box" required></textarea>
                        </div>
                        
                        <button type="submit" style="background:var(--primary); color:white; border:none; padding:12px 20px; border-radius:8px; font-weight:bold; cursor:pointer; width:100%">Gửi Đánh Giá</button>
                    </form>
                @else
                    <div style="text-align:center; padding:20px 0; color:#666">
                        <div style="font-size:2rem; margin-bottom:10px">🔒</div>
                        <p style="font-size:0.95rem; font-weight:600">Bạn chưa mua sản phẩm này?</p>
                        <p style="font-size:0.85rem">Chỉ khách hàng đã nhận hàng thành công mới có quyền đánh giá hệ thống.</p>
                    </div>
                @endif
            </div>

            <!-- Cột phải: List Đánh giá -->
            <div>
                @forelse($reviews as $rev)
                <div style="border-bottom:1px solid #eee; padding-bottom:20px; margin-bottom:20px">
                    <div style="display:flex; justify-content:space-between; align-items:start">
                        <div style="display:flex; gap:12px; align-items:center">
                            <div style="width:40px; height:40px; background:var(--dark); color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold">
                                {{ strtoupper(substr($rev->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:bold; color:#1a1a2e; font-size:1rem">{{ $rev->user->name }}</div>
                                <div style="display:flex; align-items:center; gap:8px">
                                    <span style="color:#FFD700; font-size:0.9rem">
                                        @for($i=1; $i<=5; $i++)
                                            @if($i <= $rev->rating) ★ @else ☆ @endif
                                        @endfor
                                    </span>
                                    <span style="font-size:0.75rem; background:#ecfdf5; color:#059669; padding:2px 8px; border-radius:4px; font-weight:600">✓ Đã Mua Hàng</span>
                                </div>
                            </div>
                        </div>
                        <div style="font-size:0.8rem; color:#888">{{ $rev->created_at->diffForHumans() }}</div>
                    </div>
                    <div style="margin-top:12px; color:#444; line-height:1.6; font-size:0.95rem">
                        {{ $rev->comment }}
                    </div>
                </div>
                @empty
                    <div style="text-align:center; padding:40px; color:#aaa">
                        Chưa có đánh giá nào cho sản phẩm này. Trở thành người đầu tiên đánh giá!
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div style="margin-top:80px">
         <h2 style="font-size:2rem; text-align:center; margin-bottom:40px">Có Thể Bạn Sẽ Thích</h2>
         <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:20px">
             @foreach($relatedProducts as $rel)
             <div class="product-card" style="margin:0">
                 <div class="product-img-wrap">
                    <img src="{{ asset($rel->image) }}" loading="lazy">
                 </div>
                 <div class="product-info">
                    <div class="product-name">
                        <a href="{{ route('product.detail', $rel->slug) }}" style="text-decoration:none; color:inherit;">{{ $rel->name }}</a>
                    </div>
                 </div>
             </div>
             @endforeach
         </div>
    </div>
    @endif
</div>
@endsection
