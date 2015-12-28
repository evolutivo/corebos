<?php
require_once 'modules/cbMap/cbMap.php';
//$focus->id
global $adb;
$resp_f=array();
$resp_fields=array();
$target_picklist=array();
$conditions=array();
$mapFieldDependecy=array();
$currentModule=$this->get_template_vars('MODULE');
$q_business_rule="Select businessrule,linktomap "
        . " from vtiger_businessrules"
        . " join vtiger_cbmap on vtiger_businessrules.linktomap=vtiger_cbmap.cbmapid"
        . " join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_cbmap.cbmapid"
        . " where module_rules=?"
        . " and maptype ='FieldDependency' and deleted=0";
$res_business_rule=$adb->pquery($q_business_rule,array($currentModule));
    for ($m=0;$m<$adb->num_rows($res_business_rule);$m++)
    {
        $businessrule=$adb->query_result($res_business_rule,$m,'businessrule'); 
        $linktomap=$adb->query_result($res_business_rule,$m,'linktomap');  
        if(empty($linktomap)) continue;
        $mapfocus=  CRMEntity::getInstance("cbMap");
        $mapfocus->retrieve_entity_info($linktomap,"cbMap");
        $mapFieldDependecy[$m]=$mapfocus->getMapFieldDependecy();
        if($m==0){
            $resp_fields=$mapFieldDependecy[$m]['respfield'];
            $target_picklist=$mapFieldDependecy[$m]['target_picklist'];
        }
        else{
            $resp_fields=array_merge($mapFieldDependecy[$m]['respfield'],$resp_fields); 
            $target_picklist=array_merge($mapFieldDependecy[$m]['target_picklist'],$target_picklist); 
        } 
        $resp_fields=array_unique($resp_fields);  
        $target_picklist=array_unique($target_picklist);
    } 
    $this->assign("MAP_RESPONSIBILE_FIELDS",$resp_fields);
    $r2='';$r3='';
    if(sizeof($resp_fields)>0){
        $r2=':'.implode(':',$resp_fields);
        $r3=','.implode(',',$resp_fields);
        }
    $this->assign("MAP_RESPONSIBILE_FIELDS3",$r2);
    //var_dump(':'.implode(':',$resp_fields));
    $this->assign("MAP_RESPONSIBILE_FIELDS2",$r3);
    //var_dump($resp_fields);var_dump(','.implode(',',$resp_fields));
    //$this->assign("MAP_TARGET_FIELDS",$mapFieldDependecy2['targetfield'][0]);
    $this->assign("MAP_PCKLIST_TARGET",$target_picklist);
    $this->assign("MAP_FIELD_DEPENDENCY",$mapFieldDependecy);//var_dump($mapFieldDependecy);
    global $current_user;
    $roleid=$current_user->roleid;
    $current_profiles=getUserProfile($current_user->id);
    $tempBlocks=$this->get_template_vars('BLOCKS');
    $tempMapFieldDep=$this->get_template_vars('MAP_FIELD_DEPENDENCY');
    $this->assign('CurrRole',$roleid);
    $this->assign('CurrProfiles',$current_profiles);
    $this->assign('BlocksJson',json_encode($tempBlocks));
    $this->assign('MapFieldDep',json_encode($tempMapFieldDep));

