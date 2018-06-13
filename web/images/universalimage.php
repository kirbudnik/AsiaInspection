<?php
	// Example Useage: universalimage.php?img=latestBarometerThumb&lang=fr

	// Get the name of the image to get
	$imgName = strtolower($_GET['img']);

	// Change the target image to the latest one and default to the literal URL (ex. ?img=news-2016Q4-barometer_homepage_thumb will point to https://s3.asiainspection.com/images/news/2016Q4/barometer_homepage_thumb)
	switch ($imgName) {
		case "latestbarometerthumb":
			$imgName = "news/2017Q4/barometer_homepage_thumb"; break;
		default:
			$imgName = str_replace("-", "/", $_GET['img']);
	}

	// Grab the image
	$image =  imagecreatefrompng("https://s3.asiainspection.com/images/".$imgName.".png");
	imagesavealpha($image, true);
	
	// Output Image
	header('Content-type: image/png');
	header('Content-Disposition: filename="'.$imgName.'.png"');
	imagepng($image);
	imagedestroy($image);
	exit;
?>