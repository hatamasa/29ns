<a href='{{ url("/shops/{$shop["id"]}") }}' class="link">
    <div class="card shop-card">
        <div class="card-head">
            <h2 class="card-title">{{ $shop['name'] }}</h2>
            @if ($shop['is_posted'] ?? false)
            <div class="_postedWrap posted-wrap posted" >
                <i class="fas fa-check-square fa-lg"></i>
            </div>
            @else
            <div class="_postedWrap posted-wrap" data-link='{{ url("/posts/create?shop_cd={$shop["id"]}") }}'>
                <i class="far fa-check-square fa-lg"></i>
            </div>
            @endif
            <div class="star-wrap @auth _shopLike @else _loginLink @endauth" data-shop_cd="{{ $shop['id'] }}">
                @if ($shop['is_liked'] ?? false)
                <i class="fas fa-star fa-lg"></i>
                @else
                <i class="far fa-star fa-lg"></i>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="shop-img">
                <img alt="" src="{{ !empty($shop['image_url']['shop_image1']) ? $shop['image_url']['shop_image1'] : asset('images/shop.png') }}" alt="店舗TOP画像">
            </div>
            <div class="shop-text">
                <ul>
                    <li>{{ (isset($shop['score']) && !empty($shop['score'])) ? $shop['score'] : 5 }}点</li>
                    <li>{{ $shop['post_count'] ?? 0 }}件の肉ログ / {{ $shop['like_count'] ?? 0 }}件のお気に入り</li>
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