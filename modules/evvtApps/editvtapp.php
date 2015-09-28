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
 
require_once('Smarty_setup.php');
require_once('data/Tracker.php');
require_once('include/CustomFieldUtil.php');
require_once('include/utils/utils.php');
$mypath='modules/evvtApps';

global $current_language,$adb;
$vtappid=$_REQUEST['vtappid'];
$path="/var/www/sunlife/homedocuments/";
require_once($mypath.'/vtapps/baseapp/vtapp.php');
require_once($mypath.'/vtapps/app'.$vtappid.'/vtapp.php');
$appName=$_REQUEST['appname'];
$app=new vtapp($vtappid);
$content= $app->getContent($current_language);

$filenames='';
$dir = opendir($path);
while ($dir && ($file = readdir($dir)) !== false) {
 if(substr($file,0,1)!='.') $filenames.="homedocuments/".$file."\r\n";
}
$popupcontent=file_get_contents("Smarty/templates/modules/vtapps/popupcontent.tpl");
$showHomePage =$adb->query_result($adb->pquery("select showhomepagepopup from vtiger_evvtapps where evvtappsid=9",array()),0);
//echo $content;
$smarty=new vtigerCRM_Smarty;
$smarty->assign("vtappID",$vtappid);
$smarty->assign("CONTENT",$content);
$smarty->assign("FILENAMES",$filenames);
$smarty->assign("POPUPCONTENT",$popupcontent);
$smarty->assign("SHOWHOMEPAGE",$showHomePage);
$smarty->display('modules/vtapps/vtappeditor.tpl');
?>