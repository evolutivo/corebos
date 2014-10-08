<?php
/*************************************************************************************************
 * Copyright 2014 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS Customizations.
* Licensed under the vtiger CRM Public License Version 1.1 (the "License"); you may not use this
* file except in compliance with the License. You can redistribute it and/or modify it
* under the terms of the License. JPL TSolucio, S.L. reserves all rights not expressly
* granted by the License. coreBOS distributed by JPL TSolucio S.L. is distributed in
* the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
* warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Unless required by
* applicable law or agreed to in writing, software distributed under the License is
* distributed on an "AS IS" BASIS, WITHOUT ANY WARRANTIES OR CONDITIONS OF ANY KIND,
* either express or implied. See the License for the specific language governing
* permissions and limitations under the License. You may obtain a copy of the License
* at <http://corebos.org/documentation/doku.php?id=en:devel:vpl11>
 *  Module       : EntittyLog
 *  Version      : 5.4.0
 *  Author       : OpenCubed
 *************************************************************************************************/

class GlobalSettingsImport extends cbupdaterWorker {
	
	function applyChange() {
		if ($this->hasError()) $this->sendError();
		if ($this->isApplied()) {
			$this->sendMsg('Changeset '.get_class($this).' already applied!');
		} else {
			$toinstall = array('GlobalSettings');
			foreach ($toinstall as $module) {
				if ($this->isModuleInstalled($module)) {
					vtlib_toggleModuleAccess($module,true);
					$this->sendMsg("$module activated!");
				} else {
					$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
// Create module instance and save it first
$module = new Vtiger_Module();
$module->name = 'GlobalSettings';
$module->save();
$module->initWebservice();
//// Initialize all the tables required
$module->initTables();
/**
* Creates the following table:
* vtiger_payslip (payslipid INTEGER)
* vtiger_payslipcf(payslipid INTEGER PRIMARY KEY)
* vtiger_payslipgrouprel((payslipid INTEGER PRIMARY KEY, groupname VARCHAR(100))
*/
 //Add the module to the Menu (entry point from UI)
$menu = Vtiger_Menu::getInstance('Sales');
$menu->addModule($module);
// Add the basic module block
$block1 = new Vtiger_Block();
$block1->label = 'LBL_GlobalSettings_INFORMATION';
$module->addBlock($block1);
// Add custom block (required to support Custom Fields)

$field1 = new Vtiger_Field();
$field1->name = 'calendar_display';
$field1->label = 'calendar display';
$field1->table ='vtiger_globalsettings';
$field1->column = 'calendar_display';
$field1->columntype = 'VARCHAR(255)';
$field1->uitype = 2;
$field1->typeofdata = 'V~M';
$block1->addField($field1);
// Set at-least one field to identifier of module record

$field2 = new Vtiger_Field();
$field2->name = 'world_clock_display';
$field2->label = 'world clock display';
$field2->table ='vtiger_globalsettings';
$field2->column = 'world_clock_display';
$field2->columntype = 'VARCHAR(255)';
$field2->uitype = 2;
$field2->typeofdata = 'V~M';
$block1->addField($field2);

//
$field3=new Vtiger_Field();
$field3->name = 'calculator_display';
$field3->label = 'calculator display';
$field3->column = 'calculator_display';
$field3->columntype = 'VARCHAR(255)';
$field3->uitype = 2;
$field3->typeofdata = 'V~M';
$block1->addField($field3);

$field4=new Vtiger_Field();
$field4->name = 'globalsettingsid';
$field4->table ='vtiger_globalsettings';
$module->setEntityIdentifier($field4);

$field5 = new Vtiger_Field();
$field5->name = 'assigned_user_id';
$field5->label = 'Assigned To';
$field5->table = 'vtiger_crmentity';
$field5->column = 'smownerid';
$field5->uitype = 53;
$field5->typeofdata = 'V~M';
$block1->addField($field5);


$field6 = new Vtiger_Field();
$field6->name = 'list_max_entries_per_page';
$field6->label = 'list_max_entries_per_page';
$field6->table = 'vtiger_globalsettings';
$field6->column = 'list_max_entries_per_page';
$field6->columntype = 'VARCHAR(255)';
$field6->uitype = 2;
$field6->typeofdata = 'V~M';
$block1->addField($field6);


$field7 = new Vtiger_Field();
$field7->name = 'limitpage_navigation';
$field7->label = 'limitpage_navigation';
$field7->table = 'vtiger_globalsettings';
$field7->column = 'limitpage_navigation';
$field7->columntype = 'VARCHAR(255)';
$field7->uitype = 2;
$field7->typeofdata = 'V~M';
$block1->addField($field7);


$field8 = new Vtiger_Field();
$field8->name = 'history_max_viewed';
$field8->label = 'history_max_viewed';
$field8->table = 'vtiger_globalsettings';
$field8->column = 'history_max_viewed';
$field8->columntype = 'VARCHAR(255)';
$field8->uitype = 2;
$field8->typeofdata = 'V~M';
$block1->addField($field8);


$field9 = new Vtiger_Field();
$field9->name = 'default_module';
$field9->label = 'default_module';
$field9->table = 'vtiger_globalsettings';
$field9->columntype = 'VARCHAR(255)';
$field9->column = 'default_module';
$field9->uitype = 2;
$field9->typeofdata = 'V~M';
$block1->addField($field9);

$field10 = new Vtiger_Field();
$field10->name = 'default_action';
$field10->label = 'default_action';
$field10->table = 'vtiger_globalsettings';
$field10->column = 'default_action';
$field10->columntype = 'VARCHAR(255)';
$field10->uitype = 2;
$field10->typeofdata = 'V~M';
$block1->addField($field10);

$field11 = new Vtiger_Field();
$field11->name = 'default_theme';
$field11->label = 'default_theme';
$field11->table = 'vtiger_globalsettings';
$field11->columntype = 'VARCHAR(255)';
$field11->column = 'default_theme';
$field11->uitype = 2;
$field11->typeofdata = 'V~M';
$block1->addField($field11);

$field12 = new Vtiger_Field();
$field12->name = 'currency_name';
$field12->label = 'currency_name';
$field12->table = 'vtiger_globalsettings';
$field12->column = 'currency_name';
$field12->columntype = 'VARCHAR(255)';
$field12->uitype = 2;
$field12->typeofdata = 'V~M';
$block1->addField($field12);

$field13 = new Vtiger_Field();
$field13->name = 'default_charset';
$field13->label = 'default_charset';
$field13->table = 'vtiger_globalsettings';
$field13->column = 'default_charset';
$field13->columntype = 'VARCHAR(255)';
$field13->uitype = 2;
$field13->typeofdata = 'V~M';
$block1->addField($field13);

$field14 = new Vtiger_Field();
$field14->name = 'listview_max_textlength';
$field14->label = 'listview_max_textlength';
$field14->table = 'vtiger_globalsettings';
$field14->column = 'listview_max_textlength';
$field14->columntype = 'VARCHAR(255)';
$field14->uitype = 2;
$field14->typeofdata = 'V~M';
$block1->addField($field14);

$field15 = new Vtiger_Field();
$field15->name = 'vtiger_current_version';
$field15->label = 'vtiger_current_version';
$field15->table = 'vtiger_globalsettings';
$field15->column = 'vtiger_current_version';
$field15->columntype = 'VARCHAR(255)';
$field15->uitype = 2;
$field15->typeofdata = 'V~M';
$block1->addField($field15);


$field16 = new Vtiger_Field();
$field16->name = 'helpdesk_support_email_id';
$field16->label = 'helpdesk_support_email_id';
$field16->table = 'vtiger_globalsettings';
$field16->column = 'helpdesk_support_email_id';
$field16->columntype = 'VARCHAR(255)';
$field16->uitype = 2;
$field16->typeofdata = 'V~M';
$block1->addField($field16);

$field17 = new Vtiger_Field();
$field17->name = 'helpdesk_support_name';
$field17->label = 'helpdesk_support_name';
$field17->table = 'vtiger_globalsettings';
$field17->column = 'helpdesk_support_name';
$field17->columntype = 'VARCHAR(255)';
$field17->uitype = 2;
$field17->typeofdata = 'V~M';
$block1->addField($field17);

$field30 = new Vtiger_Field();
$field30->name = 'corebos_app_name';
$field30->label = 'corebos_app_name';
$field30->table = 'vtiger_globalsettings';
$field30->column = 'corebos_app_name';
$field30->columntype = 'VARCHAR(255)';
$field30->uitype = 2;
$field30->typeofdata = 'V~M';

$block1->addField($field30);
$field31 = new Vtiger_Field();
$field31->name = 'corebos_app_version';
$field31->label = 'corebos_app_version';
$field31->table = 'vtiger_globalsettings';
$field31->column = 'corebos_app_version';
$field31->columntype = 'VARCHAR(255)';
$field31->uitype = 2;
$field31->typeofdata = 'V~M';
$block1->addField($field31);


$field28 = new Vtiger_Field();
$field28->name = 'corebos_app_url';
$field28->label = 'corebos_app_url';
$field28->table = 'vtiger_globalsettings';
$field28->column = 'corebos_app_url';
$field28->columntype = 'VARCHAR(255)';
$field28->uitype = 2;
$field28->typeofdata = 'V~M';
$block1->addField($field28);


$field29 = new Vtiger_Field();
$field29->name = 'upload_dir';
$field29->label = 'upload_dir';
$field29->table = 'vtiger_globalsettings';
$field29->column = 'upload_dir';
$field29->columntype = 'VARCHAR(255)';
$field29->uitype = 2;
$field29->typeofdata = 'V~M';
$block1->addField($field29);



$field18 = new Vtiger_Field();
$field18->name = 'upload_badext';
$field18->label = 'upload_badext';
$field18->table = 'vtiger_globalsettings';
$field18->column = 'upload_badext';
$field18->columntype = 'VARCHAR(255)';
$field18->uitype = 2;
$field18->typeofdata = 'V~M';
$block1->addField($field18);


$field19 = new Vtiger_Field();
$field19->name = 'maxwebservicesessionlifespan';
$field19->label = 'maxwebservicesessionlifespan';
$field19->table = 'vtiger_globalsettings';
$field19->column = 'maxwebservicesessionlifespan';
$field19->columntype = 'VARCHAR(255)';
$field19->uitype = 2;
$field19->typeofdata = 'V~M';
$block1->addField($field19);

$field20 = new Vtiger_Field();
$field20->name = 'maxwebservicesessionidletime';
$field20->label = 'maxwebservicesessionidletime';
$field20->table = 'vtiger_globalsettings';
$field20->column = 'maxwebservicesessionidletime';
$field20->columntype = 'VARCHAR(255)';
$field20->uitype = 2;
$field20->typeofdata = 'V~M';
$block1->addField($field20);

$field21 = new Vtiger_Field();
$field21->name = 'upload_maxsize';
$field21->label = 'upload_maxsize';
$field21->table = 'vtiger_globalsettings';
$field21->column = 'upload_maxsize';
$field21->columntype = 'VARCHAR(255)';
$field21->uitype = 2;
$field21->typeofdata = 'V~M';
$block1->addField($field21);

$field22 = new Vtiger_Field();
$field22->name = 'allow_exports';
$field22->label = 'allow_exports';
$field22->table = 'vtiger_globalsettings';
$field22->column = 'allow_exports';
$field22->columntype = 'VARCHAR(255)';
$field22->uitype = 2;
$field22->typeofdata = 'V~M';
$block1->addField($field22);

$field23 = new Vtiger_Field();
$field23->name = 'minimum_cron_frequency';
$field23->label = 'minimum_cron_frequency';
$field23->table = 'vtiger_globalsettings';
$field23->column = 'minimum_cron_frequency';
$field23->columntype = 'VARCHAR(255)';
$field23->uitype = 2;
$field23->typeofdata = 'V~M';
$block1->addField($field23);


$field24 = new Vtiger_Field();
$field24->name = 'default_timezone';
$field24->label = 'default_timezone';
$field24->table = 'vtiger_globalsettings';
$field24->column = 'default_timezone';
$field24->columntype = 'VARCHAR(255)';
$field24->uitype = 2;
$field24->typeofdata = 'V~M';
$block1->addField($field24);


$field25 = new Vtiger_Field();
$field25->name = 'default_language';
$field25->label = 'default_language';
$field25->table = 'vtiger_globalsettings';
$field25->column = 'default_language';
$field25->columntype = 'VARCHAR(255)';
$field25->uitype = 2;
$field25->typeofdata = 'V~M';
$block1->addField($field25);


$field26 = new Vtiger_Field();
$field26->name = 'import_dir';
$field26->label = 'import_dir';
$field26->table = 'vtiger_globalsettings';
$field26->column = 'import_dir';
$field26->columntype = 'VARCHAR(255)';
$field26->uitype = 2;
$field26->typeofdata = 'V~M';
$block1->addField($field26);

$field32 = new Vtiger_Field();
$field32->name = 'root_directory';
$field32->label = 'root_directory';
$field32->table = 'vtiger_globalsettings';
$field32->column = 'root_directory';
$field32->columntype = 'VARCHAR(255)';
$field32->uitype = 2;
$field32->typeofdata = 'V~M';
$block1->addField($field32);

$field33 = new Vtiger_Field();
$field33->name = 'site_url';
$field33->label = 'site_url';
$field33->table = 'vtiger_globalsettings';
$field33->column = 'site_url';
$field33->columntype = 'VARCHAR(255)';
$field33->uitype = 2;
$field33->typeofdata = 'V~M';
$block1->addField($field33);

$field34 = new Vtiger_Field();
$field34->name = 'cache_dir';
$field34->label = 'cache_dir';
$field34->table = 'vtiger_globalsettings';
$field34->column = 'cache_dir';
$field34->columntype = 'VARCHAR(255)';
$field34->uitype = 2;
$field34->typeofdata = 'V~M';
$block1->addField($field34);

$field35 = new Vtiger_Field();
$field35->name = 'tmp_dir';
$field35->label = 'tmp_dir';
$field35->table = 'vtiger_globalsettings';
$field35->column = 'tmp_dir';
$field35->columntype = 'VARCHAR(255)';
$field35->uitype = 2;
$field35->typeofdata = 'V~M';
$block1->addField($field35);
/** END */

// Create default custom filter (mandatory)
$filter1 = new Vtiger_Filter();
$filter1->name = 'All';
$filter1->isdefault = true;
$module->addFilter($filter1);
// Add fields to the filter created
$filter1->addField($field1)->addField($field2, 1);
/** Set sharing access of this module */
$module->setDefaultSharing('Private');
/** Enable and Disable available tools */
$module->enableTools(Array('Import', 'Export'));
$module->disableTools('Merge');
				}
			}
			$this->sendMsg('Changeset '.get_class($this).' applied!');
			$this->markApplied();
		}
		$this->finishExecution();
	}
	
	function undoChange() {
		if ($this->hasError()) $this->sendError();
		if ($this->isApplied()) {
			vtlib_toggleModuleAccess('GlobalSettings',false);
			$this->sendMsg('GlobalSettings deactivated!');
			$this->markUndone(false);
			$this->sendMsg('Changeset '.get_class($this).' undone!');
		} else {
			$this->sendMsg('Changeset '.get_class($this).' not applied, it cannot be undone!');
		}
		$this->finishExecution();
	}
	
}
