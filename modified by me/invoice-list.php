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

//print_r($_REQUEST);

if($_REQUEST['trsns_pname']=="invoice-list-inst-sale-goods")
{
    $trsns_pname = "invoice-list-inst-sale-goods";
    
    $select_query = "select * from payment_plan where id=".$_REQUEST['id']." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);

    $back_data="customer-ledger.php?cust_id=".$select_data['cust_id'];
    $old_trans_id = $select_data['trans_id'];
    $old_printable_invoice_number = $select_data['printable_invoice_number'];
    $old_cust_id = $select_data['cust_id'];
    $old_project_id = $select_data['on_project'];
    $old_amount = $select_data['credit'];
    $old_description = $select_data['description'];
    $old_payment_date = $select_data['payment_date'];
    $old_payment_subdivision = $select_data['subdivision'];
    $old_link_id = $select_data['link_id'];
    $old_id = $select_data['id'];
    $id_first_cust = $select_data['id'];
    $id_second_proj = $select_data['link_id'];
    $id_third_bankpay = $select_data['link2_id'];
    $id_four_cust_pay = $select_data['link3_id'];
    $old_invoice_issuer_id = $select_data['invoice_issuer_id'];
    $old_subdivision = $select_data['subdivision'];
    $old_gst_subdivision = $select_data['gst_subdivision'];
    $old_tds_subdivision = $select_data['tds_subdivision'];
    $old_invoice_idnew = $select_data['invoice_id'];
    $old_payment_flag =  $select_data['payment_flag'];
    
    $select_query_pay = "select * from payment_plan where id=".$id_third_bankpay." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result_pay = mysql_query($select_query_pay) or die('error in query select supplier query '.mysql_error().$select_query_pay);
    $select_data_pay = mysql_fetch_array($select_result_pay);
    //echo "$select_query_pro";
    $old_pay_bank_id = $select_data_pay['bank_id'];
    $old_pay_amount = $select_data_pay['credit'];
    $old_pay_method = $select_data_pay['payment_method'];
    $old_pay_checkno = $select_data_pay['payment_checkno'];
    $old_pay_payment_date = $select_data_pay['payment_date'];
    
       
}

