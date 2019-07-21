@extends('layouts.app')

@section('style')
<link href='{{ "@addtimestamp(css/users/show.css)" }}' rel="stylesheet">
@endsection

@section('script')
<script>
    let arg = {};
    let pair=location.search.substring(1).split('&');
    for(let i=0;pair[i];i++) {
        let kv = pair[i].split('=');
        arg[kv[0]]=kv[1];
    }

    if (arg.tab == undefined || arg.tab == 1) {
        document.getElementsByClassName("users-page-tab")[0].children[0].classList.add("current");
    }
    if (arg.tab != undefined && arg.tab == 2) {
        document.getElementsByClassName("users-page-tab")[0].children[1].classList.add("current");
    }
    if (arg.tab != undefined && arg.tab == 3) {
        document.getElementsByClassName("users-page-tab")[0].children[2].classList.add("current");
    }
    if (arg.tab != undefined && arg.tab == 4) {
        document.getElementsByClassName("users-page-tab")[0].children[3].classList.add("current");
    }

    $('.delete-post-form').submit(evt => {
        if (! confirm("投稿を削除しますか？")) return false;
        $(evt.target).prop("disabled", true);
    });
</script>
@endsection

@if (Auth::id() == $users->id)
    @section('title', "Myページ")
@else
    @section('title', $user->name."さんのページ")
@endif

@section('content')
<div>
    <div class="block-head">
        @if (Auth::id() == $users->id)
        <a href='{{ url("/users/{$users->id}/edit") }}'>編集→</a>
        @else
        <ul class="user-follow">
            @include ('common.follow', ['user' => $users])
        </ul>
        @endif
    </div>
    <div class="block-body">
        <div class="user-card">
            <div class="card-body">
                <div class="user-img">
                    @if ($users->thumbnail_url)
                    <img alt="" src="{{ $users->thumbnail_url }}" class="icon">
                    @elseif ($users->sex == 1)
                    <img alt="" src="{{ asset('/images/man.png') }}" class="icon">
                    @elseif ($users->sex == 2)
                    <img alt="" src="{{ asset('/images/woman.png') }}" class="icon">
                    @endif
                </div>
                <div class="user-text">
                    <ul>
                        <li>{{ floor((date('Ym') - $user->birth_ym) / 1000) * 10 }}代</li>
                        <li>{{ $users->post_count }}件の肉ログ</li>
                        <li>
                            フォロー{{ $users->follow_count }}件
                        </li>
                        <li>
                            フォロワー{{ $users->follower_count }}件
                        </li>
                        <li class="contents">{{ $users->contents }}</li>
                    </ul>
                </div>
            </div>
        </div>


        <ul class="users-page-tab">
            <a href="{{ url()->current().'?tab=1' }}"><li>肉ログ</li></a>
            <a href="{{ url()->current().'?tab=2' }}"><li>お気に入り</li></a>
            <a href="{{ url()->current().'?tab=3' }}"><li>フォロー</li></a>
            <a href="{{ url()->current().'?tab=4' }}"><li>フォロワー</li></a>
        </ul>

        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-format="fluid"
             data-ad-layout-key="-fb+5w+4e-db+86"
             data-ad-client="ca-pub-4702990894338882"
             data-ad-slot="8743192041"></ins>
        <script>
             (adsbygoogle = window.adsbygoogle || []).push({});
        </script>

        <div>
        {{-- お気に入り --}}
        @if (strpos(url()->full(), 'tab=2'))
            @if (count($list) != 0)
            @foreach ($list as $shop)
                @include('common.shop_users_page', ['shop' => $shop])
                @include('users.show_ad', ['loop' => $loop])
            @endforeach
            @else
            <span class="empty-comment">まだお気に入りをしていません。@if ($user->is_followed)<br>気に入った店舗や行きたい店舗の星マークをタップしてお気に入りしましょう！</span>@endif
            @endif

        {{-- フォロー --}}
        @elseif (strpos(url()->full(), 'tab=3'))
            @if (count($list) != 0)
            @foreach ($list as $user)
                <ul class="follow-card">
                    <a href='{{ url("/users/{$user->id}") }}'>
                        <li>
                            @if ($user->thumbnail_url)
                            <img alt="" src="{{ $user->thumbnail_url }}" class="icon">
                            @elseif ($user->sex == 1)
                            <img alt="" src="{{ asset('/images/man.png') }}" class="icon">
                            @elseif ($user->sex == 2)
                            <img alt="" src="{{ asset('/images/woman.png') }}" class="icon">
                            @endif
                            {{ $user->name }}さん
                        </li>
                    </a>
                    @include ('common.follow', ['user' => $user])
                </ul>
                @include('users.show_ad', ['loop' => $loop])
            @endforeach
            @else
            <span class="empty-comment">まだフォローしていません。@if ($user->is_followed)<br>気に入る投稿やいいね、コメントをしてくれたユーザをフォローしましょう！</span>@endif
            @endif

        {{-- フォロワー --}}
        @elseif (strpos(url()->full(), 'tab=4'))
            @if (count($list) != 0)
            @foreach ($list as $user)
                <ul class="follow-card">
                    <a href='{{ url("/users/{$user->id}") }}'>
                        <li>
                            @if ($user->thumbnail_url)
                            <img alt="" src="{{ $user->thumbnail_url }}" class="icon">
                            @elseif ($user->sex == 1)
                            <img alt="" src="{{ asset('/images/man.png') }}" class="icon">
                            @elseif ($user->sex == 2)
                            <img alt="" src="{{ asset('/images/woman.png') }}" class="icon">
                            @endif
                            {{ $user->name }}さん
                        </li>
                    </a>
                    @include ('common.follow', ['user' => $user])
                </ul>
                @include('users.show_ad', ['loop' => $loop])
            @endforeach
            @else
            <span class="empty-comment">まだフォロワーがいません。@if ($user->is_followed)<br>いいねやコメントを積極的にしてフォローしてもらいましょう！</span>@endif
            @endif

        {{-- 肉ログ --}}
        @else
            @if (count($list) != 0)
            @foreach ($list as $post)
                @include('common.post', ['post' => $post])
                @include('users.show_ad', ['loop' => $loop])
            @endforeach
            @else
            <span class="empty-comment">まだレビューがありません。@if ($user->is_followed)<br>店舗にレビューを投稿しましょう！</span>@endif
            @endif
        @endif
        </div>

    </div>
    {{ $list->appends($input)->links('common.pagination') }}
</div>

<div class="ad">
    <center>スポンサーリンク(広告)</center>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- ユーザ詳細フッター -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-4702990894338882"
         data-ad-slot="6984922665"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
    <script>
         (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
</div>

@endsection
