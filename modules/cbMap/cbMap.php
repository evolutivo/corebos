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
if(!class_exists('crxml'))
require_once('modules/cbMap/crXml.php');
include_once('include/utils/VTCacheUtils.php');
class cbMap extends CRMEntity {
	var $db, $log; // Used in class functions of CRMEntity
	var $module_list = Array();
	var $rel_fields = Array();
	var $primodule;
	var $secmodule;
	var $table_name = 'vtiger_cbmap';
	var $table_index= 'cbmapid';
	var $column_fields = Array();

	/** Indicator if this is a custom module or standard module */
	var $IsCustomModule = true;
	var $HasDirectImageField = false;
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('vtiger_cbmapcf', 'cbmapid');

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('vtiger_crmentity', 'vtiger_cbmap', 'vtiger_cbmapcf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'vtiger_crmentity' => 'crmid',
		'vtiger_cbmap'   => 'cbmapid',
	    'vtiger_cbmapcf' => 'cbmapid');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array (
		/* Format: Field Label => Array(tablename => columnname) */
		// tablename should not have prefix 'vtiger_'
		'Map Name'=> Array('cbmap'=> 'mapname'),
		'Assigned To' => Array('crmentity'=>'smownerid')
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
		/* Format: Field Label => Array(tablename => columnname) */
		// tablename should not have prefix 'vtiger_'
		'Map Name'=> Array('cbmap'=> 'mapname')
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
		$sql = 'SELECT 1 FROM vtiger_field WHERE uitype=69 and tabid = ?';
		$tabid = getTabid($currentModule);
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
        function getMapProfile(){
           $map=htmlspecialchars_decode($this->column_fields['content']);
           $x = new crXml();
           $x->loadXML($map);
           $target_profile=array();
           foreach ($x->map->profile[0] as $k => $v) {
                    $target_profile[]=  (string)$v;
           }
           $return_profile=$target_profile;
           return $return_profile;
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
            elseif(!empty($v->Orgfields[0]->Orgfield) && isset($v->Orgfields[0]->Orgfield) ){
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
            elseif(!empty($v->Orgfields[0]->Relfield)&& isset($v->Orgfields[0]->Relfield) ){
                $allRelValues=array();
            foreach ($v->Orgfields[0]->Relfield as $key => $value) {
                if ($key == 'RelfieldName') {
                    $allRelValues['fieldname']=(string) $value;
                }
                if ($key == 'RelModule') {
                    $allRelValues['relmodule']=(string) $value;
                }
                if ($key == 'linkfield') {
                    $allRelValues['linkfield']=(string) $value;
                }
                if ($key == 'delimiter') {
                    $delimiter = (string) $value;
                    if (empty($delimiter))
                        $delimiter = "";
                }
                
            }
            $allmergeFields[]=$allRelValues;
            $target_fields[$fieldname] = array("delimiter" => $delimiter, "relatedFields" => $allmergeFields);
            }
            
        }
        return $target_fields;
    }
      function readTableMappingType() {
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
            $target_fields[$fieldname] = array('value'=>$value);
            //}
        }
        return array('target' => $target_fields);
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
            $conditionsValues = array();
            //$allmergeFields = array();
            if (!empty($v->value)) {
                $value = (string) $v->value;
            }
            if (!empty($v->conditions[0])) {
                foreach ($v->conditions[0] as $condkey => $condval) {
                    $condition = (string) $condval->cond;
                    $condValue = (string) $condval->value;
                    $conditionsValues[] = array('cond' => $condition, 'value' => $condValue);
                }
            }
            if(!empty($v->predefined)){
            $predefined=(string) $v->predefined;
            }
            if(empty($v->Orgfields[0]->Relfield)){

            $target_fields[$fieldname] = array('value'=>$value,'predefined'=>$predefined,'conditions'=>$conditionsValues);
            //}
        }
        elseif(!empty($v->Orgfields[0]->Relfield) && isset($v->Orgfields[0]->Relfield) ){
                $allRelValues=array();
            foreach ($v->Orgfields[0]->Relfield as $key => $value1) { 

                if ($key == 'RelfieldName') {
                    $allRelValues['fieldname']=(string) $value1;
                }
                if ($key == 'RelModule') {
                    $allRelValues['relmodule']=(string) $value1;
                }
                if ($key == 'linkfield') {
                    $allRelValues['linkfield']=(string) $value1;
                }
                if ($key == 'delimiter') {
                    $delimiter = (string) $value1;
                    if (empty($delimiter))
                        $delimiter = "";
                }               
            }
        $allmergeFields[]=$allRelValues;
        $target_fields[$fieldname] = array('value'=>$value,'predefined'=>$predefined,'conditions'=>$conditionsValues, "relatedFields" => $allmergeFields);

                }
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
            $mandatory=array();
            $automatic=array();
            $targetfield=array();
            $respfield=array();
            $index=0;
            foreach($x->map->fields->field->Orgfields[0] as $k=>$v) {
                
                if($k=='Orgfield'){
                    $targetfield[]=  (string)$v->fieldname;
                    $action[]=  (string)$v->fieldaction;
                    $targetvalue[]=  (string)$v->fieldvalue;
                    $ismand=false;$isautomatic=false;
    //                if($v->fieldlength){
    //                        $fieldlength[]=  (string)$v->fieldlength;
    //                    }
                    foreach($v as $k_r=>$v_r) {
                        if($k_r=='mandatory'){
                            $mandatory[]=  (string)$v_r;
                            $ismand=true;
                        }                    
                    }
                    if(!$ismand){
                        $mandatory[]='';
                    }
                    foreach($v as $k_r=>$v_r) {
                        if($k_r=='automatic'){
                            $automatic[]=  (string)$v_r;
                            $isautomatic=true;
                        }                    
                    }
                    if(!$isautomatic){
                        $automatic[]='';
                    }
                                 
                }
                                 
                if($k=='Responsiblefield'){
                $respfield[]=  (string)$v->fieldname;
                $r_values=array();
                foreach($v as $k_r=>$v_r) {
                    if($k_r=='fieldvalue')
                        $r_values[]=  (string)$v_r;
                }
                $respvalue[]='"'. implode('","',$r_values).'"';;
                $respvalue_portal[]=$r_values;
                $comp=(string)$v->comparison;
                $ret_comp='==';
                if($comp=='equal')
                    $ret_comp='==';
                elseif($comp=='notequal')
                    $ret_comp='!=';
                elseif(!empty($comp))
                    $ret_comp=$comp;
                $respcomparison[]=  $ret_comp;
                }

                if($k=='Picklist'){
                $target_picklist[]=  (string)$v->fieldname;
                $target_piclist_values_temp=array();
                    foreach($v as $k1=>$v1) {
                        if($k1=='values'){
                            $target_piclist_values_temp[]=  (string)$v1;
                        }
                    }
                    $target_piclist_values[(string)$v->fieldname]=  $target_piclist_values_temp;
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
           $target_fields['mandatory']=  $mandatory;
           $target_fields['automatic']=  $automatic;
           
           $target_fields['respfield']=  $respfield;
           $target_fields['respvalue']=  $respvalue;
           $target_fields['respvalue_portal']=  $respvalue_portal;
           $target_fields['comparison']=  $respcomparison;
           
           $target_fields['target_picklist']=  $target_picklist;
           $target_fields['target_picklist_values']=  $target_piclist_values;
           
           $target_fields['target_roles'] = $target_roles;
           
           $target_fields['target_profiles'] = $target_profiles;
           
           $target_fields['target_mode']=  $target_mode;
                
           return $target_fields;
        }
        
function getMapPermissionActions  (){
          $map=htmlspecialchars_decode($this->column_fields['content']);
            $x = new crXml();
            $x->loadXML($map);
            $target_actions=array();
            $target_actions==array();
            $index=0;
            foreach($x->map->fields->field->Orgfields[0] as $k=>$v) {
               
                if($k=='ResponsibleProfile'){
                    foreach($v as $k1=>$v1) {
                        if($k1=='values'){
                            $target_profiles[]=  (string)$v1;
                        }
                    }
                }
                
                if($k=='Actions'){
                    foreach($v as $k1=>$v1) {
                        if($k1=='values'){
                            $target_actions[]=  (string)$v1;
                        }
                    }
                }
                
                if($k=='View'){
                    foreach($v as $k1=>$v1) {
                        if($k1=='values'){
                            $target_view[]=  (string)$v1;
                        }
                    }
                }
              }
           $target_fields['target_profiles'] = $target_profiles;
           $target_fields['target_view'] = $target_view;
           $target_fields['target_actions']=  $target_actions;
                
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
                
                if($k=='RelatedField'){
                $relatedtargetfield[]=  (string)$v->OrgfieldName;
                $relatedcolumnfield[]=  (string)$v->OrgfieldCorrespond;
                $relatedentityfield[]=  (string)$v->OrgfieldRelatedField;
                }
                
              }
           $target_fields['targetfield']=  $targetfield;
           $target_fields['columnfield']=  $columnfield;
           
           $target_fields['relatedtargetconstant']=  $relatedtargetfield;
           $target_fields['relatedcolumnfield']=  $relatedcolumnfield;
           $target_fields['relatedui10field']=  $relatedentityfield;
           
           return $target_fields;
       }
       
       function getMapCustomerTypes(){
            $map=htmlspecialchars_decode($this->column_fields['content']);
            $x = new crXml();
            $x->loadXML($map);
            $target_data=array();
            $index=0;
            foreach($x->map as $k=>$v) {
                
                if($k=='respmodule'){
                    $respmodule=  (string)$v;
                }
                
                if($k=='relatedmodule'){
                    $modulename=  (string)$v->name;
                    $field=  (string)$v->field;
                    $throughmodulename=array();
                    // indirect related modules
                    $relmod=  (string)$v->step->throughmodule;
                    $throughfield=  (string)$v->step->throughfield;
                    if($relmod!==''){
                        $throughmodulename[$relmod]=  $throughfield;
                    }
                    $rel_mod[$modulename]=array('field'=>$field,
                        'throughmodule'=>$throughmodulename);
                }
                
              }
           $target_fields['respmodule']=  $respmodule;
           $target_fields['relmodule']=  $rel_mod;
           
           return $target_fields;
       }
       
       function getMapMenuStructure(){
            $default_language = 'it_it';
            global $current_language,$adb; 
            $current_language = $default_language; 
            $current_language = vtws_preserveGlobal('current_language',$current_language); 

            $appStrings = return_application_language($current_language);
            $appListString = return_app_list_strings_language($current_language);
            vtws_preserveGlobal('app_strings',$appStrings);
            vtws_preserveGlobal('app_list_strings',$appListString);
            
            $map=htmlspecialchars_decode($this->column_fields['content']);
            $x = new crXml();
            $x->loadXML($map);
            $rows=array();
            $columns=array();
            $name='';
            foreach($x->map->menus[0] as $k0=>$v0) {
            if($k0=='profile'){
                $profile=(string)$v0;
            }
            else{
                foreach($v0 as $k1=>$v1) {
                    if($k1=='label')
                        $label=(string)$v1;
                    if($k1=='name'){
                        $res_entity=$adb->pquery("Select isentitytype"
                                . " from vtiger_tab"
                                . " where name=?",array((string)$v1));
                        $isentitytype=$adb->query_result($res_entity,0,'isentitytype');
                        $columns[$label][]=  array('item'=>(string)$v1,
                            'label'=>getTranslatedString((string)$v1,(string)$v1),
                            'entitytype'=>$isentitytype);
                    }
                  }
            }
              }
            $res=array('modules'=>$columns,'profile'=>$profile);
            return $res;
        }
        
        function getMapPortalDvBlocks(){
            $map=htmlspecialchars_decode($this->column_fields['content']);
            $x = new crXml();
            $x->loadXML($map);
            $rows=array();$rows1=array();
            $columns=array();$block=array();
            $name='';$i=0;
            foreach($x->map->blocks[0] as $k0=>$v0) {
                    foreach($v0 as $k=>$v) {
                        if($k=='name'){                           
                            $name=$v;
                            $block[$i]=(string)$name;
                        }
                        if($k=='row'){
                            $columns=array();
                            foreach($v as $k1=>$v1) {
                                        if($k1=='column'){
                                        $columns[]=  (string)$v1;
                                    }
                            }
                            $rows[$i][]=  $columns; 
                        }
//                        $rows1[$name]=  $rows; 
                    }
                    $i++;
              }
//              var_dump($block);
               $target_fields['rows']=  $rows;
               $target_fields['blocks']=  $block;
          return $target_fields;
        }
                   
       function getMapSQL(){
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
                                                isset($this->module_list[$module][getTranslatedString($blocklabel,$module)])) {
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
function ReadFromXmlContent($mapid) {
global $log,$adb;
$query=$adb->pquery("select content from vtiger_cbmap where cbmapid=?",array($mapid));
$xmlcontent=html_entity_decode($adb->query_result($query,0,'content'));
$map = CRMEntity::getInstance('cbMap');
$blockinfo=array();
if($map->isXML($xmlcontent)){
    $xml=simplexml_load_string($xmlcontent);
    foreach($xml->blocks->block as $block) {
          $blockinfo[]=array(
            'blockid' => $block->blockID,
            'blockname'=>$block->blockname,
            'blocklabel'=>$block->blocklabel,
            );
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
    function getEntityFieldNamesByTablename($tablename) {
	$adb = PearDatabase::getInstance();
	$data = array();
	if (!empty($tablename)) {
		$query = "select fieldname,modulename,tablename,entityidfield from vtiger_entityname where tablename = ?";
		$result = $adb->pquery($query, array($tablename));
		$fieldsName = $adb->query_result($result, 0, 'fieldname');
		$tableName = $adb->query_result($result, 0, 'tablename');
		$entityIdField = $adb->query_result($result, 0, 'entityidfield');
		$moduleName = $adb->query_result($result, 0, 'modulename');
		if (!(strpos($fieldsName, ',') === false)) {
			$fieldsName = explode(',', $fieldsName);
		}
	}
	$data = array("tablename" => $tableName, "modulename" => $moduleName, "fieldname" => $fieldsName, "entityidfield" => $entityIdField);
	return $data;
}
    function create_query(){
        global $log,$adb;
        $content=html_entity_decode($this->column_fields['content']);
        $isxml=$this->isXML($content);
           if($isxml=='true'){
             $xml=simplexml_load_string($content);
             $xml_module=$xml->modules->module;  
             foreach($xml_module as $key=>$value){
                 $modules[]=array('modulename'=>(string)$value->modulename,
                                   'tablename'=>(string)$value->tablename);
             }
             
             $xml_fields=$xml->modules->fields->field;
             foreach($xml_fields as $field_key=>$field_value){
                 $fields[]=array('fieldname'=>(string)$field_value->fieldname,
                               'operator'=>(string)$field_value->operator,
                               'expectedvalue'=>(string)$field_value->expectedvalue,
                               'uniquesearch'=>(string)$field_value->uniquesearch
                         );
             }
        }       
        $sql='';
        $select=' ';
        $join=' ';
        $crmentity_check=' ';
        $deleted=' ';
        $where=" ";
        $entityid=array();
        if(sizeof($modules)>0 && sizeof($fields)>0 ){
            if(sizeof($modules)>1){
                for($i=0;$i<sizeof($modules);$i++){
                    for($j=$i+1;$j<sizeof($modules);$j++){
                        $modulename_i=$modules[$i]['modulename'];
                        $modulename_j=$modules[$j]['modulename'];
                       // $tablename_i=$modules[$i]['tablename'];
                        //$tablename_j=$modules[$j]['tablename'];
                   
                        $related_modules_query=$adb->pquery('SELECT * FROM  vtiger_fieldmodulerel 
                            WHERE  (module=? and relmodule=?) or (module=? and relmodule=?)',array($modulename_i,$modulename_j,$modulename_j,$modulename_i));
                        if($adb->num_rows($related_modules_query)==1){
                              if($j!=1)   {
                                  $join.=' join ';
                                  $deleted.=' and ';
                                 }
                            $module=$adb->query_result($related_modules_query,0,'module');
                            $relmodule=$adb->query_result($related_modules_query,0,'relmodule');
                            $fieldid=$adb->query_result($related_modules_query,0,'fieldid');
                            require_once('include/utils/CommonUtils.php');
                            $relmodule_info=getEntityFieldNames($relmodule);//moduli uitype 10 ->2
                            $module_info=getEntityFieldNames($module);// mod 1
                            $fieldid_name_query=$adb->pquery("select fieldname from vtiger_field where fieldid=?",array($fieldid));
                            $fieldid_name=$adb->query_result($fieldid_name_query,0,'fieldname');
                              
                          if(stristr($join,$module_info['tablename'])!='' && stristr($join,$relmodule_info['tablename'])!=''){
                             $join.=" ". $relmodule_info['tablename'] . " as tab".$i.$j." on ".$module_info['tablename'].".".$fieldid_name."=tab".$i.$j.".".$relmodule_info['entityidfield'];
                             $crmentity_check.=" join vtiger_crmentity as c".$i.$j." on c".$i.$j.".crmid=tab".$i.$j.".".$relmodule_info['entityidfield'];
                             $deleted.=" c$i$j.deleted=0 ";
                             $entityid[]=$relmodule_info['tablename'].".".$fieldid_name;
                                        
                          }else
                          if(stristr($join,$module_info['tablename'])!=''){                   
                             $join.=" ". $relmodule_info['tablename'] . " on ".$module_info['tablename'].".".$fieldid_name."=".$relmodule_info['tablename'].".".$relmodule_info['entityidfield'];
                             $crmentity_check.=" join vtiger_crmentity as c".$i.$j." on c".$i.$j.".crmid=".$relmodule_info['tablename'].".".$fieldid_name;
                             $deleted.=" c$i$j.deleted=0 ";
                             $entityid[]=$relmodule_info['tablename'].".".$fieldid_name;
                                          } 
                           else if(stristr($join,$relmodule_info['tablename'])!='') {
                            $join.=" ".$module_info['tablename']."  on ".$module_info['tablename'].".".$fieldid_name."=".$relmodule_info['tablename'].".".$relmodule_info['entityidfield'];
                            $crmentity_check.=" join vtiger_crmentity as c".$i.$j." on c".$i.$j.".crmid=".$module_info['tablename'].".".$module_info['entityidfield'];
                            $deleted.=" c$i$j.deleted=0 ";
                            $entityid[]=$module_info['tablename'].".".$module_info['entityidfield'];
                           }    
                            else{
                            $join.=$module_info['tablename']." join  ".$relmodule_info['tablename'] . " on ".$module_info['tablename'].".".$fieldid_name."=".$relmodule_info['tablename'].".".$relmodule_info['entityidfield'];
                            $crmentity_check.=" join vtiger_crmentity as c".$i.$j." on c".$i.$j.".crmid=".$module_info['tablename'].".".$module_info['entityidfield'];
                            $crmentity_check.=" join vtiger_crmentity as c".$i.$j.$j." on c".$i.$j.$j.".crmid=".$relmodule_info['tablename'].".".$relmodule_info['entityidfield'];
                            $deleted.=" c$i$j.deleted=0 and c$i$j$j.deleted=0 " ;
                            $entityid[]=$module_info['tablename'].".".$module_info['entityidfield'];
                            $entityid[]=$relmodule_info['tablename'].".".$relmodule_info['entityidfield'];
                            
                            }
                         }
                    }
                }
              
                $where.=' and ';
                $join.= $crmentity_check. " where  ". $deleted;
            } else if(sizeof($modules)==1){
                 $modulename=$modules[0]['modulename'];
                 //$tablename=$modules[0]['tablename'];
                  $module_info=getEntityFieldNames($modulename);
                  $join=" ". $module_info['tablename']." join vtiger_crmentity on crmid=".$module_info['entityidfield']." where deleted=0 and " ;
                              
            }
            $vals=' ';
            for($f=0;$f<sizeof($fields);$f++){
                if($f!=0)
                    $vals.=' and ';

                $fieldname=$fields[$f]['fieldname'];
                $fieldnames[]=$fieldname;
                
                $operator=trim($fields[$f]['operator']);
                $expectedvalue=trim($fields[$f]['expectedvalue']);
                $uniquesearch=trim($fields[$f]['uniquesearch']);
                if($operator=='in' && stristr($expectedvalue,'vtiger_')==''){
                     $vals.=" " .$fieldname." ".$operator. ' ("'.str_replace(';','","',$expectedvalue).' ")';
                    
                }else
                if(stristr($expectedvalue,';')!=''){
                    $array_values=explode(';',$expectedvalue);
                    $c=0;
                    foreach($array_values as $value){
                       $c++;
                        if($c>1)
                            $vals.=' and ';
                        if($value=="''")
                           $vals.=" " .$fieldname." ".$operator. " '' ";
                        else
                        if(gettype($value)=='string'){
                            
                         if(stristr($value,'.')!='' && stristr($value,'vtiger_')!=''){
                          $expectedvalue_array=  explode('.', $value);
                          $expected_table_name=$expectedvalue_array[0];

                          if(stristr($join,$expected_table_name)==''   || ($uniquesearch!='' && stristr($join,$expected_table_name)!='')){
                                $searched_field_array=explode('.',$fieldname);// search is taken from fieldname
                                $searched_field_table_name=$searched_field_array[0];
                                $value=$this->generate_subquery($fieldname,$value,$uniquesearch,$expected_table_name,$searched_field_table_name,$i,$j);
                           }
                 }
                            
                            
                            
                           if(stristr($value,'vtiger_')!='' )
                                  $vals.=" " .$fieldname." ".$operator. " $value";
                          else  if(stristr($operator,'like')!='')
                          $vals.=" " .$fieldname." ".$operator. " '%$value%'";
                            else
                          $vals.=" " .$fieldname." ".$operator. " '$value'";  
                        }else
                         $vals.=" " .$fieldname." ".$operator. " $value";
                    }
                    
                }else  if($expectedvalue=="''")
                           $vals.=" " .$fieldname." ".$operator. " '' ";
                 else  if(gettype($expectedvalue)=="string"){
                                      // vetem ne rastin kur expected element eshte nje vtiger_module.field.
                      if(stristr($expectedvalue,'.')!='' && stristr($expectedvalue,'vtiger_')!='' ){
                          $expectedvalue_array=  explode('.', $expectedvalue);
                          $expected_table_name=$expectedvalue_array[0];

                          if(stristr($join,$expected_table_name)=='' || ($uniquesearch!='' && stristr($join,$expected_table_name)!='')){
                                $searched_field_array=explode('.',$fieldname);// search is taken from fieldname
                                $searched_field_table_name=$searched_field_array[0];
                                             
                                $expectedvalue=$this->generate_subquery($fieldname,$expectedvalue,$uniquesearch,$expected_table_name,$searched_field_table_name,$i,$j);
                    }
                 }
           
                 $log->Debug($expectedvalue);
                 if(stristr($expectedvalue,'select')!='' || stristr($expectedvalue,'vtiger_')!='' )
                          $vals.=" " .$fieldname." ".$operator. " $expectedvalue";
                 else if(stristr($operator,'like')!='')
                                 $vals.=" " .$fieldname." ".$operator. " '%$expectedvalue%'";
                                  else
                                $vals.=" " .$fieldname." ".$operator. " '$expectedvalue'";  
                       }
               else  
                        $vals.=" " .$fieldname." ".$operator. " $expectedvalue";
              }
            
            $where.="  $vals ";
            array_merge($fieldnames,$entityid);
            $select=" select ".implode(',',$fieldnames)." from ";
            $sql=$select ." ". $join ." ".$where;
            
            return $sql;
        }else
            return false;
     }
     function generate_subquery($fieldname,$expectedvalue,$uniquesearch,$expected_table_name,$searched_field_table_name,$i,$j){
       global $adb,$currentModule,$log;
            
       $expected_module=$this->getEntityFieldNamesByTablename($expected_table_name);
       $searched_module=$this->getEntityFieldNamesByTablename($searched_field_table_name);
       $where=' where ';
       $related_tab_query=$adb->pquery('SELECT * FROM  vtiger_fieldmodulerel 
           WHERE  (module=? and relmodule=?) or (module=? and relmodule=?)',array($expected_module['modulename'],$searched_module['modulename'],$searched_module['modulename'],$expected_module['modulename']));
 
       if($adb->num_rows($related_tab_query)>0){
           $first_module_name=$adb->query_result($related_tab_query,0,'module');
           $related_module_name=$adb->query_result($related_tab_query,0,'relmodule');
           $first_module_fieldid=$adb->query_result($related_tab_query,0,'fieldid');// field in first module
           
           $first_module=$this->getEntityFieldNamesByTablename($first_module_name);
           $related_module=$this->getEntityFieldNamesByTablename($related_module_name);
                                        
           $related_fieldid_name_query=$adb->pquery("select fieldname from vtiger_field where fieldid=?",array($first_module_fieldid));
           $related_fieldid_name=$adb->query_result($related_fieldid_name_query,0,'fieldname');
                                        
           $expectedvalue_base="(Select $fieldname from ". $first_module['tablename'] . " join vtiger_crmentity as c".$i.$j." on c".$i.$j.".crmid=".$first_module['tablename'].".".$first_module['entityidfield']."
                             join  ".$related_module['tablename']." on  ".$related_module['tablename'].".".$related_module['entityidfield']."=".$first_module['tablename'].".".$related_fieldid_name."
                            join vtiger_crmentity as c".$i.$j.$j." on c".$i.$j.$j.".crmid=".$related_module['tablename'].".".$related_module['entityidfield'];
           $where.="  c".$i.$j.".deleted=0 and c".$i.$j.$j.".deleted=0 ";
           }else // no relation between modules
              { 
               $first_module=$this->getEntityFieldNamesByTablename($searched_field_table_name);
               $related_module=$this->getEntityFieldNamesByTablename($expected_table_name);
                                                      
               $expectedvalue_base="(Select $expectedvalue from ". $first_module['tablename'] . " join vtiger_crmentity as c".$i.$j." on c".$i.$j.".crmid=".$first_module['tablename'].".".$first_module['entityidfield']."
                                join  ".$related_module['tablename']." on  $fieldname=$expectedvalue 
                                 join vtiger_crmentity as c".$i.$j.$j." on c".$i.$j.$j.".crmid=".$related_module['tablename'].".".$related_module['entityidfield'];
                $where.="  c".$i.$j.".deleted=0 and c".$i.$j.$j.".deleted=0 ";
               
              }
             
              if($uniquesearch!=''){
                   if(stristr($uniquesearch,';')==''){
                       $expectedvalue_array=$this->generate_sub_subquery($expectedvalue_base,$uniquesearch,$searched_module,$expected_module,$i,$j);
                  
              }else{
                  $uniquesearch_array=explode(';',$uniquesearch);
                  $expectedvalue_arrays='';
                  foreach($uniquesearch_array as $uniquesearch){
                      if(!empty($expectedvalue_arrays)){
                          $expectedvalue_base=$expectedvalue_arrays['sql'];
                          $expectedvalue_arrays=$this->generate_sub_subquery($expectedvalue_base,$uniquesearch,$searched_module,$expected_module,$i,$j);
                           }else
                          $expectedvalue_arrays=$this->generate_sub_subquery($expectedvalue_base,$uniquesearch,$searched_module,$expected_module,$i,$j);
                          
                      }
                  $expectedvalue_array=$expectedvalue_arrays;
                  }
             
              }
             
if(!empty($expectedvalue_array)){
    $expectedvalue=$expectedvalue_array['sql'];
    $first_unique_module=$expectedvalue_array['first_unique_module'];
    $related_unique_module=$expectedvalue_array['related_unique_module'];
}  
                if($_REQUEST['proj_id']!='')
                    $_REQUEST['record']=$_REQUEST['proj_id'];
                
                if($currentModule==$first_module['modulename'] && ($_REQUEST['record']!=''))
                    $expectedvalue.=" and ". $first_module['tablename'].".".$first_module['entityidfield']."=".$_REQUEST['record']. " )";
                
                else if($_REQUEST['record']!='' && $currentModule==$related_module['modulename'])
                    $expectedvalue.=" and ". $related_module['tablename'].".".$related_module['entityidfield']."=".$_REQUEST['record']. " )";
                
                else if($first_unique_module['modulename']==$currentModule && $_REQUEST['record']!='' )
                      $expectedvalue.=" and ". $first_unique_module['tablename'].".".$related_module['entityidfield']."=".$_REQUEST['record']. " )";
                
                else if($related_unique_module['modulename']==$currentModule && $_REQUEST['record']!='' )
                      $expectedvalue.=" and ". $related_unique_module['tablename'].".".$related_unique_module['entityidfield']."=".$_REQUEST['record']. " )";
                
                else if(($_REQUEST['record']!='') && $currentModule!=''){
                    
                    $currentmodule_info=getEntityFieldNames($currentModule);
                    $related_tables_query=$adb->pquery('SELECT * FROM  vtiger_fieldmodulerel 
                                WHERE  (module=? and relmodule=?) or (module=? and relmodule=?)',array($currentmodule_info['modulename'],$first_module['modulename'],$first_module['modulename'],$currentmodule_info['modulename']));
                    
                      if($adb->num_rows($related_tables_query)==0)
                          $related_tables_query=$adb->pquery('SELECT * FROM  vtiger_fieldmodulerel 
                                         WHERE  (module=? and relmodule=?) or (module=? and relmodule=?)',array($currentmodule_info['modulename'],$related_module['modulename'],$related_module['modulename'],$currentmodule_info['modulename']));
                                       
                      if($adb->num_rows($related_tables_query)>0){
                                             $field_relation=$adb->query_result($related_tables_query,0,'fieldid');
                                             $relation_fieldid_name_query=$adb->pquery("select fieldname ,tablename from vtiger_field where fieldid=?",array($field_relation));
                                             $relation_fieldid_name=$adb->query_result($relation_fieldid_name_query,0,'fieldname');
                                             $table=$adb->query_result($relation_fieldid_name_query,0,'tablename');
                                             $expectedvalue.=" and ".$table.".".$relation_fieldid_name."=".$_REQUEST['record'].' )' ;
                                        }
                                        else
                                             $expectedvalue.=" ) ";
                                        }
                                        else
                                      $expectedvalue.=" ) ";
     
      return $expectedvalue;
     }
     
     function generate_sub_subquery($expectedvalue_base,$uniquesearch,$searched_module,$expected_module,$i,$j){
         global $adb;
         if(stristr($uniquesearch,'=')!=''){
                      $uniquesearch_array=explode('=',$uniquesearch);
                      $uniquesearch_field=$uniquesearch_array[0];
                      $uniquesearch_value=$uniquesearch_array[1];
                      
                      $uniquesearch_table_name=explode('.',$uniquesearch_field);
                      //tab exist in query and value is not field name
                      if(stristr($expectedvalue_base,$uniquesearch_table_name)!='' && stristr($uniquesearch_value,'vtiger_')==''){
                          if(gettype($uniquesearch_value)=='string')
                              $where.=" and  $uniquesearch_field='".$uniquesearch_value."' ";
                          else 
                              $where.=" and  $uniquesearch ";
                        }    //tab does not exist in query and value is not field name
                    else if(stristr($expectedvalue_base,$uniquesearch_table_name[0])!='' && stristr($uniquesearch_value,'vtiger_')!=''){
                              $uniquesearch_table_name=explode('.',$uniquesearch_value);
                             $uniquesearch_module=$this->getEntityFieldNamesByTablename($uniquesearch_table_name[0]);

                         $related_uniquesearch_query=$adb->pquery('SELECT * FROM  vtiger_fieldmodulerel 
                           WHERE  (module=? and relmodule=?) or (module=? and relmodule=?)',
                                 array($searched_module['modulename'],$uniquesearch_module['modulename'],$uniquesearch_module['modulename'],$searched_module['modulename']));
                        
                         if($adb->num_rows($related_uniquesearch_query)==0)
                             $related_uniquesearch_query=$adb->pquery('SELECT * FROM  vtiger_fieldmodulerel 
                           WHERE  (module=? and relmodule=?) or (module=? and relmodule=?)',
                                 array($expected_module['modulename'],$uniquesearch_module['modulename'],$uniquesearch_module['modulename'],$expected_module['modulename']));
                       
                         if($adb->num_rows($related_uniquesearch_query)>0) {
                            $first_unique_module_name=$adb->query_result($related_uniquesearch_query,0,'module');
                            $related_unique_module_name=$adb->query_result($related_uniquesearch_query,0,'relmodule');
                            $first_unique_module_fieldid=$adb->query_result($related_uniquesearch_query,0,'fieldid');// field in first module
                          
                            $first_unique_module=getEntityFieldNames($first_unique_module_name);
                            $related_unique_module=getEntityFieldNames($related_unique_module_name);
                           
                            $related_unique_fieldid_name_query=$adb->pquery("select fieldname from vtiger_field where fieldid=?",array($first_unique_module_fieldid));
                            $related_unique_fieldid_name=$adb->query_result($related_unique_fieldid_name_query,0,'fieldname');
                          
                            $expectedvalue_base.=" join ". $uniquesearch_table_name[0] ." on ". $first_unique_module['tablename'].".$related_unique_fieldid_name=".$related_unique_module['tablename'].".".$related_unique_module['entityidfield']." ";
                            $where.=" and  c".$i.$j.".deleted=0 " ;
                            if(gettype($uniquesearch_value)=='string' && stristr($uniquesearch_value,'vtiger_')!='')
                              $where.=" and $uniquesearch_field=".$uniquesearch_value." ";
                            else if(gettype($uniquesearch_value)=='string')
                              $where.=" and $uniquesearch_field='".$uniquesearch_value."' ";
                            else 
                              $where.=" and $uniquesearch ";
                            
                         }
                    }      
                     else if(stristr($expectedvalue_base,$uniquesearch_table_name[0])=='' && stristr($uniquesearch_value,'vtiger_')==''){
                         $uniquesearch_module=$this->getEntityFieldNamesByTablename($uniquesearch_table_name[0]);

                         $related_uniquesearch_query=$adb->pquery('SELECT * FROM  vtiger_fieldmodulerel 
                           WHERE  (module=? and relmodule=?) or (module=? and relmodule=?)',
                                 array($searched_module['modulename'],$uniquesearch_module['modulename'],$uniquesearch_module['modulename'],$searched_module['modulename']));
                       
                         if($adb->num_rows($related_uniquesearch_query)>0) {
                            $first_unique_module_name=$adb->query_result($related_uniquesearch_query,0,'module');
                            $related_unique_module_name=$adb->query_result($related_uniquesearch_query,0,'relmodule');
                            $first_unique_module_fieldid=$adb->query_result($related_uniquesearch_query,0,'fieldid');// field in first module
                           
                           
                            $first_unique_module=getEntityFieldNames($first_unique_module_name);
                            $related_unique_module=getEntityFieldNames($related_unique_module_name);
                           
                            $related_unique_fieldid_name_query=$adb->pquery("select fieldname from vtiger_field where fieldid=?",array($first_unique_module_fieldid));
                            $related_unique_fieldid_name=$adb->query_result($related_unique_fieldid_name_query,0,'fieldname');
                          
                            $expectedvalue_base.=" join ". $uniquesearch_table_name[0] ." on ". $first_unique_module['tablename'].".$related_unique_fieldid_name=".$related_unique_module['tablename'].".".$related_unique_module['entityidfield']." ";
                            $where.=" and  c".$i.$j.".deleted=0 " ;
                            if(gettype($uniquesearch_value)=='string')
                              $where.=" and $uniquesearch_field='".$uniquesearch_value."' ";
                            else 
                              $where.=" and $uniquesearch ";
                            
                         }
                      }
                       else if(stristr($expectedvalue_base,$uniquesearch_table_name[0])=='' && stristr($uniquesearch_value,'vtiger_')!=''){
                           
                       }
                  }
                  
                  $expectedvalue=$expectedvalue_base. ' '. $where;
                  $result=array('sql'=>$expectedvalue,
                      'first_unique_module'=>$first_unique_module,
                      'related_unique_module'=>$related_unique_module
                      );

                  return $result;
     }
     function search_update_fields(){
         global $adb,$log;
         
        $content=html_entity_decode($this->column_fields['content']);
        $isxml=$this->isXML($content);

        if($isxml=='true'){
             $xml=simplexml_load_string($content);
             $search_xml_module=$xml->search->module;  
             foreach($search_xml_module as $search_key=>$search_value){
                 $search_module[]=array('modulename'=>(string)$search_value->modulename,
                                        'tablename'=>(string)$search_value->tablename);
             }
             
             $search_xml_fields=$xml->search->fields->field;
             foreach($search_xml_fields as $search_field_key=>$search_field_value){
                $search_fields[]=array('fieldname'=>(string)$search_field_value->fieldname,
                                       'operator'=>(string)$search_field_value->operator,
                                       'expectedvalue'=>(string)$search_field_value->expectedvalue
                         );
             }
             $search_xml_rules=$xml->search->rules->rule;
             foreach($search_xml_rules as $search_rule_key=>$search_rule_value){
                 $rule=array();
                 for($f=0;$f<sizeof($search_rule_value->searchfield);$f++){
                   if((string)$search_rule_value->searchfield[$f]!='')
                   $rule_field=(string)$search_rule_value->searchfield[$f];
                   if((string)$search_rule_value->operator[$f] !='')
                    $rule_operator=(string)$search_rule_value->operator[$f];
                    $rule[]=array('field'=>$rule_field,
                                  'operator'=>$rule_operator,
                                  'alter_expectedvalue'=>array('alter_operator'=>(string)$search_rule_value->alter_expectedvalue[$f]->operator,
                                                               'alter_value'=>(string)$search_rule_value->alter_expectedvalue[$f]->value));
                 }
                 $search_rules[]=$rule;
             }  

             $update_xml_module=$xml->update->modules->module;
             foreach($update_xml_module as $update_key=>$update_value){
                  $update_module[]=array('modulename'=>(string)$update_value->modulename,
                                         'tablename'=>(string)$update_value->tablename);
              }
           
             $update_xml_fields=$xml->update->fields->field;
             foreach($update_xml_fields as $update_field_key=>$update_field_value){
                  $update_fields[]=array('fieldname'=>(string)$update_field_value->fieldname,
                                         'operator'=>(string)$update_field_value->operator,
                                         'expectedvalue'=>(string)$update_field_value->expectedvalue
                         );
             }
            
        }
        if(!empty($search_module) && !empty($search_fields)){
        $result=array('Search'=>array('module'=>$search_module,
                                      'fields'=>$search_fields,
                                      'rules'=>$search_rules),
                       'Update'=>array('module'=>$update_module,
                                       'fields'=>$update_fields));
        return $result;
        }
        else
            return false;
   }
   
   function search_query($result){
     global $log;
    require_once('include/utils/CommonUtils.php');
    
    $search_module=$result['Search']['module'][0]['modulename'];
    //$search_table=$result['Search'][0]['tablename'];
    
    $search_module_info=getEntityFieldNames($search_module);
    
    $select="SELECT * from ".$search_module_info['tablename']." join vtiger_crmentity on 
        crmid=".$search_module_info['tablename'].".".$search_module_info['entityidfield'] ." where deleted=0 and ";

    $rules=$result['Search']['rules'];
    for($r=0;$r<sizeof($rules);$r++){
        $expected_values='';$where='';

      
        for($rr=0;$rr<sizeof($rules[$r]);$rr++){
            
            $rule_field=trim($rules[$r][$rr]['field']);
            $rule_operator=$rules[$r][$rr]['operator'];
            $rule_alter_rule=$rules[$r][$rr]['alter_expectedvalue'];
            for($f=0;$f<sizeof($result['Search']['fields']);$f++){
                
               $fieldname=trim($result['Search']['fields'][$f]['fieldname']);
               if($rule_field===$fieldname){ 
                if($where!=''){
                $where.=" ".$rule_operator." ";
                $expected_values.=',';
                }
                if($rule_alter_rule['alter_operator']!=''){
                    $expected_values.=$rule_alter_rule['alter_value'];
                }else
                $expected_values.=$result['Search']['fields'][$f]['expectedvalue'];
                $where.=" ".$fieldname.$result['Search']['fields'][$f]['operator']."? ";
                $f=sizeof($result['Search']['fields'])+1;
                }

            }
            
        }
        $sql_array[]=array('select'=>$select,
                           'where'=>$where,
                           'expectedvalues'=>$expected_values,
                           'update'=>$result['Update']);
    }
   return $sql_array;
   }
     
    function read_map(){
        global $adb,$log;  
        $map_type=$this->column_fields['maptype'];
        if(strtolower($map_type)=='query'){
            $sql=$this->create_query();$log->Debug('dionimap');
            
            if($sql!=''){
                $projectid=$_REQUEST['proj_id'];
                if($projectid=='')
                    $projectid=$_REQUEST['record'];
                $module=$_REQUEST['module'];
                if($module!=''&& $projectid!=''){
                    $ModuleInfo=getEntityField($module);
                    $sql.= ' and '.$ModuleInfo['tablename'].'.'.$ModuleInfo['entityid'].'= ?';
                    $result=$adb->pquery($sql,array($projectid));
                     if($adb->num_rows($result)>0){
                        return true;
                    }else
                        return false;
                }else
                if($projectid!='' && $projectid!=0)
                {   $sql.= ' and vtiger_project.projectid=? ';
                    $result=$adb->pquery($sql,array($projectid));
                     if($adb->num_rows($result)>0){
                        return true;
                    }else
                        return false;
                }else{
                    return $sql;
                }
                
            }
        }else
         if(strtolower($map_type)=='search and update'){
             $data=$this->search_update_fields();
             if(!empty($data)){ 
                 $result=$this->search_query($data);
                 $log->debug('dioni serach map');
                 $log->debug($result);
                 return $result;
               }
             else 
                 return false;

         }
    }
function getBlocksPortal1($module, $disp_view, $mode, $col_fields = '', $info_type = '',$profile) {
	global $log;
	$log->debug("Entering getBlocks(" . $module . "," . $disp_view . "," . $mode . "," . $col_fields . "," . $info_type . ") method ...");
	global $adb, $current_user;
	global $mod_strings;
	$tabid = getTabid($module);
	$block_detail = Array();
	$getBlockinfo = "";
	$query = "select blockid,blocklabel,show_title,display_status from vtiger_blocks where tabid=? and $disp_view=0 and visible = 0 order by sequence";
	$result = $adb->pquery($query, array($tabid));
	$noofrows = $adb->num_rows($result);
	$prev_header = "";
        $blockid_list = array();
	for ($i = 0; $i < $noofrows; $i++) {
		$blockid = $adb->query_result($result, $i, "blockid");
		array_push($blockid_list, $blockid);
		$block_label[$blockid] = $adb->query_result($result, $i, "blocklabel");

		$sLabelVal = getTranslatedString($block_label[$blockid], $module);
		$aBlockStatus[$sLabelVal] = $adb->query_result($result, $i, "display_status");
	}
        
	if ($mode == 'edit') {
		$display_type_check = 'vtiger_field.displaytype = 1';
	} elseif ($mode == 'mass_edit') {
		$display_type_check = 'vtiger_field.displaytype = 1 AND vtiger_field.masseditable NOT IN (0,2)';
	} else {
		$display_type_check = 'vtiger_field.displaytype in (1,4)';
	}

	/* if($non_mass_edit_fields!='' && sizeof($non_mass_edit_fields)!=0){
	  $mass_edit_query = "AND vtiger_field.fieldname NOT IN (". generateQuestionMarks($non_mass_edit_fields) .")";
	  } */

	//retreive the vtiger_profileList from database
	require('user_privileges/user_privileges_' . $current_user->id . '.php');
	if ($disp_view == "detail_view") {
		
			$profileList = array($profile);
			$sql = "SELECT vtiger_field.*, vtiger_profile2field.readonly FROM vtiger_field INNER JOIN vtiger_profile2field ON vtiger_profile2field.fieldid=vtiger_field.fieldid INNER JOIN vtiger_def_org_field ON vtiger_def_org_field.fieldid=vtiger_field.fieldid WHERE vtiger_field.tabid=? AND vtiger_field.block IN (" . generateQuestionMarks($blockid_list) . ") AND vtiger_field.displaytype IN (1,2,4) and vtiger_field.presence in (0,2) AND vtiger_profile2field.visible=0 AND vtiger_def_org_field.visible=0 AND vtiger_profile2field.profileid IN (" . generateQuestionMarks($profileList) . ") GROUP BY vtiger_field.fieldid ORDER BY block,sequence";
			$params = array($tabid, $blockid_list, $profileList);
			//Postgres 8 fixes
			if ($adb->dbType == "pgsql")
				$sql = fixPostgresQuery($sql, $log, 0);
		$result = $adb->pquery($sql, $params);

		// Added to unset the previous record's related listview session values
		if (isset($_SESSION['rlvs']))
			unset($_SESSION['rlvs']);

		$getBlockInfo = getDetailBlockInformation($module, $result, $col_fields, $tabid, $block_label);
	}
	else {
		if ($info_type != '') {
			$profileList = array($profile);
				$sql = "SELECT vtiger_field.* FROM vtiger_field INNER JOIN vtiger_profile2field ON vtiger_profile2field.fieldid=vtiger_field.fieldid INNER JOIN vtiger_def_org_field ON vtiger_def_org_field.fieldid=vtiger_field.fieldid  WHERE vtiger_field.tabid=? AND vtiger_field.block IN (" . generateQuestionMarks($blockid_list) . ") AND $display_type_check AND info_type = ? AND vtiger_profile2field.visible=0 AND vtiger_profile2field.readonly = 0 AND vtiger_def_org_field.visible=0 AND vtiger_profile2field.profileid IN (" . generateQuestionMarks($profileList) . ") and vtiger_field.presence in (0,2) GROUP BY vtiger_field.fieldid ORDER BY block,sequence";
				$params = array($tabid, $blockid_list, $info_type, $profileList);
				//Postgres 8 fixes
				if ($adb->dbType == "pgsql")
					$sql = fixPostgresQuery($sql, $log, 0);
		}
		else {
			$profileList = array("$profile");
				$sql = "SELECT vtiger_field.* FROM vtiger_field INNER JOIN vtiger_profile2field ON vtiger_profile2field.fieldid=vtiger_field.fieldid INNER JOIN vtiger_def_org_field ON vtiger_def_org_field.fieldid=vtiger_field.fieldid  WHERE vtiger_field.tabid=? AND vtiger_field.block IN (" . generateQuestionMarks($blockid_list) . ") AND $display_type_check AND vtiger_profile2field.visible=0 AND vtiger_profile2field.readonly = 0 AND vtiger_def_org_field.visible=0 AND vtiger_profile2field.profileid IN (" . generateQuestionMarks($profileList) . ") and vtiger_field.presence in (0,2) GROUP BY vtiger_field.fieldid ORDER BY block,sequence";
				$params = array($tabid, $blockid_list, $profileList);
				//Postgres 8 fixes
				if ($adb->dbType == "pgsql")
					$sql = fixPostgresQuery($sql, $log, 0);
		}
		$result = $adb->pquery($sql, $params);
		$getBlockInfo = getBlockInformation($module, $result, $col_fields, $tabid, $block_label, $mode);
	}
	$log->debug("Exiting getBlocks method ...");
	if (count($getBlockInfo) > 0) {
		foreach ($getBlockInfo as $label => $contents) {
			if (empty($getBlockInfo[$label])) {
				unset($getBlockInfo[$label]);
			}
		}
	}
	return $getBlockInfo;
}
public function __call($name, $arguments) {
		require_once 'modules/cbMap/processmap/'.$name.'.php';
		$processmap = new $name($this);
		$ret = $processmap->processMap($arguments);
		return $ret;
	}
public static function getMapByID($cbmapid) {
		$cbmap = new cbMap();
		$cbmap->retrieve_entity_info($cbmapid, 'cbMap');
		return $cbmap;
	}

	public static function getMapByName($name,$type='') {
		global $adb;
		$sql = 'select cbmapid
			from vtiger_cbmap
			inner join vtiger_crmentity on crmid=cbmapid
			where deleted=0 and mapname=?';
		$prm = array($name);
		if ($type!='') {
			$sql .= ' and maptype=?';
			$prm[] = $type;
		}
		$mrs = $adb->pquery($sql, $prm);
		if ($mrs and $adb->num_rows($mrs)>0) {
			$cbmapid = $adb->query_result($mrs, 0, 0);
			$cbmap = new cbMap();
			$cbmap->retrieve_entity_info($cbmapid, 'cbMap');
			return $cbmap;
		} else {
			return null;
		}
	}
	public static function getMapIdByName($name) {
		global $adb;
		$mrs = $adb->pquery('select cbmapid
			from vtiger_cbmap
			inner join vtiger_crmentity on crmid=cbmapid
			where deleted=0 and mapname=?', array($name));
		if ($mrs and $adb->num_rows($mrs)>0) {
			$cbmapid = $adb->query_result($mrs, 0, 0);
			return $cbmapid;
		} else {
			return 0;
		}
	}
}
?>
