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


function get_total($gst_id,$end_date)
{
    $date_list_query2 = "select SUM(gst_amount) as gst_amount  from goods_details where gst_subdivision = '".$gst_id."' ";
    //$date_list_query2 = "select SUM(gst_amount) as gst_amount  from goods_details where gst_subdivision = '".$gst_id."' and payment_date <= '".$end_date."' ";
    $date_list_result2 = mysql_query($date_list_query2) or die("error in date list query ".mysql_error());
    $total_day2 = mysql_num_rows($date_list_result2);
    if($total_day2 > 0)
    {
        $date_list2 = mysql_fetch_array($date_list_result2);
        return $date_list2['gst_amount'];
    }
    else
    {
        return 0;
    }
    
}


/*     Create  Account   */


if(trim($_REQUEST['action_perform']) == "add_bank")
{
     $gst_subdivision=mysql_real_escape_string(trim($_REQUEST['gst_subdivision']));
    $gst_type=mysql_real_escape_string(trim($_REQUEST['gst_type']));
    
    $quuerrr="select id from gst_subdivision where name = '".$gst_subdivision."'";
    
    $sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
    $no=mysql_num_rows($sql);
    if($no > 0)
    {
        
        
        $error_msg = "gst_subdivision name already exist try another";
        
    }
    else 
    {
        $query="insert into gst_subdivision set name = '".$gst_subdivision."' ,type='".$gst_type."'";
        $result= mysql_query($query) or die('error in query '.mysql_error().$query);
        $msg = "GST subdivision added successfully.";
    }
    
}

/*     Deletion  Account   */

if(mysql_real_escape_string(trim($_REQUEST['gst_search'])) != "")
{
    /* $pay_from_arr = explode(" -",$_REQUEST['bank_search']);
     $query = "select * from gst_subdivision where id ='".$pay_bank_id."' and type = 'bank account' ORDER BY bank_account_name ASC";
    $result = mysql_query($query) or die('error in query select bank query '.mysql_error().$query);
    $total_row = mysql_num_rows($result);
    */
    $column = $_REQUEST['gst_search'];
   /* if($column =="All"){
        $query = "select * from gst_subdivision where  type = 'output_gst' ORDER BY name ASC ";
   }else{
     $query = "select * from gst_subdivision where name LIKE '%".mysql_real_escape_string(trim($_REQUEST['gst_search']))."%' and type = 'output_gst' ORDER BY name ASC LIMIT $startResults, $resultsPerPage";   
   }*/
    $query = "select * from gst_subdivision where name LIKE '%".mysql_real_escape_string(trim($_REQUEST['gst_search']))."%' and type = 'output_gst' ORDER BY name ASC";
    $result = mysql_query($query) or die('error in query select bank query '.mysql_error().$query);
    $total_row = mysql_num_rows($result);
}
else
{
    $query = "select * from gst_subdivision where type = 'output_gst' ORDER BY name ASC";
    $result = mysql_query($query) or die('error in query select bank query '.mysql_error().$query);
    $total_row = mysql_num_rows($result);
}
$page = $_REQUEST['page'];
if ($page < 1) $page = 1;
$numberOfPages = numberofpages();
$resultsPerPage = resultperpage();
$startResults = ($page - 1) * $resultsPerPage;
$totalPages = ceil($total_row / $resultsPerPage);

if(mysql_real_escape_string(trim($_REQUEST['gst_search'])) != "")
{
    //$column = $_REQUEST['search_type'];
     $column = $_REQUEST['gst_search'];
   
   if($column =="All"){
       $select_query = "select * from gst_subdivision where  type = 'output_gst' ORDER BY name ASC ";
   }else{
    $select_query = "select * from gst_subdivision where name LIKE '%".mysql_real_escape_string(trim($_REQUEST['gst_search']))."%' and type = 'output_gst' ORDER BY name ASC LIMIT $startResults, $resultsPerPage";   
   }
    
    $select_result = mysql_query($select_query) or die('error in query select output gst query '.mysql_error().$select_query);
    $select_total = mysql_num_rows($select_result);

}
else
{
    $select_query = "select * from gst_subdivision where type = 'output_gst' ORDER BY name ASC LIMIT $startResults, $resultsPerPage";
    $select_result = mysql_query($select_query) or die('error in query select output gst query '.mysql_error().$select_query);
    $select_total = mysql_num_rows($select_result);
}

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
<html>
<head>
<title>Admin Panel</title>

</head>
<script src="js/datetimepicker_css.js"></script>
<body>
<?php 
include_once("header.php");
?>

