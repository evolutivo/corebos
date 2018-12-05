<?php
/*************************************************************************************************
 * Copyright 2018 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS Customizations.
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
*************************************************************************************************/

class addfieldstobusinessactions extends cbupdaterWorker {
	function applyChange() {
		global $adb;
		if ($this->hasError()) $this->sendError();
		if ($this->isApplied()) {
			$this->sendMsg('Changeset '.get_class($this).' already applied!');
		} else {
			global $adb;
			$modname = 'BusinessActions';
			$module = Vtiger_Module::getInstance($modname);
                        $block = Vtiger_Block::getInstance('LBL_BUSINESSACTIONS_INFORMATION', $module);
                        $field_acc=new Vtiger_Field();
                        $field_acc->name='handler_path';
                        $field_acc->label='handler_path';
                        $field_acc->column = 'handler_path';
                        $field_acc->columntype = 'VARCHAR(250)';
                        $field_acc->uitype=1;
                        $field_acc->typeofdata = 'V~O';
                        $field_acc->quickcreate = 1;
                        $field_acc->displaytype = 1;
                        $field_acc->masseditable = 1;
                        $block->addField($field_acc);
                        
                        $field_acc=new Vtiger_Field();
                        $field_acc->name='handler_class';
                        $field_acc->label='handler_class';
                        $field_acc->column = 'handler_class';
                        $field_acc->columntype = 'VARCHAR(250)';
                        $field_acc->uitype=1;
                        $field_acc->typeofdata = 'V~O';
                        $field_acc->quickcreate = 1;
                        $field_acc->displaytype = 1;
                        $field_acc->masseditable = 1;
                        $block->addField($field_acc);
                        
                        $field_acc=new Vtiger_Field();
                        $field_acc->name='handler';
                        $field_acc->label='handler';
                        $field_acc->column = 'handler';
                        $field_acc->columntype = 'VARCHAR(250)';
                        $field_acc->uitype=1;
                        $field_acc->typeofdata = 'V~O';
                        $field_acc->quickcreate = 1;
                        $field_acc->displaytype = 1;
                        $field_acc->masseditable = 1;
                        $block->addField($field_acc);
                        
                        $field_acc=new Vtiger_Field();
                        $field_acc->name='module_list';
                        $field_acc->label='module_list';
                        $field_acc->column = 'module_list';
                        $field_acc->columntype = 'VARCHAR(250)';
                        $field_acc->uitype=3314;
                        $field_acc->typeofdata = 'V~O';
                        $field_acc->quickcreate = 1;
                        $field_acc->displaytype = 1;
                        $field_acc->masseditable = 1;
                        
                        $field_acc=new Vtiger_Field();
                        $field_acc->name='active';
                        $field_acc->label='active';
                        $field_acc->column = 'active';
                        $field_acc->columntype = 'VARCHAR(250)';
                        $field_acc->uitype=56;
                        $field_acc->typeofdata = 'C~O';
                        $field_acc->quickcreate = 1;
                        $field_acc->displaytype = 1;
                        $field_acc->masseditable = 1;
                        
                        $field_acc=new Vtiger_Field();
                        $field_acc->name='acrole';
                        $field_acc->label='acrole';
                        $field_acc->column = 'acrole';
                        $field_acc->columntype = 'VARCHAR(250)';
                        $field_acc->uitype=1024;
                        $field_acc->typeofdata = 'V~O';
                        $field_acc->quickcreate = 1;
                        $field_acc->displaytype = 1;
                        $field_acc->masseditable = 1;
                        
                        $field_acc=new Vtiger_Field();
                        $field_acc->name='mandatory';
                        $field_acc->label='mandatory';
                        $field_acc->column = 'mandatory';
                        $field_acc->columntype = 'VARCHAR(250)';
                        $field_acc->uitype=56;
                        $field_acc->typeofdata = 'C~O';
                        $field_acc->quickcreate = 1;
                        $field_acc->displaytype = 1;
                        $field_acc->masseditable = 1;
                        
                        $field_acc=new Vtiger_Field();
                        $field_acc->name='onlyonmymodule';
                        $field_acc->label='onlyonmymodule';
                        $field_acc->column = 'onlyonmymodule';
                        $field_acc->columntype = 'VARCHAR(250)';
                        $field_acc->uitype=56;
                        $field_acc->typeofdata = 'C~O';
                        $field_acc->quickcreate = 1;
                        $field_acc->displaytype = 1;
                        $field_acc->masseditable = 1;
                        
                        $field_acc=new Vtiger_Field();
                        $field_acc->name='brmap';
                        $field_acc->label='brmap';
                        $field_acc->column = 'brmap';
                        $field_acc->columntype = 'VARCHAR(250)';
                        $field_acc->uitype=10;
                        $field_acc->typeofdata = 'V~O';
                        $field_acc->quickcreate = 1;
                        $field_acc->displaytype = 1;
                        $field_acc->masseditable = 1;
                        $block->addField($field_acc);
                        
			$this->sendMsg('Changeset '.get_class($this).' applied!');
			$this->markApplied();
		}
		$this->finishExecution();
	}
	
	function undoChange() {
		if ($this->isBlocked()) return true;
		if ($this->hasError()) $this->sendError();
		if ($this->isSystemUpdate()) {
			$this->sendMsg('Changeset '.get_class($this).' is a system update, it cannot be undone!');
		}
		$this->finishExecution();
	}
}


