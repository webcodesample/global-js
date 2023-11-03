<?php 
	
	$con = mysql_connect('localhost', 'root','');
	$db	 = mysql_select_db('daybook');
	$query 	= $_REQUEST['term'];
	$sql 	= "select countryName from `countries` where countryName like '%$query%'";
	$query 	= mysql_query($sql);
	while($row = mysql_fetch_assoc($query)){
		$val[] = $row['countryName'];
	}
	echo json_encode($val);

?>