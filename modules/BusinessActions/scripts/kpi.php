<?php
ini_set('display_errors', 'On');
ini_set('error_reporting', 'On');
include_once('data/CRMEntity.php');
include_once('modules/Map/Map.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');

//get action arguments
$log = & LoggerManager::getLogger("index");
$current_user = new Users();
$result = $current_user->retrieveCurrentUserInfoFromFile(1);
global $adb, $log, $current_user;
$request = array();
if (isset($argv) && !empty($argv)) {
    for ($i = 1; $i < count($argv); $i++) {
        list($key, $value) = explode("=", $argv[$i]);
        $request[$key] = $value;
    }
}


$mapid = $request['mapid']; 
$closedProject = $adb->query("Select * from vtiger_project join vtiger_crmentity
                              on projectid = crmid where deleted = 0 
                              and substatus  not like '%_closed%'and praticaid > 1000
			      and createdtime >= '2015-05-01 00:00:00'");

$nr = $adb->num_rows($closedProject);
for ($j = 0; $j < $nr; $j++){
    $recordid = $adb->query_result($closedProject,$j,'projectid');
    $commessa = $adb->query_result($closedProject,$j,'commessa');
    
    $focus1 = CRMEntity::getInstance("Map");
    $focus1->retrieve_entity_info($mapid, "Map");

    $origin_module = $focus1->getMapOriginModule();

    $target_module = $focus1->getMapTargetModule();

    $target_fields = $focus1->readMappingType();

    include_once ("modules/$target_module/$target_module.php");
    include_once ("modules/$origin_module/$origin_module.php");
    
    // get related milestone 
    $projectMilestone = $adb->pquery("Select * from vtiger_projectmilestone 
                                      join vtiger_crmentity on projectmilestoneid = crmid 
                                      where deleted = 0 and projectid = ?",array($recordid));
    $focus = new $target_module();
    
    $focus2 = CRMEntity::getInstance($origin_module);
    $focus2->retrieve_entity_info($recordid, $origin_module);
    
    $allparameters = array();
    
     foreach ($target_fields as $key => $value) {
        $foundValues = array();
        if (!empty($value['value'])) {
            $focus->column_fields[$key] = $value['value'];
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
        }
    }
    
    if (empty($focus->column_fields['assigned_user_id'])) {
        $focus->column_fields['assigned_user_id'] = $current_user->id;
    }
    if($adb->num_rows($projectMilestone) == 1){
        $dataChGestione = $adb->query_result($projectMilestone,0,'datachiusura'); 
        if($dataChGestione == ""){ 
       //Exist do update
        $milestoneid = $adb->query_result($projectMilestone,0,'projectmilestoneid'); 
        $focus->mode = 'edit';
        $focus->id = $milestoneid;
    }
}
    else{
    $focus->mode = '';
    $focus->id = '';
    }
    if($commessa != "") $focus->column_fields['linktoproctemp'] = $commessa;
    $focus->column_fields['projectid'] = $recordid;
    $focus->save($target_module);
    if (!empty($focus->id))  $successIds[] = $focus->id;
}
$response['outputid'] = implode(",", $successIds);
echo json_encode($response, true);


