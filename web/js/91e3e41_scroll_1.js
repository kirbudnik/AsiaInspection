//Calculating the useable window space and setting section heights
//$(".halfSection").height( parseInt(window.innerHeight) / 2 );
//$(".fullSection").height( parseInt(window.innerHeight) );

navAdj = $(".navbar-ai").height();
$("#fixedNavSpacer").height(navAdj);




//Javascript Media Query
var mq768 = window.matchMedia('all and (max-width: 768px)');
var mq500 = window.matchMedia('all and (max-width: 500px)');

if(!mq768.matches) {
    var vertSect = parseInt(window.innerHeight)/12;
    $(".ScrollSection").height( vertSect*7 );
}else{
    var vertSect = parseInt(window.innerHeight)/12;
    //$(".ScrollSection").height(vertSect*12);
    $("#scrollAnchor_1").height(500);
    $("#scrollAnchor_2").height(650);
    $("#scrollAnchor_3").height(610);
    $("#scrollAnchor_4").height(990);
    $("#scrollAnchor_5").height(780).find(".homeTestimonial").height(780);
    $("#scrollAnchor_6").height(650);
}

mq768.addListener(function(changed) {
    if(changed.matches) {
        //Small Screens
        var vertSect = parseInt(window.innerHeight)/12;
        $(".ScrollSection").height(vertSect*12);
    } else {
        //Large Screens
        var vertSect = parseInt(window.innerHeight)/12;
        $(".ScrollSection").height( vertSect*7 );
    }
});

if(mq500.matches) {
    $(".feature3button", "#scrollAnchor_3").eq(2).html("Technical");
    $(".vcenter_parent", "#homeClientLogoWrapper").addClass("vcenter").removeClass("vcenter_parent");
}
// [END] Javascript Media Query

  //global ipad change
$(function() {
    if((navigator.userAgent.match(/iPad/i)) && (navigator.userAgent.match(/iPad/i)!= null)){
        $(".row").css("margin-left","0px");
        $(".row").css("margin-right","0px");
        $('.slideMainblock').addClass('paddingLeftRightZero');
        $('#scrollAnchor_1').removeClass("slide-feature1");
        $('#scrollAnchor_1').addClass("slide-feature1-ipad");
        $('#pricing').removeClass('vcenter');
        $('#pricing').css("margin-bottom","0px");
        $('#ipadSpectialHomeClientTestimonialWrapper').removeClass('vcenter');
        $('#ipadSection5Title').css("margin-bottom","0px");

      

       
    }
});
//end ipad
    
    var doc = document.documentElement;
    var anchors = new Array();
    
    $(document).ready(function(){
        if( $(".scrollAnchor").length > 0 ){
            $(".scrollAnchor").each(function(){
                anchors.push( [$(this).attr("id"), (parseInt($(this).offset().top) - navAdj)] );
            });
        }
    });
    
    //Scrolling Effect Disabled (they may ask for it back later so it's just commented out for now)
    /*$('body').on('mousewheel', function(event) {
        pagePos = (window.pageYOffset || doc.scrollTop)  - (doc.clientTop || 0);
    
        if(event.deltaY < 0){
            scrollDir = "down";
            pagePos = pagePos + 5;
        }
        if(event.deltaY > 0){
            scrollDir = "up";
            pagePos = pagePos - 5;
        }
    
        if( anchors.length > 0 ){
    
            for (i = 0; i < anchors.length; i++) {
                if( pagePos > anchors[i][1] ){
                    prevAnchor = anchors[i];
                    prevAnchor[2] = i;
                }else{
                    if(i == 0 && pagePos < anchors[i][1]){
                        //prevAnchor = ["body", 0];
                        prevAnchor = [anchors[0][0], anchors[0][1]];
                        prevAnchor[2] = -1;
                    }
                }
            }
    
            if(scrollDir == "up"){
                if( anchors[prevAnchor[2]] != undefined ){
                    if( pagePos >= prevAnchor[1] ) JQscrollTo(anchors[prevAnchor[2]][0], event);
                }else if(prevAnchor[2] == -1) JQscrollTo("body", event);
    
            }else{ //(scrollDir == "down")
                if( anchors[prevAnchor[2] + 1] != undefined ){
                    if( pagePos >= prevAnchor[1] ) JQscrollTo(anchors[prevAnchor[2] + 1][0], event);
                }
            }
    
        }
    
    });*/
    
    function testOut(y, scrollDir, msg){
        $("#TestDiv").html("<b>Y:</b> " + y + "<br /><b>Dir:</b> " + scrollDir + "<br /><i>" + msg + "</i><br />");
    }
    
    function JQscrollTo(target, event){
        if(event) event.preventDefault();
        if(target != "body") target = "#" + target;
        scroll2Pos = parseInt( $(target).offset().top - navAdj );
        $('html, body').stop(true).animate({ scrollTop: scroll2Pos }, 900);
    }

//Testimonial Slider [Begin]
$(function() {
    $('#TestimonialSlider').unslider({
        speed: 500,
        delay: 10000
    });
    if((navigator.userAgent.match(/iPad/i)) && (navigator.userAgent.match(/iPad/i)!= null)){
        $('#TestimonialSlider').css( "width", "100%" );
    }
});

if(mq768.matches) {
    charlimit = 350;
}else{
    charlimit = 470;    
}

$('.sliderText','#TestimonialSlider').each(function(){
    if ( $(this).text().length > charlimit ){
        origtext = $(this).text();
        newText = origtext.substr(0,charlimit) + ' ..."';
        $(this).text( newText );
    }
});
//Testimonial Slider [End]

//Industry Background Slider [Begin]
$(function() {
    if((navigator.userAgent.match(/iPad/i)) && (navigator.userAgent.match(/iPad/i)!= null)){
        $('#scrollAnchor_3').removeClass('slide-feature3');
        $('#scrollAnchor_3').addClass('slide-feature3-ipad');
    }else{
    var slidey = $('#IndustryBGslider').unslider({
        speed: 1500,
        delay: 8000
    });
    IndustrySlider = slidey.data('unslider');
    IndustrySlider.next();
}
});
//Industry Background Slider [End]

//Video Background [Begin]
if(!mq768.matches) {
    //special div for ipad
    if((navigator.userAgent.match(/iPad/i)) && (navigator.userAgent.match(/iPad/i)!= null)){
        $('#scrollAnchor_1').removeClass("slide-feature1");
        $('#scrollAnchor_1').addClass("slide-feature1-ipad");
    //for the rest
    }else{

        $('#scrollAnchor_1').videoBG({
            position:"absolute",
            zIndex:0,
            poster:'https://s3.asiainspection.com/images/responsive/main_right.jpg',
            mp4:'https://s3.asiainspection.com/images/responsive/video/AI_bg.m4v',
            webm:'https://s3.asiainspection.com/images/responsive/video/AI_bg.webm',
            ogv:'https://s3.asiainspection.com/images/responsive/video/AI_bg.ogv',
            opacity:1,
            scale:true
        });
    }
    //$(document).ready(function(){
    //  $('video').css({"max-height":"170%"});  
    //})
}
//Video Background [End]



//} //End of 768px Media Query
