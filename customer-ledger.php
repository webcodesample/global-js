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
    $old_clear_table_link_id=$data_del['clear_table_link_id'];
    $del_query = "delete from payment_plan where id = '".$old_payment_id_cust."' ";
    $del_result = mysql_query($del_query) or die("error in delete payment customer  query ".mysql_error());
       
    if($old_payment_id_bank!="NULL")
    {
        $del_query = "delete from payment_plan where id = '".$old_payment_id_bank."' ";
    $del_result = mysql_query($del_query) or die("error in delete payment bank query ".mysql_error());
    $bank_id_val="and pp_linkid_2=".$old_payment_id_bank;
    //and  pp_linkid_2=".$old_payment_id_bank."
    }
    else{
        $bank_id_val="";
    }
      
    $query_del_file="select *  from ".$due_tb_name." where pp_linkid_1 = ".$old_payment_id_cust." and invoice_id=".$old_invoice_id_paytb." ";
    $result_del_file= mysql_query($query_del_file) or die('error in query '.mysql_error().$query_del_file);
    $data_del_file = mysql_fetch_array($result_del_file);

    
        if($data_del_file['cert_file_name']!="")
        {
            unlink($file_folder."/".$data_del_file['cert_file_name']);
        }

    $del_query = "delete from ".$due_tb_name." where pp_linkid_1 = ".$old_payment_id_cust." and invoice_id=".$old_invoice_id_paytb." ";
    $del_result = mysql_query($del_query) or die("error in delete payment bank query ".mysql_error());
     
     //exit;
     $query_del="select *  from ".$due_tb_name." where invoice_id = '".$old_invoice_id_paytb."'";
     $result_del= mysql_query($query_del) or die('error in query '.mysql_error().$query_del);
        
     //$query_del_withour_clear="select *  from ".$due_tb_name." where invoice_id = '".$old_invoice_id_paytb."' and clear_due_flag!='1'";
     $query_del_withour_clear="select *  from ".$due_tb_name." where invoice_id = '".$old_invoice_id_paytb."' ";
     
     $result_del_withour_clear= mysql_query($query_del_withour_clear) or die('error in query '.mysql_error().$query_del_withour_clear);
     $total_gst2_withour_clear = mysql_num_rows($result_del_withour_clear);
                                
        if($total_gst2_withour_clear>0){
            $flag_val=1;
        }
        else{
            $flag_val=0;
        }
     
     
     $pay_id="";
     $due_pay_id="";
     while($data_del = mysql_fetch_array($result_del))
	{
      
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

    /*********   clear due amount delete automaticly      ******* */
/*
    if($flag_val==0)
    {   
        $query_del_file="select *  from clear_due_amount where invoice_id = '".$old_invoice_id_paytb."' and payment_plan_id='".$old_main_id_paytb."' and type='".$payment_type."' ";
        $result_del_file1= mysql_query($query_del_file) or die('error in query '.mysql_error().$query_del_file);
        $total_gst2_withour_clear1 = mysql_num_rows($result_del_file1);
     
        $data_del_file1 = mysql_fetch_array($result_del_file1);
        
        $clear_due_payment_planid=$data_del_file1['pp_linkid_1'];
       if($total_gst2_withour_clear1>0)
       {
        $query_del="delete  from payment_plan where invoice_id = '".$old_invoice_id_paytb."' and id='".$clear_due_payment_planid."'";
        $result_del= mysql_query($query_del) or die('error in query '.mysql_error().$query_del);
     
       }
       
        $clear_val_change=",".$clear_type_flag.'= 0';
        $del_query = "delete from clear_due_amount where invoice_id = '".$old_invoice_id_paytb."' and payment_plan_id='".$old_main_id_paytb."' and type='".$payment_type."' ";
        $del_result = mysql_query($del_query) or die("error in delete payment bank query ".mysql_error());
        
        $query_del="delete  from ".$due_tb_name." where invoice_id = '".$old_invoice_id_paytb."'";
        $result_del= mysql_query($query_del) or die('error in query '.mysql_error().$query_del);
     
    }
    else{
        $clear_val_change="";
    }
*/
    if($old_clear_table_link_id!="")
    {
        $del_query = "delete from clear_due_amount where invoice_id = '".$old_invoice_id_paytb."' and payment_plan_id='".$old_main_id_paytb."' and type='".$payment_type."' and id='".$old_clear_table_link_id."' ";
        $del_result = mysql_query($del_query) or die("error in delete payment bank query ".mysql_error());
        $clear_val_change=",".$clear_type_flag.'= 0';

        $detail_info ="Delete clear due amount for Invoice id ".$old_invoice_id_paytb."  and type : ".$payment_type ." Payment , payment_plan id: ".$old_main_id_paytb;
        get_user_info('',$payment_type,$detail_info);    
            
    }

$query5_pay="update payment_plan set ".$type_flag." = '".$flag_val."',".$type_pay_id."='".$pay_id."',".$type_due_pay_id."='".$due_pay_id."' ".$clear_val_change." where invoice_id = '".$old_invoice_id_paytb."'";
$result5_pay= mysql_query($query5_pay) or die('error in query '.mysql_error().$query5_pay);
//echo $query5_pay;

    }
    else if($trans_t_name=="instmulti_receive_payment")
    {
        $del_query = "delete from payment_plan where invoice_id = '".$invoice_id."' and trans_type_name='instmulti_receive_payment'";
        $del_result = mysql_query($del_query) or die("error in invoice delete query ".mysql_error());
        
        
        $query5_1="update payment_plan set link2_id = '0',link3_id = '0' ,payment_flag = '0' where invoice_id = '".$invoice_id."'";
        $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);

        $detail_info ="Detete Customer Receive Payment for Invoice id ".$invoice_id."  ";
        get_user_info('22','instmulti_receive_payment',$detail_info);

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
            $detail_info ="detete Customer Invoice id ".$invoice_id."  ";
            get_user_info('',$trans_t_name,$detail_info);

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
            $detail_info ="delete Customer Invoice id ".$invoice_id." and also Delete all GST TDS & Invoice ";
            get_user_info('24','instmulti_sale_goods',$detail_info);
        }
    }           
    $msg = "Customer Invoice Deleted Successfully.";
}


if(isset($_POST['trans_id_combind']) && $_POST['trans_id_combind'] != "")
{

    $trans_id = $_POST['trans_id_combind'];
    $payment_id = $_POST['payment_id'];
    $del_query = "delete from payment_plan where trans_id = '".$trans_id."'";
    $del_result = mysql_query($del_query) or die("error in Transaction delete query ".mysql_error());
    
    $del_query = "delete from invoice_due_info where pp_linkid_1 = '".$payment_id."'";
    $del_result = mysql_query($del_query) or die("error in Transaction delete query ".mysql_error());
    
    $del_query = "delete from tds_due_info where pp_linkid_1 = '".$payment_id."'";
    $del_result = mysql_query($del_query) or die("error in Transaction delete query ".mysql_error());
    
    $del_query = "delete from gst_due_info where pp_linkid_1 = '".$payment_id."'";
    $del_result = mysql_query($del_query) or die("error in Transaction delete query ".mysql_error());
    
    $msg = "Transaction Deleted Successfully.";
}


 if(isset($_POST['trans_id']) && $_POST['trans_id'] != "")
{

    $trans_id = $_POST['trans_id'];
    $del_query = "delete from payment_plan where trans_id = '".$trans_id."'";
    $del_result = mysql_query($del_query) or die("error in Transaction delete query ".mysql_error());
    $msg = "Transaction Deleted Successfully.";
}
if(isset($_POST['trans_id_invoice']) && $_POST['trans_id_invoice'] != "")
{ 
    //  echo "hello";
//exit;

    $trans_id_gst = $_POST['trans_id_invoice'];
    
    $query_del="select *  from invoice_due_info where id = '".$trans_id_gst."'";
    $result_del= mysql_query($query_del) or die('error in query '.mysql_error().$query_del);
    $data_del = mysql_fetch_array($result_del);
    $fnm= $data_del['cert_file_name'];
 
    unlink("invoice_files/$fnm");
    $del_query_1 = "update invoice_due_info set cert_file_name=''   where id = '".$trans_id_gst."'";
    $del_result_1 = mysql_query($del_query_1) or die("error in Transaction delete query ".mysql_error());
       
   
    
    $msg = "Attach file Deleted Successfully.";
}

if(isset($_POST['trans_id_gst']) && $_POST['trans_id_gst'] != "")
{ //  echo "hello";
//exit;

    $trans_id_gst = $_POST['trans_id_gst'];
    
    $query_del="select *  from gst_due_info where id = '".$trans_id_gst."'";
    $result_del= mysql_query($query_del) or die('error in query '.mysql_error().$query_del);
    $data_del = mysql_fetch_array($result_del);
    $fnm= $data_del['cert_file_name'];
 
    unlink("gst_files/$fnm");
    $del_query_1 = "update gst_due_info set cert_file_name=''   where id = '".$trans_id_gst."'";
    $del_result_1 = mysql_query($del_query_1) or die("error in Transaction delete query ".mysql_error());
       
   
    
    $msg = "Attach file Deleted Successfully.";
}

