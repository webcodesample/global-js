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
	$project_id = get_field_value("id","project","name",$_REQUEST['project']);
	$amount=mysql_real_escape_string(trim($_REQUEST['amount']));
	$description=mysql_real_escape_string(trim($_REQUEST['description']));
	//$subdivision = get_field_value("id","subdivision","name",$_REQUEST['subdivision']);
    $invoice_issuer_arr = explode("- ",$_REQUEST['invoice_issuer']);
    $invoice_issuer_id = $invoice_issuer_arr[1];
$subdivision = get_field_value("id","subdivision","name",$_REQUEST['subdivision']);
    $gst_subdivision = get_field_value("id","gst_subdivision","name",$_REQUEST['gst_subdivision']);
        
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
        /* goods detail start*/
        
       $desc_t=$_REQUEST['desc_t'];
       $qty_t=$_REQUEST['qty_t'];
       $unit_price_1=$_REQUEST['unit_price_1'];
       $sub_total=$_REQUEST['sub_total'];
       $gst=$_REQUEST['gst'];
       $gst_amount=$_REQUEST['gst_amount'];
       $total=$_REQUEST['total'];
       //totall value fields
       $qty_tot=$_REQUEST['qty_tot'];
       $unit_price_tot=$_REQUEST['unit_price_tot'];
       $sub_total_tot=$_REQUEST['sub_total_tot'];
       $total_tot=$_REQUEST['total_tot'];
       $gst_amount_tot=$_REQUEST['gst_amount_tot'];
       
       $old_id_check1=$_REQUEST['old_id_check1'];
       $old_id_check2=$_REQUEST['old_id_check2'];
       $old_new_check=$_REQUEST['old_new_check'];
       
    /* goods detail end  */
