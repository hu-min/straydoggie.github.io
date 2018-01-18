<?php

// by straydoggie
$code = $_GET ['code'];
$url="http://192.168.1.14/cdapp/functions/common/wx_access.php?mathod=verify_user&code={$code}";
$user = file_get_contents($url);
$user=clearbom($user);

if($user){
	$url="http://192.168.1.14/cdapp/functions/hxsx/php/receive.php?type=user&data={$user}";
	$userinfo = file_get_contents($url);
	if($userinfo){
		$url2="http://192.168.1.14/cdapp/functions/hxsx/html/test.html?info={$userinfo}";
		Header("HTTP/1.1 303 See Other"); 
		Header("Location: $url2"); 
		exit;
	}else{
		echo "读取用户信息失败！";
	}
}else{
	echo "非法访问！";
}
function clearbom($contents){    
    //UTF8 去掉文本中的 bom头
    $BOM = chr(239).chr(187).chr(191); 
    return str_replace($BOM,'',$contents);    
}
function logstr2file($str) {
	$file = fopen ( "./datalog.txt", "a" );
	fwrite ( $file, "\r\n[".date("Y-m-d H:i:s")."]". $str);
	fclose ( $file );
}
?>