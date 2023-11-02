<?php
numberTowords(118000);

function numberTowords($num)
{ 
$ones = array( 
1 => "one", 
2 => "two", 
3 => "three", 
4 => "four", 
5 => "five", 
6 => "six", 
7 => "seven", 
8 => "eight", 
9 => "nine", 
10 => "ten", 
11 => "eleven", 
12 => "twelve", 
13 => "thirteen", 
14 => "fourteen", 
15 => "fifteen", 
16 => "sixteen", 
17 => "seventeen", 
18 => "eighteen", 
19 => "nineteen" 
); 
$tens = array( 

2 => "twenty", 
3 => "thirty", 
4 => "forty", 
5 => "fifty", 
6 => "sixty", 
7 => "seventy", 
8 => "eighty", 
9 => "ninety" 
); 
$hundreds = array( 
"hundred", 
"thousand", 
"million", 
"billion", 
"trillion", 
"quadrillion" 
); //limit t quadrillion 
$num = number_format($num,2,".",","); 
$num_arr = explode(".",$num);
print_r($num_arr);
echo "<br>";
$wholenum = $num_arr[0]; 
$decnum = $num_arr[1]; 
$whole_arr = array_reverse(explode(",",$wholenum));
print_r($whole_arr);
echo "<br>";
krsort($whole_arr);
print_r($whole_arr);
echo "<br>";

$rettxt = ""; 
foreach($whole_arr as $key => $i)
{ 
if($i < 20)
{ 
$rettxt .= $ones[$i]; 
}
elseif($i < 100 && $i>20)
{ 
$rettxt .= $tens[substr($i,0,1)]; 
$rettxt .= " ".$ones[substr($i,1,1)]; 
}
else
{
	if(substr($i,0,1)>0)
	{
	$rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0];
	}
$rettxt .= " ".$tens[substr($i,1,1)]; 
$rettxt .= " ".$ones[substr($i,2,1)]; 
}

if($key > 0)
{ 
$rettxt .= " ".$hundreds[$key]." "; 
} 

} 
if($decnum > 0){ 
$rettxt .= " and "; 
if($decnum < 20){ 
$rettxt .= $ones[$decnum]; 
}elseif($decnum < 100){ 
$rettxt .= $tens[substr($decnum,0,1)]; 
$rettxt .= " ".$ones[substr($decnum,1,1)]; 
} 
} 
echo $rettxt; 
}  ?>