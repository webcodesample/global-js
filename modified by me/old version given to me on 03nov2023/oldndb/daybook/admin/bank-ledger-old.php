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

if(mysql_real_escape_string(trim($_REQUEST['search_text'])) != "")
{
	$search_text_arr = explode("- ",$_REQUEST['search_text']);
	$bank_id = get_field_value("id","bank","bank_account_number",$search_text_arr[1]);
	$select_query = "select * from payment_plan inner join bank on payment_plan.bank_id = bank.id and bank.id = '".$bank_id."' ORDER BY payment_plan.create_date ASC ";
	$select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
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
	<h3>Bank Ledger</h3>
	<?php if($msg != "") { ?>
	<div class="sukses">
		<?php echo $msg; ?>
		</div>
	<?php } else if($error_msg != "") { ?>
	<div class="gagal">
		
		<?php echo $error_msg; ?>
		</div>
	<?php } ?>
	
		<form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" >
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td width="180" align="left" valign="top"><input type="text" name="search_text" id="search_text" value="<?php echo mysql_real_escape_string(trim($_REQUEST['search_text'])); ?>" /></td>
					
					<td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='bank-ledger.php';"  /></td>
					
					
				</tr>
			</table>
			<input type="hidden" name="page" id="page" value=""  />
			</form>
		<?php if($_REQUEST['search_text'] != "") { ?>
		<table class="data">
			<tr class="data">
				<th class="data" width="30px">S.No.</th>
				<th class="data">Date</th>
				<th class="data">Description</th>
				<th class="data">Debit</th>
				<th class="data">Credit</th>
				<th class="data">Balance</th>
				
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
						<td class="data" width="30px"><?php echo $i; ?></td>
						<td class="data"><?php echo date("d-m-Y H:i:s",strtotime($select_data['create_date'])); ?></td>
						<td class="data"><?php echo $select_data['description']; ?></td>
						<td class="data">
						<?php
							if($select_data['debit'] > 0)
							{
								echo currency_symbol().$select_data['debit'];
							}
							
							
							$bal=(int)$bal-(int)$select_data['debit'];
							?>
						</td>
						<td class="data">
						<?php
							if($select_data['credit'] > 0)
							{
								echo currency_symbol().$select_data['credit'];
							}
							
							$bal=(int)$bal+(int)$select_data['credit'];
							?>
						</td>
						<td class="data"><?php echo currency_symbol().$bal; ?></td>
						
					</tr>
				<?php
					$i++;
				}
				
			}
			else
			{
				?>
				<tr class="data" >
					<td  width="30px" colspan="6" class="record_not_found" >Record Not Found</td>
				</tr>
				<?php
			}
			?>
			
		</table>
		<?php } ?>
	</div>
<div class="clear"></div>
<?php
include_once("footer.php");
?>
</div>
</body>
</html>
<script>

function search_valid()
{
	if(document.getElementById("search_text").value=="")
	{
	 alert("Please enter search text to search");
	 document.getElementById("search_text").focus();
	 return false;
	} 
	
}

</script>
 <link rel="stylesheet" href="css/jquery-ui.css" />
  <script src="js/jquery-1.9.1.js"></script>
  <script src="js/jquery-ui.js"></script>
	<script>
	$(document).ready(function(){
		$( "#search_text" ).autocomplete({
			source: "bank-ajax.php"
		});
		
		
	})
	</script>
