@extends('layouts.app')

@section('style')
<link href='{{ "@addtimestamp(css/shops/ranking.css)" }}' rel="stylesheet">
@endsection

@section('script')
@endsection

@section('title', '人気店舗ランキング')

@section('content')
<div>
    @include('common.landing_regist')

    <div class="block-body">
    @foreach ($shops as $shop)
        @include ('common.shop_ranking', ['shop' => $shop, 'offset' => $offset])

        @if ($loop->iteration % 6 == 0)
        <div class="ad">
            @if ($loop->iteration == 6)
            <script type="text/javascript">
            var nend_params = {"media":61795,"site":324943,"spot":963964,"type":1,"oriented":1};
            </script>
            <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @elseif ($loop->iteration == 12)
            <script type="text/javascript">
            var nend_params = {"media":61795,"site":324943,"spot":963956,"type":1,"oriented":1};
            </script>
            <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @elseif ($loop->iteration == 18)
            <script type="text/javascript">
            var nend_params = {"media":61795,"site":324943,"spot":963958,"type":1,"oriented":1};
            </script>
            <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @elseif ($loop->iteration == 24)
            <script type="text/javascript">
            var nend_params = {"media":61795,"site":324943,"spot":963962,"type":1,"oriented":1};
            </script>
            <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @endif
        </div>
        @endif
    @endforeach
    </div>
    {{ $shops->links('common.pagination') }}
</div>

 <div class="ad">
     <script type="text/javascript">
     var nend_params = {"media":61795,"site":324943,"spot":963963,"type":1,"oriented":1};
     </script>
     <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
 </div>
@endsection
