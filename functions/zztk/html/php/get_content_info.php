<?php
// by straydoggie
include_once '../../../common/basis.php';
echo header("Access-Control-Allow-Origin:*");
$result=db_exec('SELECT * FROM lib_info');
$datas = '';
while ( $row = mysqli_fetch_row ( $result ) ) {
	$datas = $row [3];
}
echo $datas;
?>