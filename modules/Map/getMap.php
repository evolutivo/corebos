<?php
require_once('Smarty_setup.php');
include_once('modules/Map/Map.php');
global $mod_strings, $app_strings, $adb, $log;
$mapTemplate = new vtigerCRM_Smarty();
$allModules = array();
$mapid = $_REQUEST["mapid"];
$mapInstance = CRMEntity::getInstance("Map");
$allModules = $mapInstance->initListOfModules();
$delimiters = array("",",",";","_","-");
$getMapQuery = $adb->pquery("Select * from vtiger_map where mapid=?",array($mapid));
$nr_row = $adb->num_rows($getMapQuery);
if($nr_row != 0){
    $viewmode = "edit";
    $origin = $adb->query_result($getMapQuery,0,'origin');
    $originname = $adb->query_result($getMapQuery,0,'originname');
    $target = $adb->query_result($getMapQuery,0,'target');
    $targetname = $adb->query_result($getMapQuery,0,'targetname');
    $field1 = $adb->query_result($getMapQuery,0,'field1');
    $field2 = $adb->query_result($getMapQuery,0,'field2');
    $seldelimiter = $adb->query_result($getMapQuery,0,'delimiter');
    $maptype=$adb->query_result($getMapQuery,0,'maptype');
    $blocks=$adb->query_result($getMapQuery,0,'blocks');
    
    $targetFieldsArr = explode("::",$field2);
    $originFieldsArr = explode(",",$field1);
    
    $nrFields = count($originFieldsArr);

    $mapTemplate->assign("nrFields",$nrFields);
    $mapTemplate->assign("originFieldsArr",$field1);
    $mapTemplate->assign("targetFieldsArr",$field2);
    $mapTemplate->assign("originID",$origin);
    $mapTemplate->assign("targetID",$target);
    $mapTemplate->assign("originName",$originname);
    $mapTemplate->assign("targetName",$targetname);
    $mapTemplate->assign("seldelimiter",$seldelimiter);
    $mapTemplate->assign('maptype',$maptype);
}
else{
    $viewmode="create";
}
$mapTemplate->assign("MOD", $mod_strings);
$mapTemplate->assign("APP", $app_strings);
$mapTemplate->assign("module_list",json_encode($mapInstance->module_list));
$mapTemplate->assign("related_modules",json_encode($mapInstance->related_modules));
$mapTemplate->assign("rel_fields",json_encode($mapInstance->rel_fields));
$mapTemplate->assign("delimiters", $delimiters);
$mapTemplate->assign("mapid", $mapid);
$mapTemplate->assign("mode", $viewmode);
$mapTemplate->assign("module_id",$mapInstance->module_id);

if(stristr($maptype,'Block Access')!='')
{$blockids=explode(',',$blocks);
$mapTemplate->assign('blocks',json_encode($mapInstance->module_list[$originname]));
$mapTemplate->assign('blockid',$mapInstance->module_list[$originname]);
$mapTemplate->assign("targetID",$blockids[0]);
$mapTemplate->display('modules/Map/blockaccess.tpl');   
}
    else
$mapTemplate->display('modules/Map/mapWindow.tpl');
?>


