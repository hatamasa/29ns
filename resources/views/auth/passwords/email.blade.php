@extends('layouts.app')

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
@endsection
