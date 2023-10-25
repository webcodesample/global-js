<?php 
	
	include_once("../connection.php");
	$query 	= $_REQUEST['term'];
	$sql 	= "select cust_id,full_name from `customer` where full_name like '%$query%' and type = 'customer'";
	$query 	= mysql_query($sql);
    $val[] = 'All - Customer';
	while($row = mysql_fetch_assoc($query)){
		$val[] = $row['full_name'].' - '.$row['cust_id'];
	}
	echo json_encode($val);

?>