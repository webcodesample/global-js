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


function get_total($project_id,$end_date)
{
	$date_list_query2 = "select SUM(debit) as debit,SUM(credit) as credit  from payment_plan where project_id = '".$project_id."' and payment_date <= '".$end_date."' ";
	$date_list_result2 = mysql_query($date_list_query2) or die("error in date list query ".mysql_error());
	$total_day2 = mysql_num_rows($date_list_result2);
	if($total_day2 > 0)
	{
		$date_list2 = mysql_fetch_array($date_list_result2);
		return $date_list2['credit']-$date_list2['debit'];
	}
	else
	{
		return 0;
	}
	
}



if(mysql_real_escape_string(trim($_REQUEST['search_action'])) != "")
{
	$project_id = mysql_real_escape_string(trim($_REQUEST['project_id']));
	$intake = mysql_real_escape_string(trim($_REQUEST['intake']));
	$from_date = strtotime(mysql_real_escape_string(trim($_REQUEST['from_date'])));
	$to_date = strtotime(mysql_real_escape_string(trim($_REQUEST['to_date'])));

	/*echo date("d-m-Y",$from_date);
	echo '<br>';
	echo date("d-m-Y",$to_date);
	echo '<br>';*/
	
	if($project_id == "ALL")
	{
	
		$query = "select * from payment_plan where project_id != '' and payment_date >= '".$from_date."' and payment_date <= '".$to_date."' GROUP BY project_id ORDER BY payment_date ASC";
	}
	else
	{
		$query = "select * from payment_plan where project_id = '".$project_id."' and payment_date >= '".$from_date."' and payment_date <= '".$to_date."' GROUP BY project_id ORDER BY payment_date ASC";
	}
	
	$result = mysql_query($query) or die('error in query'.mysql_error());
	$result2 = mysql_query($query) or die('error in query'.mysql_error());
	$total_row = mysql_num_rows($result);
	
	
	
}



