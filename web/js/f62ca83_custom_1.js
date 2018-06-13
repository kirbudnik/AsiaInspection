var mq500 = window.matchMedia('all and (max-width: 500px)');
var mq768 = window.matchMedia('all and (max-width: 768px)');

$("a","#yourAccount").click(function() {
    setCookie("isClient", 1, 365);
});

// Adjust nav seperator height so they are all the same
if(!mq768.matches){
  $(".navMenuCol","#mainNav").each(function(){
    parentHeight = $(this).parents(".dropdown-menu").height() + 5;
    if(parentHeight > 300) parentHeight = 300;
    $(this).css({
      "min-height": parentHeight+"px",
      "padding-bottom": "10px"
    });
  });
}

var main = function() {
    $('.tab').click(function() {
        $('.tab').removeClass('active');
        $(this).addClass('active');
    });

    //choose different price box to show, in pricing page under about us
    $('.asiaButton').click(function() {
        $('.asiaButton').removeClass('deactive');
        $('.europeButton').addClass('deactive');
        $('.europePricing').removeClass('active-infoBlock');
        $('.europePricing').addClass('infoBlock');
        $('.asiaPricing').removeClass('infoBlock');
        $('.asiaPricing').addClass('active-infoBlock');

    });
    // Sample reports page radio buttons change function
    $('.typeSelectBtn').click(function(){
       var type= $(this).val();
       if(type=="service"){
        $('#industryDropdownMain').hide();
        $("#serviceDropdownMain").show();
        $("#industryDropDown").hide();
        $("#serviceDropDown").hide();
        $('.dropdown-menu.item-menu li.service1').show();
        $('div.displaySampleReport').parent().hide();
       }
       else{
        $("#industryDropdownMain").show();
        $("#serviceDropdownMain").hide();
        $("#industryDropDown").hide();
        $("#serviceDropDown").hide();
        $('.dropdown-menu.item-menu li.industry1').show();
        $('div.displaySampleReport').parent().hide();
       }
    });
    $("#userEmail").keypress(function(event) {
    if (event.which == 13) {
        event.preventDefault();
        report = document.getElementById("id").value;
        email = document.getElementById("userEmail").value;
        setCookie("email", email, 365);
        $("popupEmailForm").submit();
        SampleReport(report, "");
    }
  });

    // Make sure the nav spacer doesn't stick out below the height the nav actually comes out to
    $("#fixedNavSpacer").css("max-height",$(".navbar-ai").height()+"px");

    $('.europeButton').click(function() {
        $('.europeButton').removeClass('deactive');
        $('.asiaButton').addClass('deactive');
        $('.asiaPricing').removeClass('active-infoBlock');
        $('.asiaPricing').addClass('infoBlock');
        $('.europePricing').removeClass('infoBlock');
        $('.europePricing').addClass('active-infoBlock');

    });
    $('#viewJobSmallScreen').click(function(){
         var div = document.getElementById('jobappDescription');
         if (div.style.display !== 'none') {
                div.style.display = 'none';
            }
        else {
                div.style.display = 'block';
            }
    });
    $('#viewTermsAndConditionsSmallScreen').click(function(){
         var div = document.getElementById('termsAndConditions');
         if (div.style.display !== 'none') {
                div.style.display = 'none';
            }
        else {
                div.style.display = 'block';
            }
    });

    $('#reglookingforBtn').click(function(){ reglookingfor(); });
    $('#popupSubmit').click(function(){
        report = document.getElementById("id").value;
        email = document.getElementById("userEmail").value;
        setCookie("email", email, 365);
        SampleReport(report, "");
    });
    $('#consultFormBtn').click(function(){ submitConsultForm("big"); });
    $('#consultFormBtnSm').click(function(){ submitConsultForm("small"); });
    // $('#validateAffiliateAppFormBtn').click(function(){ validateAffiliateAppForm(); }); // don't think this button exists

   $("#ai_responsivebundle_register_country").focus(function(){
      $("#ai_responsivebundle_register_country").removeClass('invalidFeild');
   });
   $("#ai_responsivebundle_register_firstName").focus(function(){
      $("#ai_responsivebundle_register_firstName").removeClass('invalidFeild');
   });
   $("#ai_responsivebundle_register_lastName").focus(function(){
      $("#ai_responsivebundle_register_lastName").removeClass('invalidFeild');
   });
   $("#ai_responsivebundle_register_company").focus(function(){
      $("#ai_responsivebundle_register_company").removeClass('invalidFeild');
   });
   $("#ai_responsivebundle_register_industry").focus(function(){
      $("#ai_responsivebundle_register_industry").removeClass('invalidFeild');
   });
   $("#ai_responsivebundle_register_telephone").focus(function(){
      $("#ai_responsivebundle_register_telephone").removeClass('invalidFeild');
   });
   $("#ai_responsivebundle_register_email").focus(function(){
      $("#ai_responsivebundle_register_email").removeClass('invalidFeild');
   });
   $("#ai_responsivebundle_register_password").focus(function(){
      $("#ai_responsivebundle_register_password").removeClass('invalidFeild');
   });
   $("#username").focus(function(){
      $("#username").removeClass('invalidFeild');
   });


    /******************************************************************************************************************/
    //expanding the Paypal and Wire Transfer links on the affiliate page
    $(".affShowMore").click(function(){
        if($(".plusminus",this).html() == "[+]"){
            $(".plusminus",this).html("[<font style='font-size:18px;line-height:13px;'>-</font>]");
        }else{
            $(".plusminus",this).html("[+]");
        }
        affShowMoreBox = $(this).attr("box");
        $("#"+affShowMoreBox).toggle();
    });
    //When click on an object with the hoverText class, find the contents and show the popup
    $(".hoverText").click(function(){
        hovCargo = "#" + $(this).attr("cargo");
        if($(hovCargo).hasClass('notshow')){
            $(hovCargo).removeClass('notshow');
            if($(window).width()>900){
                    hovPosLeft = (parseInt($(this).position().left,10) + parseInt($(this).css("width"),10) + 20) +"px";
                    hovPosTop = (parseInt($(this).position().top,10) + parseInt($(this).css("height"),10) + 10) + "px";
                }
            else{
                    hovPosLeft = (parseInt($(this).position().left,10) + parseInt($(this).css("width"),10) - 100) +"px";
                    hovPosTop = (parseInt($(this).position().top,10) + parseInt($(this).css("height"),10) + 10) + "px";
            }
            $("<div id='HoverText'></div>").css({
            'left': hovPosLeft,
            'top': hovPosTop,
            }).html($(hovCargo).html()).insertAfter(this);
        }
        else{
             $("#HoverText").remove();
             $(hovCargo).addClass('notshow');
         }
    });
 $(".container").mouseup(function(e)
    {
        var subject = $("#HoverText");

        if(e.target.id != subject.attr('id'))
        {
            $("#HoverText").remove();
            $('#txtAuditor').addClass('notshow');
            $('#txtSameDayAudit').addClass('notshow');
            $('#txtFastReport').addClass('notshow');
         }
    });


    $("#toTop").click(function(){
          $('html, body').animate({ scrollTop: 0 }, 'slow');
    });

    $("#sample-reports").change(function(){ SampleReport( $(this).val(), $(this).attr("title")); });
    //When clicking on "View Sample Report" in the service Pages
    $(".serviceReport").click(function(){
        id = $(this).attr("report");
        title = $(this).attr("title");
        type = $(this).attr("type");
        if(type == undefined || type == null) type = "default";
        tb_show(title,"/marketoEmailPopup/"+type+"/"+id+"?height=150&width=445&TB_iframe=true");
     });


    $('.loadMore').click(function() {
        $('.more').addClass('active-infoBlock');
        $('.loadMore').addClass('infoBlock');
    });
    $('.loadMoreInterviews').click(function() {
        $('.moreNews').addClass('active-infoBlock');
        $('.loadMoreInterviews').addClass('infoBlock');
    });

    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {

        var input = $(this).closest('.input-group').find(':text:first'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;

        if( input.length ) {
            input.val(log);
        } else {
            if( log ) alert(log);
        }

    });

    $('.requestCall').click(function() {
        $('.sendMessageClass').removeClass('active-infoBlock');
        $('.sendMessageClass').addClass('infoBlock');
        $('.requestCallClass').removeClass('infoBlock');
        $('.requestCallClass').addClass('active-infoBlock');

    });
    $('.sendMessage').click(function() {

        $('.requestCallClass').removeClass('active-infoBlock');
        $('.requestCallClass').addClass('infoBlock');
        $('.sendMessageClass').removeClass('infoBlock');
        $('.sendMessageClass').addClass('active-infoBlock');

    });


    // Html5 form validation for browsers which support it but don't run it automatically (eg. Safari?)
    // Adapted from http://blueashes.com/2013/web-development/html5-form-validation-fallback/
    // Forms which don't want html5 validation should remove their 'validate-form' class and add 'novalidate' attribute
    if (hasHtml5Validation()) {
          $('.validate-form').submit(function (e) {
              if (!this.checkValidity()) {
                  e.preventDefault();
                  $(this).addClass('invalid');
              } else {
                  $(this).removeClass('invalid');
              }
          });
    }


    // affiliate registration validation and spam check
    $('#affiliateCompleteForm').submit(function (event) {
        event.preventDefault();  // can't do this conditionally because of asynchronous call below
        $(".invalidFeild").removeClass('invalidFeild');
        $(this).removeClass('invalid');
        $("#errorMessage").hide();
        $('#errorMessage').html("");
        var rawFormElement = this;
/*
        if (hasHtml5Validation() && !this.checkValidity()) {
            $(this).addClass('invalid');
            $("#errorMessage").append("Please ensure any marked fields are filled in correctly and try again.<br />"); // TODO: localize
            $("#errorMessage").append("If you need help contact us at <a href='mailto:customerservice@asiainspection.com?subject=Affiliate%20Registration%20Assistance'>customerservice@asiainspection.com</a>.");
            $("#errorMessage").show();
        } else
*/
        if (validateAffiliateAppForm()) {
            var url = "/spamCheckAffiliateRegForm";
            var posting = $.post(url, {
                affEmail: $("[name='affEmail']").val(),
                affCompany: $("[name='affCompany']").val()
            });
            posting.done(function (data) {
                var spamResponse = $.parseJSON(data);
                var spamPassed = true;
                if ('affEmail' in spamResponse) {
                    spamPassed =false;
                    // mark email field
                    $("#errorMessage").append(spamResponse.affEmail +"<br />");
                    invalidateField($("[name='affEmail']"));
                }
                if ('affCompany' in spamResponse) {
                    spamPassed = false;
                    // mark company field
                    $("#errorMessage").append(spamResponse.affCompany +"<br />");
                    invalidateField($("[name='affCompany']"));
                }
                if (spamPassed) {
                    // trigger default form submission
                    // note: this does not re-trigger non-default submit handlers
                    // alert('submit');
                    rawFormElement.submit();
                } else {
                    $(this).addClass('invalid');
                    $("#errorMessage").append("Please ensure any marked fields are filled in correctly and try again.<br />"); // TODO: localize
                    $("#errorMessage").append("If you need help contact us at <a href='mailto:customerservice@asiainspection.com?subject=Affiliate%20Registration%20Assistance'>customerservice@asiainspection.com</a>.");
                    $("#errorMessage").show();
                }
            });
        } else {
            $(this).addClass('invalid');
            $("#errorMessage").append("Please ensure any marked fields are filled in correctly and try again.<br />"); // TODO: localize
            $("#errorMessage").append("If you need help contact us at <a href='mailto:customerservice@asiainspection.com?subject=Affiliate%20Registration%20Assistance'>customerservice@asiainspection.com</a>.");
            $("#errorMessage").show();
        }
    });


    HomeSlider = window.setInterval(autoSlide, 5000);
    function autoSlide() {
        var currentSlide = $('.active-slide');
        var nextSlide = currentSlide.next();

        if (nextSlide.length == 0) {
            nextSlide = $('.slide:first');
        }

        currentSlide.fadeOut(500).removeClass('active-slide');
        nextSlide.fadeIn(500).addClass('active-slide');
    }

    /*Clicking on the nav menu for the sliders*/
    $("li",".slider-dots").click(function(){
        clearInterval(HomeSlider);
        $(".slide").stop();
        $(this).parents(".slide").fadeOut(500).removeClass('active-slide');
        nextSlide = "." + $(this).attr("tag");
        $(nextSlide).fadeIn(500).addClass('active-slide');
        HomeSlider = window.setInterval(autoSlide, 5000);
    });

    /* Main Nav open sub nav on hover on large devices*/
    var mq = window.matchMedia( "(min-width: 769px)" );
    if (mq.matches) {
       $("#mainNav li.dropdown").hover(
           function () { // function to execute when the mouse pointer enters the element
                //hides all other menus to prevent flickering
                $('ul').filter(function () {
                    return this.className.match(/navmenu/);
                }).hide()
                //end hide
                $(this).children("ul.dropdown-menu").toggle();
            }, function() { // function to execute when the mouse pointer leaves the element
                //hides all because the mouse is leaving
                $('ul').filter(function () {
                    return this.className.match(/navmenu/);
                }).hide()
            });
        $("#navServiceDropToggle").attr("href","/quality-control-services").attr("data-toggle","");
        $("#navIndustryDropToggle").attr("href","/quality-control-services-by-industry").attr("data-toggle","");
        $("#navAboutDropToggle").attr("href","/who-we-are").attr("data-toggle","");
    }

//expanding the "Learn More" link on the service page (in the "Benefits, Quality Standards, FAQs, Accreditation" box)
$("#learn_more").click(function(){
  $('#learn_more_content').animate({
    height: 'toggle'
  }, 500);
});
/**nav select phone box clicked**/
$('.selectOffice').click(function(){
    $('#selectPhone').text($(this).attr('phone'));
});

// Making sure the 3rd box on the pricing page stays the correct height
if(typeof(pricingBoxMaxMeight) == "undefined") pricingBoxMaxMeight = 0;
if($("#thirdPricingBox").height() != pricingBoxMaxMeight) {
    pricingBoxMaxMeight = 0;
    $('.pricingContent').each(function () {
        pricingBoxMaxMeight = ($(this).height() > pricingBoxMaxMeight ? $(this).height() : pricingBoxMaxMeight);
    });
    pricingBoxHeightDif = pricingBoxMaxMeight - $("#thirdPricingBox").height();
    $('.thirdPricingBoxTopSpacer').height(pricingBoxHeightDif);
    $('.thirdPricingBoxBottomSpacer').height(pricingBoxHeightDif/2);
}

/******************************************************************************************************************/
//Saving the HTTP Referrer cookie
//if(document.getElementById("http_referer").value != "") { setCookie("http_referer", document.getElementById("http_referer").value, 365); }
/******************************************************************************************************************/
//Saving the Affiliate ID Cookie
c_name="affid";
var pos = document.location.href.toLowerCase().indexOf(c_name +"=");
var affid = pos!=-1?document.location.href.substr(pos+6,4):"";
if(affid != "") setCookie(c_name, affid, 365);
/******************************************************************************************************************/
//expanding the "See what you can't ship" link in the "how to send samples" box
$("#sidebox_cantShip").click(function(){
  $('#sidebox_cantShip_content').animate({
    height: 'toggle'
  }, 500);
});
$("#box_subscr_news_submit").click(submitNewsletterBox);
/**Reference samples select box**/
    $("#refCountryDrop").change(function(){
            $("#dropdownref_asia").hide();
            $("#dropdownref_africa").hide();
            $("#dropdownref_europe").hide();
            $("#dropdownref_latinamerica").hide();
            dropdownname = "#dropdownref_"+$(this).val();
            $(dropdownname).show();
            $(".dropdownref:visible").change();
        });
/**selctting china by default (reference samples page)**/
    $('#dropdownref_asia').val("China").change();
//Choosing a country on the reference samples page
    $(".dropdownref").change(function(){
            $("#refLocationBoxTitle").html("Serving "+$('option:selected',this).text());
            $(".RSTcontent").hide();
            $(".RST_Chinanote").hide();
            $(".RST_"+$(this).val()).show();
            $("tr","#ReferenceSamplesTable").css("background-color","");
});
    $("#jobdrop_Department").change(function(){
          SearchJobs();
                });
    $("#jobdrop_Countries").change(function(){
          SearchJobs();
                });
     $("#jobdrop_Type").change(function(){
          SearchJobs();
                });
     /**Job App Submit Button Submit**/
    $('#jobAppSubmit').click(validateJobApp);

    //selecting different tabs when clicking on them on the service page
    $(".serviceTab").click(function(){
        $(".serviceTab").removeClass("selectedServiceTab").addClass("gradient-grey4");
        $(this).removeClass("gradient-grey4").addClass("selectedServiceTab");
        target = $(this).attr("tab");
        $(".tabContent").css("display","none");
        $("#"+target).css("display","block");
        var width=$(window).width();
        if( width<500){
            var tab = $(this).attr( "tab" );
            if(tab=='tabContent_inspect'  ){
                   // $('#toTop').css("display","inline-block");
                    $('#toTop').text('Choose a different location');
            }
            else
            {
                $('#toTop').text('To top');
               // $('#toTop').css("display","none");
            }
        }
    });


    $(".ContactNumberSelect1").change(function(){
        $(".ContactNumberBox1").show();
    country = $("option:selected", this).val();
        phonenum = $("option:selected", this).attr("phone");
        tollfreenum = $("option:selected", this).attr("tollfree");
    if(country=="China (English)"){
      $(".numers_China_contact").show();
    }else{
      $(".numers_China_contact").hide();
    }
        $(".ui-link1",".ContactNumberPhone1").html(phonenum).attr("href","tel:"+phonenum);
        if(tollfreenum != undefined){
            $(".ui-link1",".ContactNumberTollfree1").html(tollfreenum).attr("href","tel:"+tollfreenum);
            $(".ContactNumberTollfree1").show();
        }else{
            $(".ui-link1",".ContactNumberTollfree1").html("").attr("href","");
            $(".ContactNumberTollfree1").hide();
        }
    });

    $(".ContactNumberSelect2").change(function(){
        country = $("option:selected", this).val();
        if(country=="China (English)"){
            $(".numers_China_contact").show();
        }else{
            $(".numers_China_contact").hide();
        }
        $(".ContactNumberBox2").show();
        phonenum = $("option:selected", this).attr("phone");
        tollfreenum = $("option:selected", this).attr("tollfree");
        $(".ui-link2",".ContactNumberPhone2").html(phonenum).attr("href","tel:"+phonenum);
        if(tollfreenum != undefined){
        $(".ui-link2",".ContactNumberTollfree2").html(tollfreenum).attr("href","tel:"+tollfreenum);
            $(".ContactNumberTollfree2").show();
        }else{
            $(".ui-link2",".ContactNumberTollfree2").html("").attr("href","");
            $(".ContactNumberTollfree2").hide();
        }
    });

    // Shows the select item in dropdown box.
    $(".dropdown-menu li").click(function(){
        $(this).parents(".dropdown").find('.optionSel').hide();
        $(this).parents(".dropdown").find('.optionSelected').text($(this).text());
    });

    // Updates Sample Reports Item Menu when Industry is selected.
    $(".dropdown-menu.industry-menu li").click(function(){
        var catid = $(this).attr('class');
        $('.dropdown-menu.item-menu').parents(".dropdown").find('.optionSel').show();
        $('.dropdown-menu.item-menu').parents(".dropdown").find('.optionSelected').text('');
        $('.dropdown-menu.item-menu li').hide();
        $("#industryDropDown").show();
        $('.dropdown-menu.item-menu li.' + catid).show();
        $('div.displaySampleReport').parent().hide();
    });
    // service menu click function
    $(".dropdown-menu.service-menu li").click(function(){
      var catid = $(this).attr('class');
      $('.dropdown-menu.item-menu').parents(".dropdown").find('.optionSel').show();
      $('.dropdown-menu.item-menu').parents(".dropdown").find('.optionSelected').text('');
      $('.dropdown-menu.item-menu li').hide();
      $("#serviceDropDown").show();
      $('.dropdown-menu.item-menu li.' + catid).show();
      $('div.displaySampleReport').parent().hide();
  });

    // Item has been selected, we display the selected PDF
    $(".dropdown-menu.item-menu li").click(function(){
        $('div.displaySampleReport').parent().show();
        var wrap = $(this).parents('.item-menu');
        var imgurl = $(this).attr('data-image');
        var dlurl = $(this).attr('data-download');
        var dlcat = $(this).attr('data-category');

        // Jump to Report
        jumpPos = $("#displaySampleReportWrapper").position().top - 90;
        $(window).scrollTop(jumpPos);

        formId = 1078;
        if(dlcat == "audit") formId = 1078;
        if(dlcat == "inspection") formId = 1080;
        if(dlcat == "labtest") formId = 1083;
        $("form","#MarketoForm").remove();
        $("#MarketoForm").append("<center><form id='mktoForm_"+formId+"'></form></center>");

        MktoForms2.loadForm("//app-ab16.marketo.com", "944-QDO-384", formId);

        $(document).arrive(".mktoButton", function() {
          $("[name=SampleAuditReport]").val(dlurl);
          $("[name=SampleInspectionReport]").val(dlurl);
          $("[name=SampleTestingReport]").val(dlurl);
        });

        $('.imgurl').text(imgurl);
        $('.dlurl').text(dlurl);
        $('.displaySampleReport img').attr('src',imgurl);
        $('.displaySampleReport img').css("opacity", "0.5");
        $('#dlSampleReport').attr('onclick','SampleReport("' + dlurl + '","")');
    });
    
    $('#ai_responsivebundle_register_telephone').focusout(phone_check);
    
    $('#username').focusout(function() { username_check(); });
    //$('#username').keyup(function() { username_check(); });
    //$('#username').change(function() { username_check(); });
    $('#username').on('paste', function () { username_check(); });
    
    $("#ai_responsivebundle_register_email").focusout(email_check);
    $("#ai_responsivebundle_register_password").focusout(password_check);
};

