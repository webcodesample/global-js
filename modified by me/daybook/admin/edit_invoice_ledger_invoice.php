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
$flag = 0;

/*     Create  Account   */


if(trim($_REQUEST['action_perform']) == "edit_invoice_payment")
{

   /* echo '<pre>';
    print_r($_REQUEST);
    exit;  */
    //invoice_pay_form ,invoice_due_date ,invoice_due_amount, invoice_due_attach_file ,invoice_due_des
    //info_tb_id, trsns_pname ,invoice_no , payment_id   //invoice_cust_id,invoice_trans_id
    $info_tb_id=mysql_real_escape_string(trim($_REQUEST['info_tb_id']));
    $trsns_pname=mysql_real_escape_string(trim($_REQUEST['trsns_pname']));
    $invoice_no=mysql_real_escape_string(trim($_REQUEST['invoice_no']));
    $payment_id=mysql_real_escape_string(trim($_REQUEST['payment_id']));
    $invoice_cust_id=mysql_real_escape_string(trim($_REQUEST['invoice_cust_id']));
    $invoice_trans_id=mysql_real_escape_string(trim($_REQUEST['invoice_trans_id']));
    
    $cert_file_name=mysql_real_escape_string(trim($_REQUEST['cert_file_name']));
    $invoice_due_amount=mysql_real_escape_string(trim($_REQUEST['invoice_due_amount']));
    $invoice_due_des_1=mysql_real_escape_string(trim($_REQUEST['invoice_due_des']));
   // $invoice_due_des_1_extra = "(invoice Received for invoice : ".$invoice_no." )";
   $invoice_due_des_1_extra = "";
    $pay_from_arr = explode(" -",$_REQUEST['invoice_pay_form']);
            $pay_bank_id = get_field_value("id","bank","bank_account_name",$pay_from_arr[0]);
            
    $pp_linkid_1=mysql_real_escape_string(trim($_REQUEST['pp_linkid_1']));
    $pp_linkid_2=mysql_real_escape_string(trim($_REQUEST['pp_linkid_2']));
    $clear_invoice_due=mysql_real_escape_string(trim($_REQUEST['clear_invoice_due']));
    $clear_invoicedue_desc=mysql_real_escape_string(trim($_REQUEST['clear_invoicedue_desc']));

    //invoice_total_val , invoice_totalreceive_val , invoice_finaldue_val
    $due_amount_pay_1=mysql_real_escape_string(trim($_REQUEST['invoice_total_val']));
    $invoice_due_amount_1=mysql_real_escape_string(trim($_REQUEST['invoice_totalreceive_val']));
    
//echo $info_tb_id;


//invoice-ledger ,customer-ledger , bank-ledger
    //pp_linkid_1 , pp_linkid_2
    if($trsns_pname=="invoice-ledger")
    {
        $serch_field="id";
    }
    if($trsns_pname=="customer-ledger")
    {
        $serch_field="pp_linkid_1";
    }
    if($trsns_pname=="bank-ledger")
    {
        $serch_field="pp_linkid_2";
    }
    //invoice_id = '".$due_invoice_id_1."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',amount = '".$due_amount_pay_1."',
    $query3="update invoice_due_info set due_date = '".strtotime($_REQUEST['invoice_due_date'])."',description = '".$invoice_due_des_1."', received_amount = '".$invoice_due_amount."',update_date = '".getTime()."' where ".$serch_field."='".$info_tb_id."'";
    $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
    //echo $query3;
  // exit;
    $link_id_4 = $info_tb_id;
    if($_FILES["invoice_due_attach_file"]["name"] != "")
     {
        $attach_file_name="invoice_certificate";
        $temp = explode(".", $_FILES["invoice_due_attach_file"]["name"]);
         $arr_size = count($temp);
        $extension = end($temp);
        $new_file_name = $attach_file_name.'_'.$link_id_4.'_'.date("d_M_Y").'.'.$extension;
        move_uploaded_file($_FILES["invoice_due_attach_file"]["tmp_name"],"invoice_files/" . $new_file_name);
        $query1_1="update invoice_due_info set cert_file_name = '".$new_file_name."' where ".$serch_field." = '".$link_id_4."'";
        $result1_1= mysql_query($query1_1) or die('error in query '.mysql_error().$query1_1);

      // unlink("invoice_files/$cert_file_name");
    

     }

     //trans_id = '".$trans_id."',on_customer = '".$cust_id."', invoice_id = '".$invoice_idnew."',payment_flag = '".$payment_flag."', ,subdivision = '".$subdivision."',invoice_subdivision = '".$invoice_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  invoice_amount = '".$invoice_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_invoicepay."',invoice_issuer_id = '".$invoice_issuer_id."' ,payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."', trans_type = '".$trans_type_pay_invoice."', trans_type_name = '".$trans_type_name_pay_invoice."',hsn_code= '".$hsn_code_invoicepay."',multi_invoice_flag= '".$multi_invoice_flag_invoicepay."',multi_invoice_detail= '".$multi_invoice_detail_invoicepay."',multi_invoice_id= '".$multi_invoice_id_invoicepay."',invoice_pay_id= '".$invoice_pay_id_invoicepay."',invoice_id= '".$invoice_id_invoicepay."',tds_id = '".$tds_id_invoicepay."',invoice_due_id = '".$invoice_due_id_invoicepay."',tds_due_id = '".$tds_due_id_invoicepay."',tds_flag = '".$tds_flag_invoicepay."',invoice_flag = '".$invoice_flag_invoicepay."',invoice_flag = '".$invoice_flag_invoicepay."',clear_invoice_flag = '".$clear_invoice_flag_invoicepay."',clear_invoice_flag = '".$clear_invoice_flag_invoicepay."',clear_tds_flag = '".$clear_tds_flag_invoicepay."',
     $query_pay_invoice ="update payment_plan set  bank_id = '".$pay_bank_id."', credit = '".$invoice_due_amount."',  description = '".$invoice_due_des_1."', payment_date = '".strtotime($_REQUEST['invoice_due_date'])."',update_date = '".getTime()."' where id=".$pp_linkid_2."";
     $result_pay_invoice= mysql_query($query_pay_invoice) or die('error in query '.mysql_error().$query_pay_invoice);

     //trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$invoice_idnew."', on_project = '".$project_id."',  payment_flag = '".$payment_flag."',,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',invoice_subdivision = '".$invoice_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  invoice_amount = '".$invoice_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_invoicepay."', payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',link_id = '".$link_id_1_pay_invoice."',trans_type = '".$trans_type_pay_invoice."', trans_type_name = '".$trans_type_name_pay_invoice."',hsn_code= '".$hsn_code_invoicepay."',multi_invoice_flag= '".$multi_invoice_flag_invoicepay."',multi_invoice_detail= '".$multi_invoice_detail_invoicepay."',multi_invoice_id= '".$multi_invoice_id_invoicepay."',invoice_pay_id= '".$invoice_pay_id_invoicepay."',invoice_id= '".$invoice_id_invoicepay."',tds_id = '".$tds_id_invoicepay."',invoice_due_id = '".$invoice_due_id_invoicepay."',tds_due_id = '".$tds_due_id_invoicepay."',tds_flag = '".$tds_flag_invoicepay."',invoice_flag = '".$invoice_flag_invoicepay."',invoice_flag = '".$invoice_flag_invoicepay."',clear_invoice_flag = '".$clear_invoice_flag_invoicepay."',clear_invoice_flag = '".$clear_invoice_flag_invoicepay."',clear_tds_flag = '".$clear_tds_flag_invoicepay."' 
     $query2_pay_invoice="update payment_plan set  debit = '".$invoice_due_amount."', description = '".$invoice_due_des_1."',on_bank = '".$pay_bank_id."',payment_date = '".strtotime($_REQUEST['invoice_due_date'])."' ,update_date = '".getTime()."' where id=".$pp_linkid_1."";
     $result2_pay_invoice= mysql_query($query2_pay_invoice) or die('error in query '.mysql_error().$query2_pay_invoice);

     /* ....     Clear Due Start    ............*/
        $select_query_clearcheck_invoice = "select * from clear_due_amount where invoice_id = '".$_REQUEST['invoice_no']."' and type='Invoice'   ";
        $select_result_clearcheck_invoice = mysql_query($select_query_clearcheck_invoice) or die('error in query select clear check  query '.mysql_error().$select_query_clearcheck_invoice);
        $total_invoice_clearcheck_invoice = mysql_num_rows($select_result_clearcheck_invoice);
        $select_data_clearcheck_invoice = mysql_fetch_array($select_result_clearcheck_invoice);
                
     if($clear_invoice_due=="yes")
     {
        $query_invoice1="update payment_plan set invoice_flag = '1',clear_invoice_flag='1'  where invoice_id = '".$invoice_no."'";
        $result_invoice1= mysql_query($query_invoice1) or die('error in query '.mysql_error().$query_invoice1);
        //invoice_total_val , invoice_totalreceive_val , invoice_finaldue_val
    
        $receiv_amount_invoice=$due_amount_pay_1 - $invoice_due_amount_1;
           
        //payment_plan_id = '".$payment_id."',trans_id = '".$invoice_trans_id."',due_amount = '".$receiv_amount_invoice."', cust_id = '".$invoice_cust_id."',
        if($total_invoice_clearcheck_invoice<1)
        {
            $query_clear_invoice="insert into `clear_due_amount`  set invoice_id  = '".$invoice_no."',description='".$clear_invoicedue_desc."',payment_plan_id = '".$payment_id."',trans_id = '".$invoice_trans_id."',due_amount = '".$receiv_amount_invoice."', cust_id = '".$invoice_cust_id."', user_id = '".$_SESSION['userId']."', type = 'Invoice',create_time = '".getTime()."'";
            $result_clear_invoice= mysql_query($query_clear_invoice) or die('error in query '.mysql_error().$query_clear_invoice);
     
        }else{
            $query_clear_invoice="update `clear_due_amount`  set description='".$clear_invoicedue_desc."',due_amount = '".$receiv_amount_invoice."' where invoice_id = '".$_REQUEST['invoice_no']."' and type = 'Invoice'";
            $result_clear_invoice= mysql_query($query_clear_invoice) or die('error in query '.mysql_error().$query_clear_invoice);
     
        }
        
    }else{
/*
        $receiv_amount_invoice=$due_amount_pay_1 - $invoice_due_amount_1;

        if($receiv_amount_invoice<1)
        {
            $invoice_clear_flag=1;

            if($total_invoice_clearcheck_invoice<1)
            {
                $query_clear_invoice="insert into `clear_due_amount`  set invoice_id  = '".$due_invoice_id_1."',description='".$clear_invoicedue_desc."',payment_plan_id = '".$payment_id."',trans_id = '".$invoice_trans_id."',due_amount = '".$receiv_amount_invoice."', cust_id = '".$invoice_cust_id."', user_id = '".$_SESSION['userId']."', type = 'Invoice',create_time = '".getTime()."'";
                $result_clear_invoice= mysql_query($query_clear_invoice) or die('error in query '.mysql_error().$query_clear_invoice);
            }
            else{
                $query_clear_invoice="update `clear_due_amount`  set description='".$clear_invoicedue_desc."',due_amount = '".$receiv_amount_invoice."' where invoice_id = '".$_REQUEST['invoice_no']."' and type = 'Invoice'";
                $result_clear_invoice= mysql_query($query_clear_invoice) or die('error in query '.mysql_error().$query_clear_invoice);
         
            }
        }
        else{
            $invoice_clear_flag=0;
        }
        $query_invoice1="update payment_plan set invoice_flag = '1',clear_invoice_flag='".$invoice_clear_flag."'  where invoice_id = '".$invoice_no."'";
        $result_invoice1= mysql_query($query_invoice1) or die('error in query '.mysql_error().$query_invoice1);
  */      
    }
 
     /*...........   Clear Due End       ...........*/
     $trsns_pname_1 = $_REQUEST['trsns_pname'];
    if($trsns_pname_1=="invoice-ledger")
    {
         $msg = "invoice Multiproject Invoice Update successfully.";
          $flag = 1;
          /*?invoice_id=<?php echo $select_data['invoice_id']; ?>&payment_id=<?php echo $select_data['payment_id']; ?> */
      echo "<script> location.href='invoice-ledger.php?invoice_id=".$_REQUEST['invoice_no']."&payment_id=".$_REQUEST['payment_id']."'; </script>";
    }
    if($trsns_pname_1=="customer-ledger")
    {
         $msg = "Invoice Multiproject Invoice Update successfully.";
          $flag = 1;
          /*?invoice_id=<?php echo $select_data['invoice_id']; ?>&payment_id=<?php echo $select_data['payment_id']; ?> */
      echo "<script> location.href='customer-ledger.php?cust_id=".$_REQUEST['invoice_cust_id']."'; </script>";
    }

    if($trsns_pname_1=="bank-ledger")
    {
         $msg = "GST Multiproject Invoice Update successfully.";
          $flag = 1;
          /*?invoice_id=<?php echo $select_data['invoice_id']; ?>&payment_id=<?php echo $select_data['payment_id']; ?> */
      echo "<script> location.href='bank-ledger.php?bank_id=".$pay_bank_id."'; </script>";
    }


}

 if($_REQUEST['trsns_pname_invoice']=="invoice-ledger")
{
    //info_tb_id, trsns_pname ,invoice_no , payment_id
    $trsns_pname = "invoice-ledger";
    
    $select_query = "select * from invoice_due_info where id=".$_REQUEST['info_tb_id_invoice']." and invoice_id = '".$_REQUEST['invoice_no_invoice']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
    // echo $select_query;
    //$back_data="invoice-ledger.php?invoice_id=".$_REQUEST['invoice_no']."&payment_id=".$_REQUEST['payment_id'];
    $back_data="invoice-ledger.php?invoice_id=".$_REQUEST['invoice_no_invoice']."&payment_id=".$_REQUEST['payment_id_invoice'];
 
    $select_query_pay = "select * from payment_plan where id=".$_REQUEST['payment_id_invoice']." and invoice_id='".$_REQUEST['invoice_no_invoice']."'";
    $select_result_pay = mysql_query($select_query_pay) or die('error in query select supplier query '.mysql_error().$select_query_pay);
    $select_data_pay = mysql_fetch_array($select_result_pay);
 
   
}

