@extends('layouts.app')

@section('metatitle')
<title>東京肉NSアカウント仮登録通知</title>
@endsection

@section('description')
<meta name="description" content="本登録を行うと東京の焼肉店が探せるグルメサイト[東京肉NS]でお気に入り、レビューができるようになります。気になるお店は星マークからお気に入りしてマイページでチェック！他のユーザのお気に入り店やレビューもチェックできる！">
@endsection

@section('style')
<link href='{{ "@addtimestamp(css/auth.css)" }}' rel="stylesheet">
@endsection

@section('title', '新規ユーザ登録')

@section('content')
<div>
    <div class="content-head">
        <p class="page-head">アカウントが仮登録のままです</p>
    </div>
    <div class="content-body">
        @if (session('resent'))
            <div class="alert alert-success" role="alert">
                登録されたアドレス宛にメールを送信しました。
            </div>
        @endif
        <p>ここから先は本登録をする必要があります。送信済みのメール本文のリンクから会員登録を完了させてください。</p>
        <a href="{{ route('verification.resend') }}" class="btn btn-primary btn-block">メールを再送信する</a>.
    </div>
</div>

<div class="ad">
    <center>スポンサーリンク(広告)</center>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- メール認証確認フッター -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-4702990894338882"
         data-ad-slot="1979582964"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
</div>

@endsection
