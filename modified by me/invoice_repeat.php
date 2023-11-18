<?php session_start();
include_once("set_con.php");

//for unique & dynamic transaction id -- Start
$wi = 0;
while($wi<1)
{
	$trans_id = rand(100000,999999);
	$ss="select id from payment_plan where trans_id=".$trans_id."";
	$sr=mysql_query($ss);
	$tot_rw=mysql_num_rows($sr);
	if($tot_rw == 0)
	{
		break;								
	}
}
//for unique & dynamic transaction id -- End

//for unique & dynamic NDB Sr. No. -- Start

$qry_max=" select max(invoice_id) as max_invoice from goods_details";
$qry_max_result = mysql_query($qry_max);
       
$qry_max_row = mysql_fetch_array($qry_max_result);
if($qry_max_row[max_invoice]<1)
{
    $invoice_idnew="1001";       
}
else
{
$invoice_id =$qry_max_row[max_invoice]+1;    
}

//for unique & dynamic NDB Sr. No. -- End

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

    $old_pay_bank_id = $select_data_pay['bank_id'];
    $old_pay_amount = $select_data_pay['credit'];
    $old_pay_method = $select_data_pay['payment_method'];
    $old_pay_checkno = $select_data_pay['payment_checkno'];
    $old_pay_payment_date = $select_data_pay['payment_date'];
}
?>

<!DOCTYPE html>
<?php include_once ("top_header1.php"); ?>   
<script src="js/datetimepicker_css.js"></script>
<style>
    /*  ******************************  For Drag CSS   ********************************* */
            
            
#dropbox{
border:1px solid #FF0000;
padding:10px;
    border-radius:3px;
    position: relative;
    min-height: 150px;
    overflow: hidden;
    padding-bottom: 40px;
    width: 500px;
    
    box-shadow:0 0 4px rgba(0,0,0,0.3) inset,0 -3px 2px rgba(0,0,0,0.1);
}


#dropbox .message{
    font-size: 11px;
    text-align: center;
    padding-top:160px;
    display: block;
    
}

#dropbox .message i{
    color:#ccc;
    font-size:10px;
    
}

#dropbox:before{
    border-radius:3px 3px 0 0;
}



/*-------------------------
    Image Previews
--------------------------*/



#dropbox .preview{
    width:150px;
    height: 20px;
    float:left;
    position: relative;
    text-align: center;
}

#dropbox .preview img{
    max-width: 240px;
    max-height:180px;
    border:3px solid #fff;
    display: block;
    
    box-shadow:0 0 2px #000;
}

#dropbox .imageHolder{
    display: inline-block;
    position:relative;
}

#dropbox .uploaded{
    position: absolute;
    top:0;
    left:0;
    height:100%;
    width:100%;
    background: url('../img/done.png') no-repeat center center rgba(255,255,255,0.5);
    display: none;
}

#dropbox .preview.done .uploaded{
    display: block;
}
</style>
<script>
function goBack() {
  window.history.back();
}
</script>
<script src="js/jquery-1.12.4.min.js"></script>

