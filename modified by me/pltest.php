<?php session_start();
include_once("set_con.php");

if($_REQUEST['subdivision'])
$query_part_subdivision = " AND subdivision = ".$_REQUEST['subdivision'];
else
$query_part_subdivision = "";

$sql = "SELECT debit,credit,subdivision,on_customer,payment_date,description FROM payment_plan WHERE project_id = '".$_REQUEST['project_id']."' ".$query_part_subdivision." ORDER BY payment_date DESC";
$result = mysql_query($sql);
echo $resultrow_count = mysql_num_rows($result);

$sql_total = "SELECT SUM(credit) AS Total, SUM(debit) AS TotalDr FROM payment_plan WHERE project_id = '".$_REQUEST['project_id']."' ".$query_part_subdivision;
$result_total = mysql_query($sql_total);

$result_set = mysql_fetch_assoc($result_total);
//print_r($result_set);
$total_cr = $result_set['Total'];
$total_dr = $result_set['TotalDr'];

$closing_bal = $total_cr-$total_dr;
$prev_bal = 0;

$i=0;

echo "<table border='1'>
    <tr>
    <th>S No</th>
    <th>Date</th>
    <th>Subdivision</th>
    <th>Customer</th>
    <th>Description</th>
    <th>Debit</th>
    <th>Credit</th>
    <th>Balance</th>
    <th>Prev Balance</th>
    <th>Action</th>
    </tr>
    <tr>
    <th colspan='5' align='right'>Closing Balance</th>
    <th>".$total_dr."</th>
    <th>".$total_cr."</th>
    <th>".$closing_bal."</th>
    <th></th>
    <th></th>
    </tr>";

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

    echo "<tr>
        <td>".($i+1)."</td>
        <td>".date("d-m-Y",$data['payment_date'])."</td>
        <td class='cellData'>".get_field_value('name','subdivision','id',$data[subdivision])."</td>
        <td>".get_field_value('full_name','customer','cust_id',$data[on_customer])."</td>
        <td>".$data[description]."</td>
        <td>".$data[debit]."</td>
        <td>".$data[credit]."</td>
        <td>".$balance."</td>
        <td>".$prev_bal."</td>
        <td>Action Icons</td>
        </tr>";

    //$total -= $data[credit];
    if($data[credit])
    $prev_bal += $data[credit];
    if($data[debit])
    $prev_bal -= $data[debit];

    $i++;
}

echo "</table>";
echo $closing_bal;

?>

<style>

.cellData {
    padding: 1px;
}

</style>
<!--
<tr>
    <th colspan='4' align='right'>Opening Balance</th>
    <th>".$balance."</th>
    <th>".$balance."</th>
    <th>".$balance."</th>
    </tr>
    -->