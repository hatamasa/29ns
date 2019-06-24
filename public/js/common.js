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
    let clickFlg = false;
    [].forEach.call(document.getElementsByClassName("star-wrap"), elem => {
        elem.addEventListener('click', evt => {
            evt.preventDefault();
            if (clickFlg) {
                alert("ただいま処理中です。");
                return false;
            }
            clickFlg = true;
            evt.target.style.color = '#d1d1d1';
            $(evt.target).parents('a').addClass('no-active');
            $(evt.target).parents('form').submit();
        });
    });

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
        .done(data => {
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
        .done(data => {
            $(evt.target).parents('.card-body-footer').find('.like-disp').text(data.like_count+'いいね');
            evt.target.classList.add('liked');
            evt.target.innerText = 'いいね済';
        })
        .fail(data => {
            alert("予期せぬエラーが発生しました。");
        });
    }
})();
