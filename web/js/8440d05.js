/**
 * Javascript specificially for the Homepage
 */

//Javascript Media Query
var mq768 = window.matchMedia('all and (max-width: 768px)');
var mq500 = window.matchMedia('all and (max-width: 500px)');

//Testimonial Slider [Begin]
$(function() {
    $('#SliderAssurance').unslider({
        nav: true,
        dots: true,
        speed: 500,
        delay: 2500,
        // delay: false,
        // animateHeight: false,
        // autoplay: false,
        fluid: true,
    });
    if((navigator.userAgent.match(/iPad/i)) && (navigator.userAgent.match(/iPad/i)!= null)){
        $('#SliderAssurance').css( "width", "100%" );
    }
});

// function GoToSlide(id){
//     slider = $('#SliderAssurance');
//     slider.unslider('stop');
//     slider.unslider('animate:'+id);
// }

charlimit = mq768.matches ? 350 : 470;

var vid = $('video#v');
var overlay = $('#videoOverlayHomepage');
// var cvs = $('canvas#c');
if(mq768.matches) {
    vid.removeAttr('autoplay');
    vid.removeAttr('controls');
    vid.attr('preload', 'none');
    vid.hide();
    // cvs.hide();
    overlay.hide();
    $('#multiDeviceImage').hide();

    // NOTE THIS IS A VERY HACKY SOLUTION TO A LAYOUT PROBLEM WHICH SHOULD BE SOLVED BY CSS
    // AND IS LIABLE TO CAUSE SOME CONFUSION IF YOU EXPECT (NATURALLY) THE DOM STRUCTURE TO MATCH THE HTML
    // move the pictograms in the assurance slider out of the <p> so that they appear above instead of beside
    $('.assuranceSlide').each(function () {
        var $pictogram = $('.sliderText > .assurancePictogram', this);
        $pictogram.parent().before($pictogram);
    });

} else {
    vid.attr('controls', '');
    vid.attr('autoplay', '');
    vid.attr('preload', 'auto');
    vid.show();
    // cvs.show();
    overlay.show();
    $('#multiDeviceImage').show();
}


$('.sliderText','#SliderAssurance').each(function(){
    if ( $(this).text().length > charlimit ){
        origtext = $(this).text();
        newText = origtext.substr(0,charlimit) + ' ..."';
        $(this).text( newText );
    }
});

// Homepage Animation
$(document).ready(function() {
    if( window.innerHeight > 900 ) animateHomepageTablets();
    windowHeightAdj = (parseInt(window.innerHeight)/1.7);
    animateTabletsPosition = $("#multiDeviceImage").offset().top - windowHeightAdj;
    animatePublicationsPosition = $("#sectionFour").offset().top - windowHeightAdj;
    animateServicesPosition = $("#sectionThree").offset().top - windowHeightAdj;
    animatePricesPosition = $("#sectionSix").offset().top - windowHeightAdj;
    
    // Animate first row of icons on the page on page load
    animateAppear("homeFeaturesIcon1", 100, 1000);
    animateAppear("homeFeaturesIcon2", 300, 1000);
    animateAppear("homeFeaturesIcon3", 500, 1000);
    animateAppear("homeFeaturesIcon4", 700, 1000);

    window.addEventListener('scroll', homepageAnimation);

    // Safari 10+ fix where overlay wasn't covering the video after animation completed
    if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
        videoTop = $("#v").css("top");
        watchVideoHeight = setInterval(function() {
            videoTopLatest = $("#v").css("top");
            if(videoTopLatest != videoTop && videoTop != "0px") {
                $("#v").css("top", (0 - parseInt(videoTopLatest)) + 10 );
                clearInterval(watchVideoHeight);
            }
            videoTop = $("#v").css("top");
        }, 1000);
    } // Enf of IF SAFARI block
    
});

function homepageAnimation() {
    pagePos = (window.pageYOffset || document.documentElement.scrollTop)  - (document.documentElement.clientTop || 0);

    // Animate tablets at the top
    if(pagePos > animateTabletsPosition) animateHomepageTablets();

    // Animate Services by Industry section
    if(pagePos > animateServicesPosition) {
        animateAppear("homeServiceBox1", 100, 1000);
        animateAppear("homeServiceBox2", 300, 1000);
        animateAppear("homeServiceBox3", 500, 1000);
        animateAppear("homeServiceBox4", 700, 1000);
    }

    // Animate Latest Publications section
    //if(pagePos > animatePublicationsPosition) {
    //    animateAppear("publicationImage0", 500, 500);
    //    animateAppear("publicationImage1", 10, 500);
    //    animateAppear("publicationImage2", 1000, 500);
    //}

    // Animate Pricing section at the bottom of the page
    //if(pagePos > animatePricesPosition) {
    //    animateAppear("homePriceBox0", 100, 1000);
    //    animateAppear("homePriceBox1", 400, 1000);
    //    animateAppear("homePriceBox2", 700, 1000);
    //}
}

