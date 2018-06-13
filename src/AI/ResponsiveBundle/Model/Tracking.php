<?php
namespace AI\ResponsiveBundle\Model;
/**
 * Tracking:
 * This class has one public function called getTrackingCode() that takes 2 parameters (and an optional 3rd)(see function definition).
 * The getTrackingCode() calls the internal private functions for each tracking system that we use (Google Analytics, SiteCatalyst, etc)
 * and returns a string of all the required tracking code for the selected page. This string is to be passed to TWIG from the controller
 * and output with the "|raw" filter.
 *
 * If you add a new page, you simply need to create an entry for it under the switch/case statement inside getTrackingCode(), call this
 * function from your controller and pass the results on to TWIG as "tracking", the code to output it is in the base.html.twig and
 * account.html.twig base templates already.
 *
 * If you create a cookie called "Developer" and set it to "true", the tracking code will be output at the bottom of each page for debugging.
 */
use SunCat\MobileDetectBundle\DeviceDetector\MobileDetector;

class Tracking {
	private $TrackingData = array(
		/* Defaults */
		"DEFAULT" => array(
			"GoogleAnalytics" => "",
			"AdWords" => array(
				"main" => "",
				"contact" => "svTNCOe2RhCLwaP7Aw",
				"register" => "O_EpCI22RhCLwaP7Aw",
				"conversion" => "1063837835",
			),
		),

		/* asiainspection.com */
		"EN" => array(
			"GoogleAnalytics" => "UA-3636987-1",
			"AdWords" => array(
				"main" => "sMjECI3slAQQi8Gj-wM",
				"contact" => "eeKrCOXwlAQQi8Gj-wM",
				"register" => "793ZCO3vlAQQi8Gj-wM",
				"conversion" => "",
			),
		),

		/* chinainspection.com */
		"CN" => array(
			"GoogleAnalytics" => "UA-3636987-10",
			"AdWords" => array(
				"main" => "",
				"contact" => "",
				"register" => "",
				"conversion" => "",
			),
		),

		/* asiainspection.com.br */
		"PT" => array(
			"GoogleAnalytics" => "UA-3636987-12",
			"AdWords" => array(
				"main" => "",
				"contact" => "",
				"register" => "",
				"conversion" => "",
			),
		),

		/* asiainspection.de */
		"DE" => array(
			"GoogleAnalytics" => "UA-3636987-7",
			"AdWords" => array(
				"main" => "d4gJCIXtlAQQi8Gj-wM",
				"contact" => "6LPpCNXylAQQi8Gj-wM",
				"register" => "vQkuCP3tlAQQi8Gj-wM",
				"conversion" => "",
			),
		),

		/* asiainspection.es */
		"ES" => array(
			"GoogleAnalytics" => "UA-3636987-6",
			"AdWords" => array(
				"main" => "",
				"contact" => "z1PSCK7I8wYQwpKN0wM",
				"register" => "z1PSCK7I8wYQwpKN0wM",
				"conversion" => "979585346",
			),
		),

		/* asiainspection.fr */
		"FR" => array(
			"GoogleAnalytics" => "UA-3636987-5",
			"AdWords" => array(
				"main" => "sMjECI3slAQQi8Gj-wM",
				"contact" => "w5nZCN3xlAQQi8Gj-wM",
				"register" => "CHSOCPXulAQQi8Gj-wM",
				"conversion" => "",
			),
		),

		/* asiainspection.it */
		"IT" => array(
			"GoogleAnalytics" => "UA-3636987-8",
			"AdWords" => array(
				"main" => "",
				"contact" => "",
				"register" => "",
				"conversion" => "",
			),
		),

		/* asiainspection.ru */
		"RU" => array(
			"GoogleAnalytics" => "UA-3636987-1",
			"AdWords" => array(
				"main" => "",
				"contact" => "",
				"register" => "",
				"conversion" => "",
			),
		),

		/* asiainspection.com.tr */
		"TR" => array(
			"GoogleAnalytics" => "UA-3636987-1",
			"AdWords" => array(
				"main" => "",
				"contact" => "",
				"register" => "",
				"conversion" => "",
			),
		),

		/* asiainspection.ae */
		"AE" => array(
			"GoogleAnalytics" => "",
			"AdWords" => array(
				"main" => "",
				"contact" => "Qhi3COX4zAoQi8Gj-wM",
				"register" => "mPu8CN2t_AgQi8Gj-wM",
				"conversion" => "",
			),
		),

		/* asiainspection.ae (alternate)*/
		"AR" => array(
			"GoogleAnalytics" => "",
			"AdWords" => array(
				"main" => "",
				"contact" => "Qhi3COX4zAoQi8Gj-wM",
				"register" => "mPu8CN2t_AgQi8Gj-wM",
				"conversion" => "",
			),
		),

		/* Mobile Device Tracking */
		"MOBILE" => array(
			"GoogleAnalytics" => "UA-3636987-13",
			"AdWords" => array(
				"main" => "",
				"contact" => "",
				"register" => "",
				"conversion" => "",
			),
		),

	);

	/**
	 * [getSiteCatalystCode Used to get the SiteCatalyst tracking code for a page]
	 * @param  [string] $pageCategory [description]
	 * @param  [string] $pageName     [description]
	 * @param  [string] $pageEvent    [description]
	 * @param  [string] $lang         [description]
	 */
	private function getSiteCatalystCode($pageCategory, $pageName, $pageEvent, $lang) {
		$code = "\n\n<!-- SiteCatalyst Tracking Code [Begin] -->\n";
		$code .= "<script type='text/javascript' src='/js/s_code.js'></script>\n";
		$code .= "\t<script type='text/javascript'>\n";
		if ($pageCategory == "404" && $pageName == "404") {
			$code .= "\ts.pageName='';\n";
			$code .= "\ts.channel='';\n";
			$code .= "\ts.prop1=s.eVar1='" . $lang . "';\n";
			$code .= "\ts.pageType='errorPage';\n";
		} else {
			if ( isset($pageCategory) && $pageCategory != "" ) {
				if( !isset($pageName) || $pageName == "" ) {
					$code .= "\ts.pageName='';\n";
				}else {
					$code .= "\ts.pageName='" . $lang . ":" . $pageCategory . ":" . $pageName . "';\n";	
				}
				$code .= "\ts.channel='" . $lang . ":" . $pageCategory . "';\n";
			} else {
				$code .= "\ts.pageName='';\n";
				$code .= "\ts.channel='';\n";
			}
			$code .= "\ts.prop1=s.eVar1='" . $lang . "';\n";
		}
		$code .= "\ts.events='" . $pageEvent . "';\n";
		$code .= "\ts.eVar3='';\n";
		$code .= "\t/************* DO NOT ALTER ANYTHING BELOW THIS LINE ! **************/\n";
		$code .= "\tvar s_code=s.t();\n";
		$code .= "\tif(s_code) document.write(s_code);\n";
		$code .= "\t/************* DO NOT ALTER ANYTHING ABOVE THIS LINE ! **************/\n";
		$code .= "</script>\n";
		$code .= "<!-- SiteCatalyst Tracking Code [End] -->\n";
		return $code;
	} //End of 'getSiteCatalystCode' Function

