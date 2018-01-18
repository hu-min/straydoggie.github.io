<?php
// by straydoggie

$address = $_GET ['address'];
$content = $_GET ['content'];

require("smtp.php");

$send_from = "chengdu_approach@sina.com";
$send_to = $address;

$smtp_host = "smtp.sina.com";
$smtp_port = 25;
$smtp_user = "chengdu_approach";
$smtp_pass = "XXooXXoo";
$smtp_auth = true;

$mail_title = "航线实习申请通知";
$mail_content = $content;
$mail_type = "TXT";

$smtp = new smtp($smtp_host, $smtp_port, $smtp_auth, $smtp_user, $smtp_pass);

$smtp->debug = false;
$smtp->log_file = "./datalog_smtp.txt";

$sent = $smtp->sendmail($send_to, $send_from, $mail_title, $mail_content, $mail_type);

if($sent){echo "success";}else{echo "fail";}
?>
