<?php
class add_actions extends cbupdaterWorker {
	
	function applyChange() {
		global $adb;
		if ($this->hasError()) $this->sendError();
		if ($this->isApplied()) {
			$this->sendMsg('Changeset '.get_class($this).' already applied!');
		} else {
			
			
			$actions = array(
				
				"Accounts Add To Do"=>array(
					"module" => "Accounts",
					"icon" => "themes/images/AddToDo.gif",
					"url" => 'index.php?module=Calendar4You&action=EventEditView&return_module=$MODULE$&return_action=DetailView&activity_mode=Task&return_id=$RECORD$&parent_id=$RECORD$',
					"label" => "LBL_ADD_TO_DO",
					"sequence" => "0",
					"action_type" => "DETAILVIEWBASIC",

				),
				"Accounts Add Event" => array(
					"module" => "Accounts",
					"icon" => "themes/images/AddEvent.gif",
					"url" => 'index.php?module=Calendar4You&action=EventEditView&return_module=$MODULE$&return_action=DetailView&activity_mode=Events&return_id=$RECORD$&parent_id=$RECORD$',
					"label" => "LBL_ADD_EVENT",
					"sequence" => "0",
					"action_type" => "DETAILVIEWBASIC",					
				),

				"Leads Add To DO"=>array(
					"module" => "Leads",
					"icon" => "themes/images/AddToDo.gif",
					"url" => 'index.php?module=Calendar4You&action=EventEditView&return_module=$MODULE$&return_action=DetailView&activity_mode=Task&return_id=$RECORD$&parent_id=$RECORD$',
					"label" => "LBL_ADD_TO_DO",
					"sequence" => "0",
					"action_type" => "DETAILVIEWBASIC",
				),

				"Leads Add Event" => array(
					"module" => "Leads",
					"icon" => "themes/images/AddEvent.gif",
					"url" => 'index.php?module=Calendar4You&action=EventEditView&return_module=$MODULE$&return_action=DetailView&activity_mode=Events&return_id=$RECORD$&parent_id=$RECORD$',
					"label" => "LBL_ADD_EVENT",
					"sequence" => "0",
					"action_type" => "DETAILVIEWBASIC",					
				),

				"Contacts Add To DO"=>array(
					"module" => "Contacts",
					"icon" => "themes/images/AddToDo.gif",
					"url" => 'index.php?module=Calendar4You&action=EventEditView&return_module=$MODULE$&return_action=DetailView&activity_mode=Task&return_id=$RECORD$&parent_id=$RECORD$',
					"label" => "LBL_ADD_TO_DO",
					"sequence" => "0",
					"action_type" => "DETAILVIEWBASIC",
				),

				"Contacts Add Event" => array(
					"module" => "Contacts",
					"icon" => "themes/images/AddEvent.gif",
					"url" => 'index.php?module=Calendar4You&action=EventEditView&return_module=$MODULE$&return_action=DetailView&activity_mode=Events&return_id=$RECORD$&parent_id=$RECORD$',
					"label" => "LBL_ADD_EVENT",
					"sequence" => "0",
					"action_type" => "DETAILVIEWBASIC",					
				),

				"Potentials " => array(
					"module" => "Potentials",
					"icon" => "themes/images/actionGenerateInvoice.gif",
					"url" => 'index.php?return_module={$MODULE}&return_action=DetailView&return_id=$RECORD$&convertmode=potentoinvoice&module=Invoice&action=EditView&account_id=',
					"label" => "LBL_CREATE_INVOICE",
					"sequence" => "0",
					"action_type" => "DETAILVIEWBASIC",					
				),


			);

			include_once('data/CRMEntity.php');
			require_once('include/utils/utils.php');
			require_once('include/database/PearDatabase.php');
			require_once("modules/Users/Users.php");
			require_once('modules/BusinessActions/BusinessActions.php');
			global $adb, $log, $current_user;
			$current_user = new Users();
			$current_user->retrieveCurrentUserInfoFromFile(1);

			foreach ($actions as $key => $value) {
				$focus = new BusinessActions();
				$focus->column_fields['reference'] = $value['label'];
				$focus->column_fields['moduleactions'] = $value['module'];
				$focus->column_fields['elementtype_action'] = $value['action_type'];
				$focus->column_fields['image_action'] = $value['icon'];
				$focus->column_fields['script_name'] = '';
				$focus->column_fields['linkurl'] = html_entity_decode($value['url']);
				$focus->column_fields['linkicon'] = $value['icon'];
				$focus->column_fields['sequence'] = $value['sequence'];
				$focus->column_fields['actions_status'] = 'Active';
				$focus->column_fields['assigned_user_id'] = $current_user->id;
				$focus->mode = "";
				$focus->id = "";
				$focus->save("BusinessActions");
			}


			//Add missing icons
			$this->ExecuteQuery("UPDATE vtiger_businessactions SET linkicon='themes/images/sendmail.png' WHERE reference='Send SMS'");

			$this->sendMsg('Changeset '.get_class($this).' applied!');
			$this->markApplied();
		}
		$this->finishExecution();
	}
}


?>


