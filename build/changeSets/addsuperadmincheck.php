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

class addsuperadmincheck extends cbupdaterWorker {
	
	function applyChange() {
		global $adb;
		if ($this->hasError()) $this->sendError();
		if ($this->isApplied()) {
			$this->sendMsg('Changeset '.get_class($this).' already applied!');
		} else {
			global $adb;
			
			$moduleInstance = Vtiger_Module::getInstance('Users');
			$block = Vtiger_Block::getInstance('LBL_USERLOGIN_ROLE', $moduleInstance);
			
				$forecast_field = new Vtiger_Field();
				$forecast_field->name = 'superadmincheck';
				$forecast_field->label = 'Superadmin';
				$forecast_field->table ='vtiger_users';
				$forecast_field->column = 'superadmincheck';
				$forecast_field->columntype = 'VARCHAR(10)';
				$forecast_field->typeofdata = 'V~O';
				$forecast_field->uitype = '56';
				$forecast_field->masseditable = '0';
				$block->addField($forecast_field);
			}
			
			$this->sendMsg('Changeset '.get_class($this).' applied!');
			$this->markApplied();
			
		
		$this->finishExecution();
	}
	
	function undoChange() {
		if ($this->hasError()) $this->sendError();
		if ($this->isApplied()) {
			// undo your magic here
			$moduleInstance=Vtiger_Module::getInstance('Potentials');
			$field = Vtiger_Field::getInstance('forecast_amount',$moduleInstance);
			if ($field) {
				$this->ExecuteQuery('update vtiger_field set presence=1 where fieldid='.$field->id);
			}
			global $adb;
			$wfrs = $adb->query("SELECT workflow_id FROM com_vtiger_workflows WHERE summary='Calculate or Update forecast amount'");
			if ($wfrs and $adb->num_rows($wfrs)==1) {
				$wfid = $adb->query_result($wfrs,0,0);
				$this->deleteWorkflow($wfid);
				$this->sendMsg('Workflow deleted!');
			}
			$this->sendMsg('Changeset '.get_class($this).' undone!');
			$this->markUndone();
		} else {
			$this->sendMsg('Changeset '.get_class($this).' not applied!');
		}
		$this->finishExecution();
	}
	
}