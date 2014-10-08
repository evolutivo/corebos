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
 *  Module       : EntittyLog
 *  Version      : 5.4.0
 *  Author       : OpenCubed
 *************************************************************************************************/
require_once('data/CRMEntity.php');
//require_once('data/Tracker.php');

class GlobalSettings extends CRMEntity {
	var $db, $log; // Used in class functions of CRMEntity

	var $table_name = 'vtiger_globalsettings';
	var $table_index= 'globalsettingsid';
	var $column_fields = Array();

	/** Indicator if this is a custom module or standard module */
	var $IsCustomModule = true;

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('vtiger_globalsettingscf', 'globalsettingsid');

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('vtiger_crmentity', 'vtiger_globalsettings', 'vtiger_globalsettingscf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'vtiger_crmentity' => 'crmid',
		'vtiger_globalsettings'   => 'globalsettingsid',
                'vtiger_globalsettingscf' => 'globalsettingsid');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array (
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'calendar_display'=> Array('globalsettings', 'calendar_display'),
                'world_clock_display'=>Array('globalsettings','world_clock_display'),                
                'calculator_display'=>Array('globalsettings','calculator_display'),
                'list_max_entries_per_page'=>Array('globalsettings','list_max_entries_per_page'),
                'limitpage_navigation'=>Array('globalsettings','limitpage_navigation'),
                'history_max_viewed'=>Array('globalsettings','history_max_viewed'),
             'default_module'=>Array('globalsettings','default_module'),
             'default_action'=>Array('globalsettings','default_action'),
             'default_theme'=>Array('globalsettings','default_theme'), 
             'currency_name'=>Array('globalsettings','currency_name'),
             'default_charset'=>Array('globalsettings','default_charset'),
             'listview_max_textlength'=>Array('globalsettings','listview_max_textlength'),
             'vtiger_current_version'=>Array('globalsettings','vtiger_current_version'),
             'helpdesk_support_email_id'=>Array('globalsettings','helpdesk_support_email_id'),
             'helpdesk_support_name'=>Array('globalsettings','helpdesk_support_name'),
             'corebos_app_name'=>Array('globalsettings','corebos_app_name'),
            'upload_badext'=>Array('globalsettings','upload_badext'),
            'maxWebServiceSessionLifeSpan'=>Array('globalsettings','maxWebServiceSessionLifeSpan'),
            'maxWebServiceSessionIdleTime'=>Array('globalsettings',',maxWebServiceSessionIdleTime'),
             'upload_maxsize'=>Array('globalsettings',',upload_maxsize'),
            'allow_exports'=>Array('globalsettings','allow_exports'),
             'minimum_cron_frequency'=>Array('globalsettings','minimum_cron_frequency'),
            'default_timezone'=>Array('globalsettings','default_timezone'),
            'default_language'=>Array('globalsettings','default_language'),
            'import_dir'=>Array('globalsettings','import_dir'),
            'helpdesk_support_email_reply_id'=>array('globalsettings','helpdesk_support_email_reply_id'),
            'upload_dir'=>Array('globalsettings','upload_dir'),
            'corebos_app_url'=> Array('globalsettings','corebos_app_url'),
            'tmp_dir'=>Array('globalsettings','tmp_dir'),
            'cache_dir'=>Array('globalsettings','cache_dir'),
            'site_url'=>Array('globalsettings','site_url'),
            'root_directory'=>Array('globalsettings','root_directory'),
            'corebos_app_version'=>Array('globalsettings','corebos_app_version') ,
            
	);
	var $list_fields_name = Array(
		/* Format: Field Label => fieldname */
		'calendar_display'=> Array('globalsettings', 'calendar_display'),
                'world_clock_display'=>Array('globalsettings','world_clock_display'),                
                'calculator_display'=>Array('globalsettings','calculator_display') ,
                'list_max_entries_per_page'=>Array('globalsettings','list_max_entries_per_page'),
                'limitpage_navigation'=>Array('globalsettings','limitpage_navigation'),
                'history_max_viewed'=>Array('globalsettings','history_max_viewed'),
             'default_module'=>Array('globalsettings','default_module'),
             'default_action'=>Array('globalsettings','default_action'),
             'default_theme'=>Array('globalsettings','default_theme'), 
             'currency_name'=>Array('globalsettings','currency_name'),
             'default_charset'=>Array('globalsettings','default_charset'),
             'listview_max_textlength'=>Array('globalsettings','listview_max_textlength'),
             'vtiger_current_version'=>Array('globalsettings','vtiger_current_version'),
             'helpdesk_support_email_id'=>Array('globalsettings','helpdesk_support_email_id'),
             'helpdesk_support_name'=>Array('globalsettings','helpdesk_support_name'),
             'corebos_app_name'=>Array('globalsettings','corebos_app_name'),
            'upload_badext'=>Array('globalsettings','upload_badext'),
            'maxwebservicesessionlifespan'=>Array('globalsettings','maxwebservicesessionlifespan'),
            'maxwebservicesessionidletime'=>Array('globalsettings',',maxwebservicesessionidletime'),
             'upload_maxsize'=>Array('globalsettings',',upload_maxsize'),
            'allow_exports'=>Array('globalsettings','allow_exports'),
             'minimum_cron_frequency'=>Array('globalsettings','minimum_cron_frequency'),
            'default_timezone'=>Array('globalsettings','default_timezone'),
            'default_language'=>Array('globalsettings','default_language'),
            'import_dir'=>Array('globalsettings','import_dir'),
            'upload_dir'=>Array('globalsettings','upload_dir'),
            'corebos_app_url'=> Array('globalsettings','corebos_app_url'),
            'helpdesk_support_email_reply_id'=>Array('globalsettings','helpdesk_support_email_reply_id'),
            'tmp_dir'=>Array('globalsettings','tmp_dir'),
            'cache_dir'=>Array('globalsettings','cache_dir'),
            'site_url'=>Array('globalsettings','site_url'),
            'root_directory'=>Array('globalsettings','root_directory'),
            'corebos_app_version'=>Array('globalsettings','corebos_app_version') ,
	);

