<?php
session_start();
include_once("set_con.php");

//current date & time
date_default_timezone_set('Asia/Calcutta');
echo $current_datentime = date('d-m-Y h:i:s A', time());

if(isset($_SESSION['userId'])) 
$userId=$_SESSION['userId'];
else
$userId='2';

$query = "Select * From customer where customer_type='tenant' and type='customer' ORDER BY full_name";
$result = mysql_query($query);
$numrow = mysql_num_rows($result);
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

<body  data-home-page-title="" class="u-body u-xl-mode" data-lang="en" onload="set_option()">
<?php include_once ("top_header2.php"); ?> 
<?php include_once ("top_menu.php"); ?>
<?php include_once("main_heading_open.php") ?>
  
<table style="width:100%;" border="0" style="padding:0px 0px; margin-top:-10px;">
    <tr>
        <td style="align:top;" align="left">
        <h4 class="u-text-2 u-text-palette-1-base " style="padding:0px; margin:0px;">Tenant Profile Report</h4>
        </td>
        <td width="" style="float:right;">
        <input type="button" name="print_button" id="print_button" value="" class="button_print" onClick="return print_data();">
        <script src="dist/jquery.table2excel.min.js"></script>
        <input type="button" id="export_to_excel" value="" class="button_export" >&nbsp;&nbsp;
        <input type="hidden" id="print_header" name="print_header" value="GST Return">
        <!--<input type="button" id="search" value="" class="button_search1" onClick="search_display();">-->
        </td>
    </tr>
</table>
<?php include_once("main_heading_close.php"); ?>
<?php include_once("main_search_open.php"); ?>
<?php include_once("main_search_close.php"); ?>
<?php include_once("main_body_open.php"); ?>

<div id="ledger_data" style="width:98%; padding-right: 10px;">

        <table id="my_table" class="data" border="1" cellpadding="1" cellspacing="0" style="width:100%; border: 0px solid #111111;">
        <tr class="data noExl">
            <td class="data" colspan="9" nowrap style="border:none;">
            <?php echo "<b>Tenant Profile Report AS On ".$current_datentime; ?>
            </td>
        </tr>    
        <tr align="center">
            <th class="data" width="30px" nowrap>S.No.</th>
            <th class="data" nowrap>Tenant Name</th>
            <th class="data" nowrap>First Agreement Date</th>
            <th class="data" nowrap>Current Agreement Date</th>
            <th class="data" nowrap>Current Rent</th>
            <th class="data" nowrap>Current Maintenance</th>
            <th class="data" nowrap>Next Renewal Rent</th>
            <th class="data" nowrap>Next Renewal Due Date</th>
            <th class="data" nowrap>Agreement Type</th>
        </tr>
            <?php
            if($numrow > 0)
            {
                $i=1;
                while($tenant = mysql_fetch_array($result))
                {
                    //$ii=$i+$startResults;    
                     ?>
                    <tr class="data">
                        <td class="data" width="30px"  nowrap><?php echo $i; ?></td>
                        <td class="data" nowrap><?php echo $tenant['full_name']?></td>
                        <td class="data "  nowrap><?php echo date("d-m-Y",$tenant['tenant_first_rent_agree_date']); ?></td>
                        <td class="data "  nowrap><?php echo date("d-m-Y",$tenant['tenant_current_rent_agree_date']); ?></td>
                        <td class="data" nowrap>&#8377;&nbsp;<?php echo $tenant['tenant_current_rent']?></td>
                        <td class="data" nowrap>&#8377;&nbsp;<?php echo $tenant['current_maintenance']?></td>
                        <td class="data "  nowrap>&#8377;&nbsp;<?= $tenant['tenant_nextrenewal_rent']?></td>
                        <td class="data "  nowrap>
                        <?php 
                        if($tenant['tenant_nextrenawal_duedate']) 
                            echo date("d-m-Y",$tenant['tenant_nextrenawal_duedate']);
                        ?>
                        </td>
                        <td class="data" nowrap><?= $tenant['tenant_registered']?></td>
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
        
<div class="clear"></div>
<?php include_once("footer.php"); ?>
</div>
</body>
</html>
<script src="js/jquery-ui.js"></script>

<script>

$(document).ready(function(){

    var filedate = <?php echo json_encode($current_datentime) ?>;

    if($( "#company_name").val())
        filename = $( "#company_name option:selected" ).text();

    $("#export_to_excel").click(function(){
        $("#my_table").table2excel({        
        
            exclude: ".noExl",
            name: "Developer data",
            filename: "Tenant Profile Report AS On "+filedate,
            fileext: ".xls",        
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true ,
        });  
    });

   $("#c_gstin").autocomplete({
			source: "gstin-ajax.php"
		});
   $("#company_name").autocomplete({
			source: "company-ajax.php"
		});
});

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
