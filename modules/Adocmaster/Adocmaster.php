<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
require_once('data/CRMEntity.php');
require_once('data/Tracker.php');

class Adocmaster extends CRMEntity {
var $db, $log; 

var $table_name = 'vtiger_adocmaster'; 
var $table_index= 'adocmasterid'; 
var $column_fields = Array();

var $IsCustomModule = true;
var $HasDirectImageField = false;
var $customFieldTable = Array('vtiger_adocmastercf', 'adocmasterid');
var $tab_name = Array('vtiger_crmentity', 'vtiger_adocmaster', 'vtiger_adocmastercf');

var $tab_name_index = Array( 
'vtiger_crmentity' => 'crmid', 
'vtiger_adocmaster'   => 'adocmasterid', 
'vtiger_adocmastercf' => 'adocmasterid');

var $list_fields = Array (
'AdocmasterNo'=> Array('adocmaster'=> 'adocmasterno'),
'Adocmaster Name'=> Array('adocmaster'=> 'adocmastername'),
'Doc Nr'=> Array('adocmaster'=> 'nrdoc'),
'Assigned To' => Array('crmentity'=> 'smownerid'));

var $list_fields_name = Array(
'AdocmasterNo'=> 'adocmasterno',
'Adocmaster Name'=> 'adocmastername',
'Doc Nr'=> 'nrdoc',
'Assigned To' => 'assigned_user_id');

var $list_link_field = 'adocmasterno';
var $search_fields = Array( 
'AdocmasterNo'=> Array('adocmaster'=> 'adocmasterno'),
'Adocmaster Name'=> Array('adocmaster'=> 'adocmastername'),
'Doc Nr'=> Array('adocmaster'=> 'nrdoc'),
);
var $search_fields_name = Array( 
'AdocmasterNo'=> 'adocmasterno',
'Adocmaster Name'=> 'adocmastername',
'Doc Nr'=> 'nrdoc',
); 
var $popup_fields = Array('adocmasterno');
var $sortby_fields = Array();
var $def_basicsearch_col = 'adocmasterno'; 
var $def_detailview_recname = 'adocmasterno';
var $required_fields = Array('adocmastername'=>1);
var $special_functions = Array('set_import_assigned_user'); 
var $default_order_by = 'adocmasterno'; 
var $default_sort_order='ASC'; 
var $mandatory_fields = Array('createdtime', 'modifiedtime','adocmastername'); 
 


	function save_module($module) {
		if ($this->HasDirectImageField) {
			$this->insertIntoAttachment($this->id,$module);
		}
	}

	/**
	 * Apply security restriction (sharing privilege) query part for List view.
	 */
	function getListViewSecurityParameter($module) {
		global $current_user;
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');

		$sec_query = '';
		$tabid = getTabid($module);

		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1
			&& $defaultOrgSharingPermission[$tabid] == 3) {

				$sec_query .= " AND (vtiger_crmentity.smownerid in($current_user->id) OR vtiger_crmentity.smownerid IN
					(
						SELECT vtiger_user2role.userid FROM vtiger_user2role
						INNER JOIN vtiger_users ON vtiger_users.id=vtiger_user2role.userid
						INNER JOIN vtiger_role ON vtiger_role.roleid=vtiger_user2role.roleid
						WHERE vtiger_role.parentrole LIKE '".$current_user_parent_role_seq."::%'
					)
					OR vtiger_crmentity.smownerid IN
					(
						SELECT shareduserid FROM vtiger_tmp_read_user_sharing_per
						WHERE userid=".$current_user->id." AND tabid=".$tabid."
					)
					OR (";

					// Build the query based on the group association of current user.
					if(sizeof($current_user_groups) > 0) {
						$sec_query .= " vtiger_groups.groupid IN (". implode(",", $current_user_groups) .") OR ";
					}
					$sec_query .= " vtiger_groups.groupid IN
						(
							SELECT vtiger_tmp_read_group_sharing_per.sharedgroupid
							FROM vtiger_tmp_read_group_sharing_per
							WHERE userid=".$current_user->id." and tabid=".$tabid."
						)";
				$sec_query .= ")
				)";
		}
		return $sec_query;
	}

	/**
	 * Invoked when special actions are performed on the module.
	 * @param String Module name
	 * @param String Event Type (module.postinstall, module.disabled, module.enabled, module.preuninstall)
	 */
	function vtlib_handler($modulename, $event_type) {
		if($event_type == 'module.postinstall') {
			// TODO Handle post installation actions
			$this->setModuleSeqNumber('configure', $modulename, $modulename.'-', '0000001');
		} else if($event_type == 'module.disabled') {
			// TODO Handle actions when this module is disabled.
		} else if($event_type == 'module.enabled') {
			// TODO Handle actions when this module is enabled.
		} else if($event_type == 'module.preuninstall') {
			// TODO Handle actions when this module is about to be deleted.
		} else if($event_type == 'module.preupdate') {
			// TODO Handle actions before this module is updated.
		} else if($event_type == 'module.postupdate') {
			// TODO Handle actions after this module is updated.
		}
	}

	/**
	 * Handle saving related module information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	// function save_related_module($module, $crmid, $with_module, $with_crmid) { }

	/**
	 * Handle deleting related module information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	//function delete_related_module($module, $crmid, $with_module, $with_crmid) { }

	/**
	 * Handle getting related list information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	//function get_related_list($id, $cur_tab_id, $rel_tab_id, $actions=false) { }

	/**
	 * Handle getting dependents list information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	//function get_dependents_list($id, $cur_tab_id, $rel_tab_id, $actions=false) { }
}
?>
