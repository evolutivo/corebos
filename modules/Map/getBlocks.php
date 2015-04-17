<?php
global $log,$mod_strings, $app_strings,$adb;
include_once('modules/Map/Map.php');
require_once('data/CRMEntity.php');
require_once('include/utils/utils.php');
require_once('database/DatabaseConnection.php');
require_once('include/database/PearDatabase.php');
require_once('Smarty_setup.php');
require_once('include/utils/utils.php');

$mapInstance = CRMEntity::getInstance("Map");
$modtype = $_REQUEST['modtype'];
$module_list[] = json_decode($_REQUEST['module_list']);
$related_modules = json_decode($_REQUEST['related_modules']);
$rel_fields = json_decode($_REQUEST['rel_fields']);
$moduleid =  $_REQUEST['pmodule'];
//$moduleName = getTabModuleName($moduleid);

$mapInstance->module_list=$module_list;
$blockinfo=$mapInstance->getBlockInfo($moduleid);
echo $mapInstance->getBlockHTML($blockinfo,$moduleid);
