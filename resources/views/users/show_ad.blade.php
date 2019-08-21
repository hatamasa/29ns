@if ($loop->iteration % 8 == 0)
<div class="ad">
    <center>スポンサーリンク(広告)</center>
    @if ($loop->iteration == 8)
        <!-- ユーザ詳細コンテンツ間１ -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-4702990894338882"
             data-ad-slot="7539031408"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
    @elseif ($loop->iteration == 16)
        <!-- ユーザ詳細コンテンツ間２ -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-4702990894338882"
             data-ad-slot="8298004337"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
    @elseif ($loop->iteration == 24)
        <!-- ユーザ詳細コンテンツ間３ -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-4702990894338882"
             data-ad-slot="9973623056"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
    @endif
</div>
@endif