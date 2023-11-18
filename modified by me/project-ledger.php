<?php session_start();
include_once("set_con.php");
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
if(isset($_POST['invoice_project_id']) && $_POST['invoice_project_id'] != "")
{
    $project_id = $_POST['invoice_project_id'];
    $query_del="select *  from payment_plan where id = '".$project_id."' ";
    $result_del= mysql_query($query_del) or die('error in query '.mysql_error().$query_del);
    $data_del = mysql_fetch_array($result_del);
    $old_invoice_id2 = $data_del['invoice_id'];
    $old_cust_linkid = $data_del['link_id'];
    
    $del_query = "delete from payment_plan where id = '".$project_id."' ";
    $del_result = mysql_query($del_query) or die("error in invoice delete query ".mysql_error());
       
    $del_query = "delete from goods_details where link1_id = '".$project_id."' ";
    $del_result = mysql_query($del_query) or die("error in invoice delete query ".mysql_error());
        
    $sql_goods_series     = "select * from `goods_details` where invoice_id= '$old_invoice_id2' ";
    $query_goods_series     = mysql_query($sql_goods_series);
    $ii=1;
    while($row_series = mysql_fetch_assoc($query_goods_series)){
        $old_sub_total =$row_series['unit_price']*$row_series['qty'];
        $old_gst_amount =($old_sub_total*$row_series['gst_per'])/100;
        $old_grand_total=$old_sub_total+$old_gst_amount;
        $tot1_grand_total = $tot1_grand_total+$old_grand_total;
        $tot1_gst_amount = $tot1_gst_amount+$old_gst_amount;
        $multi_project_id = $multi_project_id.$row_series['link1_id'].",";
        $desc_total_n = $desc_total_n."(".$ii.")".$row_series['description'].",";
        $goods_details_idlist=$goods_details_idlist.$row_series['id'].",";
        $ii++;         
    }
    $query_goods="update payment_plan set goods_detail_id = '".$goods_details_idlist."' ,multi_project_id= '".$multi_project_id."' ,description = '".$desc_total_n."',credit = '".$tot1_grand_total."',gst_amount = '".$tot1_gst_amount."' where id = '".$old_cust_linkid."'";
    $result_goods= mysql_query($query_goods) or die('error in query '.mysql_error().$query_goods);
             
    $msg = "Project Invoice Deleted Successfully.";
}

if(isset($_POST['trans_id']) && $_POST['trans_id'] != "")
{

	$trans_id = $_POST['trans_id'];
	$del_query = "delete from payment_plan where trans_id = '".$trans_id."'";
	$del_result = mysql_query($del_query) or die("error in Transaction delete query ".mysql_error());
	$msg = "Transaction Deleted Successfully.";
}

