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


if(trim($_REQUEST['action_perform']) == "add_project")
{
	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
	$from_arr = explode("- ",$_REQUEST['from']);
	$cust_id = $from_arr[1];
	$project_id = get_field_value("id","project","name",$_REQUEST['project']);
	$amount=mysql_real_escape_string(trim($_REQUEST['amount']));
	$description=mysql_real_escape_string(trim($_REQUEST['description']));
	$subdivision = get_field_value("id","subdivision","name",$_REQUEST['subdivision']);
    //$subdivision=mysql_real_escape_string(trim($_REQUEST['subdivision']));
	//echo $project_id ;
    //exit;
	$payment_date = strtotime($_REQUEST['payment_date']);
    $trans_id = mysql_real_escape_string(trim($_REQUEST['trans_id'])); 
    $id_first1=$_REQUEST['id_first'];
    $id_second1=$_REQUEST['id_second'];
	$trans_id = mysql_real_escape_string(trim($_REQUEST['trans_id']));
	//$id_first=project, id_second   =costomer
	
	$query="update payment_plan set  project_id = '".$project_id."', debit = '".$amount."', description = '".$description."', on_customer = '".$cust_id."', payment_date = '".strtotime($_REQUEST['payment_date'])."',subdivision = '".$subdivision."',update_date = '".getTime()."' where  id = '".$id_first1."'";
    
	$result= mysql_query($query) or die('error in query '.mysql_error().$query);
	
	$link_id_1 = mysql_insert_id();
	
	$query2="update payment_plan set cust_id = '".$cust_id."', credit = '".$amount."', description = '".$description."', on_project = '".$project_id."', payment_date = '".strtotime($_REQUEST['payment_date'])."',subdivision = '".$subdivision."',update_date = '".getTime()."' where  id = '".$id_second1."'";
	$result2= mysql_query($query2) or die('error in query '.mysql_error().$query2);
	
	$link_id_2 = mysql_insert_id();
	
	$query5="update payment_plan set link_id = '".$link_id_2."' where id = '".$link_id_1."'";
	$result5= mysql_query($query5) or die('error in query '.mysql_error().$query5);
	
	
   
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
    if($trsns_pname_1=="customer-ledger")
    {
         $msg = "Sale Invoice Update successfully.";
          $flag = 1;
   // header(supplier-payment.php);
      echo "<script> location.href='customer.php'; </script>";
        
    }
    if($trsns_pname_1=="project-ledger")
    {
        $msg = "Sale Invoice Update successfully.";
         $flag = 1;
   // header(supplier-payment.php);
      echo "<script> location.href='project.php'; </script>";
    
    }
     if($trsns_pname_1=="By subdivision")
    {
         $msg = "GST Multiproject Invoice Update successfully.";
          $flag = 1;
   // header(supplier-payment.php);
      echo "<script>  location.href='subdivision.php'; </script>";
      //window.history.back();
        
    }
    
}

if($_REQUEST['trsns_pname']=="project-ledger")
{
//    echo "customer ledger update";
    if($_REQUEST['back']!="")
   {
       $trsns_pname = "By subdivision";
   }
    else{
    //    $trsns_pname = "project-ledger-inst-sale-goods";
    $trsns_pname = "project-ledger";
    }
    
    
    $select_query = "select * from payment_plan where id=".$_REQUEST['id']." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
   // echo "$select_query";
    //$cust_id,bank_id,project_id,amount,description,payment_date,trans_id
   
    $on_pro = $select_data['project_id'];
    $old_trans_id = $select_data['trans_id'];
    $old_cust_id = $select_data['on_customer'];
    $old_project_id = $select_data['project_id'];
    $old_subdivision = $select_data['subdivision'];
    $old_amount = $select_data['debit'];
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
    
    
}else if($_REQUEST['trsns_pname']=="customer-ledger")
{
   // echo "bank ledger update";
    $select_query = "select * from payment_plan where id=".$_REQUEST['id']." and trans_id = '".$_REQUEST['trans_id']."'";
    $select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
    $select_data = mysql_fetch_array($select_result);
   // echo "$select_query";
    //$cust_id,bank_id,project_id,amount,description,payment_date,trans_id
    $trsns_pname = "customer-ledger";
    
    $on_pro = $select_data['on_project'];
    $old_trans_id = $select_data['trans_id'];
    $old_cust_id = $select_data['cust_id'];
    $old_project_id = $select_data['on_project'];
    $old_subdivision = $select_data['subdivision'];
    $old_amount = $select_data['credit'];
    $old_description = $select_data['description'];
    $old_payment_date = $select_data['payment_date'];
    $old_link_id = $select_data['link_id'];
    $old_id = $select_data['id'];
     
   $id_first = $select_data['link_id'];
    $id_second = $select_data['id'];
    
}

?>
<html>

<head>
<title>Admin Panel</title>
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

</head>

<body>
<?php 
include_once("header.php");
?>

