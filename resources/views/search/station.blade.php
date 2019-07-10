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

<div class="ad">
    <script type="text/javascript">
    var nend_params = {"media":61795,"site":324943,"spot":963944,"type":1,"oriented":1};
    </script>
    <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
</div>

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

        <div class="ad">
            @if ($loop->iteration == 6)
            <script type="text/javascript">
            var nend_params = {"media":61795,"site":324943,"spot":964182,"type":1,"oriented":1};
            </script>
            <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @elseif ($loop->iteration == 12)
            <script type="text/javascript">
            var nend_params = {"media":61795,"site":324943,"spot":964183,"type":1,"oriented":1};
            </script>
            <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @elseif ($loop->iteration == 18)
            <script type="text/javascript">
            var nend_params = {"media":61795,"site":324943,"spot":964184,"type":1,"oriented":1};
            </script>
            <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @elseif ($loop->iteration == 24)
            <script type="text/javascript">
            var nend_params = {"media":61795,"site":324943,"spot":964185,"type":1,"oriented":1};
            </script>
            <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @elseif ($loop->iteration == 30)
            <script type="text/javascript">
            var nend_params = {"media":61795,"site":324943,"spot":964186,"type":1,"oriented":1};
            </script>
            <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @elseif ($loop->iteration == 36)
            <script type="text/javascript">
            var nend_params = {"media":61795,"site":324943,"spot":964187,"type":1,"oriented":1};
            </script>
            <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @elseif ($loop->iteration == 42)
            <script type="text/javascript">
            var nend_params = {"media":61795,"site":324943,"spot":964172,"type":1,"oriented":1};
            </script>
            <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @elseif ($loop->iteration == 48)
            <script type="text/javascript">
            var nend_params = {"media":61795,"site":324943,"spot":964173,"type":1,"oriented":1};
            </script>
            <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @elseif ($loop->iteration == 54)
             <script type="text/javascript">
            var nend_params = {"media":61795,"site":324943,"spot":964174,"type":1,"oriented":1};
            </script>
            <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @elseif ($loop->iteration == 60)
            <script type="text/javascript">
            var nend_params = {"media":61795,"site":324943,"spot":964175,"type":1,"oriented":1};
            </script>
            <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @elseif ($loop->iteration == 66)
            <script type="text/javascript">
            var nend_params = {"media":61795,"site":324943,"spot":964175,"type":1,"oriented":1};
            </script>
            <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @elseif ($loop->iteration == 72)
            <script type="text/javascript">
            var nend_params = {"media":61795,"site":324943,"spot":964176,"type":1,"oriented":1};
            </script>
            <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
            @endif
        </div>
    @endforeach
        <div id="_stationSearchSubmitWap" class="station-search-submit-wap">
            <button type="submit" class="btn btn-primary btn-block">検索</button>
        </div>
    </form>

</div>

<div class="ad">
    <script type="text/javascript">
    var nend_params = {"media":61795,"site":324943,"spot":964181,"type":1,"oriented":1};
    </script>
    <script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
</div>
@endsection
