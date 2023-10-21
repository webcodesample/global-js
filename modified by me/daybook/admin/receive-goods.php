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
	//$subdivision=mysql_real_escape_string(trim($_REQUEST['subdivision']));
    $subdivision = get_field_value("id","subdivision","name",$_REQUEST['subdivision']);
	
	 $trans_type = 3;
    $trans_type_name = "receive_goods" ;
   
	$trans_id = mysql_real_escape_string(trim($_REQUEST['trans_id']));
	
	
	$query="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."', debit = '".$amount."', description = '".$description."', on_project = '".$project_id."', payment_date = '".strtotime($_REQUEST['payment_date'])."',subdivision = '".$subdivision."',trans_type = '".$trans_type."', trans_type_name = '".$trans_type_name."', create_date = '".getTime()."'";
	$result= mysql_query($query) or die('error in query '.mysql_error().$query);
	
	$link_id_1 = mysql_insert_id();
	
	$query2="insert into payment_plan set trans_id = '".$trans_id."', project_id = '".$project_id."', credit = '".$amount."', description = '".$description."', on_customer = '".$cust_id."', payment_date = '".strtotime($_REQUEST['payment_date'])."',link_id = '".$link_id_1."',subdivision = '".$subdivision."',trans_type = '".$trans_type."', trans_type_name = '".$trans_type_name."', create_date = '".getTime()."'";
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
                
        }
        else
        {
            $new_file_name = "";        
        }
        
    }
    
    
    
	$msg = "Receive Goods successfully.";
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
	<h3>Receive Goods</h3>
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
	
	<div id="adddiv" >
	
	<form name="project_form" id="project_form" action="" method="post" enctype="multipart/form-data" >
		<table width="95%">
			<tr><td width="125px">Transaction ID</td>
			<td style="color:#FF0000; font-weight:bold;"><input type="hidden" id="trans_id"  name="trans_id" value="<?php echo $trans_id; ?>"/>&nbsp;<?php echo $trans_id; ?></td></tr>
			<tr><td width="125px">From</td>
			<td><input type="text" id="from"  name="from" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td >Project</td>
			<td><input type="text" id="project"  name="project" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td >Sub Division Name</td>
			<td>
			 <input type="text" id="subdivision"  name="subdivision" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span>
			
			</td></tr>
			
			
			
			<tr><td align="left" valign="top" >Amount</td>
			<td><input type="text"  name="amount" id="amount" value="" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			<tr><td align="left" valign="top" >Date</td>
			<script src="js/datetimepicker_css.js"></script>
			<td><input type="text"  name="payment_date" id="payment_date" value="<?php echo $_REQUEST['payment_date']; ?>" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('payment_date')" style="cursor:pointer"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			<tr><td valign="top" >Description</td>
			<td><textarea name="description" id="description" style="width:250px; height:100px;"></textarea>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td valign="top" >Attach File</td>
			<td><input type="file" name="attach_file" id="attach_file" value="" onChange="return hide_drag();" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td valign="top" >Attach File Name</td>
			<td><input type="text" id="attach_file_name"  name="attach_file_name" value="" autocomplete="off"/></td></tr>
			
			<tr><td valign="top" colspan="2" >
			<link rel="stylesheet" href="css/jquery-ui.css" />
  <script src="js/jquery-1.9.1.js"></script>
  <script src="js/jquery-ui.js"></script>
			<div id="drag_div" style="border:1px solid #CCCCCC; width:100%; background-color:#FFFFFF; border-radius:10px; ">
					
					
					<div style="height:20px; width:100%; background-color:#F9F9F9; border-top-left-radius:10px; border-top-right-radius:10px; color:#FF0000; text-align:left; float:right; " >&nbsp;&nbsp;&nbsp;&nbsp;<strong>Drag Files To Upload</strong>
							</div>
							<div id="dropbox" >
			<span class="message" >Drop Files here to upload.</span>
		</div>
		
		<!-- Including the HTML5 Uploader plugin -->
		<script src="js/jquery.filedrop.js"></script>
		
		<!-- The main script file -->
        <script src="js/script.js"></script>		
						</div>
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
			source: "supplier-ajax.php"
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