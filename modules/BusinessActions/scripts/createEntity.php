<?php
include_once('data/CRMEntity.php');
include_once('modules/cbMap/cbMap.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');

function createEntity($request) {
global $adb,$log,$current_user;

$current_user = new Users();
$current_user->retrieveCurrentUserInfoFromFile(1);

$recordid = $request['recordid'];
$mapid = $request['mapid'];
$outputType=$request['outputType'];
  
$mapfocus=  CRMEntity::getInstance("cbMap");
$mapfocus->retrieve_entity_info($mapid,"cbMap");

$module=$mapfocus->getMapOriginModule();
$return_module=$mapfocus->getMapTargetModule();
$target_fields=$mapfocus->getMapTargetFields();

include_once ("modules/$module/$module.php");
include_once ("modules/$return_module/$return_module.php");

$focus =  CRMEntity::getInstance($return_module);
$focus2 = CRMEntity::getInstance($module);
$focus2->retrieve_entity_info($recordid,$module);
$table=$focus2->table_name;
$index=$focus2->table_index;
$allparameters=array();
foreach ($target_fields as $field=>$elements){
    $fieldname=$field; 
    foreach ($elements as $key=>$value){
        if($key=='delimiter'){
            $delimiter=(string)$value;
            if (empty($delimiter))
                $delimiter="";
        }
        if($key=='merge'){
            $allfields= implode(",",$value);
          $foundValues=array();
            $selfieds=explode(",",$allfields);
            for($i=0;$i<count($selfieds);$i++){
                $focus2Query=$adb->pquery("SELECT $allfields 
                          FROM $table
                          INNER JOIN vtiger_crmentity ce ON ce.crmid=$table.$index
                          WHERE ce.deleted=0 AND $table.$index=?",array($recordid));
            $selfieds=explode(",",$allfields);
            for($i=0;$i<count($selfieds);$i++){
                //echo $adb->query_result($focus2Query,0,$selfieds[$i]);
                $foundValues[]=$adb->query_result($focus2Query,0,$selfieds[$i]);
            }
            }
            
        }
    }    
        $allparameters[] = $fieldname."=".implode("$delimiter", $foundValues);
}
if($return_module=='Documents'){
    $allparameters[] = "createmode=link";
}
$fixedparams=implode("&",$allparameters);
$allResult=array();
$allResult[]=$recordid;
if($outputType=='sequencer'){
    return implode(":::",$allResult);
}
else{
return "index.php?module=$return_module&action=EditView&return_module=$module&return_action=DetailView&return_id=$recordid&$fixedparams";
}
}
?>
