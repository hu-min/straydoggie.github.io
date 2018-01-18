<?php
// by straydoggie
include_once '../../../common/basis.php';
$type = $_POST ['type'];
$user = $_POST ['user'];
$guid = $_POST ['guid'];
$brief = $_POST ['brief'];
$detail =$_POST ['detail'];
$time = date('y-m-d H:i:s',time());
$guids = $_POST ['guids'];
$user = trim($user, "\xEF\xBB\xBF");
echo header("Access-Control-Allow-Origin:*");
switch ($type){
	case 'status_save':
		status_save($user,$guid,$brief,$detail,$time);
		break;
	case 'result_save':
		result_save($user,$guid,$brief,$detail,$time);
		break;
	case 'status_load':
		status_load($user);
		break;
	case 'result_load':
		result_load($guid);
		break;
	case 'result_list':
		result_list($user);
		break;
	case 'status_delete':
		status_delete($user);
		break;
	case 'result_delete':
		result_delete($guid);
		break;
	case 'fav_save':
		fav_save($user,$time,$detail);
		break;
	case 'fav_load':
		fav_load($user);
		break;
	case 'fav_load_content':
		fav_load_content($user);
		break;
	default:
		echo '';
}

function fav_load_content($user) {
	$sql="SELECT * FROM lib_userdata WHERE user = '{$user}' AND type = 'fav'";
	$result = db_exec ($sql);
	$source_array;
	$content='';
	while ( $row = mysqli_fetch_array ( $result ) ) {
		$source_array = explode(',',$row ['detail']);
	}
	$sql = "SELECT * FROM lib_info WHERE id = 0";
	$result = $result = db_exec ($sql);
	$infostr='';
	while ( $row = mysqli_fetch_array ( $result ) ) {
		$infostr = explode(';',$row ['content']);
	}
	for($index=0;$index<count($source_array);$index++) {
		$tmpdata = explode('-',$source_array[$index]);
		$chapter = $tmpdata[0];
		$section = $tmpdata[1];
		$number = $tmpdata[2];
		$title='';
		for($idx=0;$idx<count($infostr);$idx++){
			$tmpstr=explode(',',$infostr[$idx]);
			if($tmpstr[1]==$chapter && $tmpstr[2]==$section){
				$title=$tmpstr[0];
			}
		}
		$content.=$chapter.','.$section.','.$title.'#'.get_content($chapter,$section,$number);
	}
	echo $content;
}
function fav_save($user,$time,$detail) {
	$guid=guid();
	$sql="DELETE FROM lib_userdata WHERE user = '{$user}' AND type = 'fav'";
	db_exec($sql);
	$sql = "INSERT INTO lib_userdata VALUES ('{$guid}','{$user}','{$time}','fav','','{$detail}')";
	db_exec($sql);
}
function fav_load($user){
	$sql="SELECT * FROM lib_userdata WHERE user = '{$user}' AND type = 'fav'";
	$str='';
	$result = db_exec($sql);
	while ( $row = mysqli_fetch_array ( $result ) ) {
		$str = $row ['detail'];
	}
	mysql_close($link);
	echo $str;
}
function result_delete($guid) {
	$sql="DELETE FROM lib_userdata WHERE guid = '{$guid}' AND type = 'result'";
	db_exec($sql);
}
function result_list($user) {
	$sql="SELECT * FROM lib_userdata WHERE user = '{$user}' AND type = 'result' ORDER BY time DESC";
	$result = db_exec($sql);
	$brief_str="";
	while ( $row = mysqli_fetch_array ( $result ) ) {
		$result_guid = $row ['guid'];
		$result_brief = explode(',',$row ['brief']);
		$result_title = $result_brief[0];
		$result_counting = $result_brief[1];
		$result_time = $row ['time'];
		$brief_str.=$result_guid.','.$result_title.','.$result_counting.','.$result_time.';';
	}
	echo $brief_str;
}
function result_load($guid) {
	$chapter = '';
	$section = '';
	$content = '';
	$errans = '';
	$sql="SELECT * FROM lib_userdata WHERE guid = '{$guid}' AND type = 'result'";
	$result = db_exec($sql);
	$arr;
	while ( $row = mysqli_fetch_array ( $result ) ) {
		$arr=explode(';',$row ['detail']);
	}
	for($index=0;$index<count($arr);$index++){
		$tmp_data=explode('-',$arr[$index]);
		$chapter=$tmp_data[0];
		$section=$tmp_data[1];
		$number=$tmp_data[2];
		$errans.=$tmp_data[3].',';
		$content.=get_content($chapter,$section,$number);
	}
	echo $errans.'#'.$content.'#'.$chapter.'#'.$section;
}
function result_save($user,$guid,$brief,$detail,$time) {
	$sql="DELETE FROM lib_userdata WHERE user = '{$user}' AND type = 'status'";
	db_exec($sql);
	$sql = "INSERT INTO lib_userdata VALUES ('{$guid}','{$user}','{$time}','result','{$brief}','{$detail}')";
	db_exec($sql);
}
function status_delete($user) {
	$sql="DELETE FROM lib_userdata WHERE user = '{$user}' AND type = 'status'";
	db_exec($sql);
}
function status_save($user,$guid,$brief,$detail,$time) {
	$sql="DELETE FROM lib_userdata WHERE user = '{$user}' AND type = 'status'";
	db_exec($sql);
	$sql = "INSERT INTO lib_userdata VALUES ('{$guid}','{$user}','{$time}','status','{$brief}','{$detail}')";
	db_exec($sql);
}
function status_load($user) {
	$status_guid = '';
	$status_brief = array();
	$status_title = '';
	$status_counting = '';
	$status_pointer = '';
	$status_detail = array();
	$status_array_answer = '';
	$status_array_string = '';
	$status_list = '';
	$sql="SELECT * FROM lib_userdata WHERE user = '{$user}' AND type = 'status'";
	$result = db_exec($sql);
	while ( $row = mysqli_fetch_array ( $result ) ) {
		$status_guid = $row ['guid'];
		$status_brief = explode(';',$row ['brief']);
		$status_title = $status_brief[0];
		$status_counting = $status_brief[1];
		$status_pointer = $status_brief[2];
		$status_detail =  explode(';',$row ['detail']);
	}
	for($index=0;$index<$status_counting;$index++){
		$str = explode('-',$status_detail[$index]);
		$chapter = $str[0];
		$section = $str[1];
		$number = $str[2];
		$status_array_answer.=$str[3].',';
		$status_array_string.=get_content($chapter,$section,$number);
	}
	echo 
	$status_guid.'#'.$status_title.'#'.$status_counting.'#'.$status_pointer.'#'.
	$chapter.'#'.$section.'#'.$status_array_answer.'#'.$status_array_string;
}

function get_content($c,$s,$n){
	$output='';
	$sql = "SELECT * FROM lib_atclicence where chapter = {$c} and section = {$s} and number = {$n}";
	$result = db_exec($sql);
	while ( $row = mysqli_fetch_array ( $result ) ) {
		$data = "";
		$data .= ltrim(rtrim ($row ['number'],"$"),"$") . ",";
		$data .= ltrim(rtrim ($row ['question'],"$"),"$") . ",";
		$data .= ltrim(rtrim ($row ['option-a'],"$"),"$") . ",";
		$data .= ltrim(rtrim ($row ['option-b'],"$"),"$") . ",";
		$data .= ltrim(rtrim ($row ['option-c'],"$"),"$") . ",";
		$data .= ltrim(rtrim ($row ['option-d'],"$"),"$") . ",";
		$data .= ltrim(rtrim ($row ['answer'],"$"),"$") . ";";
		$output = $data;
	}
	return $output;
}
?>