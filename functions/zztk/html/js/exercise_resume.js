var app_name = "";
var app_mark = "";
var theme_color_light = "";
var theme_color_dark = "";
var uid;
var gid;
var chapter;
var section;
var str_array = [];
var ans_array = [];
var title;
var counting;
var color_wrong = "#999999";
var color_dark = "#DDDDDD";
var color_light = "#000000";
var pointer = 0;

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
	document.title = app_name;
	loadcontent();
}
function loadcontent() {
	$.ajaxSetup({async : false});
	$.post("php/status_process.php?",
	{
		user:uid,
		type:"status_load",
	},
	function(data,status){
		var rawdata=data.split("#");
		gid = rawdata[0];
		title = rawdata[1];
		counting = rawdata[2];
		pointer = rawdata[3];
		chapter = rawdata[4];
		section = rawdata[5];
		ans_array = rawdata[6].substring(0,rawdata[6].length-1).split(",");
		str_array = rawdata[7].substring(0,rawdata[7].length-1).split(";");
	});
	if(gid=='')	{
		$('#content').html(GetHtml("ediv"));
	}
	else {
		$('#header').html("<h1>" + title + "</h1>");
		$('#footer').html("Powered by StrayDoggie, 2016, Chengdu.");
		$('#content').html(GetHtml("wdiv"));
		//document.getElementById("content").style.height = "auto";
		//document.getElementById("content").hidden = false;
		layout_b();
	}
}
function SaveStatus() {
	var content_str="";
	for(var i = 0;i<str_array.length;i++) {
		content_str+=chapter+"-"+section+"-"+str_array[i].split(",")[0]+"-"+ans_array[i];
		if(i<str_array.length-1){content_str+=";";}
	}
	var brief_str=title+";"+str_array.length+";"+pointer.toString();
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
	//alert(ans_array.length);
	for (var i = 0;i < ans_array.length;i++) {
		//alert(i);
		if(ans_array[i]=="X"){
			num_blank+=str_array[i].split(",")[0]+",";
			blncounting++;
		}
		else if(str_array[i].split(",")[6]!=ans_array[i]) {
			str_save+=chapter+"-"+section+"-"+str_array[i].split(",")[0]+"-"+ans_array[i]+";";
			errcounting++;
		}
	}
	var fin = true;
	if(blncounting>0) {
		num_blank=num_blank.substring(0,num_blank.length-1); 
		fin = confirm("之前跳过了第"+num_blank+"题还没有选择答案，确定要提交练习？");
	}
	if(fin){
		var str="此次练习中共包含"+ans_array.length.toString()+"道题，";
		str += "您一共做了"+(ans_array.length-blncounting).toString()+"道题，";
		str += "做错了"+errcounting.toString()+"道题，";
		var rate = 0;
		if(ans_array.length-blncounting==0){
			rate = 0;
		}
		else{
			rate = (1-errcounting/(ans_array.length-blncounting))*100;
		}
		str += "正确率"+rate.toString()+"%";
		if(errcounting>0){
			str += "，本次做错的题目已保存在“错题簿”中。";
			str_brief = title+","+errcounting.toString();
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
		$('#header').html("");
		$('#content').html(GetHtml("fdiv"));
		alert(str);
		
	}else{
		//$('#chatAudio')[0].play();
	}
}
function chose(chose) {
	if (ans_array[pointer] == "X") {
        ans_array[pointer] = chose;
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
		SaveStatus();
    }
    else {
        if(confirm("已经是第一题了，是否要跳转至最后一题？")){
			pointer = str_array.length - 1;
			layout_a();
			SaveStatus();
		}
        else{
			//$('#chatAudio')[0].play();
		}
    }
}
function jumpNext() {
    if (pointer < str_array.length - 1) {
        document.getElementById("jump_prev").style.color = color_light;
        pointer++;
        layout_a();
		SaveStatus();
    }
    else {
        if(confirm("已经是最后一题了，是否要提交练习结果？")){
			SaveResult();
		}
		else if(confirm("是否要跳转到第一题？")) {
			pointer = 0;
			layout_a();
			SaveStatus();
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
	collection_indicate();
    $('#swchAudio')[0].play();
	
}
function layout_b() {
    collection_refresh();
    var client_width = document.documentElement.clientWidth;
	
    document.getElementById("jump_goto").style.width = client_width / 4 + "px";  
	document.getElementById("jump_mark").style.width = client_width / 4 + "px";
	document.getElementById("jump_prev").style.width = client_width / 4 + "px";
    document.getElementById("jump_next").style.width = client_width / 4 + "px";
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
		//alert("Data: " + data + "\nStatus: " + status);
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
    case "footer_standard":
        str = "Powered by StrayDoggie, 2016, Chengdu.";
        break;
	case "ediv":
        str = "<div style='margin:50px 10px 150px 10px;'><p><h1><img src=\"images/icons_cut_0011_about.png\" height=\"28\" align=\"center\" style='padding-right:5px'>出错了</h1></p><p>未能查询到您上一次练习状态，请点击“新练习”开始使用。</p></div>";
        break;
	case "fdiv":
        str = "<div style='margin:50px 10px 150px 10px;'><p><h1><img src=\"images/icons_cut_0010_emoticon.png\" height=\"28\" align=\"center\" style='padding-right:5px'>已完成</h1></p><p>请点击“返回”离开此页。</p></div>";
        break;
    case "wdiv":
        str += "<p id=\"text\" style=\"padding:5px 10px 5px 10px;\"></p>";//text
		str += "<div class=\"chose\" id=\"chose_a\" onclick=\"chose('A')\"></div>";//selection a
		str += "<div class=\"chose\" id=\"chose_b\" onclick=\"chose('B')\" style='border-top:solid 1px #999999;'></div>";//selection b
		str += "<div class=\"chose\" id=\"chose_c\" onclick=\"chose('C')\" style='border-top:solid 1px #999999;'></div>";//selection c
		str += "<div class=\"chose\" id=\"chose_d\" onclick=\"chose('D')\" style='border-top:solid 1px #999999;'></div>";//selection d
		str += "<div class=\"part\"><div style='padding:10px 0px 0px 0px;'>&nbsp;</div>";
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