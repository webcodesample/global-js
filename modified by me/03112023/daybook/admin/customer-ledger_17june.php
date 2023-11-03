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
 //invoice_id,invoice_form          
if(isset($_POST['invoice_id']) && $_POST['invoice_id'] != "")
{
     //trans_t_name , invoice_id, del_payment_id, del_type  ,  instmulti_receive_gst_tds
    $trans_t_name = $_POST['trans_t_name'];
    $invoice_id = $_POST['invoice_id'];
    if($trans_t_name=="instmulti_receive_gst_tds")
    {
        $payment_id_tb = $_POST['del_payment_id'];
        $payment_type = $_POST['del_type'];
      // $id_dueinfo_tb = $_POST['id_dueinfo_tb'];
       
        if($payment_type=="Invoice")
    {
        $due_tb_name="invoice_due_info";
                
        $type_flag="invoice_flag";
        $clear_type_flag="clear_invoice_flag";
        $type_pay_id="invoice_pay_id";
        $type_due_pay_id="invoice_due_pay_id";
        $file_folder="invoice_files";
        
    }
    if($payment_type=="GST")
    {
        $due_tb_name="gst_due_info";
                
        $type_flag="gst_flag";
        $clear_type_flag="clear_gst_flag";
        $type_pay_id="gst_id";
        $type_due_pay_id="gst_due_id";
        $file_folder="gst_files";
    }
    if($payment_type=="TDS")
    {
        $due_tb_name="tds_due_info";
                
        $type_flag="tds_flag";
        $clear_type_flag="clear_tds_flag";
        $type_pay_id="tds_id";
        $type_due_pay_id="tds_due_id";
        $file_folder="tds_files";
        
    }

        $query_del="select *  from payment_plan where id = '".$payment_id_tb."' ";
        $result_del= mysql_query($query_del) or die('error in query '.mysql_error().$query_del);
        $data_del = mysql_fetch_array($result_del);
        $old_payment_id_cust = $data_del['id'];
        $old_payment_id_bank = $data_del['link_id'];
        $old_main_id_paytb = $data_del['link3_id'];
        $old_invoice_id_paytb = $data_del['invoice_id'];
        
    $del_query = "delete from payment_plan where id = '".$old_payment_id_cust."' ";
    $del_result = mysql_query($del_query) or die("error in delete payment customer  query ".mysql_error());
       
    $del_query = "delete from payment_plan where id = '".$old_payment_id_bank."' ";
    $del_result = mysql_query($del_query) or die("error in delete payment bank query ".mysql_error());
      
    $query_del_file="select *  from ".$due_tb_name." where pp_linkid_1 = ".$old_payment_id_cust." and  pp_linkid_2=".$old_payment_id_bank." and invoice_id=".$old_invoice_id_paytb." ";
    $result_del_file= mysql_query($query_del_file) or die('error in query '.mysql_error().$query_del_file);
    $data_del_file = mysql_fetch_array($result_del_file);

    
        if($data_del_file['cert_file_name']!="")
        {
            unlink($file_folder."/".$data_del_file['cert_file_name']);
        }

    $del_query = "delete from ".$due_tb_name." where pp_linkid_1 = ".$old_payment_id_cust." and  pp_linkid_2=".$old_payment_id_bank." and invoice_id=".$old_invoice_id_paytb." ";
    $del_result = mysql_query($del_query) or die("error in delete payment bank query ".mysql_error());
     
     //exit;
     $query_del="select *  from ".$due_tb_name." where invoice_id = '".$old_invoice_id_paytb."'";
     $result_del= mysql_query($query_del) or die('error in query '.mysql_error().$query_del);
     $flag_val=0;
     
     $pay_id="";
     $due_pay_id="";
     while($data_del = mysql_fetch_array($result_del))
	{
        $flag_val=1;
        if($pay_id=="")
        {
            $pay_id=$data_del['pp_linkid_1'];
        }
        else{
            $pay_id=$invoice_pay_id_gstpay.','.$data_del['pp_linkid_1'];
        }

        if($due_pay_id=="")
        {
                $due_pay_id = $data_del['id'];
        }else{
            $due_pay_id=$due_pay_id.','.$data_del['id'];
        }

     }
    /*
    $flag_val=0;
     $pay_id="";
     $due_pay_id="";
     
    */
    if($flag_val==0)
    {
        $clear_val_change=",".$clear_type_flag.'= 0';
        $del_query = "delete from clear_due_amount where invoice_id = '".$old_invoice_id_paytb."' and payment_plan_id='".$old_main_id_paytb."' and type='".$payment_type."' ";
    $del_result = mysql_query($del_query) or die("error in delete payment bank query ".mysql_error());

    }
    else{
        $clear_val_change="";
    }

$query5_pay="update payment_plan set ".$type_flag." = '".$flag_val."',".$type_pay_id."='".$pay_id."',".$type_due_pay_id."='".$due_pay_id."' ".$clear_val_change." where invoice_id = '".$old_invoice_id_paytb."'";
$result5_pay= mysql_query($query5_pay) or die('error in query '.mysql_error().$query5_pay);
echo $query5_pay;
    
    
    }
    else if($trans_t_name=="instmulti_receive_payment")
    {
        $del_query = "delete from payment_plan where invoice_id = '".$invoice_id."' and trans_type_name='instmulti_receive_payment'";
    $del_result = mysql_query($del_query) or die("error in invoice delete query ".mysql_error());
        
        
    $query5_1="update payment_plan set link2_id = '0',link3_id = '0' ,payment_flag = '0' where invoice_id = '".$invoice_id."'";
    $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);
    }
    else
    {
        $del_query = "delete from payment_plan where invoice_id = '".$invoice_id."'";
        $del_result = mysql_query($del_query) or die("error in invoice delete query ".mysql_error());
        
        $del_query2 = "delete from goods_details where invoice_id = '".$invoice_id."'";
        $del_result2 = mysql_query($del_query2) or die("error in invoice delete query ".mysql_error());
        
        $query_del="select *  from attach_file where attach_id = '".$invoice_id."'";
        $result_del= mysql_query($query_del) or die('error in query '.mysql_error().$query_del);
    
        while($data_del = mysql_fetch_array($result_del))
        {
            $fnm = $data_del['file_name'];
        
        $del_query_1 = "delete from attach_file where id = '".$data_del['id']."'";
        $del_result_1 = mysql_query($del_query_1) or die("error in Transaction delete query ".mysql_error());
        
        unlink("transaction_files/".$fnm);
            
        }
       

        if($trans_t_name=="instmulti_sale_goods")
        {
            $query_del_gst="select *  from gst_due_info where invoice_id = '".$invoice_id."' and cert_file_name!='' ";
            $result_del_gst= mysql_query($query_del_gst) or die('error in query '.mysql_error().$query_del_gst);
            while($data_del_gst = mysql_fetch_array($result_del_gst))
            {
                $fnm = $data_del_gst['cert_file_name'];
                $del_query_gst = "delete from gst_due_info where id = '".$data_del_gst['id']."'";
                $del_result_gst = mysql_query($del_query_gst) or die("error in gst payment delete query ".mysql_error());
                unlink("gst_files/".$fnm);
               // unlink($file_folder."/".$data_del_file['cert_file_name']);
            }

            $query_del_tds="select *  from tds_due_info where invoice_id = '".$invoice_id."' and cert_file_name!='' ";
            $result_del_tds= mysql_query($query_del_tds) or die('error in query '.mysql_error().$query_del_tds);
            while($data_del_tds = mysql_fetch_array($result_del_tds))
            {
                $fnm = $data_del_tds['cert_file_name'];
                $del_query_tds = "delete from tds_due_info where id = '".$data_del_tds['id']."'";
                $del_result_tds = mysql_query($del_query_tds) or die("error in TDS payment delete query ".mysql_error());
                unlink("tds_files/".$fnm);
            }

            $query_del_invoice="select *  from invoice_due_info where invoice_id = '".$invoice_id."' and cert_file_name!='' ";
            $result_del_invoice= mysql_query($query_del_invoice) or die('error in query '.mysql_error().$query_del_invoice);
            while($data_del_invoice = mysql_fetch_array($result_del_invoice))
            {
                $fnm = $data_del_invoice['cert_file_name'];
                $del_query_invoice = "delete from invoice_due_info where id = '".$data_del_invoice['id']."'";
                $del_result_invoice = mysql_query($del_query_invoice) or die("error in invoice payment delete query ".mysql_error());
                unlink("invoice_files/".$fnm);
            }
    
            
            $del_query_gst = "delete from gst_due_info where invoice_id = '".$invoice_id."'";
            $del_result_gst = mysql_query($del_query_gst) or die("error in invoice payment delete query ".mysql_error());
            $del_query_invoice = "delete from invoice_due_info where invoice_id = '".$invoice_id."'";
            $del_result_invoice = mysql_query($del_query_invoice) or die("error in invoice payment delete query ".mysql_error());
            $del_query_tds = "delete from tds_due_info where invoice_id = '".$invoice_id."'";
            $del_result_tds = mysql_query($del_query_tds) or die("error in tds payment delete query ".mysql_error());
            $del_query_clear_due = "delete from clear_due_amount where invoice_id = '".$invoice_id."'";
            $del_result_clear_due = mysql_query($del_query_clear_due) or die("error in clear due payment delete query ".mysql_error());
       
        }
    }           
    $msg = "Customer Invoice Deleted Successfully.";
}


 if(isset($_POST['trans_id']) && $_POST['trans_id'] != "")
{

    $trans_id = $_POST['trans_id'];
    $del_query = "delete from payment_plan where trans_id = '".$trans_id."'";
    $del_result = mysql_query($del_query) or die("error in Transaction delete query ".mysql_error());
    $msg = "Transaction Deleted Successfully.";
}

if(isset($_POST['trans_id_1']) && $_POST['trans_id_1'] != "")
{ //  echo "hello";
//exit;

    $trans_id_1 = $_POST['trans_id_1'];
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

//tds_due_div, tds_due_form , tds_cerf , tds_due_date, tds_due_amount , tds_due_attach_file , tds_due_des
if(mysql_real_escape_string(trim($_REQUEST['file_button'])) == "Submit")
{
    /*echo '<pre>';
    print_r($_REQUEST);
    exit;*/
    if($_FILES["attach_file"]["name"] != "")
    {
        $attach_file_name=mysql_real_escape_string(trim($_REQUEST['attach_file_name']));
        $attach_file_id=mysql_real_escape_string(trim($_REQUEST['attach_file_id']));
        $temp = explode(".", $_FILES["attach_file"]["name"]);
        $arr_size = count($temp);
        $extension = end($temp);
        
        $invoice_flag=mysql_real_escape_string(trim($_REQUEST['invoice_flag']));
        //invoice_flag,invoice_attach_file_function
        if($invoice_flag=="1")
        {
            $query3="insert into attach_file set attach_id = '".$attach_file_id."', file_name = '".$new_file_name."'";
    $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
    $link_id_4 = mysql_insert_id();
    $new_file_name = $attach_file_name.'_'.$link_id_4.'_'.date("d_M_Y").'.'.$extension;
        move_uploaded_file($_FILES["attach_file"]["tmp_name"],"transaction_files/" . $new_file_name);
         $query5_1="update attach_file set file_name = '".$new_file_name."' where id = '".$link_id_4."'";
    $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);
        }
        else
        {
        $query="select link_id from payment_plan where id = '".$attach_file_id."'";
        $result= mysql_query($query) or die('error in query '.mysql_error().$query);
        $data = mysql_fetch_array($result);
        
        $link_id_2 = $data['link_id'];
    
         $query3="insert into attach_file set attach_id = '".$attach_file_id."', link_id = '".$link_id_2."',file_name = '".$new_file_name."'";
    $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
    $link_id_4 = mysql_insert_id();
    
        //$new_file_name = $attach_file_name.'_'.date("d_M_Y").'.'.$extension;
         $new_file_name = $attach_file_name.'_'.$link_id_4.'_'.date("d_M_Y").'.'.$extension;
        move_uploaded_file($_FILES["attach_file"]["tmp_name"],"transaction_files/" . $new_file_name);
              
    $query4="insert into attach_file set attach_id = '".$link_id_2."', link_id = '".$attach_file_id."',old_id = '".$link_id_4."',file_name = '".$new_file_name."'";
    $result4= mysql_query($query4) or die('error in query '.mysql_error().$query4);
    $link_id_5 = mysql_insert_id();
    
    $query5_1="update attach_file set old_id = '".$link_id_5."',file_name = '".$new_file_name."' where id = '".$link_id_4."'";
    $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);
        }
    }
    
    
}


/*  --------      TDS DUE WORK        -----------------------  */
    
