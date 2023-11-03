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
//trans_t_name=loanadvance_makepayment,advance_loan_no,advance_loan_no_form

if(isset($_POST['invoice_id']) && $_POST['invoice_id'] != "")
{
    
    $trans_t_name = $_POST['trans_t_name'];
    $invoice_id = $_POST['invoice_id'];
    if($trans_t_name=="instmulti_receive_payment")
    {
        $del_query = "delete from payment_plan where invoice_id = '".$invoice_id."' and trans_type_name='instmulti_receive_payment'";
    $del_result = mysql_query($del_query) or die("error in invoice delete query ".mysql_error());
        
        
    $query5_1="update payment_plan set link2_id = '0',link3_id = '0' ,payment_flag = '0' where invoice_id = '".$invoice_id."'";
    $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);
    }
         
    $msg = "Customer Invoice Deleted Successfully.";
}
if(isset($_POST['trans_id']) && $_POST['trans_id'] != "")
{

	$trans_id = $_POST['trans_id'];
	$del_query = "delete from payment_plan where trans_id = '".$trans_id."'";
	$del_result = mysql_query($del_query) or die("error in Transaction delete query ".mysql_error());
	$msg = "Transaction Deleted Successfully.";
}

if(mysql_real_escape_string(trim($_REQUEST['search_action_trans'])) == "trans_final")
{
    ////search_action_trans = trans_final, trans_pay_type , trans_flag ,trans_flag_no
    $trans_pay_type = $_REQUEST['trans_pay_type'];
    $trans_flag_no = $_REQUEST['trans_flag_no'];
    $trans_flag = $_REQUEST['trans_flag'];
    
	$_REQUEST['search_action_trans']="";
    $size = $_REQUEST['trans_flag_no'];
    if($size > 1)
	{
		
       // echo "hello";
        //exit;	
		for($i=0;$i<$size;$i++)
		{
			if(trim($trans_flag[$i]) != "")
			{
                
                $query_up="update tbl_info set final_payment_type = '".$trans_pay_type."',payment_type = '".$trans_pay_type."',final_pay_type_flag = '1'  where id = '".$trans_flag[$i]."'";
                $result_up= mysql_query($query_up) or die('error in query '.mysql_error().$query_up);
    
            }
        }
        $msg = "Transaction Successfully.";
    }       
	
}




if(mysql_real_escape_string(trim($_REQUEST['search_action'])) != "")
{
    // search_pay_type,  all , received_payment , make_payment , internal_payment , other_payment , lad_make_payment, lad_received_payment , 
/*	$from_date = strtotime(mysql_real_escape_string(trim($_REQUEST['from_date'])));
	$to_date = strtotime(mysql_real_escape_string(trim($_REQUEST['to_date'])));
	$select_query = "select * from tbl_info where file_import_id = '".$_REQUEST['file_id']."' and payment_date >= '".$from_date."' and payment_date <= '".$to_date."' ORDER BY payment_date DESC ";
*/
    $search_pay_type = $_POST['search_pay_type'];
   /* if($search_pay_type=="received_payment")
    {
        $pay_type1="and payment_type like '%re%'";
        $pay_type2="or payment_type like '%Re%'";
        $pay_type3="or payment_type like '%RE%'";
        $trans_flag_permision=1;
    }else if($search_pay_type=="make_payment")
    {
        $pay_type1="and payment_type like '%mak%'";
        $pay_type2="or payment_type like '%Mak%'";
        $pay_type3="or payment_type like '%MAK%'";
        $trans_flag_permision=1;
    }else if($search_pay_type=="internal_payment")
    {
        $pay_type1="and payment_type like '%int%'";
        $pay_type2="or payment_type like '%Int%'";
        $pay_type3="or payment_type like '%INT%'";
        $trans_flag_permision=1;
    }else if($search_pay_type=="lad_make_payment")
    {
        $pay_type1="and payment_type like '%lad-mak%'";
        $pay_type2="or payment_type like '%lad_mak%'";
        $pay_type3="or payment_type like '%lad - mak%'";
        $trans_flag_permision=1;
    }else if($search_pay_type=="lad_received_payment")
    {
        $pay_type1="and payment_type like '%lad-re%'";
        $pay_type2="or payment_type like '%lad_re%'";
        $pay_type3="or payment_type like '%lad - re%'";
        $trans_flag_permision=1;
    }else if($search_pay_type=="all")
    {
        $pay_type1="";
        $pay_type2="";
        $pay_type3="";
        $trans_flag_permision=0;
    }
 */
    if($search_pay_type=="all")
    {
        $pay_type1="";
    }else{
        $pay_type1="and final_payment_type= '".$search_pay_type."'";
    }
    $select_query = "select * from tbl_info where file_import_id = '".$_REQUEST['file_id']."' and action_flag=1 ".$pay_type1."    ORDER BY payment_date DESC ";
    //$select_query = "select * from tbl_info where file_import_id = '".$_REQUEST['file_id']."' and payment_type like %'".$pay_type1."'%   ORDER BY payment_date DESC ";
    $select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
	//echo $select_query;
}
else
{
	$select_query = "select * from tbl_info where file_import_id = '".$_REQUEST['file_id']."' and action_flag=1 ORDER BY payment_date DESC";
	$select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
	$trans_flag_permision=0;
	/*$select_query2 = "select *,payment_plan.id as payment_id from payment_plan inner join bank on payment_plan.bank_id = bank.id and bank.id = '".$_REQUEST['bank_id']."' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
	$select_result2 = mysql_query($select_query2) or die('error in query select bank query '.mysql_error().$select_query2);
	$select_total2 = mysql_num_rows($select_result2);
    */
         
}

