@extends('layouts.app')

@section('style')
<link href='{{ "@addtimestamp(css/shops/index.css)" }}' rel="stylesheet">
@endsection

@section('script')
<script>
</script>
@endsection

@section('title', '店舗一覧')

@section('content')
<div class="ad">
    <script type="text/javascript">
    var nend_params = {"media":61795,"site":324943,"spot":964161,"type":1,"oriented":1};
    </script>
    <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
</div>

<div>
    @include('common.landing_regist')

    <div class="block-head">
        <h2>{{ implode(",", $search_condition) }}の店舗</h2>
        <p>{{ $total_hit_count }}件</p>
    </div>

    <div class="block-body">
    @foreach ($shops as $shop)
        @include ('common.shop', ['shop' => $shop])

        @if ($loop->iteration % 6 == 0)
        <div class="ad">
            @if ($loop->iteration == 6)
            <script type="text/javascript">
var nend_params = {"media":61795,"site":324943,"spot":964166,"type":1,"oriented":1};
</script>
<script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @elseif ($loop->iteration == 12)
            <script type="text/javascript">
var nend_params = {"media":61795,"site":324943,"spot":964167,"type":1,"oriented":1};
</script>
<script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @elseif ($loop->iteration == 18)
            <script type="text/javascript">
var nend_params = {"media":61795,"site":324943,"spot":964174,"type":1,"oriented":1};
</script>
<script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @elseif ($loop->iteration == 24)
            <script type="text/javascript">
var nend_params = {"media":61795,"site":324943,"spot":964169,"type":1,"oriented":1};
</script>
<script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @endif
        </div>
        @endif

    @endforeach
    </div>
    {{ $shops->appends($input)->links('common.pagination') }}
</div>

 <div class="ad">
     <script type="text/javascript">
    var nend_params = {"media":61795,"site":324943,"spot":964170,"type":1,"oriented":1};
    </script>
    <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
 </div>
@endsection
