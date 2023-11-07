<?php

$str = "p3nlmysql83plsk.secureserver.net,TestNDB,TestNDB1!2022,TestNDB";

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

function numberofpages()
{
	$munpage = 5;
	return $munpage;
}
function resultperpage()
{
	$perpage = 10;
	return $perpage;
}
function currency_symbol()
{
	$currency = "<strong>&#8377;</strong>&nbsp;";
	return $currency;
}

date_default_timezone_set("Asia/Calcutta");
function getTime()
{
	$date = new DateTime();
	$date->setTimezone(new DateTimeZone("Asia/Calcutta"));
	return $date->format("Y-m-d H:i:s");
}

//mysqli_close($con);
?>