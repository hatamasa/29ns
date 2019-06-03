<div class="card">
    <div class="card-title">
        <a href='{{ url("/shops/{$shop->shop_cd}") }}'>
            <span class="rank">No.{{ ($offset ?? 0) + $loop->iteration }}</span>{{ $shop->shop_name }}
        </a>
    </div>
    <div class="card-body">
        <div class="shop-img">
            <img alt="" src="{{ $shop->shop_img_url ?? asset('images/shop.png') }}">
        </div>
        <div class="shop-text">
            <ul>
                <li>{{ $shop->score }}点</li>
                <li>{{ $shop->post_count }}件の29ログ / {{ $shop->like_count }}件のお気に入り</li>
                <li>{{ $shop->line }} {{ $shop->station }} 徒歩{{ $shop->walk }}分 {{ $shop->note }}</li>
                @empty ($shop->budget)
                @else
                <li>予算 ¥{{ $shop->budget }}</li>
                @endempty
            </ul>
        </div>
    </div>
</div>