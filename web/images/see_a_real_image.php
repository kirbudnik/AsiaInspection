<?php
	header('Content-type: image/png');
	$image = "https://s3.asiainspection.com/images/responsive/sidebarImages/realPSI.jpg"; // Default image

	// Get the type and set it to PSI by default
	$type = ( isset($_GET['type']) ? strtolower($_GET['type']) : "psi" );
	if( !in_array( $type, array("ea","ma","psi") ) ) $type = "psi";

	if($type == "psi") $image = "https://s3.asiainspection.com/images/responsive/sidebarImages/realPSI.jpg";
	if($type == "ea") $image = "https://s3.asiainspection.com/images/responsive/sidebarImages/realEA.jpg";
	if($type == "ma") $image = "https://s3.asiainspection.com/images/responsive/sidebarImages/realMA.jpg";

	// Support for different languages
	if( isset($_GET['lang']) ){
		$lang = strtolower($_GET['lang']);
		// French
		if( $lang == "fr" && $type == "psi" ) $image = "https://s3.asiainspection.com/images/responsive/sidebarImages/realPSI_fr.jpg";
		if( $lang == "fr" && $type == "ea" ) $image = "https://s3.asiainspection.com/images/responsive/sidebarImages/realEA_fr.jpg";
		if( $lang == "fr" && $type == "ma" ) $image = "https://s3.asiainspection.com/images/responsive/sidebarImages/realMA_fr.jpg";

		// Chinese
		if( $lang == "zh" && $type == "psi" ) $image = "https://s3.asiainspection.com/images/responsive/sidebarImages/realPSI_cn.jpg";
		if( $lang == "zh" && $type == "ea" ) $image = "https://s3.asiainspection.com/images/responsive/sidebarImages/realEA_cn.jpg";
		if( $lang == "zh" && $type == "ma" ) $image = "https://s3.asiainspection.com/images/responsive/sidebarImages/realMA_cn.jpg";

		// German
		if( $lang == "de" && $type == "psi" ) $image = "https://s3.asiainspection.com/images/responsive/sidebarImages/realPSI_de.jpg";
		if( $lang == "de" && $type == "ea" ) $image = "https://s3.asiainspection.com/images/responsive/sidebarImages/realEA_de.jpg";
		if( $lang == "de" && $type == "ma" ) $image = "https://s3.asiainspection.com/images/responsive/sidebarImages/realMA_de.jpg";

		// Spanish
		if( $lang == "es" && $type == "psi" ) $image = "https://s3.asiainspection.com/images/responsive/sidebarImages/realPSI_es.jpg";
		if( $lang == "es" && $type == "ea" ) $image = "https://s3.asiainspection.com/images/responsive/sidebarImages/realEA_es.jpg";
		if( $lang == "es" && $type == "ma" ) $image = "https://s3.asiainspection.com/images/responsive/sidebarImages/realMA_es.jpg";
	}

	$image = file_get_contents($image);
	echo $image;
?>