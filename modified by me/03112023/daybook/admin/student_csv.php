<?php 
include_once("../connection.php");
$datarow="";
	$datarow .='"BRANCHCODE","EBRANCHNAME","HBRANCHNAME","HNDBRANCHNAME"'."\n";
	
	
	
	$query = "select * from degbranchmaster";
	$result = mysql_query($query) or die('error in query '.mysql_error());
	$total_row = mysql_num_rows($result);
	if($total_row > 0)
	{
		while($row = mysql_fetch_array($result))
		{
		$BRANCHCODE=$row['BRANCHCODE'];
		$EBRANCHNAME=$row['EBRANCHNAME'];
		$HBRANCHNAME=html_entity_decode($row['HBRANCHNAME']);
		$HNDBRANCHNAME=$row['HNDBRANCHNAME'];
		
			$datarow .= '"'.$BRANCHCODE.'","'.$EBRANCHNAME.'","'.$HBRANCHNAME.'","'.$HNDBRANCHNAME.'"'."\n";
		}
	}

	/*header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename=student_list.csv");
	header("Pragma: no-cache");
	header("Expires: 0");*/
	echo $datarow;

?>