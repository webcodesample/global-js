<?php include("includes/main_header.php");
include_once("fckeditor/fckeditor.php");
	
$msg = "";
if($_REQUEST['update_action'] != "")
{
	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
	$arr_size = count($_REQUEST['id_arr']);
	for($m=0;$m<$arr_size;$m++)
	{
		if($_REQUEST['shift_1_'.$_REQUEST['id_arr'][$m]] != "" && $_REQUEST['shift_1_'.$_REQUEST['id_arr'][$m]] != "O" && $_REQUEST['shift_2_'.$_REQUEST['id_arr'][$m]] == "")
		{
			$update_query = "update attendence set shift_1 = 'P', shift_2 = 'A' where id = '".$_REQUEST['id_arr'][$m]."' ";
		}
		else if($_REQUEST['shift_1_'.$_REQUEST['id_arr'][$m]] == "" && $_REQUEST['shift_2_'.$_REQUEST['id_arr'][$m]] != "" && $_REQUEST['shift_2_'.$_REQUEST['id_arr'][$m]] != "O")
		{
			$update_query = "update attendence set shift_1 = 'A', shift_2 = 'P' where id = '".$_REQUEST['id_arr'][$m]."' ";
		}
		else if($_REQUEST['shift_1_'.$_REQUEST['id_arr'][$m]] != "" && $_REQUEST['shift_1_'.$_REQUEST['id_arr'][$m]] != "O" && $_REQUEST['shift_2_'.$_REQUEST['id_arr'][$m]] != "" && $_REQUEST['shift_2_'.$_REQUEST['id_arr'][$m]] != "O")
		{
			$update_query = "update attendence set shift_1 = 'P', shift_2 = 'P' where id = '".$_REQUEST['id_arr'][$m]."' ";
		}
		else if($_REQUEST['shift_1_'.$_REQUEST['id_arr'][$m]] != "" && $_REQUEST['shift_1_'.$_REQUEST['id_arr'][$m]] == "O" && $_REQUEST['shift_2_'.$_REQUEST['id_arr'][$m]] != "" && $_REQUEST['shift_2_'.$_REQUEST['id_arr'][$m]] == "O")
		{
			$update_query = "update attendence set shift_1 = 'O', shift_2 = 'O' where id = '".$_REQUEST['id_arr'][$m]."' ";
		}
		else if($_REQUEST['shift_1_'.$_REQUEST['id_arr'][$m]] != "" && $_REQUEST['shift_1_'.$_REQUEST['id_arr'][$m]] != "O" && $_REQUEST['shift_2_'.$_REQUEST['id_arr'][$m]] != "" && $_REQUEST['shift_2_'.$_REQUEST['id_arr'][$m]] == "O")
		{
			$update_query = "update attendence set shift_1 = 'P', shift_2 = 'O' where id = '".$_REQUEST['id_arr'][$m]."' ";
		}
		else if($_REQUEST['shift_1_'.$_REQUEST['id_arr'][$m]] != "" && $_REQUEST['shift_1_'.$_REQUEST['id_arr'][$m]] == "O" && $_REQUEST['shift_2_'.$_REQUEST['id_arr'][$m]] != "" && $_REQUEST['shift_2_'.$_REQUEST['id_arr'][$m]] != "O")
		{
			$update_query = "update attendence set shift_1 = 'O', shift_2 = 'P' where id = '".$_REQUEST['id_arr'][$m]."' ";
		}
		else if($_REQUEST['shift_1_'.$_REQUEST['id_arr'][$m]] != "" && $_REQUEST['shift_1_'.$_REQUEST['id_arr'][$m]] == "O" && $_REQUEST['shift_2_'.$_REQUEST['id_arr'][$m]] == "" )
		{
			$update_query = "update attendence set shift_1 = 'O', shift_2 = 'A' where id = '".$_REQUEST['id_arr'][$m]."' ";
		}
		else if($_REQUEST['shift_1_'.$_REQUEST['id_arr'][$m]] == "" && $_REQUEST['shift_2_'.$_REQUEST['id_arr'][$m]] != "" && $_REQUEST['shift_2_'.$_REQUEST['id_arr'][$m]] == "O" )
		{
			$update_query = "update attendence set shift_1 = 'A', shift_2 = 'O' where id = '".$_REQUEST['id_arr'][$m]."' ";
		}
		else
		{
			$update_query = "update attendence set shift_1 = 'A', shift_2 = 'A' where id = '".$_REQUEST['id_arr'][$m]."' ";
		}
		$update_result = mysql_query($update_query) or die("error in update query ".mysql_error());
	}
	$msg = "Updated Successfully.";
	 
}

