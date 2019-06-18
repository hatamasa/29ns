@extends('layouts.app')

@section('style')
<link href="{{ asset('css/users/show.css') }}" rel="stylesheet">
@endsection

@section('script')
<script src="{{ asset('js/users.js') }}"></script>
@endsection

@section('content')

<div class="card">
    <div class="card-title">{{ $users->name }}のページ</div>
    <div class="card-body">
        <div class="user-img">
            @if ($users->thumbnail_url)
            <img alt="" src="{{ $users->thumbnail_url }}">
            @elseif ($users->sex == 1)
            <img alt="" src="{{ asset('/images/man.png') }}">
            @elseif ($users->sex == 2)
            <img alt="" src="{{ asset('/images/woman.png') }}">
            @endif
        </div>
        <div class="user-text">
            <ul>
                <li></li>
                <li>{{ $users->posts_count }}の29ログ</li>
                <li>フォロー{{ $users->follow_count }}件</li>
                <li>フォロワー{{ $users->follower_count }}件</li>
                <li>{{ $users->contents }}</li>
            </ul>
        </div>
    </div>
</div>



@endsection
