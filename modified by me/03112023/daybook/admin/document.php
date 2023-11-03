<?php session_start();
include_once("../connection.php");

$mime_types = array(
'txt' => 'text/plain',
'htm' => 'text/html',
'html' => 'text/html',
'php' => 'text/html',
'css' => 'text/css',
'js' => 'application/javascript',
'json' => 'application/json',
'xml' => 'application/xml',
'swf' => 'application/x-shockwave-flash',
'flv' => 'video/x-flv',

// images
'png' => 'image/png',
'jpe' => 'image/jpeg',
'jpeg' => 'image/jpeg',
'jpg' => 'image/jpeg',
'gif' => 'image/gif',
'bmp' => 'image/bmp',
'ico' => 'image/vnd.microsoft.icon',
'tiff' => 'image/tiff',
'tif' => 'image/tiff',
'svg' => 'image/svg+xml',
'svgz' => 'image/svg+xml',

// archives
'zip' => 'application/zip',
'rar' => 'application/x-rar-compressed',
'exe' => 'application/x-msdownload',
'msi' => 'application/x-msdownload',
'cab' => 'application/vnd.ms-cab-compressed',

// audio/video
'mp3' => 'audio/mpeg',
'qt' => 'video/quicktime',
'mov' => 'video/quicktime',

// adobe
'pdf' => 'application/pdf',
'psd' => 'image/vnd.adobe.photoshop',
'ai' => 'application/postscript',
'eps' => 'application/postscript',
'ps' => 'application/postscript',

// ms office
'doc' => 'application/msword',
'rtf' => 'application/rtf',
'xls' => 'application/vnd.ms-excel',
'ppt' => 'application/vnd.ms-powerpoint',

// open office
'odt' => 'application/vnd.oasis.opendocument.text',
'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
);


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
if($_REQUEST['search_type'] == "")
{
	$_REQUEST['search_type'] = "All";
}

/*  Start E-Mail Code  */

if($_REQUEST['email_action'] != "")
{

	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
	$from = "mahendra@maruti-technology.com";
	$to = mysql_real_escape_string(trim($_REQUEST['to']));
	$subject = mysql_real_escape_string(trim($_REQUEST['subject']));
	$message = mysql_real_escape_string(trim($_REQUEST['content']));
	$headers = "From: \"\" <".$from.">\n"; 
	$headers.= "To: \"\" <".$to.">\n";
	$semi_rand = md5(time()); 
	$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

    $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";
	
	$message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n"; 
	$message .= "--{$mime_boundary}\n";
	
     // now we'll process our uploaded files
	$email = $_REQUEST['email'];
	if($email != "")
	{
		$size = count($email);
		
		for($i=0;$i<$size;$i++)
		{
			if(trim($email[$i]) != "")
			{
				$file = fopen($email[$i],'rb');
				$data = fread($file,filesize($email[$i]));
				fclose($file);
				$data = chunk_split(base64_encode($data));
				
				$ext = strtolower(array_pop(explode(".",basename($email[$i]))));
				if (array_key_exists($ext, $mime_types))
				{
					$mime_type =  $mime_types[$ext];
				}
				else
				{
					$mime_type = "application/octet-stream";
				}
				$basename = basename($email[$i]);
				$message .= "Content-Type: {\"$mime_type\"};\n" . " name=\"$basename\"\n" . 
	"Content-Disposition: attachment;\n" . " filename=\"$basename\"\n" . 
	"Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
	$message .= "--{$mime_boundary}\n";
			}
		}
	}
	 
	if(@mail($to,$subject,$message,$headers))
	{
		$files = $_REQUEST['file_string'];
		if(strstr($files,","))
		{
			$files_arr = explode(",",$files);
			$files_size = count($files_arr);
			for($i=0;$i<$files_size;$i++)
			{
				if(trim($files_arr[$i]) != "")
				{
					$insert_query="insert into email_detail set email_to = '".$to."', document_file = '".$files_arr[$i]."', send_time = '".getTime()."' ";
					$insert_result= mysql_query($insert_query) or die('error in query '.mysql_error().$insert_query);
					
				}
			}
		}
		else
		{
			$insert_query="insert into email_detail set email_to = '".$to."', document_file = '".$files."', send_time = '".getTime()."' ";
			$insert_result= mysql_query($insert_query) or die('error in query '.mysql_error().$insert_query);
		}
		
		$msg = "E-Mail Has Been Send Successfully";
	}
	else
	{
		$error_msg = "Error in sending E-Mail";
	}
	
}


