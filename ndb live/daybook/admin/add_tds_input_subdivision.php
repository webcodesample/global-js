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
    $tds_subdivision=mysql_real_escape_string(trim($_REQUEST['tds_subdivision']));
    $tds_type=mysql_real_escape_string(trim($_REQUEST['tds_type']));
    
    $quuerrr="select id from tds_subdivision where name = '".$tds_subdivision."'";
    
    $sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
    $no=mysql_num_rows($sql);
    if($no > 0)
    {
        
        
        $error_msg = "TDS subdivision name already exist try another";
        
    }
    else
    {
        $query="insert into tds_subdivision set name = '".$tds_subdivision."' ,type='".$tds_type."'";
        $result= mysql_query($query) or die('error in query '.mysql_error().$query);
        $msg = "TDS subdivision added successfully.";
         header("Location:input_tds.php?msg=Input TDS Subdivision Added successfully");
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
        Create New TDS Subdivision  </h4>
  </td>
        <td width="" style="float:right;">
        <a href="input_tds.php" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
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
	<form name="user_form" id="user_form" action="" method="post" >
    
	<table width="98%" style="padding:20px;" >
			<tr >
				<td >
				    
    <table width="95%">
        <tr><td width="200px"> TDS Sub Division Name</td>
            <td><input type="text" id="tds_subdivision" style="width: 250px"  name="tds_subdivision" value="<?php echo $_REQUEST['tds_subdivision']; ?>"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <input type="hidden" name="tds_type" id="tds_type" value="output_tds">
            
            
         
            
            <tr><td></td><td>
    </br>
            <input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
            </td></tr>
        </table>
        </td></tr>
        </table>
        <input type="hidden" name="action_perform" id="action_perform" value="" >
        <input type="hidden" name="del_id" id="del_id" value="" >
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
{
    if($("#tds_subdivision").val() == "")
    {
        alert("Please enter Output TDS Subdivision Name .");
        $("#tds_subdivision").focus();
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