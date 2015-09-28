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
 *  Module       : NgBlock
 *  Version      : 1.8
 *  Author       : OpenCubed
 *************************************************************************************************/

global $adb;
$sql=$adb->query("SELECT tabid,tablabel FROM vtiger_tab where isentitytype=1");
                   $nr=$adb->num_rows($sql);
                   for($i=0 ;$i < $nr ;$i++)
                   {
                     $content[$i]['tabid']=$adb->query_result($sql,$i,'tabid');
                     $content[$i]['tablabel']=$adb->query_result($sql,$i,'tablabel');
                     $content[$i]['tabtrans']=  getTranslatedString($adb->query_result($sql,$i,'tablabel'),$adb->query_result($sql,$i,'module'));
                   }
       echo json_encode($content);
?>