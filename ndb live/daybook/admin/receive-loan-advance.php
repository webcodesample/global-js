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
	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
	$from_arr = explode("- ",$_REQUEST['from']);
	$to_arr = explode(" -",$_REQUEST['to']);
	$cust_id = $from_arr[1];
	$bank_id = get_field_value("id","bank","bank_account_name",$to_arr[0]);
    
	 $loan_id_new = mysql_real_escape_string(trim($_REQUEST['loan_id_new'])); 
    $to_arr = explode(" -",$_REQUEST['to']);
    $bank_id = get_field_value("id","bank","bank_account_name",$to_arr[0]);
    $client_id = get_field_value("id","loan_advance","name",$_REQUEST['from']);
    $amount=mysql_real_escape_string(trim($_REQUEST['amount']));
    $description=mysql_real_escape_string(trim($_REQUEST['description']));
     $pay_method=mysql_real_escape_string(trim($_REQUEST['pay_method']));
    $pay_checkno=mysql_real_escape_string(trim($_REQUEST['pay_checkno']));
    $payment_date = strtotime($_REQUEST['payment_date']);
    $interest=mysql_real_escape_string(trim($_REQUEST['interest']));
    
    $trans_type =6;
    $trans_type_name = "loanadvance_receivepayment" ;
    
	 //$trans_type,$trans_type_name
    //trans_type = '".$trans_type."', trans_type_name = '".$trans_type_name."',
    //$query="insert into loan_advance_payment set loan_id = '".$loan_id_new."', bank_id = '".$bank_id."',client_id = '".$client_id."',  credit = '".$amount."', description = '".$description."', payment_date = '".$payment_date."',payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."', interest_per = '".$interest."', trans_type = '".$trans_type."', trans_type_name = '".$trans_type_name."',create_date = '".getTime()."'";
    
	
	 $query="insert into payment_plan set advance_loan_no = '".$loan_id_new."',on_loan = '".$client_id."', bank_id = '".$bank_id."', credit = '".$amount."', description = '".$description."',  payment_date = '".$payment_date."', payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."', interest_per = '".$interest."',trans_type = '".$trans_type."', trans_type_name = '".$trans_type_name."',create_date = '".getTime()."'";
    $result= mysql_query($query) or die('error in query '.mysql_error().$query);
    
    $link_id_1 = mysql_insert_id();
    
    $query2="insert into payment_plan set advance_loan_no = '".$loan_id_new."',loan_id = '".$client_id."', on_bank = '".$bank_id."',  debit = '".$amount."', description = '".$description."', payment_date = '".$payment_date."',payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."', interest_per = '".$interest."',link_id = '".$link_id_1."',trans_type = '".$trans_type."', trans_type_name = '".$trans_type_name."', create_date = '".getTime()."'";
    $result2= mysql_query($query2) or die('error in query '.mysql_error().$query2);
    
    $link_id_2 = mysql_insert_id();
    
    $query5="update payment_plan set link_id = '".$link_id_2."' where id = '".$link_id_1."'";
    $result5= mysql_query($query5) or die('error in query '.mysql_error().$query5);
	
    
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
    
	
	
	
	
	$msg = "Receive Payment successfully.";
	$flag = 1;
	
}
 $qry_max=" select max(loan_id) as max_loan_id from loan_advance_payment
