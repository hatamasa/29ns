<a href='{{ url("/shops/{$shop->shop_cd}") }}' class="link">
    <div class="card">
        <div class="card-head">
            <div class="card-title">
                <span class="rank">No.{{ ($offset ?? 0) + $loop->iteration }}</span>{{ $shop->shop_name }}
            </div>
            <div class="star-wrap" data-shop_cd="{{ $shop->shop_cd }}">
                @if ($shop->is_liked)
                <i class="fas fa-star fa-lg"></i>
                @else
                <i class="far fa-star fa-lg"></i>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="shop-img">
                <img alt="" src="{{ $shop->shop_img_url ?? asset('images/shop.png') }}">
            </div>
            <div class="shop-text">
                <ul>
                    <li class="shop-score">{{ (isset($shop->score) && !empty($shop->score)) ? $shop->score : 5 }}点</li>
                    <li>{{ $shop->post_count ?? 0 }}件の29ログ / {{ $shop->like_count ?? 0 }}件のお気に入り</li>
                    <li>{{ $shop->line }} {{ $shop->station }} 徒歩{{ $shop->walk }}分 {{ $shop->note }}</li>
                    @empty ($shop->budget)
                    @else
                    <li>予算 ¥{{ $shop->budget }}</li>
                    @endempty
                </ul>
            </div>
        </div>
    </div>
</a>