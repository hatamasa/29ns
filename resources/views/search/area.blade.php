@extends('layouts.app')

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
            document.getElementById("_areaSearchSubmitWap").style.display = "block";
        } else {
            document.getElementById("_areaSearchSubmitWap").style.display = "none";
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
            document.getElementById("_areaSearchSubmitWap").style.display = "block";
        } else {
            document.getElementById("_areaSearchSubmitWap").style.display = "none";
        }
    }

</script>
@endsection

@section('title', 'エリアから探す')

@section('content')
<div>
    @include('common.landing_regist')

    <form action="{{ url('/shops') }}" method="get">
    @foreach ($areas as $area_l_cd => $area)
        <div class="area-wap">
            <div class="area-l">{{ Config::get('const.area_l')[$area_l_cd] }}</div>
            <div class="area-m-wap">
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
    @endforeach
        <div id="_areaSearchSubmitWap" class="area-search-submit-wap">
            <button type="submit" class="btn btn-primary btn-block">検索</button>
        </div>
    </form>

</div>
@endsection
