@extends('layouts.app')

@section('style')
<link href='{{ "@addtimestamp(css/posts.css)" }}' rel="stylesheet">
@endsection

@section('script')
<script>
</script>
@endsection

@section('content')
<div>
    @include('common.landing_regist')

    <div class="block-head">
        <p>みんなの29ログ</p>
    </div>

    <div class="block-body">
    @foreach ($posts as $post)
        @include ('common.post', ['post' => $post])
    @endforeach
    </div>
    {{ $posts->links('common.pagination') }}
</div>
@endsection
