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

class BusinessRules extends CRMEntity {
	var $db, $log; // Used in class functions of CRMEntity

	var $table_name = 'vtiger_businessrules';
	var $table_index= 'businessrulesid';
	var $column_fields = Array();

	/** Indicator if this is a custom module or standard module */
	var $IsCustomModule = true;
	var $HasDirectImageField = false;
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('vtiger_businessrulescf', 'businessrulesid');
	// Uncomment the line below to support custom field columns on related lists
	// var $related_tables = Array('vtiger_payslipcf'=>array('payslipid','vtiger_payslip', 'payslipid'));

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('vtiger_crmentity', 'vtiger_businessrules', 'vtiger_businessrulescf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'vtiger_crmentity' => 'crmid',
		'vtiger_businessrules'   => 'businessrulesid',
	    'vtiger_businessrulescf' => 'businessrulesid');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array (
		/* Format: Field Label => Array(tablename => columnname) */
		// tablename should not have prefix 'vtiger_'
		'BusinessRules Name'=> Array('businessrules'=> 'businessrules_name'),
		'Assigned To' => Array('crmentity'=> 'smownerid')
	);
	var $list_fields_name = Array(
		/* Format: Field Label => fieldname */
		'BusinessRules Name'=> 'businessrules_name',
		'Assigned To' => 'assigned_user_id'
	);

	// Make the field link to detail view from list view (Fieldname)
	var $list_link_field = 'businessrules_name';

	// For Popup listview and UI type support
	var $search_fields = Array(
		/* Format: Field Label => Array(tablename => columnname) */
		// tablename should not have prefix 'vtiger_'
		'BusinessRules Name'=> Array('businessrules'=> 'businessrules_name')
	);
	var $search_fields_name = Array(
		/* Format: Field Label => fieldname */
		'BusinessRules Name'=> 'businessrules_name'
	);

	// For Popup window record selection
	var $popup_fields = Array('businessrules_name');

	// Placeholder for sort fields - All the fields will be initialized for Sorting through initSortFields
	var $sortby_fields = Array();

	// For Alphabetical search
	var $def_basicsearch_col = 'businessrules_name';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'businessrules_name';

	// Required Information for enabling Import feature
	var $required_fields = Array('businessrules_name'=>1);

	// Callback function list during Importing
	var $special_functions = Array('set_import_assigned_user');

	var $default_order_by = 'businessrules_name';
	var $default_sort_order='ASC';
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vtiger_field.fieldname values.
	var $mandatory_fields = Array('createdtime', 'modifiedtime', 'businessrules_name');

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

        function get_permitted_profiles()
        {
           $roles_array=explode(',',$this->column_fields["busrule_roles"]);
           return $roles_array;
        }
        function isProfilePermitted() {
        global $current_user;
        $roles_array = explode(' |##| ', $this->column_fields["busrule_roles"]);
        $currentprofiles = getUserProfile($current_user->id);
        $allowed = false;
        //while (!$allowed) {
            $role=$current_user->roleid;
            if (in_array($role, $roles_array)) {
                $allowed = true;
                return $allowed;
            }
        //}
        return $allowed;
    }
    function apply_bussinessrules(){
        global $adb,$log;
        $mapid=$this->column_fields['linktomap'];
        if($mapid!='' && $mapid!=0){
            require_once ("modules/cbMap/cbMap.php");
            $map_focus = CRMEntity::getInstance("cbMap");
            $map_focus->retrieve_entity_info($mapid, "cbMap");
            $result=$map_focus->read_map();

            return $result;
          
        }
    }
    function isRolePermitted() {
        global $current_user;
        $roles_array = $this->column_fields["br_allowedroles"];
        $currentRole = $current_user->roleid;
        $allowed = false;
        if (!empty($roles_array)) {
            $roles_array = explode(',', $this->column_fields["br_allowedroles"]);
            if (in_array($currentRole, $roles_array)) {
                $allowed = true;
            } else {
                $allowed = false;
            }
        } else {
            $allowed = true;
        }
        return $allowed;
    }
     function executeSQLQuery($record) {
        global $adb,$current_user;
        $params = array();
        $allelements = array("CURRENT_USER" => $current_user->id, "CURRENT_RECORD" => $record,"CURRENT_RECORO" => $record);
        $mapid = $this->column_fields['linktomap'];
        if (!empty($mapid)) {
            require_once ("modules/cbMap/cbMap.php");
            $mapfocus = CRMEntity::getInstance("cbMap");
            $mapfocus->retrieve_entity_info($mapid, "cbMap");
            $mapINFO = $mapfocus->getMapSQLCondition();
            $sqlQuery = $mapINFO['sqlString'];
            $sqlCondition = $mapINFO['sqlCondition'];
            $this->log->debug("condition");
            $this->log->debug($sqlCondition);
            foreach ($allelements as $elem => $value) {
                $pos_el = strpos($sqlQuery, $elem);
                if ($pos_el) {
                    $sqlQuery = str_replace($elem, " ? ", $sqlQuery);
                    array_push($params, $value);
                }
            }
            $res_logic = $adb->pquery($sqlQuery, $params);
            if (isset($sqlCondition)) {
                $condRes = $adb->query_result($res_logic, 0, 0);
                $this->log->debug("This is the map condition");
                $this->log->debug($condRes);
                if ($condRes == $sqlCondition) {
                    return true;
                } else {
                    return false;
                }
            } else {
                if ($adb->num_rows($res_logic) > 0) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        else{
            return true;
        }
    }

    function executeSQLQuery2($therecid) {
    	//new parameter for put_ actions in sap
        global $adb, $current_user;
    
        $params = array();
        $allelements = array("CURRENT_USER" => $current_user->id, "CURRENT_RECORD" => $therecid,"CURRENT_RECORO" => $therecid);
        $mapid = $this->column_fields['linktomap'];
        if (!empty($mapid)) {
            require_once ("modules/cbMap/cbMap.php");
            $mapfocus = CRMEntity::getInstance("cbMap");
            $mapfocus->retrieve_entity_info($mapid, "cbMap");
            $mapINFO = $mapfocus->getMapSQLCondition();
            $sqlQuery = $mapINFO['sqlString'];
            $sqlCondition = $mapINFO['sqlCondition'];
            $this->log->debug("condition");
            $this->log->debug($sqlCondition);
		
            foreach ($allelements as $elem => $value) {
                $pos_el = strpos($sqlQuery, $elem);
                if ($pos_el) {
                    $sqlQuery = str_replace($elem, " ? ", $sqlQuery);
                    array_push($params, $value);
                }
            }
            $res_logic = $adb->pquery($sqlQuery, $params);
            if (isset($sqlCondition)) {
                $condRes = $adb->query_result($res_logic, 0, 0);
                $this->log->debug("This is the map condition");
                $this->log->debug($condRes);
                if ($condRes == $sqlCondition) {
                    return true;
                } else {
                    return false;
                }
            } else {
                if ($adb->num_rows($res_logic) > 0) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        else{
            return true;
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
