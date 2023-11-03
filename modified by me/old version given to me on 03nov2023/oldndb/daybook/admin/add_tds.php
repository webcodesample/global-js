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



if(trim($_REQUEST['action_perform']) == "add_user")
{
    /*echo '<pre>';
    print_r($_REQUEST);
    exit;*/
    $tds=mysql_real_escape_string(trim($_REQUEST['tds']));
    
    $quuerrr="select id from tds_list where tds = '".$tds."'";
    
    $sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
    $no=mysql_num_rows($sql);
    if($no > 0)
    {
        
        
        $error_msg = "TDS already exist try another";
        
    }
    else
    {
        $tds_per=mysql_real_escape_string(trim($_REQUEST['tds']));
	//$display_name=mysql_real_escape_string(trim($_REQUEST['display_name']));
	
	
		$query="insert into tds_list set tds = '".$tds_per."', create_date = '".getTime()."'";
		$result= mysql_query($query) or die('error in query '.mysql_error().$query);
	
        $msg = "TDS Percentage added successfully.";
         header("Location:tds-list.php?msg=TDS Percentage Added successfully");
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
        Create New TDS</h4>
  </td>
        <td width="" style="float:right;">
        <a href="tds-list.php" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a> </td>
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
	<form name="user_form" id="user_form" action="" method="post" >
        <table width="95%">
            <tr><td width="125px">TDS %</td>
            <td><input type="text" id="tds"  name="tds" value="<?php echo $_REQUEST['tds']; ?>"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            
         
            
            <tr><td></td><td></br></br>
            <input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
            </br></br></br></br></br></br></br></br></td></tr>
        </table>
        <input type="hidden" name="action_perform" id="action_perform" value="" >
        <input type="hidden" name="del_id" id="del_id" value="" >
        <input type="hidden" name="count" id="count" value="<?php echo $i; ?>"  />    
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
    if($("#tds").val() == "")
    {
        alert("Please enter TDS Percentage.");
        $("#tds").focus();
        return false;
    }
    else
    {
        $("#action_perform").val("add_user");
        $("#user_form").submit();
        return true;
    }
    
}
</script>