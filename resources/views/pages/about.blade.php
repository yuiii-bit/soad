@extends('layouts.app')

@section('title', 'Về Chúng Tôi – CrocsVN')

@push('css')
<style>
.about-hero { background: var(--dark); color: white; padding: 100px 20px; text-align: center; }
.about-hero h1 { font-size: 3rem; font-weight: 800; margin-bottom: 20px; }
.about-hero p { font-size: 1.2rem; color: rgba(255,255,255,0.8); max-width: 700px; margin: 0 auto; }

.about-content { max-width: 1000px; margin: -50px auto 60px; background: white; border-radius: 20px; padding: 50px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); position: relative; }
.about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center; margin-bottom: 60px; }
.about-img { width: 100%; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
.about-title { font-size: 2rem; font-weight: 800; color: var(--dark); margin-bottom: 20px; }
.about-text { font-size: 1.05rem; color: #555; line-height: 1.8; }

.stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; text-align: center; margin-top: 50px; border-top: 1px solid #eee; padding-top: 50px; }
.stat-box { padding: 20px; }
.stat-num { font-size: 3rem; font-weight: 900; color: var(--primary); margin-bottom: 10px; }
.stat-label { font-size: 1.1rem; font-weight: 600; color: #666; }

@media (max-width: 768px) {
    .about-grid { grid-template-columns: 1fr; }
    .stats-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="about-hero">
    <h1>Hành Trình Của CrocsVN</h1>
    <p>Mang đến sự thoải mái tuyệt đối cho hàng triệu đôi bàn chân Việt Nam bằng những sản phẩm nhẹ nhàng, bền bỉ và đầy phong cách.</p>
</div>

<div class="about-content">
    <div class="about-grid">
        <div>
            <img src="{{ asset('images/hero.png') }}" class="about-img" alt="Crocs Store" style="background:#f4f4f4">
        </div>
        <div>
            <h2 class="about-title">Câu chuyện bắt đầu từ 2015</h2>
            <div class="about-text">
                <p>Khởi nguồn từ một cửa hàng bán lẻ nhỏ tại trung tâm Sài Gòn, CrocsVN nhận ra nhu cầu về một đôi giày không chỉ đẹp mà còn phải cực kỳ êm ái cho những ngày mưa rào hay những chuyến đi bộ dài.</p>
                <p style="margin-top:15px">Hôm nay, chúng tôi tự hào là đại lý phân phối chính thức của thương hiệu Crocs toàn cầu, mang đến hơn hàng nghìn mẫu thiết kế đa dạng màu sắc cùng phụ kiện Jibbitz độc đáo.</p>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-num">10+</div>
            <div class="stat-label">Năm Hoạt Động</div>
        </div>
        <div class="stat-box">
            <div class="stat-num">50k+</div>
            <div class="stat-label">Khách Hàng Tin Dùng</div>
        </div>
        <div class="stat-box">
            <div class="stat-num">15</div>
            <div class="stat-label">Cửa Hàng Toàn Quốc</div>
        </div>
    </div>
</div>
@endsection
