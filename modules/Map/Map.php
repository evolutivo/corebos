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
require_once('modules/Map/crXml.php');
include_once('include/utils/VTCacheUtils.php');
class Map extends CRMEntity {
	var $db, $log; // Used in class functions of CRMEntity
        var $module_list = Array();
        var $rel_fields = Array();
        var $primodule;
        var $secmodule;
	var $table_name = 'vtiger_map';
	var $table_index= 'mapid';
	var $column_fields = Array();

	/** Indicator if this is a custom module or standard module */
	var $IsCustomModule = true;

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('vtiger_mapcf', 'mapid');

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('vtiger_crmentity', 'vtiger_map', 'vtiger_mapcf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'vtiger_crmentity' => 'crmid',
		'vtiger_map'   => 'mapid',
	    'vtiger_mapcf' => 'mapid');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array (
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Map Name'=> Array('map', 'mapname'),
		'Assigned To' => Array('crmentity','smownerid')
	);
	var $list_fields_name = Array(
		/* Format: Field Label => fieldname */
		'Map Name'=> 'mapname',
		'Assigned To' => 'assigned_user_id'
	);

	// Make the field link to detail view from list view (Fieldname)
	var $list_link_field = 'mapname';

	// For Popup listview and UI type support
	var $search_fields = Array(
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Map Name'=> Array('map', 'mapname')
	);
	var $search_fields_name = Array(
		/* Format: Field Label => fieldname */
		'Map Name'=> 'mapname'
	);

	// For Popup window record selection
	var $popup_fields = Array('mapname');

	// Placeholder for sort fields - All the fields will be initialized for Sorting through initSortFields
	var $sortby_fields = Array();

	// For Alphabetical search
	var $def_basicsearch_col = 'mapname';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'mapname';

	// Required Information for enabling Import feature
	var $required_fields = Array('mapname'=>1);

	// Callback function list during Importing
	var $special_functions = Array('set_import_assigned_user');

	var $default_order_by = 'mapname';
	var $default_sort_order='ASC';
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vtiger_field.fieldname values.
	var $mandatory_fields = Array('createdtime', 'modifiedtime', 'mapname');
	
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
        //koment
       function getMapTargetModule(){
           $map=htmlspecialchars_decode($this->column_fields['content']);
           $x = new crXml();
           $x->loadXML($map);
           $originmodule=(string)$x->map->targetmodule[0]->targetname;
           return $originmodule;
        }
       function getMapOriginModule(){
           $map=htmlspecialchars_decode($this->column_fields['content']);
           $x = new crXml();
           $x->loadXML($map);
           $return_module=(string)$x->map->originmodule[0]->originname;
           return $return_module;
        }
       function getMapTargetFields(){
            $map=htmlspecialchars_decode($this->column_fields['content']);
            $x = new crXml();
            $x->loadXML($map);
            $target_fields=array();
            $index=0;
            foreach($x->map->fields[0] as $k=>$v) {
            $fieldname=  (string)$v->fieldname;
            $allmergeFields=array();
			if(!empty($v->value)){
            	$target_fields[$fieldname]["value"] = (string) $v->value;
            }
            foreach($v->Orgfields[0]->Orgfield as $key=>$value) {
              // echo $fk;
               if($key=='OrgfieldName')
                  $allmergeFields[]=(string)$value;
               if($key=='delimiter')
                   $target_fields[$fieldname]['delimiter']=(string)$value;
            }
            $target_fields[$fieldname]['merge']=$allmergeFields;
           }
           return $target_fields;
        }
         function readMappingType() {
        $map = htmlspecialchars_decode($this->column_fields['content']);
        $x = new crXml();
        $x->loadXML($map);
        $target_fields = array();
        foreach ($x->map->fields[0] as $k => $v) {
            $fieldname = (string) $v->fieldname;
            $allmergeFields = array();
            if(!empty($v->value)){
            $target_fields[$fieldname] = array("value" => $v->value);  
            }
            else{
            foreach ($v->Orgfields[0]->Orgfield as $key => $value) {
                if ($key == 'OrgfieldName') {
                    $allmergeFields[] = (string) $value;
                }
                if ($key == 'delimiter') {
                    $delimiter = (string) $value;
                    if (empty($delimiter))
                        $delimiter = "";
                }
            }
            $target_fields[$fieldname] = array("delimiter" => $delimiter, "listFields" => $allmergeFields);
            }
        }
        return $target_fields;
    }
    function readImportType() {
    $map = htmlspecialchars_decode($this->column_fields['content']);
        $x = new crXml();
        $x->loadXML($map);
        $target_fields = array();
        $match_fields = array();
        $update_rules = array();
        foreach ($x->map->fields[0] as $k => $v) {
            $fieldname = (string) $v->fieldname;
            //$allmergeFields = array();
            $value=(string) $v->value;
            $predefined=(string) $v->predefined;
            $target_fields[$fieldname] = array('value'=>$value,'predefined'=>$predefined);
            //}
        }
        foreach ($x->map->matches[0] as $key => $value) {
            //if ($key == 'fieldname') {
            $fldname = (string) $value->fieldname;
            //}
            //if ($key == 'value') {
            $fldval = (string) $value->value;
            // }
            $match_fields[$fldname] = $fldval;
        }
         foreach ($x->map->options[0] as $key => $value) {
            //if ($key == 'update') {
           //$update = (string) $value;
            //}
            //if ($key == 'value') {
            //$fldval = (string) $value->value;
            // }
            $update_rules[$key] = (string) $value;
        }

        return array('target' => $target_fields, 'match' => $match_fields,'options'=>$update_rules);
    }
       function getMapOriginEmail_table(){
           $map=htmlspecialchars_decode($this->column_fields['content']);
           $x = new crXml();
           $x->loadXML($map);
           $return_module=(string)$x->map->targetmodule[0]->targetid;
           //var_dump($return_module);
           return $return_module;
        }
        