	// Make the field link to detail view from list view (Fieldname)
	var $list_link_field = 'calendar_display';

	// For Popup listview and UI type support
	var $search_fields = Array(
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		);
	var $search_fields_name = Array(
		/* Format: Field Label => fieldname */
		);

	
	// Placeholder for sort fields - All the fields will be initialized for Sorting through initSortFields
	var $sortby_fields = Array();

	
        var $default_order_by = 'calendar_display';
	var $default_sort_order='ASC';
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vtiger_field.fieldname values.

	
	function __construct() {
		global $log, $currentModule;
		$this->column_fields = getColumnFields($currentModule);
		$this->db = PearDatabase::getInstance();
		$this->log = $log;
	}

	function getSortOrder() {
		global $currentModule;

		$sortorder = $this->default_sort_order;
		if($_REQUEST['sorder']) $sortorder = $this->db->sql_escape_string($_REQUEST['sorder']);
		else if($_SESSION[$currentModule.'_Sort_Order']) 
			$sortorder = $_SESSION[$currentModule.'_Sort_Order'];

		return $sortorder;
	}

	function getOrderBy() {
		global $currentModule;
		
		$use_default_order_by = '';		
		if(PerformancePrefs::getBoolean('LISTVIEW_DEFAULT_SORTING', true)) {
			$use_default_order_by = $this->default_order_by;
		}
		
		$orderby = $use_default_order_by;
		if($_REQUEST['order_by']) $orderby = $this->db->sql_escape_string($_REQUEST['order_by']);
		else if($_SESSION[$currentModule.'_Order_By'])
			$orderby = $_SESSION[$currentModule.'_Order_By'];
		return $orderby;
	}

	function save_module($module) {
	}

	/**
	 * Return query to use based on given modulename, fieldname
	 * Useful to handle specific case handling for Popup
	 */
	function getQueryByModuleField($module, $fieldname, $srcrecord, $query='') {
		// $srcrecord could be empty
	}

