@if ($user->id != Auth::id())
    @if ($user->is_followed)
    <li class="follow-icon followed-li">
        <div class="follow-link" data-user_id="{{ $user->id }}">
            フォロー中<div><span class="followed"></span></div>
        </div>
    </li>
    @else
    <li class="follow-icon follow-li">
        <div class="follow-link" data-user_id="{{ $user->id }}">
            フォロー<div><span class="follow"></span></div>
        </div>
    </li>
    @endif
@endif