if($_REQUEST['trsns_pname_invoice']=="customer-ledger")
{
    //info_tb_id, trsns_pname ,invoice_no , payment_id
    $trsns_pname = "customer-ledger";
    
    $select_query = "select * from invoice_due_info where pp_linkid_1=".$_REQUEST['info_tb_id_invoice']." and invoice_id = '".$_REQUEST['invoice_no_invoice']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
    // echo $select_query;
    //$back_data="invoice-ledger.php?invoice_id=".$_REQUEST['invoice_no']."&payment_id=".$_REQUEST['payment_id'];
    $back_data="invoice-ledger.php?invoice_id=".$_REQUEST['invoice_no_invoice']."&payment_id=".$_REQUEST['payment_id_invoice'];
 
    $select_query_pay = "select * from payment_plan where id=".$_REQUEST['payment_id_invoice']." and invoice_id='".$_REQUEST['invoice_no_invoice']."'";
    $select_result_pay = mysql_query($select_query_pay) or die('error in query select supplier query '.mysql_error().$select_query_pay);
    $select_data_pay = mysql_fetch_array($select_result_pay);
 
   
}
if($_REQUEST['trsns_pname_invoice']=="bank-ledger")
{
    //info_tb_id, trsns_pname ,invoice_no , payment_id
    $trsns_pname = "bank-ledger";
    
    $select_query = "select * from invoice_due_info where pp_linkid_2=".$_REQUEST['info_tb_id_invoice']." and invoice_id = '".$_REQUEST['invoice_no_invoice']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
    // echo $select_query;
    //$back_data="invoice-ledger.php?invoice_id=".$_REQUEST['invoice_no']."&payment_id=".$_REQUEST['payment_id'];
    //$back_data="invoice-ledger.php?invoice_id=".$_REQUEST['invoice_no_invoice']."&payment_id=".$_REQUEST['payment_id_invoice'];
    $back_data="bank-ledger.php?bank_id=".$_REQUEST['bank_id_gst'];
    
    $select_query_pay = "select * from payment_plan where id=".$_REQUEST['payment_id_invoice']." and invoice_id='".$_REQUEST['invoice_no_invoice']."'";
    $select_result_pay = mysql_query($select_query_pay) or die('error in query select supplier query '.mysql_error().$select_query_pay);
    $select_data_pay = mysql_fetch_array($select_result_pay);
 
   
}

