@extends('layouts.app')

@section('style')
<link href="{{ asset('css/auth.css') }}" rel="stylesheet">
@endsection

@section('content')
<div>
    <div class="content-head">
        <div class="page-title">新規ユーザ登録</div>
    </div>
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
                    name="sex" value="1" required autofocus @if (old('sex') == 1) checked="checked" @endif>
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
                <span>誕生年月</span>
                <input id="birth_y" type="tel" class="year form-control{{ $errors->has('birth_y') ? ' is-invalid' : '' }}"
                    name="birth_y" value="{{ old('birth_y') }}" required placeholder="年" maxlength="4"><span>年</span>
                <input id="birth_m" type="tel" class="month form-control{{ $errors->has('birth_m') ? ' is-invalid' : '' }}"
                    name="birth_m" value="{{ old('birth_m') }}" required placeholder="月" maxlength="2"><span>月</span>
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
@endsection
