<?php

require_once('include/database/PearDatabase.php');
require_once('modules/Adocmaster/Adocmaster.php');
require_once('modules/Adocdetail/Adocdetail.php');
include_once('data/CRMEntity.php');
include_once('modules/Map/Map.php');
require_once('include/utils/utils.php');
global $adb,$log,$current_user;
  $log->debug('prova');
 
 $recordid = $argv[1];
 $mapid = $argv[2];
 $causale=$argv[4];
  
$sql10 = "SELECT * FROM `vtiger_stocksettings` LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid=vtiger_stocksettings.stocksettingsid WHERE stocksettingsname = '$causale'";
$result10 = $adb->query($sql10);
$nrcusale=$adb->num_rows($result10);
$mapidstock=$adb->query_result($result10,0,'mapstock'); 
    if($nrcusale ==1){
        $sql11 = "SELECT * FROM `vtiger_stocksettings` LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid=vtiger_stocksettings.stocksettingsid WHERE stocksettingsname = '$causale'";
        $result11 = $adb->query($sql11);
             
        $cedente =$adb->query_result($result11,0,'cedente'); 
        $mod =getSalesEntityType($cedente);
             if($mod !='Map')
             {
                 $cedente =$adb->query_result($result11,0,'cedente');
                 
             }
             else {
                    $cedente=''; 
                  }
             
        $cessionario=$adb->query_result($result11,0,'cessionario');
        $mod =getSalesEntityType($cessionario);
             if($mod !='Map')
             {$cedente =$adb->query_result($result11,0,'cessionario');}
        else {
           $cedente=''; 
        }
        
        $intestatario=$adb->query_result($result11,0,'intestatario');
        $mod =getSalesEntityType($intestatario);
             if($mod !='Map')
             {$cedente =$adb->query_result($result11,0,'intestatario');}
        else {
           $cedente=''; 
        }
    }
   //numero documento 
   $proj_id=$recordid;
 $progressive_final='';
        $settingquery="SELECT * FROM vtiger_setting 
                     WHERE tipo_documento='ODA Ordine di acquisto' ";
        
      $setting=$adb->query($settingquery);
      $nrsetting=$adb->num_rows($setting);
if($nrsetting>0){
      $settingid=$adb->query_result($setting,0,'settingid');
      $progressive=$adb->query_result($setting,0,'ultimo_no')+1;
      $suffisso=$adb->query_result($setting,0,'suffisso');
      }
else
{
$settingquery="SELECT * FROM vtiger_setting 
                     WHERE tipo_documento='ODA Ordine di acquisto'   ";
        
      $setting=$adb->query($settingquery);
      $nrsetting=$adb->num_rows($setting);
if($nrsetting>0){
      $settingid=$adb->query_result($setting,0,'settingid');
      $progressive=$adb->query_result($setting,0,'ultimo_no')+1;
      $suffisso=$adb->query_result($setting,0,'suffisso');
}
}
if($progressive/10 <1)
    $progressive_final='000'.$progressive;
    else if($progressive/100 <1)
        $progressive_final='00'.$progressive;
    else if($progressive/1000 <1)
        $progressive_final='0'.$progressive;
        else
          $progressive_final=$progressive;  
      
      $adb->query("UPDATE vtiger_setting set ultimo_no=$progressive, ultima_data='".date('Y-m-d')."' WHERE settingid=".$settingid);
      $nr_doc= $suffisso."-".$progressive_final;
    
 
 //echo $nr_doc;
    $focus = new Adocmaster();
    $focus->id='';
    $focus->column_fields['nrdoc']=$nr_doc;
    $focus->column_fields['doctype']='ODA Ordine di acquisto';
    if($causale=='TRASH'){
    $focus->column_fields['adocmastername']='ODA Ordine di acquisto';}
    else
    {$focus->column_fields['adocmastername']='DDT Prodotto';}
    $focus->column_fields['adoc_account']='';     
    $focus->column_fields['project']=$recordid; 
    $focus->column_fields['cedente']=$cedente; 
    $focus->column_fields['cessionario']=$cessionario; 
    $focus->column_fields['intestatario']=$intestatario;   
    $focus->column_fields['accountcommittente']='';
    $focus->column_fields['assigned_user_id']=$current_user->id;
    $focus->saveentity("Adocmaster"); 
    $log->debug('juli');
  
    $focus=  CRMEntity::getInstance("Map");
$focus->retrieve_entity_info($mapidstock,"Map");
//$log->debug('julidimo'.$mapidstock);
$sql1=$focus->getMapSQL();
   $log->debug('julidimo'.$sql1);
   if($causale=='TRASH'){
$result1 = $adb->pquery($sql1,array($recordid));
$nrpcdetails=$adb->num_rows($result1);
   for($i=0;$i<$nrpcdetails;$i++)
    {
       $log->debug('julidimodimo'.$adb->query_result($result1,0,'linktoproduct'));
  $focus_det = CRMEntity::getInstance("Adocdetail");
    $focus_det->id='';
    $focus_det->column_fields['adocdetailname']=$adb->query_result($result1,$i,'description');
    $focus_det->column_fields['adoc_product']=$adb->query_result($result1,$i,'linktoproduct');
    $focus_det->column_fields['adocdetail_project']= $projid;
    $focus_det->column_fields['adoctomaster']= $focus->id;
    $focus_det->column_fields['assigned_user_id']= $current_user->id;
    $focus_det->save("Adocdetail");
    }
   }  else {
       $result1 = $adb->pquery($sql1,array($recordid));
        $focus_det = CRMEntity::getInstance("Adocdetail");
    $focus_det->id='';
    $focus_det->column_fields['adocdetailname']=$adb->query_result($result1,0,'description');
     $focus_det->column_fields['adoc_product']=$adb->query_result($result1,0,'productname');
    $focus_det->column_fields['adoc_quantity']="1";
    $focus_det->column_fields['adocdetail_project']= $projid;
    $focus_det->column_fields['adoctomaster']= $focus->id;
    $focus_det->column_fields['assigned_user_id']= $current_user->id;
    $focus_det->save("Adocdetail");
       
}
 
//    $recordidadoc = $focus->id;
//    
//    $sql1 = "SELECT *
//		FROM vtiger_pcdetails
//			LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid=vtiger_pcdetails.pcdetailsid
//                        LEFT JOIN vtiger_project ON vtiger_project.projectid=vtiger_pcdetails.linktoproject
//                        WHERE deleted=0
//			AND linktoproject=$recordid";
//
//   $result1 = $adb->query($sql1);
//  $nrpcdetails=$adb->num_rows($result1);
//   for($i=0;$i<$nrpcdetails;$i++)
//    {
      
 //   $actionid=11404;
//
//$outputType='Refresh';
//$module=  getSalesEntityType($actionid);
// $methodCalled="executeAction";
//$focus=CRMEntity::getInstance($module);
//$focus->retrieve_entity_info($actionid,$module);
//$response=$focus->$methodCalled($recordidadoc,$outputType);
//echo $response;

    //}



    //echo "DDT creato";



?>
