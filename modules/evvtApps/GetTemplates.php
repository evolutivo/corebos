<?php
/*************************************************************************************************
 * Copyright 2012-2013 OpenCubed  --  
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
 *  Module       : evvtApps
 *  Version      : 1.8
 *  Author       : OpenCubed
 *************************************************************************************************/
 
$moduleid=  getTabid($_REQUEST['formodule']);
global $adb,$log,$mod_strings;
$log->debug('alb3 '.$moduleid.' '.$_REQUEST['formodule']);
$query=$adb->pquery("Select evvtappsid,appname from vtiger_evvtapps where moduleid=?",array($moduleid));
$number=$adb->num_rows($query);
if ($number>0) $output="Template: <select class='small' id='pdftemplate' name='pdftemplate'>";
for($i=0;$i<$number;$i++)
{
$output.="<option value='".$adb->query_result($query,$i,'evvtappsid')."'>".$adb->query_result($query,$i,'appname')."</option>";
}
if ($number==0) $output='Template';
else $output.="</select> <br/><br/>";
echo $output;
?>
