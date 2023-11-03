<?php

include_once("set_con.php");

$query 	= $_REQUEST['term'];
$sql 	= "select * from `project` where name like '%$query%'";
$query 	= mysql_query($sql);
while($row = mysql_fetch_assoc($query)){
	$val[] = $row['name']." - ".$row['id'];
}
echo json_encode($val);

?>