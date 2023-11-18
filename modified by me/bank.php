<?php 
session_start();
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

function get_total($bank_id,$end_date)
{
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


/*     Deletion  Account   */

if($_REQUEST['action_perform'] == "delete_bank")
{
	$del_id = $_REQUEST['del_id'];
	$del_query = "delete from bank where id = '".$del_id."'";
	$del_result = mysql_query($del_query) or die("error in delete query ".mysql_error());
	$msg = "Account Deleted Successfully.";
	
}

if(mysql_real_escape_string(trim($_REQUEST['bank_search'])) != "")
{
	$pay_from_arr = explode(" -",$_REQUEST['bank_search']);
    $pay_bank_id = get_field_value("id","bank","bank_account_name",$pay_from_arr[0]);
   
	$query = "select * from bank where id ='".$pay_bank_id."' and type = 'bank account' ORDER BY bank_account_name ASC";
	$result = mysql_query($query) or die('error in query select bank query '.mysql_error().$query);
	$total_row = mysql_num_rows($result);
}else if(mysql_real_escape_string(trim($_REQUEST['search_text'])) != "")
{
    $column = $_REQUEST['search_type'];
    $query = "select * from bank where $column LIKE '%".mysql_real_escape_string(trim($_REQUEST['search_text']))."%' and type = 'bank account' ORDER BY bank_account_name ASC";
    $result = mysql_query($query) or die('error in query select bank query '.mysql_error().$query);
    $total_row = mysql_num_rows($result);
}

else
{
	$query = "select * from bank where type = 'bank account' ORDER BY bank_account_name ASC";
	$result = mysql_query($query) or die('error in query select bank query '.mysql_error().$query);
	$total_row = mysql_num_rows($result);
}
/*
$page = $_REQUEST['page'];
if ($page < 1) $page = 1;
$numberOfPages = numberofpages();
$resultsPerPage = resultperpage();
$startResults = ($page - 1) * $resultsPerPage;
$totalPages = ceil($total_row / $resultsPerPage);
*/
if(mysql_real_escape_string(trim($_REQUEST['bank_search'])) != "")
{
    //$column = $_REQUEST['search_type'];
     $pay_from_arr = explode(" -",$_REQUEST['bank_search']);
    $pay_bank_id = get_field_value("id","bank","bank_account_name",$pay_from_arr[0]);
   if($pay_from_arr[0] =="All"){
       $select_query = "select * from bank where  type = 'bank account' ORDER BY bank_account_name ASC ";
   }else{
   // $select_query = "select * from bank where id ='".$pay_bank_id."'  and type = 'bank account' ORDER BY bank_account_name ASC LIMIT $startResults, $resultsPerPage";   
   $select_query = "select * from bank where id ='".$pay_bank_id."'  and type = 'bank account' ORDER BY bank_account_name ASC ";     
}
    
    $select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
    $select_total = mysql_num_rows($select_result);

}else if(mysql_real_escape_string(trim($_REQUEST['search_text'])) != "")
{
	$column = $_REQUEST['search_type'];
	//$select_query = "select * from bank where $column LIKE '%".mysql_real_escape_string(trim($_REQUEST['search_text']))."%' and type = 'bank account' ORDER BY bank_account_name ASC LIMIT $startResults, $resultsPerPage";
	$select_query = "select * from bank where $column LIKE '%".mysql_real_escape_string(trim($_REQUEST['search_text']))."%' and type = 'bank account' ORDER BY bank_account_name ASC ";
	
	$select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
}
else
{
	//$select_query = "select * from bank where type = 'bank account' ORDER BY bank_account_name ASC LIMIT $startResults, $resultsPerPage";
	$select_query = "select * from bank where type = 'bank account' ORDER BY bank_account_name ASC ";
	
	$select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
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
  <!--<body data-home-page="Home.php" data-home-page-title="Home" class="u-body u-xl-mode" data-lang="en">-->
  <?php include_once ("top_header2.php"); ?> 
  <?php include_once ("top_menu.php"); ?>
  <form name="search_form1" id="search_form1" action="" method="post" onSubmit="return search_valid1();" enctype="multipart/form-data">
             
			 <link rel="stylesheet" href="css/jquery-ui.css" />
			 <!--<script src="js/jquery-1.9.1.js"></script>-->
			  <script src="js/jquery-ui.js"></script>
			  <script src="dist/jquery.table2excel.min.js"></script>
	 <input type="hidden" id="print_header" name="print_header" value="Bank - List">
	  
  <?php include_once("main_heading_open.php") ?>
    
	<table style="width:100%;" border="0" style="padding:0px 0px; margin-top:-10px;">
    <tr>
        <td style="valign:top;" align="left"><h4 class="u-text-2 u-text-palette-1-base " style="padding:0px;margin:0px; ">
        Bank Account List     </h4>
  </td>
        <td width="" style="float:right; margin-right:0px;">
        	<a href="add_bank.php" ><input type="button" name="add_button" id="add_button" value="" class="button_add" /></a>
    		<input type="button" name="print_button" id="print_button" value="" class="button_print" onClick="return print_data();"  />
			<input type="button" id="export_to_excel" value="" class="button_export" >&nbsp;&nbsp;
    		<input type="button" id="search" value="" class="button_search1" onClick="search_display();" >
  		</td>
    </tr>
</table>

	 
	 

  <?php include_once("main_heading_close.php") ?>
  <!-------------->
  <?php include_once("main_search_open.php") ?>
  <input type="hidden" name="search_check_val" id="search_check_val" value="<?php echo $sear_val_f;//echo $_REQUEST['search_check_val']; ?>" >
  
	 
	 <table width="100%" align="center">
	
	 <tr><td valign="top">
		 
			   <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				 <tr>
						 <td width="450" align="left" valign="top">Search By Bank Account Name
						 &nbsp;&nbsp;&nbsp;&nbsp;
						 <input type="text" id="bank_search"  name="bank_search" value="<?php echo $_REQUEST['bank_search']; ?>" style="width:250px;"/></td>
						 
						 <td align="left" valign="top" ><input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;</td>
						 </tr>
			   </table>
		 </form>
		 
		 <form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" enctype="multipart/form-data">
		<input type="hidden" name="search_check_val_1" id="search_check_val_1" value="<?php echo $sear_val_f;//echo $_REQUEST['search_check_val']; ?>" >
  
			 <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				 <tr>
					 <td width="255" align="left" valign="top"> Search By:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						 <select name="search_type" id="search_type" >
						 <option value="" >-- Please Select --</option>
						 <option value="bank_account_name" <?php if($_REQUEST['search_type'] == "bank_account_name") echo 'selected="selected"'; ?>  >Bank Account Name</option>
						 <option value="bank_account_number" <?php if($_REQUEST['search_type'] == "bank_account_number") echo 'selected="selected"'; ?>  >Bank Account Number</option>
						 <option value="bank_name" <?php if($_REQUEST['search_type'] == "bank_name") echo 'selected="selected"'; ?>  >Bank Name</option>
						 </select>
				   </td>
					 <td width="180" align="left" valign="top"><input type="text" name="search_text" id="search_text" value="<?php echo mysql_real_escape_string(trim($_REQUEST['search_text'])); ?>" /></td>
					 <td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='bank.php';"  /></td>
				 </tr>
			 </table>
			 <input type="hidden" name="page" id="page" value=""  />
			 </form>
 </td>
 <td valign="top" width="300px;" align="right">
 <span id="plus_total_div" style="color:#000000; font-size:18px; font-weight:bold;"></span>
		 <BR>
		 <span id="minus_total_div" style="color:#FF0000; font-size:18px; font-weight:bold;"></span>
		 <BR>
		 <span id="grand_total_div" style="color:#FF0000; font-size:18px; font-weight:bold;"></span>
 </td>
 </tr></table>

  <?php include_once("main_search_close.php") ?>
 <!-------------->
  <?php include_once("main_body_open.php") ?>
   <!------- main _body Start----------->
   <?php if($msg != "") { ?>
    <div class="sukses">
        <?php echo $msg; ?>
        </div>
    <?php } else if($error_msg != "") { ?>
    <div class="gagal">
        
        <?php echo $error_msg; ?>
        </div>
    <?php } ?>
    
   <div id="ledger_data" style="height: 390px;  overflow-y: scroll;overflow-x: scroll;padding:0px;">
   <table class="data" id="my_table" border="1" cellpadding="1" cellspacing="0" style="border: 1px solid #111111;">
   <tr style="display:none ;">
            <td><b>Bank Account List : </b></td>
            <td><b> Generated On :</b></td>
			<td><b><?php echo getTime(); echo "("; $username_1=get_field_value("full_name","user","userid",$_SESSION['userId']); echo $username_1; echo ")";?></b></td>
			<td colspan="4"></td>
        </tr>    
            <tr >
            <thead class="report-header">
				<th class="data" width="30px">No</th>
				<th class="data">Bank Account Name</th>
				<th class="data">Bank Account Number</th>
				
				<th class="data">Bank Name</th>
                <th class="data">Current Balance</th>
                <th class="data">No.OfEntries</th>
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
						<td class="data" width="30px"><?php echo $ii; ?></td>
						<td class="data"><?php echo $select_data['bank_account_name']; ?></td>
						<td class="data"><a href="bank-ledger.php?bank_id=<?php echo $select_data['id']; ?>" title="View Ledger"  ><?php echo $select_data['bank_account_number']; ?></a></td>
												<td class="data"><?php echo $select_data['bank_name']; ?></td>
                                                <td class="data" <?php if(get_total($select_data['id'],strtotime(date("d-m-Y")))<0) { ?> style="color:#FF0000;" <?php } ?>><?php echo currency_symbol().number_format(get_total($select_data['id'],strtotime(date("d-m-Y"))),2);
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
                         <td class="data " width="30px" align="center">
                         <?php
                          
                            
                        
                           $select_tot = "select SUM(debit) as total_debit,SUM(credit) as total_credit , count(id) as no_entry from payment_plan where description != 'Opening Balance'  and bank_id='".$select_data['id']."' and bank_id!='' and bank_id > 0 ";
    	$result_tot = mysql_query($select_tot) or die('error in query select subdivision query '.mysql_error().$select_tot);
      	$select_data3 = mysql_fetch_array($result_tot);
                        echo $select_data3['no_entry'];
                       // echo $select_data3['total_credit']-$select_data3['total_debit']
                         ?>
                         
                         </td>
						<td class="data noExl" width="75px" align="left">
						&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="edit_bank.php?id=<?php echo $select_data['id']; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
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
					<td  width="30px" colspan="7" class="record_not_found" >Record Not Found</td>
				</tr>
				<?php
			}
			?>
			
		</table>
		</div>     
  <?php include_once("main_body_close.php") ?>
  <!--------------> 
  

  <?php include_once ("footer.php"); ?>  
  <form name="bank_form" id="bank_form" action="" method="post" >
<input type="hidden" name="action_perform" id="action_perform" value="" >
        <input type="hidden" name="del_id" id="del_id" value="" >
</form> 
</body></html>
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
            filename: "bank_List",
            fileext: ".xls",        
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true            
        });  
        // $("#thead").show();
    });
   // $('table td').find('td:eq(4)').show(); 
    $( "#bank_search" ).autocomplete({
            source: "bank1-ajax.php"
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

function account_delete(del_id)
{
	if(confirm("Are you sure want to delete?!!!!!......"))
	{
		$("#action_perform").val("delete_bank");
		$("#del_id").val(del_id);
		$("#bank_form").submit();
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
<?php
if(isset($grand_total))
{
?>
document.getElementById("plus_total_div").innerHTML = 'Positive = <?php echo currency_symbol().number_format($plus_total,2); ?>';
document.getElementById("minus_total_div").innerHTML = 'Nagetive = <?php echo currency_symbol().number_format($minus_total,2); ?>';
document.getElementById("grand_total_div").innerHTML = 'Total = <?php echo currency_symbol().number_format($grand_total,2); ?>';
<?php
}
?>
</script>
  