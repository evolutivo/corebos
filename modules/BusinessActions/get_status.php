<?php
/*************************************************************************************************
* Copyright 2012-2013 OpenCubed  --  This file is a part of vtMktDashboard.
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
*  Module       : BusinessActions
*  Version      : 1.8
*  Author       : OpenCubed
*************************************************************************************************/
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');

global $log,$adb;

$c_val=array();
$sql="Select *
        from vtiger_actions_block
        ";    
    $result=$adb->pquery($sql,array());
    for($i=0;$i<$adb->num_rows($result);$i++)
    {
       //$content[$i]['accountid']=$adb->query_result($result,$i,'accountid');
       $block_name=$adb->query_result($result,$i,'block_name');
       $c_val[]=array("str"=>$block_name,"name"=>$block_name);

    }

    //$c_val=json_encode($c_val);

echo json_encode($c_val);