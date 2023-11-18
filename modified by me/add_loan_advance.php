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


/*     Create  Account   */

if(trim($_REQUEST['action_perform']) == "add_user")
{
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
    $quuerrr="select id from loan_advance where name = '".$full_name."'";
    
    $sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
    $no=mysql_num_rows($sql);
    if($no > 0)
    {
        
        
        $error_msg = "loan_advance name already exist try another";
        
    }
    else
    {
        $query="insert into loan_advance set  name = '".$full_name."', type = '".$client_type."', type_id = '".$type_id."', mobile = '".$mobile."', email = '".$email."', current_address = '".$current_address."', opening_balance = '".$opening_balance."',company_name = '".$company_name."', another_contact_name = '".$another_contact_name."', another_mobile = '".$another_mobile."', opening_balance_date = '".$opening_balance_date."',create_date = '".getTime()."', added_by = '".$_SESSION['userId']."', added_on = '".time()."'";
        $result= mysql_query($query) or die('error in query '.mysql_error().$query);
        $loan_id1 = mysql_insert_id();
        
        $query2="insert into payment_plan set loan_id = '".$loan_id1."', credit = '".$opening_balance."', description = 'Opening Balance', payment_date = '".$opening_balance_date."', create_date = '".getTime()."'";
        $result2= mysql_query($query2) or die('error in query '.mysql_error().$query2);
        $msg = "loan & advance added successfully.";
         
        header("Location:loan_advance.php?msg= Added successfully");
    }
    
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
        Add Loan & Advances </h4>
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
	<form name="user_form" id="user_form" action="" method="post" >
        <table width="95%">
                <tr>
                    <td valign="top">
                        <table width="100%">
                        <tr><td width="200px">Type :</td>
            <td>
            <select id="client_type" name="client_type" onchange="show_divtype();" style="width: 250px; height: 25px;">
                <option value="other"  <?php if($_REQUEST['loan_type']=="other"){ echo "selected='selected'"; } ?>>Other</option>
                <option value="customer"  <?php if($_REQUEST['loan_type']=="customer"){ echo "selected='selected'"; } ?>>Customer</option>
                <option value="supplier"  <?php if($_REQUEST['loan_type']=="supplier"){ echo "selected='selected'"; } ?>>Supplier</option>
            </select>
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr>
                <td colspan="2">
                <div id="other_id" style="width: 100%;">
                    <table width="100%">
                        <tr><td width="190px">Other Name</td>
            <td><input type="text" id="other_name"  name="other_name" value="<?php echo $_REQUEST['other_name']; ?>" style="width: 250px;"/>&nbsp;</td></tr>
                    </table>
                </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                <div id="customer_id" style="width: 100%; display: none;">
                    <table width="100%">
                        <tr><td width="190px">Customer Name</td>
            <td>
            
<select id="customer_name" name="customer_name" style="width: 250px; height: 25px;">

<?php

    $sql_bank     = "select * from `customer` where type='customer'  ";
    $query_bank     = mysql_query($sql_bank);
    
    while($row_bank = mysql_fetch_array($query_bank)){
     ?>
     <option value="<?php echo $row_bank['cust_id']; ?>" <?php if($row_bank['cust_id']==$_REQUEST['customer_name']){ echo "selected='selected'"; } ?>><?php echo $row_bank['full_name'].' - '.$row_bank['cust_id'];?></option>
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
                <div id="supplier_id" style="width: 100%; display: none;">
                    <table width="100%">
                        <tr><td width="190px">Supplier Name</td>
            <td>
            
<select id="supplier_name" name="supplier_name" style="width: 250px; height: 25px;">

<?php

    $sql_bank     = "select * from `customer` where type='supplier'  ";
    $query_bank     = mysql_query($sql_bank);
    
    while($row_bank = mysql_fetch_array($query_bank)){
     ?>
     <option value="<?php echo $row_bank['cust_id']; ?>" <?php if($row_bank['cust_id']==$_REQUEST['supplier_name']){ echo "selected='selected'"; } ?>><?php echo $row_bank['full_name'].' - '.$row_bank['cust_id'];?></option>
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
            <td><input type="text" name="opening_balance" id="opening_balance" value="<?php echo $_REQUEST['opening_balance']; ?>" style="width: 250px;" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
           
            <tr><td align="left" valign="top" >Current Address</td>
            <td><textarea name="current_address" id="current_address" style="width:250px; height:100px;"><?php echo $_REQUEST['current_address']; ?></textarea></td></tr>
           
                    </table>
                    </td>
                    <td valign="top">
                        <table width="100%">
                        <tr><td >Mobile</td>
            <td><input type="text" name="mobile" id="mobile" value="<?php echo $_REQUEST['mobile']; ?>" style="width: 250px;" ></td></tr>
            
            <tr><td >E-Mail Id</td>
            <td><input type="text" name="email" id="email" value="<?php echo $_REQUEST['email']; ?>" style="width: 250px;" ></td></tr>
            
            
            <tr><td >Date</td>
            <td>
            <input type="text"  name="opening_balance_date" id="opening_balance_date" style="width: 250px;" value="<?php echo date("d-m-Y"); ?>" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('opening_balance_date')" style="cursor:pointer"/>
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <tr><td >Company Name</td>
            <td><input type="text" name="company_name" id="company_name" value="<?php echo $_REQUEST['company_name']; ?>" style="width: 250px;" ></td></tr>
            
<tr><td >Another Contact Name</td>
            <td><input type="text" name="another_contact_name" id="another_contact_name" value="<?php echo $_REQUEST['another_contact_name']; ?>" style="width: 250px;" ></td></tr>
            <tr><td >Another Contact Mobile</td>
            <td><input type="text" name="another_mobile" id="another_mobile" value="<?php echo $_REQUEST['another_mobile']; ?>" style="width: 250px;" ></td></tr>
           
                    </table>
                    </td>
            
            <tr><td colspan="2" align="center">
                <input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
            </td></tr> </table>  </td></tr></table> </td></tr></table>
        <input type="hidden" name="action_perform" id="action_perform" value="" >
        <input type="hidden" name="del_id" id="del_id" value="" >
        <input type="hidden" name="count" id="count" value="<?php echo $i; ?>"  />    
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
    /*if($("#loan_advance").val() == "")
    {
        alert("Please enter loan_advance name.");
        $("#loan_advance").focus();
        return false;
    }
    else
    {*/
        $("#action_perform").val("add_user");
        $("#user_form").submit();
        return true;
    //}
    
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
