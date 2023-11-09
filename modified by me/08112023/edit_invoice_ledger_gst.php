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
$flag = 0;

/*     Create  Account   */


if(trim($_REQUEST['action_perform']) == "edit_gst_payment")
{
    print_r($_REQUEST); die();

    $info_tb_id=mysql_real_escape_string(trim($_REQUEST['info_tb_id']));
    $trsns_pname=mysql_real_escape_string(trim($_REQUEST['trsns_pname']));
    $invoice_no=mysql_real_escape_string(trim($_REQUEST['invoice_no']));
    $payment_id=mysql_real_escape_string(trim($_REQUEST['payment_id']));
    $gst_cust_id=mysql_real_escape_string(trim($_REQUEST['gst_cust_id']));
    $gst_trans_id=mysql_real_escape_string(trim($_REQUEST['gst_trans_id']));
    
    $cert_file_name=mysql_real_escape_string(trim($_REQUEST['cert_file_name']));
    $gst_due_amount=mysql_real_escape_string(trim($_REQUEST['gst_due_amount']));
    $gst_due_des_1=mysql_real_escape_string(trim($_REQUEST['gst_due_des']));
   $gst_due_des_1_extra = "";
    $pay_from_arr = explode(" -",$_REQUEST['gst_pay_form']);
            $pay_bank_id = get_field_value("id","bank","bank_account_name",$pay_from_arr[0]);
            
    $pp_linkid_1=mysql_real_escape_string(trim($_REQUEST['pp_linkid_1']));
    $pp_linkid_2=mysql_real_escape_string(trim($_REQUEST['pp_linkid_2']));
    $clear_gst_due=mysql_real_escape_string(trim($_REQUEST['clear_gst_due']));
    $clear_gstdue_desc=mysql_real_escape_string(trim($_REQUEST['clear_gstdue_desc']));

    $due_amount_pay_1=mysql_real_escape_string(trim($_REQUEST['gst_total_val']));
    $gst_due_amount_1=mysql_real_escape_string(trim($_REQUEST['gst_totalreceive_val']));
    
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

    $query3="update gst_due_info set due_date = '".strtotime($_REQUEST['gst_due_date'])."',description = '".$gst_due_des_1."', received_amount = '".$gst_due_amount."',update_date = '".getTime()."' where ".$serch_field."='".$info_tb_id."'";
    $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
    $link_id_4 = $info_tb_id;
    if($_FILES["gst_due_attach_file"]["name"] != "")
     {
        $attach_file_name="gst_certificate";
        $temp = explode(".", $_FILES["gst_due_attach_file"]["name"]);
         $arr_size = count($temp);
        $extension = end($temp);
        $new_file_name = $attach_file_name.'_'.$link_id_4.'_'.date("d_M_Y").'.'.$extension;
        move_uploaded_file($_FILES["gst_due_attach_file"]["tmp_name"],"gst_files/" . $new_file_name);
        $query1_1="update gst_due_info set cert_file_name = '".$new_file_name."' where ".$serch_field." = '".$link_id_4."'";
        $result1_1= mysql_query($query1_1) or die('error in query '.mysql_error().$query1_1);
     }

     $query_pay_gst ="update payment_plan set  bank_id = '".$pay_bank_id."', credit = '".$gst_due_amount."',  description = '".$gst_due_des_1."', payment_date = '".strtotime($_REQUEST['gst_due_date'])."',update_date = '".getTime()."' where id=".$pp_linkid_2."";
     $result_pay_gst= mysql_query($query_pay_gst) or die('error in query '.mysql_error().$query_pay_gst);

     $query2_pay_gst="update payment_plan set  debit = '".$gst_due_amount."', description = '".$gst_due_des_1."',on_bank = '".$pay_bank_id."',payment_date = '".strtotime($_REQUEST['gst_due_date'])."' ,update_date = '".getTime()."' where id=".$pp_linkid_1."";
     $result2_pay_gst= mysql_query($query2_pay_gst) or die('error in query '.mysql_error().$query2_pay_gst);

     /* ....     Clear Due Start    ............*/
        $select_query_clearcheck_gst = "select * from clear_due_amount where invoice_id = '".$_REQUEST['invoice_no']."' and type='GST'   ";
        $select_result_clearcheck_gst = mysql_query($select_query_clearcheck_gst) or die('error in query select clear check  query '.mysql_error().$select_query_clearcheck_gst);
        $total_invoice_clearcheck_gst = mysql_num_rows($select_result_clearcheck_gst);
        $select_data_clearcheck_gst = mysql_fetch_array($select_result_clearcheck_gst);
                
     if($clear_gst_due=="yes")
     {
        $query_gst1="update payment_plan set gst_flag = '1',clear_gst_flag='1'  where invoice_id = '".$invoice_no."'";
        $result_gst1= mysql_query($query_gst1) or die('error in query '.mysql_error().$query_gst1);
        //gst_total_val , gst_totalreceive_val , gst_finaldue_val
    
        $receiv_amount_gst=$due_amount_pay_1 - $gst_due_amount_1;
           
        //payment_plan_id = '".$payment_id."',trans_id = '".$gst_trans_id."',due_amount = '".$receiv_amount_gst."', cust_id = '".$gst_cust_id."',
        if($total_invoice_clearcheck_gst<1)
        {
            $query_clear_gst="insert into `clear_due_amount`  set invoice_id  = '".$invoice_no."',description='".$clear_gstdue_desc."',payment_plan_id = '".$payment_id."',trans_id = '".$gst_trans_id."',due_amount = '".$receiv_amount_gst."', cust_id = '".$gst_cust_id."', user_id = '".$_SESSION['userId']."', type = 'GST',create_time = '".getTime()."'";
            $result_clear_gst= mysql_query($query_clear_gst) or die('error in query '.mysql_error().$query_clear_gst);
     
        }else{
            $query_clear_gst="update `clear_due_amount`  set description='".$clear_gstdue_desc."',due_amount = '".$receiv_amount_gst."' where invoice_id = '".$_REQUEST['invoice_no']."' and type = 'GST'";
            $result_clear_gst= mysql_query($query_clear_gst) or die('error in query '.mysql_error().$query_clear_gst);
     
        }
        
    }else{
/*
        $receiv_amount_gst=$due_amount_pay_1 - $gst_due_amount_1;

        if($receiv_amount_gst<1)
        {
            $gst_clear_flag=1;

            if($total_invoice_clearcheck_gst<1)
            {
                $query_clear_gst="insert into `clear_due_amount`  set invoice_id  = '".$due_invoice_id_1."',description='".$clear_gstdue_desc."',payment_plan_id = '".$payment_id."',trans_id = '".$gst_trans_id."',due_amount = '".$receiv_amount_gst."', cust_id = '".$gst_cust_id."', user_id = '".$_SESSION['userId']."', type = 'GST',create_time = '".getTime()."'";
                $result_clear_gst= mysql_query($query_clear_gst) or die('error in query '.mysql_error().$query_clear_gst);
            }
            else{
                $query_clear_gst="update `clear_due_amount`  set description='".$clear_gstdue_desc."',due_amount = '".$receiv_amount_gst."' where invoice_id = '".$_REQUEST['invoice_no']."' and type = 'GST'";
                $result_clear_gst= mysql_query($query_clear_gst) or die('error in query '.mysql_error().$query_clear_gst);
         
            }
        }
        else{
            $gst_clear_flag=0;
        }
        $query_gst1="update payment_plan set gst_flag = '1',clear_gst_flag='".$gst_clear_flag."'  where invoice_id = '".$invoice_no."'";
        $result_gst1= mysql_query($query_gst1) or die('error in query '.mysql_error().$query_gst1);
  */      
    }


     /*...........   Clear Due End       ...........*/
     $trsns_pname_1 = $_REQUEST['trsns_pname'];
     //customer-ledger
    if($trsns_pname_1=="invoice-ledger")
    {
         $msg = "GST Multiproject Invoice Update successfully.";
          $flag = 1;
          /*?invoice_id=<?php echo $select_data['invoice_id']; ?>&payment_id=<?php echo $select_data['payment_id']; ?> */
      echo "<script> location.href='invoice-ledger.php?invoice_id=".$_REQUEST['invoice_no']."&payment_id=".$_REQUEST['payment_id']."'; </script>";
    }
   // $back_data="bank-ledger.php?bank_id=".$pay_bank_id;
    if($trsns_pname_1=="customer-ledger")
    {
         $msg = "GST Multiproject Invoice Update successfully.";
          $flag = 1;
          /*?invoice_id=<?php echo $select_data['invoice_id']; ?>&payment_id=<?php echo $select_data['payment_id']; ?> */
      echo "<script> location.href='customer-ledger.php?cust_id=".$_REQUEST['gst_cust_id']."'; </script>";
    }

    if($trsns_pname_1=="bank-ledger")
    {
         $msg = "GST Multiproject Invoice Update successfully.";
          $flag = 1;
          /*?invoice_id=<?php echo $select_data['invoice_id']; ?>&payment_id=<?php echo $select_data['payment_id']; ?> */
      echo "<script> location.href='bank-ledger.php?bank_id=".$pay_bank_id."'; </script>";
    }

}

 if($_REQUEST['trsns_pname_gst']=="invoice-ledger")
{
    //info_tb_id, trsns_pname ,invoice_no , payment_id
    $trsns_pname = "invoice-ledger";
    
    $select_query = "select * from gst_due_info where id=".$_REQUEST['info_tb_id_gst']." and invoice_id = '".$_REQUEST['invoice_no_gst']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
    // echo $select_query;
    //$back_data="invoice-ledger.php?invoice_id=".$_REQUEST['invoice_no']."&payment_id=".$_REQUEST['payment_id'];
    $back_data="invoice-ledger.php?invoice_id=".$_REQUEST['invoice_no_gst']."&payment_id=".$_REQUEST['payment_id_gst'];
 
    $select_query_pay = "select * from payment_plan where id=".$_REQUEST['payment_id_gst']." and invoice_id='".$_REQUEST['invoice_no_gst']."'";
    $select_result_pay = mysql_query($select_query_pay) or die('error in query select supplier query '.mysql_error().$select_query_pay);
    $select_data_pay = mysql_fetch_array($select_result_pay);
 
   
}

