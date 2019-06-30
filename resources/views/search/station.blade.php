@extends('layouts.app')

@section('style')
<link href='{{ "@addtimestamp(css/searh.css)" }}' rel="stylesheet">
@endsection

@section('script')
<link href='{{ "@addtimestamp(js/search.js)" }}' rel="stylesheet">
@endsection

@section('content')
<div>
    @include('common.landing_regist')

    <div class="block-head">
        <p>駅から探す</p>
    </div>
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
