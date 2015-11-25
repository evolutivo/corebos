<?php
/*************************************************************************************************
 * Copyright 2014 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS Customizations.
* Licensed under the vtiger CRM Public License Version 1.1 (the "License"); you may not use this
* file except in compliance with the License. You can redistribute it and/or modify it
* under the terms of the License. JPL TSolucio, S.L. reserves all rights not expressly
* granted by the License. coreBOS distributed by JPL TSolucio S.L. is distributed in
* the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
* warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Unless required by
* applicable law or agreed to in writing, software distributed under the License is
* distributed on an "AS IS" BASIS, WITHOUT ANY WARRANTIES OR CONDITIONS OF ANY KIND,
* either express or implied. See the License for the specific language governing
* permissions and limitations under the License. You may obtain a copy of the License
* at <http://corebos.org/documentation/doku.php?id=en:devel:vpl11>
*************************************************************************************************/

class addmoduleBAwf extends cbupdaterWorker {
	
	function applyChange() {
		global $adb;
		if ($this->hasError()) $this->sendError();
		if ($this->isApplied()) {
			$this->sendMsg('Changeset '.get_class($this).' already applied!');
		} else {
                        global $adb;
                        $n= $adb->getUniqueID("com_vtiger_workflowtasks_entitymethod");
                        $n++;
                         $adb->query('Insert into com_vtiger_workflowtasks_entitymethod values ("'.$n.'","BusinessActions", "updateBA", "modules/BusinessActions/workflowBABlock.php", "updateBA")');
                        $wm=new VTWorkflowManager($adb);
                        $wf = $wm->newWorkflow("BusinessActions");
			$wf->description = "updateBA";
                        $wf->type = 'basic';
			//$wf->taskId ="";
			$wf->executionConditionAsLabel("ON_EVERY_SAVE");
			$wm->save($wf);
                        $w=$adb->query("select * from com_vtiger_workflows where summary='updateBA'");
                        $work=$adb->query_result($w,0,"workflow_id");

                        $tm = new VTTaskManager($adb);

			$taskType ="VTEntityMethodTask" ;
			$workflowId =$work;
			$task = $tm->createTask($taskType, $workflowId);
                        $task->summary ="updateBA";
                        $task->active=true;
                        $task->methodName ="updateBA";
                        $task->subject="updateBA";
                        $tm->saveTask($task);
			}
			
			$this->sendMsg('Changeset '.get_class($this).' applied!');
			$this->markApplied();
			
		
		$this->finishExecution();
	}
	
	function undoChange() {
		if ($this->hasError()) $this->sendError();
		if ($this->isApplied()) {
			global $adb;
			$wfrs = $adb->query("SELECT workflow_id FROM com_vtiger_workflows WHERE summary='updateBA'");
			if ($wfrs and $adb->num_rows($wfrs)==1) {
				$wfid = $adb->query_result($wfrs,0,0);
				$this->deleteWorkflow($wfid);
				$this->sendMsg('Workflow deleted!');
			}
			$this->sendMsg('Changeset '.get_class($this).' undone!');
			$this->markUndone();
		} else {
			$this->sendMsg('Changeset '.get_class($this).' not applied!');
		}
		$this->finishExecution();
	}
	
}