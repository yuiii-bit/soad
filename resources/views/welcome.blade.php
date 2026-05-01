@extends('layouts.app')

@section('content')
<!-- ========== HERO ========== -->
<section class="hero" id="home">
  <div class="hero-container">
    <div class="hero-content">
      <div class="hero-badge">Bộ sưu tập mới 2025</div>
      <h1 class="hero-title">
        Bước Đi Thoải Mái<br>
        <span class="highlight">Phong Cách Riêng</span><br>
        Cùng Crocs
      </h1>
      <p class="hero-desc">Khám phá bộ sưu tập dép Crocs chính hãng đa dạng màu sắc, kiểu dáng. Êm chân, bền đẹp — dành cho mọi lứa tuổi và phong cách sống.</p>
      <div class="hero-btns">
        <a href="#products" class="btn-primary">🛍️ Mua Ngay</a>
        <a href="#categories" class="btn-secondary">📦 Xem Danh Mục</a>
      </div>
      <div class="hero-stats">
        <div class="stat"><div class="stat-num">50K+</div><div class="stat-label">Khách hàng</div></div>
        <div class="stat"><div class="stat-num">200+</div><div class="stat-label">Mẫu sản phẩm</div></div>
        <div class="stat"><div class="stat-num">4.9⭐</div><div class="stat-label">Đánh giá</div></div>
      </div>
    </div>
    <div class="hero-image-wrap">
      <div class="hero-img-bg">
        <img src="{{ asset('images/hero.png') }}" alt="Crocs Collection" loading="eager">
      </div>
      <div class="hero-float-card card1">
        <div class="card-icon" style="background:#e8f8f3">🚚</div>
        <div>
          <div style="font-weight:700;font-size:0.85rem;color:#1a1a2e">Miễn phí ship</div>
          <div style="font-size:0.75rem;color:#666">Đơn từ 500K</div>
        </div>
      </div>
      <div class="hero-float-card card2">
        <div class="card-icon" style="background:#fff3ee">✅</div>
        <div>
          <div style="font-weight:700;font-size:0.85rem;color:#1a1a2e">Chính hãng 100%</div>
          <div style="font-size:0.75rem;color:#666">Bảo hành 1 năm</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========== MARQUEE ========== -->
<div class="marquee-section">
  <div class="marquee-track" id="marquee">
    <span class="marquee-item">🐊 Crocs Classic Clog</span>
    <span class="marquee-item">🌈 100+ Màu Sắc</span>
    <span class="marquee-item">🚚 Miễn Phí Ship</span>
    <span class="marquee-item">✅ Chính Hãng 100%</span>
    <span class="marquee-item">🎁 Quà Tặng Jibbitz</span>
    <span class="marquee-item">⭐ Đánh Giá 4.9/5</span>
    <span class="marquee-item">🔄 Đổi Trả 30 Ngày</span>
    <span class="marquee-item">💳 Thanh Toán Linh Hoạt</span>
  </div>
</div>

<!-- ========== CATEGORIES ========== -->
<section class="categories" id="categories">
  <div class="section-header">
    <div class="section-tag">Danh Mục</div>
    <h2 class="section-title">Khám Phá Theo Kiểu Dáng</h2>
    <p class="section-desc">Từ dép clog cổ điển đến platform trendy — luôn có một đôi Crocs hoàn hảo cho bạn</p>
  </div>
  <div class="cat-grid">
    @php
       $colors = ['green', 'orange', 'purple', 'yellow'];
       $icons = ['🥿', '👡', '🩴', '🌸'];
    @endphp
    @foreach($categories as $index => $cat)
    <a href="#products" class="cat-card {{ $colors[$index % count($colors)] }}">
      <span class="cat-icon">{{ $icons[$index % count($icons)] }}</span>
      <div class="cat-title">{{ $cat->name }}</div>
      <div class="cat-count">{{ rand(15, 60) }} sản phẩm</div>
    </a>
    @endforeach
  </div>
</section>

