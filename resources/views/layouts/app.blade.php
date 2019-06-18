<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery-3.4.0.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
<!--     <script src="{{ asset('js/app.js') }}" defer></script> -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/common.css') }}" rel="stylesheet">
    @yield('style')
</head>
<body>
    <input type="hidden" id="token" value="{{ $access_token ?? '' }}">
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="nav-header">
            <a href="{{ url('/home') }}" class="col-4">東京29NS</a>
            <a href="{{ url('/home') }}" class="col-3">ホーム</a>
            {{--<a href="{{ url('/search') }}" class="col-2">検索</a>--}}
            <a style="display: none"></a>
            <a href="{{ url('/posts') }}" class="col-3">みんなの29ログ</a>
            @guest
                <a href="{{ url('/login') }}" class="col-2">ログイン</a>
            @else
                <input id="nav-input" type="checkbox" class="display-none">
                <label id="nav-open" class="side-open col-2" for="nav-input">
                @if ($user->thumbnail_url)
                    <img alt="" src="{{ $user->thumbnail_url }}">
                @elseif ($user->sex == 1)
                    <img alt="" src="{{ asset('/images/man.png') }}">
                @elseif ($user->sex == 2)
                    <img alt="" src="{{ asset('/images/woman.png') }}">
                @endif
                    <span></span>
                </label>
                <label id="nav-close" class="display-none" for="nav-input"></label>
                <div id="nav-side">
                    <ul>
                        <li>
                            {{ $user->name }}さん
                        </li>
                        <li>
                            <a href="{{ url('/users').'/'.Auth::id() }}">Myページ</a>
                        </li>
                        <li>
                            <div class="">
                                <a class="" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    ログアウト
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            @endguest
        </div>
    </nav>
    <div class="container">
        @include('common.message')
        <main class="py-2">
            @yield('content')
        </main>
    </div>
    <div class="footer"></div>
    <script src="{{ asset('js/common.js') }}"></script>
    @yield('script')
</body>
</html>
