@extends('layouts.app')

@section('style')
<link href="{{ asset('css/posts/show.css') }}" rel="stylesheet">
@endsection

@section('script')
{{--<script src="{{ asset('js/posts.js') }}"></script>--}}
@endsection

@section('content')

<div class="card">
    <div class="card-title"><a href='{{ url("/shops/{$post->shop_cd}") }}'>{{ $post->shop_name }}</a></div>
    <div class="card-body">
        <div class="post-text">
            <ul>
                <li class="post-text-top">
                    <p>{{ $post->score }}点</p>
                    <a href='{{ url("/users/{$post->user_id}")}}'>
                        <p>
                            <img alt="" src="{{ $post->user_thumbnail_url }}">{{ $post->user_name }}
                        </p>
                    </a>
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
        </ul>
        @endauth
    </div>
</div>

@foreach ($post_comments as $post_comment)
    <div class="comment">
        <a href="{{ url('/user/{$post_comment->user_id}') }}">
            @if ($post_comment->thumbnail_url)
            <img alt="" src="{{ $post_comment->thumbnail_url }}">
            @elseif ($post_comment->sex == 1)
            <img alt="" src="{{ asset('/images/man.png') }}">
            @elseif ($post_comment->sex == 2)
            <img alt="" src="{{ asset('/images/woman.png') }}">
            @endif
        </a>
        <div class="comment-text">
            <a href="{{ url('/user/{$post_comment->user_id}') }}"><span>{{ $post_comment->name }}</span></a><span>@time_diff($post_comment->created_at)</span>
            <br>
            {{ $post_comment->contents }}
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

<form action='{{ url("/post_comments/{$post->id}") }}' method="POST">
    @csrf
    <div class="comment">
        @if ($user->thumbnail_url)
        <img alt="" src="{{ $user->thumbnail_url }}">
        @elseif ($user->sex == 1)
        <img alt="" src="{{ asset('/images/man.png') }}">
        @elseif ($user->sex == 2)
        <img alt="" src="{{ asset('/images/woman.png') }}">
        @endif
        <textarea name="contents" class="comment-text comment-input" value="" placeholder="コメントする..."></textarea>
        <button type="submit" class="btn btn-primary btn-sm">コメント</button>
    </div>
</form>

@endsection
