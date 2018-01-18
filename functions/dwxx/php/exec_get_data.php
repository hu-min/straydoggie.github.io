<?php
// by straydoggie
include_once "./config.php";
include_once "../../common/basis.php";

$key = $_POST ['key'];
$ident = $_POST ['ident'];
$length = $_POST ['length'];

if(!$key){$key=$_GET ['key'];}
if(!$ident){$ident=$_GET ['ident'];}
if(!$length){$length=$_GET ['length'];}


if ($key=='get') {
    $n1=$ident;
    $n2=$ident+$length;
    $sql="SELECT * FROM lib_exec_dw WHERE Indentifier > {$n1} AND Indentifier <= {$n2}";
    $result=db_exec($sql);
    $arr=array();
    while ($row=mysqli_fetch_row($result)) {
        $content;
        if(json_decode($row['2'])){
            $content=json_decode($row['2']);
        }else{
            $content=str_ireplace("\\","",$row['2']);
        }

        array_push($arr, array(
            'identifier'=>$row['0'],
            'type'=>$row['1'],
            //'content'=>json_decode($row['2']),
            'content'=>$content,
            'tag'=>$row['3']
        ));
    }
    //die($sql);
    die(json_encode($arr, JSON_UNESCAPED_UNICODE));
}
