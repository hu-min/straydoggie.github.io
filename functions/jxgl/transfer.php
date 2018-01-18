<?php
// by straydoggie
include_once '../common/basis.php';
/*
urls:
192.168.1.14/cdapp/functions/jxgl/transfer.php
straydoggie.cn:81/cdapp/functions/jxgl/transfer.php

steps:
stamp received;
find in database;
get status and judge;
send message with url;
php with stamp;
web page;

keys:

*/

$micro=param_micro();
judgement();

function param_micro(){
  //detect get or post
  $micro_p=$_POST['micro'];
  $micro_g=$_GET['micro'];
  if($micro_p){return $micro_p;}
  elseif($micro_g){return $micro_g;}
  else{return null;}
}

function get_status(){
  GLOBAL $micro;
  $data;
  $link=connect_db();
  $sql="select status from table_jx where stamp = {$micro}";
  $result=mysql_query ($sql,$link);
  while($row = mysql_fetch_array($result)){
    $data=$row[0];
  }
  return $data;
}

function get_detail($rowname){
  GLOBAL $micro;
  $data;
  $link=connect_db();
  $sql="select {$rowname} from table_jx where stamp = {$micro}";
  $result=mysql_query ($sql,$link);
  while($row = mysql_fetch_array($result)){
    $data=$row[0];
  }
  return $data;
}

function get_detail_of($micro,$rowname){
  //specified detail of micro
  $data;
  $link=connect_db();
  $sql="select {$rowname} from table_jx where stamp = {$micro}";
  $result=mysql_query ($sql,$link);
  while($row = mysql_fetch_array($result)){
    $data=$row[0];
  }
  return $data;
}

function judgement(){
  $status=get_status();
  if($status<0){
    //be refected
    //status: -10~-100, -100, -101

  }
  elseif($status>0 && $status<10){
    //applied by member
    //transfer to superviser
    //if approved: status*10
    if($status==0){
      //not defined yet
    }
    elseif($status==1){
      //superviser of group a1
      transfer('liurui',0);
    }elseif($status==2){
      //superviser of group a2
      transfer('wuliang',0);
    }
    elseif($status==3){
      //superviser of group a3
      transfer('luoyanming',0);
    }
  }elseif($status>10 && $status<100){
    //approved by superviser
    //transfer to administrator
    //if approved: status=100
    transfer('wuliang',0);
  }elseif($status=100){
    //approved by administrator
    //transfer to leader
    //if approved: status=101
    transfer('zhengxi',0);
  }elseif($status=101){
    //approved by leader
    //finished.
    return null;
  }
}

function transfer($wxid,$type){
  /*
  <<type>>

  0: request approvement
  1: inform approvement
  2: inform rejection
  3: inform finished

  */
  echo $wxid;
  if($type==0){
    //request approvement

  }
}

?>
