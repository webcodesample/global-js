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


if(trim($_REQUEST['action_perform']) == "add_payment")
{
	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
	$from_arr = explode(" -",$_REQUEST['from']);
	$to_arr = explode(" -",$_REQUEST['to']);
	
	$from_bank_id = get_field_value("id","bank","bank_account_name",$from_arr[0]);
	$to_bank_id = get_field_value("id","bank","bank_account_name",$to_arr[0]);
	$amount=mysql_real_escape_string(trim($_REQUEST['amount']));
	$description=mysql_real_escape_string(trim($_REQUEST['description']));
	$trans_id = mysql_real_escape_string(trim($_REQUEST['trans_id'])); 
	$trans_type = 7;
    $trans_type_name = "internal_transfer" ;
   //id_first_debit,id_second_credit,trsns_pname
   $id_first_debit = mysql_real_escape_string(trim($_REQUEST['id_first_debit'])); 
   $id_second_credit = mysql_real_escape_string(trim($_REQUEST['id_second_credit'])); 
   $trsns_pname = mysql_real_escape_string(trim($_REQUEST['trsns_pname'])); 
    
	$query="update payment_plan set trans_id = '".$trans_id."', bank_id = '".$from_bank_id."', debit = '".$amount."', description = '".$description."', on_bank = '".$to_bank_id."', payment_date = '".strtotime($_REQUEST['payment_date'])."',update_date = '".getTime()."' where id = '".$id_first_debit."'";
	$result= mysql_query($query) or die('error in query '.mysql_error().$query);
	
	$link_id_1 = $id_first_debit;
	
	$query2="update payment_plan set trans_id = '".$trans_id."', bank_id = '".$to_bank_id."', credit = '".$amount."', description = '".$description."', on_bank = '".$from_bank_id."', payment_date = '".strtotime($_REQUEST['payment_date'])."', update_date = '".getTime()."' where id = '".$id_second_credit."'";
	$result2= mysql_query($query2) or die('error in query '.mysql_error().$query2);
	
	$link_id_2 = $id_second_credit;
	
	
    
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
    
    }
    else
    {
        $new_file_name = "";
    }
    
    
	
	
	
	/*$query3="insert into payment_plan set project_id = '".$project_id."', debit = '".$amount."', description = '".$description."', create_date = '".getTime()."'";
	$result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);*/
	
	
	$msg = "Internal Transfer successfully.";
	$flag = 1;
    $trsns_pname_1 = $_REQUEST['trsns_pname'];
    if($trsns_pname_1=="bank-ledger")
    {
         $msg = "Internal Transfer Update successfully.";
          $flag = 1;
   // header(supplier-payment.php);
      echo "<script> location.href='bank.php'; </script>";
        
    }
    if($trsns_pname_1=="cash-ledger")
    {
        $msg = "Internal Transfer Update successfully.";
         $flag = 1;
   // header(supplier-payment.php);
      echo "<script> location.href='cash.php'; </script>";
    
    }
	
}

