<?php session_start();
include_once("set_con.php");
function get_total_record($category)
{
	$quuerrr="select * from document where category='".$category."' GROUP BY category ";
	$sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
	$no=mysql_num_rows($sql);
	return $no;
	
}
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


if(trim($_REQUEST['action_perform']) == "add_category")
{
	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
	$category=mysql_real_escape_string(trim($_REQUEST['category']));
	
	$quuerrr="select id from document_category where category='".$category."' ";
	
	$sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
	$no=mysql_num_rows($sql);
	if($no > 0)
	{
		
		$error_msg = "$category already exist try another";
		
	}
	else
	{
		$query="insert into document_category set category = '".$category."' , added_by = '".$_SESSION['userId']."', added_on = '".time()."'";
		$result= mysql_query($query) or die('error in query '.mysql_error().$query);
		$msg = "Document Category added successfully.";
	}
	
}

/*     Deletion  Account   */

if($_REQUEST['action_perform'] == "delete_category")
{
	$del_id = $_REQUEST['del_id'];
	$del_query = "delete from document_category where id = '".$del_id."'";
	$del_result = mysql_query($del_query) or die("error in delete query ".mysql_error());
	$msg = "document category deleted successfully.";
	
}
if(mysql_real_escape_string(trim($_REQUEST['search_text'])) != "")
{
	$column = $_REQUEST['search_type'];
	$query = "select * from document_category where $column LIKE '%".mysql_real_escape_string(trim($_REQUEST['search_text']))."%' ORDER BY category ASC";
	$result = mysql_query($query) or die('error in query select Category query '.mysql_error().$query);
	$total_row = mysql_num_rows($result);
}
else
{
	$query = "select * from document_category ORDER BY category ASC";
	$result = mysql_query($query) or die('error in query '.mysql_error().$query);
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
	//$column = $_REQUEST['search_type'];
	$column = "category";
	$select_query = "select * from document_category where $column LIKE '%".mysql_real_escape_string(trim($_REQUEST['search_text']))."%' ORDER BY category ASC LIMIT $startResults, $resultsPerPage";
	$select_result = mysql_query($select_query) or die('error in query select Category query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
}
else
{
	$select_query = "select * from document_category ORDER BY category ASC LIMIT $startResults, $resultsPerPage";
	$select_result = mysql_query($select_query) or die('error in query select Category query '.mysql_error().$select_query);
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
		Category List </h4>
  </td>
        <td width="" style="float:right;">
        <input type="button" name="add_button" id="add_button" value="" class="button_add" onClick="search_display();"  />
		<input type="button" id="search" value="" class="button_search1" onClick="search_display();" >
         </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
<!-------------->
<?php include_once("main_search_open.php") ?>
<table>
	<tr><td>
	
	<!--<div id="adddiv" style="display:<?php if($error_msg != "") { ?>block<?php } else { ?>none<?php } ?>;">-->
	<div id="adddiv" >
		
	<form name="category_form" id="category_form" action="" method="post" >
		<table width="95%">
			<tr><td width="200px">New Document Category Name</td>
			<td width="280px"><input type="text" id="category"  name="category" value="<?php echo $_REQUEST['category']; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td>
			<td valign="top">&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" align="left"  class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
			</td></tr>
		</table>
		<input type="hidden" name="action_perform" id="action_perform" value="" >
		<input type="hidden" name="del_id" id="del_id" value="" >
		<input type="hidden" name="count" id="count" value="<?php echo $i; ?>"  />	
		</form>
		
		</div>
		
	
	</td></tr>
	<tr>
		<td>
  <input type="hidden" name="search_check_val" id="search_check_val" value="0" >
  <form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" >
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td width="200" align="left" valign="top">Search Category Name</td>
					<td width="280px"><input type="text" name="search_text" id="search_text" value="<?php echo mysql_real_escape_string(trim($_REQUEST['search_text'])); ?>" style="width:250px;" /></td>
						<!--<select name="search_type" id="search_type" >
						<option value="" >-- Please Select --</option>
						<option value="category" <?php if($_REQUEST['search_type'] == "category") echo 'selected="selected"'; ?>  >Category Name</option>
						</select>-->
					<td align="left" valign="top" >&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='category.php';"  />
					</td>
					
					
				</tr>
			</table>
			</form>
</td></tr></table>
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
			<input type="hidden" name="page" id="page" value=""  />
		
		<table class="data">
			<tr class="data">
				<th class="data" width="30px">S.No.</th>
				<th class="data">Category</th>
				<th class="data" nowrap>Added By</th>
				<th class="data" nowrap>Added On</th>
				<th class="data" nowrap>Updated By</th>
				<th class="data" nowrap>Updated On</th>
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
						<td class="data"><?php echo $select_data['category']; ?></td>
						<td class="data" nowrap>&nbsp;
						<?php
						echo get_field_value("full_name","user","userid",$select_data['added_by']);							 
						?>&nbsp;
						</td>
						<td class="data" nowrap>&nbsp;
						<?php
						if($select_data['added_on'])
						echo date('d-m-Y h:i:s A', $select_data['added_on']);	
						?>&nbsp;
						</td>
						<td class="data" nowrap>&nbsp;
						<?php
						echo get_field_value("full_name","user","userid",$select_data['updated_by']); 
						?>&nbsp;
						</td>
						<td class="data" nowrap>&nbsp;
						<?php
						if($select_data['updated_on'])
						echo date('d-m-Y h:i:s A', $select_data['updated_on']);							 
						?>&nbsp;
						</td>
						<td class="data" width="75px">
						<a href="edit_category.php?id=<?php echo $select_data['id']; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
						<?php if(get_total_record($select_data['id']) == 0)
						{
						?>
						<a href="javascript:account_delete(<?php echo $select_data['id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>&nbsp;&nbsp;&nbsp;
						<?php } ?>
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
					<td  width="30px" colspan="6" class="record_not_found" >Record Not Found</td>
				</tr>
				<?php
			}
			?>
			
		</table>
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

function validation()
{
	if($("#category").val() == "")
	{
		alert("Please enter Category Name.");
		$("#category").focus();
		return false;
	}
	else
	{
		$("#action_perform").val("add_category");
		$("#category_form").submit();
		return true;
	}
	
}
function account_delete(del_id)
{
	if(confirm("Are you sure want to delete?!!!!!......"))
	{
		$("#action_perform").val("delete_category");
		$("#del_id").val(del_id);
		$("#category_form").submit();
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
