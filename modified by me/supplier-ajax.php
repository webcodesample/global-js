<?php 
	
	include_once("set_con.php");

	$query 	= $_REQUEST['term'];
	$sql 	= "select cust_id,full_name from `customer` where full_name like '%$query%' and type = 'supplier'";
    $query 	= mysql_query($sql);
	while($row = mysql_fetch_assoc($query)){
        if($row['cust_id']==$cus_id){
        }
        $val[] = $row['full_name'].' - '.$row['cust_id'];  
		
	}
	echo json_encode($val);

?>