	/**
	 * [getYandexCode description]
	 * @param  [type] $pageName   [description]
	 * @return [string]           [Tracking code to be output]
	 */
	private function getYandexCode($pageName) {
		$code = "\n\n<!-- Yandex Tracking Code [Begin] -->\n";
		$code .= "<script type='text/javascript'>\n";
		$code .= "(function (d, w, c) {\n";
		$code .= "\t(w[c] = w[c] || []).push(function() {\n";
		$code .= "\t\ttry {\n";
		$code .= "\t\t\tw.yaCounter17072302 = new Ya.Metrika({id:17072302,\n";
		$code .= "\t\t\t\tclickmap:true,\n";
		$code .= "\t\t\t\ttrackLinks:true,\n";
		$code .= "\t\t\t\taccurateTrackBounce:true});\n";
		$code .= "\t\t} catch(e) { }\n";
		$code .= "\t});\n\n";
		$code .= "\tvar n = d.getElementsByTagName('script')[0],\n";
		$code .= "\t\ts = d.createElement('script'),\n";
		$code .= "\t\tf = function () { n.parentNode.insertBefore(s, n); };\n";
		$code .= "\ts.type = 'text/javascript';\n";
		$code .= "\ts.async = true;\n";
		$code .= "\ts.src = (d.location.protocol == 'https:' ? 'https:' : 'http:') + '//mc.yandex.ru/metrika/watch.js';\n\n";
		$code .= "\tif (w.opera == '[object Opera]') {\n";
		$code .= "\t\t\td.addEventListener('DOMContentLoaded', f, false);\n";
		$code .= "\t} else { f(); }\n";
		$code .= "})(document, window, 'yandex_metrika_callbacks');\n";
		if ($pageName == 'RegSuccess') {
			$code .= "yaCounter17597161.reachGoal('REGISTRATION');\n";
		}

		if ($pageName == 'ContactSuccess') {
			$code .= "yaCounter17597161.reachGoal('ContactForm');\n";
		}
		$code .= "</script>\n";
		$code .= "<noscript><div><img src='http://mc.yandex.ru/watch/17072302' style='position:absolute; left:-9999px;' alt=' /></div></noscript>\n";
		$code .= "<!-- Yandex Tracking Code [End] -->\n";
		return $code;
	} //End of 'getYandexCode' Function

	/**
	 * [getBingCode description]
	 * @return [string] [Tracking code to be output]
	 */
	private function getBingCode() {
		$code = "\n\n<!-- Bing Tracking Code [Begin] -->\n";
		$code .= "<script>(function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],f=function(){var o={ti:'5117352'};o.q=w[u],w[u]=new UET(o),w[u].push('pageLoad')},n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){var s=this.readyState;s&&s!=='loaded'&&s!=='complete'||(f(),n.onload=n.onreadystatechange=null)},i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)})(window,document,'script','//bat.bing.com/bat.js','uetq');</script>\n";
		$code .= "<noscript><img src='//bat.bing.com/action/0?ti=5117352&Ver=2' height='0' width='0' style='display:none; visibility: hidden;' /></noscript>\n";
		$code .= "<!-- Bing Tracking Code [End] -->\n";
		return $code;
	} //End of 'getBingCode' Function

	/**
	 * [getCriteoCode description]
	 * @param  [type] $pageName [description]
	 * @return [string] [Tracking code to be output]
	 */
	private function getCriteoCode($pageName = "", $prodID = "") {
		$body = false;
		$emailCookie = ( isset($_COOKIE['email']) ? $_COOKIE['email'] : "" );
		$criteoLastProductCookie = ( isset($_COOKIE['criteoLastProduct']) ? $_COOKIE['criteoLastProduct'] : "Registration" );
		$mode = "d"; // m for mobile or t for tablet or d for desktop
		try {
			$mobileDetector = new MobileDetector();
			if( $mobileDetector->isMobile() ) $mode = "m";
			if( $mobileDetector->isTablet() ) $mode = "t";
		} catch(\Exception $e) {
			// Do nothing, just don't break :-)
		}
		
		switch ($pageName) {
			case "homepage":
				$event = '{ event: "viewHome" }'; $body = true; break;
			case "service-landing":
				$event = '{ event: "viewList", item: ["Inspection_PSI","Audit_MA","Lab_TESTING"] }'; $body = true; break;
			case "industry-landing":
				$event = '{ event: "viewList", item: ["Industry_HARDLINES","Industry_SOFTLINES","Industry_TECHNICAL"] }'; $body = true; break;
			case "registration":
				$event = '{ event: "viewBasket", item: ["'.$criteoLastProductCookie.'"]}'; $body = true; break;
			case "regsuccess":
				$event = '{ event: "trackTransaction", id: "'.uniqid("Criteo_").'", item: [ { id: "'.$criteoLastProductCookie.'", price: 1, quantity: 1 } ]}'; $body = true; break;
		}
		
		if( $prodID != "" ) {
			setcookie("criteoLastProduct", $prodID, strtotime("+1 year"), "/");
			$event = '{ event: "viewItem", item: "'.$prodID.'" }';
			$body = true;
		}

		$code = "\n\n<!-- Criteo Tracking Code [Begin] -->\n";
		$code .= "<script type='text/javascript' src='https://static.criteo.net/js/ld/ld.js' async='true'></script>\n";
		$code .= "<script type='text/javascript'>\n";
		$code .= "\twindow.criteo_q = window.criteo_q || [];\n";
		$code .= "\twindow.criteo_q.push(\n";
		$code .= "\t\t{ event: 'setAccount', account: 27375 },\n";
		$code .= "\t\t{ event: 'setSiteType', type: '".$mode."' },\n";
		/*$code .= "\t\t{ event: 'setEmail', email: '".$emailCookie."' },\n";*/
		$code .= "\t\t".$event."\n";
		$code .= "\t);\n";
		$code .= "</script>\n";
		$code .= "<!-- Criteo Code [End] -->\n";

		return ( $body ? $code : "" );
	} //End of 'getCriteoCode' Function

