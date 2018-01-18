<?php
// by straydoggie
include_once "../php/performance.php";
include_once "../php/respond.support.php";
include_once '../../common/basis.php';

$code = $_GET ['code'];
$test = false;
if ($test) {
    $content = preview(1000);
} else {
    $member = verify($code);
    if(!$member){
        echo 'Forbidden';
        exit;
    }
    $content = preview($member[3]);
}
$lines = gen_lines($content);
$res = gen_res($content);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<meta charset="UTF-8" content="">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no"/>
<meta name="apple-mobile-web-app-capable" content="yes"/>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>航线实习</title>
    <base href="http://straydoggie.cn:81/cdapp/functions/hxsx/html/"/>
    <base target="_blank"/>
    <link href="css/<?php echo $css_name; ?>" rel="stylesheet" type="text/css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
</head>
<body oncontextmenu="return false;" onselectstart="return false">
<div id="container">
    <div id="view">
        <div id="header" class="header">
            <div class="header_text"><?php echo $member[1]; ?></div>

        </div>
        <div id="content" class="content">
            <?php echo $lines; ?>
        </div>
    </div>
    <div id="popups">
        <div id="pop1" class="popup_a">
            <p class="pop1_title"><img class="icon-s" src="img/icon/ico_0013_about.png">拒绝申请</p>
            <br>
            <p class="popup_text">原因</p>
            <textarea class="input_textarea" id="input5"></textarea>
            <div class="cmd-line-main-popup" onClick="submit_rejected()">拒绝<img class="icon-s"
                                                                                src="img/icon/ico_0004_forward.png">
            </div>
            <div class="cmd-line-popup" onClick="closepop('pop1')">取消</div>
        </div>

        <div id="pop2" class="popup_a">
            <div id="pop2_title" class="popup_title"></div>
            <div id="pop2_content" class="popup_text"></div>
            <input id="pop2_button1" class="popup_button_grey" type="button" value="取消" onclick="closepop('pop2')"
                   style="padding: 10px 10px">
            <input id="pop2_button2" class="popup_button_orange" type="button" value="拒绝" onclick="closepop('pop2')"
                   style="padding: 10px 10px">
            <input id="pop2_button3" class="popup_button_blue" type="button" value="同意" onclick="closepop('pop2')"
                   style="padding: 10px 30px">
        </div>
        <div id="pop3" class="popup_a">
            <div class="popup_title" style="text-align: center;padding: 30px 60px">正在提交数据</div>
        </div>
        <div class="popup_cover" id="popcover"></div>
    </div>
