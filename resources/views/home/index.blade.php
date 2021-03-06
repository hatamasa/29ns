@extends('layouts.app')

@section('metatitle')
<title>{{ config('app.name', 'Laravel') }}</title>
@endsection

@section('description')
<meta name="description" content="焼肉に特化したグルメサイト。東京の焼肉店やお肉が食べれる店を検索、お気に入り、口コミ。気になるお店は星マークからお気に入りしてマイページでチェック！他のユーザのお気に入り店や口コミもチェックできる！肉好きの肉好きによる肉好きのためのSNS。">
@endsection

@section('canonical')
<link rel="canonical" href='{{ url("/")}}'>
@endsection

@section('style')
<link href='{{ "@addtimestamp(css/home/index.css)" }}' rel="stylesheet">
@endsection

@section('script')
<script>
    document.querySelector('.nav-header>a:nth-child(2)').style.backgroundColor = '#800000';

    $('.delete-post-form').submit(evt => {
        if (! confirm("投稿を削除しますか？")) return false;
        $(evt.target).prop("disabled", true);
    });
</script>
@endsection

@section('content')
<div>
    <div class="counter">
        <div>掲載店舗数
            <span>{{ floor($shops_count/1000) }}</span>
            <span>{{ floor($shops_count%1000/100 )}}</span>
            <span>{{ floor($shops_count%100/10 )}}</span>
            <span>{{ floor($shops_count%10 )}}</span>
        </div>
        <div>掲載口コミ数
            <span>{{ floor($posts_count/1000) }}</span>
            <span>{{ floor($posts_count%1000/100 )}}</span>
            <span>{{ floor($posts_count%100/10 )}}</span>
            <span>{{ floor($posts_count%10 )}}</span>
        </div>
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
            <button type="submit" class="btn btn-primary btn-sm"><img alt="検索ボタン" src="{{ asset('images/search.png') }}"></button>
        </form>
    </div>

    <div class="form-group search-btn">
        <a href="{{ url('/search/line_company') }}" class="btn btn-default btn-lg"><img alt="駅から探す" src="{{ asset('images/station.png') }}"><span>駅から探す</span></a>
        <a href="{{ url('/search/area') }}" class="btn btn-default btn-lg"><img alt="エリアから探す" src="{{ asset('images/erea.png') }}"><span>エリアから探す</span></a>
        @auth
        <a href='{{ url("/users/{$user->id}")."?tab=2" }}' class="btn btn-default btn-lg">
        @else
        <a href='{{ url("/login") }}' class="btn btn-default btn-lg">
        @endauth
            <img alt="行った/お気に入り" src="{{ asset('images/star.png') }}">
            <span>行った/お気に入り</span>
        </a>

    </div>

    <div class="ad">
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-format="fluid"
             data-ad-layout-key="-fb+5w+4e-db+86"
             data-ad-client="ca-pub-4702990894338882"
             data-ad-slot="2301018729"></ins>
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
        <!-- ホームコンテンツ間１ -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-4702990894338882"
             data-ad-slot="3135562593"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
    </div>
    @endauth

    <div class="form-group">
        <div class="block-head">
            <h2>ランキング</h2>
            @auth
                @if ($user->post_count > 0)
                <a href="{{ url('/shops/ranking') }}">ランキングをもっと見る→</a>
                @endif
            @endauth
        </div>
        <div class="block-body">
        @auth
            @if ($user->post_count > 0)
                @foreach ($shops as $shop)
                    @include ('common.shop_ranking', ['shop' => $shop])
                @endforeach
            @else
                @include('common.landing_post')
            @endif
        @else
            ランキングを見るには無料会員登録が必要です
            @include('common.landing_regist')
        @endauth
        </div>
    </div>

    <div class="ad">
        <center>スポンサーリンク(広告)</center>
        <!-- ホームフッター -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-4702990894338882"
             data-ad-slot="2943990908"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
    </div>
</div>

@endsection
