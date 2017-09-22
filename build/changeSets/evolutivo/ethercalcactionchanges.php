<?php
/*************************************************************************************************
 * Copyright 2017 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS Customizations.
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

class ethercalcactionchanges extends cbupdaterWorker {

	function applyChange() {
		global $adb;
		if ($this->hasError()) $this->sendError();
		if ($this->isApplied()) {
			$this->sendMsg('Changeset '.get_class($this).' already applied!');
		} else {    global $current_user;
                            require_once('modules/BusinessActions/BusinessActions.php');
                            require_once('modules/cbMap/cbMap.php');
                            require_once("vtlib/Vtiger/Module.php");
                            $module = Vtiger_Module::getInstance("BusinessActions");
                            $fld_gvname = Vtiger_Field::getInstance('output_type', $module);
                            $fld_gvname->setPicklistValues(Array('AlertReload'));
                            
                            $focus1 = new cbMap();
                            $focus1->column_fields['mapname'] = 'Spreadsheets I/O Map';
                            $focus1->column_fields['maptype'] = 'IOMap';
                            $focus1->column_fields['content'] = '<?xml version="1.0"?><map><input><fields><field><fieldname>recordid</fieldname></field></fields></input><output><fields><field><fieldname>result</fieldname></field></fields></output></map>';
                            $focus1->column_fields['assigned_user_id'] = $current_user->id;
                            $focus1->save("cbMap");
                            
                            $focus = new BusinessActions();
                            $focus->column_fields['reference'] = 'EtherCalc';
                            $focus->column_fields['moduleactions'] = 'Spreadsheets';
                            $focus->column_fields['actions_type']='Launch a script';
                            $focus->column_fields['elementtype_action'] = 'DETAILVIEWBASIC';
                            $focus->column_fields['output_type']='AlertReload';
                            $focus->column_fields['iomap']=$focus1->id;
                            $focus->column_fields['script_name'] = 'modules/BusinessActions/scripts/createspreadsheetdv';
                            $focus->column_fields['actions_status'] = 'Active';
                            $focus->column_fields['assigned_user_id'] = $current_user->id;
                            $focus->save("BusinessActions");
			$this->sendMsg('Changeset '.get_class($this).' applied!');
			$this->markApplied();
		}
		$this->finishExecution();
	}

}
