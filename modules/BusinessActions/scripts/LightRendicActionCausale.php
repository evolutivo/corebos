<?php
include_once('data/CRMEntity.php');
require_once('include/utils/utils.php');
function LightRendicActionCausale($request) {
        require_once('include/database/PearDatabase.php');
	require_once("modules/Users/Users.php");
        require_once 'modules/Map/Map.php';
        require_once 'modules/SalesOrderMaster/SalesOrderMaster.php';
        require_once 'include/utils/LightRendicontaUtils.php';
	
	global $current_user, $log, $adb;
        
        $uid=$_SESSION["authenticated_user_id"];
	$current_user = new Users();
	$current_user->retrieveCurrentUserInfoFromFile($uid);
	
	$recordid = $request['recordid'];
                
        $userTemp=explode('x',$userid);
        $user=$userTemp[1];
	$response = array();
	$allrecordsID = explode(',', $recordid);
	$currentModule= getSalesEntityType($recordid);
        $mapRendicontaConfig=rendicontaConfig($currentModule);
        $statusfield=$mapRendicontaConfig['statusfield'];
        $processtemp=$mapRendicontaConfig['processtemp'];
        $causalefield=$mapRendicontaConfig['causalefield'];
        
	for ($i = 0; $i < count($allrecordsID); $i++) {
                $id=$allrecordsID[$i];
                $focus = CRMEntity::getInstance($currentModule);
                $focus->id = $id;
                $focus->mode = 'edit';
                $focus->retrieve_entity_info($id, $currentModule);
                
                $actual_substatus=$focus->column_fields["$statusfield"];   
                $processtemp_val=$focus->column_fields["$processtemp"];
                $causalefield_val=$focus->column_fields["$causalefield"];               
                $processlog=array(
                    'actual_substatus'=>$actual_substatus,
                    'actual_processtemp'=>$processtemp_val,
                    'actual_causale'=>$causalefield_val,
                    'current_user'=>$uid,
                    'entityname_val'=>'',
                    'recordid'=>$id,
                    'statusfield'=>$statusfield,
                    'processtemp'=>$processtemp,
                    'causalefield'=>$causalefield,
                    'currentModule'=>$currentModule);
                pickFlowCausale($processlog);
        }
        $response['message'] = "OK";
    return $response;
}
?>