if(mysql_real_escape_string(trim($_REQUEST['search_action'])) != "")
{
	$course = mysql_real_escape_string(trim($_REQUEST['course']));
	$intake = mysql_real_escape_string(trim($_REQUEST['intake']));
	$from_month = mysql_real_escape_string(trim($_REQUEST['from_month']));
	$from_year = mysql_real_escape_string(trim($_REQUEST['from_year']));
	$to_month = mysql_real_escape_string(trim($_REQUEST['to_month']));
	$to_year = mysql_real_escape_string(trim($_REQUEST['to_year']));
	
	$from_date = mktime(0, 0, 0, $from_month, 1, $from_year);
	$to_date = mktime(0, 0, 0, $to_month, 1, $to_year);
	//$days_in_from_date = date("t", $from_date);
	$days_in_to_date = date("t", $to_date);
	
	$to_date = mktime(0, 0, 0, $to_month, $days_in_to_date, $to_year);
	
	if($course == "ALL" && $intake == "ALL" )
	{
		$total_total_query = "select * from attendence where atten_date >= '".$from_date."' and atten_date <= '".$to_date."' GROUP BY college_id ORDER BY student_name ASC";
	}
	else if($course != "ALL" && $intake == "ALL")
	{
		$total_total_query = "select * from attendence where course_title = '".$course."' and atten_date >= '".$from_date."' and atten_date <= '".$to_date."' GROUP BY college_id ORDER BY student_name ASC";
	}
	else
	{
		$total_total_query = "select * from attendence where course_title = '".$course."' and intake = '".$intake."' and atten_date >= '".$from_date."' and atten_date <= '".$to_date."' GROUP BY college_id ORDER BY student_name ASC";
	}
	
	$total_total_result = mysql_query($total_total_query) or die('error in query'.mysql_error());
	$total_total_row = mysql_num_rows($total_total_result);
	
}

if(mysql_real_escape_string(trim($_REQUEST['search_action'])) != "")
{
	$course = mysql_real_escape_string(trim($_REQUEST['course']));
	$intake = mysql_real_escape_string(trim($_REQUEST['intake']));
	$from_month = mysql_real_escape_string(trim($_REQUEST['from_month']));
	$from_year = mysql_real_escape_string(trim($_REQUEST['from_year']));
	$to_month = mysql_real_escape_string(trim($_REQUEST['to_month']));
	$to_year = mysql_real_escape_string(trim($_REQUEST['to_year']));
	
	$from_date = mktime(0, 0, 0, $from_month, 1, $from_year);
	$to_date = mktime(0, 0, 0, $to_month, 1, $to_year);
	//$days_in_from_date = date("t", $from_date);
	$days_in_to_date = date("t", $to_date);
	
	$to_date = mktime(0, 0, 0, $to_month, $days_in_to_date, $to_year);
	if($course == "ALL" && $intake == "ALL")
	{
		$query = "select * from attendence where atten_date >= '".$from_date."' and atten_date <= '".$to_date."' GROUP BY college_id ORDER BY student_name ASC";
	}
	else if($course != "ALL" && $intake == "ALL")
	{
		$query = "select * from attendence where course_title = '".$course."' and atten_date >= '".$from_date."' and atten_date <= '".$to_date."' GROUP BY college_id ORDER BY student_name ASC";
	}
	else
	{
		$query = "select * from attendence where course_title = '".$course."' and intake = '".$intake."' and atten_date >= '".$from_date."' and atten_date <= '".$to_date."' GROUP BY college_id ORDER BY student_name ASC";
	}
	$result = mysql_query($query) or die('error in query'.mysql_error());
	$total_row = mysql_num_rows($result);
}

$page = $_REQUEST['page'];
if ($page < 1) $page = 1;
$numberOfPages = 8;
$resultsPerPage = 10;
$startResults = ($page - 1) * $resultsPerPage;
$totalPages = ceil($total_row / $resultsPerPage);


