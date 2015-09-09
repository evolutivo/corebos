<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
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
