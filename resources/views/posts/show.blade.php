@extends('layouts.app')

@section('metatitle')
<title>{{ $post->shop_name }}の口コミ詳細画面 - 東京肉NS</title>
@endsection

@section('description')
<meta name="description" content="東京肉NSユーザの{{ $post->user_name }}さんの{{ $post->shop_name }}への口コミ詳細画面です。東京肉NSは焼肉に特化したグルメサイトです！気になるお店は星マークからお気に入りしてマイページでチェック！他のユーザのお気に入り店や口コミもチェックできる！">
@endsection

@section('style')
<link href='{{ "@addtimestamp(css/posts/show.css)" }}' rel="stylesheet">
@endsection

@section('script')
<script>
    // コメント投稿サブミット
    $("#post-comment-form").submit(evt => {
        let comment_text = document.getElementById("comment-text");
        if(comment_text.value.trim() === "") {
            comment_text.classList.add("error");
            comment_text.placeholder = "コメントを入力してください";
            alert("コメントを入力してください");
            return false;
        }

        $(evt.target).find("[type='submit']").prop("disabled", true);
    });

    // コメント削除サブミット
    $(".comment-del-form").submit(evt => {
        $(evt.target).find("[type='submit']").prop("disabled", true);
    });

    // 登録削除サブミット
    $('#delete-post-form').submit(evt => {
        if (! confirm("投稿を削除しますか？")) return false;
        $(evt.target).prop("disabled", true);
    });

</script>
@endsection

@section('title', '肉ログ詳細')

@section('content')

<div>
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-format="fluid"
         data-ad-layout-key="-fb+5w+4e-db+86"
         data-ad-client="ca-pub-4702990894338882"
         data-ad-slot="2361616529"></ins>
</div>

<div class="card">
    <h2 class="card-title"><a href='{{ url("/shops/{$post->shop_cd}") }}'>{{ $post->shop_name }}</a></h2>
    <div class="card-body">
        <div class="post-text">
            <ul>
                <li class="post-text-top">
                    <p>{{ $post->score }}点</p>
                    <p>@time_diff($post->post_created_at)</p>
                </li>
                <li class="post-text-top">
                    @if ($post->user_id == 0)
                    <p class="name">
                        <img alt="" src="{{ asset('/images/user.png') }}" class="icon" alt="ユーザデフォルトプロフィール画像">
                        {{ strpos($post->title, '/') ? explode("/", $post->title)[1] : '' }}
                    </p>
                    @else
                    <a href='{{ url("/users/{$post->user_id}")}}'>
                        <p class="name">
                            @if (isset($post->thumbnail_url) && !empty($post->thumbnail_url))
                            <img alt="" src="{{ $post->user_thumbnail_url }}" class="icon" alt="ユーザプロフィール画像">
                            @else
                            <img alt="" src="{{ asset('/images/user.png') }}" class="icon" alt="ユーザデフォルトプロフィール画像">
                            @endif
                            {{ $post->user_name }}
                        </p>
                    </a>
                    @endif
                </li>
                <li class="post-text-center">
                    @empty ($post->title)
                    <p>{{ $post->shop_name }}へ行きました！</p>
                    @else
                    <p>{{ $post->title }}</p>
                    @endif
                    <p>{!! nl2br(e($post->contents)) !!}</p>
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
            <li><img class="comment-icon" src="{{ asset('images/comment_black.png') }}" alt="コメントアイコン画像">{{ $post->comment_count }}</li>
        </ul>
        @auth
        <ul class="post-detail-link">
            <li>
                @if ($post->is_liked)
                <a href='javascript:void(0)' class="like-link _liked" data-post_id="{{ $post->id }}"><img class="like-icon _liked" src="{{ asset('images/like.png') }}" alt="いいねアイコン画像">済</a>
                @else
                <a href='javascript:void(0)' class="like-link _notlike" data-post_id="{{ $post->id }}"><img class="like-icon _notlike" src="{{ asset('images/like.png') }}" alt="いいねアイコン画像"></a>
                @endif
            </li>
        </ul>
        @else
        <ul class="post-detail-link">
            <li>
                <a href='javascript:void(0)' class="like-link _loginLink"><img class="like-icon" src="{{ asset('images/like.png') }}" alt="いいねアイコン画像"></a>
            </li>
        </ul>
        @endauth
    </div>
</div>

@foreach ($post_comments as $post_comment)
    <div class="comment">
        <a href='{{ url("/users/{$post_comment->user_id}") }}'>
            @if (isset($post_comment->thumbnail_url) && !empty($post_comment->thumbnail_url))
            <img alt="ユーザプロフィール画像" src="{{ $post_comment->thumbnail_url }}" class="icon">
            @else
            <img alt="ユーザデフォルトプロフィール画像" src="{{ asset('/images/user.png') }}" class="icon">
            @endif
        </a>
        <div class="comment-text">
            <a href='{{ url("/users/{$post_comment->user_id}") }}'><span>{{ $post_comment->name }}</span></a>
            <span>@time_diff($post_comment->created_at)</span>
            <div>{{ $post_comment->contents }}</div>
        </div>
        @if (Auth::id() == $post_comment->user_id)
        <form action='{{ url("/post_comments/{$post_comment->id}") }}' method="POST" class="comment-del-form">
            @method("DELETE")
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">削除</button>
        </form>
        @endif
    </div>
@endforeach

@auth
<form action='{{ url("/post_comments") }}' id="post-comment-form" method="POST">
    @csrf
    <div class="comment">
        @if (isset($user->thumbnail_url) && !empty($user->thumbnail_url))
        <img alt="ユーザプロフィール画像" src="{{ $user->thumbnail_url }}">
        @else
        <img alt="" src="{{ asset('/images/user.png') }}" class="icon" alt="ユーザデフォルトプロフィール画像">
        @endif
        <textarea name="contents" id="comment-text" class="comment-text comment-input text-required" value="" data-name="コメント" placeholder="コメントする..."></textarea>
        <input type="hidden" name="post_id" value="{{ $post->id }}">
        <button type="submit" class="btn btn-primary btn-sm">コメント</button>
    </div>
</form>
@if ($post->user_id == Auth::id())
<div>
    <form action='{{ url("/posts/{$post->id}") }}' method="POST" id="delete-post-form">
    @method("DELETE")
    @csrf
        <input type="hidden" name="redirect_url" value="{{ $redirect_url }}">
        <button type="submit" class="btn btn-danger btn-block">削除</button>
    </form>
</div>
@endif
@else
    @include('common.landing_regist')
@endauth

<div class="ad">
    <center>スポンサーリンク(広告)</center>
    <!-- 投稿詳細フッター -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-4702990894338882"
         data-ad-slot="8489576026"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
</div>
@endsection
