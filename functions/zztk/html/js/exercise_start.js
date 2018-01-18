var app_name = "";
var app_mark = "";
var theme_color_light = "";
var theme_color_dark = "";
var uid = "";
var gid = "";
var str = "";
var str_array = [];
var info_array = [];
var fav_array = [];
var value_chapter;
var value_section;
var value_from;
var value_to;
var value_count;
var value_random = 0;
var value_title;
var value_max;
var pointer = 0;
var color_wrong = "#999999";
var color_dark = "#DDDDDD";
var color_light = "#000000";
var debug = false;
var test = false; //true
var ansarr = [];
var volume = 1;
//document.getElementById("content").hidden=true;
$('<audio id="chatAudio"><source src="images/cgsys03s.mp3" type="audio/mpeg"></audio>').appendTo('body');
$('<audio id="intrAudio"><source src="images/cgsys07s.mp3" type="audio/mpeg"></audio>').appendTo('body');
$('<audio id="swchAudio"><source src="images/cgsys01s.mp3" type="audio/mpeg"></audio>').appendTo('body');

function GetHtml(id) {
    var str = "";
    switch (id) {
    case "footer":
        str = "Powered by StrayDoggie, 2016, Chengdu.<img id=\"logo\" src=\"images/mylogo_ws.png\" height=\"14\" align=\"top\" style='padding-left:3px'>";
        if (debug) {
            alert("footer");
        }
        break;
    case "footer_standard":
        str = "Powered by StrayDoggie, 2016, Chengdu.";
        if (debug) {
            alert("footer");
        }
        break;
    case "footer_depart":
        str = "<img id=\"logo\" src=\"images/atmb3.png\" height=\"14\" align=\"top\" style='padding-right:3px'> 成都终端管制室，2016年7月";
        if (debug) {
            alert("footer");
        }
        break;
    case "header":
        str = "<h1>User: " + uid + "</h1>";
        if (debug) {
            alert("header");
        }
        break;
    case "header_standard":
        str = "<div><div>&nbsp;</div><div style='font-size:28px;float:left'><img src=\"images/mylogo_ws.png\" height=\"36\" align=\"top\" style='padding-right:5px'><b>"+app_name+"</b></div><div style='text-align:right;font-size:12px;float:left;padding-left:5px'>"+app_mark+"</div><div style='padding-bottom:40px'>&nbsp;</div></div>";
        if (debug) {
            alert("header_standard");
        }
        break;
    case "wdiv":
        str += "<p id=\"text\" style=\"padding:5px 10px 5px 10px;\"></p>";//text
		str += "<div class=\"chose\" id=\"chose_a\" onclick=\"chose('A')\"></div>";//selection a
		str += "<div class=\"chose\" id=\"chose_b\" onclick=\"chose('B')\" style='border-top:solid 1px #999999;'></div>";//selection b
		str += "<div class=\"chose\" id=\"chose_c\" onclick=\"chose('C')\" style='border-top:solid 1px #999999;'></div>";//selection c
		str += "<div class=\"chose\" id=\"chose_d\" onclick=\"chose('D')\" style='border-top:solid 1px #999999;'></div>";//selection d
		str += "<div class=\"part\"><div style='padding:10px 0px 0px 0px;'>&nbsp;</div>";
		str += "<div class=\"ctl\" id=\"jump_back\" onclick=\"jumpBack()\" ><img src=\"images/icons_cut_0004_home.png\" height=\"30\" align=\"center\"></div>";
		str += "<div class=\"ctl\" id=\"jump_goto\" onclick=\"jumpGoto()\" ><img src=\"images/icons_cut_0002_search.png\" height=\"30\" align=\"center\"></div>";
		str += "<div class=\"ctl\" id=\"jump_mark\" onclick=\"jumpMark()\" ><img src=\"images/icons_cut_0009_mark.png\" height=\"30\" align=\"center\"></div>";
		//str += "<div class=\"ctl\" id=\"jump_note\" onclick=\"jumpGoto()\" ><img src=\"images/icons_cut_0008_edit.png\" height=\"30\" align=\"center\"></div>";
		str += "<div class=\"ctl\" id=\"jump_prev\" onclick=\"jumpPrev()\" ><img src=\"images/icons_cut_0006_front.png\" height=\"30\" align=\"center\"></div>";
		str += "<div class=\"ctl\" id=\"jump_next\" onclick=\"jumpNext()\" ><img src=\"images/icons_cut_0007_next.png\" height=\"30\" align=\"center\"></div>";
		str += "<div style='padding:10px 0px 0px 0px;'>&nbsp;</div></div>";
        break;
    }
    return str;
}