if(isset($_POST['trans_id_tds']) && $_POST['trans_id_tds'] != "")
{ //  echo "hello";
//exit;

    $trans_id_tds = $_POST['trans_id_tds'];
    
    $query_del="select *  from tds_due_info where id = '".$trans_id_tds."'";
    $result_del= mysql_query($query_del) or die('error in query '.mysql_error().$query_del);
    $data_del = mysql_fetch_array($result_del);
    $fnm= $data_del['cert_file_name'];
 
    unlink("tds_files/$fnm");
    $del_query_1 = "update tds_due_info set cert_file_name=''   where id = '".$trans_id_tds."'";
    $del_result_1 = mysql_query($del_query_1) or die("error in Transaction delete query ".mysql_error());
    
    $msg = "Attach file Deleted Successfully.";
}
if(isset($_POST['trans_id_1']) && $_POST['trans_id_1'] != "")
{ 
    //  echo "hello";
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
   
//tds_due_amount_pay , tds_due_invoice_id, tds_due_trans_id , tds_invoice_flag , tds_attach_file_id , tds_due_payment_id , tds_due_flag_value , clearall_due_flag_tds
    $tds_cerf_1=mysql_real_escape_string(trim($_REQUEST['tds_cerf']));
    $tds_due_date_1=mysql_real_escape_string(trim($_REQUEST['tds_due_date']));
    $tds_due_amount_1=mysql_real_escape_string(trim($_REQUEST['tds_due_amount']));
    $tds_due_des_1=mysql_real_escape_string(trim($_REQUEST['tds_due_des']));
    $tds_due_flag_value_1=mysql_real_escape_string(trim($_REQUEST['tds_due_flag_value']));
    $due_trans_id_1=mysql_real_escape_string(trim($_REQUEST['tds_due_trans_id']));
    $due_payment_id_1=mysql_real_escape_string(trim($_REQUEST['tds_due_payment_id']));
    $due_invoice_id_1=mysql_real_escape_string(trim($_REQUEST['tds_due_invoice_id']));
    $due_amount_pay_1=mysql_real_escape_string(trim($_REQUEST['tds_due_amount_pay']));
    $tds_due_date_1_n=strtotime($tds_due_date_1);
    
    $clearall_due_flag=mysql_real_escape_string(trim($_REQUEST['clearall_due_flag_tds']));
    $tds_due_amount_new_total=mysql_real_escape_string(trim($_REQUEST['tds_due_amount_new_total']));
    $clear_tds_due=mysql_real_escape_string(trim($_REQUEST['clear_tds_due']));
    $clear_tdsdue_desc=mysql_real_escape_string(trim($_REQUEST['clear_tdsdue_desc']));
    
    //description='".$clear_tdsdue_desc."'
    $trans_type_pay_tds = 52;
    $trans_type_name_pay_tds= "tds_receive_payment" ;
       /*
            echo '<pre>';
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
           // $trans_type_pay_gst = 51;
           // $trans_type_name_pay_gst= "gst_receive_payment" ;
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
            $pay_from_arr = explode(" -",$_REQUEST['tds_pay_form']);
            $pay_bank_id = get_field_value("id","bank","bank_account_name",$pay_from_arr[0]);
                
            $tds_due_des_1_extra = "TDS Received for invoice : ".$invoice_idnew."";    
            $clear_tds_due_des_1_extra = "TDS Clear Amount for invoice : ".$invoice_idnew."";  


            if($clearall_due_flag=="2")
            {
                $query2_pay_tds="insert into payment_plan set userid_create = '".$_SESSION['userId']."', trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$due_invoice_id_1."', debit = '".$tds_due_amount_new_total."', description = '(".$clear_tds_due_des_1_extra.") ".$clear_tdsdue_desc."', on_project = '".$project_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['clear_pay_payment_date_tds'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."', payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',trans_type = '".$trans_type_pay_tds."', trans_type_name = '".$trans_type_name_pay_tds."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."' ,create_date = '".getTime()."'";
                $result2_pay_tds= mysql_query($query2_pay_tds) or die('error in query '.mysql_error().$query2_pay_tds);
                $link_id_2_pay_tds_clear = mysql_insert_id();  
        
        
                $query3_clear="insert into tds_due_info set userid_create = '".$_SESSION['userId']."', pp_linkid_1 = '".$link_id_2_pay_tds_clear."',invoice_id = '".$due_invoice_id_1."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['clear_pay_payment_date_tds'])."',description = '(".$clear_tds_due_des_1_extra.") ".$clear_tdsdue_desc."',amount = '".$due_amount_pay_1."', received_amount = '".$tds_due_amount_new_total."',clear_due_flag=1 ,create_date = '".getTime()."'";
                $result3_clear= mysql_query($query3_clear) or die('error in query '.mysql_error().$query3_clear);
                $link_id_4_clear = mysql_insert_id();
                //clear_table_link_id           
                   $query_clear_tds="insert into `clear_due_amount`  set pp_linkid_1 = '".$link_id_2_pay_tds_clear."',invoice_id  = '".$due_invoice_id_1."',description='".$clear_tdsdue_desc."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',due_amount = '".$tds_due_amount_new_total."', cust_id = '".$cust_id."', user_id = '".$_SESSION['userId']."',due_date = '".strtotime($_REQUEST['clear_pay_payment_date_tds'])."', type = 'TDS',create_time = '".getTime()."'";
                   $result_clear_tds= mysql_query($query_clear_tds) or die('error in query '.mysql_error().$query_clear_tds);
                   $link_id_2_clear_tds = mysql_insert_id();  
        
                   $query_tds1_clear="update payment_plan set tds_flag = '1',clear_tds_flag='1' where invoice_id = '".$due_invoice_id_1."'";
                   $result_tds1_clear= mysql_query($query_tds1_clear) or die('error in query '.mysql_error().$query_tds1_clear);
        
                   $query_tds2_clear="update payment_plan set clear_table_link_id = '".$link_id_2_clear_tds."' where id = '".$link_id_2_pay_tds_clear."'";
                   $result_tds2_clear= mysql_query($query_tds2_clear) or die('error in query '.mysql_error().$query_tds2_clear);
                
               
               
              
            }
            else{

        if($tds_due_flag_value_1=="1")
        {                  
     //       pp_linkid_1 = '".$pp_linkid_1_invoice."',pp_linkid_2 = '".$pp_linkid_2_invoice."',
    
            $query3="insert into tds_due_info set userid_create = '".$_SESSION['userId']."',  invoice_id = '".$due_invoice_id_1."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['tds_due_date'])."',description = '(".$tds_due_des_1_extra.") ".$tds_due_des_1."',amount = '".$due_amount_pay_1."', received_amount = '".$tds_due_amount_1."',create_date = '".getTime()."'";
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

           $query_pay_tds ="insert into payment_plan set userid_create = '".$_SESSION['userId']."',  trans_id = '".$trans_id."', bank_id = '".$pay_bank_id."', credit = '".$tds_due_amount_1."',  description = '(".$tds_due_des_1_extra.") ".$tds_due_des_1."', on_customer = '".$cust_id."', invoice_id = '".$invoice_idnew."',payment_flag = '".$payment_flag."', payment_date = '".strtotime($_REQUEST['tds_due_date'])."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."',invoice_issuer_id = '".$invoice_issuer_id."' ,payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."', trans_type = '".$trans_type_pay_tds."', trans_type_name = '".$trans_type_name_pay_tds."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."',create_date = '".getTime()."'";
           $result_pay_tds= mysql_query($query_pay_tds) or die('error in query '.mysql_error().$query_pay_tds);


           $link_id_1_pay_tds = mysql_insert_id();


           $query2_pay_tds="insert into payment_plan set userid_create = '".$_SESSION['userId']."',  trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$invoice_idnew."', debit = '".$tds_due_amount_1."', description = '(".$tds_due_des_1_extra.") ".$tds_due_des_1."', on_project = '".$project_id."', on_bank = '".$pay_bank_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['tds_due_date'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."', payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',link_id = '".$link_id_1_pay_tds."',trans_type = '".$trans_type_pay_tds."', trans_type_name = '".$trans_type_name_pay_tds."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."' ,create_date = '".getTime()."'";
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
              
           $receiv_amount_tds=$due_amount_pay_1 - $tds_due_amount_1;
           if($receiv_amount_tds>0)
           {
          
           
           /****************     ***********/
           $query2_pay_tds="insert into payment_plan set userid_create = '".$_SESSION['userId']."',  trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$due_invoice_id_1."', debit = '".$receiv_amount_tds."', description = '(".$clear_tds_due_des_1_extra.") ".$clear_tdsdue_desc."', on_project = '".$project_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['clear_pay_payment_date_tds'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."', payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',trans_type = '".$trans_type_pay_tds."', trans_type_name = '".$trans_type_name_pay_tds."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."' ,create_date = '".getTime()."'";
           $result2_pay_tds= mysql_query($query2_pay_tds) or die('error in query '.mysql_error().$query2_pay_tds);
           $link_id_2_pay_tds_clear = mysql_insert_id();  
   
   
           $query3_clear="insert into tds_due_info set userid_create = '".$_SESSION['userId']."', pp_linkid_1 = '".$link_id_2_pay_tds_clear."',invoice_id = '".$due_invoice_id_1."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['clear_pay_payment_date_tds'])."',description = '(".$clear_tds_due_des_1_extra.") ".$clear_tdsdue_desc."',amount = '".$due_amount_pay_1."', received_amount = '".$receiv_amount_tds."',clear_due_flag=1 ,create_date = '".getTime()."'";
           $result3_clear= mysql_query($query3_clear) or die('error in query '.mysql_error().$query3_clear);
           $link_id_4_clear = mysql_insert_id();
           //clear_table_link_id           
              $query_clear_tds="insert into `clear_due_amount`  set pp_linkid_1 = '".$link_id_2_pay_tds_clear."',invoice_id  = '".$due_invoice_id_1."',description='".$clear_tdsdue_desc."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',due_amount = '".$receiv_amount_tds."', cust_id = '".$cust_id."', user_id = '".$_SESSION['userId']."',due_date = '".strtotime($_REQUEST['clear_pay_payment_date_tds'])."', type = 'TDS',create_time = '".getTime()."'";
              $result_clear_tds= mysql_query($query_clear_tds) or die('error in query '.mysql_error().$query_clear_tds);
              $link_id_2_clear_tds = mysql_insert_id();  
   
              $query_tds1_clear="update payment_plan set tds_flag = '1',clear_tds_flag='1' where invoice_id = '".$due_invoice_id_1."'";
              $result_tds1_clear= mysql_query($query_tds1_clear) or die('error in query '.mysql_error().$query_tds1_clear);
   
              $query_tds2_clear="update payment_plan set clear_table_link_id = '".$link_id_2_clear_tds."' where id = '".$link_id_2_pay_tds_clear."'";
              $result_tds2_clear= mysql_query($query_tds2_clear) or die('error in query '.mysql_error().$query_tds2_clear);
           
              $query_tds1="update payment_plan set tds_flag = '1',clear_tds_flag='1' ,tds_id='".$tds_id_gstpay."',tds_due_id='".$tds_due_id_gstpay."' where invoice_id = '".$due_invoice_id_1."'";
              $result_tds1= mysql_query($query_tds1) or die('error in query '.mysql_error().$query_tds1);
           }    
         
            
           }
  /*         else{

               $receiv_amount_tds=$due_amount_pay_1 - $tds_due_amount_1;

           if($receiv_amount_tds<1)
           {
               $tds_clear_flag=1;
         
           $query2_pay_tds="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$due_invoice_id_1."', debit = '".$receiv_amount_tds."', description = '(".$clear_tds_due_des_1_extra.") ".$clear_tdsdue_desc."', on_project = '".$project_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['clear_pay_payment_date_tds'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."', payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',trans_type = '".$trans_type_pay_tds."', trans_type_name = '".$trans_type_name_pay_tds."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."' ,create_date = '".getTime()."'";
           $result2_pay_tds= mysql_query($query2_pay_tds) or die('error in query '.mysql_error().$query2_pay_tds);
           $link_id_2_pay_tds_clear = mysql_insert_id();  
   
   
           $query3_clear="insert into tds_due_info set pp_linkid_1 = '".$link_id_2_pay_tds_clear."',invoice_id = '".$due_invoice_id_1."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['clear_pay_payment_date_tds'])."',description = '(".$clear_tds_due_des_1_extra.") ".$clear_tdsdue_desc."',amount = '".$due_amount_pay_1."', received_amount = '".$receiv_amount_tds."',clear_due_flag=1 ,create_date = '".getTime()."'";
           $result3_clear= mysql_query($query3_clear) or die('error in query '.mysql_error().$query3_clear);
           $link_id_4_clear = mysql_insert_id();
           
           $query_clear_tds="insert into `clear_due_amount`  set pp_linkid_1 = '".$link_id_2_pay_tds_clear."',invoice_id  = '".$due_invoice_id_1."',description='".$clear_tdsdue_desc."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',due_amount = '".$receiv_amount_tds."', cust_id = '".$cust_id."', user_id = '".$_SESSION['userId']."',due_date = '".strtotime($_REQUEST['clear_pay_payment_date_tds'])."', type = 'TDS',create_time = '".getTime()."'";
              $result_clear_tds= mysql_query($query_clear_tds) or die('error in query '.mysql_error().$query_clear_tds);
              $link_id_2_clear_tds = mysql_insert_id();  
   
              $query_tds1_clear="update payment_plan set tds_flag = '1',clear_tds_flag='1' where invoice_id = '".$due_invoice_id_1."'";
              $result_tds1_clear= mysql_query($query_tds1_clear) or die('error in query '.mysql_error().$query_tds1_clear);
   
              $query_tds2_clear="update payment_plan set clear_table_link_id = '".$link_id_2_clear_tds."' where id = '".$link_id_2_pay_tds_clear."'";
              $result_tds2_clear= mysql_query($query_tds2_clear) or die('error in query '.mysql_error().$query_tds2_clear);
           
              $query_tds1="update payment_plan set tds_flag = '1',clear_tds_flag='1' ,tds_id='".$tds_id_gstpay."',tds_due_id='".$tds_due_id_gstpay."' where invoice_id = '".$due_invoice_id_1."'";
              $result_tds1= mysql_query($query_tds1) or die('error in query '.mysql_error().$query_tds1);
              
              $tds_clear_flag=1;
           
           }else{
               $tds_clear_flag=0;
           }
           $query_tds1="update payment_plan set tds_flag = '1',clear_tds_flag='".$tds_clear_flag."' ,tds_id='".$tds_id_gstpay."',tds_due_id='".$tds_due_id_gstpay."' where invoice_id = '".$due_invoice_id_1."'";
           $result_tds1= mysql_query($query_tds1) or die('error in query '.mysql_error().$query_tds1);
           
           }
*/

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
          $clear_gst_due_des_1_extra = "GST Clear Amount for invoice : ".$due_invoice_id_1."";  
    
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
                    $query2_pay_gst="insert into payment_plan set userid_create = '".$_SESSION['userId']."', trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$due_invoice_id_1."', debit = '".$due_amount_pay_1."', description = '(".$clear_gst_due_des_1_extra.") ".$clear_gstdue_desc."', on_project = '".$project_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['clear_pay_payment_date_gst'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."', payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',trans_type = '".$trans_type_pay_gst."', trans_type_name = '".$trans_type_name_pay_gst."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."' ,create_date = '".getTime()."'";
                    $result2_pay_gst= mysql_query($query2_pay_gst) or die('error in query '.mysql_error().$query2_pay_gst);
                    $link_id_2_pay_gst_clear = mysql_insert_id();  
        
        
                    $query3_clear="insert into gst_due_info set userid_create = '".$_SESSION['userId']."',  pp_linkid_1 = '".$link_id_2_pay_gst_clear."',invoice_id = '".$due_invoice_id_1."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['clear_pay_payment_date_gst'])."',description = '(".$clear_gst_due_des_1_extra.") ".$clear_gstdue_desc."',amount = '".$receiv_amount_gst."', received_amount = '".$due_amount_pay_1."',clear_due_flag=1 ,create_date = '".getTime()."'";
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
                /*
                   $query_gst1="update payment_plan set gst_flag = '1',clear_gst_flag='1' ,gst_id='".$gst_id_gstpay."',gst_due_id='".$gst_due_id_gstpay."' where invoice_id = '".$due_invoice_id_1."'";
                   $result_gst1= mysql_query($query_gst1) or die('error in query '.mysql_error().$query_gst1);
                  */  
        
                }
                else{

                if($tds_due_flag_value_1=="1")
                {                  
        
                    $query3="insert into gst_due_info set userid_create = '".$_SESSION['userId']."', invoice_id = '".$due_invoice_id_1."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['gst_due_date'])."',description = '(".$tds_due_des_1_extra.") ".$tds_due_des_1."',amount = '".$due_amount_pay_1."', received_amount = '".$tds_due_amount_1."',create_date = '".getTime()."'";
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
                   
                            
            $query_pay_gst ="insert into payment_plan set userid_create = '".$_SESSION['userId']."', trans_id = '".$trans_id."', bank_id = '".$pay_bank_id."', credit = '".$tds_due_amount_1."',  description = '(".$tds_due_des_1_extra.") ".$tds_due_des_1."', on_customer = '".$cust_id."', invoice_id = '".$invoice_idnew."',payment_flag = '".$payment_flag."', payment_date = '".strtotime($_REQUEST['gst_due_date'])."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."',invoice_issuer_id = '".$invoice_issuer_id."' ,payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."', trans_type = '".$trans_type_pay_gst."', trans_type_name = '".$trans_type_name_pay_gst."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."',create_date = '".getTime()."'";
            $result_pay_gst= mysql_query($query_pay_gst) or die('error in query '.mysql_error().$query_pay_gst);


            $link_id_1_pay_gst = mysql_insert_id();


            $query2_pay_gst="insert into payment_plan set userid_create = '".$_SESSION['userId']."', trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$invoice_idnew."', debit = '".$tds_due_amount_1."', description = '(".$tds_due_des_1_extra.") ".$tds_due_des_1."', on_project = '".$project_id."', on_bank = '".$pay_bank_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['gst_due_date'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."', payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',link_id = '".$link_id_1_pay_gst."',trans_type = '".$trans_type_pay_gst."', trans_type_name = '".$trans_type_name_pay_gst."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."' ,create_date = '".getTime()."'";
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
            
            $receiv_amount_gst=$due_amount_pay_1 - $tds_due_amount_1;
          
            if($receiv_amount_gst>0)
            {
            
            $query2_pay_gst="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$due_invoice_id_1."', debit = '".$receiv_amount_gst."', description = '(".$clear_gst_due_des_1_extra.") ".$clear_gstdue_desc."', on_project = '".$project_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['clear_pay_payment_date_gst'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."', payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',trans_type = '".$trans_type_pay_gst."', trans_type_name = '".$trans_type_name_pay_gst."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."' ,userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
            $result2_pay_gst= mysql_query($query2_pay_gst) or die('error in query '.mysql_error().$query2_pay_gst);
            $link_id_2_pay_gst_clear = mysql_insert_id();  
    
    
            $query3_clear="insert into gst_due_info set pp_linkid_1 = '".$link_id_2_pay_gst_clear."',invoice_id = '".$due_invoice_id_1."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['clear_pay_payment_date_gst'])."',description = '(".$clear_gst_due_des_1_extra.") ".$clear_gstdue_desc."',amount = '".$due_amount_pay_1."', received_amount = '".$receiv_amount_gst."',clear_due_flag=1 ,userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
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
  /*          else{

                $receiv_amount_gst=$due_amount_pay_1 - $tds_due_amount_1;

            if($receiv_amount_gst<1)
            {
                $gst_clear_flag=1;
          
                $query2_pay_gst="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$due_invoice_id_1."', debit = '".$receiv_amount_gst."', description = '(".$clear_gst_due_des_1_extra.") ".$clear_gstdue_desc."', on_project = '".$project_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['clear_pay_payment_date_gst'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."', payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',trans_type = '".$trans_type_pay_gst."', trans_type_name = '".$trans_type_name_pay_gst."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."' ,create_date = '".getTime()."'";
                $result2_pay_gst= mysql_query($query2_pay_gst) or die('error in query '.mysql_error().$query2_pay_gst);
                $link_id_2_pay_gst_clear = mysql_insert_id();  
        
        
                $query3_clear="insert into gst_due_info set pp_linkid_1 = '".$link_id_2_pay_gst_clear."',invoice_id = '".$due_invoice_id_1."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['clear_pay_payment_date_gst'])."',description = '(".$clear_gst_due_des_1_extra.") ".$clear_gstdue_desc."',amount = '".$due_amount_pay_1."', received_amount = '".$receiv_amount_gst."',clear_due_flag=1 ,create_date = '".getTime()."'";
                $result3_clear= mysql_query($query3_clear) or die('error in query '.mysql_error().$query3_clear);
                $link_id_4_clear = mysql_insert_id();
             
                   $query_clear_gst="insert into `clear_due_amount`  set pp_linkid_1 = '".$link_id_2_pay_gst_clear."',invoice_id  = '".$due_invoice_id_1."',description='".$clear_gstdue_desc."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',due_amount = '".$receiv_amount_gst."', cust_id = '".$cust_id."', user_id = '".$_SESSION['userId']."',due_date = '".strtotime($_REQUEST['clear_pay_payment_date_gst'])."', type = 'GST',create_time = '".getTime()."'";
                   $result_clear_gst= mysql_query($query_clear_gst) or die('error in query '.mysql_error().$query_clear_gst);
                   $link_id_2_clear_gst = mysql_insert_id();  
        
                   $query_gst1_clear="update payment_plan set gst_flag = '1',clear_gst_flag='1' where invoice_id = '".$due_invoice_id_1."'";
                   $result_gst1_clear= mysql_query($query_gst1_clear) or die('error in query '.mysql_error().$query_gst1_clear);
        
                   $query_gst2_clear="update payment_plan set clear_table_link_id = '".$link_id_2_clear_gst."' where id = '".$link_id_2_pay_gst_clear."'";
                   $result_gst2_clear= mysql_query($query_gst2_clear) or die('error in query '.mysql_error().$query_gst2_clear);
                
                   $query_gst1="update payment_plan set gst_flag = '1',clear_gst_flag='1' ,gst_id='".$gst_id_gstpay."',gst_due_id='".$gst_due_id_gstpay."' where invoice_id = '".$due_invoice_id_1."'";
                   $result_gst1= mysql_query($query_gst1) or die('error in query '.mysql_error().$query_gst1);
                   
               
            }else{
                $gst_clear_flag=0;
            }
            $query_gst1="update payment_plan set gst_flag = '1',clear_gst_flag='".$gst_clear_flag."' ,gst_id='".$gst_id_gstpay."',gst_due_id='".$gst_due_id_gstpay."' where invoice_id = '".$due_invoice_id_1."'";
            $result_gst1= mysql_query($query_gst1) or die('error in query '.mysql_error().$query_gst1);
            
            }
*/
                      
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
          $clear_invoice_due_des_1_extra = "Clear Amount for invoice : ".$due_invoice_id_1."";  

       /* $multi_project_id = $row_pay['multi_project_id']; 
           $goods_detail_id =$row_pay['goods_detail_id']; 
           $link_id_1="0";
           $link_id_2=$due_payment_id_1;
       */
       if($clearall_due_flag_invoice=="2")
       {
        $query2_pay_invoice="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$invoice_idnew."', debit = '".$invoice_due_amount_new_total."', description = '(".$clear_invoice_due_des_1_extra.") ".$clear_invoicedue_desc."', on_project = '".$project_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['clear_pay_payment_date'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."', payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',trans_type = '".$trans_type_pay_invoice."', trans_type_name = '".$trans_type_name_pay_invoice."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."' ,userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
        $result2_pay_invoice= mysql_query($query2_pay_invoice) or die('error in query '.mysql_error().$query2_pay_invoice);
        $link_id_2_pay_invoice_clear = mysql_insert_id();  


        $query3_clear="insert into invoice_due_info set pp_linkid_1 = '".$link_id_2_pay_invoice_clear."',invoice_id = '".$invoice_idnew."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['clear_pay_payment_date'])."',description = '(".$clear_invoice_due_des_1_extra.") ".$clear_invoicedue_desc."',amount = '".$due_amount_pay_1."', received_amount = '".$invoice_due_amount_new_total."',clear_due_flag=1 ,userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
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
                   $query3="insert into invoice_due_info set invoice_id = '".$invoice_idnew."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['pay_payment_date'])."',description = '(".$invoice_due_des_1_extra.") ".$invoice_due_des_1."',amount = '".$due_amount_pay_1."', received_amount = '".$pay_amount_1."',userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
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
                  
                           
           $query_pay_invoice ="insert into payment_plan set trans_id = '".$trans_id."', bank_id = '".$pay_bank_id."', credit = '".$pay_amount_1."',  description = '(".$invoice_due_des_1_extra.") ".$invoice_due_des_1."', on_customer = '".$cust_id."', invoice_id = '".$invoice_idnew."',payment_flag = '".$payment_flag."', payment_date = '".strtotime($_REQUEST['pay_payment_date'])."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."',invoice_issuer_id = '".$invoice_issuer_id."' ,payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."', trans_type = '".$trans_type_pay_invoice."', trans_type_name = '".$trans_type_name_pay_invoice."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."',userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
           $result_pay_invoice= mysql_query($query_pay_invoice) or die('error in query '.mysql_error().$query_pay_invoice);


           $link_id_1_pay_invoice = mysql_insert_id();


           $query2_pay_invoice="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$invoice_idnew."', debit = '".$pay_amount_1."', description = '(".$invoice_due_des_1_extra.") ".$invoice_due_des_1."', on_project = '".$project_id."', on_bank = '".$pay_bank_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['pay_payment_date'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."', payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',link_id = '".$link_id_1_pay_invoice."',trans_type = '".$trans_type_pay_invoice."', trans_type_name = '".$trans_type_name_pay_invoice."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."' ,userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
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
           //$gst_received_flag=1;
           //amount = '".$due_amount_pay_1."', received_amount = '".$tds_due_amount_1."
       
           if($clear_invoice_due=="yes"){
            $receiv_amount_invoice=$due_amount_pay_1 - $pay_amount_1;
          if($receiv_amount_invoice>0)
          {
            $query2_pay_invoice_clear="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$invoice_idnew."', debit = '".$receiv_amount_invoice."', description = '(".$clear_invoice_due_des_1_extra.") ".$clear_invoicedue_desc."', on_project = '".$project_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['clear_pay_payment_date'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."', payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',trans_type = '".$trans_type_pay_invoice."', trans_type_name = '".$trans_type_name_pay_invoice."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."' ,userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
            $result2_pay_invoice_clear= mysql_query($query2_pay_invoice_clear) or die('error in query '.mysql_error().$query2_pay_invoice_clear);
            $link_id_2_pay_invoice_clear = mysql_insert_id();  


            $query3_clear="insert into invoice_due_info set pp_linkid_1 = '".$link_id_2_pay_invoice_clear."',invoice_id = '".$invoice_idnew."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['clear_pay_payment_date'])."',description = '(".$clear_invoice_due_des_1_extra.") ".$clear_invoicedue_desc."',amount = '".$due_amount_pay_1."', received_amount = '".$receiv_amount_invoice."',clear_due_flag=1 ,userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
            $result3_clear= mysql_query($query3_clear) or die('error in query '.mysql_error().$query3_clear);
            $link_id_4_clear = mysql_insert_id();
                  
           $query_clear_invoice="insert into `clear_due_amount`  set pp_linkid_1 = '".$link_id_2_pay_invoice_clear."',invoice_id  = '".$invoice_idnew."',description='".$clear_invoicedue_desc."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',due_amount = '".$receiv_amount_invoice."', cust_id = '".$cust_id."', user_id = '".$_SESSION['userId']."',due_date = '".strtotime($_REQUEST['clear_pay_payment_date'])."', type = 'Invoice',create_time = '".getTime()."'";
           $result_clear_invoice= mysql_query($query_clear_invoice) or die('error in query '.mysql_error().$query_clear_invoice);
           $link_id_2_clear_invoice = mysql_insert_id();  

           $query_invoice2_clear="update payment_plan set clear_table_link_id = '".$link_id_2_clear_invoice."' where id = '".$link_id_2_pay_invoice_clear."'";
           $result_invoice2_clear= mysql_query($query_invoice2_clear) or die('error in query '.mysql_error().$query_invoice2_clear);
        

           $query_invoice1="update payment_plan set invoice_flag = '1',clear_invoice_flag='1' ,invoice_pay_id='".$invoice_pay_id_gstpay."',invoice_due_pay_id='".$invoice_due_pay_id_gstpay."' where invoice_id = '".$invoice_idnew."'";
           $result_invoice1= mysql_query($query_invoice1) or die('error in query '.mysql_error().$query_invoice1);
        

           //amount = '".$due_amount_pay_1."', received_amount = '".$pay_amount_1."'
          
        /*   $query_invoice1_clear="update payment_plan set invoice_flag = '1',clear_invoice_flag='1' where invoice_id = '".$invoice_idnew."'";
           $result_invoice1_clear= mysql_query($query_invoice1_clear) or die('error in query '.mysql_error().$query_invoice1_clear);
*/
           

          }
           }
           /*
           else{

               $receiv_amount_invoice=$due_amount_pay_1 - $pay_amount_1;

           if($receiv_amount_invoice<1)
           {
               $invoice_clear_flag=1;
              
           $query2_pay_invoice_clear="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$invoice_idnew."', debit = '".$receiv_amount_invoice."', description = '(".$clear_invoice_due_des_1_extra.") ".$invoice_due_des_1."', on_project = '".$project_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['pay_payment_date'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_due_pay_id = '".$invoice_due_pay_id_gstpay."', payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',trans_type = '".$trans_type_pay_invoice."', trans_type_name = '".$trans_type_name_pay_invoice."',hsn_code= '".$hsn_code_gstpay."',multi_invoice_flag= '".$multi_invoice_flag_gstpay."',multi_invoice_detail= '".$multi_invoice_detail_gstpay."',multi_invoice_id= '".$multi_invoice_id_gstpay."',invoice_pay_id= '".$invoice_pay_id_gstpay."',gst_id= '".$gst_id_gstpay."',tds_id = '".$tds_id_gstpay."',gst_due_id = '".$gst_due_id_gstpay."',tds_due_id = '".$tds_due_id_gstpay."',tds_flag = '".$tds_flag_gstpay."',gst_flag = '".$gst_flag_gstpay."',invoice_flag = '".$invoice_flag_gstpay."',clear_invoice_flag = '".$clear_invoice_flag_gstpay."',clear_gst_flag = '".$clear_gst_flag_gstpay."',clear_tds_flag = '".$clear_tds_flag_gstpay."' ,create_date = '".getTime()."'";
           $result2_pay_invoice_clear= mysql_query($query2_pay_invoice_clear) or die('error in query '.mysql_error().$query2_pay_invoice_clear);
           $link_id_2_pay_invoice_clear = mysql_insert_id();  
   
   
           $query3_clear="insert into invoice_due_info set pp_linkid_1 = '".$link_id_2_pay_invoice_clear."',invoice_id = '".$invoice_idnew."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['pay_payment_date'])."',description = '(".$clear_invoice_due_des_1_extra.") ".$invoice_due_des_1."',amount = '".$due_amount_pay_1."', received_amount = '".$receiv_amount_invoice."',clear_due_flag=1 ,create_date = '".getTime()."'";
           $result3_clear= mysql_query($query3_clear) or die('error in query '.mysql_error().$query3_clear);
           $link_id_4_clear = mysql_insert_id();
                     
              $query_clear_invoice="insert into `clear_due_amount`  set pp_linkid_1 = '".$link_id_2_pay_invoice_clear."',invoice_id  = '".$invoice_idnew."',description='".$invoice_due_des_1."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',due_amount = '".$receiv_amount_invoice."', cust_id = '".$cust_id."', user_id = '".$_SESSION['userId']."',due_date = '".strtotime($_REQUEST['pay_payment_date'])."', type = 'Invoice',create_time = '".getTime()."'";
              $result_clear_invoice= mysql_query($query_clear_invoice) or die('error in query '.mysql_error().$query_clear_invoice);
              $link_id_2_clear_invoice = mysql_insert_id();  
   
              $query_invoice2_clear="update payment_plan set clear_table_link_id = '".$link_id_2_clear_invoice."' where id = '".$link_id_2_pay_invoice_clear."'";
              $result_invoice2_clear= mysql_query($query_invoice2_clear) or die('error in query '.mysql_error().$query_invoice2_clear);
           
            }else{
               $invoice_clear_flag=0;
           }
            $query_invoice1="update payment_plan set invoice_flag = '1',clear_invoice_flag='".$invoice_clear_flag."' ,invoice_pay_id='".$invoice_pay_id_gstpay."',invoice_due_pay_id='".$invoice_due_pay_id_gstpay."' where invoice_id = '".$due_invoice_id_1."'";
           $result_invoice1= mysql_query($query_invoice1) or die('error in query '.mysql_error().$query_invoice1);
              
        }
       */
           }


       }

    
}

/* ---------------   INVOICE DUE WORK END   -----------------------------*/

/*  --------      COMBIND PAYMENT WORK START        -----------------------  */
//pay_flag, invoice_cerf,pay_payment_date,invoice_due_attach_file,pay_form,pay_method,pay_checkno,pay_amount
        //invoice_due_flag_value,invoice_payment_id,invoice_attach_file_id,invoice_flag,invoice_trans_id,invoice_invoice_id,invoice_amount_pay,file_button_invoice
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
    // combind_payment_amount_new_total , combind_payment_pay_form , combind_payment_due_date , combind_payment_due_amount,combind_payment_due_attach_file ,combind_payment_due_des
   
   // $due_trans_id_1=mysql_real_escape_string(trim($_REQUEST['invoice_trans_id']));
 //  $due_payment_id_1=mysql_real_escape_string(trim($_REQUEST['invoice_payment_id']));
  // $due_invoice_id_1=mysql_real_escape_string(trim($_REQUEST['invoice_invoice_id']));
   $combind_due_des_1=mysql_real_escape_string(trim($_REQUEST['combind_payment_due_des']));
   $pay_amount_1=mysql_real_escape_string(trim($_REQUEST['combind_payment_due_amount']));
   $pay_form=mysql_real_escape_string(trim($_REQUEST['combind_payment_pay_form']));
   $invoice_due_date_1_n=strtotime($_REQUEST['combind_payment_due_date']);
   $combind_cust_id_1=mysql_real_escape_string(trim($_REQUEST['combind_cust_id']));
   
   
   $pay_from_arr = explode(" -",$_REQUEST['combind_payment_pay_form']);
   $pay_bank_id = get_field_value("id","bank","bank_account_name",$pay_from_arr[0]);
   $trans_type_pay_combind = 51;
   $trans_type_name_pay_combind= "combind_payment" ;
  
   
   $query_pay_invoice ="insert into payment_plan set  trans_id = '".$trans_id."', bank_id = '".$pay_bank_id."', credit = '".$pay_amount_1."',  description = '".$combind_due_des_1."', on_customer = '".$combind_cust_id_1."',payment_flag = '".$payment_flag."', payment_date = '".strtotime($_REQUEST['combind_payment_due_date'])."',payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."', trans_type = '".$trans_type_pay_combind."', trans_type_name = '".$trans_type_name_pay_combind."',userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
   $result_pay_invoice= mysql_query($query_pay_invoice) or die('error in query '.mysql_error().$query_pay_invoice);
   $link_id_1_pay_invoice = mysql_insert_id();


   $query2_pay_invoice="insert into payment_plan set  trans_id = '".$trans_id."',cust_id = '".$combind_cust_id_1."',debit = '".$pay_amount_1."', description = '".$combind_due_des_1."',  on_bank = '".$pay_bank_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['combind_payment_due_date'])."' ,payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."',link3_id = '".$due_payment_id_1."',link_id = '".$link_id_1_pay_invoice."',trans_type = '".$trans_type_pay_combind."', trans_type_name = '".$trans_type_name_pay_combind."' ,userid_create = '".$_SESSION['userId']."' ,create_date = '".getTime()."'";
   $result2_pay_invoice= mysql_query($query2_pay_invoice) or die('error in query '.mysql_error().$query2_pay_invoice);
   $link_id_2_pay_invoice = mysql_insert_id();  
   //combind_gst_invoiceid ,combind_gst_amount , combind_gst_payment_planid ,combind_gst_type
        //combind_invoice_count,combind_tds_count,combind_gst_count
       // echo mysql_real_escape_string(trim($_REQUEST['combind_gst_count']));
       // echo mysql_real_escape_string(trim($_REQUEST['combind_tds_count']));
       // echo mysql_real_escape_string(trim($_REQUEST['combind_invoice_count']));
           $tot_final_pay="0"; 
        for($im=1;$im<mysql_real_escape_string(trim($_REQUEST['combind_gst_count']));$im++)  
        {
            $combind_gst_invoiceid=mysql_real_escape_string(trim($_REQUEST['combind_gst_invoiceid'.$im]));
            $combind_gst_amount=mysql_real_escape_string(trim($_REQUEST['combind_gst_amount'.$im]));
            $combind_gst_amount_pay=mysql_real_escape_string(trim($_REQUEST['combind_gst_amount_pay'.$im]));
            $combind_gst_payment_planid=mysql_real_escape_string(trim($_REQUEST['combind_gst_payment_planid'.$im]));
            $combind_gst_type=mysql_real_escape_string(trim($_REQUEST['combind_gst_type'.$im]));
               $due_trans_id_1=""; 
            $query3="insert into gst_due_info set combind_payment='1', invoice_id = '".$combind_gst_invoiceid."',payment_plan_id = '".$combind_gst_payment_planid."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['combind_payment_due_date'])."',combine_description = '(Combined GST Pay For Invoice".$combind_gst_invoiceid.") ',description='".$combind_due_des_1."',amount = '".$combind_gst_amount."', received_amount = '".$combind_gst_amount_pay."',pp_linkid_1 = '".$link_id_2_pay_invoice."',pp_linkid_2 = '".$link_id_1_pay_invoice."',userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
            $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
            $link_id_4 = mysql_insert_id();
            
            $tot_final_pay=$tot_final_pay+$combind_gst_amount_pay;
        }
        for($im=1;$im<mysql_real_escape_string(trim($_REQUEST['combind_tds_count']));$im++)  
        {
            $combind_tds_invoiceid=mysql_real_escape_string(trim($_REQUEST['combind_tds_invoiceid'.$im]));
            $combind_tds_amount=mysql_real_escape_string(trim($_REQUEST['combind_tds_amount'.$im]));
            $combind_tds_amount_pay=mysql_real_escape_string(trim($_REQUEST['combind_tds_amount_pay'.$im]));
            $combind_tds_payment_planid=mysql_real_escape_string(trim($_REQUEST['combind_tds_payment_planid'.$im]));
            $combind_tds_type=mysql_real_escape_string(trim($_REQUEST['combind_tds_type'.$im]));
               $due_trans_id_1=""; 
            $query3="insert into tds_due_info set combind_payment='1', invoice_id = '".$combind_tds_invoiceid."',payment_plan_id = '".$combind_tds_payment_planid."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['combind_payment_due_date'])."',combine_description = '(Combined TDS Pay For Invoice".$combind_gst_invoiceid.") ',description='".$combind_due_des_1."',amount = '".$combind_tds_amount."', received_amount = '".$combind_tds_amount_pay."',pp_linkid_1 = '".$link_id_2_pay_invoice."',pp_linkid_2 = '".$link_id_1_pay_invoice."',userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
            $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
            $link_id_4 = mysql_insert_id();
            $tot_final_pay=$tot_final_pay+$combind_tds_amount_pay;
           
        }
        for($im=1;$im<mysql_real_escape_string(trim($_REQUEST['combind_invoice_count']));$im++)  
        {
            $combind_invoice_invoiceid=mysql_real_escape_string(trim($_REQUEST['combind_invoice_invoiceid'.$im]));
            $combind_invoice_amount=mysql_real_escape_string(trim($_REQUEST['combind_invoice_amount'.$im]));
            $combind_invoice_amount_pay=mysql_real_escape_string(trim($_REQUEST['combind_invoice_amount_pay'.$im]));
            $combind_invoice_payment_planid=mysql_real_escape_string(trim($_REQUEST['combind_invoice_payment_planid'.$im]));
            $combind_invoice_type=mysql_real_escape_string(trim($_REQUEST['combind_invoice_type'.$im]));
               $due_trans_id_1=""; 
            $query3="insert into invoice_due_info  set combind_payment='1', invoice_id = '".$combind_invoice_invoiceid."',payment_plan_id = '".$combind_invoice_payment_planid."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['combind_payment_due_date'])."',combine_description = '(Combined Invoice Pay For Invoice".$combind_invoice_invoiceid.") ',description='".$combind_due_des_1."',amount = '".$combind_invoice_amount."', received_amount = '".$combind_invoice_amount_pay."',pp_linkid_1 = '".$link_id_2_pay_invoice."',pp_linkid_2 = '".$link_id_1_pay_invoice."',userid_create = '".$_SESSION['userId']."',create_date = '".getTime()."'";
            $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
            $link_id_4 = mysql_insert_id();
           
            $tot_final_pay=$tot_final_pay+$combind_invoice_amount_pay;
        }
        
        $query_invoice_update="update payment_plan set  credit = '".$tot_final_pay."'  where id = '".$link_id_1_pay_invoice."'";
        $result5_invoice_update= mysql_query($query_invoice_update) or die('error in query '.mysql_error().$query_invoice_update);

        
        $query_invoice_update_1="update payment_plan set  debit = '".$tot_final_pay."' where id = '".$link_id_2_pay_invoice."'";
        $result5_invoice_update_1= mysql_query($query_invoice_update_1) or die('error in query '.mysql_error().$query_invoice_update_1);

   /*     

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
          $clear_invoice_due_des_1_extra = "Clear Amount for invoice : ".$due_invoice_id_1."";  

      */

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
    
            $select_query_tdsgst = "select SUM(invoice_pay_amount) as total_invoice_pay_amount,SUM(tds_amount) as total_tds_amount ,SUM(gst_amount) as total_gst_amount from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and payment_plan.trans_type_name='instmulti_sale_goods'  and payment_plan.credit > 0 and payment_plan.credit!='null' and payment_plan.payment_date <= '".$to_date."' and payment_plan.payment_date > '1648665000' ";
            $select_result_tdsgst = mysql_query($select_query_tdsgst) or die('error in query select cash query '.mysql_error().$select_query_tdsgst);
            $select_data_tdsgst = mysql_fetch_array($select_result_tdsgst);

            $total_invoice_pay_amount = $select_data_tdsgst['total_invoice_pay_amount'];
            $total_gst_amount = $select_data_tdsgst['total_gst_amount'];
            $total_tds_amount = $select_data_tdsgst['total_tds_amount'];

            $select_query_invoice_rec = "select SUM(received_amount) as invoice_received_amount from invoice_due_info inner join  payment_plan on invoice_due_info.payment_plan_id=payment_plan.id inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and payment_plan.trans_type_name='instmulti_sale_goods'  and payment_plan.credit > 0 and payment_plan.credit!='null' and payment_plan.payment_date <= '".$to_date."' and payment_plan.payment_date > '1648665000' ";
            $select_result_invoice_rec = mysql_query($select_query_invoice_rec) or die('error in query select cash query '.mysql_error().$select_query_invoice_rec);
            $select_data_invoice_rec = mysql_fetch_array($select_result_invoice_rec);
            $invoice_tot_due=$total_invoice_pay_amount-$select_data_invoice_rec['invoice_received_amount'];
           
            $select_query_gst_rec = "select SUM(received_amount) as gst_received_amount from gst_due_info inner join  payment_plan on gst_due_info.payment_plan_id=payment_plan.id inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and payment_plan.trans_type_name='instmulti_sale_goods'  and payment_plan.credit > 0 and payment_plan.credit!='null' and payment_plan.payment_date <= '".$to_date."' and payment_plan.payment_date > '1648665000' ";
            $select_result_gst_rec = mysql_query($select_query_gst_rec) or die('error in query select cash query '.mysql_error().$select_query_gst_rec);
            $select_data_gst_rec = mysql_fetch_array($select_result_gst_rec);
            $gst_tot_due=$total_gst_amount-$select_data_gst_rec['gst_received_amount'];
           
            $select_query_tds_rec = "select SUM(received_amount) as tds_received_amount from tds_due_info inner join  payment_plan on tds_due_info.payment_plan_id=payment_plan.id inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and payment_plan.trans_type_name='instmulti_sale_goods'  and payment_plan.credit > 0 and payment_plan.credit!='null'and payment_plan.payment_date <= '".$to_date."' and payment_plan.payment_date > '1648665000' ";
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
    
            $select_query_tdsgst = "select SUM(invoice_pay_amount) as total_invoice_pay_amount,SUM(tds_amount) as total_tds_amount ,SUM(gst_amount) as total_gst_amount from payment_plan inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and payment_plan.trans_type_name='instmulti_sale_goods'  and payment_plan.credit > 0 and payment_plan.credit!='null' and payment_plan.payment_date > '1648665000' ";
            $select_result_tdsgst = mysql_query($select_query_tdsgst) or die('error in query select cash query '.mysql_error().$select_query_tdsgst);
            $select_data_tdsgst = mysql_fetch_array($select_result_tdsgst);

            $total_invoice_pay_amount = $select_data_tdsgst['total_invoice_pay_amount'];
            $total_gst_amount = $select_data_tdsgst['total_gst_amount'];
            $total_tds_amount = $select_data_tdsgst['total_tds_amount'];
            
            $select_query_invoice_rec = "select SUM(received_amount) as invoice_received_amount from invoice_due_info inner join  payment_plan on invoice_due_info.payment_plan_id=payment_plan.id inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and payment_plan.trans_type_name='instmulti_sale_goods'  and payment_plan.credit > 0 and payment_plan.credit!='null' and payment_plan.payment_date > '1648665000' ";
            $select_result_invoice_rec = mysql_query($select_query_invoice_rec) or die('error in query select cash query '.mysql_error().$select_query_invoice_rec);
            $select_data_invoice_rec = mysql_fetch_array($select_result_invoice_rec);
            $invoice_tot_due=$total_invoice_pay_amount-$select_data_invoice_rec['invoice_received_amount'];
           
            $select_query_gst_rec = "select SUM(received_amount) as gst_received_amount from gst_due_info  inner join  payment_plan on gst_due_info.payment_plan_id=payment_plan.id inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."'  and customer.type = 'customer'  and payment_plan.trans_type_name='instmulti_sale_goods'  and payment_plan.credit > 0 and payment_plan.credit!='null' and payment_plan.payment_date > '1648665000'  ";
            $select_result_gst_rec = mysql_query($select_query_gst_rec) or die('error in query select cash query '.mysql_error().$select_query_gst_rec);
            $select_data_gst_rec = mysql_fetch_array($select_result_gst_rec);
            $gst_tot_due=$total_gst_amount-$select_data_gst_rec['gst_received_amount'];
           
            $select_query_tds_rec = "select SUM(received_amount) as tds_received_amount from tds_due_info inner join  payment_plan on tds_due_info.payment_plan_id=payment_plan.id inner join customer on payment_plan.cust_id = customer.cust_id and customer.cust_id = '".$_REQUEST['cust_id']."' and customer.type = 'customer' and payment_plan.trans_type_name='instmulti_sale_goods'  and payment_plan.credit > 0 and payment_plan.credit!='null' and payment_plan.payment_date > '1648665000' and tds_due_info.received_amount > 0 ";
            $select_result_tds_rec = mysql_query($select_query_tds_rec) or die('error in query select cash query '.mysql_error().$select_query_tds_rec);
            $select_data_tds_rec = mysql_fetch_array($select_result_tds_rec);
            $tds_tot_due=$total_tds_amount-$select_data_tds_rec['tds_received_amount'];
           
            }
    


