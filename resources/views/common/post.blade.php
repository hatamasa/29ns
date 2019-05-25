<div class="card">
    <div class="card-title">{{ $post->shop_name }}</div>
    <div class="card-body">
        <div class="post-img">
            <img alt="" src="{{ $post->img_url_1 ?? $post->shop_img_url ?? asset('images/shop.png') }}">
        </div>
        <div class="post-text">
            <ul>
                <li class="post-text-top">
                    <p>{{ $post->score }}点</p>
                    <p>
                        <a href='{{ url("/users/{$post->user_id}")}}'>
                            <img alt="" src="{{ $post->user_thumbnail_url }}">{{ $post->user_name }}
                        </a>
                    </p>
                    <p>@time_diff($post->post_created_at)</p>
                </li>
                <li class="post-text-center">
                    <a href='{{ url("/posts/{$post->id}") }}'><span>{{ $post->title }}</span> ...詳細を見る</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-body-footer">
        <ul class="post-text-under">
            <li>{{ $post->like_count }}いいね</li>
            <li>コメント{{ $post->comment_count }}件</li>
        </ul>
    </div>
</div>