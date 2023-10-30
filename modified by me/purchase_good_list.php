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


/*blocked by amit*/
if(mysql_real_escape_string(trim($_REQUEST['search_action'])) == "enddate")
{ 
    $from_date = strtotime(mysql_real_escape_string(trim($_REQUEST['from_date'])));
    
    $to_date = strtotime(mysql_real_escape_string(trim($_REQUEST['to_date'])));

     if($_REQUEST['cust_id']){
        $supplier_data = explode(" - ",$_REQUEST['cust_id']);
        $customerdata ="and cust_id='".$supplier_data[1]."'";
    }else { $customerdata=""; }
    
    if($_REQUEST['project_id'])
    {
        $project_data =explode(" - ",$_REQUEST['project_id']);
        $projectdata ="and on_project='".$project_data[1]."'";
    }else { $projectdata=""; }
    
    if($_REQUEST['invoice_id']!="All"){
        $invoice_iddata ="and trans_id='".$_REQUEST['invoice_id']."'";
    }else { $invoice_iddata=""; }
    
     if($_REQUEST['subdivision'])
     {
         $subdivision = explode(" - ",$_REQUEST['subdivision']);
         echo $subdivision[1];
        $subdivisiondata ="and subdivision='".$subdivision[1]."'";
    }else { $subdivisiondata=""; }
    
    if($from_date!=""){
        $from_datedata ="and payment_date >= '".$from_date."'";
    }else { $from_datedata=""; }
    
    if($to_date!=""){
        $to_datedata ="and payment_date <= '".$to_date."'";
    }else { $to_datedata=""; }
            //select * from payment_plan where trans_id > 0 and trans_type_name in('receive_goods','inst_receive_goods') ".$customerdata." ".$subdivisiondata." ".$projectdata."".$invoice_iddata." ".$from_datedata." ".$to_datedata." group by trans_id ORDER BY payment_date DESC ";
    $query = "select * from payment_plan where trans_id > 0 and trans_type_name in('receive_goods','inst_receive_goods') ".$customerdata." ".$subdivisiondata." ".$projectdata."".$invoice_iddata." ".$from_datedata." ".$to_datedata." group by trans_id ORDER BY payment_date DESC ";
    $result = mysql_query($query) or die('error in query select invoice_issuer query '.mysql_error().$query);
    $total_row = mysql_num_rows($result);
    echo "test 1<br>";
}
else
{
    $query = "select * from payment_plan where trans_id > 0 and cust_id!='' and cust_id > 0  and trans_type_name in('receive_goods','inst_receive_goods') group by trans_id ORDER BY payment_date DESC ";
    $result = mysql_query($query) or die('error in query select  query '.mysql_error().$query);
    $total_row = mysql_num_rows($result);
    echo "test 0<br>";
}

$page = $_REQUEST['page'];
if($page < 1) $page = 1;
$numberOfPages = numberofpages();

if($_REQUEST['rpp'])
{$resultsPerPage = $_REQUEST['rpp'];}
else
{$resultsPerPage = 20;}

$startResults = ($page - 1) * $resultsPerPage;
echo $total_row."<br>";
echo $resultsPerPage."<br>";
echo $totalPages = ceil($total_row / $resultsPerPage);
echo "<br>".$page;


