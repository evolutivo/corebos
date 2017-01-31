<?php  
require_once('include/utils/utils.php');
include_once('vtlib/Vtiger/Utils.php');

global $adb,$db_use,$log; 
$content=array();
$kaction=$_REQUEST['kaction'];

if($kaction=='savePF'){
    include_once('modules/ProcessFlow/ProcessFlow.php');
    
    $processTemp=$_REQUEST['processTemp'];
    $start_status=$_REQUEST['start_status'];
    $end_status=$_REQUEST['end_status'];
    $focus = CRMEntity::getInstance("ProcessFlow");
    $focus->id='';
    $focus->column_fields['assigned_user_id']=1;
    $focus->column_fields['starttasksubstatus']=$start_status;
    $focus->column_fields['end_subst']=$end_status;
    $focus->column_fields['starttaskname']=$start_status;
    $focus->column_fields['endtaskname']=$end_status;
    $focus->column_fields['linktoprocesstemplate']=$processTemp;
    $focus->column_fields['processflowname']=$start_status.' -> '.$end_status;
    $focus->save("ProcessFlow"); 
}
?> 