//tds_due_div, tds_due_form , tds_cerf , tds_due_date, tds_due_amount , tds_due_attach_file , tds_due_des , tds-due_flag_value
//due_invoice_id,due_payment_id,due_trans_id ,due_amount_pay
if(mysql_real_escape_string(trim($_REQUEST['file_button1'])) == "Submit")
{
   // tds_due_date
    $tds_cerf_1=mysql_real_escape_string(trim($_REQUEST['tds_cerf']));
    $tds_due_date_1=mysql_real_escape_string(trim($_REQUEST['tds_due_date']));
    $tds_due_amount_1=mysql_real_escape_string(trim($_REQUEST['tds_due_amount']));
    $tds_due_des_1=mysql_real_escape_string(trim($_REQUEST['tds_due_des']));
    $tds_due_flag_value_1=mysql_real_escape_string(trim($_REQUEST['tds_due_flag_value']));
    $due_trans_id_1=mysql_real_escape_string(trim($_REQUEST['due_trans_id']));
    $due_payment_id_1=mysql_real_escape_string(trim($_REQUEST['due_payment_id']));
    $due_invoice_id_1=mysql_real_escape_string(trim($_REQUEST['due_invoice_id']));
    $due_amount_pay_1=mysql_real_escape_string(trim($_REQUEST['due_amount_pay']));
    $tds_due_date_1_n=strtotime($tds_due_date_1);
    
    $clearall_due_flag=mysql_real_escape_string(trim($_REQUEST['clearall_due_flag_tds']));
    $tds_due_amount_new_total=mysql_real_escape_string(trim($_REQUEST['tds_due_amount_new_total']));
    $clear_tds_due=mysql_real_escape_string(trim($_REQUEST['clear_tds_due']));
    $clear_tdsdue_desc=mysql_real_escape_string(trim($_REQUEST['clear_tdsdue_desc']));
    
    //description='".$clear_tdsdue_desc."'
    $trans_type_pay_tds = 52;
    $trans_type_name_pay_tds= "tds_receive_payment" ;
       
            /*echo '<pre>';
            print_r($_REQUEST);
            exit;*/
         /*   $quuerrr="select id from tds_due_info where payment_plan_id='".$due_payment_id_1."' and trans_id = '".$due_trans_id_1."' and invoice_id = '".$due_invoice_id_1."' ";
    
            $sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
            $no=mysql_num_rows($sql);
            if($no > 0)
            {
                
                    $error_msg = "TDS Due already exist for this invoice id";
                
            }
            else
            {
                */
                
            $sql_pay 	= "select * from `payment_plan` where id ='".$due_payment_id_1."'";
            $query_pay_1 	= mysql_query($sql_pay);
            $row_pay = mysql_fetch_array($query_pay_1);
            $trans_id=$row_pay['trans_id'];
           // $pay_bank_id="";
            //$due_amount_pay_1 = due_amount_pay_1;
            $cust_id = $row_pay['cust_id'];
            $invoice_idnew = $row_pay['invoice_id'];
            //$tds_due_des_1 = "GST Received for invoice : ".$invoice_idnew."";
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
            //$link_id_2 = $row_pay[''];
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
            $tds_due_des_1_extra = "TDS Received for invoice : ".$invoice_idnew."";    
            $pay_from_arr = explode(" -",$_REQUEST['tds_pay_form']);
            $pay_bank_id = get_field_value("id","bank","bank_account_name",$pay_from_arr[0]);
    

            if($clearall_due_flag=="2")
            {
                $query_tds1="update payment_plan set tds_flag = '1',clear_tds_flag='1' where invoice_id = '".$invoice_idnew."'";
                $result_tds1= mysql_query($query_tds1) or die('error in query '.mysql_error().$query_tds1);
                $query_clear_tds="insert into `clear_due_amount`  set invoice_id  = '".$due_invoice_id_1."',description='".$clear_tdsdue_desc."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',due_amount = '".$tds_due_amount_new_total."', cust_id = '".$cust_id."', user_id = '".$_SESSION['userId']."', type = 'TDS',create_time = '".getTime()."'";
                $result_clear_tds= mysql_query($query_clear_tds) or die('error in query '.mysql_error().$query_clear_tds);
                
    
            }
            else{

        if($tds_due_flag_value_1=="1")
        {                  
     //       pp_linkid_1 = '".$pp_linkid_1_invoice."',pp_linkid_2 = '".$pp_linkid_2_invoice."',
    
            $query3="insert into tds_due_info set invoice_id = '".$due_invoice_id_1."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['tds_due_date'])."',description = '(".$tds_due_des_1_extra.") ".$tds_due_des_1."',amount = '".$due_amount_pay_1."', received_amount = '".$tds_due_amount_1."',create_date = '".getTime()."'";
            $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
            $link_id_4 = mysql_insert_id();
            
            if($_FILES["tds_due_attach_file"]["name"] != "")
             {
                $attach_file_name="tds_certificate";
                $temp = explode(".", $_FILES["tds_due_attach_file"]["name"]);
                 $arr_size = count($temp);
                $extension = end($temp);
                $new_file_name = $attach_file_name.'_'.$link_id_4.'_'.date("d_M_Y").'.'.$extension;
            move_uploaded_file($_FILES["tds_due_attach_file"]["tmp_name"],"tds_files/" . $new_file_name);
            $query1_1="update tds_due_info set cert_file_name = '".$new_file_name."' where id = '".$link_id_4."'";
            $result1_1= mysql_query($query1_1) or die('error in query '.mysql_error().$query1_1);

            
             }
            
            // $query1_2="update payment_plan set tds_flag = '1',tds_id='".$link_id_4."' where id = '".$due_payment_id_1."'";
           // $result1_2= mysql_query($query1_2) or die('error in query '.mysql_error().$query1_2);

           $query_pay_tds ="insert into payment_plan set trans_id = '".$trans_id."', bank_id = '".$pay_bank_id."', credit = '".$tds_due_amount_1."',  description = '(".$tds_due_des_1_extra.") ".$tds_due_des_1."', on_customer = '".$cust_id."', invoice_id = '".$invoice_idnew."',payment_flag = '".$payment_flag."', payment_date = '".strtotime($_REQUEST['tds_due_date'])."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."',invoice_issuer_id = '".$invoice_issuer_id."' ,payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."', trans_type = '".$trans_type_pay_tds."', trans_type_name = '".$trans_type_name_pay_tds."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."',create_date = '".getTime()."'";
           $result_pay_tds= mysql_query($query_pay_tds) or die('error in query '.mysql_error().$query_pay_tds);


           $link_id_1_pay_tds = mysql_insert_id();


           $query2_pay_tds="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$invoice_idnew."', debit = '".$tds_due_amount_1."', description = '(".$tds_due_des_1_extra.") ".$tds_due_des_1."', on_project = '".$project_id."', on_bank = '".$pay_bank_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['tds_due_date'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."', payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',link_id = '".$link_id_1_pay_tds."',trans_type = '".$trans_type_pay_tds."', trans_type_name = '".$trans_type_name_pay_tds."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."' ,create_date = '".getTime()."'";
           $result2_pay_tds= mysql_query($query2_pay_tds) or die('error in query '.mysql_error().$query2_pay_tds);

           $link_id_2_pay_tds = mysql_insert_id();  

           $query_tds_update="update tds_due_info set  pp_linkid_1 = '".$link_id_2_pay_tds."',pp_linkid_2 = '".$link_id_1_pay_tds."'  where id = '".$link_id_4."'";
           $result5_tds_update= mysql_query($query_tds_update) or die('error in query '.mysql_error().$query_tds_update);
   
           
                   //$gst_id_gstpay= $row_pay['gst_id'];
                   //$gst_due_id_gstpay =$row_pay['gst_due_id'];
                    //$link_id_4 , link_id_2_pay_gst
                    //,gst_id='".$gst_id_gstpay."',gst_due_id='".$gst_due_id_gstpay."'
                   if($tds_id_gstpay=="")
                   {
                       $tds_id_gstpay=$link_id_2_pay_tds;
                   }
                   else{
                       $tds_id_gstpay=$tds_id_gstpay.','.$link_id_2_pay_tds;
                   }

                   if($tds_due_id_gstpay=="")
                   {
                           $tds_due_id_gstpay = $link_id_4;
                   }else{
                       $tds_due_id_gstpay=$tds_due_id_gstpay.','.$link_id_4;
                   }

           $query5_pay_tds="update payment_plan set link_id = '".$link_id_2_pay_tds."' ,tds_flag = '1',tds_id='".$tds_id_gstpay."',tds_due_id='".$tds_due_id_gstpay."' where id = '".$link_id_1_pay_tds."'";
           $result5_pay_tds= mysql_query($query5_pay_tds) or die('error in query '.mysql_error().$query5_pay_tds);

           $query5_pay_tds="update payment_plan set tds_flag = '1',tds_id='".$tds_id_gstpay."',tds_due_id='".$tds_due_id_gstpay."' where id = '".$link_id_2_pay_tds."'";
           $result5_pay_tds= mysql_query($query5_pay_tds) or die('error in query '.mysql_error().$query5_pay_tds);

           $query1_2="update payment_plan set tds_flag = '1',tds_id='".$tds_id_gstpay."',tds_due_id='".$tds_due_id_gstpay."' where id = '".$due_payment_id_1."'";
           $result1_2= mysql_query($query1_2) or die('error in query '.mysql_error().$query1_2); 
           //$gst_received_flag=1;
           //amount = '".$due_amount_pay_1."', received_amount = '".$tds_due_amount_1."
       
           if($clear_tds_due=="yes"){
               $query_tds1="update payment_plan set tds_flag = '1',clear_tds_flag='1' ,tds_id='".$tds_id_gstpay."',tds_due_id='".$tds_due_id_gstpay."' where invoice_id = '".$due_invoice_id_1."'";
           $result_tds1= mysql_query($query_tds1) or die('error in query '.mysql_error().$query_tds1);
           
           $receiv_amount_tds=$due_amount_pay_1 - $tds_due_amount_1;
           $query_clear_tds="insert into `clear_due_amount`  set invoice_id  = '".$due_invoice_id_1."',description='".$clear_tdsdue_desc."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',due_amount = '".$receiv_amount_tds."', cust_id = '".$cust_id."', user_id = '".$_SESSION['userId']."', type = 'TDS',create_time = '".getTime()."'";
           $result_clear_tds= mysql_query($query_clear_tds) or die('error in query '.mysql_error().$query_clear_tds);
                   

           }
           else{

               $receiv_amount_tds=$due_amount_pay_1 - $tds_due_amount_1;

           if($receiv_amount_tds<1)
           {
               $tds_clear_flag=1;
               $query_clear_tds="insert into `clear_due_amount`  set invoice_id  = '".$due_invoice_id_1."',description='".$clear_tdsdue_desc."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',due_amount = '".$receiv_amount_tds."', cust_id = '".$cust_id."', user_id = '".$_SESSION['userId']."', type = 'TDS',create_time = '".getTime()."'";
           $result_clear_tds= mysql_query($query_clear_tds) or die('error in query '.mysql_error().$query_clear_tds);
           
           }else{
               $tds_clear_flag=0;
           }//gst_id='".$link_id_2_pay_gst."',gst_due_id='".$link_id_4_gst."'
   
           $query_tds1="update payment_plan set tds_flag = '1',clear_tds_flag='".$tds_clear_flag."' ,tds_id='".$tds_id_gstpay."',tds_due_id='".$tds_due_id_gstpay."' where invoice_id = '".$due_invoice_id_1."'";
           $result_tds1= mysql_query($query_tds1) or die('error in query '.mysql_error().$query_tds1);
           
           }


        }
    }


    
}

/* ---------------   TDS DUE WORK END   -----------------------------*/

/*  --------      gst DUE WORK        -----------------------  */
    
