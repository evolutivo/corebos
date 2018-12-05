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

class addFieldsToBAconvertlink extends cbupdaterWorker {
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
                        $field_acc->name='linkicon';
                        $field_acc->label='Action Image';
                        $field_acc->column = 'linkicon';
                        $field_acc->columntype = 'VARCHAR(255)';
                        $field_acc->uitype=69;
                        $field_acc->typeofdata = 'V~O';
                        $block->addField($field_acc);
                        
                        $field_acc=new Vtiger_Field();
                        $field_acc->name='linkurl';
                        $field_acc->label='Action Function';
                        $field_acc->column = 'linkurl';
                        $field_acc->columntype = 'VARCHAR(255)';
                        $field_acc->uitype=1;
                        $field_acc->typeofdata = 'V~O';
                        $block->addField($field_acc);
                        
                        $field_acc=new Vtiger_Field();
                        $field_acc->name='sequence';
                        $field_acc->label='Sequence';
                        $field_acc->column = 'sequence';
                        $field_acc->columntype = 'VARCHAR(255)';
                        $field_acc->uitype=1;
                        $field_acc->typeofdata = 'V~O';
                        $block->addField($field_acc);
                        
                        $field_acc=new Vtiger_Field();
                        $field_acc->name='isnode';
                        $field_acc->label='Node Exec';
                        $field_acc->column = 'isnode';
                        $field_acc->columntype = 'VARCHAR(255)';
                        $field_acc->uitype=56;
                        $field_acc->typeofdata = 'V~O';
                        $block->addField($field_acc);
                        
                        $adb->query(" CREATE TABLE IF NOT EXISTS `vtiger_actions_block` (
                                      `block_id` int(11) NOT NULL AUTO_INCREMENT,
                                      `block_name` varchar(100) NOT NULL,
                                      `module_id` varchar(100) DEFAULT NULL,
                                      PRIMARY KEY (`block_id`)
                                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");
                        
                        
                    include_once('data/CRMEntity.php');
                    require_once('include/utils/utils.php');
                    require_once('include/database/PearDatabase.php');
                    require_once("modules/Users/Users.php");
                    require_once('modules/BusinessActions/BusinessActions.php');
                    global $adb, $log, $current_user;
                    $current_user = new Users();
                    $current_user->retrieveCurrentUserInfoFromFile(1);

                    $linksQuery = $adb->query("SELECT * FROM vtiger_links"
                            . " order by sequence");
                    $cnt=0;
                    while ($linksQuery && $row = $adb->fetch_array($linksQuery)) {
                        $tabid = $row['tabid'];
                        $linktype = $row['linktype'];
                        $linklabel = $row['linklabel'];
                        $linkurl = $row['linkurl'];
                        $linkicon = $row['linkicon'];
                        $sequence = $row['sequence'];
                    //    if ($linktype == "HEADERSCRIPT" || $linktype == "HEADERCSS" || $linktype == "DETAILVIEWWIDGET") {
                            $modulename = getTabname($tabid);

                            $focus = new BusinessActions();
                            $focus->column_fields['reference'] = $linklabel;
                            $focus->column_fields['moduleactions'] = $modulename;
                            $focus->column_fields['elementtype_action'] = $linktype;
                            $focus->column_fields['image_action'] = $linkicon;
                            $focus->column_fields['script_name'] = '';
                            $focus->column_fields['linkurl'] = html_entity_decode($linkurl);
                            $focus->column_fields['linkicon'] = $linkicon;
                            if($linktype=='HEADERSCRIPT'){
                                $focus->column_fields['sequence'] = $cnt;
                                $cnt++;
                            }
                            else{
                                $focus->column_fields['sequence'] = $sequence;
                            }
                            $focus->column_fields['actions_status'] = 'Active';
                            $focus->column_fields['assigned_user_id'] = $current_user->id;
                            $focus->mode = "";
                            $focus->id = "";
                            $focus->save("BusinessActions");
                    //    }
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