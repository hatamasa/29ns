@extends('layouts.app')

@section('style')
<link href="{{ asset('css/auth.css') }}" rel="stylesheet">
@endsection

@section('content')
<div>
    <div class="content-head">
        <div class="page-head">アカウントが仮登録のままです</div>
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
@endsection