$(document).on('change', '.btn-file :file', function() {
  var input = $(this),
      numFiles = input.get(0).files ? input.get(0).files.length : 1,
      label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
  input.trigger('fileselect', [numFiles, label]);
});
$(document).ready(main);

function username_check() {
    var username = $('#username').val();
    $("#ai_responsivebundle_register_submit").removeAttr("disabled");

    if(username == "") {
        $('#username').css("background", "transparent");
        $('#username').css('background-color', 'white');
        registrationBoxHeightAdjust();
        return;
    }

    if(username != "") {
        $('#username').removeClass('invalidFeild');
        if( username.indexOf(' ') >=0 || username.indexOf('%20') >=0 || username.indexOf('&nbsp;') >=0 ) {
            $('#username').addClass('invalidFeild');
            $('#username').css('bakground-color', 'white');
            $('#usernameInvalidFormat').show();
            $('#username').css("background", "transparent");
            $('#username').css('background-color', 'white');
        } else {
            $('#usernameInvalidFormat').hide();
            $('#username').css("background", "white url(https://s3.asiainspection.com/images/responsive/checking.gif) no-repeat right center");
            $.ajax({
                type: "POST",
                url: "checkUsername",
                async: "false",
                data: 'username='+ username,
                dataType: "text",
                success: function(response) {
                    if(response == 1) {
                        $('#username').addClass('invalidFeild');
                        $('#usernameInUse').show();
                        $('#username').css("background", "transparent");
                        $('#username').css('background-color', 'white');
                        $("#ai_responsivebundle_register_submit").attr("disabled","disabled");
                    } else {
                        $('#username').removeClass('invalidFeild');
                        $('#usernameInUse').hide();
                        $('#username').css("background", "white url(https://s3.asiainspection.com/images/tic.gif) no-repeat right center");
                   }
                   registrationBoxHeightAdjust();
                }
            });
        }
    }
} // End of username_check

