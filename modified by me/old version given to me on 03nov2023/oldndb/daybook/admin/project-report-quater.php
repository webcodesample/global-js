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
		//return $date_list2['credit']-$date_list2['debit'];
		return $date_list2['credit'].'~'.$date_list2['debit'];
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
	$quater = mysql_real_escape_string(trim($_REQUEST['quater']));
	$to_date = strtotime($quater.mysql_real_escape_string(trim($_REQUEST['to_date'])));

	/*echo date("d-m-Y",$from_date);
	echo '<br>';
	echo date("d-m-Y",$to_date);
	echo '<br>';*/
	
	if($project_id == "ALL")
	{
	
		$query = "select project_id,SUM(debit) as debit,SUM(credit) as credit from payment_plan where project_id != '' and payment_date <= '".$to_date."' GROUP BY project_id ORDER BY payment_date ASC";
	}
	else
	{
		$query = "select project_id,SUM(debit) as debit,SUM(credit) as credit from payment_plan where project_id = '".$project_id."' and payment_date <= '".$to_date."' GROUP BY project_id ORDER BY payment_date ASC";
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
<script src="js/datetimepicker_css.js"></script>
<STYLE TYPE="text/css" media="print">    

#pb {page-break-after:always}
        .pb {page-break-after:always}
    .hs {page-break-before:always;
}

    .hd{display:none} 
    
    thead.report-header {
    display:table-header-group;
}
</STYLE>
<body>
<?php 
include_once("header.php");
?>

<div id="wrapper">
	<?php
	include_once("leftbar.php");
	?>
	<div id="rightContent">
	<h3>Quaterly Project Report
    <span style="float: right;">
    
    <input type="button" name="print_button" id="print_button" value="" class="button_print" onClick="return print_data();"  />
    <script src="dist/jquery.table2excel.min.js"></script>
    <input type="button" id="export_to_excel" value="" class="button_export" >&nbsp;&nbsp;
    </span>
    </h3>
    <input type="hidden" id="print_header" name="print_header" value="Project - Reports - Quater">

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
					&nbsp;&nbsp;Date
					</td>
					<td width="70">
					<select name="to_date" id="to_date" size="1" style="width:140px; height:20px;"  >
                        
                       
                         <?php
				 	
					for($yr=1980;$yr<=date("Y");$yr++)
					{?>
					<option value="<?php echo $yr; ?>" <?php if($_REQUEST['to_date'] == $yr) echo 'selected="selected"'; ?> <?php if($yr == date("Y")) { echo 'selected="selected"'; } ?>  ><?php echo $yr; ?></option>	
						
				<?php	}?> 
				                          
               </select>
					
				 </td>
				 <td width="70">
					<select name="quater" id="quater" size="1" style="width:140px; height:20px;"  >
                    <option value="31-3-" <?php if($_REQUEST['quater'] == "31-3-") echo 'selected="selected"'; ?> >31 Mar</option>	
					<option value="30-6-" <?php if($_REQUEST['quater'] == "30-6-") echo 'selected="selected"'; ?> >30 Jun</option>	
					<option value="30-9-" <?php if($_REQUEST['quater'] == "30-9-") echo 'selected="selected"'; ?> >30 Sep</option>	
					<option value="31-12-" <?php if($_REQUEST['quater'] == "31-12-") echo 'selected="selected"'; ?> >31 DEC</option>	
						
				
				                          
               </select>
					
				 </td>
				 
					<td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='project-report-quater.php';"  /></td>
					
					
				</tr>
			</table>
			<input type="hidden" name="search_action" id="search_action" value="Search"  />
			<input type="hidden" name="page" id="page" value=""  />
			</form>
		<?php if(mysql_real_escape_string(trim($_REQUEST['search_action'])) != "") { ?>
			
		<table class="data">
			<tr class="data">
				<th class="data" width="30px">No</th>
				<th class="data">Project Name</th>
				
				<!--<th  class="data" >Debit</th>-->
				<th  class="data" >Investments</th>
				
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
						
						<!--<td  class="data" ><a href="javascript:void(0);" title="View Detail" onClick="return view_file_function('<?php echo $select_data['project_id']; ?>','<?php echo $to_date; ?>','debit');" ><?php echo currency_symbol().number_format($select_data['debit'],2); ?></a></td>-->
							<td  class="data" ><a href="javascript:void(0);" title="View Detail" onClick="return view_file_function('<?php echo $select_data['project_id']; ?>','<?php echo $to_date; ?>','credit');" ><?php echo currency_symbol().number_format($select_data['credit'],2); ?></a></td>
							
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
		
		<?php } ?>
		 <div id="view_div" style=" margin-left:10px;  width:600px; min-height:250px; display:none; background-color:#FFFFFF; border:1px solid #999999; " >
    
    </div>
	</div>
   
    
<div class="clear"></div>
<?php
include_once("footer.php");
?>
</div>

<!--<div id="view_div" style="position:absolute;top:50%; left:40%; width:600px; min-height:250px; margin:-100px 0 0 -50px;z-index:100; display:; background-color:#FFFFFF; border:8px solid #999999; overflow:scroll;" >

</div> -->
</body>
</html>
<script>
function close_view()
{
	$('#view_div').hide("slow");
}
/*$(function() {
	 
	$('#to_date').datepick({dateFormat: 'dd-mm-yyyy'});
	
});*/

function search_valid()
{
	/*if(document.getElementById("to_date").value=="")
	{
	 alert("Please enter Date ");
	 document.getElementById("to_date").focus();
	 return false;
	} */
	
	
}
function show_records(getno)
{
    //alert(getno);
    document.getElementById("page").value=getno;
    document.search_form.submit(); 
}
function view_file_function(project_id,to_date,str)
{
	
	$('#view_div').show("slow");
	$.ajax({
		url: "subdivision_ajax.php?project_id="+project_id+"&to_date="+to_date+"&str="+str,
		type: 'GET',
		dataType: 'html',
		beforeSend: function () {
			$('#view_div').html('Processing..................');
			
		},
		success: function (data, textStatus, xhr) {
			/*var arr = data.split("EXPLODE");
			$('#export_div').show();
			$('#cas_issued_div').html(arr[0]);		
			$('#student_query').val(arr[1]);
			$('#student_export').show();*/
			$('#view_div').html(data);		
			
			
		},
		error: function (xhr, textStatus, errorThrown) {
			$('#view_div').html(textStatus);
		}
	});
	
}
</script>
