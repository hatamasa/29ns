@extends('layouts.app')

@section('metatitle')
<title>東京のエリアから焼肉店を検索 - 東京肉NS</title>
@endsection

@section('description')
<meta name="description" content="東京のエリア選択画面です。東京肉NSは焼肉に特化したグルメサイトです！気になるお店は星マークからお気に入りしてマイページでチェック！他のユーザのお気に入り店や口コミもチェックできる！">
@endsection

@section('style')
<link href='{{ "@addtimestamp(css/search.css)" }}' rel="stylesheet">
@endsection

@section('script')
<script>

    //エリアLがチェックされたら配下のエリアMを全てチェック、外れたら全て外す
    [].forEach.call(document.getElementsByClassName("_areaLInput"), elem => {
        elem.addEventListener("click", evt => areaLClicked(evt));
    });
    function areaLClicked(evt) {
        let tgt = evt.target;
        // チェックがつくときは10個を超えないようにチェックする
        let areaMInput = tgt.parentNode.nextElementSibling.getElementsByClassName("_areaMInput");
        let areaMInputChecked = tgt.parentNode.nextElementSibling.querySelectorAll("._areaMInput:checked");
        if (tgt.checked) {
            if ((areaMInput.length - areaMInputChecked.length
                + document.querySelectorAll("._areaMInput:checked").length) > 10) {
                tgt.checked = false;
                alert("エリアの選択は10個までです。");
                return;
            }
        }
        // エリアLの入力に応じてエリアMをつけたり外したりする
        [].forEach.call(areaMInput, elem => {
            if (tgt.checked) {
                elem.checked = true;
            } else {
                elem.checked = false;
            }
        });
        // チェックされているエリアMが一つでもあればボタンを表示する
        let checkedInputM = tgt.parentNode.parentNode.querySelectorAll("._areaMInput:checked");
        if (checkedInputM.length > 0) {
            document.getElementById("_areaSearchSubmitWrap").style.display = "block";
        } else {
            document.getElementById("_areaSearchSubmitWrap").style.display = "none";
        }
    }

    [].forEach.call(document.getElementsByClassName("_areaMInput"), elem => {
        elem.addEventListener("click", evt => areaMClicked(evt));
    });
    function areaMClicked(evt) {
        let tgt = evt.target;
        // チェックがつくときは10個を超えないようにチェックする
        if (tgt.checked) {
            if (document.querySelectorAll("._areaMInput:checked").length > 10) {
                tgt.checked = false;
                alert('エリアの選択は10個までです。');
                return;
            }
        }
        let areaLInput = tgt.parentNode.parentNode.previousElementSibling.getElementsByClassName("_areaLInput")[0];
        let checkedInputM = tgt.parentNode.parentNode.querySelectorAll("._areaMInput:checked");
        if (areaLInput.checked) {
            // エリアLがチェックされた状態でエリアMのチェックを外した場合はエリアLのチェックを外す
            areaLInput.checked = false;
        } else {
            // エリアL配下のMが全てチェックされたらエリアLをチェックする
            let inputM = tgt.parentNode.parentNode.querySelectorAll("._areaMInput");
            if (checkedInputM.length == inputM.length) {
                areaLInput.checked = true;
            }
        }
        // チェックされているエリアMが一つでもあればボタンを表示する
        if (checkedInputM.length > 0) {
            document.getElementById("_areaSearchSubmitWrap").style.display = "block";
        } else {
            document.getElementById("_areaSearchSubmitWrap").style.display = "none";
        }
    }

</script>
@endsection

@section('title', 'エリアから探す')

@section('content')

    <ins class="adsbygoogle"
         style="display:block"
         data-ad-format="fluid"
         data-ad-layout-key="-gw-3+1f-3d+2z"
         data-ad-client="ca-pub-4702990894338882"
         data-ad-slot="9888752751"></ins>

<div>
    @include('common.landing_regist')

    <form action="{{ url('/shops') }}" method="get">
    @foreach ($areas as $area_l_cd => $area)
        <div class="area-wrap">
            <input type="checkbox" id="{{ $area_l_cd }}" class="area-l-check">
            <label class="area-l" for="{{ $area_l_cd }}">{{ Config::get('const.area_l')[$area_l_cd] }}</label>
            <div class="area-m-wrap">
                <a href='{{ url("/shops?areacode_l=").$area_l_cd }}'>
                    <p class="area-m">{{ Config::get('const.area_l')[$area_l_cd] }}(全て)</p>
                </a>
            @foreach ($area as $val)
                <a href='{{ url("/shops?areacode_m=").$val["area_cd"] }}'>
                    <p class="area-m">{{ $val['area_name'] }}</p>
                </a>
            @endforeach
            </div>
        </div>

        @if ($loop->iteration % 15 == 0)
        <div class="ad">
            <center>スポンサーリンク(広告)</center>
        {{--@if ($loop->iteration == 3)
                <!-- 検索画面コンテンツ間１ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="8105173466"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
            @elseif ($loop->iteration == 6)
                <!-- 検索画面コンテンツ間２ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="4980316670"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
            @if ($loop->iteration == 12)
                <!-- 検索画面コンテンツ間３ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="1041071665"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>--}}
            @if ($loop->iteration == 15)
                <!-- 検索画面コンテンツ間４ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="5330836314"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
        {{--@elseif ($loop->iteration == 18)
                <!-- 検索画面コンテンツ間５ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="7454081557"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
            @elseif ($loop->iteration == 21)
                <!-- 検索画面コンテンツ間６ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="6140999889"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
            @elseif ($loop->iteration == 24)
                <!-- 検索画面コンテンツ間７ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="3514836547"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
            @elseif ($loop->iteration == 27)
                <!-- 検索画面コンテンツ間８ -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4702990894338882"
                     data-ad-slot="8536418300"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>--}}
            @endif
        </div>
        @endif

    @endforeach
        <div id="_areaSearchSubmitWrap" class="area-search-submit-wrap">
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