<script>
    $(document).ready(function(){ 
        var ii=$("#i_val").val();
 //record,snum,no_check,desc_t,qty_t,unit_price_1,sub_total,gst,total,gst_amount
 //qty_tot,unit_price_tot,sub_total_tot,total_tot,gst_amount_tot
  
        $(".add-row").click(function(){ 
            if($("#project_1").val() == "")
    {
        alert("Please enter Project Name.");
        $("#project_1").focus();
        return false;
    }
    else if($("#subdivision_1").val() == "")
    {
        alert("Please enter Project Subdivision Name.");
        $("#subdivision_1").focus();
        return false;
    }
    else if($("#qty1").val() == "")
    {
        alert("Please enter project Quentity.");
        $("#qty1").focus();
        return false;
    }
    else if($("#unit_price1").val() == "")
    {
        alert("Please enter Unit Price.");
        $("#unit_price1").focus();
        return false;
    }
     else if($("#hsn_code1").val() == "")
    {
        alert("Please select HSN Code.");
        $("#hsn_code1").focus();
        return false;
    }
    else if($("#desc1").val() == "")
    {
        alert("Please enter Description");
        $("#desc1").focus();
        return false;
    }else
            {
             var project2 = $("#project_1").val();
           var subdivision2 = $("#subdivision_1").val();
         //  var gst_subdivision2 = $("#gst_subdivision_1").val();
            var desc2 = $("#desc1").val();
            var qty2 = $("#qty1").val();
            var unit_price2 = $("#unit_price1").val();
            var gst2 = $("#gst1").val();
            var tds2 = $("#tds1").val();
             count=$('#myTable tbody tr').length;
             var sub_total2 = unit_price2*qty2;
             var gst_amount =(sub_total2*gst2)/100;
             var tds_amount_1 =(sub_total2*tds2)/100;
             var total2 = sub_total2+gst_amount;
              var hsn_code2 = $("#hsn_code1").val();
             
            
            
   /* var markup="<tr><td style='text-align: left;padding: 2px;'><input type='checkbox' name='record' class='case'/></td><td style='text-align: left;padding: 2px;'><span id='snum"+ii+"'>"+ii+".</span><input type='hidden' id='no_check"+ii+"' value='"+ii+"' name='no_check[]'/></td>";
    markup +="<td style='text-align: left;padding: 2px;'><input type='text' id='desc_t"+ii+"' value='"+desc2+"' name='desc_t[]' style='width: 200px;' readonly='readonly'/></td> <td style='text-align: left;padding: 2px;'><input type='text' id='qty_t"+ii+"' name='qty_t[]' style='width: 70px;' readonly='readonly' value='"+qty2+"'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='unit_price_1"+ii+"' value='"+unit_price2+"' style='width: 100px;' readonly='readonly' name='unit_price_1[]'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='sub_total"+ii+"' value='"+sub_total2+"' style='width: 100px;' readonly='readonly' name='sub_total[]'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='gst"+ii+"' value='"+gst2+"' style='width: 50px;' readonly='readonly' name='gst[]'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='gst_amount"+ii+"' style='width: 100px;' readonly='readonly' value='"+gst_amount+"' name='gst_amount[]'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='total"+ii+"' style='width: 100px;' readonly='readonly' value='"+total2+"' name='total[]'/></td><td style='color: red;' align='center'><input type='hidden' id='old_new_check"+ii+"' value='new' name='old_new_check[]'/>New</td></tr>";
   */  //project,subdivision,gst_subdivision
   var markup="<tr><td style='text-align: left;padding: 2px;'><input type='checkbox' name='record' class='case'/></td><td style='text-align: left;padding: 2px;'><span id='snum"+ii+"'>"+ii+".</span><input type='hidden' id='no_check"+ii+"' value='"+ii+"' name='no_check[]'/></td>";
    markup +="<td style='text-align: left;padding: 2px;'><input type='text' id='project"+ii+"' value='"+project2+"' name='project[]' style='width: 130px;' readonly='readonly'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='subdivision"+ii+"' value='"+subdivision2+"' name='subdivision[]' style='width: 130px;' readonly='readonly'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='desc_t"+ii+"' value='"+desc2+"' name='desc_t[]' style='width: 140px;' readonly='readonly'/><td style='text-align: left;padding: 2px;'><input type='text' id='hsn_code"+ii+"' name='hsn_code[]' style='width: 70px;' readonly='readonly' value='"+hsn_code2+"'/></td></td> <td style='text-align: left;padding: 2px;'><input type='text' id='qty_t"+ii+"' name='qty_t[]' style='width: 30px;' readonly='readonly' value='"+qty2+"'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='unit_price_1"+ii+"' value='"+unit_price2+"' style='width: 60px;' readonly='readonly' name='unit_price_1[]'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='sub_total"+ii+"' value='"+sub_total2+"' style='width: 60px;' readonly='readonly' name='sub_total[]'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='tds"+ii+"' value='"+tds2+"' style='width: 30px;' readonly='readonly' name='tds[]'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='tds_amount_f"+ii+"' value='"+tds_amount_1+"' name='tds_amount_f[]' style='width: 60px;' readonly='readonly'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='gst"+ii+"' value='"+gst2+"' style='width: 30px;' readonly='readonly' name='gst[]'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='gst_amount"+ii+"' style='width: 60px;' readonly='readonly' value='"+gst_amount+"' name='gst_amount[]'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='total"+ii+"' style='width: 60px;' readonly='readonly' value='"+total2+"' name='total[]'/></td><td style='color: red;' align='center'><input type='hidden' id='old_new_check"+ii+"' value='new' name='old_new_check[]'/>New</td></tr>";
     
    $(' #myTable tbody').append(markup);
    ii++;
   // $("#desc1").val()="";
   var vall="";
   $("#desc1").val(vall);
   $("#qty1").val(vall);
   $("#unit_price1").val(vall);
   $("#project_1").val(vall);
   $("#subdivision_1").val(vall);
   //$("#gst_subdivision_1").val(vall);
    //qty_tot,unit_price_tot,sub_total_tot,tds_tot,tds_amount_tot,gst_tot,gst_amount_tot,total_tot
   
   var sum = 0;
 // alert("hi");
 
var qty_t_total = 0;
var sub_total_total=0;
var unit_price_total=0;
var total_amount_total=0;
var gst_amount_total=0;
var tds_amount_total=0;
var desc_total="";
            
    for (var i = 1; i <= ii; i++) { 
            if($("#no_check"+i+"").val()==i){
            qty_t_total =Number(qty_t_total)+Number($("#qty_t"+i+"").val()) ; 
            unit_price_total =Number(unit_price_total)+Number($("#unit_price_1"+i+"").val()) ;
            sub_total_total =Number(sub_total_total)+Number($("#sub_total"+i+"").val()) ; 
            total_amount_total =Number(total_amount_total)+Number($("#total"+i+"").val()) ; 
            gst_amount_total =Number(gst_amount_total)+Number($("#gst_amount"+i+"").val()) ; 
            tds_amount_total =Number(tds_amount_total)+Number($("#tds_amount_f"+i+"").val()) ; 
            
            desc_total =desc_total+"("+i+")"+$("#desc_t"+i+"").val()+","; 

            
                
            }
            } 
            document.getElementById("qty_tot").value = qty_t_total;
            document.getElementById("unit_price_tot").value = unit_price_total;
            document.getElementById("sub_total_tot").value = sub_total_total;
            document.getElementById("total_tot").value = total_amount_total;
            document.getElementById("gst_amount_tot").value = gst_amount_total;
            document.getElementById("amount").value = total_amount_total;
            document.getElementById("description").value = desc_total;
            document.getElementById("tds_amount_tot").value = tds_amount_total;
          //  document.getElementById("tds_due_amount_new").value = tds_amount_total;
           // document.getElementById("tds_due_amount_new_total").value = tds_amount_total;
           // document.getElementById("tds_due_amount_new_due").value = "0";
            
        }
        });
        
        // Find and remove selected table rows
        $(".delete-row").click(function(){
            $("#myTable tbody").find('input[name="record"]').each(function(){
                if($(this).is(":checked")){
                    $(this).parents("#myTable tbody tr").remove();
                }
             count=$('#myTable tbody tr').length;   
var qty_t_total = 0;
var sub_total_total=0;
var unit_price_total=0;
var total_amount_total=0;
var gst_amount_total=0;
var tds_amount_total=0;
var desc_total="";
            for (var i = 1; i <= ii; i++) { 
            if($("#no_check"+i+"").val()==i){
            qty_t_total =Number(qty_t_total)+Number($("#qty_t"+i+"").val()) ; 
            unit_price_total =Number(unit_price_total)+Number($("#unit_price_1"+i+"").val()) ;
            sub_total_total =Number(sub_total_total)+Number($("#sub_total"+i+"").val()) ; 
            total_amount_total =Number(total_amount_total)+Number($("#total"+i+"").val()) ; 
            gst_amount_total =Number(gst_amount_total)+Number($("#gst_amount"+i+"").val()) ; 
            tds_amount_total =Number(tds_amount_total)+Number($("#tds_amount_f"+i+"").val()) ; 
            
            //desc_total =gst_amount_total+"("+i+")"+$("#desc"+i+"").val()+",";     
            desc_total =desc_total+"("+i+")"+$("#desc_t"+i+"").val()+",";
            }
            } 
            document.getElementById("qty_tot").value = qty_t_total;
            document.getElementById("unit_price_tot").value = unit_price_total;
            document.getElementById("sub_total_tot").value = sub_total_total;
            document.getElementById("total_tot").value = total_amount_total;
            document.getElementById("gst_amount_tot").value = gst_amount_total;
            
            document.getElementById("tds_amount_tot").value = tds_amount_total;
           
            document.getElementById("amount").value = total_amount_total;
            document.getElementById("description").value = desc_total;
            
            });
        });
    });    
