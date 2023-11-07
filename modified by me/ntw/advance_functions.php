<?php

//curl_init();
//curl_setopt();
//curl_exec();
//curl_close();

//convert number to word
function ntw($number)
{
$ch = curl_init();

$target_url = "http://as4u.in/support/ntwwodec.php";

curl_setopt($ch,CURLOPT_URL,$target_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = array('term'=>$number);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

echo $response = curl_exec($ch);
//print_r($response);
//echo $response;
curl_close($ch);
}

function ntw_new($number)
{
$ch = curl_init();

$target_url = "http://as4u.in/support/ntwwodec_new.php";

curl_setopt($ch,CURLOPT_URL,$target_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = array('term'=>$number);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

echo $response = curl_exec($ch);
//print_r($response);
//echo $response;
curl_close($ch);
}

//display invoice_type on print as per condition
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

//set file url
function setfileURL($fileUrl)
{
$ch = curl_init();

$target_url = "http://as4u.in/support/fileurl_support.php";

curl_setopt($ch,CURLOPT_URL,$target_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = array('term'=>$fileUrl);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

echo $response = curl_exec($ch);
curl_close($ch);
}
?>