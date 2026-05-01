@extends('layouts.app')

@section('title', 'Tất Cả Sản Phẩm – CrocsVN')

@push('css')
<style>
.shop-header {
    background: linear-gradient(135deg, var(--dark), #1a1a2e);
    color: white; padding: 120px 5% 60px; text-align: center;
}
.shop-header h1 { font-size: 2.8rem; font-weight: 800; margin-bottom: 10px; }
.shop-container { display: grid; grid-template-columns: 280px 1fr; gap: 3rem; padding: 60px 5%; align-items: start; }
.filter-widget {
    background: white; border-radius: 16px; padding: 24px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05); position: sticky; top: 100px;
}
.filter-widget h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: 1.2rem; padding-bottom: 10px; border-bottom: 1px solid #eee; }
.filter-list { list-style: none; display: flex; flex-direction: column; gap: 12px; }
.filter-list a { text-decoration: none; color: var(--text); font-weight: 500; font-size: 0.95rem; transition: all 0.3s; display: flex; justify-content: space-between; }
.filter-list a:hover, .filter-list a.active { color: var(--primary); transform: translateX(5px); }
.shop-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }

@media (max-width: 900px) {
    .shop-container { grid-template-columns: 1fr; }
    .filter-widget { position: relative; top: 0; }
    .shop-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
    .shop-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="shop-header">
    <h1>Khám Phá Tất Cả Bộ Sưu Tập</h1>
    <p>Tìm đôi Crocs hoàn hảo dành riêng cho bạn</p>
</div>

<div class="shop-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="filter-widget">
            <h3>Danh Mục Sản Phẩm</h3>
            <ul class="filter-list">
                <li><a href="{{ route('shop') }}" class="{{ request('category') ? '' : 'active' }}">Tất Cả</a></li>
                @foreach($categories as $cat)
                <li>
                    <a href="{{ route('shop', ['category' => $cat->id]) }}" class="{{ request('category') == $cat->id ? 'active' : '' }}">
                        {{ $cat->name }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        
        <div class="filter-widget" style="margin-top:2rem">
            <h3>Tìm Kiếm</h3>
            <form action="{{ route('shop') }}" method="GET" style="display:flex;gap:8px">
                <input type="text" name="keyword" placeholder="Nhập tên dép..." value="{{ request('keyword') }}" style="width:100%;padding:10px;border-radius:8px;border:1px solid #ddd;outline:none">
                <button type="submit" style="background:var(--primary);color:white;border:none;padding:10px 15px;border-radius:8px;cursor:pointer">🔍</button>
            </form>
        </div>
    </aside>

    <!-- Products -->
    <div class="products-list-wrapper">
        <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 2rem;">
            <div style="font-weight:600; color:var(--text-light)">Hiển thị {{ $products->count() }} sản phẩm</div>
            <select style="padding:10px 16px; border-radius:50px; border:1px solid #ddd; outline:none; font-family:inherit; font-weight:600">
                <option>Mới nhất</option>
                <option>Giá rẻ nhất</option>
                <option>Giá cao nhất</option>
            </select>
        </div>

        <div class="shop-grid">
            @forelse($products as $product)
            <div class="product-card">
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
                    <div class="product-category">Crocs Official</div>
                    <div class="product-name">
                        <a href="{{ route('product.detail', $product->slug) }}" style="text-decoration:none; color:inherit;">
                            {{ $product->name }}
                        </a>
                    </div>
                    <div class="product-rating">
                        <span class="stars">★★★★★</span>
                    </div>
                    <div class="product-footer" style="margin-top:1rem">
                        <div class="product-price">
                            {{ number_format($product->discount_price ?? $product->price, 0, ',', '.') }}₫
                            @if($product->discount_price)
                                <span class="old-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                            @endif
                        </div>
                        <button class="btn-add" onclick="window.location.href='{{ route('product.detail', $product->slug) }}'">Chi Tiết</button>
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column: 1/-1; text-align:center; padding: 4rem; background:white; border-radius:16px;">
                <div style="font-size:3rem; margin-bottom:1rem">📭</div>
                <h3 style="margin-bottom:1rem">Không tìm thấy sản phẩm nào!</h3>
                <a href="{{ route('shop') }}" style="color:var(--primary); font-weight:700">Tải lại tât cả</a>
            </div>
            @endforelse
        </div>
        
        <div style="margin-top:3rem; display:flex; justify-content:center">
            <!-- Laravel Pagination Links -->
            {!! $products->links() !!}
        </div>
    </div>
</div>
@endsection
