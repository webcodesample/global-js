<?php 
	include_once("set_con.php");

	$pinv_data = $_REQUEST['customer'].",".$_REQUEST['issuer'].",".$_REQUEST['inv_type'].",".$_REQUEST['inv_month'].",".$_REQUEST['inv_fy'].",".$_REQUEST['ndb_sr_no'];
	$val = setPinv($pinv_data);
	echo json_encode($val);
?>