<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="description" content="東京の焼肉店やお肉が食べれる店を検索、お気に入り、レビュー。気になるお店は星マークからお気に入りしてマイページでチェック！他のユーザのお気に入り店やレビューもチェックできる！肉好きの肉好きによる肉好きのためのSNS。東京の肉好きのユーザたちと交流しよう！">
    @yield('canonical')

    <link rel="icon" href="/favicon.ico">

    <!-- Styles -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link href='{{ "@addtimestamp(css/common.css)" }}' rel="stylesheet">
    @yield('style')

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-139979567-2"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-139979567-2');
    </script>

    <!-- ogp -->
    <meta property="og:url"         content="{{ $url }}" />
    <meta property="og:type"        content="website" />
    <meta property="og:site_name"   content="東京肉NS">
    <meta property="og:title"       content="東京肉NS" />
    <meta property="og:description" content="肉は確かに旨いが、東京の肉はもっと旨い！肉好きの肉好きによる肉好きのためのサイト！" />
    <meta property="og:image"       content="https://img.29-ns.com/29ns_ogp.jpg" />
    <!-- twitter -->
    <meta name="twitter:card"        content="summary_large_image" />
    <meta name="twitter:url"         content="{{ $url }}" />
    <meta name="twitter:title"       content="東京肉NS" />
    <meta name="twitter:description" content="肉は確かに旨いが、東京の肉はもっと旨い！肉好きの肉好きによる肉好きのためのサイト！" />
    <meta name="twitter:image"       content="https://img.29-ns.com/29ns_ogp.jpg" />

    <meta http-equiv="x-dns-prefetch-control" content="on">
    <link rel="dns-prefetch" href="//pagead2.googlesyndication.com"/>
    <link rel="dns-prefetch" href="//googleads.g.doubleclick.net"/>
    <link rel="dns-prefetch" href="//tpc.googlesyndication.com"/>
    <link rel="dns-prefetch" href="//www.gstatic.com"/>
</head>
<body>
    <!-- facebook -->
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v3.3"></script>

    <input type="hidden" id="token" value="{{ $access_token ?? '' }}">
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="nav-header">
            @if (url()->current() == url('/') || url()->current() == url('/home'))
            <a href="{{ url('/home') }}" class="col-4">
                <h1>東京の肉好きのためのSNS</h1>
                <span>東京肉NS</span>
            </a>
            <a href="{{ url('/home') }}" class="col-2"><img src="{{ asset('images/home.png') }}" alt="ホームボタン"><span>ホーム</span></a>
            <a href="{{ url('/shops/ranking') }}" class="col-2"><img src="{{ asset('images/ranking.png') }}" alt="ランキングボタン"><span>ランキング</span></a>
            <a href="{{ url('/posts') }}" class="col-2"><img src="{{ asset('images/29log.png') }}" alt="投稿一覧ボタン"><span>みんなの<br>肉ログ</span></a>
            @else
            <a href="{{ !empty(session('referrers')) ? session('referrers')[count(session('referrers'))-1] : url('/') }}" class="previous col-2">←</a>
            <h1 class="page-title col-8">@yield('title')</h1>
            @endif

            @guest
                <a href="{{ url('/login') }}" class="login col-2">ログイン</a>
            @else
                <input id="nav-input" type="checkbox" class="display-none">
                <label id="nav-open" class="side-open col-2" for="nav-input">
                    @if ($user->thumbnail_url)
                    <img alt="" src="{{ $user->thumbnail_url }}" class="icon" alt="ユーザプロフィール画像">
                    @elseif ($user->sex == 1)
                    <img alt="" src="{{ asset('/images/man.png') }}" class="icon" alt="ユーザ男性デフォルトプロフィール画像">
                    @elseif ($user->sex == 2)
                    <img alt="" src="{{ asset('/images/woman.png') }}" class="icon" alt="ユーザ女性デフォルトプロフィール画像">
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
               data-href="https://www.29-ns.com/"
               data-layout="button_count"
               data-size="small"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fwww.29-ns.com%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">シェア</a></div>
            <!-- twitter -->
            <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-show-count="false">Tweet</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
            <!-- LINE -->
            <div class="line-it-button" data-lang="ja" data-type="share-a" data-ver="3" data-url="https://www.29-ns.com" data-color="default" data-size="small" data-count="true" style="display: none;"></div>
            <script src="https://d.line-scdn.net/r/web/social-plugin/js/thirdparty/loader.min.js" async="async" defer="defer"></script>
        </div>
        <span class="ask">
            お問い合わせは<a href="mailto:hatamasa29&#64;yahoo.co.jp">こちら</a>まで
        </span>
        <div class="sponsored">
            <a href="https://api.gnavi.co.jp/api/scope/" target="_blank">
                <img src="https://api.gnavi.co.jp/api/img/credit/api_90_35.gif" width="90" height="35" border="0" alt="グルメ情報検索サイト　ぐるなび">
            </a>
        </div>
    </div>
    <script src='{{ "@addtimestamp(js/common.min.js)" }}'></script>
    @yield('script')
</body>
</html>
