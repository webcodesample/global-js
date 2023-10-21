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


/*     Create  Account   */


if(trim($_REQUEST['action_perform']) == "add_user")
{
    /*echo '<pre>';
    print_r($_REQUEST);
    exit;*/
    //issuer_name,display_name,email,mobile,address,gst_no,cin_no,pan_no
    //,,email,mobile,address,,,
    $issuer_name=mysql_real_escape_string(trim($_REQUEST['issuer_name']));
    $display_name=mysql_real_escape_string(trim($_REQUEST['display_name']));
    
    $mobile=mysql_real_escape_string(trim($_REQUEST['mobile']));
    $email=mysql_real_escape_string(trim($_REQUEST['email']));
    $address=mysql_real_escape_string(trim($_REQUEST['address']));
    
    $gst_no=mysql_real_escape_string(trim($_REQUEST['gst_no']));
   // $cin_no=base64_encode(mysql_real_escape_string(trim($_REQUEST['cin_no'])));
    $pan_no=mysql_real_escape_string(trim($_REQUEST['pan_no']));
     $cin_no=mysql_real_escape_string(trim($_REQUEST['cin_no']));
     $company_name = mysql_real_escape_string(trim($_REQUEST['company_name']));
    $reg_no=mysql_real_escape_string(trim($_REQUEST['reg_no']));
    $vat_no=mysql_real_escape_string(trim($_REQUEST['vat_no']));
    
    
        $query="insert into invoice_issuer set issuer_name = '".$issuer_name."', display_name = '".$display_name."', mobile = '".$mobile."', email = '".$email."', address = '".$address."', gst_no = '".$gst_no."', cin_no = '".$cin_no."',company_name = '".$company_name."', reg_no = '".$reg_no."', vat_no = '".$vat_no."', pan_no = '".$pan_no."', create_date = '".getTime()."'";
        $result= mysql_query($query) or die('error in query '.mysql_error().$query);
        $msg = "Invoice Issuer added successfully.";
    
    
}

/*     Deletion  Account   */

if($_REQUEST['action_perform'] == "delete_user")
{
    $del_id = $_REQUEST['del_id'];
    $del_query = "delete from invoice_issuer where id = '".$del_id."'";
    //echo $del_query;
    //exit;
    $del_result = mysql_query($del_query) or die("error in delete query ".mysql_error());
    $msg = "user Deleted Successfully.";
    
}
if(mysql_real_escape_string(trim($_REQUEST['search_action'])) == "enddate")
{ 
    $from_date = strtotime(mysql_real_escape_string(trim($_REQUEST['from_date'])));
    
    $to_date = strtotime(mysql_real_escape_string(trim($_REQUEST['to_date'])));

    if($_REQUEST['cust_id']!="All"){
        $customerdata ="and cust_id='".$_REQUEST['cust_id']."'";
    }else { $customerdata=""; }
    
    if($_REQUEST['project_id']!="All"){
        $projectdata ="and project_id='".$_REQUEST['project_id']."'";
    }else { $projectdata=""; }
    
    if($_REQUEST['invoice_id']!="All"){
        $invoice_iddata ="and invoice_id='".$_REQUEST['invoice_id']."'";
    }else { $invoice_iddata=""; }
    
    
    if($from_date!=""){
        $from_datedata ="and payment_date >= '".$from_date."'";
    }else { $from_datedata=""; }
    
    if($to_date!=""){
        $to_datedata ="and payment_date <= '".$to_date."'";
    }else { $to_datedata=""; }
  
    // and payment_date >= '".$from_date."' and payment_date <= '".$to_date."' 
    
    $query = "select *  from goods_details where  invoice_id > '0' ".$customerdata." ".$projectdata."".$invoice_iddata." ".$from_datedata." ".$to_datedata." group by invoice_id ORDER BY invoice_id ASC ";
    
    $result = mysql_query($query) or die('error in query select invoice_issuer query '.mysql_error().$query);
    //$total_row = mysql_num_rows($result);
}
else
{
    $query = "select * from goods_details where invoice_id > '0' group by invoice_id ORDER BY invoice_id ASC ";
    $result = mysql_query($query) or die('error in query select invoice_issuer query '.mysql_error().$query);
    $total_row = mysql_num_rows($result);
    //echo $total_row;
}

