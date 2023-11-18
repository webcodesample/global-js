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


if(trim($_REQUEST['action_perform']) == "edit_project")
{
	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
	$name=mysql_real_escape_string(trim($_REQUEST['name']));
	$opening_balance=mysql_real_escape_string(trim($_REQUEST['opening_balance']));
	
	$opening_balance_date=strtotime(mysql_real_escape_string(trim($_REQUEST['opening_balance_date'])));
	$quuerrr="select * from project where name='".$name."' and id != '".$_REQUEST['id']."' ";
	
	$sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
	$no=mysql_num_rows($sql);
	if($no > 0)
	{
		
			$error_msg = "Project name already exist try another";
		
	}
	else
	{
	$query="update project set name = '".$name."', opening_balance = '".$opening_balance."' ,opening_balance_date = '".$opening_balance_date."', updated_by = '".$_SESSION['userId']."', updated_on = '".time()."' where id = '".$_REQUEST['id']."'";
	$result= mysql_query($query) or die('error in query project query '.mysql_error().$query);
	
	/*$query2="update payment_plan set credit = '".$opening_balance."',payment_date = '".$opening_balance_date."' where project_id = '".$_REQUEST['id']."' and description = 'Opening Balance'";
	$result2= mysql_query($query2) or die('error in query Cash query '.mysql_error().$query2);
    */
     $ss1="select * from payment_plan  where project_id = '".$_REQUEST['id']."' and description = 'Opening Balance'";
            $sr1=mysql_query($ss1);
            $tot_rw1=mysql_num_rows($sr1);
            if($tot_rw1 == 0)
            {
                $query2="insert into payment_plan set project_id = '".$_REQUEST['id']."', credit = '".$opening_balance."', description = 'Opening Balance', payment_date = '".$opening_balance_date."', create_date = '".getTime()."'";
        $result2= mysql_query($query2) or die('error in query '.mysql_error().$query2);
                                                   
            } else
            {   
         $query2="update payment_plan set credit = '".$opening_balance."',payment_date = '".$opening_balance_date."' where project_id = '".$_REQUEST['id']."' and description = 'Opening Balance'";
    $result2= mysql_query($query2) or die('error in query Cash query '.mysql_error().$query2);
            }
   
	
	
	header("Location:project.php?msg=Project added successfully");
	
	}
}



$select_query = "select * from project where id = '".$_REQUEST['id']."'";
$select_result = mysql_query($select_query) or die('error in query select project query '.mysql_error().$select_query);
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
        Edit Project</h4>
  </td>
        <td width="" style="float:right;">
            <a href="project.php" title="Back" style=""><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
            </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
  <?php include_once("main_body_open.php") ?>
  	
  <table width="100%" style="padding:20px;">
    <tr><td>
  <table width="100%" class="tbl_border" >
  <tr><td>
		
	<?php if($error_msg != "") { ?>
	<div class="gagal">
		
		<?php echo $error_msg; ?>
		</div>
	<?php } ?>
	
	<form name="project_form" id="project_form" action="" method="post" >
		<table width="95%">
			<tr><td width="125px">Project Name</td>
			<td><input type="text" name="name" id="name" value="<?php echo $select_data['name']; ?>" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td >Opening Balance</td>
			<td><input type="text" name="opening_balance" id="opening_balance" value="<?php echo $select_data['opening_balance']; ?>" ></td></tr>
			<tr><td >Date</td>
            <td>
            <input type="text"  name="opening_balance_date" id="opening_balance_date" value="<?php echo date("d-m-Y",$select_data['opening_balance_date']); ?>" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('opening_balance_date')" style="cursor:pointer"/>
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
			<tr><td></td><td><br><br><br>
			<input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
            
			</td></tr>
            <tr><td colspan="2"><br><br><br><h3></h3></td></tr>
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
$(function() {
	 
	//$('#opening_balance_date').datepick({dateFormat: 'dd-mm-yyyy'});
	
});
function validation()
{
	if($("#name").val() == "")
	{
		alert("Please enter project name.");
		$("#name").focus();
		return false;
	}
	else
	{
		$("#action_perform").val("edit_project");
		$("#project_form").submit();
		return true;
	}
	
}

</script>