function fillHeader() {
    $('#header').html(GetHtml("header_standard"));
    $('#footer').html(GetHtml("footer_standard"));
}

function InitialView() {
    if (uid == "") {
        var view = "<div style='margin:50px 10px 150px 10px;'><p><h1><img src=\"images/cr2.png\" height=\"48\" align=\"center\" style='padding-right:15px'>此应用仅用于内部测试</h1></p><p>如果你看到此消息，说明你还未关注“成都终端管制室”公众平台，或微信号未经验证。</p><p>如果你是成都终端管制室成员，请关注“成都终端管制室”公众平台，并在“企业助手”中点击用户验证链接，根据提示输入姓名、手机、短信验证码进行验证。</p><p>如果你不是成都终端管制室成员，请在该应用正式公布后访问此页面，谢谢！</p></div>";
        $('#content').html(view);
        $('#footer').html(GetHtml("footer"));
    }
    else {
        //alert("此应用仅供测试使用，请勿转发链接");
        GetContentInfo();
    }
}

function GetContentInfo() {
    if (debug) {
		alert("get content info");
		
    }
	
    //document.getElementById("content").hidden = true;
	$.ajaxSetup({async : false});
    $.get("php/get_content_info.php", function (datainfo, status) {
		if(debug){alert(datainfo)};
        info_array = datainfo.split(";");
        var hdiv = "";
        for (var i = 0; i < info_array.length; i++) {
            var arr_i = info_array[i].split(",");
            if (arr_i[2] == 0) {
                var ch = arr_i[1];
                hdiv += "<div class='chapter'>" + arr_i[0] + "</div>";
                //if(debug){alert("charter="+arr_i[0]);}
                var cdiv = "<div class='part' id='ch" + ch + "'>";
                var cdiv_str = "";
                for (var j = 0; j < info_array.length; j++) {
                    var arr_j = info_array[j].split(",");
                    if (arr_j[1] == ch && arr_j[2] > 0) {
                        cdiv_str += arr_j[0] + ',' + arr_j[1] + ',' + arr_j[2] + ',' + arr_j[3] + ';';
                        //if(debug){alert("section="+arr_j[0]);}
                    }
                }
                var cdiv_strarray = cdiv_str.split(";");
                for (var m = 0; m < cdiv_strarray.length - 1; m++) {
                    //if(debug){alert("filling div");}
                    var tarr = cdiv_strarray[m].split(",");
                    var parameter = "\"" + tarr[0] + "\"," + tarr[1] + "," + tarr[2] + "," + tarr[3];
                    if (m == 0) {
                        cdiv += "<div class='section' id='se" + tarr[2] + "' onclick='GetConfig(" + parameter + ")'>" + tarr[0] + "</div>";
                    }
                    else {
                        cdiv += "<div class='section' id='se" + tarr[2] + "' onclick='GetConfig(" + parameter + ")' style='border-top:solid 1px #999999;'>" + tarr[0] + "</div>";
                    }
                }
                cdiv += "</div>";
                hdiv += cdiv;
            }
        }
        $('#content').html(hdiv);
        fillHeader();
        document.getElementById("content").style.height = "auto";
        document.getElementById("content").hidden = false;
        //$('#intrAudio')[0].play();
    });
}



