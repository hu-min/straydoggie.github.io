var app_name = "";
var app_mark = "";
var theme_color_light = "";
var theme_color_dark = "";
var uid;
var gid;
var chapter;
var section;
var brf_array = [];
var gid_array = [];
var str_array = [];
var ans_array = [];
var title;
var counting;
var color_wrong = "#999999";
var color_dark = "#DDDDDD";
var color_light = "#000000";
var pointer = 0;
var delete_guid_bool = false;

$('<audio id="chatAudio"><source src="images/cgsys03s.mp3" type="audio/mpeg"></audio>').appendTo('body');
$('<audio id="intrAudio"><source src="images/cgsys07s.mp3" type="audio/mpeg"></audio>').appendTo('body');
$('<audio id="swchAudio"><source src="images/cgsys01s.mp3" type="audio/mpeg"></audio>').appendTo('body');

function start() {
	uid = document.getElementById("suid").innerHTML;
	app_name = document.getElementById("appn").innerHTML;
	app_mark = document.getElementById("appm").innerHTML;
	theme_color_light = document.getElementById("cr_l").innerHTML;
	theme_color_dark = document.getElementById("cr_d").innerHTML;
	document.getElementById("inform").innerHTML="";
	document.getElementsByTagName("body")[0].style.backgroundColor = theme_color_light;
	document.getElementById("footer").style.color = theme_color_dark;
	document.title = app_name+" - 错题簿";
	$('#footer').html("Powered by StrayDoggie, 2016, Chengdu.");
	$('#header').html(GetHtml("header_review"));
	load_brief();
}
function restart(){
	$('#header').html(GetHtml("header_review"));
	load_brief();
}
function load_brief() {
	$.ajaxSetup({async : false});
	$.post("php/status_process.php?",
	{
		user:uid,
		type:"result_list",
	},
	function(data,status){
		//alert(data);
		if(data.length>0){
			brf_array=data.substring(0,data.length-1).split(";");
			show_brief();
		}else{
			$('#header').html(GetHtml(""));
			$('#content').html(GetHtml("ediv"));
		}
	});
}
function show_brief()
{
	
	var html_str = "";
	for(var i=0;i<brf_array.length;i++) {
		html_str+=cell_brief(i);
	}
	
	$('#content').html(html_str);
}
function cell_brief(idx){
	var data = brf_array[idx].split(",");
	var html_str = "";
	if(idx==0){
		html_str+="<div class='section2'>";
	}else{
		html_str+="<div class='section2' style='border-top:solid 1px #999999;'>";
	}
	html_str+="<div onclick=\"delete_guid("+idx.toString()+")\" style='padding-top:5px;float:right;'>";
	html_str+="<img src=\"images/icons_cut_0005_drafts_alt.png\" height=\"36\" align=\"center\"></div>";
	html_str+="<div class='part_blank2' onclick=\"select_guid("+idx.toString()+")\" >记录"+(idx+1).toString()+"："+data[1]+"</div>";
	html_str+="<div class='tip' onclick=\"select_guid("+idx.toString()+")\" >包含"+data[2]+"道错题，记录时间："+data[3]+"</div>";
	html_str+="</div>";
	return html_str;
}
function select_guid(idx){
	//alert("select guid = "+idx+" !");
	var data = brf_array[idx].split(",");
	gid=data[0];
	title=data[1];
	counting=data[2];
	load_content();
}
function delete_guid(idx){
	var data = brf_array[idx].split(",");
	var dspname="记录"+(idx+1).toString()+"："+data[1];
	if(confirm("确定要删除“"+dspname+"”？")){
		//result_delete
		sguid=data[0];
		$.ajaxSetup({async : false});
		$.post("php/status_process.php?",
		{
			guid:sguid,
			type:"result_delete",
		},
		function(data,status){
			
		});
		restart();
	}
}
function load_content() {
	
	$.ajaxSetup({async : false});
	$.post("php/status_process.php?",
	{
		guid:gid,
		type:"result_load",
	},
	function(data,status){
		var rawdata=data.split("#");
		ans_array = rawdata[0].substring(0,rawdata[0].length-1).split(",");
		str_array = rawdata[1].substring(0,rawdata[1].length-1).split(";");
		chapter = rawdata[2];
		section = rawdata[3];
	});
	$('#header').html("<h1>" + title + "</h1>");
	$('#content').html(GetHtml("wdiv"));
	layout_b();
}
function chose(chose) {
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
function jumpBack(){
	if(confirm("要退出练习吗？")) {
		restart();
	}
}
function jumpGoto() {
	var n = prompt("请输入需要跳转到的序号", "");
	if(n){
		if(str_array[n-1]!=null){
			pointer = n-1;
			layout_a();
		}else{
			alert("没有找到所输入的序号:"+n);
		}
	}
}
function jumpPrev() {
    if (pointer > 0) {
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
    }
}
function jumpNext() {
    if (pointer < str_array.length - 1) {
        pointer++;
        layout_a();
    }
    else {
        if(confirm("已经是最后一题了，是否要跳转到第一题？")) {
			pointer = 0;
			layout_a();
		}
		else{
			//$('#chatAudio')[0].play();
		}
    }
}
function jumpMark() {
	collection_switch();
}
function layout_a() {
    $('#text').html(str_array[pointer].split(",")[0] + "." + str_array[pointer].split(",")[1]);
    if (str_array[pointer].split(",")[4] == "" && str_array[pointer].split(",")[5] == "") {
        $('#chose_a').html("T43：对");
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
	switch(ans_array[pointer]){
		case 'A':
		document.getElementById("chose_a").style.color = color_wrong;
		break;
		case 'B':
		document.getElementById("chose_b").style.color = color_wrong;
		break;
		case 'C':
		document.getElementById("chose_c").style.color = color_wrong;
		break;
		case 'D':
		document.getElementById("chose_d").style.color = color_wrong;
		break;
		default:
		break;
	}
	collection_indicate();
    $('#swchAudio')[0].play();
	
}
function layout_b() {
    collection_refresh();
    var client_width = document.documentElement.clientWidth;
	document.getElementById("jump_back").style.width = client_width / 5 + "px";
	document.getElementById("jump_goto").style.width = client_width / 5 + "px";
	document.getElementById("jump_mark").style.width = client_width / 5 + "px";
	document.getElementById("jump_prev").style.width = client_width / 5 + "px";
    document.getElementById("jump_next").style.width = client_width / 5 + "px";
	layout_a();
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
	  });
}
function collection_switch(){
	
	var faved = false;
	var thisid=chapter+'-'+section +'-'+str_array[pointer].split(',')[0];
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
	var thisid=chapter+'-'+section +'-'+str_array[pointer].split(',')[0];
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
function GetHtml(id) {
    var str = "";
    switch (id) {
	case "header_standard":
        str = "<div><div>&nbsp;</div><div style='font-size:28px;float:left'><img src=\"images/mylogo_ws.png\" height=\"36\" align=\"top\" style='padding-right:5px'><b>"+app_name+"</b></div><div style='text-align:right;font-size:12px;float:left;padding-left:5px'>"+app_mark+"</div><div style='padding-bottom:40px'>&nbsp;</div></div>";
        break;
	case "header_review":
        str = "<div><div>&nbsp;</div><div style='font-size:28px;float:left'><img src=\"images/mylogo_ws.png\" height=\"36\" align=\"top\" style='padding-right:5px'><b>"+app_name+"</b></div><div style='text-align:right;font-size:12px;float:left;padding-left:5px'>"+app_mark+"</div><div style='padding-bottom:40px'>&nbsp;</div><p>请选择一条记录进行练习：</p></div>";
        break;
    case "footer_standard":
        str = "Powered by StrayDoggie, 2016, Chengdu.";
        break;
	case "ediv":
        str = "<div style='margin:50px 10px 150px 10px;'><p><h1><img src=\"images/icons_cut_0011_about.png\" height=\"28\" align=\"center\" style='padding-right:5px'>错题簿是空的~</h1></p><p>未能查询到您的错题簿，请点击“新练习”开始使用。</p></div>";
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
		str += "<div class=\"ctl\" id=\"jump_prev\" onclick=\"jumpPrev()\" ><img src=\"images/icons_cut_0006_front.png\" height=\"30\" align=\"center\"></div>";
		str += "<div class=\"ctl\" id=\"jump_next\" onclick=\"jumpNext()\" ><img src=\"images/icons_cut_0007_next.png\" height=\"30\" align=\"center\"></div>";
		str += "<div style='padding:10px 0px 0px 0px;'>&nbsp;</div></div>";
        break;
    }
    return str;
}
start();