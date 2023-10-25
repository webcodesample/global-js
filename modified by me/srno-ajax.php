<?php 
	
	include_once("../connection.php");
	$query 	= $_REQUEST['term'];
	$sql 	= "select invoice_id,printable_invoice_number,supplier_invoice_number from `payment_plan` where invoice_id like '%$query%' group by invoice_id";
	$query 	= mysql_query($sql);
	while($row = mysql_fetch_assoc($query))
	{
		if($row['supplier_invoice_number']=='')
		{
			if($row['printable_invoice_number'])
			$val[] = $row['invoice_id'].' - '.$row['printable_invoice_number'];
			else
			$val[] = $row['invoice_id'].' - Manual Invoice';
		}
	}
	echo json_encode($val);

?>