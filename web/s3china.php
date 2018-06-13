<?php
	$url = "https://s3.asiainspection.com".$_SERVER['SCRIPT_URL'];
	$headers = get_headers($url);
	$header = "";
	foreach ($headers as $v) {
		if( strpos($v, "Content-Type") !== false || strpos($v, "Content-Length") !== false ) $header .= $v . "; ";
	}
	header($header);
	$data = file_get_contents($url);
	echo $data;
?>