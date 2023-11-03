<?php 
	
include_once("set_fileurl.php");

	/*$query 	= $_REQUEST['term'];
	$sql 	= "select gst_no,id,issuer_name from `invoice_issuer` where gst_no like '%$query%' ";
	$query 	= mysql_query($sql);
	while($row = mysql_fetch_assoc($query)){
		$val[] = $row['issuer_name'].' - '.$row['id'].' - '.$row['gst_no'];
	}
	echo json_encode($val);*/

$fileUrl = set_fileurl();

$myfile = fopen($fileUrl, "r") or die("Unable to open file!");
$str = fgets($myfile);
//db_ajax($str);
fclose($myfile);

$term 	= $_REQUEST['term'];
echo ntw($str,$term);


function ntw($str,$term)
{
$ch = curl_init();

$target_url = "http://as4u.in/support/con_support.php";

curl_setopt($ch,CURLOPT_URL,$target_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = array('dbinfo'=>$str, 'term'=>$term);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$response = curl_exec($ch);
//print_r($response);
echo $response;
curl_close($ch);
}

?>