?>
<!DOCTYPE html>
<?php include_once ("top_header1.php"); ?>   
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
<body  data-home-page-title="" class="u-body u-xl-mode" data-lang="en">
  <?php include_once ("top_header2.php"); ?> 
  <?php include_once ("top_menu.php"); ?>
  <?php include_once("main_heading_open.php") ?>
  
	<table style="width:100%;" border="0" style="padding:0px 0px; margin-top:-10px;">
    <tr>
        <td style="align:top;" align="left">
        <h4 class="u-text-2 u-text-palette-1-base " style="padding:0px; margin:0px;">
        File Records - (<?php echo get_field_value("file_name","file_import","id",$_REQUEST['file_id']); ?>)  </h4>
  </td>
        <td width="" style="float:right;">
        <input type="hidden" id="print_header" name="print_header" value="File Records - (<?php echo get_field_value("file_name","file_import","id",$_REQUEST['file_id']); ?>) ">
        <a href="import_file.php" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>&nbsp;<input type="button" name="print_button" id="print_button" value="" class="button_print" onClick="return print_data();"  />
                    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>-->
                    <script src="dist/jquery.table2excel.min.js"></script>
                    <input type="button" id="export_to_excel" value="" class="button_export" >
                    
        <input type="button" id="search" value="" class="button_search1" onClick="search_display();" >
         </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
<!-------------->
<?php include_once("main_search_open.php") ?>
  <input type="hidden" name="search_check_val" id="search_check_val" value="0" >

  <form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid1();" >
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
                <td width="105" align="left" valign="top"> Search By Payment Type :</td>
                      <td width="180" align="left" valign="top">
                      <select name="search_pay_type" id="search_pay_type" style="width:250px; height: 25px;">
                      <option value="all" <?php if($_REQUEST['search_pay_type']=="all"){ echo "selected='selected'"; } ?> >All</option>
                      <option value="received_payment" <?php if($_REQUEST['search_pay_type']=="received_payment"){ echo "selected='selected'"; } ?> >Received Payment</option> 
                      <option value="make_payment" <?php if($_REQUEST['search_pay_type']=="make_payment"){ echo "selected='selected'"; } ?> >Make Payment</option>
                      <option value="internal_payment" <?php if($_REQUEST['search_pay_type']=="internal_payment"){ echo "selected='selected'"; } ?> >Internal Transfer</option>
                      <option value="lad_make_payment" <?php if($_REQUEST['search_pay_type']=="lad_make_payment"){ echo "selected='selected'"; } ?> >Loan-Adv Make Payment</option>
                      <option value="lad_received_payment" <?php if($_REQUEST['search_pay_type']=="lad_received_payment"){ echo "selected='selected'"; } ?> >Loan-Adv Received Payment</option>
                     
                     </select>    
                      </td>
                      <td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='import_file-ledger.php?file_id=<?php echo $_REQUEST['file_id']; ?>';"  /></td>
				      
					<td align="right" valign="top" >
                        
                    </td>
					
					
				</tr>
			</table>
			<input type="hidden" name="search_action" id="search_action" value="Search"  />
			
			</form>
  <?php include_once("main_search_close.php") ?>
 <!-------------->

