<?php
require_once('include/database/PearDatabase.php');
require_once('modules/Adocmaster/Adocmaster.php');
require_once('modules/Adocdetail/Adocdetail.php');
global $adb,$log,$current_user;
$inoutmaster=$_REQUEST['record_id'];
 $sql11 = "SELECT vtiger_account.docaccett,vtiger_account.accountid
FROM vtiger_project
LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid=vtiger_project.projectid
LEFT JOIN vtiger_account ON vtiger_account.accountid = vtiger_project.linktobuyer WHERE deleted=0
			AND `projectid`=$inoutmaster";
 $result11 = $adb->query($sql11);
 $docaccet = $adb->query_result($result11,0,0);
 $accid = $adb->query_result($result11,0,1);
 $var = 0;
      $sql10 ="SELECT `doctype` FROM `vtiger_adocmaster`
          LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid=vtiger_adocmaster.adocmasterid
          WHERE deleted=0
			AND `project`=$inoutmaster";
      $result10 = $adb->query($sql10);
   echo $adb->num_rows($result10);
        for($i=0;$i<$adb->num_rows($result10);$i++)
    {
            $vl = strpos($adb->query_result($result10,$i,0),"Accettazione Prodotto");
         
             if($vl == 0)
             {  $var = 1;
            }
    }
   
if($var == 0){
    $docaccet++;
    
    $adb->query("UPDATE vtiger_account set docaccett=$docaccet WHERE accountid=".$accid);
}
    
    $sql1 = "SELECT *
		FROM vtiger_pcdetails
			LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid=vtiger_pcdetails.pcdetailsid
                        LEFT JOIN vtiger_project ON vtiger_project.projectid=vtiger_pcdetails.project
                        LEFT JOIN vtiger_account ON vtiger_account.accountid=vtiger_project.linktoaccountscontacts
                        LEFT JOIN vtiger_sites ON vtiger_account.accountid=vtiger_sites.accountid
                        LEFT JOIN vtiger_accountbillads ON vtiger_account.accountid = vtiger_accountbillads.accountaddressid
                        WHERE deleted=0
			AND project=$inoutmaster

        ";
    $result1 = $adb->query($sql1);
    $sql = "SELECT *
		FROM vtiger_pcdetails
			LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid=vtiger_pcdetails.pcdetailsid
                        LEFT JOIN vtiger_products ON vtiger_products.productid=vtiger_pcdetails.linktoproduct
                        LEFT JOIN vtiger_project ON vtiger_project.projectid=vtiger_pcdetails.project
                        
                        WHERE deleted=0
			AND project=$inoutmaster
        
        ";
    $result = $adb->query($sql);
    $sql2 = "SELECT * 
FROM vtiger_project
LEFT JOIN vtiger_products ON vtiger_products.productid = vtiger_project.products
LEFT JOIN vtiger_account ON vtiger_account.accountid = vtiger_project.linktoaccountscontacts
LEFT JOIN vtiger_sites ON vtiger_account.accountid = vtiger_sites.accountid
LEFT JOIN vtiger_accountbillads ON vtiger_account.accountid = vtiger_accountbillads.accountaddressid
WHERE projectid =$inoutmaster";
   
    $result2 = $adb->query($sql2);
    $sql5 = "SELECT vtiger_account.accountid