	/**
	 * [getFacebookCode description]
	 * @param  [type] $pageName [description]
	 * @return [string]         [Tracking code to be output]
	 */
	private function getFacebookCode($pageName = "") {
		$code = "\n\n<!-- Facebook Tracking Code [Begin] -->\n";
		if ($pageName == 'RegSuccess') {
			$code .= "<script type='text/javascript'>\n";
			$code .= "\t!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?\n";
			$code .= "\tn.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;\n";
			$code .= "\tn.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;\n";
			$code .= "\tt.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,\n";
			$code .= "\tdocument,'script','//connect.facebook.net/en_US/fbevents.js');\n";
			$code .= "\tfbq('init', '288123598012225');\n";
			$code .= "\tfbq('track', 'CompleteRegistration');\n";
			$code .= "</script>\n";
		} else if ($pageName == 'Register') {
			$code .= "<script type='text/javascript'>\n";
			$code .= "\t!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?\n";
			$code .= "\tn.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;\n";
			$code .= "\tn.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;\n";
			$code .= "\tt.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,\n";
			$code .= "\tdocument,'script','//connect.facebook.net/en_US/fbevents.js');\n";
			$code .= "\tfbq('init', '288123598012225');\n";
			$code .= "\tfbq('track', 'InitiateCheckout');\n";
			$code .= "</script>\n";
		} else if ($pageName == 'Content') {
			/******************************************************************
			This code is output by the trackContentDownload() function in custom.js
			*******************************************************************			
			$code .= "<script type='text/javascript'>\n";
			$code .= "\t!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?\n";
			$code .= "\tn.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;\n";
			$code .= "\tn.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;\n";
			$code .= "\tt.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,\n";
			$code .= "\tdocument,'script','//connect.facebook.net/en_US/fbevents.js');\n";
			$code .= "\tfbq('init', '288123598012225');\n";
			$code .= "\tfbq('track', 'ViewContent');\n";
			$code .= "</script>\n";
			*/
		}
		return $code;
	} //End of 'getFacebookCode' Function

	/**
	 * [getBaiduCode description]
	 * @return [string] [Tracking code to be output]
	 */
	private function getBaiduCode() {
		$code = "\n\n<!-- Baidu Tracking Code [Begin] -->\n";
		$code .= "<script type='text/javascript' src='https://hm.baidu.com/hm.js?47e77c487289ff5af4186ecb02bb7fbc'></script>\n";
		$code .= "<script type='text/javascript'>\n";
		$code .= "//Remove the popup that Baidu decided they could slip into our tracking code!\n";
		$code .= "baiduloop=0;\n";
		$code .= "var baidulooptimer = setInterval(function() {\n";
		$code .= "\tbaiduloop++;\n";
		$code .= "\tif (($('#LXB_CONTAINER_SHOW').length > 0 && $('#LXB_CONTAINER').length > 0) || baiduloop > 3000) {\n";
		$code .= "\t\tclearInterval(baidulooptimer);\n";
		$code .= "\t\t$('#LXB_CONTAINER_SHOW').remove();\n";
		$code .= "\t\t$('#LXB_CONTAINER').remove();\n";
		$code .= "\t}\n";
		$code .= "},100);\n";
		$code .= "</script>\n";
		$code .= "<!-- Baidu Tracking Code [End] -->\n";
		return $code;
	} //End of 'getBaiduCode' Function

	/**
	 * [getAdobeTestandTargetCode description]
	 * @param  [type] $pageName [description]
	 * @return [string]           [Tracking code to be output]
	 */
	private function getAdobeTestandTargetCode($pageName) {
		$code = "\n\n<!-- Adobe Test and Target Tracking Code [Begin] -->\n";
		//$code .= "<script type='text/javascript' src='https://s3.asiainspection.com/libs/mbox.js'></script>\n";
		if ($pageName == 'RegSuccess') $code .= "<script type='text/javascript'>mboxCreate('en_conv_registration_page_success');</script>\n";
		if ($pageName == 'ContactSuccess') $code .= "<script type='text/javascript'>mboxCreate('en_conv_contact_us_success');</script>\n";
		$code .= "<!-- Adobe Test and Target Tracking Code [End] -->\n";
		return $code;
	} //End of 'getAdobeTestandTargetCode' Function

	/**
	 * [getGoogleAnalyticsCode description]
	 * @param  [type] $id       [description]
	 * @return [string]           [Tracking code to be output]
	 */
	private function getGoogleAnalyticsCode($id) {
		$code = "\n\n<!-- Google Analytics Tracking Code [Begin] -->\n";
		//$code .= "<script type='text/javascript'>\n";
		//$code .= "\tvar _gaq = _gaq || [];\n";
		//$code .= "\t_gaq.push(['_setAccount', '".$id."']);\n";
		//$code .= "\t_gaq.push(['_trackPageview']);\n\n";
		//$code .= "\t(function() {\n";
		//$code .= "\t\tvar ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;\n";
		//$code .= "\t\tga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';\n";
		//$code .= "\t\tvar s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);\n";
		//$code .= "\t})();\n\n";
		//$code .= "/* Global Analytics Code */\n";
		//$code .= "var _gaq = _gaq || [];\n";
		//$code .= "_gaq.push(['_setAccount', 'UA-3636987-17']);\n";
		//$code .= "_gaq.push(['_trackPageview']);\n\n";
		//$code .= "(function() {\n";
		//$code .= "\tvar ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;\n";
		//$code .= "\tga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';\n";
		//$code .= "\tvar s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);\n";
		//$code .= "})();\n";
		//$code .= "</script>\n";
		$code .= "<!-- Google Analytics Tracking Code [End] -->\n";
		return $code;
	} //End of 'getGoogleAnalyticsCode' Function

	/**
	 * [getGoogleRemarketingCode description]
	 * @param  [type] $id           [description]
	 * @param  [type] $conversionID [description]
	 * @return [string]               [Tracking code to be output]
	 */

	private function getGoogleRemarketingCode($id, $conversionID) {
		$code = "\n\n<!-- Google Remarketing Tracking Code [Begin] -->\n";
		$code .= "<script type='text/javascript'>\n";
		$code .= "\tvar google_conversion_id = ".$conversionID.";\n";
		$code .= "\tvar google_conversion_label = '".$id."';\n";
		$code .= "\tvar google_custom_params = window.google_tag_params;\n";
		$code .= "\tvar google_remarketing_only = true;\n";
		$code .= "</script>\n";
		$code .= "<!-- This Line is wrapped in a div because it is creating an iframe 13px high at the top of the page -->\n";
		$code .= "<div style='display:none;'><script type='text/javascript' src='https://www.googleadservices.com/pagead/conversion.js'></script></div>\n";
		$code .= "<noscript>\n";
		$code .= "\t<div style='display:inline;'><img height='1' width='1' style='border-style:none;' alt=' src='http://googleads.g.doubleclick.net/pagead/viewthroughconversion/".$conversionID."/?value=0&amp;label=".$id."&amp;guid=ON&amp;script=0'/></div>\n";
		$code .= "</noscript>\n";
		$code .= "<!-- Google Remarketing Tracking Code [End] -->\n";
		return $code;
	} //End of 'getGoogleRemarketingCode' Function

