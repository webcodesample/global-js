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


if(trim($_REQUEST['action_perform']) == "edit_cash")
{
	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
	$bank_account_name=mysql_real_escape_string(trim($_REQUEST['bank_account_name']));
	$opening_balance=mysql_real_escape_string(trim($_REQUEST['opening_balance']));
    $opening_balance_date=strtotime(mysql_real_escape_string(trim($_REQUEST['opening_balance_date'])));	
	$quuerrr="select * from bank where bank_account_name='".$bank_account_name."' and id != '".$_REQUEST['id']."' ";
	$sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
	$no=mysql_num_rows($sql);
	if($no > 0)
	{
		
			$error_msg = "Cash account name already exist try another";
		
	}
	else
	{

	$query="update bank set bank_account_name = '".$bank_account_name."',opening_balance = '".$opening_balance."',opening_balance_date = '".$opening_balance_date."' where id = '".$_REQUEST['id']."'";
	$result= mysql_query($query) or die('error in query Cash query '.mysql_error().$query);
	
	
	/*$query2="update payment_plan set credit = '".$opening_balance."' , payment_date = '".$opening_balance_date."' where bank_id = '".$_REQUEST['id']."' and description = 'Opening Balance'";
	$result2= mysql_query($query2) or die('error in query Cash query '.mysql_error().$query2);
    */
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
   
	
	header("Location:cash.php?msg=Account added successfully");
	
	}
}



$select_query = "select * from bank where id = '".$_REQUEST['id']."'";
$select_result = mysql_query($select_query) or die('error in query select cash query '.mysql_error().$select_query);
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
        Edit Cash Account </h4>
  </td>
        <td width="" style="float:right;">
            <a href="cash.php" title="Back" style=""><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
            </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
  <?php include_once("main_body_open.php") ?>
  
	
	<form name="cash_form" id="cash_form" action="" method="post" >
		<table width="95%" style="padding:30px;"
>
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
        
			<tr><td width="125px">Cash Account Name</td>
			<td><input type="text" name="bank_account_name" id="bank_account_name" value="<?php echo $select_data['bank_account_name']; ?>" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td >Opening Balance</td>
			<td><input type="text" name="opening_balance" id="opening_balance" value="<?php echo $select_data['opening_balance']; ?>" ></td></tr>
			 <tr><td >Opening Balance Date</td>
            <td>
                <input type="date"  name="opening_balance_date" id="opening_balance_date" value="<?php echo date('Y-m-d',$select_data['opening_balance_date']); ?>" max="<?= date('Y-m-d',time()) ?>">
            </td></tr>
           
			<tr><td colspan="2"><br></td></tr>
			<tr><td></td><td>
			<input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
			</td></tr><tr><td colspan="2"><br></td></tr></table></td>
        </tr>
		</table>
		<input type="hidden" name="action_perform" id="action_perform" value="" >
		
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
{
	if($("#bank_account_name").val() == "")
	{
		alert("Please enter Cash account name.");
		$("#bank_account_name").focus();
		return false;
	}
	else
	{
		$("#action_perform").val("edit_cash");
		$("#cash_form").submit();
		return true;
	}
	
}

</script>