FROM vtiger_project
LEFT JOIN vtiger_products ON vtiger_products.productid = vtiger_project.products
LEFT JOIN vtiger_account ON vtiger_account.accountid = vtiger_project.linktoaccountscontacts
LEFT JOIN vtiger_sites ON vtiger_account.accountid = vtiger_sites.accountid
LEFT JOIN vtiger_accountbillads ON vtiger_account.accountid = vtiger_accountbillads.accountaddressid
WHERE projectid =$inoutmaster";
    $result5 = $adb->query($sql5);
      //echo $adb->query_result($result2,0,'accountname');exit;
   // $nr_doc=crp_series(); 
    $log->debug('juli'.$nr_doc);
    $focus = CRMEntity::getInstance("Adocmaster");
    $focus->id='';

    $focus->column_fields['adocmastername']='Accettazione Prodotto';
    
    $prog =$adb->query_result($result1,0,'progetto');
    $sql3="SELECT projectname FROM vtiger_project WHERE projectid = $inoutmaster";
    $result3 = $adb->query($sql3);
    $projname= $adb->query_result($result3,0,'projectname');
    
    $account = $adb->query_result($result2,0,'linktobuyer');
     $sql4="SELECT accountname FROM vtiger_account WHERE accountid = $account";
    $result4 = $adb->query($sql4);
    $accountname= $adb->query_result($result4,0,'accountname');
   
    $focus->column_fields['adoc_account']=$adb->query_result($result5,0,0);   
    $focus->column_fields['docdate_from']=$adb->query_result($result1,0,'startdate');   
    $focus->column_fields['project']=$inoutmaster; 
     $focus->column_fields['causaleadm']='Accettazione Prodotto';    

      $focus->column_fields['projectrma']=$adb->query_result($result2,0,'rma');
       $focus->column_fields['projectcommessa']=$projname;
        $focus->column_fields['accountcommittente']=$accountname;
       if(strpos($adb->query_result($result2,0,'customertype'),"Dealer") !=false)
         $focus->column_fields['accounttname']=$adb->query_result($result2,0,'dealer');
       else
        $focus->column_fields['accounttname']=$adb->query_result($result2,0,'accountid');
        $focus->column_fields['clientaddress']=$adb->query_result($result2,0,'bill_street');
        
        $ac = $adb->query_result($result5,0,0);
        $sql6="SELECT accountname FROM vtiger_account WHERE accountid = $ac";
    $result6 = $adb->query($sql6);
    
          $focus->column_fields['accounttname']=  $adb->query_result($result6,0,'accountname');; 
        $focus->column_fields['telefonocliente']=$adb->query_result($result2,0,'phone');
          $focus->column_fields['cellularecliente']=$adb->query_result($result2,0,'cellulare');
        $focus->column_fields['indirizzocommittente']=$adb->query_result($result2,0,'phone');
     
    $focus->column_fields['doctype']='Accettazione Prodotto'; 
    //$focus->column_fields['nrdoc']=$nr_doc; 
    $focus->column_fields['cap']=$adb->query_result($result1,0,'ship_codepr');
    $focus->column_fields['telefonocliente']=$adb->query_result($result1,0,'mphone');
    $focus->column_fields['assigned_user_id']=$current_user->id;
    $focus->save("Adocmaster"); 
 

    
    $focus_det = CRMEntity::getInstance("Adocdetail");
    $focus_det->id='';

    $focus_det->column_fields['adocdetailname']=$adb->query_result($result2,0,'projectname');
    //$focus_det->column_fields['nrline']=$adb->query_result($result,$i,'linktoaccountscontacts');
  
     $focus_det->column_fields['adoc_product']=$adb->query_result($result2,0,'model_number');
    $focus_det->column_fields['adoc_quantity']= '1';
    //$focus_det->column_fields['adoc_price']= $adb->query_result($result,$i,'teknemaunitprice'); 
//    $focus_det->column_fields['inout_docnr']= $adb->query_result($result,$i,'linktoaccountscontacts');
//    $focus_det->column_fields['poteknema']=  $adb->query_result($result,$i,'linktoaccountscontacts');
//    $focus_det->column_fields['posupplier']=  $adb->query_result($result,$i,'linktoaccountscontacts');
//    $focus_det->column_fields['riferimento']=  $adb->query_result($result,$i,'linktoaccountscontacts');
//    $focus_det->column_fields['adoc_stock']= $adb->query_result($result,$i,'linktoaccountscontacts');
    $focus_det->column_fields['adoctomaster']= $focus->id;
    $focus_det->column_fields['assigned_user_id']= $current_user->id;
    $focus_det->save("Adocdetail"); 
      

 // crp_series();       
 
//function crp_series(){
//	global $adb,$log;
//	$proj_id=$_REQUEST['record'];
// $progressive_final='';
//        $settingquery="SELECT * FROM vtiger_setting 
//                     WHERE tipo_documento='Accettazione Prodotto'   ";
//        
//      $setting=$adb->query($settingquery);
//      $nrsetting=$adb->num_rows($setting);$log->debug('nr_test'.$nrsetting);
//if($nrsetting>0){
//      $settingid=$adb->query_result($setting,0,'settingid');
//      $progressive=$adb->query_result($setting,0,'ultimo_no')+1;
//      $suffisso=$adb->query_result($setting,0,'suffisso');
//      }
//else
//{
//$settingquery="SELECT * FROM vtiger_setting 
//                     WHERE tipo_documento='Accettazione Prodotto'   ";
//        
//      $setting=$adb->query($settingquery);
//      $nrsetting=$adb->num_rows($setting);
//if($nrsetting>0){
//      $settingid=$adb->query_result($setting,0,'settingid');
//      $progressive=$adb->query_result($setting,0,'ultimo_no')+1;
//      $suffisso=$adb->query_result($setting,0,'suffisso');
//}
//}
//if($progressive/10 <1)
//    $progressive_final='000'.$progressive;
//    else if($progressive/100 <1)
//        $progressive_final='00'.$progressive;
//    else if($progressive/1000 <1)
//        $progressive_final='0'.$progressive;
//        else
//          $progressive_final=$progressive;  
//      
//      $adb->query("UPDATE vtiger_setting set ultimo_no=$progressive, ultima_data='".date('Y-m-d')."' WHERE settingid=".$settingid);
//      return $suffisso."-".$progressive_final;
//}








?>
