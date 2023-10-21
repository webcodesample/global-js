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

if(isset($_POST['trans_id']) && $_POST['trans_id'] != "")
{

	$trans_id = $_POST['trans_id'];
	$del_query = "delete from payment_plan where trans_id = '".$trans_id."'";
	$del_result = mysql_query($del_query) or die("error in Transaction delete query ".mysql_error());
	$msg = "Transaction Deleted Successfully.";
}




if(trim($_REQUEST['trans_makepayment']) == "make_payment_yes")
{
		 //no_check , trans_flag_ , payment_date_n , from_name_old , from_name_n , 
					 //to_name_old ,  to_name_n , project_old , project_n , description_n , amount_n , trans_flag_no , file_id ,action_page
		/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
	$file_id=$_REQUEST['file_id'];
	$action_page=$_REQUEST['action_page'];
	$no_check=$_REQUEST['no_check'];
	$trans_flag_n=$_REQUEST['trans_flag'];
	$payment_date_n=$_REQUEST['payment_date_n'];
	$description_n=$_REQUEST['description_n'];
	$amount_n=$_REQUEST['amount_n'];
    $from_name_old=$_REQUEST['from_name_old'];
	$from_name_n=$_REQUEST['from_name_n'];
	$to_name_old=$_REQUEST['to_name_old'];
    $to_name_n=$_REQUEST['to_name_n'];
	$project_old=$_REQUEST['project_old'];
	$project_n=$_REQUEST['project_n'];
	//$file_attach
    $trans_flag_no = mysql_real_escape_string(trim($_REQUEST['trans_flag_no'])); 
	$trans_type = 1;
    $trans_type_name = "make_payment" ;
    
	 for($i = 0; $i < $trans_flag_no; $i++) {
		$no_check_v = $no_check[$i];
		if( $no_check[$i]!=""){	
			echo $no_check[$i];
			echo"</br>";
			for($ii = 0; $ii < $trans_flag_no; $ii++) {

			if($ii==$no_check[$i]){
				 echo $trans_flag_n[$ii];
				 echo"</br>";
				 echo $payment_date_n[$ii];
				 echo"</br>";
				 echo $description_n[$ii];
				 echo"</br>";
				 echo $amount_n[$ii];
				 echo"</br>";				 
				 echo $from_name_old[$ii];
				 echo"</br>";
				 echo $from_name_n[$ii];
				 echo"</br>";
				 echo $to_name_old[$ii];
				 echo"</br>";
				 echo $to_name_n[$ii];
				 echo"</br>";
				 echo $project_old[$ii];
				 echo"</br>";				 				 
				 echo $project_n[$ii];
				 echo"</br>";
				 echo"</br>";
			/*	 $from_arr = explode(" -",$from_name_n[$ii]);
				 $bank_id_n = get_field_value("id","bank","bank_account_name",$from_arr[0]);
			*/	
			/*	 $to_arr = explode("- ",$to_name_n[$ii]);
				 $cust_id = $to_arr[1];
			*/	
			/*	 $project_id = get_field_value("id","project","name",$project_n[$ii]);
			*/	 
			$insert_f = 0;

				 if($from_name_n[$ii]=="new")
				 {
					$insert_f=1;
				 }
				 else{
					$from_arr = explode(" -",$from_name_n[$ii]);
				 	$bank_id_n = get_field_value("id","bank","bank_account_name",$from_arr[0]);
				}
				if($to_name_n[$ii]=="new")
				 {
					$insert_f=1;
				 }
				 else{
					$to_arr = explode("- ",$to_name_n[$ii]);
					$cust_id_n = $to_arr[1];
				}
				if($project_n[$ii]=="new")
				 {
					//$from_name_old[$ii];
					$insert_f=1;
				 }
				 else{
					$project_id_n = get_field_value("id","project","name",$project_n[$ii]);
				}
				if($insert_f!=1)
				{
					

					$wi = 0;
					while($wi<1)
					{
						$trans_id_n = rand(100000,999999);
						$ss="select id from payment_plan where trans_id=".$trans_id_n."";
						$sr=mysql_query($ss);
						$tot_rw=mysql_num_rows($sr);
						if($tot_rw == 0)
						{
							break;								
						}
					}
					//bank_id_n ,cust_id_n ,project_id_n
					// trans_id , bank_id , amount , description , cust_id , project_id , payment_date 
					$trans_id=$trans_id_n;
					$bank_id=$bank_id_n;
					$amount = $amount_n[$ii];
					$description = $description_n[$ii];
					$cust_id = $cust_id_n;
					$project_id = $project_id_n;
					$payment_date =$payment_date_n[$ii];
					//$trans_flag_n[$ii]; ,file_id
					//payment_plan_link1 ,payment_plan_link2 ,action_flag
					//file_import_id='".file_id."', file_import_data_id='".trans_flag_n[$ii]."'
					$query="insert into payment_plan set trans_id = '".$trans_id."', bank_id = '".$bank_id."', debit = '".$amount."', description = '".$description."', on_customer = '".$cust_id."', on_project = '".$project_id."', payment_date = '".$payment_date."', trans_type = '".$trans_type."', trans_type_name = '".$trans_type_name."',file_import_id='".$file_id."', file_import_data_id='".$trans_flag_n[$ii]."',create_date = '".getTime()."'";
					$result= mysql_query($query) or die('error in query '.mysql_error().$query);
					
					$link_id_1 = mysql_insert_id();
					
					$query2="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."', credit = '".$amount."', description = '".$description."', on_project = '".$project_id."', on_bank = '".$bank_id."', payment_date = '".$payment_date."',link_id = '".$link_id_1."',trans_type = '".$trans_type."', trans_type_name = '".$trans_type_name."', file_import_id='".$file_id."', file_import_data_id='".$trans_flag_n[$ii]."',create_date = '".getTime()."'";
					$result2= mysql_query($query2) or die('error in query '.mysql_error().$query2);
					
					$link_id_2 = mysql_insert_id();
					
					$query5="update payment_plan set link_id = '".$link_id_2."' where id = '".$link_id_1."'";
					$result5= mysql_query($query5) or die('error in query '.mysql_error().$query5);
					
					$query_imp1="update tbl_info set payment_plan_link1 = '".$link_id_2."',payment_plan_link2 = '".$link_id_1."',trans_id='".$trans_id."',from_id='".$bank_id."',to_id='".$cust_id."',project_id='".$project_id."', action_flag=1,transfer_date='".getTime()."' where id = '".$trans_flag_n[$ii]."'";
					$result_imp1= mysql_query($query_imp1) or die('error in query '.mysql_error().$query_imp1);
					
					/******* file Attachment****** */
						if($_FILES["file_attach"]["name"][$ii] != "")
						{
							$query3="insert into attach_file set attach_id = '".$link_id_1."', link_id = '".$link_id_2."',file_name = '".$new_file_name."'";
						$result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
						$link_id_4 = mysql_insert_id();
						
							$attach_file_name=mysql_real_escape_string(trim($_FILES["file_attach"]["name"][$ii]));
							$temp = explode(".", $_FILES["file_attach"]["name"][$ii]);
							$arr_size = count($temp);
							$extension = end($temp);
							//$new_file_name = $attach_file_name.'_'.date("d_M_Y").'.'.$extension;
							$new_file_name = $attach_file_name.'_'.$link_id_4.'_'.date("d_M_Y").'.'.$extension;
							move_uploaded_file($_FILES["file_attach"]["tmp_name"][$ii],"transaction_files/" . $new_file_name);
							
								
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
					

					/***********     // file attachment    ************** */
				}
			}
			}
		}
		 
		 
	 }
	 header("Location:import_file_make_payment-ledger.php?file_id=$file_id");

}


if(mysql_real_escape_string(trim($_REQUEST['search_action'])) != "")
{
    $search_pay_type = $_POST['search_pay_type'];
 
    $select_query = "select * from tbl_info where file_import_id = '".$_REQUEST['file_id']."' ".$pay_type1." ".$pay_type2." ".$pay_type3."   ORDER BY payment_date DESC ";
    //$select_query = "select * from tbl_info where file_import_id = '".$_REQUEST['file_id']."' and payment_type like %'".$pay_type1."'%   ORDER BY payment_date DESC ";
    $select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
	
}
else
{
	$select_query = "select * from tbl_info where file_import_id = '".$_REQUEST['file_id']."' and final_payment_type='make_payment' and action_flag!=1 ORDER BY payment_date DESC";
	$select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
	$trans_flag_permision=0;
         
}

?>
<html>
<head>
<title>Admin Panel</title>
<script src="js/datetimepicker_css.js"></script>
<STYLE TYPE="text/css" media="print">    

#pb {page-break-after:always}
        .pb {page-break-after:always}
    .hs {page-break-before:always;
}

    .hd{display:none} 
    
    thead.report-header {
    display:table-header-group;
}
</STYLE>
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
	<h3>File Records - (<?php echo get_field_value("file_name","file_import","id",$_REQUEST['file_id']); ?>) </h3>
    <input type="hidden" id="print_header" name="print_header" value="File Records - (<?php echo get_field_value("file_name","file_import","id",$_REQUEST['file_id']); ?>) ">
		<br>
        <!--
        <form name="search_form1" id="search_form1" action="" method="post" onSubmit="return search_valid();" enctype="multipart/form-data">
         
         <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
             <tr>
                	<td width="50">&nbsp;&nbsp;From</td>
					<td width="120"><input type="text"  name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>" style="width:100px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('from_date')" style="cursor:pointer"/></td>
				    <td width="50">&nbsp;&nbsp;To</td>
					<td width="120"><input type="text"  name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>" style="width:100px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('to_date')" style="cursor:pointer"/></td>
					<td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='import_file.php';"  /></td>
            </tr>
         </table>
            <input type="hidden" name="page" id="page" value=""  />
         </form> -->
         
<form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" >
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
				<td width="50">&nbsp;&nbsp;From</td>
					<td width="120"><input type="text"  name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>" style="width:100px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('from_date')" style="cursor:pointer"/></td>
				    <td width="50">&nbsp;&nbsp;To</td>
					<td width="120"><input type="text"  name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>" style="width:100px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('to_date')" style="cursor:pointer"/></td>
			
                      <td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='import_file-ledger.php?file_id=<?php echo $_REQUEST['file_id']; ?>';"  /></td>
				      
					<td align="right" valign="top" ><a href="import_file.php" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>&nbsp;<input type="button" name="print_button" id="print_button" value="" class="button_print" onClick="return print_data();"  />
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
                    <script src="dist/jquery.table2excel.min.js"></script>
                    <input type="button" id="export_to_excel" value="" class="button_export" >
                    
                    </td>
					
					
				</tr>
			</table>
			<input type="hidden" name="search_action" id="search_action" value="Search"  />
			
			</form>
            <form name="search_form1" id="search_form1" action="" method="post"  enctype="multipart/form-data">
         
         
		<div id="ledger_data" style="width:98%; overflow-y: auto;">
		<table class="data" id="my_table" border="1" cellpadding="1" cellspacing="0" style="border: 1px solid #111111; width:100%;">
        
			<tr >
            <thead class="report-header">
				<th class="data" width="15px"><b>S.No</b></th>
                <th class="data" width="15px"></th>
                <th class="data" width="15px"><b>S.No<br>xlsx</b></th>
				<th class="data" width="100px" style="width:100px;"><b>Date&nbsp;&nbsp;</b></th>
				<th class="data"><b>From </b></th>
				<th class="data"><b>From as </b></th>
				<th class="data"><b>To</b></th>
                <th class="data"><b>To As </b></th>
				<th class="data"><b>Project</b></th>
				<th class="data"><b>Project as</b></th>
				<th class="data"><b>Description</b></th>
				<th class="data" width="50px"><b>Amount</b></th>
				<th class="data" width="50px"><b>file</b></th>
				<th class="data" width="50px"><b>Action</b>
            </th>
				</thead>
			</tr>
			<?php
			if($select_total > 0)
			{
				$i = 1;
                $inm=0;
				while($select_data = mysql_fetch_array($select_result))
				{
					 ?> 
					<tr class="data">
						<td class="data" align="center" ><?php echo $i; ?></td>
						<td class="data" width="15px">
                        <?php if($select_data['final_pay_type_flag']==1)
                        { 
							?>
							<input type='checkbox' id="<?php echo "no_check$inm"; ?>" value="<?php echo $inm; ?>" name='no_check[]'/>
                            <input type="hidden" name="trans_flag[]" id="trans_flag<?php echo $inm; ?>" value="<?php echo $select_data['id']; ?>" >
                            <?php  $inm++; 
                         }   ?>    
                       </td>
                       <td class="data" align="center"><?php echo $select_data['file_series']; ?>	</td>
						           <td class="data">
                          <input style="width: 60px;" readonly="readonly" type='text' value="<?php echo date("d-m-y",$select_data['payment_date']);//echo $select_data['payment_date']; ?>" id="<?php echo "payment_date_n$inm"; ?>" name='payment_date_n[]'/>
                          <?php //echo date("d-m-y",$select_data['payment_date']); ?>
                       </td>
						          <td class="data">
                        <input style="width: 100px;" readonly="readonly" type='text' value="<?php echo $select_data['from_name']; ?>" id="<?php echo "from_name_old$inm"; ?>" name='from_name_old[]'/>
                        <?php //echo $select_data['from_name']; ?></td>
						<td class="data"><?php 
						$from_val1=substr($select_data['from_name'],0,4);
							$sql 	= "select bank_account_name,bank_account_number from `bank` where bank_account_name like '%".$from_val1."%'";
							$query 	= mysql_query($sql);  ?>
						 	<select id="<?php echo "from_name_n$inm"; ?>" name='from_name_n[]' style="width:200px; height: 25px;">
						 <?php  while($row = mysql_fetch_assoc($query)){
								$val = $row['bank_account_name'].' - '.$row['bank_account_number']; ?>
		               			<option value="<?php echo $val; ?>" <?php if($select_data['from_name']==$row['bank_account_name']){ echo "selected='selected'"; } ?> ><?php echo $row['bank_account_name']; ?></option> 
       							<?php 	} 	//echo $select_data['from_name']; ?>
							<option value="new">New</option>
							</select>    
						</td>  
						<td class="data">
						<input style="width: 100px;" readonly="readonly" type='text' value="<?php echo $select_data['to_name']; ?>" id="<?php echo "to_name_old$inm"; ?>" name='to_name_old[]'/>
                    	<?php //echo $select_data['to_name']; ?>	</td>
						<td class="data" style="width:200px;"><?php 
						$to_val1=substr($select_data['to_name'],0,4);
						$sql 	= "select cust_id,full_name from `customer` where full_name like '%$to_val1%' and type = 'supplier'";
						$query 	= mysql_query($sql); ?>
						<select id="<?php echo "to_name_n$inm"; ?>" name='to_name_n[]' style="width:200px; height: 25px;">
					<?php
						while($row = mysql_fetch_assoc($query)){
							if($row['cust_id']==$cus_id){
							}
							$val = $row['full_name'].' - '.$row['cust_id'];  
							?>
							<option value="<?php echo $val; ?>" <?php if($select_data['to_name']==$row['full_name']){ echo "selected='selected'"; } ?> ><?php echo $row['full_name']; ?></option> 
							<?php 	} 	//echo $select_data['from_name']; ?>
					 <option value="new">New</option>
					 </select>    
						   
									</td>
						<td class="data">
						<input style="width: 100px;" readonly="readonly" type='text' value="<?php echo $select_data['project']; ?>" id="<?php echo "project_old$inm"; ?>" name='project_old[]'/>
                    	<?php //echo $select_data['project']; ?></td>
						<td class="data"><?php 
						$to_val1=substr($select_data['project'],0,4);
						$sql 	= "select name from `project` where name like '%$to_val1%' ";
						$query 	= mysql_query($sql); ?>
						<select id="<?php echo "project_n$inm"; ?>" name='project_n[]' style="width:200px; height: 25px;">
					<?php
						while($row = mysql_fetch_assoc($query)){
							
							$val_project = $row['name'];  
							?>
							<option value="<?php echo $row['name']; ?>" <?php if($select_data['project']==$row['name']){ echo "selected='selected'"; } ?> ><?php echo $row['name']; ?></option> 
							<?php 	} 	//echo $select_data['from_name']; ?>
					 <option value="new">New</option>
					 </select> 
						</td>
						<td class="data">
						<input style="width: 100px;" readonly="readonly" type='text' value="<?php echo $select_data['description']; ?>" id="<?php echo "description_n$inm"; ?>" name='description_n[]'/>
                    	<?php //echo $select_data['description']; ?></td>
						<td class="data">
						<input style="width: 100px;" readonly="readonly" type='text' value="<?php echo number_format($select_data['amount'],2,'.',''); ?>" id="<?php echo "amount_n$inm"; ?>" name='amount_n[]'/>
                    	<?php //echo number_format($select_data['amount'],2,'.',''); ?> 	</td>
						<td class="data">
						<input style="width: 100px;"  type='file' value="" id="<?php echo "file_attach$inm"; ?>" name='file_attach[]'/>
                    	<?php //echo number_format($select_data['amount'],2,'.',''); ?> 	</td>
						
						<td class="data" nowrap="nowrap"> 
                        <a href="javascript:edit_action(<?php echo $select_data['id']; ?>)"><img src="mos-css/img/edit.png" title="Edit"></a>                        
                        </td>
						
					</tr>
				<?php
					$i++;
				} 
                ?>
                <input type="hidden" id="trans_flag_no" name="trans_flag_no" value="<?php echo $inm; ?>">
                <?php
				
			}
			else
			{
				?>
				<tr class="data" >
					<td  width="30px" colspan="10" class="record_not_found" >Record Not Found</td>
				</tr>
				<?php
			}
			?>
			
		</table>
		</br>
		
			
        
		</div>
		<input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation_import();">	
		<input type="hidden" name="trans_makepayment" id="trans_makepayment" value="" >
		<input type="hidden" name="action_page" id="action_page" value="import_make_payment_ledger" >
			<input type="hidden" name="file_id" id="file_id" value="<?php echo $_REQUEST['file_id']; ?>" >
				
	</form>
	</div>
<div class="clear"></div>
<?php
include_once("footer.php");
?>
</div>
		<form name="trans_form" id="trans_form" action="" method="post" >
		
			<input type="hidden" name="trans_id" id="trans_id" value="" >
		</form>

		
		<form name="edit_form" id="edit_form" action="edit_import_file_data.php" method="post" >
			<input type="hidden" name="id" id="id" value="" >
			<input type="hidden" name="action_page" id="action_page" value="import_make_payment_ledger" >
			<input type="hidden" name="file_id" id="file_id" value="<?php echo $_REQUEST['file_id']; ?>" >
			
		</form>
                
</body>
</html>
<script>
$(document).ready(function(){
    $("#export_to_excel").click(function(){
        //document.getElementById("hid1").style.visibility = "hidden";    
        //$("td:hidden,th:hidden","#my_table").remove();
		//newWin.document.write(getHeader());
		//$('table td').find('td:eq(4)').hide(); 
        //$("#thead").hide(); 
        //$("td:hidden,th:hidden","#my_table").remove();
        $("#my_table").table2excel({        
        
            exclude: ".noExl",
            name: "Developer data",
            filename: "import_file_ledger",
            fileext: ".xls",        
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true            
        });  
        // $("#thead").show();
    });
   // $('table td').find('td:eq(4)').show(); 
   
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

function account_transaction(trans_id)
{
	if(confirm("Are you sure want to delete?!!!!!......"))
	{
		$("#trans_id").val(trans_id);
		$("#trans_form").submit();
		return true;
	}
}

function validation_import()
{
	if(confirm("Are you sure to Import this data in make Payment file?!!!!!......"))
	{
		$("#trans_makepayment").val("make_payment_yes");
		$("#search_form1").submit();
		return true;
	}
}
function edit_action(id)
{
	$("#id").val(id);
	$("#edit_form").submit();
		
}

function print_data()
{

     var print_header1 = $("#print_header").val();           
   var divToPrint1 = document.getElementById("ledger_data");
var divToPrint = divToPrint1;
divToPrint.border = 3;
divToPrint.cellSpacing = 0;
divToPrint.cellPadding = 2;
divToPrint.style.borderCollapse = 'collapse';
newWin = window.open();
//newWin.document.write(getHeader());
//newWin.document.write("<h3 align='center'>Master Designation List </h3>");
newWin.document.write("<h3 align='center'>"+print_header1+" </h3>");
//$('tr').children().eq(3).hide();

 $("#header1").hide();
$('table tr').find('td:eq(8)').hide();
newWin.document.write(divToPrint.outerHTML);
newWin.print();
//$('tr').children().eq(7).show();

$('table tr').find('td:eq(8)').show();
$("#header1").show();
newWin.close();
   
   /* printMe=window.open();
    printMe.document.write(document.getElementById("").innerHTML);
    printMe.print();
    printMe.close();*/
}


</script>