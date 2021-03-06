@extends('layouts.app')

@section('metatitle')
<title>{{ $shop['name'] }} - 東京肉NS</title>
@endsection

@section('description')
<meta name="description" content="{{ $shop['name'] }}の店舗情報や口コミをご紹介します！{{ $shop['name'] }}が気に入った時は星マークからお気に入り登録をしましょう！そして来店したら感謝の気持ちを込めて口コミしよう！">
@endsection

@section('style')
<link href="{{ asset('css/shops/show.css') }}" rel="stylesheet">
@endsection

@section('script')
<script>
    $('.delete-post-form').submit(evt => {
        if (! confirm("投稿を削除しますか？")) return false;
        $(evt.target).prop("disabled", true);
    });
</script>
@endsection

@section('title', '店舗詳細')

@section('content')
<div>
    @auth
    <div class="block-head user-thumbnail">
        @if (isset($user->thumbnail_url) && !empty($user->thumbnail_url))
        <img alt="ユーザプロフィール画像" src="{{ $user->thumbnail_url }}" class="icon">
        @else
        <img alt="ユーザデフォルトプロフィール画像" src="{{ asset('/images/user.png') }}" class="icon">
        @endif
        <a href='{{ url("/posts/create?shop_cd={$shop["id"]}") }}' class="icon">
            <div>このお店に行った場合はコチラ！</div>
        </a>
    </div>
    @else
        @include('common.landing_regist')
    @endauth
    <div class="block-body">
        <div class="card shop-detail">
            <div class="card-head">
                <div class="card-title">
                    <h2>{{ $shop['name'] }}</h2>
                    <div>{{ (isset($shop['score']) && !empty($shop['score'])) ? $shop['score'] : 5 }}点</div>
                </div>
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
                    <img alt="店舗TOP画像" src="{{ !empty($shop['image_url']['shop_image1']) ? $shop['image_url']['shop_image1'] : asset('images/shop.png') }}">
                </div>
                <div class="shop-text">
                    <ul>
                        <li>{{ $shop['post_count'] ?? 0 }}件の肉ログ / {{ $shop['like_count'] ?? 0 }}件のお気に入り</li>
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

    <p class="gnavi-link"><a href="{{ $shop['url_mobile'] }}" target="_blank">ぐるなびのページはこちらから→</a></p>

    <ins class="adsbygoogle"
         style="display:block"
         data-ad-format="fluid"
         data-ad-layout-key="-fb+5w+4e-db+86"
         data-ad-client="ca-pub-4702990894338882"
         data-ad-slot="9628141100"></ins>

    <div class="block-head">
        <h2>この店舗への肉ログ</h2>
    </div>
    <div class="block-body">
    @if (count($posts) == 0)
    <div>このお店への肉ログはまだありません。</div>
    <div>お店を訪ずれたことあったら、上の「このお店を肉ログする」からレビューを投稿しましょう！</div>
    @endif
    @foreach ($posts as $post)
        <div class="card post">
            <div class="card-body">
                <div class="post-text">
                    <ul>
                        <li class="post-text-top">
                            <p>{{ $post->score }}点</p>
                            <p>
                                @if ($post->user_id == 0)
                                <p class="name">
                                    <img alt="" src="{{ asset('/images/user.png') }}" class="icon" alt="ユーザデフォルトプロフィール画像">
                                    {{ strpos($post->title, '/') ? explode("/", $post->title)[1] : '' }}
                                </p>
                                @else
                                <a href='{{ url("/users/{$post->user_id}")}}'>
                                    @if (isset($post->user_thumbnail_url) && !empty($post->user_thumbnail_url))
                                    <img alt="" src="{{ $post->user_thumbnail_url }}" class="icon">
                                    @else
                                    <img alt="" src="{{ asset('/images/user.png') }}" class="icon">
                                    @endif
                                    {{ $post->user_name }}
                                </a>
                                @endif
                            </p>
                            <p>@time_diff($post->post_created_at)</p>
                        </li>
                        <li class="post-text-center">
                            @empty ($post->title)
                            <p>{{ $shop['name'] }}へ行きました！</p>
                            @else
                            <p>{{ explode("/", $post->title)[0] }}</p>
                            @endif
                            <p>{{ $post->contents }}</p>
                        </li>
                    </ul>
                </div>
                <ul class="post-img">
                    @if (!empty($post->img_url_1))
                    <li><img alt="投稿画像1" src="{{ $post->img_url_1 }}"></li>
                    @endif
                    @if (!empty($post->img_url_2))
                    <li><img alt="投稿画像2" src="{{ $post->img_url_2 }}"></li>
                    @endif
                    @if (!empty($post->img_url_3))
                    <li><img alt="投稿画像3" src="{{ $post->img_url_3 }}"></li>
                    @endif
                </ul>
            </div>
            <div class="card-body-footer">
                <ul class="post-text-under">
                    <li class="like-disp"><img class="like-icon" src="{{ asset('images/like_black.png') }}">{{ $post->like_count }}</li>
                    <li><a href='{{ url("/posts/{$post->id}") }}'><img alt="コメントアイコン画像" class="comment-icon" src="{{ asset('images/comment.png') }}">{{ $post->comment_count }}</a></li>
                </ul>
                @auth
                <ul class="post-detail-link">
                    <li>
                    @if ($post->is_liked)
                    <a href='javascript:void(0)' class="like-link _liked" data-post_id="{{ $post->id }}"><img class="like-icon _liked" src="{{ asset('images/like.png') }}" alt="いいねアイコン">済</a>
                    @else
                    <a href='javascript:void(0)' class="like-link _notlike" data-post_id="{{ $post->id }}"><img class="like-icon _notlike" src="{{ asset('images/like.png') }}" alt="いいねアイコン"></a>
                    @endif
                    </li>
                    <li><a href='{{ url("/posts/{$post->id}") }}'><img class="comment-icon" src="{{ asset('images/comment.png') }}" alt="コメントアイコン"></a></li>
                    @if (Auth::id() == $post->user_id)
                    <li>
                        <form action='{{ url("/posts/{$post->id}") }}' method="POST" class="delete-post-form">
                        @method("DELETE")
                        @csrf
                            <input type="hidden" name="redirect_url" value="{{ url()->full() }}">
                            <button type="submit" class="btn-link delete-link"><img src="{{ asset('images/delete.png') }}"></button>
                        </form>
                    </li>
                    @endif
                </ul>
                @else
                <ul class="post-detail-link">
                    <li>
                        <a href='javascript:void(0)' class="like-link _loginLink"><img class="like-icon" src="{{ asset('images/like.png') }}" alt="いいねアイコン画像"></a>
                    </li>
                    <li><a href='{{ url("/posts/{$post->id}") }}'><img class="comment-icon" src="{{ asset('images/comment.png') }}" alt="コメントアイコン画像"></a></li>
                </ul>
                @endauth
            </div>
        </div>

        @if ($loop->iteration % 5 == 0)
        <div class="ad">
            <center>スポンサーリンク(広告)</center>
            @if ($loop->iteration == 5)
                <!-- 店舗詳細コンテンツ間１ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="6716904327"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
            @elseif ($loop->iteration == 10)
                <!-- 店舗詳細コンテンツ間２ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="2777659316"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
            @endif
        </div>
        @endif

    @endforeach
    </div>
    {{ $posts->links('common.pagination') }}

    <div class="ad">
        <center>スポンサーリンク(広告)</center>
        <!-- 店舗詳細フッター -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-4702990894338882"
             data-ad-slot="9151495970"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
    </div>
</div>

@endsection
