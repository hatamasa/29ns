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
    [].forEach.call(document.getElementsByClassName("shop-like"), elem => {
        elem.addEventListener('click', evt => {
            evt.preventDefault();
            $(evt.target).parents('a').addClass('no-active');
            let cardHead = $(evt.target).parents('.card-head');
            if (cardHead.find('i').hasClass("fas")) {
                unLikeShop(cardHead);
            } else {
                likeShop(cardHead);
            }
        });
    });
    function unLikeShop(cardHead) {
        cardHead.find('i').removeClass('fas');
        cardHead.find('i').addClass('far');
        $.ajax({
            url: '/user_like_shops/'+cardHead.find('.shop-like').data('shop_cd'),
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
    function likeShop(cardHead) {
        cardHead.find('i').removeClass('far');
        cardHead.find('i').addClass('fas');
        $.ajax({
            url: '/user_like_shops',
            type: 'POST',
            data: {
                'shop_cd': cardHead.find('.shop-like').data('shop_cd'),
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

    // ユーザフォローイベント
    [].forEach.call(document.getElementsByClassName('follow-link'), elem => {
        elem.addEventListener('click', evt => {
            let span = $(evt.target).parents('.follow-icon').find('span').get(0);
            if (span.classList.contains('followed')) {
                unfollow(evt);
            } else {
                follow(evt);
            }
        });
    });
    function unfollow(evt) {
        let followLink = $(evt.target).parents('.follow-icon').find('.follow-link');
        followLink.parent().removeClass('followed-li');
        followLink.parent().addClass('follow-li');
        followLink.html('フォロー<div><span class="follow"></span></div>');
        $.ajax({
            url: '/user_follows/'+followLink.data('user_id'),
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
            alert('予期せぬエラーが発生しました。');
        });
    }
    function follow(evt) {
        let followLink = $(evt.target).parents('.follow-icon').find('.follow-link');
        followLink.parent().removeClass('follow-li');
        followLink.parent().addClass('followed-li');
        followLink.html('フォロー中<div><span class="followed"></span></div>');
        $.ajax({
            url: '/user_follows',
            type: 'POST',
            data: {
                'follow_user_id': +followLink.data('user_id'),
            }
        })
        .done(result => {
            if (! result.return_code) {
                alert(result.message);
                return;
            }
        })
        .fail(error => {
            alert('予期せぬエラーが発生しました。');
        });
    }


})();
