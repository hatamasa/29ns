@extends('layouts.app')

@section('style')
<link href="{{ asset('css/posts/create.css') }}" rel="stylesheet">
<link href='{{ "@addtimestamp(css/posts/create.css)" }}' rel="stylesheet">
@endsection

@section('script')
<script>
    const THUMB_SIZE = 200;
    /**
     * 投稿作成
     */
    [].forEach.call(document.getElementsByClassName("img"), elem => {
        elem.addEventListener("change", evt => preview(evt));
    });
    function preview(evt) {
        let file = evt.target;
        let strFileInfo = evt.target.files[0];
        if(!strFileInfo) return;

        let imgArea = evt.target.previousElementSibling;
        let previewArea = evt.target.parentNode;

        if(strFileInfo && strFileInfo.type.match('image.*')){
            showLoading();
            let filename = evt.target.files[0].name;
            let image = new Image();
            image.onload = function() {
                // canvasに描画してサイズを下げる
                let cnv = document.createElement('canvas');
                let ratio = image.naturalWidth / image.naturalHeight;
                if (ratio == 1) {
                    cnv.width = THUMB_SIZE;
                    cnv.height = THUMB_SIZE;
                } else if (ratio > 1) {
                    cnv.width = THUMB_SIZE * ratio;
                    cnv.height = THUMB_SIZE;
                } else if (ratio < 1) {
                    cnv.width = THUMB_SIZE;
                    cnv.height = THUMB_SIZE / ratio;
                }
                let ctx = cnv.getContext('2d');
                ctx.drawImage(image, 0, 0, cnv.width, cnv.height);
                if(cnv.msToBlob) {
                    preview.src = URL.createObjectURL(cnv.msToBlob());
                } else {
                    cnv.toBlob(blob => {
                        preview.src = URL.createObjectURL(blob);
                    }, 'image/png'); // msToBlobと合わせるためpngに設定
                }
                // ファイルを送信
                let base64 = cnv.toDataURL('image/png');
                sendTmpImg(evt.target.id, filename, base64);
                // プレビューを設定
                imgArea.remove();
                previewArea.classList.add("uploaded");
                let preview = document.createElement("img");
                preview.classList.add("preview");
                previewArea.insertBefore(preview, file);
            }
            image.src = URL.createObjectURL(strFileInfo);
        } else if (strFileInfo) {
            alert("不正な画像ファイルがアップロードされました");
        }
    };

    function sendTmpImg(id, filename, base64) {
        $.ajax({
            type: 'POST',
            url: "/posts/image_update",
            data: { file: base64, filename: filename },
        })
        .done(result => {
            document.getElementById('tmp-'+id).value = result.path;
        })
        .fail(error => {
            alert(error.responseJSON.message);
        })
        .always(() => {
            hiddenLoading();
        });
    }

    function showLoading() {
        let loading = document.createElement('div');
        loading.id = 'loading';
        let img = document.createElement('img');
        img.src = '/images/loading.gif';
        loading.appendChild(img);
        document.getElementsByTagName('body')[0].appendChild(loading);
        document.getElementsByTagName('body')[0].style.overflowY = 'hidden';
    }
    function hiddenLoading() {
        document.getElementById('loading').remove();
        document.getElementsByTagName('body')[0].style.overflowY = 'scroll';
    }

    $("#post-from").submit(evt => {
        let score = document.getElementById("score");
        let visit_count = document.getElementById("visit_count");
        let title = document.getElementById("title");
        let contents = document.getElementById("contents");
        score.classList.remove("error");
        visit_count.classList.remove("error");
        title.classList.remove("error");
        contents.classList.remove("error");

        if (score.value === undefined) {
            score.classList.add("error");
            alert("点数を入力してください");
            return false;
        }
        if (visit_count.value === undefined) {
            visit_count.classList.add("error");
            alert("訪問回数を入力してください");
            return false;
        }
        if (title.value.length > 100) {
            title.classList.add("error");
            alert("タイトルは100文字以内で入力してください");
            return false;
        }
        if (contents.value.length > 1000) {
            contents.classList.add("error");
            alert("本文は1000文字以内で入力してください");
            return false;
        }

        $(evt.target).find("[type='submit']").prop("disabled", true);
    });

</script>
@endsection

@section('title', '肉ログを投稿')

@section('content')
<div>
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
                    <input type="file" id="file1" class="img" accept="image/png, image/jpg, image/jpeg">
                    <input type="hidden" id="tmp-file1" name="files[]" value="">
                </label>
                <label class="preview-area" id="preview-file2">
                    <span>写真</span>
                    <input type="file" id="file2" class="img" accept="image/png, image/jpg, image/jpeg">
                    <input type="hidden" id="tmp-file2" name="files[]" value="">
                </label>
                <label class="preview-area" id="preview-file3">
                    <span>写真</span>
                    <input type="file" id="file3" class="img" accept="image/png, image/jpg, image/jpeg">
                    <input type="hidden" id="tmp-file3" name="files[]" value="">
                </label>
            </div>
            <input type="hidden" name="shop_cd" value="{{ $shop['id'] ?? session('shop_cd') }}">
            <button type="submit" class="btn btn-primary btn-block">投稿する</button>
            <a href="{{ url()->previous() }}" class="btn btn-default btn-block">キャンセル</a>
        </form>
    </div>
</div>

<div class="ad">
    <center>スポンサーリンク(広告)</center>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- 投稿登録フッター -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-4702990894338882"
         data-ad-slot="5643920832"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
    <script>
         (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
</div>
@endsection
