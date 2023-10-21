<style>
.scc {
 
  
}

</style>

<?php session_start();
include_once("../connection.php");

        $query1="select * from goods_details where invoice_id = '".$_REQUEST['invoice_id']."'";
        $result1= mysql_query($query1) ;
        $data1 = mysql_fetch_array($result1);
        
        $payment_customer = $data1["link2_id"];
        
        $query_pay_plan="select * from payment_plan where id = '".$payment_customer."'";
        $result_pay_plan= mysql_query($query_pay_plan) ;
        $select_pay_plan = mysql_fetch_array($result_pay_plan);
        
        $invoice_details = $select_pay_plan["invoice_issuer_id"];
        $customer_nm = get_field_value("full_name","customer","cust_id",$data1['cust_id']); 
         //$project1_nm = get_field_value("name","project","id",$row_series['project_id']); 
        //$subdivision1_nm = get_field_value("name","subdivision","id",$row_series['subdivision']);  
        //$gst_subdivision1_nm = get_field_value("name","gst_subdivision","id",$row_series['gst_subdivision']);  
        $query_issuer="select * from invoice_issuer where id = '".$invoice_details."'";
        $result_issuer= mysql_query($query_issuer) ;
        $select_issuer = mysql_fetch_array($result_issuer);
    ?>
<br>
&nbsp;<a href="#" onClick="print_data()" class="hd" >
<font face = "verdana" size ="4" color = "red">Click here to print Invoice</font> </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="invoice-list.php" title="Back" ><input type="button" name="back_button" id="back_button" value="Back" class="button"  /></a>
<br><br>

</a>
 <div id="ledger_data" style="" >
