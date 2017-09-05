<?php

/*************************************************************************************************
 * Copyright 2014 Opencubed -- This file is a part of TSOLUCIO coreBOS customizations.
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
 ***********************************************************************************************
 *  Module       : Perspectives
 *  Version      : 5.4.0
 *  Author       : axhemshahaj
 *************************************************************************************************/

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


/** Common fields that should be in every module, linked to vtiger CRM core table
*/
$field3 = new Vtiger_Field();
$field3->name = 'assigned_user_id';
$field3->label = 'Assigned To';
$field3->table = 'vtiger_crmentity';
$field3->column = 'smownerid';
$field3->uitype = 53;
$field3->typeofdata = 'V~M';
$block1->addField($field3);

$field4 = new Vtiger_Field();
$field4->name = 'CreatedTime';
$field4->label= 'Created Time';
$field4->table = 'vtiger_crmentity';
$field4->column = 'createdtime';
$field4->uitype = 70;
$field4->typeofdata = 'T~O';
$field4->displaytype= 2;
$block1->addField($field4);

$field5 = new Vtiger_Field();
$field5->name = 'ModifiedTime';
$field5->label= 'Modified Time';
$field5->table = 'vtiger_crmentity';
$field5->column = 'modifiedtime';
$field5->uitype = 70;
$field5->typeofdata = 'T~O';
$field5->displaytype= 2;
$block1->addField($field5);
/** END */


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