       function getMapSendEmails(){
            $map=htmlspecialchars_decode($this->column_fields['content']);
            $x = new crXml();
            $x->loadXML($map);
            $target_data=array();
            $index=0;
            foreach($x->map->fields->field->Orgfields[0] as $k=>$v) {
                
                if($k=='Orgfield'){
                $target_tab[]=  (string)$v->Orgfieldid;
                $target_table[]=  (string)$v->OrgfieldName;
                }
              }
           $target_fields['tab']=$target_tab;
           $target_fields['field']=$target_table;
           return $target_fields;
        }
          
function getMapFieldDependecy(){
            $map=htmlspecialchars_decode($this->column_fields['content']);
            $x = new crXml();
            $x->loadXML($map);
            $target_picklist=array();
            $target_roles=array();
            $target_profiles=array();
            $target_piclist_values=array();
            $target_mode='';
            $index=0;
            foreach($x->map->fields->field->Orgfields[0] as $k=>$v) {
                
                if($k=='Orgfield'){
                $targetfield[]=  (string)$v->fieldname;
                $action[]=  (string)$v->fieldaction;
                $targetvalue[]=  (string)$v->fieldvalue;
                if($v->fieldlength){
                        $fieldlength[]=  (string)$v->fieldlength;
                    }
                }
                
                if($k=='Responsiblefield'){
                $respfield[]=  (string)$v->fieldname;
                $respvalue[]=  (string)$v->fieldvalue;
                $comp=(string)$v->comparison;
                $ret_comp='==';
                if($comp=='equal')
                    $ret_comp='==';
                elseif($comp=='notequal')
                    $ret_comp='!=';
                $respcomparison[]=  $ret_comp;
                }

                if($k=='Picklist'){
                $target_picklist[]=  (string)$v->fieldname;
                    foreach($v as $k1=>$v1) {
                        if($k1=='values'){
                            $target_piclist_values[]=  (string)$v1;
                        }
                    }
                }
                
                if($k=='ResponsibleRole'){
                    foreach($v as $k1=>$v1) {
                        if($k1=='values'){
                            $target_roles[]=  (string)$v1;
                        }
                    }
                }
                
                if($k=='ResponsibleProfile'){
                    foreach($v as $k1=>$v1) {
                        if($k1=='values'){
                            $target_profiles[]=  (string)$v1;
                        }
                    }
                }
                
                if($k=='ResponsibleMode'){
                    foreach($v as $k1=>$v1) {
                        if($k1=='values'){
                            $target_mode=  (string)$v1;
                        }
                    }
                }
              }
           $target_fields['targetfield']=  $targetfield;
           $target_fields['action']=  $action;
           $target_fields['targetvalue']=  $targetvalue;
           $target_fields['fieldlength']=  $fieldlength;
           
           $target_fields['respfield']=  $respfield;
           $target_fields['respvalue']=  $respvalue;
           $target_fields['comparison']=  $respcomparison;
           
           $target_fields['target_picklist']=  $target_picklist;
           $target_fields['target_picklist_values']=  $target_piclist_values;
           
           $target_fields['target_roles'] = '"'. implode('","',$target_roles).'"';
           
           $target_fields['target_profiles'] = '"'. implode('","',$target_profiles).'"';
           
           $target_fields['target_mode']=  $target_mode;
                
           return $target_fields;
        }
        
