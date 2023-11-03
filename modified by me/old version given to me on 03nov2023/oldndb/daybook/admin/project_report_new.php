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


if(mysql_real_escape_string(trim($_REQUEST['search_action'])) != "")
{
	$from_date = strtotime(mysql_real_escape_string(trim($_REQUEST['from_date'])));
	
	$to_date = strtotime(mysql_real_escape_string(trim($_REQUEST['to_date'])));

	$select_query = "select *,payment_plan.id as payment_id from payment_plan inner join project on payment_plan.project_id = project.id and project.id = '".$_REQUEST['project_id']."'  and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
	$select_result = mysql_query($select_query) or die('error in query select cash query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
	
	$select_query2 = "select *,payment_plan.id as payment_id from payment_plan inner join project on payment_plan.project_id = project.id and project.id = '".$_REQUEST['project_id']."'  and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
	$select_result2 = mysql_query($select_query2) or die('error in query select cash query '.mysql_error().$select_query2);
	$select_total2 = mysql_num_rows($select_result2);
	
    
     $select_query3 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join project on payment_plan.project_id = project.id and project.id = '".$_REQUEST['project_id']."'  and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."'  ";
                $select_result3 = mysql_query($select_query3) or die('error in query select cash query '.mysql_error().$select_query3);
                $select_data3 = mysql_fetch_array($select_result3);
                //echo $select_data3['total_credit']-$select_data3['total_debit'];
                $bal=$select_data3['total_credit']-$select_data3['total_debit'];
                
                
                
                $select_query3_1 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join project on payment_plan.project_id = project.id and project.id = '".$_REQUEST['project_id']."'  and payment_plan.payment_date <= '".$to_date."'  ";
                $select_result3_1 = mysql_query($select_query3_1) or die('error in query select cash query '.mysql_error().$select_query3_1);
                $select_data3_1 = mysql_fetch_array($select_result3_1);
                //echo $select_data3['total_credit']-$select_data3['total_debit'];
                //$bal_credit,$bal_debit
                $bal_1=$select_data3_1['total_credit']-$select_data3_1['total_debit'];
                $bal_credit = $select_data3_1['total_credit'];
                $bal_debit = $select_data3_1['total_debit'];
                $search_start ="1";
                
                
                $select_query5 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join project on payment_plan.project_id = project.id and project.id = '".$_REQUEST['project_id']."'  and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."'  ";
                
       
}
else
{
	$select_query = "select *,payment_plan.id as payment_id from payment_plan inner join project on payment_plan.project_id = project.id and project.id = '".$_REQUEST['project_id']."' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
	$select_result = mysql_query($select_query) or die('error in query select customer query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
	
	$select_query2 = "select *,payment_plan.id as payment_id from payment_plan inner join project on payment_plan.project_id = project.id and project.id = '".$_REQUEST['project_id']."' ORDER BY payment_plan.payment_date DESC,payment_plan.id DESC ";
	$select_result2 = mysql_query($select_query2) or die('error in query select customer query '.mysql_error().$select_query2);
	$select_total2 = mysql_num_rows($select_result2);
 
        
    $select_query3 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join project on payment_plan.project_id = project.id and project.id = '".$_REQUEST['project_id']."'";
                $select_result3 = mysql_query($select_query3) or die('error in query select cash query '.mysql_error().$select_query3);
                $select_data3 = mysql_fetch_array($select_result3);
                //echo $select_data3['total_credit']-$select_data3['total_debit'];
                $bal=$select_data3['total_credit']-$select_data3['total_debit'];

                $select_query5 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan inner join project on payment_plan.project_id = project.id and project.id = '".$_REQUEST['project_id']."'";
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
        Project - <?php echo get_field_value("name","project","id",$_REQUEST['project_id']); ?> Ledger</h4>
  </td>
        <td width="" style="float:right;">
            <a href="bank.php" title="Back" style=""><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
            </td>
    </tr>
</table>
<input type="hidden" id="print_header" name="print_header" value="Project - <?php echo get_field_value("name","project","id",$_REQUEST['project_id']); ?> Ledger</h3>">

<?php include_once("main_heading_close.php") ?>
  <?php include_once("main_body_open.php") ?>
		<br>
		
		<form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" >
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					
					<td width="50">
					&nbsp;&nbsp;From
					</td>
					<td width="120">
					<input type="text"  name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>" style="width:100px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('from_date')" style="cursor:pointer"/>
					<!--<input type="text" name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>"  readonly="" style="width:100px;" >-->
				 </td>
				
				 <td width="50">
					&nbsp;&nbsp;To
					</td>
					<td width="120">
					<input type="text"  name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>" style="width:100px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('to_date')" style="cursor:pointer"/>
					<!--<input type="text" name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>"  readonly="" style="width:100px;" >-->
				 </td>
				 
					<td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='project-ledger.php?project_id=<?php echo $_REQUEST['project_id']; ?>';"  /></td>
					<td align="right" valign="top" >
                    <a href="project.php" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
                    <input type="button" name="print_button" id="print_button" value="" class="button_print" onClick="return print_data();"  />
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="dist/jquery.table2excel.min.js"></script>
<!--<script src="jquery.min.js"></script>-->
<!--<script type="text/javascript" src="script.js"></script>-->
<input type="button" id="export_to_excel" value="" class="button_export" >
                    
                    </td>
					
					
				</tr>
			</table>
			<input type="hidden" name="search_action" id="search_action" value="Search"  />
			
			</form>
		<div id="ledger_data">
        <table class="data" id="my_table" border="1" cellpadding="1" cellspacing="0" style="border: 1px solid #111111;">
        
			<tr >
            <thead class="report-header">
				<th class="data" width="15px"><b>S.No.</b></th>
				<th class="data" width="80"><b>Date</b></th>
				<th class="data"><b>To&nbsp; / From</b></th>
				<th class="data"><b>Bank</b></th>
                <th class="data"><b>Description</b></th>
				<th class="data"><b>GST%</b></th>
                <th class="data" width="50"><b>Debit</b></th>
				<th class="data" width="50"><b>Credit</b></th>
				<th class="data" width="70"><b>Balance</b></th>
				</thead>
			</tr>
			<?php
			if($select_total > 0)
			{
				$i = 1;
				/*$select_query3 = "select SUM(debit) as total_debit,SUM(credit) as total_credit from payment_plan where project_id = '".$_REQUEST['project_id']."'   ";
				$select_result3 = mysql_query($select_query3) or die('error in query select cash query '.mysql_error().$select_query3);
				$select_data3 = mysql_fetch_array($select_result3);
				
				$bal=$select_data3['total_credit']-$select_data3['total_debit'];
			
            */	while($select_data = mysql_fetch_array($select_result))
				{
					if($i > 1)
					{
						$select_query4 = "select debit,credit from payment_plan where project_id = '".$_REQUEST['project_id']."' and id = '".$temp_payment_id."' LIMIT 0,1  ";
						$select_result4 = mysql_query($select_query4) or die('error in query select cash query '.mysql_error().$select_query4);
						$select_data4 = mysql_fetch_array($select_result4);
						
						if($select_data4['debit'] > 0 && $select_data4['description'] != "Opening Balance" )
						{
							$bal=(float)$bal+(float)$select_data4['debit'];
							
						}
						if($select_data4['credit'] > 0 && $select_data4['description'] != "Opening Balance" )
						{
							$bal=(float)$bal-(float)$select_data4['credit'];
							
						}
						
					}
					$temp_payment_id = $select_data['payment_id'];
					     
                    if($i==1)
                    {
                        //$bal_1=$select_data3_1['total_credit']-$select_data3_1['total_debit'];
                         if($search_start=="1")
                         {
                             //$from_date,$to_date
                             ?>
                                                     <tr class="data">
                        <td class="data" width="30px"><?php //echo $i; ?></td>
                        <td class="data"><b> <?php echo date("d-m-Y",$to_date); ?></b></td>
                       <td class="data"></td>
                        <td class="data"></td>
                        <td class="data"><b><?php echo "Closing Balance"; ?></b></td>
                        <td class="data"><b><?php echo number_format($select_data3_1['total_debit'],2,'.',''); ?> </b></td>
                        <td class="data"><b><?php echo number_format($select_data3_1['total_credit'],2,'.',''); ?></b></td>
                        <td class="data" <?php if($bal_1<0 ) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?>><b><?php echo currency_symbol().number_format($bal_1,2,'.',''); ?></b></td>
                    </tr>
                             <?php
                         }
                         else{
                             ?>
                             
                                            <tr class="data">
                        <td class="data" width="30px"><?php //echo $i; ?></td>
                        <td class="data"><b> <?php echo date("d-m-Y",$select_data['payment_date']); ?></b></td>
                        <td class="data"></td>
                        <td class="data"></td>
                        <td class="data"><b><?php echo "Closing Balance"; ?></b></td>
                        <td class="data"><b><?php echo number_format($select_data3['total_debit'],2,'.',''); ?> </b></td>
                        <td class="data"><b><?php echo number_format($select_data3['total_credit'],2,'.',''); ?></b></td>
                        <td class="data" <?php if($bal<0) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?>><b><?php echo currency_symbol().number_format($bal,2,'.',''); ?></b></td>
                    </tr>
                             <?php
    

                         }
?>
                         

                        <?php
    
    
                    }
                     ?>

					<tr class="data">
						<td class="data" align="center"><?php echo $i; ?></td>
						<td class="data"><?php echo date("d-m-y",$select_data['payment_date']); ?></td>
						<td class="data"><?php 
						if($select_data['on_customer'] != "")
						{
							echo get_field_value("full_name","customer","cust_id",$select_data['on_customer']);
						}
						else if($select_data['on_project'] != "")
						{
							echo get_field_value("name","project","id",$select_data['on_project']);
						}
						
						 ?></td>
						<td class="data"><?php echo get_field_value("bank_account_name","bank","id",$select_data['on_bank']); ?></td>
						<td class="data"><?php echo $select_data['description']; ?></td>
						<td class="data">
						<?php
							if($select_data['debit'] > 0 && $select_data['description'] != "Opening Balance")
							{
								echo number_format($select_data['debit'],2,'.','');
							}
							
							
							
							?>
						</td>
						<td class="data">
						<?php
							if($select_data['credit'] > 0 && $select_data['description'] != "Opening Balance")
							{
								echo number_format($select_data['credit'],2,'.','');
							}
							
							
							?>
						</td>
						 <?php 
                      /* if($bal<0) { ?> style="color:#FF0000;" <?php } ?> ><?php echo currency_symbol().number_format($bal,2,'.',''); */
                         
                        
                         if($search_start=="1"){
                             ?>
                             <td class="data" <?php if($bal_1<0 ) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?>  >
                             <?php 
                            echo currency_symbol().number_format($bal_1,2,'.','');
                            ?>
                            </td>
                            <?php 
                        }else
                        {
                            ?>
                             <td class="data" <?php if($bal<0) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?> >
                             <?php 
                            echo currency_symbol().number_format($bal,2,'.','');  
                            ?>
                            </td>
                            <?php  
                        }
                         ?>
                         
                         
                         <?php 
                        $bal_1=(float)$bal_1+(float)$select_data['debit'];
                        $bal_1=(float)$bal_1-(float)$select_data['credit'];
                        $bal_credit =(float)$bal_credit-(float)$select_data['credit'];
                        $bal_debit = (float)$bal_debit-(float)$select_data['debit'];
                        $date_old = $select_data['payment_date'];
                       
                        
                        ?>
                        
                        
				
					</tr>
				<?php
					$i++;
				}
                 if($search_start=="1")
                {
                    ?>
                    
                    <tr class="data">
                        <td class="data" width="30px"><?php //echo $i; ?></td>
                        <td class="data"><b> <?php echo date("d-m-Y",$from_date); ?></b></td>
                        <td class="data"></td><td class="data"></td>
                        <td class="data"><b><?php echo "Opening Balance"; ?></b></td>
                        <td class="data"><b><?php echo number_format($bal_debit,2,'.',''); ?> </b></td>
                        <td class="data"><b><?php echo number_format($bal_credit,2,'.',''); ?></b></td>
                        <td class="data" <?php if($bal_1<0 ) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?>><b><?php echo currency_symbol().number_format($bal_1,2,'.',''); ?></b></td>
                    </tr>
                    <?php
    

                }
                
				
			}
			else
			{
				?>
				<tr class="data" >
					<td  width="30px" colspan="7" class="record_not_found" >Record Not Found</td>
					
				</tr>
				<?php
			}
			?>
			
		</table>
		</div>
				
		<?php include_once("main_body_close.php") ?>
        <?php include_once ("footer.php"); ?>
      
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

<form name="invoice_project_form" id="invoice_project_form" action="" method="post" >
        
        <input type="hidden" name="invoice_project_id" id="invoice_project_id" value="" >
        <input type="hidden" name="trans_t_name" id="trans_t_name" value="" >
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
            filename: "project_ledger",
            fileext: ".xls",        
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true            
        });  
        // $("#thead").show();
    });
   // $('table td').find('td:eq(4)').show(); 
   
});

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

function invoice_attach_file_function(id)
{
    
    document.getElementById("attach_div").style.display="block";
    document.getElementById("attach_file_id").value=id;
    document.getElementById("invoice_flag").value="1";
    
}

function close_div()
{
	document.getElementById("attach_div").style.display="none";
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
function account_transaction(trans_id)
{
	if(confirm("Are you sure want to delete?!!!!!......"))
	{
		$("#trans_id").val(trans_id);
		$("#trans_form").submit();
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

function account_transaction_invoice(invoice_project_id)
{
    if(confirm("Are you sure want to delete?!!!!!......"))
    { 
       // $("#trans_t_name").val("instmulti_receive_payment");
        $("#invoice_project_id").val(invoice_project_id);
        $("#invoice_project_form").submit();
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

