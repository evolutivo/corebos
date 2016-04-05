<?php
/*************************************************************************************************
 * Copyright 2012-2014 JPL TSolucio, S.L.  --  This file is a part of coreBOSCP.
* You can copy, adapt and distribute the work under the "Attribution-NonCommercial-ShareAlike"
* Vizsage Public License (the "License"). You may not use this file except in compliance with the
* License. Roughly speaking, non-commercial users may share and modify this code, but must give credit
* and share improvements. However, for proper details please read the full License, available at
* http://vizsage.com/license/Vizsage-License-BY-NC-SA.html and the handy reference for understanding
* the full license at http://vizsage.com/license/Vizsage-Deed-BY-NC-SA.html. Unless required by
* applicable law or agreed to in writing, any software distributed under the License is distributed
* on an  "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and limitations under the
* License terms of Creative Commons Attribution-NonCommercial-ShareAlike 3.0 (the License).
*************************************************************************************************/

function vtws_getrelatedactions($module,$type){
	global $log,$adb,$default_language;
	$log->debug("Entering function vtws_getrelatedactions");

        $sql = 'SELECT * FROM vtiger_actions'
                . ' INNER JOIN vtiger_crmentity ce ON ce.crmid=vtiger_actions.actionsid'
                . ' where ce.deleted=0  and actions_status="Active"'
                . ' and elementtype_action =? and moduleactions=? ';
        $result=$adb->pquery($sql,array($type,$module));
        $count=$adb->num_rows($result);
        $relmods=array();
        if($count>0){
            for($i=0;$i<$count;$i++){
                $relmods[$i]=array('actionid'=>$adb->query_result($result,$i,'actionsid'),
                    'reference'=>$adb->query_result($result,$i,'reference'),
                    'description'=>$adb->query_result($result,$i,'description'));
            }            
        }
	return $relmods;
}

function vtws_RunAction($parameters){  
        include_once('modules/BusinessActions/runJSONAction.php');
        global $adb, $log, $current_user,$root_directory,$default_language;
        $actionid=$parameters['actionid'];
        
        $sql = 'SELECT * FROM vtiger_actions'
                . ' where actionsid=? ';
        $result=$adb->pquery($sql,array($actionid));
        $outputtype=$adb->query_result($result,0,'output_type');
    
        
        $arr_ret= runJSONAction($actionid,json_encode($parameters));
        $arr=json_decode($arr_ret,true);
        $arr['outputtype']=$outputtype;
        return $arr;
}

function vtws_getFieldDep($module,$type){
	global $log,$adb,$default_language;
	$log->debug("Entering function vtws_getFieldDep");

        $resp_f=array();
        $resp_fields=array();
        $target_picklist=array();
        $conditions=array();
        $mapFieldDependecy=array();
        $sql = 'SELECT * FROM vtiger_businessrules'
        . ' INNER JOIN vtiger_crmentity ce ON ce.crmid=vtiger_businessrules.businessrulesid'
        . ' INNER JOIN vtiger_cbmap  ON vtiger_businessrules.linktomap=vtiger_cbmap.cbmapid'
        . ' INNER JOIN vtiger_crmentity c2 ON c2.crmid=vtiger_cbmap.cbmapid'
        . ' where ce.deleted=0 and c2.deleted=0 and module_rules =? and maptype=?';
        $res_business_rule=$adb->pquery($sql,array($module,$type));
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
        } 
        $resp_fields=array_values(array_unique($resp_fields));  
        $target_picklist=array_values(array_unique($target_picklist));
        $r2='';$r3='';
        if(sizeof($resp_fields)>0){
            $r2=':'.implode(':',$resp_fields);
            $r3=','.implode(',',$resp_fields);
        }
	return array('all_field_dep'=>$mapFieldDependecy,
                        'MAP_RESPONSIBILE_FIELDS'=>$resp_fields,
                        'MAP_RESPONSIBILE_FIELDS3'=>$r2,
                        'MAP_RESPONSIBILE_FIELDS2'=>$r3,
                        'MAP_PCKLIST_TARGET'=>$target_picklist);
}


?>
