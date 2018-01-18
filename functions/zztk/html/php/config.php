<?php
// by straydoggie

function testmode(){
	$value = true;
	$value = false;
	return $value;
}

function appname(){
	$value = '管制员执照题库';
	return $value;
}

function appnamemark(){
	$value = '微信版Beta';
	return $value;
}

function approot(){
	$value = 'http://straydoggie.cn:81/cdapp/functions/zztk/html/';
	//$value='http://192.168.1.173/cdapp/functions/zztk/html/';
	return $value;
}

function themecolor($color){
	//theme color of start new exercise
	$value_start_light='#0060cc';
	$value_start_dark='#0045aa';
	//theme color of resume last exercise
	$value_resume_light='#335588';
	$value_resume_dark='#204070';
	//theme color of error review exercise
	$value_review_light='#664466';
	$value_review_dark='#503050';
	//theme color of collection exercise
	$value_collection_light='#336655';
	$value_collection_dark='#205040';
	
	switch($color){
		case 'start_light':
			return $value_start_light;
			break;
		case 'start_dark':
			return $value_start_dark;
			break;
		case 'resume_light':
			return $value_resume_light;
			break;
		case 'resume_dark':
			return $value_resume_dark;
			break;
		case 'review_light':
			return $value_review_light;
			break;
		case 'review_dark':
			return $value_review_dark;
			break;
		case 'fav_light':
			return $value_collection_light;
			break;
		case 'fav_dark':
			return $value_collection_dark;
			break;
		default:
			return '#dddddd';
			break;
	}
}

?>