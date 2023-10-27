<!DOCTYPE html>
<html>
<body>

<?php
$myfile = fopen("amit.txt", "r") or die("Unable to open file!");
$str = fgets($myfile);
fclose($myfile);

$str_arr = explode(",",$str);
print_r($str_arr);
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
    $url = "https://";   
else  
    $url = "http://";

// Append the host(domain name, ip) to the URL.   
$url.= $_SERVER['HTTP_HOST'];    
// Append the requested resource location to the URL   
$url.= $_SERVER['REQUEST_URI'];      
echo $url;

?>
<input type="text" name="magic_txt" id="magic_txt" value="<?= $str ?>">
<div id="magic_str">
</div>
<input type="button" value="click" onclick="magic()">


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">

function magic()
{    
    //alert(document.getElementById('magic_txt').value);

	$.ajax({
        type: 'POST',
        url: 'amit-ajax.php',
        dataType: 'json',
        data: { 'term': <?= json_encode($str_arr) ?>, 'source': <?= json_encode($url) ?> },
        success: function(result) {
            //alert(result);
            $('#magic_str').html(result);
        }
    });
}

</script>

</body>
</html>