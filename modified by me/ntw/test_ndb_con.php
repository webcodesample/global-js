<?php

$str = "p3nlmysql83plsk.secureserver.net,TestNDB,TestNDB1!2022,TestNDB";


$str_arr = explode(",",$str);

$host = $str_arr[0];
$user = $str_arr[1];
$pass = $str_arr[2];
$dbname = $str_arr[3];

$con = mysqli_connect($host, $user, $pass, $dbname);

//$con = mysqli_connect("localhost","my_user","my_password","my_db");

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}

// Perform query
if ($result = mysqli_query($con, "SELECT * FROM invoice_issuer")) {
  echo "Returned rows are: " . mysqli_num_rows($result);

  // Associative array
$res = mysqli_fetch_all($result,MYSQLI_ASSOC);

//$res = mysqli_fetch_assoc($result);

print_r($res);


foreach($res as $row)
{
echo "<br>";
//print_r($row);
echo $row['id']." - ".$row['issuer_name']." ( ".$row['gst_no']." ) ";
}


// Free result set
mysqli_free_result($result);
}

mysqli_close($con);
?>