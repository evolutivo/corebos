<?php
class addFieldsCbMap extends cbupdaterWorker {
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

                    $field = Vtiger_Field::getInstance('description',$module);
                    if (!$field) {
                        $field1 = new Vtiger_Field();
                        $field1->name = 'description';
                        $field1->label= 'Description';
                        $field1->column = 'description';
                        $field1->columntype = 'VARCHAR(255)';
                        $field1->sequence = 2;
                        $field1->uitype = 2;
                        $field1->typeofdata = 'V~M';
                        $field1->displaytype = 1;
                        $field1->presence = 0;
                        $block->addField($field1);
                    }
                       
                    $field = Vtiger_Field::getInstance('selectedoperators',$module);
                    if (!$field) {
                        $field1 = new Vtiger_Field();
                        $field1->name = 'selectedoperators';
                        $field1->label= 'Selected Operators';
                        $field1->column = 'selectedoperators';
                        $field1->columntype = 'VARCHAR(255)';
                        $field1->sequence = 2;
                        $field1->uitype = 1;
                        $field1->typeofdata = 'V~O';
                        $field1->displaytype = 1;
                        $field1->presence = 0;
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
