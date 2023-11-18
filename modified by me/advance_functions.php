<?php

function ntw($number)
{
$ch = curl_init();

$target_url = "http://api-support.as4u.in/api-support/ntw_advance.php";

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

$target_url = "http://api-support.as4u.in/api-support/display.php";

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

$target_url = "http://api-support.as4u.in/api-support/fileurl_support.php";

curl_setopt($ch,CURLOPT_URL,$target_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = array('term'=>$fileUrl);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

return $response = curl_exec($ch);
curl_close($ch);
}

function setPinv($pinv_data)
{
$ch = curl_init();

$target_url = "http://api-support.as4u.in/api-support/dynamicpin.php";

curl_setopt($ch,CURLOPT_URL,$target_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = array('term'=>$pinv_data);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

return $response = curl_exec($ch);
curl_close($ch);
}
?>