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

echo $cutoff_date = strtotime("1 september 2023");

if(mysql_real_escape_string(trim($_REQUEST['search_action'])) == "ledger_search")
{
    $from_date = strtotime(mysql_real_escape_string(trim($_REQUEST['from_date'])));
    
    $to_date = strtotime(mysql_real_escape_string(trim($_REQUEST['to_date'])));

    if($_REQUEST['company_name'])
    {
        $invoice_rcvr = explode(" - ",$_REQUEST['company_name']);
        $querypart_rcvr = "and invoice_issuer_id='".$invoice_rcvr[1]."'";
    }
    elseif($_REQUEST['c_gstin'])
    {
        $invoice_rcvr = explode(" - ",$_REQUEST['c_gstin']);
        $querypart_rcvr = "and invoice_issuer_id='".$invoice_rcvr[1]."'";
    }
    else{$querypart_rcvr = "";}

    
    if($from_date!="")
    {
        $from_datedata ="and payment_date >= '".$from_date."'";
        $from_dt = "From : ".$_REQUEST['from_date'];
    }else { $from_datedata ="and payment_date >= '".$cutoff_date."'";}
    
    if($to_date!=""){
        $to_datedata ="and payment_date <= '".$to_date."'";
        $to_dt = " To : ".$_REQUEST['to_date'];
    }else { $to_datedata=""; }

    

    //purchase return queries
    $select_query = "select * from payment_plan where trans_id > 0 and trans_type_name in('receive_goods','inst_receive_goods') ".$querypart_rcvr."".$from_datedata." ".$to_datedata." group by trans_id ORDER BY payment_date ASC";
    $select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
    $select_total = mysql_num_rows($select_result);

    $paid_amout_query = "SELECT SUM(invoice_pay_amount) AS amount_paid, SUM(gst_amount) AS gst_paid FROM payment_plan where trans_id > '0' and trans_type_name in('receive_goods','inst_receive_goods')".$querypart_rcvr.$from_datedata.$to_datedata;
    $paid_amout_result = mysql_query($paid_amout_query) or die('error in query select user query '.mysql_error().$paid_amout_query);
    $paid_amount = mysql_fetch_array($paid_amout_result);

    //sales return queries
    $sales_query = "select * from payment_plan where trans_id > 0 and trans_type_name='instmulti_sale_goods' ".$querypart_rcvr."".$from_datedata." ".$to_datedata." group by trans_id ORDER BY payment_date ASC";
    $sales_result = mysql_query($sales_query) or die('error in query select bank query '.mysql_error().$sales_query);
    $sales_total_row = mysql_num_rows($sales_result);

    $rcvd_amout_query = "SELECT SUM(invoice_pay_amount) AS amount_paid, SUM(gst_amount) AS gst_paid FROM payment_plan where trans_id > '0' and trans_type_name='instmulti_sale_goods' ".$querypart_rcvr.$from_datedata.$to_datedata;
    $rcvd_amout_result = mysql_query($rcvd_amout_query) or die('error in query select user query '.mysql_error().$rcvd_amout_query);
    $rcvd_amount = mysql_fetch_array($rcvd_amout_result);
}
else
{
    //purchase queries
    $select_query = "select * from payment_plan where trans_id > '0' and payment_date >= '".$cutoff_date."' and trans_type_name in('receive_goods','inst_receive_goods') group by trans_id ORDER BY payment_date ASC";
    $select_result = mysql_query($select_query) or die('error in query select user query '.mysql_error().$select_query);
    $select_total = mysql_num_rows($select_result);

    $paid_amout_query = "SELECT SUM(invoice_pay_amount) AS amount_paid, SUM(gst_amount) AS gst_paid FROM payment_plan where trans_id > '0' and trans_type_name in('receive_goods','inst_receive_goods') and payment_date >= '".$cutoff_date."'";
    $paid_amout_result = mysql_query($paid_amout_query) or die('error in query select user query '.mysql_error().$paid_amout_query);
    $paid_amount = mysql_fetch_array($paid_amout_result);
    
    //sales return queries
    $sales_query = "select * from payment_plan where trans_id > 0 and payment_date >= '".$cutoff_date."' and trans_type_name='instmulti_sale_goods' group by trans_id ORDER BY payment_date ASC";
    $sales_result = mysql_query($sales_query) or die('error in query select bank query '.mysql_error().$sales_query);
    $sales_total_row = mysql_num_rows($sales_result);

    $rcvd_amout_query = "SELECT SUM(invoice_pay_amount) AS amount_paid, SUM(gst_amount) AS gst_paid FROM payment_plan where trans_id > '0' and trans_type_name='instmulti_sale_goods' and payment_date >= '".$cutoff_date."'";
    $rcvd_amout_result = mysql_query($rcvd_amout_query) or die('error in query select user query '.mysql_error().$rcvd_amout_query);
    $rcvd_amount = mysql_fetch_array($rcvd_amout_result);
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

<body  data-home-page-title="" class="u-body u-xl-mode" data-lang="en" onload="set_option()">
  <?php include_once ("top_header2.php"); ?> 
  <?php include_once ("top_menu.php"); ?>
  <?php include_once("main_heading_open.php") ?>
  
<table style="width:100%;" border="0" style="padding:0px 0px; margin-top:-10px;">
    <tr>
        <td style="align:top;" align="left">
        <h4 class="u-text-2 u-text-palette-1-base " style="padding:0px; margin:0px;">GST Retrun</h4>
        </td>
        <td width="" style="float:right;">
        <input type="button" name="print_button" id="print_button" value="" class="button_print" onClick="return print_data();">
        <script src="dist/jquery.table2excel.min.js"></script>
        <input type="button" id="export_to_excel" value="" class="button_export" >&nbsp;&nbsp;
        <input type="hidden" id="print_header" name="print_header" value="GST Return">
        <input type="button" id="search" value="" class="button_search1" onClick="search_display();">
        </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
<?php include_once("main_search_open.php") ?>

<form name="search_form" id="search_form" action="" method="post">
  <input type="hidden" name="search_check_val" id="search_check_val" value="<?= $_REQUEST['search_check_val']?>" >
     
<input type="hidden" name="gst_subdivision_id" id="gst_subdivision_id" value="<?php echo $_REQUEST['gst_subdivision_id']; ?>">
            <table width="" border="1" align="left" cellpadding="0" cellspacing="0">
                <tr>
                
                    <td width="80">From Date</td>
                    <td width="280">
                    <input type="text"  name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>" style="width:250px; height: 25px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('from_date')" style="cursor:pointer"/>
                    
                   <!-- <input type="text" name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>"  readonly="" style="width:120px;" >-->
                 </td>
                
                 <td width="80">To Date</td>
                    <td width="280">
                    <input type="text"  name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>" style="width:250px; height: 25px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('to_date')" style="cursor:pointer"/>
                 </td>

                 <td colspan="2" align="center">
                 <input type="button" name="search_button1" id="search_button1" value="Search" onClick="search_date();" class="button" >
                 </td>
                </tr>

                <tr>
                    <td width="80px;">Company</td>
                    <td width="280px;">
                        <input type="text" name="company_name" id="company_name" onblur="set_option()" value="<?= $_REQUEST['company_name'] ?>" style="width:250px; height: 25px;">
                    </td>
                    
                    <td width="80">GSTIN</td>
                    <td width="280px;">
                        <input type="text" name="c_gstin" id="c_gstin" onblur="set_option()" value="<?= $_REQUEST['c_gstin'] ?>" style="width:250px; height: 25px;">
                    </td>

                 <td colspan="2" align="center">
                 <input type="reset" name="refresh" id="refresh" value="Refresh" class="button">&nbsp;
                 </td>
                    
                </tr>
            </table>
            
            
                        <input type="hidden" name="search_action" id="search_action" value="<?= $_REQUEST['search_action'] ?>"  />
                        <input type="hidden" name="page" id="page" value=""/>
                        <input type="hidden" name="rpp" id="rpp" value="<?= $_REQUEST['rpp']?>"/>
            </form>    
  
  <?php include_once("main_search_close.php") ?>
 <!-------------->
  
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
    </div>
        

        <form name="user_form" id="user_form" action="" method="post" >
        <input type="hidden" name="action_perform" id="action_perform" value="" >
        <input type="hidden" name="del_id" id="del_id" value="" >
        <input type="hidden" name="count" id="count" value="<?php echo $i; ?>"  />    
        </form>

        <div id="ledger_data" style="width:98%; padding-right: 10px;">

        <div style="width:100%; font-weight:bold; color:#800000; font-size:13px; text-align:center;">
        <?php
        if($_REQUEST['search_action'])
        {
            $company_name = get_field_value("company_name","invoice_issuer","id",$invoice_rcvr[1]);
            $company_gstin = $invoice_rcvr[2];

            if($_REQUEST['company_name']!=='' || $_REQUEST['c_gstin']!=='')
                echo "GST Return for ".$company_name." ( GSTIN : ".$company_gstin." )<br>";
            if($_REQUEST['to_date']!=='' || $_REQUEST['from_date']!=='')
                echo $from_dt.$to_dt;
        }
        else 
        {
	    echo "GST Return : All";
        }
        ?>
        </div>

        <table class="data" id="my_table" border="1" cellpadding="1" cellspacing="0" style="border: 1px solid #111111;">
        <tr class="data">
            <td class="data" colspan="9" nowrap>
            <b>GST Purchase Return: Generated On :
            <?php echo getTime(); echo " ( By : "; $username_1=get_field_value("full_name","user","userid",$_SESSION['userId']); echo $username_1; echo " )";?>
            </b>
            </td>
        </tr>    
            <tr align="center">
                <th class="data" width="30px" nowrap>S.No.</th>
                <th class="data" width="75px" nowrap>Date</th>
                <th class="data" nowrap>Invoice Id</th>
                <th class="data" nowrap>Supplier Name</th>
                <th class="data" nowrap>Supplier GSTIN</th>
                <th class="data" nowrap>Amount (excl. GST)</th>
                <th class="data" nowrap>GST Amount</th>
                <th class="data" nowrap>GST Rate</th>
                <th class="data" width="50px" nowrap>Total Amount</th>
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
                        <td class="data" width="30px" align="center" nowrap><?php echo $ii; ?></td>
                        <td class="data " align="center" nowrap><?php echo date("d-m-Y",$select_data['payment_date']); ?></td>
                        
                        <td class="data" nowrap>
                        <?php
                            if($select_data['invoice_id'])
                            echo $select_data['invoice_id'];
                        ?>
                        </td>
                        <td class="data" nowrap>
                        <?php //echo $select_data['cust_id'];
                        $customer_nm = get_field_value("full_name","customer","cust_id",$select_data['cust_id']); 
                        echo $customer_nm;
                         ?>
                         </td>
                         <td class="data" nowrap><?php //echo $select_data['cust_id'];
                        $gst_nm = get_field_value("supply_gst_no","customer","cust_id",$select_data['cust_id']); 
                        echo $gst_nm;
                         ?></td>

                        <td class="data" nowrap>&#8377;&nbsp;
                        <?php 
                            $subtot= $select_data['debit']-$select_data['gst_amount'];
                            echo number_format((float)$subtot, 2,'.','');
                        ?>
                        </td>
                        <td style="" class="data" nowrap>&#8377;&nbsp;
                        <?php 
                        if($select_data['gst_amount']>1)
                        {
                         echo number_format((float)$select_data['gst_amount'], 2,'.','');
                        }
                        ?>
                        </td>
                        <td class="data" nowrap>
                        <?php
                        if($select_data['gst_amount']>1)
                        {
                            $subtot = $select_data['debit']-$select_data['gst_amount'];
                            $gstper = ($select_data['gst_amount']/$subtot)*100;
                            echo ceil($gstper).'%';
                        }
                        ?>
                        </td>
                        <td class="data" nowrap style="color:red; text-align:justify; text-justify:inter-word;">
                        &#8377;&nbsp;
                        <?php echo number_format(floatval($select_data['debit']),2,'.',''); ?>
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
                    <td align="center" colspan="14" class="record_not_found" >Sorry! No Record Available</td>
                </tr>
                <?php
            }
            ?>
            
        <tr class="data" style="font-weight:bold;">
        <td class="data" colspan="5" align="right" style="color:#800000;">
        Total Paid
        </td>
        <td class="data" style="color:#800000;">&#8377;&nbsp;
        <?php echo number_format(floatval($paid_amount['amount_paid']/2),2,'.',''); ?>
        </td>
        <td class="data" style="color:#800000;">&#8377;&nbsp;
        <?php echo number_format(floatval($paid_amount['gst_paid']/2),2,'.',''); ?>
        </td>
        <td colspan="2" class="data"></td>
        </tr>
        <tr class="data"><td colspan="9" class="data">&nbsp;</td></tr>
        <tr class="data">
            <td class="data" colspan="9" nowrap>
            <b>GST Sale Return: Generated On :
            <?php echo getTime(); echo " ( By : "; $username_1=get_field_value("full_name","user","userid",$_SESSION['userId']); echo $username_1; echo " )";?>
            </b>
            </td>
        </tr>    
            <tr align="center">
                <th class="data" width="30px" nowrap>S.No.</th>
                <th class="data" width="75px" nowrap>Date</th>
                <th class="data" nowrap>Invoice Id</th>
                <th class="data" nowrap>Customer Name</th>
                <th class="data" nowrap>Customer GSTIN</th>
                <th class="data" nowrap>Amount (excl. GST)</th>
                <th class="data" nowrap>GST Amount</th>
                <th class="data" nowrap>GST Rate</th>
                <th class="data" width="50px" nowrap>Total Amount</th>
            </tr>

            <?php
            if($sales_total_row > 0)
            {
                $i=1;
                while($sales_data = mysql_fetch_array($sales_result))
                {
                    $ii=$i+$startResults;

                     ?>
                    <tr class="data">
                        <td class="data" width="30px" align="center" nowrap><?php echo $ii; ?></td>
                        <td class="data " align="center" nowrap><?php echo date("d-m-Y",$sales_data['payment_date']); ?></td>
    
                        <td class="data" nowrap>
                        <?php
                            if($sales_data['printable_invoice_number'])
                            echo $sales_data['printable_invoice_number'];
                            else
                            echo $sales_data['invoice_id'];
                        ?>
                        </td>
                        <td class="data" nowrap>
                        <?php 
                        $customer_nm = get_field_value("full_name","customer","cust_id",$sales_data['cust_id']); 
                        echo $customer_nm;
                         ?>
                         </td>
                         <td class="data" nowrap><?php 
                        $gst_nm = get_field_value("client_gst","customer","cust_id",$sales_data['cust_id']); 
                        echo $gst_nm;
                         ?></td>

                        <td class="data" nowrap>&#8377;&nbsp;
                        <?php 
                            $subtot= $sales_data['credit']-$sales_data['gst_amount'];
                            echo number_format((float)$subtot, 2,'.','');
                        ?>
                        </td>
                        <td style="" class="data" nowrap>&#8377;&nbsp;
                        <?php 
                        if($sales_data['gst_amount']>1)
                        {
                         echo number_format((float)$sales_data['gst_amount'], 2,'.','');
                        }
                        ?>
                        </td>
                        <td class="data" nowrap>
                        <?php
                        if($sales_data['gst_amount']>1)
                        {
                            $subtot = $sales_data['credit']-$sales_data['gst_amount'];
                            $gstper = ($sales_data['gst_amount']/$subtot)*100;
                            echo ceil($gstper).'%';
                        }
                        ?>
                        </td>
                        <td class="data" nowrap style="color:red; text-align:justify; text-justify:inter-word;">
                        &#8377;&nbsp;
                        <?php echo number_format(floatval($sales_data['credit']),2,'.',''); ?>
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
                    <td width="30px" colspan="9" class="record_not_found" align="center">No Record Available</td>
                </tr>
                <?php
            }
            ?>
            
        <tr class="data" style="font-weight:bold;">
        <td class="data" colspan="5" align="right" style="color:#800000;">
        Total Received
        </td>
        <td class="data" style="color:#800000;">&#8377;&nbsp;
        <?php echo number_format(floatval($rcvd_amount['amount_paid']/2),2,'.',''); ?>
        </td>
        <td class="data" style="color:#800000;">&#8377;&nbsp;
        <?php echo number_format(floatval($rcvd_amount['gst_paid']/2),2,'.',''); ?>
        </td>
        <td colspan="2" class="data"></td>
        </tr>

        </table>
        
        <?php
            if($rcvd_amount['gst_paid']>$paid_amount['gst_paid'])
            echo "<div style='width:100%; font-weight:bold; color:red; font-size:13px; text-align:center;'>Payable GST Amount : &#8377;&nbsp; ".number_format(floatval(($rcvd_amount['gst_paid']-$paid_amount['gst_paid'])/2),2,'.','')."/- Only</div>";
            elseif($rcvd_amount['gst_paid']<$paid_amount['gst_paid'])
            echo "<div style='width:100%; font-weight:bold; color:green; font-size:13px; text-align:center;'>Refundable GST Amount : &#8377;&nbsp; ".number_format(floatval(($paid_amount['gst_paid']-$rcvd_amount['gst_paid'])/2),2,'.','')."/- Only</div>";
            else
            echo "<div style='width:100%; font-weight:bold; color:green; font-size:13px; text-align:center;'>***No GST Due & No GST Refund***</div>";
        ?>

        </div>
        
<div class="clear"></div>
<?php
include_once("footer.php");
?>
</div>
</body>
</html>
<script src="js/jquery-ui.js"></script>
<script src="amit.js"></script>

<script>

$(document).ready(function(){
    $("#export_to_excel").click(function(){
        $("#my_table").table2excel({        
        
            exclude: ".noExl",
            name: "Developer data",
            filename: "GSTReturn",
            fileext: ".xls",        
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true ,
        });  
    });

   $("#c_gstin").autocomplete({
			source: "gstin-ajax.php"
		});
   $("#company_name").autocomplete({
			source: "company-ajax.php"
		});
});

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

newWin.document.write(divToPrint.outerHTML);
newWin.print();

newWin.close();
   
}


</script>