</script>

<body  data-home-page-title="" class="u-body u-xl-mode" data-lang="en">
<?php //include_once ("top_header2.php"); ?> 
<?php //include_once ("top_menu.php"); ?>
<?php //include_once("main_heading_open.php") ?>
<?php //include_once("main_heading_close.php") ?>
<?php include_once("main_body_open.php") ?>
    
<div id="adddiv" width="30%">
    
<form name="project_form" id="project_form" action="instant-sale-invoice_multiple.php" target="_parent" method="post" enctype="multipart/form-data" >
<input type="hidden" id="id_first_cust" name="id_first_cust" value="<?php echo $id_first_cust; ?>">
<input type="hidden" id="id_second_proj" name="id_second_proj" value="<?php echo $id_second_proj; ?>">
<input type="hidden" id="id_third_bankpay" name="id_third_bankpay" value="<?php echo $id_third_bankpay; ?>">
<input type="hidden" id="id_four_cust_pay" name="id_four_cust_pay" value="<?php echo $id_four_cust_pay; ?>">
<input type="hidden" id="trsns_pname" name="trsns_pname" value="<?php echo $trsns_pname; ?>">
<input type="hidden" name="from_page" value="Repeat Invoice">
        
<script src="js/datetimepicker_css.js"></script>
<link rel="stylesheet" href="css/jquery-ui.css" />
<!--<script src="js/jquery-1.9.1.js"></script>-->
<script src="js/jquery-ui.js"></script>