function phone_check(){
   phone = $("#ai_responsivebundle_register_telephone").val();
   errors = 0;
    var stripped = phone.replace(/\+/g,'');
        stripped = stripped.replace(/\(/g,'');
        stripped = stripped.replace(/\)/g,'');
        stripped = stripped.replace(/\ /g,'');
        stripped = stripped.replace(/\s/g,'')
        stripped = stripped.trim();
        // Check length is greater than 5
        phoneLength = stripped.length;
    if(isNaN(stripped) || phoneLength < 5) {
        $("#ai_responsivebundle_register_telephone").addClass('invalidFeild');
        $('#validPhone').show();
        registrationBoxHeightAdjust();
        return false;
    } else {
        $('#validPhone').hide();
        $('#invalidPhoneNumber').hide();
        $.ajax({
            type: "POST",
            url: "twilioVerify",
            async: "false",
            data: 'number='+ stripped,
            dataType: "text",
            success: function(response) {
                if(response != "false") {
                    $("#ai_responsivebundle_register_telephone").val(response);
                    $('#invalidPhoneNumber').hide();
                } else {
                    $('#invalidPhoneNumber').show();
                }
                registrationBoxHeightAdjust();
            }
        });
        registrationBoxHeightAdjust();
        return true;
    }
}

function password_check() {
   password = $("#ai_responsivebundle_register_password").val();
   if(password.length <6) {
        $("#ai_responsivebundle_register_password").addClass('invalidFeild');
        $('#passwordLength').show();
    } else {
        $('#ai_responsivebundle_register_password').removeClass('invalidFeild');
        $('#passwordLength').hide();
    }
    registrationBoxHeightAdjust();
}

//checks if email user ID is a role based email ID ie "marketing@t.com"
var emailRoleNames = ["abuse", "admin", "contact", "hostmaster", "info", "investorrelations", "jobs", "marketing", "media", "postmaster", "privacy", "root", "sales", "spam", "webmaster"]

$("#ai_responsivebundle_register_email").blur(function(){
  var roleEmail = $('#ai_responsivebundle_register_email').val().toLowerCase();
  var emailUserId = '';
  for (var i = 0; i < roleEmail.length; i++){
    if (roleEmail[i] === "@"){
      emailUserId = roleEmail.substr(0, i);
    }
  }
  for (var j = 0; j < emailRoleNames.length; j++){
    if (emailUserId === emailRoleNames[j]){
      $('#ai_responsivebundle_register_email').addClass('invalidFeild');
      $('#roleEmail').show().html("We reccommend using a personal or work email, instead of " + emailUserId + "@");
    }
  }
  registrationBoxHeightAdjust();
});

$("#ai_responsivebundle_register_email").focus(function(){
  $('#roleEmail').hide();
  registrationBoxHeightAdjust();
});

function email_check() {
    var email = $('#ai_responsivebundle_register_email').val();
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    if(email!=""){
        if(email.indexOf(' ')>=0||(!pattern.test(email))){
            $('#ai_responsivebundle_register_email').addClass('invalidFeild');
            $('#validEmail').show();
            $('#emailInUse').hide();
        }else{
            if($('#validEmail').is(':visible')){
                $('#validEmail').hide();
                if(!$('#emailInUse').is(':visible')){
                    $('#ai_responsivebundle_register_email').removeClass('invalidFeild');
                    $('#emailInUse').hide();
                }
            }

            $.ajax({
                type: "POST",
                url: "checkEmail",
                async: "false",
                data: 'email='+ email,
                dataType: "text",
                success: function(response){
                    if(response == 1){
                        $('#ai_responsivebundle_register_email').addClass('invalidFeild');
                        $('#emailInUse').show();
                        $('#roleEmail').hide();
                    } else {
                        if($('#emailInUse').is(':visible') ){
                            $('#emailInUse').hide();
                            if(!$('#validEmail').is(':visible')){
                                $('#emailInUse').hide();
                                $('#ai_responsivebundle_register_email').removeClass('invalidFeild');
                            }
                        }

                    }
                    registrationBoxHeightAdjust();
                }
            });
        }
    }
    registrationBoxHeightAdjust();
}

