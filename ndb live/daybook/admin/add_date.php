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

if(trim($_REQUEST['action_perform']) == "add_bank")
{
    /*echo '<pre>';
    print_r($_REQUEST);
    exit;*/
    
    $new_date =convert_date_format(trim($_REQUEST['opening_balance_date']));
    echo $new_date;
    
        
     }
	
	
	
	


?>
<html>
<head>
<title>Admin Panel</title>

</head>
<script src="js/datetimepicker_css.js"></script>
<body>
<?php 
include_once("header.php");
?>

<div id="wrapper">
	<?php
	include_once("leftbar.php");
	?>
	<div id="rightContent">
	<h3>New Cash Account Create</h3>
	
	<?php if($error_msg != "") { ?>
	<div class="gagal">
		
		<?php echo $error_msg; ?>
		</div>
	<?php } ?>
	<table width="100%" cellpadding="0" cellspacing="0" border="0" >
			<tr >
				<td align="right">
					<a href="cash.php" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
				</td>
			</tr>
		</table>
	<form name="bank_form" id="bank_form" action="" method="post" >
        <table width="95%">
        <tr><td >old Date</td>
            <td>
<?php echo $new_date; ?>               </td></tr>
   
            <tr><td >New Date</td>
            <td>
                <input type="text"  name="opening_balance_date" id="opening_balance_date" value="<?php echo $_REQUEST['opening_balance_date']; ?>" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('opening_balance_date')" style="cursor:pointer"/>
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <!--
            <tr><td >Bank Name</td>
            <td><input type="text" name="bank_name" id="bank_name" value="<?php echo $_REQUEST['bank_name']; ?>" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td width="125px">IFSC Code</td>
            <td><input type="text" name="ifsc_code" id="ifsc_code" value="<?php echo $select_data['ifsc_code']; ?>" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <tr><td align="left" valign="top" >Bank Address</td>
            <td><textarea name="bank_address" id="bank_address" style="width:250px; height:100px;"><?php echo $_REQUEST['bank_address']; ?></textarea>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            -->
            <tr><td></td><td>
            <input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
            </td></tr>
            <tr><td colspan="2"><br><h3></h3><br><br><br></td></tr>
        </table>
        <input type="hidden" name="action_perform" id="action_perform" value="" >
        <input type="hidden" name="del_id" id="del_id" value="" >
        </form>		
		
		
		
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
    if($("#bank_account_name").val() == "")
    {
        alert("Please enter bank account name.");
        $("#bank_account_name").focus();
        return false;
    }  
    else
    {
        $("#action_perform").val("add_bank");
        $("#bank_form").submit();
        return true;
    }
    
}

</script>