(() => {

    let arg = {};
    let pair=location.search.substring(1).split('&');
    for(let i=0;pair[i];i++) {
        let kv = pair[i].split('=');
        arg[kv[0]]=kv[1];
    }

    if (arg.tab == undefined || arg.tab == 1) {
        document.getElementsByClassName("users-page-tab")[0].children[0].classList.add("current");
    }
    if (arg.tab != undefined && arg.tab == 2) {
        document.getElementsByClassName("users-page-tab")[0].children[1].classList.add("current");
    }
    if (arg.tab != undefined && arg.tab == 3) {
        document.getElementsByClassName("users-page-tab")[0].children[2].classList.add("current");
    }
    if (arg.tab != undefined && arg.tab == 4) {
        document.getElementsByClassName("users-page-tab")[0].children[3].classList.add("current");
    }

})();
