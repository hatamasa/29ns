@extends('layouts.app')

@section('metatitle')
<title>ユーザページ - 東京肉NS</title>
@endsection

@section('description')
<meta name="description" content="ユーザの個人ページです。東京肉NSに登録してある情報や、レビュー履歴、行った/お気に入りお店、フォロー、フォロワーをみることができます。">
@endsection

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

    if (arg.filter == 1) {
        document.getElementById("posted-shops").classList.add("filtered");
    } else if (arg.filter == 2) {
        document.getElementById("liked-shops").classList.add("filtered");
    } else {
        document.getElementById("all-shops").classList.add("filtered");
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
    @section('title', $users->name."さんのページ")
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
                    @if (isset($users->thumbnail_url) && !empty($users->thumbnail_url))
                    <img alt="ユーザプロフィール画像" src="{{ $users->thumbnail_url }}" class="icon">
                    @else
                    <img alt="ユーザデフォルトプロフィール画像" src="{{ asset('/images/user.png') }}" class="icon">
                    @endif
                </div>
                <div class="user-text">
                    <ul>
                        <li>{{ floor((date('Ym') - $users->birth_ym) / 1000) * 10 }}代</li>
                        <li>{{ $users->post_count }}件の肉ログ</li>
                        <li>
                            フォロー{{ $users->follow_count }}人
                        </li>
                        <li>
                            フォロワー{{ $users->follower_count }}人
                        </li>
                        <li class="contents">{{ $users->contents }}</li>
                    </ul>
                </div>
            </div>
        </div>


        <ul class="users-page-tab">
            <a href="{{ url()->current().'?tab=1' }}"><li>肉ログ</li></a>
            <a href="{{ url()->current().'?tab=2' }}"><li>行った<br>お気に入り</li></a>
            <a href="{{ url()->current().'?tab=3' }}"><li>フォロー</li></a>
            <a href="{{ url()->current().'?tab=4' }}"><li>フォロワー</li></a>
        </ul>

        <ins class="adsbygoogle"
             style="display:block"
             data-ad-format="fluid"
             data-ad-layout-key="-fb+5w+4e-db+86"
             data-ad-client="ca-pub-4702990894338882"
             data-ad-slot="8743192041"></ins>

        <div>
        {{-- 行った、お気に入り --}}
        @if (strpos(url()->full(), 'tab=2'))
            @if (count($list) != 0)
            <div class="filter-area">
                <a href="{{ url()->current().'?tab=2' }}" id="all-shops" class="btn btn-default btn-lg">全て</a>
                <a href="{{ url()->current().'?tab=2&filter=1' }}" id="posted-shops" class="btn btn-default btn-lg">行った</a>
                <a href="{{ url()->current().'?tab=2&filter=2' }}" id="liked-shops" class="btn btn-default btn-lg">お気に入り</a>
            </div>
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
                            @if (isset($user->thumbnail_url) && !empty($user->thumbnail_url))
                            <img alt="ユーザプロフィール画像" src="{{ $user->thumbnail_url }}" class="icon">
                            @else
                            <img alt="ユーザデフォルトプロフィール画像" src="{{ asset('/images/user.png') }}" class="icon">
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
                            @if (isset($user->thumbnail_url) && !empty($user->thumbnail_url))
                            <img alt="ユーザプロフィール画像" src="{{ $user->thumbnail_url }}" class="icon">
                            @else
                            <img alt="ユーザデフォルトプロフィール画像" src="{{ asset('/images/user.png') }}" class="icon">
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
    <!-- ユーザ詳細フッター -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-4702990894338882"
         data-ad-slot="6984922665"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
</div>

@endsection