if(isset($_POST['trans_id_1']) && $_POST['trans_id_1'] != "")
{ 
    $trans_id_1 = $_POST['trans_id_1'];
    
    $query_del="select *  from attach_file where id = '".$trans_id_1."'";
    $result_del= mysql_query($query_del) or die('error in query '.mysql_error().$query_del);
    $data_del = mysql_fetch_array($result_del);
    $second_id= $data_del['old_id'];
    $second_id2= $data_del['old_id2'];
    $second_id3= $data_del['old_id3'];
    $fnm = $data_del['file_name'];
    
    $del_query_1 = "delete from attach_file where id = '".$trans_id_1."'";
    $del_result_1 = mysql_query($del_query_1) or die("error in Transaction delete query ".mysql_error());
    
    unlink("transaction_files/$fnm");
     $del_query_2 = "delete from attach_file where id = '".$second_id."'";
    $del_result_2 = mysql_query($del_query_2) or die("error in Transaction delete query ".mysql_error());
    
    $del_query_2 = "delete from attach_file where id = '".$second_id2."'";
    $del_result_2 = mysql_query($del_query_2) or die("error in Transaction delete query ".mysql_error());
    
    $del_query_2 = "delete from attach_file where id = '".$second_id3."'";
    $del_result_2 = mysql_query($del_query_2) or die("error in Transaction delete query ".mysql_error());
    
    
    $msg = "Transaction Deleted Successfully.";
}
if(mysql_real_escape_string(trim($_REQUEST['file_button'])) == "Submit")
{
	if($_FILES["attach_file"]["name"] != "")
	{
		$attach_file_name=mysql_real_escape_string(trim($_REQUEST['attach_file_name']));
		$attach_file_id=mysql_real_escape_string(trim($_REQUEST['attach_file_id']));
		$temp = explode(".", $_FILES["attach_file"]["name"]);
		$arr_size = count($temp);
		$extension = end($temp);
        
        $invoice_flag=mysql_real_escape_string(trim($_REQUEST['invoice_flag']));		
        if($invoice_flag=="1")
        {
        $query3="insert into attach_file set attach_id = '".$attach_file_id."', file_name = '".$new_file_name."'";
        $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
        $link_id_4 = mysql_insert_id();
        $new_file_name = $attach_file_name.'_'.$link_id_4.'_'.date("d_M_Y").'.'.$extension;
        move_uploaded_file($_FILES["attach_file"]["tmp_name"],"transaction_files/" . $new_file_name);
        $query5_1="update attach_file set file_name = '".$new_file_name."' where id = '".$link_id_4."'";
        $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);
        }
        else
       {
		$query="select link_id from payment_plan where id = '".$attach_file_id."'";
		$result= mysql_query($query) or die('error in query '.mysql_error().$query);
		$data = mysql_fetch_array($result);
		
		$link_id_2 = $data['link_id'];
	
		$query3="insert into attach_file set attach_id = '".$attach_file_id."', link_id = '".$link_id_2."',file_name = '".$new_file_name."'";
		$result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
        $link_id_4 = mysql_insert_id();
        $new_file_name = $attach_file_name.'_'.$link_id_4.'_'.date("d_M_Y").'.'.$extension;
		
		$query4="insert into attach_file set attach_id = '".$link_id_2."', link_id = '".$attach_file_id."',old_id = '".$link_id_4."',file_name = '".$new_file_name."'";
		$result4= mysql_query($query4) or die('error in query '.mysql_error().$query4);
        
         $link_id_5 = mysql_insert_id();
         
    $query5_1="update attach_file set old_id = '".$link_id_5."',file_name = '".$new_file_name."' where id = '".$link_id_4."'";
    $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);
    
        move_uploaded_file($_FILES["attach_file"]["tmp_name"],"transaction_files/" . $new_file_name);
	} }
	
	
}


//code by amit
$query_part_fromdate = "";
$query_part_todate = "";
$query_part_subdivision = "";
$opening_bal = 0;
$opening_bal_str = "Opening Balance  ";
$closing_bal_str = "Closing Balance AS ON ".date("d-m-Y",time())."&nbsp;&nbsp;";

if($_REQUEST['subdivision'])
$query_part_subdivision = " AND subdivision = ".$_REQUEST['subdivision'];

if($_REQUEST['from_date'])
{
    $from_date = strtotime(mysql_real_escape_string(trim($_REQUEST['from_date'])));
    $query_part_fromdate = " AND payment_date>=".$from_date;

    $sql_total_upto_fromdate = "SELECT SUM(credit) AS Total, SUM(debit) AS TotalDr FROM payment_plan WHERE project_id = '".$_REQUEST['project_id']."' ".$query_part_subdivision." AND payment_date< ".$from_date;
    $result_total_upto_fromdate = mysql_query($sql_total_upto_fromdate);
    $result_set_upto_fromdate = mysql_fetch_assoc($result_total_upto_fromdate);
    $total_cr_upto_fromdate = $result_set_upto_fromdate['Total'];
    $total_dr_upto_fromdate = $result_set_upto_fromdate['TotalDr'];

    $opening_bal = $total_cr_upto_fromdate-$total_dr_upto_fromdate;
    $opening_bal_str .= " AS ON ".$_REQUEST['from_date']."&nbsp;&nbsp;";
}

if($_REQUEST['to_date'])
{
    $to_date = strtotime(mysql_real_escape_string(trim($_REQUEST['to_date'])));
    $query_part_todate = " AND payment_date<=".$to_date;
    $closing_bal_str = "Closing Balance AS ON ".$_REQUEST['to_date']."&nbsp;&nbsp;";
}

