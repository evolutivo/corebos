<?php

ini_set('display_errors', 'On');
ini_set('error_reporting', 'On');
include_once('data/CRMEntity.php');
include_once('modules/Map/Map.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');

require_once("modules/com_vtiger_workflow/VTWorkflowManager.inc");
require_once("modules/com_vtiger_workflow/VTTaskManager.inc");
require_once("modules/com_vtiger_workflow/VTWorkflowApplication.inc");
require_once ("modules/com_vtiger_workflow/VTEntityMethodManager.inc");
require_once("include/utils/CommonUtils.php");
require_once("include/events/SqlResultIterator.inc");
require_once("modules/com_vtiger_workflow/VTWorkflowUtils.php");

$log = & LoggerManager::getLogger("index");
$current_user = new Users();
$result = $current_user->retrieveCurrentUserInfoFromFile(1);
global $adb, $log, $current_user;
$request = array();

$recordid = $argv[1];
$mapid = $argv[2];
$recid = explode(',', $recordid);
for ($j = 0; $j < count($recid); $j++) {
    $recordid = $recid[$j];
    $focus1 = CRMEntity::getInstance("Map");
    $focus1->retrieve_entity_info($mapid, "Map");

    $origin_module = $focus1->getMapOriginModule();

    $target_module = $focus1->getMapTargetModule();

    $target_fields = $focus1->readMappingType();
    $log->debug($target_fields);
    $pointing_field = $focus1->getMapPointingFieldUpdate();
    $log->debug('alba1 '.$pointing_field);
    include_once ("modules/$target_module/$target_module.php");
    include_once ("modules/$origin_module/$origin_module.php");
   
    $focus2 = CRMEntity::getInstance($origin_module);
    $focus2->retrieve_entity_info($recordid, $origin_module);
    $pointing_value=$focus2->column_fields[$pointing_field];
    $pointing_val=explode(',',$pointing_value);
    $pointing_module=  getSalesEntityType($pointing_val[0]);
    $allparameters = array();
    for($nr_record=0;$nr_record<sizeof($pointing_val);$nr_record++){
        $focus = CRMEntity::getInstance($target_module);
        $target_table=$focus->table_name;
        $target_id=$focus->table_index;
        $focus->retrieve_entity_info($pointing_val[$nr_record], $target_module);
        if(empty($pointing_val[$nr_record])) { continue; }
        $cols = array();
        $vals = array();
        foreach ($target_fields as $key => $value) {
            
            $foundValues = array();
            if (!empty($value['value'])) {
                $focus->column_fields[$key] = $value['value'];
                $cols[]=" $key=? ";
                $vals[]=implode($value['delimiter'], $value['value']);
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
                $focus->column_fields[$key] = implode($value['delimiter'], $foundValues);
                $cols[]=" $key=? ";
                $vals[]=implode($value['delimiter'], $foundValues);
            }
        }
//    if (empty($focus->column_fields['assigned_user_id'])) {
//        $focus->column_fields['assigned_user_id'] = $current_user->id;
//    }
    $focus->mode = 'edit';
    $focus->id = $pointing_val[$nr_record];
//    $focus->save($target_module); 
       
    array_push($vals,$pointing_val[$nr_record]);
    $keys=implode(',',$cols);
    $query=$adb->pquery("Update $target_table"
            . " set $keys"
            . " where $target_id=?",array($vals));
    
    $em = new VTEventsManager($adb);
    // Initialize Event trigger cache
    $em->initTriggerCache();
    $entityData  = VTEntityData::fromCRMEntity($focus);
    $em->triggerEvent("vtiger.entity.beforesave.modifiable", $entityData);
    $em->triggerEvent("vtiger.entity.beforesave", $entityData);
    $em->triggerEvent("vtiger.entity.beforesave.final", $entityData);
    $em->triggerEvent("vtiger.entity.aftersave", $entityData);
        
   }
}
echo json_encode($response, true);
