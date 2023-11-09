<?php
// DB
define('DB_DRIVER', 'mysql');
define('DB_HOSTNAME', 'p3nlmysql83plsk.secureserver.net');
define('DB_USERNAME', 'TestNDB');
define('DB_PASSWORD', 'TestNDB1!2022');
define('DB_DATABASE', 'TestNDB');
$conn = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die('error in connection'.mysql_error());
$db = mysql_select_db(DB_DATABASE, $conn);

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