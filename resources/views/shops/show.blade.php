@extends('layouts.app')

@section('style')
<link href="{{ asset('css/shops.css') }}" rel="stylesheet">
@endsection

@section('script')
{{--<script src="{{ asset('js/shops.js') }}"></script>--}}
@endsection

@section('content')
<div>
    <div class="block-head user-thumbnail">
        @if ($user->thumbnail_url)
        <img alt="" src="{{ $user->thumbnail_url }}">
        @elseif ($user->sex == 1)
        <img alt="" src="{{ asset('/images/man.png') }}">
        @elseif ($user->sex == 2)
        <img alt="" src="{{ asset('/images/woman.png') }}">
        @endif
        <a href='{{ url("/posts/create?shop_cd={$shop["id"]}") }}'>
            <div>このお店を29ログする</div>
        </a>
    </div>
    <div class="block-body">
        <div class="card shop-detail">
            <div class="card-title">
                <div>{{ $shop['name'] }}</div>
                <div>{{ $shop['score'] ?? 5 }}点</div>
            </div>
            <div class="card-body">
                <div class="shop-img">
                    <img alt="" src="{{ !empty($shop['image_url']['shop_image1']) ? $shop['image_url']['shop_image1'] : asset('images/shop.png') }}">
                </div>
                <div class="shop-text">
                    <ul>
                        <li>{{ $shop['post_count'] ?? 0 }}件の29ログ / {{ $shop['like_count'] ?? 0 }}件のお気に入り</li>
                        <li>{{ $shop['access']['line'] }} {{ $shop['access']['station'] }} 徒歩{{ $shop['access']['walk'] }}分 {{ $shop['access']['note'] }}</li>
                        @empty ($shop['budget'])
                        @else
                        <li>予算 ¥{{ $shop['budget'] }}</li>
                        @endempty
                        <li>営業日 {{ $shop['opentime'] }}</li>
                        <li>定休日 {{ $shop['holiday'] }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <p class="gnavi-link"><a href="{{ $shop['url_mobile'] }}">ぐるなびのページはこちらから→</a></p>

    <div class="block-head">
        <p>この店舗への29ログ</p>
    </div>
    <div class="block-body">
    @if (count($posts) == 0)
    <div>このお店への29ログはまだありません。</div>
    <div>お店を訪ずれたことあったら、上の「このお店を29ログする」からレビューを投稿しましょう！</div>
    @endif
    @foreach ($posts as $post)
        <div class="card post">
            <div class="card-body">
                <div class="post-text">
                    <ul>
                        <li class="post-text-top">
                            <p>{{ $post->score }}点</p>
                            <p>
                                <a href='{{ url("/users/{$post->user_id}")}}'>
                                    @if ($user->thumbnail_url)
                                    <img alt="" src="{{ $post->user_thumbnail_url }}">
                                    @elseif ($user->sex == 1)
                                    <img alt="" src="{{ asset('/images/man.png') }}">
                                    @elseif ($user->sex == 2)
                                    <img alt="" src="{{ asset('/images/woman.png') }}">
                                    @endif
                                    {{ $post->user_name }}
                                </a>
                            </p>
                            <p>@time_diff($post->post_created_at)</p>
                        </li>
                        <li class="post-text-center">
                            <p>{{ $post->title }}</p>
                            <p>{{ $post->contents }}</p>
                        </li>
                    </ul>
                </div>
                <ul class="post-img">
                    @if (!empty($post->img_url_1))
                    <li><img alt="" src="{{ $post->img_url_1 }}"></li>
                    @endif
                    @if (!empty($post->img_url_2))
                    <li><img alt="" src="{{ $post->img_url_2 }}"></li>
                    @endif
                    @if (!empty($post->img_url_3))
                    <li><img alt="" src="{{ $post->img_url_3 }}"></li>
                    @endif
                </ul>
            </div>
            <div class="card-body-footer">
                <ul class="post-text-under">
                    <li>{{ $post->like_count }}いいね</li>
                    <li>コメント{{ $post->comment_count }}件</li>
                </ul>
                @auth
                <ul class="post-detail-link">
                    <a href='javascript:void(0)' class="like"><li>いいね</li></a>
                    <a href='{{ url("/posts/{$post->id}") }}'><li>コメントする</li></a>
                </ul>
                @endauth
            </div>
        </div>
    @endforeach
    </div>
    {{ $posts->links('common.pagination') }}
</div>

@endsection
