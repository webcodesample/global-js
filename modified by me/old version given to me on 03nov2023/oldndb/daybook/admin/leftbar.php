<?php 
$authority1 = $_SESSION['authority'];
$arr=explode(",",$authority1);
$page_name = basename($_SERVER['PHP_SELF']); ?>
<div style="width:185px; float:left;  position: fixed; overflow: hidden; " >
<style>

.sub_menu ul {list-style: none; padding: 0; margin: 0;font-size: 13px; font-weight:bold; text-align:right;}
.sub_menu ul li {
	width: 172px;
	/*background: url(img/icon-left.png) no-repeat left center, url(img/green.jpg) repeat left center;*/
	/*padding: 10px 0 10px 10px; margin-top: 8px;margin-left: 9px;*/
	padding-top:5px;
	padding-bottom:5px;
    
	}
.sub_menu ul li a {
	width: 100%;
	color: #2e2e2e;
    cursor: pointer;
	/*padding: 10px 65px 10px 20px;*/
	-webkit-transition: padding-left 250ms ease-out;
	-moz-transition: padding-left 250ms ease-out;
	-o-transition: padding-left 250ms ease-out;
}
.sub_menu ul li a:hover {
    padding-right: 5px;
	text-decoration: none;
	color:#008040;
	text-shadow: 1px 1px #496d2a;
}
</style>
<script>
function show_hide(id)
{
	for(var i=1;i<=10;i++)
	{
		if(i == id)
		{
			$("#submenu_"+i).slideDown("slow");
		}
		else
		{
			$("#submenu_"+i).slideUp("slow");
		}
		
	}
}
</script>
<div id="leftBar" >
	<ul> 
		<li><a href="index.php">Dashbord</a></li>
		<?php if(in_array(4,$arr) || in_array(5,$arr)) { ?>
		<li><a href="cash.php">Cash</a></li>
		<?php } if(in_array(6,$arr) || in_array(7,$arr)) { ?>
		<li><a href="bank.php">Bank</a></li>
		<?php } if(in_array(8,$arr) || in_array(9,$arr)) { ?>
		<li><a href="project.php">Project</a></li>
		<?php } if(in_array(10,$arr) || in_array(11,$arr)) { ?>
		<li><a href="customer.php">Customer</a></li>
		<?php } if(in_array(12,$arr) || in_array(13,$arr)) { ?>
		<li><a href="supplier.php">Supplier</a></li>
		<?php } if(in_array(14,$arr) || in_array(15,$arr)) { ?>
		<li><a href="internal-transfer.php">Internal Transfer</a></li>
		<?php } if(in_array(16,$arr) || in_array(17,$arr)|| in_array(18,$arr)) { ?>
		<li><a href="javascript:show_hide(1);">Payment</a></li>
		<?php }  ?>
		
		
	</ul>
	
</div>
<div class="sub_menu" id="submenu_1" style="width:185px; <?php if($page_name == "make-payment.php" || $page_name == "receive-payment.php" ) { echo 'display:block;'; } else { echo 'display:none;'; } ?>  ">
	<ul >
		<?php if(in_array(17,$arr)) { ?>
		<li><a href="make-payment.php">Make Payment</a></li>
		<?php } if(in_array(18,$arr)) { ?>
		<li><a href="receive-payment.php">Receive Payment</a></li>
		<?php } ?>
	 </ul>
</div>
<?php if(in_array(19,$arr) || in_array(20,$arr)|| in_array(21,$arr)|| in_array(48,$arr)) { ?>
<div id="leftBar"  >
    
    <ul><li><a href="javascript:show_hide(4);">Purchase Goods :</a></li></ul>
    
</div>
<?php }  ?>
<div class="sub_menu" id="submenu_4" style="width:185px; <?php if($page_name == "receive-goods.php" || $page_name == "instant-receive-goods.php" || $page_name == "purchase_good_list.php") { echo 'display:block;'; } else { echo 'display:none;'; } ?>  ">
    <ul >
    <?php if(in_array(48,$arr)) { ?>
        <li ><a href="purchase_good_list.php">Receive Goods list</a></li>
        <?php } if(in_array(20,$arr)) { ?>
        <li ><a href="receive-goods.php">Receive Goods</a></li>
        <?php } if(in_array(21,$arr)) { ?>
        <li ><a href="instant-receive-goods.php">Instant Receive Goods</a></li>
        <?php } ?>
     </ul>
