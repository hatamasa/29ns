(() => {

    document.addEventListener('DOMContentLoaded', function() {
        [].forEach.call(document.getElementsByClassName('adsbygoogle'), () => {
            (adsbygoogle = window.adsbygoogle || []).push({});
        });
    });

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

    // ログインへ飛ばす
    const LOGIN_LINK = document.getElementsByClassName("_loginLink");
    if (LOGIN_LINK) {
        [].forEach.call(LOGIN_LINK, elem => {
            elem.addEventListener("click", evt => {
                evt.preventDefault();
                location.href = '/login';
            });
        });
    }

    // 投稿イベント
    const POSTED_WRAP = document.getElementsByClassName("_postedWrap");
    if (POSTED_WRAP) {
        [].forEach.call(POSTED_WRAP, elem => {
            if (elem.classList.contains("posted")) {
                elem.addEventListener("click", evt => {
                    evt.preventDefault();
                    alert("すでに投稿しています。");
                })
            } else {
                elem.addEventListener("click", evt => {
                    evt.preventDefault();
                    $(evt.target).parents('a').addClass('no-active');
                    location.href = $(evt.target).parents('.card-head').find('._postedWrap').data('link');
                });
            }
        });
    }

    // 店舗お気に入りイベント
    const SHOP_LIKE = document.getElementsByClassName("_shopLike");
    if (SHOP_LIKE) {
        [].forEach.call(SHOP_LIKE, elem => {
            elem.addEventListener('click', evt => {
                evt.preventDefault();
                $(evt.target).parents('a').addClass('no-active');
                let cardHead = $(evt.target).parents('.card-head');
                if (cardHead.find('i.fa-star').hasClass("fas")) {
                    unLikeShop(evt, cardHead);
                } else {
                    likeShop(evt, cardHead);
                }
            });
        });
    }
    function unLikeShop(evt, cardHead) {
        cardHead.find('i.fa-star').removeClass('fas');
        cardHead.find('i.fa-star').addClass('far');
        $.ajax({
            url: '/user_like_shops/'+cardHead.find('._shopLike').data('shop_cd'),
            type: 'POST',
            data: {
                '_method': 'DELETE'
            }
        })
        .done(result => {
        })
        .fail(error => {
            cardHead.find('i.fa-star').removeClass('far');
            cardHead.find('i.fa-star').addClass('fas');
            if (error.responseJSON.verified) {
                location.href = '/email/verify';
                return;
            }
            alert("予期せぬエラーが発生しました。");
        })
        .always(() => {
            $(evt.target).parents('a').removeClass('no-active');
        });
    }
    function likeShop(evt, cardHead) {
        cardHead.find('i.fa-star').removeClass('far');
        cardHead.find('i.fa-star').addClass('fas');
        $.ajax({
            url: '/user_like_shops',
            type: 'POST',
            data: {
                'shop_cd': cardHead.find('._shopLike').data('shop_cd'),
            }
        })
        .done(result => {
        })
        .fail(error => {
            cardHead.find('i.fa-star').removeClass('fas');
            cardHead.find('i.fa-star').addClass('far');
            if (error.responseJSON.verified) {
                location.href = '/email/verify';
                return;
            }
            alert("予期せぬエラーが発生しました。");
        })
        .always(() => {
            $(evt.target).parents('a').removeClass('no-active');
        });
    }

    // 投稿いいねイベント
    document.addEventListener('click', evt => {
        switch (true) {
            case evt.target.classList.contains('_liked'):
                removeLike(evt);
                break;
            case evt.target.classList.contains('_notlike'):
                addLike(evt);
                break;
        }
    });
    function removeLike(evt) {
        let like = $(evt.target).parents(".post-detail-link").find("a").get(0);
        $.ajax({
            url: '/post_like_users/'+like.dataset.post_id,
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
            let url_base = location.protocol+"//"+location.host;
            $(evt.target).parents('.card-body-footer').find('.like-disp').html('<img class="like-icon" src="'+url_base+'/images/like_black.png">'+result.like_count);
            like.classList.remove('_liked');
            like.classList.add('_notlike');
            like.innerHTML = '<img class="like-icon notlike" src="'+url_base+'/images/like.png">';
        })
        .fail(data => {
            alert("予期せぬエラーが発生しました。");
        });
    }
    function addLike(evt) {
        let like = $(evt.target).parents(".post-detail-link").find("a").get(0);
        $.ajax({
            url: "/post_like_users",
            type: 'POST',
            data: {'post_id': like.dataset.post_id}
        })
        .done(result => {
            if (! result.return_code) {
                alert(result.message);
                return;
            }
            let url_base = location.protocol+"//"+location.host;
            $(evt.target).parents('.card-body-footer').find('.like-disp').html('<img class="like-icon" src="'+url_base+'/images/like_black.png">'+result.like_count);
            like.classList.remove('_notlike');
            like.classList.add('_liked');
            like.innerHTML = '<img class="like-icon liked" src="'+url_base+'/images/like.png">済';
        })
        .fail(data => {
            alert("予期せぬエラーが発生しました。");
        });
    }

    // ユーザフォローイベント
    const FOLLOW_LINK = document.getElementsByClassName('follow-link');
    if (FOLLOW_LINK) {
        [].forEach.call(FOLLOW_LINK, elem => {
            elem.addEventListener('click', evt => {
                let span = $(evt.target).parents('.follow-icon').find('span').get(0);
                if (span.classList.contains('followed')) {
                    unfollow(evt);
                } else {
                    follow(evt);
                }
            });
        });
    }
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
