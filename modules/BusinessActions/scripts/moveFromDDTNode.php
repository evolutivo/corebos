<?php

function moveFromDDTNode($request) {
    
include_once('data/CRMEntity.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
require_once('modules/Adocmaster/Adocmaster.php');
require_once('modules/Adocdetail/Adocdetail.php');
require_once('modules/StockSettings/StockSettings.php');
require_once('modules/Stock/Stock.php');
require_once('modules/Map/Map.php');
global $adb, $log, $current_user; 

$recordid = $request['recordid']; //Adocmaster ID
$allrecordsID = explode(',', $recordid);
$settingid = $request['stocksettingsname'];
$allcreatedrecords = array();
$allstockids = array();

$stockQuery = $adb->pquery("SELECT * FROM vtiger_stocksettings
                            INNER JOIN vtiger_crmentity ce ON ce.crmid=vtiger_stocksettings.stocksettingsid
                            WHERE ce.deleted=0 AND stocksettingsid=?", array($settingid));
$settingsid = $adb->query_result($stockQuery, 0, 'stocksettingsid');


$response['recordid']=$settingsid;
return $response;
}
?>
