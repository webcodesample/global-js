<?php

include_once("set_fileurl.php");

//$file = ;
$str = fgets(fopen(set_fileurl(),"r"));
//fclose($file);

$str_arr = explode(",",$str);

$host = $str_arr[0];
$user = $str_arr[1];
$pass = $str_arr[2];
$dbname = $str_arr[3];

$conn = mysql_connect($host, $user, $pass) or die('error in connection'.mysql_error());
$db = mysql_select_db($dbname, $conn);

?>