function validateRegistrationForm() {
    firstName = $("#ai_responsivebundle_register_firstName").val();
    lastName = $("#ai_responsivebundle_register_lastName").val();
    country = $("#ai_responsivebundle_register_country").val();
    industry = $("#ai_responsivebundle_register_industry").val();
    company = $("#ai_responsivebundle_register_company").val();
    phone = $("#ai_responsivebundle_register_telephone").val();
    email = $("#ai_responsivebundle_register_email").val();
    username = $("#username").val();
    password = $("#ai_responsivebundle_register_password").val();
    
    if( firstName == "" || lastName == "" || country == "empty_value" || country == "" || company == "" || phone == "" || email == "" || username == "" || password == "" || password.length < 6 || industry == "" ) {
        if( country == "empty_value" || country == "" ) $("#ai_responsivebundle_register_country").addClass('invalidFeild');
        if( industry == "empty_value" || industry == "" ) $("#ai_responsivebundle_register_industry").addClass('invalidFeild');
        if( firstName == "" ) $("#ai_responsivebundle_register_firstName").addClass('invalidFeild');
        if( lastName == "" ) $("#ai_responsivebundle_register_lastName").addClass('invalidFeild');
        if( company == "" ) $("#ai_responsivebundle_register_company").addClass('invalidFeild');
        if( phone == "" || phone.length < 5) $("#ai_responsivebundle_register_telephone").addClass('invalidFeild');
        if( email == "" ) $("#ai_responsivebundle_register_email").addClass('invalidFeild');
        if( username == "" ) $("#username").addClass('invalidFeild');
        if( password == "" || password.length < 6 ){
            $("#ai_responsivebundle_register_password").addClass('invalidFeild');
            $("#passwordLength").show();
        }

        if( $("#ai_responsivebundle_register_firstName").hasClass("invalidFeild") || $("#ai_responsivebundle_register_lastName").hasClass("invalidFeild") || $("#ai_responsivebundle_register_industry").hasClass("invalidFeild") || $("#ai_responsivebundle_register_company").hasClass("invalidFeild") || $("#ai_responsivebundle_register_email").hasClass("invalidFeild") ) $("#registerFormJumpBack").click();
        registrationBoxHeightAdjust();
        return false;
    }

    phoneCheck = phone_check();
    if( !phoneCheck )  return false;

    $("#regLoadingGif").show();
    return true;
}

// to keep the boxes the same height in the 2-step registration page
function registrationBoxHeightAdjust() {
    box2vis = $("#registrationStepTwoBox:visible").css("opacity");
    if( box2vis > 0 ) {
        $("#registrationStepOneBox").animate({
            height: $("#registrationStepTwoBox").height()
        }, 300);
    }
}


function validateJobApp(){
    var lang = $("#language").val();
    contactusFormErrors = 0;

    job_GenderTitle = $("[name='jobGenderTitle']").val();
    job_FirstName = $("[name='jobFirstName']").val();
    job_LastName = $("[name='jobLastName']").val();
    job_Address = $("[name='jobAddress']").val();
    job_City = $("[name='jobCity']").val();
    job_Country = $("[name='jobCountry']").val();
    job_Phone = $("[name='jobPhone']").val();
    job_Mobile = $("[name='jobMobile']").val();
    job_Email = $("[name='jobEmail']").val();
    job_ConfirmEmail = $("[name='jobConfirmEmail']").val();
    job_SupportComments = $("[name='jobSupportComments']").val();
    job_Resume = $("[name=jobUploadCV]").val();
    job_Spamcheck = $("[name=spamcheck]").val();
    job_WhereHear = $("[name='jobWhereHear']").val();
    job_Specify = $("[name='jobSpecifics']").val();

    //Check First Name
    if(job_FirstName == ""){
        contactusFormErrors = contactusFormErrors + 1;
        if(lang != "ar"){
            validateErrorVer2("jobFirstName");
        }else{
            validateError("jobFirstName");
        }
    }

    //Check Last Name
    if(job_LastName == ""){
        contactusFormErrors = contactusFormErrors + 1;
        if(lang != "ar"){
            validateErrorVer2("jobLastName");
        }else{
            validateError("jobLastName");
        }
    }

    //Check Address
    if(job_Address == ""){
        contactusFormErrors = contactusFormErrors + 1;
        if(lang != "ar"){
            validateErrorVer2("jobAddress");
        }else{
            validateError("jobAddress");
        }
    }

    //Check City
    if(job_City == ""){
        contactusFormErrors = contactusFormErrors + 1;
        if(lang != "ar"){
            validateErrorVer2("jobCity");
        }else{
            validateError("jobCity");
        }
    }

    //Check Country
    if(job_Country == ""){
        contactusFormErrors = contactusFormErrors + 1;
        if(lang != "ar"){
            validateErrorVer2("jobCountry");
        }else{
            validateError("jobCountry");
        }
    }

    //Check Phone
    if(job_Phone == ""){
        contactusFormErrors = contactusFormErrors + 1;
        if(lang != "ar"){
            validateErrorVer2("jobPhone");
        }else{
            validateError("jobPhone");
        }
    }
    obj = $("[name='jobPhone']").val();
    for (i = 0; i < obj.length; i ++){
        var code =  obj.charAt(i);
        if (isNaN(code)&&code!="-"&&code!="—"&&code!="+"&&code!="+"&&code!="("&&code!=")"){
            if(lang != "ar"){
                validateErrorVer2("jobPhone");
            }else{
                validateError("jobPhone");
            }
            contactusFormErrors = contactusFormErrors + 1;
        }
    }

    //Check Mobile
    if(job_Mobile == ""){
        contactusFormErrors = contactusFormErrors + 1;
        if(lang != "ar"){
            validateErrorVer2("jobMobile");
        }else{
            validateError("jobMobile");
        }
    }
    obj = $("[name='jobMobile']").val();
    for (i = 0; i < obj.length; i ++){
        var code =  obj.charAt(i);
        if (isNaN(code)&&code!="-"&&code!="—"&&code!="+"&&code!="+"&&code!="("&&code!=")"){
            if(lang != "ar"){
                validateErrorVer2("jobMobile");
            }else{
                validateError("jobMobile");
            }
            contactusFormErrors = contactusFormErrors + 1;
        }
    }

    //Check Email
    obj = $("[name='jobEmail']").val();
    if(job_Email == ""){
        contactusFormErrors = contactusFormErrors + 1;
        if(lang != "ar"){
            validateErrorVer2("jobEmail");
        }else{
            validateError("jobEmail");
        }
    }else if (obj.charAt(0)=="." ||
        obj.charAt(0)=="@"||
        obj.indexOf('@', 0) == -1 ||
        obj.indexOf('.', 0) == -1 ||
        obj.indexOf(' ') != -1 ||
        obj.lastIndexOf("@")==obj.length-1 ||
        obj.lastIndexOf(".")==obj.length-1) {
            if(lang != "ar"){
                validateErrorVer2("jobEmail");
            }else{
                validateError("jobEmail");
            }
            contactusFormErrors = contactusFormErrors + 1;
    }

    //Check Confirm Email
    if(job_ConfirmEmail == ""){
        contactusFormErrors = contactusFormErrors + 1;
        if(lang != "ar"){
            validateErrorVer2("jobConfirmEmail");
        }else{
            validateError("jobConfirmEmail");
        }
    }
    if(job_Email != job_ConfirmEmail){
        contactusFormErrors = contactusFormErrors + 1;
        if(lang != "ar"){
            validateErrorVer2("jobConfirmEmail");
        }else{
            validateError("jobConfirmEmail");
        }
    }
    //Check If social has been input
    if(job_WhereHear == null){
        contactusFormErrors = contactusFormErrors + 1;
        box = $("#jobWhereHear");
        $(box).addClass('invalidFeild');
        $(box).focus(function(){
            $(box).removeClass('invalidFeild');
        });
    }

    if(  job_Specify=="" && (job_WhereHear=="referral"||job_WhereHear=="other")){
        contactusFormErrors = contactusFormErrors + 1;
        if(lang != "ar"){
            validateErrorVer2("jobSpecifics");
        }else{
            validateError("jobSpecifics");
        }
    }

    //Check If a CV has been input
    if(job_Resume == ""){
        contactusFormErrors = contactusFormErrors + 1;
        if(lang != "ar"){
            validateErrorVer2("jobUploadCV");
        }else{
            validateError("jobUploadCV");
        }
    }

    if(contactusFormErrors == 0 && job_Spamcheck == ""){
        $("#jobFormLoadingWait").css("display","inline-block");
        //Send the data to Lotus
        document.getElementById("jobAppCompleteForm").submit();
    }else{
        $("#jobFormLoadingWait").css("display","none");
    }
}



/******************************************************************************************************************/
//add a validation error box to a field
function invalidateField (field) {

    // not sure what is special about Arabic but it apparently at one time required special treatment
    if ($("#language").val() == "ar")
        return validateError(field.attr("name"));
    else {
        // var field = $("[name='" + fieldName + "']");

        if ($(window).width() < 770) {
            // Included to maintain expected behaviour of validateErrorVer2(), which this function replaces.
            var fieldWrapper = field.parents(".wrap");
            if (fieldWrapper.length == 0) fieldWrapper = field.parents(".upload-input");  // can't find any usages of 'upload-input' class
            fieldWrapper.css("position", "relative");
        }

        //
        if (field.attr("name") == "jobUploadCV") {
            // Included to maintain expected behaviour of validateErrorVer2() which this function replaces.
            // Note that the field which is invalidated is in this case distinct from the one that re-validates,
            // or else we would rather do this switch before calling the current function.
            $("#cvUpload").addClass('invalidFeild');
            field.focus(function () {
                $("#cvUpload").removeClass('invalidFeild');
            });
        } else {
            field.addClass('invalidFeild').focus(function () {
                $(this).removeClass('invalidFeild');
            });
        }
    }
}


