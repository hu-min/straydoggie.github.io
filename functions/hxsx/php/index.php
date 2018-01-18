
<?php
header ( "Content-Type: text;charset=utf-8" );

// by straydoggie
include_once "wx/WXBizMsgCrypt.php";

$encodingAesKey = "LDKEKfKJPyhoLh2SzfN8pi9bjYvXi5cBXPCOS9Vx3D4";
$token = "weixin";
$corpId = "wx29136ddcd832c0b5";

$sReqMsgSig = $sVerifyMsgSig = $_GET ['msg_signature'];
$sReqTimeStamp = $sVerifyTimeStamp = $_GET ['timestamp'];
$sReqNonce = $sVerifyNonce = $_GET ['nonce'];
$sReqData = file_get_contents ( "php://input" );
$sVerifyEchoStr = $_GET ['echostr'];

$wxcpt = new WXBizMsgCrypt ( $token, $encodingAesKey, $corpId );

// logstr ( "二室排班查询" );

if ($sVerifyEchoStr) {
	$sEchoStr = "";
	$errCode = $wxcpt->VerifyURL ( $sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $sEchoStr );
	if ($errCode == 0) {
		logstr ( $sVerifyEchoStr . "\r\n" . $sEchoStr );
		echo $sEchoStr;
	} else {
		// print($errCode . "\n\n");
		logstr ( "Verify EchoStr Failed: " . $errCode );
	}
	exit ();
}

