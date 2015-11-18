<?php
ini_set('display_errors','off');
include_once('data/CRMEntity.php');
include_once('modules/Map/Map.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
global $adb,$log,$current_user;

//$recordid=$_REQUEST['recordid'];
//$mapid=$_REQUEST['map'];
 if(isset($argv) && !empty($argv)){
  $recordid = $argv[1];
  $mapid = $argv[2];
  $causale=$argv[3];
  $outputType=$argv[5];
 }  
$mapfocus=  CRMEntity::getInstance("Map");
$mapfocus->retrieve_entity_info($mapid,"Map");

$module=$mapfocus->getMapOriginModule();
$return_module=$mapfocus->getMapTargetModule();
$target_fields=$mapfocus->getMapTargetFields();

include_once ("modules/$module/$module.php");
include_once ("modules/$return_module/$return_module.php");

$focus=  CRMEntity::getInstance($return_module);
$focus2=CRMEntity::getInstance($module);
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
echo "index.php?module=$return_module&action=EditView&return_module=$module&return_action=DetailView&return_id=$recordid&$fixedparams";
}
?>
