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
    @guest
    <div class="form-group">
        <a class="btn btn-info btn-block" href="{{ route('register') }}">新規会員登録はこちら</a>
    </div>
    @endguest

    <div class="block-head">
        <p>エリアから探す</p>
    </div>
    <form action="{{ url('/search/area') }}" name="areaSearchForm">
    @foreach ($areas as $area_l_cd => $area)
        <div class="area-wap">
            <div class="area-l">
                <input type="checkbox" id="area-l-{{ $area_l_cd }}" class="form-check-input _areaLInput" val="{{ $area_l_cd }}">
                <label for="area-l-{{ $area_l_cd }}" class="form-check-label">{{ Config::get('const.area_l')[$area_l_cd] }}</label>
            </div>
            <div class="area-m-wap">
            @foreach ($area as $val)
                <p class="area-m">
                    <input type="checkbox" name="area_m[]" id="area-m-{{ $val['area_cd'] }}" class="form-check-input _areaMInput" val="{{ $val['area_cd'] }}">
                    <label for="area-m-{{ $val['area_cd'] }}" class="form-check-label">{{ $val['area_name'] }}</label>
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
