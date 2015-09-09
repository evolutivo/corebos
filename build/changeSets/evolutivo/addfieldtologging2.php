<?php


class addfieldtologging2 extends cbupdaterWorker {
	
	function applyChange() {
		if ($this->hasError()) $this->sendError();
		if ($this->isApplied()) {
			$this->sendMsg('Changeset '.get_class($this).' already applied!');
		} else {
			global $adb;
                        $this->ExecuteQuery("ALTER TABLE  `vtiger_loggingconfiguration` ADD  `queryelastic` text NOT NULL ");
		        $this->ExecuteQuery("ALTER TABLE  `vtiger_loggingconfiguration` ADD  `relmodules` text NOT NULL ");
			$this->ExecuteQuery("ALTER TABLE  `vtiger_loggingconfiguration` ADD  `indextype` VARCHAR( 250 ) NOT NULL ");
		        $this->ExecuteQuery("ALTER TABLE  `vtiger_loggingconfiguration` ADD  `fieldselastic` text NOT NULL ");
			$this->sendMsg('Changeset '.get_class($this).' applied!');
			$this->markApplied();
		}
		$this->finishExecution();
	}
	
}
?>