?>
<!DOCTYPE html>
<?php include_once ("top_header1.php"); ?>   
  <script src="js/datetimepicker_css.js"></script>
<script src="js/jquery-1.12.4.min.js"></script>

     
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
                           <td valign="top" >Paid Into</td>
                           <td>
                            <input type="text" id="combind_payment_pay_form"  name="combind_payment_pay_form" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span> 
                       </td>
                       </tr>
                        <tr>    <td valign="top"  width="180px" >Date</td>
                           <td><input type="text"  name="combind_payment_due_date" id="combind_payment_due_date" value="<?php //echo $_REQUEST['tds_due_date']; ?>" style="width:250px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('combind_payment_due_date')" style="cursor:pointer"/></td>
                       </tr>
                       <tr>
                           <td valign="top" colspan="2" >Amount Received</td>
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

                         <!--- COMBIND PAYMENT Close Div   -->
       
  <?php include_once ("top_header2.php"); ?> 
  <?php include_once ("top_menu.php"); ?>
  <?php include_once("main_heading_open.php") ?>
  
	<table style="width:100%;" border="0" style="padding:0px 0px; margin-top:-10px;">
    <tr>
        <td style="align:top;" align="left">
        <h4 class="u-text-2 u-text-palette-1-base " style="padding:0px; margin:0px;">
     Customer - <?php echo get_field_value("full_name","customer","cust_id",$_REQUEST['cust_id']); ?> Ledger </h4>
  </td>
        <td width="" style="float:right;">
        <a href="javascript:void(0);"   title="combind Payment" onClick="return combind_payment_function();" ><input type="button" name="com_button" id="com_button" value="Combine Payment" class="button_normal"  /> </a>
                        
        <a href="customer.php" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
                    <input type="button" name="print_button" id="print_button" value="" class="button_print" onClick="return print_data();"  />
