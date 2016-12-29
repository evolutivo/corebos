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

class BusinessActions extends CRMEntity {
	var $db, $log; // Used in class functions of CRMEntity

	var $table_name = 'vtiger_businessactions';
	var $table_index= 'businessactionsid';
	var $column_fields = Array();

	/** Indicator if this is a custom module or standard module */
	var $IsCustomModule = true;
	var $HasDirectImageField = false;
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('vtiger_businessactionscf', 'businessactionsid');
// Uncomment the line below to support custom field columns on related lists
// var $related_tables = Array('vtiger_payslipcf'=>array('payslipid','vtiger_payslip', 'payslipid'));

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('vtiger_crmentity', 'vtiger_businessactions', 'vtiger_businessactionscf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'vtiger_crmentity' => 'crmid',
		'vtiger_businessactions'   => 'businessactionsid',
		'vtiger_businessactionscf' => 'businessactionsid');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array (
		/* Format: Field Label => Array(tablename => columnname) */
		// tablename should not have prefix 'vtiger_'
		'Reference'=> Array('businessactions'=> 'reference'),
		'actions_status' => array('businessactions'=> 'actions_status'),
		'actions_type' => array('businessactions'=> 'actions_type'),
		'Assigned To' => array('crmentity'=> 'smownerid'),
		'tags' => array('businessactions'=> 'tags'),
	);
	var $list_fields_name = Array(
		/* Format: Field Label => fieldname */
		'Reference'=> 'reference',
		'actions_status' => 'actions_status',
		'actions_type' => 'actions_type',
		'Assigned To' => 'assigned_user_id',
		'tags' => 'tags',
	);

	// Make the field link to detail view from list view (Fieldname)
	var $list_link_field = 'reference';

	// For Popup listview and UI type support
	var $search_fields = Array(
		/* Format: Field Label => Array(tablename => columnname) */
		// tablename should not have prefix 'vtiger_'
		'Reference'=> Array('businessactions'=> 'reference'),
		'actions_status' => array('businessactions'=> 'actions_status'),
		'actions_type' => array('businessactions'=> 'actions_type'),
		'Assigned To' => array('crmentity'=> 'smownerid'),
		'tags' => array('businessactions'=> 'tags'),
	);
	var $search_fields_name = Array(
		/* Format: Field Label => fieldname */
		'Reference'=> 'reference',
		'actions_status' => 'actions_status',
		'actions_type' => 'actions_type',
		'Assigned To' => 'assigned_user_id',
		'tags' => 'tags',
	);

	// For Popup window record selection
	var $popup_fields = Array('reference');

	// Placeholder for sort fields - All the fields will be initialized for Sorting through initSortFields
	var $sortby_fields = Array();

	// For Alphabetical search
	var $def_basicsearch_col = 'reference';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'reference';

	// Required Information for enabling Import feature
	var $required_fields = Array('reference'=>1);

	// Callback function list during Importing
	var $special_functions = Array('set_import_assigned_user');

	var $default_order_by = 'reference';
	var $default_sort_order='ASC';
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vtiger_field.fieldname values.
	var $mandatory_fields = Array('createdtime', 'modifiedtime', 'reference');

	function __construct() {
		global $log;
		$this_module = get_class($this);
		$this->column_fields = getColumnFields($this_module);
		$this->db = PearDatabase::getInstance();
		$this->log = $log;
		$sql = 'SELECT 1 FROM vtiger_field WHERE uitype=69 and tabid = ? limit 1';
		$tabid = getTabid($this_module);
		$result = $this->db->pquery($sql, array($tabid));
		if ($result and $this->db->num_rows($result)==1) {
			$this->HasDirectImageField = true;
		}
	}

