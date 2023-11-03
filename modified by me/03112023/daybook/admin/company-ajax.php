<?php 
	
	include_once("set_con.php");

	$query 	= $_REQUEST['term'];
	$sql 	= "select gst_no,id,issuer_name from `invoice_issuer` where issuer_name like '%$query%' ";
	$query 	= mysql_query($sql);
	while($row = mysql_fetch_assoc($query)){
		$val[] = $row['issuer_name'].' - '.$row['id'].' - '.$row['gst_no'];
	}
	echo json_encode($val);
?>