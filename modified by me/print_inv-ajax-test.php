<?php 
	include_once("set_con.php");
	//$pinv_data = $_REQUEST['customer'].",".$_REQUEST['issuer'].",".$_request['inv_type'].",".$_REQUEST['inv_month'].",".$_REQUEST['inv_fy'];
	$pinv_data = "amit - 12 - rt,mgn - 1 - bafna,asdf0956tu";
	$val = setPinv($pinv_data);
	echo $val."<br>";
	echo json_encode($val);
?>