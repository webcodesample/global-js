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

function get_total($cust_id,$end_date)
{
    $date_list_query2 = "select SUM(debit) as debit,SUM(credit) as credit from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$cust_id."' and customer.type = 'supplier'";
    
    //$date_list_query2 = "select SUM(debit) as debit,SUM(credit) as credit  from payment_plan where cust_id = '".$cust_id."' and payment_date <= '".$end_date."' ";
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


if(trim($_REQUEST['action_perform']) == "add_supplier")
{
	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
	$fname=mysql_real_escape_string(trim($_REQUEST['fname']));
	$lname=mysql_real_escape_string(trim($_REQUEST['lname']));
	$full_name = $fname.' '.$lname;
	$phone=mysql_real_escape_string(trim($_REQUEST['phone']));
	$mobile=mysql_real_escape_string(trim($_REQUEST['mobile']));
	$email=mysql_real_escape_string(trim($_REQUEST['email']));
	$current_address=mysql_real_escape_string(trim($_REQUEST['current_address']));
	$permanent_address=mysql_real_escape_string(trim($_REQUEST['permanent_address']));
	
	$opening_balance=mysql_real_escape_string(trim($_REQUEST['opening_balance']));
	$opening_balance = str_replace(",","",$opening_balance);
	$opening_balance_date=strtotime(mysql_real_escape_string(trim($_REQUEST['opening_balance_date'])));
	
	if(mysql_real_escape_string(trim($_REQUEST['same_current'])) == "yes")
	{
		$same_address = "yes";
	}
	else
	{
		$same_address = "no";
	}
	
	$quuerrr="select cust_id from customer where email='".$email."' and type = 'supplier' ";
	
	$sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
	$no=mysql_num_rows($sql);
	if($no > 0)
	{
		
			$error_msg = "E-Mail ID already exist try another";
		
	}
	else
	{
		$wi = 0;
		while($wi<1)
		{
			$cust_id = rand(10000,99999);
			$ss="select fname from customer where cust_id=".$cust_id."";
			$sr=mysql_query($ss);
			$tot_rw=mysql_num_rows($sr);
			if($tot_rw == 0)
			{
				break;											
			}
		}
		
		
		$query="insert into customer set cust_id = '".$cust_id."', fname = '".$fname."', lname = '".$lname."', full_name = '".$full_name."', mobile = '".$mobile."', email = '".$email."', current_address = '".$current_address."', same_address = '".$same_address."', permanent_address = '".$permanent_address."', type = 'supplier',opening_balance = '".$opening_balance."', opening_balance_date = '".$opening_balance_date."', create_date = '".getTime()."'";
		$result= mysql_query($query) or die('error in query '.mysql_error().$query);
		
		$query2="insert into payment_plan set cust_id = '".$cust_id."', credit = '".$opening_balance."', description = 'Opening Balance', payment_date = '".$opening_balance_date."', create_date = '".getTime()."'";
		$result2= mysql_query($query2) or die('error in query '.mysql_error().$query2);
		
		$msg = "supplier added successfully.";
	}
	
}

/*     Deletion  Account   */

if($_REQUEST['action_perform'] == "delete_supplier")
{
	$del_id = $_REQUEST['del_id'];
	$del_query = "delete from customer where cust_id = '".$del_id."'";
	$del_result = mysql_query($del_query) or die("error in delete query ".mysql_error());
	$msg = "Supplier Deleted Successfully.";
	
}

if(mysql_real_escape_string(trim($_REQUEST['supplier_search'])) != "")
{
    $column = $_REQUEST['supplier_search'];
         $pay_from_arr = explode(" -",$_REQUEST['supplier_search']);
    $pay_bank_id = get_field_value("cust_id","customer","full_name",$pay_from_arr[0]);
   if($pay_from_arr[0] =="All"){
      $query = "select * from customer where type = 'supplier' ORDER BY full_name ASC ";
      $total_row=0;
   }else{
    
     $query = "select * from customer where cust_id =".$pay_bank_id." and type = 'supplier' ORDER BY full_name ASC LIMIT $startResults, $resultsPerPage";
   // $result = mysql_query($query) or die('error in query select supplier query '.mysql_error().$query);
   // $total_row = mysql_num_rows($result);
   }
    //$column = $_REQUEST['search_type'];
   // $query = "select * from customer where $column LIKE '%".mysql_real_escape_string(trim($_REQUEST['search_text']))."%' and type = 'supplier' ORDER BY full_name ASC";
   
}else
if(mysql_real_escape_string(trim($_REQUEST['search_text'])) != "")
{
	$column = $_REQUEST['search_type'];
	$query = "select * from customer where $column LIKE '%".mysql_real_escape_string(trim($_REQUEST['search_text']))."%' and type = 'supplier' ORDER BY full_name ASC";
	$result = mysql_query($query) or die('error in query select supplier query '.mysql_error().$query);
	$total_row = mysql_num_rows($result);
}
else
{
	$query = "select * from customer where type = 'supplier' ORDER BY full_name ASC";
	$result = mysql_query($query) or die('error in query select supplier query '.mysql_error().$query);
	$total_row = mysql_num_rows($result);
}

$page = $_REQUEST['page'];

if ($page < 1) $page = 1;
echo "Page Index : ".$page;
echo "<br>";
echo "Number of Pages : ".$numberOfPages = numberofpages();
echo "<br>";
echo "Result Per Page : ".$resultsPerPage = resultperpage();
echo "<br>";
echo "Start Index : ".$startResults = ($page - 1) * $resultsPerPage;
echo "<br>";
echo "Total Pages : ".$totalPages = ceil($total_row / $resultsPerPage);

//
if(mysql_real_escape_string(trim($_REQUEST['supplier_search'])) != "")
{
	$column = $_REQUEST['supplier_search'];
	$pay_from_arr = explode(" -",$_REQUEST['supplier_search']);
    $pay_bank_id = get_field_value("cust_id","customer","full_name",$pay_from_arr[0]);
   if($pay_from_arr[0] =="All")
   {
	$select_query = "select * from customer where type = 'supplier' ORDER BY full_name ASC ";
   }
   else
   {    
	$select_query = "select * from customer where cust_id =".$pay_bank_id." and type = 'supplier' ORDER BY full_name ASC LIMIT $startResults, $resultsPerPage";
   }
 // echo $select_query;
  //exit;
	$select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
    
}else if(mysql_real_escape_string(trim($_REQUEST['search_text'])) != "")
{
    $column = $_REQUEST['search_type'];
    $select_query = "select * from customer where $column LIKE '%".mysql_real_escape_string(trim($_REQUEST['search_text']))."%' and type = 'supplier' ORDER BY full_name ASC LIMIT $startResults, $resultsPerPage";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_total = mysql_num_rows($select_result);
}

else
{
	$select_query = "select * from customer where type = 'supplier' ORDER BY full_name ASC LIMIT $startResults, $resultsPerPage";
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
        All Supplier List</h4>
  </td>
        <td width="" style="float:right;">
		<a href="add_supplier.php"><input type="button" name="add_button" id="add_button" value="" class="button_add" /></a>
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
  <input type="hidden" name="search_check_val" id="search_check_val" value="0" >
 <table>
	<tr><td>
  <form name="search_form1" id="search_form1" action="" method="post" onSubmit="return search_valid1();" enctype="multipart/form-data">
             <script src="js/datetimepicker_css.js"></script>
            <link rel="stylesheet" href="css/jquery-ui.css" />
            <!-- <script src="js/jquery-1.9.1.js"></script> -->
             <script src="js/jquery-ui.js"></script>
	
<input type="hidden" id="print_header" name="print_header" value="<h3>All Supplier List</h3>">

	
	<div id="adddiv" style="display:<?php if($error_msg != "") { ?>block<?php } else { ?>none<?php } ?>;">
		</div>
		 
              <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                        <td width="450" align="left" valign="top">Search By Supplier Name
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="text" id="supplier_search"  name="supplier_search" value="<?php echo $_REQUEST['supplier_search']; ?>" style="width:250px;"/></td>
                        
                        <td align="left" valign="top" ><input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;</td>
                        </tr>
              </table>
        </form>
</td></tr><tr><td>
		<form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" >
		<input type="hidden" name="page" id="page" value=""  /><!-- by amit -->
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td width="230" align="left" valign="top">Search By :&nbsp;&nbsp;&nbsp;
                        <select name="search_type" id="search_type" >
                        <option value="" >-- Please Select --</option>
                        <option value="fname" <?php if($_REQUEST['search_type'] == "fname") echo 'selected="selected"'; ?>  >First Name</option>
                        <option value="lname" <?php if($_REQUEST['search_type'] == "lname") echo 'selected="selected"'; ?>  >Last Name</option>
                        <option value="mobile" <?php if($_REQUEST['search_type'] == "mobile") echo 'selected="selected"'; ?>  >Mobile</option>
                        
                        
                        </select>
                  </td><td width="180" align="left" valign="top"><input type="text" name="search_text" id="search_text" value="<?php echo mysql_real_escape_string(trim($_REQUEST['search_text'])); ?>" /></td>
					
					<td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='supplier.php';"  />&nbsp;&nbsp;

                    </td>
					<td align="right" valign="top">

                    </td>
					
				</tr>
			</table>
			</form>
</td></tr></table> 
  <?php include_once("main_search_close.php") ?>
 <!-------------->

  <?php include_once("main_body_open.php") ?>
  
		<form name="supplier_form" id="supplier_form" action="" method="post" >
        
        <input type="hidden" name="action_perform" id="action_perform" value="" >
        <input type="hidden" name="del_id" id="del_id" value="" >
		
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
	<table width="100%" style="padding:10px;">
	<tr><td>
<div id="ledger_data">
        <table class="data" id="my_table" border="1" cellpadding="1" cellspacing="0" style="border: 1px solid #111111; width: 95%;">
        
        <tr style="display:none ;">
            <td><b>Supplier List : </b></td>
            <td><b> Generated On :</b></td>
			<td><b><?php echo getTime(); echo "("; $username_1=get_field_value("full_name","user","userid",$_SESSION['userId']); echo $username_1; echo ")";?></b></td>
			<td colspan="4"></td>
        </tr>    
       

            <tr >
            <thead class="report-header">
            	<th class="data" width="30px">S.No.</th>
				<th class="data">First Name</th>
				<th class="data">Last Name</th>
				<th class="data">Mobile</th>
				<th class="data">E-Mail</th>
                <th class="data">Current Balance</th>
                <th class="data">No. Of Entries</th>
				<th class="data" width="75px" id="header1">Action</th>
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
						<td class="data"><a href="supplier-ledger.php?cust_id=<?php echo $select_data['cust_id']; ?>" title="View Ledger"  ><?php echo $select_data['fname']; ?></a></td>
						<td class="data"><?php echo $select_data['lname']; ?></td>
						<td class="data"><?php echo $select_data['mobile']; ?></td>
						<td class="data"><?php echo $select_data['email']; ?></td>
                         <td class="data" <?php if(get_total($select_data['cust_id'],strtotime(date("d-m-Y")))<0) { ?> style="color:#FF0000;" <?php } ?>>
						 <?php echo number_format(get_total($select_data['cust_id'],strtotime(date("d-m-Y"))),2);
						 //<?php echo currency_symbol().number_format(get_total($select_data['cust_id'],strtotime(date("d-m-Y"))),2);
                        $get_total = get_total($select_data['cust_id'],strtotime(date("d-m-Y")));
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
                        <td class="data" align="center" width="100px;">
                        
                         <?php 
                         $select_tot = "select SUM(debit) as total_debit,SUM(credit) as total_credit , count(id) as no_entry from payment_plan where description != 'Opening Balance' and cust_id='".$select_data['cust_id']."' and cust_id!='' and cust_id > 0 ";
    $result_tot = mysql_query($select_tot) or die('error in query select pyamentplan query '.mysql_error().$select_tot);
    //$total_tot = mysql_num_rows($result_tot);
      $select_data3 = mysql_fetch_array($result_tot);
                        echo $select_data3['no_entry'];
                        
                        ?>
                        
                        </td>
						<td class="data" width="75px" align="left">
						&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="edit_supplier.php?cust_id=<?php echo $select_data['cust_id']; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                         <?php 
                            if($select_data3['no_entry']<1)
                            { ?>
                    <a href="javascript:account_delete(<?php echo $select_data['cust_id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>&nbsp;&nbsp;&nbsp;
                         <?php    }
                         ?>
				<!--		<a href="javascript:account_delete(<?php echo $select_data['cust_id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>&nbsp;&nbsp;&nbsp; -->
						
						
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
					<td  width="30px" colspan="8" class="record_not_found" >Record Not Found</td>
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
            filename: "Supplier_List",
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
