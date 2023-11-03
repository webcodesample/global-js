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
$flag = 0;

/*     Create  Account   */


if(trim($_REQUEST['action_perform']) == "add_project")
{
	/*echo '<pre>';
	print_r($_REQUEST);
	exit;*/
	$from_arr = explode("- ",$_REQUEST['from']);
	$cust_id = $from_arr[1];
	$project_id = get_field_value("id","project","name",$_REQUEST['project']);
	$amount=mysql_real_escape_string(trim($_REQUEST['amount']));
	$description=mysql_real_escape_string(trim($_REQUEST['description']));
	$subdivision = get_field_value("id","subdivision","name",$_REQUEST['subdivision']);
    $gst_subdivision = get_field_value("id","gst_subdivision","name",$_REQUEST['gst_subdivision']);
    $invoice_issuer_arr = explode("- ",$_REQUEST['invoice_issuer']);
    $invoice_issuer_id = $invoice_issuer_arr[1];
    //subdivision,gst_subdivision
   // invoice_issuer
    ///////payment Fiels values ///////
    $pay_from_arr = explode(" -",$_REQUEST['pay_form']);
    $pay_bank_id = get_field_value("id","bank","bank_account_name",$pay_from_arr[0]);
    $pay_amount=mysql_real_escape_string(trim($_REQUEST['pay_amount']));
    
    $pay_method=mysql_real_escape_string(trim($_REQUEST['pay_method']));
    $pay_checkno=mysql_real_escape_string(trim($_REQUEST['pay_checkno']));
    //////////////
    
    /* goods detail start*/
        
       $desc_t=$_REQUEST['desc_t'];
       $qty_t=$_REQUEST['qty_t'];
       $unit_price_1=$_REQUEST['unit_price_1'];
       $sub_total=$_REQUEST['sub_total'];
       $gst=$_REQUEST['gst'];
       $gst_amount=$_REQUEST['gst_amount'];
       $total=$_REQUEST['total'];
       //totall value fields
       $qty_tot=$_REQUEST['qty_tot'];
       $unit_price_tot=$_REQUEST['unit_price_tot'];
       $sub_total_tot=$_REQUEST['sub_total_tot'];
       $total_tot=$_REQUEST['total_tot'];
       $gst_amount_tot=$_REQUEST['gst_amount_tot'];
       
    /* goods detail end  */
	 $trans_type = 14;
    $trans_type_name = "inst_sale_goods" ;
   
   
	$trans_id = mysql_real_escape_string(trim($_REQUEST['trans_id']));
	   
      


	//
	$query="insert into payment_plan set trans_id = '".$trans_id."', project_id = '".$project_id."', debit = '".$sub_total_tot."', gst_amount = '".$gst_amount_tot."', description = '".$description."', on_customer = '".$cust_id."', payment_date = '".strtotime($_REQUEST['payment_date'])."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision."',invoice_issuer_id = '".$invoice_issuer_id."',trans_type = '".$trans_type."', trans_type_name = '".$trans_type_name."', create_date = '".getTime()."'";
    
	$result= mysql_query($query) or die('error in query '.mysql_error().$query);
	
	$link_id_1 = mysql_insert_id();
	
	$query2="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."', credit = '".$total_tot."',  gst_amount = '".$gst_amount_tot."',description = '".$description."', on_project = '".$project_id."', payment_date = '".strtotime($_REQUEST['payment_date'])."',link_id = '".$link_id_1."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision."',trans_type = '".$trans_type."', trans_type_name = '".$trans_type_name."', create_date = '".getTime()."'";
	$result2= mysql_query($query2) or die('error in query '.mysql_error().$query2);
	
	$link_id_2 = mysql_insert_id();
	
	$query5="update payment_plan set link_id = '".$link_id_2."' where id = '".$link_id_1."'";
	$result5= mysql_query($query5) or die('error in query '.mysql_error().$query5);
	
	
    /*       payment query start           */
    //,
     $trans_type_pay = 12;
    $trans_type_name_pay= "inst_receive_payment" ;
   
    $trans_id = mysql_real_escape_string(trim($_REQUEST['trans_id']));
    
    $query_pay ="insert into payment_plan set trans_id = '".$trans_id."', bank_id = '".$pay_bank_id."', credit = '".$pay_amount."', gst_amount = '".$gst_amount_tot."', description = '".$description."', on_customer = '".$cust_id."', on_project = '".$project_id."', payment_date = '".strtotime($_REQUEST['pay_payment_date'])."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision."',invoice_issuer_id = '".$invoice_issuer_id."' ,payment_method = '".$pay_method."',payment_checkno = '".$pay_checkno."',link2_id = '".$link_id_1."',link3_id = '".$link_id_2."', trans_type = '".$trans_type_pay."', trans_type_name = '".$trans_type_name_pay."',create_date = '".getTime()."'";
    $result_pay= mysql_query($query_pay) or die('error in query '.mysql_error().$query_pay);
    
    
    $link_id_1_pay = mysql_insert_id();
    
    $query2_pay="insert into payment_plan set trans_id = '".$trans_id."', cust_id = '".$cust_id."', debit = '".$pay_amount."', gst_amount = '".$gst_amount_tot."', description = '".$description."', on_project = '".$project_id."', on_bank = '".$pay_bank_id."', payment_date = '".strtotime($_REQUEST['pay_payment_date'])."' ,payment_method = '".$pay_method."',invoice_issuer_id = '".$invoice_issuer_id."',subdivision = '".$subdivision."',gst_subdivision = '".$gst_subdivision."',payment_checkno = '".$pay_checkno."',link2_id = '".$link_id_1."',link3_id = '".$link_id_2."',link_id = '".$link_id_1_pay."',trans_type = '".$trans_type_pay."', trans_type_name = '".$trans_type_name_pay."', create_date = '".getTime()."'";
    $result2_pay= mysql_query($query2_pay) or die('error in query '.mysql_error().$query2_pay);
    
    
    
    $link_id_2_pay = mysql_insert_id();
    $query5_pay="update payment_plan set link_id = '".$link_id_2_pay."' where id = '".$link_id_1_pay."'";
    $result5_pay= mysql_query($query5_pay) or die('error in query '.mysql_error().$query5_pay);
    
    $query5="update payment_plan set link2_id = '".$link_id_1_pay."',link3_id = '".$link_id_2_pay."' where id = '".$link_id_2."'";
    $result5= mysql_query($query5) or die('error in query '.mysql_error().$query5);
    
    $query5="update payment_plan set link2_id = '".$link_id_1_pay."',link3_id = '".$link_id_2_pay."' where id = '".$link_id_1."'";
    $result5= mysql_query($query5) or die('error in query '.mysql_error().$query5);
    
    
 
    /*       payment query end           */
    
    /*   goods detail start    */
                  
 //record,snum,no_check,desc_t,qty_t,unit_price_1,sub_total,gst,total,gst_amount
 //qty_tot,unit_price_tot,sub_total_tot,total_tot,gst_amount_tot
      
       
       
       $ij=1;
    for($i = 0; $i < count($desc_t); $i++) {
        // do something with $array[$i]o
       // echo $desc_t[$i];
        $desc_total_n = $desc_total_n."(".$ij.")".$desc_t[$i].",";
        $query_goods_details ="insert into goods_details set trans_id = '".$trans_id."', link1_id = '".$link_id_1."',link2_id = '".$link_id_2."',link3_id = '".$link_id_1_pay."',link4_id = '".$link_id_2_pay."', description = '".$desc_t[$i]."',qty = '".$qty_t[$i]."',unit_price = '".$unit_price_1[$i]."',gst_per = '".$gst[$i]."',gst_amount = '".$gst_amount[$i]."',trans_type = '".$trans_type."',trans_type_name = '".$trans_type_name."',create_date = '".getTime()."'";
        $result_goods_details= mysql_query($query_goods_details) or die('error in query '.mysql_error().$query_goods_details);
        $link_goods_details = mysql_insert_id();
    // $goods_details_idlist=$goods_details_idlist.",".$link_goods_details;
     $goods_details_idlist=$goods_details_idlist.$link_goods_details.",";
     $ij++;
}
//goods_detail_id
         $query_goods="update payment_plan set goods_detail_id = '".$goods_details_idlist."' , description = '".$desc_total_n."' where id = '".$link_id_1."'";
          $result_goods= mysql_query($query_goods) or die('error in query '.mysql_error().$query_goods);
          $query_goods="update payment_plan set goods_detail_id = '".$goods_details_idlist."' , description = '".$desc_total_n."' where id = '".$link_id_2."'";
          $result_goods= mysql_query($query_goods) or die('error in query '.mysql_error().$query_goods);
          $query_goods="update payment_plan set goods_detail_id = '".$goods_details_idlist."' , description = '".$desc_total_n."' where id = '".$link_id_1_pay."'";
          $result_goods= mysql_query($query_goods) or die('error in query '.mysql_error().$query_goods);
          $query_goods="update payment_plan set goods_detail_id = '".$goods_details_idlist."' , description = '".$desc_total_n."' where id = '".$link_id_2_pay."'";
          $result_goods= mysql_query($query_goods) or die('error in query '.mysql_error().$query_goods);
   
    /*     goods detail end   */
    
    
   if($_FILES["attach_file"]["name"] != "")
    {
    $query3="insert into attach_file set attach_id = '".$link_id_1."', link_id = '".$link_id_2."',file_name = '".$new_file_name."'";
    $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
    $link_id_4 = mysql_insert_id();
    
        $attach_file_name=mysql_real_escape_string(trim($_REQUEST['attach_file_name']));
        $temp = explode(".", $_FILES["attach_file"]["name"]);
        $arr_size = count($temp);
        $extension = end($temp);
        $new_file_name = $attach_file_name.'_'.$link_id_4.'_'.date("d_M_Y").'.'.$extension;
        move_uploaded_file($_FILES["attach_file"]["tmp_name"],"transaction_files/" . $new_file_name);
        
        
    $query4="insert into attach_file set attach_id = '".$link_id_2."', link_id = '".$link_id_1."',old_id = '".$link_id_4."',file_name = '".$new_file_name."'";
    $result4= mysql_query($query4) or die('error in query '.mysql_error().$query4);
    $link_id_5 = mysql_insert_id();
    
    
    $query5_1="update attach_file set old_id = '".$link_id_5."',file_name = '".$new_file_name."' where id = '".$link_id_4."'";
    $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);
    //$link_id_1_pay,$link_id_2_pay
    ///  payment start//
    $query3_pay="insert into attach_file set attach_id = '".$link_id_1_pay."',old_id2 = '".$link_id_4."',old_id3 = '".$link_id_5."', link_id = '".$link_id_2_pay."',file_name = '".$new_file_name."'";
    $result3_pay= mysql_query($query3_pay) or die('error in query '.mysql_error().$query3_pay);
    $link_id_4_pay = mysql_insert_id();
       
    $query4_pay="insert into attach_file set attach_id = '".$link_id_2_pay."',old_id2 = '".$link_id_4."',old_id3 = '".$link_id_5."', link_id = '".$link_id_1_pay."',old_id = '".$link_id_4_pay."',file_name = '".$new_file_name."'";
    
    $result4_pay= mysql_query($query4_pay) or die('error in query '.mysql_error().$query4_pay);
    $link_id_5_pay = mysql_insert_id();
    
    $query5_1_pay="update attach_file set old_id = '".$link_id_5_pay."',file_name = '".$new_file_name."' where id = '".$link_id_4_pay."'";
    $result5_1_pay= mysql_query($query5_1_pay) or die('error in query '.mysql_error().$query5_1_pay);
   
     $query5_1="update attach_file set old_id2 = '".$link_id_4_pay."',old_id3 = '".$link_id_5_pay."' where id = '".$link_id_4."'";
    $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);
    
     $query5_1="update attach_file set old_id2 = '".$link_id_4_pay."',old_id3 = '".$link_id_5_pay."' where id = '".$link_id_5."'";
    $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);
   
   
    
    ///  payment end//
    
    }
     else
    {
        $files = glob("drag uploads/*.*");
        $new_file_name=mysql_real_escape_string(trim($_REQUEST['attach_file_name'])).'_'.$link_id_4.'_'.date("d_M_Y");
        
        if(count($files) > 0)
        {
                    $query3="insert into attach_file set attach_id = '".$link_id_1."', link_id = '".$link_id_2."',file_name = '".$new_file_name."'";
    $result3= mysql_query($query3) or die('error in query '.mysql_error().$query3);
    $link_id_4 = mysql_insert_id();
    
            foreach($files as $file)
            {
                $basename = basename($file);
                $ext = substr(strrchr($basename,'.'),1);
                rename ("$file", "transaction_files/$new_file_name.$ext");
                
            }

                
         $new_file_name = $new_file_name.'.'.$ext;
    $query4="insert into attach_file set attach_id = '".$link_id_2."', link_id = '".$link_id_1."',old_id = '".$link_id_4."',file_name = '".$new_file_name."'";
    $result4= mysql_query($query4) or die('error in query '.mysql_error().$query4);
    $link_id_5 = mysql_insert_id();
    
    $query5_1="update attach_file set old_id = '".$link_id_5."',file_name = '".$new_file_name."' where id = '".$link_id_4."'";
    $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);
         ///  payment start//
    $query3_pay="insert into attach_file set attach_id = '".$link_id_1_pay."',old_id2 = '".$link_id_4."',old_id3 = '".$link_id_5."', link_id = '".$link_id_2_pay."',file_name = '".$new_file_name."'";
    $result3_pay= mysql_query($query3_pay) or die('error in query '.mysql_error().$query3_pay);
    $link_id_4_pay = mysql_insert_id();
       
    $query4_pay="insert into attach_file set attach_id = '".$link_id_2_pay."',old_id2 = '".$link_id_4."',old_id3 = '".$link_id_5."', link_id = '".$link_id_1_pay."',old_id = '".$link_id_4_pay."',file_name = '".$new_file_name."'";
    
    $result4_pay= mysql_query($query4_pay) or die('error in query '.mysql_error().$query4_pay);
    $link_id_5_pay = mysql_insert_id();
    
    $query5_1_pay="update attach_file set old_id = '".$link_id_5_pay."',file_name = '".$new_file_name."' where id = '".$link_id_4_pay."'";
    $result5_1_pay= mysql_query($query5_1_pay) or die('error in query '.mysql_error().$query5_1_pay);
   
     $query5_1="update attach_file set old_id2 = '".$link_id_4_pay."',old_id3 = '".$link_id_5_pay."' where id = '".$link_id_4."'";
    $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);
    
     $query5_1="update attach_file set old_id2 = '".$link_id_4_pay."',old_id3 = '".$link_id_5_pay."' where id = '".$link_id_5."'";
    $result5_1= mysql_query($query5_1) or die('error in query '.mysql_error().$query5_1);
   
   
    
    ///  payment end//
   
                
        }
        else
        {
            $new_file_name = "";        
        }
        
    }
    
    
    
	$msg = "Instant Sale Goods and Payment successfully.";
	$flag = 1;
	
}
else
{
	$files = glob("drag uploads/*.*");
	if(count($files) > 0)
	{
		foreach($files as $file)
		{
      		unlink($file);
    	}
	}
}


