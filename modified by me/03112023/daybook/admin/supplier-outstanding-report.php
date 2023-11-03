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


if(mysql_real_escape_string(trim($_REQUEST['supplier_search'])) != "")
{
	$column = $_REQUEST['supplier_search'];
	     $pay_from_arr = explode(" -",$_REQUEST['supplier_search']);
    $pay_bank_id = get_field_value("cust_id","customer","full_name",$pay_from_arr[0]);
   if($pay_from_arr[0] =="All"){
     // $select_query = "select * from customer where type = 'supplier' ORDER BY full_name ASC ";
     $select_query = "select *,payment_plan.id as payment_id  from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id  and payment_plan.cust_id!='' and payment_plan.cust_id > 0  and customer.type='supplier'  group by payment_plan.cust_id  ORDER BY customer.full_name ASC ";
   }else{
    
    // $select_query = "select * from customer where cust_id =".$pay_bank_id." and type = 'supplier' ORDER BY full_name ASC ";
    $select_query = "select *,payment_plan.id as payment_id  from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id  and payment_plan.cust_id='".$pay_bank_id."'   and customer.type='supplier'  group by payment_plan.cust_id  ORDER BY customer.full_name ASC ";
   
   }
 // echo $select_query;
  //exit;
	$select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
    
}
else
{
	//$select_query = "select * from customer where type = 'supplier' ORDER BY full_name ASC ";
    $select_query = "select *,payment_plan.id as payment_id  from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id  and payment_plan.cust_id!='' and payment_plan.cust_id > 0  and customer.type='supplier'  group by payment_plan.cust_id  ORDER BY customer.full_name ASC ";
	$select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
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
    <form name="search_form1" id="search_form1" action="" method="post" onSubmit="return search_valid1();" enctype="multipart/form-data">
             <script src="js/datetimepicker_css.js"></script>
            <link rel="stylesheet" href="css/jquery-ui.css" />
             <script src="js/jquery-1.9.1.js"></script>
             <script src="js/jquery-ui.js"></script>
	<h3>Supplier Outstanding Report
    <span style="float: right;">
    
    <input type="button" name="print_button" id="print_button" value="" class="button_print" onClick="return print_data();"  />
    <script src="dist/jquery.table2excel.min.js"></script>
    <input type="button" id="export_to_excel" value="" class="button_export" >&nbsp;&nbsp;
    </span>
    </h3>
    
<input type="hidden" id="print_header" name="print_header" value="<h3>Supplier-Outstanding-Report</h3>">

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
	<div id="adddiv" style="display:<?php if($error_msg != "") { ?>block<?php } else { ?>none<?php } ?>;">
	
	
		
		</div>
		 
              <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                        <td width="450" align="left" valign="top">Search By Supplier Name
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="text" id="supplier_search"  name="supplier_search" value="<?php echo $_REQUEST['supplier_search']; ?>" style="width:250px;"/></td>
                        
                        <td align="left" valign="top" ><input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='supplier-outstanding-report.php';"  /></td>
                        </tr>
              </table>
        </form>
        
		<form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" >
			<input type="hidden" name="page" id="page" value=""  />
			</form>
		<form name="supplier_form" id="supplier_form" action="" method="post" >
        
        <input type="hidden" name="action_perform" id="action_perform" value="" >
        <input type="hidden" name="del_id" id="del_id" value="" >
        </form>
		
<div id="ledger_data">
        <table class="data" id="my_table" border="1" cellpadding="1" cellspacing="0" style="border: 1px solid #111111; width: 95%;">
        
            <tr >
            <thead class="report-header">
            	<th class="data" width="30px">S.No.</th>
				<th class="data">Supplier Name</th>
				<th class="data">Last Invoice Date</th>
                <th class="data"> Last Paid date</th>
                <th class="data">Current Outstanding Due amount</th>
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
						<td class="data" width="30px" align="center"><?php echo $ii; ?></td>
						<td class="data"><?php echo $select_data['full_name']; ?></td>
						<td class="data" align="center">
                          <?php 
                        $select_query_pay1 = "select max(payment_date) as payment_max_date from payment_plan  where cust_id = '".$select_data['cust_id']."'   and trans_type_name in('receive_goods','sale_goods','internal_transfer','inst_receive_goods','inst_sale_goods','instmulti_sale_goods')   ";
                       // echo  $select_query_pay;
                    $select_result_pay1 = mysql_query($select_query_pay1) or die('error in query select customer query '.mysql_error().$select_query_pay1);
                     $select_total_pay1 = mysql_num_rows($select_result_pay1);
                     
                    $select_data_pay1 = mysql_fetch_array($select_result_pay1);
                    if($select_data_pay1['payment_max_date']>0)
                     {
                    echo date("d-m-Y",$select_data_pay1['payment_max_date']);
                              
                     }
                   
                        ?>
                        
                      
                        </td>
						<td class="data" align="center">
                        <?php 
                        $select_query_pay = "select max(payment_date) as payment_max_date from payment_plan  where cust_id = '".$select_data['cust_id']."'   and trans_type_name in('make_payment','receive_payment','loanadvance_makepayment','loanadvance_receivepayment','inst_make_payment','inst_receive_payment','instmulti_receive_payment')   ";
                       // echo  $select_query_pay;
                    $select_result_pay = mysql_query($select_query_pay) or die('error in query select customer query '.mysql_error().$select_query_pay);
                     $select_total_pay = mysql_num_rows($select_result_pay);
                     
                    $select_data_pay = mysql_fetch_array($select_result_pay);
                    if($select_data_pay['payment_max_date']>0)
                     {
                    echo date("d-m-Y",$select_data_pay['payment_max_date']);
                              
                     }
                   
                        ?>
                        
                        
                        </td>
						<?php
                         $select_query_pro = "select id as payment_id ,SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan  where cust_id = '".$select_data['cust_id']."'     ";
  // echo $select_query;
    $select_result_pro = mysql_query($select_query_pro) or die('error in query select customer query '.mysql_error().$select_query_pro);
    $select_total_pro = mysql_num_rows($select_result_pro);
    $select_data_pro = mysql_fetch_array($select_result_pro);
                             $total_outstanding= $select_data_pro['total_credit']-$select_data_pro['total_debit'];
                         ?>
                        <td align="left" class="data" <?php if($total_outstanding <0) { ?> style="color:#FF0000;" <?php } ?>>&nbsp;&nbsp;&nbsp;<?php echo currency_symbol().number_format($total_outstanding,2);
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
					<td  width="30px" colspan="5" class="record_not_found" >Record Not Found</td>
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
	</div>
<div class="clear"></div>
<?php
include_once("footer.php");
?>
</div>
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
            filename: "Supplier_Outstanding_Report",
            fileext: ".xls",        
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true            
        });  
        // $("#thead").show();
    });
   $( "#supplier_search" ).autocomplete({
            source: "supplier1-ajax.php"
        });
        
       
   
});



