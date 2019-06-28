(() => {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const FLASH = document.getElementsByClassName('flash');
    if (FLASH) {
        [].forEach.call(FLASH, elem => {
            $(elem).fadeOut(4000);
        });
    }

    // 店舗お気に入りイベント
    [].forEach.call(document.getElementsByClassName("star-wrap"), elem => {
        elem.addEventListener('click', evt => {
            evt.preventDefault();
            $(evt.target).parents('a').addClass('no-active');
            if (evt.target.classList.contains("fas")) {
                evt.target.classList.remove('fas');
                evt.target.classList.add('far');
                unLikeShop(evt);
            } else {
                evt.target.classList.remove('far');
                evt.target.classList.add('fas');
                likeShop(evt);
            }
        });
    });
    function unLikeShop(evt) {
        $.ajax({
            url: '/user_like_shops/'+evt.target.parentNode.dataset.shop_cd,
            type: 'POST',
            data: {
                '_method': 'DELETE'
            }
        })
        .done(result => {
            if (! result.return_code) {
                alert(result.message);
                return;
            }
        })
        .fail(error => {
            alert("予期せぬエラーが発生しました。");
        });
    }
    function likeShop(evt) {
        $.ajax({
            url: '/user_like_shops',
            type: 'POST',
            data: {
                'shop_cd': evt.target.parentNode.dataset.shop_cd,
            }
        })
        .done(result => {
            if (! result.return_code) {
                alert(result.message);
                return;
            }
        })
        .fail(error => {
            alert("予期せぬエラーが発生しました。");
        });
    }

    // 投稿いいねイベント
    document.addEventListener('click', evt => {
        switch (true) {
            case evt.target.classList.contains('liked'):
                removeLike(evt);
                break;
            case evt.target.classList.contains('like'):
                addLike(evt);
                break;
        }
    });

    // like除外処理
    function removeLike(evt) {
        $.ajax({
            url: '/post_like_users/'+evt.target.dataset.post_id,
            type: 'POST',
            data: {
                'post_id': evt.target.dataset.post_id,
                '_method': 'DELETE'
            }
        })
        .done(result => {
            if (! result.return_code) {
                alert(result.message);
                return;
            }
            $(evt.target).parents('.card-body-footer').find('.like-disp').text(data.like_count+'いいね');
            evt.target.classList.remove('liked');
            evt.target.innerText = 'いいね';
        })
        .fail(data => {
            alert("予期せぬエラーが発生しました。");
        });
    }
    // like付与処理
    function addLike(evt) {
        $.ajax({
            url: "/post_like_users",
            type: 'POST',
            data: {'post_id': evt.target.dataset.post_id}
        })
        .done(result => {
            if (! result.return_code) {
                alert(result.message);
                return;
            }
            $(evt.target).parents('.card-body-footer').find('.like-disp').text(data.like_count+'いいね');
            evt.target.classList.add('liked');
            evt.target.innerText = 'いいね済';
        })
        .fail(data => {
            alert("予期せぬエラーが発生しました。");
        });
    }
})();