	/**
	 * [getGoogleAdWordsCode description]
	 * @param  [type] $adwordsID    [description]
	 * @param  [type] $lang         [description]
	 * @param  [type] $conversionID [description]
	 * @return [string]             [Tracking code to be output]
	 */
	private function getGoogleAdWordsCode($pageName, $adwordsID, $lang, $conversionID) {
		$code = "\n\n<!-- Google AdWords Tracking Code [Begin] -->\n";
		$google_conversion_format = "";
		$google_conversion_value = "0";
		if ($pageName == 'RegSuccess') {
			$google_conversion_format = "1";
			$google_conversion_value = "1.00";
		}
		if ($pageName == 'ContactSuccess') $google_conversion_format = "3";
		$code .= "<script type='text/javascript'>\n";
		$code .= "\tvar google_conversion_id = ".$conversionID.";   // This number is unique to your AdWords account. This is how we know what AdWords account the conversion is for.\n";
		$code .= "\tvar google_conversion_language = '".$lang."';\n";
		$code .= "\tvar google_conversion_format = '".$google_conversion_format."';\n";
		$code .= "\tvar google_conversion_color = 'ffffff';\n";
		$code .= "\tvar google_conversion_label = '".$adwordsID."';  // This value is unique for each conversion name you've defined in your account.\n";
		$code .= "\tvar google_conversion_value = ".$google_conversion_value.";\n";
		$code .= "\tvar google_conversion_currency = 'USD';\n";
		$code .= "\tvar google_remarketing_only = false;\n";
		$code .= "</script>\n";
		$code .= "<!-- This Line is wrapped in a div because it is creating an iframe 13px high at the top of the page -->\n";
		$code .= "<div style='display:none;'><script type='text/javascript' src='https://www.googleadservices.com/pagead/conversion.js'></script></div>\n";
		$code .= "<noscript>\n";
		$code .= "\t<div style='display:inline;'>\n";
		$code .= "\t\t<img height='1' width='1' style='border-style:none;' alt=' src='http://www.googleadservices.com/pagead/conversion/".$conversionID."/?value=0&amp;label=".$adwordsID."&amp;guid=ON&amp;script=0' />\n";
		$code .= "\t</div>\n";
		$code .= "</noscript>\n";
		$code .= "<!-- Google AdWords Tracking Code [End] -->\n";
		return $code;
	} //End of 'getGoogleAdWordsCode' Function

	/**
	 * [getGoogleCrossConversionCode description]
	 * @param  [type] $pageName [description]
	 * @param  [type] $lang     [description]
	 * @return [string]         [Tracking code to be output]
	 */
	private function getGoogleCrossConversionCode($pageName, $lang) {
		$code = "\n\n<!-- Google Cross-Conversion Tracking Code [Begin] -->\n";
		if ($pageName == 'RegSuccess') {
			$google_conversion_format = "";
			$conversionValue = 0;
			$google_conversion_format = "3";
			$adwordsID = "Gxz0CJvYpwkQveKP4AM";
			$conversionValue = 309;
		}
		if ($pageName == 'ContactSuccess') {
			$google_conversion_format = "";
			$conversionValue = 0;
			$google_conversion_format = "3";
			$adwordsID = "v1M5CJPZpwkQveKP4AM";
			$conversionValue = 0;
		}
		$code .= "<script type='text/javascript'>\n";
		$code .= "\tvar google_conversion_id = 1006891325;   // This number is unique to your AdWords account. This is how we know what AdWords account the conversion is for.\n";
		$code .= "\tvar google_conversion_language = '".$lang."';\n";
		$code .= "\tvar google_conversion_format = '".$google_conversion_format."';\n";
		$code .= "\tvar google_conversion_color = 'ffffff';\n";
		$code .= "\tvar google_conversion_label = '".$adwordsID."';  // This value is unique for each conversion name you've defined in your account.\n";
		$code .= "\tvar google_conversion_value = ".$conversionValue.";  // This is the optional value associated with the conversion. Conversion URL: This is the URL that's shown in the Webpages tab of conversion tracking reports.\n";
		$code .= "\tvar google_remarketing_only = false;\n";
		$code .= "</script>\n";
		$code .= "<!-- This Line is wrapped in a div because it is creating an iframe 13px high at the top of the page -->\n";
		$code .= "<div style='display:none;'><script type='text/javascript' src='https://www.googleadservices.com/pagead/conversion.js'></script></div>\n";
		$code .= "<noscript>\n";
		$code .= "\t<div style='display:inline;'>\n";
		$code .= "\t\t<img height='1' width='1' style='border-style:none;' alt=' src='http://www.googleadservices.com/pagead/conversion/1006891325/?value=".$conversionValue."&amp;label=".$adwordsID."&amp;guid=ON&amp;script=0' />\n";
		$code .= "\t</div>\n";
		$code .= "</noscript>\n";
		$code .= "<!-- Google Cross-Conversion Tracking Code [End] -->\n";
		return $code;
	} //End of 'getGoogleCrossConversionCode' Function

	/**
	 * [getGoogleTagManagerCode description]
	 * @return [string] [Tracking code to be output]
	 */
	private function getGoogleTagManagerHeadCode() {
		$code = "\n\n<!-- Google Tag Manager Tracking Code [Begin] -->\n";
		$code .= "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':\n";
		$code .= "new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],\n";
		$code .= "j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=\n";
		$code .= "'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);\n";
		$code .= "})(window,document,'script','dataLayer','GTM-WX7G');</script>\n";
		$code .= "<!-- Google Tag Manager Tracking Code [End] -->\n";
		return $code;
	} //End of 'getGoogleTagManagerCode' Function

	private function getGoogleTagManagerCode() {
		$code = "\n\n<!-- Google Tag Manager Tracking Code [Begin] -->\n";
		$code .= "<noscript><iframe src='https://www.googletagmanager.com/ns.html?id=GTM-WX7G' height='0' width='0' style='display:none;visibility:hidden'></iframe></noscript>\n";
		$code .= "<!-- Google Tag Manager Tracking Code [End] -->\n";
		return $code;
	} //End of 'getGoogleTagManagerCode' Function

