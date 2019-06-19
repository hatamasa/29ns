@extends('layouts.app')

@section('style')
<link href="{{ asset('css/users/show.css') }}" rel="stylesheet">
@endsection

@section('script')
<script src="{{ asset('js/users.js') }}"></script>
@endsection

@section('content')
<div>
    <div class="block-head">
        <p>{{ $users->name }}さんのページ</p>
        <a href="{{ url('/user').'/'.$users->id.'/'.'edit' }}">編集→</a>
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
        @if (strpos(url()->full(), 'tab=2'))
            @foreach ($list as $shop)
                @include('common.shop_users_page', ['shop' => $shop])
            @endforeach
        @elseif (strpos(url()->full(), 'tab=3'))

        @elseif (strpos(url()->full(), 'tab=4'))

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
