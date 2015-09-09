<?php

/* * *******************************************************************************

 * * The contents of this file are subject to the Evolutivo BPM License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * ****************************************************************************** */
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