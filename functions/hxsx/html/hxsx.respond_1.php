<?php
// by straydoggie
include_once "../php/performance.php";

$code = $_GET ['code'];
$guid = $_GET ['id'];

//html header style
$style_division_chief="header_text_weak";
$style_section_chief="header_text_weak";
$style_assistant="header_text_weak";

//check code
if($code!=""&&$code!=null){
	$url ="http://192.168.1.14/cdapp/functions/common/wx_access.php?mathod=verify_user&code={$code}";
	$member = file_get_contents($url);
	$member = clearbom($member);

	//check member
	if($member!=""&&$member!=null){
		if($member = $member_d0){
			$identity = "处室领导" ;
			$status=2000;
			$style_division_chief="header_text";
		}elseif($member = $member_d1){
			$identity = "科室领导1 " ;
            $status=1000;
			$style_section_chief="header_text";
		}elseif($member = $member_d2){
			$identity = "科室领导2" ;
            $status=1000;
			$style_section_chief="header_text";
		}elseif($member = $member_d3){
			$identity = "科室领导3" ;
            $status=1000;
			$style_section_chief="header_text";
		}elseif($member = $member_a0){
            $status=3000;
			$identity = "综合业务室" ;
			$style_assistant="header_text";
		}elseif($member = $member_s0){
			$identity = "Admin" ;
		}else{
			//echo 'Err: user undelegated';
			journal('undelegated',$user);
			//exit;
		}
	}
	else{
		//echo 'Err: user missing';
        journal('usermissed','');
		//exit;
	}
}
else{
    //echo 'Err: code missing';
    journal('codemissed','');
    //exit;
}

//get content of this time
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

//get brief of recent
$url = "http://192.168.1.14/cdapp/functions/hxsx/php/receive.php?type=recent_brief&data={$user}";
$content = file_get_contents($url);
$arr=explode(';',$content);
$brief="";
for($i=0;$i<count($arr);$i++){
	$arr_brief=explode(',',$arr[$i]);

	$brief.=$arr_brief[0]." 至 ".$arr_brief[1]."，前往：".$arr_brief[3]."、".$arr_brief[4]."、".$arr_brief[5]."";
}
$brief=rtrim($brief,";");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<meta charset="UTF-8" content="">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no" />
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>航线实习<?php echo $identity; ?></title>
    <base href="http://straydoggie.cn:81/cdapp/functions/hxsx/html/" /> 
    <base target="_blank" />
    <link href="css/<?php echo $css_name; ?>" rel="stylesheet" type="text/css" />
    <script src="jquery-1.8.0.js"></script>
</head>
<body oncontextmenu="return false;" onselectstart="return false">
    <div id="container" >
        <div id="view1">
	        <div class="header">
	        	<div class="header_text_weak" style="padding:0px 0px 0px 10px">提交<img class="icon-xs" style="padding-left:5px" src="img/icon/ico_0009_next.png"></div>
	        	<div class="<?php echo $style_section_chief; ?>" style="padding:0px 0px 0px 5px">科室<img class="icon-xs" style="padding-left:5px" src="img/icon/ico_0009_next.png"></div>
	        	
	        	<div class="<?php echo $style_division_chief; ?>" style="padding:0px 0px 0px 5px">终端<img class="icon-xs" style="padding-left:5px" src="img/icon/ico_0009_next.png"></div>

	        	<div class="<?php echo $style_assistant; ?>" style="padding:0px 0px 0px 5px">综合业务室</div>
	        </div>
	        <div class="content">
		        <p class="text_normal">申请人：<?php echo $name; ?></p>
		        <p class="text_normal">起止时间：<?php echo $date1; ?> 至 <?php echo $date2; ?></p>
		        <p class="text_normal">实习地点：<?php echo $dest1; ?>, <?php echo $dest2; ?>, <?php echo $dest3; ?>, <?php echo $dest4; ?></p>
		        <p class="text_normal">情况说明：<?php echo $remark; ?></p>
		        <br>
		        <p class="text_normal">本季度已批准：</p>
		        <p class="text_normal"><?php echo $brief; ?></p>
		        <br>
	        </div>
	        <div class="footer">
	        	<div id="cmd1" class="cmd-line-main" onclick="submit_approved()">同意<img class="icon-s" src="img/icon/ico_0004_forward.png"></div>
	        	<div id="cmd2" class="cmd-line" onclick="openpop('pop1')">拒绝</div>
				
	        </div>
		</div>
		<div id="popups">
			<div id="pop1" class="popup-a">
				<p class="popup-title"><img class="icon-s" src="img/icon/ico_0013_about.png">拒绝申请</p>
		        <br>
		        <p class="popup-text">原因</p>
		        <textarea class="input-round-textarea" id="input5"></textarea>
		        <div class="cmd-line-main-popup" onClick="submit_rejected()">拒绝<img class="icon-s" src="img/icon/ico_0004_forward.png"></div>
		        <div class="cmd-line-popup" onClick="closepop('pop1')">取消</div>
			</div>
			<div class="popup-cover" id="popcover"></div>
		</div>
	</div>
	<script type="text/javascript">
        alert(<?php echo $identity; ?>);
        var type = <?php echo $type; ?>;
        var guid = "<?php echo $guid; ?>";
        var url_approve = "http://192.168.1.14/cdapp/functions/hxsx/php/receive.php?type=update&data=<?php echo $guid; ?>***<?php echo $status; ?>***";
        var url_reject = "http://192.168.1.14/cdapp/functions/hxsx/php/receive.php?type=update&data=<?php echo $guid; ?>***-<?php echo ($status+1); ?>***";
        //
        var wd=window.innerWidth;
        var ht=window.innerHeight;
        function submit_approved(){
            $.get(url_approve, {g_pmid : 500}, function(result) {
                //alert(result);
            });
        }
        function submit_rejected() {
            var rmk = document.getElementById("input5").value;
            $.get(url_reject + rmk, {g_pmid: 500}, function (result) {
                //alert(result);
            });
        }
        function openpop(v) {
            var popdiv=document.getElementById(v);
            var popcov=document.getElementById("popcover");
            popdiv.style.display="inline";
            popdiv.style.width=wd-60+"px";
            popdiv.style.left=30+"px";
            popdiv.style.top=(ht-popdiv.offsetHeight)*0.3+"px";
            popcov.style.display="inline";
            popcov.style.width=wd+"px";
            popcov.style.height=ht+"px";
        }
        function closepop(v) {
            document.getElementById(v).style.display="none";
            document.getElementById("popcover").style.display="none";
        }

        function closeapp() {
            //
            window.opener = null;
            window.open('', '_self');
            window.close();
            WeixinJSBridge.invoke('closeWindow', {}, function (res) {
            });
        }
	</script>
</body>
</html>