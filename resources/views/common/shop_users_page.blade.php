<a href='{{ url("/shops/{$shop->shop_cd}") }}' class="link">
    <div class="card">
        <div class="card-head">
            <h2 class="card-title">{{ $shop->shop_name }}</h2>
            @if (Auth::id() == $users->id)
            @auth
            <div class="star-wrap shop-like" data-shop_cd="{{ $shop->shop_cd }}">
            @else
            <div class="star-wrap" data-shop_cd="{{ $shop->shop_cd }}">
            @endauth
                <i class="fas fa-star fa-lg"></i>
            </div>
            @endif
        </div>
        <div class="card-body">
            <div class="shop-img">
                <img alt="" src="{{ $shop->shop_img_url ?? asset('images/shop.png') }}">
            </div>
            <div class="shop-text">
                <ul>
                    <li>{{ (isset($shop->score) && !empty($shop->score)) ? $shop->score : 5 }}点</li>
                    <li>{{ $shop->post_count ?? 0 }}件の肉ログ / {{ $shop->like_count ?? 0 }}件のお気に入り</li>
                    <li>{{ $shop->line }} {{ $shop->station }} {{ $shop->walk }}分 {{ $shop->note }}</li>
                    @empty ($shop->budget)
                    @else
                    <li>予算 ¥{{ $shop->budget }}</li>
                    @endempty
                </ul>
            </div>
        </div>
    </div>
</a>