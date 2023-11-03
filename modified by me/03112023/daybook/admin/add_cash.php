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
    $bank_account_name=mysql_real_escape_string(trim($_REQUEST['bank_account_name']));
    $bank_account_number=mysql_real_escape_string(trim($_REQUEST['bank_account_number']));
    $bank_account_address=mysql_real_escape_string(trim($_REQUEST['bank_account_address']));
    $opening_balance=mysql_real_escape_string(trim($_REQUEST['opening_balance']));
    $opening_balance = str_replace(",","",$opening_balance);
    $bank_name=mysql_real_escape_string(trim($_REQUEST['bank_name']));
    $bank_address=mysql_real_escape_string(trim($_REQUEST['bank_address']));
    
    $exact_bank_name=mysql_real_escape_string(trim($_REQUEST['exact_bank_name']));
    $ifsc_code=mysql_real_escape_string(trim($_REQUEST['ifsc_code']));
    
    $new_date =convert_date_format(trim($_REQUEST['opening_balance_date']));
    //echo $new_date;
    //exit;
    $opening_balance_date=strtotime(mysql_real_escape_string(trim($new_date)));
    
    $quuerrr="select * from bank where bank_account_name = '".$bank_account_name."' and type='cash account'  ";
    
    $sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
    $no=mysql_num_rows($sql);
    if($no > 0)
    {
        $check_data=mysql_fetch_array($sql);
       
            $error_msg = "Cash account name  already exist try another";
       
        
        
    }
    else
    {
        /*$query="insert into bank set bank_account_name = '".$bank_account_name."', bank_account_number= '".$bank_account_number."',bank_account_address = '".$bank_account_address."', opening_balance = '".$opening_balance."',exact_bank_name= '".$exact_bank_name."',ifsc_code = '".$ifsc_code."', opening_balance_date = '".$opening_balance_date."', bank_name = '".$bank_name."', bank_address = '".$bank_address."', type = 'cash account', create_date = '".getTime()."'";*/
        $query="insert into bank set bank_account_name = '".$bank_account_name."',  opening_balance = '".$opening_balance."', opening_balance_date = '".$opening_balance_date."', type = 'cash account', create_date = '".getTime()."'";
        $result= mysql_query($query) or die('error in query bank query '.mysql_error().$query);
        
        $bank_id = mysql_insert_id();
        
        $query2="insert into payment_plan set bank_id = '".$bank_id."', credit = '".$opening_balance."', description = 'Opening Balance', payment_date = '".$opening_balance_date."', create_date = '".getTime()."'";
        $result2= mysql_query($query2) or die('error in query '.mysql_error().$query2);
        
        $msg = "Account added successfully.";
        header("Location:cash.php?msg=Account added successfully");
    }
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
        New Cash Account Create </h4>
  </td>
        <td width="" style="float:right;">
            <a href="cash.php" title="Back" style=""><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
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
            
        
            <tr><td width="225px">Cash Account Name</td>
            <td><input type="text" name="bank_account_name" id="bank_account_name" value="<?php echo $_REQUEST['bank_account_name']; ?>" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td >Opening Balance</td>
            <td><input type="text" name="opening_balance" id="opening_balance" value="<?php echo $_REQUEST['opening_balance']; ?>" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <tr><td >Opening Balance Date</td>
            <td>
                <input type="text"  name="opening_balance_date" id="opening_balance_date" value="<?php echo $_REQUEST['opening_balance_date']; ?>" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('opening_balance_date')" style="cursor:pointer"/>
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td colspan="2"><br></td></tr>
            <tr><td></td><td>
            <input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
            </td></tr>   <tr><td colspan="2"><br></td></tr>
        </table></td>
        </tr>
            <tr><td colspan="2"><br></td></tr>
        </table>
        <input type="hidden" name="action_perform" id="action_perform" value="" >
        <input type="hidden" name="del_id" id="del_id" value="" >
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