//tds_due_div, tds_due_form , tds_cerf , tds_due_date, tds_due_amount , tds_due_attach_file , tds_due_des , tds-due_flag_value
//due_invoice_id,due_payment_id,due_trans_id ,due_amount_pay
if(mysql_real_escape_string(trim($_REQUEST['file_button_gst'])) == "Submit")
{
   // tds_due_date
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

    //description='".$clear_gstdue_desc."'
    $tds_due_des_1_extra = "GST Received for invoice : ".$due_invoice_id_1."";     
    /*echo '<pre>';
            print_r($_REQUEST);
            exit;*/
          /*  $quuerrr="select id from gst_due_info where payment_plan_id='".$due_payment_id_1."' and trans_id = '".$due_trans_id_1."' and invoice_id = '".$due_invoice_id_1."' ";
    
            $sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
            $no=mysql_num_rows($sql);
            if($no > 0)
            {
                
                    $error_msg = "Gst Due already exist for this invoice id";
                
            }*/
            $pay_from_arr = explode(" -",$_REQUEST['gst_pay_form']);
            $pay_bank_id = get_field_value("id","bank","bank_account_name",$pay_from_arr[0]);
    
            $sql_pay 	= "select * from `payment_plan` where id ='".$due_payment_id_1."'";
                  $query_pay_1 	= mysql_query($sql_pay);
                  $row_pay = mysql_fetch_array($query_pay_1);
                  $trans_id=$row_pay['trans_id'];
                  //$pay_bank_id="";
                  //$due_amount_pay_1 = due_amount_pay_1;
                  $cust_id = $row_pay['cust_id'];
                  $invoice_idnew = $row_pay['invoice_id'];
                  //$tds_due_des_1 = "GST Received for invoice : ".$invoice_idnew."";
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
                  //$link_id_2 = $row_pay[''];
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
                  
                  //invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."',
                  //hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."',
                  

                if($clearall_due_flag=="2")
                {
                    $query_gst1="update payment_plan set gst_flag = '1',clear_gst_flag='1' where invoice_id = '".$invoice_idnew."'";
                    $result_gst1= mysql_query($query_gst1) or die('error in query '.mysql_error().$query_gst1);
                    $query_clear_gst="insert into `clear_due_amount`  set invoice_id  = '".$due_invoice_id_1."',description='".$clear_gstdue_desc."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',due_amount = '".$gst_due_amount_new_total."', cust_id = '".$cust_id."', user_id = '".$_SESSION['userId']."', type = 'GST',create_time = '".getTime()."'";
                    $result_clear_gst= mysql_query($query_clear_gst) or die('error in query '.mysql_error().$query_clear_gst);
                    
        
                }
                else{

                if($tds_due_flag_value_1=="1")
                {                  
        
                    $query3="insert into gst_due_info set invoice_id = '".$due_invoice_id_1."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['gst_due_date'])."',description = '(".$tds_due_des_1_extra.") ".$tds_due_des_1."',amount = '".$due_amount_pay_1."', received_amount = '".$tds_due_amount_1."',create_date = '".getTime()."'";
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
                    
                  //   $query1_2="update payment_plan set gst_flag = '1',gst_id='".$link_id_4."' where id = '".$due_payment_id_1."'";
                  //  $result1_2= mysql_query($query1_2) or die('error in query '.mysql_error().$query1_2);
                   
                            
            $query_pay_gst ="insert into payment_plan set trans_id = '".$trans_id."', bank_id = '".$pay_bank_id."', credit = '".$tds_due_amount_1."',  description = '(".$tds_due_des_1_extra.") ".$tds_due_des_1."', on_customer = '".$cust_id."', invoice_id = '".$invoice_idnew."',payment_flag = '".$payment_flag."', payment_date = '".strtotime($_REQUEST['gst_due_date'])."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."',invoice_issuer_id = '".$invoice_issuer_id."' ,payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."', trans_type = '".$trans_type_pay_gst."', trans_type_name = '".$trans_type_name_pay_gst."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."',create_date = '".getTime()."'";
            $result_pay_gst= mysql_query($query_pay_gst) or die('error in query '.mysql_error().$query_pay_gst);


            $link_id_1_pay_gst = mysql_insert_id();


            $query2_pay_gst="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$invoice_idnew."', debit = '".$tds_due_amount_1."', description = '(".$tds_due_des_1_extra.") ".$tds_due_des_1."', on_project = '".$project_id."', on_bank = '".$pay_bank_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['gst_due_date'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."', payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',link_id = '".$link_id_1_pay_gst."',trans_type = '".$trans_type_pay_gst."', trans_type_name = '".$trans_type_name_pay_gst."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."' ,create_date = '".getTime()."'";
            $result2_pay_gst= mysql_query($query2_pay_gst) or die('error in query '.mysql_error().$query2_pay_gst);

            $link_id_2_pay_gst = mysql_insert_id();  

            $query_gst_update="update gst_due_info set  pp_linkid_1 = '".$link_id_2_pay_gst."',pp_linkid_2 = '".$link_id_1_pay_gst."'  where id = '".$link_id_4."'";
            $result5_gst_update= mysql_query($query_gst_update) or die('error in query '.mysql_error().$query_gst_update);
    
                    //$gst_id_gstpay= $row_pay['gst_id'];
                    //$gst_due_id_gstpay =$row_pay['gst_due_id'];
                     //$link_id_4 , link_id_2_pay_gst
                     //,gst_id='".$gst_id_gstpay."',gst_due_id='".$gst_due_id_gstpay."'
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
            //$gst_received_flag=1;
            //amount = '".$due_amount_pay_1."', received_amount = '".$tds_due_amount_1."
        
            if($clear_gst_due=="yes"){
                $query_gst1="update payment_plan set gst_flag = '1',clear_gst_flag='1' ,gst_id='".$gst_id_gstpay."',gst_due_id='".$gst_due_id_gstpay."' where invoice_id = '".$due_invoice_id_1."'";
            $result_gst1= mysql_query($query_gst1) or die('error in query '.mysql_error().$query_gst1);
            
            $receiv_amount_gst=$due_amount_pay_1 - $tds_due_amount_1;
            $query_clear_gst="insert into `clear_due_amount`  set invoice_id  = '".$due_invoice_id_1."',description='".$clear_gstdue_desc."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',due_amount = '".$receiv_amount_gst."', cust_id = '".$cust_id."', user_id = '".$_SESSION['userId']."', type = 'GST',create_time = '".getTime()."'";
            $result_clear_gst= mysql_query($query_clear_gst) or die('error in query '.mysql_error().$query_clear_gst);
                    

            }else{

                $receiv_amount_gst=$due_amount_pay_1 - $tds_due_amount_1;

            if($receiv_amount_gst<1)
            {
                $gst_clear_flag=1;
                $query_clear_gst="insert into `clear_due_amount`  set invoice_id  = '".$due_invoice_id_1."',description='".$clear_gstdue_desc."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',due_amount = '".$receiv_amount_gst."', cust_id = '".$cust_id."', user_id = '".$_SESSION['userId']."', type = 'GST',create_time = '".getTime()."'";
            $result_clear_gst= mysql_query($query_clear_gst) or die('error in query '.mysql_error().$query_clear_gst);
            
            }else{
                $gst_clear_flag=0;
            }//gst_id='".$link_id_2_pay_gst."',gst_due_id='".$link_id_4_gst."'
    
            $query_gst1="update payment_plan set gst_flag = '1',clear_gst_flag='".$gst_clear_flag."' ,gst_id='".$gst_id_gstpay."',gst_due_id='".$gst_due_id_gstpay."' where invoice_id = '".$due_invoice_id_1."'";
            $result_gst1= mysql_query($query_gst1) or die('error in query '.mysql_error().$query_gst1);
            
            }

                      
        }
            }
                  




    
}

/* ---------------   GST DUE WORK END   -----------------------------*/

        

/*  --------      INVOICE DUE WORK        -----------------------  */
//pay_flag, invoice_cerf,pay_payment_date,invoice_due_attach_file,pay_form,pay_method,pay_checkno,pay_amount
        //invoice_due_flag_value,invoice_payment_id,invoice_attach_file_id,invoice_flag,invoice_trans_id,invoice_invoice_id,invoice_amount_pay,file_button_invoice
        if(mysql_real_escape_string(trim($_REQUEST['file_button_invoice'])) == "Submit")
{
    
    //invoice_due_amount_new_total , pay_amount
   // tds_due_date
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
   //// description='".$clear_invoicedue_desc."'
          /* echo '<pre>';
           print_r($_REQUEST);
           exit;*/
           $pay_from_arr = explode(" -",$_REQUEST['pay_form']);
           $pay_bank_id = get_field_value("id","bank","bank_account_name",$pay_from_arr[0]);
   
           $sql_pay 	= "select * from `payment_plan` where id ='".$due_payment_id_1."'";
           $query_pay_1 	= mysql_query($sql_pay);
           $row_pay = mysql_fetch_array($query_pay_1);
           $trans_id=$row_pay['trans_id'];
           //$pay_bank_id="";
           //$due_amount_pay_1 = due_amount_pay_1;
           
           //$tds_due_des_1 = "GST Received for invoice : ".$invoice_idnew."";
           
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
          // $invoice_due_des_1_extra = "Amount Received for invoice : ".$invoice_due_des_1."";
           $invoice_due_des_1_extra = "Amount Received for invoice : ".$due_invoice_id_1."";     
   
       /* $multi_project_id = $row_pay['multi_project_id']; 
           $goods_detail_id =$row_pay['goods_detail_id']; 
           $link_id_1="0";
           $link_id_2=$due_payment_id_1;
       */
       if($clearall_due_flag_invoice=="2")
       {
           $query_invoice1="update payment_plan set invoice_flag = '1',clear_invoice_flag='1' where invoice_id = '".$invoice_idnew."'";
           $result_invoice1= mysql_query($query_invoice1) or die('error in query '.mysql_error().$query_invoice1);
           $query_clear_invoice="insert into `clear_due_amount`  set invoice_id  = '".$invoice_idnew."',description='".$clear_invoicedue_desc."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',due_amount = '".$invoice_due_amount_new_total."', cust_id = '".$cust_id."', user_id = '".$_SESSION['userId']."', type = 'Invoice',create_time = '".getTime()."'";
           $result_clear_invoice= mysql_query($query_clear_invoice) or die('error in query '.mysql_error().$query_clear_invoice);
           

       }
       else{

             
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
                   
                 //   $query1_2="update payment_plan set gst_flag = '1',gst_id='".$link_id_4."' where id = '".$due_payment_id_1."'";
                 //  $result1_2= mysql_query($query1_2) or die('error in query '.mysql_error().$query1_2);
                  
                           
           $query_pay_invoice ="insert into payment_plan set trans_id = '".$trans_id."', bank_id = '".$pay_bank_id."', credit = '".$pay_amount_1."',  description = '(".$invoice_due_des_1_extra.") ".$invoice_due_des_1."', on_customer = '".$cust_id."', invoice_id = '".$invoice_idnew."',payment_flag = '".$payment_flag."', payment_date = '".strtotime($_REQUEST['pay_payment_date'])."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."',invoice_issuer_id = '".$invoice_issuer_id."' ,payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."', trans_type = '".$trans_type_pay_invoice."', trans_type_name = '".$trans_type_name_pay_invoice."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."',create_date = '".getTime()."'";
           $result_pay_invoice= mysql_query($query_pay_invoice) or die('error in query '.mysql_error().$query_pay_invoice);


           $link_id_1_pay_invoice = mysql_insert_id();


           $query2_pay_invoice="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$invoice_idnew."', debit = '".$pay_amount_1."', description = '(".$invoice_due_des_1_extra.") ".$invoice_due_des_1."', on_project = '".$project_id."', on_bank = '".$pay_bank_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['pay_payment_date'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."', payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',link_id = '".$link_id_1_pay_invoice."',trans_type = '".$trans_type_pay_invoice."', trans_type_name = '".$trans_type_name_pay_invoice."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."' ,create_date = '".getTime()."'";
           $result2_pay_invoice= mysql_query($query2_pay_invoice) or die('error in query '.mysql_error().$query2_pay_invoice);

           $link_id_2_pay_invoice = mysql_insert_id();  

           
        $query_invoice_update="update invoice_due_info set  pp_linkid_1 = '".$link_id_2_pay_invoice."',pp_linkid_2 = '".$link_id_1_pay_invoice."'  where id = '".$link_id_4."'";
        $result5_invoice_update= mysql_query($query_invoice_update) or die('error in query '.mysql_error().$query_invoice_update);


           //$invoice_pay_id_gstpay= $row_pay['invoice_pay_id'];
          // $invoice_due_pay_id_gstpay= $row_pay['invoice_due_pay_id'];
           //$payment_flag = $row_pay['payment_flag'];

                   //$gst_id_gstpay= $row_pay['gst_id'];
                   //$gst_due_id_gstpay =$row_pay['gst_due_id'];
                    //$link_id_4 , link_id_2_pay_gst
                    //,invoice_pay_id='".$invoice_pay_id_gstpay."',
                    //invoice_due_pay_id='".$invoice_due_pay_id_gstpay."'

//,invoice_pay_id='".$invoice_pay_id_gstpay."',invoice_due_pay_id='".$invoice_due_pay_id_gstpay."'

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
           //$gst_received_flag=1;
           //amount = '".$due_amount_pay_1."', received_amount = '".$tds_due_amount_1."
       
           if($clear_invoice_due=="yes"){
               $query_invoice1="update payment_plan set invoice_flag = '1',clear_invoice_flag='1' ,invoice_pay_id='".$invoice_pay_id_gstpay."',invoice_due_pay_id='".$invoice_due_pay_id_gstpay."' where invoice_id = '".$invoice_idnew."'";
           $result_invoice1= mysql_query($query_invoice1) or die('error in query '.mysql_error().$query_invoice1);
           //amount = '".$due_amount_pay_1."', received_amount = '".$pay_amount_1."'
           $receiv_amount_invoice=$due_amount_pay_1 - $pay_amount_1;
           $query_clear_invoice="insert into `clear_due_amount`  set invoice_id  = '".$due_invoice_id_1."',description='".$clear_invoicedue_desc."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',due_amount = '".$receiv_amount_invoice."', cust_id = '".$cust_id."', user_id = '".$_SESSION['userId']."', type = 'Invoice',create_time = '".getTime()."'";
           $result_clear_invoice= mysql_query($query_clear_invoice) or die('error in query '.mysql_error().$query_clear_invoice);
                   

           }else{

               $receiv_amount_invoice=$due_amount_pay_1 - $pay_amount_1;

           if($receiv_amount_invoice<1)
           {
               $invoice_clear_flag=1;
               $query_clear_invoice="insert into `clear_due_amount`  set invoice_id  = '".$due_invoice_id_1."',description='".$clear_invoicedue_desc."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',due_amount = '".$receiv_amount_invoice."', cust_id = '".$cust_id."', user_id = '".$_SESSION['userId']."', type = 'Invoice',create_time = '".getTime()."'";
           $result_clear_invoice= mysql_query($query_clear_invoice) or die('error in query '.mysql_error().$query_clear_invoice);
           
           }else{
               $invoice_clear_flag=0;
           }//gst_id='".$link_id_2_pay_gst."',gst_due_id='".$link_id_4_gst."'
   
           $query_invoice1="update payment_plan set invoice_flag = '1',clear_invoice_flag='".$invoice_clear_flag."' ,invoice_pay_id='".$invoice_pay_id_gstpay."',invoice_due_pay_id='".$invoice_due_pay_id_gstpay."' where invoice_id = '".$due_invoice_id_1."'";
           $result_invoice1= mysql_query($query_invoice1) or die('error in query '.mysql_error().$query_invoice1);
               }
       
           }


       }

    
}

