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

if($_REQUEST['trsns_pname']=="supplier-ledger-inst-receive-goods")
{
    $trsns_pname = "supplier-ledger-inst-receive-goods";
    $select_query = "select * from payment_plan where id=".$_REQUEST['id']." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
     
    
    $old_trans_id = $select_data['trans_id'];
    $old_cust_id = $select_data['cust_id'];
    $old_project_id = $select_data['on_project'];
    $old_amount = $select_data['debit'];
    $old_gst_amount = $select_data['gst_amount'];
    $old_description = $select_data['description'];
    $old_payment_date = $select_data['payment_date'];
    $old_payment_subdivision = $select_data['subdivision'];
    $old_link_id = $select_data['link_id'];
    $old_id = $select_data['id'];
    $id_first_cust = $select_data['id'];
    $id_second_proj = $select_data['link_id'];
    $id_third_bankpay = $select_data['link2_id'];
    $id_four_cust_pay = $select_data['link3_id'];
    
    
    $select_query_pay = "select * from payment_plan where id=".$id_third_bankpay." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result_pay = mysql_query($select_query_pay) or die('error in query select supplier query '.mysql_error().$select_query_pay);
    $select_data_pay = mysql_fetch_array($select_result_pay);
    //echo "$select_query_pro";
    $old_pay_bank_id = $select_data_pay['bank_id'];
    $old_pay_amount = $select_data_pay['debit'];
    $old_pay_method = $select_data_pay['payment_method'];
    $old_pay_checkno = $select_data_pay['payment_checkno'];
    $old_pay_payment_date = $select_data_pay['payment_date'];
     $old_payment_flag =  $select_data['payment_flag'];
   
       
}else 
if($_REQUEST['trsns_pname']=="supplier-ledger-inst-make-payment")
{
    $trsns_pname = "supplier-ledger-inst-make-payment";
    
    $select_query_pay = "select * from payment_plan where id=".$_REQUEST['id']." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result_pay = mysql_query($select_query_pay) or die('error in query select supplier query '.mysql_error().$select_query_pay);
    $select_data_pay = mysql_fetch_array($select_result_pay);
    //echo "$select_query_pro";
    $old_pay_bank_id = $select_data_pay['on_bank'];
    $old_pay_amount = $select_data_pay['credit'];
    $old_pay_method = $select_data_pay['payment_method'];
    $old_pay_checkno = $select_data_pay['payment_checkno'];
    $id_first_cust = $select_data_pay['link2_id'];
    $old_pay_payment_date = $select_data_pay['payment_date'];
    $old_payment_flag =  $select_data_pay['payment_flag'];
   
    $select_query = "select * from payment_plan where id=".$id_first_cust." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
     
    $old_trans_id = $select_data['trans_id'];
    $old_cust_id = $select_data['cust_id'];
    $old_project_id = $select_data['on_project'];
    $old_amount = $select_data['debit'];
    $old_gst_amount = $select_data['gst_amount'];
    $old_description = $select_data['description'];
    $old_payment_date = $select_data['payment_date'];
    $old_payment_subdivision = $select_data['subdivision'];
    $old_link_id = $select_data['link_id'];
    $old_id = $select_data['id'];
    $id_first_cust = $select_data['id'];
    $id_second_proj = $select_data['link_id'];
    $id_third_bankpay = $select_data['link2_id'];
    $id_four_cust_pay = $select_data['link3_id'];
   // $old_payment_flag =  $select_data['payment_flag'];  
}
else if($_REQUEST['trsns_pname']=="project-ledger-inst-receive-goods")
{
    
if($_REQUEST['back']!="")
   {
       $trsns_pname = "By subdivision";
   }
    else{
   //     $trsns_pname = "project-ledger";
    $trsns_pname = "project-ledger-inst-receive-goods";
    }

   
    $select_query = "select * from payment_plan where id=".$_REQUEST['id']." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
     
   //$old_trans_id,$old_cust_id,$old_project_id,$old_amount,$old_description,$old_payment_date,$old_payment_subdivision,$id_first_cust,$id_second_proj,$id_third_bankpay,$id_four_cust_pay
   //$old_pay_bank_id,$old_pay_amount,$old_pay_method,$old_pay_checkno
    
    $old_trans_id = $select_data['trans_id'];
    $old_cust_id = $select_data['on_customer'];
    $old_project_id = $select_data['project_id'];
    $old_amount = $select_data['credit'];
    $old_gst_amount = $select_data['gst_amount'];
    $old_description = $select_data['description'];
    $old_payment_date = $select_data['payment_date'];
    $old_payment_subdivision = $select_data['subdivision'];
    $old_link_id = $select_data['link_id'];
    $old_id = $select_data['id'];
    $id_first_cust = $select_data['link_id'];
    $id_second_proj = $select_data['id'];
    $id_third_bankpay = $select_data['link2_id'];
    $id_four_cust_pay = $select_data['link3_id'];
    $old_payment_flag =  $select_data['payment_flag'];
    
    
    $select_query_pay = "select * from payment_plan where id=".$id_third_bankpay." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result_pay = mysql_query($select_query_pay) or die('error in query select supplier query '.mysql_error().$select_query_pay);
    $select_data_pay = mysql_fetch_array($select_result_pay);
    //echo "$select_query_pro";
    $old_pay_bank_id = $select_data_pay['bank_id'];
    $old_pay_amount = $select_data_pay['debit'];
    $old_pay_method = $select_data_pay['payment_method'];
    $old_pay_checkno = $select_data_pay['payment_checkno'];
    $old_pay_payment_date = $select_data_pay['payment_date'];
       
}else 
if($_REQUEST['trsns_pname']=="bank-ledger-inst-make-payment")
{
    $trsns_pname = "bank-ledger-inst-make-payment";
    
    $select_query_pay = "select * from payment_plan where id=".$_REQUEST['id']." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result_pay = mysql_query($select_query_pay) or die('error in query select supplier query '.mysql_error().$select_query_pay);
    $select_data_pay = mysql_fetch_array($select_result_pay);
    //echo "$select_query_pro";
    $old_pay_bank_id = $select_data_pay['bank_id'];
    $old_pay_amount = $select_data_pay['debit'];
    $old_pay_method = $select_data_pay['payment_method'];
    $old_pay_checkno = $select_data_pay['payment_checkno'];
    $id_first_cust = $select_data_pay['link2_id'];
    $old_pay_payment_date = $select_data_pay['payment_date'];
    
    $select_query = "select * from payment_plan where id=".$id_first_cust." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
     
   //$old_trans_id,$old_cust_id,$old_project_id,$old_amount,$old_description,$old_payment_date,$old_payment_subdivision,$id_first_cust,$id_second_proj,$id_third_bankpay,$id_four_cust_pay
   //$old_pay_bank_id,$old_pay_amount,$old_pay_method,$old_pay_checkno
   $old_payment_flag =  $select_data['payment_flag'];
    
    $old_trans_id = $select_data['trans_id'];
    $old_cust_id = $select_data['cust_id'];
    $old_project_id = $select_data['on_project'];
    $old_amount = $select_data['debit'];
    $old_gst_amount = $select_data['gst_amount'];
    
    $old_description = $select_data['description'];
    $old_payment_date = $select_data['payment_date'];
    $old_payment_subdivision = $select_data['subdivision'];
    $old_link_id = $select_data['link_id'];
    $old_id = $select_data['id'];
    $id_first_cust = $select_data['id'];
    $id_second_proj = $select_data['link_id'];
    $id_third_bankpay = $select_data['link2_id'];
    $id_four_cust_pay = $select_data['link3_id'];
    
}

