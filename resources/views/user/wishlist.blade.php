@extends('user.layout')
@section('title', 'Danh Sách Yêu Thích')
@section('profile-title', '❤️ Sản Phẩm Yêu Thích Của Bạn')

@push('profile-css')
<style>
.wishlist-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; }
.wishlist-card { background: white; border: 1px solid #e5e7eb; border-radius: 16px; overflow: hidden; transition: 0.3s; position: relative; }
.wishlist-card:hover { border-color: var(--primary); box-shadow: 0 10px 20px rgba(0,0,0,0.05); transform: translateY(-5px); }

.wishlist-img { height: 200px; background: #f8f9fa; padding: 20px; position: relative; }
.wishlist-img img { width: 100%; height: 100%; object-fit: contain; transition: 0.3s; }
.wishlist-card:hover .wishlist-img img { transform: scale(1.05); }

.btn-remove-wishlist { position: absolute; top: 10px; right: 10px; background: white; color: #ef4444; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; border: none; cursor: pointer; box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 10; transition: 0.2s; }
.btn-remove-wishlist:hover { background: #ef4444; color: white; }

.wishlist-info { padding: 15px; }
.wishlist-title { font-weight: 700; font-size: 0.95rem; color: #1a1a2e; margin-bottom: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-decoration: none; display: block; }
.wishlist-price { font-weight: 800; color: var(--primary); font-size: 1.1rem; }

.btn-buy-now { display: block; width: 100%; background: #1a1a2e; color: white; text-align: center; padding: 12px; font-weight: 700; text-decoration: none; border-top: 1px solid rgba(0,0,0,0.05); transition: 0.2s; font-size: 0.9rem; }
.wishlist-card:hover .btn-buy-now { background: var(--primary); }
</style>
@endpush

@section('profile-content')

@if($wishlists->count() > 0)
    <div class="wishlist-grid">
        @foreach($wishlists as $wish)
        @php $prod = $wish->product; @endphp
        @if($prod)
            <div class="wishlist-card" id="wishlist-item-{{ $prod->id }}">
                <button class="btn-remove-wishlist" onclick="toggleWishlist({{ $prod->id }})" title="Bỏ Yêu Thích">❌</button>
                <a href="{{ route('product.detail', $prod->slug) }}" class="wishlist-img">
                    <img src="{{ asset($prod->image ?? 'images/hero.png') }}" alt="{{ $prod->name }}">
                </a>
                <div class="wishlist-info">
                    <a href="{{ route('product.detail', $prod->slug) }}" class="wishlist-title">{{ $prod->name }}</a>
                    <div class="wishlist-price">
                        {{ number_format($prod->discount_price ?? $prod->price, 0, ',', '.') }}₫
                    </div>
                </div>
                <a href="{{ route('product.detail', $prod->slug) }}" class="btn-buy-now">Xem Chi Tiết</a>
            </div>
        @endif
        @endforeach
    </div>
@else
    <div style="text-align:center; padding:50px 20px; color:#666; background:#fff2f2; border-radius:15px; border:1px solid #ffe5e5">
        <div style="font-size:4rem; margin-bottom:15px">💔</div>
        <p style="font-size:1.1rem; font-weight:600; color:#dc2626; margin-bottom:10px">Danh sách yêu thích đang trống!</p>
        <p style="margin-bottom:20px; color:#888">Bạn có thể thả tim vào bất kỳ sản phẩm nào trên cửa hàng để lưu lại.</p>
        <a href="{{ route('shop') }}" class="btn btn-primary" style="display:inline-block; padding:12px 25px; background:#dc2626; color:white; text-decoration:none; border-radius:50px; font-weight:700">DẠO CỬA HÀNG</a>
    </div>
@endif

@endsection

@push('profile-js')
<script>
async function toggleWishlist(productId) {
    if(!confirm('Bạn muốn bỏ sản phẩm này khỏi yêu thích?')) return;
    
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
        
        if (data.status === 'removed') {
            const card = document.getElementById('wishlist-item-' + productId);
            if(card) {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.8)';
                setTimeout(() => card.remove(), 300);
            }
        }
    } catch(e) {
        alert("Có lỗi xảy ra!");
    }
}
</script>
@endpush