<div id="wrapper">
	<?php
	include_once("leftbar.php");
	?>
	<div id="rightContent">
	 <?php
     
    
     
    if($_REQUEST['trsns_pname']=="customer-ledger")
    { ?> <h3>Customer - <?php echo get_field_value("full_name","customer","cust_id",$old_cust_id); ?> Ledger </h3>
    <h2>Update Sale Invoice ( <?php  echo get_field_value("name","project","id",$old_project_id);?>
<font size="3">&nbsp;(<?php echo date("d-m-Y",$select_data['payment_date']); ?>)</font>)</h2>
   
    <?php    
    }else if($_REQUEST['trsns_pname']=="project-ledger")
    { ?> <h3>Bank - <?php echo get_field_value("name","project","id",$old_project_id); ?> Ledger </h3>
    <h2>Update Sale Invoice ( <?php echo get_field_value("full_name","customer","cust_id",$old_cust_id); ?>
<font size="3">&nbsp;(<?php echo date("d-m-Y",$select_data['payment_date']); ?>)</font>)</h2>
   
    <?php    
    }
?>
	<table width="100%" cellpadding="0" cellspacing="0" border="0" >
	<tr>
		<td>
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
	</tr>
	</table>
	<table width="100%" cellpadding="0" cellspacing="0" border="0" >
            <tr >
                <td align="right">
                <?php 
                 if($trsns_pname=="customer-ledger")
    {
         ?> <a href="customer.php" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a><?php 
      
        
    }
    if($trsns_pname=="project-ledger")
    {
        ?> <a href="project.php" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a><?php 
      
    
    }
     if($trsns_pname=="By subdivision")
    {
        ?> <a href="subdivision.php" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a><?php 
        
      
        
    }
                
                ?>
                   
                </td>
            </tr>
        </table>
	<div id="adddiv" >
	
	<form name="project_form" id="project_form" action="" method="post" enctype="multipart/form-data" >
    <input type="hidden" id="id_first" name="id_first" value="<?php echo $id_first; ?>">
    <input type="hidden" id="id_second" name="id_second" value="<?php echo $id_second; ?>">
    <input type="hidden" id="trsns_pname" name="trsns_pname" value="<?php echo $trsns_pname; ?>">

		<table width="95%">
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
             <?php
                $project_nm = get_field_value("name","project","id",$old_project_id);
                    ?>
			<td><input type="text" id="project"  name="project" value="<?php echo $project_nm; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td >to</td>
             <?php
             $sql_cus     = "select cust_id,full_name from `customer` where cust_id=".$old_cust_id." and type = 'customer'";
             $query_cus     = mysql_query($sql_cus);
             $select_cus = mysql_fetch_array($query_cus);
  
            ?>
			<td><input type="text" id="from"  name="from" value="<?php echo $select_cus['full_name'].' - '.$select_cus['cust_id']; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td >Sub Division Name</td>
			<td><?php
                $subdivision1 = get_field_value("name","subdivision","id",$old_subdivision);
                    ?>
             <input type="text" id="subdivision"  name="subdivision" value="<?php echo $subdivision1; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span>
			
			</td></tr>
			
			
			
			<tr><td align="left" valign="top" >Amount</td>
			<td><input type="text"  name="amount" id="amount" value="<?php echo $old_amount; ?>" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			<tr><td align="left" valign="top" >Date</td>
			<script src="js/datetimepicker_css.js"></script>
			<td><input type="text"  name="payment_date" id="payment_date" value="<?php echo date("d-m-Y",$old_payment_date); ?>" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('payment_date')" style="cursor:pointer"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			<tr><td valign="top" >Description</td>
			<td><textarea name="description" id="description" style="width:250px; height:100px;"><?php echo $old_description; ?></textarea>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td valign="top" >Attach File</td>
			<td><input type="file" name="attach_file" id="attach_file" value="" onChange="return hide_drag();" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td valign="top" >Attach File Name</td>
			<td><input type="text" id="attach_file_name"  name="attach_file_name" value="" autocomplete="off"/></td></tr>
			
			<tr><td valign="top" colspan="2" >
			<link rel="stylesheet" href="css/jquery-ui.css" />
  <script src="js/jquery-1.9.1.js"></script>
  <script src="js/jquery-ui.js"></script>
  
        <!-- Including the HTML5 Uploader plugin -->
        <script src="js/jquery.filedrop.js"></script>
        
        <!-- The main script file -->
        <script src="js/script.js"></script>
			</td></tr>
			
			
			<tr><td></td><td>
			<input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
			</td></tr>
		</table>
		<input type="hidden" name="action_perform" id="action_perform" value="" >
		
		</form>
		
		</div>
		
		
		

	</div>
<div class="clear"></div>
<?php
include_once("footer.php");
?>
</div>
</body>
</html>
<script>
function hide_drag()
{
	$("#drag_div").hide("fast");
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
			source: "customer-ajax.php"
		});
		$( "#project" ).autocomplete({
			source: "project-ajax.php"
		});
         $( "#subdivision" ).autocomplete({
            source: "subdivision2_ajax.php"
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