$sql = "SELECT * FROM payment_plan WHERE project_id = '".$_REQUEST['project_id']."' ".$query_part_subdivision.$query_part_fromdate.$query_part_todate." ORDER BY payment_date DESC";
$result = mysql_query($sql);
echo $resultrow_count = mysql_num_rows($result);

$sql_total = "SELECT SUM(credit) AS Total, SUM(debit) AS TotalDr FROM payment_plan WHERE project_id = '".$_REQUEST['project_id']."' ".$query_part_subdivision.$query_part_fromdate.$query_part_todate;
$result_total = mysql_query($sql_total);

$result_set = mysql_fetch_assoc($result_total);
//print_r($result_set);
$total_cr = $result_set['Total'];
$total_dr = $result_set['TotalDr'];

$closing_bal = $total_cr-$total_dr+$opening_bal;

$prev_bal = 0;

$i=0;

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
        <a href="project_subdivisionlist.php?project=<?= $_REQUEST['project_id']?>" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
                    <input type="button" name="print_button" id="print_button" value="" class="button_print" onClick="return print_data();"  />
                    
            <script src="dist/jquery.table2excel.min.js"></script>
            <!--<script src="jquery.min.js"></script>-->
            <!--<script type="text/javascript" src="script.js"></script>-->
            <input type="button" id="export_to_excel" value="" class="button_export" >
            <input type="button" id="search" value="" class="button_search1" onClick="search_display();" >
    
            <input type="hidden" id="print_header" name="print_header" value="Project - <?php echo get_field_value("name","project","id",$_REQUEST['project_id']); ?> Ledger</h3>">
		
        </td>
                </tr>
            </table>
            <?php include_once("main_heading_close.php") ?>
            
        <!-------------->
            <?php include_once("main_search_open.php") ?>
        <input type="hidden" name="search_check_val" id="search_check_val" value="0" >
        <form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" >
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					
                <td width="50">From</td>
                <td width="150" nowrap>
                <input type="text"  name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>" style="width:100px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('from_date')" style="cursor:pointer"/>
                </td>
				
                <td width="50">To</td>
                <td width="150" nowrap>
                <input type="text"  name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>" style="width:100px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('to_date')" style="cursor:pointer"/>
                </td>
				 
				<td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='project-ledger.php?project_id=<?php echo $_REQUEST['project_id']; ?>';"  /></td>
				</tr>
			</table>
			<input type="hidden" name="search_action" id="search_action" value="Search"  />
			
			</form>
		
        <?php include_once("main_search_close.php") ?>
        <!-------------->

            <?php include_once("main_body_open.php") ?>
  
	
		
            <table width="100%" style="padding:5px;" >
  <tr><td>
		<div id="ledger_data" style="height: 450px;  overflow-y: scroll; overflow-x: scroll; padding:0px;">
        <h4 class="u-text-2 u-text-palette-1-base1 " style="padding:0px; margin:0px;">
       Subdivision - <?php 
       if($_REQUEST['subdivision']) 
       echo get_field_value('name','subdivision','id',$_REQUEST['subdivision'])."&nbsp; <a href='project-ledger.php?project_id=".$_REQUEST['project_id']."'>All Subdivision Entry</a>";
       else echo "All";
       ?></h4>
        <table class="data" id="my_table" border="1" cellpadding="1" cellspacing="0" style="border: 1px solid #111111;">
        <tr style="display:none ;">
            <td><b>Project Ledger : </b></td>
            <td><b><?php echo get_field_value("name","project","id",$_REQUEST['project_id']); ?></b></td>
            <td><b> Generated On :</b></td>
			<td><b><?php echo getTime(); echo "("; $username_1=get_field_value("full_name","user","userid",$_SESSION['userId']); echo $username_1; echo ")";?></b></td>
			<td colspan="4"></td>
        </tr>    

			<tr >
            <thead class="report-header">
                <th class="data">S.No.</th>
                <th class="data">Date</th>
                <th class="data">Subdivision</th>
                <th class="data">Customer</th>
                <th class="data">Description</th>
                <th class="data">Debit</th>
                <th class="data">Credit</th>
                <th class="data">Balance</th>
                <th class="data noExl">Action</th>
			</thead>
			</tr>
			<?php

			if($resultrow_count > 0)
			{
                    $font_red = "style = 'color:red;'";
                    $font_green = "style = 'color:green;'";
                    $font_black = "style='color:black;'";

                    if($closing_bal<0)
                    $font_color = $font_red;
                    else
                    $font_color = $font_green;

                echo "<tr>
                    <td class='data' colspan='5' align='right' ".$font_black."><b>".$closing_bal_str."</b></td>
                    <td class='data' ".$font_red."><b>&#8377;&nbsp;".number_format($total_dr,2,'.','')."</b></td>
                    <td class='data' ".$font_green."><b>&#8377;&nbsp;".number_format($total_cr,2,'.','')."</b></td>
                    <td class='data' ".$font_color."><b>&#8377;&nbsp;".number_format($closing_bal,2,'.','')."</b></td>
                    <td class='data'></td>
                    </tr>";

				$i = 0;
                while($data = mysql_fetch_assoc($result))
                {
                    if($data['description']=='Opening Balance')
                    continue;

                    //print_r($data);
                    //echo "<br><br>New<br><br>";
                    if($i==0)
                    $balance = $closing_bal;
                    else
                    $balance = $closing_bal-$prev_bal;

                    if($data[debit])
                    $debit_amount = "&#8377;&nbsp;".number_format($data[debit],2,'.','');
                    else
                    $debit_amount = '';

                    if($data[credit])
                    $credit_amount = "&#8377;&nbsp;".number_format($data[credit],2,'.','');
                    else
                    $credit_amount = '';

                    if($balance<0)
                    $font_color = $font_red;
                    else
                    $font_color = $font_green;

                    echo "<tr>
                        <td class='data' nowrap>".($i+1)."</td>
                        <td class='data' nowrap>".date("d-m-Y",$data['payment_date'])."</td>
                        <td class='data'>".get_field_value('name','subdivision','id',$data[subdivision])."</td>
                        <td class='data'>".get_field_value('full_name','customer','cust_id',$data[on_customer])."</td>
                        <td class='data'>".$data[description]."</td>
                        <td class='data' nowrap ".$font_red.">".$debit_amount."</td>
                        <td class='data' nowrap ".$font_green.">".$credit_amount."</td>
                        <td class='data' nowrap ".$font_color.">&#8377;&nbsp;".number_format($balance,2,'.','')."</td>
                        <td class='data noExl' nowrap>";
?>

                    <?php //////////////   delete File start     ///////////////?>
						 <?php 
                        if($data['trans_type_name']=="instmulti_sale_goods")
                        { ?>
                        <?php if($data['invoice_id'] != "" && $data['invoice_id'] != 0) { ?>
                        &nbsp;<a href="javascript:account_transaction_invoice(<?php echo $data['id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>
                        <?php    
                        }}
                        else
						 if($data['trans_id'] != "" && $data['trans_id'] != 0) 
                        { ?>
						&nbsp;<a href="javascript:account_transaction(<?php echo $data['trans_id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>
						<?php } ?>
                        <?php //////////////    File delete end    ///////////////?>  
                        <?php //////////////   Attach File start     ///////////////?>
					<?php  if($data['trans_type_name']=="instmulti_sale_goods")
                        {  ?>
                        &nbsp;<a href="javascript:void(0);" title="Attach File" onClick="return invoice_attach_file_function('<?php echo $data['invoice_id']; ?>');" ><img src="images/images.jpg" width="20" ></a>
                        <?php
                        }else{ ?>
                    	&nbsp;<a href="javascript:void(0);" title="Attach File" onClick="return attach_file_function('<?php echo $data['id']; ?>');" ><img src="images/images.jpg" width="20" ></a>
                        <?php } ?>
                         <?php //////////////   Attach File end     ///////////////?>
                           <?php //////////////   edit File start     ///////////////?>                 
                        <?php
                        if($data['trans_type_name']=="receive_goods")
                        {  ?>
                        <a href="edit_project_ledger.php?project_id=<?php echo $data['project_id']; ?>&trans_id=<?php echo $data['trans_id']; ?>&id=<?php echo $data['id']; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } ?>
                        <?php
                        if($data['trans_type_name']=="sale_goods")
                        {  ?>
                        <a href="edit_sale_invoice.php?trans_id=<?php echo $data['trans_id']; ?>&id=<?php echo $data['id']; ?>&trsns_pname=<?php echo "project-ledger"; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } ?>
                         <?php
                        if($data['trans_type_name']=="inst_receive_goods")
                        {  ?>
                        <a href="edit-instant-receive-goods.php?trans_id=<?php echo $data['trans_id']; ?>&id=<?php echo $data['id']; ?>&trsns_pname=<?php echo "project-ledger-inst-receive-goods"; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } ?>
                        
                        <?php
                        if($data['trans_type_name']=="inst_sale_goods")
                        {  ?>
                        <a href="edit-instant-sale-invoice.php?trans_id=<?php echo $data['trans_id']; ?>&id=<?php echo $data['id']; ?>&trsns_pname=<?php echo "project-ledger-inst-sale-goods"; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } ?>
                 
                 <?php
                        if($data['trans_type_name']=="instmulti_sale_goods")
                        {  ?>
                        <a href="edit_instant-sale-invoice_multiple.php?trans_id=<?php echo $data['trans_id']; ?>&id=<?php echo $data['id']; ?>&trsns_pname=<?php echo "project-ledger-inst-sale-goods"; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } ?>
                  <?php //////////////   edit File end     ///////////////?>
                           <?php //////////////   view File start     ///////////////?>                 
                  <?php
                        $total_rows_view=0;
                        if($data['trans_type_name']=="instmulti_sale_goods")
                        {  
                            
                        $query_view="select *  from attach_file where attach_id = '".$data['invoice_id']."'";
                        $result_view= mysql_query($query_view) or die('error in query '.mysql_error().$query_view);
                        $total_rows_view = mysql_num_rows($result_view);
                        if($total_rows_view != 0)
                        { ?>
                           <a href="javascript:void(0);" title="View File" onClick="return view_file_function('<?php echo $data['invoice_id']; ?>');" >View</a>     <?php           }
                            
                        }else
                        {
                        $query_view="select *  from attach_file where attach_id = '".$data['id']."'";
                        $result_view= mysql_query($query_view) or die('error in query '.mysql_error().$query_view);
                        $total_rows_view = mysql_num_rows($result_view);
                        if($total_rows_view != 0)
                        { ?>
                           <a href="javascript:void(0);" title="View File" onClick="return view_file_function('<?php echo $data['id']; ?>');" >View</a>       <?php           }
                        }

?><?php //////////////   view File end     ///////////////?>



<?php
                    echo "</td>
                        </tr>";

                    if($data[credit])
                    {
                        $prev_bal += $data[credit];
                        //$opening_bal = $balance - $data[credit];
                    }
                    if($data[debit])
                    {
                        $prev_bal -= $data[debit];
                        //$opening_bal = $balance + $data[debit];
                    }

                    $i++;
                }

                

                if($_REQUEST['subdivision']=="" || $_REQUEST['from_date']!="")
                {
                    if($opening_bal<0)
                    $font_color = $font_red;
                    else if($opening_bal==0)
                    $font_color = $font_black;
                    else
                    $font_color = $font_green;

                    echo "<tr>
                    <td class='data' colspan='5' align='right' ".$font_black."><b>".$opening_bal_str."</b></td>
                    <td class='data'></td>
                    <td class='data'></td>
                    <td class='data' ".$font_color."><b>&#8377;&nbsp;".number_format($opening_bal,2,'.','')."</b></td>
                    <td class='data'></td>
                    </tr>";
                }
				
			}
			else
			{
				?>
				<tr class="data" >
					<td  width="30px" colspan="9" class="record_not_found" >Record Not Found</td>
				</tr>
				<?php
			}
			?>
			
		</table>
		</div>
		</td></tr></table>		
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
        $("#my_table").table2excel({        
        
            exclude: ".noExl",
            name: "Developer data",
            filename: "project_ledger",
            fileext: ".xls",        
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true            
        });  
    });
   
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
newWin.document.write("<h3 align='center'>"+print_header1+" </h3>");

$("#header1").hide();
$('table tr').find('td:eq(10)').hide();
newWin.document.write(divToPrint.outerHTML);
newWin.print();

$('table tr').find('td:eq(9)').show();
$("#header1").show();
newWin.close();
}


</script>

