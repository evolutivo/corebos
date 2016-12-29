<?php
class addconditionfieldtoaction extends cbupdaterWorker {
	function applyChange() {
		global $adb;
		if ($this->hasError()) $this->sendError();
		if ($this->isApplied()) {
			$this->sendMsg('Changeset '.get_class($this).' already applied!');
		} else {
			$modname = 'BusinessActions';
			$module = Vtiger_Module::getInstance($modname);
                        $field = Vtiger_Field::getInstance('actobrnew',$module);
                        if (!$field) {
                        $block = Vtiger_Block::getInstance('LBL_BUSINESSACTIONS_INFORMATION', $module);
                        $field_acc=new Vtiger_Field();
                        $field_acc->name='actobrnew';
                        $field_acc->label='BR Condition';
                        $field_acc->column = 'actobrnew';
                        $field_acc->columntype = 'VARCHAR(255)';
                        $field_acc->uitype=10;
                        $field_acc->typeofdata = 'V~O';
                        $block->addField($field_acc); 
                        $field_acc->setRelatedModules(Array("BusinessRules"));
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