<script src="dist/jquery.table2excel.min.js"></script>

<input type="button" id="export_to_excel" value="" class="button_export" >
                      <input type="button" id="search" value="" class="button_search1" onClick="search_display();" >
                      <input type="button" id="view" value="Profile" class="button_normal" onClick="profile_display();" >
                       
        </td>
    </tr>
</table>
<input type="hidden" id="print_header" name="print_header" value="<h3>Customer - <?php echo get_field_value("full_name","customer","cust_id",$_REQUEST['cust_id']); ?> Ledger</h3>">   

<?php include_once("main_heading_close.php") ?>

<?php include_once("main_search_open.php") ?>
  
  <form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" >
  <input type="hidden" name="search_check_val" id="search_check_val" value="<?php echo $sear_val_f; ?>" >
  <input type="hidden" name="search_check_val_1" id="search_check_val_1" value="<?php echo $sear_val_f; ?>" >
  
<link rel="stylesheet" href="css/jquery-ui.css" />

             <script src="js/jquery-ui.js"></script>    
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="50">
                    &nbsp;&nbsp;From
                    </td>
                    <td width="150">
                    <input type="text"  name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>" style="width:100px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('from_date')" style="cursor:pointer"/>
                    </td>
                
                 <td width="50">
                    &nbsp;&nbsp;To
                    </td>
                    <td width="150">
                    <input type="text"  name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>" style="width:100px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('to_date')" style="cursor:pointer"/>
                 </td>
                 
                    <td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Reset" class="button" onClick="window.location='customer-ledger.php?cust_id=<?php echo $_REQUEST['cust_id']; ?>';"  /></td>
                    <td align="right" valign="top" >
                    
                    </td>
                    
                    
                </tr>
            </table>
            <input type="hidden" name="search_action" id="search_action" value="Search"  />
            
            </form>
  
  <?php include_once("main_search_close.php") ?>
 <!-- Profile Start -->
 <div class="u-layout-row" id="profile_div_1" name="profile_div_1" style="display:none;">
          <div class="u-align-left u-container-style u-layout-cell u-size-60 u-layout-cell-1" style="padding-right:0px;margin-bottom:0px; margin-top:px;">
            <div class="u-expanded-width u-layout-grid u-list u-list-1" style=""  >
              <div class="u-align-left u-container-style u-list-item u-repeater-item u-shape-rectangle u-white u-list-item-1" style="padding:10px;margin-top:-5px;margin-bottom:-35px;"  >
              <input type="hidden" name="profile_check_val" id="profile_check_val" value="0" >
              
              <table width="100%">
                <tr><td>
                    <?php 
                    