/* ---------------   INVOICE DUE WORK END   -----------------------------*/

if(mysql_real_escape_string(trim($_REQUEST['search_action'])) != "")
{
        $from_date = strtotime(mysql_real_escape_string(trim($_REQUEST['from_date'])));
    
        $to_date = strtotime(mysql_real_escape_string(trim($_REQUEST['to_date'])));
//,payment_plan.id as payment_id
        $select_query = "select * ,payment_plan.id as payment_id from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
        $select_result = mysql_query($select_query) or die('error in query select cash query '.mysql_error().$select_query);
        $select_total = mysql_num_rows($select_result);
    
        $select_query2 = "select * ,payment_plan.id as payment_id from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
        $select_result2 = mysql_query($select_query2) or die('error in query select cash query '.mysql_error().$select_query2);
        $select_total2 = mysql_num_rows($select_result2);
    
    
        $select_query3_bal = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."' ";
        $select_result3_bal = mysql_query($select_query3_bal) or die('error in query select cash query '.mysql_error().$select_query3_bal);
        $select_data3_bal = mysql_fetch_array($select_result3_bal);
        $bal=$select_data3_bal['total_credit']-$select_data3_bal['total_debit'];
                
                
    //echo $bal;
    
                $select_query3 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."'  ";
                $select_result3 = mysql_query($select_query3) or die('error in query select cash query '.mysql_error().$select_query3);
                $select_data3 = mysql_fetch_array($select_result3);
                //echo $select_data3['total_credit']-$select_data3['total_debit'];
                $bal=$select_data3['total_credit']-$select_data3['total_debit'];
                $select_query3_1 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and  payment_plan.payment_date <= '".$to_date."'  ";
                $select_result3_1 = mysql_query($select_query3_1) or die('error in query select cash query '.mysql_error().$select_query3_1);
                $select_data3_1 = mysql_fetch_array($select_result3_1);
                //echo $select_data3['total_credit']-$select_data3['total_debit'];
                //$bal_credit,$bal_debit
                $bal_1=$select_data3_1['total_credit']-$select_data3_1['total_debit'];
                $bal_credit = $select_data3_1['total_credit'];
                $bal_debit = $select_data3_1['total_debit'];
                $search_start ="1";             
                $select_query5 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."'  ";
                
            /*.......   gst tds invoice total query    ........ */
    
            $select_query_tdsgst = "select SUM(invoice_pay_amount) as total_invoice_pay_amount,SUM(tds_amount) as total_tds_amount ,SUM(gst_amount) as total_gst_amount from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and payment_plan.trans_type_name='instmulti_sale_goods'  and payment_plan.credit > 0 and payment_plan.credit!='null' and payment_plan.payment_date <= '".$to_date."'  ";
            $select_result_tdsgst = mysql_query($select_query_tdsgst) or die('error in query select cash query '.mysql_error().$select_query_tdsgst);
            $select_data_tdsgst = mysql_fetch_array($select_result_tdsgst);

            $total_invoice_pay_amount = $select_data_tdsgst['total_invoice_pay_amount'];
            $total_gst_amount = $select_data_tdsgst['total_gst_amount'];
            $total_tds_amount = $select_data_tdsgst['total_tds_amount'];

            $select_query_invoice_rec = "select SUM(received_amount) as invoice_received_amount from invoice_due_info inner join  payment_plan on invoice_due_info.payment_plan_id=payment_plan.id inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and payment_plan.trans_type_name='instmulti_sale_goods'  and payment_plan.credit > 0 and payment_plan.credit!='null' and payment_plan.payment_date <= '".$to_date."'  ";
            $select_result_invoice_rec = mysql_query($select_query_invoice_rec) or die('error in query select cash query '.mysql_error().$select_query_invoice_rec);
            $select_data_invoice_rec = mysql_fetch_array($select_result_invoice_rec);
            $invoice_tot_due=$total_invoice_pay_amount-$select_data_invoice_rec['invoice_received_amount'];
           
            $select_query_gst_rec = "select SUM(received_amount) as gst_received_amount from gst_due_info inner join  payment_plan on gst_due_info.payment_plan_id=payment_plan.id inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and payment_plan.trans_type_name='instmulti_sale_goods'  and payment_plan.credit > 0 and payment_plan.credit!='null' and payment_plan.payment_date <= '".$to_date."' ";
            $select_result_gst_rec = mysql_query($select_query_gst_rec) or die('error in query select cash query '.mysql_error().$select_query_gst_rec);
            $select_data_gst_rec = mysql_fetch_array($select_result_gst_rec);
            $gst_tot_due=$total_gst_amount-$select_data_gst_rec['gst_received_amount'];
           
            $select_query_tds_rec = "select SUM(received_amount) as tds_received_amount from tds_due_info inner join  payment_plan on tds_due_info.payment_plan_id=payment_plan.id inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and payment_plan.trans_type_name='instmulti_sale_goods'  and payment_plan.credit > 0 and payment_plan.credit!='null'and payment_plan.payment_date <= '".$to_date."'  ";
            $select_result_tds_rec = mysql_query($select_query_tds_rec) or die('error in query select cash query '.mysql_error().$select_query_tds_rec);
            $select_data_tds_rec = mysql_fetch_array($select_result_tds_rec);
            $tds_tot_due=$total_tds_amount-$select_data_tds_rec['tds_received_amount'];
           

}
else
{
    $select_query = "select * ,payment_plan.id as payment_id from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
    $select_result = mysql_query($select_query) or die('error in query select customer query '.mysql_error().$select_query);
    $select_total = mysql_num_rows($select_result);
    
    $select_query2 = "select * ,payment_plan.id as payment_id from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
    $select_result2 = mysql_query($select_query2) or die('error in query select customer query '.mysql_error().$select_query2);
    $select_total2 = mysql_num_rows($select_result2);
    
    $select_query3_bal = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer'   ";
                $select_result3_bal = mysql_query($select_query3_bal) or die('error in query select cash query '.mysql_error().$select_query3_bal);
                $select_data3_bal = mysql_fetch_array($select_result3_bal);
                $bal=$select_data3_bal['total_credit']-$select_data3_bal['total_debit'];
    //echo $bal;
     
                $select_query3 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer'   ";
                $select_result3 = mysql_query($select_query3) or die('error in query select cash query '.mysql_error().$select_query3);
                $select_data3 = mysql_fetch_array($select_result3);
                //echo $select_data3['total_credit']-$select_data3['total_debit'];
                $bal=$select_data3['total_credit']-$select_data3['total_debit'];

                $select_query5 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer'   ";

            /*.......   gst tds invoice total query    ........ */
    
            $select_query_tdsgst = "select SUM(invoice_pay_amount) as total_invoice_pay_amount,SUM(tds_amount) as total_tds_amount ,SUM(gst_amount) as total_gst_amount from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and payment_plan.trans_type_name='instmulti_sale_goods'  and payment_plan.credit > 0 and payment_plan.credit!='null'  ";
            $select_result_tdsgst = mysql_query($select_query_tdsgst) or die('error in query select cash query '.mysql_error().$select_query_tdsgst);
            $select_data_tdsgst = mysql_fetch_array($select_result_tdsgst);

            $total_invoice_pay_amount = $select_data_tdsgst['total_invoice_pay_amount'];
            $total_gst_amount = $select_data_tdsgst['total_gst_amount'];
            $total_tds_amount = $select_data_tdsgst['total_tds_amount'];

            $select_query_invoice_rec = "select SUM(received_amount) as invoice_received_amount from invoice_due_info inner join  payment_plan on invoice_due_info.payment_plan_id=payment_plan.id inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and payment_plan.trans_type_name='instmulti_sale_goods'  and payment_plan.credit > 0 and payment_plan.credit!='null'  ";
            $select_result_invoice_rec = mysql_query($select_query_invoice_rec) or die('error in query select cash query '.mysql_error().$select_query_invoice_rec);
            $select_data_invoice_rec = mysql_fetch_array($select_result_invoice_rec);
            $invoice_tot_due=$total_invoice_pay_amount-$select_data_invoice_rec['invoice_received_amount'];
           
            $select_query_gst_rec = "select SUM(received_amount) as gst_received_amount from gst_due_info inner join  payment_plan on gst_due_info.payment_plan_id=payment_plan.id inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and payment_plan.trans_type_name='instmulti_sale_goods'  and payment_plan.credit > 0 and payment_plan.credit!='null'  ";
            $select_result_gst_rec = mysql_query($select_query_gst_rec) or die('error in query select cash query '.mysql_error().$select_query_gst_rec);
            $select_data_gst_rec = mysql_fetch_array($select_result_gst_rec);
            $gst_tot_due=$total_gst_amount-$select_data_gst_rec['gst_received_amount'];
           
            $select_query_tds_rec = "select SUM(received_amount) as tds_received_amount from tds_due_info inner join  payment_plan on tds_due_info.payment_plan_id=payment_plan.id inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and payment_plan.trans_type_name='instmulti_sale_goods'  and payment_plan.credit > 0 and payment_plan.credit!='null'  ";
            $select_result_tds_rec = mysql_query($select_query_tds_rec) or die('error in query select cash query '.mysql_error().$select_query_tds_rec);
            $select_data_tds_rec = mysql_fetch_array($select_result_tds_rec);
            $tds_tot_due=$total_tds_amount-$select_data_tds_rec['tds_received_amount'];
           
            }
    


?>
<html>
<head>
<title>Admin Panel</title>
<script src="js/jquery-1.12.4.min.js"></script>

</head>
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
<body>
<?php 
include_once("header.php");
?>

<div id="wrapper">
    <?php
    include_once("leftbar.php");

    ?>
    <div id="rightContent">
    <h3>Customer - <?php echo get_field_value("full_name","customer","cust_id",$_REQUEST['cust_id']); ?> Ledger</h3>
     <input type="hidden" id="print_header" name="print_header" value="<h3>Customer - <?php echo get_field_value("full_name","customer","cust_id",$_REQUEST['cust_id']); ?> Ledger</h3>">   
<br>

<form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" >

<link rel="stylesheet" href="css/jquery-ui.css" />
             <script src="js/jquery-1.9.1.js"></script>
             <script src="js/jquery-ui.js"></script>    
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    
                    <td width="50">
                    
                    &nbsp;&nbsp;From
                    </td>
                    <td width="120">
                    <input type="text"  name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>" style="width:100px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('from_date')" style="cursor:pointer"/>
                   
                                <!--<input type="text" name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>"  readonly="" style="width:100px;" >-->
                 </td>
                
                 <td width="50">
                    &nbsp;&nbsp;To
                    </td>
                    <td width="120">
                    <input type="text"  name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>" style="width:100px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('to_date')" style="cursor:pointer"/>
                    <!--<input type="text" name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>"  readonly="" style="width:100px;" >-->
                 </td>
                 
                    <td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='customer-ledger.php?cust_id=<?php echo $_REQUEST['cust_id']; ?>';"  /></td>
                    <td align="right" valign="top" >
                    <a href="customer.php" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
                    <input type="button" name="print_button" id="print_button" value="" class="button_print" onClick="return print_data();"  />
                    
                  <!--  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->
<script src="dist/jquery.table2excel.min.js"></script>
<!--<script src="jquery.min.js"></script>-->
<!--<script type="text/javascript" src="script.js"></script>-->

<input type="button" id="export_to_excel" value="" class="button_export" >
                    
                    </td>
                    
                    
                </tr>
            </table>
            <input type="hidden" name="search_action" id="search_action" value="Search"  />
            
            </form>
       