/*     Create  Account   */


if(trim($_REQUEST['action_perform']) == "add_project")
{
	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
	$from_arr = explode("- ",$_REQUEST['from']);
	$cust_id = $from_arr[1];
	$project_id = get_field_value("id","project","name",$_REQUEST['project']);
	$amount=mysql_real_escape_string(trim($_REQUEST['amount']));
	$amount_gst_amount=mysql_real_escape_string(trim($_REQUEST['amount_gst_amount']));

    //add by amit
    $invoice_receiver_data = explode(" - ", $_REQUEST['invoice_receiver']);
	$invoice_receiver_id = $invoice_receiver_data[1];

    //added by amit
    if($_REQUEST['supplier_invoice_number'])
    {$supplier_invoice_number = $_REQUEST['supplier_invoice_number'];}

	$description=mysql_real_escape_string(trim($_REQUEST['description']));
	//$subdivision=mysql_real_escape_string(trim($_REQUEST['subdivision']));
    $subdivision = get_field_value("id","subdivision","name",$_REQUEST['subdivision']);
	///////payment Fiels values ///////
    $pay_from_arr = explode(" -",$_REQUEST['pay_form']);
    $pay_bank_id = get_field_value("id","bank","bank_account_name",$pay_from_arr[0]);
	$pay_amount=mysql_real_escape_string(trim($_REQUEST['pay_amount']));
    
    $pay_method=mysql_real_escape_string(trim($_REQUEST['pay_method']));
    $pay_checkno=mysql_real_escape_string(trim($_REQUEST['pay_checkno']));
    //////////////
    ///  $id_first_cust,$id_second_proj,$id_third_bankpay,$id_four_cust_pay
    $id_first_cust=mysql_real_escape_string(trim($_REQUEST['id_first_cust']));
    $id_second_proj=mysql_real_escape_string(trim($_REQUEST['id_second_proj']));
    $id_third_bankpay=mysql_real_escape_string(trim($_REQUEST['id_third_bankpay']));
    $id_four_cust_pay=mysql_real_escape_string(trim($_REQUEST['id_four_cust_pay']));
    
    
	$trans_id = mysql_real_escape_string(trim($_REQUEST['trans_id']));
	
	
	$query="update payment_plan set  cust_id = '".$cust_id."', debit = '".$amount."', gst_amount = '".$amount_gst_amount."', description = '".$description."', on_project = '".$project_id."', payment_date = '".strtotime($_REQUEST['payment_date'])."',subdivision = '".$subdivision."',update_date = '".getTime()."', invoice_issuer_id = '".$invoice_receiver_id."', supplier_invoice_number = '".$supplier_invoice_number."', invoice_id = '".$supplier_invoice_number."', updated_by = '".$_SESSION['userId']."', updated_on = '".time()."'  where  id = '".$id_first_cust."'";
	$result= mysql_query($query) or die('error in query '.mysql_error().$query);
	
	$link_id_1 = $id_first_cust;
	
	$query2="update payment_plan set  project_id = '".$project_id."', credit = '".$amount."', gst_amount = '".$amount_gst_amount."', description = '".$description."', on_customer = '".$cust_id."', payment_date = '".strtotime($_REQUEST['payment_date'])."',subdivision = '".$subdivision."',update_date = '".getTime()."', invoice_issuer_id = '".$invoice_receiver_id."', supplier_invoice_number = '".$supplier_invoice_number."', invoice_id = '".$supplier_invoice_number."', updated_by = '".$_SESSION['userId']."', updated_on = '".time()."'  where  id = '".$id_second_proj."'";
	$result2= mysql_query($query2) or die('error in query '.mysql_error().$query2);
	
	$link_id_2 = $id_second_proj;

      /*       payment query start           */
  
    $payment_flag=$_REQUEST['payment_flag'];
    if($payment_flag==1){
        if($payment_flag==1){

            $query_pay ="insert into payment_plan set trans_id = '".$trans_id."', bank_id = '".$pay_bank_id."', debit = '".$pay_amount."', description = '".$description."', on_customer = '".$cust_id."', on_project = '".$project_id."', payment_date = '".strtotime($_REQUEST['pay_payment_date'])."', payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."',link2_id = '".$link_id_1."',link3_id = '".$link_id_2."', trans_type = '".$trans_type_pay."', trans_type_name = '".$trans_type_name_pay."',create_date = '".getTime()."', invoice_issuer_id = '".$invoice_receiver_id."', supplier_invoice_number = '".$supplier_invoice_number."', invoice_id = '".$supplier_invoice_number."'";
            $result_pay= mysql_query($query_pay) or die('error in query '.mysql_error().$query_pay);
            
            $link_id_1_pay = mysql_insert_id();
            
            $query2_pay="insert into payment_plan set payment_flag = '".$payment_flag."' ,trans_id = '".$trans_id."', cust_id = '".$cust_id."', credit = '".$pay_amount."', description = '".$description."', on_project = '".$project_id."', on_bank = '".$pay_bank_id."', payment_date = '".strtotime($_REQUEST['pay_payment_date'])."' ,payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."',link2_id = '".$link_id_1."',link3_id = '".$link_id_2."',link_id = '".$link_id_1_pay."',trans_type = '".$trans_type_pay."', trans_type_name = '".$trans_type_name_pay."', create_date = '".getTime()."', invoice_issuer_id = '".$invoice_receiver_id."', supplier_invoice_number = '".$supplier_invoice_number."', invoice_id = '".$supplier_invoice_number."'";
            $result2_pay= mysql_query($query2_pay) or die('error in query '.mysql_error().$query2_pay);
            
            $link_id_2_pay = mysql_insert_id();
            $query5_pay="update payment_plan set payment_flag = '".$payment_flag."', link_id = '".$link_id_2_pay."' where id = '".$link_id_1_pay."'";
            $result5_pay= mysql_query($query5_pay) or die('error in query '.mysql_error().$query5_pay);
            
            $query5="update payment_plan set link2_id = '".$link_id_1_pay."',link3_id = '".$link_id_2_pay."' where id = '".$link_id_2."'";
            $result5= mysql_query($query5) or die('error in query '.mysql_error().$query5);
            
            $query5="update payment_plan set payment_flag = '".$payment_flag."',link2_id = '".$link_id_1_pay."',link3_id = '".$link_id_2_pay."' where id = '".$link_id_1."'";
            $result5= mysql_query($query5) or die('error in query '.mysql_error().$query5);
            $query5="update payment_plan set payment_flag = '".$payment_flag."' where id = '".$link_id_2."'";
            $result5= mysql_query($query5) or die('error in query '.mysql_error().$query5);
           
            
            }    
            
    }

    //,$id_third_bankpay,$id_four_cust_pay
    $old_payment_flag=$_REQUEST['old_payment_flag'];
    if($old_payment_flag=="1"){
   
    $query_pay ="update payment_plan set  bank_id = '".$pay_bank_id."', debit = '".$pay_amount."', description = '".$description."', on_customer = '".$cust_id."', on_project = '".$project_id."', payment_date = '".strtotime($_REQUEST['pay_payment_date'])."', payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."', update_date = '".getTime()."', invoice_issuer_id = '".$invoice_receiver_id."', supplier_invoice_number = '".$supplier_invoice_number."', invoice_id = '".$supplier_invoice_number."' where  id = '".$id_third_bankpay."'";
    $result_pay= mysql_query($query_pay) or die('error in query '.mysql_error().$query_pay);
    
    $link_id_1_pay = $id_third_bankpay;
    
    $query2_pay="update payment_plan set  cust_id = '".$cust_id."', credit = '".$pay_amount."', description = '".$description."', on_project = '".$project_id."', on_bank = '".$pay_bank_id."', payment_date = '".strtotime($_REQUEST['pay_payment_date'])."' ,payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."', update_date = '".getTime()."', invoice_issuer_id = '".$invoice_receiver_id."', supplier_invoice_number = '".$supplier_invoice_number."', invoice_id = '".$supplier_invoice_number."' where  id = '".$id_four_cust_pay."'";
    $result2_pay= mysql_query($query2_pay) or die('error in query '.mysql_error().$query2_pay);
    
    $link_id_2_pay = $id_four_cust_pay;

    //code added by amit
    $query_gst_due_info = "update gst_due_info set received_amount = '".$_REQUEST['pay_amount_gst']."' where trans_id = '".$_REQUEST['trans_id']."'";
    $result_gst_due_info = mysql_query($query_gst_due_info) or die('error in query '.mysql_error().$query_gst_due_info);
    //code added by amit
    $query_invoice_due_info = "update invoice_due_info set received_amount = '".$_REQUEST['pay_amount_wogst']."' where trans_id = '".$_REQUEST['trans_id']."'";
    $result_invoice_due_info = mysql_query($query_invoice_due_info) or die('error in query '.mysql_error().$query_invoice_due_info);

    }  
    
    /*       payment query end           */
	
	if($_FILES["attach_file"]["name"] != "")
    {
    $query3="insert into attach_file set attach_id = '".$link_id_1."', link_id = '".$link_id_2."',file_name = '".$new_file_name."'";
    $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
    $link_id_4 = mysql_insert_id();
    
    $attach_file_name=mysql_real_escape_string(trim($_REQUEST['attach_file_name']));
    $temp = explode(".", $_FILES["attach_file"]["name"]);
    $arr_size = count($temp);
    $extension = end($temp);
    $new_file_name = $attach_file_name.'_'.$link_id_4.'_'.date("d_M_Y").'.'.$extension;
    move_uploaded_file($_FILES["attach_file"]["tmp_name"],"transaction_files/" . $new_file_name);
        
        
    $query4="insert into attach_file set attach_id = '".$link_id_2."', link_id = '".$link_id_1."',old_id = '".$link_id_4."',file_name = '".$new_file_name."'";
    $result4= mysql_query($query4) or die('error in query '.mysql_error().$query4);
    $link_id_5 = mysql_insert_id();
    
    
    $query5_1="update attach_file set old_id = '".$link_id_5."',file_name = '".$new_file_name."' where id = '".$link_id_4."'";
    $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);
    //$link_id_1_pay,$link_id_2_pay
    ///  payment start//
    $query3_pay="insert into attach_file set attach_id = '".$link_id_1_pay."',old_id2 = '".$link_id_4."',old_id3 = '".$link_id_5."', link_id = '".$link_id_2_pay."',file_name = '".$new_file_name."'";
    $result3_pay= mysql_query($query3_pay) or die('error in query '.mysql_error().$query3_pay);
    $link_id_4_pay = mysql_insert_id();
       
    $query4_pay="insert into attach_file set attach_id = '".$link_id_2_pay."',old_id2 = '".$link_id_4."',old_id3 = '".$link_id_5."', link_id = '".$link_id_1_pay."',old_id = '".$link_id_4_pay."',file_name = '".$new_file_name."'";
    
    $result4_pay= mysql_query($query4_pay) or die('error in query '.mysql_error().$query4_pay);
    $link_id_5_pay = mysql_insert_id();
    
    $query5_1_pay="update attach_file set old_id = '".$link_id_5_pay."',file_name = '".$new_file_name."' where id = '".$link_id_4_pay."'";
    $result5_1_pay= mysql_query($query5_1_pay) or die('error in query '.mysql_error().$query5_1_pay);
   
     $query5_1="update attach_file set old_id2 = '".$link_id_4_pay."',old_id3 = '".$link_id_5_pay."' where id = '".$link_id_4."'";
    $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);
    
     $query5_1="update attach_file set old_id2 = '".$link_id_4_pay."',old_id3 = '".$link_id_5_pay."' where id = '".$link_id_5."'";
    $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);
    }
    else
    {
        $files = glob("drag uploads/*.*");
        $new_file_name=mysql_real_escape_string(trim($_REQUEST['attach_file_name'])).'_'.$link_id_4.'_'.date("d_M_Y");
        
        if(count($files) > 0)
        {
        $query3="insert into attach_file set attach_id = '".$link_id_1."', link_id = '".$link_id_2."',file_name = '".$new_file_name."'";
        $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
        $link_id_4 = mysql_insert_id();
    
            foreach($files as $file)
            {
                $basename = basename($file);
                $ext = substr(strrchr($basename,'.'),1);
                rename ("$file", "transaction_files/$new_file_name.$ext");
            }
                
    $new_file_name = $new_file_name.'.'.$ext;
    $query4="insert into attach_file set attach_id = '".$link_id_2."', link_id = '".$link_id_1."',old_id = '".$link_id_4."',file_name = '".$new_file_name."'";
    $result4= mysql_query($query4) or die('error in query '.mysql_error().$query4);
    $link_id_5 = mysql_insert_id();
    
    $query5_1="update attach_file set old_id = '".$link_id_5."',file_name = '".$new_file_name."' where id = '".$link_id_4."'";
    $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);
     ///  payment start//
    $query3_pay="insert into attach_file set attach_id = '".$link_id_1_pay."',old_id2 = '".$link_id_4."',old_id3 = '".$link_id_5."', link_id = '".$link_id_2_pay."',file_name = '".$new_file_name."'";
    $result3_pay= mysql_query($query3_pay) or die('error in query '.mysql_error().$query3_pay);
    $link_id_4_pay = mysql_insert_id();
       
    $query4_pay="insert into attach_file set attach_id = '".$link_id_2_pay."',old_id2 = '".$link_id_4."',old_id3 = '".$link_id_5."', link_id = '".$link_id_1_pay."',old_id = '".$link_id_4_pay."',file_name = '".$new_file_name."'";
    
    $result4_pay= mysql_query($query4_pay) or die('error in query '.mysql_error().$query4_pay);
    $link_id_5_pay = mysql_insert_id();
    
    $query5_1_pay="update attach_file set old_id = '".$link_id_5_pay."',file_name = '".$new_file_name."' where id = '".$link_id_4_pay."'";
    $result5_1_pay= mysql_query($query5_1_pay) or die('error in query '.mysql_error().$query5_1_pay);
   
    $query5_1="update attach_file set old_id2 = '".$link_id_4_pay."',old_id3 = '".$link_id_5_pay."' where id = '".$link_id_4."'";
    $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);
    
    $query5_1="update attach_file set old_id2 = '".$link_id_4_pay."',old_id3 = '".$link_id_5_pay."' where id = '".$link_id_5."'";
    $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);
        }
        else
        {
            $new_file_name = "";        
        }
    }
    
	$msg = "Instant Receive Goods and Payment successfully.";
	$flag = 1;
    
    $trsns_pname_1 = $_REQUEST['trsns_pname'];
   
    if($trsns_pname_1=="supplier-ledger-inst-receive-goods")
    {
        $msg = " Update successfully.";
        $flag = 1;
        echo "<script> location.href='supplier.php'; </script>";
    }
    
    if($trsns_pname_1=="supplier-ledger-inst-make-payment")
    {
        $msg = " Update successfully.";
        $flag = 1;
        echo "<script> location.href='supplier.php'; </script>";
    }
    
    if($trsns_pname_1=="project-ledger-inst-receive-goods")
    {
        $msg = " Update successfully.";
        $flag = 1;
        echo "<script> location.href='project.php'; </script>";
    }
    if($trsns_pname_1=="By subdivision")
    {
        $msg = " Update successfully.";
        $flag = 1;
        echo "<script>  location.href='subdivision.php'; </script>";
    }
    
    if($trsns_pname_1=="bank-ledger-inst-make-payment")
    {
        $msg = " Update successfully.";
        $flag = 1;
        echo "<script> location.href='bank-ledger.php?bank_id=".$select_data_pay['bank_id']."'; </script>";
    }
}
else
{
	$files = glob("drag uploads/*.*");
	if(count($files) > 0)
	{
		foreach($files as $file)
		{
      		unlink($file);
    	}
	}
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
<script type="text/javascript">
function findTotal()
{
<!-- //amount_sub,amount_gstper,amount_gst_amount, amount_grand, amount -->
     var amount_sub_1 = document.getElementById('amount_sub').value;
     var amount_gstper_1 = document.getElementById('amount_gstper').value;
     var gst_val;
     gst_val_1 = amount_sub_1* amount_gstper_1;
      gst_val= gst_val_1 /100;
      var grand_ttot =  parseFloat(amount_sub_1)+ parseFloat(gst_val);
    document.getElementById('amount').value = grand_ttot;
    document.getElementById('amount_grand').value = grand_ttot;
    document.getElementById('amount_gst_amount').value = gst_val;
}

    </script>

<body  data-home-page-title="" class="u-body u-xl-mode" data-lang="en">
  <?php include_once ("top_header2.php"); ?> 
  <?php include_once ("top_menu.php"); ?>
  <?php include_once("main_heading_open.php") ?>
  
	<table style="width:100%;" border="0" style="padding:0px 0px; margin-top:-10px;">
    <tr>
        <td style="align:top;" align="left">
        <h4 class="u-text-2 u-text-palette-1-base " style="padding:0px; margin:0px;">
       Edit Instant Purchases </h4>
  </td>
        <td width="" style="float:right;">
        <?php
                     if($trsns_pname=="supplier-ledger-inst-receive-goods")
    {
         ?> <a href="supplier.php" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>  <?php 
   
    }
    
    if($trsns_pname=="supplier-ledger-inst-make-payment")
    {
             ?> <a href="supplier.php" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>  <?php   
     }
    
    if($trsns_pname=="project-ledger-inst-receive-goods")
    {
           ?> <a href="project.php" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>  <?php    
     }
    if($trsns_pname=="By subdivision")
    {
           ?> <a href="subdivision.php" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>  <?php     
     }
    
    if($trsns_pname=="bank-ledger-inst-make-payment")
    {
            ?> <a href="bank-ledger.php?bank_id=<?= $select_data_pay['bank_id']?>" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>  <?php   
     }
      ?></td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
  <?php include_once("main_body_open.php") ?>
  <table width="100%" style="padding:10px;">
    <tr><td>
  <table width="98%" class="tbl_border">
    <tr><td>

   	<?php if($msg != "") { ?>
	<div class="sukses">
		<?php echo $msg; ?>
		</div>
	<?php } else if($error_msg != "") { ?>
	<div class="gagal">
		
		<?php echo $error_msg; ?>
		</div>
	<?php } ?>
	    	<div id="adddiv" >
	
	<form name="project_form" id="project_form" action="" method="post" enctype="multipart/form-data" >
    <input type="hidden" id="id_first_cust" name="id_first_cust" value="<?php echo $id_first_cust; ?>">
    <input type="hidden" id="id_second_proj" name="id_second_proj" value="<?php echo $id_second_proj; ?>">
    <input type="hidden" id="id_third_bankpay" name="id_third_bankpay" value="<?php echo $id_third_bankpay; ?>">
    <input type="hidden" id="id_four_cust_pay" name="id_four_cust_pay" value="<?php echo $id_four_cust_pay; ?>">
    <input type="hidden" id="trsns_pname" name="trsns_pname" value="<?php echo $trsns_pname; ?>">

    <link rel="stylesheet" href="css/jquery-ui.css" />
  <!--<script src="js/jquery-1.9.1.js"></script>-->
  <script src="js/jquery-ui.js"></script>

        <!-- Including the HTML5 Uploader plugin -->
        <script src="js/jquery.filedrop.js"></script>
        
        <!-- The main script file -->
        <script src="js/script.js"></script>        

    <table width="98%">
        <tr style="width: 50%;" >
            <td>
            <table width="98%" border="0">
            <tr><td colspan="2"> <h4 class="u-text-2 u-text-palette-1-base1 " style="padding:0px; margin:0px;">Instant Receive Goods</h4></td></tr>
            <tr><td width="125px">Transaction ID</td>
            <td style="color:#FF0000; font-weight:bold;"><input type="hidden" id="trans_id"  name="trans_id" value="<?php echo $old_trans_id; ?>"/>&nbsp;<?php echo $old_trans_id; ?></td></tr>
            <tr><td width="125px">Supplier Name</td>
            <td>
             <?php
             $sql_cus = "select cust_id,full_name from `customer` where cust_id=".$old_cust_id." and type = 'supplier'";
             $query_cus = mysql_query($sql_cus);
             $select_cus = mysql_fetch_array($query_cus);
            ?>
            <input type="text" id="from"  name="from" value="<?php echo $select_cus['full_name'].' - '.$select_cus['cust_id']; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>

            <tr>
            <td >Supplier Invoice No.</td>
            <td>
            <input type="text" id="supplier_invoice_number"  name="supplier_invoice_number" value="<?= $select_data['supplier_invoice_number'] ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span>
            </td>
            </tr>

			<tr><td width="125px">Invoice Receiver</td>
            <?php
            if($select_data['invoice_issuer_id'])
            {
                 $sql_issuer     = "select issuer_name,display_name from `invoice_issuer` where id='".$select_data['invoice_issuer_id']."'";
                 $query_issuer    = mysql_query($sql_issuer);
                 $select_issuer = mysql_fetch_array($query_issuer);
                 $invoice_receiver = $select_issuer['issuer_name']." - ".$select_data['invoice_issuer_id']." - ".$select_issuer['display_name'];
            }
            else{$invoice_receiver="";}
            ?>
            <td><input type="text" id="invoice_receiver"  name="invoice_receiver" value="<?= $invoice_receiver ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <tr><td >Project</td>
             <?php $project_nm = get_field_value("name","project","id",$old_project_id);  ?>
            <td><input type="text" id="project"  name="project" value="<?php echo $project_nm; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <tr><td >Sub Division Name</td>
            <td>
            <?php $subdivision_nm = get_field_value("name","subdivision","id",$old_payment_subdivision);  ?>
             <input type="text" id="subdivision"  name="subdivision" value="<?php echo $subdivision_nm; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span>
            
            </td></tr>
            
            <tr><td align="left" valign="top" >
            <?php
                    ///  Gst Calculation
                    //$old_gst_amount
                    $sub_tot=  $old_amount- $old_gst_amount;
                   // $gstper = ($select_data['gst_amount']/$subtot)*100;
                    $gst_per= ($old_gst_amount/$sub_tot)*100;
             ?>
            Sub Total</td>
            <td><input type="text"  name="amount_sub" id="amount_sub" value="<?php echo $sub_tot ; ?>"  />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>

            <tr><td align="left" valign="top" >GST (%)</td>
            <td><input type="text" onblur="findTotal()"  name="amount_gstper" id="amount_gstper" value="<?php echo $gst_per ; ?>" onblur="findTotal()"  />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>

            <tr><td align="left" valign="top" >GST Amount</td>
            <td><input type="text"  name="amount_gst_amount" id="amount_gst_amount" value="<?php echo $old_gst_amount ; ?>" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
             <!-- //amount_sub,amount_gstper,amount_gst_amount, amount_grand, amount -->


            <tr><td align="left" valign="top" >Grand Total</td>
            <td><input type="hidden"  name="amount_grand" id="amount_grand" value="<?php echo $old_amount ; ?>" />
            <input type="text"  name="amount" id="amount" value="<?php echo $old_amount ; ?>" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>

            
           <!-- <tr><td align="left" valign="top" >Amount</td>
            <td><input type="text"  name="amount" id="amount" value="<?php echo $old_amount ; ?>" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            -->
            <tr><td align="left" valign="top" >Date</td>
            
        
            <td><input type="text"  name="payment_date" id="payment_date" value="<?php echo date("d-m-Y",$old_payment_date); ?>" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('payment_date')" style="cursor:pointer"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td valign="top" >Description</td>
            <td><textarea name="description" id="description" style="width:250px; height:100px;"><?php echo $old_description; ?></textarea>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <tr><td valign="top" >Attach File</td>
            <td><input type="file" name="attach_file" id="attach_file" value="" onChange="return hide_drag();" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <tr><td valign="top" >Attach File Name</td>
            <td><input type="text" id="attach_file_name"  name="attach_file_name" value="" autocomplete="off"/></td></tr>
            
            <tr><td valign="top" colspan="2" >
            </td></tr>
            
            
            <tr><td></td><td align="right">
            <input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
            </td></tr>
        </table>
            </td>
            <td style="width: 50%;" valign="top">
            <input type="hidden" name="old_payment_flag" id="old_payment_flag" value="<?php echo $old_payment_flag; ?>">
            <?php 
               if($old_payment_flag=="1"){
                   
                   ?>
                   <input type="hidden" name="old_payment_flag" id="old_payment_flag" value="<?php echo $old_payment_flag; ?>">
                
                <table width="95%">
                <tr><td colspan="2"> <h4 class="u-text-2 u-text-palette-1-base1 " style="padding:0px; margin:0px;">Instant Payment Details</h4></td></tr>
            <tr><td width="125px">&nbsp;</td>
            <td style="color:#FF0000; font-weight:bold;">&nbsp;</td></tr>
            <tr><td width="125px">Paid From</td>
            <?php
             $sql_bank     = "select bank_account_name,bank_account_number from `bank`  where id=".$old_pay_bank_id." ";
             $query_bank     = mysql_query($sql_bank);
             $select_bank = mysql_fetch_array($query_bank);
  
            ?>
            <td><input type="text" id="pay_form"  name="pay_form" value="<?php echo $select_bank['bank_account_name'].' - '.$select_bank['bank_account_number']; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <tr><td align="left" valign="top" >Date</td>
        
            <td><input type="text"  name="pay_payment_date" id="pay_payment_date" value="<?php echo date("d-m-Y",$old_pay_payment_date); ?>" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('pay_payment_date')" style="cursor:pointer"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td >Payment Mothod</td>
            <td><br>
            <input type="radio" id="pay_method" name="pay_method" <?php if($old_pay_method=='check'){ echo "checked='checked'"; } ?>   onchange=" return checkno_create();" value="check">
            <label for="male">Cheque</label>&nbsp;&nbsp;
            <input type="radio" id="pay_method" name="pay_method" <?php if($old_pay_method=='bank'){ echo "checked='checked'"; } ?>  onchange="return checkno_create1();" value="bank">
            <label for="female">Bank</label>&nbsp;&nbsp;
            <input type="radio" id="pay_method" name="pay_method" <?php if($old_pay_method=='cash'){ echo "checked='checked'"; } ?>  onchange="return checkno_create1();" value="cash">
            <label for="other">Cash</label>&nbsp;&nbsp;<br><br>
            </td></tr>
            <tr>
                
                <td colspan="2">
                    <div id="pay_check" align="left" <?php if($old_pay_method=='check'){ echo "style='display:block;'"; }else{ echo "style='display:none;'"; } ?>  >
                    <table>
                        <tr>
                            <td width="120px">Cheque No.</td>
                            <td><input type="text"  name="pay_checkno" id="pay_checkno" value="<?php echo $old_pay_checkno; ?>" /><br></td>
                        </tr>
                    </table>
                     <br>
                    </div>
                </td>
            </tr>           
            <?php
            //added by amit
            $select_query_gst = "select * from gst_due_info where trans_id = '".$_REQUEST['trans_id']."'";
            $select_result_gst = mysql_query($select_query_gst) or die('error in query select supplier query '.mysql_error().$select_query_gst);
            $select_data_gst = mysql_fetch_array($select_result_gst);
            $old_gst_paid_amount = $select_data_gst['received_amount'];
            $old_net_amount = $old_pay_amount - $old_gst_paid_amount;
            ?>
            <tr><td align="left" valign="top" >Net Amount Paid</td>
            <td><input type="text"  name="pay_amount_wogst" id="pay_amount_wogst" onblur="setTotalAmountPaid()" value="<?php echo $old_net_amount; ?>" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <tr><td align="left" valign="top" >GST Paid</td>
            <td><input type="text"  name="pay_amount_gst" id="pay_amount_gst" onblur="setTotalAmountPaid()" value="<?php echo $old_gst_paid_amount; ?>" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>

            <tr><td align="left" valign="top" >Total Amount Paid</td>
            <td><input type="text"  name="pay_amount" id="pay_amount" value="<?php echo $old_pay_amount; ?>" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>

        </table>
        <?php
               }else{ 
                ?>
                <table width="95%">
                <tr><td > <h4 class="u-text-2 u-text-palette-1-base1 " style="padding:0px; margin:0px;">
       Instant Payment Details</h4></td></tr>
                <tr><td ><input type="checkbox" name="pay_flag" id="pay_flag" onchange=" return checkpay_flag();"  >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Payment</b></td></tr>
            <tr>
            <td style="color:#FF0000; font-weight:bold;">&nbsp;</td></tr>
            <tr><td>
            <div id="pay_flag_div"   style="display:none; " >
            <table width="100%">
            <tr><td width="125px">Paid From</td>
            <td><input type="text" id="pay_form"  name="pay_form" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td align="left" valign="top" >Payment Date</td>
        
            <td><input type="text"  name="pay_payment_date" id="pay_payment_date" value="<?php echo $_REQUEST['payment_date']; ?>" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('pay_payment_date')" style="cursor:pointer"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <tr><td >Payment Mothod</td>
            <td><br>
            <input type="radio" id="pay_method" name="pay_method"  onchange=" return checkno_create();" value="check">
            <label for="male">Cheque</label>&nbsp;&nbsp;
            <input type="radio" id="pay_method" name="pay_method"  onchange="return checkno_create1();" value="bank">
            <label for="female">Bank</label>&nbsp;&nbsp;
            <input type="radio" id="pay_method" name="pay_method"  onchange="return checkno_create1();" value="cash">
            <label for="other">Cash</label>&nbsp;&nbsp;<br><br>
            </td></tr>
            <tr>
                
                <td colspan="2">
                    <div id="pay_check" align="left"  style="display:none; " >
                    <table>
                        <tr>
                            <td width="120px">Cheque No.</td>
                            <td><input type="text"  name="pay_checkno" id="pay_checkno" value="" /><br></td>
                        </tr>
                    </table>
                     <br>
                    </div>
                </td>
            </tr>            
            
            <!--<tr><td align="left" valign="top" >Net Amount Paid</td>
            <td><input type="text"  name="pay_amount" id="pay_amount" value="" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>-->

            <?php
            //added by amit
            $select_query_gst = "select * from gst_due_info where trans_id = '".$_REQUEST['trans_id']."'";
            $select_result_gst = mysql_query($select_query_gst) or die('error in query select supplier query '.mysql_error().$select_query_gst);
            $select_data_gst = mysql_fetch_array($select_result_gst);
            $old_gst_paid_amount = $select_data_gst['received_amount'];
            $old_net_amount = $old_pay_amount - $old_gst_paid_amount;
            ?>
            <tr><td align="left" valign="top" >Net Amount Paid</td>
            <td><input type="text"  name="pay_amount_wogst" id="pay_amount_wogst" onblur="setTotalAmountPaid()" value="<?php echo $old_net_amount; ?>" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <tr><td align="left" valign="top" >GST Paid</td>
            <td><input type="text"  name="pay_amount_gst" id="pay_amount_gst" onblur="setTotalAmountPaid()" value="<?php echo $old_gst_paid_amount; ?>" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>

            <tr><td align="left" valign="top" >Total Amount Paid</td>
            <td><input type="text"  name="pay_amount" id="pay_amount" value="<?php echo $old_pay_amount; ?>" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>


            <input type="hidden" name="payment_flag" id="payment_flag" value="0">
        </table></div>
    </td></tr></table>
                <?php 
               }
                   ?>
            </td>
        </tr>
    </table>
				<input type="hidden" name="action_perform" id="action_perform" value="" >
		
		</form>
		
		</div>
		
		</td></tr></table>
        </td></tr></table>
		

		<?php include_once("main_body_close.php") ?>
        <?php include_once ("footer.php"); ?>
        

</body>
</html>
<script>

function setTotalAmountPaid()
{
    if(document.getElementById('pay_amount_gst').value)
    document.getElementById('pay_amount').value = Number.parseFloat(document.getElementById('pay_amount_gst').value) + Number.parseFloat(document.getElementById('pay_amount_wogst').value);
    else
    document.getElementById('pay_amount').value = Number.parseFloat(document.getElementById('pay_amount_wogst').value);
}

//checkno_create(),pay_check,pay_checkno,pay_method
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
	if($("#from").val() == "")
	{
		alert("Please enter from data.");
		$("#from").focus();
		return false;
	}
	else if($("#project").val() == "")
	{
		alert("Please enter project name.");
		$("#to").focus();
		return false;
	}
	else if($("#subdivision").val() == "")
	{
		alert("Please Select Subdivision Option.");
		$("#subdivision").focus();
		return false;
	}
	else if($("#amount").val() == "")
	{
		alert("Please enter amount.");
		$("#amount").focus();
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
		if(confirm("Do you want to print?!!!!!....."))
		{
			
			var text = '<table cellpadding="10" cellspacing="0" border="0" width="95%"><tr><td width="125px">From</td><td>'+$("#from").val()+'</td></tr><tr><td >Project</td><td>'+$("#project").val()+'</td></tr><tr><td>Amount</td><td>Rs. &nbsp;'+$("#amount").val()+'</td></tr><tr><td>Date</td><td>'+$("#payment_date").val()+'</td></tr><tr><td >Description</td><td>'+$("#description").val()+'</td></tr></table>';
						printMe=window.open();
						printMe.document.write(text);
						printMe.print();
						printMe.close();
						
			$("#action_perform").val("add_project");
			$("#project_form").submit();
			return true;
		}
		else
		{
			$("#action_perform").val("add_project");
			$("#project_form").submit();
			return true;
		}
		
	}
	
}