<?php
    $sql_cus = "select cust_id,full_name,short_name from `customer` where cust_id=".$old_cust_id." and type = 'customer'";
    $query_cus = mysql_query($sql_cus);
    $select_cus = mysql_fetch_array($query_cus);

    $sql_insv = "select * from `invoice_issuer` where id=".$old_invoice_issuer_id." ";
    $query_insv = mysql_query($sql_insv);
    $select_insv = mysql_fetch_array($query_insv);

    $sql_td = "select name from `tds_subdivision` where id=".$old_tds_subdivision." ";
    $query_td = mysql_query($sql_td);
    $row_td = mysql_fetch_array($query_td);
                 
    $sql_gs = "select name from `gst_subdivision` where id=".$old_gst_subdivision." ";
    $query_gs = mysql_query($sql_gs);
    $row_gs = mysql_fetch_array($query_gs);
?>
<!--old values as hidden type-->
<input type="hidden" id="trans_id"  name="trans_id" value="<?= $trans_id; ?>"/>
<input type="hidden" id="invoice_idnew"  name="invoice_idnew" value="<?php echo $invoice_id; ?>"/>
<input type="hidden" id="invoice_type" name="invoice_type" value="<?= $select_data['invoice_type']?>">
<input type="hidden"  name="amount" id="amount" value="<?php echo $old_amount ; ?>">
<input type="hidden" name="description" id="description" value="<?php echo $old_description; ?>">
<input type="hidden" id="gst_subdivision_1"  name="gst_subdivision_1" value="<?php echo $row_gs['name']; ?>">
<input type="hidden" id="tds_subdivision_1"  name="tds_subdivision_1" value="<?php echo $row_td['name']; ?>">

