<?php

$key_arr = implode("-",$_REQUEST['term']);
$key_arr = $key_arr ." - ". $_REQUEST['source'];

echo json_encode($key_arr);

?>