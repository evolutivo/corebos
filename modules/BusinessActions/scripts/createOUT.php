<?php
ini_set('display_errors','on');
include_once('data/CRMEntity.php');
include_once('modules/Map/Map.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
require_once('include/Inoutdetail/Inoutdetail.php');
global $adb,$log,$current_user;

$causale=$_REQUEST['causale'];
$stockparameters=explode("|",$_REQUEST['stockmarameters']);
$type=$stockparameters[0];
$stockQuery=$adb->pquery("SELECT * FROM vtiger_stocksettings
                          WHERE stocksettingsname=?",array($causale));
$settingsid=$adb->query_result($stockQuery,0,'stocksettingsid');
$warehouse_to=$adb->query_result($stockQuery,0,'wh_to');
$warehouse_from=$adb->query_result($stockQuery,0,'wh_from');
$location_to=$adb->query_result($stockQuery,0,'loc_to');
$location_from=$adb->query_result($stockQuery,0,'loc_from');
if (getSalesEntityType($warehouse_to) == 'Map') {
    $mapfocus = CRMEntity::getInstance("Map");
    $mapfocus->retrieve_entity_info($warehouse_to, "Map");
    $sqlString = $mapfocus->getMapSQL();
    $queryExec = $adb->query($sqlString);
    $warehouse_toid = $adb->query_result($queryExec, 0);
} else {
    $warehouse_toid = $warehouse_to;
}
if (getSalesEntityType($warehouse_from) == 'Map') {
    $mapfocus = CRMEntity::getInstance("Map");
    $mapfocus->retrieve_entity_info($warehouse_from, "Map");
    $sqlString = $mapfocus->getMapSQL();
    $queryExec = $adb->query($sqlString);
    $warehouse_fromid = $adb->query_result($queryExec, 0);
} else {
    $warehouse_fromid = $warehouse_from;
}
if (getSalesEntityType($location_to) == 'Map') {
    $mapfocus = CRMEntity::getInstance("Map");
    $mapfocus->retrieve_entity_info($location_to, "Map");
    $sqlString = $mapfocus->getMapSQL();
    $queryExec = $adb->query($sqlString);
    $location_toid = $adb->query_result($queryExec, 0);
} else {
    $location_toid = $location_to;
}
if (getSalesEntityType($location_from) == 'Map') {
    $mapfocus = CRMEntity::getInstance("Map");
    $mapfocus->retrieve_entity_info($location_from, "Map");
    $sqlString = $mapfocus->getMapSQL();
    $queryExec = $adb->query($sqlString);
    $location_fromid = $adb->query_result($queryExec, 0);
} else {
    $location_fromid = $location_from;
}
if($type=="OUT"){
$inout_det= new Inoutdetail();
$inout_det->column_fields['inoutdetailname'] =$causale;
$inout_det->column_fields['codcausale'] = 1;
$inout_det->column_fields['warehouseto'] =$warehouse_toid;
$inout_det->column_fields['locationto']=$location_toid;
$inout_det->column_fields['quantityout']=1;
}
?>
