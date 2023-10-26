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


if(trim($_REQUEST['action_perform']) == "add_project")
{
    /*echo '<pre>';
    print_r($_REQUEST);
    exit;*/
    $from_arr = explode("- ",$_REQUEST['from']);
    $cust_id = $from_arr[1];
    //$project_id = get_field_value("id","project","name",$_REQUEST['project']);
    $amount=mysql_real_escape_string(trim($_REQUEST['amount']));
    $description=mysql_real_escape_string(trim($_REQUEST['description']));
    //$subdivision = get_field_value("id","subdivision","name",$_REQUEST['subdivision']);
    $invoice_issuer_arr = explode("- ",$_REQUEST['invoice_issuer']);
    $invoice_issuer_id = $invoice_issuer_arr[1];
    //$subdivision = get_field_value("id","subdivision","name",$_REQUEST['subdivision']);
   // $gst_subdivision = get_field_value("id","gst_subdivision","name",$_REQUEST['gst_subdivision']);
        
    ///////payment Fiels values ///////
    $pay_from_arr = explode(" -",$_REQUEST['pay_form']);
    $pay_bank_id = get_field_value("id","bank","bank_account_name",$pay_from_arr[0]);
    $pay_amount=mysql_real_escape_string(trim($_REQUEST['pay_amount']));
    
    $pay_method=mysql_real_escape_string(trim($_REQUEST['pay_method']));
    $pay_checkno=mysql_real_escape_string(trim($_REQUEST['pay_checkno']));
    ////////////
    ///  $id_first_cust,$id_second_proj,$id_third_bankpay,$id_four_cust_pay
    $id_first_cust=mysql_real_escape_string(trim($_REQUEST['id_first_cust']));
    $id_second_proj=mysql_real_escape_string(trim($_REQUEST['id_second_proj']));
    $id_third_bankpay=mysql_real_escape_string(trim($_REQUEST['id_third_bankpay']));
    $id_four_cust_pay=mysql_real_escape_string(trim($_REQUEST['id_four_cust_pay']));
        $trans_id = mysql_real_escape_string(trim($_REQUEST['trans_id']));    
        $invoice_idnew = mysql_real_escape_string(trim($_REQUEST['invoice_idnew']));
        /* goods detail start*/
        
       $desc_t=$_REQUEST['desc_t'];
       $qty_t=$_REQUEST['qty_t'];
       $unit_price_1=$_REQUEST['unit_price_1'];
       $sub_total=$_REQUEST['sub_total'];
       $gst=$_REQUEST['gst'];
       $tds=$_REQUEST['tds'];
       $gst_amount=$_REQUEST['gst_amount'];
       $tds_amount=$_REQUEST['tds_amount_f'];
       $total=$_REQUEST['total'];
       //totall value fields
       $qty_tot=$_REQUEST['qty_tot'];
       $unit_price_tot=$_REQUEST['unit_price_tot'];
       $sub_total_tot=$_REQUEST['sub_total_tot'];
       $total_tot=$_REQUEST['total_tot'];
       $gst_amount_tot=$_REQUEST['gst_amount_tot'];
       $tds_amount_tot=$_REQUEST['tds_amount_tot'];
       $project_array =$_REQUEST['project'];
       $subdivision_array =$_REQUEST['subdivision'];
       $get_subdivision_array =$_REQUEST['gst_subdivision'];
       $hsn_code=$_REQUEST['hsn_code'];
       
       $gst_subdivision_n = get_field_value("id","gst_subdivision","name",$_REQUEST['gst_subdivision_1']);
       $tds_subdivision_n = get_field_value("id","tds_subdivision","name",$_REQUEST['tds_subdivision_1']);
      
       $old_id_check1=$_REQUEST['old_id_check1'];
       $old_id_check2=$_REQUEST['old_id_check2'];
       $old_new_check=$_REQUEST['old_new_check'];
       $old_link1_id=$_REQUEST['old_link1_id'];
       $old_link2_id=$_REQUEST['old_link2_id'];
        $trans_type = 24;
    $trans_type_name = "instmulti_sale_goods" ;
   
     $trans_id = mysql_real_escape_string(trim($_REQUEST['trans_id']));   
     $invoice_pay_amount = $sub_total_tot - $tds_amount_tot;
    /* goods detail end  */
    //gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."'
    //gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',  ";
    // gst_amount = '".$gst_amount_tot."',
    $query2="update payment_plan set cust_id = '".$cust_id."', credit = '".$total_tot."', description = '".$description."', on_project = '".$project_id."', payment_date = '".strtotime($_REQUEST['payment_date'])."',invoice_issuer_id = '".$invoice_issuer_id."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."', gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."',update_date = '".getTime()."',printable_invoice_number ='".$_REQUEST['invoice_id_print']."',invoice_type = '".$_REQUEST['invoice_type']."' where  id = '".$id_first_cust."'";
    $result2= mysql_query($query2) or die('error in query '.mysql_error().$query2);
  
    $link_id_2 = $id_first_cust;
    
    
    /*       payment query start           */
    
  /*
   $payment_flag=$_REQUEST['payment_flag'];
    if($payment_flag==1){
         $trans_type_pay = 22;
    $trans_type_name_pay= "instmulti_receive_payment" ;
   
    $query_pay ="insert into payment_plan set trans_id = '".$trans_id."', bank_id = '".$pay_bank_id."', credit = '".$pay_amount."', gst_amount = '".$gst_amount_tot."', description = '".$description."', on_customer = '".$cust_id."', invoice_id = '".$invoice_idnew."',payment_flag = '".$payment_flag."', payment_date = '".strtotime($_REQUEST['pay_payment_date'])."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision."',invoice_issuer_id = '".$invoice_issuer_id."' ,payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."',link2_id = '".$link_id_1."',link3_id = '".$link_id_2."', trans_type = '".$trans_type_pay."', trans_type_name = '".$trans_type_name_pay."',create_date = '".getTime()."'";
    $result_pay= mysql_query($query_pay) or die('error in query '.mysql_error().$query_pay);
    
    
    $link_id_1_pay = mysql_insert_id();
    
    
    $query2_pay="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."',invoice_id = '".$invoice_idnew."', debit = '".$pay_amount."', gst_amount = '".$gst_amount_tot."', description = '".$description."', on_project = '".$project_id."', on_bank = '".$pay_bank_id."', payment_flag = '".$payment_flag."',payment_date = '".strtotime($_REQUEST['pay_payment_date'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision."',payment_checkno = '".$pay_checkno."',link2_id = '".$link_id_1."',link3_id = '".$link_id_2."',link_id = '".$link_id_1_pay."',trans_type = '".$trans_type_pay."', trans_type_name = '".$trans_type_name_pay."', create_date = '".getTime()."'";
    $result2_pay= mysql_query($query2_pay) or die('error in query '.mysql_error().$query2_pay);
    
    $link_id_2_pay = mysql_insert_id();   
    
    $query5="update payment_plan set payment_flag = '".$payment_flag."' where id = '".$link_id_2."'";
    $result5= mysql_query($query5) or die('error in query '.mysql_error().$query5);
      $query5="update payment_plan set link2_id = '".$link_id_1_pay."',link3_id = '".$link_id_2_pay."' where id = '".$link_id_2."'";
    $result5= mysql_query($query5) or die('error in query '.mysql_error().$query5);
       $query5_pay="update payment_plan set link_id = '".$link_id_2_pay."' where id = '".$link_id_1_pay."'";
    $result5_pay= mysql_query($query5_pay) or die('error in query '.mysql_error().$query5_pay);
 
  
    }
   $old_payment_flag=$_REQUEST['old_payment_flag'];
   if($old_payment_flag=="1"){
    
    $query_pay ="update payment_plan set  bank_id = '".$pay_bank_id."', credit = '".$pay_amount."',gst_amount = '".$gst_amount_tot."', description = '".$description."', on_customer = '".$cust_id."', on_project = '".$project_id."', payment_date = '".strtotime($_REQUEST['pay_payment_date'])."',invoice_issuer_id = '".$invoice_issuer_id."', payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."', update_date = '".getTime()."' where  id = '".$id_third_bankpay."'";
    $result_pay= mysql_query($query_pay) or die('error in query '.mysql_error().$query_pay);
    
  
    $link_id_1_pay = $id_third_bankpay;
  
    $query2_pay="update payment_plan set cust_id = '".$cust_id."', debit = '".$pay_amount."',gst_amount = '".$gst_amount_tot."', description = '".$description."',  on_bank = '".$pay_bank_id."', payment_date = '".strtotime($_REQUEST['pay_payment_date'])."' ,invoice_issuer_id = '".$invoice_issuer_id."',payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."', update_date = '".getTime()."' where  id = '".$id_four_cust_pay."'";
    $result2_pay= mysql_query($query2_pay) or die('error in query '.mysql_error().$query2_pay);
    
      
    $link_id_2_pay = $id_four_cust_pay;
    
   }
    */
    /*       payment query end           */
    
    
    /*   goods detail start    */
                  
 //record,snum,no_check,desc_t,qty_t,unit_price_1,sub_total,gst,total,gst_amount
 //qty_tot,unit_price_tot,sub_total_tot,total_tot,gst_amount_tot
      
       
     for($ij = 0; $ij < count($old_id_check2); $ij++) 
           {
              if (in_array($old_id_check2[$ij], $old_id_check1))
              {
                  
              }
              else
              {
             // echo $old_id_check2[$ij];
             $del_id = $old_id_check2[$ij];
              $del_project = $old_link1_id[$ij];
              $del_query = "delete from goods_details where id = '".$del_id."'";
              $del_result = mysql_query($del_query) or die("error in delete query ".mysql_error());
    
                $del_query_pro = "delete from payment_plan where id = '".$del_project."'";
              $del_result_pro = mysql_query($del_query_pro) or die("error in delete query ".mysql_error());
                 
              }
           }   
       $ij=1;
       //exit;
    for($i = 0; $i < count($desc_t); $i++) {
        //$old_id_check1,$old_id_check2,$old_new_check
       // desc_total =desc_total+"("+i+")"+$("#desc_t"+i+"").val()+",";
        $project_id = get_field_value("id","project","name",$project_array[$i]);
       
    $subdivision = get_field_value("id","subdivision","name",$subdivision_array[$i]);
    $gst_subdivision = get_field_value("id","gst_subdivision","name",$get_subdivision_array[$i]);
     $sub_tot_perproject = $qty_t[$i]*$unit_price_1[$i];
  $gst_amount_perproject = $gst_amount[$i];
  $tds_amount_perproject = $tds_amount[$i];
       $desc_total_n = $desc_total_n."(".$ij.")".$desc_t[$i].",";
       //echo $project_id;
       
       //$old_id_check1
         if($old_new_check[$i]=="old"){
            $link_goods_details= $old_id_check1[$i];
                //gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."', gst_amount = '".$gst_amount_tot."',  tds_amount = '".$tds_amount_tot."',invoice_pay_amount='".$invoice_pay_amount."'
                 $query="update payment_plan set  on_customer = '".$cust_id."',payment_date = '".strtotime($_REQUEST['payment_date'])."', invoice_issuer_id = '".$invoice_issuer_id."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."', gst_amount = '".$gst_amount_perproject."',  tds_amount = '".$tds_amount_perproject."',invoice_pay_amount='".$invoice_pay_amount."',update_date = '".getTime()."' where  id = '".$old_link2_id[$i]."'";
               // echo $query;
                  //exit;
    $result2= mysql_query($query) or die('error in query '.mysql_error().$query);
    //$link_id_2 = $id_first_cust;
              $query="update goods_details set  cust_id = '".$cust_id."',payment_date = '".strtotime($_REQUEST['payment_date'])."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."', update_date = '".getTime()."', invoice_type='".$_REQUEST['invoice_type']."' where  id = '".$link_goods_details."'";
    $result2= mysql_query($query) or die('error in query '.mysql_error().$query);   
   /* if($payment_flag==1){
    $query5="update payment_plan set payment_flag = '".$payment_flag."', link2_id = '".$link_id_1_pay."',link3_id = '".$link_id_2_pay."' where id = '".$old_link2_id[$i]."'";
     
    $result5= mysql_query($query5) or die('error in query '.mysql_error().$query5);
    }*/
     $multi_project_id = $multi_project_id.$old_link2_id[$i].",";
     
               }
               else if($old_new_check[$i]=="new"){
                 //   echo $project_id;
        //exit;
                    $query="insert into payment_plan set trans_id = '".$trans_id."', project_id = '".$project_id."',  invoice_id = '".$invoice_idnew."', debit = '".$sub_tot_perproject."', gst_amount = '".$gst_amount_perproject."',tds_amount = '".$tds_amount_perproject."',invoice_pay_amount='".$invoice_pay_amount."', description = '".$desc_t[$i]."', hsn_code = '".$hsn_code[$i]."',on_customer = '".$cust_id."', payment_date = '".strtotime($_REQUEST['payment_date'])."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',invoice_issuer_id = '".$invoice_issuer_id."',trans_type = '".$trans_type."', payment_flag = '".$payment_flag."',trans_type_name = '".$trans_type_name."', create_date = '".getTime()."',printable_invoice_number ='".$_REQUEST['invoice_id_print']."',invoice_type = '".$_REQUEST['invoice_type']."'";
    //echo $query;
    //exit;
    $result= mysql_query($query) or die('error in query '.mysql_error().$query);
    
     $link_id_1= mysql_insert_id();
     $query5="update payment_plan set link2_id = '".$link_id_1_pay."',link3_id = '".$link_id_2_pay."' where id = '".$link_id_1."'";
     
    $result5= mysql_query($query5) or die('error in query '.mysql_error().$query5);
    
    $query5="update payment_plan set link_id = '".$link_id_2."' where id = '".$link_id_1."'";
    $result5= mysql_query($query5) or die('error in query '.mysql_error().$query5);
    
   
        $multi_project_id = $multi_project_id.$link_id_1.",";
         //project_id,subdivision,gst_subdivision,invoice_id
        //$desc_total_n = $desc_total_n."(".$ij.")".$desc_t[$i].","; 
        $query_goods_details ="insert into goods_details set trans_id = '".$trans_id."',cust_id = '".$cust_id."', invoice_id = '".$invoice_idnew."',project_id = '".$project_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision_n."',tds_subdivision = '".$tds_subdivision_n."',link1_id = '".$link_id_1."',link2_id = '".$link_id_2."',link3_id = '".$link_id_1_pay."',link4_id = '".$link_id_2_pay."', description = '".$desc_t[$i]."',hsn_code = '".$hsn_code[$i]."',qty = '".$qty_t[$i]."',unit_price = '".$unit_price_1[$i]."',gst_per = '".$gst[$i]."',gst_amount = '".$gst_amount[$i]."',tds_per = '".$tds[$i]."',tds_amount = '".$tds_amount[$i]."',sub_total = '".$sub_tot_perproject."',trans_type = '".$trans_type."',trans_type_name = '".$trans_type_name."',payment_date = '".strtotime($_REQUEST['payment_date'])."',create_date = '".getTime()."'";
        $result_goods_details= mysql_query($query_goods_details) or die('error in query '.mysql_error().$query_goods_details);
        $link_goods_details = mysql_insert_id();
    // $goods_details_idlist=$goods_details_idlist.",".$link_goods_details;
    
 ////////
                                      
                 
               }$goods_details_idlist=$goods_details_idlist.$link_goods_details.",";
               $ij++;
       
}
//goods_detail_id
         /*$query_goods="update payment_plan set goods_detail_id = '".$goods_details_idlist."' , description = '".$desc_total_n."' where id = '".$link_id_1."'";
          $result_goods= mysql_query($query_goods) or die('error in query '.mysql_error().$query_goods);
          $query_goods="update payment_plan set goods_detail_id = '".$goods_details_idlist."' ,description = '".$desc_total_n."' where id = '".$link_id_2."'";
          $result_goods= mysql_query($query_goods) or die('error in query '.mysql_error().$query_goods);
          $query_goods="update payment_plan set goods_detail_id = '".$goods_details_idlist."' ,description = '".$desc_total_n."' where id = '".$link_id_1_pay."'";
          $result_goods= mysql_query($query_goods) or die('error in query '.mysql_error().$query_goods);
          $query_goods="update payment_plan set goods_detail_id = '".$goods_details_idlist."' ,description = '".$desc_total_n."' where id = '".$link_id_2_pay."'";
          $result_goods= mysql_query($query_goods) or die('error in query '.mysql_error().$query_goods);
   */
     $query_goods="update payment_plan set goods_detail_id = '".$goods_details_idlist."' ,multi_project_id= '".$multi_project_id."' ,description = '".$desc_total_n."' where id = '".$link_id_2."'";
          $result_goods= mysql_query($query_goods) or die('error in query '.mysql_error().$query_goods);
          $query_goods="update payment_plan set goods_detail_id = '".$goods_details_idlist."' ,multi_project_id= '".$multi_project_id."' , description = '".$desc_total_n."' where id = '".$link_id_1_pay."'";
          $result_goods= mysql_query($query_goods) or die('error in query '.mysql_error().$query_goods);
          $query_goods="update payment_plan set goods_detail_id = '".$goods_details_idlist."' , multi_project_id= '".$multi_project_id."' ,description = '".$desc_total_n."' where id = '".$link_id_2_pay."'";
          $result_goods= mysql_query($query_goods) or die('error in query '.mysql_error().$query_goods);
   
    /*     goods detail end   */
    
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
        move_uploaded_file($_FILES["attach_file"]["tmp_name"],"transaction_files/" . $new_file_name);
        
        
    
    
    $query5_1="update attach_file set file_name = '".$new_file_name."' where id = '".$link_id_4."'";
    $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);
    
    
   
    
    ///  payment end//
    
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
    
    
    
    $msg = "Instant Sale Goods and Payment Update successfully.";
    $flag = 1;
     $trsns_pname_1 = $_REQUEST['trsns_pname'];
    if($trsns_pname_1=="gst-ledger-instmulti-sale-goods")
    {
         $msg = "GST Multiproject Invoice Update successfully.";
          $flag = 1;
     // echo "<script> location.href='output_gst_subdivision.php'; </script>";
     echo "<script> location.href='output_gst_subdivision_ledger_invoice.php?gst_subdivision_id=".$gst_subdivision_n."'; </script>";
        
    }
    if($trsns_pname_1=="tds-ledger-instmulti-sale-goods")
    {
         $msg = "TDS Multiproject Invoice Update successfully.";
          $flag = 1;
          //output_tds_subdivision_ledger.php?tds_subdivision_id=8
      echo "<script> location.href='output_tds_subdivision_ledger.php?tds_subdivision_id=".$tds_subdivision_n."'; </script>";
        
    }
    if($trsns_pname_1=="customer-ledger-inst-sale-goods")
    {
         $msg = "GST Multiproject Invoice Update successfully.";
          $flag = 1;
   // header(supplier-payment.php);
      echo "<script> location.href='customer.php'; </script>";
        
    }
    
    if($trsns_pname_1=="customer-ledger-inst-receive-payment")
    {
         $msg = "GST Multiproject Invoice Update successfully.";
          $flag = 1;
   // header(supplier-payment.php);
      echo "<script> location.href='customer.php'; </script>";
        
    }
    
    if($trsns_pname_1=="project-ledger-inst-sale-goods")
    {
         $msg = "GST Multiproject Invoice Update successfully.";
          $flag = 1;
   // header(supplier-payment.php);
      echo "<script> location.href='project.php'; </script>";
      //window.history.back();
        
    }
    if($trsns_pname_1=="By subdivision")
    {
         $msg = "GST Multiproject Invoice Update successfully.";
          $flag = 1;
   // header(supplier-payment.php);
      echo "<script>  location.href='subdivision.php'; </script>";
      //window.history.back();
        
    }
    
    if($trsns_pname_1=="bank-ledger-inst-receive-payment")
    {
         $msg = "GST Multiproject Invoice Update successfully.";
          $flag = 1;
   // header(supplier-payment.php);
      echo "<script> location.href='bank.php'; </script>";
        
    }
    if($trsns_pname_1=="invoice-list-inst-sale-goods")
    {
         $msg = " Invoice Update successfully.";
          $flag = 1;
   // header(supplier-payment.php);
      echo "<script> location.href='invoice-list.php'; </script>";
        
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


if($_REQUEST['trsns_pname']=="customer-ledger-inst-sale-goods")
{
    
    $heading_new="( Customer Ledger )";
    $trsns_pname = "customer-ledger-inst-sale-goods";
    
    $select_query = "select * from payment_plan where id=".$_REQUEST['id']." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
     
   //$old_trans_id,$old_cust_id,$old_project_id,$old_amount,$old_description,$old_payment_date,$old_payment_subdivision,$id_first_cust,$id_second_proj,$id_third_bankpay,$id_four_cust_pay
   //$old_pay_bank_id,$old_pay_amount,$old_pay_method,$old_pay_checkno
    $back_data="customer-ledger.php?cust_id=".$select_data['cust_id'];
    $old_trans_id = $select_data['trans_id'];
    
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
    
       
} else if($_REQUEST['trsns_pname']=="invoice-list-inst-sale-goods")
{
    $trsns_pname = "invoice-list-inst-sale-goods";
    
    $select_query = "select * from payment_plan where id=".$_REQUEST['id']." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
     
   //$old_trans_id,$old_cust_id,$old_project_id,$old_amount,$old_description,$old_payment_date,$old_payment_subdivision,$id_first_cust,$id_second_proj,$id_third_bankpay,$id_four_cust_pay
   //$old_pay_bank_id,$old_pay_amount,$old_pay_method,$old_pay_checkno
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
else if($_REQUEST['trsns_pname']=="gst-ledger-instmulti-sale-goods")
{
    $heading_new="( GST Ledger )";
    $trsns_pname = "gst-ledger-instmulti-sale-goods";
    $select_query = "select * from payment_plan where cust_id=".$_REQUEST['cust_id']." and invoice_id=".$_REQUEST['invoice_id']." and trans_id = '".$_REQUEST['trans_id']."' and trans_type_name='instmulti_sale_goods'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
     
   //$old_trans_id,$old_cust_id,$old_project_id,$old_amount,$old_description,$old_payment_date,$old_payment_subdivision,$id_first_cust,$id_second_proj,$id_third_bankpay,$id_four_cust_pay
   //$old_pay_bank_id,$old_pay_amount,$old_pay_method,$old_pay_checkno
    $back_data="customer-ledger.php?cust_id=".$select_data['cust_id'];
    $old_trans_id = $select_data['trans_id'];
    
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
else if($_REQUEST['trsns_pname']=="tds-ledger-instmulti-sale-goods")
{
    $heading_new="( TDS Ledger )";
    $trsns_pname = "tds-ledger-instmulti-sale-goods";
    $select_query = "select * from payment_plan where cust_id=".$_REQUEST['cust_id']." and invoice_id=".$_REQUEST['invoice_id']." and trans_id = '".$_REQUEST['trans_id']."' and trans_type_name='instmulti_sale_goods'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
     
   //$old_trans_id,$old_cust_id,$old_project_id,$old_amount,$old_description,$old_payment_date,$old_payment_subdivision,$id_first_cust,$id_second_proj,$id_third_bankpay,$id_four_cust_pay
   //$old_pay_bank_id,$old_pay_amount,$old_pay_method,$old_pay_checkno
    $back_data="customer-ledger.php?cust_id=".$select_data['cust_id'];
    $old_trans_id = $select_data['trans_id'];
    
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
    
       
}else
if($_REQUEST['trsns_pname']=="customer-ledger-inst-receive-payment")
{
    $heading_new="( Customer Ledger )";
    $back_data="customer-ledger.php?cust_id=".$select_data['cust_id'];
    $trsns_pname = "customer-ledger-inst-receive-payment";
    $select_query_pay = "select * from payment_plan where id=".$_REQUEST['id']." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result_pay = mysql_query($select_query_pay) or die('error in query select supplier query '.mysql_error().$select_query_pay);
    $select_data_pay = mysql_fetch_array($select_result_pay);
    //echo "$select_query_pro";
    $old_pay_bank_id = $select_data_pay['on_bank'];
    $old_pay_amount = $select_data_pay['debit'];
    $old_pay_method = $select_data_pay['payment_method'];
    $old_pay_checkno = $select_data_pay['payment_checkno'];
     $old_pay_payment_date = $select_data_pay['payment_date'];
    $id_first_cust = $select_data_pay['link3_id'];
    
    $select_query = "select * from payment_plan where id=".$id_first_cust." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
     
   //$old_trans_id,$old_cust_id,$old_project_id,$old_amount,$old_description,$old_payment_date,$old_payment_subdivision,$id_first_cust,$id_second_proj,$id_third_bankpay,$id_four_cust_pay
   //$old_pay_bank_id,$old_pay_amount,$old_pay_method,$old_pay_checkno
    
    $old_trans_id = $select_data['trans_id'];
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
    
}else if($_REQUEST['trsns_pname']=="project-ledger-inst-sale-goods")
{ 
   $back_data="customer-ledger.php?cust_id=".$select_data['cust_id'];
   if($_REQUEST['back']!="")
   {
    
    $heading_new="( Subdivision Ledger )";
       $trsns_pname = "By subdivision";
   }
    else{
        
    $heading_new="(Project Ledger )";
        $trsns_pname = "project-ledger-inst-sale-goods";
    }
    
    $select_query_pro = "select * from payment_plan where id=".$_REQUEST['id']." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result_pro = mysql_query($select_query_pro) or die('error in query select supplier query '.mysql_error().$select_query_pro);
    $select_data_pro = mysql_fetch_array($select_result_pro);
     $search_cust_id=$select_data_pro['link_id'];
   //$old_trans_id,$old_cust_id,$old_project_id,$old_amount,$old_description,$old_payment_date,$old_payment_subdivision,$id_first_cust,$id_second_proj,$id_third_bankpay,$id_four_cust_pay
   //$old_pay_bank_id,$old_pay_amount,$old_pay_method,$old_pay_checkno
    
    $select_query = "select * from payment_plan where id=".$search_cust_id." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
     
   //$old_trans_id,$old_cust_id,$old_project_id,$old_amount,$old_description,$old_payment_date,$old_payment_subdivision,$id_first_cust,$id_second_proj,$id_third_bankpay,$id_four_cust_pay
   //$old_pay_bank_id,$old_pay_amount,$old_pay_method,$old_pay_checkno
    
    $old_trans_id = $select_data['trans_id'];
    
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
       
}else 
if($_REQUEST['trsns_pname']=="bank-ledger-inst-receive-payment")
{
    
    $heading_new="(Bank Ledger )";
   $back_data="customer-ledger.php?cust_id=".$select_data['cust_id'];
    $trsns_pname = "bank-ledger-inst-receive-payment";
   $select_query_pay = "select * from payment_plan where id=".$_REQUEST['id']." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result_pay = mysql_query($select_query_pay) or die('error in query select supplier query '.mysql_error().$select_query_pay);
    $select_data_pay = mysql_fetch_array($select_result_pay);
    //echo "$select_query_pro";
    $old_pay_bank_id = $select_data_pay['bank_id'];
    $old_pay_amount = $select_data_pay['credit'];
    $old_pay_method = $select_data_pay['payment_method'];
    $old_pay_checkno = $select_data_pay['payment_checkno'];
    $id_first_cust = $select_data_pay['link3_id'];
     $old_pay_payment_date = $select_data_pay['payment_date'];
     
    
    $select_query = "select * from payment_plan where id=".$id_first_cust." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
     
   //$old_trans_id,$old_cust_id,$old_project_id,$old_amount,$old_description,$old_payment_date,$old_payment_subdivision,$id_first_cust,$id_second_proj,$id_third_bankpay,$id_four_cust_pay
   //$old_pay_bank_id,$old_pay_amount,$old_pay_method,$old_pay_checkno
    
    $old_trans_id = $select_data['trans_id'];
    $old_cust_id = $select_data['cust_id'];
    $old_project_id = $select_data['on_project'];
    $old_amount = $select_data['credit'];
    $old_description = $select_data['description'];
    $old_payment_date = $select_data['payment_date'];
    $old_payment_subdivision = $select_data['subdivision'];
    $old_subdivision = $select_data['subdivision'];
    $old_gst_subdivision = $select_data['gst_subdivision'];
    $old_tds_subdivision = $select_data['tds_subdivision'];
    $old_link_id = $select_data['link_id'];
    $old_id = $select_data['id'];
    $id_first_cust = $select_data['id'];
    $id_second_proj = $select_data['link_id'];
    $id_third_bankpay = $select_data['link2_id'];
    $id_four_cust_pay = $select_data['link3_id'];
    $old_invoice_issuer_id = $select_data['invoice_issuer_id'];
    $old_invoice_idnew = $select_data['invoice_id'];
    $old_payment_flag =  $select_data['payment_flag'];
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
  <?php include_once ("top_header2.php"); ?> 
  <?php include_once ("top_menu.php"); ?>
  <?php include_once("main_heading_open.php") ?>
  
	<table style="width:100%;" border="0" style="padding:0px 0px; margin-top:-10px;">
    <tr>
        <td style="align:top;" align="left">
        <h4 class="u-text-2 u-text-palette-1-base " style="padding:0px; margin:0px;">
        Update Instant sale Invoice <?php echo $heading_new; ?></h4>
  </td>
        <td width="" style="float:right;">
           <input type="button" name="back_button" id="back_button" onclick="goBack()" value="" class="button_back"  />
            </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
  <?php include_once("main_body_open.php") ?>
    
    <div id="adddiv" >
    
    <form name="project_form" id="project_form" action="" method="post" enctype="multipart/form-data" >
        <input type="hidden" id="id_first_cust" name="id_first_cust" value="<?php echo $id_first_cust; ?>">
    <input type="hidden" id="id_second_proj" name="id_second_proj" value="<?php echo $id_second_proj; ?>">
    <input type="hidden" id="id_third_bankpay" name="id_third_bankpay" value="<?php echo $id_third_bankpay; ?>">
    <input type="hidden" id="id_four_cust_pay" name="id_four_cust_pay" value="<?php echo $id_four_cust_pay; ?>">
    <input type="hidden" id="trsns_pname" name="trsns_pname" value="<?php echo $trsns_pname; ?>">
    

        
        <script src="js/datetimepicker_css.js"></script>
        <link rel="stylesheet" href="css/jquery-ui.css" />
  <!--<script src="js/jquery-1.9.1.js"></script>-->
  <script src="js/jquery-ui.js"></script>
           <table width="98%"  style="padding:10px 0px 20px 20px; " >
           <tr><td>
           <table width="100%" class="tbl_border" >
   
        <tr style="width: 50%;" >
            <td style="align:top;" valign="top">
     
        <table width="98%" border="0" >
        <tr><td colspan="2" valign="top">
        <?php if($msg != "") { ?>
    <div class="sukses">
        <?php echo $msg; ?>
        </div>
    <?php } else if($error_msg != "") { ?>
    <div class="gagal">
        
        <?php echo $error_msg; ?>
        </div>
    <?php } ?>
       
        </td></tr>
           <!-- <tr><td colspan="2" valign="top" ><h4 class="u-text-2 u-text-palette-1-base1 " style="padding:0px; margin:0px;">Instant Sale Goods</h4></td></tr>
    -->
            <tr><td width="125px">Transaction ID</td>
            <td style="color:#FF0000; font-weight:bold;"><input type="hidden" id="trans_id"  name="trans_id" value="<?php echo $old_trans_id; ?>"/>&nbsp;<?php echo $old_trans_id; ?></td></tr>
            <tr><td width="125px">NDB Sr. No.</td>
            <td style="color:#FF0000; font-weight:bold;"><input type="hidden" id="invoice_idnew"  name="invoice_idnew" value="<?php echo $old_invoice_idnew; ?>"/>&nbsp;<?php echo $old_invoice_idnew; ?></td></tr>

            <tr><td >Customer name</td>
            <?php
             $sql_cus     = "select cust_id,full_name,short_name from `customer` where cust_id=".$old_cust_id." and type = 'customer'";
             $query_cus     = mysql_query($sql_cus);
             $select_cus = mysql_fetch_array($query_cus);
            ?>
            <td><input type="text" id="from"  name="from" value="<?php echo $select_cus['full_name'].' - '.$select_cus['cust_id'].' - '.$select_cus['short_name']; ?>" onblur="setPrintInvoiceNo()" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>

            <tr><td width="125px">Invoice Issuer</td>
             <?php
             $sql_insv     = "select * from `invoice_issuer` where id=".$old_invoice_issuer_id." ";
             $query_insv     = mysql_query($sql_insv);
             $select_insv = mysql_fetch_array($query_insv);
            ?>
            <td><input type="text" id="invoice_issuer"  name="invoice_issuer" value="<?php echo $select_insv['issuer_name'].' - '.$select_insv['id']; ?>" onblur="setPrintInvoiceNo()" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <tr>
            <td width="125px">Invoice Type</td>
			<td style="font-weight:bold;">
            <Select id="invoice_type" name="invoice_type" onChange="setPrintInvoiceNo()">
            <option value="">Select Invoice Type</option>
            <option value="R" <?php if($select_data['invoice_type']=='R') echo 'selected';?>>GST Rent</option>
            <option value="M" <?php if($select_data['invoice_type']=='M') echo 'selected';?>>GST Maintenance</option>
            <option value="S" <?php if($select_data['invoice_type']=='S') echo 'selected';?>>GST Sales</option>
            <option value="RN" <?php if($select_data['invoice_type']=='RN') echo 'selected';?>>Reimbursement Note</option>
            </select>
            </td>
            </tr>

			<tr>
            <td width="125px">Invoice Month</td>
			<td style="font-weight:bold;">
            <?php
             $pin_parts = explode("/",$select_data['printable_invoice_number']);
             if($select_data['invoice_type']=='M')
             {$pinv_month = $pin_parts[3]; $pinv_year = $pin_parts[4];}
             else
             {$pinv_month = $pin_parts[2]; $pinv_year = $pin_parts[3];}
            ?>
            <Select id="invoice_month" name="invoice_month" onChange="setPrintInvoiceNo()">
            <option value="">Select Month</option>            
            <option value="/01"  <?php if($pinv_month==01) echo 'selected';?>>April</option>
            <option value="/02"  <?php if($pinv_month==02) echo 'selected';?>>May</option>
            <option value="/03"  <?php if($pinv_month==03) echo 'selected';?>>June</option>
            <option value="/04"  <?php if($pinv_month==04) echo 'selected';?>>July</option>
            <option value="/05"  <?php if($pinv_month==05) echo 'selected';?>>August</option>
            <option value="/06"  <?php if($pinv_month==06) echo 'selected';?>>September</option>
            <option value="/07"  <?php if($pinv_month==07) echo 'selected';?>>October</option>
            <option value="/08"  <?php if($pinv_month==08) echo 'selected';?>>November</option>
            <option value="/09"  <?php if($pinv_month==09) echo 'selected';?>>December</option>
            <option value="/10"  <?php if($pinv_month==10) echo 'selected';?>>January</option>
            <option value="/11"  <?php if($pinv_month==11) echo 'selected';?>>February</option>
            <option value="/12"  <?php if($pinv_month==12) echo 'selected';?>>March</option>
            </select>
            </td>
            </tr>

			<tr>
            <td width="125px">Invoice Year</td>
			<td style="font-weight:bold;">
            <Select id="invoice_fy" name="invoice_fy" onChange="setPrintInvoiceNo()">
            <option value="">Select Year</option>            
            <option value="/22" <?php if($pinv_year==22) echo 'selected';?>>22-23</option>
            <option value="/23" <?php if($pinv_year==23) echo 'selected';?>>23-24</option>
            <option value="/24" <?php if($pinv_year==24) echo 'selected';?>>24-25</option>
            <option value="/25" <?php if($pinv_year==25) echo 'selected';?>>25-26</option>
            </select>
            </td>
            </tr>

            <tr>
            <td width="125px"><div id="inv_label">Invoice No.</div></td>
            <td>
            <input type="text" style="color:#FF0000; font-weight:bold;" id="invoice_id_print"  name="invoice_id_print" value="<?= $old_printable_invoice_number ?>" maxlength="16" readonly/>
            </td>
            </tr>
            
            
            <tr><td align="left" valign="top" >Amount</td>
            <td><input type="text"  name="amount" id="amount" value="<?php echo $old_amount ; ?>" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td align="left" valign="top" >Date</td>
        
            <td><input type="text"  name="payment_date" id="payment_date" value="<?php echo date("d-m-Y",$old_payment_date); ?>" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('payment_date')" style="cursor:pointer"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td valign="top" >Description</td>
            
            <td><textarea name="description" id="description" style="width:250px; height:100px;"><?php echo $old_description; ?></textarea>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <?php
             $sql_td     = "select name from `tds_subdivision` where id=".$old_tds_subdivision." ";
             $query_td    = mysql_query($sql_td);
             $row_td = mysql_fetch_array($query_td);
                 
             $sql_gs     = "select name from `gst_subdivision` where id=".$old_gst_subdivision." ";
             $query_gs    = mysql_query($sql_gs);
             $row_gs = mysql_fetch_array($query_gs);
              
            
           /*  $sql_insv     = "select id,issuer_name from `invoice_issuer` where id=".$old_invoice_issuer_id." ";
             $query_insv     = mysql_query($sql_insv);
             $select_insv = mysql_fetch_array($query_insv);
           */ ?>
            <tr><td valign="top" >GST Subdivision Name</td>
			<td><input type="text" id="gst_subdivision_1"  name="gst_subdivision_1" value="<?php echo $row_gs['name']; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
            
			<tr><td valign="top" >TDS Subdivision Name</td>
			<td><input type="text" id="tds_subdivision_1"  name="tds_subdivision_1" value="<?php echo $row_td['name']; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
            <tr><td valign="top" >Attach File</td>
            <td><input type="file" name="attach_file" id="attach_file" value="" onChange="return hide_drag();" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <tr><td valign="top" >Attach File Name</td>
            <td><input type="text" id="attach_file_name"  name="attach_file_name" value="" autocomplete="off"/></td></tr>
            
            <tr><td valign="top" colspan="2" >
            </td></tr>
            
            
            <tr><td></td><td>
            
            </td></tr>
        </table>
        
         </td>
            <td style="width: 50%;" valign="top">
            <?php 
            /**************    Payment work start      ************* */ 
            /*   ?> 
            <input type="hidden" name="old_payment_flag" id="old_payment_flag" value="<?php echo $old_payment_flag; ?>">
               <?php 
               if($old_payment_flag=="1"){
                   
                   ?>
                   <input type="hidden" name="old_payment_flag" id="old_payment_flag" value="<?php echo $old_payment_flag; ?>">
                   <table width="95%">
                <tr><td colspan="2"><h2>Instant Payment Details</h2></td></tr>
            <tr><td width="125px">&nbsp;</td>
            <td style="color:#FF0000; font-weight:bold;">&nbsp;</td></tr>
            <tr><td width="125px">Paid Into</td>
            <td>
            <?php
             $sql_bank     = "select bank_account_name,bank_account_number from `bank`  where id=".$old_pay_bank_id." ";
             $query_bank     = mysql_query($sql_bank);
             $select_bank = mysql_fetch_array($query_bank);
  
            ?>
            <input type="text" id="pay_form"  name="pay_form" value="<?php echo $select_bank['bank_account_name'].' - '.$select_bank['bank_account_number']; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td align="left" valign="top" >Payment Date</td>
        
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
            
            
            <tr><td align="left" valign="top" >Amount Paid</td>
            <td><input type="text"  name="pay_amount" id="pay_amount" value="<?php echo $old_pay_amount; ?>" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
        </table>
                   <?php
               }else{
                   ?>
                   <table width="375px">
            <tr><td>
                <h2 align="left">Instant Payment Details</h2>
                </td></tr>
                <tr><td><input type="checkbox" name="pay_flag" id="pay_flag" onchange=" return checkpay_flag();"  >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Payment</b></td></tr>
                <tr><td>
                <div id="pay_flag_div"   style="display:none; " >
                
                <table width="95%">
              
            <tr><td width="125px">&nbsp;</td>
            <td style="color:#FF0000; font-weight:bold;">&nbsp;</td></tr>
            <tr><td width="125px">Paid Into</td>
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
            
            
            <tr><td align="left" valign="top" >Amount Paid</td>
            <td><input type="text"  name="pay_amount" id="pay_amount" value="" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
        </table>
        </div>
            <input type="hidden" name="payment_flag" id="payment_flag" value="0">
        </td>
        </tr>
        </table>
                   <?php
    
              }
              */ 
              /**************    Payment work start      ************* */ 
              ?> 
                        
            </td>
        </tr>
        
        <tr><td colspan="2">
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
                <option value="<?php echo $row_tds['tds']; ?>" <?php if($row_tds['default_select']==1){ echo "selected='selected'"; } ?> ><?php echo $row_tds['tds']."%"; ?></option> 
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
            
            </td><td ></td>
            <td></td>
            </tr>
            <tr>
                <td>Description</td>
                <td colspan="3"><textarea name="desc1" id="desc1" style="width:640px; height:30px;" ></textarea></td>
            </tr>
            <tr><td colspan="4" align="center"><input type="button" class="add-row" value="Add Row" ></td></tr>
               </table>     
             </br>
               <h4 class="u-text-2 u-text-palette-1-base1 " style="padding:0px; margin:0px;">All Goods List :</h4>
    
    <table style="margin: 0px 0; border-radius: 10px;border: 1px solid #111111;">
    <tr>
        <td >
            <table  id="myTable"  style="width: 750; padding: 10px;">
    
 <!--       <thead>
            <tr><th style="width: 20px; left;padding: 2px; " align="center">&nbsp;</th>
    <th style="width: 10px; left;padding: 2px; " align="center" > S. No</th>
    <th style="width: 130px; left;padding: 2px;" align="center"> Project</th>
    <th style="width: 130px; left;padding: 2px;" align="center"> Sub Division</th>
    <th style="width: 130px; left;padding: 2px;" align="center"> GST Division</th>
    <th style="width: 140px; left;padding: 2px;" align="center"> Description</th>
    <th style="width: 70px; left;padding: 2px;" align="center"> HSN Code</th>
    <th style="width: 30px; left;padding: 2px;" align="center"> Qty.</th>
    <th style="width: 60px; left;padding: 2px;" align="center"> Unit Price</th>
    <th style="width: 60px; left;padding: 2px;" align="center"> SubTotal</th>
    <th style="width: 30px; left;padding: 2px;" align="center"> GST</th>
    <th style="width: 60px; left;padding: 2px;" align="center"> GST Amount</th>
    <th style="width: 60px; left;padding: 2px;" align="center"> Total</th>
    <th style="width: 20px;"></th>
  </tr>
 
  </thead> -->
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
    <th style="width: 20px;"></th>
  </tr>
  </thead>
        
        <tbody>
        <?php
        $sql_goods_series     = "select * from `goods_details` where trans_id= '$old_trans_id' order by id";
    $query_goods_series     = mysql_query($sql_goods_series);
    //echo $sql_goods_series;
    $ii=1;
    while($row_series = mysql_fetch_assoc($query_goods_series)){
        //$val[] = $row['bank_account_name'].' - '.$row['bank_account_number'];
        //record,snum,no_check,desc_t,qty_t,unit_price_1,sub_total,gst,total,gst_amount
 //qty_tot,unit_price_tot,sub_total_tot,total_tot,gst_amount_tot
   
            
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
        ?>
   <!--       <tr>
    <td style="text-align: left;padding: 2px;"><input type='checkbox' name="record" class='case'/></td>
    <td  style='text-align: left;padding: 2px;'><span id='snum'><?php echo $ii; ?></span><input type='hidden' id="<?php echo "no_check$ii"; ?>" value="<?php echo $ii; ?>" name='no_check[]'/></td>
    <td  style='text-align: left;padding: 2px;'><input style="width: 200px;" readonly="readonly" value="<?php echo $row_series['description']; ?>" id="<?php echo "desc_t$ii"; ?>"  type='text'  name='desc_t[]'/></td>
    <td  style='text-align: left;padding: 2px;'><input style="width: 70px;" readonly="readonly" value="<?php echo $row_series['qty']; ?>" type='text' id="<?php echo "qty_t$ii"; ?>" name='qty_t[]'/></td>
    <td  style='text-align: left;padding: 2px;'><input style="width: 100px;" readonly="readonly" type='text' value="<?php echo $row_series['unit_price']; ?>" id="<?php echo "unit_price_1$ii"; ?>" name='unit_price_1[]'/></td>
    <td  style='text-align: left;padding: 2px;'><input style="width: 100px;" readonly="readonly" type='text' value="<?php echo $old_sub_total; ?>" id="<?php echo "sub_total$ii"; ?>" name='sub_total[]'/> </td>
    <td  style='text-align: left;padding: 2px;'><input style="width: 50px;" readonly="readonly" type='text' value="<?php echo $row_series['gst_per']; ?>" id="<?php echo "gst$ii"; ?>" name='gst[]'/> </td>
    <td  style='text-align: left;padding: 2px;'><input style="width: 100px;" readonly="readonly" type='text' value="<?php echo $old_gst_amount; ?>" id="<?php echo "gst_amount$ii"; ?>" name='gst_amount[]'/> </td>
    <td  style='text-align: left;padding: 2px;'><input style="width: 100px;" readonly="readonly" type='text' value="<?php echo $old_grand_total; ?>" id="<?php echo "total$ii"; ?>" name='total[]'/> </td>
    <td style="color: red;" align="center"><input type='hidden' id='old_new_check"+ii+"' value='old' name='old_new_check[]'/><input type='hidden' id='old_id_check1"+ii+"' value=<?php echo $row_series['id']; ?> name='old_id_check1[]'/>Old</td>
  </tr>-->
  <?php   $project1_nm = get_field_value("name","project","id",$row_series['project_id']); 
        $subdivision1_nm = get_field_value("name","subdivision","id",$row_series['subdivision']);  
        $gst_subdivision1_nm = get_field_value("name","gst_subdivision","id",$row_series['gst_subdivision']);  ?>
   
  
  <tr>
    <td style='text-align: left;padding: 2px;'><input type='checkbox' name="record" class='case'/></td>
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
    <td style="color: red;" align="center"><input type='hidden' id='old_new_check"+ii+"' value='old' name='old_new_check[]'/><input type='hidden' id='old_id_check1"+ii+"' value=<?php echo $row_series['id']; ?> name='old_id_check1[]'/><input type='hidden' id='old_link2_id"+ii+"' value=<?php echo $row_series['link1_id']; ?> name='old_link2_id[]'/>Old</td>
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
    <tr><td >
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
  </tr></table>
    </td></tr>
    </table>
    
        <button type="button" class="delete-row">Delete Row</button>
       
                </td>
            </tr>
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
<script src="amit.js"></script>

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
       /* document.getElementById('pay_form').value="";
        document.getElementById('pay_payment_date').value="";
        document.getElementById('pay_method').value="";
        document.getElementById('pay_checkno').value="";
        document.getElementById('pay_amount').value="";
        */
    }
   /* document.getElementById('pay_flag_div').style.display='block';
    */
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
       /* if(confirm("Do you want to print?!!!!!....."))
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
*/            $("#action_perform").val("add_project");
            $("#project_form").submit();
            return true;
  //      }
        
    }
    
}
function validation_2()
{ //project_1,subdivision_1,gst_subdivision_1,qty1,unit_price1,desc1
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
    else if($("#desc1").val() == "")
    {
        alert("Please enter Description");
        $("#desc1").focus();
        return false;
    }
}
</script>

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
/*    if(confirm("Do you want to print?!!!!!....."))
        {
            
            var text = '<table cellpadding="10" cellspacing="0" border="0" width="95%"><tr><td colspan="2" >Receive Goods</td></tr><tr><td width="125px">Transaction ID</td><td><?php echo $_REQUEST['trans_id']; ?></td></tr><tr><td width="125px">From</td><td><?php echo $_REQUEST['from']; ?></td></tr><tr><td >Project</td><td><?php echo $_REQUEST['project']; ?></td></tr><tr><td>Amount</td><td>Rs. &nbsp;<?php echo $_REQUEST['amount']; ?></td></tr><tr><td>Date</td><td><?php echo $_REQUEST['payment_date']; ?></td></tr><tr><td >Description</td><td><?php echo $_REQUEST['description']; ?></td></tr></table>';
            printMe=window.open();
            printMe.document.write(text);
            printMe.print();
            printMe.close();
                        
            
        }
  */  </script>
    <?php
}

?>

