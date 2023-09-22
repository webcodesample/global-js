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


if(trim($_REQUEST['action_perform']) == "edit_customer")
{
	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
	$fname=mysql_real_escape_string(trim($_REQUEST['fname']));
	$lname=mysql_real_escape_string(trim($_REQUEST['lname']));
	$full_name = $fname.' '.$lname;
	$phone=mysql_real_escape_string(trim($_REQUEST['phone']));
	$mobile=mysql_real_escape_string(trim($_REQUEST['mobile']));
	$email=mysql_real_escape_string(trim($_REQUEST['email']));
	$current_address=mysql_real_escape_string(trim($_REQUEST['current_address']));
	$permanent_address=mysql_real_escape_string(trim($_REQUEST['permanent_address']));
    $opening_balance=mysql_real_escape_string(trim($_REQUEST['opening_balance']));
    $opening_balance = str_replace(",","",$opening_balance);
    $opening_balance_date=strtotime(mysql_real_escape_string(trim($_REQUEST['opening_balance_date'])));
    //company_name,client_name,project_manager,client_mobile,project_type
	//client_gst,project,client_email,client_contact_per,bank_attachment
    //,,,,
    $company_name=mysql_real_escape_string(trim($_REQUEST['company_name']));
    $client_name=mysql_real_escape_string(trim($_REQUEST['client_name']));
    $project_manager=mysql_real_escape_string(trim($_REQUEST['project_manager']));    
    $client_mobile=mysql_real_escape_string(trim($_REQUEST['client_mobile']));
    $project_type=mysql_real_escape_string(trim($_REQUEST['project_type']));
    
    $client_gst=mysql_real_escape_string(trim($_REQUEST['client_gst']));
    $project=mysql_real_escape_string(trim($_REQUEST['project']));
    $client_email=mysql_real_escape_string(trim($_REQUEST['client_email']));
    $client_contact_per=mysql_real_escape_string(trim($_REQUEST['client_contact_per']));
    $bank_attachment=mysql_real_escape_string(trim($_REQUEST['bank_attachment']));
    //client_gst = '".$client_gst."', project = '".$project."', client_email = '".$client_email."', client_contact_per = '".$client_contact_per."', bank_attachment = '".$bank_attachment."',

	if(mysql_real_escape_string(trim($_REQUEST['customer_type'])) == "tenant")
	{
	   $customer_type=mysql_real_escape_string(trim($_REQUEST['customer_type']));            
	   $tenant_first_rent_agree_date=strtotime(mysql_real_escape_string(trim($_REQUEST['tenant_first_rent_agree_date'])));
	   $tenant_current_rent_agree_date=strtotime(mysql_real_escape_string(trim($_REQUEST['tenant_current_rent_agree_date'])));
	   $tenant_current_rent=mysql_real_escape_string(trim($_REQUEST['tenant_current_rent']));
	   $tenant_nextrenawal_duedate=strtotime(mysql_real_escape_string(trim($_REQUEST['tenant_nextrenawal_duedate'])));
	   $tenant_nextrenewal_rent=mysql_real_escape_string(trim($_REQUEST['tenant_nextrenewal_rent']));
	   $tenant_registered=mysql_real_escape_string(trim($_REQUEST['tenant_registered']));       
	   $tenant_info = " customer_type = '".$customer_type."', tenant_first_rent_agree_date = '".$tenant_first_rent_agree_date."', tenant_current_rent_agree_date = '".$tenant_current_rent_agree_date."', tenant_current_rent = '".$tenant_current_rent."', tenant_nextrenawal_duedate = '".$tenant_nextrenawal_duedate."', tenant_nextrenewal_rent = '".$tenant_nextrenewal_rent."', tenant_registered = '".$tenant_registered."', ";
	}
	else
	{
		$tenant_info = "";
	}
   
	//company_name = '".$company_name."', client_name = '".$client_name."', project_manager = '".$project_manager."', client_mobile = '".$client_mobile."', project_type = '".$project_type."',
    if(mysql_real_escape_string(trim($_REQUEST['same_current'])) == "yes")
	{
		$same_address = "yes";
	}
	else
	{
		$same_address = "no";
	}
	
	$quuerrr="select cust_id from customer where email='".$email."' and type = 'customer' and cust_id != '".$_REQUEST['cust_id']."' ";
	
	$sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
	$no=mysql_num_rows($sql);
	if($no > 0)
	{
		
			$error_msg = "E-Mail ID already exist try another";
		
	}
	else
	{
		$query="update customer set fname = '".$fname."', lname = '".$lname."', full_name = '".$full_name."', mobile = '".$mobile."', email = '".$email."', current_address = '".$current_address."', same_address = '".$same_address."', opening_balance = '".$opening_balance."',company_name = '".$company_name."', client_name = '".$client_name."', project_manager = '".$project_manager."', client_mobile = '".$client_mobile."', client_gst = '".$client_gst."', project = '".$project."', client_email = '".$client_email."', client_contact_per = '".$client_contact_per."', bank_attachment = '".$bank_attachment."',project_type = '".$project_type."', opening_balance_date = '".$opening_balance_date."', update_date = '".getTime()."' ,userid_update = '".$_SESSION['userId']."',".$tenant_info." permanent_address = '".$permanent_address."' where cust_id = '".$_REQUEST['cust_id']."' and type = 'customer'";
		$result= mysql_query($query) or die('error in query '.mysql_error().$query);
        
    $ss1="select * from payment_plan  where cust_id = '".$_REQUEST['cust_id']."' and description = 'Opening Balance'";
            $sr1=mysql_query($ss1);
            $tot_rw1=mysql_num_rows($sr1);
            if($tot_rw1 == 0)
            {
                $query2="insert into payment_plan set cust_id = '".$_REQUEST['cust_id']."', credit = '".$opening_balance."', description = 'Opening Balance', payment_date = '".$opening_balance_date."', create_date = '".getTime()."'";
        $result2= mysql_query($query2) or die('error in query '.mysql_error().$query2);
                                                   
            } else
            {   
         $query2="update payment_plan set credit = '".$opening_balance."',payment_date = '".$opening_balance_date."' where cust_id = '".$_REQUEST['cust_id']."' and description = 'Opening Balance'";
    $result2= mysql_query($query2) or die('error in query Cash query '.mysql_error().$query2);
            }
        
		header("Location:customer.php?msg=Customer Updated successfully");
	}
	
	
}