if(mysql_real_escape_string(trim($_REQUEST['search_action'])) == "enddate")
{
    $from_date = strtotime(mysql_real_escape_string(trim($_REQUEST['from_date'])));
    
    $to_date = strtotime(mysql_real_escape_string(trim($_REQUEST['to_date'])));

    if($_REQUEST['cust_id']){
        $supplier_data = explode(" - ",$_REQUEST['cust_id']);
        $customerdata ="and cust_id='".$supplier_data[1]."'";
    }else { $customerdata=""; }
    
    if($_REQUEST['project_id'])
    {
        $project_data =explode(" - ",$_REQUEST['project_id']);
        $projectdata ="and on_project='".$project_data[1]."'";
    }else { $projectdata=""; }
    
    if($_REQUEST['invoice_id']!="All"){
        $invoice_iddata ="and trans_id='".$_REQUEST['invoice_id']."'";
    }else { $invoice_iddata=""; }
    
     if($_REQUEST['subdivision'])
     {
         $subdivision = explode(" - ",$_REQUEST['subdivision']);
         echo $subdivision[1];
        $subdivisiondata ="and subdivision='".$subdivision[1]."'";
    }else { $subdivisiondata=""; }
    
    if($from_date!=""){
        $from_datedata ="and payment_date >= '".$from_date."'";
    }else { $from_datedata=""; }
    
    if($to_date!=""){
        $to_datedata ="and payment_date <= '".$to_date."'";
    }else { $to_datedata=""; }

    echo $select_query = "select * from payment_plan where trans_id > 0 and trans_type_name in('receive_goods','inst_receive_goods') ".$customerdata." ".$subdivisiondata." ".$projectdata."".$invoice_iddata." ".$from_datedata." ".$to_datedata." group by trans_id ORDER BY payment_date DESC LIMIT ".$startResults.", ".$resultsPerPage;
    $select_result = mysql_query($select_query) or die('error in query select bank query '.mysql_error().$select_query);
    $select_total = mysql_num_rows($select_result);
    echo 'select_query_num : '.$select_total;
}
else
{
    echo $select_query = "select * from payment_plan where trans_id > '0' and trans_type_name in('receive_goods','inst_receive_goods') group by trans_id ORDER BY payment_date DESC LIMIT ".$startResults.", ".$resultsPerPage;
    $select_result = mysql_query($select_query) or die('error in query select user query '.mysql_error().$select_query);
    $select_total = mysql_num_rows($select_result);
    echo 'select_query_num : '.$select_total;
}
print_r($_REQUEST);

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
        Purchase Goods List </h4>
  </td>
        <td width="" style="float:right;">
        <b>Rows Per Page</b>
        <?php
        //code by amit
        if($_REQUEST['rpp']==10)
            $sel_10 = 'selected';
        elseif($_REQUEST['rpp']==50)
            $sel_50 = 'selected';
        elseif($_REQUEST['rpp']==30)
            $sel_30 = 'selected';
        elseif($_REQUEST['rpp']==40)
            $sel_40 = 'selected';
        else
            $sel_20 = "selected";
        ?>
    <select name="rpp_select" id="rpp_select" onchange="document.getElementById('rpp').value=this.value; document.getElementById('search_form').submit();">
       <option value="10" <?= $sel_10; ?>>10</option>
       <option value="20" <?= $sel_20; ?>>20</option>
       <option value="30" <?= $sel_30; ?>>30</option>
       <option value="40">40</option>
       <option value="50">50</option>
    </select>
    &nbsp;
        <input type="button" name="print_button" id="print_button" value="" class="button_print" onClick="return print_data();"  />
    <script src="dist/jquery.table2excel.min.js"></script>
    <input type="button" id="export_to_excel" value="" class="button_export" >&nbsp;&nbsp;
     <input type="hidden" id="print_header" name="print_header" value="Purchase Goods List">
     <input type="button" id="search" value="" class="button_search1" onClick="search_display();" >

       </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>

<!-------------->
<?php include_once("main_search_open.php") ?>
<form name="search_form" id="search_form" action="" method="post">
  <input type="hidden" name="search_check_val" id="search_check_val" value="0" >
     