	/**
	 * [getAdrollPixelCode description]
	 * @return [string] [Tracking code to be output]
	 */
	private function getAdrollPixelCode() {
		$code = "\n\n<!-- Adroll Pixel Tracking Code [Begin] -->\n";
		$code .= "<script type='text/javascript'>\n";
		$code .= "adroll_adv_id = 'CVTHN6XQ3JHQNHFOH3AE4Q';\n";
		$code .= "adroll_pix_id = 'SPY6SYFXVZG7DOX3T65VOG';\n";
		$code .= "(function () {\n";
		$code .= "var oldonload = window.onload;\n";
		$code .= "window.onload = function(){\n";
		$code .= "\t__adroll_loaded=true;\n";
		$code .= "\tvar scr = document.createElement('script');\n";
		$code .= "\tvar host = (('https:' == document.location.protocol) ? 'https://s.adroll.com' : 'http://a.adroll.com');\n";
		$code .= "\tscr.setAttribute('async', 'true');\n";
		$code .= "\tscr.type = 'text/javascript';\n";
		$code .= "\tscr.src = host + '/j/roundtrip.js';\n";
		$code .= "\t((document.getElementsByTagName('head') || [null])[0] || document.getElementsByTagName('script')[0].parentNode).appendChild(scr);\n";
		$code .= "\tif(oldonload){oldonload()}};\n";
		$code .= "}());\n";
		$code .= "</script>\n";
		$code .= "<!-- Adroll Pixel Tracking Code [End] -->\n";
		return $code;
	} //End of 'getAdrollPixelCode' Function

	/**
	 * [getTwitterConversionCode description]
	 * @return [string] [Tracking code to be output]
	 */
	private function getTwitterConversionCode() {
		$code = "\n\n<!-- Twitter Conversion Tracking Code [Begin] -->\n";
		$code .= "<script src='//platform.twitter.com/oct.js' type='text/javascript'></script>\n";
		$code .= "<script type='text/javascript'>twttr.conversion.trackPid('l6ro1', { tw_sale_amount: 0, tw_order_quantity: 0 });</script>\n";
		$code .= "<noscript>\n";
		$code .= "<img height='1' width='1' style='display:none;' alt=' src='https://analytics.twitter.com/i/adsct?txn_id=l6ro1&p_id=Twitter&tw_sale_amount=0&tw_order_quantity=0' />\n";
		$code .= "<img height='1' width='1' style='display:none;' alt=' src='//t.co/i/adsct?txn_id=l6ro1&p_id=Twitter&tw_sale_amount=0&tw_order_quantity=0' />\n";
		$code .= "</noscript>\n";
		$code .= "<!-- Twitter Conversion Tracking Code [End] -->\n";
		return $code;
	} //End of 'getTwitterConversionCode' Function

	/**
	 * [getMarketoCode description]
	 * @return [string] [Tracking code to be output]
	 */
	private function getMarketoCode() {
		$code = "\n\n<!-- Marketo Tracking Code [Begin] -->\n";
		$code .= "<script type='text/javascript'>\n";
		$code .= "(function() {\n";
		$code .= "\tvar didInit = false;\n";
		$code .= "\tfunction initMunchkin() {\n";
		$code .= "\t\tif(didInit === false) {\n";
		$code .= "\t\t\tdidInit = true;\n";
		$code .= "\t\t\tMunchkin.init('944-QDO-384');\n";
		$code .= "\t\t}\n";
		$code .= "\t}\n";
		$code .= "\tvar s = document.createElement('script');\n";
		$code .= "\ts.type = 'text/javascript';\n";
		$code .= "\ts.async = true;\n";
		$code .= "\ts.src = '//munchkin.marketo.net/munchkin.js';\n";
		$code .= "\ts.onreadystatechange = function() {\n";
		$code .= "\t\tif (this.readyState == 'complete' || this.readyState == 'loaded') {\n";
		$code .= "\t\t\tinitMunchkin();\n";
		$code .= "\t\t}\n";
		$code .= "\t};\n";
		$code .= "\ts.onload = initMunchkin;\n";
		$code .= "\tdocument.getElementsByTagName('head')[0].appendChild(s);\n";
		$code .= "})();\n";
		$code .= "</script>\n";
		$code .= "<!-- Marketo Tracking Code [End] -->\n";
		return $code;
	} //End of 'getMarketoCode' Function

	/**
	 * [getLinkedInRetargetingCode description]
	 * @return [string] [Tracking code to be output]
	 */
	private function getLinkedInRetargetingCode() {
		$code = "\n\n<!-- LinkedIn Retargeting Tracking Code [Begin] -->\n";
		$code .= "<script type='text/javascript'>\n";
		$code .= "\t_linkedin_data_partner_id = '57278';\n";
		$code .= "</script>\n";
		$code .= "<script type='text/javascript'>\n";
		$code .= "\t(function() {\n";
		$code .= "\t\tvar s = document.getElementsByTagName('script')[0];\n";
		$code .= "\t\tvar b = document.createElement('script');\n";
		$code .= "\t\tb.type = 'text/javascript';b.async = true;\n";
		$code .= "\t\tb.src = 'https://snap.licdn.com/li.lms-analytics/insight.min.js';\n";
		$code .= "\t\ts.parentNode.insertBefore(b, s);})();\n";
		$code .= "</script>\n";
		$code .= "<noscript>\n";
		$code .= "\t<img height='1' width='1' style='display:none;' alt=' src='https://dc.ads.linkedin.com/collect/?pid=57278&fmt=gif' />\n";
		$code .= "</noscript>\n";
		$code .= "<!-- LinkedIn Retargeting Tracking Code [End] -->\n";
		return $code;
	} //End of 'getLinkedInRetargetingCode' Function


/*
	Tracking Code Functions Reference
	----------------------------------
	getSiteCatalystCode($pageCategory, $pageName, $pageEvent, $lang)
	getYandexCode($pageName)
	getBingCode()
	getFacebookCode($pageName)
	getBaiduCode()
	getAdobeTestandTargetCode($pageName)
	getGoogleAnalyticsCode($pageName, $id)
	getGoogleRemarketingCode($id, $conversionID)
	getGoogleAdWordsCode($adwordsID, $lang, $conversionID)
	getGoogleCrossConversionCode($pageName, $lang)
	getGoogleTagManagerCode()
	getAdrollPixelCode()
	getTwitterConversionCode()
 */


