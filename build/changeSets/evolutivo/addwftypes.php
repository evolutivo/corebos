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

class addwftypes extends cbupdaterWorker {
	
	function applyChange() {
		if ($this->hasError()) $this->sendError();
		if ($this->isApplied()) {
			$this->sendMsg('Changeset '.get_class($this).' already applied!');
		} else {
		
			$this->ExecuteQuery("CREATE TABLE IF NOT EXISTS `com_vtiger_workflow_tasktypes_seq` ( `id` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
			$this->ExecuteQuery("CREATE TABLE IF NOT EXISTS `com_vtiger_workflow_tasktypes` ( `id` int(11) NOT NULL,`tasktypename` varchar(255) NOT NULL,`label` varchar(255) DEFAULT NULL, `classname` varchar(255) DEFAULT NULL, `classpath` varchar(255) DEFAULT NULL,`templatepath` varchar(255) DEFAULT NULL,`modules` varchar(500) DEFAULT NULL, `sourcemodule` varchar(255) DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
                        $this->ExecuteQuery('INSERT INTO `com_vtiger_workflow_tasktypes` (`id`, `tasktypename`, `label`, `classname`, `classpath`, `templatepath`, `modules`, `sourcemodule`) VALUES
(1, \'VTEmailTask\', \'Send Mail\', \'VTEmailTask\', \'modules/com_vtiger_workflow/tasks/VTEmailTask.inc\', \'com_vtiger_workflow/taskforms/VTEmailTask.tpl\', \'{"include":[],"exclude":[]}\', \'\'),
(2, \'VTEntityMethodTask\', \'Invoke Custom Function\', \'VTEntityMethodTask\', \'modules/com_vtiger_workflow/tasks/VTEntityMethodTask.inc\', \'com_vtiger_workflow/taskforms/VTEntityMethodTask.tpl\', \'{"include":[],"exclude":[]}\', \'\'),
(3, \'VTCreateTodoTask\', \'Create Todo\', \'VTCreateTodoTask\', \'modules/com_vtiger_workflow/tasks/VTCreateTodoTask.inc\', \'com_vtiger_workflow/taskforms/VTCreateTodoTask.tpl\', \'{"include":["Leads","Accounts","Potentials","Contacts","HelpDesk","Campaigns","Quotes","PurchaseOrder","SalesOrder","Invoice"],"exclude":["Calendar","FAQ","Events"]}\', \'\'),
(4, \'VTCreateEventTask\', \'Create Event\', \'VTCreateEventTask\', \'modules/com_vtiger_workflow/tasks/VTCreateEventTask.inc\', \'com_vtiger_workflow/taskforms/VTCreateEventTask.tpl\', \'{"include":["Leads","Accounts","Potentials","Contacts","HelpDesk","Campaigns"],"exclude":["Calendar","FAQ","Events"]}\', \'\'),
(5, \'VTUpdateFieldsTask\', \'Update Fields\', \'VTUpdateFieldsTask\', \'modules/com_vtiger_workflow/tasks/VTUpdateFieldsTask.inc\', \'com_vtiger_workflow/taskforms/VTUpdateFieldsTask.tpl\', \'{"include":[],"exclude":[]}\', \'\'),
(6, \'VTCreateEntityTask\', \'Create Entity\', \'VTCreateEntityTask\', \'modules/com_vtiger_workflow/tasks/VTCreateEntityTask.inc\', \'com_vtiger_workflow/taskforms/VTCreateEntityTask.tpl\', \'{"include":[],"exclude":[]}\', \'\'),
(7, \'VTSMSTask\', \'SMS Task\', \'VTSMSTask\', \'modules/com_vtiger_workflow/tasks/VTSMSTask.inc\', \'com_vtiger_workflow/taskforms/VTSMSTask.tpl\', \'{"include":[],"exclude":[]}\', \'SMSNotifier\'),
(8, \'CBRelateSales\', \'CBRelateSales\', \'CBRelateSales\', \'modules/com_vtiger_workflow/tasks/CBRelateSales.inc\', \'com_vtiger_workflow/taskforms/CBRelateSales.tpl\', \'{"include":["Quotes","SalesOrder","Invoice","PurchaseOrder"],"exclude":[]}\', \'\'),
(9, \'CBExecBAction\', \'Execute BAction\', \'CBExecBAction\', \'modules/com_vtiger_workflow/tasks/CBExecBAction.inc\', \'com_vtiger_workflow/taskforms/CBExecBAction.tpl\', \'{"include":[],"exclude":[]}\', \'BusinessActions\');');
                        $this->sendMsg('Changeset '.get_class($this).' applied!');
			$this->markApplied();
		}
		$this->finishExecution();
	}
	
}

?>