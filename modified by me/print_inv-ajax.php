<?php 
	
	$customer 	= $_REQUEST['customer'];
	$issuer = $_REQUEST['issuer'];
	$inv_type = $_request['inv_type'];
	$inv_month = $_REQUEST['inv_month'];
	$inv_fy = $_REQUEST['inv_fy'];
	//$val = set_print_inv($customer,$issuer,$inv_type,$inv_month,$inv_fy);
	//$val = $customer.$issuer.$inv_type.$inv_month.$inv_fy;
	$val = set_print_inv();
	echo json_encode($val);
?>