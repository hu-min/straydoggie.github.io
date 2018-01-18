<?php
// by straydoggie
include_once '../../../common/basis.php';
$chapter = $_GET ['chapter'];
$section = $_GET ['section'];
$valuefrom = $_GET ['from'];
$valueto = $_GET ['to'];
$valuecount = $_GET ['count'];
$valuerandom = $_GET ['random'];
$sql = "SELECT * FROM lib_atclicence where chapter = {$chapter} and section = {$section} and number >= {$valuefrom} and number <= {$valueto}";
$result = db_exec ($sql);
$datas = array();
	while ( $row = mysqli_fetch_array ( $result ) ) {
		$data = "";
		$data .= ltrim(rtrim ($row ['number'],"$"),"$") . ",";
		$data .= ltrim(rtrim ($row ['question'],"$"),"$") . ",";
		$data .= ltrim(rtrim ($row ['option-a'],"$"),"$") . ",";
		$data .= ltrim(rtrim ($row ['option-b'],"$"),"$") . ",";
		$data .= ltrim(rtrim ($row ['option-c'],"$"),"$") . ",";
		$data .= ltrim(rtrim ($row ['option-d'],"$"),"$") . ",";
		$data .= ltrim(rtrim ($row ['answer'],"$"),"$") . ";";
		array_push($datas,$data);
	}
	if($valuerandom==1){shuffle($datas);}
	$datas = array_slice($datas,0,$valuecount);
	echo header("Access-Control-Allow-Origin:*");
	foreach($datas as $tmp){echo $tmp;}
?>