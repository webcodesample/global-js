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


 if(mysql_real_escape_string(trim($_REQUEST['search_val1'])) == "customer_s_start")
{
            $column = $_REQUEST['customer_search'];
            $pay_from_arr = explode(" -",$_REQUEST['customer_search']);
            $pay_bank_id = get_field_value("cust_id","customer","full_name",$pay_from_arr[0]);
            
            /* PROJECT */
            $column_pro = $_REQUEST['project_search'];
        $pay_from_arr_pro = explode(" -",$_REQUEST['project_search']);
        $pay_bank_id_pro = get_field_value("id","project","name",$pay_from_arr_pro[0]);
            /*  PROJECT */
            
   if($pay_from_arr[0] =="All"){
       $customer_data = "";
   }else{
  //  $select_query = "select * from customer where cust_id=".$pay_bank_id." and type = 'customer' ORDER BY full_name ASC LIMIT $startResults, $resultsPerPage";
    $customer_data ="and cust_id ='".$pay_bank_id."' ";
   }
   
    if($pay_from_arr_pro[0] =="All"){
       $project_data = "";
   }else{
  //  $select_query = "select * from customer where cust_id=".$pay_bank_id." and type = 'customer' ORDER BY full_name ASC LIMIT $startResults, $resultsPerPage";
    $project_data ="and project ='".$pay_bank_id_pro."' ";
   }
    $select_query = "select * from customer where  type = 'customer' and project >0 ".$customer_data." ".$project_data." ORDER BY full_name ASC ";
    $select_result = mysql_query($select_query) or die('error in query select customer query '.mysql_error().$select_query);
    $select_total = mysql_num_rows($select_result);
}
else
{
    $select_query = "select * from customer where type = 'customer' and project !='' and project > 0 ORDER BY full_name ASC ";
  /* $select_query = "select *,payment_plan.id as payment_id  from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id  and payment_plan.cust_id!='' and payment_plan.cust_id > 0 and payment_plan.on_project!='' and payment_plan.on_project > 0 and customer.type='customer'  group by payment_plan.cust_id  ORDER BY customer.full_name ASC ";
 */
    $select_result = mysql_query($select_query) or die('error in query select customer query '.mysql_error().$select_query);
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
//include_once("header.php");
?>

<div id="wrapper" style="">
    <?php
    include_once("leftbar.php");
    ?>
    <div id="rightContent">
    <form name="search_form1" id="search_form1" action="" method="post" onSubmit="return search_valid1();" enctype="multipart/form-data">
         
             <script src="js/datetimepicker_css.js"></script>
            <link rel="stylesheet" href="css/jquery-ui.css" />
             <script src="js/jquery-1.9.1.js"></script>
             <script src="js/jquery-ui.js"></script>
    <h3>Customer Outstanding Report
    
    <span style="float: right;">
    
    <input type="button" name="print_button" id="print_button" value="" class="button_print" onClick="return print_data();"  />
    <script src="dist/jquery.table2excel.min.js"></script>
    <input type="button" id="export_to_excel" value="" class="button_export" >&nbsp;&nbsp;
    </span></h3>
    
<input type="hidden" id="print_header" name="print_header" value="Customer Outstanding Reports</h3>">

    <?php if($msg != "") { ?>
    <div class="sukses">
        <?php echo $msg; ?>
        </div>
    <?php } else if($error_msg != "") { ?>
    <div class="gagal">
        
        <?php echo $error_msg; ?>
        </div>
    <?php } ?>
    <br> <?php
   // echo $select_query;
?>
     <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr> 
                        <td width="450" align="left" valign="top">Search By Project Name
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="text" id="project_search"  name="project_search" value="<?php if($_REQUEST['project_search']!=""){ echo $_REQUEST['project_search']; } else { echo "All";  }?>" style="width:250px;"/></td> 
                        
                        <td align="left" valign="top" >
                        <!--<input type="button" name="search_button2" id="search_button2" value="Search" class="button" onclick="search_project()"  />-->
                        &nbsp;&nbsp;</td>
                        </tr>
              </table>
             
              <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                        <td width="450" align="left" valign="top">Search By Customer Name
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        
                        
                        <input type="text" id="customer_search"  name="customer_search" value="<?php if($_REQUEST['customer_search']!=""){ echo $_REQUEST['customer_search']; } else { echo "All - Customer";  }?>" style="width:250px;"/></td>
                        
                        <td align="left" valign="top" ><input type="button" name="search_button" id="search_button" value="Search" onclick="search_customer()" class="button"  />&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='customer-outstanding-report.php';"  />&nbsp;&nbsp;</td>
                        </tr>
              </table>
       
       <input id="search_val1" name="search_val1" type="hidden" value="0" >
        </form>
        
        <form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" >
           <!-- <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="250" align="left" valign="top">Search By :&nbsp;&nbsp;&nbsp;&nbsp;
                        <select name="search_type" id="search_type" >
                        <option value="" >-- Please Select --</option>
                        <option value="fname" <?php if($_REQUEST['search_type'] == "fname") echo 'selected="selected"'; ?>  >First Name</option>
                        <option value="lname" <?php if($_REQUEST['search_type'] == "lname") echo 'selected="selected"'; ?>  >Last Name</option>
                        <option value="mobile" <?php if($_REQUEST['search_type'] == "mobile") echo 'selected="selected"'; ?>  >Mobile</option>
                        
                        
                        </select>
                  </td>
                    <td width="180" align="left" valign="top"><input type="text" name="search_text" id="search_text" value="<?php echo mysql_real_escape_string(trim($_REQUEST['search_text'])); ?>" /></td>
                    
                    <td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />
                   
                   
                    </td>
                    
                    <td align="right" valign="top">
          
     
                    </td>
                </tr>
            </table> -->
            <input type="hidden" name="page" id="page" value=""  />
            </form>
        
        
<div id="ledger_data">
        <table class="data" id="my_table" border="1" cellpadding="1" cellspacing="0" style="border: 1px solid #111111; width: 95%;">
        
            <tr >
            <thead class="report-header">
                <th class="data" width="30px" >S.No.</th>
                <th class="data">Customer Name</th>
                <th class="data">Associated Project</th>
                <th class="data">Last Invoice Date</th>
                <th class="data"> Last Payment received date</th>
                <th class="data">Current Outstanding Due amount</th>
                
                
                 </thead>
            </tr>
            <?php
            if($select_total > 0)
            {
                $i=1;
                while($select_data = mysql_fetch_array($select_result))
                {
                    if($_REQUEST['project_search']!="")
                    {
                        $column1 = $_REQUEST['project_search'];
                        $pay_from_arr1 = explode(" -",$_REQUEST['project_search']);
                        $pay_bank_id1 = get_field_value("id","project","name",$pay_from_arr1[0]);
                        //payment_plan.on_project='".$pay_bank_id."'   
                        $pro_val= "and on_project='".$pay_bank_id."'";
                    }else
                    {
                        $pro_val="";
                    }
                    
                    $ii=$i+$startResults;    
                     
                     ?>
                     
                    <tr class="data">
                        <td class="data" width="30px" align="center"><?php echo $ii ?></td>
                        <td class="data">&nbsp;<?php 
                        //echo $select_data['fname'];
                        echo get_field_value("full_name","customer","cust_id",$select_data['cust_id']);
                         ?></td>
                        
                        <td class="data">&nbsp;<?php //echo $select_data['mobile'];
                        echo get_field_value("name","project","id",$select_data['project']);
                        
                         ?></td>
                        <td class="data" align="center">&nbsp;<?php //echo date("d-m-Y",$select_data_pro['maxpayment_date']);   ?>
                        <?php 
                        $select_query_pay1 = "select max(payment_date) as payment_max_date from payment_plan  where cust_id = '".$select_data['cust_id']."'  and  on_project ='".$select_data['project']."' and trans_type_name in('receive_goods','sale_goods','internal_transfer','inst_receive_goods','inst_sale_goods','instmulti_sale_goods')   ";
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
                        $select_query_pay = "select max(payment_date) as payment_max_date from payment_plan  where cust_id = '".$select_data['cust_id']."'  and  on_project ='".$select_data['project']."' and trans_type_name in('make_payment','receive_payment','loanadvance_makepayment','loanadvance_receivepayment','inst_make_payment','inst_receive_payment','instmulti_receive_payment')   ";
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
                         $select_query_pro = "select id as payment_id ,SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan  where cust_id = '".$select_data['cust_id']."'  and  on_project ='".$select_data['project']."'   ";
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
            filename: "Customer_outstanding_reports",
            fileext: ".xls",        
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true            
        });  
        // $("#thead").show();
    });
   $( "#customer_search" ).autocomplete({
            source: "customer1-ajax.php"
        });
   $( "#project_search" ).autocomplete({
            source: "project1-ajax.php"
        });      
   
});


/*
    $(document).ready(function(){
        $( "#customer_search" ).autocomplete({
            source: "customer1-ajax.php"
        });
        
       
        
    }) */
    </script>

<script>


function add_div()
{
    $("#adddiv").toggle("slow");
}
function search_project()
{
    document.getElementById("search_val1").value="0";
    //project_search,customer_search,search_form1,search_val1
    if(document.getElementById("project_search").value=="")
    {
        alert("Please enter Search Project Name");
        document.getElementById("project_search").focus();
     
    } else
    {
        document.getElementById("search_val1").value="project_s_start";
        document.search_form1.submit();     
    }
    
}
function search_customer()
{   
    document.getElementById("search_val1").value="0";
    if(document.getElementById("project_search").value=="")
    {
        alert("Please enter Search Project Name");
        document.getElementById("project_search").focus();
     
    } else if(document.getElementById("customer_search").value=="")
    {
        alert("Please enter Search Customer Name");
        document.getElementById("customer_search").focus();
     
    } else
    {    
        document.getElementById("search_val1").value="customer_s_start";
        document.search_form1.submit(); 
    }
    /*document.getElementById("search_val1").value="customer_s_start";
        document.search_form1.submit(); */
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
