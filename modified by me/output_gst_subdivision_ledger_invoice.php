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
if(isset($_POST['trans_id']) && $_POST['trans_id'] != "")
{

	$trans_id = $_POST['trans_id'];
	$del_query = "delete from payment_plan where trans_id = '".$trans_id."'";
	$del_result = mysql_query($del_query) or die("error in Transaction delete query ".mysql_error());
	$msg = "Transaction Deleted Successfully.";
}

if(isset($_POST['trans_id_gst']) && $_POST['trans_id_gst'] != "")
{
    $trans_id_gst = $_POST['trans_id_gst'];
    
    $query_del="select *  from gst_due_info where id = '".$trans_id_gst."'";
$result_del= mysql_query($query_del) or die('error in query '.mysql_error().$query_del);
$data_del = mysql_fetch_array($result_del);
$fnm= $data_del['cert_file_name'];
 
    unlink("gst_files/$fnm");
    $del_query_1 = "update gst_due_info set cert_file_name=''   where id = '".$trans_id_gst."'";
    $del_result_1 = mysql_query($del_query_1) or die("error in Transaction delete query ".mysql_error());
       
   
    
    $msg = "Attach file Deleted Successfully.";
}

if(isset($_POST['trans_id_1']) && $_POST['trans_id_1'] != "")
{ //  echo "hello";
//exit;

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
    
   
    
    $msg = "Transaction Deleted Successfully.";
}
if(mysql_real_escape_string(trim($_REQUEST['file_button'])) == "Submit")
{
	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
	if($_FILES["attach_file"]["name"] != "")
	{
        $attach_file_name=mysql_real_escape_string(trim($_REQUEST['attach_file_name']));
        $attach_file_id=mysql_real_escape_string(trim($_REQUEST['attach_file_id']));
        $temp = explode(".", $_FILES["attach_file"]["name"]);
        $arr_size = count($temp);
        $extension = end($temp);
        
        $link_id_1= $attach_file_id;
    $query3="insert into attach_file set attach_id = '".$link_id_1."',file_name = '".$new_file_name."'";
    $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
    $link_id_4 = mysql_insert_id();
    
        $attach_file_name=mysql_real_escape_string(trim($_REQUEST['attach_file_name']));
        $temp = explode(".", $_FILES["attach_file"]["name"]);
        $arr_size = count($temp);
        $extension = end($temp);
        $new_file_name = $attach_file_name.'_'.$link_id_4.'_'.date("d_M_Y").'.'.$extension;
        move_uploaded_file($_FILES["attach_file"]["tmp_name"],"transaction_files/" . $new_file_name);
        
        
    
    
    $query5_1="update attach_file set file_name = '".$new_file_name."' where id = '".$link_id_4."'";
    $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);
    
	}
	
	
}
//withoutdate,enddate
if(mysql_real_escape_string(trim($_REQUEST['search_action'])) == "withoutdate")
{    
    if($_REQUEST['cust_id'])
    {
        $customer = explode(" - ",$_REQUEST['cust_id']);
        $customerdata ="and cust_id='".$customer[1]."'";
    }else { $customerdata=""; }
    
    $select_query = "select *  from payment_plan where  gst_subdivision = '".$_REQUEST['gst_subdivision_id']."' and gst_amount > '0' and	trans_type_name='instmulti_sale_goods' and cust_id!='' and cust_id!='NULL' ".$customerdata." ".$projectdata."".$subdivisiondata." ORDER BY payment_date DESC,id DESC ";
    $select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
    $select_total = mysql_num_rows($select_result);
    
    $select_query3_1 = "select SUM(gst_amount) as total_gst from payment_plan  where  gst_subdivision = '".$_REQUEST['gst_subdivision_id']."' and	trans_type_name='instmulti_sale_goods' and cust_id!='' and cust_id!='NULL' and payment_date > '1648665000'  ".$customerdata." ".$projectdata."".$subdivisiondata."";
                
    $select_result3_1 = mysql_query($select_query3_1) or die('error in query select gst_subdivision query '.mysql_error().$select_query3_1);
    $select_data3_1 = mysql_fetch_array($select_result3_1);
    $bal_1=$select_data3_1['total_gst'];
    $select_query_gst_rec = "select SUM(received_amount) as gst_received_amount from gst_due_info inner join  payment_plan on gst_due_info.payment_plan_id=payment_plan.id  and payment_plan.gst_subdivision = '".$_REQUEST['gst_subdivision_id']."' and payment_plan.gst_amount > '0'  and	payment_plan.trans_type_name='instmulti_sale_goods' and payment_plan.cust_id!='' and payment_plan.cust_id!='NULL' and payment_plan.payment_date > '1648665000'  ".$customerdata." ".$projectdata."".$subdivisiondata."";
    $select_result_gst_rec = mysql_query($select_query_gst_rec) or die('error in query select cash query '.mysql_error().$select_query_gst_rec);
    $select_data_gst_rec = mysql_fetch_array($select_result_gst_rec);
    $gst_tot_due=$bal_1-$select_data_gst_rec['gst_received_amount'];
    $gst_tot_receive=$select_data_gst_rec['gst_received_amount'];
    
    $gst_tot_due_tot = $gst_tot_due;
    $gst_tot_receive_tot = $gst_tot_receive;
    $bal_1_tot = $bal_1;    
}
else if(mysql_real_escape_string(trim($_REQUEST['search_action'])) == "enddate")
{
    $from_date = strtotime(mysql_real_escape_string(trim($_REQUEST['from_date'])));
    
    $to_date = strtotime(mysql_real_escape_string(trim($_REQUEST['to_date'])));

    if($_REQUEST['cust_id'])
    {
        $customer = explode(" - ",$_REQUEST['cust_id']);
        $customerdata ="and cust_id='".$customer[1]."'";
    }else { $customerdata=""; }
    
    $select_query  = "select *  from payment_plan where  gst_subdivision = '".$_REQUEST['gst_subdivision_id']."' and gst_amount > '0' and	trans_type_name='instmulti_sale_goods' and cust_id!='' and cust_id!='NULL' and payment_date >= '".$from_date."' and payment_date <= '".$to_date."'  ".$customerdata."  ORDER BY payment_date DESC,id DESC ";
	$select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
	
    $select_query3_1 = "select SUM(gst_amount) as total_gst from payment_plan  where  gst_subdivision = '".$_REQUEST['gst_subdivision_id']."' and gst_amount > '0' and	trans_type_name='instmulti_sale_goods' and cust_id!='' and cust_id!='NULL' and payment_date <= '".$to_date."' and payment_date > '1648665000'   ".$customerdata." ";
    $select_result3_1 = mysql_query($select_query3_1) or die('error in query select gst_subdivision query '.mysql_error().$select_query3_1);
    $select_data3_1 = mysql_fetch_array($select_result3_1);
    $bal_1=$select_data3_1['total_gst'];
    $search_start ="1";
                
    $select_query3_2 = "select SUM(gst_amount) as total_gst from payment_plan  where  gst_subdivision = '".$_REQUEST['gst_subdivision_id']."' and	trans_type_name='instmulti_sale_goods' and cust_id!='' and cust_id!='NULL' and payment_date < '".$from_date."' and payment_date > '1648665000'  ".$customerdata." ".$projectdata."".$subdivisiondata."";
    $select_result3_2 = mysql_query($select_query3_2) or die('error in query select gst_subdivision query '.mysql_error().$select_query3_1);
    $select_data3_2 = mysql_fetch_array($select_result3_2);
    $bal_2=$select_data3_2['total_gst'];

    $select_query_gst_rec = "select SUM(received_amount) as gst_received_amount from gst_due_info inner join  payment_plan on gst_due_info.payment_plan_id=payment_plan.id  and payment_plan.gst_subdivision = '".$_REQUEST['gst_subdivision_id']."' and payment_plan.gst_amount > '0'  and	payment_plan.trans_type_name='instmulti_sale_goods' and payment_plan.cust_id!='' and payment_plan.cust_id!='NULL' and payment_plan.payment_date > '1648665000' and payment_plan.payment_date <= '".$to_date."'  ".$customerdata." ".$projectdata."".$subdivisiondata."";
    $select_result_gst_rec = mysql_query($select_query_gst_rec) or die('error in query select cash query '.mysql_error().$select_query_gst_rec);
    $select_data_gst_rec = mysql_fetch_array($select_result_gst_rec);
    $gst_tot_due=$bal_1-$select_data_gst_rec['gst_received_amount'];
    $gst_tot_receive=$select_data_gst_rec['gst_received_amount'];

    $select_query_gst_rec_2 = "select SUM(received_amount) as gst_received_amount from gst_due_info inner join  payment_plan on gst_due_info.payment_plan_id=payment_plan.id  and payment_plan.gst_subdivision = '".$_REQUEST['gst_subdivision_id']."' and payment_plan.gst_amount > '0'  and	payment_plan.trans_type_name='instmulti_sale_goods' and payment_plan.cust_id!='' and payment_plan.cust_id!='NULL' and payment_plan.payment_date > '1648665000' and payment_plan.payment_date < '".$from_date."' ".$customerdata." ".$projectdata."".$subdivisiondata."";
    $select_result_gst_rec_2 = mysql_query($select_query_gst_rec_2) or die('error in query select cash query '.mysql_error().$select_query_gst_rec_2);
    $select_data_gst_rec_2 = mysql_fetch_array($select_result_gst_rec_2);
    $gst_tot_due_2=$bal_2-$select_data_gst_rec_2['gst_received_amount'];
    $gst_tot_receive_2=$select_data_gst_rec_2['gst_received_amount'];

    $select_query3_1_tot = "select SUM(gst_amount) as total_gst from payment_plan  where  gst_subdivision = '".$_REQUEST['gst_subdivision_id']."' and gst_amount > '0' and	trans_type_name='instmulti_sale_goods' and cust_id!='' and cust_id!='NULL' and payment_date >= '".$from_date."' and payment_date <= '".$to_date."'  and payment_date > '1648665000'   ".$customerdata." ";
    $select_result3_1_tot = mysql_query($select_query3_1_tot) or die('error in query select gst_subdivision query '.mysql_error().$select_query3_1_tot);
    $select_data3_1_tot = mysql_fetch_array($select_result3_1_tot);
    $bal_1_tot11=$select_data3_1_tot['total_gst'];
                
    $select_query_gst_rec_tot = "select SUM(received_amount) as gst_received_amount from gst_due_info inner join  payment_plan on gst_due_info.payment_plan_id=payment_plan.id  and payment_plan.gst_subdivision = '".$_REQUEST['gst_subdivision_id']."' and payment_plan.gst_amount > '0'  and	payment_plan.trans_type_name='instmulti_sale_goods' and payment_plan.cust_id!='' and payment_plan.cust_id!='NULL' and payment_plan.payment_date > '1648665000' and payment_plan.payment_date >= '".$from_date."' and payment_plan.payment_date <= '".$to_date."'  ".$customerdata." ".$projectdata."".$subdivisiondata."";
    $select_result_gst_rec_tot = mysql_query($select_query_gst_rec_tot) or die('error in query select cash query '.mysql_error().$select_query_gst_rec_tot);
    $select_data_gst_rec_tot = mysql_fetch_array($select_result_gst_rec_tot);
    $gst_tot_due__tot11=$bal_1_tot11-$select_data_gst_rec_tot['gst_received_amount'];
    $gst_tot_receive_tot11=$select_data_gst_rec_tot['gst_received_amount'];

    $gst_tot_due_tot = $gst_tot_due__tot11;
    $gst_tot_receive_tot = $gst_tot_receive_tot11;
    $bal_1_tot = $bal_1_tot11;
}
else
{
	$select_query = "select *  from payment_plan where  gst_subdivision = '".$_REQUEST['gst_subdivision_id']."' and gst_amount > '0'  and	trans_type_name='instmulti_sale_goods' and cust_id!='' and cust_id!='NULL' ORDER BY payment_date DESC,id DESC ";
	$select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
	
    $select_query3_1 = "select SUM(gst_amount) as total_gst from payment_plan  where  gst_subdivision = '".$_REQUEST['gst_subdivision_id']."' and gst_amount > '0'  and	trans_type_name='instmulti_sale_goods' and cust_id!='' and cust_id!='NULL' and payment_date > '1648665000' ";
                
    $select_result3_1 = mysql_query($select_query3_1) or die('error in query select gst_subdivision query '.mysql_error().$select_query3_1);
    $select_data3_1 = mysql_fetch_array($select_result3_1);
                
    $bal_1=$select_data3_1['total_gst'];
    $bal_2="0";
    $select_query_gst_rec = "select SUM(received_amount) as gst_received_amount from gst_due_info inner join  payment_plan on gst_due_info.payment_plan_id=payment_plan.id  and payment_plan.gst_subdivision = '".$_REQUEST['gst_subdivision_id']."' and payment_plan.gst_amount > '0'  and	payment_plan.trans_type_name='instmulti_sale_goods' and payment_plan.cust_id!='' and payment_plan.cust_id!='NULL' and payment_plan.payment_date > '1648665000' ";
    $select_result_gst_rec = mysql_query($select_query_gst_rec) or die('error in query select cash query '.mysql_error().$select_query_gst_rec);
    $select_data_gst_rec = mysql_fetch_array($select_result_gst_rec);
    $gst_tot_due=$bal_1-$select_data_gst_rec['gst_received_amount'];
    $gst_tot_receive=$select_data_gst_rec['gst_received_amount'];
    $gst_tot_due_tot = $gst_tot_due;
    $gst_tot_receive_tot = $gst_tot_receive;
    $bal_1_tot = $bal_1;
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
        GST subdivision - <?php echo get_field_value("name","gst_subdivision","id",$_REQUEST['gst_subdivision_id']); ?> Ledger</h4>
  </td>
        <td width="" style="float:right;">
        <input type="button" name="print_button" id="print_button" value="" class="button_print" onClick="return print_data();"  />
                    <a href="output_gst_subdivision.php" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
                   <script src="dist/jquery.table2excel.min.js"></script>
                <input type="button" id="export_to_excel" value="" class="button_export" >
                   
        <input type="button" id="search" value="" class="button_search1" onClick="search_display();" >
         </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>
 
<!-------------->
<?php include_once("main_search_open.php") ?>

<form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" >
<input type="hidden" name="search_check_val" id="search_check_val" value="0" >
<input type="hidden" id="print_header" name="print_header" value="<h3>Output GST subdivision - <?php echo get_field_value("name","gst_subdivision","id",$_REQUEST['gst_subdivision_id']); ?> Ledger</h3>">
<input type="hidden" name="gst_subdivision_id" id="gst_subdivision_id" value="<?php echo $_REQUEST['gst_subdivision_id']; ?>">
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td width="100px">Customer Name</td>
            <td width="300px">
                <input type="text" name="cust_id" id="cust_id" value="<?= $_REQUEST['cust_id']?>" style="width:280px; height: 25px;">
            </td>
            <td>
                <input type="button" name="search_button2" id="search_button2" class="button" value="search without date" onClick="return search_withoutdate();" >
            </td>
        </tr>
    </table>
            <input type="hidden" name="search_action1" id="search_action1" value="Search"  />
            
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					
                <td width="100">Date (From - To)</td>
                <td width="150">
                <input type="text"  name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>" style="width:120px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('from_date')" style="cursor:pointer"/>
                </td>
                <td width="150">
                <input type="text"  name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>" style="width:120px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('to_date')" style="cursor:pointer"/>
                </td>
				 
				<td align="left" valign="top" > 
                <input type="button" name="search_button1" id="search_button1" value="search with date" onClick="return search_date();" class="button" >               
                &nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='output_gst_subdivision_ledger_invoice.php?gst_subdivision_id=<?php echo $_REQUEST['gst_subdivision_id']; ?>';"  />
                </td>
					
				</tr>
			</table>
			<input type="hidden" name="search_action" id="search_action" value=""  />
			
			</form>
		
  <?php include_once("main_search_close.php") ?>
 <!-------------->
 
  <?php include_once("main_body_open.php") ?>
  <table width="100%" style="padding:5px 0px 0px 0px;" >
  <tr><td>
		<div id="ledger_data" style="height: 390px;  overflow-y: scroll;overflow-x: scroll;">
        <table class="data" id="my_table" width="100%" border="1" cellpadding="1" cellspacing="0" style="border: 1px solid #111111;">

            <tr >
            <thead class="report-header" style="height:70px;" >
				<th class="data"  style="font-size:10px;width:30px; position: sticky; top: 0;">S.No.</th>
				<th class="data" style="font-size:10px;width:70px; position: sticky; top: 0;">Date</th>
				<th class="data"  style="font-size:10px;width:120px; position: sticky; top: 0;">Project</th>
                <th class="data"  style="font-size:10px;width:120px; position: sticky; top: 0;">Customer</th>
				<th class="data"  style="font-size:10px;width:120px; position: sticky; top: 0;">Sub Division</th>
				<th class="data"  style="font-size:10px;width:120px; position: sticky; top: 0;">Description</th>
				<th class="data" style="font-size:10px;width:80px; position: sticky; top: 0;">GST%</th>
                <th class="data"  style="font-size:10px;width:80px; position: sticky; top: 0;">GST</br>Orignal Due</th>
                <th class="data"  style="font-size:10px;width:80px; position: sticky; top: 0;">GST Received</th>
                <th class="data"  style="font-size:10px;width:80px; position: sticky; top: 0;">Balance</br>Gst To Be</br>Received</th>
				<th class="data" id="header1" style="font-size:10px;width:50px; position: sticky; top: 0;"><b>File</b></th>
            </thead>
				
			</tr>
			<?php
			if($select_total > 0)
			{
				$i = 1;
				
				while($select_data = mysql_fetch_array($select_result))
				{
					$temp_payment_id = $select_data['id'];
                      
                    if($i==1)
                    {
                        
                         if($search_start=="1")
                         {
                                  ?>
                 <tr class="data">
                    <td class="data" style="width:30px; position: sticky; top: 65px; " bgcolor="white"><?php //echo $i; ?></td>
                    <td class="data"  style="width:70px; position: sticky; top: 65px;" bgcolor="white"><b> <?php echo date("d-m-y",$to_date); ?></b></td>
                    <td class="data"  style="width:120px; position: sticky; top: 65px;" bgcolor="white"></td>
                    <td class="data"  style="width:120px; position: sticky; top: 65px;" bgcolor="white"></td>
                    <td class="data"  style="width:120px; position: sticky; top: 65px;" bgcolor="white"></td>
                    <td class="data"  style="width:120px; position: sticky; top: 65px;" bgcolor="white"><b><?php echo "Closing Balance"; ?></b></td>
                    <td class="data"  style="width:80px; position: sticky; top: 65px;" bgcolor="white"><b></b></td>
                    <?php $cgst1=$bal_1/2;
                            $sgst1 = $bal_1/2;
                        ?> 
                    <td class="data"  style="width:80px; position: sticky; top: 65px;" bgcolor="white" <?php if($bal_1<0) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?> ><?php  echo number_format($bal_1,2,'.',''); ?> </td>
                    <td class="data"  style="width:80px; position: sticky; top: 65px;" bgcolor="white"><?php    echo number_format($gst_tot_receive,2,'.',''); ?>    </td> 
                    <td class="data"  style="width:80px; position: sticky; top: 65px;" bgcolor="white"><?php    echo number_format($gst_tot_due,2,'.',''); ?>    </td> 
                    <td class="data"  style="width:80px; position: sticky; top: 65px;" bgcolor="white" nowrap="nowrap"></td>                        
                </tr>
                             <?php
                         }
                         else{
                             ?>
                         <tr class="data" >
                        <td class="data"  style="width:30px; position: sticky; top: 65px;" bgcolor="white"><?php //echo $i; ?></td>
                        <td class="data"  style="width:70px; position: sticky; top: 65px;" bgcolor="white"><b> <?php echo date("d-m-y",$select_data['payment_date']); ?></b></td>
                        <td class="data"  style="width:120px; position: sticky; top: 65px;" bgcolor="white"></td>
                        <td class="data"  style="width:120px; position: sticky; top: 65px;" bgcolor="white"></td>
                        <td class="data"  style="width:120px; position: sticky; top: 65px;" bgcolor="white"></td>
                        <td class="data"  style="width:120px; position: sticky; top: 65px;" bgcolor="white"><b><?php echo "Closing Balance"; ?></b></td>
                        <td class="data"  style="width:80px; position: sticky; top: 65px;" bgcolor="white"><b></b></td>
                        <td class="data"  style="width:80px; position: sticky; top: 65px;" bgcolor="white" <?php if($bal_1<0 ) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?>><?php  echo number_format($bal_1,2,'.',''); ?> </td>
                        <td class="data"  style="width:80px; position: sticky; top: 65px;" bgcolor="white"><?php    echo number_format($gst_tot_receive,2,'.',''); ?>    </td> 
                        <td class="data"  style="width:80px; position: sticky; top: 65px;" bgcolor="white"><?php    echo number_format($gst_tot_due,2,'.',''); ?>    </td> 
                        
                        <td class="data"  style="width:80px; position: sticky; top: 65px;" bgcolor="white" nowrap="nowrap"></td>                        
                    </tr>
                             <?php } 
                    }
                    if($i==1)
                    {
					 ?>
                     <tr class="data" >
                        <td class="data"  style="width:30px; position: sticky; top: 90px;" bgcolor="white" ><?php //echo $i; ?></td>
                        <td class="data"  style="width:70px; position: sticky; top: 90px;" bgcolor="white"><b> </b></td>
                        <td class="data"  style="width:120px; position: sticky; top: 90px;" bgcolor="white"></td>
                        <td class="data"  style="width:120px; position: sticky; top: 90px;" bgcolor="white"></td>
                        <td class="data"  style="width:120px; position: sticky; top: 90px;" bgcolor="white"></td>
                        <td class="data"  style="width:120px; position: sticky; top: 90px;" bgcolor="white"><b><?php echo "Total"; ?></b></td>
                        <td class="data"  style="width:80px; position: sticky; top: 90px;" bgcolor="white"><b></b></td>
                        <td class="data"  style="width:80px; position: sticky; top: 90px;" bgcolor="white" <?php if($bal_1_tot<0 ) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?>><b><?php  echo number_format($bal_1_tot,2,'.',''); ?> </b></td>
                        <td class="data"  style="width:80px; position: sticky; top: 90px;" bgcolor="white"><b><?php    echo number_format($gst_tot_receive_tot,2,'.',''); ?>   </b> </td> 
                        <td class="data"  style="width:80px; position: sticky; top: 90px;" bgcolor="white"><b><?php    echo number_format($gst_tot_due_tot,2,'.',''); ?>    </b></td> 
                        
                        <td class="data"  style="width:80px; position: sticky; top: 90px;" bgcolor="white" nowrap="nowrap"></td>                        
                    </tr>
                    <?php } ?>
					<tr class="data">
						<td class="data" ><?php echo $i; ?></td>
						<td class="data"><?php echo date("d-m-y",$select_data['payment_date']);?></td>
						<td class="data"><?php	
                         $project_query1 = "select project_id from goods_details where trans_id = '".$select_data['trans_id']."' and invoice_id = '".$select_data['invoice_id']."' group by project_id";
                         $project_result2 = mysql_query($project_query1) or die("error in date list query ".mysql_error());
                        $imn=1;
                         while($project_gst = mysql_fetch_array($project_result2))
                        {
                            if($imn ==1)
                            {
                                echo "(";
                                echo get_field_value("name","project","id",$project_gst['project_id']);
                                $imn++;                       
                            }else
                            {
                                echo ",";
                                echo get_field_value("name","project","id",$project_gst['project_id']);
                                $imn++;
                            }
                        }
                        if($imn>1)
                        {
                            echo ")";
                        }
                        ?>	</td>
                        <td class="data"><?php    echo get_field_value("full_name","customer","cust_id",$select_data['cust_id']); ?>   </td>
                        <td class="data"><?php    
                         $subdivision_query1 = "select subdivision from goods_details where trans_id = '".$select_data['trans_id']."' and invoice_id = '".$select_data['invoice_id']."' group by subdivision";
                         $subdivision_result2 = mysql_query($subdivision_query1) or die("error in date list query ".mysql_error());
                        $imn=1;
                         while($subdivision_gst = mysql_fetch_array($subdivision_result2))
                        {
                            if($imn ==1)
                            {
                                echo "(";
                                echo get_field_value("name","subdivision","id",$subdivision_gst['subdivision']);
                                $imn++;                       
                            }else
                            {
                                echo ",";
                                echo get_field_value("name","subdivision","id",$subdivision_gst['subdivision']);
                                $imn++;
                            }
                        }
                        if($imn>1)
                        {
                            echo ")";
                        } 
                        ?>   </td>
						<td class="data" style="font-size:10px;"><?php echo $select_data['description']; ?></td>
						<td class="data" style="font-size:10px;"><?php	//echo $select_data['gst_per']; 
                         $tds_due_query_gst_per = "select *  from goods_details where invoice_id = '".$select_data['invoice_id']."' ";
                         $tds_due_result_gst_per = mysql_query($tds_due_query_gst_per) or die("error in date list query ".mysql_error());
                         $total_tds_gst_per = mysql_num_rows($tds_due_result_gst_per);
                         $tot_gst="";
                         if($total_tds_gst_per > 0)
                             {
                                while( $find_tds_gst_per = mysql_fetch_array($tds_due_result_gst_per))
				                {
				
                                
                                 if($tot_gst=="")
                                 { $tot_gst= $find_tds_gst_per['gst_per']; }else
                                 {
                                    $tot_gst=$tot_gst.",".$find_tds_gst_per['gst_per'];
                                 }
                                }
                             }
                        echo $tot_gst;
                        
                        ?>	</td>
                        
                        <?php $cgst=$select_data['gst_amount']/2;
                                $sgst = $select_data['gst_amount']/2;
                         ?>
                        <?php
                         $gst_due_query1 = "select SUM(amount) as amount,SUM(received_amount) as received_amount  from gst_due_info where payment_plan_id = '".$select_data['id']."' and invoice_id = '".$select_data['invoice_id']."' and received_amount > 0";
                         $gst_due_result2 = mysql_query($gst_due_query1) or die("error in date list query ".mysql_error());
                         $total_gst2 = mysql_num_rows($gst_due_result2);
                         $find_gst = mysql_fetch_array($gst_due_result2);
                         $due_gst_final = $select_data['gst_amount']-$find_gst['received_amount'];
                        
                        ?>
                        
						<td class="data" ><?php  
                        if($select_data['payment_date']>'1648665000')
                        {
                        echo number_format($select_data['gst_amount'],2,'.','');
                         } ?> 
                       
                        </td>
						<td class="data"><?php 
                        if($select_data['payment_date']>'1648665000')
                        {
                            echo number_format($find_gst['received_amount'],2,'.',''); 
                        }
                        ?> </td>
                        <td class="data"><?php 
                        if($select_data['payment_date']>'1648665000')
                        {
                            echo number_format($due_gst_final,2,'.',''); 
                        }
                        ?> </td>
                       
                        <td class="data" nowrap="nowrap">
						&nbsp;<a href="javascript:void(0);" title="Attach File" onClick="return attach_file_function('<?php echo $select_data['invoice_id']; ?>');" ><img src="images/images.jpg" width="20" ></a>
                    <?php
                    if($select_data['trans_type_name']=="instmulti_sale_goods")
                        {  ?>
                        <a href="edit_instant-sale-invoice_multiple.php?trans_id=<?php echo $select_data['trans_id']; ?>subdivision=<?php echo $select_data['subdivision']; ?>&cust_id=<?php echo $select_data['cust_id']; ?>&invoice_id=<?php echo $select_data['invoice_id']; ?>&trsns_pname=<?php echo "gst-ledger-instmulti-sale-goods"; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                     <?php  } 
                        $total_rows_view=0;
                        $query_view="select *  from attach_file where attach_id = '".$select_data['invoice_id']."'";
                        $result_view= mysql_query($query_view) or die('error in query '.mysql_error().$query_view);
                        $total_rows_view_1 = mysql_num_rows($result_view);
                        if($total_rows_view_1>0)
                        {
                            $total_rows_view=$total_rows_view_1;
                        }

                        $query_gst="select *  from gst_due_info where invoice_id = '".$select_data['invoice_id']."' and cert_file_name!=''";
                        $result_gst= mysql_query($query_gst) or die('error in query '.mysql_error().$query_gst);
                        $total_rows_gst = mysql_num_rows($result_gst);
                        if($total_rows_gst>0)
                        {
                            $total_rows_view=$total_rows_gst;
                        }

                        if($total_rows_view != 0)
                        { ?>
                            <a href="javascript:void(0);" title="View File" onClick="return view_file_function('<?php echo $select_data['invoice_id']; ?>');" >View</a>
                    <?php   }?> 
						</td>
						
					</tr>
				<?php
					$i++;
				}
				  if($search_start=="1")
                {
                    ?>
                     <tr class="data">
                        <td class="data"><?php //echo $i; ?></td>
                        <td class="data"><b> <?php echo date("d-m-Y",$from_date); ?></b></td>
                       <td class="data"></td>
                        <td class="data"></td>
                        <td class="data"></td>
                        <td class="data"><b><?php echo "Opening Balance"; ?></b></td>
                        <td class="data"><b></b></td>
                        <td class="data" <?php if($bal_2<0) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?> ><?php  echo number_format($bal_2,2,'.',''); ?> </td>
                        <td class="data"><?php  echo number_format($gst_tot_receive_2,2,'.',''); ?>    </td>
                        <td class="data"><?php  echo number_format($gst_tot_due_2,2,'.',''); ?>    </td>
                        <td class="data"> </td>
                    </tr>

                    
                    <?php
    

                }
               
			}
			else
			{
				?>
				<tr class="data" >
					<td  colspan="12" class="record_not_found" >Record Not Found</td>
				</tr>
				<?php
			}
			?>
			
		</table>
		</div>
        </td></tr></table>
        <?php include_once("main_body_close.php") ?>
        <?php include_once ("footer.php"); ?>

<div id="attach_div" style="position:absolute;top:50%; left:40%; width:500px; height:180px; margin:-100px 0 0 -50px;z-index:100; display:none; background-color:#FFFFFF; border:8px solid #999999;" >
<form name="attach_form" id="attach_form" method="post" action="" onSubmit="return attach_validation();" enctype="multipart/form-data" >
<table cellpadding="0" cellspacing="0" border="1" width="100%" >
<tr><td valign="top" align="right" colspan="2" ><img src="images/close.gif" onClick="return close_div();" ></td></tr>
<tr><td valign="top" align="left" >&nbsp;&nbsp;Attach File</td>
			<td><input type="file" name="attach_file" id="attach_file" class="w_250" value="" ></td></tr>
			
			<tr><td valign="top" align="left" >&nbsp;&nbsp;Attach File Name</td>
			<td><input type="text" id="attach_file_name"  name="attach_file_name" class="w_250" value="" autocomplete="off"/></td></tr>
			
			<tr><td></td><td>
			<input type="submit" class="button" name="file_button" id="file_button" value="Submit" >
			</td></tr>
</table>
<input type="hidden" id="attach_file_id"  name="attach_file_id" value="" />
</form>
</div>
<div id="view_div" style="position:absolute;top:50%; left:40%; width:510px; min-height:250px; margin:-100px 0 0 -50px;z-index:100; display:none; background-color:#FFFFFF; border:8px solid #999999;" >

</div>
<form name="trans_form" id="trans_form" action="" method="post" >
		
		<input type="hidden" name="trans_id" id="trans_id" value="" >
		</form>
                
<form name="trans_form_1" id="trans_form_1" action="" method="post" >
        
        <input type="hidden" name="trans_id_1" id="trans_id_1" value="" >
        </form>
         
<form name="trans_form_gst" id="trans_form_gst" action="" method="post" >
        
    <input type="hidden" name="trans_id_gst" id="trans_id_gst" value="" >
</form>
</body>
</html>
<script src="js/jquery-ui.js"></script>
<script>

 
$(document).ready(function(){
    $("#export_to_excel").click(function(){
        $("#my_table").table2excel({        
            exclude: ".noExl",
            name: "Developer data",
            filename: "output_GST_Subdivision_ledger",
            fileext: ".xls",        
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true            
        });  
    });

    $("#cust_id").autocomplete({
			source: "customer-ajax.php"
		});
});


function close_view()
{
	$('#view_div').hide("slow");
}
function view_file_function(id)
{
	n_type="gst";

	$('#view_div').show("slow");
	$.ajax({
		url: "attach_file_ajax.php?id="+id+'&invoice_type='+n_type,
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
function search_date()
{
    //document.getElementById("search_action").value="enddate";
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
    }else{
        $("#search_action").val("enddate");
        $("#search_form").submit();
    }
        
    
}

function search_withoutdate()
{
    //document.getElementById("search_action").value="withoutdate";
    
        $("#search_action").val("withoutdate");
        $("#search_form").submit();
    
}
function search_valid()
{
	 
	
	
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

function account_transaction_gst(trans_id_1)
{
    if(confirm("Are you sure want to delete?!!!!!......"))
    {
        $("#trans_id_gst").val(trans_id_1);
        $("#trans_form_gst").submit();
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
$('table tr').find('td:eq(10)').hide();
newWin.document.write(divToPrint.outerHTML);
newWin.print();
//$('tr').children().eq(7).show();

$('table tr').find('td:eq(10)').show();
$("#header1").show();
newWin.close();
   
   /* printMe=window.open();
    printMe.document.write(document.getElementById("").innerHTML);
    printMe.print();
    printMe.close();*/
}

</script>

