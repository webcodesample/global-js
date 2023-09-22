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
	$from_arr = explode(" -",$_REQUEST['from']);
	$to_arr = explode("- ",$_REQUEST['to']);
	$cust_id = $to_arr[1];
	$bank_id = get_field_value("id","bank","bank_account_name",$from_arr[0]);
	$project_id = get_field_value("id","project","name",$_REQUEST['project']);
	$amount=mysql_real_escape_string(trim($_REQUEST['amount']));
	$description=mysql_real_escape_string(trim($_REQUEST['description']));
	$trans_id = mysql_real_escape_string(trim($_REQUEST['trans_id'])); 
	$payment_date = strtotime($_REQUEST['payment_date']);
    $id_first1=$_REQUEST['id_first'];
    $id_second1=$_REQUEST['id_second'];
    
	
	$query="update payment_plan set  bank_id = '".$bank_id."', debit = '".$amount."', description = '".$description."', on_customer = '".$cust_id."', on_project = '".$project_id."', payment_date = '".$payment_date."',update_date = '".getTime()."' where  id = '".$id_second1."'";
	$result= mysql_query($query) or die('error in query '.mysql_error().$query);
	
    
	
	
	$query2="update payment_plan set cust_id = '".$cust_id."', credit = '".$amount."', description = '".$description."', on_project = '".$project_id."', on_bank = '".$bank_id."', payment_date = '".$payment_date."',  update_date = '".getTime()."' where  id = '".$id_first1."'";
	$result2= mysql_query($query2) or die('error in query '.mysql_error().$query2);
	
	
	
    
    if($_FILES["attach_file"]["name"] != "")
    {
        $query3="insert into attach_file set attach_id = '".$id_first1."', link_id = '".$id_second1."',file_name = '".$new_file_name."'";
    $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
    $link_id_4 = mysql_insert_id();
    
        $attach_file_name=mysql_real_escape_string(trim($_REQUEST['attach_file_name']));
        $temp = explode(".", $_FILES["attach_file"]["name"]);
        $arr_size = count($temp);
        $extension = end($temp);
        //$new_file_name = $attach_file_name.'_'.date("d_M_Y").'.'.$extension;
         $new_file_name = $attach_file_name.'_'.$link_id_4.'_'.date("d_M_Y").'.'.$extension;
        move_uploaded_file($_FILES["attach_file"]["tmp_name"],"transaction_files/" . $new_file_name);
        
             
    $query4="insert into attach_file set attach_id = '".$id_second1."', link_id = '".$id_first1."',old_id = '".$link_id_4."',file_name = '".$new_file_name."'";
    $result4= mysql_query($query4) or die('error in query '.mysql_error().$query4);
    $link_id_5 = mysql_insert_id();
    
    $query5_1="update attach_file set old_id = '".$link_id_5."',file_name = '".$new_file_name."' where id = '".$link_id_4."'";
    $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);
   
    }
    else
    {
        $new_file_name = "";
    }
    
    
    
    $trsns_pname_1 = $_REQUEST['trsns_pname'];
    if($trsns_pname_1=="supplier-ledger")
    {
         $msg = "Receive Payment Update successfully.";
          $flag = 1;
   // header(supplier-payment.php);
      echo "<script> location.href='supplier.php'; </script>";
        
    }
    if($trsns_pname_1=="bank-ledger")
    {
        $msg = "Receive Payment Update successfully.";
         $flag = 1;
   // header(supplier-payment.php);
      echo "<script> location.href='bank.php'; </script>";
    
    }
    
    
	
}