	/**
	 * Get list view query (send more WHERE clause condition if required)
	 */
	function getListQuery($module, $usewhere='') {
		$query = "SELECT vtiger_crmentity.*, $this->table_name.*";
		
		// Keep track of tables joined to avoid duplicates
		$joinedTables = array();

		// Select Custom Field Table Columns if present
		if(!empty($this->customFieldTable)) $query .= ", " . $this->customFieldTable[0] . ".* ";

		$query .= " FROM $this->table_name";

		$query .= "	INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = $this->table_name.$this->table_index";

		$joinedTables[] = $this->table_name;
		$joinedTables[] = 'vtiger_crmentity';
		
		// Consider custom table join as well.
		if(!empty($this->customFieldTable)) {
			$query .= " INNER JOIN ".$this->customFieldTable[0]." ON ".$this->customFieldTable[0].'.'.$this->customFieldTable[1] .
				      " = $this->table_name.$this->table_index";
			$joinedTables[] = $this->customFieldTable[0]; 
		}
		$query .= " LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid";
		$query .= " LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid";

		$joinedTables[] = 'vtiger_users';
		$joinedTables[] = 'vtiger_groups';
		
		$linkedModulesQuery = $this->db->pquery("SELECT distinct fieldname, columnname, relmodule FROM vtiger_field" .
				" INNER JOIN vtiger_fieldmodulerel ON vtiger_fieldmodulerel.fieldid = vtiger_field.fieldid" .
				" WHERE uitype='10' AND vtiger_fieldmodulerel.module=?", array($module));
		$linkedFieldsCount = $this->db->num_rows($linkedModulesQuery);
		
		for($i=0; $i<$linkedFieldsCount; $i++) {
			$related_module = $this->db->query_result($linkedModulesQuery, $i, 'relmodule');
			$fieldname = $this->db->query_result($linkedModulesQuery, $i, 'fieldname');
			$columnname = $this->db->query_result($linkedModulesQuery, $i, 'columnname');
			
			$other =  CRMEntity::getInstance($related_module);
			vtlib_setup_modulevars($related_module, $other);
			
			if(!in_array($other->table_name, $joinedTables)) {
				$query .= " LEFT JOIN $other->table_name ON $other->table_name.$other->table_index = $this->table_name.$columnname";
				$joinedTables[] = $other->table_name;
			}
		}

		global $current_user;
		$query .= $this->getNonAdminAccessControlQuery($module,$current_user);
		$query .= "	WHERE vtiger_crmentity.deleted = 0 ".$usewhere;
		return $query;
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
					OR 
						(";
		
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


	function getDuplicatesQuery($module,$table_cols,$field_values,$ui_type_arr,$select_cols='') {
		$select_clause = "SELECT ". $this->table_name .".".$this->table_index ." AS recordid, vtiger_users_last_import.deleted,".$table_cols;

		// Select Custom Field Table Columns if present
		if(isset($this->customFieldTable)) $query .= ", " . $this->customFieldTable[0] . ".* ";

		$from_clause = " FROM $this->table_name";

		$from_clause .= "	INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = $this->table_name.$this->table_index";

		// Consider custom table join as well.
		if(isset($this->customFieldTable)) {
			$from_clause .= " INNER JOIN ".$this->customFieldTable[0]." ON ".$this->customFieldTable[0].'.'.$this->customFieldTable[1] .
				      " = $this->table_name.$this->table_index"; 
		}
		$from_clause .= " LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid
						LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid";
		
		$where_clause = "	WHERE vtiger_crmentity.deleted = 0";
		$where_clause .= $this->getListViewSecurityParameter($module);
					
		if (isset($select_cols) && trim($select_cols) != '') {
			$sub_query = "SELECT $select_cols FROM  $this->table_name AS t " .
				" INNER JOIN vtiger_crmentity AS crm ON crm.crmid = t.".$this->table_index;
			// Consider custom table join as well.
			if(isset($this->customFieldTable)) {
				$sub_query .= " LEFT JOIN ".$this->customFieldTable[0]." tcf ON tcf.".$this->customFieldTable[1]." = t.$this->table_index";
			}
			$sub_query .= " WHERE crm.deleted=0 GROUP BY $select_cols HAVING COUNT(*)>1";	
		} else {
			$sub_query = "SELECT $table_cols $from_clause $where_clause GROUP BY $table_cols HAVING COUNT(*)>1";
		}	
		
		
		$query = $select_clause . $from_clause .
					" LEFT JOIN vtiger_users_last_import ON vtiger_users_last_import.bean_id=" . $this->table_name .".".$this->table_index .
					" INNER JOIN (" . $sub_query . ") AS temp ON ".get_on_clause($field_values,$ui_type_arr,$module) .
					$where_clause .
					" ORDER BY $table_cols,". $this->table_name .".".$this->table_index ." ASC";
					
		return $query;		
	}

	/**
	 * Invoked when special actions are performed on the module.
	 * @param String Module name
	 * @param String Event Type (module.postinstall, module.disabled, module.enabled, module.preuninstall)
	 */
	function vtlib_handler($modulename, $event_type) {
		if($event_type == 'module.postinstall') {
			// TODO Handle post installation actions
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

}
?>
