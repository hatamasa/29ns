@extends('layouts.app')

@section('style')
<link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endsection

@section('script')
<script>
</script>
@endsection

@section('content')
<div>
    <p>お店を探して29ログ(レビュー)をしよう！</p>
    @include('common.landing_regist')

    <div class="form-group search">
        <form method="get" name="search_form" action="{{ url('/shops') }}">
            <input type="text" id="_keyword" class="form-control" name="keyword" value="" placeholder="店名、駅名、住所など入力...">
            <button type="submit" class="btn btn-primary btn-sm">検索</button>
        </form>
    </div>

    <div class="form-group search-btn">
        <a href="{{ url('/search/station') }}" class="btn btn-default btn-lg" role="button">駅から<br>探す</a>
        <a href="{{ url('/search/area') }}" class="btn btn-default btn-lg">エリアから<br>探す</a>
        <a href="{{ url('/shops/near') }}" class="btn btn-default btn-lg">近くのお店を<br>探す</a>
    </div>

    <div class="form-group recently-post">
        <div class="block-head">
            <p>最新の29ログ</p>
            <a href="{{ url('/posts') }}">29ログをもっと見る→</a>
        </div>
        <div class="block-body">
        @foreach ($posts as $post)
            @include('common.post', ['post' => $post])
        @endforeach
        </div>
    </div>

    <div class="form-group">
        <div class="block-head">
            <p>人気のお店</p>
            <a href="{{ url('/shops/ranking') }}">人気のお店をもっと見る→</a>
        </div>
        <div class="block-body">
        @foreach ($shops as $shop)
            @include ('common.shop_ranking', ['shop' => $shop])
        @endforeach
        </div>
    </div>

</div>
@endsection
