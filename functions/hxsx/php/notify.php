<?php
// by straydoggie

include_once "../../common/basis.php";

$guid = $_GET ['guid'];
$member = $_GET ['member'];
$model = $_GET ['model'];

notify($guid,$member,$model);

function notify($guid,$member,$model) {
    $url = "http://192.168.1.14/cdapp/functions/hxsx/php/receive.php?type=content&data={$guid}";
    $content = file_get_contents($url);
    $arr=explode(',',$content);
    $user=$arr[1];
    $name=$arr[2];
    $date1=$arr[6];
    $date2=$arr[7];
    $dest1=$arr[8];
    $dest2=$arr[9];
    $dest3=$arr[10];
    $dest4=$arr[11];
    $type=$arr[12];
    $remark=$arr[13];
    if($remark==null||$remark==""){
        $remark="无";
    }
    $status=$arr[14];
    $shortid=explode('-',$guid)[0];
	switch ($model){
		case 'proceed':
			$msg="{$name}申请从{$date1}至{$date2}前往{$dest1}{$dest2}{$dest3}{$dest4}航线实习，请批复";
			break;
		case 'approved':
            $msg="您的航线实习申请（编号{$shortid}）";
            break;
        case 'rejected':
            $msg="您的航线实习申请（编号{$shortid}）";
            break;
        case 'finished':
            $msg="您的航线实习申请（编号{$shortid}）";
            break;
		case 'submited':
            //$msg="您的航线实习申请（编号{$shortid}）：从{$date1}至{$date2}前往{$dest2}{$dest3}{$dest4}已提交，请留意微信/邮件/短信通知。";
            $msg="您的航线实习申请（编号{$shortid}）已提交，请留意微信/邮件/短信通知。";
            break;
		default:
			return 'failed';
			break;
	}

	if($msg)
	{
		$url="http://192.168.1.14/cdapp/functions/common/wx_access.php?mathod=send_msg&msg_data={$member},10,{$msg}";
		$result = json_decode(file_get_contents($url),false);
		$errcode = $result['errcode'];
		$errmsg = $result['errmsg'];
		if($errcode==0){
			journal('notify',$msg);
		}else{
			journal('notify',"failed:\r\ncode = {$errcode}\r\nmsg = {$errmsg}");
		}
	}
	else{
        journal('notify','failed: missing message');
	}
}

?>
