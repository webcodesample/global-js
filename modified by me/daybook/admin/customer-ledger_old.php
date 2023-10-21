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
if(isset($_POST['trans_id']) && $_POST['trans_id'] != "")
{

	$trans_id = $_POST['trans_id'];
	$del_query = "delete from payment_plan where trans_id = '".$trans_id."'";
	$del_result = mysql_query($del_query) or die("error in Transaction delete query ".mysql_error());
	$msg = "Transaction Deleted Successfully.";
}
if(mysql_real_escape_string(trim($_REQUEST['file_button'])) == "Submit")
{
	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
	if($_FILES["attach_file"]["name"] != "")
	{
		$attach_file_name=mysql_real_escape_string(trim($_REQUEST['attach_file_name']));
		$attach_file_id=mysql_real_escape_string(trim($_REQUEST['attach_file_id']));
		$temp = explode(".", $_FILES["attach_file"]["name"]);
		$arr_size = count($temp);
		$extension = end($temp);
		$new_file_name = $attach_file_name.'_'.date("d_M_Y").'.'.$extension;
		move_uploaded_file($_FILES["attach_file"]["tmp_name"],"transaction_files/" . $new_file_name);
		
		$query="select link_id from payment_plan where id = '".$attach_file_id."'";
		$result= mysql_query($query) or die('error in query '.mysql_error().$query);
		$data = mysql_fetch_array($result);
		
		$link_id_2 = $data['link_id'];
	
		$query3="insert into attach_file set attach_id = '".$attach_file_id."', link_id = '".$link_id_2."',file_name = '".$new_file_name."'";
		$result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
		
		$query4="insert into attach_file set attach_id = '".$link_id_2."', link_id = '".$attach_file_id."',file_name = '".$new_file_name."'";
		$result4= mysql_query($query4) or die('error in query '.mysql_error().$query4);
	}
	
	
}
if(mysql_real_escape_string(trim($_REQUEST['search_action'])) != "")
{
	$from_date = strtotime(mysql_real_escape_string(trim($_REQUEST['from_date'])));
	
	$to_date = strtotime(mysql_real_escape_string(trim($_REQUEST['to_date'])));

	$select_query = "select * from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
	$select_result = mysql_query($select_query) or die('error in query select cash query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
	
	$select_query2 = "select * from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
	$select_result2 = mysql_query($select_query2) or die('error in query select cash query '.mysql_error().$select_query2);
	$select_total2 = mysql_num_rows($select_result2);
    
    
    $select_query3_bal = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."' ";

                $select_result3_bal = mysql_query($select_query3_bal) or die('error in query select cash query '.mysql_error().$select_query3_bal);
                $select_data3_bal = mysql_fetch_array($select_result3_bal);
                $bal=$select_data3_bal['total_credit']-$select_data3_bal['total_debit'];
                //echo $bal;
	
}
else
{
	$select_query = "select * from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
	$select_result = mysql_query($select_query) or die('error in query select customer query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
	
	$select_query2 = "select * from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
	$select_result2 = mysql_query($select_query2) or die('error in query select customer query '.mysql_error().$select_query2);
	$select_total2 = mysql_num_rows($select_result2);
    
    $select_query3_bal = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer'   ";
                $select_result3_bal = mysql_query($select_query3_bal) or die('error in query select cash query '.mysql_error().$select_query3_bal);
                $select_data3_bal = mysql_fetch_array($select_result3_bal);
                $bal=$select_data3_bal['total_credit']-$select_data3_bal['total_debit'];
    //echo $bal;
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
	<h3><?php echo get_field_value("full_name","customer","cust_id",$_REQUEST['cust_id']); ?> Ledger</h3>
		
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
				 
					<td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='customer-ledger.php?cust_id=<?php echo $_REQUEST['cust_id']; ?>';"  /></td>
					<td align="right" valign="top" ><input type="button" name="print_button" id="print_button" value="Print" class="button" onClick="return print_data();"  />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="customer.php" title="Back" ><input type="button" name="back_button" id="back_button" value="Back" class="button"  /></a></td>
					
					
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
				<th class="data" width="70">Balance </th>
				<th class="data">File</th>
				
			</tr>
			<?php
			if($select_total > 0)
			{
				$i = 1;
				//$bal=0;
                ///////////////st/////////////////////
               
                
              
              //echo $bal;
                ///////////////////end////////////////
                
                
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
						 ?></td>
						<td class="data"><?php echo get_field_value("name","project","id",$select_data['on_project']); ?></td>
						<td class="data"><?php echo $select_data['description']; ?></td>
						<td class="data">
						<?php
							if($select_data['debit'] > 0)
							{
								echo number_format($select_data['debit'],2,'.','');
                                 
							}
 
							?>
						</td>
						<td class="data">
						<?php
							if($select_data['credit'] > 0)
							{
								echo number_format($select_data['credit'],2,'.','');
                             }
							?>
						</td>
						<td class="data"> <?php echo currency_symbol().number_format($bal,2,'.','');
                        $bal=(float)$bal+(float)$select_data['debit'];
                        $bal=(float)$bal-(float)$select_data['credit'];
                         ?></td>
						<td class="data" nowrap="nowrap">
						<a href="javascript:void(0);" title="View File" onClick="return view_file_function('<?php echo $select_data['payment_id']; ?>');" >View</a>
						<?php if($select_data['trans_id'] != "" && $select_data['trans_id'] != 0) { ?>
						&nbsp;<a href="javascript:account_transaction(<?php echo $select_data['trans_id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>
						<?php } ?>
						&nbsp;<a href="javascript:void(0);" title="Attach File" onClick="return attach_file_function('<?php echo $select_data['payment_id']; ?>');" ><img src="images/images.jpg" width="20" ></a>
						</td>
						
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
		<h3><?php echo get_field_value("full_name","customer","cust_id",$_REQUEST['cust_id']); ?> Ledger</h3>
		
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
                
                
                $select_query3_bal = "select SUM(debit) as total_debit,SUM(credit) as total_credit from  payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."' ";
              //echo $select_query3_bal;
                $select_result3_bal = mysql_query($select_query3_bal) or die('error in query select cash query '.mysql_error().$select_query3_bal);
                $select_data3_bal = mysql_fetch_array($select_result3_bal);
                $bal=$select_data3_bal['total_credit']-$select_data3_bal['total_debit'];
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
							
							
							
							?>
						</td>
						<td class="data">
						<?php
							if($select_data2['credit'] > 0 && $select_data2['description'] != "Opening Balance")
							{
								echo number_format($select_data2['credit'],2,'.','');
							}
							
							
							?>
						</td>
						<td class="data"><?php echo currency_symbol().number_format($bal,2,'.',''); 
                        $bal=(float)$bal-(float)$select_data2['debit'];
                        $bal=(float)$bal+(float)$select_data2['credit'];
                        ?></td>
						
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
<div id="attach_div" style="position:absolute;top:50%; left:40%; width:500px; height:150px; margin:-100px 0 0 -50px;z-index:100; display:none; background-color:#FFFFFF; border:8px solid #999999;" >
<form name="attach_form" id="attach_form" method="post" action="" onSubmit="return attach_validation();" enctype="multipart/form-data" >
<table cellpadding="0" cellspacing="0" border="1" width="100%" >
<tr><td valign="top" align="right" colspan="2" ><img src="images/close.gif" onClick="return close_div();" ></td></tr>
<tr><td valign="top" >Attach File</td>
			<td><input type="file" name="attach_file" id="attach_file" value="" ></td></tr>
			
			<tr><td valign="top" >Attach File Name</td>
			<td><input type="text" id="attach_file_name"  name="attach_file_name" value="" autocomplete="off"/></td></tr>
			
			<tr><td></td><td>
			<input type="submit" class="button" name="file_button" id="file_button" value="Submit" >
			</td></tr>
</table>
<input type="hidden" id="attach_file_id"  name="attach_file_id" value="" />
</form>
</div>
<div id="view_div" style="position:absolute;top:50%; left:40%; width:500px; min-height:250px; margin:-100px 0 0 -50px;z-index:100; display:none; background-color:#FFFFFF; border:8px solid #999999;" >

</div>
<form name="trans_form" id="trans_form" action="" method="post" >
		
		<input type="hidden" name="trans_id" id="trans_id" value="" >
		</form>
</body>
</html>
<script>
function close_view()
{
	$('#view_div').hide("slow");
}
function view_file_function(id)
{
	
	$('#view_div').show("slow");
	$.ajax({
		url: "attach_file_ajax.php?id="+id,
		type: 'GET',
		dataType: 'html',
		beforeSend: function () {
			$('#view_div').html('Processing..................');
			
		},
		success: function (data, textStatus, xhr) {
			/*var arr = data.split("EXPLODE");
			$('#export_div').show();
			$('#cas_issued_div').html(arr[0]);		
			$('#student_query').val(arr[1]);
			$('#student_export').show();*/
			$('#view_div').html(data);		
			
			
		},
		error: function (xhr, textStatus, errorThrown) {
			$('#view_div').html(textStatus);
		}
	});
	
}
function attach_validation()
{
	if(document.getElementById("attach_file").value=="")
	{
	 alert("Please Select file");
	 document.getElementById("attach_file").focus();
	 return false;
	}
	else if(document.getElementById("attach_file_name").value=="")
	{
	 alert("Please enter attach file name ");
	 document.getElementById("attach_file_name").focus();
	 return false;
	} 
}
function attach_file_function(id)
{
	
	document.getElementById("attach_div").style.display="block";
	document.getElementById("attach_file_id").value=id;
	
}
function close_div()
{
	document.getElementById("attach_div").style.display="none";
}
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
function account_transaction(trans_id)
{
	if(confirm("Are you sure want to delete?!!!!!......"))
	{
		$("#trans_id").val(trans_id);
		$("#trans_form").submit();
		return true;
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

