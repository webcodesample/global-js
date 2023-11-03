<?php 
	
	include_once("../connection.php");
	
    $query     = $_REQUEST['term'];
    $sql     = "select name from `gst_subdivision` where name like '%$query%' and type='output_gst'";
    $query     = mysql_query($sql);
    while($row = mysql_fetch_assoc($query)){
        $val[] = $row['name'];
    }
    echo json_encode($val);


?>