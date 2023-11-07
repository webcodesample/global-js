<?php
include_once("set_fileurl.php");
include_once("advance_functions.php");
//include_once("function.php");


$str = setfileURL(set_fileurl());

$str_arr = explode(",",$str);

$host = $str_arr[0];
$user = $str_arr[1];
$pass = $str_arr[2];
$dbname = $str_arr[3];

$con = mysqli_connect($host, $user, $pass, $dbname);

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}

function get_field_value($con,$get_feild_name,$table_name,$compare_feild,$compare_feild_value)
{
	$get_query = "select $get_feild_name from $table_name where $compare_feild = '".$compare_feild_value."'";
	$get_rsult = mysqli_query($con, $get_query);
	$feild_arr = mysqli_fetch_assoc($get_rsult);
	$feild_value = $feild_arr[$get_feild_name];
	return $feild_value;
}

//mysqli_close($con);
?>