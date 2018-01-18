<?php
// by straydoggie
include_once '../../common/basis.php';
$type = $_GET[ 'type' ];
$event = $_GET[ 'event' ];
$key = $_GET[ 'key' ];
$text = $_GET[ 'text' ];

$appname='管制员执照题库';

if ( $type == '' or $type == null ) {
    echo 'missing type';
}elseif ( $type == 'text' ) {
	echo msg_text($text);
}elseif ( $type == 'event' ) {
    echo msg_event($event, $key);
}else {
    echo '/(ㄒoㄒ)/~~';
}

function msg_event($event,$key){
	if($key=='enter_agent'){return msg_enter();}
	elseif($key=='subscribe'){return msg_onetime();}
	elseif($key=='k_help'){return msg_help();}
}

function msg_text($text){
/*
	$note="...";
	return $note;
*/
}

function msg_enter(){
/*
	global $appname;
	$str_enter = '';//msg_welcome();
	return $str_enter;
*/
}
function msg_onetime(){
	global $appname;
	$str_welcome = "欢迎关注{$appname}！";
	return $str_welcome;
}
function msg_help(){
	global $appname;
	$str_help="暂无帮助内容";
	return $str_help;
}
function msg_welcome(){
	global $appname;
	$h = date('H');
	$s='';
	if($h>=5 && $h<9){
		$s="早上好，欢迎使用{$appname}！";
	}else if($h>=9 && $h<11){
		$s="上午好，欢迎使用{$appname}！";
	}else if($h>=11 && $h<14){
		$s="中午好，欢迎使用{$appname}！";
	}else if($h>=14 && $h<19){
		$s="下午好，欢迎使用{$appname}！";
	}else if($h>=19){
		$s="晚上好，欢迎使用{$appname}！";
	}else if($h>=0 && $h<5){
		$s="欢迎使用{$appname}！夜深了，注意休息~~";
	}else {
		$s="你好，欢迎使用{$appname}！";
	}
	return $s;
}