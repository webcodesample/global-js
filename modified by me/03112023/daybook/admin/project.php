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
	//$date_list_query2 = "select SUM(debit) as debit,SUM(credit) as credit  from payment_plan where project_id = '".$project_id."' and payment_date <= '".$end_date."' ";
    $date_list_query2 = "select SUM(debit) as debit,SUM(credit) as credit  from payment_plan where project_id = '".$project_id."'  ";
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


/*     Create  Account   */


/*     Deletion  Account   */

if($_REQUEST['action_perform'] == "delete_project")
{
	$del_id = $_REQUEST['del_id'];
	$del_query = "delete from project where id = '".$del_id."'";
	$del_result = mysql_query($del_query) or die("error in delete query ".mysql_error());
	$msg = "project Deleted Successfully.";
    
    $ss1="delete from payment_plan  where project_id = '".$_REQUEST['del_id']."' and description = 'Opening Balance'";
            $sr1=mysql_query($ss1);
	
}

if(mysql_real_escape_string(trim($_REQUEST['project_search'])) != "")
{
     $pro_search = $_REQUEST['project_search'];
     if($pro_search =="All"){
       $query = "select * from project ORDER BY name ASC ";
       $total_row=0;
   }else{
    $query = "select * from project where name like '%$pro_search%'   ORDER BY name ASC LIMIT $startResults, $resultsPerPage";
   // $result = mysql_query($query) or die('error in query select project query '.mysql_error().$query);
    //$total_row = mysql_num_rows($result);
   }
   
    
   
    
}
else if(mysql_real_escape_string(trim($_REQUEST['search_text'])) != "")
{
	$column = $_REQUEST['search_type'];
	$query = "select * from project where $column LIKE '%".mysql_real_escape_string(trim($_REQUEST['search_text']))."%' ORDER BY name ASC";
	$result = mysql_query($query) or die('error in query select project query '.mysql_error().$query);
	$total_row = mysql_num_rows($result);
}
else
{
	$query = "select * from project ORDER BY name ASC";
	$result = mysql_query($query) or die('error in query select project query '.mysql_error().$query);
	$total_row = mysql_num_rows($result);
}


$page = $_REQUEST['page'];
if ($page < 1) $page = 1;
$numberOfPages = numberofpages();
$resultsPerPage = resultperpage();
$startResults = ($page - 1) * $resultsPerPage;
$totalPages = ceil($total_row / $resultsPerPage);