if(mysql_real_escape_string(trim($_REQUEST['search_action'])) != "")
{
	$course = mysql_real_escape_string(trim($_REQUEST['course']));
	$intake = mysql_real_escape_string(trim($_REQUEST['intake']));
	$from_month = mysql_real_escape_string(trim($_REQUEST['from_month']));
	$from_year = mysql_real_escape_string(trim($_REQUEST['from_year']));
	$to_month = mysql_real_escape_string(trim($_REQUEST['to_month']));
	$to_year = mysql_real_escape_string(trim($_REQUEST['to_year']));
	
	$from_date = mktime(0, 0, 0, $from_month, 1, $from_year);
	$to_date = mktime(0, 0, 0, $to_month, 1, $to_year);
	//$days_in_from_date = date("t", $from_date);
	$days_in_to_date = date("t", $to_date);
	
	$to_date = mktime(0, 0, 0, $to_month, $days_in_to_date, $to_year);
	
	if($course == "ALL" && $intake == "ALL")
	{
		$query = "select * from attendence where atten_date >= '".$from_date."' and atten_date <= '".$to_date."' GROUP BY college_id ORDER BY student_name ASC LIMIT $startResults, $resultsPerPage";
	}
	else if($course != "ALL" && $intake == "ALL")
	{
		$query = "select * from attendence where course_title = '".$course."' and atten_date >= '".$from_date."' and atten_date <= '".$to_date."' GROUP BY college_id ORDER BY student_name ASC LIMIT $startResults, $resultsPerPage";
	}
	else
	{
		$query = "select * from attendence where course_title = '".$course."' and intake = '".$intake."' and atten_date >= '".$from_date."' and atten_date <= '".$to_date."' GROUP BY college_id ORDER BY student_name ASC LIMIT $startResults, $resultsPerPage";
	}
	$result = mysql_query($query) or die('error in query'.mysql_error());
	$result2 = mysql_query($query) or die('error in query'.mysql_error());
	$total_row = mysql_num_rows($result);
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
 
  <script src="../calendar/datetimepicker_css.js"></script>
 
           <div align="center" class="first_div"  >
			<div class="second_div" >&nbsp;&nbsp;&nbsp;Student Attendence Detail&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(mysql_real_escape_string(trim($_REQUEST['search_action'])) != "" && $total_total_row > 0) { ?><b style="font-size:12px; font-weight:bold;" >Showing</b>&nbsp;&nbsp;&nbsp;<b style="font-size:12px; font-weight:bold; color:#FF0000;" ><?php echo $startResults; ?>&nbsp;<b style="color:#000000;">To</b>&nbsp;<?php echo $startResults+$total_row; ?>&nbsp;&nbsp;<b style="color:#000000;">Of</b>&nbsp;&nbsp;<?php echo $total_total_row; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="stock_csv();" title="Export To CSV" ><img src="images/excel.gif" border="0" /></a><?php } ?>
			<div id="update_div" style="float:right; display:none;"><input type="button" name="submit_button" id="submit_button" value="Update" onclick="return update_attendence();" class="button" />&nbsp;&nbsp;</div>
			</div>			
			<br />
<form name="csv_form" id="csv_form" action="student_attendence_csv.php" method="post" >
	<input type="hidden" name="query" id="query" value="<?php echo $total_total_query; ?>" />
	<input type="hidden" name="course_csv" id="course_csv" value="<?php echo $_REQUEST['course']; ?>" />
	<input type="hidden" name="intake_csv" id="intake_csv" value="<?php echo $_REQUEST['intake']; ?>" />
	<input type="hidden" name="from_month_csv" id="from_month_csv" value="<?php echo $_REQUEST['from_month']; ?>" />
	<input type="hidden" name="from_year_csv" id="from_year_csv" value="<?php echo $_REQUEST['from_year']; ?>" />
	<input type="hidden" name="to_month_csv" id="to_month_csv" value="<?php echo $_REQUEST['to_month']; ?>" />
	<input type="hidden" name="to_year_csv" id="to_year_csv" value="<?php echo $_REQUEST['to_year']; ?>" />

</form>
			<form name="search_form" id="search_form" action="" method="post" >
			<table width="900" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					
					<td width="80" align="left" valign="middle">Course Title </td>
					<td width="150">
					
					<select name="course" id="course" size="1" style="width:140px; height:20px;" onchange="cat_function(this.value,'subcategory');"  >
                        
                        <option value="ALL">ALL</option> 
                         <?php
				 	$sql_course=mysql_query("select * from course_title where status = 1 ORDER BY name ASC ");
					while($res_course=mysql_fetch_array($sql_course))
					{?>
					<option value="<?php echo $res_course['id']; ?>" <?php if($_REQUEST['course'] == $res_course['id']) echo 'selected="selected"'; ?> ><?php echo $res_course['name']; ?></option>	
						
				<?php	}?> 
				                          
               </select>
				 </td>
				  <td align="left" valign="middle">
					<div id="for_intake_name" style="display:block" >Intake&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></td>
					<td align="left" valign="middle">
					<div id="subcategory_list" style="display:block" >
					  <select name="intake" id="intake" size="1" style="width:110px;" >
					  <option value="ALL">ALL</option> 
                        
                            <?php if($_REQUEST['course']!="" && $_REQUEST['course']!="ALL")
							{
							 $sql_intake=mysql_query("select * from intake where course_title = '".$_REQUEST['course']."' ORDER BY year");
							}
							else
							{
				 	$sql_intake=mysql_query("select * from intake ORDER BY year");
					}
					while($res_intake=mysql_fetch_array($sql_intake))
					{?>
					<option value="<?php echo $res_intake['id']; ?>" <?php if($_REQUEST['intake'] == $res_intake['id']) echo 'selected="selected"'; ?>><?php echo $res_intake['month']." ".$res_intake['year']; ?></option>	
						
				<?php	}?>   
                       
              </select>
			  </div>
					</td>
					<td width="50">
					&nbsp;&nbsp;From
					</td>
					<td width="70">
					
					<select name="from_month" id="from_month" size="1" style="width:50px; height:20px;"  >
					<option value="1" <?php if($_REQUEST['from_month'] == 1) echo 'selected="selected"'; ?> >Jan</option>
					<option value="2" <?php if($_REQUEST['from_month'] == 2) echo 'selected="selected"'; ?> >Feb</option> 
					<option value="3" <?php if($_REQUEST['from_month'] == 3) echo 'selected="selected"'; ?> >Mar</option> 
					<option value="4" <?php if($_REQUEST['from_month'] == 4) echo 'selected="selected"'; ?> >Apr</option> 
					<option value="5" <?php if($_REQUEST['from_month'] == 5) echo 'selected="selected"'; ?> >May</option> 
					<option value="6" <?php if($_REQUEST['from_month'] == 6) echo 'selected="selected"'; ?> >Jun</option> 
					<option value="7" <?php if($_REQUEST['from_month'] == 7) echo 'selected="selected"'; ?> >Jul</option> 
					<option value="8" <?php if($_REQUEST['from_month'] == 8) echo 'selected="selected"'; ?> >Aug</option> 
					<option value="9" <?php if($_REQUEST['from_month'] == 9) echo 'selected="selected"'; ?> >Sep</option> 
					<option value="10" <?php if($_REQUEST['from_month'] == 10) echo 'selected="selected"'; ?> >Oct</option> 
					<option value="11" <?php if($_REQUEST['from_month'] == 11) echo 'selected="selected"'; ?> >Nov</option> 
					<option value="12" <?php if($_REQUEST['from_month'] == 12) echo 'selected="selected"'; ?> >Dec</option>  
                    </select>
				 </td>
				 <td width="70">
					
					<select name="from_year" id="from_year" size="1" style="width:60px; height:20px;"  >
					<?php for($j=2000;$j<=date("Y");$j++) { ?>
					<option value="<?php echo $j; ?>" <?php if($_REQUEST['from_year'] != "") { if($_REQUEST['from_year'] == $j) echo 'selected="selected"'; } else { if(date("Y") == $j) echo 'selected="selected"'; } ?> ><?php echo $j; ?></option>
					<?php } ?>
                    </select>
				 </td>
				 <td width="50">
					&nbsp;&nbsp;To
					</td>
					<td width="70">
					
					<select name="to_month" id="to_month" size="1" style="width:50px; height:20px;"  >
					<option value="1" <?php if($_REQUEST['to_month'] == 1) echo 'selected="selected"'; ?> >Jan</option>
					<option value="2" <?php if($_REQUEST['to_month'] == 2) echo 'selected="selected"'; ?> >Feb</option> 
					<option value="3" <?php if($_REQUEST['to_month'] == 3) echo 'selected="selected"'; ?> >Mar</option> 
					<option value="4" <?php if($_REQUEST['to_month'] == 4) echo 'selected="selected"'; ?> >Apr</option> 
					<option value="5" <?php if($_REQUEST['to_month'] == 5) echo 'selected="selected"'; ?> >May</option> 
					<option value="6" <?php if($_REQUEST['to_month'] == 6) echo 'selected="selected"'; ?> >Jun</option> 
					<option value="7" <?php if($_REQUEST['to_month'] == 7) echo 'selected="selected"'; ?> >Jul</option> 
					<option value="8" <?php if($_REQUEST['to_month'] == 8) echo 'selected="selected"'; ?> >Aug</option> 
					<option value="9" <?php if($_REQUEST['to_month'] == 9) echo 'selected="selected"'; ?> >Sep</option> 
					<option value="10" <?php if($_REQUEST['to_month'] == 10) echo 'selected="selected"'; ?> >Oct</option> 
					<option value="11" <?php if($_REQUEST['to_month'] == 11) echo 'selected="selected"'; ?> >Nov</option> 
					<option value="12" <?php if($_REQUEST['to_month'] == 12) echo 'selected="selected"'; ?> >Dec</option>  
                    </select>
				 </td>
				 <td width="50">
					
					<select name="to_year" id="to_year" size="1" style="width:60px; height:20px;"  >
					<?php for($k=2000;$k<=date("Y");$k++) { ?>
					<option value="<?php echo $k; ?>" <?php if($_REQUEST['to_year'] != "") { if($_REQUEST['to_year'] == $k) echo 'selected="selected"'; } else { if(date("Y") == $k) echo 'selected="selected"'; } ?> ><?php echo $k; ?></option>
					<?php } ?>
                    </select>
				 </td>
				
				 
				 
				  <td align="right" valign="top" width="50" colspan="2"><input type="button" name="search_button" id="search_button" value="Search" class="button" onClick="return search_validation();" /></td>
					
				</tr>
				
				
			</table>
			<br />
			<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
			<tr>
				<td style="color:#FF0000; font-weight:bold;">&nbsp;&nbsp;<?php if($msg != "") echo $msg; ?>
				<?php if(mysql_real_escape_string(trim($_REQUEST['search_action'])) != "" && $total_total_row > 0) { ?>
				<br />
				&nbsp;&nbsp;&nbsp;Check All &nbsp;
				<input type="checkbox" name="check_all" id="check_all" value="" onclick="return check_all_records();" />
				<?php } ?>
				</td>
				<td>
			<table width="300" border="0" align="right" cellpadding="0" cellspacing="0">
			<tr>
				<td width="100" align="center">
					<?php
						if($page > 1)
						{
							$page = $page-1;
							echo '<a href="javascript:void(0)" onclick="return show_records('.$page.')" ><div class="button" style="width:50px;">Previous</div></a>&nbsp';
							$page = $page+1;
							}
							
						?>
						</td><td valign="top" width="100" align="center">
						<?php
						if($range['end'] != 1)
						{
							for ($i = $range['start']; $i <= $range['end']; $i++)
							{
								if($i == $page)
									echo '<strong>'.$i.'</strong>&nbsp;';
								else
									echo '<a href="javascript:void(0)" onclick="return show_records('.$i.')">'.$i.'</a>&nbsp;';
							}
						}
						?>
						</td><td width="100" align="center">
						<?php
						if ($page < $totalPages)
						{
							$page = $page+1;
							echo '<a href="javascript:void(0)" onclick="return show_records('.$page.')" ><div class="button" style="width:40px;">&nbsp;&nbsp;Next</div></a>&nbsp;';
							$page = $page-1;
						}
						
					 ?>
				</td>
			</tr>
			</table>
			</td>
			</tr></table>
			<input type="hidden" name="page" id="page" value="<?php echo $_REQUEST['page']; ?>"  />
			<input type="hidden" name="search_action" id="search_action" value=""  />
			<input type="hidden" name="email_action" id="email_action" value=""  />
			
			
						
			<br />
			<?php if(mysql_real_escape_string(trim($_REQUEST['search_action'])) != "") { ?>
			<div style="width:915px; overflow:scroll; height:350px;"> 
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" >
			<?php if($total_row > 0)
			{ ?>
			<tr style="height:30px; background-color:#E6E6F2; " >
				<th align="center" valign="middle" width="70px" class="border_right_heading">Student ID</th>
				<th align="center" valign="middle" width="200px" class="border_right_heading">Student Name</th>
				<?php
				 $for_date_list = mysql_fetch_array($result);
				 $date_list_query = "select * from attendence where college_id = '".$for_date_list['college_id']."' and atten_date >= '".$from_date."' and atten_date <= '".$to_date."' ORDER BY atten_date ASC";
				$date_list_result = mysql_query($date_list_query) or die("error in date list query ".mysql_error());
				$total_day = mysql_num_rows($date_list_result);
				while($date_list = mysql_fetch_array($date_list_result))
				{
				 ?>
				<th align="center" valign="middle" width="70px" colspan="2" class="border_right_heading" ><?php echo date("d-m-Y",$date_list['atten_date']); ?></th>
				<?php } ?>
				<th align="center" valign="middle" class="border_right_heading">Attendance<br />Average</th>
			</tr>
			
			<tr style="height:30px; background-color:#E6E6F2; " >
				<th align="center" valign="middle" width="70px" class="border_right_heading">&nbsp;</th>
				<th align="center" valign="middle" width="200px" class="border_right_heading">&nbsp;</th>
				<?php
				 /*$for_max = 0;
				 while($for_date_list = mysql_fetch_array($result))
				 {
					$for_max_query = "select * from attendence where college_id = '".$for_date_list['college_id']."' and atten_date >= '".$from_date."' and atten_date <= '".$to_date."' ORDER BY atten_date ASC";
					$for_max_result = mysql_query($for_max_query) or die("error in date list query ".mysql_error());
					$for_max_total = mysql_num_rows($for_max_result);
					if($for_max_total > $for_max)
					{
						$for_max_result_list = mysql_fetch_array($for_max_result);
						$college_id_for_list = $for_date_list['college_id'];
						$for_max = $for_max_total;
					}
				
				} */
				$date_list_query22 = "select * from attendence where college_id = '".$for_date_list['college_id']."' and atten_date >= '".$from_date."' and atten_date <= '".$to_date."' ORDER BY atten_date ASC";
				$k=1;
				$date_list_result22 = mysql_query($date_list_query22) or die("error in date list query ".mysql_error());
				while($date_list22 = mysql_fetch_array($date_list_result22))
				{
				 ?>
				<th align="center" valign="middle" width="35px" class="border_top_heading" >
				M<br />
				<input type="checkbox" name="shift_1_<?php echo $k; ?>" id="shift_1_<?php echo $k; ?>" value="" onclick="return check_single_list(this.id);"  />
				</th>
				<?php $k++; ?>
				<th align="center" valign="middle" width="35px" class="border_top_heading" >
				A<br />
				<input type="checkbox" name="shift_2_<?php echo $k; ?>" id="shift_2_<?php echo $k; ?>" value="" onclick="return check_single_list(this.id);" />
				</th>
				<?php
				$k++;
				 } ?>
				<th align="center" valign="middle" class="border_right_heading">&nbsp;</th>
			</tr>
			<?php
			
			
				$i = 1;
				$cust_id_string = "";
				
				while($row = mysql_fetch_array($result2))
				{
					$total_attendance = 0;
					
						?>
							<tr style="font-size:8px; background-color:<?php if($i%2 == 0) { ?>#EEEEEE<?php } else { ?>#F8F8F8 <?php } ?>;">
							<td class="border_right" ><?php echo $row['college_id']; ?></td>
							<td class="border_right" ><?php echo $row['student_name'];	 ?>
							
							</td>
							
							<?php 
							$attendence_list_query = "select * from attendence where college_id = '".$row['college_id']."' and atten_date >= '".$from_date."' and atten_date <= '".$to_date."' ORDER BY atten_date ASC";
							$attendence_list_result = mysql_query($attendence_list_query) or die("error in date list query ".mysql_error());
							$z=1;
							while($attendence_list = mysql_fetch_array($attendence_list_result))
							{
								if($attendence_list['shift_1'] == "P" && $attendence_list['shift_2'] == "P")
								{
									$total_attendance++;
								}
								else if($attendence_list['shift_1'] != "P" && $attendence_list['shift_2'] == "P")
								{
									$total_attendance = $total_attendance + 0.5;
								}
								else if($attendence_list['shift_1'] == "P" && $attendence_list['shift_2'] != "P")
								{
									$total_attendance = $total_attendance + 0.5;
								}
								
							 ?>
							<td class="border_right" width="35px" style="font-size:14px;" align="center" >
							<input type="checkbox" name="id_arr[]" id="id_arr_<?php echo $z; ?>" value="<?php echo $attendence_list['id']; ?>" checked="checked" style="display:none;"  />
							
							<?php 
							$leave_query = "select * from leave_reason where college_id = '".$attendence_list['college_id']."' and from_date <= '".$attendence_list['atten_date']."' and to_date >= '".$attendence_list['atten_date']."' ";
							$leave_result = mysql_query($leave_query) or die("error in leave query ".mysql_error());
							
							$leave_total = mysql_num_rows($leave_result);
							if($attendence_list['shift_1'] != "O" && $leave_total == 0)
							{
							?>
							
							<input type="checkbox" name="shift_1_<?php echo $attendence_list['id']; ?>" id="shift_1_<?php echo $z; ?>_<?php echo $i; ?>" value="<?php echo $attendence_list['id']; ?>" <?php if($attendence_list['shift_1'] == "P") { echo 'checked="checked"'; } ?> onclick="return for_update_show('shift_1_<?php echo $z; ?>');" />
							
							
							<?php
							}
							else
							{
								echo "O";
								?>
							
							<input type="checkbox" name="shift_1_<?php echo $attendence_list['id']; ?>" id="shift_1_<?php echo $z; ?>_<?php echo $i; ?>" value="O" <?php if($attendence_list['shift_1'] == "O" || $leave_total != 0) { echo 'checked="checked"'; } ?> onclick="return for_update_show('shift_1_<?php echo $z; ?>');" style="display:none;" />
							
							
							<?php
							}
							$z++;
							 ?>
							</td>
							<td class="border_right" width="35px" style="font-size:14px;" align="center" >
							<?php 
							if($attendence_list['shift_2'] != "O" && $leave_total == 0)
							{
							?>
							<input type="checkbox" name="shift_2_<?php echo $attendence_list['id']; ?>" id="shift_2_<?php echo $z; ?>_<?php echo $i; ?>" value="<?php echo $attendence_list['id']; ?>" <?php if($attendence_list['shift_2'] == "P") { echo 'checked="checked"'; } ?> onclick="return for_update_show('shift_2_<?php echo $z; ?>');" />
							</td>
							<?php
							}
							else
							{
								echo "O";
								?>
							<input type="checkbox" name="shift_2_<?php echo $attendence_list['id']; ?>" id="shift_2_<?php echo $z; ?>_<?php echo $i; ?>" value="O" <?php if($attendence_list['shift_2'] == "O" || $leave_total != 0) { echo 'checked="checked"'; } ?> onclick="return for_update_show('shift_2_<?php echo $z; ?>');" style="display:none;" />
							</td>
							<?php
							}
							$z++;
							 }
							 ?>
							 <td class="border_right" ><?php echo number_format(($total_attendance/$total_day)*100, 2, '.', ''); echo '%'; ?></td>
						</tr>
			<?php 
					$i++;
				}
				?>
				<input type="hidden" name="row_count" id="row_count" value="<?php echo $i; ?>"  />
				<input type="hidden" name="column_count" id="column_count" value="<?php echo $z; ?>"  />
				<input type="hidden" name="update_action" id="update_action" value=""  />
				
				<?php
			}
			else
			{  ?>
				
				<tr style="background-color:#F8F8F8;">
					<td height="21" colspan="20" align="left" valign="top" style="color:#FF0000;"><strong>Record Not Found</strong></td>
				</tr>
			
		<?php	}
			
			 ?>
			
			</table>
		</div>
			
			<?php } ?>
			</form>
			
			<br /><br />
			</div>
			
			
<?php include("includes/main_footer.php"); ?> 
<script type="text/javascript" src="js/jquery.js"></script>
<script>
function for_update_show(id)
{
	document.getElementById("update_div").style.display="block";
	var row_count = document.getElementById("row_count").value;
	var for_check = 1;
	for(var i=1;i<row_count;i++)
	{
		if(document.getElementById(id+"_"+i).checked == false)
		{
			for_check = 2;
		}
		
	}
	if(for_check == 2)
	{
		document.getElementById(id).checked = false;
	}
	else
	{
		document.getElementById(id).checked = true;
	}
	
}
function check_all_records()
{
	
	
	var row_count = document.getElementById("row_count").value;
	var column_count = document.getElementById("column_count").value;
	if(document.getElementById("check_all").checked == true)
	{
		for(var i=1;i<row_count;i++)
		{
			document.getElementById("update_div").style.display="block";
			for(var k=1;k<column_count;k++)
			{
				if(k%2 == 0)
				{
					document.getElementById("shift_2_"+k).checked = true;
					if(document.getElementById("shift_2_"+k+"_"+i).value != "O")
					document.getElementById("shift_2_"+k+"_"+i).checked = true;
				}
				else
				{
					document.getElementById("shift_1_"+k).checked = true;
					if(document.getElementById("shift_1_"+k+"_"+i).value != "O")
					document.getElementById("shift_1_"+k+"_"+i).checked = true;
				}
			}
		}
	}
	else
	{
		document.getElementById("update_div").style.display="none";
		for(var i=1;i<row_count;i++)
		{
			for(var k=1;k<column_count;k++)
			{
				if(k%2 == 0)
				{
					document.getElementById("shift_2_"+k).checked = false;
					if(document.getElementById("shift_2_"+k+"_"+i).value != "O")
					document.getElementById("shift_2_"+k+"_"+i).checked = false;
				}
				else
				{
					document.getElementById("shift_1_"+k).checked = false;
					if(document.getElementById("shift_1_"+k+"_"+i).value != "O")
					document.getElementById("shift_1_"+k+"_"+i).checked = false;
				}
			}
		}
	}
}
function check_single_list(checkbox_id)
{
	
	var row_count = document.getElementById("row_count").value;
	if(document.getElementById(checkbox_id).checked == true)
	{
		document.getElementById("update_div").style.display="block";
		for(var i=1;i<row_count;i++)
		{
			if(document.getElementById(checkbox_id+"_"+i).value != "O")
			document.getElementById(checkbox_id+"_"+i).checked = true;
		}
	}
	else
	{
		for(var i=1;i<row_count;i++)
		{
			if(document.getElementById(checkbox_id+"_"+i).value != "O")
			document.getElementById(checkbox_id+"_"+i).checked = false;
		}
	}
	
}
function show_update_button()
{
	
	document.getElementById("update_div").style.display="block";
}
function stock_csv()
{
	document.csv_form.submit();
}
function update_attendence()
{
	document.getElementById("search_action").value="Search";
	document.getElementById("update_action").value="update_records";
	document.search_form.submit();
}


function search_validation()
{
	if(document.getElementById("course").value == "")
	{
		alert("Please Select Course.");
		document.getElementById("course").focus();
		return false;
	}
	else
	{
		document.getElementById("search_action").value="Search";
		document.getElementById("page").value="";
		document.search_form.submit();
		
		/*var year = document.getElementById("year").value;
		var course = document.getElementById("course").value;
		var search_by = "year";
		
			$.ajax({
			url: "cash_report_ajax.php",
			type: 'GET',
			data: {"year":year,"course":course,"search_by":search_by},
			dataType: 'html',
			beforeSend: function () {
				$('#ajax_content').html('<img src="images/processing.gif" width="100px">');
			},
			success: function (data, textStatus, xhr) {
			var arr = data.split("EXPLODE");
			
			$('#export_div').show();		
	$('#query').val(arr[1]);		
	 $('#ajax_content').html(arr[0]);
	 
	 		
			},
			error: function (xhr, textStatus, errorThrown) {
				$('#ajax_content').html(textStatus);
			}
		}); */
		
		
	}
} 
function show_records(getno)
{
    //alert(getno);
	document.getElementById("search_action").value="Search";
    document.getElementById("page").value=getno;
    document.search_form.submit(); 
}

function cat_function(id,type)
{
	var xmlhttp;
		if (window.XMLHttpRequest)
		  {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		  }
		else
		  {// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		xmlhttp.onreadystatechange=function()
		  {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
			
			var values=xmlhttp.responseText;
			//var arr=values.split('~');
		
			var values=xmlhttp.responseText;
			//var arr=values.split('EXPLODE');
			//document.getElementById('query').value = arr[1];
			document.getElementById('for_intake_name').style.display = "block";
			document.getElementById('subcategory_list').innerHTML = values;
			
			
			
			}
		  }
		  var url="../ajax_intake.php";
		url=url+"?id="+id+"&type="+type;
		
		xmlhttp.open("GET",url,true);
		xmlhttp.send();

}
	</script>