$wi = 0;
	while($wi<1)
	{
		$trans_id = rand(100000,999999);
		$ss="select id from payment_plan where trans_id=".$trans_id."";
		$sr=mysql_query($ss);
		$tot_rw=mysql_num_rows($sr);
		if($tot_rw == 0)
		{
			break;								
		}
	}

?>
<html>

<head>
<title>Admin Panel</title>
<style>
	/*  ******************************  For Drag CSS   ********************************* */
			
			
#dropbox{
border:1px solid #FF0000;
padding:10px;
	border-radius:3px;
	position: relative;
	min-height: 150px;
	overflow: hidden;
	padding-bottom: 40px;
    width: 500px;
	
	box-shadow:0 0 4px rgba(0,0,0,0.3) inset,0 -3px 2px rgba(0,0,0,0.1);
}


#dropbox .message{
	font-size: 11px;
    text-align: center;
    padding-top:160px;
    display: block;
	
}

#dropbox .message i{
	color:#ccc;
	font-size:10px;
	
}

#dropbox:before{
	border-radius:3px 3px 0 0;
}



/*-------------------------
	Image Previews
--------------------------*/



#dropbox .preview{
	width:150px;
	height: 20px;
	float:left;
	position: relative;
	text-align: center;
}

#dropbox .preview img{
	max-width: 240px;
	max-height:180px;
	border:3px solid #fff;
	display: block;
	
	box-shadow:0 0 2px #000;
}

