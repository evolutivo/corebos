<?php
require_once('modules/Task/Task.php');
require_once('modules/Potentials/Potentials.php');
require_once('include/logging.php');
require_once('include/utils/CommonUtils.php');
require_once('include/utils/utils.php');
global $log,$current_user;
$taskid=$_REQUEST['id'];
$task=  CRMEntity::getInstance("task");
$task->retrieve_entity_info($taskid,"Task");

$potential=new Potentials();
$potential->id = '';
$potential->potential_no='';
$potential->column_fields['potentialname']="test";
$str=$task->column_fields['taskstop'];
if($str!='0000-00-00 00:00:00') $taskstop=date('Y-m-d',strtotime($str));
else $taskstop=getNewDisplayDate();
$potential->column_fields['closingdate']=$taskstop;
$potential->column_fields['related_to']=$task->column_fields['linktoentity'];
//$potential->column_fields['assigned_user_id']=1;
$_REQUEST['assigntype'] = 'U';
$potential->column_fields['assigned_user_id'] = $current_user->id;
$potential->save("Potentials");
echo $potential->id;
?>