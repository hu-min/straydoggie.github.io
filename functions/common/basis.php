<?php
//by straydoggie

function journal($event,$content) {
	$file = filename();
	$time = date ( 'Y-m-d H:i:s');
	$micro = microtime(true);
	$sql = "INSERT INTO journal (time, micro, file, event, content) VALUES ('$time', '$micro', '$file', '$event', '$content')";
	db_exec($sql);
	return "success";
}

function log_file($text){
	$file_path="./logfile.txt";
	$file = fopen ($file_path , "a" );
	fwrite ($file, "{$text}\r\n");
	fclose ($file);
}

function db_conn(){
	$dbhost = "localhost";
	$dbusr = "cdapp";
	$dbpwd = "gzzxjjwt1";
	$bdname = "cdapp";
	$link = mysqli_connect( $dbhost, $dbusr, $dbpwd,$bdname );
	if(mysqli_connect_errno($link)){
		return null;
	}else{
		return $link;
	}
}

function db_exec($sql){
	$dbhost = "localhost";
	$dbusr = "cdapp";
	$dbpwd = "gzzxjjwt1";
	$bdname = "cdapp";
	$link = mysqli_connect( $dbhost, $dbusr, $dbpwd,$bdname );
	if(!$link){
		echo mysqli_connect_errno();
		echo mysqli_connect_error();
		return null;
	}
	mysqli_query($link,"set character set 'utf8'");
	mysqli_query ($link,"set names 'utf8'");
	if($result=mysqli_query($link,$sql))
	{
		mysqli_close($link);
		return $result;
	}
	echo 'No result!';
	mysqli_close($link);
	return null;
}

function guid() {
    $charid = strtoupper(md5(uniqid(mt_rand(), true)));
    $hyphen = chr(45);// "-"
    $uuid = ""//chr(123) "{"
    .substr($charid, 0, 8).$hyphen
    .substr($charid, 8, 4).$hyphen
    .substr($charid,12, 4).$hyphen
    .substr($charid,16, 4).$hyphen
    .substr($charid,20,12)
    ."";//chr(125) "}"
    return $uuid;
}

function filename(){
	$dir_file = $_SERVER['SCRIPT_NAME']; 
	$filename = basename($dir_file); 
	return $filename; 
}

function clearbom($contents){
    $BOM = chr(239).chr(187).chr(191);
    return str_replace($BOM,'',$contents);
}
?>