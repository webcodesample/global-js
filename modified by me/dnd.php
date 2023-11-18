<?php
include_once("con_ajax.php");

/*$tables = ["attach_file", "bank", "clear_due_amount", "countries", "customer", "document", 
			"document_category", "email_detail", "file_import", "goods_details", "gst_due_info",
			"gst_list", "gst_subdivision", "hsn_list", "invoice_due_info", "invoice_issuer",
			"loan_advance", "loan_advance_payment", "loan_attach_file", "menu", "menu_old",
			"payment_plan", "project", "subdivision", "tbl_info", "tds_due_info", "tds_list",
			"tds_subdivision", "user"];*/

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