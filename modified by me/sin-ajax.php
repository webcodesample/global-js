<?php 
	
	include_once("set_con.php");

	$query 	= $_REQUEST['term'];
	$sql 	= "select invoice_id,trans_id from `payment_plan` where invoice_id like '%$query%' GROUP BY invoice_id";
    $query 	= mysql_query($sql);
	while($row = mysql_fetch_assoc($query)){
        if($row['cust_id']==$cus_id){
        }
        $val[] = $row['invoice_id'].' - '.$row['trans_id'];  
		
	}
	echo json_encode($val);

?>