<?php
include_once("con_ajax.php");

$sql = "SHOW TABLES";
$result = mysql_query($sql);

while($tables = mysql_fetch_array($result))
{
    $table_array[] = $tables[0];
}

print_r($table_array);
?>