<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$Vtiger_Utils_Log = true;

include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');

// Create module instance and save it first
$module = new Vtiger_Module();
$module->name = 'DASHBOARD_NAME';
$module->version = '1.0';
$module->save();

// Add the module to the Menu (entry point from UI)
$menu = Vtiger_Menu::getInstance('PARENT_NAME');
$menu->addModule($module);
$module->initWebservice();
$adb->pquery('UPDATE vtiger_tab set isentitytype=? WHERE name=?',Array(4, $module->name));
//isentitytype 4 for all FormExtensions
?>
