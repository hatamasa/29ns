@extends('layouts.app')

@section('metatitle')
<title>{{ implode(",", $search_condition) }}の焼肉店の一覧 - 東京肉NS</title>
@endsection

@section('description')
<meta name="description" content="{{ implode(',', $search_condition) }}の焼肉店の一覧画面です。東京肉NSは焼肉に特化したグルメサイトです！気になるお店は星マークからお気に入りしてマイページでチェック！他のユーザのお気に入り店や口コミもチェックできる！">
@endsection

@section('style')
<link href='{{ "@addtimestamp(css/shops/index.css)" }}' rel="stylesheet">
@endsection

@section('script')
<script>
</script>
@endsection

@section('title', '店舗一覧')

@section('content')

<ins class="adsbygoogle"
     style="display:block"
     data-ad-format="fluid"
     data-ad-layout-key="-fb+5w+4e-db+86"
     data-ad-client="ca-pub-4702990894338882"
     data-ad-slot="2337565791"></ins>

<div>
    @include('common.landing_regist')

    <div class="block-head">
        <h2>{{ implode(",", $search_condition) }}の店舗</h2>
        <p>{{ $total_hit_count }}件</p>
    </div>

    <div class="block-body">
    @foreach ($shops as $shop)
        @include ('common.shop', ['shop' => $shop])

        @if ($loop->iteration % 24 == 0)
        <div class="ad">
            <center>スポンサーリンク(広告)</center>
            @if ($loop->iteration == 24)
                <!-- 店舗一覧コンテンツ間１ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="2665144529"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
            {{-- @elseif ($loop->iteration == 24)
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
                     data-full-width-responsive="true"></ins> --}}
            @endif
        </div>
        @endif

    @endforeach
    </div>
    {{ $shops->appends($input)->links('common.pagination') }}
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
