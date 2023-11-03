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


/*     Deletion  Account   */

if($_REQUEST['action_perform'] == "delete_user")
{
	$del_id = $_REQUEST['del_id'];
	$del_query = "delete from tds_list where id = '".$del_id."'";
	//echo $del_query;
    //exit;
    $del_result = mysql_query($del_query) or die("error in delete query ".mysql_error());
	$msg = "TDS Deleted Successfully.";
	
}

if($_REQUEST['action_perform'] == "active_user")
{
    $del_id = $_REQUEST['del_id'];
    $active_val = $_REQUEST['active_val'];
    $del_query = "update tds_list set active = '".$active_val."' where id = '".$del_id."'";
    //echo $del_query;
    //exit;
    $del_result = mysql_query($del_query) or die("error in delete query ".mysql_error());
    $msg = "TDS Active/dactive Successfully.";
    
}

if($_REQUEST['action_perform'] == "default_selected")
{
    $del_id = $_REQUEST['del_id'];
    $active_val = $_REQUEST['active_val'];
    $del_query = "update tds_list set default_select = '0' ";
    $del_result = mysql_query($del_query) or die("error in delete query ".mysql_error());
    
	$del_query = "update tds_list set default_select = '".$active_val."' where id = '".$del_id."'";
    //echo $del_query;
    //exit;
    $del_result = mysql_query($del_query) or die("error in delete query ".mysql_error());
    $msg = "TDS Active/dactive Successfully.";
    
}

	$select_query = "select * from tds_list ORDER BY tds ASC ";
	$select_result = mysql_query($select_query) or die('error in query select tds query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);




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
        TDS ( % ) List </h4>
  </td>
        <td width="" style="float:right;">
        <a href="add_tds.php"><input type="button" name="add_button" id="add_button" value="" class="button_add" /></a> </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>

<?php include_once("main_body_open.php") ?>
		<?php if($msg != "") { ?>
	<div class="sukses">
		<?php echo $msg; ?>
		</div>
	<?php } else if($error_msg != "") { ?>
	<div class="gagal">
		
		<?php echo $error_msg; ?>
		</div>
	<?php } ?>
		
	<div id="adddiv" style="display:<?php if($error_msg != "") { ?>block<?php } else { ?>none<?php } ?>;">
	
	<form name="user_form" id="user_form" action="" method="post" >
		
		<input type="hidden" name="action_perform" id="action_perform" value="" >
		<input type="hidden" name="del_id" id="del_id" value="" >
		<input type="hidden" name="count" id="count" value="<?php echo $i; ?>"  />	
        <input type="hidden" name="active_val" id="active_val" value="">
		</form>
		
		</div>
		
		
		<table class="data">
			<tr class="data">
				<th class="data" width="30px">S.No.</th>
				<th class="data">TDS Percentage ( % )</th>
				<th class="data" width="75px">default </br>selected</th>
				<th class="data" width="75px">Active</th>
                <th class="data" width="75px">Action</th>
			</tr>
			<?php
			if($select_total > 0)
			{
				$i=1;
				while($select_data = mysql_fetch_array($select_result))
				{
					
					 ?>
					<tr class="data">
						<td class="data" width="30px"><?php echo $i; ?></td>
						<td class="data"><?php echo $select_data['tds']; ?></td>
						<td class="data" width="75px">
                       <?php   if($select_data['default_select']=="1"){
                           ?><center><a href=""><img src="mos-css/img/active.png" title="default selected" ></a>&nbsp;&nbsp;&nbsp;</center>
                         <!-- <center><a href="javascript:account_active_select(<?php echo $select_data['id'] ?>);"><img src="mos-css/img/active.png" title="default selected" ></a>&nbsp;&nbsp;&nbsp;</center>
                            -->  
						   <?php 
                       }else if($select_data['default_select']=="0"){ ?>
                          <center><a href="javascript:account_dactive_select(<?php echo $select_data['id'] ?>);"><img src="mos-css/img/dactive.png" title="default unselected" ></a>&nbsp;&nbsp;&nbsp;</center> 
                    <?php    } ?>
                        </td>
						
                       <td class="data" width="75px">
                       <?php if($select_data['active']=="1"){
                           ?><center><a href="javascript:account_active(<?php echo $select_data['id'] ?>);"><img src="mos-css/img/active.png" title="Active" ></a>&nbsp;&nbsp;&nbsp;</center>
                           <?php 
                       }else if($select_data['active']=="0"){ ?>
                          <center><a href="javascript:account_dactive(<?php echo $select_data['id'] ?>);"><img src="mos-css/img/dactive.png" title="dactive" ></a>&nbsp;&nbsp;&nbsp;</center> 
                    <?php    } ?>
                        </td>
						<td class="data" width="75px">
						<center>
						<a href="javascript:account_delete(<?php echo $select_data['id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>&nbsp;&nbsp;&nbsp;
						
						</center>
						</td>
					</tr>
				<?php
					$i++;
				}
				
			}
			else
			{
				?>
				<tr class="data" >
					<td  width="30px" colspan="3" class="record_not_found" >Record Not Found</td>
				</tr>
				<?php
			}
			?>
			
		</table>
		<?php include_once("main_body_close.php") ?>
        <?php include_once ("footer.php"); ?>

</body>
</html>
<script>
function add_div()
{
	$("#adddiv").toggle("slow");
}

function account_delete(del_id)
{
	if(confirm("Are you sure want to delete?!!!!!......"))
	{
		$("#action_perform").val("delete_user");
		$("#del_id").val(del_id);
		$("#user_form").submit();
		return true;
	}
}
function account_active(del_id)
{
    if(confirm("Are you sure want to dactive?!!!!!......"))
    { 
        $("#action_perform").val("active_user");
        $("#active_val").val("0");
        $("#del_id").val(del_id);
        $("#user_form").submit();
        return true;
    }

}

function account_active_select(del_id)
{
    if(confirm("Are you sure want to dactive?!!!!!......"))
    { 
        $("#action_perform").val("default_selected");
        $("#active_val").val("0");
        $("#del_id").val(del_id);
        $("#user_form").submit();
        return true;
    }

}

function account_dactive_select(del_id)
{
    if(confirm("Are you sure want to default selected?!!!!!......"))
    {
        $("#action_perform").val("default_selected");
         $("#active_val").val("1");
        $("#del_id").val(del_id);
        $("#user_form").submit();
        return true;
    }
}

function account_dactive(del_id)
{
    if(confirm("Are you sure want to Active?!!!!!......"))
    {
        $("#action_perform").val("active_user");
         $("#active_val").val("1");
        $("#del_id").val(del_id);
        $("#user_form").submit();
        return true;
    }
}
function show_records(getno)
{
    //alert(getno);
    document.getElementById("page").value=getno;
    document.search_form.submit(); 
}
 
 </script>
