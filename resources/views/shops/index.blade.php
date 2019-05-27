@extends('layouts.app')

@section('style')
<link href="{{ asset('css/shops.css') }}" rel="stylesheet">
@endsection

@section('script')
<script src="{{ asset('js/jquery.inview.min.js') }}"></script>
<script src="{{ asset('js/shops.js') }}"></script>
@endsection

@section('content')
<div>
    @include('common.landing_regist')

    <div class="block-head">
        <p>検索結果</p>
    </div>
    <div class="block-body">
    @foreach ($shops as $shop)
        <div class="card">
            <div class="card-title">{{ $shop['name'] }}</div>
            <div class="card-body">
                <div class="popularity-img">
                    <img alt="" src="{{ $shop['image_url']['shop_image1'] ?? asset('images/shop.png') }}">
                </div>
                <div class="popularity-text">
                    <ul>
                        <li></li>
                        <li>{{ $shop['access']['line'] }} {{ $shop['access']['station'] }} 徒歩{{ $shop['access']['walk'] }}分 {{ $shop['access']['note'] }}</li>
                        <li>予算 ¥{{ $shop['budget'] }}</li>
                    </ul>
                </div>
            </div>
        </div>
    @endforeach
    </div>

</div>
@endsection
