(() => {

    let page = 1; // 一覧の現在のページ

    let token = document.getElementById("token").value;
    $.ajaxSetup({
        headers: {
            'Authorization' : 'bearer '+token
        }
    });

    /** 投稿一覧スクロールイベント登録 */
    $('#postList').on('inview', '#inviewLoad',(event, isInView) => {
            console.log("inview");
        if (isInView) getPostList();
    });
    /** 投稿取得結果作成 */
    function getPostList(){
        $.ajax({
            url: '/api/v1/getPostList?page='+page,
            type: 'GET',
        })
        .done( result => {
            let val = result;
//            let val = JSON.parse(result);
            if(val['return_code'] == 1){
                alert('投稿を取得できませんでした');
                return;
            }
            let list = [];
            if (val['posts'].length == 0) {
                $('#inviewLoad').remove();
                $('#postList').after(list.join(""));
            } else {
                $.each(val['posts'], (idx, val) => {
                    list.push('<div class="card">');
                    list.push('<div class="card-header">');
                    if (val['thumbnail_url']) {
                            list.push('<img src="'+val['thumbnail_url']+'">');
                    } else {
                            list.push('<img src="../images/user.png">');
                    }
                    list.push('<div>');
                    if (val['groups_title']) {
                        list.push('<p>'+val['users_name']+'が'+val['groups_title']+'に投稿しました</p>');
                    } else {
                        list.push('<p>'+val['users_name']+'が投稿しました</p>');
                    }
                    list.push('<p>'+dateToStr24H(new Date(val['posts_created_at']), 'Y/M/D h:m')+'</p>');
                    list.push('</div>');
                    list.push('</div>');
                    list.push('<div class="card-body">');
                    list.push('<div>'+val['posts_title']+'</div>');
                    list.push('<div>'+val['posts_contents']+'</div>');
                    list.push('</div>');
                    list.push('</div>');
                });
                list.push('<div id="inviewLoad"></div>');
                $('#inviewLoad').remove();
                $('#postList').append(list.join(""));
            }
            page++;
        })
        .fail( (XMLHttpRequest, textStatus, errorThrown) => {
            alert('通信エラーが発生しました');
        });
    }

    function dateToStr24H(date, format) {
        if (!format) {
            format = 'Y/M/D h:m:s';
        }
        format = format.replace(/Y/g, date.getFullYear());
        format = format.replace(/M/g, (date.getMonth() + 1));
        format = format.replace(/D/g, date.getDate());
        format = format.replace(/h/g, date.getHours());
        format = format.replace(/m/g, date.getMinutes());
        format = format.replace(/s/g, date.getSeconds());
        return format;
    }

})();