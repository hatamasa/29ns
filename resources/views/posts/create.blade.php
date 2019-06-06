@extends('layouts.app')

@section('style')
<link href="{{ asset('css/posts.css') }}" rel="stylesheet">
@endsection

@section('script')
{{--<script src="{{ asset('js/posts.js') }}"></script>--}}
@endsection

@section('content')
<div>
    <div class="block-head">
        <p>29ログを投稿</p>
    </div>

    <div class="block-body">
        <div class="card">
            <div class="card-title">
                {{ $shop['name'] }}
            </div>
            <div class="card-body">
                <div class="shop-img">
                    <img alt="" src="{{ !empty($shop['image_url']['shop_image1']) ? $shop['image_url']['shop_image1'] : asset('images/shop.png') }}">
                </div>
                <div class="shop-text">
                    <ul>
                        <li>{{ $shop['score'] ?? 5 }}点</li>
                        <li>{{ $shop['post_count'] ?? 0 }}件の29ログ / {{ $shop['like_count'] ?? 0 }}件のお気に入り</li>
                        <li>{{ $shop['access']['line'] }} {{ $shop['access']['station'] }} 徒歩{{ $shop['access']['walk'] }}分 {{ $shop['access']['note'] }}</li>
                        @empty ($shop['budget'])
                        @else
                        <li>予算 ¥{{ $shop['budget'] }}</li>
                        @endempty
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
