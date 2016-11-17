<?php
class phabricatoridfield extends cbupdaterWorker {
	function applyChange() {
		global $adb;
		if ($this->hasError()) $this->sendError();
		if ($this->isApplied()) {
			$this->sendMsg('Changeset '.get_class($this).' already applied!');
		} else {
			global $adb;
			$modname = 'Project';
			$module = Vtiger_Module::getInstance($modname);
                        $block = Vtiger_Block::getInstance('LBL_PROJECT_INFORMATION', $module);

                    $field = Vtiger_Field::getInstance('phabricatorid',$module);
                    if (!$field) {
                        $field1 = new Vtiger_Field();
                        $field1->name = 'phabricatorid';
                        $field1->label= 'Phabricator Id';
                        $field1->column = 'phabricatorid';
                        $field1->columntype = 'int(11)';
                        $field1->uitype = 2;
                        $field1->typeofdata = 'I~O';
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