$select_query_customer = "select * from customer where cust_id = '".$_REQUEST['cust_id']."'";
$select_result_customer = mysql_query($select_query_customer) or die('error in query select customer query '.mysql_error().$select_query_customer);
$select_data_customer = mysql_fetch_array($select_result_customer)

                    ?>
                <table width="100%" class="">
                        
                <tr><td colspan="4"><b><u>Tenant Infomation Details :</u></b></td></tr>
                        <tr><td width="200px">Start Date Of First Lease/Rent Agreement</td>
            <td width="300px"><?php if($select_data_customer['tenant_first_rent_agree_date']>"978287400") {echo date("d-m-Y",$select_data_customer['tenant_first_rent_agree_date']);} ?></td>
            
            <td width="200px">Start Date Of Current Lease/Rent Agreement</td>
            <td><?php if($select_data_customer['tenant_current_rent_agree_date']>"978287400") { echo date("d-m-Y",$select_data_customer['tenant_current_rent_agree_date']);} ?></td></tr>
                  
            <tr><td width="200px">Current Rent</td>
            <td><?php echo $select_data_customer['tenant_current_rent']; ?> </td>
                    
            <td width="200px">Next Renewal due Date</td>
            <td><?php if($select_data_customer['tenant_nextrenawal_duedate']>"978287400") { echo date("d-m-Y",$select_data_customer['tenant_nextrenawal_duedate']); }?></td></tr>
                    
            <tr><td width="200px">Next Renewal Rent</td>
            <td><?php echo $select_data_customer['tenant_nextrenewal_rent']; ?></td>
                    
            <td width="200px">Registered / Unregistered</td>
            <td><?php echo $select_data_customer['tenant_registered'];?>
        </td></tr>
                    
        </table>
                </td></tr>
</table>
              </div>
            </div>
          </div>
        </div>


 <!-- Profile End -->
<?php include_once("main_body_open.php") ?>
  
     
     
