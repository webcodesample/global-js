<?php 
	
	include_once("../connection.php");
	$query 	= $_REQUEST['term'];
	$sql 	= "select id,issuer_name,display_name from `invoice_issuer` where issuer_name like '%$query%' ";
	$query 	= mysql_query($sql);
	while($row = mysql_fetch_assoc($query)){
		$val[] = $row['issuer_name'].' - '.$row['id'].' - '.$row['display_name'];
	}
	echo json_encode($val);

?>