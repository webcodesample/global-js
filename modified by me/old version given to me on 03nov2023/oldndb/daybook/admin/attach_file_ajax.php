<?php 
include_once("../connection.php");
$total_rows=0;
$query="select *  from attach_file where attach_id = '".$_REQUEST['id']."'";
$result= mysql_query($query) or die('error in query '.mysql_error().$query);
$total_rows_1 = mysql_num_rows($result);
if($total_rows_1>0)
{
	$total_rows=$total_rows_1;
}
//invoice_type='tds'
/******    tds attachment     *******/
	$new_type= $_REQUEST['invoice_type'];
	if($new_type=="tds")
	{
		$query_tds="select *  from tds_due_info where invoice_id = '".$_REQUEST['id']."' and cert_file_name!=''";
		$result_tds= mysql_query($query_tds) or die('error in query '.mysql_error().$query_tds);
		$total_rows_tds = mysql_num_rows($result_tds);
		if($total_rows_tds>0)
		{
			$total_rows=$total_rows_tds;
		}
		
	}
	if($new_type=="gst")
	{
		$query_gst="select *  from gst_due_info where invoice_id = '".$_REQUEST['id']."' and cert_file_name!=''";
		$result_gst= mysql_query($query_gst) or die('error in query '.mysql_error().$query_gst);
		$total_rows_gst = mysql_num_rows($result_gst);
		if($total_rows_gst>0)
		{
			$total_rows=$total_rows_gst;
		}
		
	}
	if($new_type=="invoice")
	{
		$query_invoice="select *  from invoice_due_info where invoice_id = '".$_REQUEST['id']."' and cert_file_name!=''";
		$result_invoice= mysql_query($query_invoice) or die('error in query '.mysql_error().$query_invoice);
		$total_rows_invoice = mysql_num_rows($result_invoice);
		if($total_rows_invoice>0)
		{
			$total_rows=$total_rows_invoice;
		}
		
	}
	if($new_type=="all_file")
	{
		$query_tds="select *  from tds_due_info where invoice_id = '".$_REQUEST['id']."' and cert_file_name!=''";
		$result_tds= mysql_query($query_tds) or die('error in query '.mysql_error().$query_tds);
		$total_rows_tds = mysql_num_rows($result_tds);
		if($total_rows_tds>0)
		{
			$total_rows=$total_rows_tds;
		}
		$query_gst="select *  from gst_due_info where invoice_id = '".$_REQUEST['id']."' and cert_file_name!=''";
		$result_gst= mysql_query($query_gst) or die('error in query '.mysql_error().$query_gst);
		$total_rows_gst = mysql_num_rows($result_gst);
		if($total_rows_gst>0)
		{
			$total_rows=$total_rows_gst;
		}
		
		$query_invoice="select *  from invoice_due_info where invoice_id = '".$_REQUEST['id']."' and cert_file_name!=''";
		$result_invoice= mysql_query($query_invoice) or die('error in query '.mysql_error().$query_invoice);
		$total_rows_invoice = mysql_num_rows($result_invoice);
		if($total_rows_invoice>0)
		{
			$total_rows=$total_rows_invoice;
		}
		
	}	
?>
	<table cellpadding="10" cellspacing="0" border="0" width="490" align="center"  >
			<tr><td valign="top" align="right" colspan="4" ><img src="images/close.gif" onClick="return close_view();" ></td></tr>
			<tr >
				<th valign="top" width="20"  style="border:1px solid #CCCCCC;">S.No.</th>
				<th style="border:1px solid #CCCCCC;">File Name</th>
                <th width="30" style="border:1px solid #CCCCCC;">Delete          
                </th>
				<th width="30" style="border:1px solid #CCCCCC;">View</th>
			</tr>
	<?php
if($total_rows == 0)
{
	?>
			
			<tr>
				<td colspan="3">No file Found</td>
			</tr>
			
			
					
		<?php
	
}
else
{
	
	$i=1;
	if($total_rows_invoice>0)
		{ 
			while($data_invoice = mysql_fetch_array($result_invoice))
	{
		?>
			
			<tr>
				<td valign="top" style="border:1px solid #CCCCCC;" ><?php echo $i; ?></td>
				<td style="border:1px solid #CCCCCC;"><?php echo $data_invoice['cert_file_name']; ?></td>
                <td style="border:1px solid #CCCCCC;">
                <a href="javascript:account_transaction_invoice(<?php echo $data_invoice['id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>
                </td>
                
				<td style="border:1px solid #CCCCCC;"><a href="invoice_files/<?php echo $data_invoice['cert_file_name']; ?>" title="View File" target="_blank" >View</a></td>
			</tr>
			
			
					
		<?php
		$i++;
	}
	
		}

	
			while($data_tds = mysql_fetch_array($result_tds))
	{
		?>
			
			<tr>
				<td valign="top" style="border:1px solid #CCCCCC;" ><?php echo $i; ?></td>
				<td style="border:1px solid #CCCCCC;"><?php echo $data_tds['cert_file_name']; ?></td>
                <td style="border:1px solid #CCCCCC;">
                <a href="javascript:account_transaction_tds(<?php echo $data_tds['id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>
                </td>
                
				<td style="border:1px solid #CCCCCC;"><a href="tds_files/<?php echo $data_tds['cert_file_name']; ?>" title="View File" target="_blank" >View</a></td>
			</tr>
			
			
					
		<?php
		$i++;
	}
	
		if($total_rows_gst>0)
		{ 
			while($data_gst = mysql_fetch_array($result_gst))
	{
		?>
			
			<tr>
				<td valign="top" style="border:1px solid #CCCCCC;" ><?php echo $i; ?></td>
				<td style="border:1px solid #CCCCCC;"><?php echo $data_gst['cert_file_name']; ?></td>
                <td style="border:1px solid #CCCCCC;">
                <a href="javascript:account_transaction_gst(<?php echo $data_gst['id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>
                </td>
                
				<td style="border:1px solid #CCCCCC;"><a href="gst_files/<?php echo $data_gst['cert_file_name']; ?>" title="View File" target="_blank" >View</a></td>
			</tr>
			
			
					
		<?php
		$i++;
	}
	
		}

	while($data = mysql_fetch_array($result))
	{
		?>
			
			<tr>
				<td valign="top" style="border:1px solid #CCCCCC;" ><?php echo $i; ?></td>
				<td style="border:1px solid #CCCCCC;"><?php echo $data['file_name']; ?></td>
                <td style="border:1px solid #CCCCCC;">
                <a href="javascript:account_transaction_1(<?php echo $data['id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>
                </td>
                
				<td style="border:1px solid #CCCCCC;"><a href="transaction_files/<?php echo $data['file_name']; ?>" title="View File" target="_blank" >View</a></td>
			</tr>
			
			
					
		<?php
		$i++;
	}
	
}
?>
	</table>
	