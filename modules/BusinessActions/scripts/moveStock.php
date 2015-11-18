<?php

ini_set('display_errors', 'on');
include_once('data/CRMEntity.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
require_once('modules/Inoutmaster/Inoutmaster.php');
require_once('modules/Inoutdetail/Inoutdetail.php');
require_once('modules/StockSettings/StockSettings.php');
require_once('modules/Stock/Stock.php');
require_once('modules/Map/Map.php');
require_once("modules/Users/Users.php");
global $adb, $log, $current_user;
$current_user = new Users();
$current_user->retrieveCurrentUserInfoFromFile(1);
/* Read practicaid and stocksettingsid from input
 * @praticaidall in format @praticaid - @authnum
 */
if (isset($argv) && !empty($argv)) {
    $praticaidall = $argv[1];
    $settingid = $argv[6];
}
$praticaidall=str_replace('"', '', $praticaidall);
$settingid=str_replace('"', '', $settingid);
if(!function_exists('getStock')){
function getStock($product,$productdetail,$warehouse_id){
    global $log,$adb, $current_user;
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
    $stock->column_fields['linktowarehouse']=$warehouse_id;
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
$type = $settings_focus->column_fields['type'];
$warehouse_to = $settings_focus->column_fields['wh_to'];
$warehouse_from = $settings_focus->column_fields['wh_from'];
$location_to = $settings_focus->column_fields['loc_to'];
$location_from = $settings_focus->column_fields['loc_from'];
$codserie = $settings_focus->column_fields['codserie'];
$codcausale = $settings_focus->column_fields['codcausale'];
$causale= $settings_focus->column_fields['causale'];
$idazienda = $settings_focus->column_fields['idazienda'];
$statomerce = $settings_focus->column_fields['statomerce'];
$codum=$settings_focus->column_fields['codnum'];
$idfornitore=$settings_focus->column_fields['idfornitore'];
$sendddtrequest = $settings_focus->column_fields['sendddtrequest'];


$queryString = "SELECT praticaid,projectid FROM vtiger_project
                INNER JOIN vtiger_crmentity ce ON ce.crmid=vtiger_project.projectid
                WHERE ce.deleted=0 AND praticaid=? ";
$params = array();
if (stristr($praticaidall, '-') != '') {
    $praticaidall = explode("-", $praticaidall);
    $praticaid = $praticaidall[0];
    $progresivenum = $praticaidall[1];
    $queryString.=" AND progressiveauth=? ";
    array_push($params, $praticaid);
    array_push($params, $progresivenum);
} else {
    $praticaid = $praticaidall;
    array_push($params, $praticaid);
}
$practicaQuery = $adb->pquery($queryString, $params);
$praticaid = $adb->query_result($practicaQuery, 0, 'praticaid');
$projectid = $adb->query_result($practicaQuery, 0, 'projectid');
/* read map for inoutmaster and fill all fields */
$focus = CRMEntity::getInstance("Map");
$focus->retrieve_entity_info($map_inoutmaster, "Map");
$master_origin_module = $focus->getMapOriginModule();
$masterfields = $focus->readMappingType();
$focus2 = CRMEntity::getInstance($master_origin_module);
$focus2->retrieve_entity_info($projectid, $master_origin_module);
$inout_master = new Inoutmaster();
   foreach ($masterfields as $key => $value) {
        $foundValues = array();
        if (!empty($value['value'])) {
            $inout_master->column_fields[$key] = $value['value'];
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
            $inout_master->column_fields[$key] = implode($value['delimiter'], $foundValues);
        }
    }
if (!isset($inout_master->column_fields['assigned_user_id']) || empty($inout_master->column_fields['assigned_user_id'])) {
    $inout_master->column_fields['assigned_user_id'] = $current_user->id;
}
if(empty($inout_master->column_fields['inoutmastername']))
$inout_master->column_fields['inoutmastername']=$causale;
$inout_master->column_fields['causalecode'] = $codcausale;
$inout_master->column_fields['companyid'] = $idazienda;
//$inout_master->column_fields['magazineid'] =
$inout_master->column_fields['serialcode'] = $codserie;
$inout_master->column_fields['tipomovimento'] =$type;
$inout_master->column_fields['codiceum']=$codum;
$inout_master->column_fields['statomerce']=$statomerce;
$inout_master->column_fields['sendddtrequest'] = $sendddtrequest;
$inout_master->mode = "";
$inout_master->id = "";
$inout_master->save("Inoutmaster");
$focus = CRMEntity::getInstance("Map");
$focus->retrieve_entity_info($map_inoutdetail, "Map");
//$origin_module = $focus->getMapOriginModule(); InoutMaster and Details fields are taken from Project
$detailfields = $focus->readMappingType();


if (getSalesEntityType($warehouse_to) == 'Map') {
    $mapfocus = CRMEntity::getInstance("Map");
    $mapfocus->retrieve_entity_info($warehouse_to, "Map");
    $sqlString = $mapfocus->getMapSQL();
    $queryExec = $adb->query($sqlString);
    $allelements = array("CURRENT_USER" => $current_user->id, "CURRENT_RECORD" =>  $projectid);
    foreach ($allelements as $elem => $value) {
        $pos_el = strpos($sqlString, $elem);
        if ($pos_el) {
            $sqlString = str_replace($elem, $value, $sqlString);
        }
    }
    $warehouse_toid = $adb->query_result($queryExec, 0);
} else {
    $warehouse_toid = $warehouse_to;
}
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
if (getSalesEntityType($location_from) == 'Map') {
    $mapfocus = CRMEntity::getInstance("Map");
    $mapfocus->retrieve_entity_info($location_from, "Map");
    $sqlString = $mapfocus->getMapSQL();
    $allelements = array("CURRENT_USER" => $current_user->id, "CURRENT_RECORD" =>  $projectid);
    foreach ($allelements as $elem => $value) {
        $pos_el = strpos($sqlString, $elem);
        if ($pos_el) {
            $sqlString = str_replace($elem, $value, $sqlString);
        }
    }
    $queryExec = $adb->query($sqlString);
    $location_fromid = $adb->query_result($queryExec, 0);
} else {
    $location_fromid = $location_from;
}
/*$inout_det = new Inoutdetail();
foreach ($detailfields as $key => $value) {

    $foundValues = array();
    if (!empty($value['value'])) {
        $inout_det->column_fields[$key] = $value['value'];
    } else {
        for ($i = 0; $i < count($value['listFields']); $i++) {
            $focus2->column_fields[$value['listFields'][$i]];
            $foundValues[] = $focus2->column_fields[$value['listFields'][$i]];
        }
        $inout_det->column_fields[$key] = implode($value['delimiter'], $foundValues);
    }
}*/
$qty_toupdate = 0;
if ($type == "IN" || $type == "IN/OUT") {
    $inout_det = new Inoutdetail();
   foreach ($detailfields as $key => $value) {
        $foundValues = array();
        if (!empty($value['value'])) {
            $inout_det->column_fields[$key] = $value['value'];
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
            $inout_det->column_fields[$key] = implode($value['delimiter'], $foundValues);
        }
    }

    $inout_det->column_fields['inoutdetailname'] = $causale;
    $inout_det->column_fields['codcausale'] = 1;
    $inout_det->column_fields['warehousefrom'] = $warehouse_fromid;
    $inout_det->column_fields['locationfrom'] = $location_fromid;
    $inout_det->column_fields['quantityin'] = 1;
    $inout_det->column_fields['movimento']="IN";
//    $inout_det->column_fields['linktoproduct'] = 1;
    $inout_det->column_fields['locator_to'] = $location_toid;
    $qty_toupdate+=1;
    $warehouse_id = $warehouse_fromid;
    $inout_det->column_fields['project'] = $projectid;
    $inout_det->column_fields['goodstat'] = $statomerce;
    $inout_det->column_fields['desccausale'] = $causale;
    $inout_det->column_fields['codcausale'] = $codcausale;
    $inout_det->column_fields['linktoinoutmaster'] = $inout_master->id;
    $inout_det->column_fields['idfornitore']=$idfornitore;
    $projectfields=$adb->pquery("SELECT codiceartfornit, codicearticolo FROM vtiger_project WHERE projectid=?",array($projectid));
    $codiceartfornit=$adb->query_result($projectfields,0,'codiceartfornit');
    $codicearticolo=$adb->query_result($projectfields,0,'codicearticolo');
    if(empty($inout_det->column_fields['skufornit']))
       $inout_det->column_fields['skufornit']=$codicearticolo;
    $stockid = getStock($inout_det->column_fields['linktoproduct'], $inout_det->column_fields['linktopdtdet'], $warehouse_id);

    $inout_det->column_fields['linktostock'] = $stockid;
    if (!isset($inout_det->column_fields['assigned_user_id']) || empty($inout_det->column_fields['assigned_user_id'])) {
        $inout_det->column_fields['assigned_user_id'] = $current_user->id;
    }
    $inout_det->mode = "";
    $inout_det->id = "";
    $inout_det->save("Inoutdetail");
} 
if ($type == "OUT" || $type == "IN/OUT") {
     $inout_det = new Inoutdetail();
    foreach ($detailfields as $key => $value) {
        $foundValues = array();
        if (!empty($value['value'])) {
            $inout_det->column_fields[$key] = $value['value'];
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
            $inout_det->column_fields[$key] = implode($value['delimiter'], $foundValues);
        }
    }
    $inout_det->column_fields['inoutdetailname'] = $causale;
    $inout_det->column_fields['codcausale'] = 1;
    $inout_det->column_fields['warehouseto'] = $warehouse_toid;
    $inout_det->column_fields['locationto'] = $location_toid;
    $inout_det->column_fields['quantityout'] = 1;
    $inout_det->column_fields['movimento'] = "OUT";
    $qty_toupdate-=1;
    $warehouse_id = $warehouse_toid;
    $inout_det->column_fields['project'] = $projectid;
    $inout_det->column_fields['goodstat'] = $statomerce;
    $inout_det->column_fields['desccausale'] = $causale;
    $inout_det->column_fields['codcausale'] = $codcausale;
    $inout_det->column_fields['linktoinoutmaster'] = $inout_master->id;
    $inout_det->column_fields['idfornitore']=$idfornitore;
    $stockid = getStock($inout_det->column_fields['linktoproduct'],$inout_det->column_fields['linktopdtdet'],$warehouse_id);
    $inout_det->column_fields['linktostock'] = $stockid;
    if (!isset($inout_det->column_fields['assigned_user_id']) || empty($inout_det->column_fields['assigned_user_id'])) {
        $inout_det->column_fields['assigned_user_id'] = $current_user->id;
    }
    $inout_det->mode = "";
    $inout_det->id = "";
    $inout_det->save("Inoutdetail");
}



echo $stockid;
?>
