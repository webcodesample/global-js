<?php session_start();

include_once("../connection.php");

//print_r($_REQUEST);

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


//code by smit 25092023

/*  --------      gst DUE WORK        -----------------------  */
    
if(mysql_real_escape_string(trim($_REQUEST['file_button_gst'])) == "Submit")
{
    $tds_cerf_1=mysql_real_escape_string(trim($_REQUEST['gst_cerf']));
    $tds_due_date_1=mysql_real_escape_string(trim($_REQUEST['gst_due_date']));
    $tds_due_amount_1=mysql_real_escape_string(trim($_REQUEST['gst_due_amount']));
    $tds_due_des_1=mysql_real_escape_string(trim($_REQUEST['gst_due_des']));
    $tds_due_flag_value_1=mysql_real_escape_string(trim($_REQUEST['gst_due_flag_value']));
    $due_trans_id_1=mysql_real_escape_string(trim($_REQUEST['gst_trans_id']));
    $due_payment_id_1=mysql_real_escape_string(trim($_REQUEST['gst_payment_id']));
    $due_invoice_id_1=mysql_real_escape_string(trim($_REQUEST['gst_invoice_id']));
    $due_amount_pay_1=mysql_real_escape_string(trim($_REQUEST['gst_amount_pay']));
    $tds_due_date_1_n=strtotime($tds_due_date_1);
    $clearall_due_flag=mysql_real_escape_string(trim($_REQUEST['clearall_due_flag']));
    $gst_due_amount_new_total=mysql_real_escape_string(trim($_REQUEST['gst_due_amount_new_total']));
    $clear_gst_due=mysql_real_escape_string(trim($_REQUEST['clear_gst_due']));
    $clear_gstdue_desc=mysql_real_escape_string(trim($_REQUEST['clear_gstdue_desc']));

    $tds_due_des_1_extra = "GST Paid for invoice : ".$due_invoice_id_1.""; 
          $clear_gst_due_des_1_extra = "GST Clear Amount for invoice : ".$due_invoice_id_1."";  
    
            $pay_from_arr = explode(" -",$_REQUEST['gst_pay_form']);
            $pay_bank_id = get_field_value("id","bank","bank_account_name",$pay_from_arr[0]);
    
                  $sql_pay 	= "select * from `payment_plan` where id ='".$due_payment_id_1."'";
                  $query_pay_1 	= mysql_query($sql_pay);
                  $row_pay = mysql_fetch_array($query_pay_1);
                  $trans_id=$row_pay['trans_id'];
                  $cust_id = $row_pay['cust_id'];
                  $invoice_idnew = $row_pay['invoice_id'];
                  $payment_flag = $row_pay['payment_flag'];
                  $subdivision = $row_pay['subdivision'];
                  $gst_subdivision_n = $row_pay['gst_subdivision'];
                  $tds_subdivision_n = $row_pay['tds_subdivision'];
                  $gst_amount_tot = $row_pay['gst_amount'];
                  $tds_amount_tot = $row_pay['tds_amount'];
                  $invoice_pay_amount = $row_pay['invoice_pay_amount'];
                  $invoice_issuer_id = $row_pay['invoice_issuer_id'];
                  $pay_method = $row_pay['payment_method'];
                  $pay_checkno = $row_pay['payment_checkno'];
                  $trans_type_pay_gst = 51;
                  $trans_type_name_pay_gst= "gst_receive_payment" ;
                  $hsn_code_gstpay= $row_pay['hsn_code'];
                  $multi_invoice_flag_gstpay= $row_pay['multi_invoice_flag'];
                  $multi_invoice_detail_gstpay= $row_pay['multi_invoice_detail'];
                  $multi_invoice_id_gstpay= $row_pay['multi_invoice_id'];
                  
                  $invoice_pay_id_gstpay= $row_pay['invoice_pay_id'];
                  $invoice_due_pay_id_gstpay= $row_pay['invoice_due_pay_id'];

                  $gst_id_gstpay= $row_pay['gst_id'];
                  $gst_due_id_gstpay =$row_pay['gst_due_id'];

                  $tds_id_gstpay = $row_pay['tds_id'];
                  $tds_due_id_gstpay = $row_pay['tds_due_id'];
                  
                  $tds_flag_gstpay = $row_pay['tds_flag'];
                  $gst_flag_gstpay = $row_pay['gst_flag'];
                  $invoice_flag_gstpay = $row_pay['invoice_flag'];
                  $clear_invoice_flag_gstpay = $row_pay['clear_invoice_flag'];
                  $clear_gst_flag_gstpay = $row_pay['clear_gst_flag'];
                  $clear_tds_flag_gstpay = $row_pay['clear_tds_flag'];

                if($clearall_due_flag=="2")
                {
                    $query2_pay_gst="insert into payment_plan set userid_create = '".$_SESSION['userId']."', trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$due_invoice_id_1."', credit = '".$due_amount_pay_1."', description = '(".$clear_gst_due_des_1_extra.") ".$clear_gstdue_desc."', on_project = '".$project_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['clear_pay_payment_date_gst'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."', payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',trans_type = '".$trans_type_pay_gst."', trans_type_name = '".$trans_type_name_pay_gst."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."' ,create_date = '".getTime()."'";
                    $result2_pay_gst= mysql_query($query2_pay_gst) or die('error in query '.mysql_error().$query2_pay_gst);
                    $link_id_2_pay_gst_clear = mysql_insert_id();  
        
        
                    $query3_clear="insert into gst_due_info set userid_create = '".$_SESSION['userId']."',  pp_linkid_1 = '".$link_id_2_pay_gst_clear."',invoice_id = '".$due_invoice_id_1."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['clear_pay_payment_date_gst'])."',description = '(".$clear_gst_due_des_1_extra.") ".$clear_gstdue_desc."', received_amount = '".$due_amount_pay_1."',clear_due_flag=1 ,create_date = '".getTime()."'";
                    $result3_clear= mysql_query($query3_clear) or die('error in query '.mysql_error().$query3_clear);
                    $link_id_4_clear = mysql_insert_id();

                   $query_clear_gst="insert into `clear_due_amount`  set pp_linkid_1 = '".$link_id_2_pay_gst_clear."',invoice_id  = '".$due_invoice_id_1."',description='".$clear_gstdue_desc."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',due_amount = '".$receiv_amount_gst."', cust_id = '".$cust_id."', user_id = '".$_SESSION['userId']."',due_date = '".strtotime($_REQUEST['clear_pay_payment_date_gst'])."', type = 'GST',create_time = '".getTime()."'";
                   $result_clear_gst= mysql_query($query_clear_gst) or die('error in query '.mysql_error().$query_clear_gst);
                   $link_id_2_clear_gst = mysql_insert_id();  
        
                   $query_gst1_clear="update payment_plan set gst_flag = '1',clear_gst_flag='1' where invoice_id = '".$due_invoice_id_1."'";
                   $result_gst1_clear= mysql_query($query_gst1_clear) or die('error in query '.mysql_error().$query_gst1_clear);
        
                   $query_gst2_clear="update payment_plan set clear_table_link_id = '".$link_id_2_clear_gst."' where id = '".$link_id_2_pay_gst_clear."'";
                   $result_gst2_clear= mysql_query($query_gst2_clear) or die('error in query '.mysql_error().$query_gst2_clear);
                }
                else{

                if($tds_due_flag_value_1=="1")
                {
                    $query3="insert into gst_due_info set userid_create = '".$_SESSION['userId']."', invoice_id = '".$due_invoice_id_1."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['gst_due_date'])."',description = '(".$tds_due_des_1_extra.") ".$tds_due_des_1."', received_amount = '".$tds_due_amount_1."',create_date = '".getTime()."'";
                    $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
                    $link_id_4 = mysql_insert_id();
                    
                    if($_FILES["gst_due_attach_file"]["name"] != "")
                     {
                        $attach_file_name="gst_certificate";
                        $temp = explode(".", $_FILES["gst_due_attach_file"]["name"]);
                         $arr_size = count($temp);
                        $extension = end($temp);
                        $new_file_name = $attach_file_name.'_'.$link_id_4.'_'.date("d_M_Y").'.'.$extension;
                        move_uploaded_file($_FILES["gst_due_attach_file"]["tmp_name"],"gst_files/" . $new_file_name);
                        $query1_1="update gst_due_info set cert_file_name = '".$new_file_name."' where id = '".$link_id_4."'";
                        $result1_1= mysql_query($query1_1) or die('error in query '.mysql_error().$query1_1);
                     }
                            
            $query_pay_gst ="insert into payment_plan set userid_create = '".$_SESSION['userId']."', trans_id = '".$trans_id."', bank_id = '".$pay_bank_id."', debit = '".$tds_due_amount_1."',  description = '(".$tds_due_des_1_extra.") ".$tds_due_des_1."', on_customer = '".$cust_id."', invoice_id = '".$invoice_idnew."',payment_flag = '".$payment_flag."', payment_date = '".strtotime($_REQUEST['gst_due_date'])."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."',invoice_issuer_id = '".$invoice_issuer_id."' ,payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."', trans_type = '".$trans_type_pay_gst."', trans_type_name = '".$trans_type_name_pay_gst."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."',create_date = '".getTime()."'";
            $result_pay_gst= mysql_query($query_pay_gst) or die('error in query '.mysql_error().$query_pay_gst);

            $link_id_1_pay_gst = mysql_insert_id();

            $query2_pay_gst="insert into payment_plan set userid_create = '".$_SESSION['userId']."', trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$invoice_idnew."', credit = '".$tds_due_amount_1."', description = '(".$tds_due_des_1_extra.") ".$tds_due_des_1."', on_project = '".$project_id."', on_bank = '".$pay_bank_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['gst_due_date'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."', payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',link_id = '".$link_id_1_pay_gst."',trans_type = '".$trans_type_pay_gst."', trans_type_name = '".$trans_type_name_pay_gst."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."' ,create_date = '".getTime()."'";
            $result2_pay_gst= mysql_query($query2_pay_gst) or die('error in query '.mysql_error().$query2_pay_gst);

            $link_id_2_pay_gst = mysql_insert_id();  

            $query_gst_update="update gst_due_info set  pp_linkid_1 = '".$link_id_2_pay_gst."',pp_linkid_2 = '".$link_id_1_pay_gst."'  where id = '".$link_id_4."'";
            $result5_gst_update= mysql_query($query_gst_update) or die('error in query '.mysql_error().$query_gst_update);
    
                    if($gst_id_gstpay=="")
                    {
                        $gst_id_gstpay=$link_id_2_pay_gst;
                    }
                    else{
                        $gst_id_gstpay=$gst_id_gstpay.','.$link_id_2_pay_gst;
                    }

                    if($gst_due_id_gstpay=="")
                    {
                            $gst_due_id_gstpay = $link_id_4;
                    }else{
                        $gst_due_id_gstpay=$gst_due_id_gstpay.','.$link_id_4;
                    }

            $query5_pay_gst="update payment_plan set link_id = '".$link_id_2_pay_gst."' ,gst_flag = '1',gst_id='".$gst_id_gstpay."',gst_due_id='".$gst_due_id_gstpay."' where id = '".$link_id_1_pay_gst."'";
            $result5_pay_gst= mysql_query($query5_pay_gst) or die('error in query '.mysql_error().$query5_pay_gst);

            $query5_pay_gst="update payment_plan set gst_flag = '1',gst_id='".$gst_id_gstpay."',gst_due_id='".$gst_due_id_gstpay."' where id = '".$link_id_2_pay_gst."'";
            $result5_pay_gst= mysql_query($query5_pay_gst) or die('error in query '.mysql_error().$query5_pay_gst);

            $query1_2="update payment_plan set gst_flag = '1',gst_id='".$gst_id_gstpay."',gst_due_id='".$gst_due_id_gstpay."' where id = '".$due_payment_id_1."'";
            $result1_2= mysql_query($query1_2) or die('error in query '.mysql_error().$query1_2); 
        
            if($clear_gst_due=="yes"){
            
            $receiv_amount_gst=$due_amount_pay_1 - $tds_due_amount_1;
          
            if($receiv_amount_gst>0)
            {
            
            $query2_pay_gst="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$due_invoice_id_1."', credit = '".$receiv_amount_gst."', description = '(".$clear_gst_due_des_1_extra.") ".$clear_gstdue_desc."', on_project = '".$project_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['clear_pay_payment_date_gst'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."', payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',trans_type = '".$trans_type_pay_gst."', trans_type_name = '".$trans_type_name_pay_gst."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."' ,userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
            $result2_pay_gst= mysql_query($query2_pay_gst) or die('error in query '.mysql_error().$query2_pay_gst);
            $link_id_2_pay_gst_clear = mysql_insert_id();  
    
            $query3_clear="insert into gst_due_info set pp_linkid_1 = '".$link_id_2_pay_gst_clear."',invoice_id = '".$due_invoice_id_1."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['clear_pay_payment_date_gst'])."',description = '(".$clear_gst_due_des_1_extra.") ".$clear_gstdue_desc."', received_amount = '".$receiv_amount_gst."',clear_due_flag=1 ,userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
            $result3_clear= mysql_query($query3_clear) or die('error in query '.mysql_error().$query3_clear);
            $link_id_4_clear = mysql_insert_id();
            //clear_table_link_id           
            $query_clear_gst="insert into `clear_due_amount`  set pp_linkid_1 = '".$link_id_2_pay_gst_clear."',invoice_id  = '".$due_invoice_id_1."',description='".$clear_gstdue_desc."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',due_amount = '".$receiv_amount_gst."', cust_id = '".$cust_id."', user_id = '".$_SESSION['userId']."',due_date = '".strtotime($_REQUEST['clear_pay_payment_date_gst'])."', type = 'GST',create_time = '".getTime()."'";
            $result_clear_gst= mysql_query($query_clear_gst) or die('error in query '.mysql_error().$query_clear_gst);
            $link_id_2_clear_gst = mysql_insert_id();  
    
            $query_gst1_clear="update payment_plan set gst_flag = '1',clear_gst_flag='1' where invoice_id = '".$due_invoice_id_1."'";
            $result_gst1_clear= mysql_query($query_gst1_clear) or die('error in query '.mysql_error().$query_gst1_clear);
    
            $query_gst2_clear="update payment_plan set clear_table_link_id = '".$link_id_2_clear_gst."' where id = '".$link_id_2_pay_gst_clear."'";
            $result_gst2_clear= mysql_query($query_gst2_clear) or die('error in query '.mysql_error().$query_gst2_clear);
            
            $query_gst1="update payment_plan set gst_flag = '1',clear_gst_flag='1' ,gst_id='".$gst_id_gstpay."',gst_due_id='".$gst_due_id_gstpay."' where invoice_id = '".$due_invoice_id_1."'";
            $result_gst1= mysql_query($query_gst1) or die('error in query '.mysql_error().$query_gst1);
            }          

            }         
        }
            }
}

