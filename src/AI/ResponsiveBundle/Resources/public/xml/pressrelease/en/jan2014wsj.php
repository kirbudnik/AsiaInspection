<h1>Asia Accidents Help Drive Up Factory Audits</h1>
<p style="display:none;">Read the article on the <a href="http://online.wsj.com/article/SB10001424052702304149404579321993799169598.html" target="_blank">Wall Street Journal</a></p>
<p>Read the article on the <a href="http://blogs.wsj.com/corporate-intelligence/2014/01/15/western-brands-rethink-low-cost-focus/" target="_blank">Wall Street Journal</a></p>

<div id="wsjContent_hide" style="display:none;">
<?php
  $url = "http://online.wsj.com/article/SB10001424052702304149404579321993799169598.html";
  $curl = curl_init();

  $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
  $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
  $header[] = "Cache-Control: max-age=0";
  $header[] = "Connection: keep-alive";
  $header[] = "Keep-Alive: 300";
  $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
  $header[] = "Accept-Language: en-us,en;q=0.5";

  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:7.0.1) Gecko/20100101 Firefox/7.0.12011-10-16 20:23:00");
  curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
  curl_setopt($curl, CURLOPT_REFERER, "http://news.google.com");
  curl_setopt($curl, CURLOPT_ENCODING, "gzip,deflate");
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_TIMEOUT, 30);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION,true);

  $html = curl_exec($curl);
  curl_close($curl);

  echo $html;
?>
</div>
<div id="wsjContent_show"></div>
<script type="text/javascript">
	contents = $("#articleBody","#wsjContent_hide").html();
	$("#wsjContent_show").html(contents);
	$(".article-chiclet").remove();
	$(".t-company").css("text-decoration","none").css("font-size","13px");
	$("#wsjContent_hide").remove();
</script>
