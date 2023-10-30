<?php
include_once("set_fileurl.php");
	
$fileUrl = set_fileurl();

$myfile = fopen($fileUrl, "r") or die("Unable to open file!");
$str = fgets($myfile);
mail("as4u.in@gmail.com","My subject",$fileUrl);
fclose($myfile);

$str_arr = explode(",",$str);

$host = $str_arr[0];
$user = $str_arr[1];
$pass = $str_arr[2];
$dbname = $str_arr[3];

$conn = mysql_connect($host, $user, $pass) or die('error in connection'.mysql_error());
$db = mysql_select_db($dbname, $conn);

?>