<input type="hidden" name="gst_subdivision_id" id="gst_subdivision_id" value="<?php echo $_REQUEST['gst_subdivision_id']; ?>">
            <table width="" border="1" align="left" cellpadding="0" cellspacing="0">
                <tr>
                
                    <td width="80">
                    &nbsp;&nbsp;From Date
                    </td>
                    <td width="280">
                    <input type="text"  name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>" style="width:250px; height: 25px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('from_date')" style="cursor:pointer"/>
                    
                   <!-- <input type="text" name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>"  readonly="" style="width:120px;" >-->
                 </td>
                
                 <td width="80">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To Date
                    </td>
                    <td width="280">
                    <input type="text"  name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>" style="width:250px; height: 25px;" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('to_date')" style="cursor:pointer"/>
                 </td>
                </tr>
                <tr>
                    
                    <td width="80px;">Supplier</td>
                    <td width="280px;">
                        <input type="text" name="cust_id" id="cust_id" value="<?= $_REQUEST['cust_id'] ?>" style="width:250px; height: 25px;">
                    </td>
                    
                    <td width="80">Project</td>
                    <td width="280px;">
                        <input type="text" name="project_id" id="project_id" style="width:250px; height: 25px;">
                    </td>
                </tr>
                
                <tr>
                    <td width="80px;">Subdivision</td>
                    <td width="280px;">
                        <input type="text" name="subdivision" id="subdivision" style="width:250px; height: 25px;">
                    </td>
                    
                    <td width="80">Trans No</td>
                    <td width="280px;">
                        <select name="invoice_id" id="invoice_id" style="width:250px; height: 25px;">
        <option value="All">All</option>
        <?php 
        $select_search3 = "select trans_id  from payment_plan where trans_type_name in('receive_goods','inst_receive_goods') group BY trans_id   ";
    $search_result3 = mysql_query($select_search3) or die('error in query select  query '.mysql_error().$select_search3);
    $select_total3 = mysql_num_rows($search_result3);
        while($search_data3 = mysql_fetch_array($search_result3))
                { 
                   // $subdivision1_nm = get_field_value("name","subdivision","id",$search_data3['subdivision']);  
                    ?>
                <option value="<?php echo $search_data3['trans_id']; ?>" <?php if($_REQUEST['invoice_id']==$search_data3['trans_id']){ echo "selected='selected'"; } ?> ><?php echo $search_data3['trans_id']; ?></option>
              <?php   }
         ?>
    </select>
    
                    </td>
                    
                </tr>
                <tr>
                
                    <td width="80"></td>
                    <td width="280px;">
                      
    
                    </td>
                    <td width="80px;"></td>
                    <td width="280px;">
                    <input type="button" name="search_button1" id="search_button1" value="Search" onClick="search_date();" class="button" >&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='purchase_good_list.php';"  />&nbsp;
                    </td>
                </tr>
                <tr><td colspan="4">&nbsp;</td></tr>
            </table>
            
            
                        <input type="hidden" name="search_action" id="search_action" value=""  />
                        <input type="hidden" name="page" id="page" value=""/>
                        <input type="hidden" name="rpp" id="rpp" value="<?= $_REQUEST['rpp']?>"/>
            </form>    
  
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
    <div id="adddiv" style="display:<?php if($error_msg != "") { ?>block<?php } else { ?>none<?php } ?>;">
    
    
        
        </div>
        

        <form name="user_form" id="user_form" action="" method="post" >
        <input type="hidden" name="action_perform" id="action_perform" value="" >
        <input type="hidden" name="del_id" id="del_id" value="" >
        <input type="hidden" name="count" id="count" value="<?php echo $i; ?>"  />    
        </form>  
        <div id="ledger_data" style="width:98%; padding-right: 10px; overflow-x: scroll;">
        <table class="data" id="my_table" border="1" cellpadding="1" cellspacing="0" style="border: 1px solid #111111;">
        <tr style="display:none ;">
            <td colspan="12" align="center">Purchase Goods List :</td>
        </tr>
    <tr style="display:none ;">
            <td colspan="12" align="center"> Generated Date & Time : <?php echo getTime(); ?></td>
    </tr>
            <tr align="center">
                <th class="data" width="30px" nowrap>S.No.</th>
                <th class="data" width="75px" nowrap>Date</th>
                <th class="data" nowrap>Invoice Id</th>
                <th class="data" nowrap>Supplier Name</th>
                <th class="data" nowrap>Supplier GSTIN</th>
                <th class="data" nowrap>Inv To</th>
                <th class="data" nowrap>Receiver GSTIN</th>
                <th class="data" nowrap>Sub Total</th>
                <th class="data" nowrap>GST (%)</th>
                <th class="data" nowrap>GST Amount</th>
                <th class="data" width="50px" nowrap>Grand Total</th>
                <th class="data" nowrap>Project Name</th>
                <th class="data" nowrap>Subdivision Name</th>
            </tr>
            <?php
            if($select_total > 0)
            {
                $i=1;
                while($select_data = mysql_fetch_array($select_result))
                {
                    $ii=$i+$startResults;    
                     ?>
                    <tr class="data">
                        <td class="data" width="30px" align="center" nowrap><?php echo $ii; ?></td>
                        <td class="data " align="center" nowrap><?php echo date("d-m-Y",$select_data['payment_date']); ?></td>
                        
                        <td class="data" id="hid1[]" nowrap>
                        <?php
                            if($select_data['invoice_id'])
                            echo $select_data['invoice_id'];
                        ?>
                        </td>
                        <td class="data" nowrap>
                        <?php //echo $select_data['cust_id'];
                        $customer_nm = get_field_value("full_name","customer","cust_id",$select_data['cust_id']); 
                        echo $customer_nm;
                         ?>
                         </td>
                         <td class="data" nowrap><?php //echo $select_data['cust_id'];
                        $gst_nm = get_field_value("supply_gst_no","customer","cust_id",$select_data['cust_id']); 
                        echo $gst_nm;
                         ?></td>

                        <td class="data" nowrap>
                        <?php
                            echo get_field_value("issuer_name","invoice_issuer","id",$select_data['invoice_issuer_id']);
                        ?>
                        </td>

                        <td class="data" nowrap>
                        <?php
                            echo get_field_value("gst_no","invoice_issuer","id",$select_data['invoice_issuer_id']);
                        ?>
                        </td>

                        <td class="data" nowrap>
                        <?php 
                            $subtot= $select_data['debit']-$select_data['gst_amount'];
                            echo number_format((float)$subtot, 2,'.','');
                        ?>
                        </td>
                        <td class="data" nowrap>
                        <?php
                        if($select_data['gst_amount']>1)
                        {
                            $subtot = $select_data['debit']-$select_data['gst_amount'];
                            $gstper = ($select_data['gst_amount']/$subtot)*100;
                            echo ceil($gstper).'%';
                        }
                        ?>
                        </td>
                        <td style="" class="data" nowrap>
                        <?php 
                        if($select_data['gst_amount']>1)
                        {
                         echo number_format((float)$select_data['gst_amount'], 2,'.','');
                        }
                        ?>
                        </td>
                        <td class="data" nowrap><span style="color:red;">&#8377;&nbsp; <?php echo number_format(floatval($select_data['debit']),2,'.',''); ?></span></td>

                        <td class="data" nowrap><?php 
                        $project_name = get_field_value("name","project","id",$select_data['on_project']); 
                         echo $project_name;
                         ?></td>
                        
                        <td class="data" nowrap>
                        <?php 
                        $subdivision_name = get_field_value("name","subdivision","id",$select_data['subdivision']); 
                         echo $subdivision_name;
                         ?>
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
                    <td align="center" colspan="14" class="record_not_found" >Sorry! No Record Available</td>
                </tr>
                <?php
            }
            ?>
            
        </table>
        </div>
        <div class="pagination" >
        
        <?php
            
        if($page > 1)
        {
            $page = $page-1;
            echo '<a href="javascript:void(0)" onclick="return show_records('.$page.')" ><span ><< prev</span></a>&nbsp';
            $page = $page+1;
        }
                            
        
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
        
        if ($page < $totalPages)
        {
            $page = $page+1;
            echo '<a href="javascript:void(0)" onclick="return show_records('.$page.')" >next >></a>&nbsp;';
            $page = $page-1;
        }
                        
        ?>
       
        </div>
    </div>
    
        </div>
<div class="clear"></div>
<?php
include_once("footer.php");
?>
</div>
</body>
</html>
<script src="js/jquery-ui.js"></script>

<script>

$(document).ready(function(){
    $("#export_to_excel").click(function(){
        $("#my_table").table2excel({        
        
            exclude: ".noExl",
            name: "Developer data",
            filename: "Purchase_Goods_List",
            fileext: ".xls",        
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true ,    
            
                   
        });  
    });

    $("#cust_id").autocomplete({
			source: "supplier-ajax.php"
		});
    $("#subdivision").autocomplete({
			source: "subdivision_search_ajax.php"
		});
   $("#project_id").autocomplete({
			source: "project-ajax.php"
		});
});
function show_records(getno)
{
    document.getElementById("page").value=getno;
    document.search_form.submit(); 
}

function search_date()
{
        $("#search_action").val("enddate");
        $("#search_form").submit();    
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

newWin.document.write(divToPrint.outerHTML);
newWin.print();

newWin.close();
   
}


</script>
