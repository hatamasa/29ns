@extends('layouts.app')

@section('style')
<link href="{{ asset('css/posts.css') }}" rel="stylesheet">
@endsection

@section('script')
{{--<script src="{{ asset('js/posts.js') }}"></script>--}}
@endsection

@section('content')

<div class="card">
    <div class="card-title"><a href='{{ url("/shops/{$post->shop_cd}") }}'>{{ $post->shop_name }}</a></div>
    <div class="card-body">
        <div class="post-img">
            <img alt="" src="{{ $post->img_url_1 ?? $post->shop_img_url ?? asset('images/shop.png') }}">
        </div>
        <div class="post-text">
            <ul>
                <li class="post-text-top">
                    <p>{{ $post->score }}点</p>
                    <p>
                        <a href='{{ url("/users/{$post->user_id}")}}'>
                            <img alt="" src="{{ $post->user_thumbnail_url }}">{{ $post->user_name }}
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
    </div>
    <div class="card-body-footer">
        <ul class="post-text-under">
            <li>{{ $post->like_count }}いいね</li>
            <li>コメント{{ $post->comment_count }}件</li>
        </ul>
        @auth
        <ul class="post-detail-link">
            <li><a href='javascript:void(0)' class="like">いいね</a></li>
        </ul>
        @endauth
    </div>
</div>

@foreach ($post_comments as $post_comment)
    <div class="comment">
        @if ($post_comment->thumbnail_url)
        <img alt="" src="{{ $post_comment->thumbnail_url }}">
        @elseif ($post_comment->sex == 1)
        <img alt="" src="{{ asset('/images/man.png') }}">
        @elseif ($post_comment->sex == 2)
        <img alt="" src="{{ asset('/images/woman.png') }}">
        @endif
        <div class="comment-text">
            <a href="{{ url('/user/{$post_comment->user_id}') }}"><span>{{ $post_comment->name }}</span></a><span>@time_diff($post_comment->created_at)</span><br>
            {{ $post_comment->contents }}
        </div>
    </div>
@endforeach
    <form action='{{ url("/postsComment/store") }}' method="POST">
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
            <input type="hidden" name="post_id" value="{{ $post->id }}">
        </div>
    </form>
@endsection
