@extends('layouts.app')

@section('style')
<link href='{{ "@addtimestamp(css/posts.css)" }}' rel="stylesheet">
@endsection

@section('script')
<script>
</script>
@endsection

@section('title', 'みんなの肉ログ')

@section('content')
<div>
    @include('common.landing_regist')

    <div class="block-body">
    @foreach ($posts as $post)
        @include ('common.post', ['post' => $post])
    @endforeach
    </div>
    {{ $posts->links('common.pagination') }}
</div>
@endsection
