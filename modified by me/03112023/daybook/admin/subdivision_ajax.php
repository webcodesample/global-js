<?php 
include_once("../connection.php");
/*echo '<pre>';
print_r($_REQUEST);
exit;*/

$project_id = $_REQUEST['project_id'];
$from_date = $_REQUEST['from_date'];
$to_date = $_REQUEST['to_date'];
$str = $_REQUEST['str'];
$query="select project_id,sum(debit) as debit,sum(credit) as credit,description,subdivision,payment_date from payment_plan where project_id = '".$project_id."' and payment_date >= '".$from_date."' and payment_date <= '".$to_date."' and $str != '' group by subdivision ORDER BY payment_date ASC";
//$query="select project_id,debit,credit,description,subdivision,payment_date from payment_plan where project_id = '".$project_id."' and payment_date <= '".$to_date."' and $str != '' ORDER BY payment_date ASC";

$result= mysql_query($query) or die('error in query '.mysql_error().$query);
$total_rows = mysql_num_rows($result);
?>
<div id="ledger_data">
	<table id="my_table" cellpadding="10" cellspacing="0" border="1" width="550" align="center"  >
			<tr><td valign="top" align="left" colspan="5" >
            From : <?php echo date('d-m-Y',$from_date); ?>&nbsp;&nbsp;To : <?php echo date('d-m-Y',$to_date); ?>
            <span id="header1" style="float: right"><img src="images/close.gif" onClick="return close_view();" ></span></td></tr>
            <tr><td valign="middle" align="left" colspan="5" style="height:15px;" >
            
            <strong>Project </strong> : <strong><span  style="color:#FF0000;"><?php echo get_field_value("name","project","id",$_REQUEST['project_id']); ?></span></strong> 
            </td></tr>
			<tr><td valign="middle" align="left" colspan="5" style="height:15px;" >
            
			<strong>Total Investments</strong> = <strong><span id="total" style="color:#FF0000;"></span></strong> 
			</td></tr>
			<tr >
				<th valign="top" width="20"  style="border:1px solid #CCCCCC;">S.No.</th>
				<th style="border:1px solid #CCCCCC;">Amount</th>
				<!--<th style="border:1px solid #CCCCCC;">Date</th>-->
				<th style="border:1px solid #CCCCCC;">Sub Division</th>
				<!--<th width="70" style="border:1px solid #CCCCCC;">Description</th>-->
			</tr>
	<?php
if($total_rows == 0)
{
	?>
			
			<tr>
				<td colspan="3">No file Found</td>
			</tr>
			
			
					
		<?php
	
}
else
{
	
	$i=1;
	$x=0;
	while($data = mysql_fetch_array($result))
	{
		?>
			
			<tr>
				<td valign="top" style="border:1px solid #CCCCCC;" ><?php echo $i; ?></td>
				<td style="border:1px solid #CCCCCC;"><?php if($str == "debit") { echo number_format($data['debit'],2); $x = $x+$data['debit']; } else { echo number_format($data['credit'],2); $x = $x+$data['credit']; } ?></td>
				<!--<td style="border:1px solid #CCCCCC;"><?php echo date("d-m-Y",$data['payment_date']); ?></td>-->
				<td style="border:1px solid #CCCCCC;"><?php echo get_field_value("name","subdivision","id",$data['subdivision']); ?>&nbsp;</td>

				<!--<td style="border:1px solid #CCCCCC;"><?php echo $data['description']; ?>&nbsp;</td>-->
			</tr>
			
			
					
		<?php
		$i++;
	}
	
}
?>
	</table>
    </div>
    <br><br>
	<script>
	document.getElementById("total").innerHTML = '<?php echo number_format($x,2); ?>';
	</script>
	