<!-- ========== PRODUCTS ========== -->
<section class="products" id="products">
  <div class="section-header">
    <div class="section-tag">Sản Phẩm Nổi Bật</div>
    <h2 class="section-title">Bán Chạy Nhất Tuần Này</h2>
    <p class="section-desc">Những đôi dép được yêu thích nhất — đừng bỏ lỡ!</p>
  </div>

  <div class="filter-tabs">
    <button class="tab active" data-filter="all" onclick="filterProducts('all',this)">Tất Cả</button>
    <button class="tab" data-filter="classic" onclick="filterProducts('classic',this)">Classic</button>
    <button class="tab" data-filter="platform" onclick="filterProducts('platform',this)">Platform</button>
    <button class="tab" data-filter="sandal" onclick="filterProducts('sandal',this)">Sandal</button>
    <button class="tab" data-filter="kids" onclick="filterProducts('kids',this)">Trẻ Em</button>
  </div>

  <div class="products-grid" id="productsGrid">
    @foreach($products as $product)
    <div class="product-card" data-category="{{ $product->category_id ?? 'classic' }}">
      <div class="product-img-wrap">
        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" loading="lazy">
        @if($product->discount_price)
        <span class="product-badge badge-sale">🏷️ Sale</span>
        @else
        <span class="product-badge badge-new">✨ HOT</span>
        @endif
        <button class="product-wish" onclick="toggleWish(this)">🤍</button>
      </div>
      <div class="product-info">
        <div class="product-category">Crocs</div>
        <div class="product-name">
            <a href="{{ route('product.detail', $product->slug) }}" style="text-decoration:none; color:inherit;">
                {{ $product->name }}
            </a>
        </div>
        <div class="product-rating">
          <span class="stars">★★★★★</span>
          <span class="rating-count">({{ rand(100, 999) }} đánh giá)</span>
        </div>
        <div class="product-colors">
          <div class="color-dot" style="background:#FFD700" title="Vàng"></div>
          <div class="color-dot" style="background:#FF6B35" title="Cam"></div>
          <div class="color-dot" style="background:#00A878" title="Xanh"></div>
        </div>
        <div class="product-footer">
          <div class="product-price">
            {{ number_format($product->discount_price ?? $product->price, 0, ',', '.') }}₫
            @if($product->discount_price)
            <span class="old-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
            @endif
          </div>
          <button class="btn-add" onclick="window.location.href='{{ route('product.detail', $product->slug) }}'">Xem Thêm ></button>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</section>

<!-- ========== CTA BANNER ========== -->
<section style="padding:60px 0">
  <div class="cta-banner">
    <div>
      <div class="cta-tag">✦ Ưu Đãi Đặc Biệt Tháng 4</div>
      <h2 class="cta-title">Crocs Classic Clog<br>Phiên Bản Giới Hạn 2025</h2>
      <p class="cta-desc">Chỉ còn 50 đôi cuối cùng! Sở hữu ngay đôi Crocs phiên bản đặc biệt với hoa văn độc đáo, kèm bộ Jibbitz miễn phí trị giá 200.000₫.</p>
      <button class="btn-cta" onclick="showToast('Sắp ra mắt! 🎉')">🛍️ Xem Chi Tiết</button>
    </div>
    <div style="text-align:center;position:relative;z-index:1">
      <div class="cta-price">
        <span>Giá gốc: 990.000₫</span>
        690.000₫
      </div>
      <div style="color:rgba(255,255,255,0.6);font-size:0.85rem;margin-top:8px">💸 Tiết kiệm 300.000₫!</div>
      <div style="margin-top:1.5rem;display:flex;flex-direction:column;gap:8px">
        <div style="color:rgba(255,255,255,0.8);font-size:0.9rem">⏱️ Còn lại:</div>
        <div id="countdown" style="font-size:1.8rem;font-weight:800;color:#FFD166">00:00:00</div>
      </div>
    </div>
  </div>
</section>