function GetContent() {
    document.getElementById("content").hidden = true;
    var url = "php/get_content.php?";
    url += "chapter=" + value_chapter + "&";
    url += "section=" + value_section + "&";
    url += "from=" + value_from + "&";
    url += "to=" + value_to + "&";
    url += "count=" + value_count + "&";
    url += "random=" + value_random;
    if (debug) {
        alert(url);
    }
	$.ajaxSetup({async : false});
    $.get(url, function (datatext, status) {
        datatext = datatext.substring(0, datatext.length - 1);
        str_array = datatext.split(";");
        document.getElementById("content").hidden = false;
        var wdiv = GetHtml("wdiv");
        $('#content').html(wdiv);
        pointer = 0;
        //$('#intrAudio')[0].play();
		ansarr = new Array(str_array.length);
		for(var i = 0 ;i<str_array.length;i++) {
			ansarr[i]="X";
		}
		layout_b();
    });
    
}

function GetConfig(st, ch, se, ct) {
    value_title = st;
    value_chapter = ch;
    value_section = se;
    value_max = ct;
    var html_btn_cfg = "<div class='ctl1' id='getcfg' style='text-align: center;' onclick='checkValue()'>开始&nbsp;<img src=\"images/icons_cut_0007_next.png\" height=\"30\" align=\"center\" style='padding-right:10px;'></div>";
    var html_btn_bak = "<div class='ctl1' id='getbak' style='text-align: center;border-right:solid 1px #ffffff;' onclick='GetContentInfo()'><img src=\"images/icons_cut_0006_front.png\" height=\"30\" align=\"center\" style='padding-left:10px;'>&nbsp;返回</div>";
    var html_alert_info = "<p id='alertinfo' style='color:#faa;margin:10px 10px 10px 10px'>&nbsp;</p>";
	var html='';
	//
	html+="<div class= 'chose_part'>";
	html+="<div class = 'chose_normaltext'>《"+value_title+"》包含"+value_max.toString()+"道题目，请设置需要练习的部分：</div>";
	html+="</div>";
	//
	html+="<div class= 'chose_part'>";
	html+="<div class= 'chose_text'>起止题号</div>";
	html+="<input type='tel' class='input_one' id = 'fnum'></input>";
	html+="<div class= 'chose_text'>至</div>";
	html+="<input type='tel' class='input_one' id = 'tnum'></input>";
	html+="</div>";
	//
	html+="<div class='chose_clean'></div>";
	html+="<div class= 'chose_part'>";
	html+="<div class= 'chose_text'>抽取数量</div>";
	html+="<input type='tel' class='input_one' id = 'cnum'></input>";
	html+="<div class= 'chose_text'>道题</div>";
	html+="</div>";
	//
	html+="<div class='chose_clean'></div>";
	html+="<div class= 'chose_part'>";
	html+="<div class= 'chose_text'>抽取方式</div>";
	html+="<div class = 'chose_left' id = 'btn_a' onClick=\"btn_swtich('btn_a')\">顺序</div>";
	html+="<div class = 'chose_right' id = 'btn_b' onClick=\"btn_swtich('btn_b')\">乱序</div>";
	html+="</div>";
	//
	html+="<div class='chose_clean'></div>";
	html+="<div class= 'chose_part' style='display:none;'>";
	html+="<div class= 'chose_text'>声音设置</div>";
	html+="<div class = 'chose_left' id = 'btn_c' onClick=\"btn_swtich('btn_c')\">开启</div>";
	html+="<div class = 'chose_right' id = 'btn_d' onClick=\"btn_swtich('btn_d')\">关闭</div>";
	html+="</div>";
	html+="<br>";
    var fdiv = html + html_btn_bak + html_btn_cfg +html_alert_info;
	
    $('#content').html(fdiv);
	btn_appear();
    document.getElementById("fnum").value = 1;
    document.getElementById("tnum").value = value_max;
    document.getElementById("cnum").value = value_max;
    $('#header').html("<h1>" + value_title + "</h1>");
    var client_width = document.documentElement.clientWidth;
    document.getElementById("getcfg").style.width = client_width / 2 + "px";
    document.getElementById("getbak").style.width = client_width / 2 - 1 + "px";
    //$('#intrAudio')[0].play();
	
}
function btn_swtich(btn){
	switch(btn){
		case 'btn_a':
		value_random=0;
		break;
		case 'btn_b':
		value_random=1;
		break;
		case 'btn_c':
		volm=1;
		break;
		case 'btn_d':
		volm=0;
		break;
	}
	btn_appear();
}
function btn_appear(){
	var btn_a =document.getElementById('btn_a');
	var btn_b =document.getElementById('btn_b');
	var btn_c =document.getElementById('btn_c');
	var btn_d =document.getElementById('btn_d');
	
	btn_a.style.backgroundColor=theme_color_light;
	btn_b.style.backgroundColor=theme_color_light;
	btn_c.style.backgroundColor=theme_color_light;
	btn_d.style.backgroundColor=theme_color_light;
	
	btn_a.style.color='#ffffff';
	btn_b.style.color='#ffffff';
	btn_c.style.color='#ffffff';
	btn_d.style.color='#ffffff';
	
	
	if(value_random==0){
		btn_a.style.backgroundColor='#ffffff';
		btn_a.style.color=theme_color_light;
	}else{
		btn_b.style.backgroundColor='#ffffff';
		btn_b.style.color=theme_color_light;
	}
	/*
	if(volm==1){
		btn_c.style.backgroundColor=theme_color_light;
		btn_c.style.color='#0066cc';
	}else{
		btn_d.style.backgroundColor=theme_color_light;
		btn_d.style.color='#0066cc';
	}
	*/
}
function checkValue() {
    value_from = parseInt(document.getElementById("fnum").value);
    value_to = parseInt(document.getElementById("tnum").value);
    value_count = parseInt(document.getElementById("cnum").value);
    var info = "";
    var cut = value_to - value_from + 1;
    if (value_from >= value_to) {
        info = "起止题号输入有误";
    }
    else if (value_from >= value_max) {
        info = "起止题号输入有误";
    }
    else if (value_from < 1) {
        info = "起止题号输入有误";
    }
    else if (value_to > value_max) {
        info = "起止题号输入有误";
    }
    else if (value_to <= 1) {
        info = "起止题号输入有误";
    }
    else if (value_count > cut) {
        info = "抽取数量有误，范围：1~" + cut.toString();
    }
    if (info == "") {
        document.getElementById("content").hidden = true;
        if (debug) {
            alert("chapter" + value_chapter + ",section" + value_section + ",from" + value_from + ",to" + value_to + ",count" + value_count + ",random" + value_random);
        }
        GetContent();
    }
    else {
        //$('#alertinfo').html(info);
		alert(info);
    }
}

