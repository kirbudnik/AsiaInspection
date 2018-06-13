//Function called by Test & Target when a location is detected on the registration page
function RegistrationCountryHandler(country){
    country = country.toUpperCase();
    $(document).ready(function(){
        $('option','#ai_responsivebundle_register_country').removeAttr('selected');
        $('option[countryCode="'+country+'"]','#ai_responsivebundle_register_country').prop('selected', true);
        $("#ai_responsivebundle_register_telephone").val("+" + Countries[country]);
        userEmail = getCookie('email');
        if(userEmail != ""){
            $("#ai_responsivebundle_register_email").val(userEmail);
            $("#username").val(userEmail);
        }
    });
}

$(document).ready(function(){
    // Trimming the Country Indentifier off the country and making it a seperate attribute
    $("option","#ai_responsivebundle_register_country").each(function(key, opt){
        code = $(opt).val().substr(0, 2);
        countryName = $(opt).val().substr(3);
        seperator = $(opt).val().substr(2, 1);
        if(seperator == "_") $(opt).attr("countryCode",code); //.val(countryName)
    });

    //Setting Areacode when country is changed
    $('#ai_responsivebundle_register_country').change(function(){
        countryCode = $("option:selected", this).attr("countrycode");
        $("#ai_responsivebundle_register_telephone").val("+" + Countries[countryCode]);
    });
});