</div>
<?php if(in_array(22,$arr) || in_array(23,$arr)||  in_array(24,$arr)|| in_array(25,$arr)) { ?>
<div id="leftBar"  >
  
    <ul><li><a href="javascript:show_hide(5);">Sale Invoice :</a></li></ul>
    
</div>
<?php } ?>
<div class="sub_menu" id="submenu_5" style="width:185px; <?php if($page_name == "sale-invoice.php" || $page_name == "instant-sale-invoice.php" ) { echo 'display:block;'; } else { echo 'display:none;'; } ?>  ">
    <ul >
        <?php if(in_array(23,$arr)) { ?>
        <li ><a href="sale-invoice.php">Sale Invoice</a></li>
        <?php } if(in_array(24,$arr)) { ?>
          <li><a href="instant-sale-invoice_multiple.php">multiple Instant Sale Invoice</a></li>
            <?php } if(in_array(25,$arr)) { ?>
         <li ><a href="invoice-list.php">Invoice List</a></li>
        <?php }  ?>
     </ul>
</div>
<?php if(in_array(26,$arr) || in_array(27,$arr)||  in_array(28,$arr)|| in_array(29,$arr)) { ?>
<div id="leftBar"  >

    <ul><li><a href="javascript:show_hide(8);">Loan & Advance :</a></li></ul>
    
</div>
<?php } ?>
<div class="sub_menu" id="submenu_8" style="width:185px; <?php if($page_name == "loan_advance.php" || $page_name == "make-loan-advance.php" || $page_name == "receive-loan-advance.php" ) { echo 'display:block;'; } else { echo 'display:none;'; } ?>  ">
    <ul >
     <?php if(in_array(27,$arr)) { ?>
        <li><a href="loan_advance.php">All Parties</a></li>       
         <?php } if(in_array(28,$arr)) { ?>
        <li ><a href="make-loan-advance.php">Make Payment</a></li>
         <?php } if(in_array(29,$arr)) { ?>
        <li><a href="receive-loan-advance.php">Receive Payment</a></li>
       <?php }  ?>
    </ul>
</div>
 <?php if(in_array(30,$arr) || in_array(31,$arr)) { ?>
<div id="leftBar"  >
    <ul>
   
        <li><a href="subdivision.php">subdivision</a></li>
        
    </ul>
</div>
<?php } ?>
<?php if(in_array(32,$arr) || in_array(33,$arr)||  in_array(34,$arr)|| in_array(35,$arr)|| in_array(36,$arr)) { ?>
<div id="leftBar"  >

    <ul><li><a href="javascript:show_hide(7);">GST :</a></li></ul>
    
</div>
<?php } ?>
<div class="sub_menu" id="submenu_7" style="width:185px; <?php if($page_name == "input_gst_subdivision.php" || $page_name == "output_gst_subdivision.php" ) { echo 'display:block;'; } else { echo 'display:none;'; } ?>  ">
    <ul >
     <?php  if(in_array(33,$arr)) { ?>
        <li><a href="input_gst_subdivision.php">GST Subdivision</a></li>    
         <?php } if(in_array(34,$arr)) { ?>   
        <li ><a href="output_gst_subdivision.php">GST Ledger</a></li>
         <?php } if(in_array(35,$arr)) { ?>
        <li><a href="gst-list.php">GST List</a></li>
         <?php } if(in_array(36,$arr)) { ?>
        <li><a href="hsn-list.php">HSN Code List</a></li>
         <?php }  ?>
    </ul>
</div>
<!--   TDS LIST        -->
<?php if(in_array(49,$arr) || in_array(50,$arr)||  in_array(51,$arr) || in_array(52,$arr)) { ?>
<div id="leftBar"  >

    <ul><li><a href="javascript:show_hide(9);">TDS :</a></li></ul>
    
</div>
<?php } ?>
<div class="sub_menu" id="submenu_9" style="width:185px; <?php if($page_name == "tds-list.php" || $page_name == "input_tds.php" || $page_name == "output_tds.php" ) { echo 'display:block;'; } else { echo 'display:none;'; } ?>  ">
    <ul >
     <?php  if(in_array(50,$arr)) { ?>
        <li><a href="input_tds.php">TDS Subdivision(Create)</a></li>    
         <?php } if(in_array(51,$arr)) { ?>   
        <li ><a href="output_tds.php">TDS By Customer</a></li>
         <?php } if(in_array(52,$arr)) { ?>
        <li><a href="tds-list.php">TDS List</a></li>
         <?php }  ?>
    </ul>
</div>

