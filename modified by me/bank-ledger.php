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
//trans_t_name=loanadvance_makepayment,advance_loan_no,advance_loan_no_form
if(isset($_POST['advance_loan_no']) && $_POST['advance_loan_no'] != "")
{
    $trans_id = $_POST['advance_loan_no'];
    
    $query_del="select * from payment_plan where advance_loan_no = '".$trans_id."'";
    $result_del= mysql_query($query_del) or die('error in query '.mysql_error().$query_del);
    $data_del = mysql_fetch_array($result_del);
    
    $attach_id=$data_del['id'];    
    
    $query_del2="select * from attach_file where attach_id = '".$attach_id."'";
    $result_del2= mysql_query($query_del2) or die('error in query '.mysql_error().$query_del2);
    while($data_del2 = mysql_fetch_array($result_del2))
    {
    $first_id= $data_del2['id'];
    $second_id= $data_del2['old_id'];

    $fnm = $data_del2['file_name'];
    
    $del_query_1 = "delete from attach_file where id = '".$first_id."'";
    $del_result_1 = mysql_query($del_query_1) or die("error in Transaction delete query ".mysql_error());
    
    unlink("transaction_files/$fnm");
    $del_query_2 = "delete from attach_file where id = '".$second_id."'";
    $del_result_2 = mysql_query($del_query_2) or die("error in Transaction delete query ".mysql_error());
    }
    
    $del_query = "delete from payment_plan where advance_loan_no = '".$trans_id."'";
    $del_result = mysql_query($del_query) or die("error in Transaction delete query ".mysql_error());
    $msg = "loan And Advance Deleted Successfully.";
    
}
if(isset($_POST['invoice_id']) && $_POST['invoice_id'] != "")
{    
    $trans_t_name = $_POST['trans_t_name'];
    $invoice_id = $_POST['invoice_id'];
    if($trans_t_name=="instmulti_receive_payment")
    {
        $del_query = "delete from payment_plan where invoice_id = '".$invoice_id."' and trans_type_name='instmulti_receive_payment'";
        $del_result = mysql_query($del_query) or die("error in invoice delete query ".mysql_error());
        
        
        $query5_1="update payment_plan set link2_id = '0',link3_id = '0' ,payment_flag = '0' where invoice_id = '".$invoice_id."'";
        $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);
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
if(isset($_POST['trans_id_invoice']) && $_POST['trans_id_invoice'] != "")
{
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
{
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
{
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
    
        $query3="insert into attach_file set attach_id = '".$attach_file_id."', link_id = '".$link_id_2."',file_name = '".$new_file_name."'";
        $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
        $link_id_4 = mysql_insert_id();
        
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
if(mysql_real_escape_string(trim($_REQUEST['search_action'])) != "")
{
	$from_date = strtotime(mysql_real_escape_string(trim($_REQUEST['from_date'])));
	
	$to_date = strtotime(mysql_real_escape_string(trim($_REQUEST['to_date'])));

	$select_query = "select *,payment_plan.id as payment_id from payment_plan inner join bank on payment_plan.bank_id = bank.id and bank.id = '".$_REQUEST['bank_id']."' and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
	$select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
	
	$select_query2 = "select *,payment_plan.id as payment_id from payment_plan inner join bank on payment_plan.bank_id = bank.id and bank.id = '".$_REQUEST['bank_id']."' and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."' ORDER BY payment_plan.payment_date,payment_plan.id DESC ";
	$select_result2 = mysql_query($select_query2) or die('error in query select bank query '.mysql_error().$select_query2);
	$select_total2 = mysql_num_rows($select_result2);
    
    $select_query3 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join bank on payment_plan.bank_id = bank.id and bank.id = '".$_REQUEST['bank_id']."' and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."'";
    $select_result3 = mysql_query($select_query3) or die('error in query select cash query '.mysql_error().$select_query3);
    $select_data3 = mysql_fetch_array($select_result3);
    $bal=$select_data3['total_credit']-$select_data3['total_debit'];
                
    $select_query3_1 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join bank on payment_plan.bank_id = bank.id and bank.id = '".$_REQUEST['bank_id']."'   and payment_plan.payment_date <= '".$to_date."'  ";
    $select_result3_1 = mysql_query($select_query3_1) or die('error in query select cash query '.mysql_error().$select_query3_1);
    $select_data3_1 = mysql_fetch_array($select_result3_1);

    $bal_1=$select_data3_1['total_credit']-$select_data3_1['total_debit'];
    $bal_credit = $select_data3_1['total_credit'];
    $bal_debit = $select_data3_1['total_debit'];
    $search_start ="1";
                
    $select_query5 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join bank on payment_plan.bank_id = bank.id and bank.id = '".$_REQUEST['bank_id']."' and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."'";
}
else
{
	$select_query = "select *,payment_plan.id as payment_id from payment_plan inner join bank on payment_plan.bank_id = bank.id and bank.id = '".$_REQUEST['bank_id']."' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
	$select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
	
	$select_query2 = "select *,payment_plan.id as payment_id from payment_plan inner join bank on payment_plan.bank_id = bank.id and bank.id = '".$_REQUEST['bank_id']."' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
	$select_result2 = mysql_query($select_query2) or die('error in query select bank query '.mysql_error().$select_query2);
	$select_total2 = mysql_num_rows($select_result2);
         
    $select_query3 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join bank on payment_plan.bank_id = bank.id and bank.id = '".$_REQUEST['bank_id']."'";
    $select_result3 = mysql_query($select_query3) or die('error in query select cash query '.mysql_error().$select_query3);
    $select_data3 = mysql_fetch_array($select_result3);
    $bal=$select_data3['total_credit']-$select_data3['total_debit'];

    $select_query5 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join bank on payment_plan.bank_id = bank.id and bank.id = '".$_REQUEST['bank_id']."'";
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
  
<script src="dist/jquery.table2excel.min.js"></script>
<link rel="stylesheet" href="css/jquery-ui.css" />
			 <!--<script src="js/jquery-1.9.1.js"></script>-->
			  <script src="js/jquery-ui.js"></script>
			
  <?php include_once("main_heading_open.php") ?>
  <table style="width:100%;" border="0" style="padding:0px 0px; margin-top:-10px;">
    <tr>
        <td style="align:top;" align="left"><h4 class="u-text-2 u-text-palette-1-base " style="padding:0px; margin:0px;">
  Bank -<?php echo get_field_value("bank_account_name","bank","id",$_REQUEST['bank_id']); ?> Ledger
    </h4>
  </td>
        <td width="" style="float:right;">
            <a href="bank.php" title="Back" style=""><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
            <input type="button" name="print_button" id="print_button" value="" class="button_print" onClick="return print_data();"  />
            <input type="button" id="export_to_excel" value="" class="button_export" >
            <input type="button" id="search" value="" class="button_search1" onClick="search_display();" >
        </td>
    </tr>
</table>
    
    
         
  <?php include_once("main_heading_close.php") ?>
         <!-------------->
         <?php include_once("main_search_open.php") ?>
         <input type="hidden" name="search_check_val" id="search_check_val" value="<?php echo $sear_val_f;//echo $_REQUEST['search_check_val']; ?>" >
  <input type="hidden" name="search_check_val_1" id="search_check_val_1" value="<?php echo $sear_val_f;//echo $_REQUEST['search_check_val']; ?>" >
  
         <input type="hidden" id="print_header" name="print_header" value="Bank - <?php echo get_field_value("bank_account_name","bank","id",$_REQUEST['bank_id']); ?> Ledger">
		<br>
<form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" >
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
				 
					<td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='bank-ledger.php?bank_id=<?php echo $_REQUEST['bank_id']; ?>';"  /></td>
					<td align="right" valign="top" >     </td>
					
				</tr>
			</table>
			<input type="hidden" name="search_action" id="search_action" value="Search"  />
			
			</form>
    
         <?php include_once("main_search_close.php") ?>
        
         <?php include_once("main_body_open.php") ?>
          <!-- MAIN BODY START -->
          <div id="ledger_data"  style="height: 390px;  overflow-y: scroll;overflow-x: scroll;padding:0px;">
		<table class="data" id="my_table" border="1" cellpadding="1" cellspacing="0" style="border: 1px solid #111111;">
        <tr style="display:none ;">
            <td><b>Bank Ledger :</b></td><td><b><?php echo get_field_value("bank_account_name","bank","id",$_REQUEST['bank_id']); ?></b></td>
            <td><b> Generated On :</b></td><td><b><?php echo getTime(); echo "("; $username_1=get_field_value("full_name","user","userid",$_SESSION['userId']); echo $username_1; echo ")";?></b></td>

        </tr>    
        <tr style="display:none ;">
        <td><b>Period Start :</b></td><td><b><?php if($_REQUEST['from_date']!=""){ echo date("d-m-Y",strtotime($_REQUEST['from_date']));  }?></b></td>
            <td><b>Period End :</b></td><td><b><?php if($_REQUEST['to_date']!=""){ echo date("d-m-Y",strtotime($_REQUEST['to_date'])); } ?></b></td>
            
        </tr>   
			<tr >
            <thead class="report-header">
				<th class="data w_30" ><b>S.No.</b></th>
				<th class="data w_80" ><b>Date</th>
				<th class="data w_80"><b>To&nbsp; / From</th>
				<th class="data w_80"><b>Project</th>
				<th class="data w_160_m "><b>Description</th>
				<th class="data w_50" ><b>Debit</th>
				<th class="data w_50" ><b>Credit</th>
				<th class="data w_50" ><b>Balance</th>
				<th class="data noExl w_80" id="header1"><b>File</b>
            </th>
				</thead>
			</tr>
			<?php
			if($select_total > 0)
			{
				$i = 1;
				
				while($select_data = mysql_fetch_array($select_result))
				{
					if($i > 1)
					{
						$select_query4 = "select debit,credit from payment_plan where bank_id = '".$_REQUEST['bank_id']."' and id = '".$temp_payment_id."' LIMIT 0,1  ";
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
                         if($search_start=="1")
                         {
                             ?>
                        <tr class="data">
                        <td class="data data_fixed top_25 bg_w" ><?php //echo $i; ?></td>
                        <td class="data font_10 data_fixed top_25 bg_w"><b> <?php echo date("d-m-Y",$to_date); ?></b></td>
                       <td class="data data_fixed top_25 bg_w"></td>
                        <td class="data data_fixed top_25 bg_w"></td>
                        <td class="data data_fixed top_25 bg_w"><b><?php echo "Closing Balance"; ?></b></td>
                        <td class="data data_fixed top_25 bg_w"><b><?php echo number_format($select_data3_1['total_debit'],2,'.',''); ?> </b></td>
                        <td class="data data_fixed top_25 bg_w"><b><?php echo number_format($select_data3_1['total_credit'],2,'.',''); ?></b></td>
                        <td class="data data_fixed top_25 bg_w" <?php if($bal_1<0 ) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?>><b><?php //echo currency_symbol().
                        echo number_format($bal_1,2,'.',''); ?></b></td>
                        <td class="data data_fixed top_25 bg_w" nowrap="nowrap"></td>                        
                    </tr>
                             <?php
                         }
                         else{
                             ?>
                             
                                            <tr class="data">
                        <td class="data data_fixed top_25 bg_w" ><?php //echo $i; ?></td>
                        <td class="data font_10 data_fixed top_25 bg_w"><b> <?php echo date("d-m-Y",$select_data['payment_date']); ?></b></td>
                        <td class="data data_fixed top_25 bg_w"></td>
                        <td class="data data_fixed top_25 bg_w"></td>
                        <td class="data data_fixed top_25 bg_w"><b><?php echo "Closing Balance"; ?></b></td>
                        <td class="data data_fixed top_25 bg_w"><b><?php echo "&#8377;&nbsp;".number_format($select_data3['total_debit'],2,'.',''); ?> </b></td>
                        <td class="data data_fixed top_25 bg_w"><b><?php echo "&#8377;&nbsp;".number_format($select_data3['total_credit'],2,'.',''); ?></b></td>
                        <td class="data data_fixed top_25 bg_w" <?php if($bal<0) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?>><b><?php //echo currency_symbol().
                        echo "&#8377;&nbsp;".number_format($bal,2,'.',''); ?></b></td>
                        <td class="data data_fixed top_25 bg_w" nowrap="nowrap"></td>                        
                    </tr>
                             <?php
    

                         }
?>
                         

                        <?php
    
    
                    }
                    
					 ?>
					<tr class="data">
						<td class="data" align="center" ><?php echo $i; ?></td>
						<td class="data font_10 w_80"><?php echo date("d-m-y",$select_data['payment_date']); ?></td>
						<td class="data"><?php	
						if($select_data['on_customer'] != "")
						{
							echo get_field_value("full_name","customer","cust_id",$select_data['on_customer']);
							
						}
						else if($select_data['on_bank'] != "")
						{
							echo get_field_value("bank_account_name","bank","id",$select_data['on_bank']);
							
							
						}
                        else if($select_data['on_loan'] > 0 )
                        {
                            echo get_field_value("name","loan_advance","id",$select_data['on_loan']);
                            echo "(Loan & Advance)";
                            
                        }
							?>	</td>
						<td class="data"><?php
						if($select_data['on_project'] != "")
						{
							echo get_field_value("name","project","id",$select_data['on_project']);
							
						}
						 ?></td>
						<td class="data"><?php echo $select_data['description']; ?></td>
						<td class="data">
						<?php
							if($select_data['debit'] > 0 && $select_data['description'] != "Opening Balance")
							{
								echo "&#8377;&nbsp;".number_format($select_data['debit'],2,'.','');
							}
							
							
							
							?>
						</td>
						<td class="data">
						<?php
						/*	if($select_data['credit'] > 0 && $select_data['description'] != "Opening Balance")
							{
								echo number_format($select_data['credit'],2,'.','');
							}*/
							
							if($select_data['credit'] > 0 && $select_data['description'] != "Opening Balance")
							{
								echo "&#8377;&nbsp;".number_format($select_data['credit'],2,'.','');
							}else
							if($select_data['credit'] < 0 && $select_data['description'] != "Opening Balance")
							{
								echo "&#8377;&nbsp;".number_format($select_data['credit'],2,'.','');
							}	
							
							
							?>
						</td>
						
                        <?php 
                        
                         if($search_start=="1"){
                             ?>
                             <td class="data" <?php if($bal_1<0 ) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?>  >
                             <?php 
                           // echo currency_symbol().
                            echo "&#8377;&nbsp;".number_format($bal_1,2,'.','');
                            ?>
                            </td>
                            <?php 
                        }else
                        {
                            ?>
                             <td class="data" <?php if($bal<0) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?> >
                             <?php 
                            //echo currency_symbol().
                            echo "&#8377;&nbsp;".number_format($bal,2,'.','');  
                            ?>
                            </td>
                            <?php  
                        }
                        $bal_1=(float)$bal_1+(float)$select_data['debit'];
                        $bal_1=(float)$bal_1-(float)$select_data['credit'];
                        $bal_credit =(float)$bal_credit-(float)$select_data['credit'];
                        $bal_debit = (float)$bal_debit-(float)$select_data['debit'];
                        $date_old = $select_data['payment_date'];
                        ?>
                        
						<td class="data noExl" nowrap="nowrap">
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
                        else if($select_data['on_loan'] != "" && $select_data['on_loan'] != 0) { 
                            if($select_data['advance_loan_no']==1001) { ?>
                            &nbsp;<a href="javascript:alert("Can not Delete") ></a>
                            <?php } else { ?>
                            &nbsp;<a href="javascript:account_transaction_loan_advance('<?php echo $select_data['advance_loan_no'] ?>');"><img src="mos-css/img/delete.png" title="Delete" ></a>
                            <?php } }
                            
                       else{
						 if($select_data['trans_id'] != "" && $select_data['trans_id'] != 0) { ?>
						&nbsp;<a href="javascript:account_transaction(<?php echo $select_data['trans_id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>
						<?php }} ?>
                        <?php
                        //////////////    File delete end    ///////////////  
                         //////////////   Attach File start     ///////////////
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
                        
                        if($select_data['trans_type_name']=="internal_transfer")
                        {  ?>
                        <a href="edit_internal_transfer.php?trans_id=<?php echo $select_data['trans_id']; ?>&id=<?php echo $select_data['payment_id']; ?>&trsns_pname=<?php echo "bank-ledger"; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } 
                        if($select_data['trans_type_name']=="receive_payment")
                        {  ?>
                        <a href="edit_receive_payment.php?trans_id=<?php echo $select_data['trans_id']; ?>&id=<?php echo $select_data['payment_id']; ?>&trsns_pname=<?php echo "bank-ledger"; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } ?>
                 <?php
                        if($select_data['trans_type_name']=="make_payment")
                        {  ?>
                        <a href="edit_make_payment.php?trans_id=<?php echo $select_data['trans_id']; ?>&id=<?php echo $select_data['payment_id']; ?>&trsns_pname=<?php echo "bank-ledger"; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } ?>
                         <?php
                        if($select_data['trans_type_name']=="inst_make_payment")
                        {  ?>
                        <a href="edit-instant-receive-goods.php?trans_id=<?php echo $select_data['trans_id']; ?>&id=<?php echo $select_data['payment_id']; ?>&trsns_pname=<?php echo "bank-ledger-inst-make-payment"; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } ?>
                        <?php
                        if($select_data['trans_type_name']=="inst_receive_payment")
                        {  ?>
                        <a href="edit-instant-sale-invoice.php?trans_id=<?php echo $select_data['trans_id']; ?>&id=<?php echo $select_data['payment_id']; ?>&trsns_pname=<?php echo "bank-ledger-inst-receive-payment"; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } ?>
                 
                 <?php
                        if($select_data['trans_type_name']=="instmulti_receive_payment")
                        {  ?>
                        <a href="edit_instant-sale-invoice_multiple.php?trans_id=<?php echo $select_data['trans_id']; ?>&id=<?php echo $select_data['payment_id']; ?>&trsns_pname=<?php echo "bank-ledger-inst-receive-payment"; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } ?>
                 <?php 
                  if($select_data['trans_type_name']=="loanadvance_makepayment")
                        {  ?>
                        <a href="edit-make-loan-advance.php?id=<?php echo $select_data['payment_id']; ?>&ledger=bank_ledger"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } ?>
                        <?php
                        if($select_data['trans_type_name']=="loanadvance_receivepayment")
                        {  ?>
                         <a href="edit-receive-loan-advance.php?id=<?php echo $select_data['payment_id']; ?>&ledger=bank_ledger"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } ?>
                 <?php
                        if($select_data['trans_type_name']=="tds_receive_payment")
                        {  if($select_data['clear_table_link_id']=="0")
                            {?>
                        <a href="javascript:void(0);"   title="Edit TDS" onClick="return edit_tds_function('<?php echo $select_data['payment_id']; ?>','bank-ledger','<?php echo $select_data['invoice_id']; ?>','<?php echo $select_data['link3_id']; ?>');" ><img src="mos-css/img/edit.png" title="Edit">  </a> 

                        
                <?php  } } ?>
                <?php
                        if($select_data['trans_type_name']=="gst_receive_payment")
                        { 
                            if($select_data['clear_table_link_id']=="0")
                            { ?>
                        <a href="javascript:void(0);"   title="Edit GST" onClick="return edit_gst_function('<?php echo $select_data['payment_id']; ?>','bank-ledger','<?php echo $select_data['invoice_id']; ?>','<?php echo $select_data['link3_id']; ?>');" ><img src="mos-css/img/edit.png" title="Edit">  </a> 

                        
                <?php  } }?>
                <?php
                        if($select_data['trans_type_name']=="instmulti_receive_payment_invoice")
                        {  
                            if($select_data['clear_table_link_id']=="0")
                            {
                            ?>
                        <a href="javascript:void(0);"   title="Edit Invoice" onClick="return edit_invoice_function('<?php echo $select_data['payment_id']; ?>','bank-ledger','<?php echo $select_data['invoice_id']; ?>','<?php echo $select_data['link3_id']; ?>');" ><img src="mos-css/img/edit.png" title="Edit">  </a> 

                        
                <?php       }  } ?>
               
                        <?php
                        $total_rows_view=0;
                         if($select_data['trans_type_name']=="instmulti_receive_payment")
                        {  
                            
                        $query_view="select *  from attach_file where attach_id = '".$select_data['invoice_id']."'";
                        $result_view= mysql_query($query_view) or die('error in query '.mysql_error().$query_view);
                        $total_rows_view = mysql_num_rows($result_view);
                        if($total_rows_view != 0)
                        { ?>
                           <a href="javascript:void(0);" title="View File" onClick="return view_file_function('<?php echo $select_data['invoice_id']; ?>','');" >View</a>     <?php           }
                            
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
                            
                        }else
                        {
                        $query_view="select *  from attach_file where attach_id = '".$select_data['payment_id']."'";
                        $result_view= mysql_query($query_view) or die('error in query '.mysql_error().$query_view);
                        $total_rows_view = mysql_num_rows($result_view);
                        if($total_rows_view != 0)
                        { ?>
                            <a href="javascript:void(0);" title="View File" onClick="return view_file_function('<?php echo $select_data['payment_id']; ?>','');" >View</a>
        <?php           }
                        }
?> 
						</td>
						
					</tr>
				<?php
					$i++;
				}
				  if($search_start=="1")
                {
                    ?>
                    
                    <tr class="data">
                        <td class="data" width="30px"><?php //echo $i; ?></td>
                        <td class="data"><b> <?php echo date("d-m-Y",$from_date); ?></b></td>
                        <td class="data"></td><td class="data"></td>
                        <td class="data"><b><?php echo "Opening Balance"; ?></b></td>
                        <td class="data"><b><?php echo number_format($bal_debit,2,'.',''); ?> </b></td>
                        <td class="data"><b><?php echo number_format($bal_credit,2,'.',''); ?></b></td>
                        <td class="data" <?php if($bal_1<0 ) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?>><b><?php //echo currency_symbol().
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

         <div id="attach_div" style="position:absolute;top:50%; left:40%; width:500px; height:180px; margin:-100px 0 0 -50px;z-index:100; display:none; background-color:#FFFFFF; border:8px solid #999999;" >
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
        
        <form name="advance_loan_no_form" id="advance_loan_no_form" action="" method="post" >
        <input type="hidden" name="advance_loan_no" id="advance_loan_no" value="" >
        <input type="hidden" name="trans_t_name" id="trans_t_name" value="" >
        </form>

        <form name="edit_invoice_form" id="edit_invoice_form" action="edit_invoice_ledger_invoice.php" method="post" >
        <input type="hidden" name="info_tb_id_invoice" id="info_tb_id_invoice" value="" >
        <input type="hidden" name="trsns_pname_invoice" id="trsns_pname_invoice" value="" >
        <input type="hidden" name="invoice_no_invoice" id="invoice_no_invoice" value="" >
        <input type="hidden" name="payment_id_invoice" id="payment_id_invoice" value="" >
        </form>

        <form name="edit_gst_form" id="edit_gst_form" action="edit_invoice_ledger_gst.php" method="post" >
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
        <form name="trans_form_combind" id="trans_form_combind" action="" method="post" >        
        <input type="hidden" name="trans_id_combind" id="trans_id_combind" value="" >
        <input type="hidden" name="payment_id" id="payment_id" value="" >
</form>


       </body></html>










  
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
            filename: "bank_ledger",
            fileext: ".xls",        
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true            
        });  
        // $("#thead").show();
    });
   // $('table td').find('td:eq(4)').show(); 
   
});

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
function account_transaction_invoice(trans_id_1)
{
    if(confirm("Are you sure want to delete?!!!!!......"))
    {
        $("#trans_id_invoice").val(trans_id_1);
        $("#trans_form_invoice").submit();
        return true;
    }
}


function edit_tds_function(info_tb_id,trsns_pname,invoice_no,payment_id)
{
 //edit_tds_form ,  info_tb_id, trsns_pname ,invoice_no , payment_id 
    document.getElementById("info_tb_id_tds").value=info_tb_id;
    document.getElementById("trsns_pname_tds").value=trsns_pname;
    document.getElementById("invoice_no_tds").value=invoice_no;
   // alert(invoice_no);
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

function invoice_attach_file_function(id)
{
    
    document.getElementById("attach_div").style.display="block";
    document.getElementById("attach_file_id").value=id;
    document.getElementById("invoice_flag").value="1";
    
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

function account_transaction_loan_advance(advance_loan_no)
{
    if(confirm("Are you sure want to delete?!!!!!......"))
    { 
        $("#trans_t_name").val("loanadvance_makepayment");
        $("#advance_loan_no").val(advance_loan_no);
        $("#advance_loan_no_form").submit();
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