#dropbox .imageHolder{
	display: inline-block;
	position:relative;
}

#dropbox .uploaded{
	position: absolute;
	top:0;
	left:0;
	height:100%;
	width:100%;
	background: url('../img/done.png') no-repeat center center rgba(255,255,255,0.5);
	display: none;
}

#dropbox .preview.done .uploaded{
	display: block;
}
</style>



<script src="js/jquery-1.12.4.min.js"></script>

<script>
    $(document).ready(function(){
        var ii=1;
 //record,snum,no_check,desc_t,qty_t,unit_price_1,sub_total,gst,total,gst_amount
 //qty_tot,unit_price_tot,sub_total_tot,total_tot,gst_amount_tot
  
        $(".add-row").click(function(){
            var desc2 = $("#desc1").val();
            var qty2 = $("#qty1").val();
            var unit_price2 = $("#unit_price1").val();
            var gst2 = $("#gst1").val();
             count=$('#myTable tbody tr').length;
             var sub_total2 = unit_price2*qty2;
             var gst_amount =(sub_total2*gst2)/100;
             var total2 = sub_total2+gst_amount;
    var markup="<tr><td style='text-align: left;padding: 2px;'><input type='checkbox' name='record' class='case'/></td><td style='text-align: left;padding: 2px;'><span id='snum"+ii+"'>"+ii+".</span><input type='hidden' id='no_check"+ii+"' value='"+ii+"' name='no_check[]'/></td>";
    markup +="<td style='text-align: left;padding: 2px;'><input type='text' id='desc_t"+ii+"' value='"+desc2+"' name='desc_t[]' style='width: 200px;' readonly='readonly'/></td> <td style='text-align: left;padding: 2px;'><input type='text' id='qty_t"+ii+"' name='qty_t[]' style='width: 70px;' readonly='readonly' value='"+qty2+"'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='unit_price_1"+ii+"' value='"+unit_price2+"' style='width: 100px;' readonly='readonly' name='unit_price_1[]'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='sub_total"+ii+"' value='"+sub_total2+"' style='width: 100px;' readonly='readonly' name='sub_total[]'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='gst"+ii+"' value='"+gst2+"' style='width: 50px;' readonly='readonly' name='gst[]'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='gst_amount"+ii+"' style='width: 100px;' readonly='readonly' value='"+gst_amount+"' name='gst_amount[]'/></td><td style='text-align: left;padding: 2px;'><input type='text' id='total"+ii+"' style='width: 100px;' readonly='readonly' value='"+total2+"' name='total[]'/></td></tr>";
    $(' #myTable tbody').append(markup);
    ii++;
   // $("#desc1").val()="";
   var vall="";
   $("#desc1").val(vall);
   $("#qty1").val(vall);
   $("#unit_price1").val(vall);
   
   var sum = 0;
  
 
var qty_t_total = 0;
var sub_total_total=0;
var unit_price_total=0;
var total_amount_total=0;
var gst_amount_total=0;
var desc_total="";
            
    for (var i = 1; i <= ii; i++) { 
            if($("#no_check"+i+"").val()==i){
            qty_t_total =Number(qty_t_total)+Number($("#qty_t"+i+"").val()) ; 
            unit_price_total =Number(unit_price_total)+Number($("#unit_price_1"+i+"").val()) ;
            sub_total_total =Number(sub_total_total)+Number($("#sub_total"+i+"").val()) ; 
            total_amount_total =Number(total_amount_total)+Number($("#total"+i+"").val()) ; 
            gst_amount_total =Number(gst_amount_total)+Number($("#gst_amount"+i+"").val()) ; 
            desc_total =desc_total+"("+i+")"+$("#desc_t"+i+"").val()+","; 
                
            }
            } 
            document.getElementById("qty_tot").value = qty_t_total;
            document.getElementById("unit_price_tot").value = unit_price_total;
            document.getElementById("sub_total_tot").value = sub_total_total;
            document.getElementById("total_tot").value = total_amount_total;
            document.getElementById("gst_amount_tot").value = gst_amount_total;
            document.getElementById("amount").value = total_amount_total;
            document.getElementById("description").value = desc_total;
        });
        
        // Find and remove selected table rows
        $(".delete-row").click(function(){
            $("#myTable tbody").find('input[name="record"]').each(function(){
                if($(this).is(":checked")){
                    $(this).parents("#myTable tbody tr").remove();
                }
             count=$('#myTable tbody tr').length;   
var qty_t_total = 0;
var sub_total_total=0;
var unit_price_total=0;
var total_amount_total=0;
var gst_amount_total=0;
var desc_total="";
            for (var i = 1; i <= ii; i++) { 
            if($("#no_check"+i+"").val()==i){
            qty_t_total =Number(qty_t_total)+Number($("#qty_t"+i+"").val()) ; 
            unit_price_total =Number(unit_price_total)+Number($("#unit_price_1"+i+"").val()) ;
            sub_total_total =Number(sub_total_total)+Number($("#sub_total"+i+"").val()) ; 
            total_amount_total =Number(total_amount_total)+Number($("#total"+i+"").val()) ; 
            gst_amount_total =Number(gst_amount_total)+Number($("#gst_amount"+i+"").val()) ; 
            //desc_total =gst_amount_total+"("+i+")"+$("#desc"+i+"").val()+",";     
            desc_total =desc_total+"("+i+")"+$("#desc_t"+i+"").val()+",";
            }
            } 
            document.getElementById("qty_tot").value = qty_t_total;
            document.getElementById("unit_price_tot").value = unit_price_total;
            document.getElementById("sub_total_tot").value = sub_total_total;
            document.getElementById("total_tot").value = total_amount_total;
            document.getElementById("gst_amount_tot").value = gst_amount_total;
            
            
            document.getElementById("amount").value = total_amount_total;
            document.getElementById("description").value = desc_total;
            
            });
        });
    });    
