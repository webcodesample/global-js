<?php

//curl_init();
//curl_setopt();
//curl_exec();
//curl_close();

function db_ajax($info)
{
$ch = curl_init();

$target_url = "http://as4u.in/support/db_ajax.php";

curl_setopt($ch,CURLOPT_URL,$target_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = array('dbinfo'=>$info);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

return $response = curl_exec($ch);
//print_r($response);
//echo $response;
curl_close($ch);
}
?>