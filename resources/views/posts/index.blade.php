@extends('layouts.app')

@section('metatitle')
<title>焼肉口コミ一覧画面 - 東京肉NS</title>
@endsection

@section('description')
<meta name="description" content="焼肉店の口コミ一覧画面です。東京肉NSは焼肉に特化したグルメサイトです！気になるお店は星マークからお気に入りしてマイページでチェック！他のユーザのお気に入り店や口コミもチェックできる！">
@endsection

@section('style')
<link href='{{ "@addtimestamp(css/posts.css)" }}' rel="stylesheet">
@endsection

@section('script')
<script>
    document.querySelector('.nav-header>a:nth-child(4)').style.backgroundColor = '#800000';

    $('.delete-post-form').submit(evt => {
        if (! confirm("投稿を削除しますか？")) return false;
        $(evt.target).prop("disabled", true);
    });
</script>
@endsection

@section('title', 'みんなの肉ログ')

@section('content')
<div>
    @include('common.landing_regist')

{{--
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-format="fluid"
         data-ad-layout-key="-fb+5w+4e-db+86"
         data-ad-client="ca-pub-4702990894338882"
         data-ad-slot="9413221989"></ins>
         --}}

    <div class="block-body">
    @foreach ($posts as $post)
        @include ('common.post', ['post' => $post])

        @if ($loop->iteration % 8 == 0)
        <div class="ad">
            <center>スポンサーリンク(広告)</center>
            @if ($loop->iteration == 8)
                <!-- 投稿一覧コンテンツ間１ -->
                <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="6361681108"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
            @elseif ($loop->iteration == 16)
                <!-- 投稿一覧コンテンツ間２ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="3735517765"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
            @elseif ($loop->iteration == 24)
                <!-- 投稿一覧コンテンツ間３ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="3989834481"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
            @endif
        </div>
        @endif

    @endforeach
    </div>
    {{ $posts->links('common.pagination') }}
</div>

<div class="ad">
    <center>スポンサーリンク(広告)</center>
    <!-- 投稿一覧フッター -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-4702990894338882"
         data-ad-slot="4857027742"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
</div>
@endsection
