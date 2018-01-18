<?php
// by straydoggie
include_once '../../common/basis.php';

$type = $_GET[ 'type' ];
$event = $_GET[ 'event' ];
$key = $_GET[ 'key' ];
$text = $_GET[ 'text' ];

/*
journal('type',$type);
journal('event',$event);
journal('key',$key);
journal('text',$text);
*/

if ( $type == '' or $type == null ) {
    echo 'Internal Error.../(ㄒoㄒ)/~~';
} elseif ( $type == 'text' ) {
	echo msg_text($text);
} elseif ( $type == 'event' ) {
    echo msg_event($event, $key);
}
else {
    echo 'Internal Error.../(ㄒoㄒ)/~~';
}

function msg_event($event,$key){

    if($key=='help'){return msg_help();}
    elseif($key=='fulltable'){return msg_table();}
}

function msg_text($text){
    $note=
        "\n\n检索结果仅供参考，请以OA或排班群发布为准；".
        "\n\n数据由排班表自动生成，排班表中的错别字会影响到检索结果；".
        "\n\n现已可以查询每日休息人员，方法见使用说明。";
    $note1="\n\n数据由排班表自动生成，排班表中的错别字会影响到检索结果。";
    if(!intime_check()){return '数据已过期';}
    elseif($reply=search_today($text)){return $reply.$note;}
	elseif($reply=search_anyday($text)){return $reply.$note;}
	elseif($reply=search_name($text)){return $reply.$note;}
	else{return '什么也没有找到~~~'.$note1;}
}

function search_today( $msg )
{
    $date = date("Y-m-d");
    $task = strtoupper($msg);
    if ($msg == '休') {
        $reply = search_idle($date);
    } else {
        $reply = search_busy($date, $task);
    }
    return $reply;
}

function search_anyday($msg)
{
    $date = substr($msg, 0, 4) . '-' . substr($msg, 4, 2) . '-' . substr($msg, 6, 2);
    $task = strtoupper(substr($msg, 8, strlen($msg) - 8));
    if (strlen($task) == 0) {
        $reply = null;
    }elseif ($task == '休') {
        $reply = search_idle($date);
    } else {
        $reply = search_busy($date, $task);
    }
    return $reply;
}

function search_name($msg)
{
    $date = date("Y-m-d");
    $sql = "SELECT * FROM pbcx WHERE name = '{$msg}' AND date >= '{$date}' ORDER BY date ASC";
    $result = db_exec($sql);
    $search = '';
    while ($row = mysqli_fetch_array($result)) {
        $arr = explode('-', $row['date']);
        $day = ltrim($arr[1], '0') . '月' . ltrim($arr[2], '0') . '日 ';
        $search .= "\n" . $day . $row['task'];
        if ($row['ext']) {
            $search .= '（' . $row['ext'] . '）';
        }
    }
    if (strlen($search) > 0) {
        $reply = "查找到{$msg}近期的排班：\n{$search}";
        return $reply;
    }
    return null;
}

function search_busy($date,$task)
{

    //journal('search_busy', $date . ' & ' . $task);
    $link = connect_db();
    $sql = "SELECT * FROM pbcx WHERE date = '{$date}' AND task LIKE '{$task}%'";
    $result = db_exec($sql);
    $search = '';
    while ($row = mysqli_fetch_array($result)) {
        $search .= "\n" . triwords_name($row['name']).' '.$row['task'];
        if ($row['ext']) {
            $search .= '（' . $row['ext'] . '）';
        }
    }
    if (strlen($search) > 0) {
        $day = ltrim(explode('-', $date)[1], '0') . '月' . ltrim(explode('-', $date)[2], '0') . '日';
        $reply = "查找到{$day}上{$task}的人员：\n{$search}";
        //echo $reply;
        return $reply;
    }
    return null;
}

function search_idle($date)
{
    $names_str = '周阳俊,李睿杰,肖芳静,黄中,费涛,苟翔,胡鹏,郑皓天,邝从举,黄建明,郭爱华,吕腾,宋源,陈华宽,邹子君,周杨,熊谭龙,张霁炜,赖鹏,杨乐,周海伦,徐文,钟华超,高曼宇,钱坤,楚捷骢,蒲滔,邹凯,何飞,王振翮,蔡成果,廉宇澄,郭霖,贺茂硕,李竞博,靳荆,王怡然,李卓然,吴量,殷茂鑫,李智楠,李阳,李富海,贾子峰,刘锐,张添伦,陈欣然,吴昊,陈航,彭涛,李剑,刘畅,刘子可,赵英男,吴继柯,薛宏伟,许湛,张丽丹,王子龙,刘振宇,周游,蒋毅,骆彦铭,茆以乐,王绍楠,胡超,周行远,刘丹,杨涛,袁福栋,蒲翔宇,王涛,许扬,蒋垄,姚昕,袁柯,林桦,田原,刘超,林彦州,王铁良,周佳凝,陈虹宇';
    $names = explode(',', $names_str);
    $sql = "SELECT * FROM pbcx WHERE date = '{$date}'";
    $result = db_exec($sql);
    $search = array();
    while ($row = mysqli_fetch_array($result)) {
        $search[] = $row['name'];
    }
    $idle = array();
    foreach ($names as $a) {
        $ex = false;
        foreach ($search as $b) {
            if ($a == $b) {
                $ex=true;
                break;
            }
        }
        if(!$ex){$idle []= $a;}
    }
    if(count($idle) > 0) {
        $day = ltrim(explode('-', $date)[1], '0') . '月' . ltrim(explode('-', $date)[2], '0') . '日';
        $idle_str='';
        for($i=0;$i<count($idle);$i++)
        {
            if(($i+1)%3==0){
                $idle_str.=triwords_name($idle[$i])."\n";
            }else{
                $idle_str.=triwords_name($idle[$i])."、";
            }
        }
        $idle_str=rtrim($idle_str,'、');
        $idle_str=rtrim($idle_str,"\n");
        $idle="查找到{$day}不上班的人员：\n\n{$idle_str}";
        return $idle;
    }
    return null;
}

function msg_help(){
    $help_msg = "【使用说明】\n\n".
        "1、输入姓名，可查询今日及以后的值班安排；\n\n".
        "2、输入执勤时段（A～G）或“休”，可查询今日执该时段或不上班的人员；\n\n".
        "3、输入八位日期+时段，可查询指定日期上班的人员；\n\n".
        "例如：输入“20160101A”可查询2016年1月1日执A时段的人员，输入“20170301”可查询当日不上班的人员。";
    return $help_msg;
}
function msg_table() {
    $today_str = date ( "Y-m-d" );
    $sql = "SELECT * FROM pbtable WHERE exp_date >= '{$today_str}' ORDER BY exp_date ASC";
    $result = db_exec($sql);
    $search ='';
    while ( $row = mysqli_fetch_array ( $result ) ) {
        $search .= "\n".'<a href="' . $row ['shared_url'] .'">' .$row ['name_str']. '</a>' ;
    }
    if(strlen($search)>0){
        $reply = "生效中的排班表：{$search}";
        return $reply;
    }
    return '没有找到有效期内的排班表~~';
}

function triwords_name($str){
    if(strlen($str)==6) {
        $strnew=$str[0].$str[1].$str[2].'　'.$str[3].$str[4].$str[5];
        return $strnew;
    }else{
        return $str;
    }

}

function intime_check(){
    $date = date("Y-m-d");
    $sql = "SELECT * FROM pbtable WHERE exp_date >= '{$date}'";
    $result = db_exec ( $sql );
    $num = mysqli_num_rows($result);
    if($num>0){
        return true;
    }else{
        return false;
    }
}


?>