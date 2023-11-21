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



if(trim($_REQUEST['action_perform']) == "add_project")
{
    /*echo '<pre>';
    print_r($_REQUEST);
    exit;*/
    $name=mysql_real_escape_string(trim($_REQUEST['name']));
    $opening_balance=mysql_real_escape_string(trim($_REQUEST['opening_balance']));
    $opening_balance = str_replace(",","",$opening_balance);
    $opening_balance_date=strtotime(mysql_real_escape_string(trim($_REQUEST['opening_balance_date'])));
    
    $quuerrr="select id from project where name='".$name."' ";
    
    $sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
    $no=mysql_num_rows($sql);
    if($no > 0)
    {
        
            $error_msg = "Project name already exist try another";
        
    }
    else
    {
        $query="insert into project set name = '".$name."', opening_balance = '".$opening_balance."', opening_balance_date = '".$opening_balance_date."', create_date = '".getTime()."', added_by = '".$_SESSION['userId']."', added_on = '".time()."'";
        $result= mysql_query($query) or die('error in query bank query '.mysql_error().$query);
        
        $project_id = mysql_insert_id();
        
        $query2="insert into payment_plan set project_id = '".$project_id."', credit = '".$opening_balance."', description = 'Opening Balance', payment_date = '".$opening_balance_date."', create_date = '".getTime()."'";
        $result2= mysql_query($query2) or die('error in query '.mysql_error().$query2);
        
        $msg = "Project added successfully.";
        header("Location:project.php?msg=Project added successfully");
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
        New Project Create </h4>
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
            <td><input type="text" id="name"  name="name" value="<?php echo $_REQUEST['name']; ?>"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <tr><td >Opening Balance</td>
            <td><input type="text" name="opening_balance" id="opening_balance" value="<?php echo $_REQUEST['opening_balance']; ?>" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <tr><td>Date</td>
            <td>
            <input type="date"  name="opening_balance_date" id="opening_balance_date" max="<?= date('Y-m-d',time()) ?>">
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            
            <tr><td></td><td>
            <input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
            </td></tr>
            <tr><td colspan="2"><br><h3></h3><br><br><br><br></td></tr>
        </table>
        <input type="hidden" name="action_perform" id="action_perform" value="" >
        <input type="hidden" name="del_id" id="del_id" value="" >
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
    if($("#name").val() == "")
    {
        alert("Please enter project name.");
        $("#name").focus();
        return false;
    }
    else if($("#opening_balance").val() == "")
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
    else
    {
        $("#action_perform").val("add_project");
        $("#project_form").submit();
        return true;
    }
    
}
</script>