if ($sReqData) {
	logstr($sReqData);
	$sMsg = "";
	$errCode = $wxcpt->DecryptMsg ( $sReqMsgSig, $sReqTimeStamp, $sReqNonce, $sReqData, $sMsg );
	if ($errCode == 0) {
		$xml = new DOMDocument ();
		$xml->loadXML ( $sMsg );
		$reqToUserName = $xml->getElementsByTagName ( 'ToUserName' )->item ( 0 )->nodeValue;
		$reqFromUserName = $xml->getElementsByTagName ( 'FromUserName' )->item ( 0 )->nodeValue;
		$reqCreateTime = $xml->getElementsByTagName ( 'CreateTime' )->item ( 0 )->nodeValue;
		$reqMsgType = $xml->getElementsByTagName ( 'MsgType' )->item ( 0 )->nodeValue;
		$reqContent = $xml->getElementsByTagName ( 'Content' )->item ( 0 )->nodeValue;
		$reqMsgId = $xml->getElementsByTagName ( 'MsgId' )->item ( 0 )->nodeValue;
		$reqAgentID = $xml->getElementsByTagName ( 'AgentID' )->item ( 0 )->nodeValue;
		
		$reqEvent = $xml->getElementsByTagName ( 'Event' )->item ( 0 )->nodeValue;
		$reqEventKey = $xml->getElementsByTagName ( 'EventKey' )->item ( 0 )->nodeValue;
		
		// logstr("UserName: ".$reqFromUserName);
		// logstr("Message: ".$reqContent);
		
		// database,reqContent & mycontent
		$dbhost = "localhost";
		$dbusr = "cdapp";
		$dbkey = "gzzxjjwt1";
		$link = mysql_connect ( $dbhost, $dbusr, $dbkey );
		mysql_query ( "set character set 'utf8'" ); // 读库
		mysql_query ( "set names 'utf8'" ); // 写库
		if (! $link) {
			print ("connect to mysql failed: " . mysql_error () . "\t(" . mysql_errno () . ")") ;
		} else {
			$selection = mysql_select_db ( "cdapp", $link );
			if (! $selection) {
				print ("select database failed: " . mysql_error () . "\t(" . mysql_errno () . ")") ;
			} else {
				// ------------------------------------------------------------
				
				if ($reqMsgType == "event") {
					if ($reqEvent == "subscribe" or $reqEventKey == "help") {
						$mycontent = msg_help ( "?" );
					} elseif ($reqEventKey == "fulltable") {
						$mycontent = msg_fulltable ( $link );
					}
				} else if ($reqMsgType == "text") {
					if ($mycontent = msg_help ( $reqContent )) {
					} else if ($mycontent = msg_name ( $reqContent, $link )) {
					} else if ($mycontent = msg_date ( $reqContent, $link )) {
					} else if ($mycontent = msg_today ( $reqContent, $link )) {
					} else {
						$mycontent = "神马也木有查到~~~\n输入问号可查看说明";
					}
				}
				// ------------------------------------------------------------
				mysql_close ( $link );
				// logstr($mycontent);
			}
			//$mycontent.="\n\n".appending();
			$sRespData = "<xml>
					<ToUserName><![CDATA[" . $reqFromUserName . "]]></ToUserName>
					<FromUserName><![CDATA[" . $corpId . "]]></FromUserName>
					<CreateTime>" . sReqTimeStamp . "</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA[" . $mycontent . "]]></Content>
					</xml>";
			$sEncryptMsg = "";
			$errCode = $wxcpt->EncryptMsg ( $sRespData, $sReqTimeStamp, $sReqNonce, $sEncryptMsg );
			if ($errCode == 0) {
				// logstr($sEncryptMsg);
				msglog ( $reqFromUserName, "[" . $reqMsgType . $reqEvent . $reqEventKey . "]" . $reqContent, $mycontent );
				echo $sEncryptMsg;
			} else {
				$errStr = "Error in EncryptMsg: " . $errCode;
				logstr ( $errStr );
			}
		}
	} else {
		$errStr = "Error in DecryptMsg: " . $errCode;
		logstr ( $errStr );
	}
} else {
	$errStr = "Postdata Empty!";
	logstr ( $errStr );
}
function msg_help($reqContent) {
	if ($reqContent == "?" or $reqContent == "？") {
		$mycontent = "【使用说明】\n\n1、输入姓名：\n可查询今日及以后的值班安排；\n\n2、输入执勤时段（A～E、调休、备份）：\n可查询今日执该时段的人员；\n\n3、输入八位日期+时段（例如20160101A）：\n可查询2016年1月1日执A时段的人员。";
	}
	return $mycontent;
}
function msg_name($reqContent, $link) {
	mysql_query ( "set character set 'utf8'" );
	$today_str = date ( "Y-m-d" );
	$sql = "SELECT * FROM pbcx WHERE name = '{$reqContent}' AND date >= '{$today_str}' ORDER BY date ASC";
	$result = mysql_query ( $sql, $link );
	$tdstr = "查询到{$reqContent}的排班：";
	while ( $row = mysql_fetch_array ( $result ) ) {
		$days = $row ['date'];
		$days_array = explode ( '-', $row ['date'] );
		$tdstr .= "\n";
		$tdstr .= ltrim ( $days_array [1], "0" ) . "月" . ltrim ( $days_array [2], "0" ) . "日" . "：" . $row ['task'];
		if ($row ['ext']) {
			$tdstr .= "（" . $row ['ext'] . "）";
		}
	}
	
	if (strlen ( $tdstr ) == strlen ( "查询到{$reqContent}的排班：" )) {
		$mycontent = "";
	} else {
		$mycontent = $tdstr . "\n以上结果仅供参考，请以OA发布为准！";
	}
	return $mycontent;
}
function msg_date($reqContent, $link) {
	$str_day = substr ( $reqContent, 0, 4 ) . "-" . substr ( $reqContent, 4, 2 ) . "-" . substr ( $reqContent, 6, 2 );
	$str_task = substr ( $reqContent, 8, strlen ( $reqContent ) - 8 );
	$str_task = strtoupper ( $str_task );
	$day_arr = explode ( '-', $str_day );
	$day_str = ltrim ( $day_arr [0], "0" ) . "年" . ltrim ( $day_arr [1], "0" ) . "月" . ltrim ( $day_arr [2], "0" ) . "日";
	$sql = "SELECT * FROM pbcx WHERE date = '{$str_day}' AND task = '{$str_task}'";
	$result = mysql_query ( $sql, $link );
	$tdstr = "查询到{$day_str}排{$str_task}的人员：";
	// logstr($tdstr);
	
	while ( $row = mysql_fetch_array ( $result ) ) {
		$tdstr .= "\n" . $row ['name'];
		if ($row ['ext']) {
			$tdstr .= "（" . $row ['ext'] . "）";
		}
		// logstr($tdstr);
	}
	if (strlen ( $tdstr ) == strlen ( "查询到{$day_str}排{$str_task}的人员：" )) {
		$mycontent = "";
	} else {
		$mycontent = $tdstr . "\n以上结果仅供参考，请以OA发布为准！";
	}
	return $mycontent;
}
function msg_today($reqContent, $link) {
	$today_str = date ( "Y-m-d" );
	$str_task = strtoupper ( $reqContent );
	$sql = "SELECT * FROM pbcx WHERE date = '{$today_str}' AND task = '{$str_task}'";
	$result = mysql_query ( $sql, $link );
	$day_arr = explode ( '-', $today_str );
	$day_str = ltrim ( $day_arr [0], "0" ) . "年" . ltrim ( $day_arr [1], "0" ) . "月" . ltrim ( $day_arr [2], "0" ) . "日";
	$tdstr = "查询到今日排{$str_task}的人员：";
	while ( $row = mysql_fetch_array ( $result ) ) {
		$days = $row ['date'];
		
		$tdstr .= "\n";
		$tdstr .= $row ['name'];
		if ($row ['ext']) {
			$tdstr .= "（" . $row ['ext'] . "）";
		}
	}
	if (strlen ( $tdstr ) == strlen ( "查询到今日排{$str_task}的人员：" )) {
		$mycontent = "";
	} else {
		$mycontent = $tdstr . "\n以上结果仅供参考，请以OA发布为准！";
	}
	return $mycontent;
}
function msg_fulltable($link) {
	$result_str = "生效中的排班表：";
	$today_str = date ( "Y-m-d" );
	$sql = "SELECT * FROM pbtable WHERE exp_date >= '{$today_str}' ORDER BY exp_date ASC";
	$result = mysql_query ( $sql, $link );
	$result_str .="\n";
	while ( $row = mysql_fetch_array ( $result ) ) {
		$result_str .="\n";
		$result_str .= '<a href="' . $row ['shared_url'] .'">' .$row ['name_str']. '</a>' ;
		$result_str .="\n";
		// logstr($tdstr);
	}
	return $result_str;
}
function msglog($UserName, $ReceivedMsg, $RepliedMsg) {
	$dbhost = "localhost";
	$dbusr = "cdapp";
	$dbkey = "gzzxjjwt1";
	$link = mysql_connect ( $dbhost, $dbusr, $dbkey );
	mysql_query ( "set character set 'utf8'" ); // 读库
	mysql_query ( "set names 'utf8'" ); // 写库
	if (! $link) {
		print ("connect to mysql failed: " . mysql_error () . "\t(" . mysql_errno () . ")") ;
	} else {
		$selection = mysql_select_db ( "cdapp", $link );
		if (! $selection) {
			print ("select database failed: " . mysql_error () . "\t(" . mysql_errno () . ")") ;
		} else {
			$time = "[" . date ( 'Y-m-d H:i:s', time () ) . "] ";
			$sql = "INSERT INTO msglog (time, usr_name, received_msg, replied_msg) VALUES ('{$time}', '{$UserName}', '{$ReceivedMsg}', '{$RepliedMsg}')";
			mysql_query ( $sql );
			mysql_close ( $loglink );
		}
	}
}
function logstr($str) {
	$dbhost = "localhost";
	$dbusr = "cdapp";
	$dbkey = "gzzxjjwt1";
	$link = mysql_connect ( $dbhost, $dbusr, $dbkey );
	mysql_query ( "set character set 'utf8'" ); // 读库
	mysql_query ( "set names 'utf8'" ); // 写库
	if (! $link) {
		print ("connect to mysql failed: " . mysql_error () . "\t(" . mysql_errno () . ")") ;
	} else {
		$selection = mysql_select_db ( "cdapp", $link );
		if (! $selection) {
			print ("select database failed: " . mysql_error () . "\t(" . mysql_errno () . ")") ;
		} else {
			if ($str == "!!!") {
				$sql = "DELETE * FROM eventlog";
			} else {
				$txt = date ( 'Y-m-d H:i:s', time () );
				$sql = "INSERT INTO eventlog (time, app, str) VALUES ('{$txt}', 'pbcx', '{$str}')";
			}
			mysql_query ( $sql );
			mysql_close ( $loglink );
		}
	}
}

function appending(){
	$a="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx29136ddcd832c0b5&redirect_uri=http%3a%2f%2fstraydoggie.cn%3a81%2fcdapp%2fhtml%2ft.html&response_type=code&scope=snsapi_base#wechat_redirect";
	$b .= "执照题库微信版".'<a href="' . $a .'">' .'点击进入'. '</a>' ;
	return;
}

?>