<?php

require_once('include/database/PearDatabase.php');
require_once('modules/Adocmaster/Adocmaster.php');
require_once('modules/Adocdetail/Adocdetail.php');
global $adb,$log,$current_user;
include_once('data/CRMEntity.php');
include_once('modules/Map/Map.php');
require_once('include/utils/utils.php');


 $adocid = $argv[1];
  $mapid = $argv[2];
 
  $sql = "SELECT * FROM `vtiger_adocmaster` WHERE `adocmasterid`= $adocid";
    $result = $adb->query($sql);
    $projid= $adb->query_result($result,0,'project');
  
  $focus=  CRMEntity::getInstance("Map");
$focus->retrieve_entity_info($mapid,"Map");
$log->debug('julidimo'.$mapid);
$sql1=$focus->getMapSQL();
 
$result1 = $adb->pquery($sql1,array($projid));
$nrpcdetails=$adb->num_rows($result1);
   for($i=0;$i<$nrpcdetails;$i++)
    {
       $log->debug('julidimodimo'.$adb->query_result($result1,0,'linktoproduct'));
  $focus_det = CRMEntity::getInstance("Adocdetail");
    $focus_det->id='';

    $focus_det->column_fields['adocdetailname']=$adb->query_result($result1,$i,'description');
    $focus_det->column_fields['adoc_product']=$adb->query_result($result1,$i,'linktoproduct');
    
    $focus_det->column_fields['adocdetail_project']= $projid;
    $focus_det->column_fields['adoctomaster']= $adocid;
    $focus_det->column_fields['assigned_user_id']= $current_user->id;
    $focus_det->save("Adocdetail");
    }
?>