/******************************************************************************************************************/
//create a validation error box using the "name" attribute to identify the target
// apparently we only need this function to handle Arabic locale.  Not sure why.
// I can't find any calls that pass errornum or any "validateErrorText2" tag ids
function validateError(fieldName,errornum){

    var box = $("[name='"+fieldName+"']");
    if(box.next(".validateErrorBox").length == 0){ // validateErrorBox class is associated with affiliate checkboxes only, IINM
        posTop = box.position().top + box.height();
        posLeft = box.position().left;
        boxWidth = box.width();
        if (errornum == null) {  // I don't think errornum is ever passed to this function, nor is the tag id "validateErrorText2" every used.
            html = "<div class='validateErrorBox' style='position:relative;width:"+boxWidth+"px;font-size:11px;text-align:center;'><div class='spriteValidateError' style='vertical-align:middle;'></div> "+$("#validateErrorText").val()+"</div>";
        } else {
            html = "<div class='validateErrorBox' style='position:relative;width:"+boxWidth+"px;font-size:11px;text-align:center;'><div class='spriteValidateError' style='vertical-align:middle;'></div> "+$("#validateErrorText2").val()+"</div>";
        }
        $(html).insertAfter("[name='"+fieldName+"']").each(function(){
            posLeft = posLeft - parseInt($(this).position().left)+2;
            $(this).css("left",posLeft+"px");
        });

    }
}


/******************************************************************************************************************/
//create a validation error box using the "name" attribute to identify the target (Version 2, on Job App page)
// Same functionality as invalidateField() but takes fieldname instead of jQuery wrapper
function validateErrorVer2(fieldName){
    var box = $("[name='"+fieldName+"']");
     if($(window).width()<770){
         var fieldWrapper = $("[name='"+fieldName+"']").parents(".wrap");
         if(fieldWrapper.length == 0) fieldWrapper = $("[name='"+fieldName+"']").parents(".upload-input"); // can't find any usages of 'upload-input' class
         fieldWrapper.css("position","relative");

         if(fieldName == "jobUploadCV"){

             $("#cvUpload").addClass('invalidFeild');
             box.focus(function(){
                 $('#cvUpload').removeClass('invalidFeild');
             });
         }else{
             $("[name='"+fieldName+"']").addClass('invalidFeild');
             box.focus(function(){
                 $("[name='"+fieldName+"']").removeClass('invalidFeild');
             });
         }
     }else{
         if(fieldName == "jobUploadCV"){
             $("#cvUpload").addClass('invalidFeild');
             box.focus(function(){
                 $('#cvUpload').removeClass('invalidFeild');
             });
         }
     }
}


/******************************************************************************************************************/
function submitConsultForm(size){
     $("#consultFormName").removeClass('invalidFeild');
     $('#consultFormEmail').removeClass('invalidFeild');
     $('#consultFormPhone').removeClass('invalidFeild');
     $("#consultFormNameSm").removeClass('invalidFeild');
     $('#consultFormEmailSm').removeClass('invalidFeild');
     $('#consultFormPhoneSm').removeClass('invalidFeild');

    if(size=="small"){
        name = $("#consultFormNameSm").val();
        email = $('#consultFormEmailSm').val();
        phone = $('#consultFormPhoneSm').val();
   }
   else{
       name = $("#consultFormName").val();
        email = $('#consultFormEmail').val();
        phone = $('#consultFormPhone').val();
    }
    if(name==""||email==""||phone==""){
        if(size=="big"){
            if(name=="")
                $("#consultFormName").addClass('invalidFeild');
            if(email=="")
                $('#consultFormEmail').addClass('invalidFeild');
            if(phone=="")
                $('#consultFormPhone').addClass('invalidFeild');
        }
        else{
            if(name=="")
                $("#consultFormNameSm").addClass('invalidFeild');
            if(email=="")
                $('#consultFormEmailSm').addClass('invalidFeild');
            if(phone=="")
                $('#consultFormPhoneSm').addClass('invalidFeild');
        }

    }
    else{
   $("#consultFormBtn").attr("disabled","disabled");
    $.ajax({
        type: "POST",
        url: "uploadConsultForm",
        async: "false",
        data: "name="+name+"&email="+email+"&phone="+phone,
        dataType: "text",
        success: function(msg){

                 $('#consultFormSm').hide();
               $('#submitFormSuccessSm').show();

               $('#consultForm').hide();
               $('#submitFormSuccess').show();

        }

    });
    }
    return false;
}

/******************************************************************************************************************/
function submitNewsletterBox(){
    thevalue = $("#sub_news_email").val();
    if(thevalue=="")
        $("#sub_news_email").after("<div id='showerror' style='color:black;font-weight:normal;'>Please input a valid email address.</div>");
    else{
     setCookie("email", thevalue,365);
    $.ajax({
        type: "POST",
        url: "/getEmail",
        async: "false",
        data: "email="+thevalue+"&target=Newsletter%20Signup&category=Newsletter",
        dataType: "text",
        success: function(response){
            $('#showerror').css("display","none");
            $("#sub_news_email").css("display","none").after("<div style='color:black;font-weight:normal;'>You Have Been Successfully Added.</div>");
        },

    });
    }
    return false;
}


/******************************************************************************************************************/
//a function to send an sms with the links to the mobile apps (for new mobilepromo page)
function smsMobileLink(){
    var countryCode = $('.mobilePhoneCountry option:selected').data('status');
    if(countryCode == "" || countryCode == undefined ) countryCode = $('.mobilePhoneCountry option:selected').eq(1).attr("data-status");
    var phoneNumber = $(".sendLinkMobileNumber").val();
    var phone = phoneNumber.replace(/\D/g, '');  // strip all non-numeric characters
    var pattern = new RegExp(/^\d{5,}$/);  // remainder must be a string of digits of sufficient length
    if(!pattern.test(phone)){
        $('.sendLinkMobileNumber').val('');
    } else {
        sendData = "countryCode="+countryCode+"&phoneNumber="+phone;
        $.ajax({
            type: "POST",
            url: "/smsMobileLink",
            async: false,
            data: sendData,
            dataType: "text",
            success: function (msg) {
                if (msg == 'Sending') {
                    replaceHTML = '<div class="mobileSuccessMessage" style="display:inline-block; text-align:center;"><div style="margin-bottom:1em;"><div style="text-align:center; background-color:white; padding:15px 25px;"><h3 style="font-size:14px; margin-bottom:0px; color:#5aa231;">Sent! Check your mobile device now to get the app\'s download link.</h3></div></div></div>';
                } else {
                    replaceHTML = '<div class="mobileSuccessMessage" style="display:inline-block; text-align:center;"><div style="margin-bottom:1em;"><div style="text-align:center; background-color:white; padding:15px 25px;"><h3 style="font-size:14px; margin-bottom:0px; color:#a25a31;">Error: ' + msg + '</h3></div></div></div>';
                }
                $("#mainDownloadForms").append(replaceHTML);
                $("#contactDownload").append(replaceHTML);
                $("#processDownload").append(replaceHTML).find(".mobileSuccessMessage").css("margin-right","10px");
                $("#mobileDownloadForms").append(replaceHTML);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log("XHR Status: " + xhr.status);
                console.log(ajaxOptions);
                console.log(thrownError);
                console.log(xhr);
            }
        });
    }
    return false;
}

/******************************************************************************************************************/
//a function to send an email with the links to the mobile apps (for new mobilepromo page)
$(document).on('change input keydown', '.sendLinkMobileEmail', 0, function() {
    newValue = $(this).val();
    curElement = $(this);
    $(".sendLinkMobileEmail").not(curElement).val(newValue);
});

$(document).on('change input keydown', '.sendLinkMobileNumber', 0, function() {
    newValue = $(this).val();
    curElement = $(this);
    $(".sendLinkMobileNumber").not(curElement).val(newValue);
});

$(document).on('change input blur', '.mobilePhoneCountry', 0, function() {
    newValue = $(this)[0].selectedIndex;
    $('.mobilePhoneCountry').each(function(){
      $("option",this).eq(newValue).attr("selected","selected");
    });
});

