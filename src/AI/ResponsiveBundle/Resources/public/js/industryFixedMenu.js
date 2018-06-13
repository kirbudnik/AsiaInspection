var mq768 = window.matchMedia('all and (max-width: 768px)');

// Adjust nav seperator height so they are all the same
if(!mq768.matches) {
    topOffset = 0;
    if(navigator.userAgent.toLowerCase().indexOf('firefox') > -1) topOffset = -140;
    if(navigator.userAgent.toLowerCase().indexOf('chrome') > -1) topOffset = 60;

    topDistance = $("#nmiBox").position().top + $(".mainBlock").position().top  + topOffset;
    bottomDistance = $(".footer").position().top - $("#nmiBox").height() - parseInt($("#nmiBox").css("padding-bottom")) - parseInt($("#nmiBox").css("padding-top"));

    BoxWidth = $("#nmiBox").width() + parseInt($("#nmiBox").css("padding-left")) + parseInt($("#nmiBox").css("padding-right"));
    rightPos = $(window).width() - ($('#nmiBox').offset().left + BoxWidth);

    $(document).scroll(function(e) {
        if(!mq768.matches) {
            var scrollpos = $(window).scrollTop();  // Get Scroll Position
            // console.log( topDistance + " - " + scrollpos + " ["+ (scrollpos - topDistance ) +"]" );
            if( scrollpos > topDistance ) {
                parentBox = $("#nmiBox").parent();
                $(parentBox).css("min-height", $(parentBox).height() + "px");
                topPos = 100;
                if( scrollpos > bottomDistance ) { topPos =  100 - (scrollpos - bottomDistance); }
                $("#nmiBox").css({
                    "position":"fixed",
                    "top":topPos + "px",
                    "right":rightPos + "px",
                    "z-index":"1998",
                    "max-width":BoxWidth + "px",
                    "border-width":"1px",
                    "border-style":"solid",
                    "border-color":"black"
                });
            } else {
                $("#nmiBox").css({
                    "position":"relative",
                    "top":"0px",
                    "right":"0px",
                    "border":"none",
                    "max-width":"none"
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
        BoxWidth = $("#nmiBox").siblings(".offer-box").width() + parseInt($("#nmiBox").css("padding-left")) + parseInt($("#nmiBox").css("padding-right"));
        rightPos = $(window).width() - ($('#nmiBox').siblings(".offer-box").offset().left + BoxWidth);
        $(document).scroll();
    }
});

//4995.60009765625 - 3835 (scrollpos)