</script>
</head>

<body>
<?php 
include_once("header.php");
?>

<div id="wrapper">
	<?php
	include_once("leftbar.php");
	?>
	<div id="rightContent">
	<h3>
    Instant sale Invoice</h3>
	<table width="100%" cellpadding="0" cellspacing="0" border="0" >
	<tr>
		<td>
		<?php if($msg != "") { ?>
	<div class="sukses">
		<?php echo $msg; ?>
		</div>
	<?php } else if($error_msg != "") { ?>
	<div class="gagal">
		
		<?php echo $error_msg; ?>
		</div>
	<?php } ?>
		</td>
	</tr>
	</table>
	
	<div id="adddiv" >
	
	<form name="project_form" id="project_form" action="" method="post" enctype="multipart/form-data" >
        <script src="js/datetimepicker_css.js"></script>
        <link rel="stylesheet" href="css/jquery-ui.css" />
  <script src="js/jquery-1.9.1.js"></script>
  <script src="js/jquery-ui.js"></script>
           <table width="98%">
        <tr style="width: 50%;" >
            <td>
     
		<table width="98%" border="0">
            <tr><td colspan="2"><h2>Instant Sale Goods</h2></td></tr>
            
			<tr><td width="125px">Transaction ID</td>
			<td style="color:#FF0000; font-weight:bold;"><input type="hidden" id="trans_id"  name="trans_id" value="<?php echo $trans_id; ?>"/>&nbsp;<?php echo $trans_id; ?></td></tr>
			<tr><td width="125px">Invoice Issuer</td>
            <td><input type="text" id="invoice_issuer"  name="invoice_issuer" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td width="125px">Project Name</td>
			<td><input type="text" id="project"  name="project" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td >Customer Name</td>
			<td><input type="text" id="from"  name="from" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			<tr><td >Sub Division Name</td>
            <td>
             <input type="text" id="subdivision"  name="subdivision" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span>
            
            </td></tr>
            
            <tr><td >GST Division Name</td>
            <td>
             <input type="text" id="gst_subdivision"  name="gst_subdivision" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >
            &nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span>
            
            </td></tr>
                      
  			<tr><td align="left" valign="top" >Amount</td>
			<td><input type="text"  name="amount" id="amount" value="" readonly="readonly" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			<tr><td align="left" valign="top" >Date</td>
		
			<td><input type="text"  name="payment_date" id="payment_date" value="<?php echo $_REQUEST['payment_date']; ?>" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('payment_date')" style="cursor:pointer"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			<tr><td valign="top" >Description</td>
			
            <td><textarea name="description" id="description" style="width:250px; height:100px;" readonly="readonly"></textarea>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td valign="top" >Attach File</td>
			<td><input type="file" name="attach_file" id="attach_file" value="" onChange="return hide_drag();" >&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
			
			<tr><td valign="top" >Attach File Name</td>
			<td><input type="text" id="attach_file_name"  name="attach_file_name" value="" autocomplete="off"/></td></tr>
			<!--
			<tr><td valign="top" colspan="2" >
			<div id="drag_div" style="border:1px solid #CCCCCC; width:100%; background-color:#FFFFFF; border-radius:10px; ">
					
					
					<div style="height:20px; width:100%; background-color:#F9F9F9; border-top-left-radius:10px; border-top-right-radius:10px; color:#FF0000; text-align:left; float:right; " >&nbsp;&nbsp;&nbsp;&nbsp;<strong>Drag Files To Upload</strong>
							</div>
							<div id="dropbox" >
			<span class="message" >Drop Files here to upload.</span>
		</div>
		
		
		<script src="js/jquery.filedrop.js"></script>
		
		
        <script src="js/script.js"></script>		
						</div>
			</td></tr>
			-->
						
		</table>
        
         </td>
            <td style="width: 50%;" valign="top">
                
                <table width="95%">
                <tr><td colspan="2"><h2>Instant Payment Details</h2></td></tr>
            <tr><td width="125px">&nbsp;</td>
            <td style="color:#FF0000; font-weight:bold;">&nbsp;</td></tr>
            <tr><td width="125px">Paid Into</td>
            <td><input type="text" id="pay_form"  name="pay_form" value="" style="width:250px;"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td align="left" valign="top" >Payment Date</td>
        
            <td><input type="text"  name="pay_payment_date" id="pay_payment_date" value="<?php echo $_REQUEST['payment_date']; ?>" autocomplete="off" />&nbsp;<img src="js/images2/cal.gif" onClick="javascript:NewCssCal('pay_payment_date')" style="cursor:pointer"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
            <tr><td >Payment Mothod</td>
            <td><br>
            <input type="radio" id="pay_method" name="pay_method"  onchange=" return checkno_create();" value="check">
            <label for="male">Cheque</label>&nbsp;&nbsp;
            <input type="radio" id="pay_method" name="pay_method"  onchange="return checkno_create1();" value="bank">
            <label for="female">Bank</label>&nbsp;&nbsp;
            <input type="radio" id="pay_method" name="pay_method"  onchange="return checkno_create1();" value="cash">
            <label for="other">Cash</label>&nbsp;&nbsp;<br><br>
            </td></tr>
            <tr>
                
                <td colspan="2">
                    <div id="pay_check" align="left"  style="display:none; " >
                    <table>
                        <tr>
                            <td width="120px">Cheque No.</td>
                            <td><input type="text"  name="pay_checkno" id="pay_checkno" value="" /><br></td>
                        </tr>
                    </table>
                     <br>
                    </div>
                </td>
            </tr>
            
            
            <tr><td align="left" valign="top" >Amount Paid</td>
            <td><input type="text"  name="pay_amount" id="pay_amount" value="" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
        </table>
        
            </td>
        </tr>
        <tr><td colspan="2"><h3>&nbsp;</h3>
        <br>
        <b><h2>Add Goods Details :</h2></b></td></tr>
        <tr>
                <td colspan="2">
                    
            
    
        <input type="text" id="desc1" placeholder="Description">
        <input type="text" id="qty1" placeholder="Qty.">
        <input type="text" id="unit_price1" placeholder="Unit Price">
    GST :<select name="gst1" id="gst1" >
        <option value="5">5%</option>
        <option value="12">12%</option>
        <option value="18">18%</option>
        <option value="28">28%</option>
    </select>&nbsp;&nbsp;&nbsp;
        <input type="button" class="add-row" value="Add Row">
        <br><br>
    
    <table style="margin: 0px 0; border-radius: 10px;border: 1px solid #111111;">
    <tr>
        <td >
            <table  id="myTable"  style="width: 750; padding: 10px;">
    
        <thead>
            <tr>
    <th style="width: 20px;"></th>
    <th style="width: 10px;" > S. No</th>
    <th style="width: 200px;"> Description</th>
    <th style="width: 70px;"> Qty.</th>
    <th style="width: 100px;"> Unit Price</th>
    <th style="width: 100px;"> SubTotal</th>
    <th style="width: 50px;"> GST(%)</th>
    <th style="width: 100px;"> GST Amount</th>
    <th style="width: 100px;"> Total</th>
  </tr>
  </thead>
        <tbody>
           <!-- <tr>
    <td style="text-align: left;padding: 2px;"><input type='checkbox' name="record" class='case'/></td>
    <td><span id='snum'>1.</span></td>
    <td><input style="width: 200px;" readonly="readonly"  type='text' id='desc' name='desc[]'/></td>
    <td><input style="width: 70px;" type='text' id='qty' name='qty[]'/></td>
    <td><input style="width: 100px;" type='text' id='unit_price' name='unit_price[]'/></td>
    <td><input style="width: 100px;" type='text' id='sub_total' name='sub_total[]'/> </td>
    <td><input style="width: 50px;" type='text' id='gst' name='gst[]'/> </td>
    <td><input style="width: 100px;" type='text' id='gst_amount' name='gst_amount[]'/> </td>
    <td><input style="width: 100px;" type='text' id='total' name='total[]'/> </td>
  </tr>-->
        </tbody>
    </table>
        </td>
    </tr>
    <tr><td >
    <table style="width: 750; border-top: 1px dashed #dcdcdc;  margin-top: -18px; padding: 0px 10px 10px 10px;">
    <tr>
    <td style='text-align: left;padding: 2px;'>&nbsp;</td>
    <td style='text-align: left;padding: 4px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td style='text-align: left;padding: 2px;'><input style="width: 200px;" readonly="readonly"  type='text' id='desc_tot' value="Total Items"  name='desc_tot'/></td>
    <td style='text-align: left;padding: 2px;'><input style="width: 70px;" readonly="readonly" type='text' id='qty_tot' name='qty_tot'/></td>
    <td style='text-align: left;padding: 2px;'><input style="width: 100px;" readonly="readonly" type='text' id='unit_price_tot' name='unit_price_tot'/></td>
    <td style='text-align: left;padding: 2px;'><input style="width: 100px;" readonly="readonly" type='text' id='sub_total_tot' name='sub_total_tot'/> </td>
    <td style='text-align: left;padding: 2px;'><input style="width: 50px;" readonly="readonly" type='text' id='gst_tot' name='gst_tot'/> </td>
    <td style='text-align: left;padding: 2px;'><input style="width: 100px;" readonly="readonly" type='text' id='gst_amount_tot' name='gst_amount_tot'/> </td>
    <td style='text-align: left;padding: 2px;'><input style="width: 100px;" readonly="readonly" type='text' id='total_tot' name='total_tot'/> </td>
  </tr></table>
    </td></tr>
    </table>
    
        <button type="button" class="delete-row">Delete Row</button>
       
                </td>
            </tr>
            <tr><td colspan="2" align="center">
            <input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
            </td></tr>
    </table>
            
		<input type="hidden" name="action_perform" id="action_perform" value="" >
		
		</form>
		
		</div>
		
		
		

	</div>
