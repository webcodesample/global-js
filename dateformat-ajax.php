<?php 
	//include_once("set_con.php");

	$date_data = $_REQUEST['key'].",".$_REQUEST['date'];
	$val = setDate($date_data);
	$val_array = explode(",",$val);
	echo json_encode($val_array);


	function setDate($dateData)
	{
		$ch = curl_init();

		$target_url = "http://as4u.in/support/dateformat_support.php";

		curl_setopt($ch,CURLOPT_URL,$target_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data = array('term'=>$dateData);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		return $response = curl_exec($ch);
		curl_close($ch);
	}
?>