/*  End E-Mail code */

/*     Create  Account   */


if(trim($_REQUEST['action_perform']) == "add_document")
{
	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
	$document_type=mysql_real_escape_string(trim($_REQUEST['document_type']));
	$document_file_name=mysql_real_escape_string(trim($_REQUEST['document_file_name']));
	$temp = explode(".", $_FILES["document_file"]["name"]);
	$arr_size = count($temp);
	$extension = end($temp);
	$new_file_name = $document_file_name.'_'.date("d_M_Y").'.'.$extension;
	
	$quuerrr="select id from document where category = '".$document_type."' and name='".$new_file_name."' ";
	
	$sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
	$no=mysql_num_rows($sql);
	if($no > 0)
	{
		
		$error_msg = "document file name already exist try another";
		
	}
	else
	{
		
		if(move_uploaded_file($_FILES["document_file"]["tmp_name"],"document/" . $new_file_name))
		{
			$query="insert into document set category = '".$document_type."', name = '".$new_file_name."' ";
			$result= mysql_query($query) or die('error in query '.mysql_error().$query);
			$msg = "Document added successfully.";
		}
		else
		{
			$error_msg = "Error in file upload.";
		}
	}
	
}

/*     Deletion  Account   */

if($_REQUEST['action_perform'] == "delete_document")
{
	$del_id = $_REQUEST['del_id'];
	
	$quuerrr="select name from document where id = '".$del_id."' ";
	$sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
	$data=mysql_fetch_array($sql);
	unlink("document/".$data['name']);
	
	
	$del_query = "delete from document where id = '".$del_id."'";
	$del_result = mysql_query($del_query) or die("error in delete query ".mysql_error());
	
	$msg = "document deleted successfully.";
	
}
if(mysql_real_escape_string(trim($_REQUEST['search_type'])) != "All")
{

	if(mysql_real_escape_string(trim($_REQUEST['search_text'])) != "")
	{
		$query = "select * from document where category = '".$_REQUEST['search_type']."' and name LIKE '%".mysql_real_escape_string(trim($_REQUEST['search_text']))."%' ORDER BY name ASC";
	}
	else
	{
		$query = "select * from document where category = '".$_REQUEST['search_type']."' ORDER BY name ASC";
	}
	$result = mysql_query($query) or die('error in query select document query '.mysql_error().$query);
	$total_row = mysql_num_rows($result);
}
else
{
	if(mysql_real_escape_string(trim($_REQUEST['search_text'])) != "")
	{
		$query = "select * from document where name LIKE '%".mysql_real_escape_string(trim($_REQUEST['search_text']))."%' ORDER BY name ASC";
	}
	else
	{
		$query = "select * from document ORDER BY name ASC";
	}
	$result = mysql_query($query) or die('error in query '.mysql_error().$query);
	$total_row = mysql_num_rows($result);
}

$page = $_REQUEST['page'];
if ($page < 1) $page = 1;
$numberOfPages = numberofpages();
$resultsPerPage = resultperpage();
$startResults = ($page - 1) * $resultsPerPage;
$totalPages = ceil($total_row / $resultsPerPage);


