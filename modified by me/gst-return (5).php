<?php session_start();
include_once("ndb_db_con.php");
include_once("advance_functions.php");

//current date & time
date_default_timezone_set('Asia/Calcutta');
echo $current_datentime = date('d-m-Y h:i:s A', time());


if(isset($_REQUEST['msg']) != "")
{
    $msg = $_REQUEST['msg'];
}
else
{
    $msg = "";
}
if(isset($_REQUEST['error_msg']) != "")
{
    $error_msg = $_REQUEST['error_msg'];
}
else
{
    $error_msg = "";
}

if(isset($_SESSION['userId'])) 
$userId=$_SESSION['userId'];
else
$userId='2';

echo $cutoff_date = strtotime("1 september 2023");
$startResults = 0;

if(mysqli_real_escape_string($con,(isset($_REQUEST['search_action']))) == "ledger_search")
{
    $from_date = strtotime(mysqli_real_escape_string($con,($_REQUEST['from_date'])));
    
    $to_date = strtotime(mysqli_real_escape_string($con,($_REQUEST['to_date'])));

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
    $select_result = mysqli_query($con,$select_query) or die('error in query select bank query '.mysqli_error().$select_query);
    $select_total = mysqli_num_rows($select_result);

    $paid_amout_query = "SELECT SUM(invoice_pay_amount) AS amount_paid, SUM(gst_amount) AS gst_paid FROM payment_plan where trans_id > '0' and trans_type_name in('receive_goods','inst_receive_goods')".$querypart_rcvr.$from_datedata.$to_datedata;
    $paid_amout_result = mysqli_query($con,$paid_amout_query) or die('error in query select user query '.mysqli_error().$paid_amout_query);
    $paid_amount = mysqli_fetch_array($paid_amout_result);

    //sales return queries
    $sales_query = "select * from payment_plan where trans_id > 0 and trans_type_name='instmulti_sale_goods' ".$querypart_rcvr."".$from_datedata." ".$to_datedata." group by trans_id ORDER BY payment_date ASC";
    $sales_result = mysqli_query($con,$sales_query) or die('error in query select bank query '.mysqli_error().$sales_query);
    $sales_total_row = mysqli_num_rows($sales_result);

    $rcvd_amout_query = "SELECT SUM(invoice_pay_amount) AS amount_paid, SUM(gst_amount) AS gst_paid FROM payment_plan where trans_id > '0' and trans_type_name='instmulti_sale_goods' ".$querypart_rcvr.$from_datedata.$to_datedata;
    $rcvd_amout_result = mysqli_query($con,$rcvd_amout_query) or die('error in query select user query '.mysqli_error().$rcvd_amout_query);
    $rcvd_amount = mysqli_fetch_array($rcvd_amout_result);
}
else
{
    //purchase queries
    $select_query = "select * from payment_plan where trans_id > '0' and payment_date >= '".$cutoff_date."' and trans_type_name in('receive_goods','inst_receive_goods') group by trans_id ORDER BY payment_date ASC";
    $select_result = mysqli_query($con,$select_query) or die('error in query select user query '.mysqli_error().$select_query);
    $select_total = mysqli_num_rows($select_result);

    $paid_amout_query = "SELECT SUM(invoice_pay_amount) AS amount_paid, SUM(gst_amount) AS gst_paid FROM payment_plan where trans_id > '0' and trans_type_name in('receive_goods','inst_receive_goods') and payment_date >= '".$cutoff_date."'";
    $paid_amout_result = mysqli_query($con,$paid_amout_query) or die('error in query select user query '.mysqli_error().$paid_amout_query);
    $paid_amount = mysqli_fetch_array($paid_amout_result);
    
    //sales return queries
    $sales_query = "select * from payment_plan where trans_id > 0 and payment_date >= '".$cutoff_date."' and trans_type_name='instmulti_sale_goods' group by trans_id ORDER BY payment_date ASC";
    $sales_result = mysqli_query($con,$sales_query) or die('error in query select bank query '.mysqli_error().$sales_query);
    $sales_total_row = mysqli_num_rows($sales_result);

    $rcvd_amout_query = "SELECT SUM(invoice_pay_amount) AS amount_paid, SUM(gst_amount) AS gst_paid FROM payment_plan where trans_id > '0' and trans_type_name='instmulti_sale_goods' and payment_date >= '".$cutoff_date."'";
    $rcvd_amout_result = mysqli_query($con,$rcvd_amout_query) or die('error in query select user query '.mysqli_error().$rcvd_amout_query);
    $rcvd_amount = mysqli_fetch_array($rcvd_amout_result);
}
?>
<!DOCTYPE html>
<?php //include_once ("top_header1.php"); ?>   
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
        <input type="hidden" name="count" id="count" value="<?php if(isset($i)) echo $i; ?>"  />    
        </form>

        <div id="ledger_data" style="width:98%; padding-right: 10px;">

        <div style="width:100%; font-weight:bold; color:#800000; font-size:13px; text-align:center;">
        <?php
        if(isset($_REQUEST['search_action']))
        {
            $company_name = get_field_value($con,"company_name","invoice_issuer","id",$invoice_rcvr[1]);
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

        <table id="my_table">
        <tr class="data"></td>

        <table class="data" border="1" cellpadding="1" cellspacing="0" style="width:100%; border: 1px solid #111111;">
        </tr>
            <td class="data" colspan="9" nowrap>
            <b>GST Purchase Return: Generated On :
            <?php 
            echo $current_datentime; echo " ( By : "; $username_1=get_field_value($con,"full_name","user","userid",$userId); echo $username_1; echo " )";?>
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
                while($select_data = mysqli_fetch_array($select_result))
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
                        <?php
                        $customer_nm = get_field_value($con,"full_name","customer","cust_id",$select_data['cust_id']); 
                        echo $customer_nm;
                         ?>
                         </td>
                         <td class="data" nowrap>
                         <?php
                        $gst_nm = get_field_value($con,"supply_gst_no","customer","cust_id",$select_data['cust_id']); 
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
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td class="data" align="right" style="color:#800000;">
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
        <tr><td colspan="9">&nbsp;</td></tr>


        <tr class="data">
            <td class="data" colspan="9" nowrap>
            <b>GST Sale Return: Generated On :
            <?php echo $current_datentime; echo " ( By : "; $username_1=get_field_value($con,"full_name","user","userid",$userId); echo $username_1; echo " )";?>
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
                while($sales_data = mysqli_fetch_array($sales_result))
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
                        $customer_nm = get_field_value($con,"full_name","customer","cust_id",$sales_data['cust_id']); 
                        echo $customer_nm;
                         ?>
                         </td>
                         <td class="data" nowrap><?php 
                        $gst_nm = get_field_value($con,"client_gst","customer","cust_id",$sales_data['cust_id']); 
                        echo $gst_nm;
                         ?></td>

                        <td class="data" nowrap>&#8377;&nbsp;
                        <?php 
                            $subtot= $sales_data['credit']-$sales_data['gst_amount'];
                            echo number_format((float)$subtot, 2,'.','');
                        ?>
                        </td>
                        <td style="" class="data" nowrap>
                        <?php 
                        if($sales_data['gst_amount']>1)
                        {
                         echo "&#8377;&nbsp;".number_format((float)$sales_data['gst_amount'], 2,'.','');
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
                        
                        <?php echo "&#8377;&nbsp;".number_format(floatval($sales_data['credit']),2,'.',''); ?>
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
        <br>
        <table class="data" border="0" cellpadding="1" cellspacing="0" style="width:100%; border: 0px solid #111111;">

        <tr><td>
        <?php
            $final_gst_amount = 0;
            if($rcvd_amount['gst_paid']>$paid_amount['gst_paid'])
            {
                $final_gst_amount = number_format(floatval(($rcvd_amount['gst_paid']-$paid_amount['gst_paid'])/2),2,'.','');
                echo "<div style='width:100%; font-weight:bold; color:red; font-size:13px; text-align:center;'>Payable GST Amount : &#8377;&nbsp; ".$final_gst_amount."/- (";
                ntw($final_gst_amount);
                echo ")</div>";
            }
            elseif($rcvd_amount['gst_paid']<$paid_amount['gst_paid'])
            {
                $final_gst_amount = number_format(floatval(($paid_amount['gst_paid']-$rcvd_amount['gst_paid'])/2),2,'.','');
                echo "<div style='width:100%; font-weight:bold; color:green; font-size:13px; text-align:center;'>Refundable GST Amount : &#8377;&nbsp; ".$final_gst_amount."/- (";
                ntw($final_gst_amount);
                echo ")</div>";
            }
            else
            echo "<div style='width:100%; font-weight:bold; color:green; font-size:13px; text-align:center;'>***No GST Due & No GST Refund***</div>";
        ?>
        </td></tr></table>

        </td></tr></table>
        </div>
        
<div class="clear"></div>
<?php
//include_once("footer.php");
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
