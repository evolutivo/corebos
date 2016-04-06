<?php
class addMapsCPortalMenuStructure extends cbupdaterWorker {
	function applyChange() {
		global $adb;
		if ($this->hasError()) $this->sendError();
		if ($this->isApplied()) {
			$this->sendMsg('Changeset '.get_class($this).' already applied!');
		} else {
			global $adb,$log, $current_user;		
                        include_once('data/CRMEntity.php');
                        require_once('include/utils/utils.php');
                        require_once('include/database/PearDatabase.php');
                        require_once("modules/Users/Users.php");
                        require_once('modules/BusinessRules/BusinessRules.php');
                        require_once('modules/cbMap/cbMap.php');

                        $focusMap = new cbMap();
                        $focusMap->column_fields['mapname'] = 'Menu structure';
                        $focusMap->column_fields['content'] = '<?xml version="1.0"?>
<map>
  <menus>
<profile>4</profile>
    <parenttab>
<label>Gestione</label>
 <name>Cases</name>
 <name>KnowledgeBase</name>
<name>Messages</name>
<name>Documents</name>
  </parenttab>
  </menus>
</map>';
                        $focusMap->column_fields['maptype'] = 'MENUSTRUCTURE';
                        $focusMap->column_fields['assigned_user_id'] = 1;
                        $focusMap->mode = "";
                        $focusMap->id = "";
                        $focusMap->save("cbMap");
                        
                        $focus = new BusinessRules();
                        $focus->column_fields['businessrules_name'] = 'Menu structure for Portal';
                        $focus->column_fields['module_rules'] = 'Contacts';
                        $focus->column_fields['linktomap'] = $focusMap->id;
                        $focus->column_fields['assigned_user_id'] = 1;
                        $focus->mode = "";
                        $focus->id = "";
                        $focus->save("BusinessRules");
                            
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