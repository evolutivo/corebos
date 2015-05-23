<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$Vtiger_Utils_Log = true;

include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');

// Create module instance and save it first
$module = new Vtiger_Module();
$module->name = 'ElasticSearch';
$module->version = '1.0';
$module->save();

// Add the module to the Menu (entry point from UI)
$menu = Vtiger_Menu::getInstance('Tool');
$menu->addModule($module);
$module->initWebservice();
?>
