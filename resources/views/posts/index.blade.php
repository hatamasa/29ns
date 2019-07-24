@extends('layouts.app')

@section('style')
<link href='{{ "@addtimestamp(css/posts.css)" }}' rel="stylesheet">
@endsection

@section('script')
<script>
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

    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-format="fluid"
         data-ad-layout-key="-fb+5w+4e-db+86"
         data-ad-client="ca-pub-4702990894338882"
         data-ad-slot="9413221989"></ins>

    <div class="block-body">
    @foreach ($posts as $post)
        @include ('common.post', ['post' => $post])

        @if ($loop->iteration % 8 == 0)
        <div class="ad">
            <center>スポンサーリンク(広告)</center>
            @if ($loop->iteration == 8)
                <!-- 投稿一覧コンテンツ間１ -->
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