         function getMapMessageMailer(){
            $map=htmlspecialchars_decode($this->column_fields['content']);
            $x = new crXml();
            $x->loadXML($map);
            $target_data=array();
            $index=0;
            foreach($x->map->fields->field->Orgfields[0] as $k=>$v) {
                
                if($k=='Orgfield'){
                $targetfield[]=  (string)$v->OrgfieldName;
                $columnfield[]=  (string)$v->OrgfieldCorrespond;
                }
                
              }
           $target_fields['targetfield']=  $targetfield;
           $target_fields['columnfield']=  $columnfield;
           
           return $target_fields;
       }
           
       function getMapSQL(){
       	global $log;
           $map= htmlspecialchars_decode($this->column_fields['content']);
           $x = new crXml();
           $x->loadXML($map);
           $sqlString=(string)$x->map->sql[0];
           return $sqlString;
        }
        
     function initListOfModules(){
            global $adb;
            $restricted_modules = array('Emails','Events','Webmails');
            $restricted_blocks = array('LBL_IMAGE_INFORMATION','LBL_COMMENTS','LBL_COMMENT_INFORMATION');
            //tabid and name of modules 
            $this->module_id = array();
            //name and blocks of modules
            $this->module_list = array();

            $modulerows = vtlib_prefetchModuleActiveInfo(false);
            $cachedInfo = VTCacheUtils::lookupMap_ListofModuleInfos();

            if($cachedInfo !== false) {
                                $this->module_list = $cachedInfo['module_list'];
                                $this->related_modules = $cachedInfo['related_modules'];
                                $this->rel_fields = $cachedInfo['rel_fields'];

            } else {
            if($modulerows) {
                foreach($modulerows as $resultrow) {
                        if($resultrow['presence'] == '1') continue;      // skip disabled modules
                        if($resultrow['isentitytype'] != '1') continue;  // skip extension modules
                        if(in_array($resultrow['name'], $restricted_modules)) { // skip restricted modules
                                continue;
                        }
                        if($resultrow['name']!='Calendar'){
                                $this->module_id[$resultrow['tabid']] = $resultrow['name'];
                        } else {
                                $this->module_id[9] = $resultrow['name'];
                                $this->module_id[16] = $resultrow['name'];

                        }
                        $this->module_list[$resultrow['name']] = array();
                }
                //get tabId of all modules
                $moduleids = array_keys($this->module_id);
                $moduleblocks = $adb->pquery("SELECT blockid, blocklabel, tabid FROM $dbname.vtiger_blocks WHERE tabid IN (" .generateQuestionMarks($moduleids) .")",
                                                        array($moduleids));
                        $prev_block_label = '';
                        if($adb->num_rows($moduleblocks)) {
                                while($resultrow = $adb->fetch_array($moduleblocks)) {
                                        $blockid = $resultrow['blockid'];
                                        $blocklabel = $resultrow['blocklabel'];
                                        $module = $this->module_id[$resultrow['tabid']];

                                        if(in_array($blocklabel, $restricted_blocks) ||
                                                in_array($blockid, $this->module_list[$module]) ||
                                                isset($this->module_list[$module][getTranslatedString($blocklabel,$module)])) 
                                                {
                                                    continue;
                                                }

                                        if(!empty($blocklabel)){
                                                if($module == 'Calendar' && $blocklabel == 'LBL_CUSTOM_INFORMATION')
                                                        $this->module_list[$module][$blockid] = getTranslatedString($blocklabel,$module);
                                                else
                                                        $this->module_list[$module][$blockid] = getTranslatedString($blocklabel,$module);
                                                $prev_block_label = $blocklabel;
                                        } else {
                                                $this->module_list[$module][$blockid] = getTranslatedString($prev_block_label,$module);
                                        }
                                }
                        }

        //                $relatedmodules = $adb->pquery(
        //                        "SELECT vtiger_tab.name, vtiger_relatedlists.tabid FROM $dbname.vtiger_tab
        //                        INNER JOIN $dbname.vtiger_relatedlists on vtiger_tab.tabid=vtiger_relatedlists.related_tabid
        //                        WHERE vtiger_tab.isentitytype=1
        //                        AND vtiger_tab.name NOT IN(".generateQuestionMarks($restricted_modules).")
        //                        AND vtiger_tab.presence = 0 AND vtiger_relatedlists.label!='Activity History'
        //                        UNION
        //                        SELECT module, vtiger_tab.tabid FROM $dbname.vtiger_fieldmodulerel
        //                        INNER JOIN $dbname.vtiger_tab on vtiger_tab.name = vtiger_fieldmodulerel.relmodule
        //                        WHERE vtiger_tab.isentitytype = 1
        //                        AND vtiger_tab.name NOT IN(".generateQuestionMarks($restricted_modules).")
        //                        AND vtiger_tab.presence = 0",
        //                        array($restricted_modules,$restricted_modules)
        //                );
                   $relatedmodules = $adb->pquery(
                                "SELECT module as name, vtiger_tab.tabid,fieldid FROM vtiger_fieldmodulerel
                                INNER JOIN vtiger_tab on vtiger_tab.name = vtiger_fieldmodulerel.relmodule
                                WHERE vtiger_tab.isentitytype = 1
                                AND vtiger_tab.name NOT IN(".generateQuestionMarks($restricted_modules).")
                                AND vtiger_tab.presence = 0",
                                array($restricted_modules)
                        );
                        if($adb->num_rows($relatedmodules)) {
                                while($resultrow = $adb->fetch_array($relatedmodules)) {
                                        $module = $this->module_id[$resultrow['tabid']];
                                        if(!isset($this->related_modules[$module])) {
                                                $this->related_modules[$module] = array();
                                        }
                                         if(!isset($this->related_modules[$module])) {
                                                $this->rel_fields[$module] = array();
                                        }

                                        if($module != $resultrow['name']) {
                                                $this->related_modules[$module][] = $resultrow['name'];

                                                $this->rel_fields[$module][$resultrow['name']] = $this->getFieldName($resultrow['fieldid']);
                                        }
                                }
                        }
                    // Put the information in cache for re-use
                    VTCacheUtils::updateMap_ListofModuleInfos($this->module_list, $this->related_modules,$this->rel_fields);
                }
            } 
        }
    
