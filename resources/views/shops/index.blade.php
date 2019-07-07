@extends('layouts.app')

@section('style')
<link href='{{ "@addtimestamp(css/shops/index.css)" }}' rel="stylesheet">
@endsection

@section('script')
<script>
</script>
@endsection

@section('title', '店舗一覧')

@section('content')
<div>
    @include('common.landing_regist')

    <div class="block-head">
        <h2>{{ implode(",", $search_condition) }}の店舗</h2>
        <p>{{ $total_hit_count }}件</p>
    </div>

    <div class="block-body">
    @foreach ($shops as $shop)
        @include ('common.shop', ['shop' => $shop])
    @endforeach
    </div>
    {{ $shops->appends($input)->links('common.pagination') }}
</div>

@endsection
