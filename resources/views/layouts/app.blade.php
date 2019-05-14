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
            <div class="col-2"><a href="{{ url('/') }}">NRT</a></div>
            <div class="col-2"><a href="{{ url('/myPage') }}"><img src="{{ asset('images/mypage.png') }}"></a></div>
            <div class="col-2"><a href="{{ url('/follower') }}"><img src="{{ asset('images/follower.png') }}"></a></div>
            <div class="col-2"><a href="{{ url('/groups') }}"><img src="{{ asset('images/group.png') }}"></a></div>
            <div class="col-2"><a href="{{ url('/search') }}"><img src="{{ asset('images/search.png') }}"></a></div>
            <input id="nav-input" type="checkbox" class="display-none">
            <label id="nav-open" class="side-open col-2" for="nav-input">
                <span class="navbar-toggler-icon"></span>
            </label>

            <label id="nav-close" class="display-none" for="nav-input"></label>
            <div id="nav-side">
                <!-- Right Side Of Navbar -->
                <ul>
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <div><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></div>
                        </li>
                        @if (Route::has('register'))
                        <li class="nav-item">
                            <div><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></div>
                        </li>
                        @endif
                    @else
                        <li class="nav-item">
                            <a id="" class="" href="#">
                                <div>{{ Auth::user()->name }}</div>
                            </a>
                        </li>
                        <li>
                            <div><a href="/message">MESSAGE</a></div>
                        </li>
                        <li>
                            <div class="">
                                <a class="" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
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
