      <?php include_once ("top_menu.php"); ?>
        
        <!-------------->  
        <div class="u-layout" style="display:block;">
            
            <!----- Top 1 open --------> 
            <div class="u-layout-row" style=" width:100%;" >
            <div class="u-align-left u-container-style u-layout-cell u-size-60 u-layout-cell-1" style="margin-top:-20px;margin-bottom:0px; float: right; width:100%;">
                <div class="u-expanded-width u-layout-grid u-list u-list-1" style="height:50px;padding-bottom:0px;" style="float: right;margin-top:0px;margin-bottom:0px;width:100%;"  >
                  <div class="u-align-left u-container-style u-list-item u-repeater-item u-shape-rectangle u-white u-list-item-1" style=" float: right; width:100%; padding:10px;margin-top:0px;margin-bottom:0px;text-align: right; "  >
                 
                  <h4 class="u-text-2 u-text-palette-1-base " style="padding:0px; margin:0px;">Cash Account</h4>
                  
    &nbsp;
     <a href="add_cash.php" style="float:right; margin-right:0px; margin-left:730px;"><input type="button" name="add_button" id="add_button" value="" class="button_add" /></a>
    <input type="button" name="print_button" id="print_button" value="" class="button_print" onClick="return print_data();"  />
    
    <input type="button" id="export_to_excel" value="" class="button_export" >&nbsp;&nbsp;
    <input type="button" id="search" value="" class="button_search1" onClick="search_display();" >
    
                  </div>
                </div>
              </div>
              </div>
              <!---top 1 close ---->
              
            <!----- Top 2 open --------> 
            
             <div class="u-layout-row" id="search_div_1" name="search_div_1" style="display:none;">
             <input type="hidden" name="search_check_val" id="search_check_val" value="0" >
            
                <div class="u-align-left u-container-style u-layout-cell u-size-60 u-layout-cell-1" style="padding-right:0px;margin-bottom:-0px; margin-top:-45px;">
                  <div class="u-expanded-width u-layout-grid u-list u-list-1" style=""  >
                    <div class="u-align-left u-container-style u-list-item u-repeater-item u-shape-rectangle u-white u-list-item-1" style="padding:10px;margin-top:0px;margin-bottom:0px;"  >
                    <form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" enctype="multipart/form-data">
         
                    <table width="100%" align="center">
    <tr><td valign="top">
        
              <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                        <td width="450" align="left" valign="top">Search By Bank Account Name
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="text" id="bank_search"  name="bank_search" value="<?php echo $_REQUEST['bank_search']; ?>" style="width:250px;"/>
                       
                      </td>
                        
                        <td align="left" valign="top" ><input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;
                      </td>
                        </tr>
              </table>
        </form>
        
        <form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" enctype="multipart/form-data">
         
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="255" align="left" valign="top"> Search By:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <select name="search_type" id="search_type" >
                        <option value="" >-- Please Select --</option>
                        <option value="bank_account_name" <?php if($_REQUEST['search_type'] == "bank_account_name") echo 'selected="selected"'; ?>  >Bank Account Name</option>
                       <!-- <option value="bank_account_number" <?php if($_REQUEST['search_type'] == "bank_account_number") echo 'selected="selected"'; ?>  >Bank Account Number</option>
                        <option value="bank_name" <?php if($_REQUEST['search_type'] == "bank_name") echo 'selected="selected"'; ?>  >Bank Name</option>
                        
                        -->
                        </select>
                  </td>
                    <td width="180" align="left" valign="top"><input type="text" name="search_text" id="search_text" value="<?php echo mysql_real_escape_string(trim($_REQUEST['search_text'])); ?>" /></td>
                    
                    <td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='cash.php';"  /></td>
                    
                </tr>
            </table>
            <input type="hidden" name="page" id="page" value=""  />
            </form>
</td><td valign="top" width="300px;" align="right">
<span id="plus_total_div" style="color:#000000; font-size:18px; font-weight:bold;"></span>
        <BR>
        <span id="minus_total_div" style="color:#FF0000; font-size:18px; font-weight:bold;"></span>
        <BR>
        <span id="grand_total_div" style="color:#FF0000; font-size:18px; font-weight:bold;"></span>
</td>
</tr></table>
<input type="hidden" name="page" id="page" value=""  />
         </form>
                  </div>
                  </div>
                </div>
              </div>
              <!------Top 2 Close ------------->
          </div>
          <!-------------->
          <!----- Top 3 open -------->  
          <div class="u-layout">
            <div class="u-layout-row">
              <div class="u-container-style u-layout-cell u-size-60 u-layout-cell-2" style="margin-top:-40px;">
                <div class="u-expanded-width u-layout-grid u-list u-list-1" >
                  <div class="u-repeater u-repeater-1" >
                    <div class="u-align-left u-container-style u-list-item u-repeater-item u-shape-rectangle u-white u-list-item-1" >
                      <div class="u-container-layout u-similar-container u-container-layout-1" style="padding:0px 0px;" >
                      <table class="data" id="my_table" border="1" width="100%" cellpadding="1" cellspacing="0" style="border: 1px solid #111111;">
        
        <tr >
        <thead class="report-header">
            <th class="data" width="30px">No</th>
            <th class="data">Bank Account Name</th>
            <th class="data">Bank Account Number</th>
            
            <th class="data">Bank Name</th>
            <th class="data">Current Balance</th>
            <th class="data">No.OfEntries</th>
            <th class="data" width="75px" id="header1">Action</th>
            </thead>
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
                    <td class="data"><a href="cash-ledger.php?bank_id=<?php echo $select_data['id']; ?>" title="View Ledger"  ><?php echo $select_data['bank_account_name']; ?></a></td>
                    <td class="data"><a href="bank-ledger.php?bank_id=<?php echo $select_data['id']; ?>" title="View Ledger"  ><?php echo $select_data['bank_account_number']; ?></a></td>
                                            <td class="data"><?php echo $select_data['bank_name']; ?></td>
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
                     <td class="data" width="30px" align="center">
                     <?php
                      
                        
                    
                       $select_tot = "select SUM(debit) as total_debit,SUM(credit) as total_credit , count(id) as no_entry from payment_plan where description != 'Opening Balance'  and bank_id='".$select_data['id']."' and bank_id!='' and bank_id > 0 ";
$result_tot = mysql_query($select_tot) or die('error in query select subdivision query '.mysql_error().$select_tot);
//$total_tot = mysql_num_rows($result_tot);
  $select_data3 = mysql_fetch_array($result_tot);
                    echo $select_data3['no_entry'];
                   // echo $select_data3['total_credit']-$select_data3['total_debit']
                     ?>
                     
                     </td>
                    <td class="data" width="75px" align="left">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="edit_cash.php?id=<?php echo $select_data['id']; ?>"><img src="mos-css/img/edit.png" title="Edit"></a>
                     <?php 
                        if($select_data3['no_entry']<1)
                        { ?>
          <a href="javascript:account_delete(<?php echo $select_data['id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>&nbsp;&nbsp;&nbsp;    
                     <?php    }
                     ?>
                    <!--<a href="javascript:account_delete(<?php echo $select_data['id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>&nbsp;&nbsp;&nbsp;-->
                    
                    
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
    
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <!----- Top 3 open -------->  
          
      </div>
     