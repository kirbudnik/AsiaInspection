jQuery(document).ready(function($) {

    // show older news posts
    $('.showOld').click(function() {
        showOld();
        window.location.hash = 'showOld';
    });

    // filter posts by type
    $('.newsFilterSelect').change(function (event) {
        var $showType = $(this).val();
        //synchronize other filter control
        $('.newsFilterSelect').val($showType);
        $('.news-post').removeClass('filtered-post');
        $('.news-post').not('.'+$showType).addClass('filtered-post');
        if ($('.old-post').not('.filtered-post').length > 0)
            $('.showOld').show();
        else
            $('.showOld').hide();
    });

    // expand reg-update sections
    $(".learnmore").click(function(){
        $(this).next().next().animate({
            height: 'toggle'
        }, 500);
    });

    // unfloat images and iframes in articles
    $("iframe").closest("div").css("float", "none");
    $("img").closest("div").css("float", "none");


    // want to remember showOld state on browser back but not
    // on page reload.  Unfortunately can't modify hash
    // in onbeforeunload.
    // window.onbeforeunload = function(e) {
    //     window.location.hash = '';
    // };

    $('#news-filter-1').change();

    // show older news posts
    if (window.location.hash == '#showOld') {
        showOld();
    }

});

function showOld () {
    $('.old-post').removeClass('old-post');
    $('.showOld').hide();
}