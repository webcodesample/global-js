<?php 
	
	include_once("../connection.php");
	$query 	= $_REQUEST['term'];
	$sql 	= "select invoice_id,printable_invoice_number from `payment_plan` where printable_invoice_number like '%$query%' group by printable_invoice_number";
	$query 	= mysql_query($sql);
	while($row = mysql_fetch_assoc($query)){
		if($row['printable_invoice_number'])
		$val[] = $row['printable_invoice_number'].' - '.$row['invoice_id'];
	}
	echo json_encode($val);

?>