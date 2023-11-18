<?php session_start();
include_once("set_con.php");

//print_r($_REQUEST);

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
	
}

/*     Deletion  Account   */

if($_REQUEST['action_perform'] == "delete_user")
{
	$del_id = $_REQUEST['del_id'];
	$del_query = "delete from subdivision where id = '".$del_id."'";
	$del_result = mysql_query($del_query) or die("error in delete query ".mysql_error());
	$msg = "subdivision Deleted Successfully.";
	
}
if(mysql_real_escape_string(trim($_REQUEST['search_action'])) != "")
{
    //echo "test";
    $from_date = strtotime(mysql_real_escape_string(trim($_REQUEST['from_date'])));
    
    $to_date = strtotime(mysql_real_escape_string(trim($_REQUEST['to_date'])));
    
    if($from_date!=""){
        $from_datedata ="and payment_plan.payment_date >= '".$from_date."'";
    }else { $from_datedata=""; }
    
    if($to_date!="")
    {
        $to_datedata ="and payment_plan.payment_date <= '".$to_date."'";
    }else { $to_datedata=""; } 
     $search_val = mysql_real_escape_string(trim($_REQUEST['subdivision2']));

    if($search_val!="All")
    {
        $subdivisiondata =" and subdivision.name LIKE '%".mysql_real_escape_string(trim($_REQUEST['subdivision2']))."%'";
    }
    else { $subdivisiondata=""; }
   
    $query = "select *,payment_plan.id as payment_id  from payment_plan inner join subdivision on payment_plan.subdivision = subdivision.id  and payment_plan.subdivision!='' and payment_plan.subdivision > 0 and payment_plan.project_id!='' and payment_plan.project_id > 0  ".$from_datedata." ".$to_datedata." ".$subdivisiondata." group by payment_plan.subdivision ORDER BY subdivision.name ASC ";
    //$query = "select * from subdivision ORDER BY name ASC";
    $result = mysql_query($query) or die('error in query select subdivision query '.mysql_error().$query);
    $total_row = mysql_num_rows($result);
}
else
{   
    //echo "all";
	$query = "select * from subdivision ORDER BY name ASC";
	$result = mysql_query($query) or die('error in query select subdivision query '.mysql_error().$query);
	$total_row = mysql_num_rows($result);
}

$page = $_REQUEST['page'];
if ($page < 1) $page = 1;
$numberOfPages = numberofpages();
$resultsPerPage = resultperpage();
$startResults = ($page - 1) * $resultsPerPage;
$totalPages = ceil($total_row / $resultsPerPage);


