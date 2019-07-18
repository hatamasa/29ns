<div class="card">
    <h2 class="card-title"><a href='{{ url("/shops/{$post->shop_cd}") }}'>{{ $post->shop_name }}</a></h2>
    <div class="card-body">
        <div class="post-img">
            <img alt="" src="{{ $post->img_url_1 ?? $post->shop_img_url ?? asset('images/shop.png') }}">
        </div>
        <div class="post-text">
            <ul>
                <li class="post-text-top">
                    <p>{{ $post->score }}点</p>
                    <p>@time_diff($post->post_created_at)</p>
                </li>
                <li class="post-text-top">
                    <a href='{{ url("/users/{$post->user_id}")}}'>
                        <p class="name">
                            @if ($post->user_thumbnail_url)
                            <img alt="" src="{{ $post->user_thumbnail_url }}" class="icon">
                            @elseif ($post->user_sex == 1)
                            <img alt="" src="{{ asset('/images/man.png') }}" class="icon">
                            @elseif ($poser->user_sex == 2)
                            <img alt="" src="{{ asset('/images/woman.png') }}" class="icon">
                            @endif
                            {{ $post->user_name }}
                        </p>
                    </a>
                </li>
                <a href='{{ url("/posts/{$post->id}") }}'>
                    <li class="post-text-center">
                        <p>{{ $post->title }}</p><span>...詳細を見る</span>
                    </li>
                </a>
            </ul>
        </div>
    </div>
    <div class="card-body-footer">
        <ul class="post-text-under">
            <li class="like-disp"><img class="like-icon" src="{{ asset('images/like_black.png') }}">{{ $post->like_count }}</li>
            <li><a href='{{ url("/posts/{$post->id}") }}'><img class="comment-icon" src="{{ asset('images/comment.png') }}">{{ $post->comment_count }}</a></li>
        </ul>
        @auth
        <ul class="post-detail-link">
            <li>
            @if ($post->is_liked)
            <a href='javascript:void(0)' class="like liked" data-post_id="{{ $post->id }}"><img class="like-icon liked" src="{{ asset('images/like.png') }}">済</a>
            @else
            <a href='javascript:void(0)' class="like" data-post_id="{{ $post->id }}"><img class="like-icon like" src="{{ asset('images/like.png') }}"></a>
            @endif
            </li>
            <li><a href='{{ url("/posts/{$post->id}") }}'><img class="comment-icon" src="{{ asset('images/comment.png') }}"></a></li>
            @if (Auth::id() == $post->user_id)
            <li>
                <form action='{{ url("/posts/{$post->id}") }}' method="POST" class="delete-post-form">
                @method("DELETE")
                @csrf
                    <input type="hidden" name="redirect_url" value="{{ url()->full() }}">
                    <button type="submit" class="btn-link delete-link"><img src="{{ asset('images/delete.png') }}"></button>
                </form>
            </li>
            @endif
        </ul>
        @endauth
    </div>
</div>