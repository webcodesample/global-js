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


if(trim($_REQUEST['action_perform']) == "edit_import_file")
{
	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
	// to_name , from_name , project , description , payment_date , amount , payment_type , data_id
	
	$to_name=mysql_real_escape_string(trim($_REQUEST['to_name']));
	$from_name=mysql_real_escape_string(trim($_REQUEST['from_name']));
	$project=mysql_real_escape_string(trim($_REQUEST['project']));
	$description=mysql_real_escape_string(trim($_REQUEST['description']));
	$payment_date=strtotime(mysql_real_escape_string(trim($_REQUEST['payment_date'])));
	$amount=mysql_real_escape_string(trim($_REQUEST['amount']));
	$payment_type=mysql_real_escape_string(trim($_REQUEST['payment_type']));
	$data_id=mysql_real_escape_string(trim($_REQUEST['data_id']));
	
	$file_import_id=mysql_real_escape_string(trim($_REQUEST['file_import_id']));
	
	
	$query="update tbl_info set from_name = '".$from_name."',  to_name = '".$to_name."',project='".$project."',amount ='".$amount."',description ='".$description."',payment_date ='".$payment_date."',payment_type ='".$payment_type."',update_date = '".getTime()."' where id = '".$_REQUEST['data_id']."' ";
	$result= mysql_query($query) or die('error in query  '.mysql_error().$query);
    
	//main_fila_id,page_action
    $main_fila_id=mysql_real_escape_string(trim($_REQUEST['main_fila_id']));
	$page_action=mysql_real_escape_string(trim($_REQUEST['page_action']));  
    
	//$back_page=mysql_real_escape_string(trim($_REQUEST['back_page']));
	//header("Location:import_file-ledger.php?file_id=$back_page");
	//header("Location:$back_page");
	if($page_action=="import_make_payment_ledger")
	{
		header("Location:import_file_make_payment-ledger.php?file_id=$main_fila_id");
	}
	else if($page_action=="import_file-ledger")
	{
		header("Location:import_file-ledger.php?file_id=$main_fila_id");
	} 
	
}

$action_page = $_REQUEST['action_page'];
$file_id = $_REQUEST['file_id'];

$select_query = "select * from tbl_info where id = '".$_REQUEST['id']."'";
$select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
$select_data = mysql_fetch_array($select_result);
if($action_page=="import_make_payment_ledger")
{
	$final_action ="import_file_make_payment-ledger.php?file_id=".$file_id;
	$main_fila_id_n=$file_id;
	$page_action_nm = "import_make_payment_ledger";
} 
else
{
	$final_action="import_file-ledger.php?file_id=".$select_data['file_import_id'];
	$main_fila_id_n=$select_data['file_import_id'];
	$page_action_nm = "import_file-ledger";
}
?>

