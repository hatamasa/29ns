<div class="card">
    <h2 class="card-title"><a href='{{ url("/shops/{$post->shop_cd}") }}'>{{ $post->shop_name }}</a></h2>
    <div class="card-body">
        <div class="post-img">
            <a href='{{ url("/shops/{$post->shop_cd}") }}'>
                <img alt="投稿TOP画像" src="{{ $post->img_url_1 ?? $post->shop_img_url ?? asset('images/shop.png') }}">
            </a>
        </div>
        <div class="post-text">
            <ul>
                <li class="post-text-top">
                    <p>{{ $post->score }}点</p>
                    <p>@time_diff($post->post_created_at)</p>
                </li>
                <li class="post-text-top">
                    @if ($post->user_id == 0)
                    <p class="name">
                        <img alt="" src="{{ asset('/images/user.png') }}" class="icon" alt="ユーザデフォルトプロフィール画像">
                        {{ strpos($post->title, '/') ? explode("/", $post->title)[1] : '' }}
                    </p>
                    @else
                    <a href='{{ url("/users/{$post->user_id}")}}'>
                        <p class="name">
                            @if (isset($post->thumbnail_url) && !empty($post->thumbnail_url))
                            <img alt="" src="{{ $post->user_thumbnail_url }}" class="icon" alt="ユーザプロフィール画像">
                            @else
                            <img alt="" src="{{ asset('/images/user.png') }}" class="icon" alt="ユーザデフォルトプロフィール画像">
                            @endif
                            @if ($post->is_resigned)
                                退会済みユーザ
                            @else
                                {{ $post->user_name }}
                            @endif
                        </p>
                    </a>
                    @endif
                </li>
                <a href='{{ url("/posts/{$post->id}") }}'>
                    <li class="post-text-center">
                        @empty ($post->title)
                        <p>{{ $post->shop_name }}へ行きました！</p>
                        @else
                        <p>{{ explode("/", $post->title)[0] }}</p><span>...詳細を見る</span>
                        @endif
                    </li>
                </a>
            </ul>
        </div>
    </div>
    <div class="card-body-footer">
        <ul class="post-text-under">
            <li class="like-disp"><img class="like-icon" src="{{ asset('images/like_black.png') }}" alt="いいねアイコン画像">{{ $post->like_count }}</li>
            <li><a href='{{ url("/posts/{$post->id}") }}'><img class="comment-icon" src="{{ asset('images/comment.png') }}" alt="コメントアイコン画像">{{ $post->comment_count }}</a></li>
        </ul>
        @auth
        <ul class="post-detail-link">
            <li>
                @if ($post->is_liked)
                <a href='javascript:void(0)' class="like-link _liked" data-post_id="{{ $post->id }}"><img class="like-icon _liked" src="{{ asset('images/like.png') }}" alt="いいねアイコン画像">済</a>
                @else
                <a href='javascript:void(0)' class="like-link _notlike" data-post_id="{{ $post->id }}"><img class="like-icon _notlike" src="{{ asset('images/like.png') }}" alt="いいねアイコン画像"></a>
                @endif
            </li>
            <li><a href='{{ url("/posts/{$post->id}") }}'><img class="comment-icon" src="{{ asset('images/comment.png') }}" alt="コメントアイコン画像"></a></li>
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
        @else
        <ul class="post-detail-link">
            <li>
                <a href='javascript:void(0)' class="like-link _loginLink"><img class="like-icon" src="{{ asset('images/like.png') }}" alt="いいねアイコン画像"></a>
            </li>
            <li><a href='{{ url("/posts/{$post->id}") }}'><img class="comment-icon" src="{{ asset('images/comment.png') }}" alt="コメントアイコン画像"></a></li>
        </ul>
        @endauth
    </div>
</div>