@extends('layouts.app')

@section('style')
<link href="{{ asset('css/search.css') }}" rel="stylesheet">
@endsection

@section('script')
<script src="{{ asset('js/jquery.inview.min.js') }}"></script>
<script src="{{ asset('js/search.js') }}"></script>
@endsection

@section('content')
<div>
    @includ('common.landing_regist')

    <div class="block-head">
        <p>エリアから探す</p>
    </div>
    <form action="{{ url('/shops') }}">
    @foreach ($areas as $area_l_cd => $area)
        <div class="area-wap">
            <div class="area-l">{{ Config::get('const.area_l')[$area_l_cd] }}</div>
            <div class="area-m-wap">
                <p class="area-m">
                    <a href='{{ url("/shops?areacode_l=").$area_l_cd }}'>{{ Config::get('const.area_l')[$area_l_cd] }}(全て)</a>
                </p>
            @foreach ($area as $val)
                <p class="area-m">
                    <a href='{{ url("/shops?areacode_m=").$val["area_cd"] }}'>{{ $val['area_name'] }}</a>
                </p>
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
