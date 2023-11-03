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
if(isset($_POST['trans_id_tds']) && $_POST['trans_id_tds'] != "")
{ //  echo "hello";
//exit;

    $trans_id_tds = $_POST['trans_id_tds'];
    
    $query_del="select *  from tds_due_info where id = '".$trans_id_tds."'";
$result_del= mysql_query($query_del) or die('error in query '.mysql_error().$query_del);
$data_del = mysql_fetch_array($result_del);
$fnm= $data_del['cert_file_name'];
 
    unlink("tds_files/$fnm");
    $del_query_1 = "update tds_due_info set cert_file_name=''   where id = '".$trans_id_tds."'";
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
    
    if($_REQUEST['cust_id']!="All"){
        $customerdata ="and cust_id='".$_REQUEST['cust_id']."'";
    }else { $customerdata=""; }
    
    if($_REQUEST['project_id']!="All"){
        $projectdata ="and project_id='".$_REQUEST['project_id']."'";
    }else { $projectdata=""; }
    
    if($_REQUEST['subdivision']!="All"){
        $subdivisiondata ="and subdivision='".$_REQUEST['subdivision']."'";
    }else { $subdivisiondata=""; }
    
    
     $select_query = "select *  from goods_details where  tds_subdivision = '".$_REQUEST['tds_subdivision_id']."' and tds_amount > '0' ".$customerdata." ".$projectdata."".$subdivisiondata." ORDER BY payment_date DESC,id DESC ";
    $select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
    $select_total = mysql_num_rows($select_result);
    
    
    $select_query3_1 = "select SUM(tds_amount) as total_tds from goods_details  where  tds_subdivision = '".$_REQUEST['tds_subdivision_id']."' ".$customerdata." ".$projectdata."".$subdivisiondata."";
                
                $select_result3_1 = mysql_query($select_query3_1) or die('error in query select tds_subdivision query '.mysql_error().$select_query3_1);
                $select_data3_1 = mysql_fetch_array($select_result3_1);
                //echo $select_data3['total_credit']-$select_data3['total_debit'];
                //$bal_credit,$bal_debit
                $bal_1=$select_data3_1['total_tds'];

    
    
}else
if(mysql_real_escape_string(trim($_REQUEST['search_action'])) == "enddate")
{
    $from_date = strtotime(mysql_real_escape_string(trim($_REQUEST['from_date'])));
    
    $to_date = strtotime(mysql_real_escape_string(trim($_REQUEST['to_date'])));

    if($_REQUEST['cust_id']!="All"){
        $customerdata ="and cust_id='".$_REQUEST['cust_id']."'";
    }else { $customerdata=""; }
    
    if($_REQUEST['project_id']!="All"){
        $projectdata ="and project_id='".$_REQUEST['project_id']."'";
    }else { $projectdata=""; }
    
    if($_REQUEST['subdivision']!="All"){
        $subdivisiondata ="and subdivision='".$_REQUEST['subdivision']."'";
    }else { $subdivisiondata=""; }
    
	$select_query  = "select *  from payment_plan where  tds_subdivision = '".$_REQUEST['tds_subdivision_id']."' and credit>0 and credit!='null' and cust_id!='null' and tds_amount > '0' and payment_date >= '".$from_date."' and payment_date <= '".$to_date."' ".$customerdata." ".$projectdata."".$subdivisiondata." ORDER BY payment_date DESC,id DESC ";
    
	
	$select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);
	
	
                $select_query3_1 = "select SUM(tds_amount) as total_tds from payment_plan  where  tds_subdivision = '".$_REQUEST['tds_subdivision_id']."' and  credit>0 and credit!='null' and cust_id!='null' and tds_amount > '0' and payment_date <= '".$to_date."'  ".$customerdata." ".$projectdata."".$subdivisiondata."";
                
                $select_result3_1 = mysql_query($select_query3_1) or die('error in query select tds_subdivision query '.mysql_error().$select_query3_1);
                $select_data3_1 = mysql_fetch_array($select_result3_1);
                //echo $select_data3['total_credit']-$select_data3['total_debit'];
                //$bal_credit,$bal_debit
                $bal_1=$select_data3_1['total_tds'];
                $search_start ="1";
                
                 $select_query3_2 = "select SUM(tds_amount) as total_tds from payment_plan  where  tds_subdivision = '".$_REQUEST['tds_subdivision_id']."' and credit>0 and credit!='null' and cust_id!='null' and payment_date <= '".$from_date."' ".$customerdata." ".$projectdata."".$subdivisiondata."";
                
                $select_result3_2 = mysql_query($select_query3_2) or die('error in query select tds_subdivision query '.mysql_error().$select_query3_1);
                $select_data3_2 = mysql_fetch_array($select_result3_2);
                //echo $select_data3['total_credit']-$select_data3['total_debit'];
                //$bal_credit,$bal_debit
                $bal_1=$select_data3_1['total_tds'];
	            $bal_2=$select_data3_2['total_tds'];
}
else
{
	$select_query = "select *  from payment_plan where  tds_subdivision = '".$_REQUEST['tds_subdivision_id']."' and tds_amount > '0' and credit>0 and credit!='null' and cust_id!='null' ORDER BY payment_date DESC,id DESC ";
	$select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
	$select_total = mysql_num_rows($select_result);

    $select_query3_1 = "select SUM(tds_amount) as total_tds from payment_plan  where  tds_subdivision = '".$_REQUEST['tds_subdivision_id']."' and credit>0 and credit!='null' and cust_id!='null'";
                
                $select_result3_1 = mysql_query($select_query3_1) or die('error in query select tds_subdivision query '.mysql_error().$select_query3_1);
                $select_data3_1 = mysql_fetch_array($select_result3_1);
                //echo $select_data3['total_credit']-$select_data3['total_debit'];
                //$bal_credit,$bal_debit
                $bal_1=$select_data3_1['total_tds'];
                //$search_start ="1";
                
                 //$select_query3_2 = "select SUM(tds_amount) as total_tds from goods_details  where  tds_subdivision = '".$_REQUEST['tds_subdivision_id']."' and payment_date <= '".$from_date."' ".$customerdata." ".$projectdata."".$subdivisiondata."";
                
                //$select_result3_2 = mysql_query($select_query3_2) or die('error in query select tds_subdivision query '.mysql_error().$select_query3_1);
                //$select_data3_2 = mysql_fetch_array($select_result3_2);
                //echo $select_data3['total_credit']-$select_data3['total_debit'];
                //$bal_credit,$bal_debit
                //$bal_1=$select_data3_1['total_tds'];
                $bal_2="0";
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
        Output tds subdivision - <?php echo get_field_value("name","tds_subdivision","id",$_REQUEST['tds_subdivision_id']); ?> Ledger</h4>
  </td>
        <td width="" style="float:right;">
        <input type="button" name="print_button" id="print_button" value="" class="button_print" onClick="return print_data();"  />
                        <a href="output_tds.php" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
                   
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
  <table>
    <tr><td>
<form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" >
<input type="hidden" name="tds_subdivision_id" id="tds_subdivision_id" value="<?php echo $_REQUEST['tds_subdivision_id']; ?>">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    
                    <td>Customer List</td>
                    <td>
                        <select name="cust_id" id="cust_id" style="width:250px; height: 25px;">
        <option value="All">All</option>
        <?php 
        $select_search1 = "select cust_id  from goods_details where  tds_subdivision = '".$_REQUEST['tds_subdivision_id']."' group BY cust_id ";
    $search_result1 = mysql_query($select_search1) or die('error in query select tds_subdivision query '.mysql_error().$select_search1);
    $select_total1 = mysql_num_rows($search_result1);
        while($search_data1 = mysql_fetch_array($search_result1))
                { 
                    // $project1_nm = get_field_value("name","project","id",$row_series['project_id']); 
                    //$subdivision1_nm = get_field_value("name","subdivision","id",$row_series['subdivision']);  
                    $customer_nm = get_field_value("full_name","customer","cust_id",$search_data1['cust_id']);  
                    ?>
                <option  value="<?php echo $search_data1['cust_id']; ?>"  <?php if($_REQUEST['cust_id']==$search_data1['cust_id']){ echo "selected='selected'"; } ?>><?php echo $customer_nm; ?></option>
              <?php   }
         ?>
    </select>
                    </td>
                    
                </tr>
                <tr>
                    
                    <td width="150">Project List</td>
                    <td>
                        <select name="project_id" id="project_id" style="width:250px; height: 25px;">
        <option value="All">All</option>
        <?php 
        $select_search2 = "select project_id  from goods_details where  tds_subdivision = '".$_REQUEST['tds_subdivision_id']."' group BY project_id ";
    $search_result2 = mysql_query($select_search2) or die('error in query select tds_subdivision query '.mysql_error().$select_search2);
    $select_total2 = mysql_num_rows($search_result2);
        while($search_data2 = mysql_fetch_array($search_result2))
                {  
                    $project1_nm = get_field_value("name","project","id",$search_data2['project_id']); 
                    ?>
                <option value="<?php echo $search_data2['project_id']; ?>" <?php if($_REQUEST['project_id']==$search_data2['project_id']){ echo "selected='selected'"; } ?>><?php echo $project1_nm; ?></option>
              <?php   }
         ?>
    </select>
                    </td>
                    
                </tr>
                <tr>
                    
                    <td width="150">Subdivision</td>
                    <td>
                        <select name="subdivision" id="subdivision" style="width:250px; height: 25px;">
        <option value="All">All</option>
        <?php 
        $select_search3 = "select subdivision  from goods_details where  tds_subdivision = '".$_REQUEST['tds_subdivision_id']."' group BY subdivision ";
    $search_result3 = mysql_query($select_search3) or die('error in query select tds_subdivision query '.mysql_error().$select_search3);
    $select_total3 = mysql_num_rows($search_result3);
        while($search_data3 = mysql_fetch_array($search_result3))
                { 
                    $subdivision1_nm = get_field_value("name","subdivision","id",$search_data3['subdivision']);  
                    ?>
                <option value="<?php echo $search_data3['subdivision']; ?>" <?php if($_REQUEST['subdivision']==$search_data3['subdivision']){ echo "selected='selected'"; } ?> ><?php echo $subdivision1_nm; ?></option>
              <?php   }
         ?>
    </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="button" name="search_button2" id="search_button2" class="button" value="search without date" onClick="return search_withoutdate();" >
    
                    </td>
                    
                </tr>
                
            </table>
            <input type="hidden" name="search_action1" id="search_action1" value="Search"  />
                </td></tr>
                <tr><td>
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					
					<td width="50">
					&nbsp;&nbsp;From
					</td>
					<td width="150">
					<input type="text"  name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>" style="width:120px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('from_date')" style="cursor:pointer"/>
                    <!--<input type="text" name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>"  readonly="" style="width:100px;" >-->
					
				 </td>
				
				 <td width="50">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To
					</td>
					<td width="225">
					<input type="text"  name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>" style="width:120px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('to_date')" style="cursor:pointer"/>
                    <!--<input type="text" name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>"  readonly="" style="width:100px;" >-->
					
				 </td>
				 
					<td align="left" valign="top" > 
                    <input type="button" name="search_button1" id="search_button1" value="search with date" onClick="return search_date();" class="button" >               
                    
                    </td>
					<td align="right" valign="top" >
                        <input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='output_tds_subdivision_ledger.php?tds_subdivision_id=<?php echo $_REQUEST['tds_subdivision_id']; ?>';"  />
                       
                    </td>
					
					
				</tr>
			</table>
			<input type="hidden" name="search_action" id="search_action" value=""  />
                </td></tr></table>
			</form>
  <?php include_once("main_search_close.php") ?>
 <!-------------->

<?php include_once("main_body_open.php") ?>
<table width="100%" style="padding:10px 0px 0px 0px;" >
  <tr><td>

	
    <input type="hidden" id="print_header" name="print_header" value="<h3>Output tds subdivision - <?php echo get_field_value("name","tds_subdivision","id",$_REQUEST['tds_subdivision_id']); ?> Ledger</h3>">
		
		
		<div id="ledger_data" style="height: 390px;  overflow-y: scroll;overflow-x: scroll;padding:0px;">
        <table class="data" id="my_table" border="1" cellpadding="1" cellspacing="0" style="border: 1px solid #111111;">
        <tr style="display:none ;">
            <td><b>TDS Subdivision Ledger :</b></td><td><b><?php echo get_field_value("name","tds_subdivision","id",$_REQUEST['tds_subdivision_id']); ?></b></td>
            <td><b>Customer :</b></td><td><b><?php $customer_nm = get_field_value("full_name","customer","cust_id",$_REQUEST['cust_id']); echo $customer_nm; ?></b></td>
            
            <td><b>Project :</b></td><td><b><?php  $project1_nm = get_field_value("name","project","id",$_REQUEST['project_id']); echo $project1_nm; ?></b></td>
            <td><b>Subdivision :</b></td><td><b><?php  $subdivision1_nm = get_field_value("name","subdivision","id",$_REQUEST['subdivision']); echo $subdivision1_nm; ?></b></td>
            
        </tr>    
        <tr style="display:none ;">
            <td><b>Period Start :</b></td><td><b><?php if($_REQUEST['from_date']!=""){ echo date("d-m-Y",strtotime($_REQUEST['from_date']));  }?></b></td>
            
            <td><b>Period End :</b></td><td><b><?php if($_REQUEST['to_date']!=""){ echo date("d-m-Y",strtotime($_REQUEST['to_date'])); } ?></b></td>
            
            <td><b>GST Reports Generated On :</b></td><td><b><?php echo getTime(); echo "("; $username_1=get_field_value("full_name","user","userid",$_SESSION['userId']); echo $username_1; echo ")";?></b></td>

        </tr>  


            <tr >
            <thead class="report-header">
				<th class="data w_30 "  >S.<br>No.</th>
				<th class="data w_50"  >Date</th>
				<th class="data w_80" >Project</th>
                <th class="data w_80" >Customer</th>
				<th class="data w_80" >Sub Division</th>
				<th class="data " >Description</th>
				<th class="data w_50"  >tds%</th>
                <th class="data w_80"  >Received</br>Amount</th>
				<th class="data w_80"  >tds Amount</th>
				<th class="data w_50"  id="header1"><b>File</b></th>
                </thead>
				
			</tr>
			<?php
			if($select_total > 0)
			{
				$i = 1;
				
				while($select_data = mysql_fetch_array($select_result))
				{
					$temp_payment_id = $select_data['payment_id'];
                      
                    if($i==1)
                    {
                        
                         if($search_start=="1")
                         {
                                  ?>
                 <tr class="data">
                        <td class="data data_fixed top_40 bg_w" ><?php //echo $i; ?></td>
                        <td class="data data_fixed top_40 bg_w"><b> <?php echo date("d-m-Y",$to_date); ?></b></td>
                        <td class="data data_fixed top_40 bg_w"></td>
                        <td class="data data_fixed top_40 bg_w"></td>
                        <td class="data data_fixed top_40 bg_w"></td>
                        <td class="data data_fixed top_40 bg_w"><b><?php echo "Closing Balance"; ?></b></td>
                        <td class="data data_fixed top_40 bg_w"><b></b></td>
                        <td class="data data_fixed top_40 bg_w"><b></b></td>
                        <td class="data data_fixed top_40 bg_w" <?php if($bal_1<0) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?> ><?php  echo number_format($bal_1,2,'.',''); //echo currency_symbol().number_format($bal_1,2,'.',''); ?> </td>
                        <td class="data data_fixed top_40 bg_w" nowrap="nowrap"></td>                        
                    </tr>
                    <?php 
                         }
                         else{
                             ?>
                    <tr class="data">
                        <td class="data data_fixed top_40 bg_w" ><?php //echo $i; ?></td>
                        <td class="data data_fixed top_40 bg_w"><b> <?php echo date("d-m-Y",$select_data['payment_date']); ?></b></td>
                        <td class="data data_fixed top_40 bg_w"></td>
                        <td class="data data_fixed top_40 bg_w"></td>
                        <td class="data data_fixed top_40 bg_w"></td>
                        <td class="data data_fixed top_40 bg_w"><b><?php echo "Closing Balance"; ?></b></td>
                        <td class="data data_fixed top_40 bg_w"><b></b></td>
                        <td class="data data_fixed top_40 bg_w"><b></b></td>
                        <td class="data data_fixed top_40 bg_w" <?php if($bal_1<0 ) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?>><?php  echo number_format($bal_1,2,'.','');//echo currency_symbol().number_format($bal_1,2,'.',''); ?> </td>
                        <td class="data data_fixed top_40 bg_w" nowrap="nowrap"></td>                        
                    </tr>
                             <?php } 
                    }
                      //Project,Customer,Division,descri,tds%,ctds,stds,total
					 ?>
					<tr class="data">
						<td class="data" ><?php echo $i; ?></td>
						<td class="data"><?php echo date("d-m-y",$select_data['payment_date']); ?></td>
						<td class="data"><?php	echo get_field_value("name","project","id",$select_data['project_id']); ?>	</td>
                        <td class="data"><?php    echo get_field_value("full_name","customer","cust_id",$select_data['cust_id']); ?>   </td>
                        <td class="data"><?php    echo get_field_value("name","subdivision","id",$select_data['subdivision']); ?>   </td>
                    	<td class="data" style="font-size:10px;"><?php echo $select_data['description']; ?></td>
						<td class="data" style="font-size:10px;"><?php	
                         $tds_due_query_tes_per = "select *  from goods_details where invoice_id = '".$select_data['invoice_id']."' ";
                         $tds_due_result_tes_per = mysql_query($tds_due_query_tes_per) or die("error in date list query ".mysql_error());
                         $total_tds_tes_per = mysql_num_rows($tds_due_result_tes_per);
                         $tot_tds="";
                         if($total_tds_tes_per > 0)
                             {
                                while( $find_tds_tes_per = mysql_fetch_array($tds_due_result_tes_per))
				                {
				
                                
                                 if($tot_tds=="")
                                 { $tot_tds= $find_tds_tes_per['tds_per']; }else
                                 {
                                    $tot_tds=$tot_tds.",".$find_tds_tes_per['tds_per'];
                                 }
                                }
                             }
                        echo $tot_tds;
                        //echo $select_data['tds_per']; echo $select_data['id']; ?>	</td>
                        
                        <?php $ctds=$select_data['tds_amount']/2;
                                $stds = $select_data['tds_amount']/2;
                         ?>
                        <td class="data"><?php	//Calculate received amount
                         $tds_due_query1 = "select SUM(amount) as amount,SUM(received_amount) as received_amount  from tds_due_info where invoice_id = '".$select_data['invoice_id']."' ";
                         $tds_due_result2 = mysql_query($tds_due_query1) or die("error in date list query ".mysql_error());
                         $total_tds2 = mysql_num_rows($tds_due_result2);
                         if($total_tds2 > 0)
                             {

                                 $find_tds = mysql_fetch_array($tds_due_result2);
                                 echo $find_tds['received_amount'];
                             }
                            
                        ?>	</td>
                    	<td class="data" ><?php  
                        echo number_format($select_data['tds_amount'],2,'.',''); 
                        //echo currency_symbol().number_format($select_data['tds_amount'],2,'.',''); ?> 
                       
                        <?php 
                     //   $date_old = $select_data['payment_date'];
                       
                        
                        ?>
                        </td>
						<td class="data" nowrap="nowrap">
						<?php /* if($select_data['trans_id'] != "" && $select_data['trans_id'] != 0) { ?>
						&nbsp;<a href="javascript:account_transaction(<?php echo $select_data['trans_id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>
						<?php } */?>
						&nbsp;<a href="javascript:void(0);" title="Attach File" onClick="return attach_file_function('<?php echo $select_data['invoice_id']; ?>');" ><img src="images/images.jpg" width="20" ></a>
                    <?php
                    if($select_data['trans_type_name']=="instmulti_sale_goods")
                        {  ?>
                        <a href="edit_instant-sale-invoice_multiple.php?trans_id=<?php echo $select_data['trans_id']; ?>subdivision=<?php echo $select_data['subdivision']; ?>&cust_id=<?php echo $select_data['cust_id']; ?>&invoice_id=<?php echo $select_data['invoice_id']; ?>&trsns_pname=<?php echo "tds-ledger-instmulti-sale-goods"; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                 <?php  } ?>
                 
                        <?php
                        $total_rows_view=0;
                        $query_view="select *  from attach_file where attach_id = '".$select_data['invoice_id']."'";
                        $result_view= mysql_query($query_view) or die('error in query '.mysql_error().$query_view);
                        $total_rows_view_1 = mysql_num_rows($result_view);

                        if($total_rows_view_1>0)
                        {
                            $total_rows_view=$total_rows_view_1;
                        }

                        $query_tds="select *  from tds_due_info where invoice_id = '".$select_data['invoice_id']."' and cert_file_name!=''";
                        $result_tds= mysql_query($query_tds) or die('error in query '.mysql_error().$query_tds);
                        $total_rows_tds = mysql_num_rows($result_tds);
                        if($total_rows_tds>0)
                        {
                            $total_rows_view=$total_rows_tds;
                        }

                        if($total_rows_view != 0)
                        { ?>
                            <a href="javascript:void(0);" title="View File" onClick="return view_file_function('<?php echo $select_data['invoice_id']; ?>');" >View</a>
        <?php           }

?> 
						</td>
						
					</tr>
				<?php
					$i++;
				}
				  if($search_start=="1")
                {
                    ?>
                     <tr class="data">
                        <td class="data" ><?php //echo $i; ?></td>
                        <td class="data"><b> <?php echo date("d-m-Y",$from_date); ?></b></td>
                       <td class="data"></td>
                        <td class="data"></td>
                        <td class="data"></td>
                        <td class="data"><b><?php echo "Opening Balance"; ?></b></td>
                        <td class="data"><b></b></td>
                   
                         <td class="data"><?php	 ?>	</td>
                        
                        <td class="data" <?php if($bal_2<0) { ?> style="color:#FF0000;" <?php }else{?> style="color:#000000;" <?php } ?> ><?php  echo number_format($bal_2,2,'.','');//echo currency_symbol().number_format($bal_2,2,'.',''); ?> </td>
                        <td class="data" nowrap="nowrap"></td>                        
                    </tr>

                    
                    <?php
    

                }
               
			}
			else
			{
				?>
				<tr class="data" >
					<td  colspan="10" class="record_not_found" >Record Not Found</td>
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
<tr><td valign="top" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;Attach File</td>
			<td><input type="file" name="attach_file" id="attach_file" value="" class="w_250" ></td></tr>
			
			<tr><td valign="top" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;Attach File Name</td>
			<td><input type="text" id="attach_file_name"  name="attach_file_name" value="" class="w_250" autocomplete="off"/></td></tr>
			
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
        <form name="trans_form_tds" id="trans_form_tds" action="" method="post" >
        
    <input type="hidden" name="trans_id_tds" id="trans_id_tds" value="" >
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
            filename: "output_tds_Subdivision_ledger",
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
	n_type="tds";
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

function account_transaction_tds(trans_id_1)
{
    if(confirm("Are you sure want to delete?!!!!!......"))
    {
        $("#trans_id_tds").val(trans_id_1);
        $("#trans_form_tds").submit();
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

