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

        $sql = 'SELECT * FROM vtiger_businessactions'
                . ' INNER JOIN vtiger_crmentity ce ON ce.crmid=vtiger_businessactions.businessactionsid'
                . ' where ce.deleted=0  and actions_status="Active"'
                . ' and elementtype_action =? and moduleactions=? ';
        $result=$adb->pquery($sql,array($type,$module));
        $count=$adb->num_rows($result);
        $relmods=array();
        if($count>0){
            for($i=0;$i<$count;$i++){
                $relmods[$i]=array('actionid'=>$adb->query_result($result,$i,'businessactionsid'),
                    'reference'=>$adb->query_result($result,$i,'reference'),
                    'description'=>$adb->query_result($result,$i,'description'));
            }            
        }
	return $relmods;
}

function vtws_RunAction($parameters){  
        include_once('modules/BusinessActions/runJSONAction.php');
        global $adb;
        $actionid=$parameters['actionid'];
        
        $sql = 'SELECT * FROM vtiger_businessactions'
                . ' where businessactionsid=? ';
        $result=$adb->pquery($sql,array($actionid));
        $outputtype=$adb->query_result($result,0,'output_type');
    
        
        $arr_ret= runJSONAction($actionid,json_encode($parameters));
        $arr=json_decode($arr_ret,true);
        $arr['outputtype']=$outputtype;
        return $arr;
}

?>
