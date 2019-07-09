@extends('layouts.app')

@section('style')
<link href='{{ "@addtimestamp(css/posts.css)" }}' rel="stylesheet">
@endsection

@section('script')
<script>
</script>
@endsection

@section('title', 'みんなの肉ログ')

@section('content')
<div>
    @include('common.landing_regist')

    <div class="ad">
        <script type="text/javascript">
        var nend_params = {"media":61795,"site":324943,"spot":963969,"type":1,"oriented":1};
        </script>
        <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
    </div>

    <div class="block-body">
    @foreach ($posts as $post)
        @include ('common.post', ['post' => $post])

        @if ($loop->iteration % 8 == 0)
        <div class="ad">
            @if ($loop->iteration == 8)
            <script type="text/javascript">
            var nend_params = {"media":61795,"site":324943,"spot":963970,"type":1,"oriented":1};
            </script>
            <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @elseif ($loop->iteration == 16)
            <script type="text/javascript">
            var nend_params = {"media":61795,"site":324943,"spot":963971,"type":1,"oriented":1};
            </script>
            <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @elseif ($loop->iteration == 24)
            <script type="text/javascript">
            var nend_params = {"media":61795,"site":324943,"spot":963972,"type":1,"oriented":1};
            </script>
            <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @endif
        </div>
        @endif

    @endforeach
    </div>
    {{ $posts->links('common.pagination') }}
</div>

<div class="ad">
    <script type="text/javascript">
    var nend_params = {"media":61795,"site":324943,"spot":963973,"type":1,"oriented":1};
    </script>
    <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
</div>
@endsection