/*
    $(document).ready(function(){
        $( "#supplier_search" ).autocomplete({
            source: "supplier1-ajax.php"
        });
        
       
        
    }) */
    </script>



<script>
function add_div()
{
	$("#adddiv").toggle("slow");
}

function validation()
{
	if($("#fname").val() == "")
	{
		alert("Please enter first name.");
		$("#fname").focus();
		return false;
	}
	/*else if($("#mobile").val() == "")
	{
		alert("Please enter mobile number.");
		$("#mobile").focus();
		return false;
	}
	else if($("#email").val() == "")
	{
		alert("Please enter email address.");
		$("#email").focus();
		return false;
	}
	else if($("#current_address").val() == "")
	{
		alert("Please enter current address.");
		$("#current_address").focus();
		return false;
	}*/
	else if($("#opening_balance").val() == "")
	{
		alert("Please enter bank opening balance.");
		$("#opening_balance").focus();
		return false;
	}
	else if($("#opening_balance_date").val() == "")
	{
		alert("Please enter bank opening balance date.");
		$("#opening_balance_date").focus();
		return false;
	}
	else
	{
		$("#action_perform").val("add_supplier");
		$("#supplier_form").submit();
		return true;
	}
	
}
function account_delete(del_id)
{
	if(confirm("Are you sure want to delete?!!!!!......"))
	{
		$("#action_perform").val("delete_supplier");
		$("#del_id").val(del_id);
		$("#supplier_form").submit();
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
 function same_address()
 {
 	if(document.getElementById("same_current").checked==true)
	{
		
		document.getElementById("permanent_address").value = document.getElementById("current_address").value;
		
	}
	else
	{
	
		document.getElementById("permanent_address").value = "";
		
	}
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
$('table tr').find('td:eq(7)').hide();
newWin.document.write(divToPrint.outerHTML);
newWin.print();
//$('tr').children().eq(7).show();

$('table tr').find('td:eq(7)').show();
$("#header1").show();
newWin.close();
   
   /* printMe=window.open();
    printMe.document.write(document.getElementById("").innerHTML);
    printMe.print();
    printMe.close();*/
}


</script>
