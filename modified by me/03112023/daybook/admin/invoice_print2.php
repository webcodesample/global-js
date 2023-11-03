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

if(mysql_real_escape_string(trim($_REQUEST['file_button'])) == "Submit")
{
	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
$query="select link_id from payment_plan where id = '".$attach_file_id."'";
        $result= mysql_query($query) or die('error in query '.mysql_error().$query);
        $data = mysql_fetch_array($result);
	
	
}

?>
<html>
<head>
<title>Admin Panel</title>

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
	<h3>Invoice Print</h3>
		<br>
<form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" >
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					
					<td width="50">
					&nbsp;&nbsp;Invoice No
					</td>
					<td width="70">
					
					<input type="text" name="invoice_no" id="invoice_no" value="<?php echo $_REQUEST['invoice_no']; ?>"  >
				 </td>
				
				 
					<td width="70">
					
					
				 </td>
				 
					<td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='bank-ledger.php?bank_id=<?php echo $_REQUEST['bank_id']; ?>';"  /></td>
					<td align="right" valign="top" ><input type="button" name="print_button" id="print_button" value="Print" class="button" onClick="return print_data();"  />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="bank.php" title="Back" ><input type="button" name="back_button" id="back_button" value="Back" class="button"  /></a></td>
					
					
				</tr>
			</table>
			<input type="hidden" name="search_action" id="search_action" value="Search"  />
			
			</form>
		
		<div id="ledger_data" style="display:none;" >
		<?php 
       //$attach_file_id ='1012';
       $query="select * from payment_plan where invoice_id = 1014";
        $result= mysql_query($query) ;
        $data = mysql_fetch_array($result);
    
        include_once("invoice-print.php");
         ?>
		<br>
		</div>
	</div>
<div class="clear"></div>
<?php
include_once("footer.php");
?>
</div>
<div id="attach_div" style="position:absolute;top:50%; left:40%; width:500px; height:150px; margin:-100px 0 0 -50px;z-index:100; display:none; background-color:#FFFFFF; border:8px solid #999999;" >
<form name="attach_form" id="attach_form" method="post" action="" onSubmit="return attach_validation();" enctype="multipart/form-data" >
<table cellpadding="0" cellspacing="0" border="1" width="100%" >
<tr><td valign="top" align="right" colspan="2" ><img src="images/close.gif" onClick="return close_div();" ></td></tr>
<tr><td valign="top" >Attach File</td>
			<td><input type="file" name="attach_file" id="attach_file" value="" ></td></tr>
			
			<tr><td valign="top" >Attach File Name</td>
			<td><input type="text" id="attach_file_name"  name="attach_file_name" value="" autocomplete="off"/></td></tr>
			
			<tr><td></td><td>
			<input type="submit" class="button" name="file_button" id="file_button" value="Submit" >
			</td></tr>
</table>
<input type="hidden" id="attach_file_id"  name="attach_file_id" value="" />
<input type="hidden" id="invoice_flag"  name="invoice_flag" value="" />
</form>
</div>
<div id="view_div" style="position:absolute;top:50%; left:40%; width:500px; min-height:250px; margin:-100px 0 0 -50px;z-index:100; display:none; background-color:#FFFFFF; border:8px solid #999999;" >

</div>
<form name="trans_form" id="trans_form" action="" method="post" >
		
		<input type="hidden" name="trans_id" id="trans_id" value="" >
		</form>
                
<form name="trans_form_1" id="trans_form_1" action="" method="post" >
        
        <input type="hidden" name="trans_id_1" id="trans_id_1" value="" >
        </form>
        <form name="invoice_form" id="invoice_form" action="" method="post" >
        
        <input type="hidden" name="invoice_id" id="invoice_id" value="" >
        <input type="hidden" name="trans_t_name" id="trans_t_name" value="" >
        </form>

</body>
</html>
<script>
function close_view()
{
	$('#view_div').hide("slow");
}
function view_file_function(id)
{
	
	$('#view_div').show("slow");
	$.ajax({
		url: "attach_file_ajax.php?id="+id,
		type: 'GET',
		dataType: 'html',
		beforeSend: function () {
			$('#view_div').html('Processing..................');
			
		},
		success: function (data, textStatus, xhr) {
			/*var arr = data.split("EXPLODE");
			$('#export_div').show();
			$('#cas_issued_div').html(arr[0]);		
			$('#student_query').val(arr[1]);
			$('#student_export').show();*/
			$('#view_div').html(data);		
			
			
		},
		error: function (xhr, textStatus, errorThrown) {
			$('#view_div').html(textStatus);
		}
	});
	
}
function attach_validation()
{
	if(document.getElementById("attach_file").value=="")
	{
	 alert("Please Select file");
	 document.getElementById("attach_file").focus();
	 return false;
	}
	else if(document.getElementById("attach_file_name").value=="")
	{
	 alert("Please enter attach file name ");
	 document.getElementById("attach_file_name").focus();
	 return false;
	} 
}
function attach_file_function(id)
{
	
	document.getElementById("attach_div").style.display="block";
	document.getElementById("attach_file_id").value=id;
	
}
function close_div()
{
	document.getElementById("attach_div").style.display="none";
}
$(function() {
	 
	$('#from_date').datepick({dateFormat: 'dd-mm-yyyy'});
	
});
$(function() {
	 
	$('#to_date').datepick({dateFormat: 'dd-mm-yyyy'});
	
});
function search_valid()
{
	if(document.getElementById("from_date").value=="")
	{
	 alert("Please enter from date");
	 document.getElementById("from_date").focus();
	 return false;
	}
	else if(document.getElementById("to_date").value=="")
	{
	 alert("Please enter to ");
	 document.getElementById("to_date").focus();
	 return false;
	} 
	
	
}

function invoice_attach_file_function(id)
{
    
    document.getElementById("attach_div").style.display="block";
    document.getElementById("attach_file_id").value=id;
    document.getElementById("invoice_flag").value="1";
    
}
function account_transaction(trans_id)
{
	if(confirm("Are you sure want to delete?!!!!!......"))
	{
		$("#trans_id").val(trans_id);
		$("#trans_form").submit();
		return true;
	}
}

function account_transaction_invoice_pay(invoice_id)
{
    if(confirm("Are you sure want to delete?!!!!!......"))
    { 
        $("#trans_t_name").val("instmulti_receive_payment");
        $("#invoice_id").val(invoice_id);
        $("#invoice_form").submit();
        return true;
    }
}

function account_transaction_1(trans_id_1)
{
    if(confirm("Are you sure want to delete?!!!!!......"))
    {
        $("#trans_id_1").val(trans_id_1);
        $("#trans_form_1").submit();
        return true;
    }
}
function print_data()
{
	printMe=window.open();
	printMe.document.write(document.getElementById("ledger_data").innerHTML);
	printMe.print();
	printMe.close();
}
</script>

