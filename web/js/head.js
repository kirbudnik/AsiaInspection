/**
 * This file is for JS that needs to be in the head tag rather that the footer
 */

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

//Function called by Test & Target when a location is detected on a page with contactPhoneDrop mbox
function LocalizedPhoneHandler(country){
    country = country.toLowerCase();
    if( country == "hong_kong_-_head_office" ) country = "hong_kong";
    if( country == "united_kingdom" ) country = "uk";

    //If country code matches another domain, redirect to that domain
    /*
    if(country != "china" && location.host == "www.asiainspection.com"){
        var domainRedirect = getCookie("domainredirect");
        if(domainRedirect == undefined){
            domainRedirect = true;
            setCookie("domainredirect", true, 365);
        }
    
        forceStay = false; // To check it they chose this from the menu
        var query = window.location.search.substring(1);
        var vars = query.split('&');
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split('=');
            if(pair[0] == "stay" && pair[1] == "true") forceStay = true;
        }
        
        if(domainRedirect == true || domainRedirect == "true"){
            if( forceStay ){
                setCookie("domainredirect", false, 1); // Allow them to use the .com for a day
            } else {
                if( country == "france" ) { window.location = "http://www.asiainspection.fr"; }
                if( country == "germany" ) { window.location = "http://www.asiainspection.de"; }
            }
        }
    }
    */

    //Possible Values as of Nov.20, 2015 (in CountryCodes array)
    var CountryCodes = new Array();
    CountryCodes["hong_kong"] = "HK";
    CountryCodes["austria"] = "AT";
    CountryCodes["bulgaria"] = "BG";
    CountryCodes["czech_republic"] = "CZ";
    CountryCodes["denmark"] = "DK";
    CountryCodes["finland"] = "FI";
    CountryCodes["france"] = "FR";
    CountryCodes["germany"] = "DE";
    CountryCodes["italy"] = "IT";
    CountryCodes["norway"] = "NO";
    CountryCodes["poland"] = "PL";
    CountryCodes["portugal"] = "PT";
    CountryCodes["romania"] = "RO";
    CountryCodes["russia"] = "RU";
    CountryCodes["slovakia"] = "SK";
    CountryCodes["spain"] = "ES";
    CountryCodes["sweden"] = "SE";
    CountryCodes["switzerland"] = "CH";
    CountryCodes["turkey"] = "TR";
    CountryCodes["uk"] = "GB";
    CountryCodes["canada"] = "CA";
    CountryCodes["mexico"] = "MX";
    CountryCodes["united_states"] = "US";
    CountryCodes["brazil"] = "BR";
    CountryCodes["colombia"] = "CO";
    CountryCodes["egypt"] = "EG";
    CountryCodes["saudi_arabia"] = "SA";

    $('option','.ContactNumberSelect2').removeAttr("selected");
    $(document).ready(function(){
        //For Navigation
        phone = $('.selectOffice[flag="'+country+'"]').attr("phone");
        $('#selectPhone').html(phone);
        //For Contact Us Page Sidebox
        newPhone = $('option[country="'+CountryCodes[country]+'"]','.ContactNumberSelect2').attr("phone");
        $('.ui-link2','.ContactNumberPhone2').attr("href","tel:"+newPhone).text(newPhone);
        $('option[country="'+CountryCodes[country]+'"]','.ContactNumberSelect2').attr("selected","selected");
    });
}