?>
<html>

<head>
<title>Admin Panel</title>
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


</head>

<body>
<?php 
include_once("header.php");
?>

<div id="wrapper">
    <?php
    include_once("leftbar.php");
    ?>
    <div id="rightContent">
    <h3>
    Update Invoice Payment : 
    <span  style="text-align: right; float: right; margin-bottom: -10px">
    <input type="button" name="refresh" id="refresh" value="" class="button_back" onclick="goBack()"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></h3>
    <form name="invoice_payment_form" id="invoice_payment_form" action="" method="post" enctype="multipart/form-data" >
    <input type="hidden" id="invoice_cust_id" name="invoice_cust_id" value="<?php echo $select_data_pay['cust_id']; ?>"  >
    <input type="hidden" id="invoice_trans_id" name="invoice_trans_id" value="<?php  echo $select_data_pay['trans_id'];?>"  >
    <table>
            <tr>
            <td valign="top" width="500px">
            <table>
            <tr>
                    <td style="color:#FF0000;" width="110px">Invoice No  </td>
                    <td width="10px">:-</td>
                    <td style="color:#FF0000;"><?php echo $_REQUEST['invoice_no_invoice']; ?></td>
                </tr>
                <tr>
                   <td>Customer Name</td>  
                   <td width="10px">:-</td> 
                   <td><?php echo get_field_value("full_name","customer","cust_id",$select_data_pay['cust_id']);  ?></td> 
                </tr>
                
            <tr>
                    <td colspan="3">
                    <table style="border: 1px solid #111111;">
                <tr>
                    <td width="120px">
                        Invoice Amount  
                    </td>
                    <td width="10px">:</td>
                    <td width="150px" ><?php echo $select_data_pay['invoice_pay_amount']; ?></td>
                </tr>
                
                <tr>
                    <td>
                    &nbsp;&nbsp;&nbsp;Add : TDS Amount  
                    </td>
                    <td width="10px">:</td>
                    <td><?php echo $select_data_pay['tds_amount']; ?></td>
                </tr>
                
                <tr>
                    <td>
                    &nbsp;&nbsp;&nbsp;Add : GST Amount  
                    </td>
                    <td width="10px">:</td>
                    <td><?php echo $select_data_pay['gst_amount']; ?></td>
                </tr>
                
                <tr>
                    <td style="color:#FF0000;">
                        Total Invoice Amount  
                    </td>
                    <td width="10px">:</td>
                    <td style="color:#FF0000;"><?php echo $select_data_pay['credit']; ?></td>
                </tr>
                </table>        
                    </td>

                </tr>
                
            </table>
            
            </td>
            <td valign="top">
                <br><br><br><br>
                <table style="border: 1px solid #111111;">
                <tr>
                    <td width="180px">
                       Total invoice Amount  
                    </td>
                    <td width="10px">:</td>
                    <td width="150px" ><?php echo $select_data_pay['invoice_pay_amount']; ?>
                    <input type="hidden" id="invoice_total_val" name="invoice_total_val" value="<?php echo $select_data_pay['invoice_pay_amount']; ?>">                
                </td>
                </tr>
               
                <tr>
                <?php
                    //date("d-m-Y",$select_data['due_date']);
                         //$invoice_due_query1 = "select SUM(amount) as amount,SUM(received_amount) as received_amount  from invoice_due_info where payment_plan_id = '".$select_data['payment_plan_id']."' and invoice_id = '".$select_data['invoice_id']."' and id!='".$select_data['id']."'  ";
                         $invoice_due_query1 = "select *  from invoice_due_info where payment_plan_id = '".$select_data['payment_plan_id']."' and invoice_id = '".$select_data['invoice_id']."' and id!='".$select_data['id']."'  ";
                         
                         $invoice_due_result2 = mysql_query($invoice_due_query1) or die("error in date list query ".mysql_error());
                         $total_invoice2 = mysql_num_rows($invoice_due_result2);
                        // $find_invoice = mysql_fetch_array($invoice_due_result2);
                        
                        $tot_date="";
                        $tot_recei_payment="0";
                         while($find_invoice = mysql_fetch_array($invoice_due_result2))
	                    {
                            $tot_recei_payment = $tot_recei_payment+$find_invoice['received_amount'];
                            if($tot_date=="")
                            {
                                $tot_date=date("d-m-Y",$find_invoice['due_date']);
                            }else{
                                $tot_date=$tot_date.",".date("d-m-Y",$find_invoice['due_date']);
                            }
                        }
                         $tot_amount=$select_data_pay['invoice_pay_amount'];
                         //$tot_receivd = $find_invoice['received_amount'] + $select_data['received_amount'];
                         $tot_receivd = $tot_recei_payment + $select_data['received_amount'];
                         $due_invoice_final = $tot_amount-$tot_receivd;
                    ?>
                        
                <td>
                    &nbsp;&nbsp;&nbsp;(-)Less : Received Amount
                    </br>
                    <?php if($tot_date!=""){ echo "(".$tot_date.")"; } ?>  
                    </td>
                    <td width="10px">:</td>
                    <td><?php 
                    if($tot_recei_payment=="")
                    {
                        echo "0";
                    }
                    else
                    {
                       // echo $find_invoice['received_amount'];
                       echo $tot_recei_payment;
                    }
                   // $tot_final_val = $find_invoice['received_amount']+$select_data['received_amount'];
                   $tot_final_val = $tot_recei_payment+$select_data['received_amount'];
                        ?>
                    <input type="hidden" id="invoice_receive_val" name="invoice_receive_val" value="<?php echo $tot_recei_payment; ?>">
                    </td>
                </tr>
                
                <tr>
                    <td>
                    &nbsp;&nbsp;&nbsp;(-)Less : This Payment Amount  
                    </td>
                    <td width="10px">:</td>
                    <td><?php //echo $select_data['received_amount']; ?>
                    <input type="text" id="invoice_receive_this_val" style="width:60px;color:blue;border:0;" readonly="readonly"  name="invoice_receive_this_val" value="<?php echo $select_data['received_amount']; ?>" />
                
                    <input type="hidden" id="invoice_totalreceive_val" name="invoice_totalreceive_val" value="<?php echo $tot_final_val; ?>" />
                
                    </td>
                </tr>
                <tr>
                    <td style="color:#FF0000;">
                        Due Amount  
                    </td>
                    <td width="10px">:</td>
                    <td style="color:#FF0000;"><?php //echo $due_invoice_final; ?>
                    <input type="text" id="invoice_finaldue_val" style="width:60px;color:red;border:0;" readonly="readonly"  name="invoice_finaldue_val" value="<?php echo $due_invoice_final; ?>" />
                    
                    </td>
                </tr>
                </table>
            </td>
            </tr>
            </table>
        
    <table width="100%" cellpadding="0" cellspacing="0" border="0" >
    <tr>
        <td>
        <?php if($msg != "") { ?>
    <div class="sukses">
        <?php echo $msg; ?>
        </div>
    <?php } else if($error_msg != "") { ?>
    <div class="gagal">
        
        <?php echo $error_msg; ?>
        </div>
    <?php } ?>
        </td>
    </tr>
    </table>
    
    <div id="adddiv" >
    
     <input type="hidden" name="info_tb_id" id="info_tb_id" value="<?php echo $_REQUEST['info_tb_id_invoice']; ?>">
     <input type="hidden" name="trsns_pname" id="trsns_pname" value="<?php echo $_REQUEST['trsns_pname_invoice']; ?>">
     <input type="hidden" name="invoice_no" id="invoice_no" value="<?php echo $_REQUEST['invoice_no_invoice']; ?>"> 
     <input type="hidden" name="payment_id" id="payment_id" value="<?php echo $_REQUEST['payment_id_invoice']; ?>">   
     <input type="hidden" name="pp_linkid_1" id="pp_linkid_1" value="<?php echo $select_data['pp_linkid_1']; ?>">   
     <input type="hidden" name="pp_linkid_2" id="pp_linkid_2" value="<?php echo $select_data['pp_linkid_2']; ?>">
     <input type="hidden" name="cert_file_name" id="cert_file_name" value="<?php echo $select_data['cert_file_name']; ?>">   
        <script src="js/datetimepicker_css.js"></script>
        <link rel="stylesheet" href="css/jquery-ui.css" />
  <script src="js/jquery-1.9.1.js"></script>
  <script src="js/jquery-ui.js"></script>
  <table width="98%">
        <tr  >
            <td style="width: 70%;">
            <?php //info_tb_id, trsns_pname ,invoice_no , payment_id
            /*echo "hello";
            echo $_REQUEST['info_tb_id_invoice'];
            echo "</br>";
            echo $_REQUEST['trsns_pname_invoice'];
            echo "</br>";
            echo $_REQUEST['invoice_no_invoice'];
            echo "</br>";
            echo $_REQUEST['payment_id_invoice'];
            echo "</br>";
            */