<div class="clear"></div>
<?php
include_once("footer.php");
?>
</div>
</body>
</html>
<script>
function checkno_create()
{
            document.getElementById('pay_check').style.display='block';
       
}
function checkno_create1()
{
            document.getElementById('pay_check').style.display='none';
            document.getElementById('pay_checkno').value="";

}

function hide_drag()
{
	$("#drag_div").hide("fast");
}
function validation()
{
	if($("#from").val() == "")
	{
		alert("Please enter from data.");
		$("#from").focus();
		return false;
	}
	else if($("#project").val() == "")
	{
		alert("Please enter project name.");
		$("#to").focus();
		return false;
	}
	else if($("#subdivision").val() == "")
	{
		alert("Please Select Subdivision Option.");
		$("#subdivision").focus();
		return false;
	}
	else if($("#amount").val() == "")
	{
		alert("Please enter amount.");
		$("#amount").focus();
		return false;
	}
	else if($("#payment_date").val() == "")
	{
		alert("Please enter pay date.");
		$("#payment_date").focus();
		return false;
	}
	else
	{
		if(confirm("Do you want to print?!!!!!....."))
		{
			
			var text = '<table cellpadding="10" cellspacing="0" border="0" width="95%"><tr><td width="125px">From</td><td>'+$("#from").val()+'</td></tr><tr><td >Project</td><td>'+$("#project").val()+'</td></tr><tr><td>Amount</td><td>Rs. &nbsp;'+$("#amount").val()+'</td></tr><tr><td>Date</td><td>'+$("#payment_date").val()+'</td></tr><tr><td >Description</td><td>'+$("#description").val()+'</td></tr></table>';
						printMe=window.open();
						printMe.document.write(text);
						printMe.print();
						printMe.close();
						
			$("#action_perform").val("add_project");
			$("#project_form").submit();
			return true;
		}
		else
		{
			$("#action_perform").val("add_project");
			$("#project_form").submit();
			return true;
		}
		
	}
	
}

