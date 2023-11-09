<?php

function get_field_value($get_feild_name,$table_name,$compare_feild,$compare_feild_value)
{
	$get_query = "select $get_feild_name from $table_name where $compare_feild = '".$compare_feild_value."'";
	$get_rsult = mysql_query($get_query) or die("error in query ".mysql_error());
	$feild_arr = mysql_fetch_array($get_rsult);
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
?>