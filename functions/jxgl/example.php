<?php
// by straydoggie
$testmode = true;
$code = $_GET ['code'];
include_once '../../common/basis.php';

if($code!=""&&$code!=null){
    $url ="http://192.168.1.14/cdapp/functions/common/wx_access.php?mathod=verify_user&code={$code}";
    $user = file_get_contents($url);
    $user = clearbom($user);
    if($user!=""&&$user!=null){
        $url = "http://192.168.1.14/cdapp/functions/hxsx/php/receive.php?type=user_info&data={$user}";
        $userinfo = file_get_contents($url);
        if($userinfo!=null){
            log_in_file("successfully visited: {$user}");
        }else{
            log_in_file("missid userinfo: {$user}");
            echo file_get_contents("./fail_userinfo.html");
            exit;
        }
    }else{
        log_in_file("invalid userid: {$code}");
        echo file_get_contents("./fail_userid.html");
        exit;
    }
}

$teststr =
    "document.getElementById(\"input1\").value=\"北京\";\r\n\t\t".
    "document.getElementById(\"input2\").value=\"上海\";\r\n\t\t".
    "document.getElementById(\"input3\").value=\"广州\";\r\n\t\t".
    "document.getElementById(\"input4\").value=\"2017-11-11\";\r\n";


function log_in_file($str) {
	$file = fopen ( "./datalog.txt", "a" );
	fwrite ( $file, "\r\n[".date("Y-m-d H:i:s")."][index.php]". $str);
	fclose ( $file );
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<meta charset="UTF-8" content="">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no" />
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>申请航线实习</title>
    <base href="http://straydoggie.cn:81/cdapp/functions/hxsx/html/" /> 
    <base target="_blank" />
    <link href="css/normal.css" rel="stylesheet" type="text/css" />
    <script src="jquery-1.8.0.js"></script>
</head>
<body oncontextmenu="return false;" onselectstart="return false">
    <div id="container" >
        <div id="view1">
            <div class="header" id="header1">
                <div class="header_text">核对信息</div>
                <div class="header_text_weak">填写申请</div>
                <div class="header_text_weak">提交申请</div>
            </div>
            <div class="content" id="content1">
                <div class="inner" id="inner1">
                    <p class="text_inform">请核对以下个人信息：</p>
                    <div class="input-round-view" id="info-img">
                    	<img class="input-view-photo" id="info-img-content" src="">
                    </div>
                    <p class="label" id="info1" style="float:none"></p>
                    <br><br>
                    <p class="label_text_s" style="padding-left:20px">身份证号：</p>
                    <p class="label_text_su" id="info2"></p>
                    <br><br><br>
                    <p class="label_text_s" style="padding-left:20px">工作证号：</p>
                    <p class="label_text_su" id="info3"></p>
                    <br><br><br>
                    <p class="label_text_s" style="padding-left:20px">联系电话：</p>
                    <p class="label_text_su" id="info4"></p>
                    <br><br><br>
                    <div class="blank_space" id="space1"></div>
                </div>
            </div>
            <div class="footer" id="footer1" >
                <div class="cmd-line-main" id="cmd1-1" onclick="loadview('view2')">下一步<img class="icon-s" src="img/icon/ico_0009_next.png"></div>
                <div class="cmd-line" id="cmd1-2" onClick="closeapp()">关闭</div>
                <div class="cmd-line" id="cmd1-3" onClick="openpop('pop5')">修改</div>
            </div>
        </div>
        <div id="view2">
            <div class="header" id="header2">
            	<div class="header_text_weak">核对信息</div>
                <div class="header_text">填写申请</div>
                <div class="header_text_weak">提交申请</div>
            </div>
            <div class="content" id="content2">
                <div class="inner" id="inner2">
    				<p class="text_inform">输入3个航线实习目的地 (除“成都”以外)</p>
                    <input class="input-round" id="input1" type="text" name="dest1">
                    <input class="input-round" id="input2" type="text" name="dest2">
                    <input class="input-round" id="input3" type="text" name="dest3">
                    <br><br><br><br><br>
                    <p class="text_inform">输入航线实习起始日期 (15个工作日以内)</p>
                    <input class="input-round" id="input4" type="date" name="date1">
                    <br><br><br>
                    <div class="blank_space" id="space2"></div>
                </div>
            </div>
            <div class="footer" id="footer2" >
            	<div class="cmd-line-main" id="cmd2-1" onclick="gaindatas()">下一步
                    <img class="icon-s" src="img/icon/ico_0009_next.png"></div>
                <div class="cmd-line" id="cmd2-2" onclick="loadview('view1')">上一步</div>
    			<div class="cmd-line" id="cmd2-3" onclick="openpop('pop1')">清空</div>
            </div>
        </div>
        <div id="view3">
            <div class="header" id="header3">
                <div class="header_text_weak">核对信息</div>
                <div class="header_text_weak">填写申请</div>
                <div class="header_text">提交申请</div>
            </div>
            <div class="content" id="content3">
                <p class="label_text" style="margin-top: 20px"> 
                <img class="icon-xs" src="img/icon/ico_0009_next.png">实习地点：</p>
                <p class="label_text" id="blank1" style="margin-left: 26px"></p>
                <p class="label_text" style="margin-top: 40px"> 
                <img class="icon-xs" src="img/icon/ico_0009_next.png">实习时间：</p>
                <p class="label_text" id="blank2" style="margin-left: 26px"></p>
                <p class="label_text" style="margin-top: 40px"> 
                <img class="icon-xs" src="img/icon/ico_0009_next.png">实习航线：</p>
                <p class="label_text" id="blank3" style="margin-left: 26px"></p>
                <p class="label_text" style="margin:40px 26px 0px 26px">
                如以上信息无误即可“提交”，若有必要 (地点不在一个方向、本季度已航实等) 可“提交为特殊申请”。</p>
                <div class="blank_space" id="space3"></div>
            </div>
            <div class="footer" id="footer3" >
                <div class="cmd-line-main" id="cmd3-1" onclick="submit_normal()">提交
                    <img class="icon-s" src="img/icon/ico_0004_forward.png"></div>
                <div class="cmd-line" id="cmd3-3" onclick="loadview('view2')">上一步</div>
                <div class="cmd-line" id="cmd3-2" onclick="submit_confirm()" style="border-bottom: 5px solid #f80">
                提交为特殊申请</div>
            </div>
        </div>    
    </div>
    <div id="popups">
        <div class="popup_a" id="pop1">
        	<p class="popup_title"><img class="icon-s" src="img/icon/ico_0013_about.png">确定要清空内容吗</p>
            <br>
        	<div class="cmd-line-main-popup" onClick="cleanform()">确定</div>
            <div class="cmd-line-popup" onClick="closepop('pop1')">取消</div>
        </div>
        <div class="popup_a" id="pop2">
        	<p class="popup_title"><img class="icon-s" src="img/icon/ico_0013_about.png">需完整填写全部内容</p>
            <br>
        	<div class="cmd-line-main-popup" onClick="closepop('pop2')">返回</div>
        </div>
        <div class="popup_a" id="pop3">
            <p class="popup_title"><img class="icon-s" src="img/icon/ico_0013_about.png">提交为特殊申请</p>
            <br>
            <p class="popup_text">填写情况说明 (可不填)</p>
            <textarea class="input_textarea" id="input5" name="remk1" style="color:#f80"></textarea>
            <div class="cmd-line-main-popup" onClick="submit_abnormal()" style="border-bottom: 5px solid #f80">提交
            <img class="icon-s" src="img/icon/ico_0004_forward.png"></div>
            <div class="cmd-line-popup" onClick="closepop('pop3')">取消</div>
        </div>
        <div class="popup_a" id="pop4">
            <p class="popup_title"><img class="icon-s" src="img/icon/ico_0013_about.png">已完成</p>
            <p class="popup_text">申请已提交，请随时关注微信提醒以及综合业务室通知，也可在“申请航线实习/查看申请状态”中进行查询。</p>
            <div class="cmd-line-main-popup" onClick="closeapp()">退出<img class="icon-s" src="img/icon/ico_0014_exit.png"></div>
        </div>
        <div class="popup_a" id="pop5">
            <p class="popup_title"><img class="icon-s" src="img/icon/ico_0013_about.png">不提供此功能</p>
            <p class="popup_text">暂时不支持修改人员信息，如信息有误请联系***进行修改。</p>
            <div class="cmd-line-main-popup" onClick="closepop('pop5')">好</div>
        </div>
        <div class="popup_cover" id="popcover"></div>
    </div>
	<script type="text/javascript">
    //basic info
    var user_id="";
    var user_name="";
    var user_img="";
    var user_id1="";
    var user_id2="";
    var user_tel="";
    //filled info
	var data_dest1="";
	var data_dest2="";
	var data_dest3="";
	var data_dest4="";
	var data_date1="";
    var data_remk="";
    var data_type="0";
    //
    var data_date2="";
    var data_date1_str="";
    var data_date2_str="";
    var data_dest0="成都";
    //
    var wd=window.innerWidth;
	var ht=window.innerHeight;
    //start here!
    loadinfo();
    //functions
	function loadview(v){
        document.getElementById("container").style.visibility="hidden";
        if(v == "view1"){
            
            document.getElementById("view1").style.display="inline";
			document.getElementById("view2").style.display="none";
            document.getElementById("view3").style.display="none";
			document.getElementById("space1").style.height="0px";

			var wdinfo=wd-150;
			var wdimg=wd*0.3;
			var offsetimg=-wdimg*0.05;
            document.getElementById("info2").style.width=wdinfo+"px";
            document.getElementById("info3").style.width=wdinfo+"px";
            document.getElementById("info4").style.width=wdinfo+"px";
			document.getElementById("info-img").style.width=wdimg+"px";
			document.getElementById("info-img").style.height=wdimg+"px";
			document.getElementById("info-img").style.borderRadius=wdimg+"px";
			document.getElementById("info-img").style.left=(wd-wdimg)/2-20+"px";
			document.getElementById("info-img-content").style.width=wdimg+"px";
			document.getElementById("info-img-content").style.top=offsetimg+"px";
			//
            var htc=document.getElementById("container").offsetHeight;
            var hts=ht-htc-20;
            document.getElementById("space1").style.height=hts+"px";
        }
        else if(v == "view2"){
            document.getElementById("view1").style.display="none";
            document.getElementById("view2").style.display="inline";
            document.getElementById("view3").style.display="none";
			document.getElementById("space2").style.height="0px";
            var wdc=wd/3-50;
            var wdi=wd-64;
            document.getElementById("input1").style.width=wdc+"px";
            document.getElementById("input2").style.width=wdc+"px";
            document.getElementById("input3").style.width=wdc+"px";
            document.getElementById("input4").style.width=wdi+"px";
            //
            var htc=document.getElementById("container").offsetHeight;
            var hts=ht-htc-20;
            document.getElementById("space2").style.height=hts+"px";
        }
        else if(v == "view3"){
            document.getElementById("view1").style.display="none";
            document.getElementById("view2").style.display="none";
            document.getElementById("view3").style.display="inline";
            document.getElementById("space3").style.height="0px";
            //
            var htc=document.getElementById("container").offsetHeight;
            var hts=ht-htc-20;
            document.getElementById("space3").style.height=hts+"px";
        }
        else{

        }
        document.getElementById("container").style.visibility="visible";
	}


    function gaindatas() {
		data_dest1=document.getElementById("input1").value;
		data_dest2=document.getElementById("input2").value;
		data_dest3=document.getElementById("input3").value;
		data_date1=document.getElementById("input4").value;
    	if(data_dest1==""||data_dest2==""||data_dest3==""||data_date1=="") {
    		openpop("pop2");
    	}
    	else{
            submit_show();
    		loadview("view3");
    	}
    }

    function submit_confirm(){
        var ht5 = Math.max(120, ht*0.20);
		document.getElementById("input5").style.width=wd-120+"px";
        document.getElementById("input5").style.height=ht5+"px";
        openpop("pop3");
    }

    function submit_show() {
        //双箭头：⇄，⇌
        var data_route1=data_dest0+" ⇌ "+data_dest1;
        var data_route2=data_dest0+" ⇌ "+data_dest2;
        var data_route3=data_dest0+" ⇌ "+data_dest3;
        var data_route4=data_dest1+" ⇌ "+data_dest2;
        var data_route5=data_dest1+" ⇌ "+data_dest3;
        var data_route6=data_dest2+" ⇌ "+data_dest3;
        var blank1=document.getElementById("blank1");
        var blank2=document.getElementById("blank2");
        var blank3=document.getElementById("blank3");

        var data_date1_arr = data_date1.split("-");  
        var m0=parseInt(data_date1_arr[1])-1;
        var data_date1_raw = new Date(data_date1_arr[0],m0,data_date1_arr[2],0,0);
        var data_date2_raw = new Date(data_date1_arr[0],m0,data_date1_arr[2],0,0);
        data_date2_raw.setDate(data_date2_raw.getDate() + 6);
        var m1 = data_date1_raw.getMonth()+1;
        var m2 = data_date2_raw.getMonth()+1;
        data_date1_str=data_date1_raw.getFullYear()+"年"+m1+"月"+data_date1_raw.getDate()+"日";
        data_date2_str=data_date2_raw.getFullYear()+"年"+m2+"月"+data_date2_raw.getDate()+"日";
        data_date1=data_date1_raw.getFullYear()+"-"+m1+"-"+data_date1_raw.getDate();
        data_date2=data_date2_raw.getFullYear()+"-"+m2+"-"+data_date2_raw.getDate();

        blank1.innerHTML=data_dest0+"，"+data_dest1+"，"+data_dest2+"，"+data_dest3;
        blank2.innerHTML=data_date1_str+" ～ "+data_date2_str;
        blank3.innerHTML=data_route1+"，"+data_route2+"，"+data_route3+"<br>"+data_route4+"，"+data_route5+"，"+data_route6;
    }
	
	function submit_normal() {
        var spliter = "***";
        var date = new Date();
        var time = "[" + date.getHours() + ":" + date.getMinutes() + "]";
        var data = user_id+spliter+user_name+spliter+user_id1+spliter+user_id2+spliter+user_tel+spliter+data_date1+spliter+data_date2+spliter+data_dest0+spliter+data_dest1+spliter+data_dest2+spliter+data_dest3+spliter+data_type+spliter+data_remk;
        var url='http://straydoggie.cn:81/cdapp/functions/hxsx/php/receive.php?type=save&data='+ data;
        //alert(data);
        $.get(url, {g_pmid : 500}, function(resultstr) {
			if(resultstr!='ok'){alert(resultstr);}
        });
        openpop("pop4");
	}
	
	function submit_abnormal() {
        data_remk = document.getElementById("input5").value;
        data_type = "1";
        closepop("pop3");
		submit_normal();
	}
	
    function cleanform() {
		closepop("pop1");
		document.getElementById("input1").value="";
		document.getElementById("input2").value="";
		document.getElementById("input3").value="";
		document.getElementById("input4").value="";
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
		window.opener=null;window.open('','_self');window.close();
		WeixinJSBridge.invoke('closeWindow',{},function(res){});
	}

    function loadinfo() {
        <?php if($testmode){echo $teststr;} ?>
        var raw = "<?php echo $userinfo; ?>";
        var info = raw.split(",");   
        user_id = info[0];
        user_name = info[1];
        user_id1 = info[2];
        user_id2 = info[3];
        user_tel = info[4];
        user_img = info[0];
        document.getElementById("info1").innerHTML=user_name;
        document.getElementById("info2").innerHTML=user_id1;
        document.getElementById("info3").innerHTML=user_id2;
        document.getElementById("info4").innerHTML=user_tel;
        document.getElementById("info-img-content").src="./img/user/"+user_img+".jpg";
        loadview("view1");
    }
    </script>
</body>
</html>
