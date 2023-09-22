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


function get_total($bank_id,$end_date)
{
    //$date_list_query2 = "select SUM(debit) as debit,SUM(credit) as credit ,count(id) as no_entry  from payment_plan where bank_id = '".$bank_id."' and payment_date <= '".$end_date."' ";
    $date_list_query2 = "select SUM(debit) as debit,SUM(credit) as credit ,count(id) as no_entry  from payment_plan where bank_id = '".$bank_id."' ";
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

if($_REQUEST['action_perform'] == "delete_import")
{
    $del_id = $_REQUEST['del_id'];
    $file_path = $_REQUEST['file_path'];
    $del_query = "delete from file_import where id = '".$del_id."'";
    $del_result = mysql_query($del_query) or die("error in delete query ".mysql_error());
    unlink("import_files/$file_path");
    $del_query_data = "delete from tbl_info where file_import_id = '".$del_id."' and file_name='".$file_path."'";
    $del_result_data = mysql_query($del_query_data) or die("error in delete query ".mysql_error());
   
    $msg = "File Deleted Successfully.";
   }
//bank_search
 if(mysql_real_escape_string(trim($_REQUEST['search_text'])) != "")
{
    $column = "file_name";
    $query = "select * from file_import where $column LIKE '%".mysql_real_escape_string(trim($_REQUEST['search_text']))."%'  ORDER BY date ASC";
    $result = mysql_query($query) or die('error in query select file query '.mysql_error().$query);
    $total_row = mysql_num_rows($result);
}

else
{
    $query = "select * from file_import  ORDER BY date ASC";
    $result = mysql_query($query) or die('error in query select file query '.mysql_error().$query);
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
    $column="file_name";
   
    $select_query = "select * from file_import where $column LIKE '%".mysql_real_escape_string(trim($_REQUEST['search_text']))."%' ORDER BY date ASC LIMIT $startResults, $resultsPerPage";
    $select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
    $select_total = mysql_num_rows($select_result);
}
else
{
    $select_query = "select * from file_import  ORDER BY date ASC LIMIT $startResults, $resultsPerPage";
    $select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
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
        Import file list </h4>
  </td>
        <td width="" style="float:right;">
        <link rel="stylesheet" href="css/jquery-ui.css" />
           <!--  <script src="js/jquery-1.9.1.js"></script>-->
             <script src="js/jquery-ui.js"></script>
    <input type="hidden" id="print_header" name="print_header" value="Cash Accouts List">
    
        <a href="add_import_file.php"><input type="button" name="add_button" id="add_button" value="" class="button_add" /></a>
    <input type="button" name="print_button" id="print_button" value="" class="button_print" onClick="return print_data();"  />
    <script src="dist/jquery.table2excel.min.js"></script>
    <input type="button" id="export_to_excel" value="" class="button_export" >&nbsp;&nbsp;
    <input type="button" id="search" value="" class="button_search1" onClick="search_display();" >
         </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
<!-------------->
<?php include_once("main_search_open.php") ?>
  <input type="hidden" name="search_check_val" id="search_check_val" value="0" >
 
  <form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" enctype="multipart/form-data">
         
         <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
             <tr>
                 <td width="105" align="left" valign="top"> Search By Name :
                    
               </td>
                 <td width="180" align="left" valign="top"><input type="text" name="search_text" id="search_text" value="<?php echo mysql_real_escape_string(trim($_REQUEST['search_text'])); ?>" /></td>
                 
                 <td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='import_file.php';"  /></td>
                 
             </tr>
         </table>
         </form>
         
  <?php include_once("main_search_close.php") ?>
 <!-------------->

<?php include_once("main_body_open.php") ?>

<form name="search_form1" id="search_form1" action="" method="post" onSubmit="return search_valid1();" enctype="multipart/form-data">
             
            
    <?php if($msg != "") { ?>
    <div class="sukses">
        <?php echo $msg; ?>
        </div>
    <?php } else if($error_msg != "") { ?>
    <div class="gagal">
        
        <?php echo $error_msg; ?>
        </div>
    <?php } ?>
    
        <!--
              <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                        <td width="450" align="left" valign="top">Search Name
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="text" id="bank_search"  name="bank_search" value="<?php echo $_REQUEST['bank_search']; ?>" style="width:250px;"/></td>
                        
                        <td align="left" valign="top" ><input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;</td>
                        </tr>
              </table>-->
        </form>
        <input type="hidden" name="page" id="page" value=""  />
             
        <div id="ledger_data">
        <table class="data" id="my_table" border="1" cellpadding="1" cellspacing="0" style="border: 1px solid #111111;">
        
            <tr >
            <thead class="report-header">
                <th class="data" width="30px">No</th>
                <th class="data">Name</th>
                <th class="data">Date</th>
                <th class="data" style="width:50px;"></th>
                <th class="data" style="width:50px;"></th>
                <th class="data" style="width:50px;"></th>
                <th class="data" style="width:50px;"></th>
                <th class="data" style="width:50px;"></th>
                <th class="data" style="width:50px;"></th>
                <th class="data" width="75px" id="header1">Action</th>
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
                        <td class="data" width="30px"><?php echo $ii; ?></td>
                        <td class="data"><a href="import_file-ledger.php?file_id=<?php echo $select_data['id']; ?>" title="View Ledger"  ><?php echo $select_data['file_name']; ?></a></td>
                        <td class="data"><?php echo $select_data['file_path']; ?></td>
                        <td class="data"><a href="import_file_permission.php?file_id=<?php echo $select_data['id']; ?>" title=""  >Permission</a></td>
                        <td class="data"><a href="import_file_internal_transfer-ledger.php?file_id=<?php echo $select_data['id']; ?>" title=""  >Internal-Tra</a></td>
                        <td class="data"><a href="import_file_make_payment-ledger.php?file_id=<?php echo $select_data['id']; ?>" title=""  >Make-Pay</a></td>
                        <td class="data"><a href="import_file_received_payment-ledger.php?file_id=<?php echo $select_data['id']; ?>" title=""  >Received-Pay</a></td>
                        <td class="data"><a href="import_file_led_received_payment-ledger.php?file_id=<?php echo $select_data['id']; ?>" title=""  >Loan-Recei</a></td>
                        <td class="data"><a href="import_file_led_make_payment_ledger.php?file_id=<?php echo $select_data['id']; ?>" title=""  >Loan-Make</a></td>
                        <td class="data" width="75px" align="left">
                        &nbsp;&nbsp;&nbsp;&nbsp;
                       <!-- <a href="edit_cash.php?id=<?php echo $select_data['id']; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>-->
                         
              <a href="javascript:account_delete('<?php echo $select_data['id']; ?>','<?php echo $select_data['file_path']; ?>')"><img src="mos-css/img/delete.png" title="Delete" ></a>&nbsp;&nbsp;&nbsp;    
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
                    <td  width="30px" colspan="4" class="record_not_found" >Record Not Found</td>
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
<form name="import_form" id="import_form" action="" method="post" >
<input type="hidden" name="action_perform" id="action_perform" value="" >
        <input type="hidden" name="del_id" id="del_id" value="" >
        <input type="hidden" name="file_path" id="file_path" value="">
</form>
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
            filename: "Cash_Accouunt_List",
            fileext: ".xls",        
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true            
        });  
        // $("#thead").show();
    });
   // $('table td').find('td:eq(4)').show(); 
    $( "#bank_search" ).autocomplete({
            source: "cash1-ajax.php"
        });
        
});
    
    </script>
  
<script>

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
    $('table tr').find('td:eq(6)').hide();
    newWin.document.write(divToPrint.outerHTML);
    newWin.print();
    $('table tr').find('td:eq(6)').show();
    $("#header1").show();
    newWin.close();

}

function add_div()
{
    $("#adddiv").toggle("slow");
}
function account_delete(del_id,file_path)
{
    if(confirm("Are you sure want to delete?!!!!!......"))
    {
        $("#action_perform").val("delete_import");
        $("#del_id").val(del_id);
        $("#file_path").val(file_path);
        $("#import_form").submit();
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
  