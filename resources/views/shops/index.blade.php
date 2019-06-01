@extends('layouts.app')

@section('style')
<link href="{{ asset('css/shops.css') }}" rel="stylesheet">
@endsection

@section('script')
{{--<script src="{{ asset('js/shops.js') }}"></script>--}}
@endsection

@section('content')
<div>
    @include('common.landing_regist')

    <div class="block-head">
        <p>{{ implode(",", $search_condition) }}の店舗</p>
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