$select_query = "select * from customer where cust_id = '".$_REQUEST['cust_id']."'";
$select_result = mysql_query($select_query) or die('error in query select customer query '.mysql_error().$select_query);
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
		Edit customer </h4>
  </td>
        <td width="" style="float:right;">
            <a href="customer.php" title="Back" style=""><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
            </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
  <?php include_once("main_body_open.php") ?>
  <table width="100%" style="padding:10px;" >
  <tr><td valign="top">
  <table width="100%" class="tbl_border" >
    <tr><td valign="top">
	<?php if($error_msg != "") { ?>
	<div class="gagal">
		
		<?php echo $error_msg; ?>
		</div>
	<?php } ?>
  	
	
	
	<form name="customer_form" id="customer_form" action="" method="post" >
		<table width="95%"   style="padding:0px;">
		<tr>
            <td valign="top">
                <table width="100%">
				<tr><td width="125px">First Name</td>
			<td><input type="text" name="fname" id="fname" style="width: 250px;" tabindex="1" value="<?php echo $select_data['fname']; ?>" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			<tr><td >Mobile</td>
			<td><input type="text" name="mobile" id="mobile" style="width: 250px;" tabindex="3" value="<?php echo $select_data['mobile']; ?>" ></td></tr>
			<tr><td >Opening Balance</td>
            <td><input type="text" name="opening_balance" id="opening_balance" style="width: 250px;"  tabindex="5" value="<?php echo $select_data['opening_balance']; ?>" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td align="left" valign="top" >Current Address</td>
			<td><textarea name="current_address" id="current_address" style="width:250px; height:100px;"  tabindex="7"><?php echo $select_data['current_address']; ?></textarea></td></tr>
			
			
            
            <tr><td >Company Name</td>
            <td><input type="text" name="company_name" id="company_name" style="width: 250px;"  tabindex="10" value="<?php echo $select_data['company_name']; ?>" ></td></tr>
            
            <tr><td >Project Manager</td>
            <td><input type="text" name="project_manager" id="project_manager" style="width: 250px;"  tabindex="11" value="<?php echo $select_data['project_manager']; ?>" ></td></tr>
            

<tr><td >Client Name</td>
            <td><input type="text" name="client_name" id="client_name" style="width: 250px;"  tabindex="13" value="<?php echo $select_data['client_name']; ?>" ></td></tr>
            <tr><td >Client Mobile</td>
            <td><input type="text" name="client_mobile" id="client_mobile" style="width: 250px;"  tabindex="15" value="<?php echo $select_data['client_mobile']; ?>" ></td></tr>
            