function emailMobileLink(){
    $(".mktoButton").click();
}
/******************************************************************************************************************/
//validation for the Affiliate Application form and then submits if everything is ok
function validateAffiliateAppForm(){

    aff_Email = $("[name='affEmail']");
    aff_ConfirmEmail = $("[name='affConfirmEmail']");
    aff_Password = $("[name='affPassword']");
    aff_ConfirmPassword = $("[name='affConfirmPassword']");

    // these are only declared to populate the array following
    aff_Company = $("[name='affCompany']");
    aff_FirstName = $("[name='affFirstName']");
    aff_LastName = $("[name='affLastName']");
    aff_Address = $("[name='affAddress']");
    aff_City = $("[name='affCity']");
    aff_Country = $("[name='affCountry']");
    aff_BannerURL = $("[name='affBannerURL']");

    var userFields = [
        aff_Company,
        aff_FirstName,
        aff_LastName,
        aff_Address,
        aff_City,
        aff_Country,
        aff_Email,
        aff_ConfirmEmail,
        aff_Password,
        aff_ConfirmPassword,
        aff_BannerURL
    ];

    aff_PaypalEmail = $("[name='affPaypalEmail']");
    aff_Beneficiary = $("[name='affBeneficiary']");

    // these are only declared to populate the array following
    aff_BankName = $("[name='affBankName']");
    // aff_BenefAddress = $("[name='affBenefAddress']");
    // aff_BranchName = $("[name='affBranchName']");
    aff_AccountNumber = $("[name='affAccountNumber']");
    aff_BankAddress = $("[name='affBankAddress']");
    aff_SwiftCode = $("[name='affSwiftCode']");
    // aff_IBAN = $("[name='affIBAN']");
    // aff_BankSortNumber = $("[name='affBankSortNumber']");
    // aff_FedWire = $("[name='affFedWire']");

    var bankFields = [
        aff_Beneficiary,
        aff_BankName,
        // aff_BenefAddress,
        // aff_BranchName,
        aff_AccountNumber,
        aff_BankAddress,
        aff_SwiftCode,
        // aff_IBAN,
        // aff_BankSortNumber,
        // aff_FedWire
    ];

    aff_Understand = $("[name='affUnderstand']");
    aff_AcceptTerms = $("[name='affAcceptTerms']");


    // this count is not returned so we could use boolean but it is useful for debugging
    var contactusFormErrors = 0;


    // for (var field in userFields) {
    //     if (userFields.hasOwnProperty(field) && field.val() == "") {
    for(var i = 0; i < userFields.length; i++) {
        var field = userFields[i];
        if (field.val() == "") {
            contactusFormErrors++;
            invalidateField(field);
        }
    }


    var email = aff_Email.val();
    if (email.charAt(0)=="." ||
        email.charAt(0)=="@"||
        email.indexOf('@', 0) == -1 ||
        email.indexOf('.', 0) == -1 ||
        email.indexOf(' ') != -1 ||
        email.lastIndexOf("@")==email.length-1 ||
        email.lastIndexOf(".")==email.length-1)
    {
        contactusFormErrors++;
        invalidateField(aff_Email);
    }


    if(aff_Email.val() != aff_ConfirmEmail.val()){
        contactusFormErrors++;
        invalidateField(aff_ConfirmEmail);
        invalidateField(aff_Email);
        $("#errorMessage").append("Email addresses don't match.<br />");  // TODO: localize

    }


    if(aff_Password.val() != aff_ConfirmPassword.val()){
        contactusFormErrors++;
        invalidateField(aff_ConfirmPassword);
        invalidateField(aff_Password);
        $("#errorMessage").append("Passwords don't match.<br />");  // TODO: localize
    }


    if(($("#affPaypalContent").css("display") == "none" && $("#affWireTransContent").css("display") == "none")
        || (aff_PaypalEmail.val() == "" && aff_Beneficiary.val() == ""))
    {
        contactusFormErrors++;
        $("#errorMessage").append("Please fill in at least one payment method.<br />");  // TODO: localize
        // invalidateField(aff_PaypalEmail);
        // invalidateField(aff_Beneficiary);
        return false;
    }

    //Check PaypalEmail
    if($("#affPaypalContent").css("display") != "none"){
        var paypalEmail = aff_PaypalEmail.val();
        if (paypalEmail == "" ||
            paypalEmail.charAt(0)=="." ||
            paypalEmail.charAt(0)=="@"||
            paypalEmail.indexOf('@', 0) == -1 ||
            paypalEmail.indexOf('.', 0) == -1 ||
            paypalEmail.indexOf(' ') != -1 ||
            paypalEmail.lastIndexOf("@")==paypalEmail.length-1 ||
            paypalEmail.lastIndexOf(".")==paypalEmail.length-1)
        {
            contactusFormErrors++;
            invalidateField(aff_PaypalEmail);
        }
    }

    if($("#affWireTransContent").css("display") != "none"){
        for(var i = 0; i < bankFields.length; i++) {
            var field = bankFields[i];
            if (field.val() == "") {
                contactusFormErrors++;
                invalidateField(field);
            }
        }
    }

    //Check Understand
    if(! aff_Understand.prop("checked")){
        contactusFormErrors++;
        invalidateField(aff_Understand);
        aff_Understand.siblings(".validateErrorBox").show();
        aff_Understand.closest(".fieldContainer").click(function(){
            aff_Understand.siblings(".validateErrorBox").hide();
        });
    }

    //Check AcceptTerms
    if(! aff_AcceptTerms.prop("checked")){
        contactusFormErrors++;
        invalidateField(aff_AcceptTerms);
        aff_AcceptTerms.siblings(".validateErrorBox").show();
        aff_AcceptTerms.closest(".fieldContainer").click(function(){
            aff_AcceptTerms.siblings(".validateErrorBox").hide();
        });
    }

    if(contactusFormErrors == 0){
        // valid
        return true;
    }else{
        // invalid
       return false;
    }
}
/******************************************************************************************************************/
//When you try to download a whitepaper
function whitepaper(file, title){
    var email = getCookie('email');
    if(title == "" || title == "undefined") title = "Download White Paper";

    if (email != null && email != ""){
        $.ajax({
            type: "POST",
            url: "/getEmail",
            async: "false",
            data: "type=getemail&email="+email+"&target="+file+"&category=Whitepaper",
            dataType: "text",
            success: function(msg){
                //we only want to remove the popoup if the new one is opened
                //self.parent.tb_remove();
            }
        });

        //Cookie Found, Show Sample Report
        url="https://s3.asiainspection.com/files/"+file;
        state='toolbar=none,location=none,left=0,top=0';
        newwin = null;

        // Detecting iPhone Safari
        var iOS = !!navigator.userAgent.match(/iPad/i) || !!navigator.userAgent.match(/iPhone/i);
        var webkit = !!navigator.userAgent.match(/WebKit/i);
        var iOSSafari = iOS && webkit && !navigator.userAgent.match(/CriOS/i);
        if(iOSSafari) {
            trackContentDownload('whitepaper', file);
            location.href = url;
        }

        if(url != "") newwin = window.open(url, "", state);

        if (newwin !== null){
            self.parent.tb_remove();
        }else{
            $("#TB_ajaxContent").html("<span style='line-height:100px;'>Your download should start momentarily, should it not please <a href='"+url+"' onClick='self.parent.tb_remove();' target='_blank'>click here</a>.</span>");
        }
        trackContentDownload('whitepaper', file);
    } else {
        $("#TB_window").remove();
        $("body").append("<div id='TB_window'></div>");
        tb_show(title,"/submitEmailPopup?height=200&width=370&type=whitepaper&report="+file);
        $("#TB_ajaxContent").css("height","auto");
    }
    return false;

}
/******************************************************************************************************************/
//When you try to download a report on the sample reports page

function SampleReport(id, title) {
    var thevalue=getCookie('email');
    url = "";
    if(title == "" || title == "undefined") title = "Sample Reports";
        if (thevalue != null && thevalue != ""){
        //log the data
        reportCat = "";
        if(id == "8tips"){
            reportCat = "8 Tips";
        }else{ reportCat = "Sample Report"; }

        $.ajax({
            type: "POST",
            url: "/getEmail",
            async: "false",
            data: "email="+thevalue+"&target="+id+"&category="+reportCat,
            dataType: "text",
            success: function(msg){
                //we only want to remove the popoup if the new one is opened
                //self.parent.tb_remove();
            }
        });

        //Cookie Found, Show Sample Report
        state='toolbar=none,location=none,left=0,top=0';
        switch (id) {
            case 'CTPAT':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20C-TPAT%20Audit%20Report.pdf"; break;
            
            case 'ENVIRO':
            case 'environmentalaudit':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Environmental%20Audit%20Report.pdf"; break;
            
            case 'ethicalaudit':
            case 'SA':
            case 'AI-Sample-Ethical-Audit':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Ethical%20Audit%20Report.pdf"; break;
            
            case 'manufacturingaudit':
            case 'FA':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Manufacturing%20Audit%20Report.pdf"; break;
            
            case 'CLC':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20CLC%20-%20Dinner%20Set.pdf"; break;
            
            case 'DUPRO':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20DUPRO%20-%20Bag.pdf"; break;
            
            case 'IPC':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20IPC%20-%20Oven.pdf"; break;
            
            case 'PM':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20PM%20-%20Gator%20Skin.pdf"; break;
            
            case 'cableandadapter':
            case 'electronics':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20PSI%20-%20Cable%20and%20Adapter.pdf"; break;
            
            case 'ceramicrabbit':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20PSI%20-%20Ceramic%20Rabbit.pdf"; break;
            
            case 'chairandtable':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20PSI%20-%20Chair%20and%20Table.pdf"; break;
            
            case 'dehumidfiers':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20PSI%20-%20Dehumidfiers.pdf"; break;
            
            case 'lamp':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20PSI%20-%20Lamp.pdf"; break;
            
            case 'plushtoy':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20PSI%20-%20Plush%20toy.pdf"; break;
            
            case 'ridingboots':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20PSI%20-%20Riding%20Boots.pdf"; break;
            
            case 'saucepan':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20PSI%20-%20Saucepan.pdf"; break;
            
            case 'scarf':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20PSI%20-%20Scarf.pdf"; break;
            
            case 'footwear':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20PSI%20-%20Shoes.pdf"; break;
            
            case 'smartphone':
            case 'PSI':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20PSI%20-%20Smartphone.pdf"; break;
            
            case 'socks':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20PSI%20-%20Socks.pdf"; break;
            
            case 'sofa':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20PSI%20-%20Sofa.pdf"; break;
            
            case 'solidwoodfurniture':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20PSI%20-%20Solid%20Wood%20Furniture.pdf"; break;
            
            case 'swimwear':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20PSI%20-%20Swimwear.pdf"; break;
            
            case 'hard-tablet':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20PSI%20-%20Tablet.pdf"; break;
            
            case 'topbox':
            case 'hard-dvb':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20PSI%20-%20Top%20Box.pdf"; break;
            
            case 'toycar':
            case 'toys':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20PSI%20-%20Toy%20Car.pdf"; break;
            
            case 'trousers':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20PSI%20-%20Trousers.pdf"; break;
            
            case 'tshirt':
            case 'soft-shirt':
            case 'clothing':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20PSI%20-%20Tshirt.pdf"; break;
            
            case 'upholsteredbed':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20PSI%20-%20Upholstered%20Bed.pdf"; break;
            
            case 'watch':
            case 'gift':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20PSI%20-%20Watch.pdf"; break;
            
            case 'CPSIA':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Lab%20Testing%20-%20CPSIA.pdf"; break;
            
            case 'REACH':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Lab%20Testing%20-%20REACH.pdf"; break;
            
            case 'structuralAudit':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Structural%20Audit%20Report.pdf"; break;
            
            case 'SMETAAudit':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Ethical%20Audit%20Report%20-%20SMETA.pdf"; break;
            case 'BangladeshAudit':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Ethical%20Audit%20Report%20-%20Bangladesh.pdf"; break;
            
            case 'trainads':
            case 'LAB':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Lab%20Testing%20-%20Toy.pdf"; break;
            
            case 'food':
                url = "https://s3.asiainspection.com/files/samplereports/AFI%20-%20Inspection%20Report%20-%20PSI%20-%20Seafood.pdf"; break;
            
            case 'vegetable':
                url = "https://s3.asiainspection.com/files/samplereports/AFI%20-%20Inspection%20Report%20-%20PSI%20-%20Vegetable.pdf"; break;
            
            case 'ceramictiles':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20-%20Inspection%20Report%20-%20PSI%20-%20Ceramic%20Tiles.pdf"; break;
            
            case 'lamflooring':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20-%20Inspection%20Report%20-%20PSI%20-%20Flooring%20Laminate.pdf"; break;
            
            case 'ROHS':
                url = "https://s3.asiainspection.com/files/samplereports/AI%20Lab%20Testing%20–%20RoHS.pdf"; break;
            case '8tips':
                url = "https://s3.asiainspection.com/files/8-Tips-for-Quality-Control-in-Asia.pdf"; break;
            case 'SAWP':
                url = "https://s3.asiainspection.com/files/asiainspection-social-audit-casestudy.pdf"; break;
            case 'eyewear':
                url = "https://s3.asiainspection.com/files/samplereports/AI-Report-PSI%20Inspection%20Report-Eyewear.pdf"; break;
            default: url = "https://s3.asiainspection.com/files/samplereports/AI%20Inspection%20Report%20-%20PSI%20-%20Smartphone.pdf"; //Default Report
        }

        newwin = null;
        if(url != "") newwin = window.open(url, "", state);
        if (newwin !== null){
            self.parent.tb_remove();
        }else{
            $("#TB_ajaxContent").html("<span style='line-height:100px;'>Your download should start momentarily, should it not please <a href='"+url+"' onClick='self.parent.tb_remove();' target='_blank'>click here</a>.</span>");
        }
        trackContentDownload('samplereport', id);
    } else {
        $("#TB_window").remove();
        $("body").append("<div id='TB_window'></div>");
        tb_show(title,"/submitEmailPopup?height=150&width=445&report="+id);
    }
    return false;

}