//old_id_check1,old_id_check2,old_new_check
          
           
   

	
	
	$query="update payment_plan set  project_id = '".$project_id."', debit = '".$sub_total_tot."',gst_amount = '".$gst_amount_tot."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision."', description = '".$description."', on_customer = '".$cust_id."', payment_date = '".strtotime($_REQUEST['payment_date'])."',invoice_issuer_id = '".$invoice_issuer_id."',update_date = '".getTime()."' where  id = '".$id_second_proj."'";
    
	$result= mysql_query($query) or die('error in query '.mysql_error().$query);
	
	$link_id_1 = $id_second_proj;
	
	$query2="update payment_plan set cust_id = '".$cust_id."', credit = '".$total_tot."',gst_amount = '".$gst_amount_tot."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision."', description = '".$description."', on_project = '".$project_id."', payment_date = '".strtotime($_REQUEST['payment_date'])."',invoice_issuer_id = '".$invoice_issuer_id."',update_date = '".getTime()."' where  id = '".$id_first_cust."'";
	$result2= mysql_query($query2) or die('error in query '.mysql_error().$query2);
	
	$link_id_2 = $id_first_cust;
	
	
    /*       payment query start           */
    
    
    $trans_id = mysql_real_escape_string(trim($_REQUEST['trans_id']));
    
    $query_pay ="update payment_plan set  bank_id = '".$pay_bank_id."', credit = '".$pay_amount."',gst_amount = '".$gst_amount_tot."', subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision."',description = '".$description."', on_customer = '".$cust_id."', on_project = '".$project_id."', payment_date = '".strtotime($_REQUEST['pay_payment_date'])."',invoice_issuer_id = '".$invoice_issuer_id."', payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."', update_date = '".getTime()."' where  id = '".$id_third_bankpay."'";
    $result_pay= mysql_query($query_pay) or die('error in query '.mysql_error().$query_pay);
    
    
    $link_id_1_pay = $id_third_bankpay;
    
    $query2_pay="update payment_plan set cust_id = '".$cust_id."', debit = '".$pay_amount."',gst_amount = '".$gst_amount_tot."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision."', description = '".$description."', on_project = '".$project_id."', on_bank = '".$pay_bank_id."', payment_date = '".strtotime($_REQUEST['pay_payment_date'])."' ,invoice_issuer_id = '".$invoice_issuer_id."',payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."', update_date = '".getTime()."' where  id = '".$id_four_cust_pay."'";
    $result2_pay= mysql_query($query2_pay) or die('error in query '.mysql_error().$query2_pay);
    
    
    
    $link_id_2_pay = $id_four_cust_pay;
    
    
    
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
              //echo $old_id_check2[$ij];
              $del_id = $old_id_check2[$ij];
              $del_query = "delete from goods_details where id = '".$del_id."'";
              $del_result = mysql_query($del_query) or die("error in delete query ".mysql_error());
    
              
              }
           }   
       $ij=1;
    for($i = 0; $i < count($desc_t); $i++) {
        //$old_id_check1,$old_id_check2,$old_new_check
       // desc_total =desc_total+"("+i+")"+$("#desc_t"+i+"").val()+",";
       $desc_total_n = $desc_total_n."(".$ij.")".$desc_t[$i].",";
         if($old_new_check[$i]=="old"){
            $link_goods_details= $old_id_check1[$i];
                 $goods_details_idlist=$goods_details_idlist.$link_goods_details.",";
               }
               else if($old_new_check[$i]=="new"){
                 $query_goods_details ="insert into goods_details set trans_id = '".$trans_id."', link1_id = '".$link_id_1."',link2_id = '".$link_id_2."',link3_id = '".$link_id_1_pay."',link4_id = '".$link_id_2_pay."', description = '".$desc_t[$i]."',qty = '".$qty_t[$i]."',unit_price = '".$unit_price_1[$i]."',gst_per = '".$gst[$i]."',gst_amount = '".$gst_amount[$i]."',trans_type = '".$trans_type."',trans_type_name = '".$trans_type_name."',create_date = '".getTime()."'";
        $result_goods_details= mysql_query($query_goods_details) or die('error in query '.mysql_error().$query_goods_details);
        $link_goods_details = mysql_insert_id();
     $goods_details_idlist=$goods_details_idlist.$link_goods_details.",";
               }
               $ij++;
       
}
//goods_detail_id
         $query_goods="update payment_plan set goods_detail_id = '".$goods_details_idlist."' , description = '".$desc_total_n."' where id = '".$link_id_1."'";
          $result_goods= mysql_query($query_goods) or die('error in query '.mysql_error().$query_goods);
          $query_goods="update payment_plan set goods_detail_id = '".$goods_details_idlist."' ,description = '".$desc_total_n."' where id = '".$link_id_2."'";
          $result_goods= mysql_query($query_goods) or die('error in query '.mysql_error().$query_goods);
          $query_goods="update payment_plan set goods_detail_id = '".$goods_details_idlist."' ,description = '".$desc_total_n."' where id = '".$link_id_1_pay."'";
          $result_goods= mysql_query($query_goods) or die('error in query '.mysql_error().$query_goods);
          $query_goods="update payment_plan set goods_detail_id = '".$goods_details_idlist."' ,description = '".$desc_total_n."' where id = '".$link_id_2_pay."'";
          $result_goods= mysql_query($query_goods) or die('error in query '.mysql_error().$query_goods);
   
    /*     goods detail end   */
    
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
    
    
    
	$msg = "Instant Sale Goods and Payment successfully.";
	$flag = 1;
	
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
    $trsns_pname = "customer-ledger-inst-sale-goods";
    $select_query = "select * from payment_plan where id=".$_REQUEST['id']." and trans_id = '".$_REQUEST['trans_id']."'";
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
}else if($_REQUEST['trsns_pname']=="project-ledger-inst-sale-goods")
{
    $trsns_pname = "project-ledger-inst-sale-goods";
    $select_query = "select * from payment_plan where id=".$_REQUEST['id']." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
     
   //$old_trans_id,$old_cust_id,$old_project_id,$old_amount,$old_description,$old_payment_date,$old_payment_subdivision,$id_first_cust,$id_second_proj,$id_third_bankpay,$id_four_cust_pay
   //$old_pay_bank_id,$old_pay_amount,$old_pay_method,$old_pay_checkno
    
    $old_trans_id = $select_data['trans_id'];
    $old_cust_id = $select_data['on_customer'];
    $old_project_id = $select_data['project_id'];
    $old_amount = $select_data['debit'];
    $old_description = $select_data['description'];
    $old_payment_date = $select_data['payment_date'];
    $old_payment_subdivision = $select_data['subdivision'];
    $old_link_id = $select_data['link_id'];
    $old_id = $select_data['id'];
    $id_first_cust = $select_data['link_id'];
    $id_second_proj = $select_data['id'];
    $id_third_bankpay = $select_data['link2_id'];
    $id_four_cust_pay = $select_data['link3_id'];
    $old_invoice_issuer_id = $select_data['invoice_issuer_id'];
    $old_subdivision = $select_data['subdivision'];
    $old_gst_subdivision = $select_data['gst_subdivision'];
    
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
    $old_link_id = $select_data['link_id'];
    $old_id = $select_data['id'];
    $id_first_cust = $select_data['id'];
    $id_second_proj = $select_data['link_id'];
    $id_third_bankpay = $select_data['link2_id'];
    $id_four_cust_pay = $select_data['link3_id'];
    $old_invoice_issuer_id = $select_data['invoice_issuer_id'];
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

<script src="js/jquery-1.12.4.min.js"></script>

<script>
    $(document).ready(function(){ 
        var ii=$("#i_val").val();
 //record,snum,no_check,desc_t,qty_t,unit_price_1,sub_total,gst,total,gst_amount
 //qty_tot,unit_price_tot,sub_total_tot,total_tot,gst_amount_tot
  
        $(".add-row").click(function(){
            var desc2 = $("#desc1").val();
            var qty2 = $("#qty1").val();
            var unit_price2 = $("#unit_price1").val();
            var gst2 = $("#gst1").val();
             count=$('#myTable tbody tr').length;
             var sub_total2 = unit_price2*qty2;
             var gst_amount =(sub_total2*gst2)/100;
             var total2 = sub_total2+gst_amount;
    var markup="<tr><td style='text-align: left;padding: 2px;'><input type='checkbox' name='record' class='case'/></td><td style='text-align: left;padding: 2px;'><span id='snum"+ii+"'>"+ii+".</span><input type='hidden' id='no_check"+ii+"' value='"+ii+"' name='no_check[]'/></td>";
    markup +="<td style='text-align: left;padding: 2px;'><input type='text' id='desc_t"+ii+"' value='"+desc2+"' name='desc_t[]' style='width: 200px;' readonly='readonly'/></td> <td style='text-align: left;padding: 2px;'><input type='text' id='qty_t"+ii+"' name='qty_t[]' style='width: 70px;' readonly='readonly' value='"+qty2+"'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='unit_price_1"+ii+"' value='"+unit_price2+"' style='width: 100px;' readonly='readonly' name='unit_price_1[]'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='sub_total"+ii+"' value='"+sub_total2+"' style='width: 100px;' readonly='readonly' name='sub_total[]'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='gst"+ii+"' value='"+gst2+"' style='width: 50px;' readonly='readonly' name='gst[]'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='gst_amount"+ii+"' style='width: 100px;' readonly='readonly' value='"+gst_amount+"' name='gst_amount[]'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='total"+ii+"' style='width: 100px;' readonly='readonly' value='"+total2+"' name='total[]'/></td><td style='color: red;' align='center'><input type='hidden' id='old_new_check"+ii+"' value='new' name='old_new_check[]'/>New</td></tr>";
    
    $(' #myTable tbody').append(markup);
    ii++;
   // $("#desc1").val()="";
   var vall="";
   $("#desc1").val(vall);
   $("#qty1").val(vall);
   $("#unit_price1").val(vall);
   
   var sum = 0;
  
 
var qty_t_total = 0;
var sub_total_total=0;
var unit_price_total=0;
var total_amount_total=0;
var gst_amount_total=0;
var desc_total="";
            
    for (var i = 1; i <= ii; i++) { 
            if($("#no_check"+i+"").val()==i){
            qty_t_total =Number(qty_t_total)+Number($("#qty_t"+i+"").val()) ; 
            unit_price_total =Number(unit_price_total)+Number($("#unit_price_1"+i+"").val()) ;
            sub_total_total =Number(sub_total_total)+Number($("#sub_total"+i+"").val()) ; 
            total_amount_total =Number(total_amount_total)+Number($("#total"+i+"").val()) ; 
            gst_amount_total =Number(gst_amount_total)+Number($("#gst_amount"+i+"").val()) ; 
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
var desc_total="";
            for (var i = 1; i <= ii; i++) { 
            if($("#no_check"+i+"").val()==i){
            qty_t_total =Number(qty_t_total)+Number($("#qty_t"+i+"").val()) ; 
            unit_price_total =Number(unit_price_total)+Number($("#unit_price_1"+i+"").val()) ;
            sub_total_total =Number(sub_total_total)+Number($("#sub_total"+i+"").val()) ; 
            total_amount_total =Number(total_amount_total)+Number($("#total"+i+"").val()) ; 
            gst_amount_total =Number(gst_amount_total)+Number($("#gst_amount"+i+"").val()) ; 
            //desc_total =gst_amount_total+"("+i+")"+$("#desc"+i+"").val()+",";     
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
            
            });
        });
    });    
</script>

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
    Update Instant sale Invoice</h3>
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
	
	<form name="project_form" id="project_form" action="" method="post" enctype="multipart/form-data" >
        <input type="hidden" id="id_first_cust" name="id_first_cust" value="<?php echo $id_first_cust; ?>">
    <input type="hidden" id="id_second_proj" name="id_second_proj" value="<?php echo $id_second_proj; ?>">
    <input type="hidden" id="id_third_bankpay" name="id_third_bankpay" value="<?php echo $id_third_bankpay; ?>">
    <input type="hidden" id="id_four_cust_pay" name="id_four_cust_pay" value="<?php echo $id_four_cust_pay; ?>">
    <input type="hidden" id="trsns_pname" name="trsns_pname" value="<?php echo $trsns_pname; ?>">

        
        <script src="js/datetimepicker_css.js"></script>
        <link rel="stylesheet" href="css/jquery-ui.css" />
  <script src="js/jquery-1.9.1.js"></script>
  <script src="js/jquery-ui.js"></script>
           <table width="98%">
        <tr style="width: 50%;" >
            <td>
     
		<table width="98%" border="0">
            <tr><td colspan="2"><h2>Instant Sale Goods</h2></td></tr>
            
			<tr><td width="125px">Transaction ID</td>
			<td style="color:#FF0000; font-weight:bold;"><input type="hidden" id="trans_id"  name="trans_id" value="<?php echo $old_trans_id; ?>"/>&nbsp;<?php echo $old_trans_id; ?></td></tr>
            <tr><td width="125px">Invoice Issuer</td>
             <?php
             $sql_insv     = "select id,issuer_name from `invoice_issuer` where id=".$old_invoice_issuer_id." ";
             $query_insv     = mysql_query($sql_insv);
             $select_insv = mysql_fetch_array($query_insv);
            ?>
            <td><input type="text" id="invoice_issuer"  name="invoice_issuer" value="<?php echo $select_insv['issuer_name'].' - '.$select_insv['id']; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
			<tr><td width="125px">Project Name</td>
			<td>
            <?php $project_nm = get_field_value("name","project","id",$old_project_id);  ?>
            <input type="text" id="project"  name="project" value="<?php echo $project_nm; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td >Customer name</td>
            <?php
             $sql_cus     = "select cust_id,full_name from `customer` where cust_id=".$old_cust_id." and type = 'customer'";
             $query_cus     = mysql_query($sql_cus);
             $select_cus = mysql_fetch_array($query_cus);
            ?>
			<td><input type="text" id="from"  name="from" value="<?php echo $select_cus['full_name'].' - '.$select_cus['cust_id']; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td >Sub Division Name</td>
            <td><?php $subdivision_nm = get_field_value("name","subdivision","id",$old_subdivision);  ?>
             <input type="text" id="subdivision"  name="subdivision" value="<?php echo $subdivision_nm; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span>
            
            </td></tr>
            
            <tr><td >GST Division Name</td>
            <td><?php $gst_subdivision_nm = get_field_value("name","gst_subdivision","id",$old_gst_subdivision);  ?>
             <input type="text" id="gst_subdivision"  name="gst_subdivision" value="<?php echo $gst_subdivision_nm; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span>
            
            </td></tr>
			
						<tr><td align="left" valign="top" >Amount</td>
			<td><input type="text"  name="amount" id="amount" value="<?php echo $old_amount ; ?>" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			<tr><td align="left" valign="top" >Date</td>
		
			<td><input type="text"  name="payment_date" id="payment_date" value="<?php echo date("d-m-Y",$old_payment_date); ?>" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('payment_date')" style="cursor:pointer"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			<tr><td valign="top" >Description</td>
			
            <td><textarea name="description" id="description" style="width:250px; height:100px;"><?php echo $old_description; ?></textarea>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td valign="top" >Attach File</td>
			<td><input type="file" name="attach_file" id="attach_file" value="" onChange="return hide_drag();" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td valign="top" >Attach File Name</td>
			<td><input type="text" id="attach_file_name"  name="attach_file_name" value="" autocomplete="off"/></td></tr>
			
			<tr><td valign="top" colspan="2" >
			<!--<div id="drag_div" style="border:1px solid #CCCCCC; width:100%; background-color:#FFFFFF; border-radius:10px; ">
					
					
					<div style="height:20px; width:100%; background-color:#F9F9F9; border-top-left-radius:10px; border-top-right-radius:10px; color:#FF0000; text-align:left; float:right; " >&nbsp;&nbsp;&nbsp;&nbsp;<strong>Drag Files To Upload</strong>
							</div>
							<div id="dropbox" >
			<span class="message" >Drop Files here to upload.</span>
		</div>
		
		
		<script src="js/jquery.filedrop.js"></script>
		
		
        <script src="js/script.js"></script>		
						</div> -->
			</td></tr>
			
			
			<tr><td></td><td>
			
			</td></tr>
		</table>
        
         </td>
            <td style="width: 50%;" valign="top">
                
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
        
            </td>
        </tr>
        
        <tr><td colspan="2"><h3>&nbsp;</h3>
        <br>
        <b><h2>Add Goods Details :</h2></b></td></tr>
        <tr>
                <td colspan="2">
                    
            
    
        <input type="text" id="desc1" placeholder="Description">
        <input type="text" id="qty1" placeholder="Qty.">
        <input type="text" id="unit_price1" placeholder="Unit Price">
    GST :<select name="gst1" id="gst1" >
        <option value="5">5%</option>
        <option value="12">12%</option>
        <option value="18">18%</option>
        <option value="28">28%</option>
    </select>&nbsp;&nbsp;&nbsp;
        <input type="button" class="add-row" value="Add Row">
        <br><br>
    
    <table style="margin: 0px 0; border-radius: 10px;border: 1px solid #111111;">
    <tr>
        <td >
            <table  id="myTable"  style="width: 750; padding: 10px;">
    
        <thead>
            <tr>
    <th style="width: 20px;"></th>
    <th style="width: 10px;" > S. No</th>
    <th style="width: 200px;"> Description</th>
    <th style="width: 70px;"> Qty.</th>
    <th style="width: 100px;"> Unit Price</th>
    <th style="width: 100px;"> SubTotal</th>
    <th style="width: 50px;"> GST(%)</th>
    <th style="width: 100px;"> GST Amount</th>
    <th style="width: 100px;"> Total</th>
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
            $old_grand_total=$old_sub_total+$old_gst_amount;
            $tot1_qty_t=$tot1_qty_t+$row_series['qty'];
            $tot1_unit_price_1=$tot1_unit_price_1+$row_series['unit_price'];
            $tot1_sub_total= $tot1_sub_total+ $old_sub_total;
            $tot1_gst_amount = $tot1_gst_amount+$old_gst_amount;
            $tot1_grand_total = $tot1_grand_total+$old_grand_total;
        ?>
          <tr>
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
  </tr>
<input type='hidden' id='old_id_check2"+ii+"' value=<?php echo $row_series['id']; ?> name='old_id_check2[]'/>
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
    <td style='text-align: left;padding: 4px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td style='text-align: left;padding: 2px;'><input style="width: 200px;" readonly="readonly"  type='text' id='desc_tot' value="Total Items"  name='desc_tot'/></td>
    <td style='text-align: left;padding: 2px;'><input style="width: 70px;" readonly="readonly" type='text' id='qty_tot' value="<?php echo $tot1_qty_t; ?>" name='qty_tot'/></td>
    <td style='text-align: left;padding: 2px;'><input style="width: 100px;" readonly="readonly" type='text' id='unit_price_tot' value="<?php echo $tot1_unit_price_1;  ?>" name='unit_price_tot'/></td>
    <td style='text-align: left;padding: 2px;'><input style="width: 100px;" readonly="readonly" type='text' id='sub_total_tot' value="<?php echo $tot1_sub_total; ?>" name='sub_total_tot'/> </td>
    <td style='text-align: left;padding: 2px;'><input style="width: 50px;" readonly="readonly" type='text' id='gst_tot' name='gst_tot'/> </td>
    <td style='text-align: left;padding: 2px;'><input style="width: 100px;" readonly="readonly" type='text' id='gst_amount_tot' value="<?php echo $tot1_gst_amount; ?>" name='gst_amount_tot'/> </td>
    <td style='text-align: left;padding: 2px;'><input style="width: 100px;" readonly="readonly" type='text' id='total_tot' value="<?php echo $tot1_grand_total; ?>" name='total_tot'/> </td>
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

	<script>
	$(document).ready(function(){
        $( "#invoice_issuer" ).autocomplete({
            source: "invoice_issuer-ajax.php"
        });
		$( "#from" ).autocomplete({
			source: "customer-ajax.php"
		});
		$( "#project" ).autocomplete({
			source: "project-ajax.php"
		});
         $( "#subdivision" ).autocomplete({
            source: "subdivision2_ajax.php"
        });
        
         $( "#gst_subdivision" ).autocomplete({
            source: "gst_subdivision_ajax.php"
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
