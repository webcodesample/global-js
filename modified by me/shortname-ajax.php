<?php 
	
	include_once("../connection.php");
	$short_name	= $_REQUEST['short_name'];
	$query 	= "select COUNT(*) AS count from `customer` where short_name = '$short_name' and type = 'customer'";
	$result = mysql_query($query);
	$data = mysql_fetch_assoc($result);
	if($data['count']>0)
	{
		$msg = "<span style='color:red; font-weight:bold;'>Sorry! ".$short_name." Already Taken</span>";
	}
	else 
	{
		$msg = "<span style='color:green; font-weight:bold;'>Congrats! ".$short_name." Is Available</span>";	
	}

	echo json_encode($msg);

?>