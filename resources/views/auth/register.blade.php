@extends('layouts.app')

@section('metatitle')
<title>東京肉NS無料会員登録</title>
@endsection

@section('description')
<meta name="description" content="東京の焼肉店が探せるグルメサイト[東京肉NS]でお得なMyページが利用できるようになります。気になるお店は星マークからお気に入りしてマイページでチェック！他のユーザのお気に入り店やレビューもチェックできる！">
@endsection

@section('style')
<link href='{{ "@addtimestamp(css/auth.css)" }}' rel="stylesheet">
@endsection

@section('title', '新規ユーザ登録')

@section('content')
<div>
    <div class="content-body">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                    name="name" value="{{ old('name') }}" required autofocus placeholder="表示ユーザ名">
                @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <input id="man" type="radio" class="form-control{{ $errors->has('sex') ? ' is-invalid' : '' }}"
                    name="sex" value="1" required autofocus @if (old('sex') != 2) checked="checked" @endif>
                <label for="man" class="radioDefault">男性</label>
                <input id="woman" type="radio" class="form-control{{ $errors->has('sex') ? ' is-invalid' : '' }}"
                    name="sex" value="2" required autofocus @if (old('sex') == 2) checked="checked" @endif>
                <label for="woman" class="radioDefault">女性</label>
                @if ($errors->has('sex'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('sex') }}</strong>
                    </span>
                @endif
            </div>

            <div class="birth form-group">
                <div>
                    <span>誕生年月</span>
                    <label class="select-parent year">
                        {{ Form::selectRange('birth_y', 1920, 2019, old('birth_y') ?? 1990) }}
                    </label>
                    年
                    <label class="select-parent month">
                        {{ Form::selectRange('birth_m', 1, 12, old('birth_m')) }}
                    </label>
                    月
                </div>
                @if ($errors->has('birth_y'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('birth_y') }}</strong>
                    </span>
                @endif
                @if ($errors->has('birth_m'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('birth_m') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                    name="email" value="{{ old('email') }}" required placeholder="メールアドレス">
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                    name="password" required placeholder="パスワード">
                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="パスワード(確認)">
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">新規ユーザ登録</button>
            </div>
        </form>
    </div>
</div>

<div class="ad">
    <center>スポンサーリンク(広告)</center>
    <!-- 新規会員登録フッター -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-4702990894338882"
         data-ad-slot="3947695786"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
</div>
@endsection
