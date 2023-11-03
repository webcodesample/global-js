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


/*     Create  Account   */


if(trim($_REQUEST['action_perform']) == "edit_cash")
{
	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
	   
        $loan_advance=mysql_real_escape_string(trim($_REQUEST['loan_advance']));
 $client_type=mysql_real_escape_string(trim($_REQUEST['client_type']));
    $other_name=mysql_real_escape_string(trim($_REQUEST['other_name']));
    $customer_name=mysql_real_escape_string(trim($_REQUEST['customer_name']));
    $supplier_name=mysql_real_escape_string(trim($_REQUEST['supplier_name']));
    $mobile=mysql_real_escape_string(trim($_REQUEST['mobile']));
    $email=mysql_real_escape_string(trim($_REQUEST['email']));
    $current_address=mysql_real_escape_string(trim($_REQUEST['current_address']));
    $company_name=mysql_real_escape_string(trim($_REQUEST['company_name']));
    $another_contact_name=mysql_real_escape_string(trim($_REQUEST['another_contact_name']));
    $another_mobile=mysql_real_escape_string(trim($_REQUEST['another_mobile']));
     $opening_balance=mysql_real_escape_string(trim($_REQUEST['opening_balance']));
    $opening_balance = str_replace(",","",$opening_balance);
    $opening_balance_date=strtotime(mysql_real_escape_string(trim($_REQUEST['opening_balance_date'])));
   
    //$=mysql_real_escape_string(trim($_REQUEST['']));
    if($client_type=="other"){
        $full_name=$other_name;
        
    }
    else if($client_type=="customer"){
        $type_id = $customer_name;
         $full_name = get_field_value("full_name","customer","cust_id",$customer_name);
    }
    else if($client_type=="supplier"){
        $type_id = $supplier_name;
         $full_name = get_field_value("full_name","customer","cust_id",$supplier_name);
    } 
    $quuerrr="select id from loan_advance where name = '".$full_name."' and id !='".$loan_advance."'";
    
    $sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
    $no=mysql_num_rows($sql);
    if($no > 0)
    {
        
        
        $error_msg = "loan_advance name already exist try another";
        
    }
    else
    {
       
         $query="update loan_advance set  name = '".$full_name."', type = '".$client_type."', type_id = '".$type_id."', mobile = '".$mobile."', email = '".$email."', current_address = '".$current_address."', opening_balance = '".$opening_balance."',company_name = '".$company_name."', another_contact_name = '".$another_contact_name."', another_mobile = '".$another_mobile."', opening_balance_date = '".$opening_balance_date."',update_date = '".getTime()."' where id='".$loan_advance."'";
        $result= mysql_query($query) or die('error in query '.mysql_error().$query);
        
        
    
    $ss1="select * from payment_plan  where loan_id = '".$loan_advance."' and description = 'Opening Balance'";
            $sr1=mysql_query($ss1);
            $tot_rw1=mysql_num_rows($sr1);
            if($tot_rw1 == 0)
            {
                $query2="insert into payment_plan set loan_id = '".$loan_advance."', credit = '".$opening_balance."', description = 'Opening Balance', payment_date = '".$opening_balance_date."', create_date = '".getTime()."'";
        $result2= mysql_query($query2) or die('error in query '.mysql_error().$query2);
                                                   
            } else
            {   
        /* $query2="update payment_plan set credit = '".$opening_balance."',payment_date = '".$opening_balance_date."' where cust_id = '".$_REQUEST['cust_id']."' and description = 'Opening Balance'";
    $result2= mysql_query($query2) or die('error in query Cash query '.mysql_error().$query2);*/
    $query2="update payment_plan set credit = '".$opening_balance."',payment_date = '".$opening_balance_date."' where loan_id = '".$loan_advance."' and description = 'Opening Balance'";
    $result2= mysql_query($query2) or die('error in query Cash query '.mysql_error().$query2);
    
            }
    
        
        $msg = "Loan And Advance Update successfully.";
        header("Location:loan_advance.php?msg= Update successfully");
    }

	
	

	
	
	
	
	
	
	
}



$select_query = "select * from loan_advance where id = '".$_REQUEST['loan_advance']."'";
$select_result = mysql_query($select_query) or die('error in query select cash query '.mysql_error().$select_query);
$select_data = mysql_fetch_array($select_result)
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
        Edit Loan & Advances</h4>
  </td>
        <td width="" style="float:right;">
        <a href="loan_advance.php" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
				 </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
  <?php include_once("main_body_open.php") ?>
  <table width="100%" style="padding:10px;" >
  <tr><td>
  <table width="100%" class="tbl_border" >
    <tr><td>

	
	<?php if($error_msg != "") { ?>
	<div class="gagal">
		
		<?php echo $error_msg; ?>
		</div>
	<?php } ?>
	<form name="cash_form" id="cash_form" action="" method="post" >
		<table width="95%">
        <tr>
                    <td valign="top">
                        <table width="100%">
                        <tr><td width="200px">Type :</td>
            <td>
            <select id="client_type" name="client_type" onchange="show_divtype();" style="width: 250px; height: 25px;">
                <option value="other"  <?php if($select_data['type']=="other"){ echo "selected='selected'"; } ?>>Other</option>
                <option value="customer"  <?php if($select_data['type']=="customer"){ echo "selected='selected'"; } ?>>Customer</option>
                <option value="supplier"  <?php if($select_data['type']=="supplier"){ echo "selected='selected'"; } ?>>Supplier</option>
            </select>
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr>
                <td colspan="2">
                <div id="other_id" style="width: 100%;<?php if($select_data['type']=="other"){ echo "display: block;"; }else { echo "display: none;"; } ?>">
                    <table width="100%">
                        <tr><td width="190px">Other Name</td>
            <td><input type="text" id="other_name"  name="other_name" value="<?php if($select_data['type']=="other"){ echo $select_data['name']; } ?>" style="width: 250px;"/>&nbsp;</td></tr>
                    </table>
                </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                <div id="customer_id" style="width: 100%;<?php if($select_data['type']=="customer"){ echo "display: block;"; }else { echo "display: none;"; } ?> ">
                    <table width="100%">
                        <tr><td width="190px">Customer Name</td>
            <td>
            
