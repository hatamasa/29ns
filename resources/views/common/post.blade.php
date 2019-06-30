<div class="card">
    <div class="card-title"><a href='{{ url("/shops/{$post->shop_cd}") }}'>{{ $post->shop_name }}</a></div>
    <div class="card-body">
        <div class="post-img">
            <img alt="" src="{{ $post->img_url_1 ?? $post->shop_img_url ?? asset('images/shop.png') }}">
        </div>
        <div class="post-text">
            <ul>
                <li class="post-text-top">
                    <p>{{ $post->score }}点</p>
                    <a href='{{ url("/users/{$post->user_id}")}}'>
                        <p>
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
                    <p>@time_diff($post->post_created_at)</p>
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
            <li class="like-disp">{{ $post->like_count }}いいね</li>
            <li>コメント{{ $post->comment_count }}件</li>
        </ul>
        @auth
        <ul class="post-detail-link">
            <li>
            @if ($post->is_liked)
            <a href='javascript:void(0)' class="like liked" data-post_id="{{ $post->id }}">いいね済</a>
            @else
            <a href='javascript:void(0)' class="like" data-post_id="{{ $post->id }}">いいね</a>
            @endif
            </li>
            <li><a href='{{ url("/posts/{$post->id}") }}'>コメントする</a></li>
        </ul>
        @endauth
    </div>
</div>