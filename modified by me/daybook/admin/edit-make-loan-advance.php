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


if(trim($_REQUEST['action_perform']) == "add_payment")
{
    //$bank_entry_id,$loan_entry_id,back_ladger
	 $main_id =  mysql_real_escape_string(trim($_REQUEST['main_id'])); 
     $bank_entry_id =  mysql_real_escape_string(trim($_REQUEST['bank_entry_id'])); 
     $loan_entry_id =  mysql_real_escape_string(trim($_REQUEST['loan_entry_id'])); 
     $back_ladger =  mysql_real_escape_string(trim($_REQUEST['back_ladger'])); 
     
    //$loan_id_new,from,to,pay_method,pay_checkno,amount,payment_date,description
    $loan_id_new = mysql_real_escape_string(trim($_REQUEST['loan_id_new'])); 
	$from_arr = explode(" -",$_REQUEST['from']);
    $bank_id = get_field_value("id","bank","bank_account_name",$from_arr[0]);
    $client_id = get_field_value("id","loan_advance","name",$_REQUEST['to']);
	$amount=mysql_real_escape_string(trim($_REQUEST['amount']));
	$description=mysql_real_escape_string(trim($_REQUEST['description']));
	 $pay_method=mysql_real_escape_string(trim($_REQUEST['pay_method']));
    $pay_checkno=mysql_real_escape_string(trim($_REQUEST['pay_checkno']));
	$payment_date = strtotime($_REQUEST['payment_date']);
    $interest=mysql_real_escape_string(trim($_REQUEST['interest']));
    
   
    
	/*
	if($_FILES["attach_file"]["name"] != "")
	{
		$attach_file_name=mysql_real_escape_string(trim($_REQUEST['attach_file_name']));
		$temp = explode(".", $_FILES["attach_file"]["name"]);
		$arr_size = count($temp);
		$extension = end($temp);
		$new_file_name = $attach_file_name.'_'.date("d_M_Y").'.'.$extension;
		move_uploaded_file($_FILES["attach_file"]["tmp_name"],"transaction_files/" . $new_file_name);
	}
	else
	{
		$new_file_name = "";
	}*/
    //$trans_type,$trans_type_name
	//trans_type = '".$trans_type."', trans_type_name = '".$trans_type_name."',
	//$query="update loan_advance_payment set bank_id = '".$bank_id."',client_id = '".$client_id."',  debit = '".$amount."', description = '".$description."', payment_date = '".$payment_date."',payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."', interest_per = '".$interest."',update_date = '".getTime()."' where id='".$main_id."'";
	
	
    
     $query="update payment_plan set advance_loan_no = '".$loan_id_new."',on_loan = '".$client_id."', bank_id = '".$bank_id."', debit = '".$amount."', description = '".$description."',  payment_date = '".$payment_date."', payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."', interest_per = '".$interest."',update_date = '".getTime()."' where id='".$bank_entry_id."'";
    $result= mysql_query($query) or die('error in query '.mysql_error().$query);
    
    $link_id_1 = $bank_entry_id;
    
    $query2="update payment_plan set advance_loan_no = '".$loan_id_new."',loan_id = '".$client_id."', on_bank = '".$bank_id."',  credit = '".$amount."', description = '".$description."', payment_date = '".$payment_date."',payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."', interest_per = '".$interest."', update_date = '".getTime()."' where id='".$loan_entry_id."'";
    $result2= mysql_query($query2) or die('error in query '.mysql_error().$query2);
    
    $link_id_2 = $loan_entry_id;
   

	 
    if($_FILES["attach_file"]["name"] != "")
    {
        $query3="insert into attach_file set attach_id = '".$link_id_1."', link_id = '".$link_id_2."',file_name = '".$new_file_name."'";
    $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
    $link_id_4 = mysql_insert_id();
    
        $attach_file_name=mysql_real_escape_string(trim($_REQUEST['attach_file_name']));
        $temp = explode(".", $_FILES["attach_file"]["name"]);
        $arr_size = count($temp);
        $extension = end($temp);
        //$new_file_name = $attach_file_name.'_'.date("d_M_Y").'.'.$extension;
         $new_file_name = "loan_advance_".$attach_file_name.'_'.$link_id_4.'_'.date("d_M_Y").'.'.$extension;
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
   
	
	$msg = "update Loan And Advance Make Payment successfully.";
	$flag = 1;
    if($back_ladger=="loan_advance")
    {
     echo "<script>  location.href='loan_advance_ledger.php?loan_id=".$client_id."'; </script>";
    }
    if($back_ladger=="bank_ledger")
    { 
     echo "<script>  location.href='bank-ledger.php?bank_id=".$bank_id."'; </script>";
    }
	//loan_advance_ledger.php?loan_advance=<?php echo $select_data['client_id']; 
}
  $select_query = "select * from payment_plan where id=".$_REQUEST['id']." ";
    $select_result = mysql_query($select_query) or die('error in query select loan_advance_payment query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
    if($_REQUEST['ledger']=="loan_advance")
    {
        $back_ladger=$_REQUEST['ledger'];
        $bank_id_old=$select_data['on_bank'];
        $loan_id_old=$select_data['loan_id'];
        $amount_old=$select_data['credit'];
        $bank_entry_id=$select_data['link_id'];
        $loan_entry_id=$select_data['id'];
        
    }
    if($_REQUEST['ledger']=="bank_ledger")
    {
        $back_ladger=$_REQUEST['ledger'];
        $bank_id_old=$select_data['bank_id'];
        $loan_id_old=$select_data['on_loan'];
        $amount_old=$select_data['debit'];
        $bank_entry_id=$select_data['id'];
        $loan_entry_id=$select_data['link_id'];
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
        Update Make Loan And Advances payment </h4>
  </td>
        <td width="" style="float:right;">
        <?php 
     if($back_ladger=="loan_advance")
    {
        ?><a href="loan_advance_ledger.php?loan_id=<?php echo $loan_id_old; ?>" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
        <?php 
    /*   echo "<script>  location.href='loan_advance_ledger.php?loan_id=".$client_id."'; </script>";   */
    }
    if($back_ladger=="bank_ledger")
    {
        ?><a href="bank-ledger.php?bank_id=<?php echo $bank_id_old; ?>" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
        <?php 
    /*echo "<script>  location.href='bank-ledger.php?bank_id=".$bank_id."'; </script>";*/
    }
    
     ?>
        </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
  <?php include_once("main_body_open.php") ?>
  <table width="100%" style="padding:10px;" >
  <tr><td valign="top">
  <table width="100%" class="tbl_border" >
    <tr><td valign="top">

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
	
    
   <!-- <a href="loan_advance_ledger.php?loan_id=<?php echo $select_data['loan_id']; ?>" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>-->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
	<form name="payment_form" id="payment_form" action="" method="post" enctype="multipart/form-data" >
		<table width="95%">

        <tr>
        
            <td valign="top">
                <table width="100%">
                <tr><td width="125px">Loan & Advance ID</td>
			<td style="color:#FF0000; font-weight:bold;"><input type="hidden"  id="loan_id_new"  name="loan_id_new" value="<?php echo $select_data['advance_loan_no']; ?>"/>&nbsp;<?php echo $select_data['advance_loan_no']; ?></td></tr>
			<tr><td width="125px">From</td>
			<td>
            <?php
             $sql_bank     = "select bank_account_name,bank_account_number from `bank`  where id=".$bank_id_old." ";
             $query_bank     = mysql_query($sql_bank);
             $select_bank = mysql_fetch_array($query_bank);
  
            ?>
            <input type="text" id="from"  name="from" value="<?php echo $select_bank['bank_account_name'].' - '.$select_bank['bank_account_number']; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			<tr><td align="left" valign="top" >Amount</td>
            <td><input type="text"  name="amount" id="amount" style="width:250px;" value="<?php echo $amount_old;  ?>" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td >Payment Mothod</td>
            <td><br>
             <input type="radio" id="pay_method" name="pay_method" <?php if($select_data['payment_method']=='check'){ echo "checked='checked'"; } ?>   onchange=" return checkno_create();" value="check">
            <label for="male">Cheque</label>&nbsp;&nbsp;
            <input type="radio" id="pay_method" name="pay_method" <?php if($select_data['payment_method']=='bank'){ echo "checked='checked'"; } ?>  onchange="return checkno_create1();" value="bank">
            <label for="female">Bank</label>&nbsp;&nbsp;
            <input type="radio" id="pay_method" name="pay_method" <?php if($select_data['payment_method']=='cash'){ echo "checked='checked'"; } ?>  onchange="return checkno_create1();" value="cash">
            <label for="other">Cash</label>&nbsp;&nbsp;
            </td></tr>
            <tr>
                
                <td colspan="2">
                    <div id="pay_check" align="left"  <?php if($select_data['payment_method']=='check'){ echo "style='display:block;'"; }else{ echo "style='display:none;'"; } ?> >
                    <table>
                        <tr>
                            <td width="120px">Cheque No.</td>
                            <td><input type="text"  name="pay_checkno" style="width:250px;" id="pay_checkno" value="<?php echo $select_data['payment_checkno']; ?>" /><br></td>
                        </tr>
                    </table>
                     
                    </div>
                </td>
            </tr>
            <tr><td valign="top" >Attach File</td>
			<td><input type="file" name="attach_file" id="attach_file" style="width:250px;" value="" ></td></tr>
			
			<tr><td valign="top" >Attach File Name</td>
			<td><input type="text" id="attach_file_name" style="width:250px;" name="attach_file_name" value="" autocomplete="off"/></td></tr>
			
            </table>
            </td>
            <td valign="top">
                <table width="100%">
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><td >To</td>
			<td>
            <?php
             $sql_client     = "select * from `loan_advance`  where id=".$loan_id_old." ";
             $query_client     = mysql_query($sql_client);
             $select_client = mysql_fetch_array($query_client);
  
            ?>
            <input type="text" id="to"  name="to" value="<?php echo $select_client['name']; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td align="left" valign="top" >Date</td>
            <script src="js/datetimepicker_css.js"></script>
            <td><input type="text"  name="payment_date" id="payment_date" style="width:250px;" value="<?php echo date("d-m-Y",$select_data['payment_date']);  ?>" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('payment_date')" style="cursor:pointer"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td valign="top" >Description</td>
            <td><textarea name="description" id="description" style="width:250px; height:100px;"><?php echo $select_data['description'];  ?></textarea>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
             
            
            </table>
            </td>

        </tr>
			
			<!--
            <tr><td align="left" valign="top" >Interest (%)</td>
            <td><input type="text"  name="interest" id="interest" value="<?php echo $select_data['interest_per'];  ?>" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  ></span></td></tr> -->
            
			<tr><td></td><td>
			<input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
			</td></tr></table>
		<input type="hidden" name="action_perform" id="action_perform" value="" >
        <input type="hidden" name="main_id" id="main_id" value="<?php echo $select_data['id']; ?>" >
        <input type="hidden" name="bank_entry_id" id="bank_entry_id" value="<?php echo $bank_entry_id; ?>" >
        <input type="hidden" name="loan_entry_id" id="loan_entry_id" value="<?php echo $loan_entry_id; ?>" >
        <input type="hidden" name="back_ladger" id="back_ladger" value="<?php echo $back_ladger; ?>" >
        
		<input type="hidden" name="del_id" id="del_id" value="" >
		</form>
		
		</div>
        </td></tr></table>	</td></tr></table>
        <?php include_once("main_body_close.php") ?>
        <?php include_once ("footer.php"); ?>
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
    else if($("#description").val() == "")
    {
        alert("Please enter description.");
        $("#description").focus();
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
  <!--<script src="js/jquery-1.9.1.js"></script>-->
  <script src="js/jquery-ui.js"></script>
	<script>
	$(document).ready(function(){
		$( "#from" ).autocomplete({
			source: "bank-ajax.php"
		});
		$( "#to" ).autocomplete({
			source: "loanadvance-ajax.php"
		});
		
	})
	</script>
	
	<?php 

if($flag == 1)
{
	?>
	<script>
	/*if(confirm("Do you want to print?!!!!!....."))
		{
			
			var text = '<table cellpadding="10" cellspacing="0" border="0" width="95%"><tr><td colspan="2" >Receive Goods</td></tr><tr><td width="125px">Transaction ID</td><td><?php echo $trans_id; ?></td></tr><tr><td width="125px">From</td><td><?php echo $_REQUEST['from']; ?></td></tr><tr><td width="125px">To</td><td><?php echo $_REQUEST['to']; ?></td></tr><tr><td >Project</td><td><?php echo $_REQUEST['project']; ?></td></tr><tr><td>Amount</td><td>Rs. &nbsp;<?php echo $_REQUEST['amount']; ?></td></tr><tr><td>Date</td><td><?php echo $_REQUEST['payment_date']; ?></td></tr><tr><td >Description</td><td><?php echo $_REQUEST['description']; ?></td></tr></table>';
			printMe=window.open();
			printMe.document.write(text);
			printMe.print();
			printMe.close();
						
			
		}*/
	</script>
	<?php
}

?>