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

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-139979567-2"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-139979567-2');
    </script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link href="{{ asset('css/common.css') }}" rel="stylesheet">
    @yield('style')

    <!-- facebook -->
    <meta property="og:url"           content="https://29ns-com/" />
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="東京29NS" />
    <meta property="og:description"   content="肉好きの肉好きによる肉好きのサイト" />
    <meta property="og:image"         content="" />
</head>
<body>
    <!-- facebook -->
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

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
                    <img alt="" src="{{ $user->thumbnail_url }}" class="icon">
                    @elseif ($user->sex == 1)
                    <img alt="" src="{{ asset('/images/man.png') }}" class="icon">
                    @elseif ($user->sex == 2)
                    <img alt="" src="{{ asset('/images/woman.png') }}" class="icon">
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
    <div class="footer">
        <div class="share-area">
            <!-- facebook -->
            <div class="fb-share-button"
                data-href="https://developers.facebook.com/docs/plugins/"
                data-layout="button" data-size="large">
                <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">シェア</a>
            </div>
            <!-- twitter -->
            <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-show-count="false">Tweet</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
            <!-- LINE -->
            <div class="line-it-button" data-lang="ja" data-type="share-a" data-ver="3" data-url="https://social-plugins.line.me/ja/how_to_install#lineitbutton" data-color="default" data-size="small" data-count="true" style="display: none;"></div>
            <script src="https://d.line-scdn.net/r/web/social-plugin/js/thirdparty/loader.min.js" async="async" defer="defer"></script>
        </div>
        <div class="sponsored">
            <a href="https://api.gnavi.co.jp/api/scope/" target="_blank">
                <img src="https://api.gnavi.co.jp/api/img/credit/api_90_35.gif" width="90" height="35" border="0" alt="グルメ情報検索サイト　ぐるなび">
            </a>
        </div>
    </div>
    <script src="{{ asset('js/common.js') }}"></script>
    @yield('script')
</body>
</html>
