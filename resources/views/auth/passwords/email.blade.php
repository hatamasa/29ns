@extends('layouts.app')

@section('metatitle')
<title>東京肉NSパスワードリセットメール送信完了</title>
@endsection

@section('description')
<meta name="description" content="ログインすると東京の焼肉店が探せるグルメサイト[東京肉NS]でお気に入り、レビューができるようになります。気になるお店は星マークからお気に入りしてマイページでチェック！他のユーザのお気に入り店やレビューもチェックできる！">
@endsection

@section('style')
<link href='{{ "@addtimestamp(css/auth.css)" }}' rel="stylesheet">
@endsection

@section('title', 'パスワードリセット')

@section('content')
<div>
    <div class="content-body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                パスワードをリセットするリンクを送信しました。メールから再度パスワード設定をお願いいたします。
            </div>
        @endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
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
                <button type="submit" class="btn btn-primary btn-block">メールを送信</button>
            </div>
        </form>
    </div>
</div>

<div class="ad">
    <center>スポンサーリンク(広告)</center>
    <!-- パスワードリセットフッター -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-4702990894338882"
         data-ad-slot="8381339783"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
</div>
@endsection
