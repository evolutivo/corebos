<?php
include_once('data/CRMEntity.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
require_once("modules/Users/Users.php");
$current_user = new Users();
$current_user->retrieveCurrentUserInfoFromFile(1);

global $root_directory,$adb, $log, $current_user,$date_var;

$module=$_REQUEST['module'];
$record=$_REQUEST['record'];
$models=$_REQUEST['models'];
$mv=json_decode($models);
require_once('modules/'.$module.'/'.$module.'.php');
$focus = CRMEntity::getInstance("$module");
if(empty($record)){
    $focus->id='';
}
else{
    $focus->id=$record;
    $focus->retrieve_entity_info($record,$module);
}
if(empty($focus->column_fields['assigned_user_id']))
{
    $focus->column_fields['assigned_user_id']=1;
}
foreach($mv as $key=>$value)
{
    $focus->column_fields["$key"]=$value; 
    if(strpos($value,'x')!==false){
        $arr=explode('x',$value);
        $focus->column_fields["$key"]=$arr[1]; 
    }
}
$focus->column_fields['filelocationtype']='I';
$focus->column_fields['filestatus']='1';
$focus->save("$module"); 
echo $focus->id;
