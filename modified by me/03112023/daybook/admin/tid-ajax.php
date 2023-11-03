<?php 
	
	include_once("set_con.php");

	$query 	= $_REQUEST['term'];
	$sql 	= "select invoice_id,trans_id from `payment_plan` where trans_id like '%$query%' GROUP BY trans_id";
    $query 	= mysql_query($sql);
	while($row = mysql_fetch_assoc($query))
	{
        $val[] = $row['trans_id'];  
	}
	echo json_encode($val);

?>