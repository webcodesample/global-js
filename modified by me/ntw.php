<?php
numberTowords(9);
echo "<br>";
numberTowords(13);
echo "<br>";
numberTowords(57);
echo "<br>";
numberTowords(525);
echo "<br>";
numberTowords(4525);
echo "<br>";
numberTowords(64525);
echo "<br>";
numberTowords(894505);
echo "<br>";
numberTowords(804505);
echo "<br>";
numberTowords(804085);
echo "<br>";
numberTowords(804005);
echo "<br>";
numberTowords(505);
echo "<br>";
numberTowords(005);
echo "<br>";
numberTowords(0005);
echo "<br>";
numberTowords(804505.25);
echo "<br>";
numberTowords(9804505.25);


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
$whole_arr = explode(",",$wholenum);
print_r($whole_arr);
echo "<br>";

$ntw_result = "";

foreach($whole_arr as $key=>$value)
{ 
	echo "Array Length : ". count($whole_arr)."<br>";
	if(count($whole_arr)>1)
	{
		if($key == count($whole_arr)-count($whole_arr))
		{
			echo "Array Index : ".$key."<br>";
			$len = strlen($value);
			echo "Key Value Length : ".$len;
			echo "<br>";
			for($len;$len>0;$len--)
			{
				if($len>2)
				{
					$ntw_result .= lakhOnly($value,$ones,$tens);
					$value = substr($value,1,$len-1);
				}
				else
				{
					$ntw_result .= thousandOnly($value,$ones,$tens);
					break;
				}
			}
			//echo $len;
			echo "<br>";
		}

		if($key == count($whole_arr)-(count($whole_arr)-1))
		{
			echo $key."<br>";
			$len = strlen($value);
			echo $len;
			echo "<br>";
			for($len;$len>0;$len--)
			{
				if($len>2)
				{
					$ntw_result .= hundredOnly($value,$ones,$tens);
					$value = substr($value,1,$len-1);
				}
				else
				{
					$ntw_result .= twodigitOnly($value,$ones,$tens);
					break;
				}
			}
			//echo $len;
			echo "<br>";
		}
	}

	else 
	{
		echo $key."<br>";
			$len = strlen($value);
			echo $len;
			echo "<br>";
			for($len;$len>0;$len--)
			{
				if($len>2)
				{
					$ntw_result .= hundredOnly($value,$ones,$tens);
					$value = substr($value,1,$len-1);
				}
				else
				{
					$ntw_result .= twodigitOnly($value,$ones,$tens);
					break;
				}
			}
	}

}

if($num_arr[1]>0)
$decimal_value = " AND ".twodigitOnly($num_arr[1],$ones,$tens)." PAISE";
else
$decimal_value = " AND ZERO PAISE";

echo "INR. ".strtoupper($ntw_result)." RUPEE".strtoupper($decimal_value)." ONLY";
}

function twodigitOnly($value,$ones,$tens)
{	
	if(substr($value,0,2)<20)
	{
		if(substr($value,0,1)>0)
		return $ones[substr($value,0,2)];
		else
		return $ones[substr($value,1,1)];
	}
	else
	{
		return $tens[substr($value,0,1)]." ".$ones[substr($value,1,1)];
	}
}

function hundredOnly($value,$ones,$tens)
{
	if(substr($value,0,1)>0)
	return $ones[substr($value,0,1)]." Hundred ";
}

function thousandOnly($value,$ones,$tens)
{
	if(substr($value,0,2)<20)
	{
		if(substr($value,0,1)>0)
		return $ones[substr($value,0,2)]." Thousand ";
		else
		return $ones[substr($value,1,1)]." Thousand ";
	}
	else
	return $tens[substr($value,0,1)]." ".$ones[substr($value,1,1)]." Thousand ";
}

function lakhOnly($value,$ones,$tens)
{
	return $ones[substr($value,0,1)]." Lakh ";
}








/*if($i < 20)
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
}*/
?>