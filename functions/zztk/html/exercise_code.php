<?php
//by straydoggie

include_once '../../common/basis.php';
$code = $_GET ['code'];
$testmode = false;
if ($testmode) {
    $user = 'test';
} else {
    if ($code) {
        $url = "http://localhost/cdapp/functions/common/wx_access.php?mathod=verify_user&code={$code}";
        $user = file_get_contents($url);
        if ($user) {

        } else {
            echo file_get_contents("./forbidden.html");
            exit;
        }
    }else{
        echo file_get_contents("./forbidden.html");
        exit;
    }
}

$link = connect_db();
$sql = "SELECT * FROM lib_airportcodes";
$result = mysql_query($sql, $link);
$data = '';
while ($row = mysql_fetch_array($result)) {
    $data .= $row['province'] . ',' . $row['city'] . ',' . $row['icao'] . ',' . $row['iata'] . ';';
}
mysql_close($link);
$data = rtrim($data, ';');

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<meta charset="UTF-8" content="">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no"/>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>机场代码练习</title>
    <base href="http://cdapp.straydoggie.cn/functions/zztk/html/"/>
    <base target="_blank"/>
    <link href="css/exercise_code.css" rel="stylesheet" type="text/css"/>
</head>
<body oncontextmenu="return false;" onselectstart="return false">
<div id="container">
    <div id="view1">
        <div class="header" id="header1">
            <div class="header_text">练习内容</div>
        </div>
        <div class="content" id="content1">
            <br><br>
            <input class="button_round_blue" id="button1" type="button" value="四字码（ICAO）" onclick="setting('icao')">
            <br>
            <input class="button_round_grey" id="button2" type="button" value="三字码（IATA）" onclick="setting('iata')">
            <br><br><br><br>
            <!--<input class="button_round_blue" id="button3" type="button" value="练习" onclick="setting('prac')">
            <input class="button_round_grey" id="button4" type="button" value="测试" onclick="setting('test')">-->
            <br><br><br><br>
            <input class="button_round_blue" id="button_start" type="button" value="开始" onclick="fill()">
            <br><br>
        </div>
        <div class="blank_space" id="space1"></div>
        <div class="footer" id="footer1">
            <div class="footer_text">● 该功能仅限测试使用，请勿转载 ●</div>
        </div>
    </div>
    <div id="view2">
        <div class="header" id="header2">
            <div class="header_text_weak">练习内容</div>
            <div class="header_text" id="title2"></div>
        </div>
        <div class="content" id="content2">
            <br><br>
            <p class="text_standerd" id="question"></p>
            <br>
            <input class="textbox_round" id="answer" type="email" value="" onclick="" onchange="check(this.value)" onkeyup="check(this.value)" style="height: 40px;font-size: 32px">
            <br><br>
            <input class="button_round_blue" id="button_prev" type="button" value="上一个" onclick="go_prev()">
            <input class="button_round_blue" id="button_next" type="button" value="下一个" onclick="go_next()">
            <br><br>
        </div>
        <div class="blank_space" id="space2"></div>
        <div class="footer" id="footer2">
            <div class="footer_text">● 该功能仅限测试使用，请勿转载 ●</div>
        </div>
    </div>
    <div id="popup">
        <div class="popup_form" id="popform">
            <div class="popup_title" id="poptitle"></div>
            <div class="popup_text" id="poptext"></div>
            <div style="text-align: right">
                <input class="popup_button" id="popbutton" type="button" value="关闭" onclick="closepop()">
            </div>
        </div>
        <div class="popup_cover" id="popcover"></div>
    </div>
