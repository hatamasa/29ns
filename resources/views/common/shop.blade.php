<a href='{{ url("/shops/{$shop["id"]}") }}' class="link">
    <div class="card">
        <div class="card-head">
            <h2 class="card-title">{{ $shop['name'] }}</h2>
            @auth
            <div class="star-wrap shop-like" data-shop_cd="{{ $shop['id'] }}">
            @else
            <div class="star-wrap" data-shop_cd="{{ $shop['id'] }}">
            @endauth
                @if ($shop['is_liked'] ?? false)
                <i class="fas fa-star fa-lg"></i>
                @else
                <i class="far fa-star fa-lg"></i>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="shop-img">
                <img alt="" src="{{ !empty($shop['image_url']['shop_image1']) ? $shop['image_url']['shop_image1'] : asset('images/shop.png') }}">
            </div>
            <div class="shop-text">
                <ul>
                    <li>{{ (isset($shop['score']) && !empty($shop['score'])) ? $shop['score'] : 5 }}点</li>
                    <li>{{ $shop['post_count'] ?? 0 }}件の29ログ / {{ $shop['like_count'] ?? 0 }}件のお気に入り</li>
                    <li>{{ $shop['access']['line'] }} {{ $shop['access']['station'] }} 徒歩{{ $shop['access']['walk'] }}分 {{ $shop['access']['note'] }}</li>
                    @empty ($shop['budget'])
                    @else
                    <li>予算 ¥{{ $shop['budget'] }}</li>
                    @endempty
                </ul>
            </div>
        </div>
    </div>
</a>