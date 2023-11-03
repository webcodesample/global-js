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
	$id_cust=$_REQUEST['id_cust'];
    $id_pro=$_REQUEST['id_pro'];
    //$subdivision=mysql_real_escape_string(trim($_REQUEST['subdivision']));
	//id_cust,id_pro
	
    $query2="update payment_plan set  project_id = '".$project_id."', credit = '".$amount."', description = '".$description."', on_customer = '".$cust_id."', payment_date = '".strtotime($_REQUEST['payment_date'])."',subdivision = '".$subdivision."', update_date = '".getTime()."' where  id = '".$id_pro."'";
    $result2= mysql_query($query2) or die('error in query '.mysql_error().$query2);
    //echo $query2;
    //echo "////////////////////";
	$trans_id = mysql_real_escape_string(trim($_REQUEST['trans_id']));
	$query="update payment_plan set cust_id = '".$cust_id."', debit = '".$amount."', description = '".$description."', on_project = '".$project_id."', payment_date = '".strtotime($_REQUEST['payment_date'])."',subdivision = '".$subdivision."', update_date = '".getTime()."' where  id = '".$id_cust."'";
	$result= mysql_query($query) or die('error in query '.mysql_error().$query);
	//echo $query;
    //exit;
    
	//$link_id_1 = mysql_insert_id();
	
	
    
    
    
    if($_FILES["attach_file"]["name"] != "")
    {
          
    $query3="insert into attach_file set attach_id = '".$id_cust."', link_id = '".$id_pro."',file_name = '".$new_file_name."'";
    $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
    $link_id_4 = mysql_insert_id();
  
        $attach_file_name=mysql_real_escape_string(trim($_REQUEST['attach_file_name']));
        $temp = explode(".", $_FILES["attach_file"]["name"]);
        $arr_size = count($temp);
        $extension = end($temp);
        $new_file_name = $attach_file_name.'_'.$link_id_4.'_'.date("d_M_Y").'.'.$extension;
        move_uploaded_file($_FILES["attach_file"]["tmp_name"],"transaction_files/" . $new_file_name);
        
          $query4="insert into attach_file set attach_id = '".$id_pro."', link_id = '".$id_cust."',old_id = '".$link_id_4."',file_name = '".$new_file_name."'";
    $result4= mysql_query($query4) or die('error in query '.mysql_error().$query4);
    $link_id_5 = mysql_insert_id();
    
    $query5_1="update attach_file set old_id = '".$link_id_5."',file_name = '".$new_file_name."' where id = '".$link_id_4."'";
    $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);
  
        
    }
    else
    {
        $files = glob("drag uploads/*.*");
        
        
        if(count($files) > 0)
        {
             $query3="insert into attach_file set attach_id = '".$id_cust."', link_id = '".$id_pro."',file_name = '".$new_file_name."'";
             $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
             $link_id_4 = mysql_insert_id();
    
             $new_file_name=mysql_real_escape_string(trim($_REQUEST['attach_file_name'])).'_'.$link_id_4.'_'.date("d_M_Y");
            foreach($files as $file)
            {
                $basename = basename($file);
                $ext = substr(strrchr($basename,'.'),1);
                rename ("$file", "transaction_files/$new_file_name.$ext");
                
            }
            
         $new_file_name = $new_file_name.'.'.$ext;    
          $query4="insert into attach_file set attach_id = '".$id_pro."', link_id = '".$id_cust."',old_id = '".$link_id_4."',file_name = '".$new_file_name."'";
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
    
    
    
    
    $trsns_pname_1 = $_REQUEST['trsns_pname'];
    
    
	$msg = "receive goods Update successfully.";
	$flag = 1;
   // header(supplier-payment.php);
   //project-ledger.php?project_id=<?php echo $_REQUEST['project_id']; 
     // echo "<script> location.href='project.php'; </script>";
      
    if($trsns_pname_1=="project-ledger")
    {
         $msg = " Update successfully.";
          $flag = 1;
   // header(supplier-payment.php);
      echo "<script> location.href='project-ledger.php?project_id=".$project_id."'; </script>";
      //window.history.back();
        
    }
    if($trsns_pname_1=="By subdivision")
    {
         $msg = " Update successfully.";
          $flag = 1;
   // header(supplier-payment.php);
      echo "<script>  location.href='subdivision.php'; </script>";
      //window.history.back();
        
    }
   
     // echo "<script> location.href='project-ledger.php?project_id=".$project_id."'; </script>";
	
}



    
$select_query = "select * from payment_plan where id=".$_REQUEST['id']." and trans_id=".$_REQUEST['trans_id']." and project_id = '".$_REQUEST['project_id']."'";
$select_result = mysql_query($select_query) or die('error in query select supplier query '.mysql_error().$select_query);
$select_data = mysql_fetch_array($select_result);
//echo "$select_query";
$on_cus = $select_data['on_customer'];

$select_query_pro = "select * from payment_plan where trans_id=".$_REQUEST['trans_id']." and cust_id=".$on_cus." and on_project = '".$_REQUEST['project_id']."'";
$select_result_pro = mysql_query($select_query_pro) or die('error in query select supplier query '.mysql_error().$select_query_pro);
$select_data_pro = mysql_fetch_array($select_result_pro);
//echo "$select_query_pro";

if($_REQUEST['back']!="")
   {
       $trsns_pname = "By subdivision";
   }
    else{
        $trsns_pname = "project-ledger";
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
	
	<div id="adddiv" >
    <div style="width: 95%;text-align: right; "><a href="project-ledger.php?project_id=<?php echo $_REQUEST['project_id']; ?>" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a></div>
    <h3>Project - <?php  echo get_field_value("name","project","id",$_REQUEST['project_id']);?> Ledger 
</h3>
    <h2>Update Receive Goods ( <?php echo get_field_value("full_name","customer","cust_id",$select_data['on_customer']); ?>
<font size="3">&nbsp;(<?php echo date("d-m-Y",$select_data['payment_date']); ?>)</font>)</h2>
    
	
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
    
	<form name="project_form" id="project_form" action="" method="post" enctype="multipart/form-data" >
    <link rel="stylesheet" href="css/jquery-ui.css" />
  <script src="js/jquery-1.9.1.js"></script>
  <script src="js/jquery-ui.js"></script>
  
    <input type="hidden" id="id_pro" name="id_pro" value="<?php echo $select_data['id']; ?>">
    <input type="hidden" id="id_cust" name="id_cust" value="<?php echo $select_data_pro['id']; ?>">
     <input type="hidden" id="trsns_pname" name="trsns_pname" value="<?php echo $trsns_pname; ?>">
		<table width="95%" >
			<tr><td width="100px">Transaction ID</td>
			<td style="color:#FF0000; font-weight:bold;"><input type="hidden" id="trans_id"  name="trans_id" value="<?php echo $select_data['trans_id']; ?>"/>&nbsp;<?php echo $select_data['trans_id']; ?></td>
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
            <tr><td width="125px">Supplier</td>
            <?php
    //$row['full_name'].' - '.$row['cust_id'];
    $sql_cus     = "select cust_id,full_name from `customer` where cust_id=".$select_data['on_customer']." and type = 'supplier'";
    $query_cus     = mysql_query($sql_cus);
    $select_cus = mysql_fetch_array($query_cus);
    
?>
            <td><input type="text" id="from"  name="from" value="<?php echo $select_cus['full_name'].' - '.$select_cus['cust_id']; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			<tr><td >Project</td>
            <?php
                $project_nm = get_field_value("name","project","id",$select_data['project_id']);
                $subdivision_nm = get_field_value("name","subdivision","id",$select_data['subdivision']);
            ?>
            <td><input type="text" id="project"  name="project" value="<?php echo $project_nm; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            			
						
			<tr><td >Sub Division Name</td>
			<td>
<!--
			<select name="subdivision1" id="subdivision1"  style="width:140px; height:20px;"  >
                     <option value="">-- Please Select --</option> 
					 <?php 
			 $subdivision_query = "select * from subdivision ORDER BY name ASC";
					 $subdivision_result = mysql_query($subdivision_query) or die("error in subdivision query ".mysql_error());
					 while($subdivision_data = mysql_fetch_array($subdivision_result))
					 {
					 	?>
						<option value="<?php echo $subdivision_data['id']; ?>"  selected="selected"><?php echo $subdivision_data['name']; ?></option> 
						<?php
					 }
					 ?>
					    
                        
			</select>
            -->
            
            <input type="text" id="subdivision"  name="subdivision" value="<?php echo $subdivision_nm; ?>" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span>
			
			</td></tr>
			
			
			
			<tr><td align="left" valign="top" >Amount</td>
			<td><input type="text"  name="amount" id="amount" value="<?php echo $select_data['credit']; ?>" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			<tr><td align="left" valign="top" >Date</td>
			<script src="js/datetimepicker_css.js"></script>
			<td><input type="text"  name="payment_date" id="payment_date" value="<?php echo date("d-m-Y",$select_data['payment_date']); ?>" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('payment_date')" style="cursor:pointer"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			<tr><td valign="top" >Description</td>
			<td><textarea  name="description" id="description" style="width:250px; height:100px;" ><?php echo $select_data['description']; ?></textarea>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			<tr><td valign="top" >Attach File</td>
            <td><input type="file" name="attach_file" id="attach_file" value="" onChange="return hide_drag();" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <tr><td valign="top" >Attach File Name</td>
            <td><input type="text" id="attach_file_name"  name="attach_file_name" value="" autocomplete="off"/></td></tr>
            
            <tr><td valign="top" colspan="3" >
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
			
			var text = '<table cellpadding="10" cellspacing="0" border="0" width="95%"><tr><td colspan="2" >Update Supplier Ledger</td></tr><tr><td width="125px">Transaction ID</td><td>'+$("#trans_id").val()+'</td></tr><tr><td width="125px">From</td><td>'+$("#from").val()+'</td></tr><tr><td >Project</td><td>'+$("#project").val()+'</td></tr><tr><td >Sub Division Name</td><td>'+$("#subdivision").val()+'</td></tr><tr><td>Amount</td><td>Rs. &nbsp;'+$("#amount").val()+'</td></tr><tr><td>Date</td><td>'+$("#payment_date").val()+'</td></tr><tr><td >Description</td><td>'+$("#description").val()+'</td></tr></table>';
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
			
			var text = '<table cellpadding="10" cellspacing="0" border="0" width="95%"><tr><td colspan="2" >Update Supplier Ledger</td></tr><tr><td width="125px">Transaction ID</td><td><?php echo $_REQUEST['trans_id']; ?></td></tr><tr><td width="125px">From</td><td><?php echo $_REQUEST['from']; ?></td></tr><tr><td >Project</td><td><?php echo $_REQUEST['project']; ?></td></tr><tr><td>Amount</td><td>Rs. &nbsp;<?php echo $_REQUEST['amount']; ?></td></tr><tr><td>Date</td><td><?php echo $_REQUEST['payment_date']; ?></td></tr><tr><td >Description</td><td><?php echo $_REQUEST['description']; ?></td></tr></table>';
			printMe=window.open();
			printMe.document.write(text);
			printMe.print();
			printMe.close();
						
			
		}
	</script>
	<?php
}

?>