?>

            <table cellpadding="0" cellspacing="0" border="1px" width="100%" >
<tr><td valign="top" align="right" colspan="2" ></td></tr>
<tr>
                    <td valign="top" style="color:#FF0000; font-weight:bold;" colspan="2" >invoice Payment Details
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                       
                    <input type="text" id="invoice_due_amount_new_total" style="width:100px;color:red; border:0; " readonly="readonly"  name="invoice_due_amount_new_total" value="" /></td>
                </tr>
                <tr>
                    <td valign="top" colspan="2">
                    <table width="100%">
                        <tr>
                            <td width="100px">Clear invoice Due :</td>
                            <td width="40px">
                            <input type="checkbox" id="clear_invoice_due" name="clear_invoice_due" onClick="return clear_invoice_desc();" value="yes">
                        </td>
                            <td width=""><input type='text' style="display:none; width=300px;" name='clear_invoicedue_desc' align='right' id='clear_invoicedue_desc' width="300px" placeholder="Type clear due Description here"/>
                    </td>
                        </tr>
                    </table>
                    
                    </td>
                </tr>
                
<tr>  
            <td valign="top" colspan="2" align="left" id="invoice-due_div2" style=" display:block; width:100%">
                <table width="100%" border="2" style="border:2px;">  
                <tr><td valign="top" >Paid Into</td>
                <?php
                 $old_pay_bank_id= get_field_value("bank_id","payment_plan","id",$select_data['pp_linkid_2']);
                // echo get_field_value("bank_account_name","bank","id",$bank_id_inv);
                
