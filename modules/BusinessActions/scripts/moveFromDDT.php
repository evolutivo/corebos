<?php
function moveFromDDT($request){
ini_set('display_errors', 'on');
include_once('data/CRMEntity.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
require_once('modules/Adocmaster/Adocmaster.php');
require_once('modules/Adocdetail/Adocdetail.php');
require_once('modules/StockSettings/StockSettings.php');
require_once('modules/Stock/Stock.php');
require_once('modules/Map/Map.php');
require_once("modules/Users/Users.php");
global $adb, $log, $current_user;
$current_user = new Users();
$current_user->retrieveCurrentUserInfoFromFile(1);
/*
if (isset($argv) && !empty($argv)) {
    for ($i = 1; $i < count($argv); $i++) {
        list($key, $value) = explode("=", $argv[$i]);
        $request[$key] = $value;
    }
}*/
$recordid = $request['recordid']; //Adocmaster ID
$allrecordsID = explode(',', $recordid);
$settingid = $request['stocksettingsid'];
$allcreatedrecords = array();
$allstockids = array();
$log->debug('edlira '.$settingid);
$log->Debug($allrecordsID);
if (!function_exists('getStock')) {

    function getStock($product, $productdetail, $warehouse_id, $qty_toupdate) {
        global $log, $adb, $current_user;
        require_once('modules/Stock/Stock.php');
        $stockQuery = $adb->pquery("SELECT * FROM vtiger_stock st
                            INNER JOIN vtiger_crmentity ce ON ce.crmid=st.stockid
                            WHERE ce.deleted=0 AND linktoproduct=? AND linktowarehouse=? AND linktopdtdetail=?", array($product, $warehouse_id, $productdetail));
        $nr_stock = $adb->num_rows($stockQuery);
        if ($nr_stock > 0) {
            $stockid = $adb->query_result($stockQuery, 0, 'stockid');
            $stock = CRMEntity::getInstance("Stock");
            $stock->retrieve_entity_info($stockid, "Stock");
            $stock->column_fields['quantityinstock']+=$qty_toupdate;
            $stock->column_fields['assigned_user_id'] = $current_user->id;
            $stock->mode = "edit";
            $stock->id = $stockid;
            $stock->save("Stock");
        } else {
            $stock = new Stock();
            $stock->column_fields['stockname'] = "causale";
            $stock->column_fields['linktoproduct'] = $product;
            //$stock->column_fields['linktolocation'] = $location_toid;
            $stock->column_fields['linktowarehouse'] = $warehouse_id;
            $stock->column_fields['linktopdtdetail'] = $productdetail;
            $stock->column_fields['quantityinstock'] = 1;
            $stock->mode = "";
            $stock->id = "";
            $stock->column_fields['assigned_user_id'] = $current_user->id;
            $stock->save("Stock");
            $stockid = $stock->id;
        }
        return $stockid;
    }

}
$stockQuery = $adb->pquery("SELECT * FROM vtiger_stocksettings
                            INNER JOIN vtiger_crmentity ce ON ce.crmid=vtiger_stocksettings.stocksettingsid
                            WHERE ce.deleted=0 AND stocksettingsid=?", array($settingid));
$settingsid = $adb->query_result($stockQuery, 0, 'stocksettingsid');
$settings_focus = CRMEntity::getInstance("StockSettings");
$settings_focus->retrieve_entity_info($settingsid, "StockSettings");
$map_inoutmaster = $settings_focus->column_fields['master_map'];
$map_inoutdetail = $settings_focus->column_fields['detail_map'];
$warehouse_from = $settings_focus->column_fields['wh_from'];
$location_from = $settings_focus->column_fields['loc_from'];
$warehouse_to = $settings_focus->column_fields['wh_to'];
$location_to = $settings_focus->column_fields['loc_to'];
$recid = explode(',', $recordid);
for ($j = 0; $j < count($recid); $j++) {
    $recordid = $recid[$j];
    $focus1 = CRMEntity::getInstance("Map");
    $focus1->retrieve_entity_info($map_inoutmaster, "Map");

    $origin_module = $focus1->getMapOriginModule();

    $target_module = $focus1->getMapTargetModule();

    $target_fields = $focus1->readMappingType();

    include_once ("modules/$target_module/$target_module.php");
    include_once ("modules/$origin_module/$origin_module.php");

    $focus_master = new $target_module();
    $focus2 = CRMEntity::getInstance($origin_module);
    $focus2->retrieve_entity_info($recordid, $origin_module);
        foreach ($target_fields as $key => $value) {
        $foundValues = array();
        if (!empty($value['value'])) {
            $focus_master->column_fields[$key] = $value['value'];
        } else {
            if (isset($value['listFields']) && !empty($value['listFields'])) {
                for ($i = 0; $i < count($value['listFields']); $i++) {
                    $foundValues[] = $focus2->column_fields[$value['listFields'][$i]];
                }
            } elseif (isset($value['relatedFields']) && !empty($value['relatedFields'])) {
                for ($i = 0; $i < count($value['relatedFields']); $i++) {
                    $relInformation = $value['relatedFields'][$i];
                    $relModule = $relInformation['relmodule'];
                    $linkField = $relInformation['linkfield'];
                    $fieldName = $relInformation['fieldname'];
                    $otherid = $focus2->column_fields[$linkField];
                    if (!empty($otherid)) {
                        include_once "modules/$relModule/$relModule.php";
                        $otherModule = CRMEntity::getInstance($relModule);
                        $otherModule->retrieve_entity_info($otherid, $relModule);
                        $foundValues[] = $otherModule->column_fields[$fieldName];
                    }
                }
            }
            $focus_master->column_fields[$key] = implode($value['delimiter'], $foundValues);
        }
    }
    if (empty($focus_master->column_fields['assigned_user_id'])) {
        $focus_master->column_fields['assigned_user_id'] = $current_user->id;
    }
    $focus_master->mode = '';
    $focus_master->id = '';
    $focus_master->save($target_module);
    if (!empty($focus_master->id))
        $allcreatedrecords[] = $focus_master->id;
$allprojects=array();    
$adocdetailsQuery=$adb->pquery("SELECT vtiger_adocdetail.adocdetailid,adoc.tipomovimento,adocdetail_project FROM vtiger_adocdetail
                                INNER JOIN vtiger_crmentity ce ON ce.crmid=vtiger_adocdetail.adocdetailid
                                INNER JOIN vtiger_adocmaster adoc ON adoc.adocmasterid=vtiger_adocdetail.adoctomaster
                                WHERE ce.deleted=0 AND vtiger_adocdetail.adoctomaster=?",array($recordid));
while($adocdetailsQuery && $row=$adb->fetch_array($adocdetailsQuery)){
   $adocdetailid=$row['adocdetailid'];
   $tipoMovimento=$row['tipomovimento'];
   $projectid=$row['adocdetail_project'];
   $allprojects[]=$projectid;
   $focus1 = CRMEntity::getInstance("Map");
    $focus1->retrieve_entity_info($map_inoutdetail, "Map");

    $origin_module = $focus1->getMapOriginModule();

    $target_module = $focus1->getMapTargetModule();

    $target_fields = $focus1->readMappingType();

    include_once ("modules/$target_module/$target_module.php");
    include_once ("modules/$origin_module/$origin_module.php");
    
      if (getSalesEntityType($location_to) == 'Map') {
            $mapfocus = CRMEntity::getInstance("Map");
            $mapfocus->retrieve_entity_info($location_to, "Map");
            $sqlString = $mapfocus->getMapSQL();
            $allelements = array("CURRENT_USER" => $current_user->id, "CURRENT_RECORD" =>  $projectid);
            foreach ($allelements as $elem => $value) {
                $pos_el = strpos($sqlString, $elem);
                if ($pos_el) {
                    $sqlString = str_replace($elem, $value, $sqlString);
                }
            }
            $queryExec = $adb->query($sqlString);
            $location_toid = $adb->query_result($queryExec, 0);
        } else {
            $location_toid = $location_to;
        }
        if (getSalesEntityType($warehouse_to) == 'Map') {
            $mapfocus = CRMEntity::getInstance("Map");
            $mapfocus->retrieve_entity_info($warehouse_to, "Map");
            $sqlString = $mapfocus->getMapSQL();
            $allelements = array("CURRENT_USER" => $current_user->id, "CURRENT_RECORD" =>  $projectid);
            foreach ($allelements as $elem => $value) {
                $pos_el = strpos($sqlString, $elem);
                if ($pos_el) {
                    $sqlString = str_replace($elem, $value, $sqlString);
                }
            }
            $queryExec = $adb->query($sqlString);
            $warehouse_toid = $adb->query_result($queryExec, 0);
        } else {
            $warehouse_toid = $warehouse_to;
        }
    $focus_detail = new $target_module();
    $focus2 = CRMEntity::getInstance($origin_module);
    $focus2->retrieve_entity_info($adocdetailid, $origin_module);
    foreach ($target_fields as $key => $value) {
        $foundValues = array();
        if (!empty($value['value'])) {
            $focus_detail->column_fields[$key] = $value['value'];
        } else {
            if (isset($value['listFields']) && !empty($value['listFields'])) {
                for ($i = 0; $i < count($value['listFields']); $i++) {
                    $foundValues[] = $focus2->column_fields[$value['listFields'][$i]];
                }
            } elseif (isset($value['relatedFields']) && !empty($value['relatedFields'])) {
                for ($i = 0; $i < count($value['relatedFields']); $i++) {
                    $relInformation = $value['relatedFields'][$i];
                    $relModule = $relInformation['relmodule'];
                    $linkField = $relInformation['linkfield'];
                    $fieldName = $relInformation['fieldname'];
                    $otherid = $focus2->column_fields[$linkField];
                    if (!empty($otherid)) {
                        include_once "modules/$relModule/$relModule.php";
                        $otherModule = CRMEntity::getInstance($relModule);
                        $otherModule->retrieve_entity_info($otherid, $relModule);
                        $foundValues[] = $otherModule->column_fields[$fieldName];
                    }
                }
            }
            $focus_detail->column_fields[$key] = implode($value['delimiter'], $foundValues);
        }
    }
    $focus_detail->column_fields['warehouseto']=$warehouse_toid;
    $focus_detail->column_fields['locationto']=$location_toid;
    $focus_detail->column_fields['linktoinoutmaster']=$focus_master->id;
    $stockid = getStock($focus_detail->column_fields['linktoproduct'], $focus_detail->column_fields['linktopdtdet'], $focus_detail->column_fields['quantityout']);
    $allstockids[] = $stockid;
    $focus_detail->column_fields['linktostock'] = $stockid;
    if (empty($focus_detail->column_fields['assigned_user_id'])) {
        $focus_detail->column_fields['assigned_user_id'] = $current_user->id;
    }
    $focus_detail->mode = '';
    $focus_detail->id = '';
    $focus_detail->save($target_module);
    if($tipoMovimento=="IN/OUT"){
             if (getSalesEntityType($warehouse_from) == 'Map') {
            $mapfocus = CRMEntity::getInstance("Map");
            $mapfocus->retrieve_entity_info($warehouse_from, "Map");
            $sqlString = $mapfocus->getMapSQL();
            $allelements = array("CURRENT_USER" => $current_user->id, "CURRENT_RECORD" =>  $projectid);
            foreach ($allelements as $elem => $value) {
                $pos_el = strpos($sqlString, $elem);
                if ($pos_el) {
                    $sqlString = str_replace($elem, $value, $sqlString);
                }
            }
            $queryExec = $adb->query($sqlString);
            $warehouse_fromid = $adb->query_result($queryExec, 0);
        } else {
            $warehouse_fromid = $warehouse_from;
        }
         if (getSalesEntityType($location_from) == 'Map') {
            $mapfocus = CRMEntity::getInstance("Map");
            $mapfocus->retrieve_entity_info($location_from, "Map");
            $sqlString = $mapfocus->getMapSQL();
            $queryExec = $adb->query($sqlString);
            $allelements = array("CURRENT_USER" => $current_user->id, "CURRENT_RECORD" =>  $projectid);
            foreach ($allelements as $elem => $value) {
                $pos_el = strpos($sqlString, $elem);
                if ($pos_el) {
                    $sqlString = str_replace($elem, $value, $sqlString);
                }
            }
            $location_fromid = $adb->query_result($queryExec, 0);
        } else {
            $location_fromid = $location_from;
        }
        $focus_detailIN = new $target_module();
        $focus_detailIN->column_fields=$focus_detail->column_fields;
        $focus_detailIN->column_fields['warehouseto']="";
        $focus_detailIN->column_fields['locationto']="";
        $focus_detailIN->column_fields['quantityout']=0;
        $focus_detailIN->column_fields['warehousefrom']=$warehouse_fromid;
        $focus_detailIN->column_fields['locationfrom']=$location_fromid;
        $focus_detailIN->column_fields['quantityin']=$focus2->column_fields['qtyin'];
        $focus_detailIN->column_fields['linktoinoutmaster']=$focus_master->id;
        $stockid = getStock($focus_detailIN->column_fields['linktoproduct'], $focus_detailIN->column_fields['linktopdtdet'],  $focus_detailIN->column_fields['quantityin']);
        $allstockids[] = $stockid;
        $focus_detailIN->column_fields['linktostock'] = $stockid;
    if (empty($focus_detailIN->column_fields['assigned_user_id'])) {
    $focus_detailIN->column_fields['assigned_user_id'] = $current_user->id;
    }
    $focus_detailIN->mode = '';
    $focus_detailIN->id = '';
    $focus_detailIN->save($target_module);
        
    }
}
}
$response['recordid']=implode(",",$allprojects);
$response["outputid"] = implode(",", $allcreatedrecords);
$response["stockid"] = implode(",", $allstockids);
return $response;
}
?>
