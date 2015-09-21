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
 
require_once("modules/evvtApps/vtapps/baseapp/vtapp.php");
$vtappid=$_REQUEST['vtappid'];
$content=$_REQUEST['vtappcontent'];
$popupcontent=$_REQUEST['popupcontent'];
$showpopup=$_REQUEST['showpopup']==='on'?1:0;

$vtapp=new vtApp($vtappid);
$vtapp->setContent($content);

file_put_contents("Smarty/templates/modules/vtapps/popupcontent.tpl",$popupcontent);
global $adb;
$adb->pquery("Update vtiger_evvtapps set showhomepagepopup=? where evvtappsid=?",array($showpopup,$vtappid));
header("Location: index.php?action=app&appname=Teknemaapp&parenttab=Home&module=evvtApps");
?>
