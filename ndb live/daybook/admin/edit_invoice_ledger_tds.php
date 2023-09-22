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


if(trim($_REQUEST['action_perform']) == "edit_tds_payment")
{

   /* echo '<pre>';
    print_r($_REQUEST);
    exit;*/  
    //tds_pay_form ,tds_due_date ,tds_due_amount, tds_due_attach_file ,tds_due_des
    //info_tb_id, trsns_pname ,invoice_no , payment_id   //tds_cust_id,tds_trans_id
    $info_tb_id=mysql_real_escape_string(trim($_REQUEST['info_tb_id']));
    $trsns_pname=mysql_real_escape_string(trim($_REQUEST['trsns_pname']));
    $invoice_no=mysql_real_escape_string(trim($_REQUEST['invoice_no']));
    $payment_id=mysql_real_escape_string(trim($_REQUEST['payment_id']));
    $tds_cust_id=mysql_real_escape_string(trim($_REQUEST['tds_cust_id']));
    $tds_trans_id=mysql_real_escape_string(trim($_REQUEST['tds_trans_id']));
    
    $cert_file_name=mysql_real_escape_string(trim($_REQUEST['cert_file_name']));
    $tds_due_amount=mysql_real_escape_string(trim($_REQUEST['tds_due_amount']));
    $tds_due_des_1=mysql_real_escape_string(trim($_REQUEST['tds_due_des']));
   // $tds_due_des_1_extra = "(tds Received for invoice : ".$invoice_no." )";
   $tds_due_des_1_extra = "";
    $pay_from_arr = explode(" -",$_REQUEST['tds_pay_form']);
            $pay_bank_id = get_field_value("id","bank","bank_account_name",$pay_from_arr[0]);
            
    $pp_linkid_1=mysql_real_escape_string(trim($_REQUEST['pp_linkid_1']));
    $pp_linkid_2=mysql_real_escape_string(trim($_REQUEST['pp_linkid_2']));
    $clear_tds_due=mysql_real_escape_string(trim($_REQUEST['clear_tds_due']));
    $clear_tdsdue_desc=mysql_real_escape_string(trim($_REQUEST['clear_tdsdue_desc']));

    //tds_total_val , tds_totalreceive_val , tds_finaldue_val
    $due_amount_pay_1=mysql_real_escape_string(trim($_REQUEST['tds_total_val']));
    $tds_due_amount_1=mysql_real_escape_string(trim($_REQUEST['tds_totalreceive_val']));
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



    //pp_linkid_1 , pp_linkid_2
    //invoice_id = '".$due_invoice_id_1."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',amount = '".$due_amount_pay_1."',
    $query3="update tds_due_info set due_date = '".strtotime($_REQUEST['tds_due_date'])."',description = '".$tds_due_des_1."', received_amount = '".$tds_due_amount."',update_date = '".getTime()."' where ".$serch_field."='".$info_tb_id."'";
    $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
    $link_id_4 = $info_tb_id;
    if($_FILES["tds_due_attach_file"]["name"] != "")
     {
        $attach_file_name="tds_certificate";
        $temp = explode(".", $_FILES["tds_due_attach_file"]["name"]);
         $arr_size = count($temp);
        $extension = end($temp);
        $new_file_name = $attach_file_name.'_'.$link_id_4.'_'.date("d_M_Y").'.'.$extension;
        move_uploaded_file($_FILES["tds_due_attach_file"]["tmp_name"],"tds_files/" . $new_file_name);
        $query1_1="update tds_due_info set cert_file_name = '".$new_file_name."' where ".$serch_field." = '".$link_id_4."'";
        $result1_1= mysql_query($query1_1) or die('error in query '.mysql_error().$query1_1);

       unlink("tds_files/$cert_file_name");
    

     }

     //trans_id = '".$trans_id."',on_customer = '".$cust_id."', invoice_id = '".$invoice_idnew."',payment_flag = '".$payment_flag."', ,subdivision = '".$subdivision."',tds_subdivision = '".$tds_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  tds_amount = '".$tds_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_tdspay."',invoice_issuer_id = '".$invoice_issuer_id."' ,payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."', trans_type = '".$trans_type_pay_tds."', trans_type_name = '".$trans_type_name_pay_tds."',hsn_code= '".$hsn_code_tdspay."',multi_invoice_flag= '".$multi_invoice_flag_tdspay."',multi_invoice_detail= '".$multi_invoice_detail_tdspay."',multi_invoice_id= '".$multi_invoice_id_tdspay."',invoice_pay_id= '".$invoice_pay_id_tdspay."',tds_id= '".$tds_id_tdspay."',tds_id = '".$tds_id_tdspay."',tds_due_id = '".$tds_due_id_tdspay."',tds_due_id = '".$tds_due_id_tdspay."',tds_flag = '".$tds_flag_tdspay."',tds_flag = '".$tds_flag_tdspay."',invoice_flag = '".$invoice_flag_tdspay."',clear_invoice_flag = '".$clear_invoice_flag_tdspay."',clear_tds_flag = '".$clear_tds_flag_tdspay."',clear_tds_flag = '".$clear_tds_flag_tdspay."',
     $query_pay_tds ="update payment_plan set  bank_id = '".$pay_bank_id."', credit = '".$tds_due_amount."',  description = '".$tds_due_des_1."', payment_date = '".strtotime($_REQUEST['tds_due_date'])."',update_date = '".getTime()."' where id=".$pp_linkid_2."";
     $result_pay_tds= mysql_query($query_pay_tds) or die('error in query '.mysql_error().$query_pay_tds);

     //trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$invoice_idnew."', on_project = '".$project_id."',  payment_flag = '".$payment_flag."',,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',tds_subdivision = '".$tds_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  tds_amount = '".$tds_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_tdspay."', payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',link_id = '".$link_id_1_pay_tds."',trans_type = '".$trans_type_pay_tds."', trans_type_name = '".$trans_type_name_pay_tds."',hsn_code= '".$hsn_code_tdspay."',multi_invoice_flag= '".$multi_invoice_flag_tdspay."',multi_invoice_detail= '".$multi_invoice_detail_tdspay."',multi_invoice_id= '".$multi_invoice_id_tdspay."',invoice_pay_id= '".$invoice_pay_id_tdspay."',tds_id= '".$tds_id_tdspay."',tds_id = '".$tds_id_tdspay."',tds_due_id = '".$tds_due_id_tdspay."',tds_due_id = '".$tds_due_id_tdspay."',tds_flag = '".$tds_flag_tdspay."',tds_flag = '".$tds_flag_tdspay."',invoice_flag = '".$invoice_flag_tdspay."',clear_invoice_flag = '".$clear_invoice_flag_tdspay."',clear_tds_flag = '".$clear_tds_flag_tdspay."',clear_tds_flag = '".$clear_tds_flag_tdspay."' 
     $query2_pay_tds="update payment_plan set  debit = '".$tds_due_amount."', description = '".$tds_due_des_1."',on_bank = '".$pay_bank_id."',payment_date = '".strtotime($_REQUEST['tds_due_date'])."' ,update_date = '".getTime()."' where id=".$pp_linkid_1."";
     $result2_pay_tds= mysql_query($query2_pay_tds) or die('error in query '.mysql_error().$query2_pay_tds);

     /* ....     Clear Due Start    ............*/
        $select_query_clearcheck_tds = "select * from clear_due_amount where invoice_id = '".$_REQUEST['invoice_no']."' and type='TDS'   ";
        $select_result_clearcheck_tds = mysql_query($select_query_clearcheck_tds) or die('error in query select clear check  query '.mysql_error().$select_query_clearcheck_tds);
        $total_invoice_clearcheck_tds = mysql_num_rows($select_result_clearcheck_tds);
        $select_data_clearcheck_tds = mysql_fetch_array($select_result_clearcheck_tds);
                
     if($clear_tds_due=="yes")
     {
        $query_tds1="update payment_plan set tds_flag = '1',clear_tds_flag='1'  where invoice_id = '".$invoice_no."'";
        $result_tds1= mysql_query($query_tds1) or die('error in query '.mysql_error().$query_tds1);
        //tds_total_val , tds_totalreceive_val , tds_finaldue_val
    
        $receiv_amount_tds=$due_amount_pay_1 - $tds_due_amount_1;
           
        //payment_plan_id = '".$payment_id."',trans_id = '".$tds_trans_id."',due_amount = '".$receiv_amount_tds."', cust_id = '".$tds_cust_id."',
        if($total_invoice_clearcheck_tds<1)
        {
            $query_clear_tds="insert into `clear_due_amount`  set invoice_id  = '".$invoice_no."',description='".$clear_tdsdue_desc."',payment_plan_id = '".$payment_id."',trans_id = '".$tds_trans_id."',due_amount = '".$receiv_amount_tds."', cust_id = '".$tds_cust_id."', user_id = '".$_SESSION['userId']."', type = 'TDS',create_time = '".getTime()."'";
            $result_clear_tds= mysql_query($query_clear_tds) or die('error in query '.mysql_error().$query_clear_tds);
     
        }else{
            $query_clear_tds="update `clear_due_amount`  set description='".$clear_tdsdue_desc."',due_amount = '".$receiv_amount_tds."' where invoice_id = '".$_REQUEST['invoice_no']."' and type = 'tds'";
            $result_clear_tds= mysql_query($query_clear_tds) or die('error in query '.mysql_error().$query_clear_tds);
     
        }
        
    }else{
/*
        $receiv_amount_tds=$due_amount_pay_1 - $tds_due_amount_1;

        if($receiv_amount_tds<1)
        {
            $tds_clear_flag=1;

            if($total_invoice_clearcheck_tds<1)
            {
                $query_clear_tds="insert into `clear_due_amount`  set invoice_id  = '".$due_invoice_id_1."',description='".$clear_tdsdue_desc."',payment_plan_id = '".$payment_id."',trans_id = '".$tds_trans_id."',due_amount = '".$receiv_amount_tds."', cust_id = '".$tds_cust_id."', user_id = '".$_SESSION['userId']."', type = 'TDS',create_time = '".getTime()."'";
                $result_clear_tds= mysql_query($query_clear_tds) or die('error in query '.mysql_error().$query_clear_tds);
            }
            else{
                $query_clear_tds="update `clear_due_amount`  set description='".$clear_tdsdue_desc."',due_amount = '".$receiv_amount_tds."' where invoice_id = '".$_REQUEST['invoice_no']."' and type = 'TDS'";
                $result_clear_tds= mysql_query($query_clear_tds) or die('error in query '.mysql_error().$query_clear_tds);
         
            }
        }
        else{
            $tds_clear_flag=0;
        }
        $query_tds1="update payment_plan set tds_flag = '1',clear_tds_flag='".$tds_clear_flag."'  where invoice_id = '".$invoice_no."'";
        $result_tds1= mysql_query($query_tds1) or die('error in query '.mysql_error().$query_tds1);
*/        
    }


     /*...........   Clear Due End       ...........*/
     $trsns_pname_1 = $_REQUEST['trsns_pname'];
    if($trsns_pname_1=="invoice-ledger")
    {
         $msg = "tds Multiproject Invoice Update successfully.";
          $flag = 1;
          /*?invoice_id=<?php echo $select_data['invoice_id']; ?>&payment_id=<?php echo $select_data['payment_id']; ?> */
      echo "<script> location.href='invoice-ledger.php?invoice_id=".$_REQUEST['invoice_no']."&payment_id=".$_REQUEST['payment_id']."'; </script>";
    }
    
    if($trsns_pname_1=="customer-ledger")
    {
         $msg = "TDS Multiproject Invoice Update successfully.";
          $flag = 1;
          /*?invoice_id=<?php echo $select_data['invoice_id']; ?>&payment_id=<?php echo $select_data['payment_id']; ?> */
      echo "<script> location.href='customer-ledger.php?cust_id=".$_REQUEST['tds_cust_id']."'; </script>";
    }

    if($trsns_pname_1=="bank-ledger")
    {
         $msg = "TDS Multiproject Invoice Update successfully.";
          $flag = 1;
          /*?invoice_id=<?php echo $select_data['invoice_id']; ?>&payment_id=<?php echo $select_data['payment_id']; ?> */
    //  echo "<script> location.href='customer-ledger.php?cust_id=".$_REQUEST['tds_cust_id']."'; </script>";
    echo "<script> location.href='bank-ledger.php?bank_id=".$pay_bank_id."'; </script>";
   
    }


}

 if($_REQUEST['trsns_pname_tds']=="invoice-ledger")
{
    //info_tb_id, trsns_pname ,invoice_no , payment_id
    $trsns_pname = "invoice-ledger";
    
    $select_query = "select * from tds_due_info where id=".$_REQUEST['info_tb_id_tds']." and invoice_id = '".$_REQUEST['invoice_no_tds']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
    // echo $select_query;
    //$back_data="invoice-ledger.php?invoice_id=".$_REQUEST['invoice_no']."&payment_id=".$_REQUEST['payment_id'];
    $back_data="invoice-ledger.php?invoice_id=".$_REQUEST['invoice_no_tds']."&payment_id=".$_REQUEST['payment_id_tds'];
 
    $select_query_pay = "select * from payment_plan where id=".$_REQUEST['payment_id_tds']." and invoice_id='".$_REQUEST['invoice_no_tds']."'";
    $select_result_pay = mysql_query($select_query_pay) or die('error in query select supplier query '.mysql_error().$select_query_pay);
    $select_data_pay = mysql_fetch_array($select_result_pay);
 
   
}

