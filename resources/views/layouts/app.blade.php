<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="CrocsVN - Shop dép Crocs chính hãng tại Việt Nam.">
  <title>@yield('title', 'CrocsVN – Shop Dép Crocs Chính Hãng')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/crocs.css') }}">
  @stack('css')
</head>
<body>

<!-- ========== NAVBAR ========== -->
<nav class="navbar" id="navbar">
  <a href="{{ route('home') }}" class="nav-brand">
    <div class="nav-logo">🐊</div>
    <span class="nav-brand-text">Crocs<span>VN</span></span>
  </a>
  <ul class="nav-links">
    <li><a href="{{ route('home') }}">Trang Chủ</a></li>
    <li><a href="{{ route('shop') }}">Sản Phẩm</a></li>
    <li><a href="{{ route('home') }}#categories">Danh Mục</a></li>
    <li><a href="{{ route('about') }}">Giới Thiệu</a></li>
    <li><a href="{{ route('contact') }}">Liên Hệ</a></li>
  </ul>
  <div class="nav-actions">
    <button class="nav-icon" id="searchBtn" title="Tìm kiếm">🔍</button>
    <a href="{{ route('cart.index') }}" class="nav-icon" title="Giỏ hàng" style="text-decoration:none">
      🛒
      <span class="cart-badge" id="cartCount">0</span>
    </a>
    
    @guest
        <a href="{{ route('login') }}" class="btn-nav" style="text-decoration:none">Đăng Nhập</a>
    @else
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="btn-nav" style="background:var(--dark); text-decoration:none; margin-right:5px">Quản Trị</a>
        @else
            <a href="{{ route('profile') }}" class="btn-nav" style="text-decoration:none; margin-right:5px">Chào, {{ explode(' ', Auth::user()->name)[0] }}</a>
        @endif

        <form action="{{ route('logout') }}" method="POST" style="display:inline">
            @csrf
            <button type="submit" class="btn-nav" style="background:transparent; color: #d9534f; border: 1px solid #d9534f">Thoát</button>
        </form>
    @endguest
  </div>
  <button class="hamburger" id="hamburger" aria-label="Menu">
    <span></span><span></span><span></span>
  </button>
</nav>

<!-- NỘI DUNG TỪNG TRANG SẼ LOAD VÀO ĐÂY -->
<main style="min-height: 80vh">
    @yield('content')
</main>

<!-- ========== FOOTER ========== -->
<footer>
  <div class="footer-grid">
    <div class="footer-brand">
      <a href="{{ route('home') }}" class="nav-brand" style="text-decoration:none;display:inline-flex;gap:10px;align-items:center;margin-bottom:1rem">
        <div class="nav-logo">🐊</div>
        <span class="nav-brand-text" style="font-size:1.3rem">Crocs<span>VN</span></span>
      </a>
      <p>Shop dép Crocs chính hãng số 1 Việt Nam. Chất lượng đảm bảo, giá cạnh tranh, giao hàng siêu tốc trên toàn quốc.</p>
      <div class="footer-socials">
        <a href="#" class="social-btn" aria-label="Facebook">f</a>
        <a href="#" class="social-btn" aria-label="Instagram">📷</a>
        <a href="#" class="social-btn" aria-label="TikTok">🎵</a>
        <a href="#" class="social-btn" aria-label="YouTube">▶</a>
      </div>
    </div>
    <div class="footer-col">
      <h4>Sản Phẩm</h4>
      <ul>
        <li><a href="{{ route('shop') }}">Tất Cả Sản Phẩm</a></li>
        <li><a href="#">Classic Clog</a></li>
        <li><a href="#">Platform Clog</a></li>
        <li><a href="#">Baya Sandal</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Hỗ Trợ</h4>
      <ul>
        <li><a href="{{ route('faq') }}">FAQ / Câu Hỏi</a></li>
        <li><a href="#">Bảng Size</a></li>
        <li><a href="#">Chính Sách Đổi Trả</a></li>
        <li><a href="#">Vận Chuyển</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Liên Hệ</h4>
      <ul>
        <li><a href="{{ route('contact') }}">📍 123 Lê Lợi, Q.1, TP.HCM</a></li>
        <li><a href="#">📞 0909 999 888</a></li>
        <li><a href="#">⏰ 8h00 – 22h00 mỗi ngày</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2025 CrocsVN. Tất cả quyền được bảo lưu.</p>
    <div class="payment-icons">
      <span class="pay-icon">VISA</span>
      <span class="pay-icon">MasterCard</span>
      <span class="pay-icon">MoMo</span>
      <span class="pay-icon">COD</span>
    </div>
  </div>
</footer>

<!-- ========== TOAST ========== -->
<div class="toast" id="toast"><span class="toast-icon">✅</span><span id="toastMsg"></span></div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
// Navbar scroll
window.addEventListener('scroll',()=>{
  document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 50);
});

// Toast
let toastTimer;
function showToast(msg) {
  const t = document.getElementById('toast');
  document.getElementById('toastMsg').textContent = msg;
  t.classList.add('show');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => t.classList.remove('show'), 3000);
}

// Wishlist
function toggleWish(btn) {
  const isWished = btn.textContent === '❤️';
  btn.textContent = isWished ? '🤍' : '❤️';
  showToast(isWished ? 'Đã bỏ khỏi yêu thích' : '❤️ Đã thêm vào yêu thích!');
}

document.getElementById('hamburger').addEventListener('click', () => {
  showToast('📱 Menu mobile đang phát triển!');
});
</script>
@stack('js')
<script>
        function updateCartBadge(count) {
            const badge = document.getElementById('cartCount');
            if (badge) {
                badge.textContent = count;
                badge.style.display = count > 0 ? '' : 'none';
            }
        }

        async function updateCartBadgeFromBackend() {
            try {
                const url = '{{ route('cart.count') }}';
                const response = await fetch(url);
                const data = await response.json();
                updateCartBadge(data.count);
            } catch (e) {
                console.error("Lỗi cập nhật giỏ hàng:", e);
            }
        }
        
        // Cập nhật khi trang tải xong
        document.addEventListener('DOMContentLoaded', updateCartBadgeFromBackend);
    </script>
    @stack('js')
</body>
</html>