?>
<html>
<head>
<title>Admin Panel</title>

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
	<h3>Project Report</h3>
	<?php if($msg != "") { ?>
	<div class="sukses">
		<?php echo $msg; ?>
		</div>
	<?php } else if($error_msg != "") { ?>
	<div class="gagal">
		
		<?php echo $error_msg; ?>
		</div>
	<?php } ?>
	
	<br>
	
		
		<form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" >
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td width="140" align="left" valign="middle"><select name="project_id" id="project_id" size="1" style="width:140px; height:20px;"  >
                        
                        <option value="ALL">---- ALL ----</option> 
                         <?php
				 	$sql_bank=mysql_query("select * from project ORDER BY name ASC ");
					while($res_bank=mysql_fetch_array($sql_bank))
					{?>
					<option value="<?php echo $res_bank['id']; ?>" <?php if($_REQUEST['project_id'] == $res_bank['id']) echo 'selected="selected"'; ?> ><?php echo $res_bank['name']; ?></option>	
						
				<?php	}?> 
				                          
               </select></td>
					<td width="50">
					&nbsp;&nbsp;From
					</td>
					<td width="70">
					
					<input type="text" name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>"  readonly="" style="width:100px;" >
				 </td>
				
				 <td width="50">
					&nbsp;&nbsp;To
					</td>
					<td width="70">
					
					<input type="text" name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>"  readonly="" style="width:100px;" >
				 </td>
				 
					<td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='project-report.php';"  /></td>
					
					
				</tr>
			</table>
			<input type="hidden" name="search_action" id="search_action" value="Search"  />
			<input type="hidden" name="page" id="page" value=""  />
			</form>
		<?php if(mysql_real_escape_string(trim($_REQUEST['search_action'])) != "") { ?>
			<div style="width:725px; overflow:scroll; height:350px;"> 
		<table class="data">
			<tr class="data">
				<th class="data" width="30px">No</th>
				<th class="data">Project Name</th>
				<?php
				if($project_id == "ALL")
				{
				
					$date_list_query = "select * from payment_plan where project_id != '' and payment_date >= '".$from_date."' and payment_date <= '".$to_date."' GROUP BY payment_date ORDER BY payment_date ASC";
				}
				else
				{
					$date_list_query = "select * from payment_plan where project_id = '".$project_id."' and payment_date >= '".$from_date."' and payment_date <= '".$to_date."' GROUP BY payment_date ORDER BY payment_date ASC";
				}
				 
				$date_list_result = mysql_query($date_list_query) or die("error in date list query ".mysql_error());
				$total_day = mysql_num_rows($date_list_result);
				while($date_list = mysql_fetch_array($date_list_result))
				{
				 ?>
				<th  class="data" ><?php echo date("d-m-y",$date_list['payment_date']); ?></th>
				<?php } ?>
			</tr>
			<?php
			if($total_row > 0)
			{
				$i=1;
				while($select_data = mysql_fetch_array($result2))
				{
					
					 ?>
					<tr class="data">
						<td class="data" width="30px"><?php echo $i; ?></td>
						<td class="data"><?php echo get_field_value("name","project","id",$select_data['project_id']); ?></td>
						
					
					<?php
						
						if($project_id == "ALL")
						{
						
							$date_list_query2 = "select * from payment_plan where project_id != '' and payment_date >= '".$from_date."' and payment_date <= '".$to_date."' GROUP BY payment_date ORDER BY payment_date ASC";
							$date_list_result2 = mysql_query($date_list_query2) or die("error in date list query ".mysql_error());
							$total_day2 = mysql_num_rows($date_list_result2);
							while($date_list2 = mysql_fetch_array($date_list_result2))
							{
								
									
									$date_list_query3 = "select * from payment_plan where project_id = '".$select_data['project_id']."' and payment_date = '".$date_list2['payment_date']."' ";
									
									$date_list_result3 = mysql_query($date_list_query3) or die("error in date list query ".mysql_error());
									$total_day3 = mysql_num_rows($date_list_result3);
									if($total_day3 > 0)
									{
										$date_list3 = mysql_fetch_array($date_list_result3);
										
										 ?>
										<td  class="data" ><?php echo currency_symbol().number_format(get_total($date_list3['project_id'],$date_list3['payment_date']),2); ?></td>
										<?php
										
									 }
									 else
									 {
									 ?>
										<td  class="data" ></td>
										<?php
									 	
									 } ?>
									
									 
								<?php 
							}
						}
						else
						{
							$date_list_query2 = "select project_id,payment_date  from payment_plan where project_id = '".$select_data['project_id']."' and payment_date >= '".$from_date."' and payment_date <= '".$to_date."' GROUP BY payment_date ORDER BY payment_date ASC";
							
							$date_list_result2 = mysql_query($date_list_query2) or die("error in date list query ".mysql_error());
							$total_day2 = mysql_num_rows($date_list_result2);
							while($date_list2 = mysql_fetch_array($date_list_result2))
							{
							 ?>
							<td  class="data" ><?php echo currency_symbol().number_format(get_total($date_list2['project_id'],$date_list2['payment_date']),2); ?></td>
							<?php }
							
						}
						
						
						 ?>
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
		</div>
		<?php } ?>
		
	</div>
<div class="clear"></div>
<?php
include_once("footer.php");
?>
</div>
</body>
</html>
<script>

$(function() {
	 
	$('#from_date').datepick({dateFormat: 'dd-mm-yyyy'});
	
});
$(function() {
	 
	$('#to_date').datepick({dateFormat: 'dd-mm-yyyy'});
	
});

function search_valid()
{
	if(document.getElementById("from_date").value=="")
	{
	 alert("Please enter from date");
	 document.getElementById("from_date").focus();
	 return false;
	}
	else if(document.getElementById("to_date").value=="")
	{
	 alert("Please enter to ");
	 document.getElementById("to_date").focus();
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
