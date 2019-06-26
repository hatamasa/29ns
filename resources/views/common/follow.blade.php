@if ($user->id != Auth::id())
    @if ($user->is_followed)
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