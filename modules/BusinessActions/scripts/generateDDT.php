<?php

function generateDDT($request) {
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
    $response = array();
    $masterids = array();
    $inoutmasterid = $request['recordid'];
    $map_adocmaster = $request['adocm_mapid'];
    $map_adocdetail = $request['adocd_mapid'];
    if (!empty($map_adocmaster) && !empty($map_adocdetail)) {
        $recid = explode(',', $inoutmasterid);
        for ($j = 0; $j < count($recid); $j++) {
            $inoutmasterid = $recid[$j];
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
                    for ($i = 0; $i < count($value['listFields']); $i++) {
                        $foundValues[] = $focus2->column_fields[$value['listFields'][$i]];
                    }
                    $adocmaster->column_fields[$key] = implode($value['delimiter'], $foundValues);
                }
            }
            if (!isset($adocmaster->column_fields['assigned_user_id']) || empty($adocmaster->column_fields['assigned_user_id'])) {
                $adocmaster->column_fields['assigned_user_id'] = $current_user->id;
            }
            $adocmaster->mode = "";
            $adocmaster->id = "";
            $adocmaster->save("Adocmaster");
            $masterids[] = $adocmaster->id;
            $detailQuery = $adb->pquery("SELECT inoutdetailid FROM vtiger_inoutdetail iodet
             INNER JOIN vtiger_crmentity ce ON ce.crmid=iodet.inoutdetailid
             WHERE ce.deleted=0 AND iodet.linktoinoutmaster=?", array($inoutmasterid));
            for($el=0;$el<$adb->num_rows($detailQuery);$el++){
            $detailid = $adb->query_result($detailQuery, $el, 'inoutdetailid');

            $focus = CRMEntity::getInstance("Map");
            $focus->retrieve_entity_info($map_adocdetail, "Map");
            $detail_origin_module = $focus->getMapOriginModule();
            $detailfields = $focus->readMappingType();
            $focus2 = CRMEntity::getInstance($detail_origin_module);
            $focus2->retrieve_entity_info($detailid, $detail_origin_module);
            $adocdetail = new Adocdetail();
            foreach ($detailfields as $key => $value) {
                $foundValues = array();
                if (!empty($value['value'])) {
                    $adocdetail->column_fields[$key] = $value['value'];
                } else {
                    for ($i = 0; $i < count($value['listFields']); $i++) {
                        $foundValues[] = $focus2->column_fields[$value['listFields'][$i]];
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
        }
        }
	$response['outputid']=implode(",", $masterids);
        $response['recordid'] = implode(",", $masterids);
	$response['confirm']="1";
        return $response;
    }
}
?>
