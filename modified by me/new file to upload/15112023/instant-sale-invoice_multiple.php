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

//testing
//print_r($_REQUEST);
/*     Create  Account   */

  $date_new_id_1=$_REQUEST['date_new_id'];  
if(trim($_REQUEST['action_perform']) == "add_project")
{
    $i_in=1;
    $multi_date[0]=$_REQUEST['date_new_id_old'];
    for($in = 0; $in < count($date_new_id_1); $in++) {
        $multi_date[$i_in]=$date_new_id_1[$in];
        $i_in++;
    }
    
    for($in = 0; $in < count($multi_date); $in++) {
        
$wi = 0;
    while($wi<1)
    {
        $trans_id_new = rand(100000,999999);
        $ss="select id from payment_plan where trans_id=".$trans_id_new."";
        $sr=mysql_query($ss);
        $tot_rw=mysql_num_rows($sr);
        if($tot_rw == 0)
        {
            break;                                
        }
    }
    $qry_max=" select max(invoice_id) as max_invoice from goods_details";
       $qry_max_result = mysql_query($qry_max);
       
       $qry_max_row = mysql_fetch_array($qry_max_result);
       if($qry_max_row[max_invoice]<1)
       {
            $invoice_idnew_new="1001";       
       }else{
       $invoice_idnew_new =$qry_max_row[max_invoice]+1;    
       }

	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
	$from_arr = explode("- ",$_REQUEST['from']);
	$cust_id = $from_arr[1];
	
	$amount=mysql_real_escape_string(trim($_REQUEST['amount']));
	$description=mysql_real_escape_string(trim($_REQUEST['description']));
	$invoice_issuer_arr = explode("- ",$_REQUEST['invoice_issuer']);
    $invoice_issuer_id = $invoice_issuer_arr[1];
    ///////payment Fiels values ///////
    $pay_from_arr = explode(" -",$_REQUEST['pay_form']);
    $pay_bank_id = get_field_value("id","bank","bank_account_name",$pay_from_arr[0]);
    $pay_amount=mysql_real_escape_string(trim($_REQUEST['pay_amount']));
    
    $pay_method=mysql_real_escape_string(trim($_REQUEST['pay_method']));
    $pay_checkno=mysql_real_escape_string(trim($_REQUEST['pay_checkno']));
    //////////////
    
    /* goods detail start*/
        
    $desc_t=$_REQUEST['desc_t'];
    $qty_t=$_REQUEST['qty_t'];
    $unit_price_1=$_REQUEST['unit_price_1'];
    $sub_total=$_REQUEST['sub_total'];
    $gst=$_REQUEST['gst'];
    $tds=$_REQUEST['tds'];
    $gst_amount=$_REQUEST['gst_amount'];
    $total=$_REQUEST['total'];
    //totall value fields
    $qty_tot=$_REQUEST['qty_tot'];
    $unit_price_tot=$_REQUEST['unit_price_tot'];
    $sub_total_tot=$_REQUEST['sub_total_tot'];
    $total_tot=$_REQUEST['total_tot'];
       
    $project_array =$_REQUEST['project'];
    $subdivision_array =$_REQUEST['subdivision'];
    $gst_subdivision_n = get_field_value("id","gst_subdivision","name",$_REQUEST['gst_subdivision_1']);
    $tds_subdivision_n = get_field_value("id","tds_subdivision","name",$_REQUEST['tds_subdivision_1']);
    // $gst_subdivision_n =$_REQUEST['gst_subdivision_1'];
    //$tds_subdivision_n=$_REQUEST['tds_subdivision_1'];
    $hsn_code=$_REQUEST['hsn_code'];
    $gst_amount_tot=$_REQUEST['gst_amount_tot'];
    $tds_amount_tot=$_REQUEST['tds_amount_tot'];
    $invoice_pay_amount=$_REQUEST['pay_amount_final'];
    $invoice_id_print = $_REQUEST['invoice_id_print'];
       
    /* goods detail end  */

     $trans_type = 24;
     $trans_type_name = "instmulti_sale_goods" ;
     $trans_id = mysql_real_escape_string(trim($trans_id_new));
     $invoice_idnew = mysql_real_escape_string(trim($invoice_idnew_new));
     $date_new_new=$multi_date[$in];
     $payment_date_n=strtotime($date_new_new);
    $query2="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."', credit = '".$total_tot."',description = '".$description."', invoice_id = '".$invoice_idnew."', payment_date = '".$payment_date_n."',link_id = '".$link_id_1."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',trans_type = '".$trans_type."', trans_type_name = '".$trans_type_name."', create_date = '".getTime()."',printable_invoice_number ='".$invoice_id_print."',invoice_type = '".$_REQUEST['invoice_type']."', added_by = '".$_SESSION['userId']."', added_on = '".time()."'";
    //echo $query2;
    
    $result2= mysql_query($query2) or die('error in query '.mysql_error().$query2);
    
    $link_id_2 = mysql_insert_id();
    /*       payment query start           */
    //payment_flag
    $payment_flag=$_REQUEST['payment_flag'];
    $trans_type_pay = 22;
    $trans_type_name_pay= "instmulti_receive_payment_invoice" ;
    $invoice_received_flag=0;
    $tds_received_flag=0;
    $gst_received_flag=0;
   //gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',
    if($in==0){
    if($payment_flag==1){
    $query_pay ="insert into payment_plan set trans_id = '".$trans_id."', bank_id = '".$pay_bank_id."', credit = '".$pay_amount."',  description = '(Invoice Amount Received ): ".$description."', on_customer = '".$cust_id."', on_project = '".$invoice_idnew."',payment_flag = '".$payment_flag."', payment_date = '".strtotime($_REQUEST['pay_payment_date'])."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_issuer_id = '".$invoice_issuer_id."' ,payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."',link2_id = '".$link_id_1."',link3_id = '".$link_id_2."', trans_type = '".$trans_type_pay."', trans_type_name = '".$trans_type_name_pay."',create_date = '".getTime()."',printable_invoice_number ='".$invoice_id_print."',invoice_type = '".$_REQUEST['invoice_type']."', added_by = '".$_SESSION['userId']."', added_on = '".time()."'";
    $result_pay= mysql_query($query_pay) or die('error in query '.mysql_error().$query_pay);
    
   
    $link_id_1_pay = mysql_insert_id();
    $pp_linkid_2_invoice=$link_id_1_pay; 
    
    $query2_pay="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$invoice_idnew."', debit = '".$pay_amount."', description = '(Invoice Amount Received ): ".$description."', on_project = '".$project_id."', on_bank = '".$pay_bank_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['pay_payment_date'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."', invoice_pay_amount='".$invoice_pay_amount."',payment_checkno = '".$pay_checkno."',link2_id = '".$link_id_1."',link3_id = '".$link_id_2."',link_id = '".$link_id_1_pay."',trans_type = '".$trans_type_pay."', trans_type_name = '".$trans_type_name_pay."', create_date = '".getTime()."',printable_invoice_number ='".$invoice_id_print."',invoice_type = '".$_REQUEST['invoice_type']."', added_by = '".$_SESSION['userId']."', added_on = '".time()."'";
    $result2_pay= mysql_query($query2_pay) or die('error in query '.mysql_error().$query2_pay);
    
    $link_id_2_pay = mysql_insert_id();   
    $pp_linkid_1_invoice=$link_id_2_pay; 

    //$link_id_2
   
    $query5="update payment_plan set payment_flag = '".$payment_flag."' where id = '".$link_id_2."'";
    $result5= mysql_query($query5) or die('error in query '.mysql_error().$query5);
    $invoice_received_flag=1;
    $receiv_amount_invoice=$invoice_pay_amount-$pay_amount;

    $query3_inv="insert into invoice_due_info set invoice_id = '".$invoice_idnew."',payment_plan_id = '".$link_id_2."',trans_id = '".$trans_id."', pp_linkid_1 = '".$pp_linkid_1_invoice."',pp_linkid_2 = '".$pp_linkid_2_invoice."',	due_date = '".strtotime($_REQUEST['pay_payment_date'])."',description = '(Invoice Amount Received ): ".$description."',received_amount = '".$pay_amount."', amount = '".$invoice_pay_amount."',create_date = '".getTime()."'";
    
    $result3_inv= mysql_query($query3_inv) or die('error in query '.mysql_error().$query3_inv);
    $link_id_4_invoice = mysql_insert_id();
    

    $query5_pay_invoice="update payment_plan set invoice_flag = '1',invoice_pay_id='".$pp_linkid_1_invoice."',invoice_due_pay_id='".$link_id_4_invoice."' where id = '".$link_id_2_pay."'";
        $result5_pay_invoice= mysql_query($query5_pay_invoice) or die('error in query '.mysql_error().$query5_pay_invoice);

        $query5_pay_invoice="update payment_plan set invoice_flag = '1',invoice_pay_id='".$pp_linkid_1_invoice."',invoice_due_pay_id='".$link_id_4_invoice."' where id = '".$link_id_2_pay."'";
        $result5_pay_invoice= mysql_query($query5_pay_invoice) or die('error in query '.mysql_error().$query5_pay_invoice);

        //$query1_2="update payment_plan set invoice_flag = '1',invoice_pay_id='".$pp_linkid_1_invoice."',invoice_due_pay_id='".$link_id_4_invoice."' where id = '".$due_payment_id_1."'";
        //$result1_2= mysql_query($query1_2) or die('error in query '.mysql_error().$query1_2);
        //$invoice_received_flag=1;
        //$receiv_amount_invoice=$invoice_amount_tot-$due_amount_pay_1;

       

    }
    }
    else
    {
        $payment_flag= 0;   
    }
      if($in>0)
          {
              $multi_invoice_flag=1;
               $multi_invoice_list=$multi_invoice_list.",".$invoice_idnew;
               $multi_invoice_id = $multi_invoice_id.",".$link_id_2;
          }
          else
          {
              $multi_invoice_flag=0; 
              $multi_invoice_list = $invoice_idnew;
              $multi_invoice_id=$link_id_2 ;
          }
    
    /*       payment query end           */
    
    /*   goods detail start    */
       
       $ij=1;
       $multi_project_id="";
       $desc_total_n="";
       $goods_details_idlist="";
       $tot_tds_amount="";
       $subdivision="";
       $gst_subdivision="";
    for($i = 0; $i < count($desc_t); $i++) {
    $project_id = get_field_value("id","project","name",$project_array[$i]);
    $subdivision = get_field_value("id","subdivision","name",$subdivision_array[$i]);
    
    $sub_tot_perproject="";
    $gst_amount_perproject="";
    $tds_per_perproject="";
    $tds_per_amount1="";
    $sub_tot_perproject = $qty_t[$i]*$unit_price_1[$i];
    $gst_amount_perproject = $gst_amount[$i];
    $tds_per_perproject=$tds[$i];
    $tds_per_amount1=($sub_tot_perproject*$tds_per_perproject)/100;
    $tot_tds_amount=$tot_tds_amount+$tds_per_amount1;
    //gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',
    $query="insert into payment_plan set trans_id = '".$trans_id."', project_id = '".$project_id."',  invoice_id = '".$invoice_idnew."', debit = '".$sub_tot_perproject."', tds_per = '".$tds_per_perproject."', tds_amount = '".$tds_per_amount1."', gst_amount = '".$gst_amount_perproject."',invoice_pay_amount='".$invoice_pay_amount."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."', description = '".$desc_t[$i]."', hsn_code = '".$hsn_code[$i]."',on_customer = '".$cust_id."', payment_flag = '".$payment_flag."',payment_date = '".$payment_date_n."',subdivision = '".$subdivision."',invoice_issuer_id = '".$invoice_issuer_id."',trans_type = '".$trans_type."', trans_type_name = '".$trans_type_name."', create_date = '".getTime()."',printable_invoice_number ='".$invoice_id_print."',invoice_type = '".$_REQUEST['invoice_type']."', added_by = '".$_SESSION['userId']."', added_on = '".time()."'";
    
    $result= mysql_query($query) or die('error in query '.mysql_error().$query);
    
    $link_id_1= mysql_insert_id();
    $query5="update payment_plan set link2_id = '".$link_id_1_pay."',link3_id = '".$link_id_2_pay."' where id = '".$link_id_1."'";
     
    $result5= mysql_query($query5) or die('error in query '.mysql_error().$query5);
    
    $query5="update payment_plan set link_id = '".$link_id_2."' where id = '".$link_id_1."'";
    $result5= mysql_query($query5) or die('error in query '.mysql_error().$query5);
    
         // TNSERT ELEMENT : gst_subdivision = '".$gst_subdivision_n."',tst_subdivision = '".$tst_subdivision_n."',tds_per = '".$tds_per_perproject."', tds_amount = '".$tds_per_amount1."',gst_per = '".$gst[$i]."',gst_amount = '".$gst_amount[$i]."' 
        $multi_project_id = $multi_project_id.$link_id_1.",";
         //project_id,subdivision,gst_subdivision,invoice_id
        $desc_total_n = $desc_total_n."(".$ij.")".$desc_t[$i].",";
        $query_goods_details ="insert into goods_details set trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$invoice_idnew."',project_id = '".$project_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',tds_per = '".$tds_per_perproject."', tds_amount = '".$tds_per_amount1."',gst_per = '".$gst[$i]."',gst_amount = '".$gst_amount[$i]."',link1_id = '".$link_id_1."',link2_id = '".$link_id_2."',link3_id = '".$link_id_1_pay."',link4_id = '".$link_id_2_pay."', description = '".$desc_t[$i]."',hsn_code = '".$hsn_code[$i]."',qty = '".$qty_t[$i]."',unit_price = '".$unit_price_1[$i]."', sub_total = '".$sub_tot_perproject."',trans_type = '".$trans_type."',trans_type_name = '".$trans_type_name."',payment_date = '".$payment_date_n."',create_date = '".getTime()."',invoice_type = '".$_REQUEST['invoice_type']."'";
        $result_goods_details= mysql_query($query_goods_details) or die('error in query '.mysql_error().$query_goods_details);
        $link_goods_details = mysql_insert_id();
    // $goods_details_idlist=$goods_details_idlist.",".$link_goods_details;
     $goods_details_idlist=$goods_details_idlist.$link_goods_details.",";
     $ij++;
}

 
          $subdivision="";
       $gst_subdivision="";
          $query_goods="update payment_plan set goods_detail_id = '".$goods_details_idlist."' ,multi_project_id= '".$multi_project_id."',tds_amount= '".$tot_tds_amount."' ,description = '".$desc_total_n."' where id = '".$link_id_2."'";
          $result_goods= mysql_query($query_goods) or die('error in query '.mysql_error().$query_goods);
   
           if($in==0){
           $query5_pay="update payment_plan set link_id = '".$link_id_2_pay."' where id = '".$link_id_1_pay."'";
            $result5_pay= mysql_query($query5_pay) or die('error in query '.mysql_error().$query5_pay);
    
            $query5="update payment_plan set link2_id = '".$link_id_1_pay."',link3_id = '".$link_id_2_pay."' where id = '".$link_id_2."'";
            $result5= mysql_query($query5) or die('error in query '.mysql_error().$query5);
        
          $query_goods="update payment_plan set goods_detail_id = '".$goods_details_idlist."' ,multi_project_id= '".$multi_project_id."' , description = '".$desc_total_n."' where id = '".$link_id_1_pay."'";
          $result_goods= mysql_query($query_goods) or die('error in query '.mysql_error().$query_goods);
          $query_goods="update payment_plan set goods_detail_id = '".$goods_details_idlist."' , multi_project_id= '".$multi_project_id."' ,description = '".$desc_total_n."' where id = '".$link_id_2_pay."'";
          $result_goods= mysql_query($query_goods) or die('error in query '.mysql_error().$query_goods);
           }
    /*     goods detail end   */
    

    /*********     GST WORK START     **********/

    $tds_cerf_1=mysql_real_escape_string(trim($_REQUEST['gst_cerf']));
    $tds_due_date_1=mysql_real_escape_string(trim($_REQUEST['gst_due_date_new']));
    $tds_due_amount_1=mysql_real_escape_string(trim($_REQUEST['gst_due_amount_new_total']));
   // $tds_due_des_1=mysql_real_escape_string(trim($_REQUEST['gst_due_des']));
    $tds_due_flag_value_1=mysql_real_escape_string(trim($_REQUEST['gst_due_flag_value']));
    $due_amount_pay_1=mysql_real_escape_string(trim($_REQUEST['gst_due_amount_new']));
    $tds_due_date_1_n=strtotime($tds_due_date_1);
    
    $due_trans_id_1=mysql_real_escape_string(trim($trans_id));
    $due_payment_id_1=mysql_real_escape_string(trim($link_id_2));
    $due_invoice_id_1=mysql_real_escape_string(trim($invoice_idnew));
    $tds_due_des_1 = "GST Received for invoice : ".$invoice_idnew.""; 
    
    //$link_id_2, trans_id, invoice_idnew
    //gst_due_date_new,  gst_due_amount_new_total , gst_due_amount_new , gst_due_attach_file , gst_due_flag_value
/**********************         id for all entry START           ************** */
/*
1 . Sale goods (customer) = $link_id_2
2. sale good ( project (multiple)) = $multi_project_id
3 payment invoice (customer) =$link_id_2_pay
4. payment invoice( bank) = $link_id_1_pay
5. payment gst (customer) = $link_id_2_pay_gst
6. payment gst (bank) = $link_id_1_pay_gst
7. payment tds (customer) =
8. payment tds (bank) =
9. gst_due table id = $link_id_4_gst
10. TDS due table id =
*/

/**********************         id for all entry END           ************** */


    if($tds_due_flag_value_1=="1")
    {                  

        $query3="insert into gst_due_info set invoice_id = '".$due_invoice_id_1."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['gst_due_date_new'])."',description = '".$tds_due_des_1."',received_amount = '".$due_amount_pay_1."', amount = '".$tds_due_amount_1."',create_date = '".getTime()."'";
        $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
        $link_id_4_gst = mysql_insert_id();
        
        if($_FILES["gst_due_attach_file"]["name"] != "")
         {
            $attach_file_name="gst_certificate";
            $temp = explode(".", $_FILES["gst_due_attach_file"]["name"]);
             $arr_size = count($temp);
            $extension = end($temp);
            $new_file_name = $attach_file_name.'_'.$link_id_4_gst.'_'.date("d_M_Y").'.'.$extension;
        move_uploaded_file($_FILES["gst_due_attach_file"]["tmp_name"],"gst_files/" . $new_file_name);
        $query1_1="update gst_due_info set cert_file_name = '".$new_file_name."' where id = '".$link_id_4_gst."'";
        $result1_1= mysql_query($query1_1) or die('error in query '.mysql_error().$query1_1);

        
         }
        
        //    $query1_2="update payment_plan set gst_flag = '1',gst_due_id='".$link_id_4."' where id = '".$due_payment_id_1."'";
        //    $result1_2= mysql_query($query1_2) or die('error in query '.mysql_error().$query1_2);
         
        $trans_type_pay_gst = 51;
        $trans_type_name_pay_gst= "gst_receive_payment" ;
       
        $query_pay_gst ="insert into payment_plan set trans_id = '".$trans_id."', bank_id = '".$pay_bank_id."', credit = '".$due_amount_pay_1."',  description = '".$tds_due_des_1."', on_customer = '".$cust_id."', invoice_id = '".$invoice_idnew."',payment_flag = '".$payment_flag."', payment_date = '".strtotime($_REQUEST['gst_due_date_new'])."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_issuer_id = '".$invoice_issuer_id."' ,payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."',link3_id = '".$link_id_2."', trans_type = '".$trans_type_pay_gst."', trans_type_name = '".$trans_type_name_pay_gst."',create_date = '".getTime()."',printable_invoice_number ='".$invoice_id_print."',invoice_type = '".$_REQUEST['invoice_type']."', added_by = '".$_SESSION['userId']."', added_on = '".time()."'";
        $result_pay_gst= mysql_query($query_pay_gst) or die('error in query '.mysql_error().$query_pay_gst);
        
        
        $link_id_1_pay_gst = mysql_insert_id();
        
        
        $query2_pay_gst="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$invoice_idnew."', debit = '".$due_amount_pay_1."', description = '".$tds_due_des_1."', on_project = '".$project_id."', on_bank = '".$pay_bank_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['gst_due_date_new'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."', payment_checkno = '".$pay_checkno."',link3_id = '".$link_id_2."',link_id = '".$link_id_1_pay_gst."',trans_type = '".$trans_type_pay_gst."', trans_type_name = '".$trans_type_name_pay_gst."', create_date = '".getTime()."',printable_invoice_number ='".$invoice_id_print."',invoice_type = '".$_REQUEST['invoice_type']."', added_by = '".$_SESSION['userId']."', added_on = '".time()."'";
        $result2_pay_gst= mysql_query($query2_pay_gst) or die('error in query '.mysql_error().$query2_pay_gst);
        
        $link_id_2_pay_gst = mysql_insert_id();  
        
        $query5_pay_gst="update payment_plan set link_id = '".$link_id_2_pay_gst."' ,gst_flag = '1',gst_id='".$link_id_2_pay_gst."',gst_due_id='".$link_id_4_gst."' where id = '".$link_id_1_pay_gst."'";
        $result5_pay_gst= mysql_query($query5_pay_gst) or die('error in query '.mysql_error().$query5_pay_gst);

        $query5_pay_gst="update payment_plan set gst_flag = '1',gst_id='".$link_id_2_pay_gst."',gst_due_id='".$link_id_4_gst."' where id = '".$link_id_2_pay_gst."'";
        $result5_pay_gst= mysql_query($query5_pay_gst) or die('error in query '.mysql_error().$query5_pay_gst);

        $query1_2="update payment_plan set gst_flag = '1',gst_id='".$link_id_2_pay_gst."',gst_due_id='".$link_id_4_gst."' where id = '".$due_payment_id_1."'";
        $result1_2= mysql_query($query1_2) or die('error in query '.mysql_error().$query1_2);
        $gst_received_flag=1;
        $receiv_amount_gst=$gst_amount_tot-$due_amount_pay_1;

        $query_gst_update="update gst_due_info set  pp_linkid_1 = '".$link_id_2_pay_gst."',pp_linkid_2 = '".$link_id_1_pay_gst."'  where id = '".$link_id_4_gst."'";
        $result5_gst_update= mysql_query($query_gst_update) or die('error in query '.mysql_error().$query_gst_update);

        // pp_linkid_1 = '".$result2_pay_gst."',pp_linkid_2 = '".$link_id_1_pay_gst."',
    }

    /*************          //GST WORk END       ********************** */
    
    /*********     TDS WORK START     **********/

    $tds_cerf_1=mysql_real_escape_string(trim($_REQUEST['tds_cerf']));
    $tds_due_date_1=mysql_real_escape_string(trim($_REQUEST['tds_due_date_new']));
    $tds_due_amount_1=mysql_real_escape_string(trim($_REQUEST['tds_due_amount_new_total']));
    $tds_due_flag_value_1=mysql_real_escape_string(trim($_REQUEST['tds_due_flag_value']));
    $due_amount_pay_1=mysql_real_escape_string(trim($_REQUEST['tds_due_amount_new']));
    $tds_due_date_1_n=strtotime($tds_due_date_1);
    $tds_due_des_1 = "TDS Received for invoice : ".$invoice_idnew.""; 
    
    $due_trans_id_1=mysql_real_escape_string(trim($trans_id));
    $due_payment_id_1=mysql_real_escape_string(trim($link_id_2));
    $due_invoice_id_1=mysql_real_escape_string(trim($invoice_idnew));
           //$link_id_2, trans_id, invoice_idnew
    //tds_due_date_new,  tds_due_amount_new_total , tds_due_amount_new , tds_due_attach_file , tds_due_flag_value

    if($tds_due_flag_value_1=="1")
    {                  


        //$query3="insert into tds_due_info set invoice_id = '".$due_invoice_id_1."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['tds_due_date_new'])."',description = '".$tds_due_des_1."',received_amount = '".$due_amount_pay_1."', amount = '".$tds_due_amount_1."',create_date = '".getTime()."'";
        $query3="insert into tds_due_info set invoice_id = '".$due_invoice_id_1."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',	due_date = '".strtotime($_REQUEST['tds_due_date_new'])."',description = '".$tds_due_des_1."',received_amount = '".$due_amount_pay_1."', amount = '".$tds_due_amount_1."',create_date = '".getTime()."'";
    
        $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
        $link_id_4_tds = mysql_insert_id();
        
        if($_FILES["tds_due_attach_file"]["name"] != "")
         {
            $attach_file_name="tds_certificate";
            $temp = explode(".", $_FILES["tds_due_attach_file"]["name"]);
             $arr_size = count($temp);
            $extension = end($temp);
            $new_file_name = $attach_file_name.'_'.$link_id_4_tds.'_'.date("d_M_Y").'.'.$extension;
        move_uploaded_file($_FILES["tds_due_attach_file"]["tmp_name"],"tds_files/" . $new_file_name);
        $query1_1="update tds_due_info set cert_file_name = '".$new_file_name."' where id = '".$link_id_4_tds."'";
        $result1_1= mysql_query($query1_1) or die('error in query '.mysql_error().$query1_1);

        
         }
        
        $trans_type_pay_tds = 52;
        $trans_type_name_pay_tds= "tds_receive_payment" ;
       
       // $query_pay_tds ="insert into payment_plan set trans_id = '".$trans_id."', bank_id = '".$pay_bank_id."', credit = '".$due_amount_pay_1."',  description = '".$tds_due_des_1."', on_customer = '".$cust_id."', invoice_id = '".$invoice_idnew."',payment_flag = '".$payment_flag."', payment_date = '".strtotime($_REQUEST['pay_payment_date'])."',subdivision = '".$subdivision."',tds_subdivision = '".$tds_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  tds_amount = '".$tds_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_issuer_id = '".$invoice_issuer_id."' ,payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."',link3_id = '".$link_id_2."', trans_type = '".$trans_type_pay_tds."', trans_type_name = '".$trans_type_name_pay_tds."',create_date = '".getTime()."'";
       $query_pay_tds ="insert into payment_plan set trans_id = '".$trans_id."', bank_id = '".$pay_bank_id."', credit = '".$due_amount_pay_1."',  description = '".$tds_due_des_1."', on_customer = '".$cust_id."', invoice_id = '".$invoice_idnew."',payment_flag = '".$payment_flag."', payment_date = '".strtotime($_REQUEST['tds_due_date_new'])."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',invoice_issuer_id = '".$invoice_issuer_id."' ,payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."',link3_id = '".$link_id_2."', trans_type = '".$trans_type_pay_tds."', trans_type_name = '".$trans_type_name_pay_tds."',create_date = '".getTime()."',printable_invoice_number ='".$invoice_id_print."',invoice_type = '".$_REQUEST['invoice_type']."', added_by = '".$_SESSION['userId']."', added_on = '".time()."'";
     
       $result_pay_tds= mysql_query($query_pay_tds) or die('error in query '.mysql_error().$query_pay_tds);
        
        
        $link_id_1_pay_tds = mysql_insert_id();
        
        //trans_id , cust_id ,, invoice_idnew ,,
        //$query2_pay_tds="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$invoice_idnew."', debit = '".$due_amount_pay_1."', description = '".$tds_due_des_1."', on_project = '".$project_id."', on_bank = '".$pay_bank_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['pay_payment_date'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',tds_subdivision = '".$tds_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  tds_amount = '".$tds_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."', payment_checkno = '".$pay_checkno."',link3_id = '".$link_id_2."',link_id = '".$link_id_1_pay_tds."',trans_type = '".$trans_type_pay_tds."', trans_type_name = '".$trans_type_name_pay_tds."', create_date = '".getTime()."'";
        $query2_pay_tds="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$invoice_idnew."', debit = '".$due_amount_pay_1."', description = '".$tds_due_des_1."', on_project = '".$project_id."', on_bank = '".$pay_bank_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['tds_due_date_new'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."', payment_checkno = '".$pay_checkno."',link3_id = '".$link_id_2."',link_id = '".$link_id_1_pay_tds."',trans_type = '".$trans_type_pay_tds."', trans_type_name = '".$trans_type_name_pay_tds."', create_date = '".getTime()."',printable_invoice_number ='".$invoice_id_print."',invoice_type = '".$_REQUEST['invoice_type']."', added_by = '".$_SESSION['userId']."', added_on = '".time()."'";
    
        $result2_pay_tds= mysql_query($query2_pay_tds) or die('error in query '.mysql_error().$query2_pay_tds);
        
        $link_id_2_pay_tds = mysql_insert_id();  
        
        $query5_pay_tds="update payment_plan set link_id = '".$link_id_2_pay_tds."' ,tds_flag = '1',tds_id='".$link_id_2_pay_tds."',tds_due_id='".$link_id_4_tds."' where id = '".$link_id_1_pay_tds."'";
        $result5_pay_tds= mysql_query($query5_pay_tds) or die('error in query '.mysql_error().$query5_pay_tds);

        $query5_pay_tds="update payment_plan set tds_flag = '1',tds_id='".$link_id_2_pay_tds."',tds_due_id='".$link_id_4_tds."' where id = '".$link_id_2_pay_tds."'";
        $result5_pay_tds= mysql_query($query5_pay_tds) or die('error in query '.mysql_error().$query5_pay_tds);

        $query1_2="update payment_plan set tds_flag = '1',tds_id='".$link_id_2_pay_tds."',tds_due_id='".$link_id_4_tds."' where id = '".$due_payment_id_1."'";
        $result1_2= mysql_query($query1_2) or die('error in query '.mysql_error().$query1_2);
        $tds_received_flag=1;
        $receiv_amount_tds=$tds_amount_tot-$due_amount_pay_1;
         
        $query_tds_update="update tds_due_info set  pp_linkid_1 = '".$link_id_2_pay_tds."',pp_linkid_2 = '".$link_id_1_pay_tds."'  where id = '".$link_id_4_tds."'";
        $result5_tds_update= mysql_query($query_tds_update) or die('error in query '.mysql_error().$query_tds_update);

    
    }

    /*************          //TDS WORk END       ********************** */

    
    if($gst_received_flag==1)
    {
        //    1 . Sale goods (customer) = $link_id_2
        //    2. sale good ( project (multiple)) = $multi_project_id
        //    3 payment invoice (customer) =$link_id_2_pay
        //    4. payment invoice( bank) = $link_id_1_pay
        //    5. payment gst (customer) = $link_id_2_pay_gst
        //    6. payment gst (bank) = $link_id_1_pay_gst
        //    7. payment tds (customer) =
        //    8. payment tds (bank) =

        if($receiv_amount_gst<1)
        {
            $gst_clear_flag=1;
            $clear_gstdue_desc="Clear All GST Payment Amount";
            $query_clear_gst="insert into `clear_due_amount`  set invoice_id  = '".$invoice_idnew."',description='".$clear_gstdue_desc."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',due_amount = '".$receiv_amount_gst."', cust_id = '".$cust_id."', user_id = '".$_SESSION['userId']."', type = 'GST',create_time = '".getTime()."'";
            $result_clear_gst= mysql_query($query_clear_gst) or die('error in query '.mysql_error().$query_clear_gst);
            
        }else{
            $gst_clear_flag=0;
        }

        $query_gst1="update payment_plan set gst_flag = '1',clear_gst_flag='".$gst_clear_flag."',gst_id='".$link_id_2_pay_gst."',gst_due_id='".$link_id_4_gst."' where invoice_id = '".$invoice_idnew."'";
        $result_gst1= mysql_query($query_gst1) or die('error in query '.mysql_error().$query_gst1);
        
    }
    if($tds_received_flag==1)
    {
        
        if($receiv_amount_tds<1)
        {
            $tds_clear_flag=1;
            $clear_tdsdue_desc="Clear All TDS Payment Amount";
            $query_clear_tds="insert into `clear_due_amount`  set invoice_id  = '".$invoice_idnew."',description='".$clear_tdsdue_desc."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',due_amount = '".$receiv_amount_tds."', cust_id = '".$cust_id."', user_id = '".$_SESSION['userId']."', type = 'TDS',create_time = '".getTime()."'";
            $result_clear_tds= mysql_query($query_clear_tds) or die('error in query '.mysql_error().$query_clear_tds);
         
        }else{
            $tds_clear_flag=0;
        }

        $query_tds1="update payment_plan set tds_flag = '1',clear_tds_flag='".$tds_clear_flag."',tds_id='".$link_id_2_pay_tds."',tds_due_id='".$link_id_4_tds."' where invoice_id = '".$invoice_idnew."'";
        $result_tds1= mysql_query($query_tds1) or die('error in query '.mysql_error().$query_tds1);
        
    }
   
    if($invoice_received_flag==1)
    {
        $invoice_due_des_1 = "Amount Received for invoice : ".$invoice_idnew."";
        

        if($receiv_amount_invoice<1)
        {
            $invoice_clear_flag=1;
            $clear_invoicedue_desc="Clear All Invoice Payment Amount";
            $query_clear_invoice="insert into `clear_due_amount`  set invoice_id  = '".$invoice_idnew."',description='".$clear_invoicedue_desc."',payment_plan_id = '".$due_payment_id_1."',trans_id = '".$due_trans_id_1."',due_amount = '".$receiv_amount_invoice."', cust_id = '".$cust_id."', user_id = '".$_SESSION['userId']."', type = 'Invoice',create_time = '".getTime()."'";
            $result_clear_invoice= mysql_query($query_clear_invoice) or die('error in query '.mysql_error().$query_clear_invoice);
         
        }else{
            $invoice_clear_flag=0;
        }
        //invoice_due_pay_id

        $query_invoice1="update payment_plan set invoice_flag = '1',clear_invoice_flag='".$invoice_clear_flag."',invoice_due_pay_id='".$link_id_4_invoice."',invoice_pay_id='".$link_id_2_pay."' where invoice_id = '".$invoice_idnew."'";
        $result_invoice1= mysql_query($query_invoice1) or die('error in query '.mysql_error().$query_invoice1);
        
    }

   if($_FILES["attach_file"]["name"] != "")
    {
    $link_id_1= $invoice_idnew;
    $query3="insert into attach_file set attach_id = '".$link_id_1."',file_name = '".$new_file_name."'";
    $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
    $link_id_4 = mysql_insert_id();
    
        $attach_file_name=mysql_real_escape_string(trim($_REQUEST['attach_file_name']));
        $temp = explode(".", $_FILES["attach_file"]["name"]);
        $arr_size = count($temp);
        $extension = end($temp);
        $new_file_name = $attach_file_name.'_'.$link_id_4.'_'.date("d_M_Y").'.'.$extension;
        if($in==0)
        {
        move_uploaded_file($_FILES["attach_file"]["tmp_name"],"transaction_files/" . $new_file_name);
        $old_new_file_name =$new_file_name;
        }
        else
        {
             copy ( "transaction_files/".$old_new_file_name,"transaction_files/".$new_file_name);
        }
    
    $query5_1="update attach_file set file_name = '".$new_file_name."' where id = '".$link_id_4."'";
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
    ///  payment end//
        }
        else
        {
            $new_file_name = "";        
        }
        
    }
    $link_id_1_pay=""; 
          $link_id_2_pay="";
          $link_id_1="";
          $link_id_2="";
         
}         
if($multi_invoice_flag==1)
            {
                 $query_n1="update payment_plan set multi_invoice_flag = '".$multi_invoice_flag."',multi_invoice_detail = '".$multi_invoice_list."',multi_invoice_id ='".$multi_invoice_id."' where invoice_id in(".$multi_invoice_list.")";
            $result_n1= mysql_query($query_n1) or die('error in query '.mysql_error().$query_n1);
            }
    
	$msg = "Instant Sale Goods and Payment successfully.";
	$flag = 1;
    if($_REQUEST['from_page'] == "Repeat Invoice")
    echo "<script> location.href='invoice-list.php'; </script>";
	
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
    $qry_max=" select max(invoice_id) as max_invoice from goods_details";
       $qry_max_result = mysql_query($qry_max);
       
       $qry_max_row = mysql_fetch_array($qry_max_result);
       if($qry_max_row[max_invoice]<1)
       {
            $invoice_idnew="1001";       
       }else{
       $invoice_idnew =$qry_max_row[max_invoice]+1;    
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



<script src="js/jquery-1.12.4.min.js"></script>

<script>
    $(document).ready(function(){
        var ii=1;
        var ij=1;
        var ijj=2;
        var invoice_idnew_12 = $("#invoice_idnew").val();
 
        $(".add-row").click(function()
        {
            
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
    }else {
           var project2 = $("#project_1").val();
           var subdivision2 = $("#subdivision_1").val();
           //var gst_subdivision2 = $("#gst_subdivision_1").val();
            var desc2 = $("#desc1").val();
            var qty2 = $("#qty1").val();
            var unit_price2 = $("#unit_price1").val();
            var gst2 = $("#gst1").val();
            var tds2 = $("#tds1").val();
            var hsn_code2 = $("#hsn_code1").val();
             count=$('#myTable tbody tr').length;
             var sub_total2 = unit_price2*qty2;
             var gst_amount =(sub_total2*gst2)/100;
             var tds_amount_1 =(sub_total2*tds2)/100;
             var total2 = sub_total2+gst_amount;
             //gst_due_amount_new
            //tds_amount_1
    var markup="<tr><td style='text-align: left;padding: 2px;'><input type='checkbox' name='record' class='case'/></td><td style='text-align: left;padding: 2px;'><span id='snum"+ii+"'>"+ii+".</span><input type='hidden' id='no_check"+ii+"' value='"+ii+"' name='no_check[]'/></td>";
    markup +="<td style='text-align: left;padding: 2px;'><input type='text' id='project"+ii+"' value='"+project2+"' name='project[]' style='width: 130px;' readonly='readonly'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='subdivision"+ii+"' value='"+subdivision2+"' name='subdivision[]' style='width: 130px;' readonly='readonly'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='desc_t"+ii+"' value='"+desc2+"' name='desc_t[]' style='width: 170px;' readonly='readonly'/> <td style='text-align: left;padding: 2px;'><input type='text' id='hsn_code"+ii+"' name='hsn_code[]' style='width: 70px;' readonly='readonly' value='"+hsn_code2+"'/></td></td> <td style='text-align: left;padding: 2px;'><input type='text' id='qty_t"+ii+"' name='qty_t[]' style='width: 30px;' readonly='readonly' value='"+qty2+"'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='unit_price_1"+ii+"' value='"+unit_price2+"' style='width: 60px;' readonly='readonly' name='unit_price_1[]'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='sub_total"+ii+"' value='"+sub_total2+"' style='width: 60px;' readonly='readonly' name='sub_total[]'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='tds"+ii+"' value='"+tds2+"' style='width: 30px;' readonly='readonly' name='tds[]'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='tds_amount_f"+ii+"' value='"+tds_amount_1+"' name='tds_amount_f[]' style='width: 60px;' readonly='readonly'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='gst"+ii+"' value='"+gst2+"' style='width: 30px;' readonly='readonly' name='gst[]'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='gst_amount"+ii+"' style='width: 60px;' readonly='readonly' value='"+gst_amount+"' name='gst_amount[]'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='total"+ii+"' style='width: 60px;' readonly='readonly' value='"+total2+"' name='total[]'/></td></tr>";
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
   
   var sum = 0;
  
var qty_t_total = 0;
var sub_total_total=0;
var unit_price_total=0;
var total_amount_total=0;
var gst_amount_total=0;
var tds_amount_total=0;
var desc_total="";

   //gst_due_amount_new 
   ////gst_due_amount_new_val1,gst_due_amount_new_total,gst_due_amount_new_val2        
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
           
            new_invoice_amount=sub_total_total-tds_amount_total;
            //tds_due_amount_new_total,  tds_due_amount_new,  tds_due_amount_new_due ,pay_amount
            //pay_amount_total,pay_amount
            document.getElementById("qty_tot").value = qty_t_total;
            document.getElementById("unit_price_tot").value = unit_price_total;
            document.getElementById("sub_total_tot").value = sub_total_total;
            document.getElementById("total_tot").value = total_amount_total;
            document.getElementById("gst_amount_tot").value = gst_amount_total;
            document.getElementById("tds_amount_tot").value = tds_amount_total;
            document.getElementById("amount").value = total_amount_total;
            document.getElementById("description").value = desc_total;
            document.getElementById("pay_amount").value = sub_total_total;
            document.getElementById("gst_due_amount_new").value = gst_amount_total;
            document.getElementById("gst_due_amount_new_total").value = gst_amount_total;
            document.getElementById("gst_due_amount_new_due").value = "0";
            document.getElementById("tds_due_amount_new").value = tds_amount_total;
            document.getElementById("tds_due_amount_new_total").value = tds_amount_total;
            document.getElementById("tds_due_amount_new_due").value = "0";
            document.getElementById("pay_amount_total").value = total_amount_total;
            document.getElementById("pay_amount").value = new_invoice_amount;
            document.getElementById("pay_amount_final").value = new_invoice_amount;
           // final_amount_show();
           flag_val = document.getElementById("gst_due_flag_value").value;
    if(flag_val=="1")
   {
    pay_amount_n = document.getElementById("pay_amount").value;
    gst_amount_n = document.getElementById("gst_due_amount_new").value;
    tot_pay_amount=Number(pay_amount_n)+Number(gst_amount_n);
    document.getElementById("pay_amount_show").value=tot_pay_amount;
   }else
   {
   
    pay_amount_n = document.getElementById("pay_amount").value;
    tot_pay_amount=pay_amount_n;
    document.getElementById("pay_amount_show").value=new_invoice_amount;
   } 
        }
        
        });
        
        // Find and remove selected table rows
        $(".delete-row").click(function()
        {
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
            //tds_due_date_new
            document.getElementById("qty_tot").value = qty_t_total;
            document.getElementById("unit_price_tot").value = unit_price_total;
            document.getElementById("sub_total_tot").value = sub_total_total;
            document.getElementById("total_tot").value = total_amount_total;
            document.getElementById("gst_amount_tot").value = gst_amount_total;
            document.getElementById("tds_amount_tot").value = tds_amount_total;
            document.getElementById("amount").value = total_amount_total;
            document.getElementById("description").value = desc_total;
            document.getElementById("pay_amount").value = sub_total_total;
            document.getElementById("gst_due_amount_new").value = gst_amount_total;            
            document.getElementById("gst_due_amount_new_total").value = gst_amount_total;
            document.getElementById("gst_due_amount_new_due").value = "0";
            document.getElementById("tds_due_amount_new").value = tds_amount_total;
            document.getElementById("tds_due_amount_new_total").value = tds_amount_total;
            document.getElementById("tds_due_amount_new_due").value = "0";
            document.getElementById("pay_amount_total").value = total_amount_total;
            document.getElementById("pay_amount").value = new_invoice_amount;
            document.getElementById("pay_amount_final").value = new_invoice_amount;
           // final_amount_show();
           flag_val = document.getElementById("gst_due_flag_value").value;
    if(flag_val=="1")
   {
    pay_amount_n = document.getElementById("pay_amount").value;
    gst_amount_n = document.getElementById("gst_due_amount_new").value;
    tot_pay_amount=Number(pay_amount_n)+Number(gst_amount_n);
    document.getElementById("pay_amount_show").value=tot_pay_amount;
   }else
   {
   
    pay_amount_n = document.getElementById("pay_amount").value;
    tot_pay_amount=pay_amount_n;
    document.getElementById("pay_amount_show").value=new_invoice_amount;
   }
            });
            
        });
        
                $(".add-date").click(function()
        {
            
            if($("#invoice_repeat_date").val() == "")
    {
        alert("Please enter New Invoice Date.");
        $("#invoice_repeat_date").focus();
        return false;
    }
   else {
           var invoice_repeat_date_1 = $("#invoice_repeat_date").val();
             //var invoice_id_new_1 = "12";
            // var trsns_id_new_1 = "1";
             invoice_idnew_12++;
                                                                             
    var markup="<tr><td style='text-align: left;padding: 2px;'><input type='checkbox' name='record' class='case'/></td><td style='text-align: left;padding: 2px;'><span id='snum_1"+ij+"'>"+ijj+".</span><input type='hidden' id='no_check_1"+ij+"' value='"+ij+"' name='no_check_1[]'/></td>";
    markup +="<td style='text-align: left;padding: 2px;'><input type='text' id='invoice_id_new"+ij+"' value='"+invoice_idnew_12+"' name='invoice_id_new[]' style='width: 250px;' readonly='readonly'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='date_new_id"+ij+"' name='date_new_id[]' style='width: 150px;' readonly='readonly' value='"+invoice_repeat_date_1+"'/></td></tr>";
    $(' #myTable_2 tbody').append(markup);
    ij++;
     ijj++;
        }
        }); 
        
           // Find and remove selected table rows
        $(".delete-date").click(function()
        {
            $("#myTable_2 tbody").find('input[name="record"]').each(function(){
                if($(this).is(":checked")){
                    $(this).parents("#myTable_2 tbody tr").remove();
                }
              
            });
        });
    });    