<tr><td >Client GST</td>
<td><input type="text" name="client_gst" id="client_gst" value="<?php echo $select_data['client_gst']; ?> "  tabindex="17" style="width: 250px;" ></td></tr>


<tr><td >Bank Attachment</td>
<td>

<select id="bank_attachment" name="bank_attachment" style="width: 250px; height: 25px;"  tabindex="19">
<option value="" >No bank Attachment</option>
<?php

    $sql_bank     = "select * from `bank`  ";
    $query_bank     = mysql_query($sql_bank);
    
    while($row_bank = mysql_fetch_array($query_bank)){
     ?>
     <option value="<?php echo $row_bank['id']; ?>" <?php if($row_bank['id']==$select_data['bank_attachment']){ echo "selected='selected'"; } ?>><?php echo $row_bank['bank_account_name'].' - '.$row_bank['bank_account_number'];?></option>
     <?php 
       
    }
 ?>
</select>
</td></tr>
            
				</table>
			</td>
			<td valign="top">
                <table width="100%">
				<tr><td >Last Name</td>
			<td><input type="text" name="lname" id="lname" value="<?php echo $select_data['lname']; ?>"  tabindex="2" style="width: 250px;" ></td></tr>
			<tr><td >E-Mail Id</td>
			<td><input type="text" name="email" id="email" value="<?php echo $select_data['email']; ?>"  tabindex="4" style="width: 250px;" ></td></tr>
			<tr><td >Date</td>
            <td>
            <input type="text"  name="opening_balance_date" id="opening_balance_date"  tabindex="6" value="<?php echo date("d-m-Y",$select_data['opening_balance_date']); ?>" style="width: 250px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('opening_balance_date')" style="cursor:pointer"/>
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td >Same as Current</td>
			<td><input type="checkbox" name="same_current" id="same_current" value="yes"  tabindex="8" onClick="return same_address();" <?php if($select_data['same_address'] == 'yes') { echo 'checked="checked"'; } ?>   /></td></tr>
			
			<tr><td align="left" valign="top" >Permanent Address</td>
			<td><textarea name="permanent_address" id="permanent_address" style="width:250px; height:100px;"  tabindex="9"><?php echo $select_data['permanent_address']; ?></textarea></td></tr>
			<tr><td >Project Name</td>
<td>
<!--<input type="text" name="project" id="project" value="<?php echo $select_data['project']; ?>" >-->
<select id="project" name="project" style="width: 250px; height: 25px;"  tabindex="12">
<option value="">No Project </option>
<?php

    $sql_pro1     = "select * from `project`  ";
    $query_pro1     = mysql_query($sql_pro1);
    
    while($row_pro1 = mysql_fetch_array($query_pro1)){
     ?>
     <option value="<?php echo $row_pro1['id']; ?>" <?php if($row_pro1['id']==$select_data['project']){ echo "selected='selected'"; } ?>><?php echo $row_pro1['name'];?></option>
     <?php 
       
    }
 ?>
</select>
</td></tr>
<tr><td >Project Type</td>
            <td><input type="text" name="project_type" id="project_type"  tabindex="14" value="<?php echo $select_data['project_type']; ?>" style="width: 250px;" ></td></tr>
			<tr><td >Client Email</td>
<td><input type="text" name="client_email" id="client_email"  tabindex="16" value="<?php echo $select_data['client_email']; ?>" style="width: 250px;" ></td></tr>

