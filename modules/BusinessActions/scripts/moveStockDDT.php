<?php
include_once('data/CRMEntity.php');
require_once('include/utils/utils.php');
require_once('modules/Adocmaster/Adocmaster.php');
require_once('modules/Adocdetail/Adocdetail.php');
require_once('modules/Inoutmaster/Inoutmaster.php');
require_once('modules/Inoutdetail/Inoutdetail.php');
require_once('modules/StockSettings/StockSettings.php');
require_once('modules/Stock/Stock.php');
require_once('modules/Map/Map.php');
require_once("modules/Users/Users.php");
global $adb, $log, $current_user;

$current_user = new Users();
$current_user->retrieveCurrentUserInfoFromFile(1);

$allrelatedstock = array();
if (!function_exists('getStock')) {

    function getStock($product, $productdetail, $warehouse_id) {
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

$alldata = $adb->query("SELECT * FROM ddt_data 
                        INNER JOIN ddt_pratica ON ddt_pratica.linktodata = ddt_data.id
                        GROUP BY numero");
$nrmaster = $adb->num_rows($alldata);
for ($el = 0; $el < $nrmaster; $el++) {
    //$dataid = $adb->query_result($alldata, $el, "id");
    $dataid = $adb->query_result($alldata, $el, "numero");
    $settingid = $adb->query_result($alldata, $el, "stocksettingsid");
    if(isRecordExists($settingid)){
    /*
     * Read from stocksettings
     */
    $stockQuery = $adb->pquery("SELECT * FROM vtiger_stocksettings
                            INNER JOIN vtiger_crmentity ce ON ce.crmid=vtiger_stocksettings.stocksettingsid
                            WHERE ce.deleted=0 AND stocksettingsid=?", array($settingid));
    $settingsid = $adb->query_result($stockQuery, 0, 'stocksettingsid');
    $settings_focus = CRMEntity::getInstance("StockSettings");
    $settings_focus->retrieve_entity_info($settingsid, "StockSettings");

    $map_inoutmaster = $settings_focus->column_fields['master_map'];
    $map_inoutdetail = $settings_focus->column_fields['detail_map'];
//$map_adocmaster=$settings_focus->column_fields['mapstock'];
//$map_adocdetail=$settings_focus->column_fields['linkdynamicmap'];
    $type = $settings_focus->column_fields['type'];
    $warehouse_to = $settings_focus->column_fields['wh_to'];
    $warehouse_from = $settings_focus->column_fields['wh_from'];
    $location_to = $settings_focus->column_fields['loc_to'];
    $location_from = $settings_focus->column_fields['loc_from'];
    $codserie = $settings_focus->column_fields['codserie'];
    $codcausale = $settings_focus->column_fields['codcausale'];
    $causale = $settings_focus->column_fields['causale'];
    $idazienda = $settings_focus->column_fields['idazienda'];
    $statomerce = $settings_focus->column_fields['statomerce'];
    $codum = $settings_focus->column_fields['codum'];
    $idfornitore = $settings_focus->column_fields['idfornitore'];
    $map_adocmaster = $settings_focus->column_fields['mapstock'];
    $map_adocdetail = $settings_focus->column_fields['linkdynamicmap'];
    $stocktypedoc = $settings_focus->column_fields['stocktypedoc'];
    $cedenteID = $settings_focus->column_fields['cedente'];
    $intestatarioID = $settings_focus->column_fields['intestatario'];
    $cessionarioID = $settings_focus->column_fields['cessionario'];
    $sendddtrequest = $settings_focus->column_fields['sendddtrequest'];

$mapfocus = CRMEntity::getInstance("Map");
$mapfocus->retrieve_entity_info($map_inoutmaster, "Map");
$mapinfo = $mapfocus->readTableMappingType();
$masterfields = $mapinfo['target'];

    $inout_master = new Inoutmaster();
    foreach ($masterfields as $key => $value) {
        $tablefld = $value['value'];
        $inout_master->column_fields[$key] = $adb->query_result($alldata, $el, $tablefld);
    }
    if (!isset($inout_master->column_fields['assigned_user_id']) || empty($inout_master->column_fields['assigned_user_id'])) {
        $inout_master->column_fields['assigned_user_id'] = $current_user->id;
    }
    if (empty($inout_master->column_fields['inoutmastername']))
        $inout_master->column_fields['inoutmastername'] = $causale;
    $inout_master->column_fields['causalecode'] = $codcausale;
    $inout_master->column_fields['companyid'] = $idazienda;
    //$inout_master->column_fields['magazineid'] =
    $inout_master->column_fields['serialcode'] = $codserie;
    $inout_master->column_fields['tipomovimento'] = $type;
    $inout_master->column_fields['codiceum'] = $codum;
    $inout_master->column_fields['statomerce'] = $statomerce;
    $inout_master->column_fields['sendddtrequest'] = $sendddtrequest;
    $inout_master->mode = "";
    $inout_master->id = "";
    $inout_master->save("Inoutmaster");
    $inoutmasterid=$inout_master->id;

    //$alldetails = $adb->pquery("SELECT * FROM ddt_pratica WHERE linktodata=?", array($dataid));
    $alldetails = $adb->pquery("SELECT * FROM ddt_data 
                        INNER JOIN ddt_pratica ON ddt_pratica.linktodata = ddt_data.id
                        WHERE numero='$dataid' ", array());
    $nrdetails = $adb->num_rows($alldetails);
    for ($j = 0; $j < $nrdetails; $j++) {
        $praticaidall = $adb->query_result($alldetails, $j, "praticaid");

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
        if ($j == 0) {
            if (!empty($map_adocmaster)) {
                if (getSalesEntityType($cedenteID) == 'Map') {
                    $mapfocus = CRMEntity::getInstance("Map");
                    $mapfocus->retrieve_entity_info($cedenteID, "Map");
                    $sqlString = $mapfocus->getMapSQL();
                    $allelements = array("CURRENT_USER" => $current_user->id, "CURRENT_RECORD" => $projectid);
                    foreach ($allelements as $elem => $value) {
                        $pos_el = strpos($sqlString, $elem);
                        if ($pos_el) {
                            $sqlString = str_replace($elem, $value, $sqlString);
                        }
                    }
                    $queryExec = $adb->query($sqlString);
                    $cedente = $adb->query_result($queryExec, 0);
                } else {
                    $cedente = $cedenteID;
                }
                $log->debug("cedente" . $cedente);
                if (getSalesEntityType($intestatarioID) == 'Map') {
                    $mapfocus = CRMEntity::getInstance("Map");
                    $mapfocus->retrieve_entity_info($intestatarioID, "Map");
                    $sqlString = $mapfocus->getMapSQL();
                    $allelements = array("CURRENT_USER" => $current_user->id, "CURRENT_RECORD" => $projectid);
                    foreach ($allelements as $elem => $value) {
                        $pos_el = strpos($sqlString, $elem);
                        if ($pos_el) {
                            $sqlString = str_replace($elem, $value, $sqlString);
                        }
                    }

                    $queryExec = $adb->query($sqlString);
                    $intestatario = $adb->query_result($queryExec, 0);
                } else {
                    $intestatario = $intestatarioID;
                }
                $log->debug("intestatario" . $intestatario);
                if (getSalesEntityType($cessionarioID) == 'Map') {
                    $mapfocus = CRMEntity::getInstance("Map");
                    $mapfocus->retrieve_entity_info($cessionarioID, "Map");
                    $sqlString = $mapfocus->getMapSQL();
                    $allelements = array("CURRENT_USER" => $current_user->id, "CURRENT_RECORD" => $projectid);
                    foreach ($allelements as $elem => $value) {
                        $pos_el = strpos($sqlString, $elem);
                        if ($pos_el) {
                            $sqlString = str_replace($elem, $value, $sqlString);
                        }
                    }
                    $queryExec = $adb->query($sqlString);
                    $cessionario = $adb->query_result($queryExec, 0);
                } else {
                    $cessionario = $cessionarioID;
                }
                $log->debug("cessionario" . $cessionario);
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
                $focus = CRMEntity::getInstance("Map");
                $focus->retrieve_entity_info($map_adocmaster, "Map");
                $master_origin_module = $focus->getMapOriginModule();
                $masterfields = $focus->readMappingType();
                $focus2 = CRMEntity::getInstance($master_origin_module);
                $focus2->retrieve_entity_info($inoutmasterid, $master_origin_module);

                $adocmaster = new Adocmaster();
                 foreach ($masterfields as $key => $value) {
                    $foundValues = array();
                    if (!empty($value['value'])) {
                        $adocmaster->column_fields[$key] = $value['value'];
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
                        $adocmaster->column_fields[$key] = implode($value['delimiter'], $foundValues);
                    }
                }
                if (!isset($adocmaster->column_fields['assigned_user_id']) || empty($adocmaster->column_fields['assigned_user_id'])) {
                    $adocmaster->column_fields['assigned_user_id'] = $current_user->id;
                }
                $adocmaster->column_fields['project'] = $projectid;
                $adocmaster->column_fields['cedente'] = $cedente;
                $adocmaster->column_fields['intestatario'] = $intestatario;
                $adocmaster->column_fields['cessionario'] = $cessionario;
                $adocmaster->mode = "";
                $adocmaster->id = "";
                $adocmaster->save("Adocmaster");
    $linktodata=$adb->query_result($alldata,$el,'linktodata');
    $adb->pquery("update ddt_pratica join ddt_data ON ddt_pratica.linktodata = ddt_data.id set executed='1' where (praticaid='$praticaidall' and linktodata='$linktodata') or (numero='$dataid') ",array());
            }
        }
        $focus = CRMEntity::getInstance("Map");
        $focus->retrieve_entity_info($map_inoutdetail, "Map");
        $master_origin_module = $focus->getMapOriginModule();
        $detailfields = $focus->readMappingType();
        $focus2 = CRMEntity::getInstance($master_origin_module);
        $focus2->retrieve_entity_info($projectid, $master_origin_module);
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
            $inout_det->column_fields['idfornitore'] = $idfornitore;
            $projectfields = $adb->pquery("SELECT codiceartfornit, codicearticolo FROM vtiger_project WHERE projectid=?", array($projectid));
            $codiceartfornit = $adb->query_result($projectfields, 0, 'codiceartfornit');
            $codicearticolo = $adb->query_result($projectfields, 0, 'codicearticolo');
            if (empty($inout_det->column_fields['skufornit']))
                $inout_det->column_fields['skufornit'] = $codicearticolo;
            $stockid = getStock($inout_det->column_fields['linktoproduct'], $inout_det->column_fields['linktopdtdet'], $warehouse_id);
            $inout_det->column_fields['linktostock'] = $stockid;
            if (!isset($inout_det->column_fields['assigned_user_id']) || empty($inout_det->column_fields['assigned_user_id'])) {
                $inout_det->column_fields['assigned_user_id'] = $current_user->id;
            }
            $inout_det->mode = "";
            $inout_det->id = "";
            $inout_det->save("Inoutdetail");
            $detailid=$inout_det->id;
            $allrelatedstock[] = $stockid;
                if (!empty($map_adocdetail)) {
                $focus_map = CRMEntity::getInstance("Map");
                $focus_map->retrieve_entity_info($map_adocdetail, "Map");
                $detail_origin_module = $focus_map->getMapOriginModule();
                $detailfields_adoc = $focus_map->readMappingType();
                $focus2_obj = CRMEntity::getInstance($detail_origin_module);
                $focus2_obj->retrieve_entity_info($detailid, $detail_origin_module);
                $adocdetail = new Adocdetail();
                  foreach ($detailfields_adoc as $key => $value) {
                    $foundValues = array();
                    if (!empty($value['value'])) {
                        $adocdetail->column_fields[$key] = $value['value'];
                    } else {
                        if (isset($value['listFields']) && !empty($value['listFields'])) {
                            for ($i = 0; $i < count($value['listFields']); $i++) {
                                $foundValues[] = $focus2_obj->column_fields[$value['listFields'][$i]];
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
                        $adocdetail->column_fields[$key] = implode($value['delimiter'], $foundValues);
                    }
                }
                $adocdetail->column_fields['adoctomaster'] = $adocmaster->id;
                if (!isset($adocdetail->column_fields['assigned_user_id']) || empty($adocdetail->column_fields['assigned_user_id'])) {
                    $adocdetail->column_fields['assigned_user_id'] = $current_user->id;
                }
                $adocdetail->mode = "";
                $adocdetail->id = "";
                $adocdetail->save("Adocdetail");
                $adocdetailid=$adocdetail->id;
            }
        }
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
            $inout_det->column_fields['movimento'] = "IN";
//    $inout_det->column_fields['linktoproduct'] = 1;
            $inout_det->column_fields['locator_to'] = $location_toid;
            $qty_toupdate+=1;
            $warehouse_id = $warehouse_fromid;
            $inout_det->column_fields['project'] = $projectid;
            $inout_det->column_fields['goodstat'] = $statomerce;
            $inout_det->column_fields['desccausale'] = $causale;
            $inout_det->column_fields['codcausale'] = $codcausale;
            $inout_det->column_fields['linktoinoutmaster'] = $inout_master->id;
            $inout_det->column_fields['idfornitore'] = $idfornitore;
            $projectfields = $adb->pquery("SELECT codiceartfornit, codicearticolo FROM vtiger_project WHERE projectid=?", array($projectid));
            $codiceartfornit = $adb->query_result($projectfields, 0, 'codiceartfornit');
            $codicearticolo = $adb->query_result($projectfields, 0, 'codicearticolo');
            if (empty($inout_det->column_fields['skufornit']))
                $inout_det->column_fields['skufornit'] = $codicearticolo;
            $stockid = getStock($inout_det->column_fields['linktoproduct'], $inout_det->column_fields['linktopdtdet'], $warehouse_id);

            $inout_det->column_fields['linktostock'] = $stockid;
            if (!isset($inout_det->column_fields['assigned_user_id']) || empty($inout_det->column_fields['assigned_user_id'])) {
                $inout_det->column_fields['assigned_user_id'] = $current_user->id;
            }
            $inout_det->mode = "";
            $inout_det->id = "";
            $inout_det->save("Inoutdetail");
            $allrelatedstock[] = $stockid;
            $detailid=$inout_det->id;
            if($type == "IN/OUT"){
                $adocdetailfocus=  CRMEntity::getInstance("Adocdetail");
                $adocdetailfocus->retrieve_entity_info($adocdetailid,"Adocdetail");
                $adocdetailfocus->column_fields['qtyin']=1;
                $adocdetailfocus->column_fields['warehousefrom']=$warehouse_fromid;
                $adocdetailfocus->mode = "edit";
                $adocdetailfocus->id = $adocdetailid;
                $adocdetailfocus->save("Adocdetail");
                
            }
        }

    }

}
}
echo implode(",", $allrelatedstock);
?>
