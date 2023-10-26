<?php 
	
	include_once("../connection.php");

    $file = addslashes(file_get_contents($_FILES["invoice_issuer_logo"]["tmp_name"]));
	
	$logo_update_query = "update invoice_issuer set logo = '".$file."' where id = '".$_REQUEST['issuer_id']."'";
	$logo_update_result= mysql_query($logo_update_query);


	//$sql 	= "select * from invoice_issuer where id = '".$_REQUEST['issuer_id']."'";
	$sql 	= "select * from invoice_issuer where id = '1'";
	$query 	= mysql_query($sql);
	$row	= mysql_fetch_assoc($query);
	
	//$val[] = $row['full_name'].' - '.$row['cust_id'].' - '.$row['short_name'];
	$logo = base64_encode($row['logo']);
	//$val[] ="<img src='data:image/jpg;charset=utf8;base64,".$logo."' style='border-radius:50%; hieght:120px; width:120px;' onClick='document.getElementById('invoice_issuer_logo').click()'>";
	
	
	echo json_encode($logo);

?>