<div id="ledger_data">
        <table class="data" id="my_table" border="1" cellpadding="1" cellspacing="0" style="border: 1px solid #111111;">
        <?php $colm=1; ?>
            <tr >
            <thead class="report-header">
                <th class="data" width="15px">S.No.</th>
                <th class="data" width="70">Date</th>
                <th class="data">To&nbsp; / From</th>                
                <th class="data">Project</th>
                <th class="data">Subdivision</th>
                <th class="data">Description</th>
                <th class="data" width="80px">TDS Due</th>
                <th class="data" width="80px">GST Due</th>
                <th class="data" width="80px">Invoice Due after TDS</th>
                <th class="data" width="50">Debit</th>
                <th class="data" width="50">Credit</th>
                <th class="data" width="70">Balance </th>
                <th class="data" id="header1">File</th>
                </thead>
            </tr>
            <?php
            if($select_total > 0)
            {
                $i = 1;
                //$bal=0;
                ///////////////st/////////////////////
               
                
              
              //echo $bal;
                ///////////////////end////////////////
                
                
                while($select_data = mysql_fetch_array($select_result))
                {
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
                             $opening_tds_tot_due = $tds_tot_due;
                             $opening_gst_tot_due = $gst_tot_due ;
                             $opening_invoice_tot_due = $invoice_tot_due;
                             
                             ?>
                                                     <tr class="data">
                        <td class="data" width="30px"><?php //echo $i; ?></td>
                        <td class="data"><b> <?php echo date("d-m-Y",$to_date); ?></b></td>
                        <td class="data"></td>
                        <td class="data"></td>
                        <td class="data"></td>
                        <td class="data"><b><?php echo "Closing Balance"; ?></b></td>
                        <td class="data"  width="80px"><b><?php //echo $total_tds_amount."&nbsp;"; 
                        echo $tds_tot_due; ?></b></td>
                        <td class="data" width="80px"><b><?php //echo $total_gst_amount."&nbsp;"; 
                        echo $gst_tot_due; ?></b></td>
                        <td class="data" width="80px"><b><?php //echo $total_invoice_pay_amount."&nbsp;";  
                        echo $invoice_tot_due;  ?></b></td>

                        <td class="data"><b><?php echo number_format($select_data3_1['total_debit'],2,'.',''); ?> </b></td>
                        <td class="data"><b><?php echo number_format($select_data3_1['total_credit'],2,'.',''); ?></b></td>
                        <td class="data" <?php if($bal_1<0 ) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?> ><b><?php 
                        //echo currency_symbol().
                        echo number_format($bal_1,2,'.',''); ?></b></td>
                        <td class="data" nowrap="nowrap"></td>                        
                    </tr>
                             <?php
                         }
                         else{
                             ?>
                        <tr class="data">
                        <td class="data" width="30px"><?php //echo $i; ?></td>
                        <td class="data"><b> <?php echo date("d-m-Y",$select_data['payment_date']); ?></b></td>
                        <td class="data"></td>
                        <td class="data"></td>
                        <td class="data"></td>
                        <td class="data"><b><?php echo "Closing Balance"; ?></b></td>
                        <td class="data"  width="80px"><b><?php //echo $total_tds_amount."&nbsp;"; 
                        echo $tds_tot_due; ?></b></td>
                        <td class="data" width="80px"><b><?php //echo $total_gst_amount."&nbsp;"; 
                        echo $gst_tot_due; ?></b></td>
                        <td class="data" width="80px"><b><?php //echo $total_invoice_pay_amount."&nbsp;";
                        echo $invoice_tot_due; ?></b></td>
                        <td class="data"><b><?php echo number_format($select_data3['total_debit'],2,'.',''); ?> </b></td>
                        <td class="data"><b><?php echo number_format($select_data3['total_credit'],2,'.',''); ?></b></td>
                        <td class="data" <?php if($bal<0 ) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?>><b><?php 
                        //echo currency_symbol().
                        echo number_format($bal,2,'.',''); ?></b>
                        </td>
                        <td class="data" nowrap="nowrap"></td>                        
                    </tr>
                 <?php   }   ?>
                 <?php   }   ?>
                 
                    <tr class="data">
                        <td class="data" ><?php echo $i; ?></td>
                        <td class="data"><?php echo date("d-m-y",$select_data['payment_date']); ?></td>
                        <td class="data"><?php 
                        if($select_data['on_customer'] != "")
                        {
                            echo get_field_value("full_name","customer","cust_id",$select_data['on_customer']);
                        }
                        else if($select_data['on_bank'] != "")
                        {
                            echo get_field_value("bank_account_name","bank","id",$select_data['on_bank']);
                        }else if($select_data['trans_type_name']=="instmulti_sale_goods")
                        { 
                            echo "Invoice No. ( ".$select_data['invoice_id']." )";
                            
                        } ?>
                         
                         
                         </td>
                        <td class="data"><?php echo get_field_value("name","project","id",$select_data['on_project']); ?></td>
                        <td class="data"><?php echo get_field_value("name","subdivision","id",$select_data['subdivision']); ?></td>
                        <td class="data"><?php echo $select_data['description']; ?></td>
                        <td id="" class="data" width="80px"    ><?php 
                        if($select_data['credit'] > 0)
                        {
                        if($select_data['tds_amount']!=0)
                        { 
                            
                            ?>
                        <!-- // <?php echo $select_data['trans_id']; ?> // <?php echo $select_data['payment_id']; ?> // <?php echo $select_data['invoice_id']; ?>  -->
                            <!-- <a href="javascript:void(0);" title="View File" onClick="return view_file_function('<?php echo $select_data['invoice_id']; ?>');" >-->
                            
                        &nbsp;
                        <?php
                            $tds_due_query1 = "select SUM(amount) as amount,SUM(received_amount) as received_amount  from tds_due_info where payment_plan_id = '".$select_data['payment_id']."' and invoice_id = '".$select_data['invoice_id']."' ";
                            $tds_due_result2 = mysql_query($tds_due_query1) or die("error in date list query ".mysql_error());
                            $total_tds2 = mysql_num_rows($tds_due_result2);
                            if($total_tds2 > 0)
                                {
                                    $find_tds = mysql_fetch_array($tds_due_result2);
                                    $due_tds_final = $select_data['tds_amount']-$find_tds['received_amount'];
                                }
                                else{
                                // $due_value="All";
                                    $due_tds_final=$select_data['tds_amount'];
                                    
                                }
                                // opening_tds_tot_due , opening_gst_tot_due  ,  opening_invoice_tot_due
                                $opening_tds_tot_due=$opening_tds_tot_due - $due_tds_final;
                            if($select_data['tds_flag']=="1")
                            {
                                ?>
                            
                            <a href="invoice-ledger.php?invoice_id=<?php echo $select_data['invoice_id']; ?>&payment_id=<?php echo $select_data['payment_id']; ?>" title="View Ledger"  >             
                            <img src="mos-css/img/active.png" title="<?php 
                            $d_date= get_field_value("due_date","tds_due_info","id",$select_data['tds_id']); 
                                echo "Date : &nbsp;".date("d-m-Y",$d_date);
                            echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                            $d_amount= $find_tds['received_amount']; 
                            echo "TDS Amount : &nbsp;".$d_amount;
                                ?>" ></a>
                            <?php 

                            }
                            if($select_data['clear_tds_flag']=="0" )
                            {   
                                ?>
                                <a href="javascript:void(0);" <?php if($select_data['clear_tds_flag']==0 ) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?>  title="TDS DUE" onClick="return tds_due_function('<?php echo $select_data['payment_id']; ?>','<?php echo $select_data['trans_id']; ?>','<?php echo $select_data['invoice_id']; ?>','<?php  echo $due_tds_final; ?>');" >
                        <?php 
                        
                        echo $due_tds_final;    ?>  
                        </a> 
                            <?php 
                            }
                        ?>
                            
                        <?php
                        } 
                    }
                        //echo $select_data['tds_amount']; ?></td>
                        
