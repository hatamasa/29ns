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
    @endforeach
        <div id="_stationSearchSubmitWap" class="station-search-submit-wap">
            <button type="submit" class="btn btn-primary btn-block">検索</button>
        </div>
    </form>

</div>
@endsection
