<?php
//echo header("Access-Control-Allow-Origin:*");
$url='http://straydoggie.cn:81/cdapp/functions/dwxx/excs.htm';
$content=file_get_contents($url);
die($content);
?>