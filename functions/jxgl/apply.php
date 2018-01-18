<?php
// by straydoggie
include_once '../common/basis.php';
//get
$content = $_GET ['content'];
$method = $_GET ['method'];
//post
$member=$_POST['member'];
$month=$_POST['month'];
$item=$_POST['item'];
$grade=$_POST['grade'];
$describe=$_POST['describe'];
$status=$_POST['status'];
$admin=$_POST['admin'];
$action=$_POST['action'];

if($action=='apply'){
  apply ($member,$month,$item,$grade,$describe,$status,$admin);
}

function apply ($member,$month,$item,$grade,$describe,$status,$admin){
  $stamp=microtime(true);
  $stamp_str=date('Y-m-d H:i:s');
  $link=connect_db();
  $sql = "INSERT INTO table_jx (stamp, member, month, item, grade, text, status, remark) VALUES ($stamp, '$member', $month, $item, $grade, '$describe', $status, '[$stamp_str] applied by $admin;')";
  //echo date('Y-m-d H:i:s',$stamp);
  //echo $sql;
  log_file($sql);
  if(mysql_query ($sql,$link)){
    echo 'yes';
  }else{
    echo 'no';
  }
}
?>
