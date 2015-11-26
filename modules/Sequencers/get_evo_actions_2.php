<?php
/*************************************************************************************************
 * Copyright 2011-2013 TSolucio  --  This file is a part of vtMktDashboard.
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
*************************************************************************************************
*  Module       : Sequencers
*  Version      : 1.8
*  Author       : OpenCubed
*************************************************************************************************/
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');

global $log,$adb;
$id=$_REQUEST['record_id'];
$ticked=false; 
$sql="Select *
        from vtiger_businessactions
        join vtiger_crmentity on crmid=businessactionsid
        where deleted=0  
        ";

$sql_evo_actions="Select evo_actions
        from vtiger_sequencers
        where sequencersid=?  
        "; 
$res_evo_actions=$adb->pquery($sql_evo_actions,array($id));
$evo_actions=$adb->query_result($res_evo_actions,0,'evo_actions');
if($evo_actions!='')
    $arr_evo_actions=explode(';',$evo_actions);

    $content=array();
    $result=$adb->pquery($sql,array());
    for($i=0;$i<$adb->num_rows($result);$i++)
    {
       $act_id=$adb->query_result($result,$i,'businessactionsid');
       $content[$i]['name']=$adb->query_result($result,$i,'reference');
       $content[$i]['maker']=$adb->query_result($result,$i,'reference');
       if($evo_actions!=''){
           if(in_array($act_id, $arr_evo_actions))
               $ticked=true;
           else
               $ticked=false;  
       }
       $content[$i]['ticked']=$ticked;
       $content[$i]['id']=$act_id;
//       $content[$i]['icon']=$adb->query_result($result,$i,'statusname');
    }

    //$c_val=json_encode($c_val);

echo json_encode($content);