if($_REQUEST['trsns_pname']=="bank-ledger")
{
    //trans_id = '".$trans_id."', bank_id = '".$from_bank_id."', debit = '".$amount."', description = '".$description."', on_bank = '".$to_bank_id."', payment_date = '".strtotime($_REQUEST['payment_date'])."',
   // echo "bank ledger update";
    $select_query = "select * from payment_plan where id=".$_REQUEST['id']." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
   // echo "$select_query";
    //$cust_id,bank_id,project_id,amount,description,payment_date,trans_id
    $trsns_pname = "bank-ledger";
   
    $old_trans_id = $select_data['trans_id'];
   
    $old_description = $select_data['description'];
    $old_payment_date = $select_data['payment_date'];
    
    if($select_data['credit']>0)
    {
        $old_amount = $select_data['credit'];    
        $id_first_debit = $select_data['link_id'];
        $id_second_credit = $select_data['id'];
         $old_from_bank = $select_data['on_bank'];
         $old_to_bank = $select_data['bank_id'];
    }
    if($select_data['debit']>0)
    {
        $old_amount = $select_data['debit'];    
        $id_first_debit = $select_data['id'];
        $id_second_credit = $select_data['link_id'];
         $old_from_bank = $select_data['bank_id'];
         $old_to_bank = $select_data['on_bank'];
         
    }
    //$old_amount,$id_first_debit,$id_second_credit,$old_from_bank,$old_to_bank,$old_description,$old_payment_date
     
    
    
}
else 
if($_REQUEST['trsns_pname']=="cash-ledger")
{
    //trans_id = '".$trans_id."', bank_id = '".$from_bank_id."', debit = '".$amount."', description = '".$description."', on_bank = '".$to_bank_id."', payment_date = '".strtotime($_REQUEST['payment_date'])."',
   // echo "bank ledger update";
    $select_query = "select * from payment_plan where id=".$_REQUEST['id']." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
   // echo "$select_query";
    //$cust_id,bank_id,project_id,amount,description,payment_date,trans_id
    $trsns_pname = "cash-ledger";
   
    $old_trans_id = $select_data['trans_id'];
   
    $old_description = $select_data['description'];
    $old_payment_date = $select_data['payment_date'];
    
    if($select_data['credit']>0)
    {
		$old_amount = $select_data['credit'];    
		$id_first_debit = $select_data['link_id'];
		$id_second_credit = $select_data['id'];
		$old_from_bank = $select_data['on_bank'];
		$old_to_bank = $select_data['bank_id'];
    }
    if($select_data['debit']>0)
    {
		$old_amount = $select_data['debit'];    
		$id_first_debit = $select_data['id'];
		$id_second_credit = $select_data['link_id'];
		$old_from_bank = $select_data['bank_id'];
		$old_to_bank = $select_data['on_bank'];
    }
    //$old_amount,$id_first_debit,$id_second_credit,$old_from_bank,$old_to_bank,$old_description,$old_payment_date
     
    
    
}

