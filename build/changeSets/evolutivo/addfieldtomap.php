<?php
class addfieldtomap extends cbupdaterWorker {
	function applyChange() {
		global $adb;
		if ($this->hasError()) $this->sendError();
		if ($this->isApplied()) {
			$this->sendMsg('Changeset '.get_class($this).' already applied!');
		} else {
			global $adb;
			$modname = 'cbMap';
			$module = Vtiger_Module::getInstance($modname);
                        $block = Vtiger_Block::getInstance('LBL_MAP_INFORMATION', $module);
                        $field1 = new Vtiger_Field();
                        $field1->name = 'selected_fields';
                        $field1->label= 'Selected Fields';
                        $field1->column = 'selected_fields';
                        $field1->columntype = 'TEXT';
                        $field1->sequence = 2;
                        $field1->uitype = 19;
                        $field1->typeofdata = 'V~M';
                        $field1->displaytype = 1;
                        $field1->presence = 0;
                        $block->addField($field1);
                        
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