";
       $qry_max_result = mysql_query($qry_max);
       
       $qry_max_row = mysql_fetch_array($qry_max_result);
       if($qry_max_row[max_loan_id]<1)
       {
            $loan_id_new="1001";       
       }else{
       $loan_id_new =$qry_max_row[max_loan_id]+1;    
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
        Receive Loan And Advances Payment </h4>
  </td>
        <td width="" style="float:right;">
         </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
  <?php include_once("main_body_open.php") ?>
		
  <table width="100%" style="padding:10px;" >
  <tr><td>
  <table width="100%" class="tbl_border" >
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
	
	<form name="payment_form" id="payment_form" action="" method="post" enctype="multipart/form-data" >
		<table width="95%">
        <tr>
            
            <td valign="top">
                <table width="100%">
                <tr><td width="135px">Loan And Advance ID</td>
			<td style="color:#FF0000; font-weight:bold;"><input type="hidden" id="loan_id_new"  name="loan_id_new" value="<?php echo $loan_id_new; ?>"/>&nbsp;<?php echo $loan_id_new; ?></td></tr>
			<tr><td width="125px">From</td>
			<td><input type="text" id="from"  name="from" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
		    
            <tr><td align="left" valign="top" >Amount</td>
            <td><input type="text"  name="amount" id="amount" style="width:250px;" value="" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
       	
			
			 <tr><td >Payment Mothod</td>
            <td>
            <input type="radio" id="pay_method" name="pay_method"  onchange=" return checkno_create();" value="check">
            <label for="male">Cheque</label>&nbsp;&nbsp;
            <input type="radio" id="pay_method" name="pay_method"  onchange="return checkno_create1();" value="bank">
            <label for="female">Bank</label>&nbsp;&nbsp;
            <input type="radio" id="pay_method" name="pay_method"  onchange="return checkno_create1();" value="cash">
            <label for="other">Cash</label>&nbsp;&nbsp;
            </td></tr>
            <tr>
                
                <td colspan="2">
                    <div id="pay_check" align="left"  style="display:none; " >
                    <table>
                        <tr>
                            <td width="120px">Cheque No.</td>
                            <td><input type="text" style="width:250px;"  name="pay_checkno" id="pay_checkno" value="" /><br></td>
                        </tr>
                    </table>
                     <br>
                    </div>
                </td>
            </tr>
            
           <tr><td valign="top" >Attach File</td>
			<td><input type="file" name="attach_file" style="width:250px;" id="attach_file" value="" ></td></tr>
			
			<tr><td valign="top" >Attach File Name</td>
			<td><input type="text" id="attach_file_name" style="width:250px;"  name="attach_file_name" value="" autocomplete="off"/></td></tr>
			
                </table>
            </td>
            <td valign="top">
                <table width="100%">
            <tr><td colspan="2">&nbsp;</td></tr>
                <tr><td >To</td>
			<td><input type="text" id="to"  name="to" style="width:250px;" value="" style="width:250px;"/></td></tr>
		    <tr><td align="left" valign="top" >Date</td>
            <script src="js/datetimepicker_css.js"></script>
            <td><input type="text"  name="payment_date" style="width:250px;" id="payment_date" value="<?php echo $_REQUEST['payment_date']; ?>" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('payment_date')" style="cursor:pointer"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td valign="top" >Description</td>
            <td><textarea name="description" id="description" style="width:250px; height:100px;"></textarea>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			 
                </table>
            </td>
        </tr>
		
			<tr><td></td><td>
			<input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
			</td></tr>
		</table>
		<input type="hidden" name="action_perform" id="action_perform" value="" >
		<input type="hidden" name="del_id" id="del_id" value="" >
		</form>
		
		</div>
		</td></tr></table></td></tr></table>
		
		
		
		
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
			source: "loanadvance-ajax.php"
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
	/*if(confirm("Do you want to print?!!!!!....."))
		{
			
			var text = '<table cellpadding="10" cellspacing="0" border="0" width="95%"><tr><td colspan="2" >Receive Goods</td></tr><tr><td width="125px">Transaction ID</td><td><?php echo $_REQUEST['trans_id']; ?></td></tr><tr><td width="125px">From</td><td><?php echo $_REQUEST['from']; ?></td></tr><tr><td width="125px">To</td><td><?php echo $_REQUEST['to']; ?></td></tr><tr><td >Project</td><td><?php echo $_REQUEST['project']; ?></td></tr><tr><td>Amount</td><td>Rs. &nbsp;<?php echo $_REQUEST['amount']; ?></td></tr><tr><td>Date</td><td><?php echo $_REQUEST['payment_date']; ?></td></tr><tr><td >Description</td><td><?php echo $_REQUEST['description']; ?></td></tr></table>';
			printMe=window.open();
			printMe.document.write(text);
			printMe.print();
			printMe.close();
						
			
		}*/
	</script>
		
<br>
	<?php
}

?>
