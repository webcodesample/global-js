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
        
if(isset($_POST['payment_id_tb']) && $_POST['payment_id_tb'] != "")
{  
    $payment_id_tb = $_POST['payment_id_tb'];
    $id_dueinfo_tb = $_POST['id_dueinfo_tb'];
    $payment_type = $_POST['payment_type'];
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
    $old_clear_table_link_id_paytb = $data_del['clear_table_link_id'];
    $old_amount_for_due = $data_del['debit'];
/*
$del_query = "delete from clear_due_amount where invoice_id = '".$invoice_id_clear_tb."' and trans_id='".$trans_id_clear_tb."' and payment_plan_id='".$payment_plan_id_clear_tb."' and type='".$payment_type."' ";
    $del_result = mysql_query($del_query) or die("error in delete payment bank query ".mysql_error());

*/
   // old_main_id_paytb
    $del_query = "delete from payment_plan where id = '".$old_payment_id_cust."' ";
    $del_result = mysql_query($del_query) or die("error in delete payment customer  query ".mysql_error());
       
    $del_query = "delete from payment_plan where id = '".$old_payment_id_bank."' ";
    $del_result = mysql_query($del_query) or die("error in delete payment bank query ".mysql_error());

    $query_del_file="select *  from ".$due_tb_name." where id= ".$id_dueinfo_tb." and pp_linkid_1 = ".$old_payment_id_cust." and  pp_linkid_2=".$old_payment_id_bank." and invoice_id=".$old_invoice_id_paytb." ";
    $result_del_file= mysql_query($query_del_file) or die('error in query '.mysql_error().$query_del_file);
    $data_del_file = mysql_fetch_array($result_del_file);

    
        if($data_del_file['cert_file_name']!="")
        {
            unlink($file_folder."/".$data_del_file['cert_file_name']);
        }

      
    $del_query = "delete from ".$due_tb_name." where id= ".$id_dueinfo_tb." and pp_linkid_1 = ".$old_payment_id_cust." and  pp_linkid_2=".$old_payment_id_bank." and invoice_id=".$old_invoice_id_paytb." ";
   // echo $del_query;
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
            $pay_id=$pay_id.','.$data_del['pp_linkid_1'];
        }

        if($due_pay_id=="")
        {
                $due_pay_id = $data_del['id'];
        }else{
            $due_pay_id=$due_pay_id.','.$data_del['id'];
        }

     }
    /*........       Due amount add in clear entry  start  ........*/
     // old_amount_for_due
     $del_query_clear_amount = "select * from clear_due_amount where invoice_id = '".$old_invoice_id_paytb."' and payment_plan_id='".$old_main_id_paytb."' and type='".$payment_type."' ";
     $del_result_clear_amount = mysql_query($del_query_clear_amount) or die("error in delete payment bank query ".mysql_error());
     $total_gst2 = mysql_num_rows($del_result_clear_amount);
    if($total_gst2 > 0)
    {
     $data_del_clear_amount = mysql_fetch_array($del_result_clear_amount);
     $clear_due_id=$data_del_clear_amount['id'];
     $clear_due_payment_id=$data_del_clear_amount['pp_linkid_1'];
     $final_amount=$data_del_clear_amount['due_amount']+$old_amount_for_due;

     $query5_pay_invoice="update clear_due_amount set due_amount='".$final_amount."' where id = '".$clear_due_id."'";
     $result5_pay_invoice= mysql_query($query5_pay_invoice) or die('error in query '.mysql_error().$query5_pay_invoice);

     
     $query5_pay_invoice="update ".$due_tb_name."  set received_amount='".$final_amount."' where pp_linkid_1 = '".$clear_due_payment_id."' and clear_due_flag=1";
     $result5_pay_invoice= mysql_query($query5_pay_invoice) or die('error in query '.mysql_error().$query5_pay_invoice);

     $query5_pay_invoice="update payment_plan set debit='".$final_amount."' where id = '".$clear_due_payment_id."'";
     $result5_pay_invoice= mysql_query($query5_pay_invoice) or die('error in query '.mysql_error().$query5_pay_invoice);
    }
    /*........       Due amount add in clear entry  end ........*/
   
    if($flag_val==0)
    {
  //      $clear_val_change=",".$clear_type_flag.'= 0';
    /*    $del_query = "delete from clear_due_amount where invoice_id = '".$old_invoice_id_paytb."' and payment_plan_id='".$old_main_id_paytb."' and type='".$payment_type."' ";
    $del_result = mysql_query($del_query) or die("error in delete payment bank query ".mysql_error());
*/
    }
    else{
        $clear_val_change="";
    }

    if($old_clear_table_link_id_paytb!="")
  {
    $clear_val_change=",".$clear_type_flag.'= 0';
    /*$del_query = "delete from clear_due_amount where invoice_id = '".$old_invoice_id_paytb."' and id='".$old_clear_table_link_id_paytb."' and type='".$payment_type."' ";
    $del_result = mysql_query($del_query) or die("error in delete payment bank query ".mysql_error());
*/
  }  

$query5_pay="update payment_plan set ".$type_flag." = '".$flag_val."',".$type_pay_id."='".$pay_id."',".$type_due_pay_id."='".$due_pay_id."'  where invoice_id = '".$old_invoice_id_paytb."'";
$result5_pay= mysql_query($query5_pay) or die('error in query '.mysql_error().$query5_pay);

//  echo $query5_pay;  

    $msg = $payment_type." payment Deleted Successfully.";
}