if(mysql_real_escape_string(trim($_REQUEST['project_search'])) != "")
{
    $pro_search = $_REQUEST['project_search'];
     if($pro_search =="All"){
       $select_query = "select * from project ORDER BY name ASC ";
   }else{
    $select_query = "select * from project where name like '%$pro_search%'   ORDER BY name ASC LIMIT $startResults, $resultsPerPage";
   }
    $select_result = mysql_query($select_query) or die('error in query select project query '.mysql_error().$select_query);
    $select_total = mysql_num_rows($select_result);
}else
if(mysql_real_escape_string(trim($_REQUEST['search_text'])) != "")
{
	$column = $_REQUEST['search_type'];
	$select_query = "select * from project where $column LIKE '%".mysql_real_escape_string(trim($_REQUEST['search_text']))."%' ORDER BY name ASC LIMIT $startResults, $resultsPerPage";
	$select_result = mysql_query($select_query) or die('error in query select project query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
}
else
{
	$select_query = "select * from project ORDER BY name ASC LIMIT $startResults, $resultsPerPage";
	$select_result = mysql_query($select_query) or die('error in query select project query '.mysql_error().$select_query);
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
<body  data-home-page-title="" class="u-body u-xl-mode" data-lang="en">
  <?php include_once ("top_header2.php"); ?> 
  <?php include_once ("top_menu.php"); ?>
  <?php include_once("main_heading_open.php") ?>
  
	<table style="width:100%;" border="0" style="padding:0px 0px; margin-top:-10px;">
    <tr>
        <td style="align:top;" align="left">
        <h4 class="u-text-2 u-text-palette-1-base " style="padding:0px; margin:0px;">
        All Project List </h4>
  </td>
        <td width="" style="float:right;">
		<a href="add_project.php"><input type="button" name="add_button" id="add_button" value="" class="button_add" /></a>
    <input type="button" name="print_button" id="print_button" value="" class="button_print" onClick="return print_data();"  />
    <script src="dist/jquery.table2excel.min.js"></script>
    <input type="button" id="export_to_excel" value="" class="button_export" >&nbsp;&nbsp;
     
	<input type="hidden" id="print_header" name="print_header" value="Project - List</h3>">
	<input type="button" id="search" value="" class="button_search1" onClick="search_display();" >

</td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>

<!-------------->
<?php include_once("main_search_open.php") ?>
  <input type="hidden" name="search_check_val" id="search_check_val" value="0" >
  <table width="100%" cellpadding="0" cellspacing="0" border="0" >
	 <tr>
		 <td>
		
	
  <form name="search_form1" id="search_form1" action="" method="post" onSubmit="return search_valid1();" enctype="multipart/form-data">
         
		 <link rel="stylesheet" href="css/jquery-ui.css" />
		<!--  <script src="js/jquery-1.9.1.js"></script>-->
   <script src="js/jquery-ui.js"></script>
	 <table width="100%" cellpadding="0" cellspacing="0" border="0" >
	 <tr>
		 <td>
		 </td>
		 <!--<td width="250">
		 <span id="plus_total_div" style="color:#000000; font-size:18px; font-weight:bold;"></span>
		 <BR>
		 <span id="minus_total_div" style="color:#FF0000; font-size:18px; font-weight:bold;"></span>
		 <BR>
		 <span id="grand_total_div" style="color:#FF0000; font-size:18px; font-weight:bold;"></span></td>-->
	 </tr>
	 </table>
	 
	 <div id="adddiv" style="display:<?php if($error_msg != "") { ?>block<?php } else { ?>none<?php } ?>;">
	 </div>
		 
   <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
			 <tr>
					 <td width="480" align="left" valign="top">Search By Project Name
					 &nbsp;&nbsp;&nbsp;&nbsp;
					 <input type="text" id="project_search"  name="project_search" value="<?php echo $_REQUEST['project_search']; ?>" style="width:280px;"/></td>
					 
					 <td align="left" valign="top" ><input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;</td>
					 </tr>
 </table>
  </form>
  </td>
</tr>
<tr><td>
		 <form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" >
			 <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				 <tr>
					 <td width="250" align="left" valign="top">
						Search By : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="search_type" id="search_type" >
						 <option value="" >-- Please Select --</option>
						 <option value="name" <?php if($_REQUEST['search_type'] == "name") echo 'selected="selected"'; ?>  >Project Name</option>
						 
						 
						 
						 </select>
				   </td>
					 <td width="210" align="left" valign="top"><input type="text" name="search_text" id="search_text" value="<?php echo mysql_real_escape_string(trim($_REQUEST['search_text'])); ?>" /></td>
					 
					 <td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='project.php';"  />
 
					 </td>
					 <td align="right" valign="top">
					 
 
					 </td>
 
				 </tr>
			 </table>
			 <input type="hidden" name="page" id="page" value=""  />
			 </form>
			 </td>
</tr></table>
		 <form name="project_form" id="project_form" action="" method="post" >
		 
		 <input type="hidden" name="action_perform" id="action_perform" value="" >
		 <input type="hidden" name="del_id" id="del_id" value="" >
		 </form>
		  
  <?php include_once("main_search_close.php") ?>
 <!-------------->
  
  <?php include_once("main_body_open.php") ?>
  <table width="100%" style="padding:10px;">
  <tr><td>
  	<?php if($msg != "") { ?>
	<div class="sukses">
		<?php echo $msg; ?>
		</div>
	<?php } else if($error_msg != "") { ?>
	<div class="gagal">
		
		<?php echo $error_msg; ?>
		</div>
	<?php } ?>
		
		<div id="ledger_data">
        <table class="data" id="my_table" border="1" cellpadding="1" cellspacing="0" style="border: 1px solid #111111; width: 98%;">
		<tr style="display:none ;">
            <td><b> All Project List : </b></td>
            <td><b> Generated On :</b></td>
			<td><b><?php echo getTime(); echo "("; $username_1=get_field_value("full_name","user","userid",$_SESSION['userId']); echo $username_1; echo ")";?></b></td>
			<td colspan="4"></td>
        </tr>    

            <tr >
            <thead class="report-header">
				<th class="data" width="30px">S.No.</th>
				<th class="data">Project Name</th>
				<th class="data">Current Balance</th>
                <th class="data">No. Of Entries</th>
				<th class="data noExl" width="75px" id="header1">Action</th>
                </thead>
			</tr>
			<?php
			if($select_total > 0)
			{
				$i=1;
				$grand_total = 0;
				while($select_data = mysql_fetch_array($select_result))
				{
				    $ii=$i+$startResults;	
					 ?>
					<tr class="data">
						<td class="data" width="30px" align="center"><?php echo $ii; ?></td>
						<td class="data">&nbsp;&nbsp;<a href="project-ledger.php?project_id=<?php echo $select_data['id']; ?>" title="View Ledger"  ><?php echo $select_data['name']; ?></a></td>
						<td class="data" <?php if(get_total($select_data['id'],strtotime(date("d-m-Y")))<0) { ?> style="color:#FF0000;" <?php } ?> >&nbsp;<?php echo currency_symbol().number_format(get_total($select_data['id'],strtotime(date("d-m-Y"))),2); 
						$get_total = get_total($select_data['id'],strtotime(date("d-m-Y")));
						if($get_total>=0)
						{
							$plus_total = $plus_total+$get_total;
						}
						else
						{
							$minus_total = $minus_total+$get_total;
						}
						
						$grand_total = $grand_total+$get_total;
						?></td>
						<td class="data" width="100px" align="center">
                        <?php 
                         $select_tot = "select SUM(debit) as total_debit,SUM(credit) as total_credit , count(id) as no_entry from payment_plan where description != 'Opening Balance' and project_id='".$select_data['id']."' and project_id!='' and project_id > 0 ";
    $result_tot = mysql_query($select_tot) or die('error in query select subdivision query '.mysql_error().$select_tot);
    //$total_tot = mysql_num_rows($result_tot);
      $select_data3 = mysql_fetch_array($result_tot);
                        echo $select_data3['no_entry'];
                        
                        ?>
                        </td>
						<td class="data noExl" width="75px" align="left">
						&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="edit_project.php?id=<?php echo $select_data['id']; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                        <?php 
                            if($select_data3['no_entry']<1)
                            { ?>
                            <a href="javascript:account_delete(<?php echo $select_data['id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>&nbsp;&nbsp;&nbsp;    
                         <?php    }
                         ?>
						
						
						
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
        
        </div>
		<div class="pagination">
		
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
					</td></tr></table>
		<?php include_once("main_body_close.php") ?>
        <?php include_once ("footer.php"); ?>

</body>
</html>
<script>
$(document).ready(function(){
    $("#export_to_excel").click(function(){
        //document.getElementById("hid1").style.visibility = "hidden";    
         //$("td:hidden,th:hidden","#my_table").remove();

//newWin.document.write(getHeader());

         //$('table td').find('td:eq(4)').hide(); 
         //$("#thead").hide(); 
         //$("td:hidden,th:hidden","#my_table").remove();
        $("#my_table").table2excel({        
        
            exclude: ".noExl",
            name: "Developer data",
            filename: "All_project_List",
            fileext: ".xls",        
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true            
        });  
        // $("#thead").show();
    });
   // $('table td').find('td:eq(4)').show(); 
   $( "#project_search" ).autocomplete({
            source: "project1-ajax.php"
        });
   
});

  /*  $(document).ready(function(){
        $( "#project_search" ).autocomplete({
            source: "project1-ajax.php"
        });
        
       
        
    })*/
    </script>
<script src="js/datetimepicker_css.js"></script>
<script>
function add_div()
{
	$("#adddiv").toggle("slow");
}
function account_delete(del_id)
{
	if(confirm("Are you sure want to delete?!!!!!......"))
	{
		$("#action_perform").val("delete_project");
		$("#del_id").val(del_id);
		$("#project_form").submit();
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

function print_data()
{

     var print_header1 = $("#print_header").val();           
   var divToPrint1 = document.getElementById("ledger_data");
var divToPrint = divToPrint1;
divToPrint.border = 3;
divToPrint.cellSpacing = 0;
divToPrint.cellPadding = 2;
divToPrint.style.borderCollapse = 'collapse';
newWin = window.open();
//newWin.document.write(getHeader());
//newWin.document.write("<h3 align='center'>Master Designation List </h3>");
newWin.document.write("<h3 align='center'>"+print_header1+" </h3>");
//$('tr').children().eq(3).hide();

 $("#header1").hide();
$('table tr').find('td:eq(4)').hide();
newWin.document.write(divToPrint.outerHTML);
newWin.print();
//$('tr').children().eq(7).show();

$('table tr').find('td:eq(4)').show();
$("#header1").show();
newWin.close();
   
   /* printMe=window.open();
    printMe.document.write(document.getElementById("").innerHTML);
    printMe.print();
    printMe.close();*/
}


<?php
if(isset($grand_total))
{
/*?>
document.getElementById("plus_total_div").innerHTML = 'Positive = <?php echo currency_symbol().number_format($plus_total,2); ?>';
document.getElementById("minus_total_div").innerHTML = 'Nagetive = <?php echo currency_symbol().number_format($minus_total,2); ?>';
document.getElementById("grand_total_div").innerHTML = 'Total = <?php echo currency_symbol().number_format($grand_total,2); ?>';
<?php*/
}
?>
</script>
  
