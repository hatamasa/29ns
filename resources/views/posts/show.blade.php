@extends('layouts.app')

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

<div class="ad">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-format="fluid"
         data-ad-layout-key="-fb+5w+4e-db+86"
         data-ad-client="ca-pub-4702990894338882"
         data-ad-slot="2361616529"></ins>
    <script>
         (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
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
                    <a href='{{ url("/users/{$post->user_id}")}}'>
                        <p class="name">
                            @if ($post->user_thumbnail_url)
                            <img alt="" src="{{ $post->user_thumbnail_url }}" class="icon">
                            @elseif ($post->user_sex == 1)
                            <img alt="" src="{{ asset('/images/man.png') }}" class="icon">
                            @elseif ($post->user_sex == 2)
                            <img alt="" src="{{ asset('/images/woman.png') }}" class="icon">
                            @endif
                            {{ $post->user_name }}
                        </p>
                    </a>
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
            <li class="like-disp"><img class="like-icon" src="{{ asset('images/like_black.png') }}">{{ $post->like_count }}</li>
            <li><img class="comment-icon" src="{{ asset('images/comment_black.png') }}">{{ $post->comment_count }}</li>
        </ul>
        @auth
        <ul class="post-detail-link">
            <li>
            @if ($post->is_liked)
            <a href='javascript:void(0)' class="like liked" data-post_id="{{ $post->id }}"><img class="like-icon liked" src="{{ asset('images/like.png') }}">済</a>
            @else
            <a href='javascript:void(0)' class="like" data-post_id="{{ $post->id }}"><img class="like-icon like" src="{{ asset('images/like.png') }}"></a>
            @endif
            </li>
        </ul>
        @endauth
    </div>
</div>

@foreach ($post_comments as $post_comment)
    <div class="comment">
        <a href='{{ url("/users/{$post_comment->user_id}") }}'>
            @if ($post_comment->thumbnail_url)
            <img alt="" src="{{ $post_comment->thumbnail_url }}" class="icon">
            @elseif ($post_comment->sex == 1)
            <img alt="" src="{{ asset('/images/man.png') }}" class="icon">
            @elseif ($post_comment->sex == 2)
            <img alt="" src="{{ asset('/images/woman.png') }}" class="icon">
            @endif
        </a>
        <div class="comment-text">
            <a href='{{ url("/users/{$post_comment->user_id}") }}'><span>{{ $post_comment->name }}</span></a><span>@time_diff($post_comment->created_at)</span>
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

<form action='{{ url("/post_comments") }}' id="post-comment-form" method="POST">
    @csrf
    <div class="comment">
        @if ($user->thumbnail_url)
        <img alt="" src="{{ $user->thumbnail_url }}">
        @elseif ($user->sex == 1)
        <img alt="" src="{{ asset('/images/man.png') }}">
        @elseif ($user->sex == 2)
        <img alt="" src="{{ asset('/images/woman.png') }}">
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

<div class="ad">
    <center>スポンサーリンク(広告)</center>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- 投稿詳細フッター -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-4702990894338882"
         data-ad-slot="8489576026"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
    <script>
         (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
</div>
@endsection
