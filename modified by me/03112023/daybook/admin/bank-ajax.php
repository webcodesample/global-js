<?php 
	
	include_once("../connection.php");
	$query 	= $_REQUEST['term'];
	$sql 	= "select bank_account_name,bank_account_number from `bank` where bank_account_name like '%$query%'";
	$query 	= mysql_query($sql);
	while($row = mysql_fetch_assoc($query)){
		$val[] = $row['bank_account_name'].' - '.$row['bank_account_number'];
	}
	echo json_encode($val);

?>