</div>
<script type="text/javascript">
    //Statics
    var wd = window.innerWidth;
    var ht = window.innerHeight;
    var content = document.getElementById("content");
    var header = document.getElementById("header");
    var contentHT = 0;
    var param=<?php echo $member[3]; ?>;
    var status=<?php echo $member[4]; ?>;
    //Contents
    var raw = "<?php echo $res ?>";
    var res = new Array();
    var raw_arr = raw.split(";");
    for (var i = 0; i < raw_arr.length; i++) {
        var arr = raw_arr[i].split(",");
        res.push(arr);
    }
    //setting ajax
    $.ajaxSetup({
        async: false
    });
    //Functions
    function respond(g) {
        for (var i = 0; i < res.length; i++) {
            if (res[i][0] == g) {
                pop_respond(res[i]);
                break;
            }
        }
    }
    function pop_respond(c) {
        contentHT = content.offsetHeight;
        content.style.height = ht - header.offsetHeight + "px";
        content.style.overflow = "hidden";
        var popform = document.getElementById("pop2");
        var popcover = document.getElementById("popcover");
        var poptitle = document.getElementById("pop2_title");
        var popcontent = document.getElementById("pop2_content");
        //var button_cancel = document.getElementById("pop2_button1");
        var button_reject = document.getElementById("pop2_button2");
        var button_approve = document.getElementById("pop2_button3");
        poptitle.innerHTML = "Respond";
        var type_str = "特殊申请";
        if (c[10] == "0") {
            type_str = "普通申请";
        }
        //双箭头：⇄，⇌
        var html =
			"<b>申请编号： </b>" + c[0].split('-')[0] +
            "<br><b>姓　　名： </b>" + c[3] +
            "<br><b>提交日期： </b>" + c[1].split(" ")[0] +
            "<br><b>实习日期： </b>" + c[4] + " 至 " + c[5] +
            "<br><b>实习地点： </b>" + c[6] + "、" + c[7] + "、" + c[8] + "、" + c[9] +
            "<br><b>实习航线： </b>" + c[6] + "⇌" + c[7] + "、" + c[6] + "⇌" + c[8] +
            "<br>　　　　　 " + c[6] + "⇌" + c[9] + "、" + c[7] + "⇌" + c[8] +
            "<br>　　　　　 " + c[7] + "⇌" + c[9] + "、" + c[8] + "⇌" + c[9] +
            "<br><b>申请类型： </b>" + type_str +
            "<br><b>备　　注： </b>" + c[11] +
            "<br><b>近期申请： </b>" + c[12];
        popcontent.innerHTML = html;
        button_approve.onclick = function () {
            approve(c[0]);
        };
        button_reject.onclick = function () {
            reject_popup(c[0]);
        };
        popform.style.display = "inline";
        popform.style.width = wd - 40 + "px";
        popform.style.left = 20 + "px";
        popform.style.top = (ht - popform.offsetHeight) * 0.4 + "px";
        popcover.style.display = "inline";
        popcover.style.width = wd + "px";
        popcover.style.height = ht + "px";
    }
    function showpopinfo(v) {
        var popform = document.getElementById(v);
        var popinfo = document.getElementById("pop3");
        popform.style.display = "none";
        popinfo.style.display = "inline";
        popinfo.style.top = (ht - popinfo.offsetHeight) * 0.4 + "px";
        popinfo.style.left = (wd - popinfo.offsetWidth) * 0.5 + "px";
    }
    function approve(id) {

        showpopinfo("pop2");

        var str = id + "***" + status + "***" + "无";
        var php = "http://straydoggie.cn:81/cdapp/functions/hxsx/php/receive.php";
        $.get(php, {type: "update", data: str}, function (data) {
            //alert(data);
            if (data != "success") {
                alert("Operation Failed !!!\n" + data);
            } else {
                //alert(data);
            }
        });

        refresh();

        closepop('pop3');

    }
    function reject_popup(id) {

    }
    function reject_close() {

    }
    function reject(id) {
        alert(id);
    }
    function refresh() {
        var php = "http://straydoggie.cn:81/cdapp/functions/hxsx/php/respond.support.php";
        $.get(php, {action: "line", param: param}, function (data) {
            //alert(data);
            content.innerHTML = data;
        });
        $.get(php, {action: "res", param: param}, function (data) {
            //alert(data);
            raw = data;
            res = new Array();
            raw_arr = raw.split(";");
            for (var i = 0; i < raw_arr.length; i++) {
                var arr = raw_arr[i].split(",");
                res.push(arr);
            }
        });
    }

    function openpop(v) {
        contentHT = content.offsetHeight;
        content.style.height = ht - header.offsetHeight + "px";
        content.style.overflow = "hidden";
        var popdiv = document.getElementById(v);
        var popcov = document.getElementById("popcover");
        popdiv.style.display = "inline";
        popdiv.style.width = wd - 60 + "px";
        popdiv.style.left = 30 + "px";
        popdiv.style.top = (ht - popdiv.offsetHeight) * 0.3 + "px";
        popcov.style.display = "inline";
        popcov.style.width = wd + "px";
        popcov.style.height = ht + "px";
    }
    function closepop(v) {
        content.style.overflow = "visible";
        content.style.height = contentHT;
        document.getElementById(v).style.display = "none";
        document.getElementById("popcover").style.display = "none";
    }
    function closeapp() {
        window.opener = null;
        window.open('', '_self');
        window.close();
        WeixinJSBridge.invoke('closeWindow', {}, function (res) {
        });
    }
</script>
</body>
</html>