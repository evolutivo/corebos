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
 
$id=$_REQUEST['pdftemplate'];
$recordval=$_REQUEST["recordvalpdf"];
$record=$_REQUEST["record"];
global $adb;
$query=$adb->pquery("Select appname,letterformat,orientation from  vtiger_evvtapps where evvtappsid=?",array($id));
$appName=$adb->query_result($query,0,"appname");
$paper=$adb->query_result($query,0,"letterformat");
$orientation=$adb->query_result($query,0,"orientation");
//include("modules/evvtApps/vtapps/app$id/content.php");
//$content=  file_get_contents("modules/evvtApps/vtapps/app$id/content.php");
require_once('Smarty_setup.php');
$smarty = new vtigerCRM_Smarty();
$smarty->assign('appName', $appName);
$smarty->assign('ID', $id);
$smarty->assign('paper', $paper);
$smarty->assign('orientation', $orientation);
$smarty->display('modules/evvtApps/RenderPDF.tpl');
?>