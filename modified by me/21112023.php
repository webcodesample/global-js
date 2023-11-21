<script type="text/javascript">

function gstrEntry_action(act,requestUrl,callbackUrl,transId,Id,trsnsPname,custId)
{
	if(window.confirm("Are you sure want to "+act+" this entry?..."))
	{
		document.getElementById('frm_gstr_entry').action = requestUrl;
		document.getElementById('returnto').value = callbackUrl;
		document.getElementById('trans_id').value = transId;
		document.getElementById('id').value = Id;
		document.getElementById('trsns_pname').value = trsnsPname;
		document.getElementById('cust_id').value = custId;
		document.getElementById('frm_gstr_entry').submit();
	}
}

function setAlert(act)
{
	if(window.confirm("Hi "+act+" welcome"))
	alert("ok");
	else
	alert("bye");
}

</script>
<body>

<a href="javascript:setAlert('asdf')">Test</a>

<form name="frm_gstr_entry" id="frm_gstr_entry" method="post">
<input type="hidden" name="returnto" id="returnto">
<input type="hidden" name="trans_id" id="trans_id">
<input type="hidden" name="id" id="id">
<input type="hidden" name="trsns_pname" id="trsns_pname">
<input type="hidden" name="cust_id" id="cust_id">
</form>

</body>