	function save_module($module) {
		if ($this->HasDirectImageField) {
			$this->insertIntoAttachment($this->id,$module);
		}
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

			$other = CRMEntity::getInstance($related_module);
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
	 * Create query to export the records.
	 */
	function create_export_query($where)
	{
		global $current_user;
		$thismodule = $_REQUEST['module'];

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery($thismodule, "detail_view");

		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT $fields_list, vtiger_users.user_name AS user_name 
				FROM vtiger_crmentity INNER JOIN $this->table_name ON vtiger_crmentity.crmid=$this->table_name.$this->table_index";

		if(!empty($this->customFieldTable)) {
			$query .= " INNER JOIN ".$this->customFieldTable[0]." ON ".$this->customFieldTable[0].'.'.$this->customFieldTable[1] .
				" = $this->table_name.$this->table_index";
		}

		$query .= " LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid";
		$query .= " LEFT JOIN vtiger_users ON vtiger_crmentity.smownerid = vtiger_users.id and vtiger_users.status='Active'";

		$linkedModulesQuery = $this->db->pquery("SELECT distinct fieldname, columnname, relmodule FROM vtiger_field" .
				" INNER JOIN vtiger_fieldmodulerel ON vtiger_fieldmodulerel.fieldid = vtiger_field.fieldid" .
				" WHERE uitype='10' AND vtiger_fieldmodulerel.module=?", array($thismodule));
		$linkedFieldsCount = $this->db->num_rows($linkedModulesQuery);

		$rel_mods[$this->table_name] = 1;
		for($i=0; $i<$linkedFieldsCount; $i++) {
			$related_module = $this->db->query_result($linkedModulesQuery, $i, 'relmodule');
			$fieldname = $this->db->query_result($linkedModulesQuery, $i, 'fieldname');
			$columnname = $this->db->query_result($linkedModulesQuery, $i, 'columnname');

			$other = CRMEntity::getInstance($related_module);
			vtlib_setup_modulevars($related_module, $other);

			if($rel_mods[$other->table_name]) {
				$rel_mods[$other->table_name] = $rel_mods[$other->table_name] + 1;
				$alias = $other->table_name.$rel_mods[$other->table_name];
				$query_append = "as $alias";
			} else {
				$alias = $other->table_name;
				$query_append = '';
				$rel_mods[$other->table_name] = 1;
			}

			$query .= " LEFT JOIN $other->table_name $query_append ON $alias.$other->table_index = $this->table_name.$columnname";
		}

		$query .= $this->getNonAdminAccessControlQuery($thismodule,$current_user);
		$where_auto = " vtiger_crmentity.deleted=0";

		if($where != '') $query .= " WHERE ($where) AND $where_auto";
		else $query .= " WHERE $where_auto";

		return $query;
	}

	/**
	 * Initialize this instance for importing.
	 */
	function initImport($module) {
		$this->db = PearDatabase::getInstance();
		$this->initImportableFields($module);
	}

	/**
	 * Create list query to be shown at the last step of the import.
	 * Called From: modules/Import/UserLastImport.php
	 */
	function create_import_query($module) {
		global $current_user;
		$query = "SELECT vtiger_crmentity.crmid, case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name, $this->table_name.* FROM $this->table_name
			INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = $this->table_name.$this->table_index
			LEFT JOIN vtiger_users_last_import ON vtiger_users_last_import.bean_id=vtiger_crmentity.crmid
			LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid
			LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid
			WHERE vtiger_users_last_import.assigned_user_id='$current_user->id'
			AND vtiger_users_last_import.bean_type='$module'
			AND vtiger_users_last_import.deleted=0";
		return $query;
	}

	/**
	 * Delete the last imported records.
	 */
	function undo_import($module, $user_id) {
		global $adb;
		$count = 0;
		$query1 = "select bean_id from vtiger_users_last_import where assigned_user_id=? AND bean_type='$module' AND deleted=0";
		$result1 = $adb->pquery($query1, array($user_id)) or die("Error getting last import for undo: ".mysql_error());
		while ( $row1 = $adb->fetchByAssoc($result1))
		{
			$query2 = "update vtiger_crmentity set deleted=1 where crmid=?";
			$result2 = $adb->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: ".mysql_error());
			$count++;
		}
		return $count;
	}

	/**
	 * Transform the value while exporting
	 */
	function transform_export_value($key, $value) {
		return parent::transform_export_value($key, $value);
	}

	/**
	 * Function which will set the assigned user id for import record.
	 */
	function set_import_assigned_user()
	{
		global $current_user, $adb;
		$record_user = $this->column_fields["assigned_user_id"];

		if($record_user != $current_user->id){
			$sqlresult = $adb->pquery("select id from vtiger_users where id = ? union select groupid as id from vtiger_groups where groupid = ?", array($record_user, $record_user));
			if($this->db->num_rows($sqlresult)!= 1) {
				$this->column_fields["assigned_user_id"] = $current_user->id;
			} else {
				$row = $adb->fetchByAssoc($sqlresult, -1, false);
				if (isset($row['id']) && $row['id'] != -1) {
					$this->column_fields["assigned_user_id"] = $row['id'];
				} else {
					$this->column_fields["assigned_user_id"] = $current_user->id;
				}
			}
		}
	}

	/**
	 * Function which will give the basic query to find duplicates
	 */
	function getDuplicatesQuery($module,$table_cols,$field_values,$ui_type_arr,$select_cols='') {
		$select_clause = "SELECT ". $this->table_name .".".$this->table_index ." AS recordid, vtiger_users_last_import.deleted,".$table_cols;

		// Select Custom Field Table Columns if present
		if(isset($this->customFieldTable)) $query .= ", " . $this->customFieldTable[0] . ".* ";

		$from_clause = " FROM $this->table_name";

		$from_clause .= " INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = $this->table_name.$this->table_index";

		// Consider custom table join as well.
		if(isset($this->customFieldTable)) {
			$from_clause .= " INNER JOIN ".$this->customFieldTable[0]." ON ".$this->customFieldTable[0].'.'.$this->customFieldTable[1] .
				" = $this->table_name.$this->table_index";
		}
		$from_clause .= " LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid
						LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid";

		$where_clause = " WHERE vtiger_crmentity.deleted = 0";
		$where_clause .= $this->getListViewSecurityParameter($module);

		if (isset($select_cols) && trim($select_cols) != '') {
			$sub_query = "SELECT $select_cols FROM $this->table_name AS t " .
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
                function executeAction($recordid,$outputType,$recarray=null,$confirmVal,$actionid,$palid){
            global $root_directory,$log;
             include_once('data/CRMEntity.php');

            $businessrules_action=$this->column_fields['businessrules_action'];
            $moduleactions=$this->column_fields['moduleactions'];
            $reference=$this->column_fields['reference'];
            $fullScriptPath=$this->column_fields['script_name'];
            $scripts=explode("/",$fullScriptPath);
            $nr=count($scripts);
            $scriptName=$scripts[$nr-1];
            $map=$this->column_fields['linktomapmodule'];
            $actiontype=$this->column_fields['actions_type'];
            $parameter1=$this->column_fields['parameter1'];
            $causale=$this->column_fields['causale'];
            $sequencer=$this->column_fields['sequencers'];
            //$function_man=($this->column_fields['actions_type']=='Send Mail' ? 'mail_manager' : 'actions_manager');  
            if($actiontype!='Send Mail'){
                if($scriptName=='Switchflussi'){
                    $result= "index.php?module=ProcessTemplate&action=Popup&html=Popup_picker&form=vtlibPopupView&forfield=newtemplate&srcmodule=Project&forrecord=".$recordid;
                }
                elseif($scriptName=='createBarcodepdf'){
                   $result="index.php?module=BusinessActions&action=BusinessActionsAjax&file=/scripts/createBarcodepdf&recordid=".$recordid."&myaction=".$parameter1."&map=".$map; 
                }elseif($scriptName=='createPdf'){
                   $result="index.php?module=BusinessActions&action=BusinessActionsAjax&file=/scripts/createPdf&recordid=".$recordid."&myaction=".$parameter1."&map=".$map; 
                }
                elseif($scriptName=='changeBarcodetemp'){
                   $result="index.php?module=BusinessActions&action=BusinessActionsAjax&file=/scripts/changeBarcodetemp&recordid=".$recordid."&myaction=".$parameter1."&map=".$map; 
                }
                else{
                shell_exec("cd $root_directory");
                $log->debug('action test');
                $jdec=json_decode($recordid);
                $jdec1=str_replace("'","",$jdec[0]);$log->debug('testim'.$recarray .' ' .$outputType.' '.$jdec1);
                if($jdec1=='ws')
                { 
                    $result= shell_exec("php -f  $fullScriptPath.php '".json_encode($jdec[1])."' '$map' '$parameter1' '$causale' '$outputType' '".json_encode($recarray)."' '$confirmVal' '$actionid' '$sequencer' '$palid'");
                }else{
                $result= shell_exec("php -f  $fullScriptPath.php '$recordid' '$map' '$parameter1' '$causale' '$outputType' '".json_encode($recarray)."' '$confirmVal' '$actionid' '$sequencer' '$palid'");
                }
                }
            }
            else{
                
            }
            return $result;
        }
        
        function executeAction1($parameters){
            global $root_directory,$log;
            include_once('data/CRMEntity.php');
            $businessrules_action=$this->column_fields['businessrules_action'];
            $moduleactions=$this->column_fields['moduleactions'];
            $reference=$this->column_fields['reference'];
            $fullScriptPath=$this->column_fields['script_name'];
            $scripts=explode("/",$fullScriptPath);
            $nr=count($scripts);
            $scriptName=$scripts[$nr-1];
            $map=$this->column_fields['linktomapmodule'];
            $actiontype=$this->column_fields['actions_type'];
            $parameter1=$this->column_fields['parameter1'];
            $causale=$this->column_fields['causale'];
            $sequencer=$this->column_fields['sequencers'];
            
            include_once("$root_directory$fullScriptPath.php");
            $action_param=array('map'=>$map,'actiontype'=>$actiontype,
                'parameter1'=>$parameter1,'causale'=>$causale);
            $parameters=array_merge($parameters,$action_param);
            return $scriptName($parameters);

        }
        
        function runBusinessLogic() {
        global $current_user, $record, $adb, $log;
        $params = array();
        $allelements = array("CURRENT_USER" => $current_user->id, "CURRENT_RECORD" => $record);
        $businessrules_action = $this->column_fields['businessrules_action'];
        $businessrulesid = $this->column_fields['linktobrules'];
        $br_focus = CRMEntity::getInstance("BusinessRules");
        $br_focus->retrieve_entity_info($businessrulesid, "BusinessRules");
        $mapid=$br_focus->column_fields['linktomap'];
        if($mapid!='' && $mapid !=0){
            $has_map=true;
            $mapfocus=  CRMEntity::getInstance("cbMap");
            $mapfocus->retrieve_entity_info($mapid,"cbMap");
            $mapfocus->id=$mapid;
            $businessrules_action=$mapfocus->getMapSQL(); 
        }
        if ($br_focus->isProfilePermitted()) {
            if($has_map){
                foreach ($allelements as $elem => $value) {
                    $pos_el = strpos($businessrules_action, $elem);
                    if ($pos_el) {
                        $businessrules_action = str_replace($elem, " ? ", $businessrules_action);
                        array_push($params, $value);
                    }
                }
                $res_logic = $adb->pquery($businessrules_action, $params);
                if ($adb->num_rows($res_logic) > 0) {
                    return true;
                }
                else
                    return false;
            }
            else
                return true;
        }
        else
            return false;
    }
    function runBusinessLogic2($therecid) {
        //new method for put_ methods of sap
        global $current_user, $record, $adb, $log;
        $businessrulesid = $this->column_fields['actobrnew'];
        if (!empty($businessrulesid)) {
            require_once ("modules/BusinessRules/BusinessRules.php");
            $br_focus = CRMEntity::getInstance("BusinessRules");
            $br_focus->retrieve_entity_info($businessrulesid, "BusinessRules");
            if ($br_focus->isRolePermitted()) {
                $retrieveQuery=$br_focus->executeSQLQuery2($therecid);
                return $retrieveQuery;
                if($retrieveQuery){
                    return true;
                }
                else{
                    return false;
                }
                return true;
            }
            else
                return false;
        }
        else
            return true;
    } 
}
?>
