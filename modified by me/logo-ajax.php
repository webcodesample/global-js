<?php 
	
	$myfile = fopen($_REQUEST['site_url'], "r") or die("Unable to open file!");
	$str = fgets($myfile);
	fclose($myfile);

	$str_arr = explode(",",$str);

	$host = $str_arr[0];
	$user = $str_arr[1];
	$pass = $str_arr[2];
	$dbname = $str_arr[3];

	$conn = mysql_connect($host, $user, $pass) or die('error in connection'.mysql_error());
	$db = mysql_select_db($dbname, $conn);

    $imageData = (file_get_contents($_REQUEST['file']));
	//$imageData = file_get_contents($_FILES['file']['tmp_name']);
	
	$logo_update_query = "update invoice_issuer set logo = '".$imageData."' where id = '".$_REQUEST['issuer_id']."'";
	//$logo_update_query = "insert into invoice_issuer logo = '".$file."' where id = '".$_REQUEST['issuer_id']."'";
	$logo_update_result= mysql_query($logo_update_query);

	$sql 	= "select * from invoice_issuer where id = '1'";
	$query 	= mysql_query($sql);
	$row	= mysql_fetch_assoc($query);
	
	$logo = base64_encode($row['logo']);
	
	echo json_encode($logo);

?>