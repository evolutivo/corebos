<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
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