/* ---------------   GST DUE WORK END   -----------------------------*/

        

/*  --------      INVOICE DUE WORK        -----------------------  */
if(mysql_real_escape_string(trim($_REQUEST['file_button_invoice'])) == "Submit")
{
   // print_r($_REQUEST);    die();
   $invoice_cerf_1=mysql_real_escape_string(trim($_REQUEST['invoice_cerf']));
   $invoice_due_date_1=mysql_real_escape_string(trim($_REQUEST['pay_payment_date']));
   $pay_amount_1=mysql_real_escape_string(trim($_REQUEST['pay_amount']));
   $invoice_due_des_1=mysql_real_escape_string(trim($_REQUEST['invoice_due_des']));
   $invoice_due_flag_value_1=mysql_real_escape_string(trim($_REQUEST['invoice_due_flag_value']));
   $due_trans_id_1=mysql_real_escape_string(trim($_REQUEST['invoice_trans_id']));
   $due_payment_id_1=mysql_real_escape_string(trim($_REQUEST['invoice_payment_id']));
   $due_invoice_id_1=mysql_real_escape_string(trim($_REQUEST['invoice_invoice_id']));
   $due_amount_pay_1=mysql_real_escape_string(trim($_REQUEST['invoice_amount_pay']));
   $pay_form=mysql_real_escape_string(trim($_REQUEST['pay_form']));
   $invoice_due_date_1_n=strtotime($invoice_due_date_1);
   
   $clearall_due_flag_invoice=mysql_real_escape_string(trim($_REQUEST['clearall_due_flag_invoice']));
   $invoice_due_amount_new_total=mysql_real_escape_string(trim($_REQUEST['invoice_due_amount_new_total']));
   $clear_invoice_due=mysql_real_escape_string(trim($_REQUEST['clear_invoice_due']));
   $clear_invoicedue_desc=mysql_real_escape_string(trim($_REQUEST['clear_invoicedue_desc']));
    $pay_from_arr = explode(" -",$_REQUEST['pay_form']);
    $pay_bank_id = get_field_value("id","bank","bank_account_name",$pay_from_arr[0]);
   
    $sql_pay 	= "select * from `payment_plan` where id ='".$due_payment_id_1."'";
    $query_pay_1 	= mysql_query($sql_pay);
    $row_pay = mysql_fetch_array($query_pay_1);
    $trans_id=$row_pay['trans_id'];
           
    $subdivision = $row_pay['subdivision'];
    $gst_subdivision_n = $row_pay['gst_subdivision'];
    $tds_subdivision_n = $row_pay['tds_subdivision'];
    $gst_amount_tot = $row_pay['gst_amount'];
    $tds_amount_tot = $row_pay['tds_amount'];
    $invoice_pay_amount = $row_pay['invoice_pay_amount'];
    $invoice_issuer_id = $row_pay['invoice_issuer_id'];
    $pay_method = $row_pay['payment_method'];
    $pay_checkno = $row_pay['payment_checkno'];
    //$link_id_2 = $row_pay[''];
    $trans_type_pay_invoice = 22;
    $trans_type_name_pay_invoice= "instmulti_receive_payment_invoice" ;
    $cust_id = $row_pay['cust_id'];
    $invoice_idnew = $row_pay['invoice_id'];
               
    $hsn_code_gstpay= $row_pay['hsn_code'];
    $multi_invoice_flag_gstpay= $row_pay['multi_invoice_flag'];
    $multi_invoice_detail_gstpay= $row_pay['multi_invoice_detail'];
    $multi_invoice_id_gstpay= $row_pay['multi_invoice_id'];
           
    $invoice_pay_id_gstpay= $row_pay['invoice_pay_id'];
    $invoice_due_pay_id_gstpay= $row_pay['invoice_due_pay_id'];
    $payment_flag = $row_pay['payment_flag'];

    $gst_id_gstpay= $row_pay['gst_id'];
    $gst_due_id_gstpay =$row_pay['gst_due_id'];

    $tds_id_gstpay = $row_pay['tds_id'];
    $tds_due_id_gstpay = $row_pay['tds_due_id'];
           
    $tds_flag_gstpay = $row_pay['tds_flag'];
    $gst_flag_gstpay = $row_pay['gst_flag'];
    $invoice_flag_gstpay = $row_pay['invoice_flag'];
    $clear_invoice_flag_gstpay = $row_pay['clear_invoice_flag'];
    $clear_gst_flag_gstpay = $row_pay['clear_gst_flag'];
    $clear_tds_flag_gstpay = $row_pay['clear_tds_flag'];
    $invoice_due_des_1_extra = "Amount Paid for invoice : ".$due_invoice_id_1."";     
    $clear_invoice_due_des_1_extra = "Clear Amount for invoice : ".$due_invoice_id_1."";  

       if($clearall_due_flag_invoice=="2")
       {
        $query2_pay_invoice="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$invoice_idnew."', credit = '".$invoice_due_amount_new_total."', description = '(".$clear_invoice_due_des_1_extra.") ".$clear_invoicedue_desc."', on_project = '".$project_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['clear_pay_payment_date'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."', payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',trans_type = '".$trans_type_pay_invoice."', trans_type_name = '".$trans_type_name_pay_invoice."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."' ,userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
        $result2_pay_invoice= mysql_query($query2_pay_invoice) or die('error in query '.mysql_error().$query2_pay_invoice);
        $link_id_2_pay_invoice_clear = mysql_insert_id();  


        $query3_clear="insert into invoice_due_info set pp_linkid_1 = '".$link_id_2_pay_invoice_clear."',invoice_id = '".$invoice_idnew."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['clear_pay_payment_date'])."',description = '(".$clear_invoice_due_des_1_extra.") ".$clear_invoicedue_desc."', received_amount = '".$invoice_due_amount_new_total."',clear_due_flag=1 ,userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
        $result3_clear= mysql_query($query3_clear) or die('error in query '.mysql_error().$query3_clear);
        $link_id_4_clear = mysql_insert_id();
        //clear_table_link_id           
           $query_clear_invoice="insert into `clear_due_amount`  set pp_linkid_1 = '".$link_id_2_pay_invoice_clear."',invoice_id  = '".$invoice_idnew."',description='".$clear_invoicedue_desc."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',due_amount = '".$invoice_due_amount_new_total."', cust_id = '".$cust_id."', user_id = '".$_SESSION['userId']."',due_date = '".strtotime($_REQUEST['clear_pay_payment_date'])."', type = 'Invoice',create_time = '".getTime()."'";
           $result_clear_invoice= mysql_query($query_clear_invoice) or die('error in query '.mysql_error().$query_clear_invoice);
           $link_id_2_clear_invoice = mysql_insert_id();  

           $query_invoice1_clear="update payment_plan set invoice_flag = '1',clear_invoice_flag='1' where invoice_id = '".$invoice_idnew."'";
           $result_invoice1_clear= mysql_query($query_invoice1_clear) or die('error in query '.mysql_error().$query_invoice1_clear);

           $query_invoice2_clear="update payment_plan set clear_table_link_id = '".$link_id_2_clear_invoice."' where id = '".$link_id_2_pay_invoice_clear."'";
           $result_invoice2_clear= mysql_query($query_invoice2_clear) or die('error in query '.mysql_error().$query_invoice2_clear);
           
       }
       else{

             
             if($invoice_due_flag_value_1=="1")
               {                  
                   $query3="insert into invoice_due_info set invoice_id = '".$invoice_idnew."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['pay_payment_date'])."',description = '(".$invoice_due_des_1_extra.") ".$invoice_due_des_1."', received_amount = '".$pay_amount_1."',userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
                   $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
                   $link_id_4 = mysql_insert_id();
                   
                   if($_FILES["invoice_due_attach_file"]["name"] != "")
                    {
                       $attach_file_name="invoice_certificate";
                       $temp = explode(".", $_FILES["invoice_due_attach_file"]["name"]);
                        $arr_size = count($temp);
                       $extension = end($temp);
                       $new_file_name = $attach_file_name.'_'.$link_id_4.'_'.date("d_M_Y").'.'.$extension;
                       move_uploaded_file($_FILES["invoice_due_attach_file"]["tmp_name"],"invoice_files/" . $new_file_name);
                       $query1_1="update invoice_due_info set cert_file_name = '".$new_file_name."' where id = '".$link_id_4."'";
                       $result1_1= mysql_query($query1_1) or die('error in query '.mysql_error().$query1_1);
                    }
                   
                           
           $query_pay_invoice ="insert into payment_plan set trans_id = '".$trans_id."', bank_id = '".$pay_bank_id."', debit = '".$pay_amount_1."',  description = '(".$invoice_due_des_1_extra.") ".$invoice_due_des_1."', on_customer = '".$cust_id."', invoice_id = '".$invoice_idnew."',payment_flag = '".$payment_flag."', payment_date = '".strtotime($_REQUEST['pay_payment_date'])."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."',invoice_issuer_id = '".$invoice_issuer_id."' ,payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."', trans_type = '".$trans_type_pay_invoice."', trans_type_name = '".$trans_type_name_pay_invoice."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."',userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
           $result_pay_invoice= mysql_query($query_pay_invoice) or die('error in query '.mysql_error().$query_pay_invoice);


           $link_id_1_pay_invoice = mysql_insert_id();


           $query2_pay_invoice="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$invoice_idnew."', credit = '".$pay_amount_1."', description = '(".$invoice_due_des_1_extra.") ".$invoice_due_des_1."', on_project = '".$project_id."', on_bank = '".$pay_bank_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['pay_payment_date'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."', payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',link_id = '".$link_id_1_pay_invoice."',trans_type = '".$trans_type_pay_invoice."', trans_type_name = '".$trans_type_name_pay_invoice."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."' ,userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
           $result2_pay_invoice= mysql_query($query2_pay_invoice) or die('error in query '.mysql_error().$query2_pay_invoice);

           $link_id_2_pay_invoice = mysql_insert_id();  

           
        $query_invoice_update="update invoice_due_info set  pp_linkid_1 = '".$link_id_2_pay_invoice."',pp_linkid_2 = '".$link_id_1_pay_invoice."'  where id = '".$link_id_4."'";
        $result5_invoice_update= mysql_query($query_invoice_update) or die('error in query '.mysql_error().$query_invoice_update);


      
        if($invoice_pay_id_gstpay=="")
        {
           $invoice_pay_id_gstpay=$link_id_2_pay_invoice;
        }
        else
        {
           $invoice_pay_id_gstpay=$invoice_pay_id_gstpay.','.$link_id_2_pay_invoice;
        }

        if($invoice_due_pay_id_gstpay=="")
        {
           $invoice_due_pay_id_gstpay = $link_id_4;
        }
        else
        {
           $invoice_due_pay_id_gstpay=$invoice_due_pay_id_gstpay.','.$link_id_4;
        }

           $query5_pay_invoice="update payment_plan set link_id = '".$link_id_2_pay_invoice."' ,invoice_flag = '1',invoice_pay_id='".$invoice_pay_id_gstpay."',invoice_due_pay_id='".$invoice_due_pay_id_gstpay."' where id = '".$link_id_1_pay_invoice."'";
           $result5_pay_invoice= mysql_query($query5_pay_invoice) or die('error in query '.mysql_error().$query5_pay_invoice);

           $query5_pay_invoice="update payment_plan set invoice_flag = '1',invoice_pay_id='".$invoice_pay_id_gstpay."',invoice_due_pay_id='".$invoice_due_pay_id_gstpay."' where id = '".$link_id_2_pay_invoice."'";
           $result5_pay_invoice= mysql_query($query5_pay_invoice) or die('error in query '.mysql_error().$query5_pay_invoice);

           $query1_2="update payment_plan set invoice_flag = '1',invoice_pay_id='".$invoice_pay_id_gstpay."',invoice_due_pay_id='".$invoice_due_pay_id_gstpay."' where id = '".$due_payment_id_1."'";
           $result1_2= mysql_query($query1_2) or die('error in query '.mysql_error().$query1_2); 
       
           if($clear_invoice_due=="yes"){
            $receiv_amount_invoice=$due_amount_pay_1 - $pay_amount_1;
          if($receiv_amount_invoice>0)
          {
            $query2_pay_invoice_clear="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$invoice_idnew."', credit = '".$receiv_amount_invoice."', description = '(".$clear_invoice_due_des_1_extra.") ".$clear_invoicedue_desc."', on_project = '".$project_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['clear_pay_payment_date'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."', payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',trans_type = '".$trans_type_pay_invoice."', trans_type_name = '".$trans_type_name_pay_invoice."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."' ,userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
            $result2_pay_invoice_clear= mysql_query($query2_pay_invoice_clear) or die('error in query '.mysql_error().$query2_pay_invoice_clear);
            $link_id_2_pay_invoice_clear = mysql_insert_id();  


            $query3_clear="insert into invoice_due_info set pp_linkid_1 = '".$link_id_2_pay_invoice_clear."',invoice_id = '".$invoice_idnew."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['clear_pay_payment_date'])."',description = '(".$clear_invoice_due_des_1_extra.") ".$clear_invoicedue_desc."', received_amount = '".$receiv_amount_invoice."',clear_due_flag=1 ,userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
            $result3_clear= mysql_query($query3_clear) or die('error in query '.mysql_error().$query3_clear);
            $link_id_4_clear = mysql_insert_id();
                  
           $query_clear_invoice="insert into `clear_due_amount`  set pp_linkid_1 = '".$link_id_2_pay_invoice_clear."',invoice_id  = '".$invoice_idnew."',description='".$clear_invoicedue_desc."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',due_amount = '".$receiv_amount_invoice."', cust_id = '".$cust_id."', user_id = '".$_SESSION['userId']."',due_date = '".strtotime($_REQUEST['clear_pay_payment_date'])."', type = 'Invoice',create_time = '".getTime()."'";
           $result_clear_invoice= mysql_query($query_clear_invoice) or die('error in query '.mysql_error().$query_clear_invoice);
           $link_id_2_clear_invoice = mysql_insert_id();  

           $query_invoice2_clear="update payment_plan set clear_table_link_id = '".$link_id_2_clear_invoice."' where id = '".$link_id_2_pay_invoice_clear."'";
           $result_invoice2_clear= mysql_query($query_invoice2_clear) or die('error in query '.mysql_error().$query_invoice2_clear);
        

           $query_invoice1="update payment_plan set invoice_flag = '1',clear_invoice_flag='1' ,invoice_pay_id='".$invoice_pay_id_gstpay."',invoice_due_pay_id='".$invoice_due_pay_id_gstpay."' where invoice_id = '".$invoice_idnew."'";
           $result_invoice1= mysql_query($query_invoice1) or die('error in query '.mysql_error().$query_invoice1);

          }
           }
           }


       }

    
}

