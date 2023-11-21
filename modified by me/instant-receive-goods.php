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

/*     Create  Account   */


if(trim($_REQUEST['action_perform']) == "add_project")
{
	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
	$from_arr = explode("- ",$_REQUEST['from']);
	$cust_id = $from_arr[1];
	$project_id = get_field_value("id","project","name",$_REQUEST['project']);
    //added by amit
    if($_REQUEST['supplier_invoice_number'])
    {$supplier_invoice_number = $_REQUEST['supplier_invoice_number'];}
    $amount=mysql_real_escape_string(trim($_REQUEST['amount']));
    $amount_gst_amount=mysql_real_escape_string(trim($_REQUEST['amount_gst_amount']));
    $sub_amount = $_REQUEST['amount_sub'];

    //add by amit
    $invoice_receiver_data = explode(" - ", $_REQUEST['invoice_receiver']);
	$invoice_receiver_id = $invoice_receiver_data[1];

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

    $rcvd_amount = $_REQUEST['pay_amount_wogst'];
    $rcvd_gst_amount = $_REQUEST['pay_amount_gst'];
    
    $trans_type = 13;
    $trans_type_name = "inst_receive_goods" ;
   
	$trans_id = mysql_real_escape_string(trim($_REQUEST['trans_id']));
	   //amount_sub,amount_gstper,amount_gst_amount, amount_grand, amount
	
	$query="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."', debit = '".$amount."', gst_amount = '".$amount_gst_amount."', description = '".$description."', on_project = '".$project_id."', payment_date = '".strtotime($_REQUEST['payment_date'])."',subdivision = '".$subdivision."',trans_type = '".$trans_type."', trans_type_name = '".$trans_type_name."', create_date = '".getTime()."', supplier_invoice_number = '".$supplier_invoice_number."', invoice_id = '".$supplier_invoice_number."', invoice_pay_amount = '".$sub_amount."', invoice_issuer_id = '".$invoice_receiver_id."', added_by = '".$_SESSION['userId']."', added_on = '".time()."'";
	$result= mysql_query($query) or die('error in query '.mysql_error().$query);
	
	$link_id_1 = mysql_insert_id();
	
	$query2="insert into payment_plan set trans_id = '".$trans_id."', project_id = '".$project_id."', credit = '".$amount."', gst_amount = '".$amount_gst_amount."', description = '".$description."', on_customer = '".$cust_id."', payment_date = '".strtotime($_REQUEST['payment_date'])."',link_id = '".$link_id_1."',subdivision = '".$subdivision."',trans_type = '".$trans_type."', trans_type_name = '".$trans_type_name."', create_date = '".getTime()."', supplier_invoice_number = '".$supplier_invoice_number."', invoice_id = '".$supplier_invoice_number."', invoice_pay_amount = '".$sub_amount."', invoice_issuer_id = '".$invoice_receiver_id."', added_by = '".$_SESSION['userId']."', added_on = '".time()."'";
	$result2= mysql_query($query2) or die('error in query '.mysql_error().$query2);
	
	$link_id_2 = mysql_insert_id();
	
	$query5="update payment_plan set link_id = '".$link_id_2."' where id = '".$link_id_1."'";
	$result5= mysql_query($query5) or die('error in query '.mysql_error().$query5);
    
    /*       payment query start           */
    //,
    $payment_flag=$_REQUEST['payment_flag'];
     $trans_type_pay = 11;
    $trans_type_name_pay= "inst_make_payment" ;
   
    $trans_id = mysql_real_escape_string(trim($_REQUEST['trans_id']));
    if($payment_flag==1)
    {
    $query_pay ="insert into payment_plan set trans_id = '".$trans_id."', bank_id = '".$pay_bank_id."', debit = '".$pay_amount."', description = '".$description."', on_customer = '".$cust_id."', on_project = '".$project_id."', payment_date = '".strtotime($_REQUEST['pay_payment_date'])."', payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."',link2_id = '".$link_id_1."',link3_id = '".$link_id_2."', trans_type = '".$trans_type_pay."', trans_type_name = '".$trans_type_name_pay."',create_date = '".getTime()."', supplier_invoice_number = '".$supplier_invoice_number."', invoice_id = '".$supplier_invoice_number."', invoice_issuer_id = '".$invoice_receiver_id."'";
    $result_pay= mysql_query($query_pay) or die('error in query '.mysql_error().$query_pay);
    
    $link_id_1_pay = mysql_insert_id();
    
    $query2_pay="insert into payment_plan set trans_id = '".$trans_id."',payment_flag = '".$payment_flag."', cust_id = '".$cust_id."', credit = '".$pay_amount."', description = '".$description."', on_project = '".$project_id."', on_bank = '".$pay_bank_id."', payment_date = '".strtotime($_REQUEST['pay_payment_date'])."' ,payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."',link2_id = '".$link_id_1."',link3_id = '".$link_id_2."',link_id = '".$link_id_1_pay."',trans_type = '".$trans_type_pay."', trans_type_name = '".$trans_type_name_pay."', create_date = '".getTime()."', supplier_invoice_number = '".$supplier_invoice_number."', invoice_id = '".$supplier_invoice_number."', invoice_issuer_id = '".$invoice_receiver_id."'";
    $result2_pay= mysql_query($query2_pay) or die('error in query '.mysql_error().$query2_pay);
    
    $link_id_2_pay = mysql_insert_id();
    $query5_pay="update payment_plan set payment_flag = '".$payment_flag."', link_id = '".$link_id_2_pay."' where id = '".$link_id_1_pay."'";
    $result5_pay= mysql_query($query5_pay) or die('error in query '.mysql_error().$query5_pay);
    
    $query5="update payment_plan set link2_id = '".$link_id_1_pay."',link3_id = '".$link_id_2_pay."' where id = '".$link_id_2."'";
    $result5= mysql_query($query5) or die('error in query '.mysql_error().$query5);
    
    $query5="update payment_plan set payment_flag = '".$payment_flag."', link2_id = '".$link_id_1_pay."',link3_id = '".$link_id_2_pay."' where id = '".$link_id_1."'";
    $result5= mysql_query($query5) or die('error in query '.mysql_error().$query5);
    $query5="update payment_plan set payment_flag = '".$payment_flag."' where id = '".$link_id_2."'";
    $result5= mysql_query($query5) or die('error in query '.mysql_error().$query5);

    // code by amit
    //print_r($_REQUEST);
    //echo number_format((float)$rcvd_amount, 2, '.', '');
    //echo "<br>";
    //echo number_format((float)$rcvd_gst_amount, 2, '.', ''); 
    $pp_linkid_1_invoice=$link_id_2_pay;

    //gst amount calculation -- By amit
    $gst_due_des = "GST Paid for invoice : ".$supplier_invoice_number;
    $query3="insert into gst_due_info set invoice_id = '".$supplier_invoice_number."',payment_plan_id = '".$link_id_1."',trans_id = '".$trans_id."',	due_date = '".strtotime($_REQUEST['pay_payment_date'])."',description = '".$gst_due_des."',received_amount = '".$rcvd_gst_amount."', amount = '".$amount_gst_amount."',create_date = '".getTime()."'";
    $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
    $link_id_4_gst = mysql_insert_id();


    //invoice amount calculation -- by amit

    

    $query3_inv="insert into invoice_due_info set invoice_id = '".$supplier_invoice_number."',payment_plan_id = '".$link_id_1."',trans_id = '".$trans_id."', pp_linkid_1 = '".$pp_linkid_1_invoice."',pp_linkid_2 = '".$pp_linkid_2_invoice."',	due_date = '".strtotime($_REQUEST['pay_payment_date'])."',description = '(Invoice Amount Received ): ".$description."',received_amount = '".$rcvd_amount."', amount = '".$_REQUEST['amount_sub']."',create_date = '".getTime()."'";
    
    $result3_inv= mysql_query($query3_inv) or die('error in query '.mysql_error().$query3_inv);
    $link_id_4_invoice = mysql_insert_id();
    

    $query5_pay_invoice="update payment_plan set invoice_flag = '1',invoice_pay_id='".$pp_linkid_1_invoice."',invoice_due_pay_id='".$link_id_4_invoice."' where id = '".$link_id_2_pay."'";
    $result5_pay_invoice= mysql_query($query5_pay_invoice) or die('error in query '.mysql_error().$query5_pay_invoice);

    $query5_pay_invoice="update payment_plan set invoice_flag = '1',invoice_pay_id='".$pp_linkid_1_invoice."',invoice_due_pay_id='".$link_id_4_invoice."' where id = '".$link_id_2_pay."'";
    $result5_pay_invoice= mysql_query($query5_pay_invoice) or die('error in query '.mysql_error().$query5_pay_invoice);
    
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
    
    
    
	$msg = "Instant Receive Goods and Payment successfully.";
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

?>

<!DOCTYPE html>
<?php include_once ("top_header1.php"); ?>   
<script src="js/datetimepicker_css.js"></script>
      <!-- //amount_sub,amount_gstper,amount_gst_amount, amount_grand, amount -->

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
        Instant Purchases :</h4>
  </td>
        <td width="" style="float:right;">
        <!--    <a href="bank.php" title="Back" style=""><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a> -->
            </td>
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
    <link rel="stylesheet" href="css/jquery-ui.css" />
 <!-- <script src="js/jquery-1.9.1.js"></script> -->
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
            <td style="color:#FF0000; font-weight:bold;"><input type="hidden" id="trans_id"  name="trans_id" value="<?php echo $trans_id; ?>"/>&nbsp;<?php echo $trans_id; ?></td></tr>
            
            <tr>
            <td >Supplier Invoice No.</td>
            <td>
            <input type="text" id="supplier_invoice_number"  name="supplier_invoice_number" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span>
            </td>
            </tr>
            
            <tr><td width="125px">Supplier Name</td>
            <td><input type="text" id="from"  name="from" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>

			<tr><td width="125px">Invoice Receiver</td>
            <td><input type="text" id="invoice_receiver"  name="invoice_receiver" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <tr><td >Project</td>
            <td><input type="text" id="project"  name="project" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <tr><td >Sub Division Name</td>
            <td>
        
             <input type="text" id="subdivision"  name="subdivision" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span>
            
            </td></tr>

            <tr><td align="left" valign="top" >
            Sub Total</td>
            <td><input type="text"  name="amount_sub" id="amount_sub" value=""  />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>

            <tr><td align="left" valign="top" >GST (%)</td>
            <td><input type="text" onblur="findTotal()"  name="amount_gstper" id="amount_gstper" value="" onblur="findTotal()"  />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <tr><td align="left" valign="top" >GST Amount</td>
            <td><input type="text"  name="amount_gst_amount" id="amount_gst_amount" value="" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <tr><td align="left" valign="top" >Grand Total</td>
            <td><input type="hidden"  name="amount_grand" id="amount_grand" value="" />
            <input type="text"  name="amount" id="amount" value="" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr>
            <td align="left" valign="top" >Date</td>
            <td>
            <input type="date" name="payment_date" id="payment_date" max="<?= date('Y-m-d',time()) ?>">
            </td>
            </tr>
            <tr><td valign="top" >Description</td>
            <td><textarea name="description" id="description" style="width:250px; height:100px;"></textarea>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
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
                
                <table width="95%">
                <tr>
                <td>
                <h4 class="u-text-2 u-text-palette-1-base1 " style="padding:0px; margin:0px;">
                Instant Payment Details
                </h4>
                </td>
                </tr>

                <tr>
                <td>
                <input type="checkbox" name="pay_flag" id="pay_flag" onchange=" return checkpay_flag();">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Payment</b>
                </td>
                </tr>
            <tr>
            <td style="color:#FF0000; font-weight:bold;">&nbsp;</td></tr>
            <tr><td>
            <div id="pay_flag_div"   style="display:none; " >
            <table width="100%">
            <tr><td width="125px">Supplier Invoice Number</td>
            <td><input type="text" id="sin_reference" value="" style="width:250px;" readonly/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  ></span></td></tr>

            <tr><td width="125px">Paid From</td>
            <td><input type="text" id="pay_form"  name="pay_form" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <tr><td align="left" valign="top" >Payment Date</td>
        
            <td><input type="date"  name="pay_payment_date" id="pay_payment_date" max="<?= date('Y-m-d',time()) ?>"></td></tr>
            
            <tr><td >Payment Method</td>
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

            <tr>
            <td align="left" valign="top" >Net Amount Paid</td>
            <td><input type="text" name="pay_amount_wogst" id="pay_amount_wogst" value="" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td>
            </tr>

            <tr>
            <td align="left" valign="top" >GST Paid</td>
            <td><input type="text"  name="pay_amount_gst" id="pay_amount_gst" onblur="setgstpaid()" value=""/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td>
            </tr>

            <tr>
            <td align="left" valign="top" >Total Amount Paid</td>
            <td><input type="text"  name="pay_amount" id="pay_amount" value="" readonly/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td>
            </tr>

            <input type="hidden" name="payment_flag" id="payment_flag" value="0">
        </table></div>
    </td></tr></table>
            </td>
        </tr>
    </table>
				<input type="hidden" name="action_perform" id="action_perform" value="" >
		
		</form>
		
		</div>
		
        </td></tr></table>
            </td>
        </tr>
    </table>
		
		

        <?php include_once("main_body_close.php") ?>
        <?php include_once ("footer.php"); ?>
        

</body>
</html>

<script>

function setgstpaid()
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
function checkpay_flag()
{
   // alert("hello");
    //pay_form,pay_payment_date,pay_method,pay_checkno,pay_amount
    if($("#payment_flag").val() == "0")
    {
        document.getElementById('payment_flag').value="1";
        document.getElementById('pay_flag_div').style.display='block';
        document.getElementById('sin_reference').value = document.getElementById('supplier_invoice_number').value;
        
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
