<?php
// by straydoggie

$user=$_GET["user"];
$content=$_GET["content"];
$tel="18628205151";
$address="straydoggie@163.com";
if($user=="" || $user==null){
	$user = "straydoggie";
}else{
	get_contact($user);
}
echo "send e-mail: ".send_email($address,$content);
echo "<br>";
echo "send wechat: ".send_wechat($user,$content);
echo "<br>";
echo "send phonesms: ".send_phonesms($tel,$content);

function get_contact($user){
	
}
function send_email($address,$content){
	$result = "fail";
	$url="http://192.168.1.14/cdapp/functions/hxsx/php/mail.php?address={$address}&content={$content}";
	$result=file_get_contents($url);
	return $result;
}
function send_wechat($user,$content){
	$result = "fail";
	$url="http://192.168.1.14/cdapp/functions/common/wx_access.php?mathod=send_msg&msg_data={$user};;;10;;;{$content}";
	$html = file_get_contents($url);
	$arr=json_decode($html, true);
	if($arr['errcode']==0){$result = "success";}
	return $result;
}
function send_phonesms($tel,$content){
	$result = "fail";
	return $result;
}
function connect_db() {
	global $dbhost;
	global $dbusr;
	global $dbpwd;
	$link = mysql_connect ( $dbhost, $dbusr, $dbpwd );
	mysql_query ( "set character set 'utf8'" ); //read
	mysql_query ( "set names 'utf8'" ); //write
	if (! $link) {
		print ("connect to mysql failed: " . mysql_error () . "\t(" . mysql_errno () . ")") ;
	} else {
		$selection = mysql_select_db ( "cdapp", $link );
		if (! $selection) {
			print ("select database failed: " . mysql_error () . "\t(" . mysql_errno () . ")") ;
		} else {
			return $link;
		}
	}
	return null;
}
?>
