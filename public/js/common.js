(() => {

//    let token = document.getElementById("token").value;
//    $.ajaxSetup({
//        headers: {
//            'Authorization' : 'bearer '+token
//        }
//    });

    const FLASH = document.getElementsByClassName('flash');
    if (FLASH) {
        [].forEach.call(FLASH, elem => {
            $(elem).fadeOut(4000);
        });
    }

    $("form").submit(evt => {
        let is_error = false;
        [].forEach.call(evt.target.getElementsByClassName("text-required"), elem => {
            if(elem.value.trim() === "") {
                elem.classList.add("error");
                elem.placeholder = elem.dataset.name + "を入力してください";
                alert(elem.dataset.name + "を入力してください");
                is_error = true;
            }
        });
        if (is_error) return false;

        $(evt.target).find("[type='submit']").prop("disabled", true);
    });

})();
