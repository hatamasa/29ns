@extends('layouts.app')

@section('canonical')
<link rel="canonical" href='{{ url("/")}}'>
@endsection

@section('style')
<link href='{{ "@addtimestamp(css/home.css)" }}' rel="stylesheet">
@endsection

@section('script')
<script>
</script>
@endsection

@section('content')
<div>
    <div class="ad">
        <script type="text/javascript">
        var nend_params = {"media":61795,"site":324943,"spot":963974,"type":1,"oriented":1};
        </script>
        <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
    </div>

    <p class="catch">
        <span>使い方は簡単 2STEP</span>
        <span>1. お店を調べる!!そして行ったお店は感謝の気持ちを込めて肉ログ(レビュー)しよう!!</span>
        <span>2. 気になるお店は星マークからお気に入りしよう!!次回行ってレビューをしよう!!</span>
    </p>

    @include('common.landing_regist')
    @auth
    @if (is_null($user->email_verified_at))
    <div class="btn btn-default verify-notice">登録いただいたメールアドレスにメールをお送りしました。<br>メール内のリンクをクリックして本登録をしてください。<br>
        <a href="{{ url('/email/verify') }}">メールが届いていない方はこちら</a>
    </div>
    @endif
    @endauth

    <div class="form-group search">
        <form method="get" name="search_form" action="{{ url('/shops') }}">
            <input type="text" class="form-control" name="keyword" value="" placeholder="店名、駅名、住所など入力...">
            <button type="submit" class="btn btn-primary btn-sm">検索</button>
        </form>
    </div>

    <div class="form-group search-btn">
        <a href="{{ url('/search/station') }}" class="btn btn-default btn-lg" role="button">駅から<br>探す</a>
        <a href="{{ url('/search/area') }}" class="btn btn-default btn-lg">エリアから<br>探す</a>
        {{--<a href="{{ url('/shops/near') }}" class="btn btn-default btn-lg">近くのお店を<br>探す</a>--}}
    </div>


    <div class="form-group recently-post">
        <div class="block-head">
            <h2>最新の肉ログ</h2>
            <a href="{{ url('/posts') }}">肉ログをもっと見る→</a>
        </div>
        <div class="block-body">
        @foreach ($posts as $post)
            @include('common.post', ['post' => $post])
        @endforeach
        </div>
    </div>

    @auth
    <div class="ad">
        <center>スポンサーリンク(広告)</center>
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- ホームコンテンツ間１ -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-4702990894338882"
             data-ad-slot="3135562593"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
        <script>
             (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>

    <div class="form-group">
        <div class="block-head">
            <h2>ランキング</h2>
                @if ($user->post_count > 0)
                <a href="{{ url('/shops/ranking') }}">ランキングをもっと見る→</a>
                @endif
        </div>
        <div class="block-body">
        @if ($user->post_count > 0)
            @foreach ($shops as $shop)
                @include ('common.shop_ranking', ['shop' => $shop])
            @endforeach
        @else
            @include('common.landing_post')
        @endif
        </div>
    </div>
    @endauth

    <div class="ad">
        <center>スポンサーリンク(広告)</center>
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- ホームフッター -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-4702990894338882"
             data-ad-slot="2943990908"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
        <script>
             (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>
</div>

@endsection
