<div class="landing-post">
    <div class="form-group">
        あと肉ログ１件で見れるようになります！<br>お店を検索して肉ログをしよう！<br>↓↓↓
    </div>

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
</div>