if(mysql_real_escape_string(trim($_REQUEST['search_action'])) != "")
{
    //echo "test2";
     $from_date = strtotime(mysql_real_escape_string(trim($_REQUEST['from_date'])));
    
    $to_date = strtotime(mysql_real_escape_string(trim($_REQUEST['to_date'])));

    $select_query1 = "select *,payment_plan.id as payment_id  from payment_plan inner join subdivision on payment_plan.subdivision = subdivision.id  and payment_plan.subdivision!='' and payment_plan.subdivision >= 0 and payment_plan.project_id!='' and payment_plan.project_id >= 0  ".$from_datedata." ".$to_datedata." ".$subdivisiondata." group by payment_plan.subdivision ORDER BY subdivision.name ASC ";
	$select_query2 = "select * from subdivision where 1 ".$from_datedata." ".$to_datedata." ".$subdivisiondata." ORDER BY name ASC";
    $select_query = $select_query2;
    
    if($from_date)
    {
        $from_datedata ="and payment_plan.payment_date >= '".$from_date."'";
        $select_query = $select_query1;
    }
    else 
    { 
        $from_datedata=""; 
    }
    
    if($to_date)
    {
        $to_datedata ="and payment_plan.payment_date <= '".$to_date."'";
        $select_query = $select_query1;
    }
    else 
    { 
        $to_datedata=""; 
    } 

	 $search_val = mysql_real_escape_string(trim($_REQUEST['subdivision2']));
     if($search_val)
     {
         $subdivisiondata =" and subdivision.name LIKE '%".$search_val."%'";
         //$select_query = $select_query1;
     }
     else 
     { 
         $subdivisiondata=""; 
     }
    //echo $select_query;
    
    $select_result = mysql_query($select_query) or die('error in query select subdivision query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
}
else
{
    //echo "All2";
	$select_query = "select * from subdivision ORDER BY name ASC LIMIT $startResults, $resultsPerPage";
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
        All Sub Division List</h4>
  </td>
        <td width="" style="float:right;">
        <a href="add_subdivision.php"><input type="button" name="add_button" id="add_button" value="" class="button_add" /></a>
    <input type="button" name="print_button" id="print_button" value="" class="button_print" onClick="return print_data();"  />
    <script src="dist/jquery.table2excel.min.js"></script>
    <input type="button" id="export_to_excel" value="" class="button_export" >
    
    <input type="button" id="search" value="" class="button_search1" onClick="search_display();" >
            </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
<!-------------->
<?php include_once("main_search_open.php") ?>
  
  <form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" >
        <input type="hidden" name="search_check_val" id="search_check_val" value="1" >
        <link rel="stylesheet" href="css/jquery-ui.css" />
                <!--   <script src="js/jquery-1.9.1.js"></script>-->
                   <script src="js/jquery-ui.js"></script>
                  
          
          <input type="hidden" id="print_header" name="print_header" value="All Subdivision - List">
              
          <div id="adddiv" style="display:<?php if($error_msg != "") { ?>block<?php } else { ?>none<?php } ?>;">
          
          
              
              </div>
              
              
<table width="" border="1" align="left" cellpadding="0" cellspacing="0">
    <tr>
        <td width="80">From Date</td>
        <td width="180">
            <input type="text"  name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>" style="width:150px; height: 25px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('from_date')" style="cursor:pointer"/>
        </td>
        <td width="80">To Date</td>
        <td width="180">
            <input type="text"  name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>" style="width:150px; height: 25px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('to_date')" style="cursor:pointer"/>
        </td>
        <td width="80px">Subdivision</td>
        <td width="250px">
            <input type="text" id="subdivision2"  name="subdivision2" value="<?= $_REQUEST['subdivision2'] ?>" style="width:250px;"/>
        </td>
        <td>
            <input type="submit" name="search_button" id="search_button" value="Search" class="button"  />
            <!--&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='subdivision.php';"  />-->
        </td>
    </tr>
</table>
                  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr>
                          <td width="180" align="left" valign="top"></td>
                          
                          <td align="left" valign="top" ></td>
                          
                          
                      </tr>
                  </table>
                  <input type="hidden" name="page" id="page" value=""  />
                  <input type="hidden" name="search_action" id="search_action" value=""  />
                  </form>
      
  <?php include_once("main_search_close.php") ?>
 <!-------------->

  <?php include_once("main_body_open.php") ?>
  
		<form name="user_form" id="user_form" action="" method="post" >
        
        <input type="hidden" name="action_perform" id="action_perform" value="" >
        <input type="hidden" name="del_id" id="del_id" value="" >
        <input type="hidden" name="count" id="count" value="<?php echo $i; ?>"  />    
        </form>
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
        <table class="data" id="my_table" border="1" cellpadding="1" cellspacing="0" style="border: 1px solid #111111;">
        
        <tr style="display:none ;">
            <td><b>All Sub Division List : </b></td>
            <td><b> Generated On :</b></td>
			<td><b><?php echo getTime(); echo "("; $username_1=get_field_value("full_name","user","userid",$_SESSION['userId']); echo $username_1; echo ")";?></b></td>
			<td colspan="4"></td>
        </tr>    
       

            <tr >
            <thead class="report-header">
				<th class="data" width="30px">S.No.</th>
				<th class="data">Sub Division Name</th>
                <th class="data">Debit</th>
                <th class="data">Credit</th>
                <th class="data">No. Of Entry</th>
				<th class="data">Added By</th>
				<th class="data">Added On</th>
				<th class="data">Updated By</th>
				<th class="data">Updated On</th>
                <th class="data noExl" width="75px" id="header1">Action</th>
                </thead>
			</tr>
			<?php
			if($select_total > 0)
			{
				$i=1;
				while($select_data = mysql_fetch_array($select_result))
				{
					$ii=$i+$startResults;
					 ?>
					<tr class="data">
						<td class="data" width="30px"><?php echo $ii; ?></td>
						<td class="data"><a href="subdivision_projectlist.php?subdivision=<?php echo $select_data['id']; ?>&from_date=<?php echo$_REQUEST['from_date']; ?>&to_date=<?php echo $_REQUEST['to_date']; ?>" title="View Ledger"  ><?php echo $select_data['name']; ?></a></td>
                        <td class="data">
                        <?php 
                        $from_date = strtotime(mysql_real_escape_string(trim($_REQUEST['from_date'])));
    
                         $to_date = strtotime(mysql_real_escape_string(trim($_REQUEST['to_date'])));
    
                            if($from_date!=""){
                                $from_datedata ="and payment_date >= '".$from_date."'";
                            }else { $from_datedata=""; }
                            
                            if($to_date!=""){
                                $to_datedata ="and payment_date <= '".$to_date."'";
                            }else { $to_datedata=""; } 
                            
                            $select_tot = "select SUM(debit) as total_debit,SUM(credit) as total_credit ,count(id) as no_entry from payment_plan where subdivision='".$select_data['id']."' and project_id!='' and project_id > 0 ".$from_datedata." ".$to_datedata."";
                            $result_tot = mysql_query($select_tot) or die('error in query select subdivision query '.mysql_error().$select_tot);
                            $select_data3 = mysql_fetch_array($result_tot);
                         ?>
                         <b><?php echo currency_symbol().number_format($select_data3['total_debit'],2,'.',''); ?></b>
                        </td>
                        <td class="data">
                         <b><?php echo currency_symbol().number_format($select_data3['total_credit'],2,'.',''); ?></b>
                        </td>
                        <td class="data" width="75px" align="center"><?php echo $select_data3['no_entry']; ?></td>
                        <td class="data">
						<?php
							echo get_field_value("full_name","user","userid",$select_data['added_by']);							 
						?>
						</td>
						<td class="data">
						<?php
						if($select_data['added_on'])
							echo date('d-m-Y h:i:s A', $select_data['added_on']);	
						?>
						</td>
						<td class="data">
						<?php
							echo get_field_value("full_name","user","userid",$select_data['updated_by']); 
						?>
						</td>
						<td class="data">
						<?php
						if($select_data['updated_on'])
							echo date('d-m-Y h:i:s A', $select_data['updated_on']);							 
						?>
						</td>
						<td class="data noExl" width="75px" align="left">
						&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="edit_subdivision.php?subdivision=<?php echo $select_data['id']; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>&nbsp;
                        <?php if($select_data3['no_entry']<1){
                            ?>
                            <a href="javascript:account_delete(<?php echo $select_data['id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>
                            <?php 
                        }?>
						
						
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
            filename: "subdivision_List",
            fileext: ".xls",        
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true            
        });  
        // $("#thead").show();
    });
   // $('table td').find('td:eq(4)').show(); 
    $( "#subdivision2" ).autocomplete({
            source: "subdivision_search_ajax.php"
        });
        
});

    
    </script>
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
	/*if(document.getElementById("search_text").value=="")
	{
	 alert("Please enter search text to search");
	 document.getElementById("search_text").focus();
	 return false;
	} */
    //document.getElementById("search_action").value="withoutdate";
    $("#search_action").val("search_start");
        $("#search_form").submit();
	
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
    newWin.document.write("<h3 align='center'>"+print_header1+" </h3>");
     $("#header1").hide();
    $('table tr').find('td:eq(5)').hide();
    newWin.document.write(divToPrint.outerHTML);
    newWin.print();
    $('table tr').find('td:eq(5)').show();
    $("#header1").show();
    newWin.close();

}
</script>
