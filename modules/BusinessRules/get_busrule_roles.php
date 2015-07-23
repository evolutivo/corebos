<?php
/**
 *************************************************************************************************
 * Copyright 2015 OpenCubed -- This file is a part of OpenCubed coreBOS customizations.
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
 *  Module       : BusinessRules
 *  Version      : 5.5.0
 *  Author       : OpenCubed.
 *************************************************************************************************/

require_once('include/logging.php');
require_once('include/database/PearDatabase.php');

global $log,$adb;
$id=$_REQUEST['record_id'];
$sel=$_REQUEST['sel_values'];//selected values actual fieldvalue
$query=$_REQUEST['query'];
$ticked=false; 
$content=array();

if(!isset($sel)) //query on click
{
$sql="Select profilename,profileid
        from vtiger_profile
        where profilename like '$query%'
        ";
        
    $result=$adb->pquery($sql,array());
    for($i=0;$i<$adb->num_rows($result);$i++)
    {
       $profileid=$adb->query_result($result,$i,'profileid');
       $content[]=array('id'=>"$profileid",
           'name'=>$adb->query_result($result,$i,'profilename'));
    }
    echo json_encode($content);
}
else{ //selected values actual fieldvalue
 
$evo_actions=$sel;
if($evo_actions!='')
{ 
    $arr_evo_actions=explode(',',$evo_actions);

    $sql="Select profilename,profileid
        from vtiger_profile
        where profileid in (".  generateQuestionMarks($arr_evo_actions).")
            ORDER BY FIELD( profileid, ".  generateQuestionMarks($arr_evo_actions).") 
        ";  

       $content=array();
        $par=array_merge($arr_evo_actions,$arr_evo_actions);
        $result=$adb->pquery($sql,$par);
        for($i=0;$i<$adb->num_rows($result);$i++)
            {
               $profileid=$adb->query_result($result,$i,'profileid');
                   if($evo_actions!=''){
                   $content[$i]['id']=$profileid;
                   $content[$i]['name']=$adb->query_result($result,$i,'profilename');
                }
            }
}
echo json_encode($content);
}