if($_REQUEST['trsns_pname_gst']=="customer-ledger")
{
    $trsns_pname = "customer-ledger";
    
    $select_query = "select * from gst_due_info where pp_linkid_1=".$_REQUEST['info_tb_id_gst']." and invoice_id = '".$_REQUEST['invoice_no_gst']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);

    $back_data="invoice-ledger.php?invoice_id=".$_REQUEST['invoice_no_gst']."&payment_id=".$_REQUEST['payment_id_gst'];
 
    $select_query_pay = "select * from payment_plan where id=".$_REQUEST['payment_id_gst']." and invoice_id='".$_REQUEST['invoice_no_gst']."'";
    $select_result_pay = mysql_query($select_query_pay) or die('error in query select supplier query '.mysql_error().$select_query_pay);
    $select_data_pay = mysql_fetch_array($select_result_pay);
}

if($_REQUEST['trsns_pname_gst']=="bank-ledger")
{
    $trsns_pname = "bank-ledger";
    
    $select_query = "select * from gst_due_info where pp_linkid_2=".$_REQUEST['info_tb_id_gst']." and invoice_id = '".$_REQUEST['invoice_no_gst']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);

    $back_data="bank-ledger.php?bank_id=".$_REQUEST['bank_id_gst'];
    $select_query_pay = "select * from payment_plan where id=".$_REQUEST['payment_id_gst']." and invoice_id='".$_REQUEST['invoice_no_gst']."'";
    $select_result_pay = mysql_query($select_query_pay) or die('error in query select supplier query '.mysql_error().$select_query_pay);
    $select_data_pay = mysql_fetch_array($select_result_pay);
}
//customer-ledger
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
    Update Invoice GST Payment : 
    <span  style="text-align: right; float: right; margin-bottom: -10px">
    <input type="button" name="refresh" id="refresh" value="" class="button_back" onclick="goBack()"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></h3>
    <form name="gst_payment_form" id="gst_payment_form" action="" method="post" enctype="multipart/form-data" >
    <input type="hidden" id="gst_cust_id" name="gst_cust_id" value="<?php echo $select_data_pay['cust_id']; ?>"  >
    <input type="hidden" id="gst_trans_id" name="gst_trans_id" value="<?php  echo $select_data_pay['trans_id'];?>"  >
    <table>
            <tr>
            <td valign="top" width="500px">
            <table>
            <tr>
                    <td style="color:#FF0000;" width="110px">Invoice No  </td>
                    <td width="10px">:-</td>
                    <td style="color:#FF0000;"><?php echo $_REQUEST['invoice_no_gst']; ?></td>
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
                       Total GST Amount  
                    </td>
                    <td width="10px">:</td>
                    <td width="150px" ><?php echo $select_data_pay['gst_amount']; ?>
                    <input type="hidden" id="gst_total_val" name="gst_total_val" value="<?php echo $select_data_pay['gst_amount']; ?>">                
                </td>
                </tr>
               
                <tr>
                <?php
                         //$gst_due_query1 = "select SUM(amount) as amount,SUM(received_amount) as received_amount  from gst_due_info where payment_plan_id = '".$select_data['payment_plan_id']."' and invoice_id = '".$select_data['invoice_id']."' and id!='".$select_data['id']."'  ";
                         $gst_due_query1 = "select *  from gst_due_info where payment_plan_id = '".$select_data['payment_plan_id']."' and invoice_id = '".$select_data['invoice_id']."' and id!='".$select_data['id']."'  ";
                         
                         $gst_due_result2 = mysql_query($gst_due_query1) or die("error in date list query ".mysql_error());
                         $total_gst2 = mysql_num_rows($gst_due_result2);
                        // $find_gst = mysql_fetch_array($gst_due_result2);
                        $tot_date="";
                        $tot_recei_payment="0";
                         while($find_invoice = mysql_fetch_array($gst_due_result2))
	                    {
                            $tot_recei_payment = $tot_recei_payment+$find_invoice['received_amount'];
                            if($tot_date=="")
                            {
                                $tot_date=date("d-m-Y",$find_invoice['due_date']);
                            }else{
                                $tot_date=$tot_date.",".date("d-m-Y",$find_invoice['due_date']);
                            }
                        }
                        


                         $tot_amount=$select_data_pay['gst_amount'];
                        // $tot_receivd = $find_gst['received_amount'] + $select_data['received_amount'];
                        $tot_receivd = $tot_recei_payment + $select_data['received_amount'];
                          
                        $due_gst_final = $tot_amount-$tot_receivd;
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
                        //echo $find_gst['received_amount'];
                        echo $tot_recei_payment;
                    }
                   // $tot_final_val = $find_gst['received_amount']+$select_data['received_amount'];
                    $tot_final_val = $tot_recei_payment+$select_data['received_amount'];
                    
                    ?>
                    <input type="hidden" id="gst_receive_val" name="gst_receive_val" value="<?php echo $tot_recei_payment; ?>">
                    </td>
                </tr>
                
                <tr>
                    <td>
                    &nbsp;&nbsp;&nbsp;(-)Less : This Payment Amount  
                    </td>
                    <td width="10px">:</td>
                    <td><?php //echo $select_data['received_amount']; ?>
                    <input type="text" id="gst_receive_this_val" style="width:60px;color:blue;border:0;" readonly="readonly"  name="gst_receive_this_val" value="<?php echo $select_data['received_amount']; ?>" />
                
                    <input type="hidden" id="gst_totalreceive_val" name="gst_totalreceive_val" value="<?php echo $tot_final_val; ?>" />
                
                    </td>
                </tr>
                <tr>
                    <td style="color:#FF0000;">
                        Due Amount  
                    </td>
                    <td width="10px">:</td>
                    <td style="color:#FF0000;"><?php //echo $due_gst_final; ?>
                    <input type="text" id="gst_finaldue_val" style="width:60px;color:red;border:0;" readonly="readonly"  name="gst_finaldue_val" value="<?php echo $due_gst_final; ?>" />
                    
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
    
     <input type="hidden" name="info_tb_id" id="info_tb_id" value="<?php echo $select_data['id']; ?>">
     <input type="hidden" name="trsns_pname" id="trsns_pname" value="<?php echo $_REQUEST['trsns_pname_gst']; ?>">
     <input type="hidden" name="invoice_no" id="invoice_no" value="<?php echo $_REQUEST['invoice_no_gst']; ?>"> 
     <input type="hidden" name="payment_id" id="payment_id" value="<?php echo $_REQUEST['payment_id_gst']; ?>">   
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
            echo $_REQUEST['info_tb_id_gst'];
            echo "</br>";
            echo $_REQUEST['trsns_pname_gst'];
            echo "</br>";
            echo $_REQUEST['invoice_no_gst'];
            echo "</br>";
            echo $_REQUEST['payment_id_gst'];
            echo "</br>";
            */
