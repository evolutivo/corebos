<?php
class add_fields_to_contacts extends cbupdaterWorker {
	
	function applyChange() {
		global $adb;
		if ($this->hasError()) $this->sendError();
		if ($this->isApplied()) {
			$this->sendMsg('Changeset '.get_class($this).' already applied!');
		} else {
			
			$this->ExecuteQuery("ALTER TABLE vtiger_contactdetails ADD api_password VARCHAR(255)");
			$this->ExecuteQuery("ALTER TABLE vtiger_contactdetails ADD api_communication VARCHAR(3)");
			$this->ExecuteQuery("INSERT INTO vtiger_field VALUES(4,'','api_communication','vtiger_contactdetails',1,56,'contact_api_communcation','Contact API communication',1,2,'',3,'',6,1,'C~O',1,NULL,'BAS',0,NULL)");

			$this->sendMsg('Changeset '.get_class($this).' applied!');
			$this->markApplied();
		}
		$this->finishExecution();
	}
}

?>