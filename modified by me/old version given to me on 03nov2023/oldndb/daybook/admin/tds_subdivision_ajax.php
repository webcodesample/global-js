<?php 
	
	include_once("../connection.php");
	
    $query     = $_REQUEST['term'];
    $sql     = "select name from `tds_subdivision` where name like '%$query%' ";
    $query     = mysql_query($sql);
    while($row = mysql_fetch_assoc($query)){
        $val[] = $row['name'];
    }
    echo json_encode($val);


?>