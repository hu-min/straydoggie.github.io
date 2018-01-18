<?php
//by straydoggie

include_once "wx/WXBizMsgCrypt.php";

$encodingAesKey = "LDKEKfKJPyhoLh2SzfN8pi9bjYvXi5cBXPCOS9Vx3D4"; 
$token = "weixin"; 
$corpId ="wx29136ddcd832c0b5"; 


$msg_signature = $_GET['msg_signature'];
$timestamp = $_GET['timestamp'];
$nonce = $_GET['nonce'];
$echostr = $_GET['echostr'];

$wxcpt = new WXBizMsgCrypt($token, $encodingAesKey, $corpId);

$sEchoStr="";

$errCode = $wxcpt->VerifyURL($msg_signature, $timestamp, $nonce, $echostr, $sEchoStr);

if ($errCode == 0) {
  echo $sEchoStr;
}
else{
  echo $errCode;
}

?>