     function getFieldName($fieldid){ 
        global $adb;
        $result = $adb->pquery("Select fieldname from vtiger_field where fieldid = ?",array($fieldid));
        $fieldname = $adb->query_result($result,0,'fieldname');
        return $fieldname;
    }   
     
    function getPriModuleFieldsList($module,$modtype,$mode='')
    {   
        global $log;
        $log->debug("Entering getPriModuleFieldsList method moduleID=".$module);
            $cachedInfo = VTCacheUtils::lookupMap_ListofModuleInfos();
            if($cachedInfo !== false) {
                $this->module_list = $cachedInfo['module_list'];
                $this->rel_fields = $cachedInfo['rel_fields'];
            }
            $modName = getTabModuleName($module); 
            $this->primodule = $module;
//            if($mode == "edit")
//            foreach($this->module_list[$modName] as $key=>$value)
//            {       
//                $ret_module_list[$modName][$value] = $this->getFieldListbyBlock($modName,$key,'direct');
//            }
//            else
   
            foreach($this->module_list->$modName as $key=>$value)
            {       
                $ret_module_list[$modName][$value] = $this->getFieldListbyBlock($modName,$key,'direct');
            }
//            var_dump($ret_module_list);
            if($modtype == "target"){ 
                $this->related_modules = $cachedInfo['related_modules'];
                $this->rel_fields = $cachedInfo['rel_fields'];
                if($mode == "edit") $arr = $this->related_modules[$modName];
                else $arr = $this->related_modules->$modName;
                for($i=0;$i <count($arr);$i++){
                    $modName = $arr[$i];
                    if($mode == "edit")
                    foreach($this->module_list[$modName] as $key=>$value)
                    {       
                            $ret_module_list[$modName][$value] = $this->getFieldListbyBlock($modName,$key,'related');
                    }
                    else
                    foreach($this->module_list->$modName as $key=>$value)
                    {       
                            $ret_module_list[$modName][$value] = $this->getFieldListbyBlock($modName,$key,'related');
                    }
                }
            }
    $this->pri_module_columnslist = $ret_module_list;
    $log->debug("Exiting getPriModuleFieldsList method");
    return true;
}
    
function getPrimaryFieldHTML($module,$modtype)
{
    global $app_list_strings;
    global $app_strings;
    global $current_language;
    $id_added=false;
    $mod_strings = return_module_language($current_language,$module);
    $block_listed = array();
    $modName = getTabModuleName($module,$this->dbname);
    foreach($this->module_list->$modName as $key=>$value)
    {
            if(isset($this->pri_module_columnslist[$modName][$value]) && !$block_listed[$value])
            {
                    $block_listed[$value] = true;
                    $shtml .= "<optgroup label=\"".getTranslatedString($modName, $module)." ".getTranslatedString($value)."\" class=\"select\" style=\"border:none\">";
                    if($id_added==false){
                            $shtml .= "<option value=\"vtiger_crmentity:crmid:".$modName."_ID:crmid:I\">".getTranslatedString($modName.' ID', $modName)."</option>";
                            $id_added=true;
                    }
                    foreach($this->pri_module_columnslist[$modName][$value] as $field=>$fieldlabel)
                    {
                            if(isset($mod_strings[$fieldlabel]))
                            {
                                    $shtml .= "<option value=\"".$field."\">".$mod_strings[$fieldlabel]."</option>";
                            }else
                            {
                                    $shtml .= "<option value=\"".$field."\">".$fieldlabel."</option>";
                            }
                    }
            }
    }
    if($modtype == "target"){
    $arr = $this->related_modules->$modName;
    for($i=0;$i <count($arr);$i++){
    $modName = $arr[$i];
    foreach($this->module_list->$modName as $key=>$value)
    {
            if(isset($this->pri_module_columnslist[$modName][$value]) && !$block_listed[$value])
            {
                    $block_listed[$value] = true;
                    $shtml .= "<optgroup label=\"".getTranslatedString($modName, $module)." ".getTranslatedString($value)."\" class=\"select\" style=\"border:none\">";
                    if($id_added==false){
                            $shtml .= "<option value=\"vtiger_crmentity:crmid:".$modName."_ID:crmid:I\">".getTranslatedString($modName.' ID', $modName)."</option>";
                            $id_added=true;
                    }
                    foreach($this->pri_module_columnslist[$modName][$value] as $field=>$fieldlabel)
                    {
                            if(isset($mod_strings[$fieldlabel]))
                            {
                                    $shtml .= "<option value=\"".$field."\">".$mod_strings[$fieldlabel]."</option>";
                            }else
                            {
                                    $shtml .= "<option value=\"".$field."\">".$fieldlabel."</option>";
                            }
                    }
            }
    }   
  }
 }
    
    return $shtml;
}
function getFieldListbyBlock($module,$block,$type)
{ 
        global $adb;
        global $log;
        global $current_user;

        if(is_string($block)) $block = explode(",", $block);
        $tabid = getTabid($module,$this->dbname);
        if ($module == 'Calendar') {
                $tabid = array('9','16');
        }
        $params = array($tabid, $block);
        $sql = "select * from $this->dbname.vtiger_field where vtiger_field.tabid in (". generateQuestionMarks($tabid) .") and vtiger_field.block in (". generateQuestionMarks($block) .") and vtiger_field.displaytype in (1,2,3) and vtiger_field.presence in (0,2) ";
        $result = $adb->pquery($sql, $params);
        $pmod = getTabModuleName($this->primodule,$this->dbname); 

        $noofrows = $adb->num_rows($result);
        for($i=0; $i<$noofrows; $i++)
        {
                $fieldtablename = $adb->query_result($result,$i,"tablename");
                $fieldcolname = $adb->query_result($result,$i,"columnname");
                $fieldname = $adb->query_result($result,$i,"fieldname");
                $fieldtype = $adb->query_result($result,$i,"typeofdata");
                $uitype = $adb->query_result($result,$i,"uitype");
                $fieldid = $adb->query_result($result,$i,"fieldid");
                $fieldtype = explode("~",$fieldtype);
                $fieldtypeofdata = $fieldtype[0];

                if($uitype == 68 || $uitype == 59)
                {
                        $fieldtypeofdata = 'V';
                }
                if($fieldtablename == "vtiger_crmentity")
                {
                        $fieldtablename = $fieldtablename.$module;
                }
                if($fieldname == "assigned_user_id")
                {
                        $fieldtablename = "vtiger_users".$module;
                        $fieldcolname = "user_name";
                }
                if($fieldname == "assigned_user_id1")
                {
                        $fieldtablename = "vtiger_usersRel1";
                        $fieldcolname = "user_name";
                }

                $fieldlabel = $adb->query_result($result,$i,"fieldlabel");
                $fieldlabel1 = str_replace(" ","_",$fieldlabel);
                if($type == "related")
                $optionvalue = $fieldtablename.":".$fieldcolname.":".$module.":".$fieldname.":".$fieldid.":".$type.":".$this->rel_fields->$pmod->$module;
                else
                $optionvalue = $fieldtablename.":".$fieldcolname.":".$module.":".$fieldname.":".$fieldid.":".$type;
                if($module != 'HelpDesk' || $fieldname !='filename')  $module_columnlist[$optionvalue] = $fieldlabel;
        }
        $blockname = getBlockName($block,$this->dbname);
        if($blockname == 'LBL_RELATED_PRODUCTS' && ($module=='PurchaseOrder' || $module=='SalesOrder' || $module=='Quotes' || $module=='Invoice')){
                $fieldtablename = 'vtiger_inventoryproductrel';
                $fields = array('productid'=>getTranslatedString('Product Name',$module),
                                                'serviceid'=>getTranslatedString('Service Name',$module),
                                                'listprice'=>getTranslatedString('List Price',$module),
                                                'discount'=>getTranslatedString('Discount',$module),
                                                'quantity'=>getTranslatedString('Quantity',$module),
                                                'comment'=>getTranslatedString('Comments',$module),
                );
                $fields_datatype = array('productid'=>'V',
                                                'serviceid'=>'V',
                                                'listprice'=>'I',
                                                'discount'=>'I',
                                                'quantity'=>'I',
                                                'comment'=>'V',
                );
                foreach($fields as $fieldcolname=>$label){
                        $fieldtypeofdata = $fields_datatype[$fieldcolname];
                        if($type == "related")
                        $optionvalue =  $fieldtablename.":".$fieldcolname.":".$module.":".$fieldcolname.":".$fieldid.":".$type.":".$this->rel_fields->$pmod->$module;
                        else
                        $optionvalue =  $fieldtablename.":".$fieldcolname.":".$module.":".$fieldcolname.":".$fieldid.":".$type;
                        $module_columnlist[$optionvalue] = $label;
                }
        }
        $log->info("Map :: FieldColumns->Successfully returned FieldlistbyBlock".$module.$block);
        return $module_columnlist;
}
function getBlockInfo($modId)
{
    global $adb , $log;
    $moduleName = getTabModuleName($modId);
    $blockinfo=array();
    $blocks_query=$adb->pquery("select blockid,tabid,blocklabel from vtiger_blocks where tabid=? order by sequence ASC",array($modId));
    for($i=0;$i<$adb->num_rows($blocks_query);$i++)
    {   
        $blockinfo[]=array(
            'blockid' => $adb->query_result($blocks_query,$i,'blockid'),
            'tabid' => $adb->query_result($blocks_query,$i,'tabid'),
            'blocklabel'=>$adb->query_result($blocks_query,$i,'blocklabel'),
            );
    }
    if($moduleName=='Project')
    {
        $size=sizeof($blockinfo);
        $blockinfo[$size]=array(
            'blockid' => '1000',
            'tabid' => $modId,
            'blocklabel'=>'Execute',
        );
    }
    return $blockinfo;
}
function getBlockHTML($blocks,$module)
{
    global $app_list_strings,$log;
    global $app_strings;
    global $current_language;
    $id_added=false;
    $mod_strings = return_module_language($current_language,$module);
    $block_listed = array();
    $modName = getTabModuleName($module,$this->dbname);$shtml='';
    for($i=0;$i<sizeof($blocks);$i++)
    { 
       foreach($blocks[$i] as $key=>$value)
           {  if($key=='blocklabel')
                 if($value=='Execute')
                 $shtml .= "<option value=\"".$blocks[$i]['blockid']."\" class=\"select\" style=\"border:none\">".getTranslatedString($value, $modName)."</option>";
                   else
                 $shtml .= "<option value=\"".$blocks[$i]['blockid']."\" class=\"select\" style=\"border:none\">".getTranslatedString($value, $modName)."</option>";
            }
    }
     
  return $shtml;
}
function isXML($xml){
   libxml_use_internal_errors(true);
   $doc = new DOMDocument('1.0', 'utf-8');
   $doc->loadXML($xml);

   $errors = libxml_get_errors();

   if(empty($errors)){
       return true;
   }

   $error = $errors[0];
   if($error->level < 3){
       return true;
   }

   $explodedxml = explode("r", $xml);
   $badxml = $explodedxml[($error->line)-1];

   $message = $error->message . ' at line ' . $error->line . '. Bad XML: ' . htmlentities($badxml);
   return $message;
}
function ReadFromXmlContent($mapid){global $log,$adb;
$query=$adb->pquery("select content from vtiger_map where mapid=?",array($mapid));
$xmlcontent=html_entity_decode($adb->query_result($query,0,'content'));
$map = CRMEntity::getInstance('Map');
$blockinfo=array();
if($map->isXML($xmlcontent)){
    $xml=simplexml_load_string($xmlcontent);
    foreach($xml->blocks->block as $block){
    {
          $blockinfo[]=array(
            'blockid' => $block->blockID,
            'blockname'=>$block->blockname,
            'blocklabel'=>$block->blocklabel,
            );
    }
 }
}
return $blockinfo;
}
function readInputFields() {
        $map = htmlspecialchars_decode($this->column_fields['content']);
        $x = new crXml();
        $x->loadXML($map);
        $input_fields = array();
        foreach ($x->map->input->fields[0] as $k => $v) {
            $fieldname = (string) $v->fieldname;
            $input_fields[] = $fieldname;
        }
        return $input_fields;
    }

    function readOutputFields() {
        $map = htmlspecialchars_decode($this->column_fields['content']);
        $x = new crXml();
        $x->loadXML($map);
        $output_fields = array();
        foreach ($x->map->output->fields[0] as $k => $v) {
            $fieldname = (string) $v->fieldname;
            $output_fields[] = $fieldname;
        }
        return $output_fields;
    }
}
?>
