<?php session_start();
include_once("set_con.php");
require('library/php-excel-reader/excel_reader2.php');
require('library/SpreadsheetReader.php');

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

if(trim($_REQUEST['action_perform']) == "add_bank")
{
    /*echo '<pre>';
    print_r($_REQUEST);
    exit;*/
    $fileMimes = array(
       // 'text/x-comma-separated-values',
       // 'text/comma-separated-values',
       //'application/octet-stream',
        'application/vnd.ms-excel',
       // 'application/x-csv',
       // 'text/x-csv',
       // 'text/csv',
       // 'application/csv',
       'text/xls','text/xlsx',
      // 'application/excel',
      'application/xlsx',
      'application/XLSX',
      
        'text/Xls','text/Xlsx'
       //'application/vnd.msexcel',
       // 'text/plain'
       
    );
    $mimes = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.oasis.opendocument.spreadsheet'];
 //////* if(in_array($_FILES["upload_attach_file"]["type"],$fileMimes)){

    $file_name=mysql_real_escape_string(trim($_REQUEST['file_name']));
    $quuerrr="select * from file_import where file_name = '".$file_name."'   ";
    $sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
    $no=mysql_num_rows($sql);
    if($no > 0)
    {
        $check_data=mysql_fetch_array($sql);
        $error_msg = "File name  already exist try another";
    }
    else
    { 
        $query="insert into file_import set file_name = '".$file_name."',   date = '".strtotime($_REQUEST['file_date'])."', user = '".$_SESSION['userId']."',create_date = '".getTime()."'";
        $result= mysql_query($query) or die('error in query bank query '.mysql_error().$query);
        $file_import_id = mysql_insert_id();
       if($_FILES["upload_attach_file"]["name"] != "")
       {
            $attach_file_name=$file_name;
            $temp = explode(".", $_FILES["upload_attach_file"]["name"]);
            $arr_size = count($temp);
            $extension = end($temp);
            $new_file_name = $attach_file_name.'_'.$file_import_id.'_'.date("d_M_Y").'.'.$extension;
            move_uploaded_file($_FILES["upload_attach_file"]["tmp_name"],"import_files/" . $new_file_name);
            $query1_1="update file_import set file_path = '".$new_file_name."' where id = '".$file_import_id."'";
            $result1_1= mysql_query($query1_1) or die('error in query '.mysql_error().$query1_1);
        }
        $file = $_FILES['upload_attach_file']['tmp_name'];
     
        /******   File Data Inserted work start     ********/
//        $uploadFilePath = 'import_files/'.basename($_FILES['upload_attach_file']['name']).'_'.$bank_id.'_'.date("d_M_Y");
    
    $uploadFilePath = 'import_files/'.$new_file_name;
    //move_uploaded_file($_FILES['file']['tmp_name'], $uploadFilePath);


    $Reader = new SpreadsheetReader($uploadFilePath);
    $totalSheet = count($Reader->sheets());
$nm=1;
     for($i=0;$i<$totalSheet;$i++)
     {
         $Reader->ChangeSheet($i);
        $im=1;
      foreach ($Reader as $Row)
      { 
        if($im==1){
        }else{
            $from_name = isset($Row[0]) ? $Row[0] : '';
            $to_name = isset($Row[1]) ? $Row[1] : '';
            $project = isset($Row[2]) ? $Row[2] : '';
            $amount = isset($Row[3]) ? $Row[3] : '';
            $description = isset($Row[4]) ? $Row[4] : '';
            $payment_date = isset($Row[5]) ? $Row[5] : '';
            $payment_type = isset($Row[6]) ? $Row[6] : '';
            //$ = isset($Row[2]) ? $Row[2] : '';
            
            $query="insert into tbl_info set file_name='".$new_file_name."',file_import_id='".$file_import_id."',file_series='".$nm."', from_name = '".$from_name."',  to_name = '".$to_name."',project='".$project."',amount ='".$amount."',description ='".$description."',payment_date ='".strtotime($payment_date)."',payment_type ='".$payment_type."',create_date = '".getTime()."'";
      
      //  $query = "insert into tbl_info(name,description) values('".$title."','".$description."')";
        $result2= mysql_query($query) or die('error in query '.mysql_error().$query);
         $nm++;  
      }
      $im++;
       }
    }


        /*******  file data Inserted work End         *********/
        $msg = "Data Uploaded successfully.";
        header("Location:import_file.php?msg=Data Uploaded successfully");

        
    }
/*
	
}else { 
    die("<br/>Sorry, File type is not allowed. Only Excel file."); 
  }
*/	    
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
        Add New Import File </h4>
  </td>
        <td width="" style="float:right;">
        <a href="import_file.php" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>  </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>

<?php include_once("main_body_open.php") ?>

<table width="100%" style="padding:30px;" >
  <tr><td>
  <table width="100%" class="tbl_border" >
    <tr><td>  	
	<?php if($error_msg != "") { ?>
	<div class="gagal">
		
		<?php echo $error_msg; ?>
		</div>
	<?php } ?>
	<form name="bank_form" id="bank_form" action="" method="post"  enctype="multipart/form-data">
    <table width="95%">
            <tr><td width="125px">File Name</td>
            <td><input type="text" name="file_name"  style="width:250px;" id="file_name" value="<?php echo $_REQUEST['file_name']; ?>" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <tr><td > Date</td>
            <td>
                <input type="date" max="<?= date('Y-m-d',time())?>">
            </td></tr>
           
            <tr><td >File Upload</td>
            <td>
            <input type="file" name="upload_attach_file"  style="width:250px;" id="upload_attach_file" value="<?php echo $_REQUEST['upload_attach_file']; ?>" >
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
           
            <tr><td></td><td>
            <input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
            </td></tr>
            <tr><td colspan="2"><br><h3></h3><br><br><br></td></tr>
        </table>
        <input type="hidden" name="action_perform" id="action_perform" value="" >
        <input type="hidden" name="del_id" id="del_id" value="" >
        </form>		
		
    </td></tr></table></td></tr></table>
        <?php include_once("main_body_close.php") ?>
        <?php include_once ("footer.php"); ?>

</body>
</html>
<script>


function validation()
{
    //file_date,upload_attach_file
    if($("#file_name").val() == "")
    {
        alert("Please enter File Name");
        $("#file_name").focus();
        return false;
    }else if($("#file_date").val() == "")
    {
        alert("Please enter Date");
        $("#file_date").focus();
        return false;
    } else if($("#upload_attach_file").val() == "")
    {
        alert("Please upload File");
        $("#upload_attach_file").focus();
        return false;
    }  
    else
    {
        $("#action_perform").val("add_bank");
        $("#bank_form").submit();
        return true;
    }
    
}

</script>