<?php 
include_once("../connection.php");
$query="select *  from loan_attach_file where attach_id = '".$_REQUEST['id']."'";
$result= mysql_query($query) or die('error in query '.mysql_error().$query);
$total_rows = mysql_num_rows($result);
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
	