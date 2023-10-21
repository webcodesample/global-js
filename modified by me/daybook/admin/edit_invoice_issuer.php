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

//company_name,reg_no,vat_no
if(trim($_REQUEST['action_perform']) == "edit_invoice_issuer")
{
	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
    $issuer_id = mysql_real_escape_string(trim($_REQUEST['issuer_id']));
	$issuer_name=mysql_real_escape_string(trim($_REQUEST['issuer_name']));
    $display_name=mysql_real_escape_string(trim($_REQUEST['display_name']));
    
    $company_name = mysql_real_escape_string(trim($_REQUEST['company_name']));
    $reg_no=mysql_real_escape_string(trim($_REQUEST['reg_no']));
    $vat_no=mysql_real_escape_string(trim($_REQUEST['vat_no']));
    
    
    $mobile=mysql_real_escape_string(trim($_REQUEST['mobile']));
    $email=mysql_real_escape_string(trim($_REQUEST['email']));
    $address=mysql_real_escape_string(trim($_REQUEST['address']));
    
    $gst_no=mysql_real_escape_string(trim($_REQUEST['gst_no']));
   // $cin_no=base64_encode(mysql_real_escape_string(trim($_REQUEST['cin_no'])));
    $pan_no=mysql_real_escape_string(trim($_REQUEST['pan_no']));
     $cin_no=mysql_real_escape_string(trim($_REQUEST['cin_no']));
   
    
        $query="update invoice_issuer set issuer_name = '".$issuer_name."', display_name = '".$display_name."', mobile = '".$mobile."', email = '".$email."', address = '".$address."', company_name = '".$company_name."', reg_no = '".$reg_no."', vat_no = '".$vat_no."', gst_no = '".$gst_no."', cin_no = '".$cin_no."', pan_no = '".$pan_no."' where id=".$issuer_id."";
        $result= mysql_query($query) or die('error in query '.mysql_error().$query);
        $msg = "Invoice Issuer update successfully.";
		header("Location:invoice-issuer.php?msg=invoice_issuer Updated successfully");
	
	
}



$select_query = "select * from invoice_issuer where id = '".$_REQUEST['id']."'";
$select_result = mysql_query($select_query) or die('error in query select invoice_issuer query '.mysql_error().$select_query);
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
        Edit invoice issuer </h4>
  </td>
        <td width="" style="float:right;">
            <a href="invoice-issuer.php" title="Back" style=""><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
            </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
  <?php include_once("main_body_open.php") ?>
	
	
	<?php if($error_msg != "") { ?>
	<div class="gagal">
		
		<?php echo $error_msg; ?>
		</div>
	<?php } ?>
	
	<form name="invoice_issuer_form" id="invoice_issuer_form" action="" method="post" >
    <input type="hidden" name="issuer_id" id="issuer_id" value="<?php echo $_REQUEST['id']; ?>">
		<table width="100%" style="padding:20px;">
            <tr>
                <td valign="top">

			<table width="100%" class="tbl_border">
            <tr>
            <td colspan="2"><h4 class="u-text-2 u-text-palette-1-base " style="padding:0px; margin:0px;">Invoice Issuer : <?php echo $select_data['company_name']; ?></h4></td></tr>

            <tr>
            <td valign="top">
                <table width="100%">
                <tr><td valign="top" >Invoice Display Name</td>
            <td><input type="text" id="company_name"  name="company_name" value="<?php echo $select_data['company_name']; ?>"/></td></tr>
            <tr><td width="200px">Issuer Name</td>
            <td><input type="text" id="issuer_name"  name="issuer_name" value="<?php echo $select_data['issuer_name']; ?>"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td >Email</td>
            <td><input type="text" id="email"  name="email" value="<?php echo $select_data['email']; ?>"/></td></tr>
            <tr><td align="left" valign="top" >VAT No.</td>
            <td><input type="text"  name="vat_no" id="vat_no" value="<?php echo $select_data['vat_no']; ?>" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td align="left" valign="top" >GST No.</td>
            <td><input type="text"  name="gst_no" id="gst_no" value="<?php echo $select_data['gst_no']; ?>" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td >PAN No.</td>
            <td><input type="text" id="pan_no"  name="pan_no" value="<?php echo $select_data['pan_no']; ?>"/></td></tr>
            <tr><td >CIN No.</td>
            <td><input type="text" id="cin_no" name="cin_no" value="<?php echo $select_data['cin_no']; ?>"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            
                </table>
            </td>
            <td valign="top">
                <table width="100%">
                <tr><td width="200px">Company Reg No.</td>
            <td><input type="text" id="reg_no"  name="reg_no" value="<?php echo $select_data['reg_no']; ?>"/></td></tr>
            <tr><td >Our Invoice contact</td>
            <td><input type="text" id="display_name"  name="display_name" value="<?php echo $select_data['display_name']; ?>"/></td></tr>
            <tr><td >Mobile</td>
            <td><input type="text" id="mobile"  name="mobile" value="<?php echo $select_data['mobile']; ?>"/></td></tr>
            <tr><td valign="top">Address</td>
            <td><textarea name="address" id="address" style="width:250px; height:100px;"><?php echo $select_data['address']; ?></textarea>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
                </table>
            </td>
            </tr>    
            
            
            
            
            
            
           
            <tr><td colspan="2" align="center">
            <br><br><br>
            <input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
            </td></tr>
        </table>
            </td></tr>
            	</table>
		
		<input type="hidden" name="action_perform" id="action_perform" value="" >
		<input type="hidden" name="count" id="count" value="<?php echo $i; ?>"  />	
		
		</form>
		
		
		
		
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
{   //issuer_name,display_name,email,mobile,address,gst_no,cin_no,pan_no
//company_name,reg_no,vat_no
/*	if($("#company_name").val() == "")
	{
		alert("Please enter Company name.");
		$("#company_name").focus();
		return false;
	}else if($("#reg_no").val() == "")
    {
        alert("Please enter Company Reg. No Name.");
        $("#reg_no").focus();
        return false;
    }else
    */ if($("#issuer_name").val() == "")
    {
        alert("Please enter Invoice Issuer name.");
        $("#issuer_name").focus();
        return false;
    }/*else if($("#display_name").val() == "")
    {
        alert("Please enter Our Invoice contact.");
        $("#display_name").focus();
        return false;
    } /*
    else if($("#email").val() == "")
    {
        alert("Please enter Email Id.");
        $("#email").focus();
        return false;
    }
	else if($("#mobile").val() == "")
	{
		alert("Please enter mobile number.");
		$("#mobile").focus();
		return false;
	}
    else if($("#address").val() == "")
    {
        alert("Please enter Address.");
        $("#address").focus();
        return false;
    }
    else if($("#vat_no").val() == "")
    {
        alert("Please enter VAT number.");
        $("#vat_no").focus();
        return false;
    }
    else if($("#gst_no").val() == "")
    {
        alert("Please enter GST number.");
        $("#gst_no").focus();
        return false;
    }
	else if($("#cin_no").val() == "")
	{
		alert("Please enter CIN Number.");
		$("#cin_no").focus();
		return false;
	}
	else if($("#pan_no").val() == "")
	{
		alert("Please enter PAN Number.");
		$("#pan_no").focus();
		return false;
	} */
	else
	{
		$("#action_perform").val("edit_invoice_issuer");
		$("#invoice_issuer_form").submit();
		return true;
	}
	
}
 

</script>