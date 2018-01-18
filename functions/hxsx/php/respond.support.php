<?php
$code = $_GET ['code'];
$action=$_GET ['action'];
$param=$_GET ['param'];

include_once '../../common/basis.php';
include_once '../php/performance.php';

//echo review(1000);
//print_r (review(1000));
//print_r(review(verify($code)['status']));

if($action=="line"){
    echo gen_lines(preview($param));
}
if($action=="res"){
    echo gen_res(preview($param));
}

function verify($usercode){
    global $members;
    $member=array();
    if($usercode) {
        $url = "http://192.168.1.14/cdapp/functions/common/wx_access.php?mathod=verify_user&code={$usercode}";
        $mid = file_get_contents($url);
        $mid = clearbom($mid);
        $member[] = $mid;
        journal('verify',$member[0]);
    }else{
        journal('verify','misscode');
        return null;
    }
    if(!$member){
        journal('verify',$member);
        return null;
    }elseif($mid==$members['d0']){
        $member[]='终端审批';
        $member[]='d0';
        $member[]=1000;
        $member[]=1500;
    }elseif($mid==$members['d1']){
        $member[]='一室审批';
        $member[]='d1';
        $member[]=10;
        $member[]=1000;
    }elseif($mid==$members['d2']){
        $member[]='二室审批';
        $member[]='d2';
        $member[]=20;
        $member[]=1000;
    }elseif($mid==$members['d3']){
        $member[]='综合业务室审批';
        $member[]='d3';
        $member[]=30;
        $member[]=1000;
    }elseif($mid==$members['a0']){
        $member[]='综合业务室处理';//[1]
        $member[]='a0';//[2]
        $member[]=1500;//[3]
        $member[]=1900;//[4]
    }else{
        journal('verify','missmember');
        return null;
    }
    journal('verify',$member[1]);
    return $member;
}

function preview($s){
    $resource=array();
    $link=connect_db();
    $sql="select * from hxsx_applications where status = {$s}";
    //echo $sql.'<br>';
    $result=mysql_query($sql,$link);
    while($row=mysql_fetch_row($result)){
        $brief=brief($row[2]);
        if(strlen($row[14])>0){
            $remark=$row[14];
        }else{
            $remark='无';
        }
        $resource[]=array(
            $row[0],$row[1],$row[2],$row[3],//guid0,time1,user2,name3
            $row[7],$row[8],//date range4-5
            $row[9],$row[10],$row[11],$row[12],//destinations6-9
            $row[13],$remark,$brief);//type10,remark11,recent12
    }
    mysql_close($link);
    return $resource;
}

function brief($u){
    $url = "http://192.168.1.14/cdapp/functions/hxsx/php/receive.php?type=recent_brief&data={$u}";
    $content = file_get_contents($url);
    $arr=explode(';',$content);
    $brief='';
    if(strlen($content)>0&&count($arr)>0) {
        for ($i = 0; $i < count($arr); $i++) {
            $arr_brief = explode(',', $arr[$i]);
            $brief .= $arr_brief[0] . " 至 " . $arr_brief[1] . "，前往：" . $arr_brief[3] . "、" . $arr_brief[4] . "、" . $arr_brief[5] . "";
        }
        return $brief;
    }else{
        return '无';
    }
}

function detail($g){
    //get content of this time
    $data=array();
    $url = "http://192.168.1.14/cdapp/functions/hxsx/php/receive.php?type=content&data={$g}";
    $content = file_get_contents($url);
    $arr=explode(',',$content);
    $data['user']=$arr[1];
    $data['user']=$arr[1];
    $data['name']=$arr[2];
    $data['date1']=$arr[6];
    $data['date2']=$arr[7];
    $data['dest1']=$arr[8];
    $data['dest2']=$arr[9];
    $data['dest3']=$arr[10];
    $data['dest4']=$arr[11];
    $data['type']=$arr[12];
    $data['remark']=$arr[13];
    if($data['remark']==null||$data['remark']==""){
        $data['remark']="无";
    }

//get brief of recent
    $url = "http://192.168.1.14/cdapp/functions/hxsx/php/receive.php?type=recent_brief&data={$data['user']}";
    $content = file_get_contents($url);
    $arr=explode(';',$content);
    $brief="";
    for($i=0;$i<count($arr);$i++){
        $arr_brief=explode(',',$arr[$i]);
        $brief.=$arr_brief[0]." 至 ".$arr_brief[1]."，前往：".$arr_brief[3]."、".$arr_brief[4]."、".$arr_brief[5]."";
    }
    $data['$brief']=rtrim($brief,';');
    return $data;
}

function gen_lines($content)
{
    $lines = '';
    for ($i = 0; $i < count($content); $i++) {
		$id1=explode('-',$content[$i][0])[0];
        $lines .=
            "<div class = \"label_round_blue\" onclick=\"respond('{$content[$i][0]}')\">" .
			"id：{$id1}<br>" .
            "申请人：{$content[$i][3]}<br>" .
            //"提交时间：{$content[$i][1]}<br>".
            "起止日期： {$content[$i][4]} 至 {$content[$i][5]}<br>" .
            "实习地点：{$content[$i][6]}、{$content[$i][7]}、{$content[$i][8]}、{$content[$i][9]}" .
            "</div>";
    }
    return $lines;
}
function gen_res($content)
{
    $res = '';
    for ($i = 0; $i < count($content); $i++) {
        $res .= implode(',', $content[$i]) . ';';
    }
    $res=rtrim($res,';');
    return $res;
}