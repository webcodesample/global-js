<?php 
	
	include_once("../connection.php");
	$query 	= $_REQUEST['term'];
	$sql 	= "select name from `loan_advance` where name like '%$query%' ";
	$query 	= mysql_query($sql);
    $val[] = 'All';
	while($row = mysql_fetch_assoc($query)){
		$val[] = $row['name'];
	}
	echo json_encode($val);

?>