<?php 
	
	include_once("../connection.php");
	$query 	= $_REQUEST['term'];
	$sql 	= "select cust_id,full_name,short_name from `customer` where full_name like '%$query%' and type = 'customer'";
	$query 	= mysql_query($sql);
	while($row = mysql_fetch_assoc($query)){
		$val[] = $row['full_name'].' - '.$row['cust_id'].' - '.$row['short_name'];
	}
	echo json_encode($val);

?>