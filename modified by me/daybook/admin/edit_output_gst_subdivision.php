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


if(trim($_REQUEST['action_perform']) == "edit_project")
{
	
         $gst_subdivision=mysql_real_escape_string(trim($_REQUEST['gst_subdivision']));
    $gst_type=mysql_real_escape_string(trim($_REQUEST['gst_type']));
   
    $quuerrr="select id from gst_subdivision where name = '".$gst_subdivision."'  and id != '".$_REQUEST['id']."'";
    
    $sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
    $no=mysql_num_rows($sql);
    if($no > 0)
    {
        
        
        $error_msg = "gst_subdivision name already exist try another";
        
    }  
    else 
    {
        $query="update gst_subdivision set name = '".$gst_subdivision."' ,type='".$gst_type."' where id = '".$_REQUEST['id']."'";
        $result= mysql_query($query) or die('error in query '.mysql_error().$query);
       // $msg = "GST subdivision added successfully.";
        header("Location:output_gst_subdivision.php?msg=Output GST Subdivision Update successfully");
    }

    
   
}



$select_query = "select * from gst_subdivision where id = '".$_REQUEST['id']."'";
$select_result = mysql_query($select_query) or die('error in query select gst_subdivision query '.mysql_error().$select_query);
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
        Edit Output GST Subdivision </h4>
  </td>
        <td width="" style="float:right;">
		<a href="output_gst_subdivision.php" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
         </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>

<?php include_once("main_body_open.php") ?>
	
<table width="100%" style="padding:30px;" >
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
			<tr><td width="225px">Output GST Sub Division Name</td>
			<td><input type="text" name="gst_subdivision" id="gst_subdivision" value="<?php echo $select_data['name']; ?>" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<input type="hidden" name="gst_type" id="gst_type" value="output_gst">
			<tr><td></td><td><br><br><br>
			<input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
            
			</td></tr>
            <tr><td colspan="2"><br><br><br></br></br><h3></h3></td></tr>
		</table>
		<input type="hidden" name="action_perform" id="action_perform" value="" >
	<input type="hidden" name="id" id="id" value="<?php echo $_REQUEST['id']; ?>">
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