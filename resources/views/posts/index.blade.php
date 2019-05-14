@extends('layouts.app')

@section('style')
<link href="{{ asset('css/posts.css') }}" rel="stylesheet">
@endsection

@section('script')
<script src="{{ asset('js/jquery.inview.min.js') }}"></script>
<script src="{{ asset('js/posts.js') }}"></script>
@endsection

@section('content')
<div class="post-area-wap row">
    <img src="{{ $user->thumbnail_url ?? asset('images/user.png') }}">
    <a href="{{ url('/post/create') }}" class="post-area">つぶやきを投稿</a>
</div>
<div class="row justify-content-center">
    <div id="postList" class="col-md-8">
    @if (count($posts) > 1)
        @foreach ($posts as $post)
        <div class="card">
            <div class="card-header">
                <img src="{{ $post->thumbnail_url ?? asset('images/user.png') }}">
                <div>
                @isset ($post->groups_title)
                    <p>{{ $post->users_name }}が{{ $post->groups_title }}に投稿しました</p>
                @else
                    <p>{{ $post->users_name }}が投稿しました</p>
                @endisset
                    <p>@datetime($post->posts_created_at)</p>
                </div>
            </div>
            <div class="card-body">
                <div>{{ $post->posts_title }}</div>
                <div>{{ $post->posts_contents }}</div>
                <div>
                    <p>○○いいね!</p>
                    <p>コメント○○件</p>
                </div>
                <div>
                    <a href="javascript:void(0)">いいね!</a>
                    <a href="javascript:void(0)">コメントする</a>
                </div>
            </div>
        </div>
        @endforeach
        <div id="inviewLoad"></div>
    @else
        <div class="card">
            <div class="card-header">投稿がありません</div>
            <div class="card-body">
                <p>ユーザをフォローしたりグループに入って投稿を確認しよう！</p>
                <a href="{{ url('/users') }}" class="btn btn-primary btn-block">ユーザを探す</a>
                <a href="{{ url('/groups') }}" class="btn btn-primary btn-block">グループを探す</a>
            </div>
        </div>
    @endif
    </div>
</div>
@endsection
