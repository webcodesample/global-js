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

	$select_query = "select *,payment_plan.id as payment_id from payment_plan inner join project on payment_plan.project_id = project.id and project.id = '".$_REQUEST['project_id']."'  and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
	$select_result = mysql_query($select_query) or die('error in query select cash query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
	
	$select_query2 = "select *,payment_plan.id as payment_id from payment_plan inner join project on payment_plan.project_id = project.id and project.id = '".$_REQUEST['project_id']."'  and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
	$select_result2 = mysql_query($select_query2) or die('error in query select cash query '.mysql_error().$select_query2);
	$select_total2 = mysql_num_rows($select_result2);
	
}
else
{
	//$select_query = "select *,payment_plan.id as payment_id from payment_plan inner join project on payment_plan.project_id = project.id and project.id = '".$_REQUEST['project_id']."' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
	$select_query = "SELECT t1.cust_id,SUM(t1.credit) as total_credit,SUM(t1.debit) as total_debit,t2.name as project_name,t3.full_name as customer_name from payment_plan as t1 left join project as t2 on t1.on_project = t2.id left join customer as t3 on t1.cust_id = t3.cust_id where t1.cust_id != '' and t1.on_project != '' and t3.type = 'supplier' GROUP BY t1.cust_id";
	$select_result = mysql_query($select_query) or die('error in query select customer query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
	
	$select_query2 = "select *,payment_plan.id as payment_id from payment_plan inner join project on payment_plan.project_id = project.id and project.id = '".$_REQUEST['project_id']."' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
	$select_result2 = mysql_query($select_query2) or die('error in query select customer query '.mysql_error().$select_query2);
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
	<h3><?php echo get_field_value("name","project","id",$_REQUEST['project_id']); ?> Ledger</h3>
		
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
				 
					<td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='project-ledger2.php?project_id=<?php echo $_REQUEST['project_id']; ?>';"  /></td>
					<td align="right" valign="top" ><input type="button" name="print_button" id="print_button" value="Print" class="button" onClick="return print_data();"  />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="project.php" title="Back" ><input type="button" name="back_button" id="back_button" value="Back" class="button"  /></a></td>
					
					
				</tr>
			</table>
			<input type="hidden" name="search_action" id="search_action" value="Search"  />
			
			</form>
		<table class="data">
			<tr class="data">
				<th class="data" width="15px">S.<br>No.</th>
				<th class="data" width="50">Supplier Name</th>
				<th class="data">Project Name</th>
				<th class="data">Total Puchase</th>
				<th class="data">Payment Made</th>
				<th class="data">O/S Balance</th>
				
			</tr>
			<?php
			if($select_total > 0)
			{
				$i=1;
				while($select_data = mysql_fetch_array($select_result))
				{
					 ?>
					<tr class="data">
						<td class="data"><?php echo $i; ?></td>
						<td class="data"><?php echo $select_data['customer_name']; ?></td>
						<td class="data"><?php echo $select_data['project_name']; ?></td>
						<td class="data"><?php echo number_format($select_data['total_credit'],2,'.',''); ?></td>
						<td class="data"><?php echo number_format($select_data['total_debit'],2,'.',''); ?></td>
						<td class="data"><?php echo number_format($select_data['total_credit']-$select_data['total_debit'],2,'.',''); ?></td>
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
		<h3><?php echo get_field_value("name","project","id",$_REQUEST['project_id']); ?> Ledger</h3>
		<br>
		<table  border="1" cellpadding="5" cellspacing="0" width="100%">
			<tr class="data">
				
				
				<th class="data" width="70">Date</th>
				<th class="data">To&nbsp; / &nbsp;From</th>
				<th class="data">Bank</th>
				<th class="data">Description</th>
				<th class="data">Debit</th>
				<th class="data">Credit</th>
				<th class="data">Balance</th>
				
			</tr>
			<?php
				$i = 1;
				$select_query5 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan where project_id = '".$_REQUEST['project_id']."'   ";
				$select_result5 = mysql_query($select_query5) or die('error in query select cash query '.mysql_error().$select_query5);
				$select_data5 = mysql_fetch_array($select_result5);
				//echo $select_data3['total_credit']-$select_data3['total_debit'];
				$bal=$select_data5['total_credit']-$select_data5['total_debit'];
				while($select_data2 = mysql_fetch_array($select_result2))
				{
					if($i > 1)
					{
						$select_query6 = "select debit,credit from payment_plan where project_id = '".$_REQUEST['project_id']."' and id = '".$temp_payment_id."' LIMIT 0,1  ";
						$select_result6 = mysql_query($select_query6) or die('error in query select cash query '.mysql_error().$select_query6);
						$select_data6 = mysql_fetch_array($select_result6);
						
						if($select_data6['debit'] > 0 && $select_data6['description'] != "Opening Balance" )
						{
							$bal=(float)$bal+(float)$select_data6['debit'];
							
						}
						if($select_data6['credit'] > 0 && $select_data6['description'] != "Opening Balance" )
						{
							$bal=(float)$bal-(float)$select_data6['credit'];
							
						}
						
					}
					$temp_payment_id = $select_data2['payment_id'];
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

