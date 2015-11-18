<?php

/***************************
  Sample using a PHP array
****************************/
require_once('include/database/PearDatabase.php');
require_once('modules/Adocmaster/Adocmaster.php');
require_once('modules/Adocdetail/Adocdetail.php');
global $adb,$log,$current_user;
include_once('data/CRMEntity.php');
include_once('modules/Map/Map.php');
require_once('include/utils/utils.php');
require('include/fpdm/fpdm.php');
$adocid=$_REQUEST['recordid'];
$mapid = $_REQUEST['map'];
 $sql = "SELECT * FROM `vtiger_adocmaster` WHERE `adocmasterid`= $adocid";
    $result = $adb->query($sql);
    $projid= $adb->query_result($result,0,'project');
  
  $focus=  CRMEntity::getInstance("Map");
$focus->retrieve_entity_info($mapid,"Map");
$log->debug('julidimo'.$mapid);
$sql1=$focus->getMapSQL();
 
$result1 = $adb->pquery($sql1,array($projid));


$fields = array(
    'Accessori1'  => $adb->query_result($result1,0,2),
//    'noprogetto1'  => $adb->query_result($result,0,'project_id'),
//    'serialmodel1'  => $adb->query_result($result,0,'seriale'),
//    'Accessori2'  => $adb->query_result($result,0,'accessorieslist'),
//    'noprogetto2'  => $adb->query_result($result,0,'project_id'),
//    'serialmodel2'  => $adb->query_result($result,0,'seriale'),
//    'incidentid1'  => $adb->query_result($result,0,'rma'),
//    'incidentid2'  =>  $adb->query_result($result,0,'rma'),
//    'incidentid3'  =>  $adb->query_result($result,0,'rma'),
//    'incidentid4'  =>  $adb->query_result($result,0,'rma'),
//    'noprogetto3'  => $adb->query_result($result,0,'project_id'),
//    'incidentid5'  =>  $adb->query_result($result,0,'rma'),
//    'serialmodel3'  => $adb->query_result($result,0,'seriale'),
//    'noprogetto4'  => $adb->query_result($result,0,'project_id'),
//    'incidentid6'  =>  $adb->query_result($result,0,'rma'),
//   'serialmodel4'  => $adb->query_result($result,0,'seriale'),
//   'modello'  => $adb->query_result($result,0,'product_description'),
//    'noprogetto5'  => $adb->query_result($result,0,'project_id'),
//    'incidentid7'  =>  $adb->query_result($result,0,'rma'),
//    'serialmodel5'  => $adb->query_result($result,0,'seriale'),
//    
//    'aziendacliente'  => $adb->query_result($result,0,'accountname'),
//    'indirizzoaziendacliente'  => $bill_street,
//    'citta'  => $cityname,
//    'cap'  => $adb->query_result($result,0,'bill_code'),
//    'provincia'  => $countyname,
    
   
    
    
);

$pdf = new FPDM('include/fpdm/opt_6.pdf');
$pdf->Load($fields, true); // second parameter: false if field values are in ISO-8859-1, true if UTF-8
$pdf->Merge();
$pdf->Output();
?>
