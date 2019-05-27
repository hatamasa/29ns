(() => {

    /****************************************************************/
    /*********************  station.blade.php   *********************/
    /****************************************************************/
    // 駅がチェックされたら個数バリデーションを行う
    [].forEach.call(document.getElementsByClassName("_stationInput"), elem => {
        elem.addEventListener("click", evt => stationClicked(evt));
    });
    function stationClicked(evt) {
        let tgt = evt.target;
        // チェックがつくときは10個を超えないようにチェックする
        if (tgt.checked) {
            if (document.querySelectorAll("._stationInput:checked").length > 10) {
                tgt.checked = false;
                alert('駅の選択は10個までです。');
                return;
            }
        }
        // チェックされている駅が一つでもあればボタンを表示する
        let checkedInput = tgt.parentNode.parentNode.querySelectorAll("._stationInput:checked");
        if (checkedInput.length > 0) {
            document.getElementById("_stationSearchSubmitWap").style.display = "block";
        } else {
            document.getElementById("_stationSearchSubmitWap").style.display = "none";
        }
    }

    /****************************************************************/
    /*********************  area.blade.php      *********************/
    /****************************************************************/
    // エリアLがチェックされたら配下のエリアMを全てチェック、外れたら全て外す
    [].forEach.call(document.getElementsByClassName("_areaLInput"), elem => {
        elem.addEventListener("click", evt => areaLClicked(evt));
    });
    function areaLClicked(evt) {
        let tgt = evt.target;
        // チェックがつくときは10個を超えないようにチェックする
        let areaMInput = tgt.parentNode.nextElementSibling.getElementsByClassName("_areaMInput");
        let areaMInputChecked = tgt.parentNode.nextElementSibling.querySelectorAll("._areaMInput:checked");
        if (tgt.checked) {
            if ((areaMInput.length - areaMInputChecked.length
                + document.querySelectorAll("._areaMInput:checked").length) > 10) {
                tgt.checked = false;
                alert("エリアの選択は10個までです。");
                return;
            }
        }
        // エリアLの入力に応じてエリアMをつけたり外したりする
        [].forEach.call(areaMInput, elem => {
            if (tgt.checked) {
                elem.checked = true;
            } else {
                elem.checked = false;
            }
        });
        // チェックされているエリアMが一つでもあればボタンを表示する
        let checkedInputM = tgt.parentNode.parentNode.querySelectorAll("._areaMInput:checked");
        if (checkedInputM.length > 0) {
            document.getElementById("_areaSearchSubmitWap").style.display = "block";
        } else {
            document.getElementById("_areaSearchSubmitWap").style.display = "none";
        }
    }

    [].forEach.call(document.getElementsByClassName("_areaMInput"), elem => {
        elem.addEventListener("click", evt => areaMClicked(evt));
    });
    function areaMClicked(evt) {
        let tgt = evt.target;
        // チェックがつくときは10個を超えないようにチェックする
        if (tgt.checked) {
            if (document.querySelectorAll("._areaMInput:checked").length > 10) {
                tgt.checked = false;
                alert('エリアの選択は10個までです。');
                return;
            }
        }
        let areaLInput = tgt.parentNode.parentNode.previousElementSibling.getElementsByClassName("_areaLInput")[0];
        let checkedInputM = tgt.parentNode.parentNode.querySelectorAll("._areaMInput:checked");
        if (areaLInput.checked) {
            // エリアLがチェックされた状態でエリアMのチェックを外した場合はエリアLのチェックを外す
            areaLInput.checked = false;
        } else {
            // エリアL配下のMが全てチェックされたらエリアLをチェックする
            let inputM = tgt.parentNode.parentNode.querySelectorAll("._areaMInput");
            if (checkedInputM.length == inputM.length) {
                areaLInput.checked = true;
            }
        }
        // チェックされているエリアMが一つでもあればボタンを表示する
        if (checkedInputM.length > 0) {
            document.getElementById("_areaSearchSubmitWap").style.display = "block";
        } else {
            document.getElementById("_areaSearchSubmitWap").style.display = "none";
        }
    }

})();