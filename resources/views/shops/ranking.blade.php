@extends('layouts.app')

@section('style')
<link href='{{ "@addtimestamp(css/shops/ranking.css)" }}' rel="stylesheet">
@endsection

@section('script')
@endsection

@section('title', '人気店舗ランキング')

@section('content')

<div>
    @include('common.landing_regist')

    @if ($user->post_count > 0)
    <div class="block-body">
        @foreach ($shops as $shop)
            @include ('common.shop_ranking', ['shop' => $shop, 'offset' => $offset])

            @if ($loop->iteration % 6 == 0)
            <div class="ad">
                <center>スポンサーリンク(広告)</center>
                @if ($loop->iteration == 6)
                    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- 店舗一覧コンテンツ間１ -->
                    <ins class="adsbygoogle"
                         style="display:block"
                         data-ad-client="ca-pub-4702990894338882"
                         data-ad-slot="2665144529"
                         data-ad-format="auto"
                         data-full-width-responsive="true"></ins>
                @elseif ($loop->iteration == 12)
                    <!-- 店舗一覧コンテンツ間２ -->
                    <ins class="adsbygoogle"
                         style="display:block"
                         data-ad-client="ca-pub-4702990894338882"
                         data-ad-slot="6201064853"
                         data-ad-format="auto"
                         data-full-width-responsive="true"></ins>
                @elseif ($loop->iteration == 18)
                    <!-- 店舗一覧コンテンツ間３ -->
                    <ins class="adsbygoogle"
                         style="display:block"
                         data-ad-client="ca-pub-4702990894338882"
                         data-ad-slot="5268091282"
                         data-ad-format="auto"
                         data-full-width-responsive="true"></ins>
                @elseif ($loop->iteration == 24)
                    <!-- 店舗一覧コンテンツ間４ -->
                    <ins class="adsbygoogle"
                         style="display:block"
                         data-ad-client="ca-pub-4702990894338882"
                         data-ad-slot="9015764607"
                         data-ad-format="auto"
                         data-full-width-responsive="true"></ins>
                @endif
            </div>
            @endif
        @endforeach
    </div>
    {{ $shops->links('common.pagination') }}
    @else
    <div class="block-body">
        @include('common.landing_post')
    </div>
    @endif
</div>

 <div class="ad">
     <center>スポンサーリンク(広告)</center>
    <!-- 店舗一覧フッター -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-4702990894338882"
         data-ad-slot="6389601263"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
 </div>
@endsection
