//Provides
function RegistrationCountryHandler(country){
	var country = country.toUpperCase();
	$('option','.nmi_CompanyCountry').removeAttr('selected');
	$('option[countrycode="'+country+'"]','.nmi_CompanyCountry').prop('selected', true);
};
