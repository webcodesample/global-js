<?php 
	
	include_once("set_con.php");

	$query 	= $_REQUEST['term'];
	$sql 	= "select gst_no,id,company_name from `invoice_issuer` where gst_no like '%$query%' ";
	$query 	= mysql_query($sql);
	while($row = mysql_fetch_assoc($query)){
		$val[] = $row['company_name'].' - '.$row['id'].' - '.$row['gst_no'];
	}
	echo json_encode($val);

?>