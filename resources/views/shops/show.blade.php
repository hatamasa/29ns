@extends('layouts.app')

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
    <div class="block-head user-thumbnail">
        @if ($user->thumbnail_url)
        <img alt="" src="{{ $user->thumbnail_url }}" class="icon">
        @elseif ($user->sex == 1)
        <img alt="" src="{{ asset('/images/man.png') }}" class="icon">
        @elseif ($user->sex == 2)
        <img alt="" src="{{ asset('/images/woman.png') }}" class="icon">
        @endif
        <a href='{{ url("/posts/create?shop_cd={$shop["id"]}") }}' class="icon">
            <div>このお店を肉ログする</div>
        </a>
    </div>
    <div class="block-body">
        <div class="card shop-detail">
            <div class="card-head">
                <div class="card-title">
                    <h2>{{ $shop['name'] }}</h2>
                    <div>{{ (isset($shop['score']) && !empty($shop['score'])) ? $shop['score'] : 5 }}点</div>
                </div>
                <div class="star-wrap shop-like" data-shop_cd="{{ $shop['id'] }}">
                @if ($shop['is_liked'] ?? false)
                <i class="fas fa-star fa-lg"></i>
                @else
                <i class="far fa-star fa-lg"></i>
                @endif
            </div>
            </form>
            </div>
            <div class="card-body">
                <div class="shop-img">
                    <img alt="" src="{{ !empty($shop['image_url']['shop_image1']) ? $shop['image_url']['shop_image1'] : asset('images/shop.png') }}">
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

    <div class="ad">
        <script type="text/javascript">
        var nend_params = {"media":61795,"site":324943,"spot":963964,"type":1,"oriented":1};
        </script>
        <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
    </div>

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
                                <a href='{{ url("/users/{$post->user_id}")}}'>
                                    @if ($user->thumbnail_url)
                                    <img alt="" src="{{ $post->user_thumbnail_url }}" class="icon">
                                    @elseif ($user->sex == 1)
                                    <img alt="" src="{{ asset('/images/man.png') }}" class="icon">
                                    @elseif ($user->sex == 2)
                                    <img alt="" src="{{ asset('/images/woman.png') }}" class="icon">
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
                    <li class="like-disp">{{ $post->like_count }}いいね</li>
                    <a href='{{ url("/posts/{$post->id}") }}'><li>コメント{{ $post->comment_count }}件</li></a>
                </ul>
                @auth
                <ul class="post-detail-link">
                    <li>
                    @if ($post->is_liked)
                    <a href='javascript:void(0)' class="like liked" data-post_id="{{ $post->id }}">いいね済</a>
                    @else
                    <a href='javascript:void(0)' class="like" data-post_id="{{ $post->id }}">いいね</a>
                    @endif
                    </li>
                    <a href='{{ url("/posts/{$post->id}") }}'><li>コメントする</li></a>
                    @if (Auth::id() == $post->user_id)
                    <li>
                        <form action='{{ url("/posts/{$post->id}") }}' method="POST" class="delete-post-form">
                        @method("DELETE")
                        @csrf
                            <button type="submit" class="btn-link delete-link">削除する</button>
                        </form>
                    </li>
                    @endif
                </ul>
                @endauth
            </div>
        </div>

        @if ($loop->iteration % 5 == 0)
        <div class="ad">
            @if ($loop->iteration == 5)
            <script type="text/javascript">
            var nend_params = {"media":61795,"site":324943,"spot":963965,"type":1,"oriented":1};
            </script>
            <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @elseif ($loop->iteration == 10)
            <script type="text/javascript">
            var nend_params = {"media":61795,"site":324943,"spot":963966,"type":1,"oriented":1};
            </script>
            <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @endif
        </div>
        @endif

    @endforeach
    </div>
    {{ $posts->links('common.pagination') }}

    <div class="ad">
        <script type="text/javascript">
        var nend_params = {"media":61795,"site":324943,"spot":963967,"type":1,"oriented":1};
        </script>
        <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
    </div>
</div>

@endsection