$page = $_REQUEST['page'];
if ($page < 1) $page = 1;
$numberOfPages = numberofpages();
$resultsPerPage = resultperpage();
$startResults = ($page - 1) * $resultsPerPage;
$totalPages = ceil($total_row / $resultsPerPage);


if(mysql_real_escape_string(trim($_REQUEST['search_action'])) == "enddate")
{
    $from_date = strtotime(mysql_real_escape_string(trim($_REQUEST['from_date'])));
    
    $to_date = strtotime(mysql_real_escape_string(trim($_REQUEST['to_date'])));

    if($_REQUEST['cust_id']!="All"){
        $customerdata ="and cust_id='".$_REQUEST['cust_id']."'";
    }else { $customerdata=""; }
    
    if($_REQUEST['project_id']!="All"){
        $projectdata ="and project_id='".$_REQUEST['project_id']."'";
    }else { $projectdata=""; }
    
    if($_REQUEST['invoice_id']!="All"){
        $invoice_iddata ="and invoice_id='".$_REQUEST['invoice_id']."'";
    }else { $invoice_iddata=""; }
    
    
    if($from_date!=""){
        $from_datedata ="and payment_date >= '".$from_date."'";
    }else { $from_datedata=""; }
    
    if($to_date!=""){
        $to_datedata ="and payment_date <= '".$to_date."'";
    }else { $to_datedata=""; }
    
    // and payment_date >= '".$from_date."' and payment_date <= '".$to_date."' 
    $select_query  = "select *  from goods_details where  invoice_id > '0' ".$customerdata." ".$projectdata."".$invoice_iddata." ".$from_datedata." ".$to_datedata." group by invoice_id ORDER BY invoice_id ASC ";
    
    
    $select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
    $select_total = mysql_num_rows($select_result);
}
else
{
    $select_query = "select * from goods_details where invoice_id > '0' group by invoice_id ORDER BY invoice_id ASC LIMIT $startResults, $resultsPerPage";
    $select_result = mysql_query($select_query) or die('error in query select user query '.mysql_error().$select_query);
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
  <form name="search_form" id="search_form" action="" method="post" onSubmit="submit();" >
  <input type="hidden" id="print_header" name="print_header" value="Sale Invoice List">
  
  <?php include_once("main_heading_open.php") ?>
    
	<table style="width:100%;" border="0" style="padding:0px 0px; margin-top:-10px;">
    <tr>
        <td style="align:top;" align="left">
        <h4 class="u-text-2 u-text-palette-1-base " style="padding:0px; margin:0px;">
        Sale Invoice List</h4>
  </td>
        <td width="" style="float:right;">
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
    
<input type="hidden" name="gst_subdivision_id" id="gst_subdivision_id" value="<?php echo $_REQUEST['gst_subdivision_id']; ?>">
<table width="" border="1" align="left" cellpadding="0" cellspacing="0">
                <tr>
                
                    <td width="80">
                    &nbsp;&nbsp;From Date
                    </td>
                    <td width="280">
                    <input type="text"  name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>" style="width:250px; height: 25px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('from_date')" style="cursor:pointer"/>
                    
                   <!-- <input type="text" name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>"  readonly="" style="width:120px;" >-->
                 </td>
                
                 <td width="80">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To Date
                    </td>
                    <td width="280">
                    <input type="text"  name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>" style="width:250px; height: 25px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('to_date')" style="cursor:pointer"/>
                  <!--  <input type="text" name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>"  readonly="" style="width:120px;" >-->
                 </td>
                </tr>
                <tr>
                    
                    <td width="80px;">Customer List</td>
                    <td width="280px;">
                        <select name="cust_id" id="cust_id" style="width:250px; height: 25px;">
        <option value="All">All</option>
        <?php 
        $select_search1 = "select cust_id  from goods_details  group BY cust_id ";
        //$select_search1 = "select cust_id  from customer where(type = 'customer')";
    $search_result1 = mysql_query($select_search1) or die('error in query select gst_subdivision query '.mysql_error().$select_search1);
    $select_total1 = mysql_num_rows($search_result1);
        while($search_data1 = mysql_fetch_array($search_result1))
                { 
                    // $project1_nm = get_field_value("name","project","id",$row_series['project_id']); 
                    //$subdivision1_nm = get_field_value("name","subdivision","id",$row_series['subdivision']);  
                    $customer_nm = get_field_value("full_name","customer","cust_id",$search_data1['cust_id']);  
                    ?>
                <option  value="<?php echo $search_data1['cust_id']; ?>"  <?php if($_REQUEST['cust_id']==$search_data1['cust_id']){ echo "selected='selected'"; } ?>><?php echo $customer_nm; ?></option>
              <?php   }
         ?>
    </select>
                    </td>
                    
                    <td width="80">Project List</td>
                    <td width="280px;">
                        <select name="project_id" id="project_id" style="width:250px; height: 25px;">
        <option value="All">All</option>
        <?php 
        $select_search2 = "select project_id  from goods_details  group BY project_id ";
    $search_result2 = mysql_query($select_search2) or die('error in query select gst_subdivision query '.mysql_error().$select_search2);
    $select_total2 = mysql_num_rows($search_result2);
        while($search_data2 = mysql_fetch_array($search_result2))
                {  
                    $project1_nm = get_field_value("name","project","id",$search_data2['project_id']); 
                    ?>
                <option value="<?php echo $search_data2['project_id']; ?>" <?php if($_REQUEST['project_id']==$search_data2['project_id']){ echo "selected='selected'"; } ?>><?php echo $project1_nm; ?></option>
              <?php   }
         ?>
    </select>
                    </td>
                </tr>
                
                <tr>
                    
                    <td width="80">Invoice No</td>
                    <td width="280px;">
                        <select name="invoice_id" id="invoice_id" style="width:250px; height: 25px;">
        <option value="All">All</option>
        <?php 
        $select_search3 = "select invoice_id  from goods_details  group BY invoice_id ";
    $search_result3 = mysql_query($select_search3) or die('error in query select  query '.mysql_error().$select_search3);
    $select_total3 = mysql_num_rows($search_result3);
        while($search_data3 = mysql_fetch_array($search_result3))
                { 
                   // $subdivision1_nm = get_field_value("name","subdivision","id",$search_data3['subdivision']);  
                    ?>
                <option value="<?php echo $search_data3['invoice_id']; ?>" <?php if($_REQUEST['invoice_id']==$search_data3['invoice_id']){ echo "selected='selected'"; } ?> ><?php echo $search_data3['invoice_id']; ?></option>
              <?php   }
         ?>
    </select>
    
                    </td>
                    <td width="80px;"></td>
                    <td width="280px;"> <input type="button" name="search_button1" id="search_button1" value="search " onClick="return search_date();" class="button" >&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='invoice-list.php?gst_subdivision_id=<?php echo $_REQUEST['gst_subdivision_id']; ?>';"  />&nbsp;
                    </td>
                </tr>
                <tr><td colspan="4">&nbsp;</td></tr>
            </table>
            
            
                        <input type="hidden" name="search_action" id="search_action" value=""  />
            <input type="hidden" name="page" id="page" value=""  />

            </form>    
           <form name="user_form" id="user_form" action="" method="post" >
                <input type="hidden" name="action_perform" id="action_perform" value="" >
        <input type="hidden" name="del_id" id="del_id" value="" >
        <input type="hidden" name="count" id="count" value="<?php echo $i; ?>"  />    
        </form>
  <?php include_once("main_search_close.php") ?>
 <!-------------->
  
  <?php include_once("main_body_open.php") ?>

     
    <div id="adddiv" style="display:<?php if($error_msg != "") { ?>block<?php } else { ?>none<?php } ?>;">
    
    
        
        </div>
        
                
        <div id="ledger_data">
        <?php if($msg != "") { ?>
    <div class="sukses">
        <?php echo $msg; ?>
        </div>
    <?php } else if($error_msg != "") { ?>
    <div class="gagal">
        
        <?php echo $error_msg; ?>
        </div>
    <?php } ?>
        
        <table class="data" id="my_table" border="1" cellpadding="1" cellspacing="0" style="border: 1px solid #111111;">
        <tr style="display:none ;">
            <td><b>Sale Invoice List : </b></td>
            <td><b> Generated On :</b></td>
			<td><b><?php echo getTime(); echo "("; $username_1=get_field_value("full_name","user","userid",$_SESSION['userId']); echo $username_1; echo ")";?></b></td>
			<td colspan="4"></td>
        </tr>    
       
            <tr >
            <thead class="report-header">
                <th class="data" width="30px">S.No.</th>
                <th class="data">Invoice. No.</th>
                <th class="data">Print Invoice. No.</th>
                <th class="data">Customer Name</th>
                <th class="data">Issuer Name</th>
                <th class="data">Date </th>
                <th class="data noExl" width="50px" id="header2">&nbsp;</th>
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
                        <td class="data"><?php echo $select_data['invoice_id']; ?></td>
                        <td class="data" align="center">
                        <?php 
                        //code by amit
                        $pin_query = "Select printable_invoice_number from payment_plan where invoice_id='".$select_data['invoice_id']."'";
                        $pin_result = mysql_query($pin_query);
	                    $pin_data = mysql_fetch_assoc($pin_result);

                        if($pin_data['printable_invoice_number'])
                        echo $pin_data['printable_invoice_number'];
                        else
                        echo $select_data['invoice_id'];

                        ?>
                        </td>
                        <td class="data"><?php //echo $select_data['cust_id'];
                        $customer_nm = get_field_value("full_name","customer","cust_id",$select_data['cust_id']); 
                        echo $customer_nm;
                         ?></td>
                        <td class="data"><?php //echo $select_data['issuer_name'];
                        $payment_customer = $select_data['link2_id'];
                        //echo  $payment_customer;
                        $invoice_issuer_id = get_field_value("invoice_issuer_id","payment_plan","id",$payment_customer); 
                         //echo $invoice_issuer_id;
                         $display_name = get_field_value("display_name","invoice_issuer","id",$invoice_issuer_id); 
                         echo $display_name;
                         ?></td>
                        
                        
                        <td class="data" align="center"><?php echo date("d-m-Y",$select_data['payment_date']); ?></td>
                        <td class="data noExl"><center><?php if($select_data['trans_type_name']=="instmulti_sale_goods")
                        {  ?>
                        <a href="edit_instant-sale-invoice_multiple.php?trans_id=<?php echo $select_data['trans_id']; ?>&id=<?php echo $select_data['link2_id']; ?>&trsns_pname=<?php echo "invoice-list-inst-sale-goods"; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } ?>&nbsp;</center></td>
                        <td class="data noExl" width="75px">
                        <center> 
                        
                        <a href="invoice-print.php?invoice_id=<?php echo $select_data['invoice_id']; ?>"><input type="button" name="print_button" id="print_button" value="Print" class="button"   /></a></center>
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
    
    <div id="ledger_data" style="display:none;" >
        <?php 
       //$attach_file_id ='1012';
       
        include_once("invoice-print.php");
         ?>
        <br>
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

        // $('table td').find('td:eq(5)').hide();
         //$("#thead").hide(); 
         //$("td:hidden,th:hidden","#my_table").remove();


        $("#my_table").table2excel({        




            exclude: ".noExl",
            name: "Developer data",
            filename: "Invoice_List",
            fileext: ".xls",        
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true            
        });  
        // $("#thead").show();
    });
   // $('table td').find('td:eq(6)').show();
   
});
function show_records(getno)
{
    //alert(getno);
    document.getElementById("page").value=getno;
    document.search_form.submit(); 
}

function search_date()
{
    //document.getElementById("search_action").value="withoutdate";
    
        $("#search_action").val("enddate");
        $("#search_form").submit();
    
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
 $("#header2").hide();
$('table tr').find('td:eq(5)').hide();
$('table tr').find('td:eq(6)').hide();
newWin.document.write(divToPrint.outerHTML);
newWin.print();
//$('tr').children().eq(7).show();

$('table tr').find('td:eq(5)').show();
$('table tr').find('td:eq(6)').show();
$("#header1").show();
$("#header2").show();
newWin.close();
   
   /* printMe=window.open();
    printMe.document.write(document.getElementById("").innerHTML);
    printMe.print();
    printMe.close();*/
}


</script>
