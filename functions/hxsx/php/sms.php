<?php
// by straydoggie

/*
【成都终端管制室】你的航线实习申请以处理完成，请尽快前往综合业务室#room#领取，如有疑问请联系#name#。
【成都终端管制室】你的航线实习申请#content#已提交，请留意微信/邮箱/短信提醒。
【成都终端管制室】你的航线实习申请已由#role#审核#result#。

$text="【成都终端管制室】你的航线实习申请{$content}已提交，请留意微信/邮箱/短信提醒。";
$text="【成都终端管制室】你的航线实习申请已由{$member}{$name}审核{$result}{$content}。";
$text="【成都终端管制室】你的航线实习申请以处理完成，请尽快前往综合业务室({$room})领取，如有疑问请联系{$name}。";
*/

$room="A411";
$name="杨璐伊";
$text="【成都终端管制室】你的航线实习申请以处理完成，请尽快前往综合业务室({$room})领取，如有疑问请联系{$name}。";
$mobile = "18628205151";

send_sms_yunpian($mobile,$text);

function send_sms_yunpian($mobile,$text){
	header("Content-Type:text/html;charset=utf-8");
	$apikey = "299e699f334a974a09a3da81626189be"; 
	$data=array('text'=>$text,'apikey'=>$apikey,'mobile'=>$mobile);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:text/plain;charset=utf-8', 'Content-Type:application/x-www-form-urlencoded','charset=utf-8'));
	curl_setopt($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	$json_data = curl_exec($ch);
	$array = json_decode($json_data,true);
	if($array['code']==0){echo "success";}
}

?>