<table style="width:100%;" border="1" style="border:1px solid; padding:0px 0px; margin-top:-10px;">
    <tr>
    <td style="align:top;" align="left">
    <h4 class="u-text-2 u-text-palette-1-base " style="padding:0px; margin:0px;">
    Repeat Sale Invoice </h4>
    </td>
    <td width="" style="float:left;">
    <input type="button" name="back_button" id="back_button" onclick="goBack()" value="" class="button_back"  />
    </td>
    </tr>
</table>

<table width="98%" border="1"  style="padding:10px 0px 20px 20px; " >

<tr>
<td>
<table width="100%" border="1" class="tbl_border" >
   
<tr>
<td style="align:top;" valign="top">
     
    <table width="98%" border="0" >

    <tr>
    <td>Customer Name</td>
    <td>
    <?= $select_cus['full_name'] ?>
    <input type="hidden" id="from"  name="from" value="<?php echo $select_cus['full_name'].' - '.$select_cus['cust_id'].' - '.$select_cus['short_name']; ?>" readonly>
    </td>
    </tr>

    <tr>
    <td>Invoice Issuer</td>
    <td>
    <?= $select_insv['company_name'] ?>
    <input type="hidden" id="invoice_issuer"  name="invoice_issuer" value="<?php echo $select_insv['issuer_name'].' - '.$select_insv['id']; ?>" readonly>
    </td>
    </tr>

	<tr>
    <td width="125px" nowrap>Invoice Month</td>
	<td style="font-weight:bold;">
    <Select id="invoice_month" name="invoice_month" onChange="set_print_inv()">
    <option value="">Select Month</option>            
    <option value="/01">April</option>
    <option value="/02">May</option>
    <option value="/03">June</option>
    <option value="/04">July</option>
    <option value="/05">August</option>
    <option value="/06">September</option>
    <option value="/07">October</option>
    <option value="/08">November</option>
    <option value="/09">December</option>
    <option value="/10">January</option>
    <option value="/11">February</option>
    <option value="/12">March</option>
    </select>
    </td>
    </tr>

	<tr>
    <td width="125px">Invoice Year</td>
	<td style="font-weight:bold;">
    <Select id="invoice_fy" name="invoice_fy" onChange="set_print_inv()">
    <option value="">Select Year</option>            
    <option value="/22">22-23</option>
    <option value="/23">23-24</option>
    <option value="/24">24-25</option>
    <option value="/25">25-26</option>
    </select>
    </td>
    </tr>

    <tr>
    <td width="125px"><div id="inv_label">Previous Invoice No.</div></td>
    <td>
    <input type="text" style="color:#FF0000; font-weight:bold;" id="old_invoice_id_print" value="<?= $old_printable_invoice_number ?>" maxlength="16" readonly/>
    </td>
    </tr>

    <tr>
    <td width="125px"><div id="inv_label">Repeat Invoice No.</div></td>
    <td>
    <input type="text" style="color:#FF0000; font-weight:bold;" id="invoice_id_print"  name="invoice_id_print" value="" maxlength="16" readonly/>
    </td>
    </tr>
            
            
    <tr>
    <td align="left" valign="top" >Date</td>
    <td><input type="text"  name="date_new_id_old" id="payment_date" value="" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('payment_date')" style="cursor:pointer"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td>
    </tr>
            
    </table>
        
</td>
</tr>
        