function animateAppear(id, pause, speed) {
    // Only trigger this if the item is already invisible
    if( $("#"+id).css("opacity") <= 0 ) {

        $("#"+id).css({
            position: "relative",
            top: 50
        });
        setTimeout(function(){
            $("#"+id).animate({
                top: 0,
                opacity: 1
            }, speed);    
        }, pause);

    }
}

function animateHomepageTablets() {
    $("#animateHomepageDesktop").animate({
        bottom: "0"
    }, 1300);
    setTimeout(function(){
        $("#animateHomepageTablet").animate({
            bottom: "0"
        }, 1300);
        $("#animateHomepageMobile").animate({
            bottom: "0"
        }, 1500);
    }, 400);
}

//Testimonial Slider [End]

// function filtervideo(idata) {
//     var data = idata.data;
//     for(var i = 0; i < data.length; i+=4) {
//         // For Tinting
//         //var average = (data[i] + data[i+1] + data[i+2]) / 6;
//         //data[i] = average + 50;          // Red
//         //data[i+1] = average + 0;        // Green
//         //data[i+2] = average + 0;       // Blue
//         data[i] = data[i] / 3;      // Red
//         data[i+1] = data[i+1] / 3;  // Green
//         data[i+2] = data[i+2] / 3;  // Blue
//     }
//
//     idata.data = data;
//     return idata;
// }

// function draw(v,c,w,h) {
//     c.drawImage(v,0,0,w,h);
//     //boxBlurCanvasRGBA('c', 0, 0, w, h, 6, 1);
//     // var idata = c.getImageData(0,0,w,h);
//     // newdata = filtervideo(idata);
//     // c.putImageData(newdata,0,0);
//     setTimeout(draw,20,v,c,w,h);
// }


// Video effects on the homepage (Bluring and tinting)
// $(function() {
//     if(! mq768.matches) {
//         var v = document.getElementById('v');
//         var canvas = document.getElementById('c');
//         var cw = canvas.clientWidth;
//         var ch = canvas.clientHeight;
//         canvas.width = cw;
//         canvas.height = ch;
//         var context = canvas.getContext('2d');
//         draw(v, context, cw, ch);
//     }
// }); // End of 'Video effects on the homepage'


onResize = function() {
    var maxheight = 0;
    $('.homeDemoBox > .title').each(function () {
        maxheight = ($(this).height() > maxheight ? $(this).height() : maxheight);
    });
    $('.homeDemoBox > .title').height(maxheight);

    maxheight = 0;
    $('.homeDemoBox').each(function () {
        maxheight = ($(this).height() > maxheight ? $(this).height() : maxheight);
    });
    $('.homeDemoBox').height(maxheight);

    maxheight = 0;
    $('.serviceBox > .title').each(function () {
        maxheight = ($(this).height() > maxheight ? $(this).height() : maxheight);
    });
    $('.serviceBox > .title').height(maxheight);

    maxheight = 0;
    $('.serviceBox').each(function () {
        maxheight = ($(this).height() > maxheight ? $(this).height() : maxheight);
    });
    $('.serviceBox').height(maxheight);

    maxheight = 0;
    $(".ourLatestBox > h3").each(function(){
        maxheight = ($(this).height() > maxheight ? $(this).height() : maxheight);
    });
    $(".ourLatestBox > h3").height(maxheight);

    // $(".ourLatestBox").each(function(){
    //     //padding the image if needed
    //     margdif = 205 - $(this).find("img").height();
    //     if(margdif > 0) $(this).find("img").css("margin-bottom", margdif+"px");
    // });

    maxheight = 0;
    $('.homePriceContent').each(function () {
        maxheight = ($(this).height() > maxheight ? $(this).height() : maxheight);
    });
    $('.homePriceContent').height(maxheight);


    $('#bookNowButton').width($('.home-pricing-box').eq(1).width());


}
$(window).load(onResize);
$(window).resize(onResize);


