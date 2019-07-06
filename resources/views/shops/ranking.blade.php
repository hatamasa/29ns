@extends('layouts.app')

@section('style')
<link href='{{ "@addtimestamp(css/shops/ranking.css)" }}' rel="stylesheet">
@endsection

@section('script')
@endsection

@section('content')
<div>
    @include('common.landing_regist')

    <div class="block-head">
        <h1>人気店舗ランキング</h1>
    </div>

    <div class="block-body">
    @foreach ($shops as $shop)
        @include ('common.shop_ranking', ['shop' => $shop, 'offset' => $offset])
    @endforeach
    </div>
    {{ $shops->links('common.pagination') }}
</div>

@endsection