<!-- ========== FEATURES ========== -->
<section class="features">
  <div class="section-header">
    <div class="section-tag">Cam Kết</div>
    <h2 class="section-title">Tại Sao Chọn CrocsVN?</h2>
  </div>
  <div class="features-grid">
    <div class="feature-card">
      <div class="feature-icon green">🚀</div>
      <div class="feature-title">Giao Hàng Siêu Tốc</div>
      <div class="feature-desc">2–4 giờ nội thành TP.HCM & Hà Nội. Toàn quốc 1–3 ngày làm việc.</div>
    </div>
    <div class="feature-card">
      <div class="feature-icon orange">🛡️</div>
      <div class="feature-title">Chính Hãng 100%</div>
      <div class="feature-desc">Nhập khẩu trực tiếp từ Crocs Inc. Có tem xác thực, bảo hành 12 tháng.</div>
    </div>
    <div class="feature-card">
      <div class="feature-icon blue">🔄</div>
      <div class="feature-title">Đổi Trả 30 Ngày</div>
      <div class="feature-desc">Không vừa size, không ưng màu? Đổi trả miễn phí trong vòng 30 ngày.</div>
    </div>
    <div class="feature-card">
      <div class="feature-icon yellow">💎</div>
      <div class="feature-title">Tư Vấn 1-1 Miễn Phí</div>
      <div class="feature-desc">Chuyên gia tư vấn trực tuyến 8h–22h hàng ngày. Hỗ trợ chọn size & màu.</div>
    </div>
  </div>
</section>

<!-- ========== TESTIMONIALS ========== -->
<section class="testimonials">
  <div class="section-header">
    <div class="section-tag">Đánh Giá</div>
    <h2 class="section-title">Khách Hàng Nói Gì?</h2>
    <p class="section-desc">Hơn 50.000 khách hàng hài lòng trên toàn quốc</p>
  </div>
  <div class="testi-grid">
    <div class="testi-card">
      <div class="testi-stars">★★★★★</div>
      <p class="testi-text">"Mình đã dùng Crocs hơn 3 năm! Đặt hàng tại CrocsVN siêu nhanh, giao trong ngày, đóng gói cẩn thận. Giày đúng hàng chính hãng, êm hơn giày nhái rất nhiều. Mình sẽ tiếp tục ủng hộ!"</p>
      <div class="testi-author">
        <div class="testi-avatar">L</div>
        <div>
          <div class="testi-name">Lê Thị Hương</div>
          <div class="testi-role">TP.HCM · Mua Classic Clog Trắng</div>
        </div>
      </div>
    </div>
    <div class="testi-card">
      <div class="testi-stars">★★★★★</div>
      <p class="testi-text">"Mua cho cả gia đình 5 người, được giảm giá combo rất tốt. Shop tư vấn nhiệt tình, giao hàng đúng hẹn. Bé 3 tuổi nhà mình mê cái dép có Jibbitz con cá lắm!"</p>
      <div class="testi-author">
        <div class="testi-avatar" style="background:var(--accent)">N</div>
        <div>
          <div class="testi-name">Nguyễn Văn Minh</div>
          <div class="testi-role">Hà Nội · Mua Crocs Kids + Classic</div>
        </div>
      </div>
    </div>
    <div class="testi-card">
      <div class="testi-stars">★★★★★</div>
      <p class="testi-text">"Platform Crocs hồng đẹp xuất sắc! Mang cả ngày không mỏi chân chút nào. Mình đã giới thiệu cho hội bạn và ai cũng rất thích. Sẽ quay lại mua thêm mùa hè này!"</p>
      <div class="testi-author">
        <div class="testi-avatar" style="background:#8B5CF6">T</div>
        <div>
          <div class="testi-name">Trần Minh Thư</div>
          <div class="testi-role">Đà Nẵng · Mua Platform Hồng</div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@stack('js')
<script>
// Filter JS
function filterProducts(cat, btn) {
  document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('.product-card').forEach(card => {
    if (cat === 'all' || card.dataset.category === cat) {
      card.style.display = '';
      card.style.animation = 'none';
      setTimeout(() => { card.style.animation = ''; }, 10);
    } else {
      card.style.display = 'none';
    }
  });
}

// Countdown
function startCountdown() {
  let h=3, m=47, s=22;
  const el = document.getElementById('countdown');
  if (!el) return;
  setInterval(() => {
    s--;
    if(s<0){s=59;m--;}
    if(m<0){m=59;h--;}
    if(h<0){h=0;m=0;s=0;}
    el.textContent = `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
  }, 1000);
}
startCountdown();

// Intersection Observer for animations
document.addEventListener("DOMContentLoaded", function() {
    const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
        }
    });
    }, { threshold: 0.1 });

    document.querySelectorAll('.product-card, .feature-card, .testi-card, .cat-card').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(el);
    });
});
</script>
