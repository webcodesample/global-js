<?php
include_once("set_fileurl.php");
include("curl_db.php");
	
$fileUrl = set_fileurl();

$myfile = fopen("dll.txt", "r") or die("Unable to open file!");
$str = fgets($myfile);
db_ajax($str);
//mail("webcodesample@gmail.com","My subject",$fileUrl);
fclose($myfile);

?>