<!--      // TDS LIST        -->
<?php if(in_array(37,$arr) || in_array(38,$arr) || in_array(39,$arr) || in_array(39,$arr))
{ ?>
<div id="leftBar"  >
	<ul>
		
		<li><a href="javascript:show_hide(3);">Document's</a></li>
			
	</ul>
	
</div>
<?php } ?>
<div class="sub_menu" id="submenu_3" style="width:185px; <?php if($page_name == "category.php" || $page_name == "document.php" ) { echo 'display:block;'; } else { echo 'display:none;'; } ?>  ">
	<ul >
		<?php if(in_array(38,$arr)) { ?>
		<li><a href="category.php">Add Category</a></li>
		<?php } if(in_array(39,$arr)) { ?>
		<li><a href="document.php">Document List</a></li>
		<?php } ?>
	 </ul>
</div>
<?php if(in_array(40,$arr) || in_array(41,$arr) || in_array(42,$arr) || in_array(43,$arr) || in_array(44,$arr) || in_array(45,$arr) || in_array(46,$arr) || in_array(47,$arr)) { ?>
<div id="leftBar"  >
	<ul>
		<li><a href="javascript:show_hide(2);">Report's</a></li>
	</ul>
	
</div>
<?php } ?>
<div class="sub_menu" id="submenu_2" style="width:185px; <?php if($page_name == "bank-report.php" || $page_name == "invoice-report.php" || $page_name == "purchase-report-new.php" || $page_name == "project-report.php" || $page_name == "project-report-quater.php" || $page_name == "project-report-date.php" || $page_name == "customer-outstanding-report.php" || $page_name == "supplier-outstanding-report.php") { echo 'display:block;'; } else { echo 'display:none;'; } ?> ">
	<ul >
		<?php if(in_array(41,$arr)) { ?>
		<li><a href="bank-report.php">Bank Report</a></li>
		<?php } 
        if(in_array(42,$arr)) { ?>
		<!--<li><a href="customer-report.php">Customer Report</a></li>-->
        <li ><a href="invoice-report.php">Sale Report</a></li>
		<?php } 
        if(in_array(43,$arr)) { ?>
		<!--<li><a href="supplier-report.php">Supplier Report</a></li>-->
        <li ><a href="purchase-report-new.php">Purchase Report</a></li>
		<?php } ?>
        <?php if(in_array(44,$arr)) { ?>
		<li><a href="gst-report.php">GST Report</a></li>
		<!--<?php } if(in_array(45,$arr)) { ?>
		<li><a href="project-report-date.php">Project Report By Date</a></li>
        <?php } if(in_array(46,$arr)) { ?>
        <li><a href="customer-outstanding-report.php">Customer Outstanding reports</a></li>
        <?php } if(in_array(47,$arr)) { ?>
        <li><a href="supplier-outstanding-report.php">Supplier Outstanding Reports</a></li>
        <?php }  ?>-->
        <!--<li ><a href="invoice-report.php">Invoice Report</a></li>
        <li ><a href="purchase-report-new.php">Purchase Report</a></li>-->
        
	 </ul>
</div>
		
<?php if(in_array(1,$arr) || in_array(2,$arr) || in_array(3,$arr)) { ?>

<div id="leftBar"  >
    <ul><li><a href="javascript:show_hide(6);">Admin :</a></li></ul>
</div>
<div class="sub_menu" id="submenu_6" style="width:185px; <?php if($page_name == "user.php" || $page_name == "invoice-issuer.php" ) { echo 'display:block;'; } else { echo 'display:none;'; } ?>  ">
    <ul >
    <?php } if(in_array(2,$arr)) { ?>
        <li><a href="user.php">User</a></li>       
        <?php } if(in_array(3,$arr)) { ?>
        <li ><a href="invoice-issuer.php">Invoice Issuer</a></li>
        <?php } ?>
    </ul>
</div>

<?php //} ?>
<div id="leftBar"  >
    <ul><li><a href="javascript:show_hide(10);">Import File :</a></li></ul>
</div>
<div class="sub_menu" id="submenu_10" style="width:185px; <?php if($page_name == "import_file.php" ) { echo 'display:block;'; } else { echo 'display:none;'; } ?>  ">
    <ul >
    <?php  ?>
        <li><a href="import_file.php">File Upload</a></li>       
        <?php ?>
       <!-- <li ><a href="invoice-report.php">Invoice Report</a></li>
        <li ><a href="purchase-report-new.php">Purchase Report</a></li>-->
        <?php  ?>
    </ul>
</div>

		
</div>
