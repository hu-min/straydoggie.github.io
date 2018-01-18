<?php
// by straydoggie

header ( "Content-Type: text;charset=utf-8" );
include_once 'wx/WXBizMsgCrypt.php';
include_once '../../common/basis.php';

$encodingAesKey = "LDKEKfKJPyhoLh2SzfN8pi9bjYvXi5cBXPCOS9Vx3D4";
$token = "weixin";
$corpId = "wx29136ddcd832c0b5";

$sReqMsgSig = $sVerifyMsgSig = $_GET ['msg_signature'];
$sReqTimeStamp = $sVerifyTimeStamp = $_GET ['timestamp'];
$sReqNonce = $sVerifyNonce = $_GET ['nonce'];
$sReqData = file_get_contents ( "php://input" );
$sVerifyEchoStr = $_GET ['echostr'];

$wxcpt = new WXBizMsgCrypt ( $token, $encodingAesKey, $corpId );

if ($sVerifyEchoStr) {
	$sEchoStr = "";
	$errCode = $wxcpt->VerifyURL ( $sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $sEchoStr );
	if ($errCode == 0) {
		logstr ( $sVerifyEchoStr . "\r\n" . $sEchoStr );
		echo $sEchoStr;
	} else {
		logstr ( "Verify EchoStr Failed: " . $errCode );
	}
	exit ();
}

if ($sReqData) {
    $sMsg = "";
    $errCode = $wxcpt->DecryptMsg($sReqMsgSig, $sReqTimeStamp, $sReqNonce, $sReqData, $sMsg);
    if ($errCode == 0) {
        $xml = new DOMDocument ();
        $xml->loadXML($sMsg);
        $reqToUserName = $xml->getElementsByTagName('ToUserName')->item(0)->nodeValue;
        $reqFromUserName = $xml->getElementsByTagName('FromUserName')->item(0)->nodeValue;
        $reqCreateTime = $xml->getElementsByTagName('CreateTime')->item(0)->nodeValue;
        $reqMsgType = $xml->getElementsByTagName('MsgType')->item(0)->nodeValue;
        $reqContent = $xml->getElementsByTagName('Content')->item(0)->nodeValue;
        $reqMsgId = $xml->getElementsByTagName('MsgId')->item(0)->nodeValue;
        $reqAgentID = $xml->getElementsByTagName('AgentID')->item(0)->nodeValue;
        $reqEvent = $xml->getElementsByTagName('Event')->item(0)->nodeValue;
        $reqEventKey = $xml->getElementsByTagName('EventKey')->item(0)->nodeValue;
        $requestURL="http://localhost/cdapp/functions/pbcx/php/message.php?type={$reqMsgType}&event={$reqEvent}&key={$reqEventKey}&text={$reqContent}";
        $myContent = file_get_contents($requestURL);
        $shortContent = str_replace("\n", '><', str_replace("\n\n", "\n", $myContent));
        journal('pbcx', "User: <{$reqFromUserName}>\r\nReceive: <{$reqContent}>\r\nReply: <{$shortContent}>");
        $sRespData = "<xml>
				<ToUserName><![CDATA[" . $reqFromUserName . "]]></ToUserName>
				<FromUserName><![CDATA[" . $corpId . "]]></FromUserName>
				<CreateTime>" . sReqTimeStamp . "</CreateTime>
				<MsgType><![CDATA[text]]></MsgType>
				<Content><![CDATA[" . $myContent . "]]></Content>
				</xml>";
        $sEncryptMsg = '';
        $errCode = $wxcpt->EncryptMsg($sRespData, $sReqTimeStamp, $sReqNonce, $sEncryptMsg);
        if ($errCode == 0) {
            echo $sEncryptMsg;
        } else {
            journal('EncryptMsg', $errCode);
        }
    } else {
        journal('DecryptMsg', $errCode);
    }
}
?>