<select id="customer_name" name="customer_name" style="width: 250px; height: 25px;">

<?php

    $sql_bank     = "select * from `customer` where type='customer'  ";
    $query_bank     = mysql_query($sql_bank);
    
    while($row_bank = mysql_fetch_array($query_bank)){
     ?>
     <option value="<?php echo $row_bank['cust_id']; ?>" <?php if($row_bank['cust_id']==$select_data['type_id']){ echo "selected='selected'"; } ?>><?php echo $row_bank['full_name'].' - '.$row_bank['cust_id'];?></option>
     <?php 
       
    }
 ?>
</select>
            </td></tr>
                    </table>
                </div>
                </td>
            </tr>
            
            <tr>
                <td colspan="2">
                <div id="supplier_id" style="width: 100%; <?php if($select_data['type']=="supplier"){ echo "display: block;"; }else { echo "display: none;"; } ?>">
                    <table width="100%">
                        <tr><td width="190px">Supplier Name</td>
            <td>
            
<select id="supplier_name" name="supplier_name" style="width: 250px; height: 25px;">

<?php

    $sql_bank     = "select * from `customer` where type='supplier'  ";
    $query_bank     = mysql_query($sql_bank);
    
    while($row_bank = mysql_fetch_array($query_bank)){
     ?>
     <option value="<?php echo $row_bank['cust_id']; ?>" <?php if($row_bank['cust_id']==$select_data['type_id']){ echo "selected='selected'"; } ?>><?php echo $row_bank['full_name'].' - '.$row_bank['cust_id'];?></option>
     <?php 
       
    }
 ?>
</select>
            </td></tr>
                    </table>
                </div>
                </td>
            </tr>
            <tr><td >Opening Balance</td>
            <td><input type="text" name="opening_balance" id="opening_balance" value="<?php echo $select_data['opening_balance']; ?>" style="width: 250px;" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
           
            <tr><td align="left" valign="top" >Current Address</td>
            <td><textarea name="current_address" id="current_address" style="width:250px; height:100px;"><?php echo $select_data['current_address']; ?></textarea></td></tr>
            
                    </table>
                    </td>
                    <td valign="top">
                        <table width="100%">
                        <tr><td >Mobile</td>
            <td><input type="text" name="mobile" id="mobile" value="<?php echo $select_data['mobile']; ?>" style="width: 250px;" ></td></tr>
            
            <tr><td >E-Mail Id</td>
            <td><input type="text" name="email" id="email" value="<?php echo $select_data['email']; ?>" style="width: 250px;" ></td></tr>
            <tr><td >Date</td>
            <td>
            <input type="text"  name="opening_balance_date" id="opening_balance_date" style="width: 250px;" value="<?php echo date("d-m-Y",$select_data['opening_balance_date']); ?>" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('opening_balance_date')" style="cursor:pointer"/>
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td >Company Name</td>
            <td><input type="text" name="company_name" id="company_name" value="<?php echo $select_data['company_name']; ?>" style="width: 250px;" ></td></tr>
              
<tr><td >Another Contact Name</td>
            <td><input type="text" name="another_contact_name" id="another_contact_name" value="<?php echo $select_data['another_contact_name']; ?>" style="width: 250px;" ></td></tr>
            <tr><td >Another Contact Mobile</td>
            <td><input type="text" name="another_mobile" id="another_mobile" value="<?php echo $select_data['another_mobile']; ?>" style="width: 250px;" ></td></tr>
         
                    </table>
                    </td>
            
            
            
            
            <tr><td colspan="2" align="center">
            <input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
            </td></tr></table> </td></tr></table> </td></tr></table>
		<input type="hidden" name="loan_advance" id="loan_advance" value="<?php echo $_REQUEST['loan_advance']; ?>" >
        <input type="hidden" name="action_perform" id="action_perform" value="" >
		
		</form>
		
		
		
		
        <?php include_once("main_body_close.php") ?>
        <?php include_once ("footer.php"); ?>
        
</body>
</html>
<script>
function add_div()
{
	$("#adddiv").toggle("slow");
}

function validation()
{
	
		$("#action_perform").val("edit_cash");
		$("#cash_form").submit();
		return true;
	
	
}
function show_divtype()
{
    
    if($("#client_type").val() == "other")
    {
         document.getElementById('customer_id').style.display='none';
          document.getElementById('supplier_id').style.display='none';
           document.getElementById('other_id').style.display='block';
    }
        if($("#client_type").val() == "customer")
    {
         document.getElementById('customer_id').style.display='block';
          document.getElementById('supplier_id').style.display='none';
           document.getElementById('other_id').style.display='none';
    }
        if($("#client_type").val() == "supplier")
    {
         document.getElementById('customer_id').style.display='none';
          document.getElementById('supplier_id').style.display='block';
           document.getElementById('other_id').style.display='none';
    }
}
</script>
