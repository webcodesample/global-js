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


if(trim($_REQUEST['action_perform']) == "edit_supplier")
{
	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
	$fname=mysql_real_escape_string(trim($_REQUEST['fname']));
	$lname=mysql_real_escape_string(trim($_REQUEST['lname']));
	$full_name = $fname.' '.$lname;
	$phone=mysql_real_escape_string(trim($_REQUEST['phone']));
	$mobile=mysql_real_escape_string(trim($_REQUEST['mobile']));
	$email=mysql_real_escape_string(trim($_REQUEST['email']));
	$current_address=mysql_real_escape_string(trim($_REQUEST['current_address']));
	$permanent_address=mysql_real_escape_string(trim($_REQUEST['permanent_address']));
    $opening_balance=mysql_real_escape_string(trim($_REQUEST['opening_balance']));
    $gst_no=mysql_real_escape_string(trim($_REQUEST['gst_no']));
    $opening_balance_date=strtotime(mysql_real_escape_string(trim($_REQUEST['opening_balance_date'])));
    
	
	if(mysql_real_escape_string(trim($_REQUEST['same_current'])) == "yes")
	{
		$same_address = "yes";
	}
	else
	{
		$same_address = "no";
	}
	
	$quuerrr="select cust_id from customer where email='".$email."' and type = 'supplier' and cust_id != '".$_REQUEST['cust_id']."' ";
	
	$sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
	$no=mysql_num_rows($sql);
	if($no > 0)
	{	
		$error_msg = "E-Mail ID already exist try another";
	}
	else
	{
		$query="update customer set fname = '".$fname."', lname = '".$lname."', full_name = '".$full_name."', mobile = '".$mobile."',supply_gst_no='".$gst_no."', email = '".$email."', current_address = '".$current_address."', same_address = '".$same_address."', opening_balance = '".$opening_balance."' ,opening_balance_date = '".$opening_balance_date."',permanent_address = '".$permanent_address."',userid_update = '".$_SESSION['userId']."', update_date = '".getTime()."', updated_by = '".$_SESSION['userId']."', updated_on = '".time()."'  where cust_id = '".$_REQUEST['cust_id']."' and type = 'supplier'";
		$result= mysql_query($query) or die('error in query '.mysql_error().$query);
        
		$ss1="select * from payment_plan  where cust_id = '".$_REQUEST['cust_id']."' and description = 'Opening Balance'";
        $sr1=mysql_query($ss1);
        $tot_rw1=mysql_num_rows($sr1);
            if($tot_rw1 == 0)
            {
                $query2="insert into payment_plan set cust_id = '".$_REQUEST['cust_id']."', credit = '".$opening_balance."', description = 'Opening Balance', payment_date = '".$opening_balance_date."', create_date = '".getTime()."'";
				$result2= mysql_query($query2) or die('error in query '.mysql_error().$query2);
                                                   
            } 
			else
            {   
				$query2="update payment_plan set credit = '".$opening_balance."',payment_date = '".$opening_balance_date."' where cust_id = '".$_REQUEST['cust_id']."' and description = 'Opening Balance'";
				$result2= mysql_query($query2) or die('error in query Cash query '.mysql_error().$query2);
            }
		header("Location:supplier.php?msg=supplier Updated successfully");
	}
}



$select_query = "select * from customer where cust_id = '".$_REQUEST['cust_id']."'";
$select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
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
		Edit supplier</h4>
  </td>
        <td width="" style="float:right;">
            <a href="supplier.php" title="Back" style=""><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
            </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
  <?php include_once("main_body_open.php") ?>
  <table width="100%" style="padding:10px;" >
  <tr><td>
  <table width="100%" class="tbl_border" >
    <tr><td>

	<?php if($error_msg != "") { ?>
	<div class="gagal">
		
		<?php echo $error_msg; ?>
		</div>
	<?php } ?>
	
	<form name="supplier_form" id="supplier_form" action="" method="post" >
		<table width="95%">
			<tr>
            <td valign="top">
            	<table width="100%">
				<tr><td width="125px">First Name</td>
			<td><input type="text" name="fname" id="fname" value="<?php echo $select_data['fname']; ?>" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			<tr><td >Mobile</td>
			<td><input type="text" name="mobile" id="mobile" value="<?php echo $select_data['mobile']; ?>" ></td></tr>
			<tr><td >Opening Balance</td>
            <td><input type="text" name="opening_balance" id="opening_balance" value="<?php echo $select_data['opening_balance']; ?>" ></td></tr>
            <tr><td >GST No.</td>
            <td><input type="text" id="gst_no" name="gst_no" value="<?php echo $select_data['supply_gst_no']; ?>"/></td></tr>
           
			<tr><td align="left" valign="top" >Current Address</td>
			<td><textarea name="current_address" id="current_address" style="width:250px; height:100px;"><?php echo $select_data['current_address']; ?></textarea></td></tr>
			
			</table>
			</td>
			<td valign="top">
            	<table width="100%">
				<tr><td >Last Name</td>
			<td><input type="text" name="lname" id="lname" value="<?php echo $select_data['lname']; ?>" ></td></tr>
			<tr><td >E-Mail Id</td>
			<td><input type="text" name="email" id="email" value="<?php echo $select_data['email']; ?>" ></td></tr>
			<tr><td>Opening Date</td>
            <td>
            <input type="date"  name="opening_balance_date" id="opening_balance_date" value="<?php echo date("d-m-Y",$select_data['opening_balance_date']); ?>" max="<?= date('Y-m-d',time()) ?>">
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td >Same as Current</td>
			<td><input type="checkbox" name="same_current" id="same_current" value="yes" onClick="return same_address();" <?php if($select_data['same_address'] == 'yes') { echo 'checked="checked"'; } ?>   /></td></tr>
			
			<tr><td align="left" valign="top" >Permanent Address</td>
			<td><textarea name="permanent_address" id="permanent_address" style="width:250px; height:100px;"><?php echo $select_data['permanent_address']; ?></textarea></td></tr>
			
			</table>
			</td>
			</tr>	
			
			
			
			
			
			
			<tr><td></td><td><br>
			<input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
			</td></tr>
		</table>
		<input type="hidden" name="action_perform" id="action_perform" value="" >
		
		</form>
	</td></tr></table></td></tr></table>
		
		
		
		<?php include_once("main_body_close.php") ?>
        <?php include_once ("footer.php"); ?>
        
</body>
</html>
<script>
function add_div()
{
	$("#adddiv").toggle("slow");
}

function validation()
{
	if($("#fname").val() == "")
	{
		alert("Please enter first name.");
		$("#fname").focus();
		return false;
	}
	/*else if($("#mobile").val() == "")
	{
		alert("Please enter mobile number.");
		$("#mobile").focus();
		return false;
	}
	else if($("#email").val() == "")
	{
		alert("Please enter email address.");
		$("#email").focus();
		return false;
	}
	else if($("#current_address").val() == "")
	{
		alert("Please enter current address.");
		$("#current_address").focus();
		return false;
	}*/
	else
	{
		$("#action_perform").val("edit_supplier");
		$("#supplier_form").submit();
		return true;
	}
	
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