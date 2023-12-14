$(function() {
    $('.tooltip-9').tooltip({
        track: true,
        hide: {
            effect: "explode",
            delay: 250
        }
    });
});

function pageScroll(count) {
    count = parseInt(count);
    if(count > 3){
        window.scrollBy(0,1);
        scrolldelay = setTimeout(pageScroll,100, count);
        setInterval('autoRefresh()', (count * 5)*1000);
    }else{
        setInterval('autoRefresh()', 5000);
    }   
}


function autoRefresh() {
    window.location = window.location.href;
}