if(mysql_real_escape_string(trim($_REQUEST['search_action'])) == "enddate")
{ 
    $from_date = strtotime(mysql_real_escape_string(trim($_REQUEST['from_date'])));
    
    $to_date = strtotime(mysql_real_escape_string(trim($_REQUEST['to_date'])));

    if($_REQUEST['cust_id'])
    {
        //modify by amit
        $customer_info = explode(" - ",$_REQUEST['cust_id']);
        $customerdata ="and cust_id='".$customer_info[1]."'";
    }
    else { $customerdata=""; }

    if($_REQUEST['invoice_type'])
    {//code by amit
        $invoice_type ="and invoice_type='".$_REQUEST['invoice_type']."'";
    }else { $invoice_type=""; }
    
    if($_REQUEST['project_id']){
        $projectdata ="and project_id='".$_REQUEST['project_id']."'";
    }else { $projectdata=""; }

    if($_REQUEST['invoice_id']){
        $invoice_iddata ="and invoice_id='".$_REQUEST['invoice_id']."'";
    }
    elseif($_REQUEST['printable_invoice_number'])
    {
        //code by amit
        $print_inv_data = explode(" - ",$_REQUEST['printable_invoice_number']);
        $invoice_iddata ="and invoice_id='".$print_inv_data[1]."'";
    }else { $invoice_iddata=""; }
    
    if($from_date){
        $from_datedata ="and payment_date >= '".$from_date."'";
    }else { $from_datedata=""; }
    
    if($to_date){
        $to_datedata ="and payment_date <= '".$to_date."'";
    }else { $to_datedata=""; }
  
    $query = "select * from goods_details where invoice_id > '0' ".$customerdata." ".$projectdata."".$invoice_iddata." ".$from_datedata." ".$to_datedata." ".$invoice_type." group by invoice_id ORDER BY invoice_id ASC ";
    $result = mysql_query($query) or die('error in query select invoice_issuer query '.mysql_error().$query);
    $total_row = mysql_num_rows($result);
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
if($_REQUEST['rpp'])
{$resultsPerPage = $_REQUEST['rpp'];}
else
{$resultsPerPage = 20;}

$startResults = ($page - 1) * $resultsPerPage;
$totalPages = ceil($total_row / $resultsPerPage);


if(mysql_real_escape_string(trim($_REQUEST['search_action'])) == "enddate")
{
    $from_date = strtotime(mysql_real_escape_string(trim($_REQUEST['from_date'])));
    
    $to_date = strtotime(mysql_real_escape_string(trim($_REQUEST['to_date'])));

    if($_REQUEST['cust_id'])
    {
        //modify by amit
        $customer_info = explode(" - ",$_REQUEST['cust_id']);
        $customerdata ="and cust_id='".$customer_info[1]."'";
    }
    else { $customerdata=""; }
    
    if($_REQUEST['project_id']){
        $projectdata ="and project_id='".$_REQUEST['project_id']."'";
    }else { $projectdata=""; }

    if($_REQUEST['invoice_type']){//code by amit
        $invoice_type ="and invoice_type='".$_REQUEST['invoice_type']."'";
    }else { $invoice_type=""; }
    
    if($_REQUEST['invoice_id']){
        $invoice_iddata ="and invoice_id='".$_REQUEST['invoice_id']."'";
    }
    elseif($_REQUEST['printable_invoice_number'])
    {
        //code by amit
        $print_inv_data = explode(" - ",$_REQUEST['printable_invoice_number']);
        $invoice_iddata ="and invoice_id='".$print_inv_data[1]."'";
    }else { $invoice_iddata=""; }
    
    if($from_date!=""){
        $from_datedata ="and payment_date >= '".$from_date."'";
    }else { $from_datedata=""; }
    
    if($to_date!=""){
        $to_datedata ="and payment_date <= '".$to_date."'";
    }else { $to_datedata=""; }
  
    // and payment_date >= '".$from_date."' and payment_date <= '".$to_date."' 
    
    $select_query = "select *  from goods_details where  invoice_id > '0' ".$customerdata." ".$projectdata."".$invoice_iddata." ".$from_datedata." ".$to_datedata." ".$invoice_type." group by invoice_id ORDER BY invoice_id DESC LIMIT ". $startResults.",". $resultsPerPage;
    
    $select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
    $select_total = mysql_num_rows($select_result);
}
else
{
    $select_query = "select * from goods_details where invoice_id > '0' group by invoice_id ORDER BY invoice_id DESC LIMIT ". $startResults.",". $resultsPerPage;
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
        Sale Invoice List</h4>&nbsp;
  </td>
        <td width="" style="float:right;">
        <b>Rows Per Page</b>
    <select name="rpp_select" id="rpp_select" onchange="document.getElementById('rpp').value=this.value; document.getElementById('search_form').submit();">
       <option value="10" <?= $sel_10; ?>>10</option>
       <option value="20" <?= $sel_20; ?>>20</option>
       <option value="30" <?= $sel_30; ?>>30</option>
       <option value="40">40</option>
       <option value="50">50</option>
    </select>
    &nbsp;
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

                    <td width="80">Invoice Type</td>
                    <td width="280px;">
                    <select name="invoice_type" id="invoice_type" style="width:250px; height: 25px;">
                    <option value="">All</option>
                    <option value="R">GST Rent</option>
                    <option value="M">GST Maintenance</option>
                    <option value="S">GST Sale</option>
                    <option value="RN">Reimbursement Note</option>
                    </select>
                    </td>

                </tr>
                <!--<tr>
                    
                    <td width="80px;" nowrap>Customer Name</td>
                    <td width="280px;">
                        <input type="text" name="cust_id" id="cust_id" style="width:250px; height: 25px;">
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
                            <option value="<?php echo $search_data2['project_id']; ?>" <?php if($_REQUEST['project_id']==$search_data2['project_id']){ echo "selected='selected'"; } ?>>&nbsp;<?php echo $project1_nm; ?></option>
                            <?php   }
                            ?>
                        </select>
                    </td>
                </tr>
                
                <tr>                    
                    <td width="80">NDB Sr. No.</td>
                    <td width="280px;">
                        <input type="text" name="invoice_id" id="invoice_id" onblur="setVisibility(this.id)" style="width:250px; height: 25px;">
                    </td>
                    <td width="80px;" nowrap>Invoice No.</td>
                    <td width="280px;">
                        <input type="text" name="printable_invoice_number" id="printable_invoice_number" onblur="setVisibility(this.id)" style="width:250px; height: 25px;">
                    </td>
                </tr>-->

                <tr>
                    
                    <td width="80px;" nowrap>Customer Name</td>
                    <td width="280px;">
                        <input type="text" name="cust_id" id="cust_id" value="<?= $_REQUEST['cust_id'] ?>" style="width:250px; height: 25px;">
                    </td>
                </tr>

                <tr align="center">
                    <td colspan="6"> <input type="button" name="search_button1" id="search_button1" value="search " onClick="return search_date();" class="button" >&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='invoice-list.php?gst_subdivision_id=<?php echo $_REQUEST['gst_subdivision_id']; ?>';"  />&nbsp;
                    </td>
                </tr>


            </table>
            
            </form>
           <form name="user_form" id="user_form" action="" method="post" >
                <input type="hidden" name="action_perform" id="action_perform" value="" >
        <input type="hidden" name="del_id" id="del_id" value="" >
        <input type="hidden" name="count" id="count" value="<?php echo $i; ?>"  />    
        </form>
        <input type="hidden" name="search_action" id="search_action" value=""  />
        <input type="hidden" name="page" id="page" value="">

  <?php include_once("main_search_close.php") ?>
 <!-------------->
  
  <?php include_once("main_body_open.php") ?>

     
    <div id="adddiv" style="display:<?php if($error_msg != "") { ?>block<?php } else { ?>none<?php } ?>;">
    
    
        
        </div>
        
                
        <div id="ledger_data" style="height: 450px;  overflow-y: scroll;overflow-x: scroll;padding:0px;">
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
			<td><b>&nbsp;<?php echo getTime(); echo "("; $username_1=get_field_value("full_name","user","userid",$_SESSION['userId']); echo $username_1; echo ")";?></b></td>
			<td colspan="4"></td>
        </tr>    
       
            <tr>
            <thead class="report-header">
                <th class="data" align="center" nowrap>S.No.</th>
                <th class="data" align="center" nowrap>NDB Sr. No.</th>
                <th class="data" align="center" nowrap>Invoice No.</th>
                <th class="data" align="center" nowrap>Invoice Type</th>
                <th class="data" align="center" nowrap>Customer Name</th>
                <th class="data" align="center" nowrap>Inv From</th>
                <th class="data" align="center" nowrap>Date </th>
                <th class="data" align="center" nowrap>Added By</th>
				<th class="data" align="center" nowrap>Added On</th>
				<th class="data" align="center" nowrap>Updated By</th>
				<th class="data" align="center" nowrap>Updated On</th>
                <th class="data noExl" id="header1" align="center">Action</th>
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
                        <td class="data" width="30px">&nbsp;<?php echo $ii; ?>&nbsp;</td>
                        <td class="data">&nbsp;<?php echo $select_data['invoice_id']; ?>&nbsp;</td>
                        <td class="data">
                        <?php 
                        //code by amit
                        $pin_query = "Select printable_invoice_number,invoice_type from payment_plan where invoice_id='".$select_data['invoice_id']."'";
                        $pin_result = mysql_query($pin_query);
	                    $pin_data = mysql_fetch_assoc($pin_result);

                        if($pin_data['printable_invoice_number'])
                        echo $pin_data['printable_invoice_number'];
                        else
                        echo $select_data['invoice_id'];

                        ?>
                        </td>

                        <td class="data" nowrap>
                        <?php 
                        //code by amit
                        if($select_data['invoice_type'] == 'R')
                        echo 'GST Rent';
                        elseif ($select_data['invoice_type'] == 'S')
                        echo 'GST Sale';
                        elseif ($select_data['invoice_type'] == 'M')
                        echo 'GST Maintenance';
                        elseif ($select_data['invoice_type'] == 'RN')
                        echo 'Reimbursement Note';
                        else
                        echo 'Manual Invoice';

                        ?>
                        </td>

                        <td class="data" nowrap>&nbsp;<?php
                        $customer_nm = get_field_value("full_name","customer","cust_id",$select_data['cust_id']); 
                        echo $customer_nm;
                         ?>&nbsp;</td>
                        <td class="data">&nbsp;<?php //echo $select_data['issuer_name'];
                        $payment_customer = $select_data['link2_id'];
                        //echo  $payment_customer;
                        $invoice_issuer_id = get_field_value("invoice_issuer_id","payment_plan","id",$payment_customer); 
                         //echo $invoice_issuer_id;
                         $display_name = get_field_value("issuer_name","invoice_issuer","id",$invoice_issuer_id); 
                         echo $display_name;
                         ?>&nbsp;</td>
                        
                        
                        <td class="data" align="center" nowrap>&nbsp;<?php echo date("d-m-Y",$select_data['payment_date']); ?>&nbsp;</td>
                        <td class="data" nowrap>&nbsp;
                        <?php
                            $added_by_id = get_field_value_groupby("added_by","payment_plan","invoice_id",$select_data['invoice_id']);
				            echo get_field_value("full_name","user","userid",$added_by_id); 
                        ?>&nbsp;
                        </td>
                        <td class="data" nowrap>&nbsp;
                        <?php
                            $added_on = get_field_value_groupby("added_on","payment_plan","invoice_id",$select_data['invoice_id']);

                            if($added_on)
				                echo date('d-m-Y h:i:s A', $added_on);							 
                        ?>&nbsp;
                        </td>
                        <td class="data" nowrap>&nbsp;
                        <?php
                            $updated_by_id = get_field_value_groupby("updated_by","payment_plan","invoice_id",$select_data['invoice_id']);
				            echo get_field_value("full_name","user","userid",$updated_by_id); 
                        ?>&nbsp;
                        </td>
                        <td class="data" nowrap>&nbsp;
                        <?php
                            $updated_on = get_field_value_groupby("updated_on","payment_plan","invoice_id",$select_data['invoice_id']);

                            if($updated_on)
				                echo date('d-m-Y h:i:s A', $updated_on);							 
                        ?>&nbsp;
                        </td>
                        <td class="data noExl" nowrap>
                        <center>
                        &nbsp;<?php if($select_data['trans_type_name']=="instmulti_sale_goods")
                        {  ?>
                        <a href="edit_instant-sale-invoice_multiple.php?trans_id=<?php echo $select_data['trans_id']; ?>&id=<?php echo $select_data['link2_id']; ?>&trsns_pname=<?php echo "invoice-list-inst-sale-goods"; ?>"><img src="mos-css/img/edit.png" style="height:14px;" title="Edit"></a>
                 <?php  } ?>&nbsp;
                        <a href="invoice-print.php?invoice_id=<?php echo $select_data['invoice_id']; ?>"><img src="images/print_icon.png" style="height:15px; width:15px;" title="Print"></a>                        
                       &nbsp;<a href="invoice_repeat.php?trans_id=<?php echo $select_data['trans_id']; ?>&id=<?php echo $select_data['link2_id']; ?>&trsns_pname=<?php echo "invoice-list-inst-sale-goods"; ?>"><img src="images/rsymbol.png" style="height:18px;" title="Repeat Invoice"></a>
                       &nbsp;
                        </center>
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
                    <td width="30px" colspan="12" class="record_not_found" align="center">Sorry! No Record Available</td>
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

<script src="js/jquery-ui.js"></script>

<script>
function windowpop(url, width, height) 
{
    var left = (screen.width / 2) - (width / 2);
    var top = (screen.height / 2) - (height / 2);
    window.open(url, "", "menubar=no,toolbar=no,status=no,width=" + width + ",height=" + height + ",top=" + top + ",left=" + left);
}
</script>

<script>

$(document).ready(function(){
    $("#export_to_excel").click(function()
    {
        $("#my_table").table2excel({        

            exclude: ".noExl",
            name: "Developer data",
            filename: "Invoice_List",
            fileext: ".xls",        
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true            
        });  
    });
    
    $("#cust_id").autocomplete({
			source: "customer-ajax.php"
		});
    $("#invoice_id").autocomplete({
			source: "srno-ajax.php"
		});
    $("#printable_invoice_number").autocomplete({
			source: "pin-ajax.php"
		});
});

function setVisibility(inv_box)
{
    //code by amit

    if(inv_box == 'invoice_id')
    {
        if(document.getElementById(inv_box).value)
            document.getElementById('printable_invoice_number').disabled = true;
        else
            document.getElementById('printable_invoice_number').disabled = false;
    }

    if(inv_box == 'printable_invoice_number')
    {
        if(document.getElementById(inv_box).value)
            document.getElementById('invoice_id').disabled = true;
        else
            document.getElementById('invoice_id').disabled = false;
    }

}

function show_records(getno)
{
    //alert(getno);
    document.getElementById("page").value=getno;
    document.search_form.submit(); 
}

function search_date()
{
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
