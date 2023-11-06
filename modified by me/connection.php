<?php
include_once("admin/set_fileurl.php");
// DB
define('DB_DRIVER', 'mysql');
define('DB_HOSTNAME', 'p3nlmysql83plsk.secureserver.net');
define('DB_USERNAME', 'TestNDB');
define('DB_PASSWORD', 'TestNDB1!2022');
define('DB_DATABASE', 'TestNDB');
$conn = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die('error in connection'.mysql_error());
$db = mysql_select_db(DB_DATABASE, $conn);

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

function convert_date_format($old_date)
	{
		$old_date = trim($old_date);
/*
# m/d or mm/dd
  (1[0-2]|0?[1-9])/(3[01]|[12][0-9]|0?[1-9])

		'/\b\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\b/' => 'Y-m-d',
        '/\b\d{4}-(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])\b/' => 'Y-d-m',
        '/\b(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-\d{4}\b/' => 'd-m-Y',
        '/\b(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])-\d{4}\b/' => 'm-d-Y',*/

		
		if (preg_match('/^[0-9]{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])$/', $old_date)) // MySQL-compatible YYYY/MM/DD format
		{
			$new_date = date("d-m-Y", strtotime($old_date));
		//	$new_date = substr($old_date, 8, 2) . '-' . substr($old_date, 5, 2) . '-' . substr($old_date, 0, 4);
		}
		elseif (preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/', $old_date)) // DD/MM/YYYY format
		{
			$new_date = date("d-m-Y", strtotime($old_date));
		//	$new_date = substr($old_date, 0, 2) . '-' . substr($old_date, 3, 2) . '-' . substr($old_date, 6, 4);
		}
		elseif (preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{2}$/', $old_date)) // DD/MM/YY format
		{
			$new_date = date("d-m-Y", strtotime($old_date));
			//$new_date = substr($old_date, 0, 2) . '-' . substr($old_date, 3, 2) . '-20' . substr($old_date, 6, 4);
		}
		elseif (preg_match('/^[0-9]{4}.(0[1-9]|1[0-2]).(0[1-9]|[1-2][0-9]|3[0-1])$/', $old_date)) // MySQL-compatible YYYY.MM.DD format
		{
			$new_date = date("d-m-Y", strtotime($old_date));
			//$new_date = substr($old_date, 8, 2) . '-' . substr($old_date, 5, 2) . '-' . substr($old_date, 0, 4);
		}
		elseif (preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1]).(0[1-9]|1[0-2]).[0-9]{4}$/', $old_date)) // DD.MM.YYYY format
		{
			$new_date = date("d-m-Y", strtotime($old_date));
		//	$new_date = substr($old_date, 0, 2) . '-' . substr($old_date, 3, 2) . '-' . substr($old_date, 6, 4);
		}
		elseif (preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1]).(0[1-9]|1[0-2]).[0-9]{2}$/', $old_date)) // DD.MM.YY format
		{
			$new_date = date("d-m-Y", strtotime($old_date));
		//	$new_date = substr($old_date, 0, 2) . '-' . substr($old_date, 3, 2) . '-20' . substr($old_date, 6, 4);
		}
		elseif (preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $old_date)) // MySQL-compatible YYYY-MM-DD format
		{
			$new_date = date("d-m-Y", strtotime($old_date));
		//	$new_date = substr($old_date, 8, 2) . '-' . substr($old_date, 5, 2) . '-' . substr($old_date, 0, 4);
		}
		elseif (preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/', $old_date)) // DD-MM-YYYY format
		{
			$new_date = date("d-m-Y", strtotime($old_date));
			//$new_date = substr($old_date, 0, 2) . '-' . substr($old_date, 3, 2) . '-' . substr($old_date, 6, 4);
		}
		elseif (preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{2}$/', $old_date)) // DD-MM-YY format
		{
			$new_date = date("d-m-Y", strtotime($old_date));
			//$new_date = substr($old_date, 0, 2) . '-' . substr($old_date, 3, 2) . '-20' . substr($old_date, 6, 4);
		}
		elseif (preg_match('/^(1[0-2]|0?[1-9])\/(3[01]|[12][0-9]|0?[1-9])\/[0-9]{2}$/', $old_date)) // MySQL-compatible 'm-d-Y' format
		{
			$new_date = date("d-m-Y", strtotime($old_date));
		  //$new_date = '0'.substr($old_date, 0, 1) . '-0' . substr($old_date, 2, 1) . '-20' . substr($old_date, 4, 2);
		}
		elseif (preg_match('/^(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])-[0-9]{2}$/', $old_date)) // MySQL-compatible 'm-d-Y' format
		{
			$new_date = date("d-m-Y", strtotime($old_date));
		  //$new_date = '0'.substr($old_date, 0, 1) . '-0' . substr($old_date, 2, 1) . '-20' . substr($old_date, 4, 2);
		}
		elseif (preg_match('/^(1[0-2]|0?[1-9]).(3[01]|[12][0-9]|0?[1-9]).[0-9]{2}$/', $old_date)) // MySQL-compatible 'm-d-Y' format
		{
			$new_date = date("d-m-Y", strtotime($old_date));
		  //$new_date = '0'.substr($old_date, 0, 1) . '-0' . substr($old_date, 2, 1) . '-20' . substr($old_date, 4, 2);
		}
		elseif (preg_match('/^(1[0-2]|0?[1-9])\/(3[01]|[12][0-9]|0?[1-9])\/[0-9]{4}$/', $old_date)) // MySQL-compatible 'm-d-Yyyy' format
		{
			$new_date = date("d-m-Y", strtotime($old_date));
		  //$new_date = '0'.substr($old_date, 0, 1) . '-0' . substr($old_date, 2, 1) . '-' . substr($old_date, 4, 4);
		}
		elseif (preg_match('/^(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])-[0-9]{4}$/', $old_date)) // MySQL-compatible 'm-d-Yyyy' format
		{
			$new_date = date("d-m-Y", strtotime($old_date));
		  //$new_date = '0'.substr($old_date, 0, 1) . '-0' . substr($old_date, 2, 1) . '-' . substr($old_date, 4, 4);
		}
		elseif (preg_match('/^(1[0-2]|0?[1-9]).(3[01]|[12][0-9]|0?[1-9]).[0-9]{4}$/', $old_date)) // MySQL-compatible 'm-d-Yyyy' format
		{
			$new_date = date("d-m-Y", strtotime($old_date));
		  //$new_date = '0'.substr($old_date, 0, 1) . '-0' . substr($old_date, 2, 1) . '-' . substr($old_date, 4, 4);
		}
		elseif (preg_match('/^(3[01]|[12][0-9]|0?[1-9]).(1[0-2]|0?[1-9]).[0-9]{4}$/', $old_date)) // MySQL-compatible 'm-d-Yyyy' format
		{
			$new_date = date("d-m-Y", strtotime($old_date));
		  //$new_date = '0'.substr($old_date, 0, 1) . '-0' . substr($old_date, 2, 1) . '-' . substr($old_date, 4, 4);
		}
		elseif (preg_match('/^(3[01]|[12][0-9]|0?[1-9])\/(1[0-2]|0?[1-9])\/[0-9]{2}$/', $old_date)) // MySQL-compatible 'd-m-Y' format
		{
			$new_date = date("d-m-Y", strtotime($old_date));
		  //$new_date = date("d-m-Y", strtotime($old_date)); 
		}
		elseif (preg_match('/^(3[01]|[12][0-9]|0?[1-9])-(1[0-2]|0?[1-9])-[0-9]{2}$/', $old_date)) // MySQL-compatible 'd-m-Y' format
		{
			$new_date = date("d-m-Y", strtotime($old_date));
		  //$new_date = '0'.substr($old_date, 0, 1) . '-0' . substr($old_date, 2, 1) . '-20' . substr($old_date, 4, 2);
		}
		elseif (preg_match('/^(3[01]|[12][0-9]|0?[1-9]).(1[0-2]|0?[1-9]).[0-9]{2}$/', $old_date)) // MySQL-compatible 'd-m-Y' format
		{
			$new_date = date("d-m-Y", strtotime($old_date));
		  //$new_date = '0'.substr($old_date, 0, 1) . '-0' . substr($old_date, 2, 1) . '-20' . substr($old_date, 4, 2);
		}
		elseif (preg_match('/^(3[01]|[12][0-9]|0?[1-9])\/(1[0-2]|0?[1-9])\/[0-9]{4}$/', $old_date)) // MySQL-compatible 'd-m-Yyyy' format
		{
			$new_date = date("d-m-Y", strtotime($old_date));
		  //$new_date = '0'.substr($old_date, 0, 1) . '-0' . substr($old_date, 2, 1) . '-' . substr($old_date, 4, 4);
		 }
		 elseif (preg_match('/^(3[01]|[12][0-9]|0?[1-9])-(1[0-2]|0?[1-9])-[0-9]{4}$/', $old_date)) // MySQL-compatible 'd-m-Yyyy' format
		{
			$new_date = date("d-m-Y", strtotime($old_date));
		  //$new_date = '0'.substr($old_date, 0, 1) . '-0' . substr($old_date, 2, 1) . '-' . substr($old_date, 4, 4);
		 }
		 elseif (preg_match('/^(3[01]|[12][0-9]|0?[1-9]).(1[0-2]|0?[1-9]).[0-9]{4}$/', $old_date)) // MySQL-compatible 'd-m-Yyyy' format
		{
			$new_date = date("d-m-Y", strtotime($old_date));
		  //$new_date = '0'.substr($old_date, 0, 1) . '-0' . substr($old_date, 2, 1) . '-' . substr($old_date, 4, 4);
		}

else // Any other format. Set it as an empty date.
		{
			$new_date = '00-00-0000';
			//$new_date = substr($old_date, 0, 2) . '-' . substr($old_date, 3, 2) . '-' . substr($old_date, 6, 4);
		}
		return $new_date;
	}
?>