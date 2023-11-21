<?php session_start();
include_once("set_con.php");
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


if(trim($_REQUEST['action_perform']) == "edit_bank")
{
	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
	$bank_account_name=mysql_real_escape_string(trim($_REQUEST['bank_account_name']));
	$bank_account_number=mysql_real_escape_string(trim($_REQUEST['bank_account_number']));
	$bank_account_address=mysql_real_escape_string(trim($_REQUEST['bank_account_address']));
	$opening_balance=mysql_real_escape_string(trim($_REQUEST['opening_balance']));
	/*$opening_balance_date=strtotime(mysql_real_escape_string(trim($_REQUEST['opening_balance_date'])));*/
	$bank_name=mysql_real_escape_string(trim($_REQUEST['bank_name']));
	$bank_address=mysql_real_escape_string(trim($_REQUEST['bank_address']));
	$opening_balance_date=strtotime(mysql_real_escape_string(trim($_REQUEST['opening_balance_date'])));
	//strtotime($_REQUEST['payment_date'])
    //exact_bank_name,ifsc_code
    $exact_bank_name=mysql_real_escape_string(trim($_REQUEST['exact_bank_name']));
    $ifsc_code=mysql_real_escape_string(trim($_REQUEST['ifsc_code']));
	$quuerrr="select * from bank where bank_account_number='".$bank_account_number."' and id != '".$_REQUEST['id']."' ";
	
	$sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
	$no=mysql_num_rows($sql);
	if($no > 0)
	{
		
			$error_msg = "Bank account number already exist try another";
		
	}
	else
	{
	/*$query="update bank set bank_account_name = '".$bank_account_name."', bank_account_number= '".$bank_account_number."',bank_account_address = '".$bank_account_address."', opening_balance = '".$opening_balance."', opening_balance_date = '".$opening_balance_date."', bank_name = '".$bank_name."', bank_address = '".$bank_address."' where id = '".$_REQUEST['id']."'";*/
	//,
    //exact_bank_name= '".$exact_bank_name."',ifsc_code = '".$ifsc_code."',
	$query="update bank set bank_account_name = '".$bank_account_name."', bank_account_number= '".$bank_account_number."',bank_account_address = '".$bank_account_address."',opening_balance = '".$opening_balance."',exact_bank_name= '".$exact_bank_name."',ifsc_code = '".$ifsc_code."', opening_balance_date = '".$opening_balance_date."', bank_name = '".$bank_name."', bank_address = '".$bank_address."' where id = '".$_REQUEST['id']."'";
	$result= mysql_query($query) or die('error in query bank query '.mysql_error().$query);
	
	$query2="update payment_plan set credit = '".$opening_balance."', payment_date = '".$opening_balance_date."' where bank_id = '".$_REQUEST['id']."' and description = 'Opening Balance'";
	$result2= mysql_query($query2) or die('error in query Cash query '.mysql_error().$query2);
    
     $ss1="select * from payment_plan  where bank_id = '".$_REQUEST['id']."' and description = 'Opening Balance'";
            $sr1=mysql_query($ss1);
            $tot_rw1=mysql_num_rows($sr1);
            if($tot_rw1 == 0)
            {
                $query2="insert into payment_plan set bank_id = '".$_REQUEST['id']."', credit = '".$opening_balance."', description = 'Opening Balance', payment_date = '".$opening_balance_date."', create_date = '".getTime()."'";
        $result2= mysql_query($query2) or die('error in query '.mysql_error().$query2);
                                                   
            } else
            {   
         $query2="update payment_plan set credit = '".$opening_balance."',payment_date = '".$opening_balance_date."' where bank_id = '".$_REQUEST['id']."' and description = 'Opening Balance'";
    $result2= mysql_query($query2) or die('error in query Cash query '.mysql_error().$query2);
            }
   
	
	header("Location:bank.php?msg=Account added successfully");
	
	}
}



