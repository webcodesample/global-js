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


if(trim($_REQUEST['action_perform']) == "edit_category")
{
	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
	$category=mysql_real_escape_string(trim($_REQUEST['category']));
	
	$quuerrr="select id from document_category where (category = '".$category."') and (id != '".$_REQUEST['id']."') ";
	$sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
	$no=mysql_num_rows($sql);
	if($no > 0)
	{
		
			$error_msg = "Category already exist try another";
		
	}
	else
	{
		$query="update document_category set category = '".$category."' where id = '".$_REQUEST['id']."'";
		$result= mysql_query($query) or die('error in query '.mysql_error().$query);
		header("Location:category.php?msg=category Updated successfully");
	}
	
	
}



$select_query = "select * from document_category where id = '".$_REQUEST['id']."'";
$select_result = mysql_query($select_query) or die('error in query select category query '.mysql_error().$select_query);
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
        Edit category </h4>
  </td>
        <td width="" style="float:right;">
            <a href="category.php" title="Back" style=""><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
            </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
  <?php include_once("main_body_open.php") ?>
	
	
	<form name="category_form" id="category_form" action="" method="post" >
		<table width="95%" style="padding:30px;">
		<tr><td colspan="2">
        <?php if($error_msg != "") { ?>
	<div class="gagal">
		
		<?php echo $error_msg; ?>
		</div>
	<?php } ?>

        </td></tr>




			<tr><td width="125px">category</td>
			<td><input type="text" name="category" id="category" value="<?php echo $select_data['category']; ?>" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			
			
			<tr><td></td><td>
			<input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
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
{
	if($("#category").val() == "")
	{
		alert("Please enter category.");
		$("#category").focus();
		return false;
	}
	else
	{
		$("#action_perform").val("edit_category");
		$("#category_form").submit();
		return true;
	}
	
}

</script>