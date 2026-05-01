@extends('layouts.app')

@section('title', 'Liên Hệ – CrocsVN')

@push('css')
<style>
.contact-wrap { max-width: 1200px; margin: 60px auto; padding: 0 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 60px; }
.contact-info h1 { font-size: 2.5rem; font-weight: 800; color: var(--dark); margin-bottom: 20px; }
.info-row { display: flex; gap: 20px; margin-bottom: 30px; align-items: flex-start; }
.info-icon { width: 50px; height: 50px; background: #e8f8f3; color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; }
.info-text h4 { font-size: 1.2rem; font-weight: 700; margin-bottom: 5px; }
.info-text p { color: #666; line-height: 1.6; }

.contact-form { background: white; padding: 40px; border-radius: 24px; box-shadow: 0 10px 40px rgba(0,0,0,0.06); }
.form-group { margin-bottom: 20px; }
.form-control { width: 100%; padding: 16px; border-radius: 12px; border: 1px solid #ddd; outline: none; font-family: inherit; font-size: 1rem; transition: 0.3s; box-sizing: border-box; }
.form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(0,168,120,0.1); }
.btn-submit { width: 100%; padding: 18px; background: var(--dark); color: white; font-weight: 700; font-size: 1.1rem; border: none; border-radius: 50px; cursor: pointer; transition: 0.3s; }
.btn-submit:hover { background: var(--primary); transform: translateY(-2px); }

@media (max-width: 768px) {
    .contact-wrap { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="contact-wrap">
    <div class="contact-info">
        <h1>Giữ Liên Lạc Nhé!</h1>
        <p style="font-size:1.1rem; color:#666; margin-bottom:40px">Bạn cần hỗ trợ về đơn hàng, chọn size hay có góp ý gì? Hãy để lại thông tin hoặc nhắn trực tiếp cho chúng tôi.</p>
        
        <div class="info-row">
            <div class="info-icon">📍</div>
            <div class="info-text">
                <h4>Trụ Sở Chính</h4>
                <p>123 Đường Lê Lợi, Phường Bến Nghé<br>Quận 1, TP. Hồ Chí Minh</p>
            </div>
        </div>
        <div class="info-row">
            <div class="info-icon">📞</div>
            <div class="info-text">
                <h4>Đường Dây Nóng</h4>
                <p>1800 6868 (Miễn phí cước cuộc gọi)<br>0909 999 888 (Hotline dự phòng)</p>
            </div>
        </div>
        <div class="info-row">
            <div class="info-icon">✉️</div>
            <div class="info-text">
                <h4>Email Hỗ Trợ Tại Đây</h4>
                <p>hello@crocsvn.vn<br>support@crocsvn.vn</p>
            </div>
        </div>
    </div>

    <div>
        <form class="contact-form" action="{{ route('home') }}" onsubmit="alert('Cảm ơn bạn đã gửi liên hệ, chúng tôi sẽ phản hồi sớm nhất!'); return false;">
            <h3 style="font-size:1.5rem; font-weight:800; margin-bottom:25px">Gửi Lời Nhắn</h3>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Họ và Tên của bạn" required>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" placeholder="Địa chỉ Email" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Chủ đề (Vd: Hỗ trợ đổi size)">
            </div>
            <div class="form-group">
                <textarea class="form-control" rows="5" placeholder="Nội dung cần hỗ trợ..." required></textarea>
            </div>
            <button type="submit" class="btn-submit">Gửi Tin Nhắn 🚀</button>
        </form>
    </div>
</div>

@endsection
