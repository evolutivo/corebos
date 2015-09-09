<?php
require_once('include/database/PearDatabase.php');
require_once('modules/Adocmaster/Adocmaster.php');
require_once('modules/Adocdetail/Adocdetail.php');
global $adb,$log,$current_user;
$inoutmaster=$_REQUEST['record_id'];
 
$ids = explode(";", $inoutmaster);
$nrids=count($ids);

    
     $sql = "SELECT *
          FROM vtiger_pcdetails pc
          INNER JOIN vtiger_crmentity ce ON ce.crmid=pc.pcdetailsid
          INNER JOIN vtiger_project proj ON proj.projectid=pc.project
          LEFT JOIN vtiger_account acc ON acc.accountid=proj.linktoaccountscontacts
          WHERE ce.deleted=0 AND pc.pcdetailsid= $ids[0]
        
        ";
    $result = $adb->query($sql);
       $account = $adb->query_result($result,0,'linktobuyer');

    
    
    
    
    
    
    

    $focus = CRMEntity::getInstance("Adocmaster");
    $focus->id='';
    $focus->column_fields['adocmastername']='OUTGOING DDT RESO PARTI';
    
    $account = $adb->query_result($result,0,'linktobuyer');
     $sql4="SELECT accountname,codcat FROM vtiger_account WHERE accountid = $account";
    $result4 = $adb->query($sql4);
    $bill_city=$adb->query_result($result,0,'bill_city');
    $cat = $adb->query_result($result,0,'codcat');
    $accountname= $adb->query_result($result,0,'accountname');
 

        
    $focus->column_fields['adoc_account']=$adb->query_result($result,0,'accountid');      
     $focus->column_fields['causaleadm']='RESO DA RIPARAZIONE';    

     $focus->column_fields['cittacat']=$cityname;
       $focus->column_fields['accountcommittente']=$accountname;
        $focus->column_fields['accounttname']=$accountname;
        $focus->column_fields['clientaddress']=$adb->query_result($result,0,'ship_streetpr'); 
        $focus->column_fields['telefonocliente']=$adb->query_result($result,0,'phone');
          $focus->column_fields['cellularecliente']=$adb->query_result($result,0,'cellulare');
        $focus->column_fields['indirizzocommittente']=$adb->query_result($result,0,'phone');
     
    $focus->column_fields['doctype']='OUTGOING DDT RESO PARTI'; 
    //$focus->column_fields['nrdoc']=$nr_doc; 
    $focus->column_fields['assigned_user_id']=$current_user->id;
    $focus->save("Adocmaster"); 
 

    
    for($i=0;$i<$nrids-1;$i++)
    {
         $sql1 = "SELECT *
          FROM vtiger_pcdetails pc
          INNER JOIN vtiger_crmentity ce ON ce.crmid=pc.pcdetailsid
          INNER JOIN vtiger_project proj ON proj.projectid=pc.project
          LEFT JOIN vtiger_account acc ON acc.accountid=proj.linktoaccountscontacts
          LEFT JOIN vtiger_products  pr ON pr.productid=pc.linktoproduct
          WHERE ce.deleted=0 AND pc.pcdetailsid= $ids[$i]
        
        ";
    $result1 = $adb->query($sql1);
     
       $focus_det = CRMEntity::getInstance("Adocdetail");
    $focus_det->id='';
    
    $focus_det->column_fields['adocdetailname']=$adb->query_result($result1,0,'pcdescriptionname');
    //$focus_det->column_fields['nrline']=$adb->query_result($result,$i,'linktoaccountscontacts');
  $focus_det->column_fields['adocdetail_project']=$adb->query_result($result1,0,'projectid');
    $focus_det->column_fields['adoc_product']=$adb->query_result($result1,0,'productid');
    $focus_det->column_fields['adoc_quantity']= $adb->query_result($result1,0,'quantity');
    $focus_det->column_fields['adoc_price']= $adb->query_result($result1,0,'teknemaunitprice'); 
    $focus_det->column_fields['add_no_parte']= $adb->query_result($result1,0,'partino'); 
//    $focus_det->column_fields['inout_docnr']= $adb->query_result($result,$i,'linktoaccountscontacts');
//    $focus_det->column_fields['poteknema']=  $adb->query_result($result,$i,'linktoaccountscontacts');
//    $focus_det->column_fields['posupplier']=  $adb->query_result($result,$i,'linktoaccountscontacts');
//    $focus_det->column_fields['riferimento']=  $adb->query_result($result,$i,'linktoaccountscontacts');
//    $focus_det->column_fields['adoc_stock']= $adb->query_result($result,$i,'linktoaccountscontacts');
    $focus_det->column_fields['adoctomaster']= $focus->id;
    $focus_det->column_fields['assigned_user_id']= $current_user->id;
        $focus_det->save("Adocdetail"); 
        $adb->query("UPDATE vtiger_pcdetails set pcdetailsstatus='SPEDITO DAL CAT' WHERE pcdetailsid=".$ids[$i]);
        
    }
      
    








?>