/* ---------------   INVOICE DUE WORK END   -----------------------------*/

//code by amit




if(isset($_REQUEST['trans_id_combind']) && $_REQUEST['trans_id_combind'] != "")
{
    $trans_id = $_REQUEST['trans_id_combind'];
    $payment_id = $_REQUEST['payment_id'];
    $del_query = "delete from payment_plan where trans_id = '".$trans_id."'";
    $del_result = mysql_query($del_query) or die("error in Transaction delete query ".mysql_error());
    
    $del_query = "delete from invoice_due_info where pp_linkid_1 = '".$payment_id."'";
    $del_result = mysql_query($del_query) or die("error in Transaction delete query ".mysql_error());
    
    $del_query = "delete from gst_due_info where pp_linkid_1 = '".$payment_id."'";
    $del_result = mysql_query($del_query) or die("error in Transaction delete query ".mysql_error());
    
    $msg = "Combined Transaction Deleted Successfully.";
}

if(isset($_REQUEST['trans_id']) && $_REQUEST['trans_id'] != "")
{
	$trans_id = $_REQUEST['trans_id'];
	$del_query = "delete from payment_plan where trans_id = '".$trans_id."'";
	$del_result = mysql_query($del_query) or die("error in Transaction delete query ".mysql_error());
	$msg = "Transaction Deleted Successfully.";

    if($_REQUEST['returnto'])
        header("Location: ".$_REQUEST['returnto']);
}
if(isset($_REQUEST['trans_id_1']) && $_REQUEST['trans_id_1'] != "")
{
    $trans_id_1 = $_REQUEST['trans_id_1'];
    
    $query_del="select *  from attach_file where id = '".$trans_id_1."'";
    $result_del= mysql_query($query_del) or die('error in query '.mysql_error().$query_del);
    $data_del = mysql_fetch_array($result_del);
    $second_id= $data_del['old_id'];

    $second_id2= $data_del['old_id2'];
    $second_id3= $data_del['old_id3'];
    $fnm = $data_del['file_name'];
    
    $del_query_1 = "delete from attach_file where id = '".$trans_id_1."'";
    $del_result_1 = mysql_query($del_query_1) or die("error in Transaction delete query ".mysql_error());
    
    unlink("transaction_files/$fnm");
        $del_query_2 = "delete from attach_file where id = '".$second_id."'";
    $del_result_2 = mysql_query($del_query_2) or die("error in Transaction delete query ".mysql_error());
    
        $del_query_2 = "delete from attach_file where id = '".$second_id2."'";
    $del_result_2 = mysql_query($del_query_2) or die("error in Transaction delete query ".mysql_error());
    
    $del_query_2 = "delete from attach_file where id = '".$second_id3."'";
    $del_result_2 = mysql_query($del_query_2) or die("error in Transaction delete query ".mysql_error());
    $msg = "Transaction Deleted Successfully.";
}
if(mysql_real_escape_string(trim($_REQUEST['file_button'])) == "Submit")
{
    if($_FILES["attach_file"]["name"] != "")
	{
        $attach_file_name=mysql_real_escape_string(trim($_REQUEST['attach_file_name']));
        $attach_file_id=mysql_real_escape_string(trim($_REQUEST['attach_file_id']));
        $temp = explode(".", $_FILES["attach_file"]["name"]);
        $arr_size = count($temp);
        $extension = end($temp);
        
        $query="select link_id from payment_plan where id = '".$attach_file_id."'";
        $result= mysql_query($query) or die('error in query '.mysql_error().$query);
        $data = mysql_fetch_array($result);
		
        $link_id_2 = $data['link_id'];
	
        $query3="insert into attach_file set attach_id = '".$attach_file_id."', link_id = '".$link_id_2."',file_name = '".$new_file_name."'";
        $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
        $link_id_4 = mysql_insert_id();
        
        $new_file_name = $attach_file_name.'_'.$link_id_4.'_'.date("d_M_Y").'.'.$extension;
        	
        $query4="insert into attach_file set attach_id = '".$link_id_2."', link_id = '".$attach_file_id."',old_id = '".$link_id_4."',file_name = '".$new_file_name."'";
        $result4= mysql_query($query4) or die('error in query '.mysql_error().$query4);
        $link_id_5 = mysql_insert_id();
    
        $query5_1="update attach_file set old_id = '".$link_id_5."',file_name = '".$new_file_name."' where id = '".$link_id_4."'";
        $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);
        move_uploaded_file($_FILES["attach_file"]["tmp_name"],"transaction_files/" . $new_file_name);
	}
}

