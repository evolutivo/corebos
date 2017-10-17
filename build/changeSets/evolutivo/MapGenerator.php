<?php
/**
 * Created by Eclips Php Oxygen.
 * User: edmondi kacaj
 * Date: 17/Oct/2017
 * Time: 12:45 PM
 */
class MapGenerator extends cbupdaterWorker {
    
    function applyChange() {
        if ($this->hasError()) $this->sendError();
        if ($this->isApplied()) {
            $this->sendMsg('Changeset '.get_class($this).' already applied!');
        } else {
            $toinstall = array('MapGenerator');
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
        if ($this->hasError()) $this->sendError();
        if ($this->isApplied()) {
            vtlib_toggleModuleAccess('MapGenerator',false);
            $this->sendMsg('MapGenerator deactivated!');
            $this->markUndone(false);
            $this->sendMsg('Changeset '.get_class($this).' undone!');
        } else {
            $this->sendMsg('Changeset '.get_class($this).' not applied, it cannot be undone!');
        }
        $this->finishExecution();
    }
    
}