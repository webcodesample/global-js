<?php session_start();
include_once("set_con.php");
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


/*     Create  Account   


if(trim($_REQUEST['action_perform']) == "add_user")
{
	$subdivision=mysql_real_escape_string(trim($_REQUEST['subdivision']));
	
	$quuerrr="select id from subdivision where name = '".$subdivision."'";
	
	$sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
	$no=mysql_num_rows($sql);
	if($no > 0)
	{	
		$error_msg = "subdivision name already exist try another";
	}
	else
	{
		$query="insert into subdivision set name = '".$subdivision."'";
		$result= mysql_query($query) or die('error in query '.mysql_error().$query);
		$msg = "subdivision added successfully.";
	}
	
}*/

/*     Deletion  Account   

if($_REQUEST['action_perform'] == "delete_user")
{
	$del_id = $_REQUEST['del_id'];
	$del_query = "delete from subdivision where id = '".$del_id."'";
	$del_result = mysql_query($del_query) or die("error in delete query ".mysql_error());
	$msg = "subdivision Deleted Successfully.";
	
}
if(mysql_real_escape_string(trim($_REQUEST['search_text'])) != "")
{
	$query = "select * from subdivision where name LIKE '%".mysql_real_escape_string(trim($_REQUEST['search_text']))."%' ORDER BY name ASC";
	$result = mysql_query($query) or die('error in query select subdivision query '.mysql_error().$query);
	$total_row = mysql_num_rows($result);
}
else
{
    $query = "select *,payment_plan.id as payment_id, SUM(payment_plan.debit) as total_debit,SUM(payment_plan.credit) as total_credit ,count(payment_plan.id) as no_entry from payment_plan inner join project on payment_plan.project_id = project.id and payment_plan.subdivision='".$_REQUEST['subdivision']."' and payment_plan.project_id!='' and payment_plan.project_id > 0 group by payment_plan.project_id ORDER BY project.name ASC";
    
	//$query = "select * from payment_plan where subdivision='".$_REQUEST['subdivision']."' and project_id!='' and project_id > 0 ORDER BY name ASC";
	$result = mysql_query($query) or die('error in query select subdivision query '.mysql_error().$query);
	$total_row = mysql_num_rows($result);
}
*/
$page = $_REQUEST['page'];
if ($page < 1) $page = 1;
$numberOfPages = numberofpages();
$resultsPerPage = resultperpage();
$startResults = ($page - 1) * $resultsPerPage;
$totalPages = ceil($total_row / $resultsPerPage);