if(isset($_POST['invoice_id_clear_tb']) && $_POST['invoice_id_clear_tb'] != "")
{ 
    //invoice_unclear_form , trans_id_clear_tb , invoice_id_clear_tb , payment_plan_id_clear_tb , payment_type_clear_tb 
    $trans_id_clear_tb = $_POST['trans_id_clear_tb'];
    $invoice_id_clear_tb = $_POST['invoice_id_clear_tb'];
    $payment_type = $_POST['payment_type_clear_tb'];
    $payment_plan_id_clear_tb = $_POST['payment_plan_id_clear_tb'];
    $pp_linkid_1_clear_tb = $_POST['pp_linkid_1_clear_tb'];
    if($payment_type=="Invoice")
    {
        $due_tb_name="invoice_due_info";
        $type_flag="invoice_flag";
        $clear_type_flag="clear_invoice_flag";
        
        
    }
    if($payment_type=="GST")
    {
        $due_tb_name="gst_due_info";
        $type_flag="gst_flag";
        $clear_type_flag="clear_gst_flag";
        
    }
    if($payment_type=="TDS")
    {
        $due_tb_name="tds_due_info";
        $type_flag="tds_flag";
        $clear_type_flag="clear_tds_flag";
        
    }
      $flag_val=0; 
    $del_query = "delete from clear_due_amount where invoice_id = '".$invoice_id_clear_tb."' and trans_id='".$trans_id_clear_tb."' and payment_plan_id='".$payment_plan_id_clear_tb."' and type='".$payment_type."' ";
    $del_result = mysql_query($del_query) or die("error in delete payment bank query ".mysql_error());

    $del_query = "delete from payment_plan where id = '".$pp_linkid_1_clear_tb."' ";
    $del_result = mysql_query($del_query) or die("error in delete payment customer  query ".mysql_error());

    $del_query = "delete from ".$due_tb_name." where pp_linkid_1 = ".$pp_linkid_1_clear_tb." and invoice_id=".$invoice_id_clear_tb." ";
    $del_result = mysql_query($del_query) or die("error in delete payment bank query ".mysql_error());
     
 

    $query_del="select *  from ".$due_tb_name." where invoice_id = '".$invoice_id_clear_tb."'";
     $result_del= mysql_query($query_del) or die('error in query '.mysql_error().$query_del);
     $total_gst2 = mysql_num_rows($result_del);

      if($total_gst2>0)
      {
            $flag_change_val= ",".$type_flag."= 1 ";
      }  
      else{
        $flag_change_val=",".$type_flag."= 0 ";
      }

    $query5_pay="update payment_plan set ".$clear_type_flag." = '".$flag_val."' ".$flag_change_val." where invoice_id = '".$invoice_id_clear_tb."'";
$result5_pay= mysql_query($query5_pay) or die('error in query '.mysql_error().$query5_pay);


    $msg = $payment_type."clear due payment Deleted Successfully.";
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
        
		//$new_file_name = $attach_file_name.'_'.date("d_M_Y").'.'.$extension;
             $invoice_flag=mysql_real_escape_string(trim($_REQUEST['invoice_flag']));		
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
	
	} }
	
	
}
if(mysql_real_escape_string(trim($_REQUEST['search_action'])) != "")
{
	$from_date = strtotime(mysql_real_escape_string(trim($_REQUEST['from_date'])));
	
	$to_date = strtotime(mysql_real_escape_string(trim($_REQUEST['to_date'])));

	$select_query = "select *,payment_plan.id as payment_id from payment_plan inner join project on payment_plan.project_id = project.id and project.id = '".$_REQUEST['project_id']."'  and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
	$select_result = mysql_query($select_query) or die('error in query select cash query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
	
	$select_query2 = "select *,payment_plan.id as payment_id from payment_plan inner join project on payment_plan.project_id = project.id and project.id = '".$_REQUEST['project_id']."'  and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
	$select_result2 = mysql_query($select_query2) or die('error in query select cash query '.mysql_error().$select_query2);
	$select_total2 = mysql_num_rows($select_result2);
	
    
     $select_query3 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join project on payment_plan.project_id = project.id and project.id = '".$_REQUEST['project_id']."'  and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."'  ";
                $select_result3 = mysql_query($select_query3) or die('error in query select cash query '.mysql_error().$select_query3);
                $select_data3 = mysql_fetch_array($select_result3);
                //echo $select_data3['total_credit']-$select_data3['total_debit'];
                $bal=$select_data3['total_credit']-$select_data3['total_debit'];
                
                
                
                $select_query3_1 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join project on payment_plan.project_id = project.id and project.id = '".$_REQUEST['project_id']."'  and payment_plan.payment_date <= '".$to_date."'  ";
                $select_result3_1 = mysql_query($select_query3_1) or die('error in query select cash query '.mysql_error().$select_query3_1);
                $select_data3_1 = mysql_fetch_array($select_result3_1);
                //echo $select_data3['total_credit']-$select_data3['total_debit'];
                //$bal_credit,$bal_debit
                $bal_1=$select_data3_1['total_credit']-$select_data3_1['total_debit'];
                $bal_credit = $select_data3_1['total_credit'];
                $bal_debit = $select_data3_1['total_debit'];
                $search_start ="1";
                
                
                $select_query5 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join project on payment_plan.project_id = project.id and project.id = '".$_REQUEST['project_id']."'  and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."'  ";
                
       
}
else
{
	$select_query = "select * from payment_plan where id = '".$_REQUEST['payment_id']."'  ORDER BY payment_date DESC ";
	$select_result = mysql_query($select_query) or die('error in query select customer query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
    $select_data = mysql_fetch_array($select_result);
    //trans_type_name ='instmulti_receive_payment'
	/*
	$select_query2 = "select *,payment_plan.id as payment_id from payment_plan inner join project on payment_plan.project_id = project.id and project.id = '".$_REQUEST['project_id']."' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
	$select_result2 = mysql_query($select_query2) or die('error in query select customer query '.mysql_error().$select_query2);
	$select_total2 = mysql_num_rows($select_result2);
 */
        
  /*  $select_query3 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join project on payment_plan.project_id = project.id and project.id = '".$_REQUEST['project_id']."'";
                $select_result3 = mysql_query($select_query3) or die('error in query select cash query '.mysql_error().$select_query3);
                $select_data3 = mysql_fetch_array($select_result3);
                //echo $select_data3['total_credit']-$select_data3['total_debit'];
                $bal=$select_data3['total_credit']-$select_data3['total_debit'];

                $select_query5 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join project on payment_plan.project_id = project.id and project.id = '".$_REQUEST['project_id']."'";

*/
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
  <?php include_once ("top_header2.php"); ?> 
  <?php include_once ("top_menu.php"); ?>
  <input type="hidden" id="print_header" name="print_header" value="Project - <?php echo get_field_value("name","project","id",$_REQUEST['project_id']); ?> Ledger</h3>">
	
  <?php include_once("main_heading_open.php") ?>
  
	<table style="width:100%;" border="0" style="padding:0px 0px; margin-top:-10px;">
    <tr>
        <td style="align:top;" align="left">
        <h4 class="u-text-2 u-text-palette-1-base " style="padding:0px; margin:0px;">
        Invoice Payment Details </h4>
  </td>
        <td width="" style="float:right;">
        <a href="customer-ledger.php?cust_id=<?php echo $select_data['cust_id']; ?>" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
     </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
  <?php include_once("main_body_open.php") ?>
    	
		<br>
		
		<div id="ledger_data">
            <table width="99%" style="padding:0px 10px 10px 10px ;">
            <tr><td>
        <table width="100%" class="tbl_border" >
<tr><td>    
        <table >
            <tr>
            <td valign="top" width="600px">
            <table>
                <tr>
                    <td style="color:#FF0000;">Invoice No  </td>
                    <td width="10px">:-</td>
                    <td style="color:#FF0000;"><?php echo $_REQUEST['invoice_id']; ?></td>
                </tr>
                <tr>
                   <td>Transaction ID</td> 
                   <td width="10px">:-</td>  
                   <td><?php echo $select_data['trans_id']; ?></td> 
                </tr>
                <tr>
                   <td>Customer Name</td>  
                   <td width="10px">:-</td> 
                   <td><?php echo get_field_value("full_name","customer","cust_id",$select_data['cust_id']);  ?></td> 
                </tr>
                <tr>
                   <td>Invoice Issure</td> 
                   <td width="10px">:-</td>  
                   <td><?php echo get_field_value("issuer_name","invoice_issuer","id",$select_data['invoice_issuer_id']);  ?></td> 
                </tr>
                
                <tr>
                   <td>GST Subdivision</td>   
                   <td width="10px">:-</td>
                   <td><?php echo get_field_value("name","gst_subdivision","id",$select_data['gst_subdivision']);  ?></td> 
                </tr>
                
                <tr>
                   <td>TDS Subdivision</td>   
                   <td width="10px">:-</td>
                   <td><?php echo get_field_value("name","tds_subdivision","id",$select_data['tds_subdivision']);  ?></td> 
                </tr>
            </table>
            
            </td>
            <td valign="top">
                <table style="border: 1px solid #111111;">
                <tr>
                    <td width="120px">
                        Invoice Amount  
                    </td>
                    <td width="10px">:</td>
                    <td width="150px" ><?php echo $select_data['invoice_pay_amount']; ?></td>
                </tr>
                
                <tr>
                    <td>
                    &nbsp;&nbsp;&nbsp;Add : TDS Amount  
                    </td>
                    <td width="10px">:</td>
                    <td><?php echo $select_data['tds_amount']; ?></td>
                </tr>
                
                <tr>
                    <td>
                    &nbsp;&nbsp;&nbsp;Add : GST Amount  
                    </td>
                    <td width="10px">:</td>
                    <td><?php echo $select_data['gst_amount']; ?></td>
                </tr>
                
                <tr>
                    <td style="color:#FF0000;">
                        Total Invoice Amount  
                    </td>
                    <td width="10px">:</td>
                    <td style="color:#FF0000;"><?php echo $select_data['credit']; ?></td>
                </tr>
                </table>
            </td>
            </tr>
            </table>
            <h4 class="u-text-2 u-text-palette-1-base1 " style="padding:0px; margin:0px;">Invoice Detail :</h4>
        <table class="data" id="my_table" border="1" cellpadding="1" cellspacing="0" style="border: 1px solid #111111;">
        
			<tr >
            <thead class="report-header">
				<th class="data" width="15px"><b>S.No.</b></th>
                <th class="data" width="80"><b>Date</b></th>
				<th class="data" width="200"><b>Bank</b></th>
				<th class="data"><b>Description</b></th>
				<th class="data" width="50"><b>Debit</b></th>
                <th class="data" width="50"><b>Credit</b></th>
                
                <th class="data" width="70">Balance </th>
				<th class="data" width="70"><b>Action</b></th>
				</thead>
			</tr>
            <tr class="data">
                   <td class="data" width="30px"><?php //echo $i; ?></td>
                   <td class="data"><b> <?php echo date("d-m-Y",$select_data['payment_date']); ?></b></td>
                   <td class="data"><b> <?php //echo date("d-m-Y",$to_date); ?></b></td>
                   
                   <td class="data"><b><?php echo "Invoice Amount"; ?></b></td>
                   <td class="data" nowrap="nowrap"></td>
                   <td class="data" nowrap="nowrap"><?php echo number_format($select_data['invoice_pay_amount'],2,'.',''); 
                   $bal_invoice_1 =  $select_data['invoice_pay_amount'];
                   ?></td>
                   <td class="data" style="color:#FF0000;"  >
                   <b><?php 
                   //echo currency_symbol().
                   echo number_format($select_data['invoice_pay_amount'],2,'.',''); ?></b></td>
              <td class="data" nowrap="nowrap"></td>                       
               </tr>
			<?php
            $select_query_inv = "select * from invoice_due_info where invoice_id = '".$_REQUEST['invoice_id']."'  order by due_date asc  ";
            $select_result_inv = mysql_query($select_query_inv) or die('error in query select gst query '.mysql_error().$select_query_inv);
            $total_invoice = mysql_num_rows($select_result_inv);
           
           /*$select_query_inv = "select * from payment_plan where invoice_id = '".$_REQUEST['invoice_id']."' and ( trans_type_name='instmulti_receive_payment' or trans_type_name ='instmulti_receive_payment_invoice' )order by payment_date asc  ";
            $select_result_inv = mysql_query($select_query_inv) or die('error in query select invoice query '.mysql_error().$select_query_inv);
            $total_invoice = mysql_num_rows($select_result_inv);
            */
			if($total_invoice > 0)
			{
				$i = 1;
				//'trans_type_name']=="instmulti_receive_payment"
			    //$select_data_inv = mysql_fetch_array($select_result_inv);
				//'trans_type_name']=="instmulti_receive_payment"
			    //$bal=$select_data3['total_credit']-$select_data3['total_debit'];
			    $bal_inv=0;
                $i=1;	
            while($select_data_inv = mysql_fetch_array($select_result_inv))
				{
                    if($i==1)
                    {
                    }
                       if($select_data_inv['received_amount']>0)
                       { ?>
        		<tr class="data">
						<td class="data" align="center"><?php echo $i; ?></td>
						<td class="data"><?php echo date("d-m-y",$select_data_inv['due_date']); ?></td>
                        <td class="data"><?php //echo get_field_value("bank_account_name","bank","id",$select_data_inv['bank_id']); 
                        $bank_id_inv= get_field_value("bank_id","payment_plan","id",$select_data_inv['pp_linkid_2']);
                        echo get_field_value("bank_account_name","bank","id",$bank_id_inv);
                        ?></td>
						<td class="data"><span  style="color:#FF0000;" ><?php echo $select_data_inv['combine_description']; ?></span><?php echo $select_data_inv['description']; ?></td>
                        <td class="data"><?php echo number_format($select_data_inv['received_amount'],2,'.','');  ?>
                        </td>
				         <!--<td class="data" <?php if($bal_inv<0) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?> >
                             <?php 
                            //echo currency_symbol().number_format($bal,2,'.','');  
                            $bal_inv=(float)$bal_inv+(float)$select_data_inv['received_amount'];
                            $bal_invoice_1 =$bal_invoice_1-$select_data_inv['received_amount'];
                           ?>
                            </td>-->
                           
                            <td class="data"></td>
                            <td class="data"><?php echo number_format($bal_invoice_1,2,'.','');  ?></td>
                         <?php 
                          ?>
                       <td class="data" nowrap="nowrap"  align="center">
                          <?php //////////////   delete File start     ///////////////?>
					     <?php if($select_data_inv['invoice_id'] != "" && $select_data_inv['invoice_id'] != 0) { 
                            if($select_data_inv['clear_due_flag']==1)
                            {}else {
                            ?>
                        <!--&nbsp;<a href="javascript:account_transaction_invoice(<?php echo $select_data_inv['pp_linkid_1'] ?>,'Invoice',<?php echo $select_data_inv['id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>-->
                    
                        <a href="javascript:void(0);"   title="Edit Invoice" onClick="return edit_invoice_function('<?php echo $select_data_inv['id']; ?>','invoice-ledger','<?php echo $select_data_inv['invoice_id']; ?>','<?php echo $_REQUEST['payment_id']; ?>');" ><img src="mos-css/img/edit.png" title="Edit">  </a> 
     
                        <?php   } 
                        }
                        
                         //////////////    File delete end    ///////////////?>  
                                      <?php 
                        if($select_data_inv['cert_file_name']!=""){
                            ?>
                                <a href="invoice_files/<?php echo $select_data_inv['cert_file_name']; ?>" title="View File" target="_blank" >View</a>
                        
                            <?php
                        }
                        ?>
                    	</td>
					</tr>
				<?php 	$i++;
                       } 
				}
                ?>
    
    <tr class="data">
                       <td class="data" width="30px"><?php //echo $i; ?></td>
                       <td class="data"></td>
                       <td class="data"><b> <?php //echo date("d-m-Y",$to_date); ?></b></td>
                       <td class="data"><b><?php echo "Total Received Amount"; ?></b></td>
                       
                       <td class="data" style="color:#FF0000;"  >
                       <b><?php 
                       //echo currency_symbol().
                       echo number_format($bal_inv,2,'.',''); ?></b></td>

                       <td class="data" nowrap="nowrap"></td>
                       <td class="data" nowrap="nowrap"></td>
                       <td class="data" nowrap="nowrap"></td>                        
                   </tr> 
                   
    <?php
		}else
			{
				?>
				<tr class="data" ><td align="center"  width="30px" colspan="8" class="record_not_found" >No Transactions Found</td></tr>
				<?php
			} ?>
           <tr class="data">
                       <?php  $due_invoice_val =$select_data['invoice_pay_amount']-$bal_inv; ?>
                       <td class="data" width="30px"><?php //echo $i; ?></td>
                       <td class="data"></td>
                       <td class="data"><b> <?php //echo date("d-m-Y",$to_date); ?></b></td>
                       <td class="data"><b><?php echo "Due Amount";
                       
                       if($select_data['clear_invoice_flag']==1){
                           ?>
                    <span  style="color:#FF0000;" > <?php     // echo "(Note : Clear all invoice amount)";
                    $select_query_clearcheck_invoice = "select * from clear_due_amount where invoice_id = '".$_REQUEST['invoice_id']."' and type='Invoice'   ";
                    $select_result_clearcheck_invoice = mysql_query($select_query_clearcheck_invoice) or die('error in query select clear check  query '.mysql_error().$select_query_clearcheck_invoice);
                    $total_invoice_clearcheck_invoice = mysql_num_rows($select_result_clearcheck_invoice);
                    $select_data_clearcheck_invoice = mysql_fetch_array($select_result_clearcheck_invoice);
                    
                    echo "(Note : Clear all Invoice amount) :";
                    echo $select_data_clearcheck_invoice['description'];
                    ?> </span>
                      <?php
                        }
                       ?></b></td>
                       <td class="data" nowrap="nowrap"></td>
                       <td class="data" nowrap="nowrap"></td>
                       <td class="data" style="color:#FF0000;"  >
                       <b><?php 
                       //number_format($select_data['invoice_pay_amount'],2,'.','');
                       
                       //echo currency_symbol().
                      echo number_format($due_invoice_val,2,'.',''); ?></b></td>
                       <td class="data" nowrap="nowrap">
                       <?php 
                     /*  if($select_data['clear_invoice_flag']==1){
                       
                       ?>
                       <a href="javascript:transaction_unclear(<?php echo $select_data_clearcheck_invoice['invoice_id']; ?>,<?php echo $select_data_clearcheck_invoice['payment_plan_id']; ?>,<?php echo $select_data_clearcheck_invoice['trans_id']; ?>,'Invoice',<?php echo $select_data_clearcheck_invoice['pp_linkid_1']; ?>);">Unclear Due</a>
                           <?php  }  */ ?> 
                           
                       </td>                        
                   </tr>
			</table>
            <?php      /***********     GST DETAILS    ********* */     ?>
            <h4 class="u-text-2 u-text-palette-1-base1 " style="padding:0px; margin:0px;">GST Detail :</h4>
        <table class="data" id="my_table" border="1" cellpadding="1" cellspacing="0" style="border: 1px solid #111111;">
        
			<tr >
            <thead class="report-header">
				<th class="data" width="15px"><b>S.No.</b></th>
                <th class="data" width="80"><b>Date</b></th>
				<th class="data" width="200"><b>Bank</b></th>
				<th class="data"><b>Description</b></th>
				<th class="data" width="50"><b>Debit</b></th>
                <th class="data" width="50"><b>Credit</b></th>
                <th class="data" width="70">Balance </th>
				<th class="data" width="70"><b>Action</b></th> 
				</thead>
			</tr>
            <tr class="data">
                   <td class="data" width="30px"><?php //echo $i; ?></td>
                   <td class="data"><b> <?php echo date("d-m-Y",$select_data['payment_date']); ?></b></td>
                   <td class="data"><b> <?php //echo date("d-m-Y",$to_date); ?></b></td>
                   <td class="data"><b><?php echo "GST Amount"; ?></b></td>
                   <td class="data" nowrap="nowrap"></td>
                   <td class="data" nowrap="nowrap"><?php 
                   //echo currency_symbol().
                   echo number_format($select_data['gst_amount'],2,'.',''); ?></td>
                   <td class="data" style="color:#FF0000;"  >
                   <b><?php 
                   //echo currency_symbol().
                   echo number_format($select_data['gst_amount'],2,'.',''); 
                   $bal_invoice_1 =$select_data['gst_amount'];
                   ?></b></td>
                  <td class="data" nowrap="nowrap"></td>                       
               </tr>
			<?php
            $select_query_gst = "select * from gst_due_info where invoice_id = '".$_REQUEST['invoice_id']."'  order by due_date asc  ";
            $select_result_gst = mysql_query($select_query_gst) or die('error in query select gst query '.mysql_error().$select_query_gst);
            $total_invoice = mysql_num_rows($select_result_gst);
            
			if($total_invoice > 0)
			{
				$i = 1;
				//'trans_type_name']=="instmulti_receive_payment"
				                
                //$select_data_inv = mysql_fetch_array($select_result_inv);
				//'trans_type_name']=="instmulti_receive_payment"
			//	$bal=$select_data3['total_credit']-$select_data3['total_debit'];
			
             $bal_gst=0;
            $i=1;	
            while($select_data_gst = mysql_fetch_array($select_result_gst))
				{
                    if($i==1)
                    {
                       ?> 
                   <?php
                    }
                       if($select_data_gst['received_amount']>0)
                       { ?>
        		<tr class="data">
						<td class="data" align="center"><?php echo $i; ?></td>
						<td class="data"><?php echo date("d-m-y",$select_data_gst['due_date']); ?></td>
                        <td class="data"><?php 
                        $bank_id_gst= get_field_value("bank_id","payment_plan","id",$select_data_gst['pp_linkid_2']);
                        echo get_field_value("bank_account_name","bank","id",$bank_id_gst);
                        //echo get_field_value("bank_account_name","bank","id",$select_data_inv['bank_id']); ?></td>
						<td class="data"><span  style="color:#FF0000;" ><?php echo $select_data_gst['combine_description']; ?></span><?php echo $select_data_gst['description']; ?></td>
						     <?php 
                            $bal_gst=(float)$bal_gst+(float)$select_data_gst['received_amount'];
                            $bal_invoice_1 =$bal_invoice_1-$select_data_gst['received_amount'];
                           ?>
                           <td class="data"><?php echo number_format($select_data_gst['received_amount'],2,'.','');  ?>
                            </td>
                            <td class="data"></td>
                            <td class="data"><?php echo number_format($bal_invoice_1,2,'.','');  ?></td>
                         <?php 
                          ?>
                       <td class="data" nowrap="nowrap"  align="center">
                         
                        <?php if($select_data_gst['invoice_id'] != "" && $select_data_gst['invoice_id'] != 0) {
                            if($select_data_gst['clear_due_flag']==1)
                            {}else {
                            
                            ?>
                       <!-- &nbsp;<a href="javascript:account_transaction_invoice(<?php echo $select_data_gst['pp_linkid_1'] ?>,'GST',<?php echo $select_data_gst['id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>
                            --><a href="javascript:void(0);"   title="Edit GST" onClick="return edit_gst_function('<?php echo $select_data_gst['id']; ?>','invoice-ledger','<?php echo $select_data_gst['invoice_id']; ?>','<?php echo $_REQUEST['payment_id']; ?>');" ><img src="mos-css/img/edit.png" title="Edit">  </a> 
                       
                        <?php    
                        } }?>
                        <?php 
                        if($select_data_gst['cert_file_name']!=""){   ?>
                                <a href="gst_files/<?php echo $select_data_gst['cert_file_name']; ?>" title="View File" target="_blank" >View</a>
                         <?php   }          ?>
                           
                         </td>
					</tr>
				<?php 	$i++;
                       } 
				}
                ?>
    
    <tr class="data">
                       <td class="data" width="30px"><?php //echo $i; ?></td>
                       <td class="data"></td>
                       <td class="data"><b> <?php //echo date("d-m-Y",$to_date); ?></b></td>
                       <td class="data"><b><?php echo "Total Received Amount"; ?></b></td>
                       <td class="data" style="color:#FF0000;"  >
                       <b><?php 
                       //echo currency_symbol().
                       echo number_format($bal_gst,2,'.',''); ?></b></td>
                       <td class="data" nowrap="nowrap"></td>
                       <td class="data" nowrap="nowrap"></td>
                       
                       <td class="data" nowrap="nowrap"></td>                      
                   </tr> 
                   
    <?php
		}else
			{
				?>
				<tr class="data" ><td align="center"  width="30px" colspan="8" class="record_not_found" >No Transactions Found</td></tr>
				<?php
			} ?>
           <tr class="data">
                       <?php  $due_gst_val =$select_data['gst_amount']-$bal_gst; ?>
                       <td class="data" width="30px"><?php //echo $i; ?></td>
                       <td class="data"></td>
                       <td class="data"><b> <?php //echo date("d-m-Y",$to_date); ?></b></td>
                       <td class="data"><b><?php echo "Due Amount";
                       
                       if($select_data['clear_gst_flag']==1){
                           ?>
                    <span  style="color:#FF0000;" > <?php  
                    $select_query_clearcheck_gst = "select * from clear_due_amount where invoice_id = '".$_REQUEST['invoice_id']."' and type='GST'   ";
                    $select_result_clearcheck_gst = mysql_query($select_query_clearcheck_gst) or die('error in query select clear check  query '.mysql_error().$select_query_clearcheck_gst);
                    $total_invoice_clearcheck_gst = mysql_num_rows($select_result_clearcheck_gst);
                    $select_data_clearcheck_gst = mysql_fetch_array($select_result_clearcheck_gst);
                    
                    echo "(Note : Clear all GST amount) :";
                    echo $select_data_clearcheck_gst['description'];
                    ?> </span>
                      <?php
                        }
                       ?></b></td>
                       <td class="data" nowrap="nowrap"></td>
                       <td class="data" nowrap="nowrap"></td>
                       <td class="data" style="color:#FF0000;"  >
                       <b><?php   
                       //number_format($select_data['invoice_pay_amount'],2,'.','');
                       // 
                       //echo currency_symbol().
                      echo number_format($due_gst_val,2,'.',''); ?></b></td>
                       <td class="data" nowrap="nowrap">
                       <?php 
                   /*    if($select_data['clear_gst_flag']==1){
                       
                       ?>
                       <a href="javascript:transaction_unclear(<?php echo $select_data_clearcheck_gst['invoice_id']; ?>,<?php echo $select_data_clearcheck_gst['payment_plan_id']; ?>,<?php echo $select_data_clearcheck_gst['trans_id']; ?>,'GST',<?php echo $select_data_clearcheck_gst['pp_linkid_1']; ?>);">Unclear Due</a>
                           <?php  } */ ?> 
                       </td>                       
                   </tr>
			</table>
            <?php  /***********    TDS DETAILS   ******************* */ ?>
            <h4 class="u-text-2 u-text-palette-1-base1 " style="padding:0px; margin:0px;"> TDS Detail :</h4>
        <table class="data" id="my_table" border="1" cellpadding="1" cellspacing="0" style="border: 1px solid #111111;">
        
			<tr >
            <thead class="report-header">
				<th class="data" width="15px"><b>S.No.</b></th>
                <th class="data" width="80"><b>Date</b></th>
				<th class="data" width="200"><b>Bank</b></th>
				<th class="data"><b>Description</b></th>
				<th class="data" width="50"><b>Debit</b></th>
                <th class="data" width="50"><b>Credit</b></th>
                <th class="data" width="70">Balance </th>
				<th class="data" width="70"><b>Action</b></th> 
				</thead>
			</tr>
            <tr class="data">
                   <td class="data" width="30px"><?php //echo $i; ?></td>
                   <td class="data"><b> <?php echo date("d-m-Y",$select_data['payment_date']); ?></b></td>
                   <td class="data"><b> <?php //echo date("d-m-Y",$to_date); ?></b></td>

                   <td class="data"><b><?php echo "TDS Amount"; ?></b></td>
                   <td class="data" nowrap="nowrap"></td>
                   <td class="data" nowrap="nowrap"><?php  echo number_format($select_data['tds_amount'],2,'.',''); ?></td>
                   <td class="data" style="color:#FF0000;"  >
                   <b><?php 
                   //echo currency_symbol().
                   echo number_format($select_data['tds_amount'],2,'.',''); 
                   $bal_invoice_1 = $select_data['tds_amount'];
                   ?></b></td>
                   <td class="data" nowrap="nowrap"></td>                      
               </tr>
			<?php 
            $select_query_tds = "select * from tds_due_info where invoice_id = '".$_REQUEST['invoice_id']."'  order by due_date asc  ";
            $select_result_tds = mysql_query($select_query_tds) or die('error in query select tds query '.mysql_error().$select_query_tds);
            $total_invoice = mysql_num_rows($select_result_tds);
            if($total_invoice > 0)
			{
				$i = 1;
				//'trans_type_name']=="instmulti_receive_payment"
				                
                //$select_data_inv = mysql_fetch_array($select_result_inv);
				//'trans_type_name']=="instmulti_receive_payment"
			//	$bal=$select_data3['total_credit']-$select_data3['total_debit'];
			
             $bal_tds=0;
            $i=1;	
            while($select_data_tds = mysql_fetch_array($select_result_tds))
				{
                    if($i==1)
                    {
                       ?> 
                    <?php
                    }
                       if($select_data_tds['received_amount']>0)
                       { ?>
        		<tr class="data">
						<td class="data" align="center"><?php echo $i; ?></td>
						<td class="data"><?php echo date("d-m-y",$select_data_tds['due_date']); ?></td>
                        <td class="data"><?php 
                        $bank_id_tds= get_field_value("bank_id","payment_plan","id",$select_data_tds['pp_linkid_2']);
                        echo get_field_value("bank_account_name","bank","id",$bank_id_tds); ?></td>
						<td class="data"><span  style="color:#FF0000;" ><?php echo $select_data_tds['combine_description']; ?></span><?php echo $select_data_tds['description']; ?></td>
                        <td class="data"><?php echo number_format($select_data_tds['received_amount'],2,'.','');  ?>
                            </td>
						  <?php    $bal_tds=(float)$bal_tds+(float)$select_data_tds['received_amount'];
                            $bal_invoice_1 =$bal_invoice_1-$select_data_tds['received_amount'];
                          ?> 
                            <td class="data"></td>
                            <td class="data"><?php echo number_format($bal_invoice_1,2,'.','');  ?></td>
                         <?php 
                          ?>
                      <td class="data" nowrap="nowrap" align="center">
                          <?php //////////////   delete File start     ///////////////?>
						 
                        <?php if($select_data_tds['invoice_id'] != "" && $select_data_tds['invoice_id'] != 0) { 
                            if($select_data_tds['clear_due_flag']==1)
                            {}else {
                            
                            ?>
                       <!-- &nbsp;<a href="javascript:account_transaction_invoice(<?php echo $select_data_tds['pp_linkid_1'] ?>,'TDS',<?php echo $select_data_tds['id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>
                            --><a href="javascript:void(0);"   title="Edit TDS" onClick="return edit_tds_function('<?php echo $select_data_tds['id']; ?>','invoice-ledger','<?php echo $select_data_tds['invoice_id']; ?>','<?php echo $_REQUEST['payment_id']; ?>');" ><img src="mos-css/img/edit.png" title="Edit">  </a> 
                    
                    <?php    
                        } }
                        // tds_due_amount_new_total ,tds_pay_form ,tds_due_date , tds_due_amount, tds_due_attach_file, tds_due_des, tds_due_info_id
                         //////////////    File delete end    ///////////////?>  
                    <?php  if($select_data_tds['cert_file_name']!=""){      ?>
                                <a href="tds_files/<?php echo $select_data_tds['cert_file_name']; ?>" title="View File" target="_blank" >View</a> 
                            <?php       }     ?>    
                            
                            
                        
                    </td> 
					</tr>
				<?php 	$i++;
                       } 
				}
                ?>
    
    <tr class="data">
                       <td class="data" width="30px"><?php //echo $i; ?></td>
                       <td class="data"></td>
                       <td class="data"><b> <?php //echo date("d-m-Y",$to_date); ?></b></td>
                       <td class="data"><b><?php echo "Total Received Amount"; ?></b></td>
                       <td class="data" style="color:#FF0000;"  >
                       <b><?php 
                       //echo currency_symbol().
                          echo number_format($bal_tds,2,'.',''); ?></b></td>
                       
                       <td class="data" nowrap="nowrap"></td>
                       <td class="data" nowrap="nowrap"></td>
                       <td class="data" nowrap="nowrap"></td>                       
                   </tr> 
                   
    <?php
		}else
			{
				?>
				<tr class="data" ><td align="center"  width="30px" colspan="8" class="record_not_found" >No Transactions Found</td></tr>
				<?php
			} ?>
            <tr class="data">
                       <?php  $due_tds_val =$select_data['tds_amount']-$bal_tds; ?>
                       <td class="data" width="30px"><?php //echo $i; ?></td>
                       <td class="data"></td>
                       <td class="data"><b> <?php //echo date("d-m-Y",$to_date); ?></b></td>
                       <td class="data"><b><?php echo "Due Amount";
                       
                       if($select_data['clear_tds_flag']==1){
                           ?>
                    <span  style="color:#FF0000;" > <?php      
                    $select_query_clearcheck_tds = "select * from clear_due_amount where invoice_id = '".$_REQUEST['invoice_id']."' and type='TDS'   ";
                    $select_result_clearcheck_tds = mysql_query($select_query_clearcheck_tds) or die('error in query select clear check  query '.mysql_error().$select_query_clearcheck_tds);
                    $total_invoice_clearcheck_tds = mysql_num_rows($select_result_clearcheck_tds);
                    $select_data_clearcheck_tds = mysql_fetch_array($select_result_clearcheck_tds);
                    
                    echo "(Note : Clear all TDS amount) :";
                    echo $select_data_clearcheck_tds['description'];
                    ?> </span>
                      <?php
                        }
                       ?></b></td>
                       <td class="data" nowrap="nowrap"></td>
                       <td class="data" nowrap="nowrap"></td>
                       <td class="data" style="color:#FF0000;"  >
                       <b><?php 
                       //number_format($select_data['invoice_pay_amount'],2,'.','');
                       
                       //echo currency_symbol().
                      echo number_format($due_tds_val,2,'.',''); ?></b></td>
                      <td class="data" nowrap="nowrap">
                       <?php 
                    /*   if($select_data['clear_tds_flag']==1){
                       
                       ?>
                       <a href="javascript:transaction_unclear(<?php echo $select_data_clearcheck_tds['invoice_id']; ?>,<?php echo $select_data_clearcheck_tds['payment_plan_id']; ?>,<?php echo $select_data_clearcheck_tds['trans_id']; ?>,'TDS',<?php echo $select_data_clearcheck_tds['pp_linkid_1']; ?>);">Unclear Due</a>
                           <?php  } */ ?> 
                       

                       </td>            
                   </tr>
           </table>
           </td></tr> </table>
           </td></tr> </table>
        </div>
        
        <?php include_once("main_body_close.php") ?>
        <?php include_once ("footer.php"); ?>
        	
<form name="invoice_project_form" id="invoice_project_form" action="" method="post" >
        
        <input type="hidden" name="payment_id_tb" id="payment_id_tb" value="" >
        <input type="hidden" name="id_dueinfo_tb" id="id_dueinfo_tb" value="" >
        
        <input type="hidden" name="payment_type" id="payment_type" value="" >
        </form>

        <form name="invoice_unclear_form" id="invoice_unclear_form" action="" method="post" >
        
            <input type="hidden" name="trans_id_clear_tb" id="trans_id_clear_tb" value="" >
            <input type="hidden" name="invoice_id_clear_tb" id="invoice_id_clear_tb" value="" >
            <input type="hidden" name="payment_plan_id_clear_tb" id="payment_plan_id_clear_tb" value="" >
            <input type="hidden" name="payment_type_clear_tb" id="payment_type_clear_tb" value="" >
            <!--<input type="hidden" name="invoice_unclear_form" id="invoice_unclear_form" value="" >-->
            <input type="hidden" name="pp_linkid_1_clear_tb" id="pp_linkid_1_clear_tb" value="" >
            
        </form>

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
            filename: "project_ledger",
            fileext: ".xls",        
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true            
        });  
        // $("#thead").show();
    });
   // $('table td').find('td:eq(4)').show(); 
   
});

