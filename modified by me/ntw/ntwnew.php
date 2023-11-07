<form name="frm" method="post" action="">
<input type="text" name="price" id="price">
<div id="ntw" style="color:#800000; font-weight:bold;">
<?php if(isset($_REQUEST['price'])) echo numberTowords($_REQUEST['price']); ?>
</div>
<input type="button" value="Convert" onClick="convert()">
<input type="Submit" value="NTW">
</form>
<script type="text/javascript">
function convert()
{
	NumVar = document.getElementById('price').value;
	alert(NumVar);

	"<?php 
$phpvar='"+NumVar+"';
$phpvar=$phpvar/1;
//echo $phpvar;
?>";

	document.getElementById('ntw').innerHTML = "<?php 
echo gettype($phpvar);
	echo $phpvar;?>";
}
</script>

<?php

/*numberTowords(50.05);
echo "<br>";
numberTowords(130);
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
numberTowords(9800505.25);
echo "<br>";
numberTowords(980505.20);
echo "<br>";
numberTowords(980505.02);
echo "<br>";
numberTowords(13989812340550.02);*/

function numberTowords($number)
{ 
$ones = array(
0 => "",
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

$ntw_result = "";
//echo $number."<br>";

$num_part = explode(".",$number);
//print_r($num_part);

$num = $num_part[0];

if(count($num_part)>1)
{
	if(strlen($num_part[1])==1)
	$dec = $num_part[1].'0';
	else
	$dec = $num_part[1];
}
else
$dec = 0;

$ntw_result .= ntw($num,$ones,$tens);

if($dec>0)
$decimal_value = " AND ".twodigitOnly($dec,$ones,$tens)." PAISE";
else
$decimal_value = " AND ZERO PAISE";

return "INR. ".strtoupper($ntw_result)." RUPEE".strtoupper($decimal_value)." ONLY";
}

function ntw($num,$ones,$tens)
{
	if(strlen($num)<3)
	{
		return twodigitOnly($num,$ones,$tens);
	}
	elseif(strlen($num)==3)
	{
		return set_hundred($num,$ones,$tens);
	}
	elseif(strlen($num)==4 || strlen($num)==5)
	{
		return set_thousand($num,$ones,$tens);
	}
	elseif(strlen($num)==6 || strlen($num)==7)
	{
		return set_lakh($num,$ones,$tens);
	}
	elseif(strlen($num)==8 || strlen($num)==9 || strlen($num)==10 || strlen($num)==11 || strlen($num)==12 || strlen($num)==13 || strlen($num)==14)
	{
		return set_crore($num,$ones,$tens);
	}
}

function set_hundred($num,$ones,$tens)
{
	$remainder = fmod($num,10);

	$num /= pow(10, 2);
	$num_part = explode(".",$num);

	if($remainder==0)
	$num_part[1] *= 10;

	if($num_part[0])
	return twodigitOnly($num_part[0],$ones,$tens)." Hundred ".twodigitOnly($num_part[1],$ones,$tens);
	else
	return twodigitOnly($num_part[1],$ones,$tens);
}

function set_thousand($num,$ones,$tens)
{
	$remainder = fmod($num,10);

	$num /= pow(10, 3);
	$num_part = explode(".",$num);

	if($remainder==0)
	$num_part[1] *= 10;

	if($num_part[0])
	return twodigitOnly($num_part[0],$ones,$tens)." Thousand ".set_hundred($num_part[1],$ones,$tens);
	else
	return set_hundred($num_part[1],$ones,$tens);
}

function set_lakh($num,$ones,$tens)
{
	$remainder = fmod($num,10);

	$num /= pow(10, 5);
	$num_part = explode(".",$num);

	if($remainder==0)
	$num_part[1] *= 10;

	if($num_part[0])
	return twodigitOnly($num_part[0],$ones,$tens)." Lakh ".set_thousand($num_part[1],$ones,$tens);
	else
	return set_thousand($num_part[1],$ones,$tens);
}

function set_crore($num,$ones,$tens)
{
	$remainder = fmod($num,10);

	$num /= pow(10, 7);
	$num_part = explode(".",$num);

	if($remainder==0)
	$num_part[1] *= 10;

	if($num_part[0])
	{
		return ntw($num_part[0],$ones,$tens)." Crore ".set_lakh($num_part[1],$ones,$tens);
	}
	else
	return set_lakh($num_part[1],$ones,$tens);
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

?>