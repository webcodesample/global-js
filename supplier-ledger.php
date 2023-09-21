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

if(isset($_POST['trans_id']) && $_POST['trans_id'] != "")
{
	$trans_id = $_POST['trans_id'];
	$del_query = "delete from payment_plan where trans_id = '".$trans_id."'";
	$del_result = mysql_query($del_query) or die("error in Transaction delete query ".mysql_error());
	$msg = "Transaction Deleted Successfully.";
}
if(isset($_POST['trans_id_1']) && $_POST['trans_id_1'] != "")
{
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
    if($_FILES["attach_file"]["name"] != "")
	{
        $attach_file_name=mysql_real_escape_string(trim($_REQUEST['attach_file_name']));
        $attach_file_id=mysql_real_escape_string(trim($_REQUEST['attach_file_id']));
        $temp = explode(".", $_FILES["attach_file"]["name"]);
        $arr_size = count($temp);
        $extension = end($temp);
        //getTime()
        // $new_file_name = $attach_file_name.'_'.date("d_M_Y").'.'.$extension;
        
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
		
            <tr >
            <thead class="report-header">
				<th class="data" width="30px">S.No.</th>
				<th class="data" width="70px">Date</th>
                <th class="data">To/From<br>Inv. No.</th>
				<th class="data">Project</th>
                <th class="data">Subdivision</th>
				<th class="data">Description</th>
                <th class="data">GST Due</th>
                <th class="data">Invoice Due</th>
				<th class="data">Debit</th>
				<th class="data">Credit</th>
				<th class="data">Balance</th>
				<th class="data noExl" id="header1">File</th>
			</thead>	
			</tr>
			<?php
            $icgst=1;
            $icinvoice=1;

			if($select_total > 0)
			{
				$i = 1;
				
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
                             ?>
                    <tr class="data">
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
                             
                                            <tr class="data">
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
						<td class="data" width="30px"><?php echo $i; ?></td>
						<td class="data"><?php echo date("d-m-Y",$select_data['payment_date']); ?></td>
						<td class="data"><?php echo $select_data['supplier_invoice_number']; ?></td>                    
						<td class="data"><?php echo get_field_value("name","project","id",$select_data['on_project']); ?></td>
                        <td class="data"><?php echo get_field_value("name","subdivision","id",$select_data['subdivision']); ?></td>
						<td class="data"><?php echo $select_data['description']; ?></td>
                        <td class="data">
                        <?php
                        
                        if($select_data['debit'] > 0)
                        {
                            $pay_date = $select_data['payment_date'];
                            //print_r($select_data);
                            if($pay_date > "1648665000"){
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
                        <td class="data">
                        
                        <?php
                        /*   invoice DUE          */
                        
                        if($select_data['debit'] > 0)
                        {
                            $pay_date = $select_data['payment_date'];
                            //echo $select_data['invoice_pay_amount'];
                            //echo $pay_date;
                            if($pay_date >"1648665000"){
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
                            echo currency_symbol().number_format($bal_1,2,'.','');
                            ?>
                            </td>
                            <?php 
                        }else
                        {
                            ?>
                             <td class="data" <?php if($bal<0) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?> >
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
						
						<?php if($select_data['trans_id'] != "" && $select_data['trans_id'] != 0) { ?>
						&nbsp;<a href="javascript:account_transaction(<?php echo $select_data['trans_id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>
						<?php } ?>
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
         <!-------------->  
         <?php include_once ("footer.php"); ?>  

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
</body>
</html>
<script>
$(document).ready(function(){
    $("#export_to_excel").click(function(){
        $("#my_table").table2excel({        
        
            exclude: ".noExl",
            name: "Developer data",
            filename: "Supplier_ledger",
            fileext: ".xls",        
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true            
        });  
    });

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
function close_div()
{
	document.getElementById("attach_div").style.display="none";
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
function account_transaction_1(trans_id_1)
{
    if(confirm("Are you sure want to delete?!!!!!......"))
    {
        $("#trans_id_1").val(trans_id_1);
        $("#trans_form_1").submit();
        return true;
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
newWin.document.write("<h3 align='center'>"+print_header1+" </h3>");

$("#header1").hide();
$('table tr').find('td:eq(8)').hide();
newWin.document.write(divToPrint.outerHTML);
newWin.print();

$('table tr').find('td:eq(8)').show();
$("#header1").show();
newWin.close();
}


</script>
<script src="https://cdn.jsdelivr.net/gh/webcodesample/global-js@main/supledger.js"></script>