<!DOCTYPE html>
<?php include_once ("top_header1.php"); ?>   
<script src="js/datetimepicker_css.js"></script>
<body  data-home-page-title="" class="u-body u-xl-mode" data-lang="en">
  <?php include_once ("top_header2.php"); ?> 
  <?php include_once ("top_menu.php"); ?>
  <?php include_once("main_heading_open.php") ?>
  
	<table style="width:100%;" border="0" style="padding:0px 0px; margin-top:-10px;">
    <tr>
        <td style="align:top;" align="left">
        <h4 class="u-text-2 u-text-palette-1-base " style="padding:0px; margin:0px;">
       Edit Import file data:</h4>
  </td>
        <td width="" style="float:right;">
            <a href="<?php echo $final_action; ?>" title="Back" style=""><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
            </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
  <?php include_once("main_body_open.php") ?>
  	
	
	<form name="import_file_form" id="import_file_form" action="" method="post" >
		<table width="95%" style="padding:30px;"> 
		<tr><td colspan="2">
        <?php if($error_msg != "") { ?>
	<div class="gagal">
		
		<?php echo $error_msg; ?>
		</div>
	<?php } ?>

        </td></tr>


			<tr><td width="150px">To</td>
			<td><input type="text" name="to_name" id="to_name" value="<?php echo $select_data['to_name']; ?>" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			<tr><td width="125px">From</td>
            <td><input type="text" name="from_name" id="from_name" value="<?php echo $select_data['from_name']; ?>" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
			<tr><td >Project</td>
			<td><input type="text" name="project" id="project" value="<?php echo $select_data['project']; ?>" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td align="left" valign="top" >Description</td>
			<td><textarea name="description" id="description" style="width:250px; height:100px;"><?php echo $select_data['description']; ?></textarea>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td >Payment Date</td>
            <td>
                <input type="text"  name="payment_date" id="payment_date" value="<?php echo date("d-m-Y",$select_data['payment_date']); ?>" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('payment_date')" style="cursor:pointer"/>
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
			<tr><td >Amount</td>
			<td><input type="text" name="amount" id="amount" value="<?php echo $select_data['amount']; ?>" ></td></tr>
			
			<!--<tr><td >Date</td>
			<td><input type="text" name="opening_balance_date" id="opening_balance_date" value="<?php echo date("d-m-Y",$select_data['opening_balance_date']); ?>" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>-->
			
			<tr><td >Payment Type</td>
			<td>
			<?php 
				if($select_data['final_pay_type_flag']==1)
				{ ?>
					<select name="final_payment_type" id="final_payment_type" style="width:250px; height: 25px;">
                      <option value="received_payment" <?php if($_REQUEST['search_pay_type']=="received_payment"){ echo "selected='selected'"; } ?> >Received Payment</option> 
                      <option value="make_payment" <?php if($_REQUEST['search_pay_type']=="make_payment"){ echo "selected='selected'"; } ?> >Make Payment</option>
                      <option value="internal_payment" <?php if($_REQUEST['search_pay_type']=="internal_payment"){ echo "selected='selected'"; } ?> >Internal Transfer</option>
                      <option value="lad_make_payment" <?php if($_REQUEST['search_pay_type']=="lad_make_payment"){ echo "selected='selected'"; } ?> >Loan-Adv Make Payment</option>
                      <option value="lad_received_payment" <?php if($_REQUEST['search_pay_type']=="lad_received_payment"){ echo "selected='selected'"; } ?> >Loan-Adv Received Payment</option>
                     
                     </select>    
                      
		<?php }
				else{
			?>
			<input type="text" name="payment_type" id="payment_type" value="<?php echo $select_data['payment_type']; ?>" ></td></tr>
			<?php } ?>
			
			<tr><td></td><td>
			<input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
			</td></tr>
		</table>
		<input type="hidden" name="action_perform" id="action_perform" value="" >
		<input type="hidden" name="data_id" id="data_id" value="<?php echo $select_data['id']; ?>">
		<input type="hidden" name="file_import_id" id="file_import_id" value="<?php echo $select_data['file_import_id']; ?>" >
		<input type="hidden" name="bake_page" id="back_page" value="<?php echo $final_action; ?>" >
		<input type="hidden" name="main_fila_id" id="main_fila_id" value="<?php echo $main_fila_id_n; ?>">
		<input type="hidden" name="page_action" id="page_action" value="<?php echo $page_action_nm; ?>">
		
		</form>
		
		
		
		
	
		<?php include_once("main_body_close.php") ?>
        <?php include_once ("footer.php"); ?>
        
</body>
</html>
<script>

function validation()
{  
	if($("#to_name").val() == "")
	{
		alert("Please enter To Type.");
		$("#to_name").focus();
		return false;
	}
	else if($("#from_name").val() == "")
	{
		alert("Please enter From Type.");
		$("#from_name").focus();
		return false;
	}
	else if($("#project").val() == "")
	{
		alert("Please enter Project Name.");
		$("#project").focus();
		return false;
	}
	else if($("#description").val() == "")
	{
		alert("Please enter Description.");
		$("#description").focus();
		return false;
	}
	else if($("#payment_date").val() == "")
	{
		alert("Please enter Payment date.");
		$("#payment_date").focus();
		return false;
	}
	else if($("#amount").val() == "")
	{
		alert("Please enter Amount.");
		$("#amount").focus();
		return false;
	}
	else if($("#payment_type").val() == "")
	{
		alert("Please enter Payment Type.");
		$("#payment_type").focus();
		return false;
	}
	else
	{
		$("#action_perform").val("edit_import_file");
		$("#import_file_form").submit();
		return true;
	}
	
}

</script>