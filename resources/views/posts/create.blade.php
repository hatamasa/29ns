@extends('layouts.app')

@section('style')
<link href="{{ asset('css/posts/create.css') }}" rel="stylesheet">
<link href='{{ "@addtimestamp(css/posts/create.css)" }}' rel="stylesheet">
@endsection

@section('script')
<script src="{{ asset('js/posts.js') }}"></script>
@endsection

@section('content')
<div>
    <div class="block-head">
        <h1>肉ログを投稿</h1>
    </div>

    <div class="block-body">
        <div class="card">
            <h2 class="card-title">
                {{ $shop['name'] }}
            </h2>
            <div class="card-body">
                <div class="shop-img">
                    <img alt="" src="{{ !empty($shop['image_url']['shop_image1']) ? $shop['image_url']['shop_image1'] : asset('images/shop.png') }}">
                </div>
                <div class="shop-text">
                    <ul>
                        <li>{{ $shop['score'] ?? 5 }}点</li>
                        <li>{{ $shop['post_count'] ?? 0 }}件の肉ログ / {{ $shop['like_count'] ?? 0 }}件のお気に入り</li>
                        <li>{{ $shop['access']['line'] }} {{ $shop['access']['station'] }} 徒歩{{ $shop['access']['walk'] }}分 {{ $shop['access']['note'] }}</li>
                        @empty ($shop['budget'])
                        @else
                        <li>予算 ¥{{ $shop['budget'] }}</li>
                        @endempty
                    </ul>
                </div>
            </div>
        </div>
        <form action="{{ url('/posts') }}" id="post-from" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="post">
                <div class="selective">
                    <div>
                        <span>点数</span>
                        <label class="select-parent">
                            {{ Form::select(
                                'score',
                                array_reverse(Config::get('const.post.score_list')),
                                session('score') ?? null,
                                ['id' => 'score', 'required'])
                            }}
                        </label>
                        点
                    </div>
                    <div>
                        <span>訪問回数</span>
                        <label class="select-parent">
                            {{ Form::select(
                                'visit_count',
                                Config::get('const.post.visit_count'),
                                session('visit_count') ?? null,
                                ['id' => 'visit_count', 'required'])
                            }}
                        </label>
                        回
                    </div>
                </div>
                <input name="title" id="title" class="title form-control" value="{{ session('title') ?? '' }}" placeholder="タイトルを入力..." required>
                <textarea name="contents" id="contents" class="contents form-control" placeholder='{!! Config::get("const.post.example") !!}' required>{{ session('contents') ?? '' }}</textarea>
            </div>
            <div class="file-list">
                <label class="preview-area" id="preview-file1">
                    <span>写真</span>
                    <input type="file" id="file1" class="img" name="files[]" src="{{ session('file')[0] ?? '' }}" accept="image/png, image/jpg, image/jpeg">
                </label>
                <label class="preview-area" id="preview-file2">
                    <span>写真</span>
                    <input type="file" id="file2" class="img" name="files[]" src="{{ session('file')[1] ?? '' }}" accept="image/png, image/jpg, image/jpeg">
                </label>
                <label class="preview-area" id="preview-file3">
                    <span>写真</span>
                    <input type="file" id="file3" class="img" name="files[]" src="{{ session('file')[2] ?? '' }}" accept="image/png, image/jpg, image/jpeg">
                </label>
            </div>
            <input type="hidden" name="shop_cd" value="{{ $shop['id'] ?? session('shop_cd') }}">
            <button type="submit" class="btn btn-primary btn-block">投稿する</button>
            <a href="{{ url()->previous() }}" class="btn btn-default btn-block">キャンセル</a>
        </form>
    </div>
</div>
@endsection
