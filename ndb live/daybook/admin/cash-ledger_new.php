<?php session_start();
include_once("../connection.php");
if($_REQUEST['msg'] != "")
{
	$msg = $_REQUEST['msg'];
}
else
{
	$msg = "";
}
if($_REQUEST['error_msg'] != "")
{
	$error_msg = $_REQUEST['error_msg'];
}
else
{
	$error_msg = "";
}

if(mysql_real_escape_string(trim($_REQUEST['search_action'])) != "")
{
	$from_date = strtotime(mysql_real_escape_string(trim($_REQUEST['from_date'])));
	
	$to_date = strtotime(mysql_real_escape_string(trim($_REQUEST['to_date'])));

	$select_query = "select * from payment_plan inner join bank on payment_plan.bank_id = bank.id and bank.id = '".$_REQUEST['cash_id']."' and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."' ORDER BY payment_plan.payment_date ASC ";
	$select_result = mysql_query($select_query) or die('error in query select cash query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
	
	$select_query2 = "select * from payment_plan inner join bank on payment_plan.bank_id = bank.id and bank.id = '".$_REQUEST['cash_id']."' and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."' ORDER BY payment_plan.payment_date ASC ";
	$select_result2 = mysql_query($select_query2) or die('error in query select cash query '.mysql_error().$select_query2);
	$select_total2 = mysql_num_rows($select_result2);
	
}
else
{
	$select_query = "select * from payment_plan inner join bank on payment_plan.bank_id = bank.id and bank.id = '".$_REQUEST['cash_id']."' ORDER BY payment_plan.payment_date ASC ";
	$select_result = mysql_query($select_query) or die('error in query select cash query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
	
	$select_query2 = "select * from payment_plan inner join bank on payment_plan.bank_id = bank.id and bank.id = '".$_REQUEST['cash_id']."' ORDER BY payment_plan.payment_date ASC ";
	$select_result2 = mysql_query($select_query2) or die('error in query select cash query '.mysql_error().$select_query2);
	$select_total2 = mysql_num_rows($select_result2);

}

	


?>
<html>
<head>
<title>Admin Panel</title>

</head>

<body>
<?php 
include_once("header.php");
?>

<div id="wrapper">
	<?php
	include_once("leftbar.php");

	?>
	<div id="rightContent">
	<h3><?php echo get_field_value("bank_account_name","bank","id",$_REQUEST['cash_id']); ?> Ledger</h3>
		<br>
		
		<form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" >
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					
					<td width="50">
					&nbsp;&nbsp;From
					</td>
					<td width="70">
					
					<input type="text" name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>"  readonly="" style="width:100px;" >
				 </td>
				
				 <td width="50">
					&nbsp;&nbsp;To
					</td>
					<td width="70">
					
					<input type="text" name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>"  readonly="" style="width:100px;" >
				 </td>
				 
					<td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='cash-ledger.php?cash_id=<?php echo $_REQUEST['cash_id']; ?>';"  /></td>
					<td align="right" valign="top" ><input type="button" name="print_button" id="print_button" value="Print" class="button" onClick="return print_data();"  />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="cash.php" title="Back" ><input type="button" name="back_button" id="back_button" value="Back" class="button"  /></a></td>
					
					
				</tr>
			</table>
			<input type="hidden" name="search_action" id="search_action" value="Search"  />
			
			</form>
		
		<table class="data">
			<tr class="data">
				<th class="data" width="15px">S.<br>No.</th>
				<th class="data" width="50">Date</th>
				<th class="data">To&nbsp; /<br>From</th>
				<th class="data">Project</th>
				<th class="data">Description</th>
				<th class="data" width="50">Debit</th>
				<th class="data" width="50">Credit</th>
				<th class="data" width="70">Balance</th>
				
			</tr>
			<?php
			if($select_total > 0)
			{
				$i = 1;
				
				
				$bal=0;
				
				while($select_data = mysql_fetch_array($select_result))
				{
					
					 ?>
					<tr class="data">
						<td class="data" ><?php echo $i; ?></td>
						<td class="data"><?php echo date("d-m-y",$select_data['payment_date']); ?></td>
						<td class="data"><?php
						if($select_data['on_customer'] != "")
						{
							echo get_field_value("full_name","customer","cust_id",$select_data['on_customer']);
						}
						else if($select_data['on_bank'] != "")
						{
							echo get_field_value("bank_account_name","bank","id",$select_data['on_bank']);
						}
							?>	</td>
						<td class="data"><?php echo get_field_value("name","project","id",$select_data['on_project']); ?></td>
						<td class="data"><?php echo $select_data['description']; ?></td>
						<td class="data">
						<?php
							if($select_data['debit'] > 0 && $select_data['description'] != "Opening Balance")
							{
								echo number_format($select_data['debit'],2,'.','');
								
								
								
							}
							$bal=(float)$bal-(float)$select_data['debit'];
							?>
						</td>
						<td class="data">
						<?php
							if($select_data['credit'] > 0 && $select_data['description'] != "Opening Balance" )
							{
								echo number_format($select_data['credit'],2,'.','');
								
								
							}
							
							$bal=(float)$bal+(float)$select_data['credit'];
							
							?>
						</td>
						<td class="data"><?php echo currency_symbol().number_format($bal,2,'.',''); ?></td>
						
					</tr>
				<?php
					$i++;
				}
				
			}
			else
			{
				?>
				<tr class="data" >
					<td  width="30px" colspan="7" class="record_not_found" >Record Not Found</td>
				</tr>
				<?php
			}
			?>
			
		</table>
		
		
		<div id="ledger_data" style="display:none;" >
		<h3><?php echo get_field_value("bank_account_name","bank","id",$_REQUEST['cash_id']); ?> Ledger</h3>
		<br>
		<table  border="1" cellpadding="5" cellspacing="0" width="100%">
			<tr class="data">
				
				<th class="data" width="60">Date</th>
				<th class="data">To&nbsp; / &nbsp;From</th>
				<th class="data">Project</th>
				<th class="data">Description</th>
				<th class="data">Debit</th>
				<th class="data">Credit</th>
				<th class="data">Balance</th>
				
			</tr>
			<?php
				$i = 1;
				$bal=0;
				while($select_data2 = mysql_fetch_array($select_result2))
				{
					
					 ?>
					<tr class="data">
						
						<td class="data"><?php echo date("d-m-y",$select_data2['payment_date']); ?></td>
						<td class="data"><?php	
						if($select_data2['on_customer'] != "")
						{
							echo get_field_value("full_name","customer","cust_id",$select_data2['on_customer']);
						}
						else if($select_data2['on_bank'] != "")
						{
							echo get_field_value("bank_account_name","bank","id",$select_data2['on_bank']);
						}
							?>	</td>
						<td class="data"><?php echo get_field_value("name","project","id",$select_data2['on_project']); ?></td>
						<td class="data"><?php echo $select_data2['description']; ?></td>
						<td class="data">
						<?php
							if($select_data2['debit'] > 0 && $select_data2['description'] != "Opening Balance")
							{
								echo number_format($select_data2['debit'],2,'.','');
							}
							
							
							$bal=(float)$bal-(float)$select_data2['debit'];
							?>
						</td>
						<td class="data">
						<?php
							if($select_data2['credit'] > 0 && $select_data2['description'] != "Opening Balance")
							{
								echo number_format($select_data2['credit'],2,'.','');
							}
							
							$bal=(float)$bal+(float)$select_data2['credit'];
							?>
						</td>
						<td class="data"><?php echo currency_symbol().number_format($bal,2,'.',''); ?></td>
						
					</tr>
				<?php
					$i++;
				}
				
			
			?>
			
		</table>
		</div>
		
	</div>
<div class="clear"></div>
<?php
include_once("footer.php");
?>
</div>
</body>
</html>
<script>

$(function() {
	 
	$('#from_date').datepick({dateFormat: 'dd-mm-yyyy'});
	
});
$(function() {
	 
	$('#to_date').datepick({dateFormat: 'dd-mm-yyyy'});
	
});
function search_valid()
{
	if(document.getElementById("from_date").value=="")
	{
	 alert("Please enter from date");
	 document.getElementById("from_date").focus();
	 return false;
	}
	else if(document.getElementById("to_date").value=="")
	{
	 alert("Please enter to ");
	 document.getElementById("to_date").focus();
	 return false;
	} 
	
	
}
function print_data()
{
	printMe=window.open();
	printMe.document.write(document.getElementById("ledger_data").innerHTML);
	printMe.print();
	printMe.close();
}
</script>