?>

            <table cellpadding="0" cellspacing="0" border="1px" width="100%" >
<tr><td valign="top" align="right" colspan="2" ></td></tr>
<tr>
                    <td valign="top" style="color:#FF0000; font-weight:bold;" colspan="2" >GST Payment Details
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                       
                    <input type="text" id="gst_due_amount_new_total" style="width:100px;color:red; border:0; " readonly="readonly"  name="gst_due_amount_new_total" value="" /></td>
                </tr>
                <tr>
                    <td valign="top" colspan="2">
                    <table width="100%">
                        <tr>
                            <td width="100px">Clear GST Due :</td>
                            <td width="40px">
                            <input type="checkbox" id="clear_gst_due" name="clear_gst_due" onClick="return clear_gst_desc();" value="yes">
                        </td>
                            <td width=""><input type='text' style="display:none; width=300px;" name='clear_gstdue_desc' align='right' id='clear_gstdue_desc' width="300px" placeholder="Type clear due Description here"/>
                    </td>
                        </tr>
                    </table>
                    
                    </td>
                </tr>
                
<tr>  
            <td valign="top" colspan="2" align="left" id="gst-due_div2" style=" display:block; width:100%">
                <table width="100%" border="2" style="border:2px;">  
                <tr><td valign="top" >Paid Into</td>
                <?php
                 $old_pay_bank_id= get_field_value("bank_id","payment_plan","id",$select_data['pp_linkid_2']);
                // echo get_field_value("bank_account_name","bank","id",$bank_id_inv);
                