function chose(chose) {
	if (ansarr[pointer] == "X") {
        ansarr[pointer] = chose;
    }
    if (str_array[pointer].split(",")[6] == chose) {
        jumpNext();
    }
    else {
        $('#chatAudio')[0].play();
        switch (chose) {
        case "A":
            document.getElementById("chose_a").style.color = color_wrong;
            break;
        case "B":
            document.getElementById("chose_b").style.color = color_wrong;
            break;
        case "C":
            document.getElementById("chose_c").style.color = color_wrong;
            break;
        case "D":
            document.getElementById("chose_d").style.color = color_wrong;
            break;
        }
    }
}
function jumpBack() {
			
	if(confirm("确定要退出练习？")) {
    	GetContentInfo();
	}
}

function jumpGoto() {
	var n = prompt("请输入需要跳转到的题目序号", "");
	if(n){
		var found = false;
		for(var i=0;i<str_array.length;i++) {
			if(str_array[i].split(",")[0]==n){
				found=true;
				pointer = i;
				layout_a();	
				break;
			}
		}
		if(!found){
			alert("没有找到所输入的题号:"+n);
		}
	}
}
function jumpPrev() {
    if (pointer > 0) {
        document.getElementById("jump_next").style.color = color_light;
        pointer--;
        layout_a();
    }
    else {
		if(confirm("已经是第一题了，是否要跳转至最后一题？")){
			pointer = str_array.length - 1;
			layout_a();
		}
        else{
			//$('#chatAudio')[0].play();
		}
        //document.getElementById("jump_prev").style.color = color_wrong;
    }
}