//code by amit Start

/*  --------      COMBIND PAYMENT WORK START        -----------------------  */
if(mysql_real_escape_string(trim($_REQUEST['file_button_combind'])) == "Submit")
{
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
   $combind_due_des_1=mysql_real_escape_string(trim($_REQUEST['combind_payment_due_des']));
   $pay_amount_1=mysql_real_escape_string(trim($_REQUEST['combind_payment_due_amount']));
   $pay_form=mysql_real_escape_string(trim($_REQUEST['combind_payment_pay_form']));
   $invoice_due_date_1_n=strtotime($_REQUEST['combind_payment_due_date']);
   $combind_cust_id_1=mysql_real_escape_string(trim($_REQUEST['combind_cust_id']));
   
   
   $pay_from_arr = explode(" -",$_REQUEST['combind_payment_pay_form']);
   $pay_bank_id = get_field_value("id","bank","bank_account_name",$pay_from_arr[0]);
   $trans_type_pay_combind = 51;
   $trans_type_name_pay_combind= "combind_payment" ;
  
   
   $query_pay_invoice ="insert into payment_plan set  trans_id = '".$trans_id."', bank_id = '".$pay_bank_id."', debit = '".$pay_amount_1."',  description = '".$combind_due_des_1."', on_customer = '".$combind_cust_id_1."',payment_flag = '".$payment_flag."', payment_date = '".strtotime($_REQUEST['combind_payment_due_date'])."',payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."', trans_type = '".$trans_type_pay_combind."', trans_type_name = '".$trans_type_name_pay_combind."',userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
   $result_pay_invoice= mysql_query($query_pay_invoice) or die('error in query '.mysql_error().$query_pay_invoice);
   $link_id_1_pay_invoice = mysql_insert_id();


   $query2_pay_invoice="insert into payment_plan set  trans_id = '".$trans_id."',cust_id = '".$combind_cust_id_1."',credit = '".$pay_amount_1."', description = '".$combind_due_des_1."',  on_bank = '".$pay_bank_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['combind_payment_due_date'])."' ,payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',link_id = '".$link_id_1_pay_invoice."',trans_type = '".$trans_type_pay_combind."', trans_type_name = '".$trans_type_name_pay_combind."' ,userid_create = '".$_SESSION['userId']."' ,create_date = '".getTime()."'";
   $result2_pay_invoice= mysql_query($query2_pay_invoice) or die('error in query '.mysql_error().$query2_pay_invoice);
   $link_id_2_pay_invoice = mysql_insert_id();  
   
   $tot_final_pay="0"; 
        for($im=1;$im<mysql_real_escape_string(trim($_REQUEST['combind_gst_count']));$im++)  
        {
            $combind_gst_invoiceid=mysql_real_escape_string(trim($_REQUEST['combind_gst_invoiceid'.$im]));
            $combind_gst_amount=mysql_real_escape_string(trim($_REQUEST['combind_gst_amount'.$im]));
            $combind_gst_amount_pay=mysql_real_escape_string(trim($_REQUEST['combind_gst_amount_pay'.$im]));
            $combind_gst_payment_planid=mysql_real_escape_string(trim($_REQUEST['combind_gst_payment_planid'.$im]));
            $combind_gst_type=mysql_real_escape_string(trim($_REQUEST['combind_gst_type'.$im]));
            $due_trans_id_1=""; 
            $query3="insert into gst_due_info set combind_payment='1', invoice_id = '".$combind_gst_invoiceid."',payment_plan_id = '".$combind_gst_payment_planid."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['combind_payment_due_date'])."',combine_description = '(Combined GST Pay For Invoice".$combind_gst_invoiceid.") ',description='".$combind_due_des_1."', received_amount = '".$combind_gst_amount_pay."',pp_linkid_1 = '".$link_id_2_pay_invoice."',pp_linkid_2 = '".$link_id_1_pay_invoice."',userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
            $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
            $link_id_4 = mysql_insert_id();
            
            $tot_final_pay=$tot_final_pay+$combind_gst_amount_pay;
        }
        for($im=1;$im<mysql_real_escape_string(trim($_REQUEST['combind_invoice_count']));$im++)  
        {
            $combind_invoice_invoiceid=mysql_real_escape_string(trim($_REQUEST['combind_invoice_invoiceid'.$im]));
            $combind_invoice_amount=mysql_real_escape_string(trim($_REQUEST['combind_invoice_amount'.$im]));
            $combind_invoice_amount_pay=mysql_real_escape_string(trim($_REQUEST['combind_invoice_amount_pay'.$im]));
            $combind_invoice_payment_planid=mysql_real_escape_string(trim($_REQUEST['combind_invoice_payment_planid'.$im]));
            $combind_invoice_type=mysql_real_escape_string(trim($_REQUEST['combind_invoice_type'.$im]));
            $due_trans_id_1=""; 
            $query3="insert into invoice_due_info  set combind_payment='1', invoice_id = '".$combind_invoice_invoiceid."',payment_plan_id = '".$combind_invoice_payment_planid."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['combind_payment_due_date'])."',combine_description = '(Combined Invoice Pay For Invoice".$combind_invoice_invoiceid.") ',description='".$combind_due_des_1."', received_amount = '".$combind_invoice_amount_pay."',pp_linkid_1 = '".$link_id_2_pay_invoice."',pp_linkid_2 = '".$link_id_1_pay_invoice."',userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
            $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
            $link_id_4 = mysql_insert_id();
           
            $tot_final_pay=$tot_final_pay+$combind_invoice_amount_pay;
        }
        
        $query_invoice_update="update payment_plan set  debit = '".$tot_final_pay."'  where id = '".$link_id_1_pay_invoice."'";
        $result5_invoice_update= mysql_query($query_invoice_update) or die('error in query '.mysql_error().$query_invoice_update);
        
        $query_invoice_update_1="update payment_plan set  credit = '".$tot_final_pay."' where id = '".$link_id_2_pay_invoice."'";
        $result5_invoice_update_1= mysql_query($query_invoice_update_1) or die('error in query '.mysql_error().$query_invoice_update_1);

             if($invoice_due_flag_value_1=="1")
               {                  
                   $query3="insert into invoice_due_info set invoice_id = '".$invoice_idnew."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['pay_payment_date'])."',description = '(".$invoice_due_des_1_extra.") ".$invoice_due_des_1."',amount = '".$due_amount_pay_1."', received_amount = '".$pay_amount_1."',create_date = '".getTime()."'";
                   $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
                   $link_id_4 = mysql_insert_id();
                   
                   if($_FILES["invoice_due_attach_file"]["name"] != "")
                    {
                       $attach_file_name="invoice_certificate";
                       $temp = explode(".", $_FILES["invoice_due_attach_file"]["name"]);
                        $arr_size = count($temp);
                       $extension = end($temp);
                       $new_file_name = $attach_file_name.'_'.$link_id_4.'_'.date("d_M_Y").'.'.$extension;
                       move_uploaded_file($_FILES["invoice_due_attach_file"]["tmp_name"],"invoice_files/" . $new_file_name);
                       $query1_1="update invoice_due_info set cert_file_name = '".$new_file_name."' where id = '".$link_id_4."'";
                       $result1_1= mysql_query($query1_1) or die('error in query '.mysql_error().$query1_1);
                    }
           
           
            $query_invoice_update="update invoice_due_info set  pp_linkid_1 = '".$link_id_2_pay_invoice."',pp_linkid_2 = '".$link_id_1_pay_invoice."'  where id = '".$link_id_4."'";
            $result5_invoice_update= mysql_query($query_invoice_update) or die('error in query '.mysql_error().$query_invoice_update);


      
                    if($invoice_pay_id_gstpay=="")
                   {
                       $invoice_pay_id_gstpay=$link_id_2_pay_invoice;
                   }
                   else{
                       $invoice_pay_id_gstpay=$invoice_pay_id_gstpay.','.$link_id_2_pay_invoice;
                   }

                   if($invoice_due_pay_id_gstpay=="")
                   {
                           $invoice_due_pay_id_gstpay = $link_id_4;
                   }else{
                       $invoice_due_pay_id_gstpay=$invoice_due_pay_id_gstpay.','.$link_id_4;
                   }

           $query5_pay_invoice="update payment_plan set link_id = '".$link_id_2_pay_invoice."' ,invoice_flag = '1',invoice_pay_id='".$invoice_pay_id_gstpay."',invoice_due_pay_id='".$invoice_due_pay_id_gstpay."' where id = '".$link_id_1_pay_invoice."'";
           $result5_pay_invoice= mysql_query($query5_pay_invoice) or die('error in query '.mysql_error().$query5_pay_invoice);

           $query5_pay_invoice="update payment_plan set invoice_flag = '1',invoice_pay_id='".$invoice_pay_id_gstpay."',invoice_due_pay_id='".$invoice_due_pay_id_gstpay."' where id = '".$link_id_2_pay_invoice."'";
           $result5_pay_invoice= mysql_query($query5_pay_invoice) or die('error in query '.mysql_error().$query5_pay_invoice);

           $query1_2="update payment_plan set invoice_flag = '1',invoice_pay_id='".$invoice_pay_id_gstpay."',invoice_due_pay_id='".$invoice_due_pay_id_gstpay."' where id = '".$due_payment_id_1."'";
           $result1_2= mysql_query($query1_2) or die('error in query '.mysql_error().$query1_2); 
       
           }
}

/* ---------------   COMBIND PAYMENT WORK END   -----------------------------*/


//code end by amit



