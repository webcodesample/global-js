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
        
        
        $error_msg = "Output TDS subdivision name already exist try another";
        
    }
    else 
    {
        $query="insert into tds_subdivision set name = '".$tds_subdivision."' ,type='".$tds_type."'";
        $result= mysql_query($query) or die('error in query '.mysql_error().$query);
        $msg = "Output TDS subdivision added successfully.";
        header("Location:output_tds.php?msg=Output TDS subdivision Added successfully");
    }
    

}

       
    

?>
<html>
<head>
<title>Admin Panel</title>
<script src="js/datetimepicker_css.js"></script>
</head>

<body>
<?php 
include_once("header.php");
?>

<div id="wrapper">
	<?php
	include_once("leftbar.php");
	?>
	<div id="rightContent">
	<h3>Create New Output TDS Subdivision</h3>
	
	<?php if($error_msg != "") { ?>
	<div class="gagal">
		
		<?php echo $error_msg; ?>
		</div>
	<?php } ?>
	<table width="100%" cellpadding="0" cellspacing="0" border="0" >
			<tr >
				<td align="right">
					<a href="output_tds.php" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
				</td>
			</tr>
		</table>
	<form name="user_form" id="user_form" action="" method="post" >
        <table width="95%">
            <tr>
            <tr><td width="200px">Output TDS Sub Division Name</td>
            <td><input type="text" id="tds_subdivision" style="width: 250px"  name="tds_subdivision" value="<?php echo $_REQUEST['tds_subdivision']; ?>"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <input type="hidden" name="tds_type" id="tds_type" value="output_tds">
            
            
         
            
            <tr><td></td><td>
            <input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
            </td></tr>
        </table>
        <input type="hidden" name="action_perform" id="action_perform" value="" >
        <input type="hidden" name="del_id" id="del_id" value="" >
        <input type="hidden" name="count" id="count" value="<?php echo $i; ?>"  />    
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
    if($("#tds_subdivision").val() == "")
    {
        alert("Please enter Output TDS subdivision.");
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