<tr>
<td>
    
    <table style="display:none; margin: 0px 0; border-radius: 10px;border: 1px solid #111111;">
    <tr>
        <td >
            <table  id="myTable"  style="width: 750; padding: 10px;">
    
 
  <thead>
            <tr>
    <th style="width: 10px; left;padding: 2px; " align="center" > S. No</th>
    <th style="width: 130px; left;padding: 2px;" align="center"> Project</th>
    <th style="width: 130px; left;padding: 2px;" align="center"> Sub Division</th>
    <th style="width: 170px; left;padding: 2px;" align="center"> Description</th>
    <th style="width: 70px; left;padding: 2px;" align="center"> HSN Code</th>
    <th style="width: 30px; left;padding: 2px;" align="center"> Qty.</th>
    <th style="width: 60px; left;padding: 2px;" align="center"> Unit Price</th>
    <th style="width: 60px; left;padding: 2px;" align="center"> SubTotal</th>
    <th style="width: 30px; left;padding: 2px;" align="center"> TDS</th>
    <th style="width: 60px; left;padding: 2px;" align="center"> TDS Amount</th>
    <th style="width: 30px; left;padding: 2px;" align="center"> GST</th>
    <th style="width: 60px; left;padding: 2px;" align="center"> GST Amount</th>
    <th style="width: 60px; left;padding: 2px;" align="center"> Total</th>
    <th style="width: 20px;"></th>
  </tr>
  </thead>
        
        <tbody>
        <?php
    $sql_goods_series = "select * from `goods_details` where trans_id= '$old_trans_id' order by id";
    $query_goods_series = mysql_query($sql_goods_series);
    
    $ii=1;
    while($row_series = mysql_fetch_assoc($query_goods_series))
    {            
        $old_sub_total =$row_series['unit_price']*$row_series['qty'];
        $old_gst_amount =($old_sub_total*$row_series['gst_per'])/100;
        $old_tds_amount = $row_series['tds_amount'];
        $old_grand_total=$old_sub_total+$old_gst_amount;
        $tot1_qty_t=$tot1_qty_t+$row_series['qty'];
        $tot1_unit_price_1=$tot1_unit_price_1+$row_series['unit_price'];
        $tot1_sub_total= $tot1_sub_total+ $old_sub_total;
        $tot1_gst_amount = $tot1_gst_amount+$old_gst_amount;
        $tot1_tds_amount = $tot1_tds_amount+$old_tds_amount;
        $tot1_grand_total = $tot1_grand_total+$old_grand_total;
        $project1_nm = get_field_value("name","project","id",$row_series['project_id']); 
        $subdivision1_nm = get_field_value("name","subdivision","id",$row_series['subdivision']);  
        $gst_subdivision1_nm = get_field_value("name","gst_subdivision","id",$row_series['gst_subdivision']);  ?>
   
  
  <tr>
    <td style='text-align: left;padding: 4px;'><span id='snum'><?php echo $ii; ?></span><input type='hidden' id="<?php echo "no_check$ii"; ?>" value="<?php echo $ii; ?>" name='no_check[]'/></td>
    <td style='text-align: left;padding: 2px;'><input style="width: 130px;" readonly="readonly"  type='text' id="<?php echo "project$ii"; ?>" value="<?php echo $project1_nm; ?>"  name='project[]'/></td>
    <td style='text-align: left;padding: 2px;'><input style="width: 130px;" readonly="readonly"  type='text' id="<?php echo "subdivision$ii"; ?>" value="<?php echo $subdivision1_nm; ?>"  name='subdivision[]'/></td>
    <td  style='text-align: left;padding: 2px;'><input style="width: 140px;" readonly="readonly" value="<?php echo $row_series['description']; ?>" id="<?php echo "desc_t$ii"; ?>"  type='text'  name='desc_t[]'/></td>
    <td  style='text-align: left;padding: 2px;'><input style="width: 70px;" readonly="readonly" value="<?php echo $row_series['hsn_code']; ?>" type='text' id="<?php echo "hsn_code$ii"; ?>" name='hsn_code[]'/></td>
    <td  style='text-align: left;padding: 2px;'><input style="width: 30px;" readonly="readonly" value="<?php echo $row_series['qty']; ?>" type='text' id="<?php echo "qty_t$ii"; ?>" name='qty_t[]'/></td>
    <td  style='text-align: left;padding: 2px;'><input style="width: 60px;" readonly="readonly" type='text' value="<?php echo $row_series['unit_price']; ?>" id="<?php echo "unit_price_1$ii"; ?>" name='unit_price_1[]'/></td>
    <td  style='text-align: left;padding: 2px;'><input style="width: 60px;" readonly="readonly" type='text' value="<?php echo $old_sub_total; ?>" id="<?php echo "sub_total$ii"; ?>" name='sub_total[]'/> </td>
    <td  style='text-align: left;padding: 2px;'><input style="width: 30px;" readonly="readonly" type='text' value="<?php echo $row_series['tds_per']; ?>" id="<?php echo "tds$ii"; ?>" name='tds[]'/> </td>
    <td  style='text-align: left;padding: 2px;'><input style="width: 60px;" readonly="readonly" type='text' value="<?php echo $old_tds_amount; ?>" id="<?php echo "tds_amount_f$ii"; ?>" name='tds_amount_f[]'/> </td>
    
    <td  style='text-align: left;padding: 2px;'><input style="width: 30px;" readonly="readonly" type='text' value="<?php echo $row_series['gst_per']; ?>" id="<?php echo "gst$ii"; ?>" name='gst[]'/> </td>
    <td  style='text-align: left;padding: 2px;'><input style="width: 60px;" readonly="readonly" type='text' value="<?php echo $old_gst_amount; ?>" id="<?php echo "gst_amount$ii"; ?>" name='gst_amount[]'/> </td>
    <td  style='text-align: left;padding: 2px;'><input style="width: 60px;" readonly="readonly" type='text' value="<?php echo $old_grand_total; ?>" id="<?php echo "total$ii"; ?>" name='total[]'/> </td>
    <td style="color: red;" align="center"><input type='hidden' id='old_new_check"+ii+"' value='old' name='old_new_check[]'/><input type='hidden' id='old_id_check1"+ii+"' value=<?php echo $row_series['id']; ?> name='old_id_check1[]'/><input type='hidden' id='old_link2_id"+ii+"' value=<?php echo $row_series['link1_id']; ?> name='old_link2_id[]'/></td>
  </tr>
<input type='hidden' id='old_id_check2"+ii+"' value=<?php echo $row_series['id']; ?> name='old_id_check2[]'/>
<input type='hidden' id='old_link1_id"+ii+"' value=<?php echo $row_series['link1_id']; ?> name='old_link1_id[]'/>
        <?php
    $ii++;
    }
    