if(mysql_real_escape_string(trim($_REQUEST['search_action'])) != "")
{
	$from_date = strtotime(mysql_real_escape_string(trim($_REQUEST['from_date'])));
	
	$to_date = strtotime(mysql_real_escape_string(trim($_REQUEST['to_date'])));

	$select_query = "select *,payment_plan.id as payment_id from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'supplier' and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
	$select_result = mysql_query($select_query) or die('error in query select cash query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
	
	$select_query2 = "select *,payment_plan.id as payment_id from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'supplier' and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
	$select_result2 = mysql_query($select_query2) or die('error in query select cash query '.mysql_error().$select_query2);
	$select_total2 = mysql_num_rows($select_result2);
    
    $select_query3 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'supplier' and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."'  ";
    $select_result3 = mysql_query($select_query3) or die('error in query select cash query '.mysql_error().$select_query3);
    $select_data3 = mysql_fetch_array($select_result3);
    //echo $select_data3['total_credit']-$select_data3['total_debit'];
    $bal=$select_data3['total_credit']-$select_data3['total_debit'];

    $select_query3_1 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'supplier' and  payment_plan.payment_date <= '".$to_date."'  ";
    $select_result3_1 = mysql_query($select_query3_1) or die('error in query select cash query '.mysql_error().$select_query3_1);
    $select_data3_1 = mysql_fetch_array($select_result3_1);
    //echo $select_data3['total_credit']-$select_data3['total_debit'];
    //$bal_credit,$bal_debit
    $bal_1=$select_data3_1['total_credit']-$select_data3_1['total_debit'];
    $bal_credit = $select_data3_1['total_credit'];
    $bal_debit = $select_data3_1['total_debit'];
    $search_start ="1";
                
                
    $select_query5 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'supplier' and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."'  ";
                
                
}
else
{
	$select_query = "select *,payment_plan.id as payment_id from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'supplier' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
	$select_result = mysql_query($select_query) or die('error in query select customer query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
    
	
	$select_query2 = "select *,payment_plan.id as payment_id from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'supplier' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
	$select_result2 = mysql_query($select_query2) or die('error in query select customer query '.mysql_error().$select_query2);
	$select_total2 = mysql_num_rows($select_result2);
    
    $select_query3 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'supplier'";
                $select_result3 = mysql_query($select_query3) or die('error in query select cash query '.mysql_error().$select_query3);
                $select_data3 = mysql_fetch_array($select_result3);
                //echo $select_data3['total_credit']-$select_data3['total_debit'];
                $bal=$select_data3['total_credit']-$select_data3['total_debit'];

                $select_query5 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'supplier'";
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

                            <!--- COMBIND PAYMENT OPEN Div   -->
        <div id="combind_payment_div" style="position:absolute; top:28%; left:40%; width:590px; height:480px; margin:-100px 0 0 -50px;z-index:100; display:none; background-color:#FFFFFF; border:8px solid #999999;" >
        <form name="combind_payment_form" id="combind_payment_form" method="post" action="" onSubmit="return combind_payment_validation();" enctype="multipart/form-data" >
       <table cellpadding="0" cellspacing="0" border="1px" width="100%" >
       <tr><td valign="top" align=left>
        <table >
            <tr> 
                <td valign="top"  width="48%" align="left" style="color:#FF0000; font-weight:bold;">&nbsp;&nbsp;Combine Payment Amount &nbsp;                            
                           <input type="text" id="combind_payment_amount_new_total" style="width:80px;color:red; border:0; " readonly="readonly"  name="combind_payment_amount_new_total" value="" /></td>
                <td valign="top"  align="right"><img src="images/close.gif" onClick="return close_combind_payment_div();" ></td>
            </tr>
           
        </table>
       </td></tr>            
       <tr>  
                   <td valign="top"  align="left" id="combind_payment-due_div2" style=" display:block; width:100%">
                       <table width="100%" border="2" style="border:2px;">  
                       <tr>
                           <td valign="top" >Paid From</td>
                           <td>
                            <input type="text" id="combind_payment_pay_form"  name="combind_payment_pay_form" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span> 
                       </td>
                       </tr>
                        <tr>    <td valign="top"  width="180px" >Date</td>
                           <td><input type="text"  name="combind_payment_due_date" id="combind_payment_due_date" value="<?php //echo $_REQUEST['tds_due_date']; ?>" style="width:250px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('combind_payment_due_date')" style="cursor:pointer"/></td>
                       </tr>
                       <tr>
                           <td valign="top" colspan="2" >Amount Paid</td>
                       </tr>
                      <tr>
                        <td colspan="2">
                            <div style="max-height: 100px;overflow:auto;" >
                        <table width="100%" id="myTable_2" ><tr><td></td></tr></table></div>
                        </td>
                      </tr>
                       <tr>
                           <td valign="top" >File Attachment</td>
                           <td><input type="file" name="combind_payment_due_attach_file" id="combind_payment_due_attach_file" value="" style="width:250px;" ></td>
                       </tr>
                       <tr>
                           <td valign="top" >Description</td>
                           <td><input type="text" id="combind_payment_due_des"  name="combind_payment_due_des" value="" style="width:250px;" autocomplete="off"/></td>
                       </tr>
                   </table>
                   </td>
               </tr>     
               <input type="hidden" id="combind_cust_id"  name="combind_cust_id" value="<?php echo $_REQUEST['cust_id']; ?>" />

               <input type="hidden" id="clearall_due_flag_combind_payment"  name="clearall_due_flag_combind_payment" value="" /> 
           <input type="hidden" id="combind_payment_due_flag_value"  name="combind_payment_due_flag_value" value="0" />
           <input type="hidden" id="due_payment_id"  name="due_payment_id" value="" />
           <input type="hidden" id="combind_attach_file_id"  name="combind_attach_file_id" value="" />
           <input type="hidden" id="invoice_flag"  name="invoice_flag" value="" />
           <input type="hidden" id="due_trans_id"  name="due_trans_id" value="" />
           <input type="hidden" id="due_invoice_id"  name="due_invoice_id" value="" />
           <input type="hidden" id="due_amount_pay"  name="due_amount_pay" value="" />
           <input type="hidden" id="combind_amount[]"  name="combind_amount[]" value="" />
           <input type="hidden" id="combind_invoice[]"  name="combind_invoice[]" value="" />
           <input type="hidden" id="combind_type[]"  name="combind_type[]" value="" />
           <tr>
               <td valign="bottom" align="center" ><input type="submit" class="button" name="file_button_combind" id="file_button_combind" value="Submit" ></td></tr>
       </table>
       
       </form>
       </div>
       
               <!-- COMBIND PAYMENT CLOSE Div -->

  <?php include_once ("top_header2.php"); ?> 
  <?php include_once ("top_menu.php"); ?>
  <?php include_once("main_heading_open.php") ?>


  
	<table style="width:100%;" border="0" style="padding:0px 0px; margin-top:-10px;">
    <tr>
        <td style="align:top;" align="left">
        <h4 class="u-text-2 u-text-palette-1-base " style="padding:0px; margin:0px;">
		Supplier - <?php echo get_field_value("full_name","customer","cust_id",$_REQUEST['cust_id']); ?> Ledger </h4>
  </td>
        <td width="" style="float:right;">
        <a href="javascript:void(0);"   title="combind Payment" onClick="return combind_payment_function();" ><input type="button" name="com_button" id="com_button" value="Combine Payment" class="button_normal"  /> </a>
		<a href="supplier.php" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
                    <input type="button" name="print_button" id="print_button" value="" class="button_print" onClick="return print_data();"  />
					
                    
<script src="dist/jquery.table2excel.min.js"></script>
<input type="button" id="export_to_excel" value="" class="button_export" >
<input type="button" id="search" value="" class="button_search1" onClick="search_display();" >
                                       
</td>
    </tr>
</table>
<input type="hidden" id="print_header" name="print_header" value="<h3>Supplier - <?php echo get_field_value("full_name","customer","cust_id",$_REQUEST['cust_id']); ?> Ledger</h3>">	

<?php include_once("main_heading_close.php") ?>

<?php include_once("main_search_open.php") ?>

  <form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" >
  <input type="hidden" name="search_check_val" id="search_check_val" value="0" >
  <input type="hidden" name="search_check_val_1" id="search_check_val_1" value="<?php echo $sear_val_f; ?>" >
  <link rel="stylesheet" href="css/jquery-ui.css" />
             <script src="js/jquery-ui.js"></script>    

			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					
					<td width="50">
					&nbsp;&nbsp;From
					</td>
					<td width="120">
					<input type="text"  name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>" style="width:100px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('from_date')" style="cursor:pointer"/>
				 </td>
				
				 <td width="50">
					&nbsp;&nbsp;To
					</td>
					<td width="120">
					<input type="text"  name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>" style="width:100px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('to_date')" style="cursor:pointer"/>
				 </td>
				 
					<td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='supplier-ledger.php?cust_id=<?php echo $_REQUEST['cust_id']; ?>';"  /></td>
					<td align="right" valign="top" >
                    </td>
					
					
				</tr>
			</table>
			<input type="hidden" name="search_action" id="search_action" value="Search"  />
			
			</form>

  <?php include_once("main_search_close.php") ?>

  <?php include_once("main_body_open.php") ?>
  

		<div id="ledger_data" style="height: 400px; width:98%; overflow-y: scroll;">
        <table class="data" id="my_table" border="1" cellpadding="1" cellspacing="0" style="border: 1px solid #111111;">
        <tr style="display:none ;">
            <td><b>Subdivision Ledger :</b></td><td><b><?php echo get_field_value("full_name","customer","cust_id",$_REQUEST['cust_id']); ?></b></td>
            <td><b> Generated On :</b></td><td><b><?php echo getTime(); echo "("; $username_1=get_field_value("full_name","user","userid",$_SESSION['userId']); echo $username_1; echo ")";?></b></td>

        </tr>    
        <tr style="display:none ;">
        <td><b>Period Start :</b></td><td><b><?php if($_REQUEST['from_date']!=""){ echo date("d-m-Y",strtotime($_REQUEST['from_date']));  }?></b></td>
            <td><b>Period End :</b></td><td><b><?php if($_REQUEST['to_date']!=""){ echo date("d-m-Y",strtotime($_REQUEST['to_date'])); } ?></b></td>
            
        </tr>   
		
            <tr align="center">
				<th class="data" width="30px">S.No.</th>
				<th class="data" width="70px">Date</th>
                <th class="data" nowrap>Invoice No.</th>
				<th class="data">Project</th>
                <th class="data">Subdivision</th>
				<th class="data">Description</th>
                <th class="data" nowrap>GST Due</th>
                <th class="data" nowrap>Invoice Due</th>
				<th class="data">Debit</th>
				<th class="data">Credit</th>
				<th class="data">Balance</th>
				<th class="data noExl" id="header1">File</th>
			</tr>
			<?php
            $icgst=1;
            $icinvoice=1;

			if($select_total > 0)
			{
				$i = 1;
				
				while($select_data = mysql_fetch_array($select_result))
				{
                    //print_r($select_data);
					if($i > 1)
					{
						$select_query4 = "select debit,credit from payment_plan where cust_id = '".$_REQUEST['cust_id']."' and id = '".$temp_payment_id."' LIMIT 0,1  ";
						$select_result4 = mysql_query($select_query4) or die('error in query select cash query '.mysql_error().$select_query4);
						$select_data4 = mysql_fetch_array($select_result4);
						
						if($select_data4['debit'] > 0 && $select_data4['description'] != "Opening Balance" )
						{
							$bal=(float)$bal+(float)$select_data4['debit'];
							
						}
						if($select_data4['credit'] > 0 && $select_data4['description'] != "Opening Balance" )
						{
							$bal=(float)$bal-(float)$select_data4['credit'];
							
						}
						
					}
					$temp_payment_id = $select_data['payment_id'];
					
                    if($i==1)
                    {
                        //$bal_1=$select_data3_1['total_credit']-$select_data3_1['total_debit'];
                         if($search_start=="1")
                         {
                             //$from_date,$to_date
                             ?>
                    <tr class="data" align="center">
                        <td class="data" width="30px"><?php //echo $i; ?></td>
                        <td class="data"><b> <?php echo date("d-m-Y",$to_date); ?></b></td>
                        <td class="data"></td>
                        <td class="data"></td>
                        <td class="data"></td>
                        <td class="data"><b><?php echo "Closing Balance"; ?></b></td>
                        <td class="data"></td>
                        <td class="data"></td>
                        <td class="data"><b><?php echo number_format($select_data3_1['total_debit'],2,'.',''); ?> </b></td>
                        <td class="data"><b><?php echo number_format($select_data3_1['total_credit'],2,'.',''); ?></b></td>
                        <td class="data" <?php if($bal_1<0 ) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?> ><b><?php echo currency_symbol().number_format($bal_1,2,'.',''); ?></b></td>
                        <td class="data noExl" nowrap="nowrap"></td>                        
                    </tr>
                             <?php
                         }
                         else{
                             ?>
                             
                                            <tr class="data" align="center">
                        <td class="data" width="30px"><?php //echo $i; ?></td>
                        <td class="data"><b> <?php echo date("d-m-Y",$select_data['payment_date']); ?></b></td>
                        <td class="data"></td>
                        <td class="data"></td>
                        <td class="data"></td>
                        <td class="data"><b><?php echo "Closing Balance"; ?></b></td>
                        <td class="data"></td>
                        <td class="data"></td>
                        <td class="data"><b><?php echo number_format($select_data3['total_debit'],2,'.',''); ?> </b></td>
                        <td class="data"><b><?php echo number_format($select_data3['total_credit'],2,'.',''); ?></b></td>
                        <td class="data" <?php if($bal<0 ) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?> ><b><?php echo currency_symbol().number_format($bal,2,'.',''); ?></b></td>
                        <td class="data noExl" nowrap="nowrap"></td>                        
                    </tr>
                             <?php
    

                         }
?>
                         

                        <?php
    
    
                    }
                     ?>
					<tr class="data">
						<td class="data" width="30px"  align="center"><?php echo $i; ?></td>
						<td class="data"  align="center"><?php echo date("d-m-Y",$select_data['payment_date']); ?></td>
						<td class="data"  align="center"><?php echo $select_data['invoice_id']; ?></td>                    
						<td class="data"><?php echo get_field_value("name","project","id",$select_data['on_project']); ?></td>
                        <td class="data"><?php echo get_field_value("name","subdivision","id",$select_data['subdivision']); ?></td>
						<td class="data"><?php echo $select_data['description']; ?></td>
                        <td class="data" align="center">
                        <?php
                        
                        if($select_data['debit'] > 0)
                        {
                            $pay_date = $select_data['payment_date'];
                            $cutoff_date = mktime(0,0,0,9,1,2023); // by amit
                            //echo $cutoff_date;
                            //$tz=date_timezone_get(date_create());
                            //echo timezone_name_get($tz);
                            //print_r($select_data);
                            if($pay_date > $cutoff_date){
                        if($select_data['gst_amount']!=0)
                        { ?>
                            
                            <?php
                            //echo $select_data['invoice_id']."<br>".$select_data['supplier_invoice_number']."<br>".$select_data['payment_id']."<br>";
                                $gst_due_query1 = "select SUM(amount) as amount,SUM(received_amount) as received_amount  from gst_due_info where payment_plan_id = '".$select_data['payment_id']."' and invoice_id = '".$select_data['supplier_invoice_number']."' and received_amount > 0";
                                $gst_due_result2 = mysql_query($gst_due_query1) or die("error in date list query ".mysql_error());
                                $total_gst2 = mysql_num_rows($gst_due_result2);
                                $gst_due_query1_yescheck = "select * from gst_due_info where payment_plan_id = '".$select_data['payment_id']."' and invoice_id = '".$select_data['invoice_id']."' and received_amount > 0";
                                $gst_due_result2_yescheck = mysql_query($gst_due_query1_yescheck) or die("error in date list query ".mysql_error());
                                $total_gst2_yescheck = mysql_num_rows($gst_due_result2_yescheck);
                                
                                $find_gst = mysql_fetch_array($gst_due_result2);
                                //print_r($find_gst);
                                if($total_gst2_yescheck > 0)
                                    {
                                        //echo "part";
                                        $due_gst_final = $find_gst['amount']-$find_gst['received_amount'];
                                    }
                                    else
                                    {
                                        //echo "full";
                                        $due_gst_final=$select_data['gst_amount'];                                        
                                    }
                                        $opening_gst_tot_due=$opening_gst_tot_due - $due_gst_final;
                                       
                                  /*  if($select_data['gst_flag']=="1")*/
                                  if($total_gst2_yescheck > 0)
                                {
                                    while( $find_gst_date = mysql_fetch_array($gst_due_result2_yescheck))
                                    {
                                        if($new_date_gst==""){
                                            $new_date_gst = date("d-m-y",$find_gst_date['due_date']);
                                        }else{
                                            $new_date_gst = $new_date_gst.",".date("d-m-y",$find_gst_date['due_date']);
                                        }
                                    }
                                   
                                    ?>
                                   <a href="invoice-ledger.php?invoice_id=<?php echo $select_data['invoice_id']; ?>&payment_id=<?php echo $select_data['payment_id']; ?>" title="View Ledger"  >  
                                   <img src="mos-css/img/active.png" title="<?php 
                                   $d_date= get_field_value("due_date","gst_due_info","id",$select_data['gst_id']); 
                                    echo "Date : &nbsp;".$new_date_gst;
                                  echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                                  $d_amount= $find_gst['received_amount']; 
                                  echo "GST Amount : &nbsp;".$d_amount;
                                    ?>" ></a>
                                  <?php 

                                }
                                if($due_gst_final>="1" )
                                {   
                                  ?>
                                <input type="hidden" name="combine_gst_check_flag[]" id="combine_gst_check_flag<?php echo $icgst; ?>" value="0" >
                                <input type='checkbox' id="<?php echo "combine_gst_check$icgst"; ?>" value="<?php echo $icgst; ?>" onClick="return combine_gst_checke_allow(<?php echo $icgst; ?>);" name='combine_gst_check[]'/>
                                <input type="hidden" name="combine_gst_amount[]" id="combine_gst_amount<?php echo $icgst; ?>" value="<?php echo $due_gst_final; ?>" >
                                <input type="hidden" name="combine_gst_invoicen_id[]" id="combine_gst_invoicen_id<?php echo $icgst; ?>" value="<?php echo $select_data['invoice_id']; ?>" >
                                <input type="hidden" name="combine_gst_type[]" id="combine_gst_type<?php echo $icgst; ?>" value="<?php echo "gst"; ?>" >
                                <input type="hidden" name="combine_gst_paymentplan_id[]" id="combine_gst_paymentplan_id<?php echo $icgst; ?>" value="<?php echo $select_data['payment_id']; ?>" >
                                </br>
                                    <a href="javascript:void(0);" <?php if($due_gst_final>="1" ) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?>  title="GST DUE" onClick="return gst_due_function('<?php echo $select_data['payment_id']; ?>','<?php echo $select_data['trans_id']; ?>','<?php echo $select_data['invoice_id']; ?>','<?php  echo $due_gst_final; ?>');" >
                          <?php   echo number_format($due_gst_final,2,'.','');    ?>  
                            </a> 
                                   <?php 
                                   $icgst++;
                                }
                        }
                    }
                    }
                            ?>   
                        </td>
                        <td class="data"  align="center">
                        
                        <?php
                        /*   invoice DUE          */
                        
                        if($select_data['debit'] > 0)
                        {
                            $pay_date = $select_data['payment_date'];
                            $cutoff_date = mktime(0,0,0,9,1,2023);//by amit
                            //echo $select_data['invoice_pay_amount'];
                            if($pay_date > $cutoff_date){
                            if($select_data['invoice_pay_amount']!=0)
                            { ?>
                                <?php
                                 //echo $select_data['invoice_id']."<br>".$select_data['invoice_pay_amount']."<br>".$select_data['payment_id']."<br>";
                                    $invoice_due_query1 = "select SUM(amount) as amount,SUM(received_amount) as received_amount  from invoice_due_info where payment_plan_id = '".$select_data['payment_id']."' and invoice_id = '".$select_data['supplier_invoice_number']."' and received_amount > 0 ";
                                    $invoice_due_result2 = mysql_query($invoice_due_query1) or die("error in date list query ".mysql_error());
                                    $total_invoice2 = mysql_num_rows($invoice_due_result2);
                                    $invoice_due_query1_yescheck = "select * from invoice_due_info where payment_plan_id = '".$select_data['payment_id']."' and invoice_id = '".$select_data['supplier_invoice_number']."' and received_amount > 0 ";
                                    $invoice_due_result2_yescheck = mysql_query($invoice_due_query1_yescheck) or die("error in date list query ".mysql_error());
                                    $total_invoice2_yescheck = mysql_num_rows($invoice_due_result2_yescheck);

                                    //print_r(mysql_fetch_array($invoice_due_result2));
                                    //echo "brk";
                                    //print_r(mysql_fetch_array($invoice_due_result2_yescheck));
                                    
                                    //echo $total_invoice2_yescheck;
                                    /*if($total_invoice2 > 0)*/
                                    $find_invoice = mysql_fetch_array($invoice_due_result2);
                                    if($total_invoice2_yescheck > 0)
                                        {
                                            //echo 'test';                                            
                                            //print_r($find_invoice);
                                            $due_invoice_final = $find_invoice['amount']-$find_invoice['received_amount'];
                                        }
                                        else
                                        {
                                            //echo 'amt';
                                            $due_invoice_final=$select_data['invoice_pay_amount'];                                            
                                        }
                                         $opening_invoice_tot_due=$opening_invoice_tot_due - $due_invoice_final;
                           
                                   /* if($select_data['invoice_flag']=="1")*/
                                    if($total_invoice2_yescheck > 0)
                                    {
                                        while( $find_invoice_date = mysql_fetch_array($invoice_due_result2_yescheck))
                                        {
                                            if($new_date_invoice==""){
                                                $new_date_invoice = date("d-m-y",$find_invoice_date['due_date']);
                                            }else{
                                                $new_date_invoice = $new_date_invoice.",".date("d-m-y",$find_invoice_date['due_date']);
                                            }
                                        }
                                        ?>
                                       <a href="invoice-ledger.php?invoice_id=<?php echo $select_data['invoice_id']; ?>&payment_id=<?php echo $select_data['payment_id']; ?>" title="View Ledger"  >  <img src="mos-css/img/active.png" title="<?php 
                                        echo "Date : &nbsp;".$new_date_invoice;
                                      echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                                      $d_amount= $find_invoice['received_amount']; 
                                      echo "Invoice Amount : &nbsp;".$d_amount;
                                        ?>" ></a>
                                      <?php 
    
                                    }
                                   if($due_invoice_final>="1" )
                                  {   
                                ?>
                                <input type="hidden" name="combine_invoice_check_flag[]" id="combine_invoice_check_flag<?php echo $icinvoice; ?>" value="0" >
                                <input type='checkbox' id="<?php echo "combine_invoice_check$icinvoice"; ?>" value="<?php echo $icinvoice; ?>" onClick="return combine_invoice_checke_allow(<?php echo $icinvoice; ?>);" name='combine_invoice_check[]'/>
                                <input type="hidden" name="combine_invoice_amount[]" id="combine_invoice_amount<?php echo $icinvoice; ?>" value="<?php echo $due_invoice_final; ?>" >
                                <input type="hidden" name="combine_invoice_invoicen_id[]" id="combine_invoice_invoicen_id<?php echo $icinvoice; ?>" value="<?php echo $select_data['invoice_id']; ?>" >
                                <input type="hidden" name="combine_invoice_type[]" id="combine_invoice_type<?php echo $icinvoice; ?>" value="<?php echo "invoice"; ?>" >
                                <input type="hidden" name="combine_invoice_paymentplan_id[]" id="combine_invoice_paymentplan_id<?php echo $icinvoice; ?>" value="<?php echo $select_data['payment_id']; ?>" >
                                  </br>         <a href="javascript:void(0);" <?php if($due_invoice_final>="1") { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?>  title="Invoice DUE" onClick="return invoice_due_function('<?php echo $select_data['payment_id']; ?>','<?php echo $select_data['trans_id']; ?>','<?php echo $select_data['invoice_id']; ?>','<?php  echo $due_invoice_final; ?>');" >
                              <?php  echo number_format($due_invoice_final,2,'.','');    ?>  
                                </a> 
                                
                                       <?php $icinvoice++;
                                    }
                            } }
                       
                        }
                        /*         //// INVOICE DUE                  */
                        ?>
                        
                        </td>
						<td class="data" align="center">
						<?php
							if($select_data['debit'] > 0)
							{
								echo number_format($select_data['debit'],2,'.','');
							}
                    	?>
						</td>
						<td class="data" align="center">
						<?php
							if($select_data['credit'] > 0)
							{
								echo number_format($select_data['credit'],2,'.','');
							}
						?>
						</td>
						 <?php 
                         if($search_start=="1"){
                             ?>
                             <td class="data" align="center" <?php if($bal_1<0 ) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?>  >
                             <?php 
                            echo currency_symbol().number_format($bal_1,2,'.','');
                            ?>
                            </td>
                            <?php 
                        }else
                        {
                            ?>
                             <td class="data" align="center" <?php if($bal<0) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?> >
                             <?php 
                            echo currency_symbol().number_format($bal,2,'.','');  
                            ?>
                            </td>
                            <?php  
                        }
                         ?>
                         
                         
                         <?php 
                         
                        $bal_1=(float)$bal_1+(float)$select_data['debit'];
                        $bal_1=(float)$bal_1-(float)$select_data['credit'];
                        $bal_credit =(float)$bal_credit-(float)$select_data['credit'];
                        $bal_debit = (float)$bal_debit-(float)$select_data['debit'];
                        $date_old = $select_data['payment_date'];
                        //$bal_1=$select_data3_1['total_credit']-$select_data3_1['total_debit'];
                        ?>
						<td class="data noExl" nowrap="nowrap">

                        <?php //code by amit
                        if($select_data['trans_type_name']=="combind_payment")
                        { ?>
                        <?php if($select_data['payment_id'] != "" && $select_data['payment_id'] != 0) { ?>
                        &nbsp;<a href="javascript:account_transaction_combind(<?php echo $select_data['trans_id'] ?>,<?php echo $select_data['payment_id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>
                        <?php    
                        }} else {?>
						
						<?php if($select_data['trans_id'] != "" && $select_data['trans_id'] != 0) { ?>
						&nbsp;<a href="javascript:account_transaction(<?php echo $select_data['trans_id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>
						<?php } }?>
						&nbsp;<a href="javascript:void(0);" title="Attach File" onClick="return attach_file_function('<?php echo $select_data['payment_id']; ?>');" ><img src="images/images.jpg" width="20" ></a>
                        <?php
                        if($select_data['trans_type_name']=="make_payment")
                        {  ?>
                        <a href="edit_make_payment.php?trans_id=<?php echo $select_data['trans_id']; ?>&id=<?php echo $select_data['payment_id']; ?>&trsns_pname=<?php echo "supplier-ledger"; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } ?>

                 <?php
                        if($select_data['trans_type_name']=="receive_goods")
                        {  ?>
                        <a href="edit_supplier_ledger.php?cust_id=<?php echo $select_data['cust_id']; ?>&trans_id=<?php echo $select_data['trans_id']; ?>&id=<?php echo $select_data['id']; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } ?>
                        
                         <?php
                        if($select_data['trans_type_name']=="inst_receive_goods")
                        {  ?>
                        <a href="edit-instant-receive-goods.php?trans_id=<?php echo $select_data['trans_id']; ?>&id=<?php echo $select_data['payment_id']; ?>&trsns_pname=<?php echo "supplier-ledger-inst-receive-goods"; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } ?>
                         <?php
                        if($select_data['trans_type_name']=="inst_make_payment")
                        {  ?>
                        <a href="edit-instant-receive-goods.php?trans_id=<?php echo $select_data['trans_id']; ?>&id=<?php echo $select_data['payment_id']; ?>&trsns_pname=<?php echo "supplier-ledger-inst-make-payment"; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } ?>
                 <?php //code by amit 25092023
                        if($select_data['trans_type_name']=="gst_receive_payment")
                        { 
                        if($select_data['invoice_id'] != "" && $select_data['invoice_id'] != 0) { ?>
                        &nbsp;<a href="javascript:account_transaction_invoice_gst(<?php echo $select_data['invoice_id']; ?>,<?php echo $select_data['payment_id']; ?>,'GST')"><img src="mos-css/img/delete.png" title="Delete" ></a>
                <?php  } }?>
                  <?php
                        $total_rows_view=0;
                        $query_view="select *  from attach_file where attach_id = '".$select_data['payment_id']."'";
                        $result_view= mysql_query($query_view) or die('error in query '.mysql_error().$query_view);
                        $total_rows_view = mysql_num_rows($result_view);
                        if($total_rows_view != 0)
                        { ?>
                       <a href="javascript:void(0);" title="View File" onClick="return view_file_function('<?php echo $select_data['payment_id']; ?>');" >View</a> <?php           }

?> 
						</td>
						
					</tr>
				<?php
					$i++;
				}
                ?>
            <input type="hidden" id="count_icgst"  name="count_icgst" value="<?php echo $icgst; ?>" />
            <input type="hidden" id="count_icinvoice"  name="count_icinvoice" value="<?php echo $icinvoice; ?>" />
            <?php
                if($search_start=="1")
                {
                    ?>
                    
                    <tr class="data">
                        <td class="data" width="30px"><?php //echo $i; ?></td>
                        <td class="data"><b> <?php echo date("d-m-Y",$from_date); ?></b></td>
                        <td class="data"></td>
                        <td class="data"></td>
                        <td class="data"><b><?php echo "Opening Balance"; ?></b></td>
                        <td class="data"><b><?php echo number_format($bal_debit,2,'.',''); ?> </b></td>
                        <td class="data"><b><?php echo number_format($bal_credit,2,'.',''); ?></b></td>
                        <td class="data" <?php if($bal_1<0 ) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?> ><b><?php echo currency_symbol().number_format($bal_1,2,'.',''); ?></b></td>
                        <td class="data noExl" nowrap="nowrap"></td>                        
                    </tr>
                    <?php
    

                }
				
			}
			else
			{
				?>
				<tr class="data" >
					<td  width="30px" colspan="9" class="record_not_found" >Record Not Found</td>
				</tr>
				<?php
			}
			?>
			
		</table>
		</div>
				
<?php include_once("main_body_close.php") ?>
<?php include_once ("footer.php"); ?>  

<div id="attach_div" style="position:absolute;top:50%; left:40%; width:500px; height:150px; margin:-100px 0 0 -50px;z-index:100; display:none; background-color:#FFFFFF; border:8px solid #999999;" >
<form name="attach_form" id="attach_form" method="post" action="" onSubmit="return attach_validation();" enctype="multipart/form-data" >
<table cellpadding="0" cellspacing="0" border="1" width="100%" >
<tr>
<td valign="top" align="right" colspan="2">
<img src="images/close.gif" onClick="return close_div();" >
</td>
</tr>
<tr>
<td valign="top" >Attach File</td>
<td><input type="file" name="attach_file" id="attach_file" value="" ></td>
</tr>
<tr>
<td valign="top" >Attach File Name</td>
<td>
<input type="text" id="attach_file_name"  name="attach_file_name" value="" autocomplete="off"/>
</td>
</tr>
<tr><td></td><td>
<input type="submit" class="button" name="file_button" id="file_button" value="Submit" >
</td>
</tr>
</table>
<input type="hidden" id="attach_file_id"  name="attach_file_id" value="" />
</form>
</div>
<div id="view_div" style="position:absolute;top:50%; left:40%; width:500px; min-height:250px; margin:-100px 0 0 -50px;z-index:100; display:none; background-color:#FFFFFF; border:8px solid #999999;" >

</div>
<form name="trans_form" id="trans_form" action="" method="post" >
<input type="hidden" name="trans_id" id="trans_id" value="" >
</form>

<form name="trans_form_combind" id="trans_form_combind" action="" method="post" >        
<input type="hidden" name="trans_id_combind" id="trans_id_combind" value="" >
<input type="hidden" name="payment_id" id="payment_id" value="" >
</form>
        
<form name="trans_form_1" id="trans_form_1" action="" method="post" >
<input type="hidden" name="trans_id_1" id="trans_id_1" value="" >
</form>

        <!---  Due GST Attchment Div   -->
<div id="gst_due_div" style="position:absolute;top:30%; left:40%; width:580px; height:480px; margin:-100px 0 0 -50px;z-index:100; display:none; background-color:#FFFFFF; border:8px solid #999999;" >
<form name="gst_due_form" id="gst_due_form" method="post" action="" onSubmit="return gst_validation();" enctype="multipart/form-data" >
<table cellpadding="0" cellspacing="0" border="1px" width="100%" >
    <tr><td valign="top" align="right"  ><img src="images/close.gif" onClick="return close_gst_div();" ></td></tr>
                <tr>
                    <td valign="top" style="color:#FF0000; font-weight:bold;"  >GST Due Amount
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                       
                    <input type="text" id="gst_due_amount_new_total" style="width:100px;color:red; border:0; " readonly="readonly"  name="gst_due_amount_new_total" value="" /></td>
                </tr>
                <tr>
                    <td valign="top" style="color:#blue; "  >
                    
                    <table width="100%" cellpadding="0" cellspacing="0" >
                        <tr>
                            <td colspan="2" align="left"  style="color:#blue; " ><b>Clear GST Due :</b>&nbsp;&nbsp;<input type="checkbox" id="clear_gst_due" onClick="return clear_gst_desc();" name="clear_gst_due" value="yes"></td>
                           
                        </tr>
                    <tr><td>
                    <table>
                        <tr id="clear_desc_display_gst" style="display:none;">
                            <td width="150px">Description</td>
                            <td width=""><input type='text' style="display:none; width:180px;font-size:12px;" name='clear_gstdue_desc' align='right' id='clear_gstdue_desc' />
                    </tr></table>         
                    </td>
                    <td>
                    <table>
                        <tr id="clear_date_display_gst" style="display:none;">
                        <td width="140px">Payment Date : </td>
                        <td><input type='text' style="" name='clear_pay_payment_date_gst' align='right' id='clear_pay_payment_date_gst'  autocomplete="off" placeholder="Type clear due Payment Date here"/></td>
                        <td><img src="js/images2/cal.gif" onClick="javascript:NewCssCal('clear_pay_payment_date_gst')" style="cursor:pointer"/></td>
                        </tr>
        </table>
        </tr></tr>  
                    </table>
                        
                </td>
                </tr>
                
            
            <tr><td valign="top"  align="left" ><b>Paid gst :</b>&nbsp;&nbsp;&nbsp;<input type="radio" name="gst_cerf" id="gst_cerf" value="1" onClick="return close_gst_div2('1');" >&nbsp;&nbsp;YES&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="gst_cerf" id="gst_cerf"  checked=true value="0" onClick="return close_gst_div2('0');" >&nbsp;&nbsp;NO
            </td>
        </tr>
        <tr>  
            <td valign="top"  align="left" id="gst-due_div2" style=" display:none; width:100%">
                <table width="100%" border="2" style="border:2px;">  
                <tr>
                    <td valign="top"  width="180px">Paid From</td>
                    <td>
                        <!--<input type="text" id="invoice_due_des" style="width:260px;"  name="invoice_due_des" value="" autocomplete="off"/>
        -->
        <input type="text" id="gst_pay_form"  name="gst_pay_form" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span> 
                </td>
                </tr>

                <tr>    <td valign="top"  width="180px" >Date</td>
                    <td><input type="text"  name="gst_due_date" id="gst_due_date" value="<?php //echo $_REQUEST['tds_due_date']; ?>" style="width:250px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('gst_due_date')" style="cursor:pointer"/></td>
                </tr>
                
                <tr>
                    <td valign="top" >Amount Paid</td>
                    <td><input type="text" id="gst_due_amount" style="width:250px;"  name="gst_due_amount" value="" onkeydown="gst_due_calculation()" onkeyup="gst_due_calculation()" onkeypress="gst_due_calculation()" />
                    <span id=""  style="color:red;" >(Due :<input type="text" id="gst_due_amount_new_due" style="width:60px;color:red;border:0;" readonly="readonly"  name="gst_due_amount_new_due" value="" /></span>
                </td>
                </tr>
                <tr>
                    <td valign="top" >GST File Attachment</td>
                    <td><input type="file" name="gst_due_attach_file" style="width:250px;"   id="gst_due_attach_file" value="" ></td>
                </tr>
                <tr>
                    <td valign="top" >Discription</td>
                    <td><input type="text" id="gst_due_des" style="width:250px;"  name="gst_due_des" value="" autocomplete="off"/></td>
                </tr>
            </table>
            </td>
        </tr>    
         
    <input type="hidden" id="clearall_due_flag"  name="clearall_due_flag" value="" />
    
    <input type="hidden" id="gst_due_flag_value"  name="gst_due_flag_value" value="0" />
    <input type="hidden" id="gst_payment_id"  name="gst_payment_id" value="" />
    <input type="hidden" id="gst_attach_file_id"  name="gst_attach_file_id" value="" />
    <input type="hidden" id="invoice_flag"  name="invoice_flag" value="" />
    <input type="hidden" id="gst_trans_id"  name="gst_trans_id" value="" />
    <input type="hidden" id="gst_invoice_id"  name="gst_invoice_id" value="" />
    <input type="hidden" id="gst_amount_pay"  name="gst_amount_pay" value="" />
    <tr>
        <td valign="bottom" align="center" colspan="2"><input type="submit" class="button" name="file_button_gst" id="file_button_gst" value="Submit" ></td></tr>
</table>

</form>
</div>

        <!--      /// Due GST Attachment Div     --->

                <!---  Due INVOICE Attchment Div   -->
 <!-- /// attach_div  ,attach_form ,  attach_form ,  attach_validation , attach_file_id   , invoice_flag  -->    
<div id="invoice_due_div" style="position:absolute;top:30%; left:40%; width:570px; height:550px; margin:-100px 0 0 -50px;z-index:100; display:none; background-color:#FFFFFF; border:8px solid #999999;" >
<form name="invoice_due_form" id="invoice_due_form" method="post" action="" onSubmit="return invoice_validation();" enctype="multipart/form-data" >
  

<table cellpadding="0" cellspacing="0" border="1px" width="100%" >
<tr><td valign="top" align="left" style="color:#FF0000; font-weight:bold;"  >Invoice Due Amount
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                       
                    <input type="text" id="invoice_due_amount_new_total" style="width:100px;color:red; border:0; " readonly="readonly"  name="invoice_due_amount_new_total" value="" /></td>
<td valign="top" align="right"  ><img src="images/close.gif" onClick="return close_invoice_div();" ></td></tr>
                <tr height="20px">
                    <td valign="top" colspan="2" align="left"  ><b>Clear Invoice Due :</b>&nbsp;&nbsp;<input type="checkbox" id="clear_invoice_due" name="clear_invoice_due" onClick="return clear_invoice_desc();" value="yes"></td>
                </tr>
                    
                <tr>
                    <td valign="top" colspan="2"  >
                    <table width="100%" cellpadding="0"  cellspacing="0">
                        <tr><td><table>
                        <tr id="clear_desc_display" style="display:none;">
                            <td width="110px">Description</td>
                            <td><input type='text' style="display:none; " name='clear_invoicedue_desc' align='right' id='clear_invoicedue_desc' width="140px" />
                        </tr></table></td>
                        <td><table>
                        <tr id="clear_date_display" style="display:none;">
                        <td width="100px">Payment Date</td>
                        <td align="left" ><input type='text' style="display:none; " name='clear_pay_payment_date' align='right' id='clear_pay_payment_date' width="140px" autocomplete="off" /></td>
                        <td><img src="js/images2/cal.gif" onClick="javascript:NewCssCal('clear_pay_payment_date')" style="cursor:pointer"/></td>
                        </tr>
        </table></td></tr>
                    </table>
                    
                    
                </td>
                    
                </tr>
                
<tr><td valign="top" width="300px" align="left" colspan="2" >&nbsp;&nbsp;<b>Received Due Invoice Amount</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="invoice_cerf" id="invoice_cerf" value="1" onClick="return close_invoice_div2('1');" >&nbsp;&nbsp;YES&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="invoice_cerf" id="invoice_cerf" checked=true value="0" onClick="return close_invoice_div2('0');" >&nbsp;&nbsp;NO
            </td>
        </tr>
        <tr>  
            <td valign="top" colspan="2" align="left" id="invoice-due_div2" style=" display:none; width:100%">
                <table width="100%" border="2" style="border:2px;">  
                <tr>    <td valign="top"  width="150px" >Payment Date</td>
                    <td><input type="text"  name="pay_payment_date" id="pay_payment_date" value="<?php //echo $_REQUEST['tds_due_date']; ?>" style="width:250px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('pay_payment_date')" style="cursor:pointer"/></td>
                </tr>
                <tr>
                    <td valign="top" >Amount Paid</td>
                    <td><input type="text" id="pay_amount" style="width:250px;"  name="pay_amount" value="" onkeydown="invoice_due_calculation()" onkeyup="invoice_due_calculation()" onkeypress="invoice_due_calculation()" />
                    <span id=""  style="color:red;" >(Due :<input type="text" id="invoice_due_amount_new_due" style="width:60px;color:red;border:0;" readonly="readonly"  name="invoice_due_amount_new_due" value="" /></span>
                </td>
                </tr>
                
                <tr>
                    <td valign="top" >Payment File Attachment</td>
                    <td><input type="file" name="invoice_due_attach_file" width="250px" id="invoice_due_attach_file" value="" ></td>
                </tr>
                <tr>
                    <td valign="top" >Paid From</td>
                    <td>
                        <!--<input type="text" id="invoice_due_des" style="width:260px;"  name="invoice_due_des" value="" autocomplete="off"/>
        -->
        <input type="text" id="pay_form"  name="pay_form" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span> 
                </td>
                </tr>
                <tr>
                    <td valign="top" >Description</td>
                    <td><input type="text" id="invoice_due_des" style="width:250px;"  name="invoice_due_des" value="" autocomplete="off"/></td>
                </tr>
                <tr>
                    <td valign="top" >Payment Method</td>
                    <td>
                    <input type="radio" id="pay_method" name="pay_method"  onchange=" return checkno_create();" value="check">
            <label for="male">Cheque</label>&nbsp;&nbsp;
            <input type="radio" id="pay_method" name="pay_method" checked=true onchange="return checkno_create1();" value="bank">
            <label for="female">Bank</label>&nbsp;&nbsp;
            <input type="radio" id="pay_method" name="pay_method"  onchange="return checkno_create1();" value="cash">
            <label for="other">Cash</label>
            

                    </td>
                </tr>
                <tr>
                    <td valign="top" colspan="2">
                    <div id="pay_check" align="left"  style="display:none; " >
                    <table>
                        <tr>
                            <td width="180px">Cheque No.</td>
                            <td><input type="text" width="180px" name="pay_checkno" id="pay_checkno" value="" /><br></td>
                        </tr>
                    </table>
                     
                    </div>
                    </td>
                </tr>
                
            </table>
            </td>
        </tr>    
      
        <input type="hidden" id="clearall_due_flag_invoice"  name="clearall_due_flag_invoice" value="" />
    <input type="hidden" id="invoice_due_flag_value"  name="invoice_due_flag_value" value="0" />
    <input type="hidden" id="invoice_payment_id"  name="invoice_payment_id" value="" />
    <input type="hidden" id="invoice_attach_file_id"  name="invoice_attach_file_id" value="" />
    <input type="hidden" id="invoice_flag"  name="invoice_invoice_flag" value="" />
    <input type="hidden" id="invoice_trans_id"  name="invoice_trans_id" value="" />
    <input type="hidden" id="invoice_invoice_id"  name="invoice_invoice_id" value="" />
    <input type="hidden" id="invoice_amount_pay"  name="invoice_amount_pay" value="" />
    <tr>
        <td valign="bottom" align="center" colspan="2"><input type="submit" class="button" name="file_button_invoice" id="file_button_invoice" value="Submit" ></td></tr>
</table>

</form>

</div>


<form name="edit_invoice_form" id="edit_invoice_form" action="edit_invoice_ledger_invoice.php" method="post" >
<!--edit_tds_form ,  info_tb_id, trsns_pname ,invoice_no , payment_id  -->
        
        <input type="hidden" name="info_tb_id_invoice" id="info_tb_id_invoice" value="" >
        <input type="hidden" name="trsns_pname_invoice" id="trsns_pname_invoice" value="" >
        <input type="hidden" name="invoice_no_invoice" id="invoice_no_invoice" value="" >
        <input type="hidden" name="payment_id_invoice" id="payment_id_invoice" value="" >
        </form>

        <form name="edit_gst_form" id="edit_gst_form" action="edit_invoice_ledger_gst.php" method="post" >
<!--edit_tds_form ,  info_tb_id, trsns_pname ,invoice_no , payment_id  -->
        
        <input type="hidden" name="info_tb_id_gst" id="info_tb_id_gst" value="" >
        <input type="hidden" name="trsns_pname_gst" id="trsns_pname_gst" value="" >
        <input type="hidden" name="invoice_no_gst" id="invoice_no_gst" value="" >
        <input type="hidden" name="payment_id_gst" id="payment_id_gst" value="" >
        </form>
        
<form name="edit_tds_form" id="edit_tds_form" action="edit_invoice_ledger_tds.php" method="post" >
<!--edit_tds_form ,  info_tb_id, trsns_pname ,invoice_no , payment_id  -->
        
        <input type="hidden" name="info_tb_id_tds" id="info_tb_id_tds" value="" >
        <input type="hidden" name="trsns_pname_tds" id="trsns_pname_tds" value="" >
        <input type="hidden" name="invoice_no_tds" id="invoice_no_tds" value="" >
        <input type="hidden" name="payment_id_tds" id="payment_id_tds" value="" >
        </form>

        <!-- Due Invoice Attachment Div -->

</body>
</html>
<script src="https://cdn.jsdelivr.net/gh/webcodesample/global-js@main/sup-ledg-1.js"></script>
<script src="https://cdn.jsdelivr.net/gh/webcodesample/global-js@main/supledg.js"></script>