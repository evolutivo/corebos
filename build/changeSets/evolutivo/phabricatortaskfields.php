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
*************************************************************************************************/
class phabricatortaskfields extends cbupdaterWorker {
	function applyChange() {
		global $adb;
		if ($this->hasError()) $this->sendError();
		if ($this->isApplied()) {
			$this->sendMsg('Changeset '.get_class($this).' already applied!');
		} else {
			global $adb;
			$modname = 'ProjectTask';
			$module = Vtiger_Module::getInstance($modname);
                    $block = Vtiger_Block::getInstance('LBL_PROJECT_TASK_INFORMATION', $module);

                    $field = Vtiger_Field::getInstance('phabricatortaskid',$module);
                    if (!$field) {
                        $field1 = new Vtiger_Field();
                        $field1->name = 'phabricatortaskid';
                        $field1->label= 'Phabricator Task Id';
                        $field1->column = 'phabricatortaskid';
                        $field1->columntype = 'int(11)';
                        $field1->uitype = 2;
                        $field1->typeofdata = 'I~O';
                        $block->addField($field1);
                    }

                    $field = Vtiger_Field::getInstance('phabricatortaskphid',$module);
                    if (!$field) {
                        $field1 = new Vtiger_Field();
                        $field1->name = 'phabricatortaskphid';
                        $field1->label= 'Phabricator Task PHId';
                        $field1->column = 'phabricatortaskphid';
                        $field1->columntype = 'VARCHAR(250)';
                        $field1->uitype = 2;
                        $field1->typeofdata = 'V~O';
                        $block->addField($field1);
                    }
                      
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

?>