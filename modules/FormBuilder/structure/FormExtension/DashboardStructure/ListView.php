<?php
global $app_strings, $mod_strings, $current_language, $currentModule, $theme;
require_once('data/CRMEntity.php');
require_once('include/utils/CommonUtils.php');
require_once('include/ListView/ListView.php');
require_once('include/utils/utils.php');
require_once('modules/CustomView/CustomView.php');

require_once('Smarty_setup.php');
require_once("modules/$currentModule/$currentModule.php");
global $adb,$log,$current_user;
ini_set('max_execution_time',100);
//ini_set('display_errors','On');
$smarty = new vtigerCRM_Smarty;

$record=$_REQUEST['record'];
$masterModule=$_REQUEST['masterModule'];
$onOpenView=$_REQUEST['onOpenView'];

$focus=new $currentModule("$currentModule");
$focus->record=$record;
$focus->masterModule=$masterModule;
if(empty($onOpenView) && empty($record)){
    $onOpenView='create';
}
else{
    $onOpenView='detail';
}
$entities=$focus->getEntities($onOpenView);
//var_dump($entities);
$type=$focus->type;
$label=$focus->label;
$table=$focus->table;



$smarty->assign('MODULE', $currentModule);
$smarty->assign('MODULE_LABEL', $label);
$smarty->assign('ENTITIES',json_encode($entities));
coreBOS_Session::set('getEntities',json_encode($entities));
$smarty->assign('record', $record);
$smarty->assign('masterModule', $masterModule);
$smarty->assign('onOpenView', $onOpenView);
$smarty->assign('RoleId', $current_user->roleid);
$smarty->assign('Profiles', getUserProfile($current_user->id));
foreach($_REQUEST as $key=>$value){
    $fixedparams[]=$key."=".$value;
}
$fixedparams=implode("&",$fixedparams);
$smarty->assign('OutsideData', $fixedparams);
$smarty->assign('LoggedUser', $current_user->id);
$smarty->assign('LoggedUserName', $current_user->user_name);

$smarty->assign('TYPE', $type);
$smarty->assign('THEME', $theme);
$smarty->assign('IMAGE_PATH', $image_path);
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$dat_fmt = $current_user->date_format;
$smarty->assign("dateStr", $dat_fmt);
$smarty->assign("dateFormat", (($dat_fmt == 'dd-mm-yyyy')?'dd-mm-yy':(($dat_fmt == 'mm-dd-yyyy')?'mm-dd-yy':(($dat_fmt == 'yyyy-mm-dd')?'yy-mm-dd':''))));
$smarty->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);

$smarty->display("modules/$currentModule/index.tpl");

?>
