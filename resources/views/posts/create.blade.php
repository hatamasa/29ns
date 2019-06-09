@extends('layouts.app')

@section('style')
<link href="{{ asset('css/posts/create.css') }}" rel="stylesheet">
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
        <form action="{{ url('/posts') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="post">
                <div class="selective">
                    <div>
                        <span>点数</span>
                        <labal class="select-parent">
                            {{ Form::select('score', array_reverse(Config::get('const.post.score_list'))) }}
                        </label>
                        点
                    </div>
                    <div>
                        <span>訪問回数</span>
                        <labal class="select-parent">
                            {{ Form::select('visit_count', Config::get('const.post.visit_count')) }}
                        </label>
                        回
                    </div>
                </div>
                <div class="title">
                    <input type="text" name="title" class="form-control" value="" placeholder="タイトルを入力...">
                </div>
                <div class="contents">
                    <textarea name="contents" class="form-control" placeholder='{!! Config::get("const.post.example") !!}'></textarea>
                </div>
            </div>
            <div class="file-list">
                <div class="preview-area" id="preview-file1"><span></span>写真</div>
                <div class="preview-area" id="preview-file2"><span></span>写真</div>
                <div class="preview-area" id="preview-file3"><span></span>写真</div>
                <input type="file" id="file1" name="file[]">
                <input type="file" id="file2" name="file[]">
                <input type="file" id="file3" name="file[]">
            </div>
            <button type="submit" class="btn btn-primary btn-block">投稿する</button>
            <a href="{{ url()->previous() }}" class="btn btn-default btn-block">キャンセル</a>
        </form>
    </div>
</div>
@endsection
