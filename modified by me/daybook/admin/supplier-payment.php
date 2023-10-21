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


/*     Create  Account   */


if(trim($_REQUEST['action_perform']) == "add_supplier_payment")
{
	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
	$from_arr = explode("- ",$_REQUEST['from']);
	$to_arr = explode("- ",$_REQUEST['to']);
	$cust_id = $to_arr[1];
	$bank_id = get_field_value("id","bank","bank_account_number",$from_arr[1]);
	$amount=mysql_real_escape_string(trim($_REQUEST['amount']));
	$description=mysql_real_escape_string(trim($_REQUEST['description']));
	
	
	$query="insert into payment_plan set bank_id = '".$bank_id."', debit = '".$amount."', description = '".$description."', create_date = '".getTime()."'";
	$result= mysql_query($query) or die('error in query '.mysql_error().$query);
	
	$query2="insert into payment_plan set cust_id = '".$cust_id."', credit = '".$amount."', description = '".$description."', create_date = '".getTime()."'";
	$result2= mysql_query($query2) or die('error in query '.mysql_error().$query2);
	
	
	$msg = "supplier payment added successfully.";
	
}

/*     Deletion  Account   */

if($_REQUEST['action_perform'] == "delete_supplier_payment")
{
	$del_id = $_REQUEST['del_id'];
	$del_query = "delete from payment_plan where cust_id = '".$del_id."'";
	$del_result = mysql_query($del_query) or die("error in delete query ".mysql_error());
	$msg = "supplier_payment Deleted Successfully.";
	
}


$page = $_REQUEST['page'];
if ($page < 1) $page = 1;
$numberOfPages = numberofpages();
$resultsPerPage = resultperpage();
$startResults = ($page - 1) * $resultsPerPage;
$totalPages = ceil($total_row / $resultsPerPage);