if(mysql_real_escape_string(trim($_REQUEST['search_type'])) != "All")
{
	
	$column = $_REQUEST['search_type'];
	
	if(mysql_real_escape_string(trim($_REQUEST['search_text'])) != "")
	{
		$select_query = "select * from document where category = '".$_REQUEST['search_type']."' and name LIKE '%".mysql_real_escape_string(trim($_REQUEST['search_text']))."%' ORDER BY name ASC LIMIT $startResults, $resultsPerPage";
	}
	else
	{
		$select_query = "select * from document where category = '".$_REQUEST['search_type']."' ORDER BY name ASC LIMIT $startResults, $resultsPerPage";
	}
	$select_result = mysql_query($select_query) or die('error in query select document query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
}
else
{
	if(mysql_real_escape_string(trim($_REQUEST['search_text'])) != "")
	{
		$select_query = "select * from document where name LIKE '%".mysql_real_escape_string(trim($_REQUEST['search_text']))."%' ORDER BY name ASC LIMIT $startResults, $resultsPerPage";
	}
	else
	{
		$select_query = "select * from document ORDER BY name ASC LIMIT $startResults, $resultsPerPage";
	}
	$select_result = mysql_query($select_query) or die('error in query select document query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
}

$halfPages = floor($numberOfPages / 2);
$range = array('start' => 1, 'end' => $totalPages);
$isEven = ($numberOfPages % 2 == 0);
$atRangeEnd = $totalPages - $halfPages;

if($isEven) $atRangeEnd++;

if($totalPages > $numberOfPages)
{
	if($page <= $halfPages)
		$range['end'] = $numberOfPages;
	elseif ($page >= $atRangeEnd)
		$range['start'] = $totalPages - $numberOfPages + 1;
	else
	{
		$range['start'] = $page - $halfPages;
		$range['end'] = $page + $halfPages;
		if($isEven) $range['end']--;
	}
}



?>
<!DOCTYPE html>
<?php include_once ("top_header1.php"); ?>   
<script src="js/datetimepicker_css.js"></script>
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script>
function send_email()
{
	
	var count = document.getElementById("count").value;
	var check = 0;
	var email_string = "";
	for(var i=1;i<count;i++)
	{
		if(document.getElementById("email_"+i).checked == true)
		{
			
			var path = document.getElementById("email_"+i).value;
			var b = path.replace(/^.*[\/\\]/g, '');
			check = 1;
			//email_string+=b+",";
			email_string+='<div id="file_div_'+i+'" style="padding:1px; background-color:#E6E6F2; display:block; width:400px; height:25px; border:1px solid #666666;"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">'+b+'</td><td width="15" align="right" valign="top"><img src="images/close.gif" width="15" onClick="return close_file('+i+');"  ></td></tr></table></div>';
			
		}
		
	}
	if(check == 0)
	{
		alert("Check atleast one letter checkbox.");
	}
	else
	{
		
		$('#view_by_day_div').show("slow");
		var strLen = email_string.length;
		email_string = email_string.slice(0,strLen-1);
		document.getElementById("attachment_files").innerHTML = email_string;
		
	}
	
}
function close_div()
{
	$('#view_by_day_div').hide("slow");
}
function final_send_email()
{
	var count = document.getElementById("count").value;
	var check = 0;
	var email_string = "";
	for(var i=1;i<count;i++)
	{
		if(document.getElementById("email_"+i).checked == true)
		{
			
			var path = document.getElementById("email_"+i).value;
			var b = path.replace(/^.*[\/\\]/g, '');
			check = 1;
			email_string+=b+",";
			
			
		}
		
	}
	if(check == 0)
	{
		alert("Please Attach Atleast One File");
	}
	else
	{
		
		var strLen = email_string.length;
		email_string = email_string.slice(0,strLen-1);
		document.getElementById("file_string").value = email_string;
		$('#view_by_day_div').hide("slow");
		document.getElementById("email_action").value="Sent_To_All";
		document.edit_form.submit();
		
	}
	
}
function close_file(i)
{
	$('#file_div_'+i).hide("slow");
	document.getElementById("email_"+i).checked = false;
}
</script>
<body  data-home-page-title="" class="u-body u-xl-mode" data-lang="en">
  <?php include_once ("top_header2.php"); ?> 
  <?php include_once ("top_menu.php"); ?>
  <?php include_once("main_heading_open.php") ?>
  
	<table style="width:100%;" border="0" style="padding:0px 0px; margin-top:-10px;">
    <tr>
        <td style="align:top;" align="left">
        <h4 class="u-text-2 u-text-palette-1-base " style="padding:0px; margin:0px;">
        Document List</h4>
  </td>
        <td width="" style="float:right;">
		<input type="button" name="add_button" id="add_button" value="" class="button_add" onClick="search_display();"  />
        <input type="button" id="search" value="" class="button_search1" onClick="search_display();" >
         </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
<!-------------->
<?php include_once("main_search_open.php") ?>
  <input type="hidden" name="search_check_val" id="search_check_val" value="0" >
 <table width="100%">
<tr><td><b>Create New Document :</b></td></tr>	
 <tr><td> 
  <!--<div id="adddiv" style="display:<?php if($error_msg != "") { ?>block<?php } else { ?>none<?php } ?>;">-->
  <div id="adddiv" >

	
	<form name="document_form" id="document_form" action="" method="post" enctype="multipart/form-data" >
		<table width="95%">
			<tr><td width="100px" valign="top">Document Type</td>
			<td valign="top">
			<select id="document_type"  name="document_type" >
			<option value="" >-- Please Select --</option>
			<?php 
			$category_query = "select * from document_category ORDER BY category ASC";
			$category_result = mysql_query($category_query) or die('error in query select document query '.mysql_error().$category_query);
			while($category_data = mysql_fetch_array($category_result))
			{
			?>
			<option value="<?php echo $category_data['id']; ?>" ><?php echo $category_data['category']; ?></option>
			
			<?php
			}
			?>
			</select>
			<span style="color:#FF0000; font-weight:bold;"  >*</span></td><td width="125px" valign="top">Document File Name</td>
			<td valign="top"><input type="text" id="document_file_name"  name="document_file_name" value="" autocomplete="off"/><span style="color:#FF0000; font-weight:bold;"  >*</span></td>
			<td valign="top" width="100px">Document File</td>
			<td valign="top"><input type="file" id="document_file"  name="document_file" value=""/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td>
			<td valign="top"><input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();"></td></tr>
		</table>
		<input type="hidden" name="action_perform" id="action_perform" value="" >
		<input type="hidden" name="del_id" id="del_id" value="" >
		
		</form>
		
		</div>
		<tr><td><hr></td></tr>
		</td></tr><tr><td>
		<form name="search_form" id="search_form" action="" method="post" enctype="multipart/form-data" >
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td width="180" align="left" valign="top"><input type="text" name="search_text" id="search_text" value="<?php echo mysql_real_escape_string(trim($_REQUEST['search_text'])); ?>" autocomplete="off" /></td>
					<td width="150" align="left" valign="top">
					
					<select id="search_type"  name="search_type" style="width:200px;"  >
					<option value="All" >-- ALL --</option>
					<?php 
					$category_query = "select * from document_category ORDER BY category ASC";
					$category_result = mysql_query($category_query) or die('error in query select document query '.mysql_error().$category_query);
					while($category_data = mysql_fetch_array($category_result))
					{
					?>
					<option value="<?php echo $category_data['id']; ?>" <?php if($category_data['id'] == $_REQUEST['search_type']) { ?> selected="selected" <?php } ?> ><?php echo $category_data['category']; ?></option>
					
					<?php
					}
					?>
					</select>
						
				  </td>
					<td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;
					<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='document.php';"  />
					&nbsp;&nbsp;<input type="button" name="email_button" id="email_button" value="E-Mail" class="button" onClick="return send_email();"    /></td>
					
					
				</tr>
			</table>
			<input type="hidden" name="page" id="page" value=""  />
			</form>	
	</td></tr>
		</table>
  <?php include_once("main_search_close.php") ?>
 <!-------------->

<?php include_once("main_body_open.php") ?>

	<?php if($msg != "") { ?>
	<div class="sukses">
		<?php echo $msg; ?>
		</div>
	<?php } else if($error_msg != "") { ?>
	<div class="gagal">
		
		<?php echo $error_msg; ?>
		</div>
	<?php } ?>
		
		
		<form name="edit_form" id="edit_form" action="" method="post" enctype="multipart/form-data" >
		<table class="data">
			<tr class="data">
				<th class="data" width="30px">S.No.</th>
				<th class="data" width="30px"></th>
				<th class="data">document</th>
				<th class="data" width="75px">Action</th>
			</tr>
			<?php
			if($select_total > 0)
			{
				$i=1;
				while($select_data = mysql_fetch_array($select_result))
				{
					
					 ?>
					<tr class="data">
						<td class="data" width="30px"><?php echo $i; ?></td>
						<td class="data" width="30px"><input type="checkbox" name="email[]" id="email_<?php echo $i; ?>" value="document/<?php echo $select_data['name']; ?>" ></td>
						<td class="data"><a href="document/<?php echo $select_data['name']; ?>" target="_blank" title="View File"><?php echo $select_data['name']; ?></a></td>
						<td class="data" width="75px">
						<center>
						<a href="javascript:account_delete(<?php echo $select_data['id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>&nbsp;&nbsp;&nbsp;
						<a href="file_email.php?file_name=<?php echo $select_data['name']; ?>" ><img src="images/icon-email.png" width="20" title="View E-Mail" ></a>
						<!--<a href="edit_category.php?id=<?php echo $select_data['id']; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>-->
						</center>
						</td>
					</tr>
				<?php
					$i++;
				}
				
			}
			else
			{
				?>
				<tr class="data" >
					<td  width="30px" colspan="6" class="record_not_found" >Record Not Found</td>
				</tr>
				<?php
			}
			?>
			<input type="hidden" name="count" id="count" value="<?php echo $i; ?>"  />
		</table>
		<div id="view_by_day_div" style="position:absolute; z-index:100; top: 50%; left: 50%; margin-left: -400px; margin-top: -200px; border:8px solid #CCCCCC; padding:10px; background-color:#E6E6F2; display:none; width:800px; " >
			<div style="background-color:#FFF; border-radius:10px;" >
				<table border="0" align="center" cellpadding="5" cellspacing="0"  width="750">
					<tr>
						<td align="right" colspan="2"><input type="button" name="final_send" id="final_send" value="Send" onClick="return final_send_email();" class="button"  />&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="close_button" id="close_button" value="Close" onClick="return close_div();" class="button"  />
						</td>
					</tr>
		 			<tr>
						<td align="left" valign="top" width="70" >To</td>
						<td align="left" valign="top" ><input type="text" name="to" id="to" value="<?php echo $data_list['email']; ?>" style="width:450px;"  /></td>
					</tr>
					<tr>
						<td align="left" valign="top"  >Subject</td>
						<td align="left" valign="top" ><input type="text" name="subject" id="subject" value="" style="width:450px;"  /></td>
					</tr>
					<tr>
						<td align="left" valign="top"  >Attachment</td>
						<td align="left" valign="top" >
							<span id="attachment_files" ></span>
						</td>
					</tr>
					<tr>
						<td align="left" valign="top" >Message</td>
						<td align="left" valign="top" >
						
						<?php
							include_once("fckeditor/fckeditor.php");
							$fck = new FCKeditor('content');
							$fck->BasePath = 'fckeditor/';
							$fck->Value = "";
							$FCKeditor->ToolbarSet = "MyToolbar";
							$FCKeditor->Width = "700px";
							$FCKeditor->Height = "150px";
							$fck->Create();
							

							
							
						 ?>
						 <input type="hidden" name="email_action" id="email_action" value=""  />
						  <input type="hidden" name="file_string" id="file_string" value=""  />
						 </td>
					</tr>
				</table>
			</div>
			</div>
		</form>
		<div class="pagination" >
		
		<?php
			
						if($page > 1)
						{
							$page = $page-1;
							echo '<a href="javascript:void(0)" onclick="return show_records('.$page.')" ><span ><< prev</span></a>&nbsp';
							$page = $page+1;
						}
							
						?>
						
						<?php
						if($range['end'] != 1)
						{
							for ($i = $range['start']; $i <= $range['end']; $i++)
							{
								if($i == $page)
									echo '<strong><span class="current">'.$i.'</span></strong>&nbsp;';
								else
									echo '<a href="javascript:void(0)" onclick="return show_records('.$i.')"><span >'.$i.'</span></a>&nbsp;';
							}
						}
						?>
						
						<?php
						if ($page < $totalPages)
						{
							$page = $page+1;
							echo '<a href="javascript:void(0)" onclick="return show_records('.$page.')" >next >></a>&nbsp;';
							$page = $page-1;
						}
						
					 ?>
       
        </div>
	
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
	if($("#document_type").val() == "")
	{
		alert("Please Select document Type.");
		$("#document_type").focus();
		return false;
	}
	else if($("#document_file").val() == "")
	{
		alert("Please select document.");
		$("#document_file").focus();
		return false;
	}
	else if($("#document_file_name").val() == "")
	{
		alert("Please enter document file name.");
		$("#document_file_name").focus();
		return false;
	}
	else
	{
		$("#action_perform").val("add_document");
		$("#document_form").submit();
		return true;
	}
	
}
function account_delete(del_id)
{
	if(confirm("Are you sure want to delete?!!!!!......"))
	{
		$("#action_perform").val("delete_document");
		$("#del_id").val(del_id);
		$("#document_form").submit();
		return true;
	}
}

function show_records(getno)
{
    //alert(getno);
    document.getElementById("page").value=getno;
    document.search_form.submit(); 
}

</script>

