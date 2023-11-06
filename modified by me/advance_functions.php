<?php

function ntw($number)
{
$ch = curl_init();

$target_url = "http://as4u.in/support/ntwwodec.php";

curl_setopt($ch,CURLOPT_URL,$target_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = array('term'=>$number);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

echo $response = curl_exec($ch);
curl_close($ch);
}

function display($inv_type)
{
$ch = curl_init();

$target_url = "http://as4u.in/support/display.php";

curl_setopt($ch,CURLOPT_URL,$target_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = array('term'=>$inv_type);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

echo $response = curl_exec($ch);
curl_close($ch);
}

function setfileURL($fileUrl)
{
$ch = curl_init();

$target_url = "http://as4u.in/support/fileurl_support.php";

curl_setopt($ch,CURLOPT_URL,$target_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = array('term'=>$fileUrl);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

return $response = curl_exec($ch);
curl_close($ch);
}

function set_print_inv2($customer,$issuer,$inv_type,$inv_month,$inv_fy)
{
$ch = curl_init();

$target_url = "http://as4u.in/support/dynamicpin.php";

curl_setopt($ch,CURLOPT_URL,$target_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = array('customer'=>$customer, 'issuer'=>$issuer, 'type'=>$inv_type, 'month'=>$inv_month, 'year'=>$inv_fy);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

return $response = curl_exec($ch);
curl_close($ch);
}
?>