function checkpay_flag()
{
   // alert("hello");
    //pay_form,pay_payment_date,pay_method,pay_checkno,pay_amount
    if($("#payment_flag").val() == "0")
    {
        document.getElementById('payment_flag').value="1";
        document.getElementById('pay_flag_div').style.display='block';
        
    }else
    {
        document.getElementById('payment_flag').value="0";
        document.getElementById('pay_flag_div').style.display='none';
        document.getElementById('pay_form').value="";
        document.getElementById('pay_payment_date').value="";
        document.getElementById('pay_method').value="";
        document.getElementById('pay_checkno').value="";
        document.getElementById('pay_amount').value="";
        
    }
   /* document.getElementById('pay_flag_div').style.display='block';
    */
}


</script>

	<script>
	$(document).ready(function(){
		$( "#from" ).autocomplete({
			source: "supplier-ajax.php"
		});
        $( "#invoice_receiver" ).autocomplete({
			source: "invoice_issuer-ajax.php"
		});
		$( "#project" ).autocomplete({
			source: "project-ajax.php"
		});
         $( "#subdivision" ).autocomplete({
            source: "subdivision2_ajax.php"
        });
        $( "#pay_form" ).autocomplete({
            source: "bankcash-ajax.php"
        });
		
	})
	</script>
	
<?php 

if($flag == 1)
{
	?>
	<script>
	if(confirm("Do you want to print?!!!!!....."))
		{
			
			var text = '<table cellpadding="10" cellspacing="0" border="0" width="95%"><tr><td colspan="2" >Receive Goods</td></tr><tr><td width="125px">Transaction ID</td><td><?php echo $_REQUEST['trans_id']; ?></td></tr><tr><td width="125px">From</td><td><?php echo $_REQUEST['from']; ?></td></tr><tr><td >Project</td><td><?php echo $_REQUEST['project']; ?></td></tr><tr><td>Amount</td><td>Rs. &nbsp;<?php echo $_REQUEST['amount']; ?></td></tr><tr><td>Date</td><td><?php echo $_REQUEST['payment_date']; ?></td></tr><tr><td >Description</td><td><?php echo $_REQUEST['description']; ?></td></tr></table>';
			printMe=window.open();
			printMe.document.write(text);
			printMe.print();
			printMe.close();
						
			
		}
	</script>
	<?php
}

?>
