<?php
global $log;
include_once('modules/Map/Map.php');
global $mod_strings, $app_strings;
;
$mapInstance = CRMEntity::getInstance("Map");
$modtype = $_REQUEST['modtype'];
$module_list = json_decode($_REQUEST['module_list']);
$related_modules = json_decode($_REQUEST['related_modules']);
$rel_fields = json_decode($_REQUEST['rel_fields']);
$pmodule =  $_REQUEST['pmodule'];

VTCacheUtils::updateMap_ListofModuleInfos($module_list,$related_modules,$rel_fields);

if($modtype == "target"){
   $mapInstance->getPriModuleFieldsList($pmodule,$modtype);
   echo $mapInstance->getPrimaryFieldHTML($pmodule,$modtype);   
}
else 
{
$mapInstance->getPriModuleFieldsList($pmodule,$modtype);
echo $mapInstance->getPrimaryFieldHTML($pmodule,$modtype);
}
?>