?>
<input type="hidden" value="<?php echo $ii; ?>" id="i_val" name="i_val">
              </tbody>
    </table>
</td>
</tr>
<tr>
<td>
    <table style="width: 750; border-top: 1px dashed #dcdcdc;  margin-top: -18px; padding: 0px 10px 10px 10px;">
    <tr>
    <td style='text-align: left;padding: 2px;'>&nbsp;</td>
    <td style='text-align: left;padding: 4px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td style='text-align: left;padding: 2px;'><input style="width: 130px;" readonly="readonly"  type='text' id='desc_tot' value=""  name='desc_tot'/></td>
    <td style='text-align: left;padding: 2px;'><input style="width: 130px;" readonly="readonly"  type='text' id='desc_tot' value=""  name='desc_tot'/></td>
    <td style='text-align: left;padding: 2px;'><input style="width: 140px;" readonly="readonly"  type='text' id='desc_tot' value="Total Items"  name='desc_tot'/></td>
    <td style='text-align: left;padding: 2px;'><input style="width: 70px;" readonly="readonly"  type='text' id='3' value=""  name='3'/></td>
    <td style='text-align: left;padding: 2px;'><input style="width: 30px;" readonly="readonly" type='text' id='qty_tot' value="<?php echo $tot1_qty_t; ?>" name='qty_tot'/></td>
    <td style='text-align: left;padding: 2px;'><input style="width: 60px;" readonly="readonly" type='text' id='unit_price_tot' value="<?php echo $tot1_unit_price_1;  ?>" name='unit_price_tot'/></td>
    <td style='text-align: left;padding: 2px;'><input style="width: 60px;" readonly="readonly" type='text' id='sub_total_tot' value="<?php echo $tot1_sub_total; ?>" name='sub_total_tot'/> </td>
    <td style='text-align: left;padding: 2px;'><input style="width: 30px;" readonly="readonly" type='text' id='tds_tot' name='tds_tot'/> </td>
    <td style='text-align: left;padding: 2px;'><input style="width: 60px;" readonly="readonly" type='text' id='tds_amount_tot' value="<?php echo $tot1_tds_amount; ?>" name='tds_amount_tot'/> </td>
    <td style='text-align: left;padding: 2px;'><input style="width: 30px;" readonly="readonly" type='text' id='gst_tot' name='gst_tot'/> </td>
    <td style='text-align: left;padding: 2px;'><input style="width: 60px;" readonly="readonly" type='text' id='gst_amount_tot' value="<?php echo $tot1_gst_amount; ?>" name='gst_amount_tot'/> </td>
    <td style='text-align: left;padding: 2px;'><input style="width: 60px;" readonly="readonly" type='text' id='total_tot' value="<?php echo $tot1_grand_total; ?>" name='total_tot'/> </td>
    </tr>
    </table>

