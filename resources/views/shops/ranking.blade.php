@extends('layouts.app')

@section('style')
<link href='{{ "@addtimestamp(css/shops/ranking.css)" }}' rel="stylesheet">
@endsection

@section('script')
@endsection

@section('title', '人気店舗ランキング')

@section('content')
<div class="ad">
    <script type="text/javascript">
    var nend_params = {"media":61795,"site":324943,"spot":964161,"type":1,"oriented":1};
    </script>
    <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
</div>

<div>
    @include('common.landing_regist')

    <div class="block-body">
    @foreach ($shops as $shop)
        @include ('common.shop_ranking', ['shop' => $shop, 'offset' => $offset])

        @if ($loop->iteration % 6 == 0)
        <div class="ad">
            @if ($loop->iteration == 6)
                <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- 店舗一覧コンテンツ間１ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="2665144529"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
                <script>
                     (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            @elseif ($loop->iteration == 12)
                <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- 店舗一覧コンテンツ間２ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="6201064853"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
                <script>
                     (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            @elseif ($loop->iteration == 18)
                <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- 店舗一覧コンテンツ間３ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="5268091282"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
                <script>
                     (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            @elseif ($loop->iteration == 24)
                <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- 店舗一覧コンテンツ間４ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="9015764607"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
                <script>
                     (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            @endif
        </div>
        @endif
    @endforeach
    </div>
    {{ $shops->links('common.pagination') }}
</div>

 <div class="ad">
     <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- 店舗一覧フッター -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-4702990894338882"
         data-ad-slot="6389601263"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
    <script>
         (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
 </div>
@endsection