function edit_tds_function1(tds_due_amount_new_total,tds_pay_form,tds_due_date,tds_due_amount,tds_due_attach_file,tds_due_des,tds_due_info_id)
{
    // tds_due_amount_new_total ,tds_pay_form ,tds_due_date , tds_due_amount, tds_due_attach_file, tds_due_des, tds_due_info_id
    
    document.getElementById("tds_due_amount_new_total").value=tds_due_amount_new_total;
    
    document.getElementById("tds_pay_form").value=tds_pay_form;
    document.getElementById("tds_due_date").value=tds_due_date;
    document.getElementById("tds_due_amount").value=tds_due_amount;
    document.getElementById("tds_due_attach_file").value=tds_due_attach_file;
    document.getElementById("tds_due_des").value=tds_due_des;
    document.getElementById("tds_due_info_id").value=tds_due_info_id;
    
    //alert(due_amount_pay);
    document.getElementById("tds_due_div").style.display="block";
    
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

function account_transaction_invoice(payment_id_tb,payment_type,id_dueinfo_tb)
{
    if(confirm("Are you sure want to delete?!!!!!......"))
    { 
       // $("#trans_t_name").val("instmulti_receive_payment");
        $("#payment_id_tb").val(payment_id_tb);
        $("#id_dueinfo_tb").val(id_dueinfo_tb);
        $("#payment_type").val(payment_type);
        $("#invoice_project_form").submit();
        return true;
    }
}
function transaction_unclear(invoice_id,payment_plan_id,trans_id,payment_type,pp_linkid_1_clear_tb)
{
    if(confirm("Are you sure want to unclear Due?!!!!!......"))
    { 
        $("#trans_id_clear_tb").val(trans_id);
        $("#invoice_id_clear_tb").val(invoice_id);
        $("#payment_plan_id_clear_tb").val(payment_plan_id);
        $("#pp_linkid_1_clear_tb").val(pp_linkid_1_clear_tb);
        
        $("#payment_type_clear_tb").val(payment_type);
        $("#invoice_unclear_form").submit();
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
//newWin.document.write(getHeader());
//newWin.document.write("<h3 align='center'>Master Designation List </h3>");
newWin.document.write("<h3 align='center'>"+print_header1+" </h3>");
//$('tr').children().eq(3).hide();

 $("#header1").hide();
$('table tr').find('td:eq(8)').hide();
newWin.document.write(divToPrint.outerHTML);
newWin.print();
//$('tr').children().eq(7).show();

$('table tr').find('td:eq(8)').show();
$("#header1").show();
newWin.close();
   
   /* printMe=window.open();
    printMe.document.write(document.getElementById("").innerHTML);
    printMe.print();
    printMe.close();*/
}


</script>

