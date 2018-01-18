<?php
// by straydoggie
include_once "basis.php";

$cropid = "wx29136ddcd832c0b5";
$corpsecret = "RzwWac4xZKwp9aDBYD-eJr2PBTltgEfv6iYDR2-kUQVd4qZ3ZQ1dykHWacVfnAUa";
$cache_path = "./access_token.txt";

$mathod=php_get('mathod');
$code=php_get('code');
$msg_data=php_get('msg_data');

if ($mathod == "get_token") {
	$token = get_token();
	journal($mathod,$token);
	echo $token;
}
else if ($mathod == "verify_user"){
	$user_id = user_verify($code);
	journal($mathod,"user={$user_id}\r\ncode={$code}");
	echo $user_id;
}
else if ($mathod == "send_msg"){
	$arr=explode(',',$msg_data);
	$usr=$arr[0];
	$app=$arr[1];
	$msg=$arr[2];
	$send_result = send_message($usr,$app,$msg);
	journal($mathod,"result={$send_result}\r\nmsg_data={$msg_data}");
	echo $send_result;
}


function get_token() {
	global $cropid;
	global $corpsecret;
	global $cache_path;
	global $access_token;
	//https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=wx29136ddcd832c0b5&corpsecret=RzwWac4xZKwp9aDBYD-eJr2PBTltgEfv6iYDR2-kUQVd4qZ3ZQ1dykHWacVfnAUa
	$file_accesstoken_r = fopen ( $cache_path, "r" );
	$token_str = fread ( $file_accesstoken_r, 1023 );
	fclose ( $file_accesstoken_r );
	$token_arr = explode ( ",", $token_str );
	$token = $token_arr [1];
	$prevtime = time () - $token_arr [0];
	if ($prevtime >= 3600 || $token=="") {
		$url_accesstoken = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid={$cropid}&corpsecret={$corpsecret}";
		$res = file_get_contents ( $url_accesstoken );
		$res_decoded = json_decode ( $res, true );
		$token = $res_decoded ["access_token"];
		$file_accesstoken_w = fopen ( $cache_path, "w" );
		$token_str = time () . "," . $token;
		fwrite ( $file_accesstoken_w, $token_str );
		fclose ( $file_accesstoken_w );
	}
	$access_token = $token;
	return $token;
}

function user_verify($code) {
	$token = get_token();
	$url_userid = "https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token={$token}&code={$code}";
	journal("user_verify_url",$url_userid);
	$res = file_get_contents ( $url_userid );
	journal("user_verify_res",$res);
	$res_decoded = json_decode ( $res, true );
	$uid = $res_decoded ["UserId"];
	$uid = trim($uid, "\xEF\xBB\xBF");
	return $uid;
}

function send_message($users,$appid,$msg) {  
	$token = 	get_token();
	$url_post="https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token={$token}";
	$postdata = '{"touser": "'.$users.'","msgtype": "text","agentid": '.$appid.',"text": {"content": "'.$msg.'"},"safe":0}';
	$options = array(  
		'http' => array(  
			'method' => 'POST',  
			'header' => 'Content-type:application/x-www-form-urlencoded',  
			'content' => $postdata,  
			'timeout' => 15 * 60 // 超时时间（单位:s）  
			)
		); 
	$context = stream_context_create($options);  
	$result = file_get_contents($url_post, false, $context); 
	return $result;  
}  

function php_get($str){ 
$val = !empty($_GET[$str]) ? $_GET[$str] : null; 
return $val; 
} 

?>