<tr><td >Client Contact Person</td>
<td><input type="text" name="client_contact_per" id="client_contact_per"  tabindex="18" value="<?php echo $select_data['client_contact_per']; ?>" style="width: 250px;" ></td></tr>
           
				</table>
			</td>
		</tr>	



		<tr>
                    <td valign="top" colspan="2">
                        <table width="100%">
                        <tr><td width="125px">Customer Type :</td>
            <td>
                <?php //customer_type ,tenant_first_rent_agree_date , tenant_current_rent_agree_date , tenant_current_rent, tenant_nextrenawal_duedate , tenant_nextrenewal_rent , tenant_registered
           ?>
           <select id="customer_type" name="customer_type" onchange="show_divtype();" style="width: 250px; height: 25px;" tabindex="20" >
		   <option value="other"  <?php if($select_data['customer_type']=="other"){ echo "selected='selected'"; } ?> >Other</option>      
		   <option value="tenant"  <?php if($select_data['customer_type']=="tenant"){ echo "selected='selected'"; } ?>>Tenant</option>
                <option value="propertybuyer"  <?php if($select_data['customer_type']=="propertybuyer"){ echo "selected='selected'"; } ?>>Property Buyer</option>
                
            </select>
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  ></span></td></tr>
            <tr>
                <td colspan="2">
                <div id="tenant_id" style="display:<?php if($select_data['customer_type']=="tenant") { ?>block<?php } else { ?>none<?php } ?>;">
	   
				<table width="100%" class="tbl_border">
                        <tr><td colspan="4"><b><u>Tenant Infomation Details :</u></b></td></tr>
                        <tr><td width="200px">Start Date Of First Lease/Rent Agreement</td>
            <td><input type="text" id="tenant_first_rent_agree_date"  name="tenant_first_rent_agree_date" tabindex="21"  value="<?php if($select_data['tenant_first_rent_agree_date']>"978287400") {echo date("d-m-Y",$select_data['tenant_first_rent_agree_date']);} ?>" style="width: 250px;"autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('tenant_first_rent_agree_date')" style="cursor:pointer"/>&nbsp;</td>
            
            <td width="200px">Start Date Of Current Lease/Rent Agreement</td>
            <td><input type="text" id="tenant_current_rent_agree_date"  name="tenant_current_rent_agree_date" tabindex="22"  value="<?php if($select_data['tenant_current_rent_agree_date']>"978287400") { echo date("d-m-Y",$select_data['tenant_current_rent_agree_date']);} ?>" style="width: 250px;"autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('tenant_current_rent_agree_date')" style="cursor:pointer"/>&nbsp;</td></tr>
                  
            <tr><td width="200px">Current Rent</td>
            <td><input type="text" id="tenant_current_rent"  name="tenant_current_rent" tabindex="23"  value="<?php echo $select_data['tenant_current_rent']; ?>" style="width: 250px;"/>&nbsp;</td>
                    
            <td width="200px">Next Renewal due Date</td>
            <td><input type="text" id="tenant_nextrenawal_duedate"  name="tenant_nextrenawal_duedate" tabindex="24"  value="<?php if($select_data['tenant_nextrenawal_duedate']>"978287400") { echo date("d-m-Y",$select_data['tenant_nextrenawal_duedate']); }?>" style="width: 250px;"autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('tenant_nextrenawal_duedate')" style="cursor:pointer"/>&nbsp;</td></tr>
                    
            <tr><td width="200px">Next Renewal Rent</td>
            <td><input type="text" id="tenant_nextrenewal_rent"  name="tenant_nextrenewal_rent" tabindex="25"  value="<?php echo $select_data['tenant_nextrenewal_rent']; ?>" style="width: 250px;"/>&nbsp;</td>
                    
            <td width="200px">Registered / Unregistered</td>
            <td><select id="tenant_registered" name="tenant_registered" onchange="show_divtype();" tabindex="26"  style="width: 250px; height: 25px;">
                <option value="Registered"  <?php if($select_data['tenant_registered']=="Registered"){ echo "selected='selected'"; } ?>>Registered</option>
                <option value="Unregistered"  <?php if($select_data['tenant_registered']=="Unregistered"){ echo "selected='selected'"; } ?>>Unregistered</option>
 
            </select>
        </td></tr>
                    
        </table>
                </div>
                </td>
            </tr>
</table></td></tr>
            

			
			
			
			<tr><td colspan="2" align="center">
			<input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
			</td></tr></table></td></tr></table></td></tr></table>
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
	if($("#fname").val() == "")
	{
		alert("Please enter first name.");
		$("#fname").focus();
		return false;
	}
	/*else if($("#mobile").val() == "")
	{
		alert("Please enter mobile number.");
		$("#mobile").focus();
		return false;
	}
	else if($("#email").val() == "")
	{
		alert("Please enter email address.");
		$("#email").focus();
		return false;
	}
	else if($("#current_address").val() == "")
	{
		alert("Please enter current address.");
		$("#current_address").focus();
		return false;
	}*/
	else
	{
		$("#action_perform").val("edit_customer");
		$("#customer_form").submit();
		return true;
	}
	
}
 function same_address()
 {
 	if(document.getElementById("same_current").checked==true)
	{
		
		document.getElementById("permanent_address").value = document.getElementById("current_address").value;
		
	}
	else
	{
	
		document.getElementById("permanent_address").value = "";
		
	}
 }
 
function show_divtype()
{
    
    if($("#customer_type").val() == "tenant")
    {
           document.getElementById('tenant_id').style.display='block';
    }else 
        {
         document.getElementById('tenant_id').style.display='none';
        }
}
</script>