</div>
<script type="text/javascript">

    var container=document.getElementById("container");

    var view1 = document.getElementById("view1");
    var view2 = document.getElementById("view2");
    var view3 = document.getElementById("view3");

    var title2 = document.getElementById("title2");

    var content1 = document.getElementById("content1");
    var content2 = document.getElementById("content2");
    var content3 = document.getElementById("content3");

    var space1 = document.getElementById("space1");
    var space2 = document.getElementById("space2");
    var space3 = document.getElementById("space3");

    var button1 = document.getElementById("button1");
    var button2 = document.getElementById("button2");
    var button3 = document.getElementById("button3");
    var button4 = document.getElementById("button4");

    var button_start = document.getElementById("button_start");
    var button_prev = document.getElementById("button_prev");
    var button_next = document.getElementById("button_next");

    var text = document.getElementById("question");
    var textbox = document.getElementById("answer");

    var popcover= document.getElementById("popcover");
    var popform= document.getElementById("popform");
    var poptitle= document.getElementById("poptitle");
    var poptext= document.getElementById("poptext");

    var pageWidth = window.innerWidth;
    var pageHeight = window.innerHeight;


    var type = "icao";
    var mode = "test";
    var answer = "";
    var question = "";
    var data_str = "<?php echo $data; ?>";
    var data = data_str.split(";");
    var pointer = 0;

    layout("view1");

    function go_next() {
        if (pointer == (data.length - 1)) {
            showpop("╮(╯▽╰)╭","已经是最后一道题目了");
        } else {
            textbox.value="";
            pointer++;
            fill(pointer);
        }
    }

    function go_prev() {
        if (pointer == 0) {
            showpop("╮(╯▽╰)╭","已经是第一道题目了");
        } else {
            textbox.value="";
            pointer--;
            fill(pointer);
        }
    }
    
    function check(ans) {
        var input=document.getElementById("answer");
        input.value=input.value.toUpperCase();
        if ((type == "icao" && ans.length == 4) || (type == "iata" && ans.length == 3)) {
            ans=ans.toUpperCase();
            if (ans == answer) {
                go_next();
            } else {
                showpop("填写错误", question+"不是"+ans+"，<br>正确答案应为" + answer);
            }
        }else{

        }
    }


    function fill(p) {
        if (!p || typeof(p) == "undefined") {
            p = 0;
        }
        var pro=(p+1)+"/"+data.length;
        var arr = data[p].split(',');
        if (type == "icao") {
            title2.innerHTML='四字码'+pro;
            text.innerHTML = '请填写' + arr[0] + arr[1] + '的四字码';
            question = arr[0] + arr[1] + '的四字码';
            answer = arr[2];
        }
        else {
            title2.innerHTML='三字码'+pro;
            text.innerHTML = '请填写' + arr[0] + arr[1] + '的三字码';
            question = arr[0] + arr[1] + '的三字码';
            answer = arr[3];
        }
        layout("view2");
    }

    function layout(v) {
        var width_value_button1 = pageWidth * 0.8;
        var width_value_button2 = pageWidth * 0.4 - 10;

        if(!v){

        }else if(v=="view1"){
            space1.style.height="0px";
            view1.style.display="inline";
            view2.style.display="none";
            button1.style.width = width_value_button1 + "px";
            button2.style.width = width_value_button1 + "px";
            //button3.style.width = width_value_button2 + "px";
            //button4.style.width = width_value_button2 + "px";
            button_start.style.width = width_value_button1 + "px";
            space1.style.height=pageHeight-container.offsetHeight-10+"px";
        }else if(v=="view2"){
            space2.style.height="0px";
            view1.style.display="none";
            view2.style.display="inline";
            button_prev.style.width = width_value_button2 + "px";
            button_next.style.width = width_value_button2 + "px";
            textbox.style.width = width_value_button1 - 20 + "px";
            text.style.width = width_value_button1 + "px";
            space2.style.height=pageHeight-container.offsetHeight-10+"px";
        }
    }

    function setting(s){
        if(!s){

        }else if(s=="icao"){
            button1.className="button_round_blue";
            button2.className="button_round_grey";
            type=s;
        }else if (s=="iata"){
            button1.className="button_round_grey";
            button2.className="button_round_blue";
            type=s;
        }else if(s=="test"){
            button3.className="button_round_grey";
            button4.className="button_round_blue";
            mode=s;
        }else if(s=="prac"){
            button3.className="button_round_blue";
            button4.className="button_round_grey";
            mode=s;
        }else{

        }
    }
    
    function showpop(title,text) {
        poptitle.innerHTML = title;
        poptext.innerHTML = text;
        popcover.style.width = pageWidth + "px";
        popcover.style.height = pageHeight + "px";
        popform.style.width = pageWidth * 0.8 + "px";
        popform.style.top = (pageHeight - popform.offsetHeight) * 0.2 + "px";
        popcover.style.display = "inline";
        popform.style.display = "inline";
        textbox.blur();
    }
    function closepop(){
        popcover.style.display = "none";
        popform.style.display = "none";
    }

</script>
</body>
</html>