	/**
	 * [getTrackingCode Used to get the complete tracking code for a page]
	 * @param [string]  $page         [the ID for the page to get tracking for]
	 * @param [string]  $locale       [the domain to get tracking for (.com, .fr, .cn, etc.), for now this should be the language]
	 * @param  [string] $subTarget    [Optional: for pages that have sub items to track such as whitepapers, samplereports or press releases]
	 * @return [string]               [Tracking code to output]
	 */
	public function getTrackingCode($page, $locale, $subTarget = '') {
		$body = $head = "";
		$page = strtolower($page);
		$lang = $locale;
		if($locale == "zh") $lang = "cn";
		if($locale == "" || $locale == null) $lang = "en";

		//return Empty if this is not one of our domains
		$allowedDomains = array(
			"www.asiainspection.com",
			"asiainspection.com",
			"www.chinainspection.com",
			"chinainspection.com",
			"www.asiainspection.fr",
			"asiainspection.fr",
			"www.asiainspection.de",
			"asiainspection.de",
			"www.asiainspection.ae",
			"asiainspection.ae",
			"www.asiainspection.es",
			"asiainspection.es",
			"www.asiainspection.com.br",
			"asiainspection.com.br",
			"www.asiainspection.com.tr",
			"asiainspection.com.tr",
			"116.62.113.240",
			"www.eyeweartesting.com",
			"eyeweartesting.com",
			"www.youreyesinthefactory.com",
			"youreyesinthefactory.com"
		);
		// if( !in_array($_SERVER['HTTP_HOST'], $allowedDomains) ) return $body;

		switch ($page) {
			//Homepage
			case "homepage":
				$body .= $this->getSiteCatalystCode('home', 'home', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page);
				break;

			//Sitemap
			case "sitemap":
				$body .= $this->getSiteCatalystCode('about', 'site map', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Affiliate Program
			case "affiliate-program":
				$body .= $this->getSiteCatalystCode('affiliate', 'about', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Affiliate Registration
			case "affiliate-registration":
				$body .= $this->getSiteCatalystCode('affiliate', 'start', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Lab Testing Page
			case "labtest":
				$body .= $this->getSiteCatalystCode('lt', 'lt', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Lab_TESTING');
				break;

			//Lab Test Landing Page
			case "labtestLanding":
				$body .= $this->getSiteCatalystCode('landing', 'laboratoryTesting', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Mobile Landing
			case "mobile-landing":
				$body .= $this->getSiteCatalystCode('about', 'mobile', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Affiliate Success
			case "affiliate-success":
				$body .= $this->getSiteCatalystCode('affiliate', 'success', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Terms & Conditions
			case "conditions":
				$body .= $this->getSiteCatalystCode('about', 'conditions', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Contact Us
			case "contact":
				$body .= $this->getSiteCatalystCode('lead', 'contact', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Career Opportunities
			case "career-ops":
				$body .= $this->getSiteCatalystCode('career', 'career opportunities', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Job Application
			case "job-app":
				$body .= $this->getSiteCatalystCode('career', 'job application', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Landing page after logging out of client account
			case "logout":
				$body .= $this->getSiteCatalystCode('landing', 'logout', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//view a job post
			case "view-job":
				$body .= $this->getSiteCatalystCode('career', 'view job', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//landing page for all our services
			case "service-landing":
				$body .= $this->getSiteCatalystCode('service', 'category', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page);
				break;

			//Pre-shipment Inspection
			case "psi":
				$body .= $this->getSiteCatalystCode('inspection', 'psi', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Inspection_PSI');
				break;

			//Production Monitoring
			case "promon":
				$body .= $this->getSiteCatalystCode('inspection', 'pm', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Inspection_PM');
				break;

			//During Production Inspection
			case "dupro":
				$body .= $this->getSiteCatalystCode('inspection', 'dupro', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Inspection_DUPRO');
				break;

			//Initial Production Inspection
			case "ipc":
				$body .= $this->getSiteCatalystCode('inspection', 'ipc', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Inspection_IPC');
				break;

			//Container Loading Check
			case "clc":
				$body .= $this->getSiteCatalystCode('inspection', 'clc', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Inspection_CLC');
				break;

			// Food Inspections
			case "fpsi":
				$body .= $this->getSiteCatalystCode('inspection', 'food-inspections', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Inspection_FPSI');
				break;

			//Manufacturing Audit
			case "manufacturing-audit":
				$body .= $this->getSiteCatalystCode('audit', 'fa', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Audit_MA');
				break;

			//Ethical Audit
			case "ethical-audit":
				$body .= $this->getSiteCatalystCode('audit', 'sa', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Audit_EA');
				break;

			//CTPAT
			case "ctpat":
				$body .= $this->getSiteCatalystCode('audit', 'ctpat', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Audit_CTPAT');
				break;

			//Environmental Audit
			case "environmental-audit":
				$body .= $this->getSiteCatalystCode('audit', 'environmental', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Audit_ENVIRO');
				break;

			// Food Audit
			case "food-audit":
				$body .= $this->getSiteCatalystCode('audit', 'food', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Audit_FOOD');
				break;

			// Food Hygiene Audit
			case "ghp":
				$body .= $this->getSiteCatalystCode('audit', 'ghp', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Audit_GHP');
				break;

			// Food Good Manufacturing Practice Audit
			case "gmp":
				$body .= $this->getSiteCatalystCode('audit', 'gmp', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Audit_GMP');
				break;

			//Lab Testing
			case "lab-testing":
				$body .= $this->getSiteCatalystCode('lt', 'lt', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Lab_TESTING');
				break;

			// Food Testing
			case "food-testing":
				$body .= $this->getSiteCatalystCode('lt', 'food', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Lab_FOOD');
				break;

			//CPSIA
			case "cpsia":
				$body .= $this->getSiteCatalystCode('lt', 'cpsia', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Lab_CPSIA');
				break;

			//REACH
			case "reach":
				$body .= $this->getSiteCatalystCode('lt', 'reach', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Lab_REACH');
				break;

			//ROHS
			case "rohs":
				$body .= $this->getSiteCatalystCode('lt', 'rohs', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Lab_ROHS');
				break;

			//Lab Reports Verification (CHB)
			case "chb-lab-reports":
				$body .= $this->getSiteCatalystCode('lt', 'reports', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Pricing
			case "pricing":
				$body .= $this->getSiteCatalystCode('about', 'pricing', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Service Coverage
			case "service-coverage":
				$body .= $this->getSiteCatalystCode('about', 'our network', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Reference Samples
			case "reference-samples":
				$body .= $this->getSiteCatalystCode('about', 'reference samples', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Softlines
			case "softline":
				$body .= $this->getSiteCatalystCode('industry', 'softlines', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Industry_SOFTLINES');
				break;

			//Hardlines
			case "hardline":
				$body .= $this->getSiteCatalystCode('industry', 'hardlines', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Industry_HARDLINES');
				break;

			//Technical Parts
			case "techparts":
				$body .= $this->getSiteCatalystCode('industry', 'technical-parts', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Industry_TECHNICAL');
				break;

			// Food
			case "food":
				$body .= $this->getSiteCatalystCode('industry', 'food', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Industry_FOOD');
				break;

			//Industry Landing
			case "industry-landing":
				$body .= $this->getSiteCatalystCode('industry', 'category', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page);
				break;

			//Who We Are
			case "who-we-are":
				$body .= $this->getSiteCatalystCode('about', 'who we are', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Our 8 Values
			case "values":
				$body .= $this->getSiteCatalystCode('about', 'company values', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Accreditations
			case "accreditations":
				$body .= $this->getSiteCatalystCode('about', 'accreditation', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Testimonials
			case "testimonials":
				$body .= $this->getSiteCatalystCode('about', 'testimonials', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Our Partners
			case "partners":
				$body .= $this->getSiteCatalystCode('about', 'partners', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Main News Page
			case "news":
				$body .= $this->getSiteCatalystCode('news', 'news', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Mobile News Page
			case "mobile-news":
				$body .= $this->getSiteCatalystCode('news', 'mobile-news', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			// Mobile News Story
			case "mobile-press-release":
				$body .= $this->getSiteCatalystCode('news', 'mobile-news-story', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Press Releases
			case "press-release":
				//Special Cases [Start]
				if (strtolower($subTarget) == "modern_day_slavery") $subTarget = "2015-slavery";
				//Special Cases [End]
				$body .= $this->getSiteCatalystCode('news', $subTarget, '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Whitepaper Landing
			case "whitepaper-landing":
				$body .= $this->getSiteCatalystCode('whitepaper', 'landing', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Each Whitepaper
			case "whitepaper":
				$subTarget = strtolower($subTarget);
				//Special Cases [Start]
				if ($subTarget == "5-golden-rules-for-successful-qc") $subTarget = "5-golden-rules";
				//Special Cases [End]
				$body .= $this->getSiteCatalystCode('whitepaper', $subTarget, '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode("Content");
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				//Criteo Tracking
				if ($subTarget == "5-reasons-why-millennial-focused-brands-cannot-afford-to-ignore-social-responsibility") $body .= $this->getCriteoCode($page, 'White-Papers-Millennial');
				if ($subTarget == "modern-slavery") $body .= $this->getCriteoCode($page, 'White-Papers-Modern-Slavery');
				if ($subTarget == "ethical-supply-chains") $body .= $this->getCriteoCode($page, 'White-Papers-EA-Supply-Chains-Investment');
				if ($subTarget == "ethical-audits") $body .= $this->getCriteoCode($page, 'White_Papers_Ethical_Audits');
				break;

			//Sample Reports Landing
			case "sample-reports":
				$body .= $this->getSiteCatalystCode('samplereport', 'landing', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//AQL Page
			case "aql-acceptable-quality-limit":
				$body .= $this->getSiteCatalystCode('inspection', 'aql', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Dashboard Demo
			case "dashboarddemo":
				$body .= $this->getSiteCatalystCode('about', 'demo', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Footwear Page
			case "footwear":
				$body .= $this->getSiteCatalystCode('industry', 'softlines:footwear', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Industry_FOOTWEAR');
				break;

			//Garments and Apparel Page
			case "garments-apparel":
				$body .= $this->getSiteCatalystCode('industry', 'softlines:garments-apparel', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Industry_APPAREL');
				break;

			//Phthalate Testing Page
			case "phthalate-testing":
				$body .= $this->getSiteCatalystCode('lt', 'phthalate', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Bangladesh Landing Page
			case "quality-control-bangladesh":
				$body .= $this->getSiteCatalystCode('service', 'bangladesh', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Vietnam Landing Page
			case "quality-control-vietnam":
				$body .= $this->getSiteCatalystCode('service', 'vietnam', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//China Landing Page
			case "quality-control-china":
				$body .= $this->getSiteCatalystCode('service', 'qcinchina', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Structural Audits Page
			case "structural-audit":
				$body .= $this->getSiteCatalystCode('audit', 'structural-audits', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page, 'Audit_STRUCT');
				break;

			//Toy Safety Article Page
			case "toy-safety-laboratory-testing":
				$body .= $this->getSiteCatalystCode('lt', '2010-toy-safety', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//404 page
			case "404":
				$body .= $this->getSiteCatalystCode('404', '404', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//Registration Page
			case "registration":
				$body .= $this->getSiteCatalystCode('register', 'start', 'event1', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode("Register");
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page);
				break;

			//Job Application Success Page
			case "job-apply-success":
				$body .= $this->getSiteCatalystCode('career', 'job application', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;
	
			//Registration Success
			case "regsuccess":
				$body .= $this->getSiteCatalystCode('register', 'success', 'event2', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getAdobeTestandTargetCode("RegSuccess");
				$body .= $this->getGoogleCrossConversionCode("RegSuccess", $lang);
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleRemarketingCode("793ZCO3vlAQQi8Gj-wM", "1063837835");
				$body .= $this->getGoogleAdWordsCode("RegSuccess","O_EpCI22RhCLwaP7Aw", $lang, "1063837835");
				$body .= $this->getFacebookCode("RegSuccess");
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getBingCode();
				$body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				$body .= $this->getCriteoCode($page);
				break;

			//Conditions of Service
			case "conditions-of-service":
				$body .= $this->getSiteCatalystCode('about', 'conditions', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "jewelry":
				$body .= $this->getSiteCatalystCode('industry', 'hardlines:jewelry', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "electrical":
				$body .= $this->getSiteCatalystCode('industry', 'hardlines:electrical', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "toys":
				$body .= $this->getSiteCatalystCode('industry', 'hardlines:toys', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "eyewear":
				$body .= $this->getSiteCatalystCode('industry', 'hardlines:eyewear', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "spectacle-frames":
				$body .= $this->getSiteCatalystCode('industry', 'hardlines:eyewear:spectacle-frames', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "sunglasses":
				$body .= $this->getSiteCatalystCode('industry', 'hardlines:eyewear:sunglasses', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "optical-lenses":
				$body .= $this->getSiteCatalystCode('industry', 'hardlines:eyewear:optical-lenses', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "reading-glasses":
				$body .= $this->getSiteCatalystCode('industry', 'hardlines:eyewear:reading-glasses', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "eye-protectors":
				$body .= $this->getSiteCatalystCode('industry', 'hardlines:eyewear:eye-protectors', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "sports-eyewear-goggles":
				$body .= $this->getSiteCatalystCode('industry', 'hardlines:eyewear:sports-eyewear-goggles', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "cosmetics":
				$body .= $this->getSiteCatalystCode('industry', 'hardlines:cosmetics', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "promotional-products":
				$body .= $this->getSiteCatalystCode('industry', 'hardlines:promotional-products', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "food-containers":
				$body .= $this->getSiteCatalystCode('industry', 'food:food-containers', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "food-produce":
				$body .= $this->getSiteCatalystCode('industry', 'food:produce', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "seafood":
				$body .= $this->getSiteCatalystCode('industry', 'food:seafood', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "meat-poultry":
				$body .= $this->getSiteCatalystCode('industry', 'food:meat-poultry', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "inspectionservices":
				$body .= $this->getSiteCatalystCode('inspection', 'inspections', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "supplierauditprograms":
				$body .= $this->getSiteCatalystCode('audit', 'audits', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;
				
			case "beverages":
				$body .= $this->getSiteCatalystCode('industry', 'food:beverages', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "processed-food":
				$body .= $this->getSiteCatalystCode('industry', 'food:processed-food', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "textiles":
				$body .= $this->getSiteCatalystCode('industry', 'softlines:textiles', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "emailprefcenter":
				$body .= $this->getSiteCatalystCode('email', 'preferences', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "sasocert":
				$body .= $this->getSiteCatalystCode('inspection', 'saso', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "smetaaudit":
				$body .= $this->getSiteCatalystCode('audit', 'smeta', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "astmtoys":
				$body .= $this->getSiteCatalystCode('lt', 'toys-atsm', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "samprep-bangladesh-audit-report":
				$body .= $this->getSiteCatalystCode('samplereport', 'bangladesh-audit', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "samprep-ethical-audit-report":
				$body .= $this->getSiteCatalystCode('samplereport', 'ethical-audit', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "samprep-pre-shipment-inspection-report":
				$body .= $this->getSiteCatalystCode('samplereport', 'psi-report', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "samprep-factory-audit-report":
				$body .= $this->getSiteCatalystCode('samplereport', 'factory-audit', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "caliprop65":
				$body .= $this->getSiteCatalystCode('industry', 'hardlines:prop-65', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "labtestjanpromo":
				$body .= $this->getSiteCatalystCode('lt', 'january-labtest-promo', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "quality-control-mexico":
				$body .= $this->getSiteCatalystCode('service', 'mexico', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "regulatoryupdates":
				$body .= $this->getSiteCatalystCode('reg-recap', '', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "qualityassurance":
				$body .= $this->getSiteCatalystCode('lt', 'quality-assurance-services', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "berries":
				$body .= $this->getSiteCatalystCode('industry', 'food:berries', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;				

			case "360labs":
				$body .= $this->getSiteCatalystCode('lt', 'see-our-labs', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "clientlanding":
				$body .= $this->getSiteCatalystCode('home', 'clients', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "iloinfographic":
				$body .= $this->getSiteCatalystCode('whitepaper', 'modern-slavery-infographic', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "regulatory-updates":
				$body .= $this->getSiteCatalystCode('reg-recap', 'regulatory-updates', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "regulatory-update":
				$body .= $this->getSiteCatalystCode('reg-recap', 'regulatory-update', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "approve-reject-reports":
				$body .= $this->getSiteCatalystCode('service', 'approve-reject-reports', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "page-not-found":
				$body .= $this->getSiteCatalystCode('404', 'page-not-found', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "mobile-regulatory-update":
				$body .= $this->getSiteCatalystCode('reg-recap', 'mobile-regulatory-update', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "survey-thanks":
				$body .= $this->getSiteCatalystCode('service', 'survey-thanks', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "affiliate-getstarted":
				$body .= $this->getSiteCatalystCode('affiliate', 'affiliate-getstarted', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "caliprop65":
				$body .= $this->getSiteCatalystCode('audit', 'caliprop65', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "labtestlanding":
				$body .= $this->getSiteCatalystCode('lt', 'labtestlanding', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "emailprefcenter":
				$body .= $this->getSiteCatalystCode('home', 'emailprefcenter', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "previo-en-origen":
				$body .= $this->getSiteCatalystCode('inspection', 'previo-en-origen', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "comptuesday":
				$body .= $this->getSiteCatalystCode('dit', 'comptuesday', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "tapwater":
				$body .= $this->getSiteCatalystCode('audit', 'tapwater', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "qca-member":
				$body .= $this->getSiteCatalystCode('service', 'qca-member', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "fun-photos":
				$body .= $this->getSiteCatalystCode('home', 'fun-photos', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "aiecc":
				$body .= $this->getSiteCatalystCode('audit', 'aiecc', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			case "tradeprotectionforum2017":
				$body .= $this->getSiteCatalystCode('audit', 'tradeprotectionforum2017', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				break;

			//For pages with no specific tracking set (Global tracking and SiteCatalyst defaults)
			default:
				$body .= $this->getSiteCatalystCode('', '', '', $lang);
				$body .= $this->getMarketoCode();
				$body .= $this->getTwitterConversionCode();
				$body .= $this->getGoogleAnalyticsCode($this->TrackingData[strtoupper($lang)]["GoogleAnalytics"]);
				$body .= $this->getGoogleTagManagerCode();
				$head .= $this->getGoogleTagManagerHeadCode();
				$body .= $this->getFacebookCode();
				$body .= $this->getGoogleRemarketingCode("sMjECI3slAQQi8Gj-wM", "1063837835");
				if($lang == "cn") $body .= $this->getBaiduCode();
				$body .= $this->getLinkedInRetargetingCode();
				// Save to a file for any calls to tracking that wasn't caught in the above case statements
				if (PHP_OS == "WINNT") { file_put_contents("C:/AI_Unanswered_Tracking_Calls.txt", "[".date("Y-m-d")."] $page\n", FILE_APPEND); } else if (PHP_OS == "Linux") { file_put_contents("/var/AI_Unanswered_Tracking_Calls.txt", "[".date("Y-m-d")."] $page\n", FILE_APPEND); }
		}

		if( !in_array($_SERVER['HTTP_HOST'], $allowedDomains) ) {
			return array("head" => "<!--\n".htmlentities($head)."\n-->", "body" => '<pre class="trackingOutputBox">'.htmlentities($body).'</pre>');
		} else {
			return array("head" => $head, "body" => $body);
		}
		
	} //End of 'getTrackingCode' Function


} //End of 'Tracking' Class

?>
