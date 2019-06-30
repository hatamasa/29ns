@extends('layouts.app')

@section('style')
<link href='{{ "@addtimestamp(css/auth.css)" }}' rel="stylesheet">
@endsection

@section('content')
<div>
    <div class="content-head">
        <div class="page-title">ログイン</div>
    </div>
    <div class="content-body">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <input id="email" type="email"
                    class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                    name="email" value="{{ old('email') }}" required autofocus placeholder="メールアドレス">
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <input id="password" type="password"
                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                    name="password" required placeholder="パスワード">
                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group form-remenber">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember"{{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">ログインしたままにする</label>
                </div>
            </div>

            <div class="form-group form-login">
                <button type="submit" class="btn btn-primary btn-block">ログイン</button>
                <a class="btn btn-info btn-block" href="{{ route('register') }}">新規会員登録はこちら</a>
                <a href="{{ route('password.request') }}">パスワードを忘れた方はこちら</a>
            </div>
        </form>

    </div>
</div>
@endsection
