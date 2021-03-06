@extends('layouts.app')

@section('metatitle')
<title>Myページ編集画面 - 東京肉NS</title>
@endsection

@section('description')
<meta name="description" content="Myページ編集画面です。レビュー時に表示する個人情報やMyページに公開されている情報を修正することができます。">
@endsection

@section('style')
<link href='{{ "@addtimestamp(css/users/edit.css)" }}' rel="stylesheet">
@endsection

@section('script')
<script>
    const THUMB_SIZE = 100;

    document.getElementById('user-img').onchange = function(evt) {
        let strFileInfo = evt.target.files[0];
        if(!strFileInfo) return;
        if(strFileInfo.type.match('image.*')){
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
                    evt.target.previousElementSibling.src = URL.createObjectURL(cnv.msToBlob());
                } else {
                    cnv.toBlob(blob => {
                        evt.target.previousElementSibling.src = URL.createObjectURL(blob);
                    }, 'image/png'); // msToBlobと合わせるためpngに設定
                }
                // ファイルを送信
                let base64 = cnv.toDataURL('image/png');
                sendTmpImg(filename, base64);
            }
            image.src = URL.createObjectURL(strFileInfo);

        } else {
            alert('不正な画像ファイルがアップロードされました。');
        }
    }

    function sendTmpImg(filename, base64) {
        $.ajax({
            type: 'POST',
            url: "/users/image_update",
            data: { file: base64, filename: filename },
        })
        .done(result => {
            document.getElementById('tmp-path').value = result.path;
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

    // ユーザ編集サブミット
    $("#user-form").submit(evt => {
        $(evt.target).find("[type='submit']").prop("disabled", true);
    });
</script>
@endsection

@section('title', "Myページ")

@section('content')
<div>
    <form action="{{ url('/users/update') }}" id="user-form" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="block-body">
            <div class="user-card">
                <div class="card-body">
                    <div class="user-img">
                        <canvas id="canvas" width="0" height="0"></canvas>
                        @if ($users->thumbnail_url)
                        <img alt="ユーザプロフィール画像" src="{{ $users->thumbnail_url }}" class="icon">
                        @elseif ($users->sex == 1)
                        <img alt="ユーザ男性デフォルトプロフィール画像" src="{{ asset('/images/man.png') }}" class="icon">
                        @elseif ($users->sex == 2)
                        <img alt="ユーザ女性デフォルトプロフィール画像" src="{{ asset('/images/woman.png') }}" class="icon">
                        @endif
                        <input type="file" id="user-img" accept="image/png, image/jpeg">
                        <label for="user-img">写真を変更する</label>
                        <input type="hidden" id="tmp-path" name="tmp-path" value="">
                    </div>
                    <div class="user-text">
                        <ul>
                            <li>{{ $users->email }}</li>
                            <li>{{ $users->name }} @if ($users->sex == 1) 男性 @else 女性 @endif</li>
                            <li>生年月日 {{ substr($user->birth_ym, 0, 4) }}年{{ substr($user->birth_ym, 4) }}月</li>
                            <li>{{ floor((date('Ym') - $user->birth_ym) / 1000) * 10 }}代</li>
                        </ul>
                    </div>
                </div>
            </div>
            @if ($errors->has('contents'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('contents') }}</strong>
                </span>
            @endif
            <textarea name="contents" maxlength="200" placeholder="{!! Config::get('const.user.example') !!}">{{ $users->contents }}</textarea>
        </div>

        <div>
            <input type="hidden" name="user_id" value="{{ $users->id }}">
            <button type="submit" class="btn btn-primary btn-block">保存</button>
            <a href='{{ url("/users/{$users->id}") }}' class="btn btn-default btn-block">キャンセル</a>
        </div>
    </form>
</div>

<div class="ad">
    <center>スポンサーリンク(広告)</center>
    <!-- ユーザ編集フッター -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-4702990894338882"
         data-ad-slot="8078512481"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
</div>

@endsection
