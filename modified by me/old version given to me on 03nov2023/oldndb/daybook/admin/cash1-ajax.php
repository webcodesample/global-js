<?php 
	
	include_once("../connection.php");
	$query 	= $_REQUEST['term'];
	$sql 	= "select bank_account_name,bank_account_number from `bank` where type='cash account' and  bank_account_name like '%$query%'";
	$query 	= mysql_query($sql);
    $val[] = 'All - cash Account';
	while($row = mysql_fetch_assoc($query)){
		$val[] = $row['bank_account_name'].' - '.$row['bank_account_number'];
	}
	echo json_encode($val);

?>