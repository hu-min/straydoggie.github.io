<?php
// by straydoggie

//
$dbhost = "localhost";
$dbusr = "cdapp";
$dbpwd = "gzzxjjwt1";
include_once "../../common/basis.php";

//
$data = $_GET ['data'];
$type = $_GET ['type'];
log_in_file_init();
//echo $data;
if ($type == 'apply') {
	save_application($data);

}
elseif ($type == 'user') {
	echo get_user_info($data);
}elseif ($type == 'username') {
	echo get_user_name();
}elseif ($type == 'get') {
	echo get_content_by_guid($data);
}elseif ($type == 'recent_guid') {
	echo get_recent_guid_by_user($data);
}elseif ($type == 'recent_content') {
	echo get_recent_content_by_user($data);
}elseif ($type == 'recent_brief') {
	echo get_recent_brief_by_user($data);
}else{
	//
	exit;
}


function log_in_file_init() {
	global $data;
	global $type;
	$file = fopen ( "./datalog.txt", "a" );
	fwrite ( $file, "\r\n[".date("Y-m-d H:i:s")."][reveive.php][" .$type."]". $data);
	fclose ( $file );
}
function log_in_file($str) {
	$file = fopen ( "./datalog.txt", "a" );
	fwrite ( $file, "\r\n[".date("Y-m-d H:i:s")."][reveive.php]". $str);
	fclose ( $file );
}

function get_user_info($user) {
	$link=connect_db();
	if($link){
		mysql_query("set character set 'utf8'");//读库 
		$sql="SELECT * FROM `userinfo` WHERE user = '{$user}'";
		//log_in_file($sql);
		$result = mysql_query ( $sql, $link );
		$data = "";
		while ( $row = mysql_fetch_array ( $result ) ) {
			$data .= $user;
			$data .= ",";
			$data .= $row ['name'];
			$data .= ",";
			$data .= $row ['id_cert'];
			$data .= ",";
			$data .= $row ['id_crop'];
			$data .= ",";
			$data .= $row ['telephone'];
		}
		mysql_close($link);
		log_in_file("[info]".$data);
		return $data;
	}
	else{
		mysql_close($link);
		return null;
	}
}