if(mysql_real_escape_string(trim($_REQUEST['search_text'])) != "")
{
	$to_arr = explode("- ",$_REQUEST['to']);
	$cust_id = $to_arr[1];
	$select_query = "select * from payment_plan where cust_id = '".$cust_id."'  ORDER BY create_date ASC LIMIT $startResults, $resultsPerPage";
	$select_result = mysql_query($select_query) or die('error in query select payment_plan query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
}


$halfPages = floor($numberOfPages / 2);
$range = array('start' => 1, 'end' => $totalPages);
$isEven = ($numberOfPages % 2 == 0);
$atRangeEnd = $totalPages - $halfPages;

if($isEven) $atRangeEnd++;

if($totalPages > $numberOfPages)
{
	if($page <= $halfPages)
		$range['end'] = $numberOfPages;
	elseif ($page >= $atRangeEnd)
		$range['start'] = $totalPages - $numberOfPages + 1;
	else
	{
		$range['start'] = $page - $halfPages;
		$range['end'] = $page + $halfPages;
		if($isEven) $range['end']--;
	}
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
	<h3>Create New supplier payment</h3>
	<?php if($msg != "") { ?>
	<div class="sukses">
		<?php echo $msg; ?>
		</div>
	<?php } else if($error_msg != "") { ?>
	<div class="gagal">
		
		<?php echo $error_msg; ?>
		</div>
	<?php } ?>
	<a href="javascript:void(0);" onClick="return add_div();" > <h2>Pay</h2> </a>
	
	<div id="adddiv" style="display:<?php if($error_msg != "") { ?>block<?php } else { ?>none<?php } ?>;">
	
	<form name="supplier_payment_form" id="supplier_payment_form" action="" method="post" >
		<table width="95%">
			<tr><td width="125px">From</td>
			<td><input type="text" id="from"  name="from" value="<?php echo $_REQUEST['from']; ?>"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td >To</td>
			<td><input type="text" id="to"  name="to" value="<?php echo $_REQUEST['to']; ?>"/></td></tr>
			
			<tr><td align="left" valign="top" >Amount</td>
			<td><input type="text"  name="amount" id="amount" value="<?php echo $_REQUEST['amount']; ?>" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			<tr><td valign="top" >Project Description</td>
			<td><textarea name="description" id="description" style="width:250px; height:100px;"><?php echo $_REQUEST['description']; ?></textarea>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td></td><td>
			<input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
			</td></tr>
		</table>
		<input type="hidden" name="action_perform" id="action_perform" value="" >
		<input type="hidden" name="del_id" id="del_id" value="" >
		</form>
		
		</div>
		
		<form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" >
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td width="180" align="left" valign="top"><input type="text" name="search_text" id="search_text" value="<?php echo mysql_real_escape_string(trim($_REQUEST['search_text'])); ?>" /></td>
					
					<td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='supplier-payment.php';"  /></td>
					
					
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
				$i=1;
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
		<div class="pagination">
		
		<?php
			
						if($page > 1)
						{
							$page = $page-1;
							echo '<a href="javascript:void(0)" onclick="return show_records('.$page.')" ><span ><< prev</span></a>&nbsp';
							$page = $page+1;
						}
							
						?>
						
						<?php
						if($range['end'] != 1)
						{
							for ($i = $range['start']; $i <= $range['end']; $i++)
							{
								if($i == $page)
									echo '<strong><span class="current">'.$i.'</span></strong>&nbsp;';
								else
									echo '<a href="javascript:void(0)" onclick="return show_records('.$i.')"><span >'.$i.'</span></a>&nbsp;';
							}
						}
						?>
						
						<?php
						if ($page < $totalPages)
						{
							$page = $page+1;
							echo '<a href="javascript:void(0)" onclick="return show_records('.$page.')" >next >></a>&nbsp;';
							$page = $page-1;
						}
						
					 ?>
       
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
function add_div()
{
	$("#adddiv").toggle("slow");
}

function validation()
{
	if($("#from").val() == "")
	{
		alert("Please enter from data.");
		$("#from").focus();
		return false;
	}
	else if($("#to").val() == "")
	{
		alert("Please enter to data.");
		$("#to").focus();
		return false;
	}
	else if($("#amount").val() == "")
	{
		alert("Please enter amount.");
		$("#amount").focus();
		return false;
	}
	else if($("#project").val() == "")
	{
		alert("Please enter project.");
		$("#project").focus();
		return false;
	}
	else if($("#create_date").val() == "")
	{
		alert("Please enter pay date.");
		$("#create_date").focus();
		return false;
	}
	else
	{
		$("#action_perform").val("add_supplier_payment");
		$("#supplier_payment_form").submit();
		return true;
	}
	
}
function account_delete(del_id)
{
	if(confirm("Are you sure want to delete?!!!!!......"))
	{
		$("#action_perform").val("delete_supplier_payment");
		$("#del_id").val(del_id);
		$("#supplier_payment_form").submit();
		return true;
	}
}
function search_valid()
{
	if(document.getElementById("search_type").value=="" && document.getElementById("search_text").value=="")
	{
		alert("Please Select Search Type and Search Text");
		document.getElementById("search_text").focus();
	 	return false;
	}
	else if(document.getElementById("search_type").value=="")
	{
	 alert("Please Select Search Type");
	 document.getElementById("search_type").focus();
	 return false;
	}
	else if(document.getElementById("search_text").value=="")
	{
	 alert("Please enter search text to search");
	 document.getElementById("search_text").focus();
	 return false;
	} 
	
}
function show_records(getno)
{
    //alert(getno);
    document.getElementById("page").value=getno;
    document.search_form.submit(); 
}
 function same_address()
 {
 	if(document.getElementById("same_current").checked==true)
	{
		
		document.getElementById("permanent_address").value = document.getElementById("current_address").value;
		
	}
	else
	{
	
		document.getElementById("permanent_address").value = "";
		
	}
 }
</script>
 <link rel="stylesheet" href="css/jquery-ui.css" />
  <script src="js/jquery-1.9.1.js"></script>
  <script src="js/jquery-ui.js"></script>
	<script>
	$(document).ready(function(){
		$( "#from" ).autocomplete({
			source: "bank-ajax.php"
		});
		$( "#to" ).autocomplete({
			source: "supplier-ajax.php"
		});
		$( "#search_text" ).autocomplete({
			source: "bank-ajax.php"
		});
		
	})
	</script>