if($_REQUEST['trsns_pname']=="supplier-ledger")
{
//    echo "customer ledger update";

    $trsns_pname = "supplier-ledger";
    $select_query = "select * from payment_plan where id=".$_REQUEST['id']." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
   // echo "$select_query";
    //$cust_id,bank_id,project_id,amount,description,payment_date,trans_id
   
    $on_pro = $select_data['on_project'];
    $old_trans_id = $select_data['trans_id'];
    $old_cust_id = $select_data['cust_id'];
    $old_bank_id = $select_data['on_bank'];
    $old_project_id = $select_data['on_project'];
    $old_amount = $select_data['credit'];
    $old_description = $select_data['description'];
    $old_payment_date = $select_data['payment_date'];
    $old_link_id = $select_data['link_id'];
    $old_id = $select_data['id'];
    $id_first = $select_data['id'];
    $id_second = $select_data['link_id'];
        
    
   /* $select_query_pro = "select * from payment_plan where trans_id=".$_REQUEST['trans_id']." and project_id=".$on_pro." and on_customer = '".$_REQUEST['cust_id']."'";
    $select_result_pro = mysql_query($select_query_pro) or die('error in query select supplier query '.mysql_error().$select_query_pro);
    $select_data_pro = mysql_fetch_array($select_result_pro);
    *///echo "$select_query_pro";
    
    
}else if($_REQUEST['trsns_pname']=="bank-ledger")
{
   // echo "bank ledger update";
    $select_query = "select * from payment_plan where id=".$_REQUEST['id']." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
   // echo "$select_query";
    //$cust_id,bank_id,project_id,amount,description,payment_date,trans_id
    $trsns_pname = "bank-ledger";
    $on_pro = $select_data['on_project'];
    $old_trans_id = $select_data['trans_id'];
    $old_cust_id = $select_data['on_customer'];
    $old_bank_id = $select_data['bank_id'];
    $old_project_id = $select_data['on_project'];
    $old_amount = $select_data['debit'];
    $old_description = $select_data['description'];
    $old_payment_date = $select_data['payment_date'];
    $old_link_id = $select_data['link_id'];
    $old_id = $select_data['id'];
   $id_first = $select_data['link_id'];
    $id_second = $select_data['id'];
    
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
        <?php
    if($_REQUEST['trsns_pname']=="supplier-ledger")
    { ?>Customer - <?php echo get_field_value("full_name","customer","cust_id",$old_cust_id); ?> Ledger 
    <?php  }else if($_REQUEST['trsns_pname']=="bank-ledger")
    { ?>Bank - <?php echo get_field_value("bank_account_name","bank","id",$old_bank_id); ?> Ledger 
    <?php } ?>    
    
    </h4>
  </td>
        <td width="" style="float:right;">
        <?php 
        if($_REQUEST['trsns_pname']=="supplier-ledger")
    { ?><a href="supplier-ledger.php?cust_id=<?php echo $old_cust_id; ?>" title="Back" style=""><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
             
    <?php  }else if($_REQUEST['trsns_pname']=="bank-ledger")
    { ?><a href="bank-ledger.php?bank_id=<?php echo $old_bank_id; ?>" title="Back" style=""><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
             
    <?php } ?>    
    
            </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
  <?php include_once("main_body_open.php") ?>
  	
	
	<div id="adddiv" >
	
	<form name="payment_form" id="payment_form" action="" method="post" enctype="multipart/form-data" >
	<input type="hidden" id="id_first" name="id_first" value="<?php echo $id_first; ?>">
    <input type="hidden" id="id_second" name="id_second" value="<?php echo $id_second; ?>">
    <input type="hidden" id="trsns_pname" name="trsns_pname" value="<?php echo $trsns_pname; ?>">

        <table width="98%" style="padding:0px 20px 30px 30px;">
            <tr>
                <td><?php
    if($_REQUEST['trsns_pname']=="supplier-ledger")
    { ?> 
    <h4 class="u-text-2 u-text-palette-1-base1 ">Update Make Payment ( <?php  echo get_field_value("bank_account_name","bank","id",$old_bank_id);?>
<font size="3">&nbsp;(<?php echo date("d-m-Y",$select_data['payment_date']); ?>)</font>)</h4>
   
    <?php    
    }else if($_REQUEST['trsns_pname']=="bank-ledger")
    { ?> 
    <h4 class="u-text-2 u-text-palette-1-base1 ">Update Make Payment ( <?php echo get_field_value("full_name","customer","cust_id",$old_cust_id); ?>
<font size="3">&nbsp;(<?php echo date("d-m-Y",$select_data['payment_date']); ?>)</font>)</h4>
   
    <?php    
    }
?> </td>   
            </tr>
            <tr>
                    <td><?php if($msg != "") { ?>
	<div class="sukses">
		<?php echo $msg; ?>
		</div>
	<?php } else if($error_msg != "") { ?>
	<div class="gagal">
		
		<?php echo $error_msg; ?>
		</div>
	<?php } ?></td>
            </tr>   
            <tr>
                <td>
                    <table width="100%" class="tbl_border" >
			<tr><td width="125px">Transaction ID</td>
			<td style="color:#FF0000; font-weight:bold;"><input type="hidden" id="trans_id"  name="trans_id" value="<?php echo $old_trans_id; ?>"/>&nbsp;<?php echo $old_trans_id; ?></td>
             <td rowspan="9" valign="top" width="250" align="left">
                <?php
                $query_file="select *  from attach_file where attach_id = '".$_REQUEST['id']."'";
$result_file= mysql_query($query_file) or die('error in query '.mysql_error().$query_file);
$total_rows_file = mysql_num_rows($result_file);
?>
    <table cellpadding="2" cellspacing="0" border="0" width="100%" align="center"  >
            <tr><td valign="top" align="left" colspan="2" >Attechment Files :</td></tr>
            <tr >
                <th valign="top" width="10"  style="border:1px solid #CCCCCC;">S.No.</th>
                <th style="border:1px solid #CCCCCC;">File Name</th>
                
            </tr> 
                <?php
                if($total_rows_file == 0)
                {
                ?>
            <tr>
                <td colspan="2">No file Found</td>
            </tr>                   
        <?php
    
}
else
{
    
    $i=1;
    while($data_file = mysql_fetch_array($result_file))
    {
        ?>
            
            <tr>
                <td valign="top" style="border:1px solid #CCCCCC;" ><?php echo $i; ?></td>
                <td style="border:1px solid #CCCCCC;"><?php echo $data_file['file_name']; ?></td>
            </tr>
        <?php
        $i++;
    }
    
}
?>
    </table>
              
            </td>
            </tr>
			<tr><td width="125px">From</td>
			<td>
             <?php
             $sql_cus     = "select bank_account_name,bank_account_number from `bank`  where id=".$old_bank_id." ";
             $query_cus     = mysql_query($sql_cus);
             $select_cus = mysql_fetch_array($query_cus);
  
            ?>
            <input type="text" id="from"  name="from" value="<?php echo $select_cus['bank_account_name'].' - '.$select_cus['bank_account_number']; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td >To</td>
             <?php
             $sql_bank     = "select cust_id,full_name from `customer` where cust_id=".$old_cust_id." and type = 'supplier'";
             $query_bank     = mysql_query($sql_bank);
             $select_bank = mysql_fetch_array($query_bank);
             
  
            ?>
			<td><input type="text" id="to"  name="to" value="<?php echo $select_bank['full_name'].' - '.$select_bank['cust_id']; ?>" style="width:250px;"/></td></tr>
			
			<tr><td >Project</td>
             <?php
                $project_nm = get_field_value("name","project","id",$old_project_id);
                    ?>
			<td><input type="text" id="project"  name="project" value="<?php echo $project_nm; ?>" style="width:250px;"/></td></tr>
			
			<tr><td align="left" valign="top" >Amount</td>
			<td><input type="text"  name="amount" id="amount" value="<?php echo $old_amount; ?>" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			<tr><td align="left" valign="top" >Date</td>
			<script src="js/datetimepicker_css.js"></script>
			<td><input type="text"  name="payment_date" id="payment_date" value="<?php echo date("d-m-Y",$old_payment_date); ?>" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('payment_date')" style="cursor:pointer"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			<tr><td valign="top" >Project Description</td>
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
	else if($("#project").val() == "")
	{
		alert("Please enter project.");
		$("#project").focus();
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
 <!-- <script src="js/jquery-1.9.1.js"></script>c-->
  <script src="js/jquery-ui.js"></script>
	<script>
	$(document).ready(function(){
		$( "#from" ).autocomplete({
			source: "bank-ajax.php"
		});
		$( "#to" ).autocomplete({
			source: "supplier-ajax.php"
		});
		$( "#project" ).autocomplete({
			source: "project-ajax.php"
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
			
			var text = '<table cellpadding="10" cellspacing="0" border="0" width="95%"><tr><td colspan="2" >Receive Goods</td></tr><tr><td width="125px">Transaction ID</td><td><?php echo $trans_id; ?></td></tr><tr><td width="125px">From</td><td><?php echo $_REQUEST['from']; ?></td></tr><tr><td width="125px">To</td><td><?php echo $_REQUEST['to']; ?></td></tr><tr><td >Project</td><td><?php echo $_REQUEST['project']; ?></td></tr><tr><td>Amount</td><td>Rs. &nbsp;<?php echo $_REQUEST['amount']; ?></td></tr><tr><td>Date</td><td><?php echo $_REQUEST['payment_date']; ?></td></tr><tr><td >Description</td><td><?php echo $_REQUEST['description']; ?></td></tr></table>';
			printMe=window.open();
			printMe.document.write(text);
			printMe.print();
			printMe.close();
						
			
		}
	</script>
	<?php
}

?>