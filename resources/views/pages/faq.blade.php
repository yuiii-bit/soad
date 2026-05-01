@extends('layouts.app')

@section('title', 'Câu Hỏi Thường Gặp – CrocsVN')

@push('css')
<style>
.faq-wrap { max-width: 800px; margin: 60px auto; padding: 0 20px; }
.faq-header { text-align: center; margin-bottom: 50px; }
.faq-header h1 { font-size: 2.5rem; font-weight: 800; color: var(--dark); margin-bottom: 15px; }

.faq-item { background: white; border-radius: 12px; margin-bottom: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); overflow: hidden; border: 1px solid #eee; }
.faq-question { width: 100%; padding: 20px 25px; text-align: left; background: transparent; border: none; font-size: 1.1rem; font-weight: 700; color: #1a1a2e; cursor: pointer; display: flex; justify-content: space-between; align-items: center; font-family: inherit; }
.faq-question::after { content: "+"; font-size: 1.5rem; font-weight: 400; color: var(--primary); transition: 0.3s; }
.faq-answer { padding: 0 25px; max-height: 0; overflow: hidden; transition: 0.3s; color: #555; line-height: 1.6; }
.faq-item.active .faq-question::after { content: "−"; transform: rotate(180deg); }
.faq-item.active .faq-answer { padding: 0 25px 25px; max-height: 500px; }
</style>
@endpush

@section('content')
<div class="faq-wrap">
    <div class="faq-header">
        <h1>Bạn Có Câu Hỏi? Mọi Đáp Án Ở Đây</h1>
        <p style="color:#666; font-size:1.1rem">Tìm hiểu thông tin về giao hàng, chọn size và đổi trả hàng</p>
    </div>

    <div class="faq-item">
        <button class="faq-question">1. Làm thế nào để biết tôi mang vừa size nào?</button>
        <div class="faq-answer">
            Size Crocs thường lớn hơn giày bình thường 1 size. Nếu bạn đang mang form giày thể thao số 39, chúng tôi khuyên bạn nên chọn Size Crocs 38. Bạn có thể tham khảo mục "Bảng Size" trên Header.
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">2. Thời gian giao hàng mất bao lâu?</button>
        <div class="faq-answer">
            Các đơn nội thành (TP.HCM, Hà Nội) Giao nhanh trong 2-4 giờ đồng hồ nếu đặt qua gói hoả tốc. Giao hàng toàn quốc sẽ mất từ 1 - 3 ngày tuỳ theo vị trí tỉnh thành.
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">3. Có được kiểm tra hàng trước khi nhận không?</button>
        <div class="faq-answer">
            Chắc chắn rồi. Hệ thống CrocsVN quy định bạn được quyền đồng kiểm cùng bưu tá. Thậm chí bạn có thể thử xem có vừa kích cỡ không. Nếu không hài lòng bạn có thể yêu cầu trả về.
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">4. Jibbitz có bị rơi khi di chuyển không?</button>
        <div class="faq-answer">
            Jibbitz chính hãng được thiết kế để gắn chặt vào lỗ của dép Crocs. Rất khó để một nút Jibbitz rơi ra trừ khi bạn có tác động vật lý rất mạnh và kéo từ mặt trong ra.
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">5. Crocs bảo hành như thế nào?</button>
        <div class="faq-answer">
            CrocsVN bảo hành toàn diện keo, nhiệt, phụ kiện trong vòng 1 năm. Nếu phát sinh lỗi nhà sản xuất bạn sẽ được đổi trả lấy 1 sản phẩm hoàn toàn mới.
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.faq-question').forEach(btn => {
    btn.addEventListener('click', () => {
        const item = btn.parentElement;
        const isActive = item.classList.contains('active');
        document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('active'));
        if(!isActive) item.classList.add('active');
    });
});
</script>
@endsection