</script>

	<script>
	$(document).ready(function(){
		$( "#invoice_issuer" ).autocomplete({
            source: "invoice_issuer-ajax.php"
        });
        
        $( "#from" ).autocomplete({
			source: "customer-ajax.php"
		});
		$( "#project" ).autocomplete({
			source: "project-ajax.php"
		});
         $( "#subdivision" ).autocomplete({
            source: "subdivision2_ajax.php"
        });
        
         $( "#gst_subdivision" ).autocomplete({
            source: "gst_subdivision_ajax.php"
        });
        
        $( "#pay_form" ).autocomplete({
            source: "bankcash-ajax.php"
        });
		
	})
	</script>
	
<?php 

if($flag == 1)
{
	?>
	<script>
	if(confirm("Do you want to print?!!!!!....."))
		{
			
			var text = '<table cellpadding="10" cellspacing="0" border="0" width="95%"><tr><td colspan="2" >Receive Goods</td></tr><tr><td width="125px">Transaction ID</td><td><?php echo $_REQUEST['trans_id']; ?></td></tr><tr><td width="125px">From</td><td><?php echo $_REQUEST['from']; ?></td></tr><tr><td >Project</td><td><?php echo $_REQUEST['project']; ?></td></tr><tr><td>Amount</td><td>Rs. &nbsp;<?php echo $_REQUEST['amount']; ?></td></tr><tr><td>Date</td><td><?php echo $_REQUEST['payment_date']; ?></td></tr><tr><td >Description</td><td><?php echo $_REQUEST['description']; ?></td></tr></table>';
			printMe=window.open();
			printMe.document.write(text);
			printMe.print();
			printMe.close();
						
			
		}
	</script>
	<?php
}

?>