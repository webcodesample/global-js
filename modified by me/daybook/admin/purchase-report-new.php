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
/*
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
    $query = "select *  from goods_details where  invoice_id > '0' ".$customerdata." ".$projectdata."".$invoice_iddata." ".$from_datedata." ".$to_datedata." group by invoice_id ORDER BY invoice_id ASC ";
    
    $result = mysql_query($query) or die('error in query select invoice_issuer query '.mysql_error().$query);
}
else
{
    $query = "select * from goods_details where invoice_id > '0' group by invoice_id ORDER BY invoice_id ASC ";
    $result = mysql_query($query) or die('error in query select invoice_issuer query '.mysql_error().$query);
    $total_row = mysql_num_rows($result);

}
*/

if(mysql_real_escape_string(trim($_REQUEST['search_action'])) == "enddate")
{
    $from_date = strtotime(mysql_real_escape_string(trim($_REQUEST['from_date'])));
    
    $to_date = strtotime(mysql_real_escape_string(trim($_REQUEST['to_date'])));
//subdivision_id
    if($_REQUEST['cust_id']!="All"){
        $customerdata ="and cust_id='".$_REQUEST['cust_id']."'";
    }else { $customerdata=""; }
    
    if($_REQUEST['project_id']!="All"){
        $projectdata ="and on_project='".$_REQUEST['project_id']."'";
    }else { $projectdata=""; }
    
    if($_REQUEST['invoice_id']!="All"){
        $invoice_iddata ="and trans_id='".$_REQUEST['invoice_id']."'";
    }else { $invoice_iddata=""; }
    
    if($_REQUEST['subdivision_id']!="All"){
        $subdivision_iddata ="and subdivision='".$_REQUEST['subdivision_id']."'";
    }else { $subdivision_iddata=""; }
    

    if($from_date!=""){
        $from_datedata ="and payment_date >= '".$from_date."'";
    }else { $from_datedata=""; }
    
    if($to_date!=""){
        $to_datedata ="and payment_date <= '".$to_date."'";
    }else { $to_datedata=""; }
    
   // $select_query  = "select *  from goods_details where  invoice_id > '0' ".$customerdata." ".$subdivision_iddata."".$projectdata."".$invoice_iddata." ".$from_datedata." ".$to_datedata."  ORDER BY invoice_id ASC ";
    $select_query = "select * from payment_plan where trans_id > 0 and trans_type_name in('receive_goods','inst_receive_goods') ".$customerdata." ".$subdivision_iddata." ".$projectdata."".$invoice_iddata." ".$from_datedata." ".$to_datedata." group by trans_id ORDER BY payment_date ASC "; 
    $select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
    $select_total = mysql_num_rows($select_result);

    $select_query_sum = "select sum(gst_amount) as tot_gst_amount, sum(debit) as tot_debit from payment_plan where trans_id > 0 and trans_type_name in('receive_goods','inst_receive_goods') ".$customerdata." ".$subdivision_iddata." ".$projectdata."".$invoice_iddata." ".$from_datedata." ".$to_datedata." "; 
    $select_result_sum = mysql_query($select_query_sum) or die('error in query select bank query '.mysql_error().$select_query_sum);
   // $select_total_sum = mysql_num_rows($select_result_sum);

}
else
{
 //    $select_query = "select * from goods_details where invoice_id > '0'  ORDER BY invoice_id ASC ";
        $select_query = "select * from payment_plan where trans_id > '0' and cust_id!='' and cust_id > 0 and trans_type_name in('receive_goods','inst_receive_goods') group by trans_id ORDER BY payment_date ASC ";
        $select_result = mysql_query($select_query) or die('error in query select user query '.mysql_error().$select_query);
        $select_total = mysql_num_rows($select_result);

        $select_query_sum = "select sum(gst_amount) as tot_gst_amount,sum(debit) as tot_debit from payment_plan where trans_id > '0' and cust_id!='' and cust_id > 0 and trans_type_name in('receive_goods','inst_receive_goods')  ";
        $select_result_sum = mysql_query($select_query_sum) or die('error in query select user query '.mysql_error().$select_query_sum);
        $select_total_sum = mysql_num_rows($select_result_sum);
        //echo $select_total_sum;
        //SUM(debit) as total_debit,SUM(credit) as total_credit
        //sum(gst_amount) as tot_gst_amount , sum(debit) as tot_debit
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
        Purchase Reports </h4>
  </td>
        <td width="" style="float:right;">
        <input type="button" name="print_button" id="print_button" value="" class="button_print" onClick="return print_data();"  />
    <script src="dist/jquery.table2excel.min.js"></script>
    <input type="button" id="export_to_excel" value="" class="button_export" >
    <input type="hidden" id="print_header" name="print_header" value="Purchase Reports">
    <input type="button" id="search" value="" class="button_search1" onClick="search_display();" >

    </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>

<!-------------->
<?php include_once("main_search_open.php") ?>
  
  <form name="search_form" id="search_form" action="" method="post"  >
  <input type="hidden" name="search_check_val" id="search_check_val" value="0" >
       
       <div id="adddiv" style="display:<?php if($error_msg != "") { ?>block<?php } else { ?>none<?php } ?>;">
       
       
           
           </div>
           
        
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
           $select_search1 = "select cust_id  from payment_plan where trans_type_name in('receive_goods','inst_receive_goods') and cust_id>0  group BY cust_id ";
     
         //  $select_search1 = "select cust_id  from goods_details  group BY cust_id ";
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
            $select_search2 = "select on_project  from payment_plan where trans_type_name in('receive_goods','inst_receive_goods') and on_project>0 group BY on_project ";
       
         //  $select_search2 = "select project_id  from goods_details  group BY project_id ";
       $search_result2 = mysql_query($select_search2) or die('error in query select gst_subdivision query '.mysql_error().$select_search2);
       $select_total2 = mysql_num_rows($search_result2);
           while($search_data2 = mysql_fetch_array($search_result2))
                   {  
                       $project1_nm = get_field_value("name","project","id",$search_data2['on_project']); 
                       ?>
                   <option value="<?php echo $search_data2['on_project']; ?>" <?php if($_REQUEST['project_id']==$search_data2['on_project']){ echo "selected='selected'"; } ?>><?php echo $project1_nm; ?></option>
                 <?php   }
            ?>
       </select>
                       </td>
                   </tr>
                   
                   <tr>
                       
                       <td width="80">Trans No</td>
                       <td width="280px;">
                           <select name="invoice_id" id="invoice_id" style="width:250px; height: 25px;">
           <option value="All">All</option>
           <?php 
           //$select_search3 = "select invoice_id  from goods_details  group BY invoice_id ";
           $select_search3 = "select trans_id  from payment_plan where trans_type_name in('receive_goods','inst_receive_goods') group BY trans_id   ";
       
           $search_result3 = mysql_query($select_search3) or die('error in query select  query '.mysql_error().$select_search3);
       $select_total3 = mysql_num_rows($search_result3);
           while($search_data3 = mysql_fetch_array($search_result3))
                   { 
                      // $subdivision1_nm = get_field_value("name","subdivision","id",$search_data3['subdivision']);  
                       ?>
                   <option value="<?php echo $search_data3['trans_id']; ?>" <?php if($_REQUEST['invoice_id']==$search_data3['trans_id']){ echo "selected='selected'"; } ?> ><?php echo $search_data3['trans_id']; ?></option>
                 <?php   }
            ?>
       </select>
       
                       </td>
                       <td width="80px;">Subdivision</td>
                       <td width="280px;">
                       <select name="subdivision_id" id="subdivision_id" style="width:250px; height: 25px;">
                           <option value="All">All</option>
                           <?php
                           $select_search3 = "select subdivision  from payment_plan where trans_type_name in('receive_goods','inst_receive_goods') and subdivision>0  group BY subdivision ";
        
          // $select_search3 = "select subdivision  from goods_details  group BY subdivision ";
       $search_result3 = mysql_query($select_search3) or die('error in query select  query '.mysql_error().$select_search3);
       $select_total3 = mysql_num_rows($search_result3);
           while($search_data3 = mysql_fetch_array($search_result3))
                   { 
                    $subdivision1_nm = get_field_value("name","subdivision","id",$search_data3['subdivision']);  
                       ?>
                   <option value="<?php echo $search_data3['subdivision']; ?>" <?php if($_REQUEST['subdivision_id']==$search_data3['subdivision']){ echo "selected='selected'"; } ?> ><?php echo $subdivision1_nm; ?></option>
                 <?php   }
            ?>
                       </select>
                            </td>
                   </tr>
                   <tr><td colspan="4" align="right"><input type="button" name="search_button1" id="search_button1" value="Search " onClick="return search_date();" class="button" >&nbsp;<input type="button" name="refresh" id="refresh" value="Reset" class="button" onClick="window.location='purchase-report-new.php';"  />&nbsp;
                       </td></tr>
               </table>
               
                           <input type="hidden" name="search_action" id="search_action" value=""  />
               <input type="hidden" name="page" id="page" value=""  />
   
               </form>    
              
  <?php include_once("main_search_close.php") ?>
 <!-------------->
  
  <?php include_once("main_body_open.php") ?>
  <form name="user_form" id="user_form" action="" method="post" >
                <input type="hidden" name="action_perform" id="action_perform" value="" >
        <input type="hidden" name="del_id" id="del_id" value="" >
        <input type="hidden" name="count" id="count" value="<?php echo $i; ?>"  />    
        </form>
        <table width="99%" style="padding:15px;">
            <tr><td>
        <div style="color:red; align:right; font-size:15;" align="right">
                <b>No of Records : <?php echo $select_total; ?></b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </div>
              
        <?php if($msg != "") { ?>
    <div class="sukses">
        <?php echo $msg; ?>
        </div>
    <?php } else if($error_msg != "") { ?>
    <div class="gagal">
        
        <?php echo $error_msg; ?>
        </div>
    <?php } ?>   
        <div id="ledger_data" style="height: 300px; width:98%; overflow-y: scroll;">
        <table class="data" id="my_table" border="1" cellpadding="1" cellspacing="0" style="border: 1px solid #111111;">
        
        <tr style="display:none ;">
            <td colspan="10" align="center">Purchase Reports :</td>
        </tr>
        
    <tr style="display:none ;">
            <td colspan="10" align="center">Reports Generated Date & Time : <?php echo getTime(); ?></td>
    </tr>
            <tr >
            <thead class="report-header">
                <th class="data" width="30px" style="width=30px; position: sticky; top: 0;">S.No.</th>
                <th class="data" style="width=80px; position: sticky; top: 0;">Date </th>
                <th class="data" style="width=80px; position: sticky; top: 0;">Trans. No.</th>
                <th class="data" style="width=120px; position: sticky; top: 0;">Project Name </th>
                <th class="data" style="width=120px; position: sticky; top: 0;">Supplier Name</th>
                <th class="data" style="width=120px; position: sticky; top: 0; display:none;">Supplier GST No.</th>
                <th class="data" style="width=120px; position: sticky; top: 0;">Subdivision Name</th>
                <th class="data" style="width=120px; position: sticky; top: 0;">Description</th>
                <th class="data" style="width=80px; position: sticky; top: 0;">Sub-Total</th>
                <th class="data" style="width=80px; position: sticky; top: 0;">GST%</th>
                <th class="data" style="width=80px; position: sticky; top: 0;">GST</th>
                <th class="data" style="width=80px; position: sticky; top: 0;">Total Amount</th>
                </thead>
            </tr>
            <?php
            if($select_total > 0)
            {
                $select_data_sum = mysql_fetch_array($select_result_sum);
                //sum(gst_amount) as tot_gst_amount,sum(debit) as tot_debit
                ?>
                <tr class="data">
                    <td class="data" style="width=80px; position: sticky; top: 30; backgroung:0;" bgcolor="white"></td>
                    <td class="data" style="width=80px; position: sticky; top: 30;" bgcolor="white"></td>
                    <td class="data" style="width=80px; position: sticky; top: 30;" bgcolor="white"></td>
                    <td class="data" style="width=80px; position: sticky; top: 30;" bgcolor="white"></td>
                    <td class="data" style="width=80px; position: sticky; top: 30;" bgcolor="white"></td>
                    
                    <td class="data" style="width=80px; position: sticky; top: 30; display:none;" bgcolor="white"></td>
                    <td class="data" style="width=80px; position: sticky; top: 30;" bgcolor="white"></td>
                    <td class="data" style="width=80px; position: sticky; top: 30;" bgcolor="white"><b>Total</b></td>
                    <td class="data" style="width=80px; position: sticky; top: 30;" bgcolor="white"><?php $subtot_tot = $select_data_sum['tot_debit']-$select_data_sum['tot_gst_amount']; echo number_format((float)$subtot_tot, 2,'.',''); ?></td>
                    
                    <td class="data" style="width=80px; position: sticky; top: 30;" bgcolor="white"></td>
                    <td class="data" style="width=80px; position: sticky; top: 30;" bgcolor="white"><?php echo number_format((float)$select_data_sum['tot_gst_amount'], 2,'.',''); ?></td>
                    <td class="data" style="width=80px; position: sticky; top: 30;" bgcolor="white"><?php echo number_format((float)$select_data_sum['tot_debit'], 2,'.',''); ?></td>
                </tr>
                <?php
                $i=1;
                while($select_data = mysql_fetch_array($select_result))
                {
                    $ii=$i+$startResults;    
                     ?>
                    <tr class="data">
                        <td class="data" width="30px"><?php echo $ii; ?></td>
                        <td class="data" align="center"><?php echo date("d-m-Y",$select_data['payment_date']); ?></td>
                        <td class="data"><?php echo $select_data['trans_id']; ?></td>
                        <td class="data"><?php $project_nm = get_field_value("name","project","id",$select_data['on_project']);  echo $project_nm; ?></td>
                        <td class="data"><?php //echo $select_data['cust_id'];
                        $customer_nm = get_field_value("full_name","customer","cust_id",$select_data['cust_id']); 
                        echo $customer_nm;
                         ?></td>
                         <td class="data" style="display:none;"><?php //echo $select_data['cust_id'];
                        $gst_nm = get_field_value("supply_gst_no","customer","cust_id",$select_data['cust_id']); 
                        echo $gst_nm;
                         ?></td>
                         <td class="data" align="center"><?php $subdivision_nm = get_field_value("name","subdivision","id",$select_data['subdivision']);  echo $subdivision_nm; ?></td>
                         <td class="data" align="center"><?php echo $select_data['description']; ?></td>
                         <td class="data" align="center"><?php $subtot = $select_data['debit']-$select_data['gst_amount']; echo number_format((float)$subtot, 2,'.',''); ?></td>
                         <td class="data" align="center"><?php 
                         $tot_gst =($select_data['gst_amount']/$subtot)*100;
                         echo $tot_gst."%";
                         //echo number_format((float)$tot_gst, 2,'.',''); 
                         ?></td>
                         
                         <td class="data" align="center"><?php echo number_format((float)$select_data['gst_amount'], 2,'.',''); ?></td>
                         <td class="data" align="center"><?php echo number_format(floatval($select_data['debit']),2,'.','');?></td>
                        </tr>
                <?php
                    $i++;
                }
                
            }
            else
            {
                ?>
                <tr class="data" >
                    <td  width="30px" colspan="16" class="record_not_found" >Record Not Found</td>
                </tr>
                <?php
            }
            ?>
            
        </table>
        </div>
        
        </td></tr></table>
    
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
            filename: "Purchase_report",
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
    
        document.getElementById("search_action").value = "enddate";
		
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

// $("#header1").hide();
// $("#header2").hide();
//$('table tr').find('td:eq(5)').hide();
//$('table tr').find('td:eq(6)').hide();
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















