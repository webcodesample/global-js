<?php
include_once("set_fileurl.php");
include_once("advance_functions.php");
include_once("function.php");

$myfile = fopen("dll.txt", "r") or die("Unable to open file!");
$str = fgets($myfile);
fclose($myfile);
//$str = setfileURL(set_fileurl());

$str_arr = explode(",",$str);

$host = $str_arr[0];
$user = $str_arr[1];
$pass = $str_arr[2];
$dbname = $str_arr[3];

$conn = mysqli_connect($host, $user, $pass, $dbname);

//$conn = mysqli_connect($host, $user, $pass) or die('error in connection'.mysqli_error());
//$db = mysqli_select_db($dbname, $conn);

?>