<td class="data" width="80px" align="center" >
                        <?php
                        if($select_data['credit'] > 0)
                        {
                        if($select_data['gst_amount']!=0)
                        { ?>
                            &nbsp;
                            <?php
                                $gst_due_query1 = "select SUM(amount) as amount,SUM(received_amount) as received_amount  from gst_due_info where payment_plan_id = '".$select_data['payment_id']."' and invoice_id = '".$select_data['invoice_id']."' ";
                                $gst_due_result2 = mysql_query($gst_due_query1) or die("error in date list query ".mysql_error());
                                $total_gst2 = mysql_num_rows($gst_due_result2);
                                if($total_gst2 > 0)
                                    {
                                        $find_gst = mysql_fetch_array($gst_due_result2);
                                        $due_gst_final = $select_data['gst_amount']-$find_gst['received_amount'];
                                    }
                                    else{
                                       // $due_value="All";
                                        $due_gst_final=$select_data['gst_amount'];
                                        
                                    }
                                        // opening_tds_tot_due , opening_gst_tot_due  ,  opening_invoice_tot_due
                                        // $opening_tds_tot_due=$opening_tds_tot_due - $due_tds_final;
                                        $opening_gst_tot_due=$opening_gst_tot_due - $due_gst_final;
                                        // $opening_invoice_tot_due=$opening_invoice_tot_due - $due_invoice_final;
                           
                                    if($select_data['gst_flag']=="1")
                                {
                                    ?>
                                   <a href="invoice-ledger.php?invoice_id=<?php echo $select_data['invoice_id']; ?>&payment_id=<?php echo $select_data['payment_id']; ?>" title="View Ledger"  >  
                                      
                                   <img src="mos-css/img/active.png" title="<?php 
                                   $d_date= get_field_value("due_date","gst_due_info","id",$select_data['gst_id']); 
                                    echo "Date : &nbsp;".date("d-m-Y",$d_date);
                                  echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                                  $d_amount= $find_gst['received_amount']; 
                                  echo "GST Amount : &nbsp;".$d_amount;
                                    ?>" ></a>
                                  <?php 

                                }
                                 if($select_data['clear_gst_flag']=="0" )
                                {   
                                       ?>
                                    <a href="javascript:void(0);" <?php if($select_data['clear_gst_flag']==0 ) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?>  title="GST DUE" onClick="return gst_due_function('<?php echo $select_data['payment_id']; ?>','<?php echo $select_data['trans_id']; ?>','<?php echo $select_data['invoice_id']; ?>','<?php  echo $due_gst_final; ?>');" >
                          <?php   echo $due_gst_final;    ?>  
                            </a> 
                                   <?php 
                                }
                        }
                    }
                            ?>   </td>
                        <td class="data" width="80px"><?php
                        /*   invoice DUE          */
                        
                        if($select_data['credit'] > 0)
                        {
                            //echo number_format($select_data['credit'],2,'.','');
                            if($select_data['invoice_pay_amount']!=0)
                            { ?>
                                &nbsp;
                                <?php
                                    $invoice_due_query1 = "select SUM(amount) as amount,SUM(received_amount) as received_amount  from invoice_due_info where payment_plan_id = '".$select_data['payment_id']."' and invoice_id = '".$select_data['invoice_id']."' ";
                                    $invoice_due_result2 = mysql_query($invoice_due_query1) or die("error in date list query ".mysql_error());
                                    $total_invoice2 = mysql_num_rows($invoice_due_result2);
                                    if($total_invoice2 > 0)
                                        {
                                            $find_invoice = mysql_fetch_array($invoice_due_result2);
                                            $due_invoice_final = $select_data['invoice_pay_amount']-$find_invoice['received_amount'];
                                        }
                                        else{
                                           // $due_value="All";
                                            $due_invoice_final=$select_data['invoice_pay_amount'];
                                            
                                        }
                                         // opening_tds_tot_due , opening_gst_tot_due  ,  opening_invoice_tot_due
                                        // $opening_tds_tot_due=$opening_tds_tot_due - $due_tds_final;
                                       // $opening_gst_tot_due=$opening_gst_tot_due - $due_gst_final;
                                         $opening_invoice_tot_due=$opening_invoice_tot_due - $due_invoice_final;
                           
                                    if($select_data['invoice_flag']=="1")
                                    {
                                        ?>
                                       <a href="invoice-ledger.php?invoice_id=<?php echo $select_data['invoice_id']; ?>&payment_id=<?php echo $select_data['payment_id']; ?>" title="View Ledger"  >  <img src="mos-css/img/active.png" title="<?php 
                                      // $d_date= get_field_value("due_date","invoice_due_info","id",$select_data['gst_id']); 
                                        echo "Date : &nbsp;".date("d-m-Y",$d_date);
                                      echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                                      $d_amount= $find_invoice['received_amount']; 
                                      echo "Invoice Amount : &nbsp;".$d_amount;
                                        ?>" ></a>
                                      <?php 
    
                                    }
                                     if($select_data['clear_invoice_flag']=="0" )
                                    {   
                                           ?>
                                        <a href="javascript:void(0);" <?php if($select_data['clear_invoice_flag']==0 ) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?>  title="Invoice DUE" onClick="return invoice_due_function('<?php echo $select_data['payment_id']; ?>','<?php echo $select_data['trans_id']; ?>','<?php echo $select_data['invoice_id']; ?>','<?php  echo $due_invoice_final; ?>');" >
                              <?php   echo $due_invoice_final;    ?>  
                                </a> 
                                       <?php 
                                    }
                            }
                       // echo $select_data['payment_flag'];
                    }
                        /*         //// INVOICE DUE                  */
                        ?></td>
                       
                        <td class="data">
                        <?php
                            if($select_data['debit'] > 0)
                            {
                                echo number_format($select_data['debit'],2,'.','');
                                 
                            }
 
                            ?>
                        </td>
                        <td class="data">
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
                             <td class="data" <?php if($bal_1<0 ) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?>  >
                             <?php 
                            //echo currency_symbol().
                            echo number_format($bal_1,2,'.','');
                            ?>
                            </td>
                            <?php 
                        }else
                        {
                            ?>
                             <td class="data" <?php if($bal<0) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?> >
                             <?php 
                            //echo currency_symbol().
                            echo number_format($bal,2,'.','');  
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
                        <?php //echo currency_symbol().number_format($bal,2,'.','');
                        //$bal=(float)$bal+(float)$select_data['debit'];
                       // $bal=(float)$bal-(float)$select_data['credit'];
                         ?>
                        <td class="data" nowrap="nowrap">
                        <?php //////////////   delete File start     ///////////////?>
                        <?php 

                        
                        if($select_data['trans_type_name']=="instmulti_receive_payment")
                        { ?>
                        <?php if($select_data['invoice_id'] != "" && $select_data['invoice_id'] != 0) { ?>
                        &nbsp;<a href="javascript:account_transaction_invoice_pay(<?php echo $select_data['invoice_id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>
                        <?php    
                        }}
                        else if($select_data['trans_type_name']=="instmulti_receive_payment_invoice")
                        { ?>
                        <?php if($select_data['invoice_id'] != "" && $select_data['invoice_id'] != 0) { ?>
                        &nbsp;<a href="javascript:account_transaction_invoice_gst(<?php echo $select_data['invoice_id'] ?>,<?php echo $select_data['payment_id'] ?>,'Invoice');"><img src="mos-css/img/delete.png" title="Delete" ></a>
                        <?php    
                        }
                    }
                        else if($select_data['trans_type_name']=="tds_receive_payment")
                        { ?>
                        <?php 
                        if($select_data['invoice_id'] != "" && $select_data['invoice_id'] != 0) { ?>
                        &nbsp;<a href="javascript:account_transaction_invoice_gst(<?php echo $select_data['invoice_id'] ?>,<?php echo $select_data['payment_id'] ?>,'TDS');"><img src="mos-css/img/delete.png" title="Delete" ></a>
                        <?php    
                        }
                    }
                        else if($select_data['trans_type_name']=="gst_receive_payment")
                        { ?>
                        <?php
                        if($select_data['invoice_id'] != "" && $select_data['invoice_id'] != 0) { ?>
                        &nbsp;<a href="javascript:account_transaction_invoice_gst(<?php echo $select_data['invoice_id'] ?>,<?php echo $select_data['payment_id'] ?>,'GST');"><img src="mos-css/img/delete.png" title="Delete" ></a>
                        <?php   
                        } 
                    }
                        
                        else if($select_data['trans_type_name']=="instmulti_sale_goods")
                        { ?>
                        <?php if($select_data['invoice_id'] != "" && $select_data['invoice_id'] != 0) { ?>
                        &nbsp;<a href="javascript:account_transaction_invoice(<?php echo $select_data['invoice_id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>
                        <?php    
                        }}
                        
                        else
                        { ?>
                        <?php if($select_data['trans_id'] != "" && $select_data['trans_id'] != 0) { ?>
                        &nbsp;<a href="javascript:account_transaction(<?php echo $select_data['trans_id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>
                        <?php } }
                         //////////////    File delete end    ///////////////  
                         //////////////   Attach File start     ///////////////
                        if($select_data['trans_type_name']=="instmulti_sale_goods")
                        {  ?>
                        &nbsp;<a href="javascript:void(0);" title="Attach File" onClick="return invoice_attach_file_function('<?php echo $select_data['invoice_id']; ?>');" ><img src="images/images.jpg" width="20" ></a>
                        <?php
                        }else
                         if($select_data['trans_type_name']=="instmulti_receive_payment")
                        {  ?>
                        &nbsp;<a href="javascript:void(0);" title="Attach File" onClick="return invoice_attach_file_function('<?php echo $select_data['invoice_id']; ?>');" ><img src="images/images.jpg" width="20" ></a>
                        <?php
                        }
                        else{
                        ?>
                        &nbsp;<a href="javascript:void(0);" title="Attach File" onClick="return attach_file_function('<?php echo $select_data['payment_id']; ?>');" ><img src="images/images.jpg" width="20" ></a>
                        <?php
                        }
                         //////////////   Attach File end     ///////////////
                         //////////////   edit File start     ///////////////
                        if($select_data['trans_type_name']=="receive_payment")
                        {  ?>
                        <a href="edit_receive_payment.php?trans_id=<?php echo $select_data['trans_id']; ?>&id=<?php echo $select_data['payment_id']; ?>&trsns_pname=<?php echo "customer-ledger"; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } ?>
                        <?php  
                        if($select_data['trans_type_name']=="sale_goods")
                        {  ?>
                        <a href="edit_sale_invoice.php?trans_id=<?php echo $select_data['trans_id']; ?>&id=<?php echo $select_data['payment_id']; ?>&trsns_pname=<?php echo "customer-ledger"; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } ?>
                 <?php
                        if($select_data['trans_type_name']=="inst_receive_payment")
                        {  ?>
                        <a href="edit-instant-sale-invoice.php?trans_id=<?php echo $select_data['trans_id']; ?>&id=<?php echo $select_data['payment_id']; ?>&trsns_pname=<?php echo "customer-ledger-inst-receive-payment"; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } ?>
                  <?php
                        if($select_data['trans_type_name']=="inst_sale_goods")
                        {  ?>
                        <a href="edit-instant-sale-invoice.php?trans_id=<?php echo $select_data['trans_id']; ?>&id=<?php echo $select_data['payment_id']; ?>&trsns_pname=<?php echo "customer-ledger-inst-sale-goods"; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } ?>
                 
                 <?php
                        if($select_data['trans_type_name']=="instmulti_sale_goods")
                        {  ?>
                        <a href="edit_instant-sale-invoice_multiple.php?trans_id=<?php echo $select_data['trans_id']; ?>&id=<?php echo $select_data['payment_id']; ?>&trsns_pname=<?php echo "customer-ledger-inst-sale-goods"; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } ?>
                 <?php
                        if($select_data['trans_type_name']=="instmulti_receive_payment")
                        {  ?>
                        <a href="edit_instant-sale-invoice_multiple.php?trans_id=<?php echo $select_data['trans_id']; ?>&id=<?php echo $select_data['payment_id']; ?>&trsns_pname=<?php echo "customer-ledger-inst-receive-payment"; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } ?>
                 <?php
                        if($select_data['trans_type_name']=="tds_receive_payment")
                        {  ?>
                        <a href="javascript:void(0);"   title="Edit GST" onClick="return edit_tds_function('<?php echo $select_data['payment_id']; ?>','customer-ledger','<?php echo $select_data['invoice_id']; ?>','<?php echo $select_data['link3_id']; ?>');" ><img src="mos-css/img/edit.png" title="Edit">  </a> 

                        
                <?php  } ?>
                <?php
                        if($select_data['trans_type_name']=="gst_receive_payment")
                        {  ?>
                        <a href="javascript:void(0);"   title="Edit GST" onClick="return edit_gst_function('<?php echo $select_data['payment_id']; ?>','customer-ledger','<?php echo $select_data['invoice_id']; ?>','<?php echo $select_data['link3_id']; ?>');" ><img src="mos-css/img/edit.png" title="Edit">  </a> 

                        
                <?php  } ?>
                <?php
                        if($select_data['trans_type_name']=="instmulti_receive_payment_invoice")
                        {  ?>
                        <a href="javascript:void(0);"   title="Edit GST" onClick="return edit_invoice_function('<?php echo $select_data['payment_id']; ?>','customer-ledger','<?php echo $select_data['invoice_id']; ?>','<?php echo $select_data['link3_id']; ?>');" ><img src="mos-css/img/edit.png" title="Edit">  </a> 

                        
                <?php  } ?>
                 
                 <?php //////////////   edit File end     ///////////////?>
                           <?php //////////////   view File start     ///////////////?>                 
                  
                 <?php
                 
                        $total_rows_view=0;
                        if($select_data['trans_type_name']=="instmulti_sale_goods")
                        {  
                            
                        $query_view="select *  from attach_file where attach_id = '".$select_data['invoice_id']."'";
                        $result_view= mysql_query($query_view) or die('error in query '.mysql_error().$query_view);
                        $total_rows_view = mysql_num_rows($result_view);
                        if($total_rows_view != 0)
                        { ?>
                           <a href="javascript:void(0);" title="View File" onClick="return view_file_function('<?php echo $select_data['invoice_id']; ?>');" >View</a>     <?php           }
                            
                        }
                        else  if($select_data['trans_type_name']=="instmulti_receive_payment")
                        {  
                            
                        $query_view="select *  from attach_file where attach_id = '".$select_data['invoice_id']."'";
                        $result_view= mysql_query($query_view) or die('error in query '.mysql_error().$query_view);
                        $total_rows_view = mysql_num_rows($result_view);
                        if($total_rows_view != 0)
                        { ?>
                           <a href="javascript:void(0);" title="View File" onClick="return view_file_function('<?php echo $select_data['invoice_id']; ?>');" >View</a>     <?php           }
                            
                        }
                        else
                        {
                        $query_view="select *  from attach_file where attach_id = '".$select_data['payment_id']."'";
                        $result_view= mysql_query($query_view) or die('error in query '.mysql_error().$query_view);
                        $total_rows_view = mysql_num_rows($result_view);
                        if($total_rows_view != 0)
                        { ?>
                           <a href="javascript:void(0);" title="View File" onClick="return view_file_function('<?php echo $select_data['payment_id']; ?>');" >View</a>     <?php           }
                        }

?> 
                 </td>
                        
                    </tr>
                <?php
                    $i++;
                }
                
                if($search_start=="1")
                {
                    // opening_tds_tot_due , opening_gst_tot_due  ,  opening_invoice_tot_due
                    ?>
                    
                    <tr class="data">
                        <td class="data" width="30px"><?php //echo $i; ?></td>
                        <td class="data"><b> <?php echo date("d-m-Y",$from_date); ?></b></td>
                        <td class="data"></td>
                        <td class="data"></td>
                        <td class="data"></td>
                        <td class="data"><b><?php echo "Opening Balance"; ?></b></td>
                        <td class="data"><b><?php echo $opening_tds_tot_due; ?></b></td>
                        <td class="data"><b><?php echo $opening_gst_tot_due; ?></b></td>
                        <td class="data"><b><?php echo $opening_invoice_tot_due; ?></b></td>
                        <td class="data"><b><?php echo number_format($bal_debit,2,'.',''); ?> </b></td>
                        <td class="data"><b><?php echo number_format($bal_credit,2,'.',''); ?></b></td>
                        <td class="data" <?php if($bal_1<0 ) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?>><b><?php 
                        //echo currency_symbol().
                        echo number_format($bal_1,2,'.',''); ?></b></td>
                        <td class="data" nowrap="nowrap"></td>                        
                    </tr>
                    <?php
    

                }
                
                
            }
            else
            {
                ?>
                <tr class="data" >
                    <td  width="30px" colspan="10" class="record_not_found" >Record Not Found</td>
                </tr>
                <?php
            }
            ?>
            
        </table>
        
           </div>     
        
    </div>
<div class="clear"></div>
<?php
include_once("footer.php");
?>
</div>

<div id="attach_div" style="position:absolute;top:50%; left:40%; width:500px; height:150px; margin:-100px 0 0 -50px;z-index:100; display:none; background-color:#FFFFFF; border:8px solid #999999;" >
<form name="attach_form" id="attach_form" method="post" action="" onSubmit="return attach_validation();" enctype="multipart/form-data" >
<table cellpadding="0" cellspacing="0" border="1" width="100%" >
<tr><td valign="top" align="right" colspan="2" ><img src="images/close.gif" onClick="return close_div();" ></td></tr>
<tr><td valign="top" >Attach File</td>
            <td><input type="file" name="attach_file" id="attach_file" value="" ></td></tr>
            
            <tr><td valign="top" >Attach File Name</td>
            <td><input type="text" id="attach_file_name"  name="attach_file_name" value="" autocomplete="off"/></td></tr>
            
            <tr><td></td><td>
            <input type="submit" class="button" name="file_button" id="file_button" value="Submit" >
            </td></tr>
</table>
<input type="hidden" id="attach_file_id"  name="attach_file_id" value="" />
<input type="hidden" id="invoice_flag"  name="invoice_flag" value="" />
</form>
</div>
<div id="view_div" style="position:absolute;top:50%; left:40%; width:500px; min-height:250px; margin:-100px 0 0 -50px;z-index:100; display:none; background-color:#FFFFFF; border:8px solid #999999;" >

</div>
<form name="trans_form" id="trans_form" action="" method="post" >
        
        <input type="hidden" name="trans_id" id="trans_id" value="" >
        </form>
<form name="trans_form_1" id="trans_form_1" action="" method="post" >
        
        <input type="hidden" name="trans_id_1" id="trans_id_1" value="" >
        </form>
        
<form name="invoice_form" id="invoice_form" action="" method="post" >
        
        <input type="hidden" name="invoice_id" id="invoice_id" value="" >
        <input type="hidden" name="trans_t_name" id="trans_t_name" value="" >
        
        <input type="hidden" name="del_payment_id" id="del_payment_id" value="" >
        <input type="hidden" name="del_type" id="del_type" value="" >
       
        </form>


        <!---  Due TDs Attchment Div   -->
 <!-- /// attach_div  ,attach_form ,  attach_form ,  attach_validation , attach_file_id   , invoice_flag  -->    
<div id="tds_due_div" style="position:absolute;top:50%; left:40%; width:500px; height:380px; margin:-100px 0 0 -50px;z-index:100; display:none; background-color:#FFFFFF; border:8px solid #999999;" >
<form name="tds_due_form" id="tds_due_form" method="post" action="" onSubmit="return tds_validation();" enctype="multipart/form-data" >
<table cellpadding="0" cellspacing="0" border="1px" width="100%" >
<tr><td valign="top" align="right" colspan="2" ><img src="images/close.gif" onClick="return close_tds_div();" ></td></tr>
<tr>
                    <td valign="top" style="color:#FF0000; font-weight:bold;" colspan="2" >TDS Due Amount
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                       
                    <input type="text" id="tds_due_amount_new_total" style="width:100px;color:red; border:0; " readonly="readonly"  name="tds_due_amount_new_total" value="" /></td>
                </tr>
                <tr>
                    <td valign="top" colspan="2" style="color:#blue; " >
                    <table width="100%">
                        <tr>
                            <td width="100px">Clear TDS Due :</td>
                            <td width="40px">
                            <input type="checkbox" id="clear_tds_due" name="clear_tds_due" onClick="return clear_tds_desc();" value="yes">
                        </td>
                            <td width=""><input type='text' style="display:none; width=300px;" name='clear_tdsdue_desc' align='right' id='clear_tdsdue_desc' width="300px" placeholder="Type clear due Description here"/>
                    </td>
                        </tr>
                    </table>
                    
                    
                </td>
                    
                </tr>
               

<tr><td valign="top" width="300px" >Received TDS Certificate</td>
            <td align="right"><input type="radio" name="tds_cerf" id="tds_cerf" value="1" onClick="return close_tds_div2('1');" >&nbsp;&nbsp;YES&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="tds_cerf" id="tds_cerf" checked=true value="0" onClick="return close_tds_div2('0');" >&nbsp;&nbsp;NO
            </td>
        </tr>
        <tr>  
            <td valign="top" colspan="2" align="left" id="tds-due_div2" style=" display:none; width:100%">
                <table width="100%" border="2" style="border:2px;">  
                <tr>
                    <td valign="top" >Paid Into</td>
                    <td>
                        <!--<input type="text" id="invoice_due_des" style="width:260px;"  name="invoice_due_des" value="" autocomplete="off"/>
        -->
        <input type="text" id="tds_pay_form"  name="tds_pay_form" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span> 
                </td>
                </tr>

                <tr>    <td valign="top"  width="250px" >Date</td>
                    <td><input type="text"  name="tds_due_date" id="tds_due_date" value="<?php //echo $_REQUEST['tds_due_date']; ?>" style="width:100px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('tds_due_date')" style="cursor:pointer"/></td>
                </tr>
                <tr>
                    <td valign="top" >Amount Received</td>
                    <td><input type="text" id="tds_due_amount" style="width:100px;"  name="tds_due_amount" value="" onkeydown="tds_due_calculation()" onkeyup="tds_due_calculation()" onkeypress="tds_due_calculation()" />
                    <span id=""  style="color:red;" >(Due :<input type="text" id="tds_due_amount_new_due" style="width:60px;color:red;border:0;" readonly="readonly"  name="tds_due_amount_new_due" value="" /></span>
                </td>
                </tr>
               
                <tr>
                    <td valign="top" >TDS File Attachment</td>
                    <td><input type="file" name="tds_due_attach_file" id="tds_due_attach_file" value="" ></td>
                </tr>
                <tr>
                    <td valign="top" >Discription</td>
                    <td><input type="text" id="tds_due_des" style="width:260px;"  name="tds_due_des" value="" autocomplete="off"/></td>
                </tr>
            </table>
            </td>
        </tr>    
        
        <input type="hidden" id="clearall_due_flag_tds"  name="clearall_due_flag_tds" value="" /> 
    <input type="hidden" id="tds_due_flag_value"  name="tds_due_flag_value" value="0" />
    <input type="hidden" id="due_payment_id"  name="due_payment_id" value="" />
    <input type="hidden" id="attach_file_id"  name="attach_file_id" value="" />
    <input type="hidden" id="invoice_flag"  name="invoice_flag" value="" />
    <input type="hidden" id="due_trans_id"  name="due_trans_id" value="" />
    <input type="hidden" id="due_invoice_id"  name="due_invoice_id" value="" />
    <input type="hidden" id="due_amount_pay"  name="due_amount_pay" value="" />
    <tr>
        <td valign="bottom" align="center" colspan="2"><input type="submit" class="button" name="file_button1" id="file_button1" value="Submit" ></td></tr>
</table>

</form>
</div>

        <!--      /// Due TDS Attachment Div     --->

        
        <!---  Due GST Attchment Div   -->
 <!-- /// attach_div  ,attach_form ,  attach_form ,  attach_validation , attach_file_id   , invoice_flag  -->    
<div id="gst_due_div" style="position:absolute;top:50%; left:40%; width:500px; height:380px; margin:-100px 0 0 -50px;z-index:100; display:none; background-color:#FFFFFF; border:8px solid #999999;" >
<form name="gst_due_form" id="gst_due_form" method="post" action="" onSubmit="return gst_validation();" enctype="multipart/form-data" >
<table cellpadding="0" cellspacing="0" border="1px" width="100%" >
    <tr><td valign="top" align="right" colspan="2" ><img src="images/close.gif" onClick="return close_gst_div();" ></td></tr>
                <tr>
                    <td valign="top" style="color:#FF0000; font-weight:bold;" colspan="2" >GST Due Amount
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                       
                    <input type="text" id="gst_due_amount_new_total" style="width:100px;color:red; border:0; " readonly="readonly"  name="gst_due_amount_new_total" value="" /></td>
                </tr>
                <tr>
                    <td valign="top" colspan="2" style="color:#blue; "  >
                    <table width="100%">
                        <tr>
                            <td width="100px">Clear GST Due :</td>
                            <td width="40px"><input type="checkbox" id="clear_gst_due" onClick="return clear_gst_desc();" name="clear_gst_due" value="yes"></td>
                            <td width=""><input type='text' style="display:none; width=300px;" name='clear_gstdue_desc' align='right' id='clear_gstdue_desc' width="300px" placeholder="Type clear due Description here"/>
                    </td>
                        </tr>
                    </table>
                    </td>
                </tr>
                
            
            <tr><td valign="top" width="300px" >Received gst :</td>
            <td align="right"><input type="radio" name="gst_cerf" id="gst_cerf" value="1" onClick="return close_gst_div2('1');" >&nbsp;&nbsp;YES&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="gst_cerf" id="gst_cerf"  checked=true value="0" onClick="return close_gst_div2('0');" >&nbsp;&nbsp;NO
            </td>
        </tr>
        <tr>  
            <td valign="top" colspan="2" align="left" id="gst-due_div2" style=" display:none; width:100%">
                <table width="100%" border="2" style="border:2px;">  
                <tr>
                    <td valign="top" >Paid Into</td>
                    <td>
                        <!--<input type="text" id="invoice_due_des" style="width:260px;"  name="invoice_due_des" value="" autocomplete="off"/>
        -->
        <input type="text" id="gst_pay_form"  name="gst_pay_form" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span> 
                </td>
                </tr>

                <tr>    <td valign="top"  width="250px" >Date</td>
                    <td><input type="text"  name="gst_due_date" id="gst_due_date" value="<?php //echo $_REQUEST['tds_due_date']; ?>" style="width:100px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('gst_due_date')" style="cursor:pointer"/></td>
                </tr>
                
                <tr>
                    <td valign="top" >Amount Received</td>
                    <td><input type="text" id="gst_due_amount" style="width:100px;"  name="gst_due_amount" value="" onkeydown="gst_due_calculation()" onkeyup="gst_due_calculation()" onkeypress="gst_due_calculation()" />
                    <span id=""  style="color:red;" >(Due :<input type="text" id="gst_due_amount_new_due" style="width:60px;color:red;border:0;" readonly="readonly"  name="gst_due_amount_new_due" value="" /></span>
                </td>
                </tr>
                <tr>
                    <td valign="top" >GST File Attachment</td>
                    <td><input type="file" name="gst_due_attach_file" id="gst_due_attach_file" value="" ></td>
                </tr>
                <tr>
                    <td valign="top" >Discription</td>
                    <td><input type="text" id="gst_due_des" style="width:260px;"  name="gst_due_des" value="" autocomplete="off"/></td>
                </tr>
            </table>
            </td>
        </tr>    
         
    <input type="hidden" id="clearall_due_flag"  name="clearall_due_flag" value="" />
    
    <input type="hidden" id="gst_due_flag_value"  name="gst_due_flag_value" value="0" />
    <input type="hidden" id="gst_payment_id"  name="gst_payment_id" value="" />
    <input type="hidden" id="attach_file_id"  name="attach_file_id" value="" />
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
<div id="invoice_due_div" style="position:absolute;top:50%; left:40%; width:500px; height:450px; margin:-100px 0 0 -50px;z-index:100; display:none; background-color:#FFFFFF; border:8px solid #999999;" >
<form name="invoice_due_form" id="invoice_due_form" method="post" action="" onSubmit="return invoice_validation();" enctype="multipart/form-data" >
  

<table cellpadding="0" cellspacing="0" border="1px" width="100%" >
<tr><td valign="top" align="right" colspan="2" ><img src="images/close.gif" onClick="return close_invoice_div();" ></td></tr>
<tr>
                    <td valign="top" style="color:#FF0000; font-weight:bold;" colspan="2" >Invoice Due Amount
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                       
                    <input type="text" id="invoice_due_amount_new_total" style="width:100px;color:red; border:0; " readonly="readonly"  name="invoice_due_amount_new_total" value="" /></td>
                </tr>
                    
                <tr>
                    <td valign="top" colspan="2" style="color:#blue; " >
                    <table width="100%">
                        <tr>
                            <td width="100px">Clear Invoice Due :</td>
                            <td width="40px">
                            <input type="checkbox" id="clear_invoice_due" name="clear_invoice_due" onClick="return clear_invoice_desc();" value="yes">
                        </td>
                            <td width=""><input type='text' style="display:none; width=300px;" name='clear_invoicedue_desc' align='right' id='clear_invoicedue_desc' width="300px" placeholder="Type clear due Description here"/>
                    </td>
                        </tr>
                    </table>
                    
                    
                </td>
                    
                </tr>
                
<tr><td valign="top" width="300px" >Received Due Invoice Amount</td>
            <td align="right"><input type="radio" name="invoice_cerf" id="invoice_cerf" value="1" onClick="return close_invoice_div2('1');" >&nbsp;&nbsp;YES&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="invoice_cerf" id="invoice_cerf" checked=true value="0" onClick="return close_invoice_div2('0');" >&nbsp;&nbsp;NO
            </td>
        </tr>
        <tr>  
            <td valign="top" colspan="2" align="left" id="invoice-due_div2" style=" display:none; width:100%">
                <table width="100%" border="2" style="border:2px;">  
                <tr>    <td valign="top"  width="250px" >Payment Date</td>
                    <td><input type="text"  name="pay_payment_date" id="pay_payment_date" value="<?php //echo $_REQUEST['tds_due_date']; ?>" style="width:100px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('pay_payment_date')" style="cursor:pointer"/></td>
                </tr>
                <tr>
                    <td valign="top" >Amount Received</td>
                    <td><input type="text" id="pay_amount" style="width:100px;"  name="pay_amount" value="" onkeydown="invoice_due_calculation()" onkeyup="invoice_due_calculation()" onkeypress="invoice_due_calculation()" />
                    <span id=""  style="color:red;" >(Due :<input type="text" id="invoice_due_amount_new_due" style="width:60px;color:red;border:0;" readonly="readonly"  name="invoice_due_amount_new_due" value="" /></span>
                </td>
                </tr>
                
                <tr>
                    <td valign="top" >Payment File Attachment</td>
                    <td><input type="file" name="invoice_due_attach_file" id="invoice_due_attach_file" value="" ></td>
                </tr>
                <tr>
                    <td valign="top" >Paid Into</td>
                    <td>
                        <!--<input type="text" id="invoice_due_des" style="width:260px;"  name="invoice_due_des" value="" autocomplete="off"/>
        -->
        <input type="text" id="pay_form"  name="pay_form" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span> 
                </td>
                </tr>
                <tr>
                    <td valign="top" >Discription</td>
                    <td><input type="text" id="invoice_due_des" style="width:260px;"  name="invoice_due_des" value="" autocomplete="off"/></td>
                </tr>
                <tr>
                    <td valign="top" >Payment Method</td>
                    <td>
                    <input type="radio" id="pay_method" name="pay_method"  onchange=" return checkno_create();" value="check">
            <label for="male">Cheque</label>&nbsp;&nbsp;
            <input type="radio" id="pay_method" name="pay_method"  onchange="return checkno_create1();" value="bank">
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
                            <td width="120px">Cheque No.</td>
                            <td><input type="text"  name="pay_checkno" id="pay_checkno" value="" /><br></td>
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

        <!--      /// Due Invoice Attachment Div     --->

</body>
</html>
<script>
 $(document).ready(function(){
    
    $("#export_to_excel").click(function(){
        //document.getElementById("hid1").style.visibility = "hidden";    
         //$("td:hidden,th:hidden","#my_table").remove();

//newWin.document.write(getHeader());

         //$('table td').find('td:eq(4)').hide(); 
         //$("#thead").hide(); 
         //$("td:hidden,th:hidden","#my_table").remove();
        $("#my_table").table2excel({        
                 
            exclude: ".noExl",
            name: "Developer data",
            filename: "Customer_ledger_test",
            fileext: ".xls",        
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true
                    
        });  
        // $("#thead").show();
    });
   // $('table td').find('td:eq(4)').show(); 
   $( "#pay_form" ).autocomplete({
            source: "bankcash-ajax.php"
        });

        $( "#gst_pay_form" ).autocomplete({
            source: "bankcash-ajax.php"
        });

        $( "#tds_pay_form" ).autocomplete({
            source: "bankcash-ajax.php"
        });
        
});


 
</script>
<script>
function close_view()
{
    $('#view_div').hide("slow");
}

function view_file_function(id)
{
    
    $('#view_div').show("slow");
    $.ajax({
        url: "attach_file_ajax.php?id="+id,
        type: 'GET',
        dataType: 'html',
        beforeSend: function () {
            $('#view_div').html('Processing..................');
            
        },
        success: function (data, textStatus, xhr) {
            /*var arr = data.split("EXPLODE");
            $('#export_div').show();
            $('#cas_issued_div').html(arr[0]);        
            $('#student_query').val(arr[1]);
            $('#student_export').show();*/
            $('#view_div').html(data);        
            
            
        },
        error: function (xhr, textStatus, errorThrown) {
            $('#view_div').html(textStatus);
        }
    });
    
}
function attach_validation()
{
    if(document.getElementById("attach_file").value=="")
    {
     alert("Please Select file");
     document.getElementById("attach_file").focus();
     return false;
    }
    else if(document.getElementById("attach_file_name").value=="")
    {
     alert("Please enter attach file name ");
     document.getElementById("attach_file_name").focus();
     return false;
    } 
}
function attach_file_function(id)
{
    
    document.getElementById("attach_div").style.display="block";
    document.getElementById("attach_file_id").value=id;
    
}

function invoice_attach_file_function(id)
{
    
    document.getElementById("attach_div").style.display="block";
    document.getElementById("attach_file_id").value=id;
    document.getElementById("invoice_flag").value="1";
    
}

function close_div()
{
    document.getElementById("attach_div").style.display="none";
}

function tds_due_function(due_id,due_trans,due_invoice,due_amount_pay)
{
         //attach_file_id , due_trans_id , due_invoice_id , due_amount_pay
    //due_invoice_id,due_payment_id,due_trans_id
    /// attach_div  ,attach_form ,  attach_form ,  attach_validation , attach_file_id   , invoice_flag    
    
    document.getElementById("due_payment_id").value=due_id;
    
    document.getElementById("due_trans_id").value=due_trans;
    document.getElementById("due_invoice_id").value=due_invoice;
    document.getElementById("due_amount_pay").value=due_amount_pay;
    document.getElementById("tds_due_amount_new_total").value=due_amount_pay;
    document.getElementById("tds_due_amount").value=0;
    document.getElementById("tds_due_amount_new_due").value=due_amount_pay;
    
    //alert(due_amount_pay);
    document.getElementById("tds_due_div").style.display="block";
    
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
    //clear_gstdue_desc
}

function tds_due_calculation()
{
   tot_val= document.getElementById("tds_due_amount_new_total").value;
    tot_get_val= document.getElementById("tds_due_amount").value;
    final_due=Number(tot_val)-Number(tot_get_val);
    document.getElementById("tds_due_amount_new_due").value=final_due;
    if(final_due<1)
    {
        document.getElementById('clear_tds_due').checked = true;
        document.getElementById("clear_tdsdue_desc").style.display="block";
    }
    else{
        document.getElementById('clear_tds_due').checked = false;
        document.getElementById("clear_tdsdue_desc").style.display="none";
    }
   // final_amount_show();
   
} 

function tds_validation()
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
        }else 
        {
        document.getElementById("clearall_due_flag_tds").value="2";
     return true;
        }
    }else 
        {
        document.getElementById("clearall_due_flag_tds").value="2";
     return true;
        }
    }
    else if(document.getElementById("tds_due_flag_value").value=="1")
    { //gst_pay_form, gst_due_date , gst_due_amount ,gst_due_des
        document.getElementById("clearall_due_flag_tds").value="0";
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
            alert("Please enter received TDS amount");
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


function close_tds_div()
{
    //tds-due_div2
    document.getElementById("tds_due_div").style.display="none";
   // document.getElementById("tds-due_div2").style.display="none";
}


function close_tds_div2(due_flag_val)
{
   // alert(due_flag_val);
    //tds-due_div2
   // document.getElementById("tds_due_div").style.display="none";
   if(due_flag_val=="0")
   {
    document.getElementById("tds-due_div2").style.display="none";
    document.getElementById("tds_due_flag_value").value="0";
   } else if(due_flag_val=="1")
   {
    document.getElementById("tds-due_div2").style.display="block";
    document.getElementById("tds_due_flag_value").value="1";
   }
   
}



function gst_due_function(due_id,due_trans,due_invoice,due_amount_pay)
{
     //attach_file_id , due_trans_id , due_invoice_id , due_amount_pay
    //due_invoice_id,due_payment_id,due_trans_id
    /// attach_div  ,attach_form ,  attach_form ,  attach_validation , attach_file_id   , invoice_flag    
    
    document.getElementById("gst_payment_id").value=due_id;
    
    document.getElementById("gst_trans_id").value=due_trans;
    document.getElementById("gst_invoice_id").value=due_invoice;
    document.getElementById("gst_amount_pay").value=due_amount_pay;
    document.getElementById("gst_due_amount_new_total").value=due_amount_pay;
    document.getElementById("gst_due_amount").value=0;
    document.getElementById("gst_due_amount_new_due").value=due_amount_pay;
   // alert(due_amount_pay);
    document.getElementById("gst_due_div").style.display="block";
    
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
function gst_due_calculation()
{
   tot_val= document.getElementById("gst_due_amount_new_total").value;
    tot_get_val= document.getElementById("gst_due_amount").value;
    final_due=Number(tot_val)-Number(tot_get_val);
    document.getElementById("gst_due_amount_new_due").value=final_due;
    if(final_due<1)
    {
        document.getElementById('clear_gst_due').checked = true;
        document.getElementById("clear_gstdue_desc").style.display="block";
    }
    else{
        document.getElementById('clear_gst_due').checked = false;
        document.getElementById("clear_gstdue_desc").style.display="none";
    }
   // final_amount_show();
   
} 

function gst_validation()
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

function close_gst_div()
{
    //tds-due_div2
    document.getElementById("gst_due_div").style.display="none";
   // document.getElementById("tds-due_div2").style.display="none";
}


function close_gst_div2(due_flag_val)
{
   // alert(due_flag_val);
    //tds-due_div2
   // document.getElementById("tds_due_div").style.display="none";
   if(due_flag_val=="0")
   {
    document.getElementById("gst-due_div2").style.display="none";
    document.getElementById("gst_due_flag_value").value="0";
   } else if(due_flag_val=="1")
   {
    document.getElementById("gst-due_div2").style.display="block";
    document.getElementById("gst_due_flag_value").value="1";
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
    //clear_gstdue_desc
}

function invoice_due_function(due_id,due_trans,due_invoice,due_amount_pay)
{
         //attach_file_id , due_trans_id , due_invoice_id , due_amount_pay
    //due_invoice_id,due_payment_id,due_trans_id
    /// attach_div  ,attach_form ,  attach_form ,  attach_validation , attach_file_id   , invoice_flag    
    
    document.getElementById("invoice_payment_id").value=due_id;
    
    document.getElementById("invoice_trans_id").value=due_trans;
    document.getElementById("invoice_invoice_id").value=due_invoice;
    document.getElementById("invoice_amount_pay").value=due_amount_pay;
    document.getElementById("invoice_due_amount_new_total").value=due_amount_pay;
    document.getElementById("pay_amount").value=0;
    document.getElementById("invoice_due_amount_new_due").value=due_amount_pay;
   
   // alert(due_amount_pay);
    document.getElementById("invoice_due_div").style.display="block";
    
}
 

function invoice_due_calculation()
{
   tot_val= document.getElementById("invoice_due_amount_new_total").value;
    tot_get_val= document.getElementById("pay_amount").value;
    final_due=Number(tot_val)-Number(tot_get_val);
    document.getElementById("invoice_due_amount_new_due").value=final_due;
    if(final_due<1)
    {
        document.getElementById('clear_invoice_due').checked = true;
        document.getElementById("clear_invoicedue_desc").style.display="block";
    }
    else{
        document.getElementById('clear_invoice_due').checked = false;
        document.getElementById("clear_invoicedue_desc").style.display="block";
    }
   // final_amount_show();
   
} 

function invoice_validation()
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
        document.getElementById("clearall_due_flag_invoice").value="2";
     return true;
    }
    }
    else{
        document.getElementById("clearall_due_flag_invoice").value="2";
     return true;
    }
    }
    else if(document.getElementById("invoice_due_flag_value").value=="1")
    { 
        //pay_payment_date ,pay_amount,pay_form
        document.getElementById("clearall_due_flag_invoice").value="0";
        if(document.getElementById('clear_invoice_due').checked == true && document.getElementById("clear_invoicedue_desc").value=="")
    {
            alert("Please enter description for clear due");
            document.getElementById("clear_invoicedue_desc").focus();
            return false;
        
    }else if(document.getElementById("pay_payment_date").value=="")
        {
            alert("Please select payment date");
            document.getElementById("pay_payment_date").focus();
            return false;
        }else if(document.getElementById("pay_amount").value=="")
        {
            alert("Please enter received Invoice amount");
            document.getElementById("pay_amount").focus();
            return false;
        }else if(document.getElementById("pay_form").value=="")
        {
            alert("Please select to the bank name");
            document.getElementById("pay_form").focus();
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

function close_invoice_div()
{
    //tds-due_div2
    document.getElementById("invoice_due_div").style.display="none";
   // document.getElementById("tds-due_div2").style.display="none";
}


function close_invoice_div2(due_flag_val)
{
   // alert(due_flag_val);
    //tds-due_div2
   // document.getElementById("tds_due_div").style.display="none";
   if(due_flag_val=="0")
   {
    document.getElementById("invoice-due_div2").style.display="none";
    document.getElementById("invoice_due_flag_value").value="0";
   } else if(due_flag_val=="1")
   {
    document.getElementById("invoice-due_div2").style.display="block";
    document.getElementById("invoice_due_flag_value").value="1";
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


function search_valid()
{
    if(document.getElementById("from_date").value=="")
    {
     alert("Please enter from date");
     document.getElementById("from_date").focus();
     return false;
    }
    else if(document.getElementById("to_date").value=="")
    {
     alert("Please enter to ");
     document.getElementById("to_date").focus();
     return false;
    } 
    
    
}

function account_transaction(trans_id)
{
    if(confirm("Are you sure want to delete?!!!!!......"))
    {
        $("#trans_id").val(trans_id);
        $("#trans_form").submit();
        return true;
    }
}

function account_transaction_invoice(invoice_id)
{
    if(confirm("Are you sure want to delete?!!!!!......"))
    { 
        
        $("#trans_t_name").val("instmulti_sale_goods");
        
        $("#invoice_id").val(invoice_id);
        $("#invoice_form").submit();
        return true;
    }
}

function account_transaction_invoice_pay(invoice_id)
{
    if(confirm("Are you sure want to delete?!!!!!......"))
    { 
        $("#trans_t_name").val("instmulti_receive_payment");
        $("#invoice_id").val(invoice_id);
        $("#invoice_form").submit();
        return true;
    }
}

function account_transaction_invoice_gst(invoice_id,payment_id,type)
{
    if(confirm("Are you sure want to delete?!!!!!......"))
    { 
        $("#trans_t_name").val("instmulti_receive_gst_tds");
        $("#invoice_id").val(invoice_id);
        $("#del_payment_id").val(payment_id);
        $("#del_type").val(type);
        $("#invoice_form").submit();
        return true;
    }
}


function account_transaction_1(trans_id_1)
{
    if(confirm("Are you sure want to delete?!!!!!......"))
    {
        $("#trans_id_1").val(trans_id_1);
        $("#trans_form_1").submit();
        return true;
    }
}


function edit_tds_function(info_tb_id,trsns_pname,invoice_no,payment_id)
{
 //edit_tds_form ,  info_tb_id, trsns_pname ,invoice_no , payment_id 
    document.getElementById("info_tb_id_tds").value=info_tb_id;
    document.getElementById("trsns_pname_tds").value=trsns_pname;
    document.getElementById("invoice_no_tds").value=invoice_no;
    document.getElementById("payment_id_tds").value=payment_id;
    $("#edit_tds_form").submit();
		return true;
}


function edit_gst_function(info_tb_id,trsns_pname,invoice_no,payment_id)
{
 //edit_tds_form ,  info_tb_id, trsns_pname ,invoice_no , payment_id 
    document.getElementById("info_tb_id_gst").value=info_tb_id;
    document.getElementById("trsns_pname_gst").value=trsns_pname;
    document.getElementById("invoice_no_gst").value=invoice_no;
    document.getElementById("payment_id_gst").value=payment_id;
    $("#edit_gst_form").submit();
//		return true;
}

function edit_invoice_function(info_tb_id,trsns_pname,invoice_no,payment_id)
{
 //edit_tds_form ,  info_tb_id, trsns_pname ,invoice_no , payment_id 
    document.getElementById("info_tb_id_invoice").value=info_tb_id;
    document.getElementById("trsns_pname_invoice").value=trsns_pname;
    document.getElementById("invoice_no_invoice").value=invoice_no;
    document.getElementById("payment_id_invoice").value=payment_id;
    $("#edit_invoice_form").submit();
//		return true;
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
$('table tr').find('td:eq(9)').hide();
newWin.document.write(divToPrint.outerHTML);
newWin.print();
//$('tr').children().eq(7).show();

$('table tr').find('td:eq(9)').show();
$("#header1").show();
newWin.close();
   
   /* printMe=window.open();
    printMe.document.write(document.getElementById("").innerHTML);
    printMe.print();
    printMe.close();*/
}

</script>

