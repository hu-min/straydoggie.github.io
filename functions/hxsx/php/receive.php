<?php
// by straydoggie

include_once '../../common/basis.php';
include_once '../php/performance.php';

$data = $_GET ['data'];
$type = $_GET ['type'];

if ($type == 'save') {
    echo save($data);
} elseif ($type == 'user_info') {
    echo get_user_info($data);
} /*elseif ($type == 'user_name') {
	echo get_user_name();
}*/
elseif ($type == 'content') {
    echo get_content($data);
} /*elseif ($type == 'recent_guid') {
	echo recent_guid($data);
}elseif ($type == 'recent_content') {
	echo recent_content($data);
}*/
elseif ($type == 'recent_brief') {
    echo recent_brief($data);
} elseif ($type == 'update') {
    echo modify($data);
} else {
    exit;
}

function get_user_info($userid)
{
    $link = connect_db();
    if ($link) {
        mysql_query("set character set 'utf8'");//读库
        $sql = "SELECT * FROM userinfo WHERE user = '{$userid}'";
        $result = mysql_query($sql, $link);
        $data = $userid;
        if (mysql_num_rows($result) == 0) {
            mysql_close($link);
            return null;
        }
        while ($row = mysql_fetch_array($result)) {
            $data .= ',';
            $data .= $row ['name'];
            $data .= ',';
            $data .= $row ['id_cert'];
            $data .= ',';
            $data .= $row ['id_crop'];
            $data .= ',';
            $data .= $row ['telephone'];
        }
        mysql_close($link);
        journal('get_user_info', $data);
        return $data;
    } else {
        mysql_close($link);
        return null;
    }
}

function get_user_dep($userid)
{
	$dep = 0;
    $link = connect_db();
    $sql = "SELECT * FROM `userinfo` WHERE `user` = '{$userid}'";
    $result = mysql_query($sql, $link);
    while ($row = mysql_fetch_array($result)) {
        $dep = $row ['department'];
    }
    mysql_close($link);
    return $dep;
}

function get_user_name($userid)
{
    $link = connect_db();
    if ($link) {
        mysql_query("set character set 'utf8'");//读库
        $sql = "SELECT * FROM userinfo WHERE user = '{$userid}'";
        log_in_file($sql);
        $result = mysql_query($sql, $link);
        $data = '';
        while ($row = mysql_fetch_array($result)) {
            $data .= $row ['name'];
        }
        mysql_close($link);
        journal('get_user_name', $data);
        return $data;
    } else {
        mysql_close($link);
        return null;
    }
}

function get_content($id)
{
    global $table_name;
    $data = '';
    $link = connect_db();
    if ($link) {
        mysql_query("set character set 'utf8'");
        $sql = "SELECT * FROM $table_name where guid = '{$id}'";
        $result = mysql_query($sql, $link);
        while ($row = mysql_fetch_array($result)) {
            for ($i = 1; $i <= 15; $i++) {
                $data .= $row[$i] . ',';
            }
        }
    }
    $data = rtrim($data, ',');
    mysql_close($link);
    journal('get_content', $data);
    return $data;
}

/*
function recent_guid($user){
	$data = get_recent_by_user($user,1);
	journal('recent_guid',$data);
	return $data;
}
function recent_content($user){
	$data = get_recent_by_user($user,2);
	journal('recent_content',$data);
	return $data;
}
*/
function recent_brief($user)
{
    $data = get_recent_by_user($user, 3);
    //journal('recent_brief',$data);
    return $data;
}