<div id="ledger_data" style="height: 400px; width:98%; overflow-y: scroll;">
        <table class="data" id="my_table" border="1" cellpadding="1" cellspacing="0" width="100%" style="border: 1px solid #111111;">
        <tr style="display:none ;">
            <td><b>Customer Ledger :</b></td><td><b><?php echo get_field_value("full_name","customer","cust_id",$_REQUEST['cust_id']); ?></b></td>
            <td><b> Generated On :</b></td><td><b><?php echo getTime(); echo "("; $username_1=get_field_value("full_name","user","userid",$_SESSION['userId']); echo $username_1; echo ")";?></b></td>

        </tr>    
        <tr style="display:none ;">
        <td><b>Period Start :</b></td><td><b><?php if($_REQUEST['from_date']!=""){ echo date("d-m-Y",strtotime($_REQUEST['from_date']));  }?></b></td>
            <td><b>Period End :</b></td><td><b><?php if($_REQUEST['to_date']!=""){ echo date("d-m-Y",strtotime($_REQUEST['to_date'])); } ?></b></td>
            
        </tr>   
		
        <?php $colm=1; ?>
            <tr >
            <thead class="report-header">
                <th class="data" width="20px" style="width=20px; position: sticky; top: 0;">S.</br>No.</th>
                <th class="data" width="60px" style="width=60px; position: sticky; top: 0;">Date</th>
                <th class="data" width="80px" style="width=60px; position: sticky; top: 0;">To&nbsp; / From</th>                
                <th class="data" width="80px" style="width=60px; position: sticky; top: 0;">Project</th>
              
                <th class="data" style=" position: sticky; top: 0;">Description</th>
                <th class="data" width="50px" style="width=50px; position: sticky; top: 0;">TDS Due</th>
                <th class="data" width="50px" style="width=50px; position: sticky; top: 0;">GST Due</th>
                <th class="data" width="50px" style="width=50px; position: sticky; top: 0; font-size:8px;">Invoice Due</br>after TDS</th>
                <th class="data" width="50" style="width=50px; position: sticky; top: 0;">Debit</th>
                <th class="data" width="50" style="width=50px; position: sticky; top: 0;">Credit</th>
                <th class="data" width="70" style="width=70px; position: sticky; top: 0;">Balance </th>
                <th class="data noExl" width="50" id="header1" style="width=50px; position: sticky; top: 0;">File</th>
                </thead>
            </tr>
            <?php
            $ictds=1;
            $icinvoice=1;
            $icgst=1;
            if($select_total > 0)
            {
                $i = 1;
                
                $im=1;
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
                    
                   
                    if($im==1)
                    {
                        //$bal_1=$select_data3_1['total_credit']-$select_data3_1['total_debit'];
                         if($search_start=="1")
                         {
                             $opening_tds_tot_due = $tds_tot_due;
                             $opening_gst_tot_due = $gst_tot_due ;
                             $opening_invoice_tot_due = $invoice_tot_due;
                             
                             ?>
                        <tr class="data ">
                        <td class="data data_fixed top_40 bg_w" width="20px"><?php //echo $i; ?></td>
                        <td class="data data_fixed top_40 bg_w"><b> <?php echo date("d-m-y",$to_date); ?></b></td>
                        <td class="data data_fixed top_40 bg_w"></td>
                        <td class="data data_fixed top_40 bg_w"></td>
                        
                        <td class="data data_fixed top_40 bg_w"><b><?php echo "Closing Balance"; ?></b></td>
                        <td class="data data_fixed top_40 bg_w"  width="50px"><b><?php //echo $total_tds_amount."&nbsp;"; 
                        echo $tds_tot_due; ?></b></td>
                        <td class="data data_fixed top_40 bg_w" width="50px"><b><?php echo $total_gst_amount."&nbsp;"; 
                        echo $gst_tot_due; ?></b></td>
                        <td class="data data_fixed top_40 bg_w" width="50px"><b><?php //echo $total_invoice_pay_amount."&nbsp;";  
                        echo $invoice_tot_due;  ?></b></td>

                        <td class="data data_fixed top_40 bg_w"><b><?php echo number_format($select_data3_1['total_debit'],2,'.',''); ?> </b></td>
                        <td class="data data_fixed top_40 bg_w"><b><?php echo number_format($select_data3_1['total_credit'],2,'.',''); ?></b></td>
                        <td class="data data_fixed top_40 bg_w" <?php if($bal_1<0 ) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?> ><b><?php 
                        //echo currency_symbol().
                        echo number_format($bal_1,2,'.',''); ?></b></td>
                        <td class="data data_fixed top_40 bg_w" nowrap="nowrap"></td>                        
                    </tr>
                             <?php
                         }
                         else{
                             ?>
                        <tr class="data">
                        <td class="data data_fixed top_40 bg_w" ><?php //echo $i; ?></td>
                        <td class="data data_fixed top_40 bg_w"><b> <?php echo date("d-m-y",$select_data['payment_date']); ?></b></td>
                        <td class="data data_fixed top_40 bg_w"></td>
                        <td class="data data_fixed top_40 bg_w"></td>
                        
                        <td class="data data_fixed top_40 bg_w"><b><?php echo "Closing Balance"; ?></b></td>
                        <td class="data data_fixed top_40 bg_w"><b><?php //echo $total_tds_amount."&nbsp;"; 
                        echo number_format($tds_tot_due,2,'.','');
                        //echo $tds_tot_due; ?></b></td>
                        <td class="data data_fixed top_40 bg_w"><b><?php //echo $total_gst_amount."&nbsp;"; 
                        echo number_format($gst_tot_due,2,'.','');
                        //echo $gst_tot_due; ?></b></td>
                        <td class="data data_fixed top_40 bg_w" ><b><?php //echo $total_invoice_pay_amount."&nbsp;";
                         echo number_format($invoice_tot_due,2,'.','');
                       // echo $invoice_tot_due; ?></b></td>
                        <td class="data data_fixed top_40 bg_w"><b><?php echo number_format($select_data3['total_debit'],2,'.',''); ?> </b></td>
                        <td class="data data_fixed top_40 bg_w"><b><?php echo number_format($select_data3['total_credit'],2,'.',''); ?></b></td>
                        <td class="data data_fixed top_40 bg_w" <?php if($bal<0 ) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?>><b><?php 
                        //echo currency_symbol().
                        echo number_format($bal,2,'.',''); ?></b>
                        </td>
                        <td class="data data_fixed top_40 bg_w" nowrap="nowrap"></td>                        
                    </tr>
                 <?php   }   ?>
                 <?php   }  
                 $im++;
                 ?>
                 <?php if($select_data['clear_table_link_id']>'1' && $select_data['debit']<'1')
                        {}
                        else{
                 ?>
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
                        <td class="data"><?php echo $select_data['description']; ?></td>
                        <td id="" align="center" class="data" valign="top"  ><?php 
                        if($select_data['credit'] > 0)
                        {
                            $pay_date = $select_data['payment_date'];
                           // echo $pay_date;
                            if($pay_date > '1648665000'){
                        if($select_data['tds_amount']!=0)
                        { 
                            
                            ?>
                        <!-- // <?php echo $select_data['trans_id']; ?> // <?php echo $select_data['payment_id']; ?> // <?php echo $select_data['invoice_id']; ?>  -->
                            <!-- <a href="javascript:void(0);" title="View File" onClick="return view_file_function('<?php echo $select_data['invoice_id']; ?>');" >-->
                            
                        
                        <?php
                            $tds_due_query1 = "select SUM(amount) as amount,SUM(received_amount) as received_amount  from tds_due_info where payment_plan_id = '".$select_data['payment_id']."' and invoice_id = '".$select_data['invoice_id']."' and received_amount > 0 ";
                            $tds_due_result2 = mysql_query($tds_due_query1) or die("error in date list query ".mysql_error());
                            $total_tds2 = mysql_num_rows($tds_due_result2);
                           // echo $tds_due_query1;
                           $tds_due_query1_yescheck = "select *  from tds_due_info where payment_plan_id = '".$select_data['payment_id']."' and invoice_id = '".$select_data['invoice_id']."' and received_amount > 0 ";
                            $tds_due_result2_yescheck = mysql_query($tds_due_query1_yescheck) or die("error in date list query ".mysql_error());
                            $total_tds2_yescheck = mysql_num_rows($tds_due_result2_yescheck);
                            //echo $total_tds2_yescheck;
                            if($total_tds2_yescheck > 0)
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
                           /* if($select_data['tds_flag']=="1")*/
                           if($total_tds2_yescheck > 0)
                            {
                                while( $find_tds_date = mysql_fetch_array($tds_due_result2_yescheck))
                                {
                                    if($new_date_tds==""){
                                        $new_date_tds = date("d-m-y",$find_tds_date['due_date']);
                                    }
                                    else{
                                        $new_date_tds = $new_date_tds.",".date("d-m-y",$find_tds_date['due_date']);
                                    }
                                }
                               
                                ?>
                            
                            <a href="invoice-ledger.php?invoice_id=<?php echo $select_data['invoice_id']; ?>&payment_id=<?php echo $select_data['payment_id']; ?>" title="View Ledger"  >             
                            <img src="mos-css/img/active.png" title="<?php 
                            $d_date= get_field_value("due_date","tds_due_info","id",$select_data['tds_id']); 
                            echo "Date : &nbsp;".$new_date_tds;
                            echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                            $d_amount= $find_tds['received_amount']; 
                            echo "TDS Amount : &nbsp;".$d_amount;
                                ?>" ></a>
                            <?php 

                            }
                           /* if($select_data['clear_tds_flag']=="0" )*/
                            
                            if($due_tds_final>="1" )
                            
                            {  
                                 //combine_invoice_checke_allow , combine_invoice_check , combine_invoice_check_flag ,combine_tds_amount ,combine_tds_invoicen_id ,combine_tds_type ,combine_tds_paymentplan_id

                                ?>
                                <input type="hidden" name="combine_tds_check_flag[]" id="combine_tds_check_flag<?php echo $ictds; ?>" value="0" >
						        <input type='checkbox' id="<?php echo "combine_tds_check$ictds"; ?>" value="<?php echo $ictds; ?>" onClick="return combine_tds_checke_allow(<?php echo $ictds; ?>);" name='combine_tds_check[]'/>
                                <input type="hidden" name="combine_tds_amount[]" id="combine_tds_amount<?php echo $ictds; ?>" value="<?php echo $due_tds_final; ?>" >
						        <input type="hidden" name="combine_tds_invoicen_id[]" id="combine_tds_invoicen_id<?php echo $ictds; ?>" value="<?php echo $select_data['invoice_id']; ?>" >
						        <input type="hidden" name="combine_tds_type[]" id="combine_tds_type<?php echo $ictds; ?>" value="<?php echo "tds"; ?>" >
						        <input type="hidden" name="combine_tds_paymentplan_id[]" id="combine_tds_paymentplan_id<?php echo $ictds; ?>" value="<?php echo $select_data['payment_id']; ?>" >
                            </br>
                            
                                <a href="javascript:void(0);" <?php if($due_tds_final>="1" ) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?>  title="TDS DUE" onClick="return tds_due_function('<?php echo $select_data['payment_id']; ?>','<?php echo $select_data['trans_id']; ?>','<?php echo $select_data['invoice_id']; ?>','<?php  echo $due_tds_final; ?>');" >
                        <?php 
                        $ictds++;
                        echo number_format($due_tds_final,2,'.','');    ?>  
                        </a> 
                            <?php 
                            }
                        ?>
                            
                        <?php
                        } 
                    }
                    }
                        //echo $select_data['tds_amount']; ?></td>
                        
<td class="data"  align="center" valign="top" >
                        <?php
                        if($select_data['credit'] > 0)
                        {
                            if($pay_date > "1648665000"){
                        if($select_data['gst_amount']!=0)
                        { ?>
                            
                            <?php
                                $gst_due_query1 = "select SUM(amount) as amount,SUM(received_amount) as received_amount  from gst_due_info where payment_plan_id = '".$select_data['payment_id']."' and invoice_id = '".$select_data['invoice_id']."' and received_amount > 0";
                                $gst_due_result2 = mysql_query($gst_due_query1) or die("error in date list query ".mysql_error());
                                $total_gst2 = mysql_num_rows($gst_due_result2);
                                $gst_due_query1_yescheck = "select * from gst_due_info where payment_plan_id = '".$select_data['payment_id']."' and invoice_id = '".$select_data['invoice_id']."' and received_amount > 0";
                                $gst_due_result2_yescheck = mysql_query($gst_due_query1_yescheck) or die("error in date list query ".mysql_error());
                                $total_gst2_yescheck = mysql_num_rows($gst_due_result2_yescheck);
                                
                                if($total_gst2_yescheck > 0)
                                    {
                                        $find_gst = mysql_fetch_array($gst_due_result2);
                                        $due_gst_final = $select_data['gst_amount']-$find_gst['received_amount'];
                                    }
                                    else{
                                       // $due_value="All";
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
                               /*  if($select_data['clear_gst_flag']=="0" )*/
                                if($due_gst_final>="1" )
                                {   
                                     
                                      //combine_invoice_checke_allow , combine_invoice_check , combine_invoice_check_flag ,combine_tds_amount ,combine_tds_invoicen_id ,combine_tds_type ,combine_tds_paymentplan_id

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
                            ?>   </td>
                        <td class="data" align="center" valign="top" ><?php
                        /*   invoice DUE          */
                        
                        if($select_data['credit'] > 0)
                        {
                            if($pay_date >"1648665000"){
                            if($select_data['invoice_pay_amount']!=0)
                            { ?>
                                <?php
                                    $invoice_due_query1 = "select SUM(amount) as amount,SUM(received_amount) as received_amount  from invoice_due_info where payment_plan_id = '".$select_data['payment_id']."' and invoice_id = '".$select_data['invoice_id']."' and received_amount > 0 ";
                                    $invoice_due_result2 = mysql_query($invoice_due_query1) or die("error in date list query ".mysql_error());
                                    $total_invoice2 = mysql_num_rows($invoice_due_result2);
                                    $invoice_due_query1_yescheck = "select * from invoice_due_info where payment_plan_id = '".$select_data['payment_id']."' and invoice_id = '".$select_data['invoice_id']."' and received_amount > 0 ";
                                    $invoice_due_result2_yescheck = mysql_query($invoice_due_query1_yescheck) or die("error in date list query ".mysql_error());
                                    $total_invoice2_yescheck = mysql_num_rows($invoice_due_result2_yescheck);
                                    
                                    /*if($total_invoice2 > 0)*/
                                    if($total_invoice2_yescheck > 0)
                                        {
                                            $find_invoice = mysql_fetch_array($invoice_due_result2);
                                            $due_invoice_final = $select_data['invoice_pay_amount']-$find_invoice['received_amount'];
                                        }
                                        else{
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
                                   /*  if($select_data['clear_invoice_flag']=="0" )*/
                                   if($due_invoice_final>="1" )
                                  {   
                                      //combine_invoice_checke_allow , combine_invoice_check , combine_invoice_check_flag ,combine_tds_amount ,combine_tds_invoicen_id ,combine_tds_type ,combine_tds_paymentplan_id

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
                        <td class="data noExl" nowrap="nowrap">
                        <?php //////////////   delete File start     ///////////////?>
                        <?php 

                        
                        if($select_data['trans_type_name']=="instmulti_receive_payment")
                        { ?>
                        <?php if($select_data['invoice_id'] != "" && $select_data['invoice_id'] != 0) { ?>
                        &nbsp;<a href="javascript:account_transaction_invoice_pay(<?php echo $select_data['invoice_id']; ?>)"><img src="mos-css/img/delete.png" title="Delete" ></a>
                        <?php    
                        }}
                        else if($select_data['trans_type_name']=="instmulti_receive_payment_invoice")
                        { ?>
                        <?php if($select_data['invoice_id'] != "" && $select_data['invoice_id'] != 0) { ?>
                        &nbsp;<a href="javascript:account_transaction_invoice_gst(<?php echo $select_data['invoice_id']; ?>,<?php echo $select_data['payment_id']; ?>,'Invoice')"><img src="mos-css/img/delete.png" title="Delete" ></a>
                        <?php    
                        }
                    }
                        else if($select_data['trans_type_name']=="tds_receive_payment")
                        { ?>
                        <?php 
                        if($select_data['invoice_id'] != "" && $select_data['invoice_id'] != 0) { ?>
                        &nbsp;<a href="javascript:account_transaction_invoice_gst(<?php echo $select_data['invoice_id']; ?>,<?php echo $select_data['payment_id']; ?>,'TDS')"><img src="mos-css/img/delete.png" title="Delete" ></a>
                        <?php    
                        }
                    }
                        else if($select_data['trans_type_name']=="gst_receive_payment")
                        { ?>
                        <?php
                        if($select_data['invoice_id'] != "" && $select_data['invoice_id'] != 0) { ?>
                        &nbsp;<a href="javascript:account_transaction_invoice_gst(<?php echo $select_data['invoice_id']; ?>,<?php echo $select_data['payment_id']; ?>,'GST')"><img src="mos-css/img/delete.png" title="Delete" ></a>
                        <?php   
                        } 
                    }
                        
                        else if($select_data['trans_type_name']=="instmulti_sale_goods")
                        { ?>
                        <?php if($select_data['invoice_id'] != "" && $select_data['invoice_id'] != 0) { ?>
                        &nbsp;<a href="javascript:account_transaction_invoice_multisale(<?php echo $select_data['invoice_id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>
                        <?php    
                        }}
                        else if($select_data['trans_type_name']=="combind_payment")
                        { ?>
                        <?php if($select_data['payment_id'] != "" && $select_data['payment_id'] != 0) { ?>
                        &nbsp;<a href="javascript:account_transaction_combind(<?php echo $select_data['trans_id'] ?>,<?php echo $select_data['payment_id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>
                        <?php    
                        }}
                        
                        else
                        { ?>
                        <?php if($select_data['trans_id'] != "" && $select_data['trans_id'] != 0) { ?>
                        &nbsp;<a href="javascript:account_transaction(<?php echo $select_data['trans_id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>
                        <?php } }
                         //////////////    File delete end    ///////////////  
                         //////////////   Attach File start     ///////////////
                         
                            //instmulti_receive_payment_invoice , gst_receive_payment , tds_receive_payment
                        if($select_data['trans_type_name']=="instmulti_sale_goods")
                        {  ?>
                        &nbsp;<a href="javascript:void(0);" title="Attach File" onClick="return invoice_attach_file_function('<?php echo $select_data['invoice_id']; ?>');" ><img src="images/images.jpg" width="20" ></a>
                        <?php
                        }else if($select_data['trans_type_name']=="instmulti_receive_payment")
                        {  ?>
                        &nbsp;<a href="javascript:void(0);" title="Attach File" onClick="return invoice_attach_file_function('<?php echo $select_data['invoice_id']; ?>');" ><img src="images/images.jpg" width="20" ></a>
                        <?php
                        }else if($select_data['trans_type_name']=="instmulti_receive_payment_invoice")
                        {  ?>
                        &nbsp;<a href="javascript:void(0);" title="Attach File" onClick="return invoice_attach_file_function('<?php echo $select_data['invoice_id']; ?>');" ><img src="images/images.jpg" width="20" ></a>
                        <?php
                        }else if($select_data['trans_type_name']=="gst_receive_payment")
                        {  ?>
                        &nbsp;<a href="javascript:void(0);" title="Attach File" onClick="return invoice_attach_file_function('<?php echo $select_data['invoice_id']; ?>');" ><img src="images/images.jpg" width="20" ></a>
                        <?php
                        }else if($select_data['trans_type_name']=="tds_receive_payment")
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
                        {  if($select_data['clear_table_link_id']=="0")
                            {?>
                        <a href="javascript:void(0);"   title="Edit GST" onClick="return edit_tds_function('<?php echo $select_data['payment_id']; ?>','customer-ledger','<?php echo $select_data['invoice_id']; ?>','<?php echo $select_data['link3_id']; ?>');" ><img src="mos-css/img/edit.png" title="Edit">  </a> 

                        
                <?php  } } ?>
                <?php
                        if($select_data['trans_type_name']=="gst_receive_payment")
                        { 
                            if($select_data['clear_table_link_id']=="0")
                            { ?>
                        <a href="javascript:void(0);"   title="Edit GST" onClick="return edit_gst_function('<?php echo $select_data['payment_id']; ?>','customer-ledger','<?php echo $select_data['invoice_id']; ?>','<?php echo $select_data['link3_id']; ?>');" ><img src="mos-css/img/edit.png" title="Edit">  </a> 

                        
                <?php  } }?>
                <?php
                        if($select_data['trans_type_name']=="instmulti_receive_payment_invoice")
                        {  
                            if($select_data['clear_table_link_id']=="0")
                            {
                            ?>
                        <a href="javascript:void(0);"   title="Edit GST" onClick="return edit_invoice_function('<?php echo $select_data['payment_id']; ?>','customer-ledger','<?php echo $select_data['invoice_id']; ?>','<?php echo $select_data['link3_id']; ?>');" ><img src="mos-css/img/edit.png" title="Edit">  </a> 

                        
                <?php       }  } ?>
                 
                 <?php //////////////   edit File end     ///////////////?>
                           <?php //////////////   view File start     ///////////////?>                 
                  
                 <?php
                 
                        $total_rows_view=0;
                        if($select_data['trans_type_name']=="instmulti_sale_goods")
                        {  
                            
                        $query_view="select *  from attach_file where attach_id = '".$select_data['invoice_id']."'";
                        $result_view= mysql_query($query_view) or die('error in query '.mysql_error().$query_view);
                        $total_rows_view_1 = mysql_num_rows($result_view);
                        if($total_rows_view_1>0)
                        {
                            $total_rows_view=$total_rows_view_1;
                        }

                        $query_tds="select *  from tds_due_info where invoice_id = '".$select_data['invoice_id']."' and cert_file_name!=''";
                        $result_tds= mysql_query($query_tds) or die('error in query '.mysql_error().$query_tds);
                        $total_rows_tds = mysql_num_rows($result_tds);
                        if($total_rows_tds>0)
                        {
                            $total_rows_view=$total_rows_tds;
                        }
                        $query_gst="select *  from gst_due_info where invoice_id = '".$select_data['invoice_id']."' and cert_file_name!=''";
                        $result_gst= mysql_query($query_gst) or die('error in query '.mysql_error().$query_gst);
                        $total_rows_gst = mysql_num_rows($result_gst);
                        if($total_rows_gst>0)
                        {
                            $total_rows_view=$total_rows_gst;
                        }
                        $query_invoice="select *  from invoice_due_info where invoice_id = '".$select_data['invoice_id']."' and cert_file_name!=''";
                        $result_invoice= mysql_query($query_invoice) or die('error in query '.mysql_error().$query_invoice);
                        $total_rows_invoice = mysql_num_rows($result_invoice);
                        if($total_rows_invoice>0)
                        {
                            $total_rows_view=$total_rows_invoice;
                        }


                        if($total_rows_view != 0)
                        { ?>
                           <a href="javascript:void(0);" title="View File" onClick="return view_file_function('<?php echo $select_data['invoice_id']; ?>','all_file');" >View</a>     <?php           
                           }
                            
                        }
                        else  if($select_data['trans_type_name']=="instmulti_receive_payment")
                        {  
                            
                        $query_view="select *  from attach_file where attach_id = '".$select_data['invoice_id']."'";
                        $result_view= mysql_query($query_view) or die('error in query '.mysql_error().$query_view);
                        $total_rows_view = mysql_num_rows($result_view);
                        if($total_rows_view != 0)
                        { ?>
                           <a href="javascript:void(0);" title="View File" onClick="return view_file_function('<?php echo $select_data['invoice_id']; ?>','invoice');" >View</a>     <?php          
                            }
                            
                        }
                         else  if($select_data['trans_type_name']=="tds_receive_payment")
                        {  
                            
                        $query_view="select *  from attach_file where attach_id = '".$select_data['invoice_id']."'";
                        $result_view= mysql_query($query_view) or die('error in query '.mysql_error().$query_view);
                        $total_rows_view_1 = mysql_num_rows($result_view);
                        if($total_rows_view_1>0)
                        {
                            $total_rows_view=$total_rows_view_1;
                        }

                        $query_tds="select *  from tds_due_info where invoice_id = '".$select_data['invoice_id']."' and cert_file_name!=''";
                        $result_tds= mysql_query($query_tds) or die('error in query '.mysql_error().$query_tds);
                        $total_rows_tds = mysql_num_rows($result_tds);
                        if($total_rows_tds>0)
                        {
                            $total_rows_view=$total_rows_tds;
                        }
                        if($total_rows_view != 0)
                        { ?>
                           <a href="javascript:void(0);" title="View File" onClick="return view_file_function('<?php echo $select_data['invoice_id']; ?>','tds');" >View</a>     <?php          
                            }
                            
                        }
                        else  if($select_data['trans_type_name']=="gst_receive_payment")
                        {  
                        $query_view="select *  from attach_file where attach_id = '".$select_data['invoice_id']."'";
                        $result_view= mysql_query($query_view) or die('error in query '.mysql_error().$query_view);
                        $total_rows_view_1 = mysql_num_rows($result_view);
                        if($total_rows_view_1>0)
                        {
                            $total_rows_view=$total_rows_view_1;
                        }

                        $query_gst="select *  from gst_due_info where invoice_id = '".$select_data['invoice_id']."' and cert_file_name!=''";
                        $result_gst= mysql_query($query_gst) or die('error in query '.mysql_error().$query_gst);
                        $total_rows_gst = mysql_num_rows($result_gst);
                        if($total_rows_gst>0)
                        {
                            $total_rows_view=$total_rows_gst;
                        }
                        if($total_rows_view != 0)
                        { ?>
                           <a href="javascript:void(0);" title="View File" onClick="return view_file_function('<?php echo $select_data['invoice_id']; ?>','gst');" >View</a>     <?php          
                            }
                            
                        }
                        else  if($select_data['trans_type_name']=="instmulti_receive_payment_invoice")
                        {  
                            
                        $query_view="select *  from attach_file where attach_id = '".$select_data['invoice_id']."'";
                        $result_view= mysql_query($query_view) or die('error in query '.mysql_error().$query_view);
                        $total_rows_view_1 = mysql_num_rows($result_view);
                        if($total_rows_view_1>0)
                        {
                            $total_rows_view=$total_rows_view_1;
                        }

                        $query_invoice="select *  from invoice_due_info where invoice_id = '".$select_data['invoice_id']."' and cert_file_name!=''";
                        $result_invoice= mysql_query($query_invoice) or die('error in query '.mysql_error().$query_invoice);
                        $total_rows_invoice = mysql_num_rows($result_invoice);
                        if($total_rows_invoice>0)
                        {
                            $total_rows_view=$total_rows_invoice;
                        }

                        if($total_rows_view != 0)
                        { ?>
                           <a href="javascript:void(0);" title="View File" onClick="return view_file_function('<?php echo $select_data['invoice_id']; ?>','invoice');" >View</a>     <?php          
                            }
                            
                        }
                        else
                        {
                        $query_view="select *  from attach_file where attach_id = '".$select_data['payment_id']."'";
                        $result_view= mysql_query($query_view) or die('error in query '.mysql_error().$query_view);
                        $total_rows_view = mysql_num_rows($result_view);
                        if($total_rows_view != 0)
                        { ?>
                           <a href="javascript:void(0);" title="View File" onClick="return view_file_function('<?php echo $select_data['payment_id']; ?>','');" >View</a>     <?php           }
                        }

?> 
                 </td>
                        
                    </tr>
                <?php
                    $i++;
                        }
                }
        ?>
            <input type="hidden" id="count_icgst"  name="count_icgst" value="<?php echo $icgst; ?>" />
            <input type="hidden" id="count_icinvoice"  name="count_icinvoice" value="<?php echo $icinvoice; ?>" />
            <input type="hidden" id="count_ictds"  name="count_ictds" value="<?php echo $ictds; ?>" />    
        <?php 
                if($search_start=="1")
                {
                    // opening_tds_tot_due , opening_gst_tot_due  ,  opening_invoice_tot_due
                    ?>
                    
                    <tr class="data">
                        <td class="data" ><?php //echo $i; ?></td>
                        <td class="data"><b> <?php echo date("d-m-Y",$from_date); ?></b></td>
                        <td class="data"></td>
                        <td class="data"></td>
                       
                        <td class="data"><b><?php echo "Opening Balance"; ?></b></td>
                        <td class="data"><b><?php echo number_format($opening_tds_tot_due,2,'.',''); ?></b></td>
                        <td class="data"><b><?php echo number_format($opening_gst_tot_due,2,'.',''); ?></b></td>
                        <td class="data"><b><?php echo number_format($opening_invoice_tot_due,2,'.',''); ?></b></td>
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
                    <td  colspan="10" class="record_not_found" >Record Not Found</td>
                </tr>
                <?php
            }
            ?>
            
        </table>
        
           </div>     
        
           <?php include_once("main_body_close.php") ?>
         <!-------------->  
         <?php include_once ("footer.php"); ?>  

<div id="attach_div" style="position:absolute;top:40%; left:40%; width:500px; height:180px; margin:-100px 0 0 -50px;z-index:100; display:none; background-color:#FFFFFF; border:8px solid #999999;" >
<form name="attach_form" id="attach_form" method="post" action="" onSubmit="return attach_validation();" enctype="multipart/form-data" >
<table cellpadding="0" cellspacing="0" border="1" width="100%" >
<tr><td valign="top" align="right" colspan="2" ><img src="images/close.gif" onClick="return close_div();" ></td></tr>
<tr><td valign="top" >Attach File</td>
            <td><input type="file" name="attach_file" id="attach_file" value="" class="w_250" ></td></tr>
            
            <tr><td valign="top" >Attach File Name</td>
            <td><input type="text" id="attach_file_name"  name="attach_file_name" value="" class="w_250" autocomplete="off"/></td></tr>
            
            <tr><td></td><td>
            <input type="submit" class="button" name="file_button" id="file_button" value="Submit" >
            </td></tr>
</table>
<input type="hidden" id="attach_file_id"  name="attach_file_id" value="" />
<input type="hidden" id="invoice_flag"  name="invoice_flag" value="" />
</form>
</div>
<div id="view_div" style="position:absolute;top:40%; left:40%; width:550px; min-height:250px; margin:-100px 0 0 -50px;z-index:100; display:none; background-color:#FFFFFF; border:8px solid #999999;" >

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
<form name="trans_form_gst" id="trans_form_gst" action="" method="post" >
        
        <input type="hidden" name="trans_id_gst" id="trans_id_gst" value="" >
</form>
<form name="trans_form_tds" id="trans_form_tds" action="" method="post" >
        
    <input type="hidden" name="trans_id_tds" id="trans_id_tds" value="" >
</form>
<form name="trans_form_invoice" id="trans_form_invoice" action="" method="post" >
        
    <input type="hidden" name="trans_id_invoice" id="trans_id_invoice" value="" >
</form>

            
<form name="invoice_form" id="invoice_form" action="" method="post" >
        
        <input type="hidden" name="invoice_id" id="invoice_id" value="" >
        <input type="hidden" name="trans_t_name" id="trans_t_name" value="" >
        
        <input type="hidden" name="del_payment_id" id="del_payment_id" value="" >
        <input type="hidden" name="del_type" id="del_type" value="" >
       
        </form>

        <!---  Due TDs Attchment Div   -->
 <!-- /// attach_div  ,attach_form ,  attach_form ,  attach_validation , attach_file_id   , invoice_flag  -->    
 <div id="tds_due_div" style="position:absolute; top:28%; left:40%; width:590px; height:480px; margin:-100px 0 0 -50px;z-index:100; display:none; background-color:#FFFFFF; border:8px solid #999999;" >
 <!--<div id="tds_due_div" style="background:#e1e1e1; display:none; top:100px;left:50%; width:820px;position:absolute; z-index:41;padding:30px;" >-->

 <form name="tds_due_form" id="tds_due_form" method="post" action="" onSubmit="return tds_validation();" enctype="multipart/form-data" >
<table cellpadding="0" cellspacing="0" border="1px" width="100%" >
<tr><td valign="top" align="right"  ><img src="images/close.gif" onClick="return close_tds_div();" ></td></tr>
<tr>
                    <td valign="top" align="left" style="color:#FF0000; font-weight:bold;" >&nbsp;&nbsp;TDS Due Amount
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                       
                    <input type="text" id="tds_due_amount_new_total" style="width:100px;color:red; border:0; " readonly="readonly"  name="tds_due_amount_new_total" value="" /></td>
                </tr>
                <tr>
                    <td valign="top" align="left"  style="color:#blue; " >
                    
                    <table width="100%" cellpadding="0" cellspacing="0" >

                        <tr>
                            <td  style="color:#blue; " colspan="2" >
                            <b>Clear TDS Due :</b>&nbsp;&nbsp; <input type="checkbox" id="clear_tds_due" name="clear_tds_due" onClick="return clear_tds_desc();" value="yes">
                        </td>
                         <tr>
                            <td>
                                <table>
                                <tr id="clear_desc_display_tds" style="display:none;">
                            <td width="150px" style="font-size:10px;">Description</td>
                            <td width="200px"><input type='text' style="display:none; width:180px;font-size:12px;" name='clear_tdsdue_desc' align='right' id='clear_tdsdue_desc' width="300px" placeholder="Type clear due Description here"/>
                            
                    </td>
                    </tr>
                                </table>
                            </td>
                            <td>
                                <table>
                                <tr id="clear_date_display_tds" style="display:none; ">
                        <td width="150px" style="font-size:10px;" >Payment Date</td>
                        <td  ><input type='text' style=" font-size:12px;" name='clear_pay_payment_date_tds' align='right' id='clear_pay_payment_date_tds' width="180px" autocomplete="off" placeholder="Type clear due Payment Date here" /></td>
                        <td><img src="js/images2/cal.gif" onClick="javascript:NewCssCal('clear_pay_payment_date_tds')" style="cursor:pointer"/></td>
                        </tr>
                                </table>
                            </td>
                         </tr> 
                        
                        
                        
                    </table>
                    
                </td>
                    
                </tr>
               

<tr><td valign="top" align="left"  ><b>Received TDS Certificate : </b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tds_cerf" id="tds_cerf" value="1" onClick="return close_tds_div2('1');" >&nbsp;&nbsp;YES&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="tds_cerf" id="tds_cerf" checked=true value="0" onClick="return close_tds_div2('0');" >&nbsp;&nbsp;NO
            </td>
        </tr>
        <tr>  
            <td valign="top"  align="left" id="tds-due_div2" style=" display:none; width:100%">
                <table width="100%" border="2" style="border:2px;">  
                <tr>
                    <td valign="top" >Paid Into</td>
                    <td>
                        <!--<input type="text" id="invoice_due_des" style="width:260px;"  name="invoice_due_des" value="" autocomplete="off"/>
        -->
        <input type="text" id="tds_pay_form"  name="tds_pay_form" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span> 
                </td>
                </tr>

                <tr>    <td valign="top"  width="180px" >Date</td>
                    <td><input type="text"  name="tds_due_date" id="tds_due_date" value="<?php //echo $_REQUEST['tds_due_date']; ?>" style="width:250px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('tds_due_date')" style="cursor:pointer"/></td>
                </tr>
                <tr>
                    <td valign="top" >Amount Received</td>
                    <td><input type="text" id="tds_due_amount" style="width:250px;"  name="tds_due_amount" value="" onkeydown="tds_due_calculation()" onkeyup="tds_due_calculation()" onkeypress="tds_due_calculation()" />
                    <span id=""  style="color:red;" >(Due :<input type="text" id="tds_due_amount_new_due" style="width:60px;color:red;border:0;" readonly="readonly"  name="tds_due_amount_new_due" value="" /></span>
                </td>
                </tr>
               
                <tr>
                    <td valign="top" >TDS File Attachment</td>
                    <td><input type="file" name="tds_due_attach_file" id="tds_due_attach_file" value="" style="width:250px;" ></td>
                </tr>
                <tr>
                    <td valign="top" >Discription</td>
                    <td><input type="text" id="tds_due_des"  name="tds_due_des" value="" style="width:250px;" autocomplete="off"/></td>
                </tr>
            </table>
            </td>
        </tr>    
        <input type="hidden" id="clearall_due_flag_tds"  name="clearall_due_flag_tds" value="" /> 
    <input type="hidden" id="tds_due_flag_value"  name="tds_due_flag_value" value="0" />
    <input type="hidden" id="tds_due_payment_id"  name="tds_due_payment_id" value="" />
    <input type="hidden" id="tds_attach_file_id"  name="tds_attach_file_id" value="" />
    <input type="hidden" id="tds_invoice_flag"  name="tds_invoice_flag" value="" />
    <input type="hidden" id="tds_due_trans_id"  name="tds_due_trans_id" value="" />
    <input type="hidden" id="tds_due_invoice_id"  name="tds_due_invoice_id" value="" />
    <input type="hidden" id="tds_due_amount_pay"  name="tds_due_amount_pay" value="" />
    <tr>
        <td valign="bottom" align="center" ><input type="submit" class="button" name="file_button1" id="file_button1" value="Submit" ></td></tr>
</table>

</form>
</div>

        <!--      /// Due TDS Attachment Div     --->

        <!---  Due GST Attchment Div   -->
 <!-- /// attach_div  ,attach_form ,  attach_form ,  attach_validation , attach_file_id   , invoice_flag  -->    
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
                
            
            <tr><td valign="top"  align="left" ><b>Received gst :</b>&nbsp;&nbsp;&nbsp;<input type="radio" name="gst_cerf" id="gst_cerf" value="1" onClick="return close_gst_div2('1');" >&nbsp;&nbsp;YES&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="gst_cerf" id="gst_cerf"  checked=true value="0" onClick="return close_gst_div2('0');" >&nbsp;&nbsp;NO
            </td>
        </tr>
        <tr>  
            <td valign="top"  align="left" id="gst-due_div2" style=" display:none; width:100%">
                <table width="100%" border="2" style="border:2px;">  
                <tr>
                    <td valign="top"  width="180px"  >Paid Into</td>
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
                    <td valign="top" >Amount Received</td>
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
                    <td valign="top" >Amount Received</td>
                    <td><input type="text" id="pay_amount" style="width:250px;"  name="pay_amount" value="" onkeydown="invoice_due_calculation()" onkeyup="invoice_due_calculation()" onkeypress="invoice_due_calculation()" />
                    <span id=""  style="color:red;" >(Due :<input type="text" id="invoice_due_amount_new_due" style="width:60px;color:red;border:0;" readonly="readonly"  name="invoice_due_amount_new_due" value="" /></span>
                </td>
                </tr>
                
                <tr>
                    <td valign="top" >Payment File Attachment</td>
                    <td><input type="file" name="invoice_due_attach_file" width="250px" id="invoice_due_attach_file" value="" ></td>
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

        
        $( "#combind_payment_pay_form" ).autocomplete({
            source: "bankcash-ajax.php"
        });
        
});


 
</script>
<script>
function close_view()
{
    $('#view_div').hide("slow");
}

function view_file_function(id,type_attachment)
{
    n_type=type_attachment;
    $('#view_div').show("slow");
    $.ajax({
        url: "attach_file_ajax.php?id="+id+'&invoice_type='+n_type,
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
 //alert(id);   
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
    
        //clearall_due_flag_tds, tds_due_flag_value , due_payment_id , attach_file_id , invoice_flag , due_trans_id , due_invoice_id , due_amount_pay
    document.getElementById("tds_due_payment_id").value=due_id;
    document.getElementById("tds_due_trans_id").value=due_trans;
    
    document.getElementById("tds_due_invoice_id").value=due_invoice;
    
    document.getElementById("tds_due_amount_pay").value=due_amount_pay;
    
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
        document.getElementById("clear_date_display_tds").style.display="block";
        document.getElementById("clear_desc_display_tds").style.display="block";
    }
    else if(document.getElementById('clear_tds_due').checked == false)
    {
        document.getElementById("clear_tdsdue_desc").style.display="none";
        document.getElementById("clear_date_display_tds").style.display="none";
        document.getElementById("clear_desc_display_tds").style.display="none";
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
       // document.getElementById('clear_tds_due').checked = true;
       // document.getElementById("clear_tdsdue_desc").style.display="block";
       // document.getElementById("clear_date_display_tds").style.display="block";
       // document.getElementById("clear_desc_display_tds").style.display="block";
    }
    else{
       // document.getElementById('clear_tds_due').checked = false;
       // document.getElementById("clear_tdsdue_desc").style.display="none";
       // document.getElementById("clear_date_display_tds").style.display="none";
       // document.getElementById("clear_desc_display_tds").style.display="none";
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
        }else if(document.getElementById("clear_pay_payment_date_tds").value=="")
        {
            alert("Please enter Payment Date for clear due");
            document.getElementById("clear_pay_payment_date_tds").focus();
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
     
    }else if(document.getElementById('clear_tds_due').checked == true && document.getElementById("clear_pay_payment_date_tds").value=="")
        {
            alert("Please enter Payment Date for clear due");
            document.getElementById("clear_pay_payment_date_tds").focus();
            return false;
        }
    else if(document.getElementById("tds_pay_form").value=="")
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
    //clear_gst_due , clear_gstdue_desc , clear_pay_payment_date_gst   TR= clear_date_display_gst , clear_date_display_gst  ,clear_desc_display_gst
    if(document.getElementById('clear_gst_due').checked == true)
    {
        document.getElementById("clear_gstdue_desc").style.display="block";

        document.getElementById("clear_date_display_gst").style.display="block";
        document.getElementById("clear_desc_display_gst").style.display="block";
    }
    else if(document.getElementById('clear_gst_due').checked == false)
    {
        document.getElementById("clear_gstdue_desc").style.display="none";
        document.getElementById("clear_date_display_gst").style.display="none";
        document.getElementById("clear_desc_display_gst").style.display="none";
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
       // document.getElementById('clear_gst_due').checked = true;
        //document.getElementById("clear_gstdue_desc").style.display="block";
        //document.getElementById("clear_date_display_gst").style.display="block";
        //document.getElementById("clear_desc_display_gst").style.display="block";
    }
    else{
       // document.getElementById('clear_gst_due').checked = false;
       // document.getElementById("clear_gstdue_desc").style.display="none";
       // document.getElementById("clear_date_display_gst").style.display="none";
       // document.getElementById("clear_desc_display_gst").style.display="none";
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
        }else if(document.getElementById("clear_pay_payment_date_gst").value=="")
        {
            alert("Please enter Payment Date for clear due");
            document.getElementById("clear_pay_payment_date_gst").focus();
            return false;
        }
        else{
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
        
        }else if(document.getElementById('clear_gst_due').checked == true && document.getElementById("clear_pay_payment_date_gst").value=="")
        {
            alert("Please enter Payment Date for clear due");
            document.getElementById("clear_pay_payment_date_gst").focus();
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
     //clear_desc_display  ,clear_date_display
    if(document.getElementById('clear_invoice_due').checked == true)
    {
        document.getElementById("clear_invoicedue_desc").style.display="block";
        document.getElementById("clear_pay_payment_date").style.display="block";
        
        document.getElementById("clear_date_display").style.display="block";
        document.getElementById("clear_desc_display").style.display="block";
        
    }
    else if(document.getElementById('clear_invoice_due').checked == false)
    {
        document.getElementById("clear_invoicedue_desc").style.display="none";
        document.getElementById("clear_pay_payment_date").style.display="none";
        document.getElementById("clear_date_display").style.display="none";
        document.getElementById("clear_desc_display").style.display="none";
       
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
      //  document.getElementById('clear_invoice_due').checked = true;
      //  document.getElementById("clear_invoicedue_desc").style.display="block";
      //  document.getElementById("clear_pay_payment_date").style.display="block";
      //  document.getElementById("clear_date_display").style.display="block";
      //  document.getElementById("clear_desc_display").style.display="block";
       
        
    }
    else{
       // document.getElementById('clear_invoice_due').checked = false;
       // document.getElementById("clear_invoicedue_desc").style.display="none";
       // document.getElementById("clear_pay_payment_date").style.display="none";
       // document.getElementById("clear_date_display").style.display="none";
       // document.getElementById("clear_desc_display").style.display="none";
       
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
        }else if(document.getElementById("clear_pay_payment_date").value=="")
        {
            alert("Please enter Payment Date for clear due");
            document.getElementById("clear_pay_payment_date").focus();
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
        
    }else if(document.getElementById('clear_invoice_due').checked == true && document.getElementById("clear_pay_payment_date").value=="")
        {
            alert("Please enter Payment Date for clear due");
            document.getElementById("clear_pay_payment_date").focus();
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

function combind_payment_function()
{
    //combind_payment_amount_new_total , combind_payment_due_des
       //combine_invoice_checke_allow , combine_invoice_check , combine_invoice_check_flag ,combine_tds_amount ,combine_tds_invoicen_id ,combine_tds_type ,combine_tds_paymentplan_id
//count_icgst
        var count_icgst=document.getElementById('count_icgst').value;
        var count_ictds=document.getElementById('count_ictds').value;
        var count_icinvoice=document.getElementById('count_icinvoice').value;
        var num_count=0;
        var tot_invoice_combind=0;
        var tot_tds_combind =0;
        var tot_gst_combind= 0;
        var description="";
        var ij=1;
        $("#myTable_2 tbody tr").remove();
        for (var i = 1; i <= count_icinvoice; i++) { 
            if($("#combine_invoice_check_flag"+i+"").val()=="1"){
                var invoiceid =$("#combine_invoice_invoicen_id"+i+"").val();
                var amount= $("#combine_invoice_amount"+i+"").val();
                var payment_planid =$("#combine_invoice_paymentplan_id"+i+"").val();
                var type =$("#combine_invoice_type"+i+"").val();
                   // alert(invoiceid+","+amount+","+payment_planid+","+type);
                    tot_invoice_combind =Number(tot_invoice_combind)+Number($("#combine_invoice_amount"+i+"").val()) ; 
                    num_count="1";
                    if(description=="")
                    {
                        description="(Invoice Received for invoice :"+invoiceid;
                    }else{
                        description=description+","+"Invoice Received for invoice :"+invoiceid;
                    }
                    
                    markup ="<tr><td style='display:block;height:20px;padding:0px;'><input type='text' width='100px' readonly='readonly' id='' style='height:20px;border:0px;'  value='"+invoiceid+"("+type+")' name=''/><input type='text' id='combind_invoice_amount_pay"+ij+"' style='height:20px;'  value='"+amount+"' name='combind_invoice_amount_pay"+ij+"'/><input type='hidden' id='combind_invoice_invoiceid"+ij+"' value='"+invoiceid+"' name='combind_invoice_invoiceid"+ij+"'/><input type='hidden' id='combind_invoice_amount"+ij+"' value='"+amount+"' name='combind_invoice_amount"+ij+"'/><input type='hidden' id='combind_invoice_payment_planid"+ij+"' value='"+payment_planid+"' name='combind_invoice_payment_planid"+ij+"'/><input type='hidden' id='combind_invoice_type"+ij+"' value='"+type+"' name='combind_invoice_type"+ij+"'/></td></tr>";
                    $(' #myTable_2 tbody').append(markup);
                    ij++;
                }
        }
        ij_tds=1;
        for (var i = 1; i <= count_ictds; i++) { 
            if($("#combine_tds_check_flag"+i+"").val()=="1"){
                var tds_invoiceid =$("#combine_tds_invoicen_id"+i+"").val();
                var tds_amount= $("#combine_tds_amount"+i+"").val();
                var tds_payment_planid =$("#combine_tds_paymentplan_id"+i+"").val();
                var tds_type =$("#combine_tds_type"+i+"").val();
                 //   alert(tds_invoiceid+","+tds_amount+","+tds_payment_planid+","+tds_type);
                    tot_tds_combind =Number(tot_tds_combind)+Number($("#combine_tds_amount"+i+"").val()) ; 
                    num_count="1";
                    if(description=="")
                    {
                        description="(TDS Received for invoice :"+tds_invoiceid;
                    }else{
                        description=description+","+"TDS Received for invoice :"+tds_invoiceid;
                    }
                    markup ="<tr><td style='display:block;height:20px;padding:0px;'><input type='text' width='100px' readonly='readonly' id='' value='"+tds_invoiceid+"("+tds_type+")' name='' style='height:20px;border:0px;' /><input type='text' id='combind_tds_amount_pay"+ij_tds+"' style='height:20px;' value='"+tds_amount+"' name='combind_tds_amount_pay"+ij_tds+"'/><input type='hidden' id='combind_tds_invoiceid"+ij_tds+"' value='"+tds_invoiceid+"' name='combind_tds_invoiceid"+ij_tds+"'/><input type='hidden' id='combind_tds_amount"+ij_tds+"' value='"+tds_amount+"' name='combind_tds_amount"+ij_tds+"'/><input type='hidden' id='combind_tds_payment_planid"+ij_tds+"' value='"+tds_payment_planid+"' name='combind_tds_payment_planid"+ij_tds+"'/><input type='hidden' id='combind_tds_type"+ij_tds+"' value='"+tds_type+"' name='combind_tds_type"+ij_tds+"'/></td></tr>";
                    $(' #myTable_2 tbody').append(markup);
                    ij_tds++;
            }
        }
        var ij_gst=1;
        for (var i = 1; i <= count_icgst; i++) { 
            if($("#combine_gst_check_flag"+i+"").val()=="1"){
                var gst_invoiceid =$("#combine_gst_invoicen_id"+i+"").val();
                var gst_amount= $("#combine_gst_amount"+i+"").val();
                var gst_payment_planid =$("#combine_gst_paymentplan_id"+i+"").val();
                var gst_type =$("#combine_gst_type"+i+"").val();
                    // alert(gst_invoiceid+","+gst_amount+","+gst_payment_planid+","+gst_type);
                    tot_gst_combind =Number(tot_gst_combind)+Number($("#combine_gst_amount"+i+"").val()) ; 
                    num_count="1";
                    if(description=="")
                    {
                        description ="(GST Received for invoice :"+gst_invoiceid;
                    }else{
                        description = description+","+"GST Received for invoice :"+gst_invoiceid;
                    }
                    markup ="<tr><td style='display:block;height:20px;padding:0px;'><input type='text'  width='100px' readonly='readonly' id='' value='"+gst_invoiceid+"("+gst_type+")' style='height:20px;border:0px;'  name=''/><input type='text' id='combind_gst_amount_pay"+ij_gst+"' style='height:20px;' value='"+gst_amount+"' name='combind_gst_amount_pay"+ij_gst+"'/><input type='hidden' id='combind_gst_invoiceid"+ij_gst+"' value='"+gst_invoiceid+"' name='combind_gst_invoiceid"+ij_gst+"'/><input type='hidden' id='combind_gst_amount"+ij_gst+"' value='"+gst_amount+"' name='combind_gst_amount"+ij_gst+"'/><input type='hidden' id='combind_gst_payment_planid"+ij_gst+"' value='"+gst_payment_planid+"' name='combind_gst_payment_planid"+ij_gst+"'/><input type='hidden' id='combind_gst_type"+ij_gst+"' value='"+gst_type+"' name='combind_gst_type"+ij_gst+"'/></td></tr>";
                    $(' #myTable_2 tbody').append(markup);
                    ij_gst++;
            }
        }   //combind_gst_invoiceid ,combind_gst_amount , combind_gst_payment_planid ,combind_gst_type
        //combind_invoice_count,combind_tds_count,combind_gst_count
        markup ="<tr><td style='display:none;'><input type='hidden' id='combind_invoice_count' value='"+ij+"' name='combind_invoice_count'/><input type='hidden' id='combind_tds_count' value='"+ij_tds+"' name='combind_tds_count'/><input type='hidden' id='combind_gst_count' value='"+ij_gst+"' name='combind_gst_count'/></td></tr>";
                    $(' #myTable_2 tbody').append(markup);
        if(description!="")
        {
            description = description+")";
        }
                    
        var subtotal_combind = Number(tot_invoice_combind)+Number(tot_tds_combind)+Number(tot_gst_combind);
        document.getElementById('combind_payment_amount_new_total').value=subtotal_combind;
        
        document.getElementById('combind_payment_due_des').value=description;
        if(num_count=="1")
        {
            document.getElementById("combind_payment_div").style.display="block";
        }
        else{
            alert("Please select any one payment type checkbox");
        }
    
}

function close_combind_payment_div()
{
    document.getElementById("combind_payment_div").style.display="none";
}

function combine_tds_checke_allow(nm)
{ 
                                    
	if(document.getElementById("combine_tds_check"+nm).checked==true)
	{
		document.getElementById("combine_tds_check_flag"+nm).value=1;
	}
	else{
		document.getElementById("combine_tds_check_flag"+nm).value=0;
	}
}
function combine_gst_checke_allow(nm)
{ 
	if(document.getElementById("combine_gst_check"+nm).checked==true)
	{
		document.getElementById("combine_gst_check_flag"+nm).value=1;
	}
	else{
		document.getElementById("combine_gst_check_flag"+nm).value=0;
	}
}
function combine_invoice_checke_allow(nm)
{ 
	if(document.getElementById("combine_invoice_check"+nm).checked==true)
	{
		document.getElementById("combine_invoice_check_flag"+nm).value=1;
	}
	else{
		document.getElementById("combine_invoice_check_flag"+nm).value=0;
	}
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

function account_transaction_combind(trans_id,payment_id)
{
    if(confirm("Are you sure want to delete?!!!!!......"))
    {
        $("#trans_id_combind").val(trans_id);
        $("#payment_id").val(payment_id);
        $("#trans_form_combind").submit();
        return true;
    }
}

function account_transaction_invoice_multisale(invoice_id)
{
    //alert("hello");
    if(confirm("Are you sure want to delete?!!!!!......"))
    { 
       
        $("#trans_t_name").val("instmulti_sale_goods");
        
        $("#invoice_id").val(invoice_id);
        $("#invoice_form").submit();
        return true;
    }
}


function account_transaction_invoice(trans_id_1)
{
    if(confirm("Are you sure want to delete?!!!!!......"))
    {
        $("#trans_id_invoice").val(trans_id_1);
        $("#trans_form_invoice").submit();
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
function account_transaction_gst(trans_id_1)
{
    if(confirm("Are you sure want to delete?!!!!!......"))
    {
        $("#trans_id_gst").val(trans_id_1);
        $("#trans_form_gst").submit();
        return true;
    }
}

function account_transaction_tds(trans_id_1)
{
    if(confirm("Are you sure want to delete?!!!!!......"))
    {
        $("#trans_id_tds").val(trans_id_1);
        $("#trans_form_tds").submit();
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

function profile_display()
{
    //profile_display, profile_div_1 ,profile_check_val
    prof_val =document.getElementById("profile_check_val").value;
    if(prof_val==0)
    {
        
    document.getElementById("profile_div_1").style.display="block";
        document.getElementById("profile_check_val").value=1;
    }else
    {
    document.getElementById("profile_div_1").style.display="none";
        document.getElementById("profile_check_val").value=0;
    }
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