<?php include_once("main_body_open.php") ?>
	
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
         
            <form name="search_form1" id="search_form1" action="" method="post"  enctype="multipart/form-data">
         <?php 
       ?><div id="ledger_data">
		<table class="data" id="my_table" border="1" cellpadding="1" cellspacing="0" style="border: 1px solid #111111;">
        
			<tr >
            <thead class="report-header">
				<th class="data" width="15px"><b>S.No</b></th>
                <th class="data" width="15px"></th>
                <th class="data" width="15px"><b>S.No<br>xlsx</b></th>
				<th class="data" width="80"><b>Date</b></th>
				<th class="data" width="80"><b>Trans Id</b></th>
                <th class="data"><b>To</b></th>
                <th class="data"><b>From </b></th>
				<th class="data"><b>Project</b></th>
				<th class="data"><b>Description</b></th>
				<th class="data" width="50"><b>Amount</b></th>
				<th class="data" width="80"><b></b></th>
				<th class="data" width="100"><b>Payment-type</b></th>
				<th class="data" width="50"><b>Action</b>
            </th>
				</thead>
			</tr>
			<?php
			if($select_total > 0)
			{
				$i = 1;
                $inm=1;
				while($select_data = mysql_fetch_array($select_result))
				{
					 ?>
					<tr class="data">
						<td class="data" align="center" ><?php echo $i; ?></td>
                        <td class="data" width="15px">
                        <?php 
                        if($trans_flag_permision==1){
                        if($select_data['final_pay_type_flag']==0){
                            ?>
                                 <input type="checkbox" name="trans_flag[]" id="trans_flag_<?php echo $inm; ?>" value="<?php echo $select_data['id']; ?>" >
                                 
                       <?php  $inm++; 
                     }  } ?>    
                       </td>
                       <td class="data" align="center"><?php echo $select_data['file_series']; ?>	</td>
						<td class="data"><?php echo date("d-m-y",$select_data['payment_date']); ?></td>
                        <td class="data"><?php echo $select_data['trans_id']; ?></td>
						<td class="data"><?php echo $select_data['to_name']; ?>	</td>
						<td class="data"><?php echo $select_data['from_name']; ?></td>
						<td class="data"><?php echo $select_data['project']; ?></td>
						<td class="data"><?php echo $select_data['description']; ?></td>
						<td class="data"><?php echo number_format($select_data['amount'],2,'.',''); ?> 	</td>
						<td class="data"><?php echo $select_data['transfer_date']; ?></td>
                        <td class="data"><?php echo $select_data['final_payment_type']; ?></td>
            			<td class="data" nowrap="nowrap"> 
                        <a href="edit_import_file_data.php?id=<?php echo $select_data['id']; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>                        
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
        </form>
		</div>
			
		<?php include_once("main_body_close.php") ?>
        <?php include_once ("footer.php"); ?>

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
function search_valid1()
{
    $("#search_form").submit();
		return true;
}
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

function edit_action(id)
{
	$("#id").val(id);
	$("#edit_form").submit();
		
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
function trans_valid()
{
	if(confirm("Are you sure to Transfer this category?!!!!!......"))
	{
		$("#search_action_trans").val("trans_final");
		$("#search_form1").submit();
		return true;
	}
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