<?php

include_once("con_ajax.php");

$query 	= $_REQUEST['term'];
$sql 	= "select * from `project` where name like '%$query%'";
$query 	= mysql_query($sql);
while($row = mysql_fetch_assoc($query)){
	$val[] = $row['name'];
}
echo json_encode($val);

?>