if(mysql_real_escape_string(trim($_REQUEST['search_text'])) != "")
{
	$select_query = "select * from project where name LIKE '%".mysql_real_escape_string(trim($_REQUEST['search_text']))."%' ORDER BY name ASC LIMIT $startResults, $resultsPerPage";
	$select_result = mysql_query($select_query) or die('error in query select project query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
}
else
{
	echo "test";
     $from_date = strtotime(mysql_real_escape_string(trim($_REQUEST['from_date'])));
    
    $to_date = strtotime(mysql_real_escape_string(trim($_REQUEST['to_date'])));
    
    if($from_date!=""){
        $from_datedata ="and payment_plan.payment_date >= '".$from_date."'";
    }else { $from_datedata=""; }
    
    if($to_date!=""){
        $to_datedata ="and payment_plan.payment_date <= '".$to_date."'";
    }else { $to_datedata=""; } 
                       
	//$select_query = "select *,payment_plan.id as payment_id , SUM(payment_plan.debit) as total_debit,SUM(payment_plan.credit) as total_credit ,count(payment_plan.id) as no_entry from payment_plan inner join project on payment_plan.project_id = project.id and payment_plan.subdivision='".$_REQUEST['subdivision']."' and payment_plan.project_id!='' and payment_plan.project_id > 0 ".$from_datedata." ".$to_datedata." group by payment_plan.project_id ORDER BY project.name ASC LIMIT $startResults, $resultsPerPage";
	$select_query = "select *,payment_plan.id as payment_id , SUM(payment_plan.debit) as total_debit,SUM(payment_plan.credit) as total_credit ,count(payment_plan.id) as no_entry from payment_plan inner join subdivision on payment_plan.subdivision = subdivision.id and payment_plan.project_id='".$_REQUEST['project']."' and payment_plan.subdivision!='' and payment_plan.subdivision > 0 ".$from_datedata." ".$to_datedata." group by payment_plan.subdivision ORDER BY subdivision.name";
	$select_result = mysql_query($select_query) or die('error in query select subdivision query '.mysql_error().$select_query);
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
        Subdivision List (Project : <?php echo get_field_value("name","project","id",$_REQUEST['project']); ?> )</h4>
  </td>
        <td width="" style="float:right;">
             
		<a href="project.php" title="Back" ><input type="button" name="back_button" id="back_button" value="Back" class="button_back"  /></a>
		</td>
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
		<table width="95%">
			<tr><td width="125px">Sub Division Name</td>
			<td>
			<input type="text" id="subdivision"  name="subdivision" value="<?php echo $_REQUEST['subdivision']; ?>"/>
			&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span>
			</td>
			</tr>
			
			<tr><td></td><td>
			<input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
			</td></tr>
		</table>
		<input type="hidden" name="action_perform" id="action_perform" value="" >
		<input type="hidden" name="del_id" id="del_id" value="" >
		<input type="hidden" name="count" id="count" value="<?php echo $i; ?>"  />	
		</form>
		
		</div>
		
        <?php if($_REQUEST['from_date']!=""){
                            $search_action=1;
                        }
                        else if($_REQUEST['to_date']!=""){
                            $search_action=1;
                        }else {
                            $search_action="";
                        }
                        ?>
        <div style="float: right;">
		<br><br></div>
		<div  style="height: 450px;  overflow-y: scroll;overflow-x: scroll;padding:0px;">
		<table class="data">
			<tr class="data">
				<th class="data" width="30px">S.No.</th>
				<th class="data">Subdivision Name</th>
                <th class="data">Debit</th>
                <th class="data">Credit</th>
                <th class="data">No. Of Entry</th>
			</tr>
			<?php
			if($select_total > 0)
			{
				$i=1;
				$total_cr = 0;
				$total_dr = 0;
				while($select_data = mysql_fetch_array($select_result))
				{					
					 ?>
					<tr class="data">
						<td class="data" width="30px"><?php echo $i; ?></td>
                        
						<td class="data"><a href="project-ledger.php?project_id=<?php echo $_REQUEST['project']; ?>&subdivision=<?php echo $select_data['subdivision']; ?>" title="View Ledger"  ><?php echo $select_data['name']; ?></a></td>
                        <td class="data">
                         <b><?php echo currency_symbol().number_format($select_data['total_debit'],2,'.',''); ?></b>
                        </td>
                        <td class="data">
                         <b><?php echo currency_symbol().number_format($select_data['total_credit'],2,'.',''); ?></b>
                        </td>
                        <td class="data" width="75px" align="center"><?php echo $select_data['no_entry']; ?></td>
					</tr>
				<?php
				$total_cr += $select_data['total_credit'];
				$total_dr += $select_data['total_debit'];
					$i++;
				}
				
			}
			else
			{
				?>
				<tr class="data" >
					<td  width="30px" colspan="5" class="record_not_found" >Record Not Found</td>
				</tr>
				<?php
			}
			?>

			<tr class="data">
				<td class="data" width="30px"></td>
				<td class="data" align="right" style="color:black; font-weight:bold;">Total</td>
                <td class="data" style="color:red; font-weight:bold;"><?php echo currency_symbol().number_format($total_dr,2,'.',''); ?></td>
                <td class="data" style="color:green; font-weight:bold;"><?php echo currency_symbol().number_format($total_cr,2,'.',''); ?></td>
                <td class="data"></td>
			</tr>
			
		</table></div>
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
	if($("#subdivision").val() == "")
	{
		alert("Please enter subdivision name.");
		$("#subdivision").focus();
		return false;
	}
	else
	{
		$("#action_perform").val("add_user");
		$("#user_form").submit();
		return true;
	}
	
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
	if(document.getElementById("search_text").value=="")
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
