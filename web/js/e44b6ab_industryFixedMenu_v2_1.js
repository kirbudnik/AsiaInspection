var mq768 = window.matchMedia('all and (max-width: 768px)');

// Adjust nav seperator height so they are all the same
if(!mq768.matches) {
    topOffset = 0;
    if(navigator.userAgent.toLowerCase().indexOf('firefox') > -1) topOffset = 200;
    if(navigator.userAgent.toLowerCase().indexOf('chrome') > -1) topOffset = 210;

    topDistance = $("#sideBoxContainer").height() + ( 0 - $("#sideBoxContainer").position().top );
    bottomDistance = $(".footer").position().top - $("#nmiBox").height();
    $(window).load(function(){
        if( $("#globalNetworkBox").length > 0 ) {
            bottomDistance = $("#globalNetworkBox").position().top - $("#nmiBox").height() - $(".headerbox").height();
        } else {
            bottomDistance = $(".footer").position().top - $("#nmiBox").height() - $(".headerbox").height();    
        }
        topDistance = $("#sideBoxContainer").height();
    });

    $(document).scroll(function(e) {
        if( !mq768.matches && (bottomDistance > topDistance) ) {
            var scrollpos = $(window).scrollTop();  // Get Scroll Position
            if( scrollpos > topDistance ) {
                topPos = scrollpos - topOffset;
                if( scrollpos > bottomDistance ) {
                    topPos =  bottomDistance;
                }
                $("#nmiBox").css({
                    "position":"relative",
                    "z-index":"1998",
                    "border-width":"1px",
                    "border-style":"solid",
                    "border-color":"black",
                    "top":topPos + "px"
                });
                if ($("#nmiBox").position().top == 0) $("#nmiBox").css("top",topPos + "px"); // bug fix for it sometimes jumping back to 0
            } else {
                $("#nmiBox").css({
                    "position":"relative",
                    "top":"0px",
                    "border":"0px"
                });
            }
        }
    });
}

$(window).resize(function(e) {
    if(mq768.matches){
        $("#nmiBox").css({
            "position":"relative",
            "top":"0px",
            "right":"0px",
            "border":"none",
            "max-width":"none"
        });
    } else {
        $(document).scroll();
    }
});