</script>
<script type="text/javascript">
function finddate_new_id()
{
<!-- ////trans_id,invoice_idnew,payment_date -->
     var payment_date_1 = document.getElementById('payment_date').value;
     var trans_id_1 = document.getElementById('trans_id').value; 
     var invoice_idnew_1 = document.getElementById('invoice_idnew').value;
       <!--  //trsns_id_new,invoice_id_new,date_new_id   -->   
    document.getElementById('date_new_id_old').value = payment_date_1;
    //document.getElementById('trsns_id_new_old').value = trans_id_1;
    document.getElementById('invoice_id_new_old').value = invoice_idnew_1;
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
        Multiple Instant sale Invoice</h4>
  </td>
        <td width="" style="float:right;">
            <!--<a href="bank.php" title="Back" style=""><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>-->
            </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
  <?php include_once("main_body_open.php") ?>
  	
  	
	<div id="adddiv" >
		<?php if($msg != "") { ?>
	<div class="sukses">
		<?php echo $msg; ?>
		</div>
	<?php } else if($error_msg != "") { ?>
	<div class="gagal">
		
		<?php echo $error_msg; ?>
		</div>
	<?php } ?>
		
	<form name="project_form" id="project_form" action="" method="post" enctype="multipart/form-data" >
        <script src="js/datetimepicker_css.js"></script>
        <link rel="stylesheet" href="css/jquery-ui.css" />
  <!--<script src="js/jquery-1.9.1.js"></script>-->
  <script src="js/jquery-ui.js"></script>
           <table width="98%"  style="padding:10px 0px 20px 20px; " >
           <tr><td>
           <table width="100%" class="tbl_border" >
           
	
        <tr style="width: 50%;" valign="top" >
            <td valign="top">
     
		<table valign="top" width="98%" border="0">
            <tr><td colspan="2" valign="top"><h4 class="u-text-2 u-text-palette-1-base1 " style="padding:0px; margin:0px;">Instant Sale Goods</h4></td></tr>
                                                           
			<tr>
            <td width="125px">Transaction ID</td>
			<td style="color:#FF0000; font-weight:bold;"><input type="hidden" id="trans_id"  name="trans_id" value="<?php echo $trans_id; ?>"/>&nbsp;<?php echo $trans_id; ?></td></tr>

            <tr>
            <td width="125px">NDB Sr. No.</td>
            <td style="color:#FF0000; font-weight:bold;">
            <input type="hidden" id="invoice_idnew"  name="invoice_idnew" value="<?php echo $invoice_idnew; ?>"/>&nbsp;<?php echo $invoice_idnew; ?></td>
            </tr>

			<tr><td >Customer Name</td>
			<td><input type="text" id="from"  name="from" value="" onblur="set_print_inv()" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>

			<tr><td width="125px">Invoice Issuer</td>
            <td><input type="text" id="invoice_issuer"  name="invoice_issuer" value="" onblur="set_print_inv()" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>

			<tr>
            <td width="125px">Invoice Type</td>
			<td style="font-weight:bold;">
            <Select id="invoice_type" name="invoice_type" onChange="set_print_inv()">
            <option value="">Select Invoice Type</option>
            <option value="R">GST Rent</option>
            <option value="M">GST Maintenance</option>
            <option value="S">GST Sales</option>
            <option value="RN">Reimbursement Note</option>
            </select>
            </td>
            </tr>

			<tr>
            <td width="125px">Invoice Month</td>
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
            <td width="125px"><div id="inv_label">Invoice No.</div></td>
            <td>
            <input type="text" style="color:#FF0000; font-weight:bold;" id="invoice_id_print"  name="invoice_id_print" value="" maxlength="16" readonly/>
            </td>
            </tr>

            <tr><td align="left" valign="top" >Date</td>
        
            <td><input type="text"  name="payment_date" id="payment_date" style="width:250px;" onchange="finddate_new_id();" onblur="finddate_new_id();" value="<?php echo $_REQUEST['payment_date']; ?>" autocomplete="off" placeholder="DD-MM-YYYY"/>&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('payment_date')" style="cursor:pointer"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>          
  			<tr><td align="left" valign="top" >Amount</td>
			<td><input type="text"  name="amount" id="amount" value="" readonly="readonly" style="width:250px;" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td valign="top" >Description</td>
			
            <td><textarea name="description" id="description" style="width:350px; height:100px;" readonly="readonly"></textarea>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			  
			<tr><td valign="top" >GST Subdivision Name</td>
			<td><input type="text" id="gst_subdivision_1"  name="gst_subdivision_1" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
            
			<tr><td valign="top" >TDS Subdivision Name</td>
			<td><input type="text" id="tds_subdivision_1"  name="tds_subdivision_1" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
            <tr><td valign="top" >Attach File</td>
			<td><input type="file" name="attach_file" id="attach_file" value="" style="width:250px;" onChange="return hide_drag();" >&nbsp;</td></tr>
			
			<tr><td valign="top" >Attach File Name</td>
			<td><input type="text" id="attach_file_name"  name="attach_file_name" value="" style="width:250px;" autocomplete="off"/></td></tr>
	        
            <!--
			<tr><td valign="top" colspan="2" >
			<div id="drag_div" style="border:1px solid #CCCCCC; width:100%; background-color:#FFFFFF; border-radius:10px; ">
					
					
					<div style="height:20px; width:100%; background-color:#F9F9F9; border-top-left-radius:10px; border-top-right-radius:10px; color:#FF0000; text-align:left; float:right; " >&nbsp;&nbsp;&nbsp;&nbsp;<strong>Drag Files To Upload</strong>
							</div>
							<div id="dropbox" >
			<span class="message" >Drop Files here to upload.</span>
		</div>
		
		
		<script src="js/jquery.filedrop.js"></script>
		
		
        <script src="js/script.js"></script>		
						</div>
			</td></tr>
			-->
						
		</table>
        
         </td>
            <td style="width: 50%;" valign="top">
            <table width="500px">
            <tr><td>
            <h4 class="u-text-2 u-text-palette-1-base1 " style="padding:0px; margin:0px;" align="left">Instant Payment Details</h4>
                </td></tr>
                <tr><td><input type="checkbox" name="pay_flag" id="pay_flag" onchange=" return checkpay_flag();"  >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Payment</b></td></tr>
                <tr><td>
                <div id="pay_flag_div"   style="display:none; " >
                
                <table width="95%">
               <!-- <tr><td colspan="2"></td></tr>-->
            <tr><td width="125px">&nbsp;</td>
            <td style="color:#FF0000; font-weight:bold;">&nbsp;</td></tr>
            <tr><td width="225px">Paid Into</td>
            <td width="400"><input type="text" id="pay_form"  name="pay_form" value="" style="width:190px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td align="left" valign="top" >Payment Date</td>
        
            <td><input type="text"  name="pay_payment_date" id="pay_payment_date" value="<?php echo $_REQUEST['payment_date']; ?>" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('pay_payment_date')" style="cursor:pointer"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <tr><td >Payment Method</td>
            <td><br>
            <input type="radio" id="pay_method" name="pay_method"  onchange=" return checkno_create();" value="check">
            <label for="check">Cheque</label>&nbsp;&nbsp;
            <input type="radio" id="pay_method" name="pay_method" checked=true   onchange="return checkno_create1();" value="bank">
            <label for="bank">Bank</label>&nbsp;&nbsp;
            <input type="radio" id="pay_method" name="pay_method"  onchange="return checkno_create1();" value="cash">
            <label for="cash">Cash</label>&nbsp;&nbsp;<br>
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
            <tr><td align="left" style="width:150px;" width=150px; valign="top" >Total Invoice Amount<br>(Includeing GST) 

            </td>
            <td><?php echo currency_symbol(); ?>
            <input type="text" id="pay_amount_total" style="width:100px;color:red; border:0; " readonly="readonly"  name="pay_amount_total" value="0" />    
            <input type="hidden" style="width:100px;"   name="pay_amount_final" id="pay_amount_final" value="" />
   
        </td></tr>
                        <tr><td align="left" valign="top" >Total Amount Received<br>(Including GST)</td>
            <td><input type="text" style="width:100px;" name="pay_amount_show" id="pay_amount_show" value="" readonly="readonly"/>&nbsp;<span style="color:#FF0000; font-weight:bold;width:100px;"  >*
            (Due :<input type="text" id="invoice_due_amount_new_due_show"  style="width:60px;color:red; border:0; " readonly="readonly"  name="invoice_due_amount_new_due_show" value="0" /></span>
        </td></tr>
            
            <tr><td align="left" valign="top" >Invoice Amount Received</td>
            <td><input type="text" style="width:100px;" onkeydown="invoice_due_calculation()" onkeyup="invoice_due_calculation()" onkeypress="invoice_due_calculation()"  name="pay_amount" id="pay_amount" value="" />&nbsp;<span style="color:#FF0000; font-weight:bold;width:100px;"  >*
            (Due :<input type="text" id="invoice_due_amount_new_due"  style="width:60px;color:red; border:0; " readonly="readonly"  name="invoice_due_amount_new_due" value="0" /></span>
        </td></tr>
            
            <tr>
                <td colspan="2">
                <table cellpadding="0" cellspacing="0" border="1px" width="100%" >

<tr><td valign="top" width="150px" >Received GST &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="gst_cerf" id="gst_cerf" value="1" onClick="return close_gst_div2('1');" >&nbsp;&nbsp;<label for="yes">Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="gst_cerf" id="gst_cerf" value="0" onClick="return close_gst_div2('0');" >&nbsp;&nbsp;No
            </td>
        </tr>
        <tr>  

            <td valign="top"  align="center" id="gst-due_div2" style=" display:none; width:100%">
                <table width="100%" border="0" style="padding: 10px; border-radius: 5px;border: 1px solid #111111;">  
                <tr>    <td valign="top"  width="250px" >Date</td>
                    <td><input type="text"  name="gst_due_date_new" id="gst_due_date_new" value="<?php //echo $_REQUEST['tds_due_date']; ?>" style="width:100px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('gst_due_date_new')" style="cursor:pointer"/></td>
                </tr>
                
                <tr>
                    <td valign="top" >GST Amount</td>
                    <td>
                       
                    <input type="text" id="gst_due_amount_new_total" style="width:100px;color:red; border:0; " readonly="readonly"  name="gst_due_amount_new_total" value="" /></td>
                </tr>
                <tr>
                    <td valign="top" >Amount Received</td>
                    <td><input type="text" id="gst_due_amount_new" style="width:100px;"  name="gst_due_amount_new" onkeydown="gst_due_calculation()" onkeyup="gst_due_calculation()" onkeypress="gst_due_calculation()" value="" />&nbsp;
                    <span id=""  style="color:red;" >(Due :<input type="text" id="gst_due_amount_new_due" style="width:60px;color:red;border:0;" readonly="readonly"  name="gst_due_amount_new_due" value="" /></span></td>
                </tr>
                <tr>
                    <td valign="top" >GST File Attachment</td>
                    <td><input type="file" name="gst_due_attach_file" id="gst_due_attach_file" value="" ></td>
                </tr>
                <!--
                <tr>
                    <td valign="top" >Discription</td>
                    <td><input type="text" id="gst_due_des" style="width:260px;"  name="gst_due_des" value="" autocomplete="off"/></td>
                </tr>-->
            </table>
            </td>
        </tr>    
    </table>
    <input type="hidden" id="gst_due_flag_value"  name="gst_due_flag_value" value="" />
                </td>
            </tr>
            <tr><td colspan="2">
            <table cellpadding="0" cellspacing="0" border="1px" width="100%" >

            <tr><td valign="top" width="150px" >Received TDS Certificate
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tds_cerf" id="tds_cerf" value="1" onClick="return close_tds_div2('1');" >&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="tds_cerf" id="tds_cerf" value="0" onClick="return close_tds_div2('0');" >&nbsp;&nbsp;No
            </td>
        </tr>
        <tr>  
            <td valign="top"  align="center" celpadding="5" id="tds-due_div2" style=" display:none; width:100%">
                <table width="100%" border="0" style=" padding: 10px; border-radius: 5px;border: 1px solid #111111;">  
                <tr>    <td valign="top"  width="250px" >Date</td>
                    <td><input type="text"  name="tds_due_date_new" id="tds_due_date_new" value="<?php //echo $_REQUEST['tds_due_date']; ?>" style="width:100px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('tds_due_date_new')" style="cursor:pointer"/></td>
                </tr>
                
                <tr>
                    <td valign="top" >TDS Amount</td>
                    <td>
                    
                    <input type="text" id="tds_due_amount_new_total" style="width:100px;color:red; border:0; " readonly="readonly"  name="tds_due_amount_new_total" value="" /></td>
                </tr>
                <tr>
                    <td valign="top" >Amount Received</td>
                    <td><input type="text" id="tds_due_amount_new" style="width:100px;"  name="tds_due_amount_new" onkeydown="tds_due_calculation()" onkeyup="tds_due_calculation()" onkeypress="tds_due_calculation()" value="" />&nbsp;
                    <span id="" style="color:red;" >(Due :<input type="text" id="tds_due_amount_new_due"  style="width:60px;color:red; border:0; " readonly="readonly"  name="tds_due_amount_new_due" value="" /></span></td>
                </tr>
                <tr>
                    <td valign="top" >TDS File Attachment</td>
                    <td><input type="file" name="tds_due_attach_file" id="tds_due_attach_file" value="" ></td>
                </tr>
                <!--
                <tr>
                    <td valign="top" >Discription</td>
                    <td><input type="text" id="tds_due_des" style="width:260px;"  name="tds_due_des" value="" autocomplete="off"/></td>
                </tr>-->
            </table>
            </td>
        </tr>    
        <input type="hidden" id="tds_due_flag_value"  name="tds_due_flag_value" value="" />
    </table>

            </td></tr>
        </table>
        </div>
            <input type="hidden" name="payment_flag" id="payment_flag" value="0">
        </td>
        </tr>
        </table>
            </td>
        </tr>
        <tr><td colspan="2">
        <br>
        <b><h4 class="u-text-2 u-text-palette-1-base1 " style="padding:0px; margin:0px;">Add Goods Details :</h4></b></td></tr>
        <tr>
                <td colspan="2">
               <table style="margin: 0px ; padding: 10px; border-radius: 10px;border: 1px solid #111111;">
                    <tr><td width="125px">Project Name</td>
            <td><input type="text" id="project_1"  name="project_1" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td>
            <td >Sub Division Name</td>
            <td>
             <input type="text" id="subdivision_1"  name="subdivision_1" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span>
            </td></tr>
            <tr><td >TDS %</td>
            <td>
            <select name="tds1" id="tds1" style="width:250px; height: 25px;">
            <?php
              $sql_tds     = "select * from `tds_list`  where active='1' order by tds ";
             $query_tds     = mysql_query($sql_tds);
            // $select_tds = mysql_fetch_array($query_tds);
             while($row_tds = mysql_fetch_array($query_tds))
             { ?>
                <option value="<?php echo $row_tds['tds']; ?>" <?php if($row_tds['default_select']==1){ echo "selected='selected'"; } ?>><?php echo $row_tds['tds']."%"; ?></option> 
          <?php    }
              ?>
        </select>
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span>
            </td>
            <td >GST %</td>
            <td>
             <select name="gst1" id="gst1" style="width:250px; height: 25px;">
             
             <?php
              $sql_gst     = "select * from `gst_list`  where active='1' order by gst ";
             $query_gst     = mysql_query($sql_gst);
            // $select_gst = mysql_fetch_array($query_gst);
             while($row_gst = mysql_fetch_array($query_gst))
             { ?>
                <option value="<?php echo $row_gst['gst']; ?>" <?php if($row_gst['default_select']==1){ echo "selected='selected'"; } ?>><?php echo $row_gst['gst']."%"; ?></option> 
          <?php    }
              ?>
        
    </select>
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span>
            
            </td>
            </tr>
            <tr>
                <td>Quentity</td>
                <td><input type="text" id="qty1" style="width:250px;">
</td>
                <td>Unit Price</td>
                <td><input type="text" id="unit_price1" style="width:250px;" ></td>
            </tr>
             <tr>
            <td >HSN Code</td>
            <td>
             <select name="hsn_code1" id="hsn_code1" style="width:250px; height: 25px;">
             
             <?php
              $sql_gst     = "select * from `hsn_list`  where active='1' order by hsn_code ";
             $query_gst     = mysql_query($sql_gst);
            // $select_gst = mysql_fetch_array($query_gst);
             while($row_gst = mysql_fetch_array($query_gst))
             { ?>
                <option value="<?php echo $row_gst['hsn_code']; ?>"><?php echo $row_gst['hsn_code']; ?></option> 
          <?php    }
              ?>
        
    </select>
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span>
            
            </td>
            <td ></td>
            <td>
             
            </td>
            </tr>
            <tr>
                <td>Description</td>
                <td colspan="3"><textarea name="desc1" id="desc1" style="width:640px; height:30px;" ></textarea></td>
            </tr>
            <tr><td colspan="4" align="center"><input type="button" class="add-row" value="Add Row"></td></tr>
               </table>     
        <br><br>
        <h4 class="u-text-2 u-text-palette-1-base1 " style="padding:0px; margin:0px;">All Goods List</h4>
    <table style="margin: 0px 0; border-radius: 10px;border: 1px solid #111111;">
    <tr>
        <td >
            <table  id="myTable"  style=" padding: 10px;">
    
        <thead>
            <tr>
    <th style="width: 20px; left;padding: 2px; " align="center">&nbsp;</th>
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
  </tr>
  </thead>
        <tbody>
<!-- <tr>
    <td style="text-align: left;padding: 2px;"><input type='checkbox' name="record" class='case'/></td>
    <td><span id='snum'>1.</span></td>
    <td><input style="width: 200px;" readonly="readonly"  type='text' id='desc' name='desc[]'/></td>
    <td><input style="width: 70px;" type='text' id='qty' name='qty[]'/></td>
    <td><input style="width: 100px;" type='text' id='unit_price' name='unit_price[]'/></td>
    <td><input style="width: 100px;" type='text' id='sub_total' name='sub_total[]'/> </td>
    <td><input style="width: 50px;" type='text' id='gst' name='gst[]'/> </td>
    <td><input style="width: 100px;" type='text' id='gst_amount' name='gst_amount[]'/> </td>
    <td><input style="width: 100px;" type='text' id='total' name='total[]'/> </td>
</tr>-->
        </tbody>
    </table>
        </td>
    </tr>
    <tr><td >
    <table style=" border-top: 1px dashed #dcdcdc;  margin-top: -18px; padding: 0px 10px 10px 10px;">
    <tr>
    <td style='text-align: left;padding: 2px;'>&nbsp;</td>
    <td style='text-align: left;padding: 4px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td style='text-align: left;padding: 2px;'><input style="width: 130px;" readonly="readonly"  type='text' id='3' value=""  name='3'/></td>
    <td style='text-align: left;padding: 2px;'><input style="width: 130px;" readonly="readonly"  type='text' id='3' value=""  name='3'/></td>
    <td style='text-align: left;padding: 2px;'><input style="width: 170px;" readonly="readonly"  type='text' id='desc_tot' value="Total Items"  name='desc_tot'/></td>
    <td style='text-align: left;padding: 2px;'><input style="width: 70px;" readonly="readonly"  type='text' id='3' value=""  name='3'/></td>
    <td style='text-align: left;padding: 2px;'><input style="width: 30px;" readonly="readonly" type='text' id='qty_tot' name='qty_tot'/></td>
    <td style='text-align: left;padding: 2px;'><input style="width: 60px;" readonly="readonly" type='text' id='unit_price_tot' name='unit_price_tot'/></td>
    <td style='text-align: left;padding: 2px;'><input style="width: 60px;" readonly="readonly" type='text' id='sub_total_tot' name='sub_total_tot'/> </td>
    <td style='text-align: left;padding: 2px;'><input style="width: 30px;" readonly="readonly" type='text' id='tds_tot' name='tds_tot'/> </td>
    <td style='text-align: left;padding: 2px;'><input style="width: 60px;" readonly="readonly"  type='text' id='tds_amount_tot'   name='tds_amount_tot'/></td>
    <td style='text-align: left;padding: 2px;'><input style="width: 30px;" readonly="readonly" type='text' id='gst_tot' name='gst_tot'/> </td>
    <td style='text-align: left;padding: 2px;'><input style="width: 60px;" readonly="readonly" type='text' id='gst_amount_tot' name='gst_amount_tot'/> </td>
    <td style='text-align: left;padding: 2px;'><input style="width: 60px;" readonly="readonly" type='text' id='total_tot' name='total_tot'/> </td>
  </tr></table>
    </td></tr>
    </table>
    
        <button type="button" class="delete-row">Delete Row</button>
       
                </td>
            </tr>
            <!---------      Add repeat Invoice Work         -->
            <tr><td colspan="2">
        <br>
        <b><h4 class="u-text-2 u-text-palette-1-base1 " style="padding:0px; margin:0px;">Repeat Invoice:</h4></b></td></tr>
        
        <tr>
     <td colspan="2">
     <!--repeat_invoice_flag,checkrepeat_invoice_flag,repeat_invoice_flag_div  -->
     <!--<input type="checkbox" name="repeat_invoice_flag" id="repeat_invoice_flag" onchange=" return checkrepeat_invoice_flag();"  >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Add Additional Date</b>
</br>-->

    <table style="margin: 0px 0; border-radius: 10px;border: 1px solid #111111;">
    <tr><td>
    <table style="margin: 0px ; padding: 10px; border-radius: 0px;border-bottom  : 1px solid #111111; width: 100%;">
            <tr>
            
            <td >&nbsp;<b>Add Additional Date</b> </td>
            <td>          <input type="text"  name="invoice_repeat_date" id="invoice_repeat_date" value="<?php echo $_REQUEST['invoice_repeat_date']; ?>" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('invoice_repeat_date')" style="cursor:pointer"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span>
            
                         &nbsp;
            </td>
            <td colspan="2" align="center"><input type="button" class="add-date" value="Add Date">
            
            </td></tr>
     </table>
    </td></tr>
    <tr>
        <td >
            <table  id="myTable_2"  style=" padding: 10px;">
    
        <thead>
            <tr>
    <th style="width: 20px; left;padding: 2px; " align="center">&nbsp;</th>
    <th style="width: 10px; left;padding: 2px; " align="center" > S. No</th>
    
    <th style="width: 250px; left;padding: 2px;" align="center"> Invoice No.</th>
    <th style="width: 150px; left;padding: 2px;" align="center"> Date</th>
    
  </tr>
  </thead>
  <tr>                                               
    <td style='text-align: left;padding: 2px;'>&nbsp;</td>
    <td style='text-align: left;padding: 4px;'>1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>                                           
                                                                 
    <td style='text-align: left;padding: 2px;'><input style="width: 250px;" readonly="readonly" type='text' id='invoice_id_new_old' name='invoice_id_new_old'/> </td>
    <td style='text-align: left;padding: 2px;'><input style="width: 150px;" readonly="readonly" type='text' id='date_new_id_old' name='date_new_id_old'/> </td>
    
  </tr>
        </table>
        </td>
        <!--  //trsns_id_new,invoice_id_new,date_new_id   -->
    </tr>
    <tr><td >
    <table style=" border-top: 1px dashed #dcdcdc;  margin-top: -18px; padding: 0px 0px 0px 0px;">
    
    </table>
    </td></tr>
    </table>
    
                 <button type="button" class="delete-date">Delete Row</button>
       
                </td>
            </tr>
        <!---------      Add repeat Invoice Work  Finish       -->
            <tr><td colspan="2" align="center">
            <input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
            </td></tr>
    </table>
    </td></tr>
    </table>
		<input type="hidden" name="action_perform" id="action_perform" value="" >
		
		</form>
		
		</div>
        <?php include_once("main_body_close.php") ?>
        <?php include_once ("footer.php"); ?>
        </body>
</html>

<script>

function checkpay_flag()
{
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

 function checkrepeat_invoice_flag()
{
    
    if($("#repeat_invoice_flag").val() == "0")
    {
        document.getElementById('repeat_invoice_flag').value="1";
        document.getElementById('repeat_invoice_flag_div').style.display='block';
        
    }else
    {
        document.getElementById('repeat_invoice_flag').value="0";
        document.getElementById('repeat_invoice_flag_div').style.display='none';
        
        document.getElementById('invoice_repeat_date').value="";
        
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
   final_amount_show();
}


function final_amount_show()
{
    flag_val = document.getElementById("gst_due_flag_value").value;
    if(flag_val=="0")
   {
   
    pay_amount_n = document.getElementById("pay_amount").value;
    tot_pay_amount=pay_amount_n;
    document.getElementById("pay_amount_show").value=tot_pay_amount;
   } else if(flag_val=="1")
   {
    pay_amount_n = document.getElementById("pay_amount").value;
    gst_amount_n = document.getElementById("gst_due_amount_new").value;
    tot_pay_amount=Number(pay_amount_n)+Number(gst_amount_n);
    document.getElementById("pay_amount_show").value=tot_pay_amount;
   }
   
}
//invoice_due_calculation ,pay_amount_final, pay_amount,  invoice_due_amount_new_due
//gst_due_calculation,gst_due_amount_new_total , gst_due_amount_new, gst_due_amount_new_due
function invoice_due_calculation()
{
    //alert("hello");
   tot_val= document.getElementById("pay_amount_final").value;
    tot_get_val= document.getElementById("pay_amount").value;
    final_due=Number(tot_val)-Number(tot_get_val);
    document.getElementById("invoice_due_amount_new_due").value=final_due;
    final_amount_show();
   
}
//pay_amount_total,pay_amount,invoice_due_amount_new_due
function tds_due_calculation()
{
   tot_val= document.getElementById("tds_due_amount_new_total").value;
    tot_get_val= document.getElementById("tds_due_amount_new").value;
    final_due=Number(tot_val)-Number(tot_get_val);
    document.getElementById("tds_due_amount_new_due").value=final_due;
   
   
}
function gst_due_calculation()
{
   tot_val= document.getElementById("gst_due_amount_new_total").value;
    tot_get_val= document.getElementById("gst_due_amount_new").value;
    final_due=Number(tot_val)-Number(tot_get_val);
    document.getElementById("gst_due_amount_new_due").value=final_due;
    final_amount_show();
   
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
	else if($("#gst_subdivision_1").val() == "")
	{
		alert("Please Select GST Subdivision Option.");
		$("#gst_subdivision_1").focus();
		return false;
	}
    else if($("#tds_subdivision_1").val() == "")
	{
		alert("Please Select TDS Subdivision Option.");
		$("#tds_subdivision_1").focus();
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
