<?php
class addthread extends cbupdaterWorker {
	function applyChange() {
		global $adb;
		if ($this->hasError()) $this->sendError();
		if ($this->isApplied()) {
			$this->sendMsg('Changeset '.get_class($this).' already applied!');
		} else {
			global $adb;
			$modname = 'Messages';
			$module = Vtiger_Module::getInstance($modname);
                        $block = Vtiger_Block::getInstance('LBL_MESSAGES_INFORMATION', $module);

                    $field = Vtiger_Field::getInstance('thread',$module);
                    if (!$field) {
                        $field1 = new Vtiger_Field();
                        $field1->name = 'thread';
                        $field1->label= 'Thread';
                        $field1->column = 'thread';
                        $field1->columntype = 'VARCHAR(255)';
                        $field1->sequence = 2;
                        $field1->uitype = 10;
                        $field1->typeofdata = 'V~O';
                        $field1->displaytype = 1;
                        $field1->presence = 0;
                        $block->addField($field1);
                        $field1->setRelatedModules(Array("Thread"));
                    }
                    $toinstall = array('Thread');
			foreach ($toinstall as $module) {
				if ($this->isModuleInstalled($module)) {
					vtlib_toggleModuleAccess($module,true);
					$this->sendMsg("$module activated!");
				} else {
					$this->installManifestModule($module);
				}
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


