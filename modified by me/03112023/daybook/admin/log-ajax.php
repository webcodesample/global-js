<?php 
	
	include_once("../connection.php");
	
	$logo_update_query = "update invoice_issuer set logo = '".$_REQUEST['logo_file']."' where id = '".$_REQUEST['issuer_id']."'";
	$logo_update_result= mysql_query($logo_update_query);


	$sql 	= "select logo from `invoice_issuer` where where id = '".$_REQUEST['issuer_id']."'";
	$query 	= mysql_query($sql);
	while($row = mysql_fetch_assoc($query))
	{
		//$val[] = $row['full_name'].' - '.$row['cust_id'].' - '.$row['short_name'];
		$val[] ='<img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($row['logo']); ?>" style="border-radius:50%; hieght:120px; width:120px;" onClick="document.getElementById('invoice_issuer_logo').click()">';
	}
	echo json_encode($val);

?>