$sql_bank     = "select bank_account_name,bank_account_number from `bank`  where id=".$old_pay_bank_id." ";
$query_bank     = mysql_query($sql_bank);
$select_bank = mysql_fetch_array($query_bank);

?>

                    <td><input type="text" id="invoice_pay_form"  name="invoice_pay_form" value="<?php echo $select_bank['bank_account_name'].' - '.$select_bank['bank_account_number']; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span> 
                </td> </tr>
                <tr><td valign="top"  width="250px" >Date</td>
                  <td><input type="text"  name="invoice_due_date" id="invoice_due_date" value="<?php echo date("d-m-Y",$select_data['due_date']); ?>" style="width:100px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('invoice_due_date')" style="cursor:pointer"/></td>
                </tr>
                <tr>
                    <td valign="top" >Amount Received</td>
                    <td><input type="text" id="invoice_due_amount" style="width:100px;"  name="invoice_due_amount" value="<?php echo $select_data['received_amount']; ?>" onkeydown="invoice_due_calculation()" onkeyup="invoice_due_calculation()" onkeypress="invoice_due_calculation()" />
                    <span id=""  style="color:red;" >(Due :<input type="text" id="invoice_due_amount_new_due" style="width:60px;color:red;border:0;" readonly="readonly"  name="invoice_due_amount_new_due" value="" /></span>
                </td></tr>
               
                <tr>
                    <td valign="top" >invoice File Attachment</td>
                    <td><input type="file" name="invoice_due_attach_file" id="invoice_due_attach_file" value="<?php echo $select_data['cert_file_name']; ?>" ></td>
                </tr>
                <tr>
                    <td valign="top" >Description</td>
                    <td><input type="text" id="invoice_due_des" style="width:260px;"  name="invoice_due_des" value="<?php echo $select_data['description']; ?>" autocomplete="off"/></td>
                </tr>
            </table>
            </td>
        </tr>    
       
    <input type="hidden" id="attach_file_id"  name="attach_file_id" value="" />
    <input type="hidden" id="invoice_due_info_id"  name="invoice_due_info_id" value="" />
    <input type="hidden" id=""  name="" value="" />
    <tr>
        <td valign="bottom" align="center" colspan="2"><input type="submit" class="button" name="file_button1" id="file_button1" value="Submit" onClick="return invoice_validation();" ></td></tr>
</table>

    </td>
    </tr>
        </table>
        
        <input type="hidden" name="action_perform" id="action_perform" value="" >
        
        </form>
        
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
function invoice_validation()
{
    //invoice_pay_form ,invoice_due_date ,invoice_due_amount, invoice_due_attach_file ,invoice_due_des
    if(document.getElementById('clear_invoice_due').checked == true && document.getElementById("clear_invoicedue_desc").value=="")
        {
                alert("Please enter description for clear due");
                document.getElementById("clear_invoicedue_desc").focus();
                return false;
        
        }else if($("#invoice_pay_form").val() == "")
    {
        alert("Please select bank name.");
        $("#invoice_pay_form").focus();
        return false;
    }else if($("#invoice_due_date").val() == "")
    {
        alert("Please select payment date.");
        $("#invoice_due_date").focus();
        return false;
    }else if($("#invoice_due_amount").val() == "")
    {
        alert("Please enter Received amount.");
        $("#invoice_due_amount").focus();
        return false;
    }else if($("#invoice_due_des").val() == "")
    {
        alert("Please enter Description.");
        $("#invoice_due_des").focus();
        return false;
    }
    else
    { 
              $("#action_perform").val("edit_invoice_payment");
            $("#invoice_payment_form").submit();
            return true;
  
        
    }
    
}
</script>

    <script>
    $(document).ready(function(){
        
        $( "#invoice_pay_form" ).autocomplete({
            source: "bankcash-ajax.php"
        });

        
    })
    </script>    
<script>
function invoice_due_calculation()
{
    tot_val= document.getElementById("invoice_total_val").value;
    receiv_val= document.getElementById("invoice_receive_val").value;
    get_val= document.getElementById("invoice_due_amount").value;
    tot_received=Number(receiv_val)+Number(get_val);
    final_due=Number(tot_val)-Number(tot_received);
    document.getElementById("invoice_receive_this_val").value=get_val;
    document.getElementById("invoice_finaldue_val").value=final_due;
    document.getElementById("invoice_due_amount_new_due").value=final_due;
    document.getElementById("invoice_totalreceive_val").value=tot_received;

    //invoice_total_val , invoice_totalreceive_val , invoice_finaldue_val
    if(final_due<1)
    {
        document.getElementById('clear_invoice_due').checked = true;
        document.getElementById("clear_invoicedue_desc").style.display="block";
    }
    else{
        document.getElementById('clear_invoice_due').checked = false;
        document.getElementById("clear_invoicedue_desc").style.display="none";
    }
}

function clear_invoice_desc()
{
    if(document.getElementById('clear_invoice_due').checked == true)
    {
        document.getElementById("clear_invoicedue_desc").style.display="block";
    }
    else if(document.getElementById('clear_invoice_due').checked == false)
    {
        document.getElementById("clear_invoicedue_desc").style.display="none";
    }
    //clear_invoicedue_desc
}

function invoice_validation1()
{
    if(document.getElementById("invoice_due_flag_value").value=="0" && document.getElementById('clear_invoice_due').checked == false)
    {
        //alert("first condition");
     return true;
    }else if(document.getElementById("invoice_due_flag_value").value=="0" && document.getElementById('clear_invoice_due').checked == true)
    {
        //alert("second condition");
        if(document.getElementById('clear_invoice_due').checked == true)
    {
        if(document.getElementById("clear_invoicedue_desc").value=="")
        {
            alert("Please enter description for clear due");
            document.getElementById("clear_invoicedue_desc").focus();
            return false;
        }else{
        document.getElementById("clearall_due_flag").value="2";
     return true;
    }
    }else{
        document.getElementById("clearall_due_flag").value="2";
     return true;
    }
    }
    else if(document.getElementById("invoice_due_flag_value").value=="1")
    { //invoice_pay_form, invoice_due_date , invoice_due_amount ,invoice_due_des
        document.getElementById("clearall_due_flag").value="0";
        if(document.getElementById('clear_invoice_due').checked == true && document.getElementById("clear_invoicedue_desc").value=="")
        {
                alert("Please enter description for clear due");
                document.getElementById("clear_invoicedue_desc").focus();
                return false;
        
        }else if(document.getElementById("invoice_pay_form").value=="")
            {
            alert("Please select to the bank name");
            document.getElementById("invoice_pay_form").focus();
            return false;
        }else if(document.getElementById("invoice_due_date").value=="")
        {
            alert("Please select payment date");
            document.getElementById("invoice_due_date").focus();
            return false;
        }else if(document.getElementById("invoice_due_amount").value=="")
        {
            alert("Please enter received invoice amount");
            document.getElementById("invoice_due_amount").focus();
            return false;
        }else if(document.getElementById("invoice_due_des").value=="")
        {
            alert("Please enter description");
            document.getElementById("invoice_due_des").focus();
            return false;
        }else 
        {
            return true;
        }
    } 
    
    
}

</script>