<table width="720"  cellpadding="0" cellspacing="0" border="0" bordercolor="" style="border: 1px solid #111111; padding:5px 0px 15px 5px;">
<tr><td align="right" style="font-size:9px;"><?php //echo "$row[PLNO]"; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
<tr><td align="center">
<table width="700" style="border-collapse:collapse;" cellpadding="0" cellspacing="0" border="0" bordercolor="#000000">
        <tr>
            <td width="700" align="center" valign="top"></td>
        </tr>
       <tr height="30">
            <td >
            <table border="0">
            <tr>
                <td align="center" valign="middle" width="440" height="100">
                  <table width="100%">
                  <tr>
                  <td><img src="mos-css/img/logo3.png" height="80" width="80" title="Edit"></td>
                  <td valign="middle"><font face="arial" size="4 " color=""><b><?php echo $select_issuer['company_name']; ?> </b></font><br>
                    <font face="arial" size="2 " color=""><b><?php echo $select_issuer['address']; ?></b></font>
                  </td>
                  </tr>
                  </table>  
                </td>
                <td align="center">
                    <?php //CODE BY AMIT
                    if($select_pay_plan['invoice_type'] == 'RN')
                    {echo 'Reimbursment Note - ';}
                    else
                    {echo 'Invoice No. - ';} 
                     
                    if($select_pay_plan['printable_invoice_number'])
                    {echo $select_pay_plan['printable_invoice_number'];}
                    else
                    {echo $data1['invoice_id'];} 
                    ?>
                    <br>
                    <table style="border-radius: 10px;border: 1px solid #111111;padding: 5px; width: 250px;height: 70px; font-size: 13;">
                    <tr>
                        <td width="40px;">PAN</td>
                        <td style="border-bottom-width: medium; border-bottom: 1px solid #111111; " ><?php echo $select_issuer['pan_no']; ?></td>
                    </tr>
                    <tr>
                        <td width="40px;" >GSTIN</td>
                        <td style="border-bottom-width: medium; border-bottom: 1px solid #111111; "><?php echo $select_issuer['gst_no']; ?></td>
                    </tr>
                    </table>
                </td>
            </tr>
            </table>
            </td>
        </tr>
        <tr valign="top">
            <td valign="top">
                <table>
                    <tr valign="top">
                        <td  width="" >
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Customer Details :<br>
                        <?php 
                        $cust_id_val= $data1['cust_id']; 
                        $query_customer="select * from customer where cust_id = '".$cust_id_val."'";
        $result_customer = mysql_query($query_customer) ;
        $select_customer = mysql_fetch_array($result_customer);
                        ?>
                    <table style="border-radius: 10px;border: 1px solid #111111;padding: 5px; width: 440;min-height:150px; font-size: 13;">
                    <tr>
                        <td valign="top" width="60px;">Name</td>
                        <td valign="top" style=" border-bottom: 1px solid #111111;" ><?php echo $select_customer['full_name']; ?></td>
                    </tr>
                   <!-- <tr ><td valign="top" >Company</td>
                        <td valign="top" style="border-bottom-width: medium; border-bottom: 1px solid #111111;color: red; "> <?php echo $select_customer['company_name']; ?></td>
                    </tr>-->
                    
                    <!--<tr ><td valign="top" >Phone</td>
                        <td valign="top" style="border-bottom-width: medium; border-bottom: 1px solid #111111; color: red;"><?php echo $select_customer['mobile']; ?></td>
                    </tr> -->
                    
                    <tr><td valign="top" >Address:</td>
                        <td valign="top" style="color: red;" >
                        <textarea rows="3" cols="46" id="multiLineInput" class=""  style=" border-right: 0px;  border-bottom: 0px;  border-left: 0px;  border-top: 0px; line-height: 4ch;  background-image: linear-gradient(transparent, transparent calc(4ch - 1px), #111111 0px);background-size: 100% 4ch; 0px;   "><?php echo $select_customer['current_address']; ?></textarea></td>
                    </tr>
                   
                    <tr><td valign="top" >GSTIN</td>
                        <td valign="top" style=" border-bottom: 1px solid #111111;color: ; "> <?php echo $select_customer['client_gst']; ?></td>
                    </tr>
                    </table>

                        </td>
                        <td>
                        <?php //CODE BY AMIT
                        if($select_pay_plan['invoice_type'] == 'RN')
                        {echo 'Reimbursment Note Details :';}
                        else
                        {echo 'Invoice Details :';} 
                        ?>
                    <br>
                        <table style="border-radius: 10px;border: 1px solid #111111;padding: 5px; width: 250; min-height:150px; font-size: 13;">
                    
                    <tr><td valign="top" width="80px;" >Date</td>
                        <td valign="top" style="border-bottom-width: medium; border-bottom: 1px solid #111111;color: ; "><?php echo date("d-m-Y",$data1['payment_date']); ?></td>
                    </tr>
                    <tr><td valign="top" >Client</td>
                        <td valign="top" style="border-bottom-width: medium; border-bottom: 1px solid #111111; "><?php echo $select_customer['client_name']; ?></td>
                    </tr>
                    <tr><td valign="top" >Project </td>
                        <td valign="top" style="border-bottom-width: medium; border-bottom: 1px solid #111111; "><?php echo $select_customer['project']; ?></td>
                    </tr>
                    
                    <tr><td valign="top" >Contact</td>
                        <td valign="top" style="border-bottom-width: medium; border-bottom: 1px solid #111111; "><?php echo $select_customer['client_contact_per']; ?></td>
                    </tr>
                    <tr><td valign="top" >Email</td>
                        <td valign="top" style="border-bottom-width: medium; border-bottom: 1px solid #111111;color: ; "><?php echo $select_customer['client_email']; ?></td>
                    </tr>
                    <tr><td valign="top" nowrap>Cust Id</td>
                        <td valign="top" style="border-bottom-width: medium; border-bottom: 1px solid #111111;color: ; "><?php echo $select_customer['cust_id']; ?></td>
                    </tr>
                    </table>


                        </td>
                    </tr>
                    
                </table>
                <br>
            </td>
        </tr>
        
        <tr><td>
        <div style="border-radius: 1px;  border: 1px dashed #DB8482; padding: 1px; width: 690; min-height:250px; font-size: 15;" >
            <table style="width: 690px; font-size: 15; padding: 0px 0px 0px 0px; margin: 0px 0px 0px 0px; "  border="0">
                    <tr valign="top"><td style=" border-bottom:1px dashed #DB8482;border-right :1px dashed #111111; padding: 0px 0 0px 0;" align="center" width="40px" height="30" valign="middle"><b>Qty</b></td>
                        <td height="30" align="center" style=" border-bottom:1px dashed #DB8482;border-right :1px dashed #DB8482; padding: 0px 0 0px 0;" valign="middle"><b>Description</b></td><td height="30" align="center" style=" border-bottom:1px dashed #DB8482;border-right :1px dashed #DB8482; padding: 0px 0 0px 0;" width="80px" valign="middle"><b>HSN Code</b></td>
                        <td height="30" align="center" style=" border-bottom:1px dashed #DB8482;border-right :1px dashed #DB8482; padding: 0px 0 0px 0;" width="120px"><b>Unit Price</b></td>
                         <td height="30" align="center" style=" border-bottom:1px dashed #DB8482;border-right :1px dashed #DB8482; padding: 0px 0 0px 0;" width="10px"><b>GST<br>%</b></td>
                        <td align="center" style=" border-bottom:1px dashed #DB8482; padding: 0px 0 0px 0;" width="130px;" valign="middle"><b>Total</b></td>
                    </tr>   
                   <tr valign="top"><td height="15px;" style=" border-right :1px dashed #DB8482; " align="center"  >&nbsp;</td>
                        <td align="left" style=" border-right :1px dashed #DB8482; padding: 0px 0 0px 5px; color: ;"></td>
                        <td align="center" style=" border-right :1px dashed #DB8482; "></td>
                        <td align="center" style=" border-right :1px dashed #DB8482; "></td>
                        <td align="center" style=" border-right :1px dashed #DB8482; "></td>
                        <td align="center" style="color: blue; background-color:E7C0A5; "  ><div id="m1" style="margin-top: -2px; background-color:E7C0A5;">&nbsp;</div></td>
                    </tr>
                    <?php 
                     $query_pro_detail="select * from goods_details where invoice_id = '".$data1['invoice_id']."'";
        $result_pro_detail= mysql_query($query_pro_detail) ;
        $i_no=1;
        while($data_pro_detail = mysql_fetch_array($result_pro_detail))
        { 
            $old_sub_total =$data_pro_detail['unit_price']*$data_pro_detail['qty'];
            $old_gst_amount =($old_sub_total*$data_pro_detail['gst_per'])/100;
            $old_grand_total=$old_sub_total+$old_gst_amount;
            $tot1_qty_t=$tot1_qty_t+$data_pro_detail['qty'];
            $tot1_unit_price_1=$tot1_unit_price_1+$data_pro_detail['unit_price'];
            $tot1_sub_total= $tot1_sub_total+ $old_sub_total;
            $tot1_gst_amount = $tot1_gst_amount+$old_gst_amount;
            $tot1_grand_total = $tot1_grand_total+$old_grand_total;
            
            ?>
             <tr valign="top" ><td height="20px;" style=" border-right :1px dashed #DB8482; " align="center"  ><?php echo $data_pro_detail['qty']; ?></td>
                        <td align="left" style=" border-right :1px dashed #DB8482; padding: 0px 0 0px 5px; color: ;"><?php echo $data_pro_detail['description'];  //$project1_nm = get_field_value("name","project","id",$data_pro_detail['project_id']); 
                      //  echo "(Project :".$project1_nm."-".$data_pro_detail['project_id'].")"; ?></td>
                        <td align="center" style=" border-right :1px dashed #DB8482; color: ; "><?php echo $data_pro_detail['hsn_code']; ?></td>
                        <td align="right" style=" border-right :1px dashed #DB8482; color: ; "><?php //echo $data_pro_detail['unit_price']; 
                        echo currency_symbol().number_format($data_pro_detail['unit_price'],2);   ?>&nbsp;</td>
                        <td align="center" style=" border-right :1px dashed #DB8482; color: ; "><?php echo $data_pro_detail['gst_per']; ?></td>
                        <td align="right" style="color: ; background-color:E7C0A5; " ><div id="m1" style="margin-top: -2px; background-color:E7C0A5;"><?php //echo $data_pro_detail['sub_total']; 
                      echo currency_symbol().number_format($old_sub_total,2);   ?>&nbsp;</div></td>
                    </tr>
         <?php   $i_no++;   
        }
        for($ij=$i_no;$ij<17;$ij++)
        { ?>
            <tr valign="top" ><td height="20px;" style=" border-right :1px dashed #DB8482; " align="center"  >&nbsp;</td>
                        <td align="left" style=" border-right :1px dashed #DB8482; padding: 0px 0 0px 5px; color: ;">&nbsp;</td>
                        <td align="center" style=" border-right :1px dashed #DB8482; color: ; ">&nbsp;</td>
                        <td align="center" style=" border-right :1px dashed #DB8482; color: ; ">&nbsp;</td>
                        <td align="center" style=" border-right :1px dashed #DB8482; color: ; ">&nbsp;</td>
                        <td align="center" style="color: ; background-color:E7C0A5; " ><div id="m1" style="margin-top: -2px; background-color:E7C0A5;">&nbsp;<?php //echo currency_symbol().number_format(12345678,2);  ?></div></td>
                    </tr>
       <?php  }
                    
                    ?>
                   
                   
                    
                    
             </table>
            </div>
       </td></tr>
       <tr><td>
       <div style=" padding: 0px; width: 698;  font-size: 15;" >
       <table width="100%">
        <tr>
            <td width="">
            <div style="border-radius: 10px;border: 1px solid #111111;padding: 5px; width: 460;min-height:80px; font-size: 15;">
            Please ensure that your remittance in favour of 
            <?php
            if($select_customer['bank_attachment']>0 && $select_customer['bank_attachment']!="" )
            { 
                $sql_bank     = "select * from `bank` where id='".$select_customer['bank_attachment']."'  ";
                $query_bank     = mysql_query($sql_bank);
                $row_bank = mysql_fetch_array($query_bank);
                ?>

            <b><?php  echo $row_bank['exact_bank_name']; ?> </b> <br>
                       Bank Account Number:<?php echo $row_bank['bank_account_number']; ?>&nbsp;&nbsp;&nbsp;IFSC Code : <?php echo $row_bank['ifsc_code']; ?>
           
                
           <?php  }
             ?>
           <br><br>
           AMOUNT IN WORDS :(Rupees <?php echo numberTowords("$tot1_grand_total")." Only"; ?>) 
            </div>
            
            
            </td>
            <td width="" valign="top">
            <table width="100%" border="0">
                <tr>
                    <td style=" border-right :1px dashed #DB8482; " align="left" width="83px"  >Sub Total </td>
                    <td style=" border-right :1px dashed #DB8482;border-bottom :1px dashed #DB8482; " align="right" width="130px"  ><?php //echo $tot1_sub_total;
                    echo currency_symbol().number_format($tot1_sub_total,2);  ?>&nbsp;</td>
                </tr>
                 <tr>
                 <?php $tot_sgst=$tot1_gst_amount/2;
                 $tot_cgst=$tot1_gst_amount/2;
                  ?>
                    <td style=" border-right :1px dashed #DB8482; " align="left" width=""  >SGST </td>
                    <td style=" border-right :1px dashed #DB8482;border-bottom :1px dashed #DB8482; " align="right" width=""  ><?php //echo $tot_sgst; 
                    echo currency_symbol().number_format($tot_sgst,2); ?>&nbsp;</td>
                </tr>
                <tr>
                    <td style=" border-right :1px dashed #DB8482; " align="left" width=""  >CGST </td>
                    <td style=" border-right :1px dashed #DB8482;border-bottom :1px dashed #DB8482; " align="right" width=""  ><?php //echo $tot_cgst;
                    echo currency_symbol().number_format($tot_cgst,2); ?>&nbsp;</td>
                </tr>
                
                <tr>
                    <td style=" border-right :1px dashed #DB8482; " align="left" width=""  ><b>Grand Total </b></td>
                    <td style=" border-right :1px dashed #DB8482;border-bottom :1px dashed #DB8482; " align="right" width=""  ><?php echo currency_symbol(); ?><b><?php //echo $tot1_grand_total;
                    echo number_format($tot1_grand_total,2); ?>&nbsp;</b></td>
                </tr>
                
               
               
            </table>
            </td>
        </tr>
       </table></div>
       </td></tr>
              <tr><td>
       <div style=" padding: 0px; width: 698;  font-size: 15;" >
       <table width="100%" border="0">
        <tr>
            <td width="390px">
            <div style=" padding: 4px; width: 350px;  font-size: 15; text-align: justify;" >
         For Any queries related to this document, Contact details are given below.<br><br>
        </div>
            
            
            </td>
            <td width="" valign="top">
            <table width="100%" border="0">
                
                 <tr>
                 <?php $tot_sgst=$tot1_gst_amount/2;
                 $tot_cgst=$tot1_gst_amount/2;
                  ?>
                    <td style=" font-size: 14; " align="left" width="30px"  ><b>For :</b> </td>
                    <td style=" border-bottom :1px dashed #111111; " align="left" width=""  ><?php echo $select_issuer['company_name']; ?></td>
                </tr>
                
                
                <tr>
                    <td style=" font-size: 14; " align="left" width="100px"   ><b>Authorise Signatory : </b></td>
                    <td style=" border-bottom :1px dashed #111111; " align="center" width=""  ><b>&nbsp;</b></td>
                </tr>
                
               
               
            </table>
            </td>
        </tr>
       </table></div>
       </td></tr>
       <tr>
        <td>
        <div style="border: 1px solid #111111; width: 700;min-height:2px; background-color: blue; "></div>
        </td>
       </tr>
       <tr>
        <td><br>
        <div style="border-top : 1px solid #111111; width: 700;  margin-top: -10px; "></div>
        </td>
       </tr>
       <tr>
        <td align="center">
         <div style="border: 0px solid #111111; width: 700;min-height:50px; color: #111111; font-size: 13px;  " align="center">
         <br>E & O.E&nbsp;&nbsp;&nbsp;&nbsp; Our Contact : <?php echo $select_issuer['display_name']; ?>
         , Mobile: <?php echo $select_issuer['mobile']; ?>  & Email ID : <?php echo $select_issuer['email']; ?>
         </div>
        </td>
       </tr>
       