//Adwords Tracking for Downloads
function trackContentDownload(downloadtype, filename){
    downloadtype = typeof downloadtype !== 'undefined' ? downloadtype : false;
    filename = typeof filename !== 'undefined' ? filename : false;
    if(typeof goog_report_conversion == 'undefined'){
        $("head").append("<script type='text/javascript' src='/js/trackContentDownload.js'></script>");
        $("head").append("<script type='text/javascript' src='//www.googleadservices.com/pagead/conversion_async.js'></script>");
    }
    goog_report_conversion();

    //Facebook Tracking
    //!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    //n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
    //n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
    //t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
    //document,'script','//connect.facebook.net/en_US/fbevents.js');
    //if(typeof fbTrackInit !== 'undefined' || fbTrackInit == false) fbq('init', '288123598012225');
    //fbq('track', 'ViewContent');
    //fbTrackInit = true;
    
    // Site Catalyst
    if(typeof s !== 'undefined'){
        if(downloadtype == "samplereport"){
            s=s_gi(s_account); 
            s.linkTrackVars="prop19,eVar4,eVar18,events"; 
            s.linkTrackEvents="event5";
            s.prop19="D=g";
            s.eVar4="sample report: " + filename;
            s.eVar18="D=g";
            s.events="event5"; 
            s.tl(this,'o','View Report');
        }
        if(downloadtype == "whitepaper"){
            s=s_gi(s_account); 
            s.linkTrackVars="prop19,eVar16,eVar18,events"; 
            s.linkTrackEvents="event12";
            s.prop19="D=g";
            s.eVar16="white paper: " + filename;
            s.eVar18="D=g";
            s.events="event12"; 
            s.tl(this,'o','Get the White Paper Now');
        }
    }
}

// For Generic Link Tracking in SiteCatalyst
function trackLink(id) {
    eventType = "event13";
    if (id == "See-A-Real-FA") id = "See-A-Real-ManufacturingAudit";
    if (id == "See-A-Real-SA") id = "See-A-Real-EthicalAudit";
    if (id == "clientLanding-getWhitepaper" || id == "clientLanding-getRegRecap" || id == "clientLanding-getBarometer") eventType = "event12";
    if (id == "clientLanding-getMobileApp") eventType = "event14";
    s=s_gi(s_account); 
    s.linkTrackVars="prop19,eVar9,eVar18,events"; 
    s.linkTrackEvents=eventType;
    s.prop19="D=g";
    s.eVar9=id;
    s.eVar18="D=g";
    s.events=eventType;
    s.tl(this,'o',id);
}

//Set a Cookie
function setCookie(c_name,value,exdays){
    var exdate=new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString())+"; path=/";
    document.cookie=c_name + "=" + c_value;
}

function getCookie(c_name){
var i,x,y,ARRcookies=document.cookie.split(";");
for (i=0;i<ARRcookies.length;i++)
{
  x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
  y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
  x=x.replace(/^\s+|\s+$/g,"");
  if (x==c_name)
    {
    return unescape(y);
    }
  }
}

//Aligning height for boxes on Industry and Service Landing Pages [Begin]
var mq1007 = window.matchMedia('all and (min-width: 1007px)');
if(mq1007.matches) alignServiceBoxes();

function alignServiceBoxes(){
    //For Service Landing
    Height_For_Landing_Box_Content = 0;
    $(".landingPageBoxContent","#page_ServiceLanding").each(function(){
        if( $(this).height() > Height_For_Landing_Box_Content ) Height_For_Landing_Box_Content = $(this).height();
    });
    $(this).height(Height_For_Landing_Box_Content);
    $(".landingPageBoxContent","#page_ServiceLanding").height(Height_For_Landing_Box_Content);

    //For Industry Landing
    Height_For_Landing_Box_Content = 0;
    $(".box-list","#page_IndustryLanding").each(function(){
        if( $(this).height() > Height_For_Landing_Box_Content ) Height_For_Landing_Box_Content = $(this).height();
    });
    $(this).height(Height_For_Landing_Box_Content);
    $(".box-list","#page_IndustryLanding").height(Height_For_Landing_Box_Content);
}
//Aligning height for boxes on Industry and Service Landing Pages [End]

if(mq500.matches){
    $("option", "#ai_responsivebundle_register_country").eq(0).attr("disabled","disabled");
}

$('ul').on('click', "li.bibeintrag", function(){
    alert('myattribute =' + $(this).attr('myattribute'));
});

// [Begin] Selecting Phone number by test & target
navPhoneNum = $('#ContactPhoneBoxDropHidden').attr("selected","selected").val();
$("#selectPhone").html(navPhoneNum + " &#x25BE;");
// [End] Selecting Phone number by test & target

// [Begin] Autofill username with Email as it's typed
$("#ai_responsivebundle_register_email").on("keydown", function(evt){
    setTimeout(function(){
        regTempUser = $("#ai_responsivebundle_register_email").val();
        $("#ai_responsivebundle_register_username").val(regTempUser);
    },200);
});
// [End] Autofill username with Email as it's typed

// [Begin] Show Password Checkbox on Registration Page
$("#regPasswordTxt").on("keydown", function(evt){
    setTimeout(function(){
        regTempPass = $("#regPasswordTxt").val();
        $("#ai_responsivebundle_register_password").val(regTempPass);
    },200);
});
$("#regShowPassword").on("change",function(){
    if($("#regShowPassword:checked").length ? true : false ){
        //Checked
        $("#regPasswordTxt").val($("#ai_responsivebundle_register_password").val()).show();
        $("#ai_responsivebundle_register_password").hide();
    }else{
        //Unchecked
        $("#regPasswordTxt").hide();
        $("#ai_responsivebundle_register_password").show();
    }
});
// [End] Show Password Checkbox on Registration Page

// Detecting IE and reloading stylesheets to fix all kinds of stuff
function detectIE() {
    var ua = window.navigator.userAgent;

    var msie = ua.indexOf('MSIE ');
    if (msie > 0) {
        // IE 10 or older => return version number
        return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
    }

    var trident = ua.indexOf('Trident/');
    if (trident > 0) {
        // IE 11 => return version number
        var rv = ua.indexOf('rv:');
        return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
    }

    var edge = ua.indexOf('Edge/');
    if (edge > 0) {
       // IE 12 => return version number
       return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
    }

    // other browser
    return false;
}
function hasHtml5Validation () {
 return typeof document.createElement('input').checkValidity === 'function';
}

function reloadStylesheets() {
    var queryString = '?reload=' + new Date().getTime();
    $('link[rel="stylesheet"]').each(function () {
        this.href = this.href.replace(/\?.*|$/, queryString);
    });

}

$(document).ready(function(){
    if( detectIE() !== false ){
        //Reload Main CSS file
        var queryString = '?reload=' + new Date().getTime();
        $('#mainCSS').each(function () { this.href = this.href.replace(/\?.*|$/, queryString); });
    }
});
// [END] Detecting IE

