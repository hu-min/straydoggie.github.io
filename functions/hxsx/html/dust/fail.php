<?php
$err=$_GET ['err'];
if($err=="userinfo"){
	echo file_get_contents("./fail_userinfo.html");

}else if($err=="userid"){
	echo file_get_contents(".\fail_userinfo.html");
}
else{
	echo file_get_contents(".\fail_userinfo.html");
}
?>
