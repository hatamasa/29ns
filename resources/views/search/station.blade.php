@extends('layouts.app')

@section('style')
<link href='{{ "@addtimestamp(css/search.css)" }}' rel="stylesheet">
@endsection

@section('script')
<script>

    // 駅がチェックされたら個数バリデーションを行う
    [].forEach.call(document.getElementsByClassName("_stationInput"), elem => {
        elem.addEventListener("click", evt => stationClicked(evt));
    });
    function stationClicked(evt) {
        let tgt = evt.target;
        // チェックがつくときは10個を超えないようにチェックする
        if (tgt.checked) {
            if (document.querySelectorAll("._stationInput:checked").length > 10) {
                tgt.checked = false;
                alert('駅の選択は10個までです。');
                return;
            }
        }
        // チェックされている駅が一つでもあればボタンを表示する
        let checkedInput = tgt.parentNode.parentNode.querySelectorAll("._stationInput:checked");
        if (checkedInput.length > 0) {
            document.getElementById("_stationSearchSubmitWap").style.display = "block";
        } else {
            document.getElementById("_stationSearchSubmitWap").style.display = "none";
        }
    }

</script>
@endsection

@section('title', '駅から探す')

@section('content')

    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-format="fluid"
         data-ad-layout-key="-gw-3+1f-3d+2z"
         data-ad-client="ca-pub-4702990894338882"
         data-ad-slot="9888752751"></ins>

<div>
    @include('common.landing_regist')

    <form action="{{ url('/shops') }}" method="get">
    @foreach ($stations as $line_cd => $station)
        <div class="line-wap">
            <div class="line">{{ Config::get('const.station_line')[$line_cd] }}</div>
            <div class="station-wap">
            @foreach ($station as $val)
                <p class="station">
                    <input type="checkbox" name="station_list[]" id="station-{{ $val['station_cd'] }}" class="form-check-input _stationInput" value="{{ $val['station_name'] }}駅">
                    <label for="station-{{ $val['station_cd'] }}" class="form-check-label">{{ $val['station_name'] }}</label>
                </p>
            @endforeach
            </div>
        </div>

        @if ($loop->iteration % 6 == 0)
        <div class="ad">
            <center>スポンサーリンク(広告)</center>
            @if ($loop->iteration == 6)
                <!-- 検索画面コンテンツ間１ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="8105173466"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
            @elseif ($loop->iteration == 12)
                <!-- 検索画面コンテンツ間２ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="4980316670"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
            @elseif ($loop->iteration == 18)
                <!-- 検索画面コンテンツ間３ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="1041071665"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
            @elseif ($loop->iteration == 24)
                <!-- 検索画面コンテンツ間４ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="5330836314"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
            @elseif ($loop->iteration == 30)
                <!-- 検索画面コンテンツ間５ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="7454081557"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
            @elseif ($loop->iteration == 36)
                <!-- 検索画面コンテンツ間６ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="6140999889"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
            @elseif ($loop->iteration == 42)
                <!-- 検索画面コンテンツ間７ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="3514836547"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
            @elseif ($loop->iteration == 48)
                <!-- 検索画面コンテンツ間８ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="8536418300"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
            @elseif ($loop->iteration == 54)
                <!-- 検索画面コンテンツ間９ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="2229675033"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
            @elseif ($loop->iteration == 60)
                <!-- 検索画面コンテンツ間１０ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="8603511699"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
            @elseif ($loop->iteration == 66)
                <!-- 検索画面コンテンツ間１１ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="5977348358"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
            @elseif ($loop->iteration == 72)
                <!-- 検索画面コンテンツ間１２ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="4597173293"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
            @endif
        </div>
        @endif

    @endforeach
        <div id="_stationSearchSubmitWap" class="station-search-submit-wap">
            <button type="submit" class="btn btn-primary btn-block">検索</button>
        </div>
    </form>

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