if($_REQUEST['trsns_pname_tds']=="customer-ledger")
{
    //info_tb_id, trsns_pname ,invoice_no , payment_id
    $trsns_pname = "customer-ledger";
    
    $select_query = "select * from tds_due_info where pp_linkid_1=".$_REQUEST['info_tb_id_tds']." and invoice_id = '".$_REQUEST['invoice_no_tds']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
    // echo $select_query;
    //$back_data="invoice-ledger.php?invoice_id=".$_REQUEST['invoice_no']."&payment_id=".$_REQUEST['payment_id'];
    $back_data="invoice-ledger.php?invoice_id=".$_REQUEST['invoice_no_tds']."&payment_id=".$_REQUEST['payment_id_tds'];
 
    $select_query_pay = "select * from payment_plan where id=".$_REQUEST['payment_id_tds']." and invoice_id='".$_REQUEST['invoice_no_tds']."'";
    $select_result_pay = mysql_query($select_query_pay) or die('error in query select supplier query '.mysql_error().$select_query_pay);
    $select_data_pay = mysql_fetch_array($select_result_pay);
 
   
}

if($_REQUEST['trsns_pname_tds']=="bank-ledger")
{
    //info_tb_id, trsns_pname ,invoice_no , payment_id
    $trsns_pname = "bank-ledger";
    
    $select_query = "select * from tds_due_info where pp_linkid_2=".$_REQUEST['info_tb_id_tds']." and invoice_id = '".$_REQUEST['invoice_no_tds']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
     //echo $select_query;
    //$back_data="invoice-ledger.php?invoice_id=".$_REQUEST['invoice_no']."&payment_id=".$_REQUEST['payment_id'];
   // $back_data="invoice-ledger.php?invoice_id=".$_REQUEST['invoice_no_tds']."&payment_id=".$_REQUEST['payment_id_tds'];
   $back_data="bank-ledger.php?bank_id=".$_REQUEST['bank_id_gst'];
    
    $select_query_pay = "select * from payment_plan where id=".$_REQUEST['payment_id_tds']." and invoice_id='".$_REQUEST['invoice_no_tds']."'";
    $select_result_pay = mysql_query($select_query_pay) or die('error in query select supplier query '.mysql_error().$select_query_pay);
    $select_data_pay = mysql_fetch_array($select_result_pay);
 
   //exit;
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
    Update Invoice tds Payment : 
    <span  style="text-align: right; float: right; margin-bottom: -10px">
    <input type="button" name="refresh" id="refresh" value="" class="button_back" onclick="goBack()"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></h3>
    <form name="tds_payment_form" id="tds_payment_form" action="" method="post" enctype="multipart/form-data" >
    <input type="hidden" id="tds_cust_id" name="tds_cust_id" value="<?php echo $select_data_pay['cust_id']; ?>"  >
    <input type="hidden" id="tds_trans_id" name="tds_trans_id" value="<?php  echo $select_data_pay['trans_id'];?>"  >
    <table>
            <tr>
            <td valign="top" width="500px">
            <table>
            <tr>
                    <td style="color:#FF0000;" width="110px">Invoice No  </td>
                    <td width="10px">:-</td>
                    <td style="color:#FF0000;"><?php echo $_REQUEST['invoice_no_tds']; ?></td>
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
                       Total tds Amount  
                    </td>
                    <td width="10px">:</td>
                    <td width="150px" ><?php echo $select_data_pay['tds_amount']; ?>
                    <input type="hidden" id="tds_total_val" name="tds_total_val" value="<?php echo $select_data_pay['tds_amount']; ?>">                
                </td>
                </tr>
               
                <tr>
                    <td>
                    &nbsp;&nbsp;&nbsp;(-)Less : Received Amount  
                    </td>
                    <td width="10px">:</td>
                    <?php
                        // $tds_due_query1 = "select SUM(amount) as amount,SUM(received_amount) as received_amount  from tds_due_info where payment_plan_id = '".$select_data['payment_plan_id']."' and invoice_id = '".$select_data['invoice_id']."' and id!='".$select_data['id']."'  ";
                        $tds_due_query1 = "select *  from tds_due_info where payment_plan_id = '".$select_data['payment_plan_id']."' and invoice_id = '".$select_data['invoice_id']."' and id!='".$select_data['id']."'  ";
                         
                        $tds_due_result2 = mysql_query($tds_due_query1) or die("error in date list query ".mysql_error());
                         $total_tds2 = mysql_num_rows($tds_due_result2);
                         $find_tds = mysql_fetch_array($tds_due_result2);
                        
                         $tot_amount=$select_data_pay['tds_amount'];
                         $tot_receivd = $find_tds['received_amount'] + $select_data['received_amount'];
                         $due_tds_final = $tot_amount-$tot_receivd;
                    ?>
                    <td><?php 
                    if($find_tds['received_amount']=="")
                    {
                        echo "0";
                    }
                    else
                    {
                        echo $find_tds['received_amount'];
                    }
                    $tot_final_val = $find_tds['received_amount']+$select_data['received_amount'];
                        ?>
                    <input type="hidden" id="tds_receive_val" name="tds_receive_val" value="<?php echo $find_tds['received_amount']; ?>">
                    </td>
                </tr>
                
                <tr>
                    <td>
                    &nbsp;&nbsp;&nbsp;(-)Less : This Payment Amount  
                    </td>
                    <td width="10px">:</td>
                    <td><?php //echo $select_data['received_amount']; ?>
                    <input type="text" id="tds_receive_this_val" style="width:60px;color:blue;border:0;" readonly="readonly"  name="tds_receive_this_val" value="<?php echo $select_data['received_amount']; ?>" />
                
                    <input type="hidden" id="tds_totalreceive_val" name="tds_totalreceive_val" value="<?php echo $tot_final_val; ?>" />
                
                    </td>
                </tr>
                <tr>
                    <td style="color:#FF0000;">
                        Due Amount  
                    </td>
                    <td width="10px">:</td>
                    <td style="color:#FF0000;"><?php //echo $due_tds_final; ?>
                    <input type="text" id="tds_finaldue_val" style="width:60px;color:red;border:0;" readonly="readonly"  name="tds_finaldue_val" value="<?php echo $due_tds_final; ?>" />
                    
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
    
     <input type="hidden" name="info_tb_id" id="info_tb_id" value="<?php echo $_REQUEST['info_tb_id_tds']; ?>">
     <input type="hidden" name="trsns_pname" id="trsns_pname" value="<?php echo $_REQUEST['trsns_pname_tds']; ?>">
     <input type="hidden" name="invoice_no" id="invoice_no" value="<?php echo $_REQUEST['invoice_no_tds']; ?>"> 
     <input type="hidden" name="payment_id" id="payment_id" value="<?php echo $_REQUEST['payment_id_tds']; ?>">   
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
            echo $_REQUEST['info_tb_id_tds'];
            echo "</br>";
            echo $_REQUEST['trsns_pname_tds'];
            echo "</br>";
            echo $_REQUEST['invoice_no_tds'];
            echo "</br>";
            echo $_REQUEST['payment_id_tds'];
            echo "</br>";
            */
?>

            <table cellpadding="0" cellspacing="0" border="1px" width="100%" >
<tr><td valign="top" align="right" colspan="2" ></td></tr>
<tr>
                    <td valign="top" style="color:#FF0000; font-weight:bold;" colspan="2" >tds Payment Details
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                       
                    <input type="text" id="tds_due_amount_new_total" style="width:100px;color:red; border:0; " readonly="readonly"  name="tds_due_amount_new_total" value="" /></td>
                </tr>
                <tr>
                    <td valign="top" colspan="2">
                    <table width="100%">
                        <tr>
                            <td width="100px">Clear tds Due :</td>
                            <td width="40px">
                            <input type="checkbox" id="clear_tds_due" name="clear_tds_due" onClick="return clear_tds_desc();" value="yes">
                        </td>
                            <td width=""><input type='text' style="display:none; width=300px;" name='clear_tdsdue_desc' align='right' id='clear_tdsdue_desc' width="300px" placeholder="Type clear due Description here"/>
                    </td>
                        </tr>
                    </table>
                    
                    </td>
                </tr>
                
<tr>  
            <td valign="top" colspan="2" align="left" id="tds-due_div2" style=" display:block; width:100%">
                <table width="100%" border="2" style="border:2px;">  
                <tr><td valign="top" >Paid Into</td>
                <?php
                 $old_pay_bank_id= get_field_value("bank_id","payment_plan","id",$select_data['pp_linkid_2']);
                // echo get_field_value("bank_account_name","bank","id",$bank_id_inv);
                
$sql_bank     = "select bank_account_name,bank_account_number from `bank`  where id=".$old_pay_bank_id." ";
$query_bank     = mysql_query($sql_bank);
$select_bank = mysql_fetch_array($query_bank);

?>

                    <td><input type="text" id="tds_pay_form"  name="tds_pay_form" value="<?php echo $select_bank['bank_account_name'].' - '.$select_bank['bank_account_number']; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span> 
                </td> </tr>
                <tr><td valign="top"  width="250px" >Date</td>
                  <td><input type="text"  name="tds_due_date" id="tds_due_date" value="<?php echo date("d-m-Y",$select_data['due_date']); ?>" style="width:100px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('tds_due_date')" style="cursor:pointer"/></td>
                </tr>
                <tr>
                    <td valign="top" >Amount Received</td>
                    <td><input type="text" id="tds_due_amount" style="width:100px;"  name="tds_due_amount" value="<?php echo $select_data['received_amount']; ?>" onkeydown="tds_due_calculation()" onkeyup="tds_due_calculation()" onkeypress="tds_due_calculation()" />
                    <span id=""  style="color:red;" >(Due :<input type="text" id="tds_due_amount_new_due" style="width:60px;color:red;border:0;" readonly="readonly"  name="tds_due_amount_new_due" value="" /></span>
                </td></tr>
               
                <tr>
                    <td valign="top" >tds File Attachment</td>
                    <td><input type="file" name="tds_due_attach_file" id="tds_due_attach_file" value="<?php echo $select_data['cert_file_name']; ?>" ></td>
                </tr>
                <tr>
                    <td valign="top" >Description</td>
                    <td><input type="text" id="tds_due_des" style="width:260px;"  name="tds_due_des" value="<?php echo $select_data['description']; ?>" autocomplete="off"/></td>
                </tr>
            </table>
            </td>
        </tr>    
       
    <input type="hidden" id="attach_file_id"  name="attach_file_id" value="" />
    <input type="hidden" id="tds_due_info_id"  name="tds_due_info_id" value="" />
    <input type="hidden" id=""  name="" value="" />
    <tr>
        <td valign="bottom" align="center" colspan="2"><input type="submit" class="button" name="file_button1" id="file_button1" value="Submit" onClick="return tds_validation();" ></td></tr>
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
function tds_validation()
{
    //tds_pay_form ,tds_due_date ,tds_due_amount, tds_due_attach_file ,tds_due_des
    if(document.getElementById('clear_tds_due').checked == true && document.getElementById("clear_tdsdue_desc").value=="")
        {
                alert("Please enter description for clear due");
                document.getElementById("clear_tdsdue_desc").focus();
                return false;
        
        }else if($("#tds_pay_form").val() == "")
    {
        alert("Please select bank name.");
        $("#tds_pay_form").focus();
        return false;
    }else if($("#tds_due_date").val() == "")
    {
        alert("Please select payment date.");
        $("#tds_due_date").focus();
        return false;
    }else if($("#tds_due_amount").val() == "")
    {
        alert("Please enter Received amount.");
        $("#tds_due_amount").focus();
        return false;
    }else if($("#tds_due_des").val() == "")
    {
        alert("Please enter Description.");
        $("#tds_due_des").focus();
        return false;
    }
    else
    { 
              $("#action_perform").val("edit_tds_payment");
            $("#tds_payment_form").submit();
            return true;
  
        
    }
    
}
</script>

    <script>
    $(document).ready(function(){
        
        $( "#tds_pay_form" ).autocomplete({
            source: "bankcash-ajax.php"
        });

        
    })
    </script>    
<script>
function tds_due_calculation()
{
    tot_val= document.getElementById("tds_total_val").value;
    receiv_val= document.getElementById("tds_receive_val").value;
    get_val= document.getElementById("tds_due_amount").value;
    tot_received=Number(receiv_val)+Number(get_val);
    final_due=Number(tot_val)-Number(tot_received);
    document.getElementById("tds_receive_this_val").value=get_val;
    document.getElementById("tds_finaldue_val").value=final_due;
    document.getElementById("tds_due_amount_new_due").value=final_due;
    document.getElementById("tds_totalreceive_val").value=tot_received;

    //tds_total_val , tds_totalreceive_val , tds_finaldue_val
    if(final_due<1)
    {
        document.getElementById('clear_tds_due').checked = true;
        document.getElementById("clear_tdsdue_desc").style.display="block";
    }
    else{
        document.getElementById('clear_tds_due').checked = false;
        document.getElementById("clear_tdsdue_desc").style.display="none";
    }
}

function clear_tds_desc()
{
    if(document.getElementById('clear_tds_due').checked == true)
    {
        document.getElementById("clear_tdsdue_desc").style.display="block";
    }
    else if(document.getElementById('clear_tds_due').checked == false)
    {
        document.getElementById("clear_tdsdue_desc").style.display="none";
    }
    //clear_tdsdue_desc
}

function tds_validation1()
{
    if(document.getElementById("tds_due_flag_value").value=="0" && document.getElementById('clear_tds_due').checked == false)
    {
        //alert("first condition");
     return true;
    }else if(document.getElementById("tds_due_flag_value").value=="0" && document.getElementById('clear_tds_due').checked == true)
    {
        //alert("second condition");
        if(document.getElementById('clear_tds_due').checked == true)
    {
        if(document.getElementById("clear_tdsdue_desc").value=="")
        {
            alert("Please enter description for clear due");
            document.getElementById("clear_tdsdue_desc").focus();
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
    else if(document.getElementById("tds_due_flag_value").value=="1")
    { //tds_pay_form, tds_due_date , tds_due_amount ,tds_due_des
        document.getElementById("clearall_due_flag").value="0";
        if(document.getElementById('clear_tds_due').checked == true && document.getElementById("clear_tdsdue_desc").value=="")
        {
                alert("Please enter description for clear due");
                document.getElementById("clear_tdsdue_desc").focus();
                return false;
        
        }else if(document.getElementById("tds_pay_form").value=="")
            {
            alert("Please select to the bank name");
            document.getElementById("tds_pay_form").focus();
            return false;
        }else if(document.getElementById("tds_due_date").value=="")
        {
            alert("Please select payment date");
            document.getElementById("tds_due_date").focus();
            return false;
        }else if(document.getElementById("tds_due_amount").value=="")
        {
            alert("Please enter received tds amount");
            document.getElementById("tds_due_amount").focus();
            return false;
        }else if(document.getElementById("tds_due_des").value=="")
        {
            alert("Please enter description");
            document.getElementById("tds_due_des").focus();
            return false;
        }else 
        {
            return true;
        }
    } 
    
    
}

</script>