function jumpNext() {
    if (pointer < str_array.length - 1) {
        document.getElementById("jump_prev").style.color = color_light;
        pointer++;
        layout_a();
    }
    else {
		if(confirm("已经是最后一题了，是否要提交练习结果？")){
			SaveResult();
		}
		else if(confirm("是否要跳转到第一题？")) {
			pointer = 0;
			layout_a();
		}
		else{
			//$('#chatAudio')[0].play();
			//document.getElementById("jump_next").style.color = color_wrong;
		}
    }
}
function jumpMark(){
	collection_switch();	
}
function layout_a() {
    $('#text').html(str_array[pointer].split(",")[0] + "." + str_array[pointer].split(",")[1]);
    if (str_array[pointer].split(",")[4] == "" && str_array[pointer].split(",")[5] == "") {
        $('#chose_a').html("T：对");
        $('#chose_b').html("F：错");
        document.getElementById("chose_c").hidden = true;
        document.getElementById("chose_d").hidden = true;
    }
    else {
        document.getElementById("chose_c").hidden = false;
        document.getElementById("chose_d").hidden = false;
        $('#chose_a').html("A：" + str_array[pointer].split(",")[2]);
        $('#chose_b').html("B：" + str_array[pointer].split(",")[3]);
        $('#chose_c').html("C：" + str_array[pointer].split(",")[4]);
        $('#chose_d').html("D：" + str_array[pointer].split(",")[5]);
    }
    document.getElementById("chose_a").style.color = color_light;
    document.getElementById("chose_b").style.color = color_light;
    document.getElementById("chose_c").style.color = color_light;
    document.getElementById("chose_d").style.color = color_light;
    $('#swchAudio')[0].play();
	collection_indicate();
	SaveStatus();
}

function layout_b() {
	collection_refresh();
    var client_width = document.documentElement.clientWidth;
	var btn_counting = 5;
    document.getElementById("jump_back").style.width = client_width / btn_counting + "px";
	document.getElementById("jump_goto").style.width = client_width / btn_counting + "px";
    document.getElementById("jump_prev").style.width = client_width / btn_counting + "px";
	//document.getElementById("jump_note").style.width = client_width / btn_counting + "px";
	document.getElementById("jump_mark").style.width = client_width / btn_counting + "px";
    document.getElementById("jump_next").style.width = client_width / btn_counting + "px";
	layout_a();
}