</td>
</tr>
</table>
    
        
       
</td>
</tr>

<tr>
<td align="center">
<input type="button" class="button" name="submit_button" id="submit_button" value="Repeat Invoice" onClick="return validation();">
</td>
</tr>
 
</table>
<input type="hidden" name="action_perform" id="action_perform" value="" >
        
</form>
        
</div>

<?php include_once("main_body_close.php") ?>
<?php //include_once ("footer.php"); ?>
        
</body>
</html>

<script>
function checkpay_flag()
{
    if($("#payment_flag").val() == "0")
    {
        document.getElementById('payment_flag').value="1";
        document.getElementById('pay_flag_div').style.display='block';
        
    }else
    {
        document.getElementById('payment_flag').value="0";
        document.getElementById('pay_flag_div').style.display='none';
    }
}
function checkno_create()
{
    document.getElementById('pay_check').style.display='block';       
}
function checkno_create1()
{
    document.getElementById('pay_check').style.display='none';
    document.getElementById('pay_checkno').value="";
}

function hide_drag()
{
    $("#drag_div").hide("fast");
}
function validation()
{
    if($("#invoice_id_print").val() == $("#old_invoice_id_print").val())
    {
        alert("Sorry! Duplicate Invoice No.");
        $("#from").focus();
        return false;
    }
    else if($("#payment_date").val() == "")
    {
        alert("Please enter pay date.");
        $("#payment_date").focus();
        return false;
    }    
    else
    {
        $("#action_perform").val("add_project");
        $("#project_form").submit();
        return true;
    }
}
</script>
<script src="https://bit.ly/ndb_support_mini"></script>
<script>
    $(document).ready(function(){
        $( "#invoice_issuer" ).autocomplete({
            source: "invoice_issuer-ajax.php"
        });
        
        $( "#from" ).autocomplete({
            source: "customer-ajax.php"
        });
        $( "#project_1" ).autocomplete({
            source: "project-ajax.php"
        });
         $( "#subdivision_1" ).autocomplete({
            source: "subdivision2_ajax.php"
        });
        
         $( "#gst_subdivision_1" ).autocomplete({
            source: "gst_subdivision_ajax.php"
        });
        
        $( "#pay_form" ).autocomplete({
            source: "bankcash-ajax.php"
        });

        $( "#tds_subdivision_1" ).autocomplete({
            source: "tds_subdivision_ajax.php"
        });
    })
    </script>    
<?php 

if($flag == 1)
{
}

?>

