@extends('layouts.app')

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
