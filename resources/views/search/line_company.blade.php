@extends('layouts.app')

@section('metatitle')
<title>東京の路線から焼肉店を検索 - 東京肉NS</title>
@endsection

@section('description')
<meta name="description" content="東京の路線選択画面です。東京肉NSは焼肉に特化したグルメサイトです！気になるお店は星マークからお気に入りしてマイページでチェック！他のユーザのお気に入り店や口コミもチェックできる！">
@endsection

@section('style')
<link href='{{ "@addtimestamp(css/search.css)" }}' rel="stylesheet">
@endsection

@section('script')
<script>
</script>
@endsection

@section('title', '路線選択')

@section('content')

<div class="ad">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-format="fluid"
         data-ad-layout-key="-gw-3+1f-3d+2z"
         data-ad-client="ca-pub-4702990894338882"
         data-ad-slot="9888752751"></ins>
</div>

<div class="line-company">
    @include('common.landing_regist')

    @foreach (Config::get('const.line_company') as $cd => $name)
        <a href='{{ url("/search/station/{$cd}") }}'>{{ $name }}</a>
    @endforeach

</div>

<div class="ad">
    <!-- 検索画面フッター -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-4702990894338882"
         data-ad-slot="3351185013"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
</div>
@endsection