// what should we do when scrolling occurs
$("#backToTop").hide();
var runOnScroll =  function(evt) {
    pos = (window.pageYOffset || document.scrollTop);
    if (pos <= (document.documentElement.clientHeight/4)){
        $("#backToTop").hide();
    }else{
        $("#backToTop").show();
    }
};
window.addEventListener("scroll", runOnScroll);


function setCookie(c_name,value,exdays){
    var exdate=new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString())+"; path=/";
    document.cookie=c_name + "=" + c_value;
}

//disabling the registration button on submit
$("[name=ai_responsivebundle_register]").on("submit",function(){
    if(!validateRegistrationForm()){
        $("#ai_responsivebundle_register_submit").attr("disabled","disabled");
        return false;
    }
    return true;
});

/******************************************************************************************************************/
//The popup on the chinese registration form asking what exactly they are looking for
function reglookingfor(){
  whatiwant = $("[name=whatiwant]:checked").val();
  switch (whatiwant){
    case 'booking':
        window.location = "/register?bypass=true";
      break;
    case 'confirm':
      $("#reglookingfor_whatwant").hide();
      $("#reglookingfor_confirminspect").show();
      break;
    case 'collaborate':
      $("#reglookingfor_whatwant").hide();
      $("#reglookingfor_emailmarket").show();
      break;
    case 'information':
      $("#reglookingfor_whatwant").hide();
      $("#reglookingfor_emailmarket").show();
      break;
    case 'job':
      $("#reglookingfor_whatwant").hide();
      $("#reglookingfor_jobposts").show();
      break;
  }
}
/******************************************************************************************************************/

//Fitting thickboxes to fullscreen on small screens
function ResizeThickBox(){
    if(mq500.matches){
        $("#TB_window").css({
            "width":"100%",
            "display":"block",
            "height":"100%",
            "margin":"0px",
            "top":"0",
            "left":"0"
        });
        $("#TB_ajaxContent").css({
            "width":"100%",
            "height":"100%",
            "text-align":"center"
        });
    }
}
window.addEventListener('resize', ResizeThickBox, true);

/***********************************************************************************************/
$(window).load(function(){
    $('.nmi_EmailAddress').each(function(){
      var userEmail = getCookie('email');
      $('.nmi_EmailAddress').val(userEmail);
    });
    $('.nmi_CompanyCountry').each(function(){
      var countryCode = $('option:selected', this).attr('areacode');
      $('.nmi_Telephone').val("+" + countryCode);
    });
});

$('.nmi_CompanyCountry').change(function(){
  var countryCode = $('option:selected', this).attr('areacode');
  $('.nmi_Telephone').val("+" + countryCode);
});

//Submitting the Need More Information Sidebox
$(document).on('click', '.nmiSubmitButton', 0, function(){submitNMIForm(this);}); //Triggering the Submission from the Submit Buttons
function submitNMIForm(e){
    parentForm = $(e).parents(".NeedMoreInfoForm");
    $(".nmi_UserName", parentForm).removeClass('invalidFeild');
    $(".nmi_CompanyName", parentForm).removeClass('invalidFeild');
    $(".nmi_EmailAddress", parentForm).removeClass('invalidFeild');
    $(".nmi_CompanyCountry", parentForm).removeClass('invalidFeild');
    $(".nmi_Telephone", parentForm).removeClass('invalidFeild');
    $(".nmi_AboutQuestion", parentForm).removeClass('invalidFeild');
    $(".nmi_Message", parentForm).removeClass('invalidFeild');
    nmi_name = $(".nmi_UserName", parentForm);
    nmi_company = $(".nmi_CompanyName", parentForm);
    nmi_email = $(".nmi_EmailAddress", parentForm);
    nmi_country = $(".nmi_CompanyCountry", parentForm);
    nmi_phone = $(".nmi_Telephone", parentForm);
    nmi_question = $(".nmi_AboutQuestion", parentForm);
    nmi_message = $(".nmi_Message", parentForm);
    nmi_customMail = $(".nmi_customMailType", parentForm).val();

    validated = true;

    //Validation
    if( $(nmi_name).val() == "" ) {
        $(nmi_name).addClass('invalidFeild');
        validated = false;
    }

    if( $(nmi_company).val() == "" ) {
        $(nmi_company).addClass('invalidFeild');
        validated = false;
    }

    var email = $(nmi_email).val();
    email = email.trim();
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    if( email.indexOf(' ') > 0 || (!pattern.test(email)) ){
        $(nmi_email).addClass('invalidFeild');
        validated = false;
    }

    if( $("option:selected",nmi_country).text() == "Country" ) {
        $(nmi_country).addClass('invalidFeild');
        validated = false;
    }

    var phone = $(nmi_phone).val();
    phone = phone.replace(/[\D]|ext/g, '');  // strip all non-numeric characters
    var pattern = new RegExp(/^\d{7,}$/);  // remainder must be a string of digits of sufficient length
    if(!pattern.test(phone)){
        $(nmi_phone).addClass('invalidFeild');
        validated = false;
    }

    if( $("option:selected",nmi_question).text() == "Question" ) {
        $(nmi_question).addClass('invalidFeild');
        validated = false;
    }

    if( $(nmi_message).val() == "" ) {
        $(nmi_message).addClass('invalidFeild');
        validated = false;
    }
    //End of Validation

    // Allowed Custom Mail Types
    var allowedMailTypes = ["AnsecoLabPromo","ProduceInquiry","BerryInquiry","QCA-Member-Program"];

    if(validated) {
        $(".nmiSubmitButton").attr("disabled","disabled").addClass("btn-disabled");
        $(".needmoreinfoFormLoadingWait").show();
        name = $(nmi_name).val();
        company = $(nmi_company).val();
        email = $(nmi_email).val();
        country = $("option:selected",nmi_country).text();
        phone = $(nmi_phone).val();
        question = $("option:selected",nmi_question).attr("value");
        message = $(nmi_message).val();
    
        $.ajax({
            type: "POST",
            url: "/submitInquiry",
            async: "false",
            data: "name="+name+"&company="+company+"&email="+email+"&country="+country+"&phone="+phone+"&question="+question+"&message="+message+"&type="+nmi_customMail,
            dataType: "text",
            success: function(msg){
                $(".nmiSubmitButton").remove();
                $(".needmoreinfoFormLoadingWait").hide();
                $(".submitFormSuccess").show();
                //Set & Write Sitecatalyst Data
                tmp_oldEvents = s.events;
                tmp_oldVar3 = s.eVar3;
                s.events = "event3";
                s.eVar3 = s.pageName;
                var s_code=s.t();
                if(s_code) document.write(s_code);
                s.events = tmp_oldEvents;
                s.eVar3 = tmp_oldVar3;
            }
        });
    }
    return false;
}

// toggling the tracking dropdown for dev environments
$(document.body).on("mouseover", ".trackingOutputBox", function() {
    $(this).css("height","auto");
});
$(document.body).on("mouseout", ".trackingOutputBox", function() {
    $(this).css("height",20);
});

function jump2NMI() {
    jumpPos = $("#nmiBox").position().top + $("#nmiBox").height() + 20;
    $(window).scrollTop(jumpPos);
    $(".nmi_UserName").focus();
    $('.nmi_AboutQuestion>option:eq(1)').prop('selected', true);
}

/* Type username as email is typed on registration page */
$("#ai_responsivebundle_register_email").on("keydown", function(evt){
    setTimeout(function(){
        regTempUser = $("#ai_responsivebundle_register_email").val();
        $("#username").val(regTempUser);
    },200);
});

//.navMenuCol foreach, if characters are longer than X chars, add a <br />
$("li",".navMenuCol").find("a").each(function(){
    string = $(this).text();
    if(string.length > 25){
        i = string.lastIndexOf(" ",25);
        string = string.substr(0, i) + "<br />" + string.substr(i+1);
    }
    $(this).html(string);
});

//Some stuff to do on page load
$(window).on("load", function() {

    //Get URL Params
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) { vars[key] = value; });
    //Check if industry is set and select correct oiption on sample reports page
    if(typeof vars.industry != 'undefined'){
        /*
        INDUSTRIES
        industry_all
        industry_apparel
        industry_electronics
        industry_food_and_food_packaging
        industry_gifts_and_premiums
        industry_industrial_and_construction_items
        industry_toys_and_recreational_items
        */
        //Forcing certain url params to a valid value
        if(vars.industry == "footwear") vars.industry = "apparel";

        $("#sampleDrop_industry").click(); //Selecting the industry dropdowns
        $("#industry_" + vars.industry).click(); //Selecting the correct industry
        if(typeof vars.item != 'undefined'){
            /*
            ITEMS
            item_zip_up_fleece
            item_shirts
            item_digital_video_box
            item_electronics
            item_tablet
            item_seafood
            item_gifts
            item_industrial_items
            item_manifolds
            item_valves
            item_plush_toys
            item_toys
            item_shoes
            */
            //Forcing certain url params to a valid value
            if(vars.item == "runningshoes") vars.item = "shoes";
            $("#item_" + vars.item).click(); //Selecting the correct report
        }
    }


});

onResize = function() {
  //Adjusting Whitepaper Landing Page Card Heights
  cardHeight = 0;
  $(".whitePaperSmallScreen").height("auto").each(function(){
    itemHeight = $(this).height();
    if( itemHeight > cardHeight ) cardHeight = itemHeight;
  });
  $(".whitePaperSmallScreen").height(cardHeight + 40);

  // Forcing industry sections to nhave a height (while still being dynamic) so we can use height 100%
  $(".industrySection").each(function(){
    $(this).css("height","auto");
    myHeight = $(this).height();
    $(this).css("height",myHeight+"px");
  });
}

$(window).load(onResize);
$(window).bind('resize', onResize);