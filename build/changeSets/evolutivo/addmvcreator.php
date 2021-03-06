<?php
/**
 * Created by PhpStorm.
 * User: edmondi kacaj
 * Date: 5/2/2017
 * Time: 5:25 PM
 */
class addmvcreator extends cbupdaterWorker {

    function applyChange() {
        if ($this->hasError()) $this->sendError();
        if ($this->isApplied()) {
            $this->sendMsg('Changeset '.get_class($this).' already applied!');
        } else {
            $toinstall = array('MVCreator');
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
            vtlib_toggleModuleAccess('MVCreator',false);
            $this->sendMsg('addmvcreator deactivated!');
            $this->markUndone(false);
            $this->sendMsg('Changeset '.get_class($this).' undone!');
        } else {
            $this->sendMsg('Changeset '.get_class($this).' not applied, it cannot be undone!');
        }
        $this->finishExecution();
    }

}