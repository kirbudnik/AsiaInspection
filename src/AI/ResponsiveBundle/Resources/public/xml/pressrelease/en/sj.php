<p style="float:left; width:330px; margin:0px 0px 20px 0px;">This content is brought to you by<br /><a href="<?php echo "https://www.sourcingjournalonline.com/" .$_GET['url']."/"; ?>" target="_blank">The Sourcing Journal Online</a></p>
<div style="clear:both;"></div>
<div id="sjContent" style="display:none;">
<?php
	require("libs/simple_html_dom.php");
	$username="michael.mesarch@asiainspection.com";
	$password="sj1234";
	$url = "https://www.sourcingjournalonline.com/" .$_GET['url']."/";
	$cookie="cookie.txt";
	$postdata = "log=". $username ."&pwd=". $password ."&wp-submit=Log%20In&testcookie=1";
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $url);
	
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
	curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookie);
	curl_setopt ($ch, CURLOPT_REFERER, $url);
	
	curl_setopt ($ch, CURLOPT_POSTFIELDS, $postdata);
	curl_setopt ($ch, CURLOPT_POST, 1);
	$result = curl_exec ($ch);
	curl_close($ch);
	
	$dom = str_get_html($result);
	$content = $dom->find('#content');
	echo $content[0];
?>
</div>

<script type="text/javascript">
	$(".printfriendly, .breadcrumb, .dd_post_share, .related-posts, .post-meta","#sjContent").remove();
	$("#sjContent").show();
</script>