function get_recent_by_user($user, $t)
{
    global $table_name;
    $month_now = date('m');
    $year_now = date('Y');
    $link = connect_db();
    $sql = '';
    $date1_str = '';
    $date2_str = '';
    $guids = '';
    $contents = '';
    $brief = '';
    if ($month_now <= 3) {
        $date1_str = $year_now . '-01-01';
        $date2_str = $year_now . '-03-31';
    } elseif ($month_now <= 6) {
        $date1_str = $year_now . '-04-01';
        $date2_str = $year_now . '-06-30';
    } elseif ($month_now <= 9) {
        $date1_str = $year_now . '-07-01';
        $date2_str = $year_now . '-09-30';
    } else {
        $date1_str = $year_now . '-10-01';
        $date2_str = $year_now . '-12-31';
    }
    $sql = "SELECT * FROM $table_name WHERE date1 >= '$date1_str' AND date1 <= '$date2_str' AND user = '$user' AND status = 9999";
    if ($link) {
        $result = mysql_query($sql, $link);
        while ($row = mysql_fetch_array($result)) {
            $guids .= $row[0] . ',';
            for ($i = 1; $i <= 15; $i++) {
                $contents .= $row[$i] . ',';
            }
            for ($i = 7; $i <= 12; $i++) {
                $brief .= $row[$i] . ',';
            }
            $contents = rtrim($contents, ',');
            $contents .= ';';
            $brief = rtrim($brief, ',');
            $brief .= ';';
            //print_r($row);
            //echo get_content($row[0]);
            //echo '<br><br>';
        }
        $contents = rtrim($contents, ';');
        $brief = rtrim($brief, ';');
        $guids = rtrim($guids, ',');
    }
    mysql_close($link);
    if ($t == 1) {
        return $guids;
    } elseif ($t == 3) {
        return $brief;
    } elseif ($t == 2) {
        return $contents;
    } else {
        return 'bad';
    }
}

function modify($data)
{
    $data_arr = explode('***', $data);
    if (count($data_arr) != 3) {
        journal('modify', "failed<{$data}>");
        return 'fail';
    }
    $guid = $data_arr[0];
    $status = $data_arr[1];
    $remark = safestr($data_arr[2]);
    $link = connect_db();
    $sql = "UPDATE hxsx_applications set text = '{$remark}', status = '{$status}' WHERE guid = '{$guid}'";
    //echo $sql;
    mysql_query($sql, $link);
    mysql_close($link);
    journal('modify', "success<{$data}>");
    return 'success';
}

function save($data)
{
    global $table_name;
    global $member_d1;
    journal('save', $data);
    $data = explode('***', $data);
    $user = $data[0];
    $name = $data[1];
    $id_cert = $data[2];
    $id_crop = $data[3];
    $tel = $data[4];
    $date1 = $data[5];
    $date2 = $data[6];
    $dest1 = safestr($data[7]);
    $dest2 = safestr($data[8]);
    $dest3 = safestr($data[9]);
    $dest4 = safestr($data[10]);
    $type = $data[11];
    $text = safestr($data[12]);
    if (strlen($text) == 0) {$text = '无';}
    $timestamp = date("Y-m-d H:i:s");
    $guid = guid();
    $state = get_user_dep($user)*10;
    $sql = "INSERT INTO `hxsx_applications` (`guid`,`time`,`user`,`name`,`id_cert`,`id_crop`,`tel`,`date1`,`date2`,`dest1`,`dest2`,`dest3`,`dest4`,`type`,`text`,`status`) VALUES ('{$guid}','{$timestamp}','{$user}','{$name}','{$id_cert}','{$id_crop}','{$tel}','{$date1}','{$date2}','{$dest1}','{$dest2}','{$dest3}','{$dest4}',{$type},'{$text}',{$state})";
	$link = connect_db();
	if (mysql_query($sql,$link)) {
        $url = "http://192.168.1.14/cdapp/functions/hxsx/php/notify.php?guid={$guid}&member={$user}&model=submited";
        file_get_contents($url);
        $url = "http://192.168.1.14/cdapp/functions/hxsx/php/notify.php?guid={$guid}&member={$member_d1}&model=proceed";
        file_get_contents($url);
        $result = 'ok';
    } else {
        journal('save', 'failed:'.mysql_error().mysql_errno());
        journal('save', addslashes($sql));
		$result = '';
    }
    mysql_close($link);
    return $result;
}

function safestr($str_raw)
{
    $str = $str_raw;
    $str = str_replace(',', '，', $str);
    $str = str_replace(';', '；', $str);
    $str = str_replace(':', '：', $str);
    $str = str_replace('/', '、', $str);
    //$str = str_replace('\\','、',$str);
    return $str;
}

?>