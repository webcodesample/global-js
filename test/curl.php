<form method="post" action="">
<input type="text" name="price" value="<?= $_REQUEST['price']?>">
<?php if($_REQUEST['price']) echo ntw($_REQUEST['price']); ?>
<span id="ntw"></span>
<input type="submit" value="Convert">
</form>


<?php

//curl_init();
//curl_setopt();
//curl_exec();
//curl_close();

function ntw($number)
{
$ch = curl_init();

$target_url = "http://as4u.in/support/ntw.php";

curl_setopt($ch,CURLOPT_URL,$target_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = array('number'=>$number);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

return $response = curl_exec($ch);
//print_r($response);
//echo $response;
curl_close($ch);
}
?>