function get_user_name() {
	global $data;
	$link=connect_db();
	if($link){
		mysql_query("set character set 'utf8'");//读库 
		//$sql="SELECT * FROM `userinfo` WHERE user = 'straydoggie'";
		$sql="SELECT * FROM `userinfo` WHERE user = '{$data}'";
		//$sql="SELECT * FROM `userinfo` WHERE `user` = `{$data}`";
		log_in_file($sql);
		$result = mysql_query ( $sql, $link );
		$data = "";
		while ( $row = mysql_fetch_array ( $result ) ) {
			$data .= $row ['name'];
		}
		mysql_close($link);
		log_in_file($data);
		return $data;
	}
	else{
		mysql_close($link);

		return null;
	}
}
/*
function tmp(){
	$link=connect_db();
	$sql = "INSERT INTO userinfo (user, name, id_cert, id_crop, tel) VALUES ('straydoggie','王涛','64020219891003009X','2012036','18628205151')";
	if(mysql_query($sql,$link)){echo "ok";}else{echo $sql;}
}
*/
function get_content_by_guid($id) {
	$data="";
	$link=connect_db();
		if($link){
		mysql_query ( "set character set 'utf8'" );
		$sql="SELECT * FROM hxsx_applications where guid = '{$id}'";
		$result = mysql_query ( $sql, $link );
		while ( $row = mysql_fetch_array ( $result ) ) {
			//print_r($row)."\r\n";
			for($i=1;$i<=15;$i++){
				$data.=$row[$i].",";
			}
		}
	}
	$data = rtrim($data,",");
	mysql_close($link);
	return $data;
}
function get_recent_guid_by_user($user){
	return get_recent_by_user($user,1);
}
function get_recent_content_by_user($user){
	return get_recent_by_user($user,2);
}
function get_recent_brief_by_user($user){
	return get_recent_by_user($user,3);
}
function get_recent_by_user($user,$t){
	$month_now=date("m");
	$year_now=date("Y");
	$link=connect_db();
	$sql="";
	$date1_str="";
	$date2_str="";
	$guids="";
	$contents="";
	$brief="";
	if($month_now<=3){
		$date1_str=$year_now."-01-01";
		$date2_str=$year_now."-03-31";
	}elseif($month_now<=6){
		$date1_str=$year_now."-04-01";
		$date2_str=$year_now."-06-30";
	}elseif($month_now<=9){
		$date1_str=$year_now."-07-01";
		$date2_str=$year_now."-09-30";
	}else{
		$date1_str=$year_now."-10-01";
		$date2_str=$year_now."-12-31";
	}
	$sql = "SELECT * FROM hxsx_applications WHERE date1 >= '{$date1_str}' AND date1 <= '{$date2_str}' AND user = '{$user}' AND status = 9999";
	//$sql = "SELECT * FROM hxsx_applications WHERE date1 >= '{$date1_str}' AND date1 <= '{$date2_str}' AND user = '{$user}'";
	if($link){
		$result = mysql_query ( $sql, $link );
		while ( $row = mysql_fetch_array ( $result ) ) {
			$guids.=$row[0].",";
			for($i=1;$i<=15;$i++){
				$contents.=$row[$i].",";
			}
			for($i=7;$i<=12;$i++){
				$brief.=$row[$i].",";
			}
			$contents=rtrim($contents,",");
			$contents.=";";
			$brief=rtrim($brief,",");
			$brief.=";";
			//print_r($row);
			//echo get_content_by_guid($row[0]);
			//echo "<br><br>";
		}
		$contents=rtrim($contents,";");
		$brief=rtrim($brief,";");
		$guids=rtrim($guids,",");
	}
	mysql_close($link);
	if($t==1){return $guids;}
	elseif($t==3){return $brief;}
	elseif($t==2){return $contents;}
	else{return "bad";}
}
function save_application($data) {
	$data=explode(',',$data); 
	//straydoggie,王涛,64020219891003009X,2012036,18628205151,2017-1-1,2017-1-7,成都,北京,上海,广州,0,
	$user=$data[0];
	$name=$data[1];
	$id_cert=$data[2];
	$id_crop=$data[3];
	$tel=$data[4];
	$date1=$data[5];
	$date2=$data[6];
	$dest1=safestr($data[7]);
	$dest2=safestr($data[8]);
	$dest3=safestr($data[9]);
	$dest4=safestr($data[10]);
	$type=$data[11];
	$text=safestr($data[12]);
	$timestamp=date("Y-m-d H:i:s");
	$guid=guid();
	$link=connect_db();
	if ($link==null) {
		echo 'error in conntcting database';
	}
	else {

		$sql = "INSERT INTO hxsx_applications (guid,time,user,name,id_cert,id_crop,tel,date1,date2,dest1,dest2,dest3,dest4,type,text,status) VALUES ('{$guid}','{$timestamp}','{$user}','{$name}','{$id_cert}','{$id_crop}','{$tel}','{$date1}','{$date2}','{$dest1}','{$dest2}','{$dest3}','{$dest4}',{$type},'{$text}',0)";
		
		//$result = mysql_query($sql,$link);
		if(mysql_query($sql,$link)){
			$url='http://192.168.1.14/cdapp/functions/hxsx/php/notify.php?guid='.$guid;
			$html = file_get_contents($url);
			log_in_file($html);
			echo $html;
			//echo 'ok!';
		}
		else{
			log_in_file($sql);
			echo $sql;
		}
		mysql_close($link);
		//echo $result;
	}
}

function connect_db() {
	global $dbhost;
	global $dbusr;
	global $dbpwd;
	$link = mysql_connect ( $dbhost, $dbusr, $dbpwd );
	mysql_query ( "set character set 'utf8'" ); // ����
	mysql_query ( "set names 'utf8'" ); // д��
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

function guid() {
    $charid = strtoupper(md5(uniqid(mt_rand(), true)));
    $hyphen = chr(45);// "-"
    $uuid = ""//chr(123) "{"
    .substr($charid, 0, 8).$hyphen
    .substr($charid, 8, 4).$hyphen
    .substr($charid,12, 4).$hyphen
    .substr($charid,16, 4).$hyphen
    .substr($charid,20,12)
    ."";//chr(125) "}"
    return $uuid;
}

function safestr($str_raw){
	$str = $str_raw;
	$str = str_replace(",","，",$str);
	$str = str_replace(";","；",$str);
	$str = str_replace(":","：",$str);
	$str = str_replace("/","、",$str);
	return $str;
}

?>