?>
<!DOCTYPE html>
<?php include_once ("top_header1.php"); ?>   
<script src="js/datetimepicker_css.js"></script>
<body  data-home-page-title="" class="u-body u-xl-mode" data-lang="en">
  <?php include_once ("top_header2.php"); ?> 
  <?php include_once ("top_menu.php"); ?>
  <?php include_once("main_heading_open.php") ?>
  
	<table style="width:100%;" border="0" style="padding:0px 0px; margin-top:-10px;">
    <tr>
        <td style="align:top;" align="left">
        <h4 class="u-text-2 u-text-palette-1-base " style="padding:0px; margin:0px;">
        Update Internal Transfer</h4>
  </td>
        <td width="" style="float:right;">
        <?php 
                    if($trsns_pname=="bank-ledger")
                    {
       ?>     <a href="bank-ledger.php?bank_id=<?= $select_data['bank_id'] ?>" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a> <?php  
     }
    if($trsns_pname=="cash-ledger")
    {
       ?>    <a href="cash.php" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a> <?php  
     }
              ?>
              </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
  <?php include_once("main_body_open.php") ?>
  	
        
	
	
	<div id="adddiv" >
	
	<form name="payment_form" id="payment_form" action="" method="post" enctype="multipart/form-data" >
    <input type="hidden" id="id_first_debit" name="id_first_debit" value="<?php echo $id_first_debit; ?>">
    <input type="hidden" id="id_second_credit" name="id_second_credit" value="<?php echo $id_second_credit; ?>">
    <input type="hidden" id="trsns_pname" name="trsns_pname" value="<?php echo $trsns_pname; ?>">

		<table width="95%" style="padding:30px;">
        <tr>
		<td colspan="2">
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
	</tr><tr>
            <td colspan="2">
            <table width="100%" class="tbl_border" style="">
        
	
			<tr><td valign="top" width="225px">Transaction ID</td>
			<td style="color:#FF0000; font-weight:bold;"><input type="hidden" id="trans_id"  name="trans_id" value="<?php echo $old_trans_id; ?>"/>&nbsp;<?php echo $old_trans_id; ?></td></tr>
			<tr><td width="125px">From</td>
			<td>
            <?php
             $sql_bank_from     = "select bank_account_name,bank_account_number from `bank`  where id=".$old_from_bank." ";
             $query_bank_from     = mysql_query($sql_bank_from);
             $select_bank_from = mysql_fetch_array($query_bank_from);
  
            ?>
            <input type="text" id="from"  name="from" value="<?php echo $select_bank_from['bank_account_name'].' - '.$select_bank_from['bank_account_number']; ?>" style="width:250px;" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td >To</td>
            <?php
             $sql_bank_to     = "select bank_account_name,bank_account_number from `bank`  where id=".$old_to_bank." ";
             $query_bank_to     = mysql_query($sql_bank_to);
             $select_bank_to = mysql_fetch_array($query_bank_to);
  
            ?>
            
			<td><input type="text" id="to"  name="to" value="<?php echo $select_bank_to['bank_account_name'].' - '.$select_bank_to['bank_account_number']; ?>" style="width:250px;" /></td></tr>
			
			
			
			<tr><td align="left" valign="top" >Amount</td>
			<td><input type="text"  name="amount" id="amount" value="<?php echo $old_amount; ?>" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			<tr><td align="left" valign="top" >Date</td>
			<script src="js/datetimepicker_css.js"></script>
			<td><input type="text"  name="payment_date" id="payment_date" value="<?php echo date("d-m-Y",$old_payment_date); ?>" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('payment_date')" style="cursor:pointer"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			<tr><td valign="top" >Description</td>
			<td><textarea name="description" id="description" style="width:250px; height:100px;"><?php echo $old_description; ?></textarea>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			<tr><td valign="top" >Attach File</td>
			<td><input type="file" name="attach_file" id="attach_file" value="" ></td></tr>
			
			<tr><td valign="top" >Attach File Name</td>
			<td><input type="text" id="attach_file_name"  name="attach_file_name" value="" autocomplete="off"/></td></tr>
			
			<tr><td></td><td>
			<input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
			</td></tr>
		</table>
        </td></tr>
		</table>
		<input type="hidden" name="action_perform" id="action_perform" value="" >
		<input type="hidden" name="del_id" id="del_id" value="" >
		</form>
		
		</div>
		
		
		
		
	
		<?php include_once("main_body_close.php") ?>
        <?php include_once ("footer.php"); ?>
        
</body>
</html>
<script>


function validation()
{
	if($("#from").val() == "")
	{
		alert("Please enter from data.");
		$("#from").focus();
		return false;
	}
	else if($("#to").val() == "")
	{
		alert("Please enter to data.");
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
		$("#action_perform").val("add_payment");
		$("#payment_form").submit();
		return true;
	}
	
}

</script>
 <link rel="stylesheet" href="css/jquery-ui.css" />
 <!-- <script src="js/jquery-1.9.1.js"></script>-->
  <script src="js/jquery-ui.js"></script>
	<script>
	$(document).ready(function(){
		$( "#from" ).autocomplete({
			source: "bank-ajax.php"
		});
		$( "#to" ).autocomplete({
			source: "bank-ajax.php"
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
			
			var text = '<table cellpadding="10" cellspacing="0" border="0" width="95%"><tr><td width="125px">Transaction ID</td><td><?php echo $trans_id; ?></td></tr><tr><td width="125px">From</td><td><?php echo $_REQUEST['from']; ?></td></tr><tr><td width="125px">To</td><td><?php echo $_REQUEST['to']; ?></td></tr><tr><td>Amount</td><td>Rs. &nbsp;<?php echo $_REQUEST['amount']; ?></td></tr><tr><td>Date</td><td><?php echo $_REQUEST['payment_date']; ?></td></tr><tr><td >Description</td><td><?php echo $_REQUEST['description']; ?></td></tr></table>';
			printMe=window.open();
			printMe.document.write(text);
			printMe.print();
			printMe.close();
						
			
		}
	</script>
	<?php
}

?>