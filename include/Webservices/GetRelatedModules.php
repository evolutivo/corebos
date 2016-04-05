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
require_once 'include/utils/CommonUtils.php';

function vtws_getrelatedmodules($module,$type){
	global $log,$adb,$default_language,$mod_strings,$current_language;
	$log->debug("Entering function vtws_getrelatedmodules");

        $get_relmod_ui10="Select *"
                . " from vtiger_ng_block"
                . " where module_name=? and destination=?"
                . " order by sequence_ngblock";

        $result=$adb->pquery($get_relmod_ui10,array($module,$type));
        $count=$adb->num_rows($result);
        $relmods=array();
        if($count>0){
            for($i=0;$i<$count;$i++){
                $t='';
                $mod=$adb->query_result($result,$i,'pointing_module_name');
                $blockname=$adb->query_result($result,$i,'name');
                if(!empty($mod)){
                require("modules/$mod/language/it_it.lang.php");
                require("include/language/it_it.lang.php");
                  $relmods[$i]=array('module'=>$mod,'module_trans'=>$blockname,
                        'relfield'=>$adb->query_result($result,$i,'pointing_field_name'));
                }
            }            
        }
        return $relmods;
}

?>