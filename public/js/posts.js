(() => {

    /**
     * 投稿詳細
     */
    // コメント
    $("#post-comment-form").submit(evt => {
        let comment_text = document.getElementById("comment-text");
        if(comment_text.value.trim() === "") {
            comment_text.classList.add("error");
            comment_text.placeholder = "コメントを入力してください";
            alert("コメントを入力してください");
            return false;
        }

        $(evt.target).find("[type='submit']").prop("disabled", true);
    });

    /**
     * 投稿作成
     */
    [].forEach.call(document.getElementsByClassName("img"), elem => {
        elem.addEventListener("change", evt => preview(evt));
    });
    function preview(evt) {
        let file = evt.target;
        let strFileInfo = evt.target.files[0];
        let imgArea = evt.target.previousElementSibling;
        let previewArea = evt.target.parentNode;

        if(strFileInfo && strFileInfo.type.match('image.*')){
            let preview = document.createElement("img");
            preview.classList.add("preview");

            fileReader = new FileReader();
            fileReader.onload = event => {
                imgArea.remove();
                previewArea.classList.add("uploaded");
                preview.src = event.target.result;
                previewArea.insertBefore(preview, file);
            }
            fileReader.readAsDataURL(strFileInfo);
        } else if (strFileInfo) {
            alert("不正な画像ファイルがアップロードされました");
        }
    };

    $("#post-from").submit(evt => {
        let score = document.getElementById("score");
        let visit_count = document.getElementById("visit_count");
        let title = document.getElementById("title");
        let contents = document.getElementById("contents");
        score.classList.remove("error");
        visit_count.classList.remove("error");
        title.classList.remove("error");
        contents.classList.remove("error");

        if (score.value === undefined) {
            score.classList.add("error");
            alert("点数を入力してください");
            return false;
        }
        if (visit_count.value === undefined) {
            visit_count.classList.add("error");
            alert("訪問回数を入力してください");
            return false;
        }
        if (title.value.length > 100) {
            title.classList.add("error");
            alert("タイトルは100文字以内で入力してください");
            return false;
        }
        if (contents.value.length > 1000) {
            contents.classList.add("error");
            alert("本文は1000文字以内で入力してください");
            return false;
        }

        $(evt.target).find("[type='submit']").prop("disabled", true);
    });

})();