function SaveStatus() {
	var content_str="";
	for(var i = 0;i<str_array.length;i++) {
		content_str+=value_chapter+"-"+value_section+"-"+str_array[i].split(",")[0]+"-"+ansarr[i];
		if(i<str_array.length-1){content_str+=";";}
	}
	var brief_str=value_title+";"+str_array.length+";"+pointer.toString();
	$.ajaxSetup({async : true});
	$.post("php/status_process.php?",
	  {
		guid:gid,
		user:uid,
		type:"status_save",
		brief:brief_str,
		detail:content_str,
	  },
	  function(data,status) {
		//alert("Data: " + data + "\nStatus: " + status);
	  });
}
function SaveResult() {
	var str_brief = "";
	var str_save = "";
	var num_blank = "";
	var blncounting = 0;
	var errcounting = 0;
	for (var i = 0;i < ansarr.length;i++) {
		if(ansarr[i]=="X"){
			num_blank+=str_array[i].split(",")[0]+",";
			blncounting++;
		}
		else if(str_array[i].split(",")[6]!=ansarr[i]) {
			str_save+=value_chapter+"-"+value_section+"-"+str_array[i].split(",")[0]+"-"+ansarr[i]+";";
			errcounting++;
		}
	}
	var fin = true;
	if(blncounting>0) {
		num_blank=num_blank.substring(0,num_blank.length-1); 
		fin = confirm("之前跳过了第"+num_blank+"题还没有选择答案，确定要提交练习？");
	}
	if(fin){
		var str="此次练习中共包含"+ansarr.length.toString()+"道题，";
		str += "您一共做了"+(ansarr.length-blncounting).toString()+"道题，";
		str += "做错了"+errcounting.toString()+"道题，";
		var rate = 0;
		
		if(ansarr.length-blncounting==0){
			rate = 0;
		}
		else{
			rate = (1-errcounting/(ansarr.length-blncounting))*100;
		}
		str += "正确率"+rate.toString()+"%";
		if(errcounting>0){
			str += "，本次做错的题目已保存在“错题簿”中。";
			str_brief = value_title+","+errcounting.toString();
			str_save=str_save.substring(0,str_save.length-1); 
			$.ajaxSetup({async : true});
			$.post("php/status_process.php?",
			  {
				guid:gid,
				user:uid,
				type:"result_save",
				brief:str_brief,
				detail:str_save,
			  },
			  function(data,status) {
				//alert("Data: " + data + "\nStatus: " + status);
			  });
		}
		else{
			$.ajaxSetup({async : true});
			$.post("php/status_process.php?",
			  {
				user:uid,
				type:"status_delete",
			  },
			  function(data,status) {
				//alert("Data: " + data + "\nStatus: " + status);
			  });
		}
		alert(str);
		GetContentInfo();
	}else{
		//$('#chatAudio')[0].play();
	}
}
function collection_refresh(){
	$.ajaxSetup({async : false});
	$.post("php/status_process.php?",
	  {
		user:uid,
		type:"fav_load",
	  },
	  function(data,status) {
		  fav_array=data.split(',');
		  //collection_indicate();
		//alert("Data: " + data + "\nStatus: " + status);
	  });
}
function collection_switch(){
	
	var faved = false;
	var favidx = -1;
	var thisid=value_chapter+'-'+value_section +'-'+str_array[pointer].split(',')[0];
	if(fav_array.length>0){
		for(var i=0;i<fav_array.length;i++){
			if(fav_array[i]==thisid){
				favidx = i;
				faved = true;
				break;
			}
		}
	}
	if(faved){
		fav_array.splice(favidx,1);
		alert('已从收藏夹移除！');
	}else{
		fav_array.push(thisid);
		alert('已加入收藏夹！');
	}
	collection_indicate();
	var fav_str=fav_array.join();
	if(fav_str.substring(0,1)==','){fav_str=fav_str.substring(1,fav_str.length);}
	if(fav_str.substring(fav_str.length-1,1)==','){fav_str=fav_str.substring(0,fav_str.length-1);}
	$.ajaxSetup({async : true});
	$.post("php/status_process.php?",
	  {
		user:uid,
		type:"fav_save",
		detail:fav_str,
	  },
	  function(data,status) {
		//alert("Data: " + data + "\nStatus: " + status);
	  });
}
function collection_indicate(){
	
	var faved = false;
	var thisid=value_chapter+'-'+value_section +'-'+str_array[pointer].split(',')[0];
	if(fav_array.length>0){
		for(var i=0;i<fav_array.length;i++){
			if(fav_array[i]==thisid){
				faved = true;
				break;
			}
		}
	}
	if(faved){
		document.getElementById("jump_mark").innerHTML="<img src=\"images/icons_cut_0000_delete.png\" height=\"30\" align=\"center\">";
	}else{
		document.getElementById("jump_mark").innerHTML="<img src=\"images/icons_cut_0009_mark.png\" height=\"30\" align=\"center\">";
	}
}
function start() {
    if (test) {
        uid = "test";
        GetContentInfo();
    }
    else {
		uid = document.getElementById("suid").innerHTML;
		gid = document.getElementById("sgid").innerHTML;
		app_name = document.getElementById("appn").innerHTML;
		app_mark = document.getElementById("appm").innerHTML;
		theme_color_light = document.getElementById("cr_l").innerHTML;
		theme_color_dark = document.getElementById("cr_d").innerHTML;
		document.getElementById("inform").innerHTML="";
		document.title = app_name;
		document.getElementsByTagName("body")[0].style.backgroundColor = theme_color_light;
		document.getElementById("footer").style.color = theme_color_dark;
        InitialView();
    }
}
start();