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

if(trim($_REQUEST['action_perform']) == "edit_invoice_issuer")
{
    $issuer_id = mysql_real_escape_string(trim($_REQUEST['issuer_id']));
	$issuer_name=mysql_real_escape_string(trim($_REQUEST['issuer_name']));
    $display_name=mysql_real_escape_string(trim($_REQUEST['display_name']));
    
    $company_name = mysql_real_escape_string(trim($_REQUEST['company_name']));
    $reg_no=mysql_real_escape_string(trim($_REQUEST['reg_no']));
    $vat_no=mysql_real_escape_string(trim($_REQUEST['vat_no']));
    
    
    $mobile=mysql_real_escape_string(trim($_REQUEST['mobile']));
    $email=mysql_real_escape_string(trim($_REQUEST['email']));
    $address=mysql_real_escape_string(trim($_REQUEST['address']));
    
    $gst_no=mysql_real_escape_string(trim($_REQUEST['gst_no']));
    $pan_no=mysql_real_escape_string(trim($_REQUEST['pan_no']));
     $cin_no=mysql_real_escape_string(trim($_REQUEST['cin_no']));

    //code by amit for logo
    $filename=$_FILES['invoice_issuer_logo']['tmp_name'];
    $imgData = mysql_escape_string(file_get_contents($filename));
    
    $query="update invoice_issuer set issuer_name = '".$issuer_name."', display_name = '".$display_name."', mobile = '".$mobile."', email = '".$email."', address = '".$address."', company_name = '".$company_name."', reg_no = '".$reg_no."', vat_no = '".$vat_no."', gst_no = '".$gst_no."', cin_no = '".$cin_no."', pan_no = '".$pan_no."', logo = '".$imgData."' where id='".$issuer_id."'"; 
    $result= mysql_query($query) or die('error in query '.mysql_error().$query.'<br>');
    $msg = "Invoice Issuer update successfully.";
	header("Location:invoice-issuer.php?msg=invoice_issuer Updated successfully");
}

$select_query = "select * from invoice_issuer where id = '".$_REQUEST['id']."'";
$select_result = mysql_query($select_query) or die('error in query select invoice_issuer query '.mysql_error().$select_query);
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
        Edit invoice issuer </h4>
  </td>
        <td width="" style="float:right;">
            <a href="invoice-issuer.php" title="Back" style=""><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
            </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
  <?php include_once("main_body_open.php") ?>
	
	
	<?php if($error_msg != "") { ?>
	<div class="gagal">
		
		<?php echo $error_msg; ?>
		</div>
	<?php } ?>
	
	<form name="invoice_issuer_form" id="invoice_issuer_form" action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="issuer_id" id="issuer_id" value="<?php echo $_REQUEST['id']; ?>">
		<table width="100%" style="padding:20px;">
            <tr>
                <td valign="top">

			<table width="100%" class="tbl_border">
            <tr>
            <td colspan="2"><h4 class="u-text-2 u-text-palette-1-base " style="padding:0px; margin:0px;">Invoice Issuer : <?php echo $select_data['company_name']; ?></h4></td></tr>

            <tr>
            <td valign="top">
                <table width="100%">
                <tr><td valign="top" >Company Name</td>
            <td><input type="text" id="company_name"  name="company_name" value="<?php echo $select_data['company_name']; ?>"/></td></tr>
            <tr><td width="200px">Short Name</td>
            <td><input type="text" id="issuer_name"  name="issuer_name" value="<?php echo $select_data['issuer_name']; ?>"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td >Email</td>
            <td><input type="text" id="email"  name="email" value="<?php echo $select_data['email']; ?>"/></td></tr>
            <tr><td align="left" valign="top" >GSTIN</td>
            <td><input type="text"  name="gst_no" id="gst_no" value="<?php echo $select_data['gst_no']; ?>" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td >PAN</td>
            <td><input type="text" id="pan_no"  name="pan_no" value="<?php echo $select_data['pan_no']; ?>"/></td></tr>
            <tr><td >CIN</td>
            <td><input type="text" id="cin_no" name="cin_no" value="<?php echo $select_data['cin_no']; ?>"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            
                </table>
            </td>
            <td valign="top">
                <table width="100%">
                <tr><td width="200px">Company Reg No.</td>
            <td><input type="text" id="reg_no"  name="reg_no" value="<?php echo $select_data['reg_no']; ?>"/></td></tr>
            <tr><td >Contact Person</td>
            <td><input type="text" id="display_name"  name="display_name" value="<?php echo $select_data['display_name']; ?>"/></td></tr>
            <tr><td >Mobile</td>
            <td><input type="text" id="mobile"  name="mobile" value="<?php echo $select_data['mobile']; ?>"/></td></tr>
            <tr>
            <td valign="top">Address</td>
            <td><textarea name="address" id="address" style="width:250px; height:100px;"><?php echo $select_data['address']; ?></textarea>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td>
            </tr>
            <tr>
            <td valign="top">Logo</td>
            <td>
            <img id="logo_display" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($select_data['logo']); ?>" style="border-radius:50%; hieght:120px; width:120px;" onClick="document.getElementById('invoice_issuer_logo').click();"/> 
            <br><input type="file" name="invoice_issuer_logo" id="invoice_issuer_logo" accept="image/*" style="display:none;">
            <div id="res"></div>
            </td>
            </tr>
            
            </table>
            </td>
            </tr>    
           
            <tr><td colspan="2" align="center">
            <br><br><br>
            <input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
            </td></tr>
        </table>
            </td></tr>
            	</table>
		
		<input type="hidden" name="action_perform" id="action_perform" value="" >
		<input type="hidden" name="count" id="count" value="<?php echo $i; ?>"  />	
		
		</form>
		
		
		
		
<?php include_once("main_body_close.php") ?>
<?php include_once ("footer.php"); ?>
<?php
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
    $url = "https://";   
else  
    $url = "http://";

// Append the host(domain name, ip) to the URL.   
$url.= $_SERVER['HTTP_HOST'];    
// Append the requested resource location to the URL   
$url.= $_SERVER['REQUEST_URI'];

$url_arr = explode("/",$url);

array_pop($url_arr);
array_pop($url_arr);
$url = implode("/",$url_arr)."/dll.txt";
?>
        
</body>
</html>
<script type="text/javascript">
/*document.getElementById("invoice_issuer_logo").onchange = function()
{
    alert($('#invoice_issuer_logo').prop("files")[1]);
    alert($('#invoice_issuer_logo').val());
        $.ajax({
        type: 'POST',
        url: 'http://www.bordersandgates.com/testndb/daybook/admin/logo-ajax.php',
        data: { file: $('#invoice_issuer_logo').val(),
        'site_url' : <?php echo json_encode($url); ?>,
    'issuer_id' : <?php echo json_encode($_REQUEST['id']); ?>},
        cache: false,
        processData: false,
        contentType: false,
        success: function (result) {
            document.getElementById("logo_display").src = "data:image/jpg;charset=utf8;base64,"+result;
            //alert(result);
        }
    });
}*/
  
function add_div()
{
	$("#adddiv").toggle("slow");
}

function validation()
{   
    if($("#issuer_name").val() == "")
    {
        alert("Please enter Invoice Issuer name.");
        $("#issuer_name").focus();
        return false;
    }
	else
	{
		$("#action_perform").val("edit_invoice_issuer");
		$("#invoice_issuer_form").submit();
		return true;
	}	
}
 

</script>