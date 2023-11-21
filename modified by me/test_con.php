<?php
include_once("set_con.php");

echo "hello";
$sql = "SHOW TABLES";
$result = mysql_query($sql);

while($table = mysql_fetch_array($result))
{
    $tables[] = $table[0];
}
//echo count($tables);

$trucated_tables_count = 0;

for($i=0; $i< count($tables); $i++)
{
	$sql = "TRUNCATE TABLE ".$tables[$i];
	mysql_query($sql);
	$trucated_tables_count++;
}

echo "Total ".$trucated_tables_count." tables has truncated.";
?>