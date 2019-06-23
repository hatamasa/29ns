@extends('layouts.app')

@section('style')
<link href="{{ asset('css/shops.css') }}" rel="stylesheet">
@endsection

@section('script')
<script>

let clickFlg = false;
[].forEach.call(document.getElementsByClassName("star-wrap"), elem => {
    elem.addEventListener('click', evt => {
        evt.preventDefault();
        if (clickFlg) {
            alert("ただいま処理中です。");
            return false;
        }
        clickFlg = true;
        evt.target.style.color = '#d1d1d1';
        $(evt.target).parents('a').addClass('no-active');
        $(evt.target).parents('form').submit();
    });
});

</script>
@endsection

@section('content')
<div>
    @include('common.landing_regist')

    <div class="block-head">
        <p>{{ implode(",", $search_condition) }}の店舗</p>
        <p>{{ $total_hit_count }}件</p>
    </div>

    <div class="block-body">
    @foreach ($shops as $shop)
        @include ('common.shop', ['shop' => $shop])
    @endforeach
    </div>
    {{ $shops->appends($input)->links('common.pagination') }}
</div>

@endsection