<div id="wrapper">
    <?php
    include_once("leftbar.php");
    ?>
    <div id="rightContent" >
    
    <table width="100%" cellpadding="0" cellspacing="0" border="0" >
    <tr>
        <td>
        <?php $gst_subdivision = $_REQUEST['gst_subdivision_id']; ?>
        <h3>Output GST subdivision - <?php echo get_field_value("name","gst_subdivision","id",$_REQUEST['gst_subdivision_id']); ?></h3>
        </td>
        <td width="250">
        </td>
    </tr>
    </table>
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
    <br>
    <!--
    <div id="adddiv"  style="  display:<?php if($error_msg != "") { ?>block<?php } else { ?>none<?php } ?>;">
    
    <form name="gst_form" id="gst_form" action="" method="post" >
        <table width="95%">
            <tr><td width="200px">Output GST Sub Division Name</td>
            <td><input type="text" id="gst_subdivision" style="width: 250px"  name="gst_subdivision" value="<?php echo $_REQUEST['gst_subdivision']; ?>"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <input type="hidden" name="gst_type" id="gst_type" value="output_gst">
            
         <tr><td></td><td><br>
            <input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();"></td></tr>
            
            <tr><td colspan="2" align="center">
            <br><h3>&nbsp;</h3><br>
            </td></tr>
        </table>        <input type="hidden" name="action_perform" id="action_perform" value="" >
        <input type="hidden" name="del_id" id="del_id" value="" >
        </form>
        
        </div> -->
        <!--<form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid1();" enctype="multipart/form-data">
             
            <link rel="stylesheet" href="css/jquery-ui.css" />
             <script src="js/jquery-1.9.1.js"></script>
             <script src="js/jquery-ui.js"></script>
              <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                        <td width="500" align="left" valign="top">Search By Output GST Division Name
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="text" id="gst_search"  name="gst_search" value="" style="width:250px;"/></td>
                        
                        <td align="left" valign="top" ><input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='output_gst_subdivision.php';"  />&nbsp;&nbsp;<input type="button" name="add_button" id="add_button" value="Add" class="button" onClick="return add_div();"  /></td>
                        </tr>
              </table>
              <br><br><br>
                <input type="hidden" name="page" id="page" value=""  />
        </form> -->
        <!--
        <form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" enctype="multipart/form-data">
         
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="255" align="left" valign="top"> Search By:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          </td>
                    <td width="180" align="left" valign="top"><input type="text" name="search_text" id="search_text" value="<?php echo mysql_real_escape_string(trim($_REQUEST['search_text'])); ?>" /></td>
                    
                    <td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  /></td>
                    
                    
                </tr>
            </table>
          
            </form>
        -->
        <table class="data">
            <tr class="data">
                <th class="data" width="30px">No</th>
                <th class="data">Customer Name</th>
                <th class="data">Project Name</th>
                <th class="data"> Subdivision Name </th>
                
                <!--<th class="data" width="75px">Action</th>-->
            </tr>
            <?php
            if($select_total > 0)
            {
                $i=1;
                $grand_total = 0;
                
                while($select_data = mysql_fetch_array($select_result))
                {
                $ii=$i+$startResults;    
                     ?>
                    <tr class="data">
                        <td class="data" width="30px"><?php echo $ii; ?></td>
                        
                        <td class="data"><a href="output_gst_subdivision_ledger.php?gst_subdivision_id=<?php echo $select_data['id']; ?>" title="View Ledger"  ><?php echo $select_data['name']; ?></a></td>
                        <td class="data" <?php if(get_total($select_data['id'],strtotime(date("d-m-Y")))<0) { ?> style="color:#FF0000;" <?php } ?>><?php echo currency_symbol().number_format(get_total($select_data['id'],strtotime(date("d-m-Y"))),2);
                        $get_total = get_total($select_data['id'],strtotime(date("d-m-Y")));
                        if($get_total>=0)
                        {
                            $plus_total = $plus_total+$get_total;
                        }
                        else
                        {
                            $minus_total = $minus_total+$get_total;
                        }
                        
                        $grand_total = $grand_total+$get_total;
                         ?></td>
                        <td class="data" ></td>
                        <td class="data" width="75px">
                        <center>
                        <a href="javascript:account_delete(<?php echo $select_data['id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>&nbsp;&nbsp;&nbsp;
                        <a href="edit_output_gst_subdivision.php?id=<?php echo $select_data['id']; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                        </center>
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
                    <td  width="30px" colspan="7" class="record_not_found" >Record Not Found</td>
                </tr>
                <?php
            }
            ?>
            
        </table>
        <div class="pagination">
        
        <?php
            
                        if($page > 1)
                        {
                            $page = $page-1;
                            echo '<a href="javascript:void(0)" onclick="return show_records('.$page.')" ><span ><< prev</span></a>&nbsp';
                            $page = $page+1;
                        }
                            
                        ?>
                        
                        <?php
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
                        ?>
                        
                        <?php
                        if ($page < $totalPages)
                        {
                            $page = $page+1;
                            echo '<a href="javascript:void(0)" onclick="return show_records('.$page.')" >next >></a>&nbsp;';
                            $page = $page-1;
                        }
                        
                     ?>
       
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
    $(document).ready(function(){
       
         $( "#gst_search" ).autocomplete({
            source: "output_gst_subdivision_ajax.php"
        }); 
       
        
    })
    </script>
  
<script>
function add_div()
{
    $("#adddiv").toggle("slow");
}

function validation()
{
    if($("#gst_subdivision").val() == "")
    {
        alert("Please enter GST Subdivision name.");
        $("#gst_subdivision").focus();
        return false;
    }
    else
    {
        $("#action_perform").val("add_bank");
        $("#gst_form").submit();
        return true;
    }
    
}
function account_delete(del_id)
{
    if(confirm("Are you sure want to delete?!!!!!......"))
    {
        $("#action_perform").val("delete_bank");
        $("#del_id").val(del_id);
        $("#gst_form").submit();
        return true;
    }
}
function search_valid()
{
    if(document.getElementById("search_type").value=="" && document.getElementById("search_text").value=="")
    {
        alert("Please Select Search Type and Search Text");
        document.getElementById("search_text").focus();
         return false;
    }
    else if(document.getElementById("search_type").value=="")
    {
     alert("Please Select Search Type");
     document.getElementById("search_type").focus();
     return false;
    }
    else if(document.getElementById("search_text").value=="")
    {
     alert("Please enter search text to search");
     document.getElementById("search_text").focus();
     return false;
    } 
    
}
function show_records(getno)
{
    //alert(getno);
    document.getElementById("page").value=getno;
    document.search_form.submit(); 
}
</script>
  