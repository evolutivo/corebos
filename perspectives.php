<?php

// Turn on debugging level
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');


// Create module instance and save it first
$module = new Vtiger_Module();
$module->name = 'Perspectives';
$module->save();

// Initialize all the tables required
$module->initTables();
$module->initWebservice();
/**
 * Creates the following table:
 * vtiger_perspectives(perspectivesid INTEGER)
 * vtiger_perspectivescf(perspectivesid INTEGER PRIMARY KEY)
 * vtiger_perspectivesgrouprel((perspectivesid INTEGER PRIMARY KEY, groupname VARCHAR(100))
 */
// A
//$module->initWebservice();

// Add the module to the Menu (entry point from UI)
$menu = Vtiger_Menu::getInstance('Tools');
$menu->addModule($module);

// Add the basic module block
$block1 = new Vtiger_Block();
$block1->label = 'LBL_PERSPECTIVES_INFORMATION';
$module->addBlock($block1);

// Add custom block (required to support Custom Fields)
$block2 = new Vtiger_Block();
$block2->label = 'LBL_CUSTOM_INFORMATION';
$module->addBlock($block2);

$block3 = new Vtiger_Block();
$block3->label = 'LBL_DESCRIPTION_INFORMATION';
$module->addBlock($block3);

//Add Fields for the module

$field1 = new Vtiger_Field();
$field1->name = 'perspective_name';
$field1->label = 'Perspective Name';
$field1->column = 'perspective_name';
$field1->columntype = 'VARCHAR(100)';
$field1->table = $module->basetable;
$field1->displaytype = 1;
$field1->typeofdata = 'V~M';
$field1->uitype = 2;
$block1->addField($field1);

$module->setEntityIdentifier($field1);

$field2 = new Vtiger_Field();
$field2->name = 'perspective_file';
$field2->label = 'Perspective File';
$field2->column = 'perspective_file';
$field2->table = $module->basetable;
$field2->columntype = 'VARCHAR(100)';
$field2->displaytype = 1;
$field2->typeofdata = 'C~O';
$field2->uitype = 44;
$block1->addField($field2);


/** END */

// Create default custom filter (mandatory)
$filter = new Vtiger_Filter();
$filter->name = 'All';
$filter->isdefault = true;
$module->addFilter($filter);

//Add fields to the created filter
$filter->addField($field1);

/** Set sharing access of this module */
$module->setDefaultSharing('Public_ReadOnly');

/** Enable and Disable available tools */
$module->enableTools(Array('Import', 'Export'));
$module->disableTools('Merge');

?>