$select_query = "select * from bank where id = '".$_REQUEST['id']."'";
$select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
$select_data = mysql_fetch_array($select_result)
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
        Edit Bank Account </h4>
  </td>
        <td width="" style="float:right;">
            <a href="bank.php" title="Back" style=""><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
            </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
  <?php include_once("main_body_open.php") ?>
  
	<form name="bank_form" id="bank_form" style="padding:30px;" action="" method="post" >
		<table width="95%">
		<tr><td colspan="2">
        <?php if($error_msg != "") { ?>
	<div class="gagal">
		
		<?php echo $error_msg; ?>
		</div>
	<?php } ?>

        </td></tr>
        <tr>
            <td colspan="2">
            <table width="100%" class="tbl_border" style="">
            
        <tr>
            <td valign="top">
        <table width="100%" style="">
        
			<tr><td width="150px">Bank Account Name</td>
			<td><input type="text" name="bank_account_name" id="bank_account_name" value="<?php echo $select_data['bank_account_name']; ?>" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			<tr><td width="125px">Exact Bank Account Name</td>
            <td><input type="text" name="exact_bank_name" id="exact_bank_name" value="<?php echo $select_data['exact_bank_name']; ?>" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
			<tr><td >Bank Account Number</td>
			<td><input type="text" name="bank_account_number" id="bank_account_number" value="<?php echo $select_data['bank_account_number']; ?>" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td align="left" valign="top" >Bank Account Address</td>
			<td><textarea name="bank_account_address" id="bank_account_address" style="width:250px; height:100px;"><?php echo $select_data['bank_account_address']; ?></textarea>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            </table>
        </td>
        <td valign="top">
        <table width="100%" style="">
        
			<tr><td >Opening Balance Date</td>
            <td>
                <input type="date"  name="opening_balance_date" id="opening_balance_date" value="<?php echo date('Y-m-d',$select_data['opening_balance_date']); ?>" max="<?= date('Y-m-d',time()) ?>">
				</td></tr>
            
			<tr><td >Opening Balance</td>
			<td><input type="text" name="opening_balance" id="opening_balance" value="<?php echo $select_data['opening_balance']; ?>" ></td></tr>
			
			<!--<tr><td >Date</td>
			<td><input type="text" name="opening_balance_date" id="opening_balance_date" value="<?php echo date("d-m-Y",$select_data['opening_balance_date']); ?>" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>-->
			
			<tr><td >Bank Name</td>
			<td><input type="text" name="bank_name" id="bank_name" value="<?php echo $select_data['bank_name']; ?>" ></td></tr>
			<tr><td width="125px">IFSC Code</td>
            <td><input type="text" name="ifsc_code" id="ifsc_code" value="<?php echo $select_data['ifsc_code']; ?>" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
			<tr><td align="left" valign="top" >Bank Address</td>
			<td><textarea name="bank_address" id="bank_address" style="width:250px; height:100px;"><?php echo $select_data['bank_address']; ?></textarea></td></tr>
			
			</table> 
        </td>
        </tr>
        <tr><td></td><td>
            <input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
            </td></tr>
            
        </table> 
        </td>
        </tr>
        
			
		</table>
		<input type="hidden" name="action_perform" id="action_perform" value="" >
		
		</form>
		
		
		
		<?php include_once("main_body_close.php") ?>
  <!-------------->
   
  <?php include_once ("footer.php"); ?>
</body>
</html>
<script>
function add_div()
{
	$("#adddiv").toggle("slow");
}
$(function() {
	 
	//$('#opening_balance_date').datepick({dateFormat: 'dd-mm-yyyy'});
	
});
function validation()
{
	if($("#bank_account_name").val() == "")
	{
		alert("Please enter bank account name.");
		$("#bank_account_name").focus();
		return false;
	}
	else if($("#bank_account_number").val() == "")
	{
		alert("Please enter bank account number.");
		$("#bank_account_number").focus();
		return false;
	}
	else if($("#bank_account_address").val() == "")
	{
		alert("Please enter bank account address.");
		$("#bank_account_address").focus();
		return false;
	}
	/*else if($("#opening_balance").val() == "")
	{
		alert("Please enter bank opening balance.");
		$("#opening_balance").focus();
		return false;
	}
	else if($("#opening_balance_date").val() == "")
	{
		alert("Please enter bank opening balance date.");
		$("#opening_balance_date").focus();
		return false;
	}
	else if($("#bank_name").val() == "")
	{
		alert("Please enter bank name.");
		$("#bank_name").focus();
		return false;
	}
	else if($("#bank_address").val() == "")
	{
		alert("Please enter bank address.");
		$("#bank_address").focus();
		return false;
	}*/
	else
	{
		$("#action_perform").val("edit_bank");
		$("#bank_form").submit();
		return true;
	}
	
}

</script>