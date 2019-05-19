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
    <input type="hidden" id="token" value="{{ $user->access_token ?? '' }}">
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="nav-header">
            <a href="{{ url('/home') }}" class="col-4">東京29NS</a>
            <a href="{{ url('/home') }}" class="col-2">ホーム</a>
            <a href="{{ url('/search') }}" class="col-2">検索</a>
            <a href="{{ url('/posts') }}" class="col-2">みんなの29ログ</a>
            <input id="nav-input" type="checkbox" class="display-none">
            <label id="nav-open" class="side-open col-2" for="nav-input">
                @guest
                    ゲストさん
                @else
                    {{ Auth::user()->name }}さん
                @endguest
                <span></span>
            </label>

            <label id="nav-close" class="display-none" for="nav-input"></label>
            <div id="nav-side">
                <!-- Right Side Of Navbar -->
                <ul>
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">ログイン</a>
                        </li>
                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">会員登録</a>
                        </li>
                        @endif
                    @else
                        <li>
                            <a href="{{ url('/myPage') }}">Myページ</a>
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
                    @endguest
                </ul>
            </div>

        </div>
    </nav>
    <div class="container">
        <main class="py-2">
            @yield('content')
        </main>
    </div>
    @yield('script')
</body>
</html>
