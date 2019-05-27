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


})();