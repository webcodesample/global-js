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
	$del_query = "delete from user where userid = '".$del_id."'";
	$del_result = mysql_query($del_query) or die("error in delete query ".mysql_error());
	$msg = "user Deleted Successfully.";
	
}
if(mysql_real_escape_string(trim($_REQUEST['search_text'])) != "")
{
	$column = $_REQUEST['search_type'];
	$query = "select * from user where $column LIKE '%".mysql_real_escape_string(trim($_REQUEST['search_text']))."%' ORDER BY full_name ASC";
	$result = mysql_query($query) or die('error in query select user query '.mysql_error().$query);
	$total_row = mysql_num_rows($result);
}
else
{
	$query = "select * from user ORDER BY full_name ASC";
	$result = mysql_query($query) or die('error in query select user query '.mysql_error().$query);
	$total_row = mysql_num_rows($result);
}

$page = $_REQUEST['page'];
if ($page < 1) $page = 1;
$numberOfPages = numberofpages();
$resultsPerPage = resultperpage();
$startResults = ($page - 1) * $resultsPerPage;
$totalPages = ceil($total_row / $resultsPerPage);


if(mysql_real_escape_string(trim($_REQUEST['search_text'])) != "")
{
	$column = $_REQUEST['search_type'];
	$select_query = "select * from user where $column LIKE '%".mysql_real_escape_string(trim($_REQUEST['search_text']))."%' ORDER BY full_name ASC LIMIT $startResults, $resultsPerPage";
	$select_result = mysql_query($select_query) or die('error in query select user query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
}
else
{
	$select_query = "select * from user ORDER BY full_name ASC LIMIT $startResults, $resultsPerPage";
	$select_result = mysql_query($select_query) or die('error in query select user query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
}

$halfPages = floor($numberOfPages / 2);
$range = array('start' => 1, 'end' => $totalPages);
$isEven = ($numberOfPages % 2 == 0);
$atRangeEnd = $totalPages - $halfPages;

if($isEven) $atRangeEnd++;

if($totalPages > $numberOfPages)
{
	if($page <= $halfPages)
		$range['end'] = $numberOfPages;
	elseif ($page >= $atRangeEnd)
		$range['start'] = $totalPages - $numberOfPages + 1;
	else
	{
		$range['start'] = $page - $halfPages;
		$range['end'] = $page + $halfPages;
		if($isEven) $range['end']--;
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
		User Account List</h4>
  </td>
        <td width="" style="float:right;">
          	<?php 
        if($_SESSION['admin_flag']=="yes")
        {   ?>
    <a href="add_user.php"><input type="button" name="add_button" id="add_button" value="" class="button_add" /></a>        
   <?php      }
        ?>
		<input type="button" id="search" value="" class="button_search1" onClick="search_display();" >

            </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>

<!-------------->
<?php include_once("main_search_open.php") ?>
		
  <input type="hidden" name="search_check_val" id="search_check_val" value="0" >
  <form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" >
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="padding:10px;">
			<tr>

					<td width="180" align="left" valign="top"><input type="text" name="search_text" id="search_text" value="<?php echo mysql_real_escape_string(trim($_REQUEST['search_text'])); ?>" /></td>
					<td width="150" align="left" valign="top">
						<select name="search_type" id="search_type" >
						<option value="" >-- Please Select --</option>
						<option value="fname" <?php if($_REQUEST['search_type'] == "fname") echo 'selected="selected"'; ?>  >First Name</option>
						<option value="lname" <?php if($_REQUEST['search_type'] == "lname") echo 'selected="selected"'; ?>  >Last Name</option>
						<option value="mobile" <?php if($_REQUEST['search_type'] == "mobile") echo 'selected="selected"'; ?>  >Mobile</option>
						
						
						</select>
				  </td>
					<td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;
                    <input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='user.php';"  />&nbsp;&nbsp;
                     
                    </td>
					
					
				</tr>
			</table>
			<input type="hidden" name="page" id="page" value=""  />
			</form>
			
  <?php include_once("main_search_close.php") ?>
 <!-------------->

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
  
	 		
		
	<table width="98%" style="padding:10px;">
	<tr><td>
		<table class="data" style="">
			<tr class="data">
				<th class="data" width="30px">S.No.</th>
				<th class="data">First Name</th>
				<th class="data">Last Name</th>
				<th class="data">Username</th>
				<th class="data">Mobile</th>
				<th class="data">E-Mail</th>
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
						<td class="data"><?php echo $select_data['fname']; ?></td>
						<td class="data"><?php echo $select_data['lname']; ?></td>
						<td class="data"><?php echo $select_data['username']; ?></td>
						<td class="data"><?php echo $select_data['mobile']; ?></td>
						<td class="data"><?php echo $select_data['email']; ?></td>
						<td class="data" width="75px">
						<center>
                        <?php 
                         if($_SESSION['admin_flag']=="yes")
                         {    ?>
                        <a href="javascript:account_delete(<?php echo $select_data['userid'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>&nbsp;&nbsp;&nbsp;
                        <a href="edit_user.php?userid=<?php echo $select_data['userid']; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                        
                         <?php     
                         }else 
                         {
                               if($select_data['userid']==$_SESSION['userId'])
                               {
                                   ?>
                                      <a href="edit_user.php?userid=<?php echo $select_data['userid']; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                               <?php
                               }
                         }
                        ?>
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
					<td  width="30px" colspan="7" class="record_not_found" >Record Not Found</td>
				</tr>
				<?php
			}
			?>
			
		</table>
		</td></tr></table>
		<div class="pagination" >
		
		<?php
			
						if($page > 1)
						{
							$page = $page-1;
							echo '<a href="javascript:void(0)" onclick="return show_records('.$page.')" ><span ><< prev</span></a>&nbsp';
							$page = $page+1;
						}
							
						?>
						
						<?php
						if($range['end'] != 1)
						{
							for ($i = $range['start']; $i <= $range['end']; $i++)
							{
								if($i == $page)
									echo '<strong><span class="current">'.$i.'</span></strong>&nbsp;';
								else
									echo '<a href="javascript:void(0)" onclick="return show_records('.$i.')"><span >'.$i.'</span></a>&nbsp;';
							}
						}
						?>
						
						<?php
						if ($page < $totalPages)
						{
							$page = $page+1;
							echo '<a href="javascript:void(0)" onclick="return show_records('.$page.')" >next >></a>&nbsp;';
							$page = $page-1;
						}
						
					 ?>
       
        </div>
	
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
function search_valid()
{
	if(document.getElementById("search_type").value=="" && document.getElementById("search_text").value=="")
	{
		alert("Please Select Search Type and Search Text");
		document.getElementById("search_text").focus();
	 	return false;
	}
	else if(document.getElementById("search_type").value=="")
	{
	 alert("Please Select Search Type");
	 document.getElementById("search_type").focus();
	 return false;
	}
	else if(document.getElementById("search_text").value=="")
	{
	 alert("Please enter search text to search");
	 document.getElementById("search_text").focus();
	 return false;
	} 
	
}
function show_records(getno)
{
    //alert(getno);
    document.getElementById("page").value=getno;
    document.search_form.submit(); 
}          
</script>
