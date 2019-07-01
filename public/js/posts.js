(() => {

    const THUMB_SIZE = 200;
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
            let image = new Image();
            fileReader = new FileReader();
            image.onload = function() {
                let cnv = document.createElement('canvas');
                let ratio = image.naturalWidth / image.naturalHeight;
                if (ratio == 1) {
                    cnv.width = THUMB_SIZE;
                    cnv.height = THUMB_SIZE;
                } else if (ratio > 1) {
                    cnv.width = THUMB_SIZE * ratio;
                    cnv.height = THUMB_SIZE;
                } else if (ratio < 1) {
                    cnv.width = THUMB_SIZE;
                    cnv.height = THUMB_SIZE / ratio;
                }
                let ctx = cnv.getContext('2d');
                ctx.drawImage(image, 0, 0, cnv.width, cnv.height);
                if(cnv.msToBlob) {
                    preview.src = URL.createObjectURL(cnv.msToBlob());
                    fileReader.readAsDataURL(cnv.msToBlob());
                } else {
                    cnv.toBlob(blob => {
                        preview.src = URL.createObjectURL(blob);
                        fileReader.readAsDataURL(blob);
                    }, 'image/png'); // msToBlobと合わせるためpngに設定
                }
                imgArea.remove();
                previewArea.classList.add("uploaded");
                let preview = document.createElement("img");
                preview.classList.add("preview");
                previewArea.insertBefore(preview, file);
            }
            image.src = URL.createObjectURL(strFileInfo);
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