</table>
</td></tr>
</table>
</div>
<script >
function print_data()
{
    printMe=window.open();
    printMe.document.write(document.getElementById("ledger_data").innerHTML);
    printMe.print();
    printMe.close();
}



</script>
<?php


function numberTowords($num)
{ 
$ones = array( 
1 => "one", 
2 => "two", 
3 => "three", 
4 => "four", 
5 => "five", 
6 => "six", 
7 => "seven", 
8 => "eight", 
9 => "nine", 
10 => "ten", 
11 => "eleven", 
12 => "twelve", 
13 => "thirteen", 
14 => "fourteen", 
15 => "fifteen", 
16 => "sixteen", 
17 => "seventeen", 
18 => "eighteen", 
19 => "nineteen" 
); 
$tens = array( 
1 => "ten",
2 => "twenty", 
3 => "thirty", 
4 => "forty", 
5 => "fifty", 
6 => "sixty", 
7 => "seventy", 
8 => "eighty", 
9 => "ninety" 
); 
$hundreds = array( 
"hundred", 
"thousand", 
"million", 
"billion", 
"trillion", 
"quadrillion" 
); //limit t quadrillion 
$num = number_format($num,2,".",","); 
$num_arr = explode(".",$num); 
$wholenum = $num_arr[0]; 
$decnum = $num_arr[1]; 
$whole_arr = array_reverse(explode(",",$wholenum)); 
krsort($whole_arr); 
$rettxt = ""; 
foreach($whole_arr as $key => $i){ 
if($i < 20){ 
$rettxt .= $ones[$i]; 
}elseif($i < 100){ 
$rettxt .= $tens[substr($i,0,1)]; 
$rettxt .= " ".$ones[substr($i,1,1)]; 
}else{ 
$rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
$rettxt .= " ".$tens[substr($i,1,1)]; 
$rettxt .= " ".$ones[substr($i,2,1)]; 
} 
if($key > 0){ 
$rettxt .= " ".$hundreds[$key]." "; 
} 
} 
if($decnum > 0){ 
$rettxt .= " and "; 
if($decnum < 20){ 
$rettxt .= $ones[$decnum]; 
}elseif($decnum < 100){ 
$rettxt .= $tens[substr($decnum,0,1)]; 
$rettxt .= " ".$ones[substr($decnum,1,1)]; 
} 
} 
return $rettxt; 
}  ?>