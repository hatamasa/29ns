@extends('layouts.app')

@section('style')
<link href='{{ "@addtimestamp(css/users/edit.css)" }}' rel="stylesheet">
@endsection

@section('script')
<script>
    document.getElementById('user-img').onchange = function(evt) {
        let strFileInfo = evt.target.files[0];

        if(strFileInfo && strFileInfo.type.match('image.*')){
            fileReader = new FileReader();
            fileReader.onload = event => {
                evt.target.previousElementSibling.src = event.target.result;
            }
            fileReader.readAsDataURL(strFileInfo);
        } else {
            alert('プレビューできません。不正な画像ファイルがアップロードされました。');
        }
    }
</script>
@endsection

@section('content')
<div>
    <div class="block-head">
        <p>{{ $users->name }}さんのページ</p>
    </div>
    <form action="{{ url('/users/update') }}" id="user-from" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="block-body">
            <div class="user-card">
                <div class="card-body">
                    <div class="user-img">
                        @if ($users->thumbnail_url)
                        <img alt="" src="{{ $users->thumbnail_url }}" class="icon">
                        @elseif ($users->sex == 1)
                        <img alt="" src="{{ asset('/images/man.png') }}" class="icon">
                        @elseif ($users->sex == 2)
                        <img alt="" src="{{ asset('/images/woman.png') }}" class="icon">
                        @endif
                        <input type="file" name="file" id="user-img" accept="image/png, image/jpeg">
                        <label for="user-img">写真を変更する</label>
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


@endsection
