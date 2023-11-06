<?php
//set_fileurl();
function set_fileurl()
{
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
        $url = "https://";   
    else 
        $url = "http://";

// Append the host(domain name, ip) to the URL.   
$url.= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];    
// Append the requested resource location to the URL   
//$url.= $_SERVER['REQUEST_URI'];  
//echo $url;

$url_arr = explode("/",$url);

array_pop($url_arr);
array_pop($url_arr);
return implode("/",$url_arr)."/dll.txt";
}

?>