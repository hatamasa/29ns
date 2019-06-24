@extends('layouts.app')

@section('style')
<link href="{{ asset('css/users/show.css') }}" rel="stylesheet">
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

</script>
@endsection

@section('content')
<div>
    <div class="block-head">
        <p>{{ $users->name }}さんのページ</p>
        @if (Auth::id() == $users->id)
        <a href='{{ url("/users/{$users->id}/edit") }}'>編集→</a>
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
                        <li>{{ $users->posts_count }}件の29ログ</li>
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
            <a href="{{ url()->current().'?tab=1' }}"><li>29ログ</li></a>
            <a href="{{ url()->current().'?tab=2' }}"><li>お気に入り</li></a>
            <a href="{{ url()->current().'?tab=3' }}"><li>フォロー</li></a>
            <a href="{{ url()->current().'?tab=4' }}"><li>フォロワー</li></a>
        </ul>
        <div>
        {{-- お気に入り --}}
        @if (strpos(url()->full(), 'tab=2'))
            @foreach ($list as $shop)
                @include('common.shop_users_page', ['shop' => $shop])
            @endforeach

        {{-- フォロー --}}
        @elseif (strpos(url()->full(), 'tab=3'))
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
                @if (Auth::id() == $users->id)
                <li class="follow-icon followed-li">
                    <form action='{{ url("/user_follows/{$user->follow_user_id}") }}' method="POST">
                        @method("DELETE")
                        @csrf
                        <button type="submit">フォロー中<div><span class="followed"></span></div></button>
                    </form>
                </li>
                @endif
            </ul>
            @endforeach

        {{-- フォロワー --}}
        @elseif (strpos(url()->full(), 'tab=4'))
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
                @if (Auth::id() == $users->id)
                    @if ($user->is_follow_each)
                    <li class="follow-icon followed-li">
                        <form action='{{ url("/user_follows/{$user->id}") }}' method="POST">
                            @method("DELETE")
                            @csrf
                            <button type="submit">フォロー中<div><span class="followed"></span></div></button>
                        </form>
                    </li>
                    @else
                    <li class="follow-icon follow-li">
                        <form action='{{ url("/user_follows") }}' method="POST">
                            @csrf
                            <input type="hidden" name="follow_user_id" value="{{ $user->id }}">
                            <button type="submit">フォロー<div><span class="follow"></span></div></button>
                        </form>
                    </li>
                    @endif
                @endif
            </ul>
            @endforeach

        {{-- 29ログ --}}
        @else
            @foreach ($list as $post)
                @include('common.post', ['post' => $post])
            @endforeach
        @endif
        </div>

    </div>
    {{ $list->appends($input)->links('common.pagination') }}
</div>


@endsection