$sql_bank     = "select bank_account_name,bank_account_number from `bank`  where id=".$old_pay_bank_id." ";
$query_bank     = mysql_query($sql_bank);
$select_bank = mysql_fetch_array($query_bank);

?>

                    <td><input type="text" id="gst_pay_form"  name="gst_pay_form" value="<?php echo $select_bank['bank_account_name'].' - '.$select_bank['bank_account_number']; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span> 
                </td> </tr>
                <tr><td valign="top"  width="250px" >Date</td>
                  <td><input type="text"  name="gst_due_date" id="gst_due_date" value="<?php echo date("d-m-Y",$select_data['due_date']); ?>" style="width:100px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('gst_due_date')" style="cursor:pointer"/></td>
                </tr>
                <tr>
                    <td valign="top" >Amount Received</td>
                    <td><input type="text" id="gst_due_amount" style="width:100px;"  name="gst_due_amount" value="<?php echo $select_data['received_amount']; ?>" onkeydown="gst_due_calculation()" onkeyup="gst_due_calculation()" onkeypress="gst_due_calculation()" />
                    <span id=""  style="color:red;" >(Due :<input type="text" id="gst_due_amount_new_due" style="width:60px;color:red;border:0;" readonly="readonly"  name="gst_due_amount_new_due" value="" /></span>
                </td></tr>
               
                <tr>
                    <td valign="top" >GST File Attachment</td>
                    <td><input type="file" name="gst_due_attach_file" id="gst_due_attach_file" value="<?php echo $select_data['cert_file_name']; ?>" ></td>
                </tr>
                <tr>
                    <td valign="top" >Description</td>
                    <td><input type="text" id="gst_due_des" style="width:260px;"  name="gst_due_des" value="<?php echo $select_data['description']; ?>" autocomplete="off"/></td>
                </tr>
            </table>
            </td>
        </tr>    
       
    <input type="hidden" id="attach_file_id"  name="attach_file_id" value="" />
    <input type="hidden" id="gst_due_info_id"  name="gst_due_info_id" value="" />
    <input type="hidden" id=""  name="" value="" />
    <tr>
        <td valign="bottom" align="center" colspan="2"><input type="submit" class="button" name="file_button1" id="file_button1" value="Submit" onClick="return gst_validation();" ></td></tr>
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
function gst_validation()
{
    //gst_pay_form ,gst_due_date ,gst_due_amount, gst_due_attach_file ,gst_due_des
    if(document.getElementById('clear_gst_due').checked == true && document.getElementById("clear_gstdue_desc").value=="")
        {
                alert("Please enter description for clear due");
                document.getElementById("clear_gstdue_desc").focus();
                return false;
        
        }else if($("#gst_pay_form").val() == "")
    {
        alert("Please select bank name.");
        $("#gst_pay_form").focus();
        return false;
    }else if($("#gst_due_date").val() == "")
    {
        alert("Please select payment date.");
        $("#gst_due_date").focus();
        return false;
    }else if($("#gst_due_amount").val() == "")
    {
        alert("Please enter Received amount.");
        $("#gst_due_amount").focus();
        return false;
    }else if($("#gst_due_des").val() == "")
    {
        alert("Please enter Description.");
        $("#gst_due_des").focus();
        return false;
    }
    else
    { 
        $("#action_perform").val("edit_gst_payment");
        $("#gst_payment_form").submit();
        return true;
    }    
}
</script>

    <script>
    $(document).ready(function(){
        
        $("#gst_pay_form").autocomplete({
            source: "bankcash-ajax.php"
        });        
    })
    </script>    
<script>
function gst_due_calculation()
{
    tot_val= document.getElementById("gst_total_val").value;
    receiv_val= document.getElementById("gst_receive_val").value;
    get_val= document.getElementById("gst_due_amount").value;
    tot_received=Number(receiv_val)+Number(get_val);
    final_due=Number(tot_val)-Number(tot_received);
    document.getElementById("gst_receive_this_val").value=get_val;
    document.getElementById("gst_finaldue_val").value=final_due;
    document.getElementById("gst_due_amount_new_due").value=final_due;
    document.getElementById("gst_totalreceive_val").value=tot_received;

    //gst_total_val , gst_totalreceive_val , gst_finaldue_val
    if(final_due<1)
    {
        document.getElementById('clear_gst_due').checked = true;
        document.getElementById("clear_gstdue_desc").style.display="block";
    }
    else{
        document.getElementById('clear_gst_due').checked = false;
        document.getElementById("clear_gstdue_desc").style.display="none";
    }
}

function clear_gst_desc()
{
    if(document.getElementById('clear_gst_due').checked == true)
    {
        document.getElementById("clear_gstdue_desc").style.display="block";
    }
    else if(document.getElementById('clear_gst_due').checked == false)
    {
        document.getElementById("clear_gstdue_desc").style.display="none";
    }
    //clear_gstdue_desc
}

function gst_validation1()
{
    if(document.getElementById("gst_due_flag_value").value=="0" && document.getElementById('clear_gst_due').checked == false)
    {
        //alert("first condition");
     return true;
    }else if(document.getElementById("gst_due_flag_value").value=="0" && document.getElementById('clear_gst_due').checked == true)
    {
        //alert("second condition");
        if(document.getElementById('clear_gst_due').checked == true)
    {
        if(document.getElementById("clear_gstdue_desc").value=="")
        {
            alert("Please enter description for clear due");
            document.getElementById("clear_gstdue_desc").focus();
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
    else if(document.getElementById("gst_due_flag_value").value=="1")
    { //gst_pay_form, gst_due_date , gst_due_amount ,gst_due_des
        document.getElementById("clearall_due_flag").value="0";
        if(document.getElementById('clear_gst_due').checked == true && document.getElementById("clear_gstdue_desc").value=="")
        {
                alert("Please enter description for clear due");
                document.getElementById("clear_gstdue_desc").focus();
                return false;
        
        }else if(document.getElementById("gst_pay_form").value=="")
            {
            alert("Please select to the bank name");
            document.getElementById("gst_pay_form").focus();
            return false;
        }else if(document.getElementById("gst_due_date").value=="")
        {
            alert("Please select payment date");
            document.getElementById("gst_due_date").focus();
            return false;
        }else if(document.getElementById("gst_due_amount").value=="")
        {
            alert("Please enter received GST amount");
            document.getElementById("gst_due_amount").focus();
            return false;
        }else if(document.getElementById("gst_due_des").value=="")
        {
            alert("Please enter description");
            document.getElementById("gst_due_des").focus();
            return false;
        }else 
        {
            return true;
        }
    } 
    
    
}

</script>
