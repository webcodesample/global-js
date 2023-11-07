<form id="convert_frm" method="post" action="">
<input type="text" id="price" name="price" value="<?php if(isset($_REQUEST['price'])) echo $_REQUEST['price']; ?>">

<select id="inv_type" name="inv_type">
<option value="">Invoice Type</option>
<option value="R">GST Rent</option>
<option value="S">GST Sale</option>
<option value="M">GST Maintenance</option>
<option value="RN">Reimbursement Note</option>
</select>
<div style='color:#800000; font-weight:bold;'>
<?php include("advance_functions.php"); if(isset($_REQUEST['price'])) ntw($_REQUEST['price']); ?>
</div>

<div style='color:#800000; font-weight:bold;'>
<?php if(isset($_REQUEST['price'])) ntw_new($_REQUEST['price']); ?>
</div>

<input type="button" value="Convert" onClick="chk_num()">
<div style='color:#809000; font-weight:bold;'>
<?php ntw(12345); ?>
</div>
<div style='color:#809000; font-weight:bold;'>
<?php if(isset($_REQUEST['inv_type'])) display($_REQUEST['inv_type']); ?>
</div>

<div style='color:#809090; font-weight:bold;'>
<?php setfileURL('dll.txt'); ?>
</div>
</form>
<script src="myscript.js"></script>