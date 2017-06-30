<?php
include_once('data/CRMEntity.php');
require_once('include/utils/utils.php');
function LightRendicAction($request) {
        require_once('include/database/PearDatabase.php');
	require_once("modules/Users/Users.php");
        require_once 'modules/Map/Map.php';
        require_once 'modules/SalesOrderMaster/SalesOrderMaster.php';
        require_once 'include/utils/LightRendicontaUtils.php';
	
	global $current_user, $log, $adb;
        
	$current_user = new Users();
	$current_user->retrieveCurrentUserInfoFromFile(1);
	
	$recordid = $request['recordid'];
        $userid= $request['userid'];
        $next_sub=$_REQUEST['next_sub'];
        $pfid=$_REQUEST['pfid'];
        
        $userTemp=explode('x',$userid);
        $user=$userTemp[1];
	$response = array();
	$allrecordsID = explode(',', $recordid);
	$currentModule='SalesOrderMaster';
	for ($i = 0; $i < count($allrecordsID); $i++) {
                $id=$allrecordsID[$i];
                $ids1=explode('x',$id);
                $id=$ids1[1];
		$next_sub=$request['next_sub'];//'closed';
                $focus = CRMEntity::getInstance($currentModule);
                $focus->id = $id;
                $focus->mode = 'edit';
                $focus->retrieve_entity_info($id, $currentModule);
                
                $mapRendicontaConfig=rendicontaConfig($currentModule);
                $statusfield=$mapRendicontaConfig['statusfield'];
                $actual_substatus=$focus->column_fields["$statusfield"];                
                $focus->column_fields['assigned_user_id']=$focus->column_fields['assigned_user_id'];
                $focus->save($currentModule);
                
                $processlog=array('actual_substatus'=>$actual_substatus,
                    'next_substatus'=>$next_sub,
                    'current_user'=>$user,
                    'entityname_val'=>'',
                    'recordid'=>$id,
                    'statusfield'=>'cf_1015',
                    'currentModule'=>$currentModule);
                updateStatusFld($processlog);
                //createProcessLog($processlog);
                pickFlow($pfid,$processlog);
        }
        $response['message'] = "OK";
    return $response;
}
?>
