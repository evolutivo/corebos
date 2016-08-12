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
require_once('data/Tracker.php');

class Entitylog extends CRMEntity {
	var $db, $log; // Used in class functions of CRMEntity

	var $table_name = 'vtiger_entitylog';
	var $table_index= 'entitylogid';
	var $column_fields = Array();

	/** Indicator if this is a custom module or standard module */
	var $IsCustomModule = true;
	var $HasDirectImageField = false;
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('vtiger_entitylogcf', 'entitylogid');

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('vtiger_crmentity', 'vtiger_entitylog', 'vtiger_entitylogcf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'vtiger_crmentity' => 'crmid',
		'vtiger_entitylog'   => 'entitylogid',
                'vtiger_entitylogcf' => 'entitylogid');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array (
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Entitylog Name'=> Array('entitylog'=> 'entitylogname'),
                'Related to'=>Array('entitylog'=> 'relatedto'),                
                'User'=>Array('entitylog'=> 'user'),
                'Related Module'=>Array('entitylog'=> 'tabid'),
                'Changes Message'=>Array('entitylog'=> 'finalstate'),
		'Assigned To' => Array('crmentity'=> 'smownerid')
	);
	var $list_fields_name = Array(
		/* Format: Field Label => fieldname */
		'Entitylog Name'=> 'entitylogname',
                'Related to'=>'relatedto',
                'User'=>'user',
                'Related Module'=>'tabid',
                'Changes Message'=>'finalstate',
		'Assigned To' => 'assigned_user_id'
	);

	// Make the field link to detail view from list view (Fieldname)
	var $list_link_field = 'entitylogname';

	// For Popup listview and UI type support
	var $search_fields = Array(
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Entitylog Name'=> Array('entitylog'=> 'entitylogname')
	);
	var $search_fields_name = Array(
		/* Format: Field Label => fieldname */
		'Entitylog Name'=> 'entitylogname'
	);

	// For Popup window record selection
	var $popup_fields = Array('entitylogname');

	// Placeholder for sort fields - All the fields will be initialized for Sorting through initSortFields
	var $sortby_fields = Array();

	// For Alphabetical search
	var $def_basicsearch_col = 'entitylogname';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'entitylogname';

	// Required Information for enabling Import feature
	var $required_fields = Array('entitylogname'=>1);

	// Callback function list during Importing
	var $special_functions = Array('set_import_assigned_user');

	var $default_order_by = 'entitylogname';
	var $default_sort_order='ASC';
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vtiger_field.fieldname values.
	var $mandatory_fields = Array('createdtime', 'modifiedtime', 'entitylogname');

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
        /*
         * function to create norm or denorm index in elastic
         */
        function create_elastic_index($moduleName,$id,$changevalues){
         global $adb,$current_user;
         include_once("modules/LoggingConf/LoggingUtils.php");
         $tabid=getTabid($moduleName); 
         $type=explode(",",getEntitylogtype($tabid));
         $queryel=getqueryelastic($tabid);
         $indextype=getEntitylogindextype($tabid);
         require_once("modules/Users/CreateUserPrivilegeFile.php");
         require_once("include/utils/GetUserGroups.php");
         $q=$adb->pquery("select smownerid from vtiger_crmentity  where crmid=?",array($id));
         $owner=$adb->query_result($q,0,"smownerid");
         $role=$adb->query("select parentrole,vtiger_role.roleid from vtiger_user2role join vtiger_role on vtiger_role.roleid=vtiger_user2role.roleid  where vtiger_user2role.userid=$owner");
         $current_user_roles=$adb->query_result($role,0,"roleid");
    //$roleid=$adb->query_result($role,0,"parentrole");
         $parrol=getParentRole($current_user_roles);
         $roleid=implode("::",$parrol);
         $userGroupFocus=new GetUserGroups();
         $userGroupFocus->getAllUserGroups($owner);
         $current_user_groups= $userGroupFocus->user_groups;
         if(count($current_user_groups)!=0)
         $grpid='::'.implode("::",$current_user_groups);
         $def_org_share=getAllDefaultSharingAction();
         $arr=getUserModuleSharingRoles($moduleName,$owner,$def_org_share ,$current_user_roles,$parrol,$current_user_groups);
         $gr=$adb->pquery("select * from vtiger_groups where groupid=?",array($owner));
         if($adb->num_rows($gr)==0){
         if(count(array_keys($arr['read']['ROLE']))!=0)
         $roleid.='::'.implode('::',array_keys($arr['read']['ROLE']));}
         else $roleid.=implode('::',array_keys($arr['read']['GROUP']));
         $roleid.='::'.$owner;
         $tab=$adb->query("select * from  vtiger_entityname where tabid=$tabid");
         $tableid=$adb->query_result($tab,0,'tablename').'.'.$adb->query_result($tab,0,'entityidfield');
         $ip=GlobalVariable::getVariable('ip_elastic_server', '',1);
         $fl=$adb->pquery("select fieldlabel from vtiger_elastic_indexes where elasticname='$indextype'");
         $fldlabel1=explode(",", $adb->query_result($fl,0,0));
         $user="1";
         $update_log = unserialize($changevalues);
                  $lines = array();
                  foreach($update_log as $data) {
                    $query = "select fieldlabel,uitype,columnname,fieldid from vtiger_field where tabid={$tabid} and fieldname='{$data['fieldname']}'";
                    $res = $adb->query($query);
                    $fieldlabel = $adb->query_result($res, 0, 0);
                    $uitype = $adb->query_result($res, 0, 1);
                    $columnname = $adb->query_result($res, 0, 2);
                    $fieldid = $adb->query_result($res, 0, 3);
                    if (in_array($uitype,array(10)))
                    {                     
                         $idold=$data['oldvalue'];
                         $relatedModule=$adb->query_result($adb->pquery("Select setype from vtiger_crmentity where crmid=?",array($idold)),0,0);
                         $data['oldvalue']=  getEntityName($relatedModule, $idold);
                         $data['oldvalue']=$data['oldvalue'][$idold];

                         $idnew= $data['newvalue'];
                         $relatedModule=$adb->query_result($adb->pquery("Select setype from vtiger_crmentity where crmid=?",array($idnew)),0,0);
                         $data['newvalue']=getEntityName($relatedModule, $idnew);
                         $data['newvalue']=$data['newvalue'][$idnew]; 
                     
                    }
                   if(is_array($data))
                    {
                    $lines[] ='fieldname='.$fieldlabel.';oldvalue='.$data['oldvalue'].';newvalue='.$data['newvalue'].';';
                    } 
                    else{
                    $lines[] ='fieldname='.$fieldlabel.';oldvalue='.$data.';newvalue='.$data.';';
                    }
                    }
         $lines=implode("",$lines);
     
         if(in_array('normalized',$type)) {
         $endpointUrl2 = "http://$ip:9200/$indextype/norm";
         $fields1=$adb->pquery("$queryel and $tableid=?",array($id));
         $fld=array();
         $fld['roles']=$roleid;
         $fld['changedvalues']=$lines;
         $fld['userchange']=$user;
         $fld['urlrecord']="<a href='index.php?module=$moduleName&action=DetailView&record=$id'>Details</a>";
         unset($fields1->fields[0]);
         $in=0;
         foreach($fields1->fields as $key => $value) {
            if( floatval($key)) {
                 unset($fields1->fields[$key]);
            }
            else {
            $fldlabel=$fldlabel1[$in];
            $fld["$fldlabel"]=$value;
            $in++;}
        }
        $channel11 = curl_init();
        //curl_setopt($channel1, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($channel11, CURLOPT_URL, $endpointUrl2);
        curl_setopt($channel11, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($channel11, CURLOPT_POST, true);
        //curl_setopt($channel11, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($channel11, CURLOPT_POSTFIELDS, json_encode($fld));
        curl_setopt($channel11, CURLOPT_CONNECTTIMEOUT, 100);
        curl_setopt($channel11, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($channel11, CURLOPT_TIMEOUT, 1000);
        $response2 = curl_exec($channel11);
       }
      
       if(in_array('denormalized',$type)) {
        $getid=$id;
        $endpointUrl12 = "http://$ip:9200/$indextype/denorm/_search?pretty";
        $pk=$adb->query_result($tab,0,'entityidfield');
        $fields1 =array('query'=>array("term"=>array("$pk"=>"$getid")));
        $channel1 = curl_init();
        curl_setopt($channel1, CURLOPT_URL, $endpointUrl12);
        curl_setopt($channel1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($channel1, CURLOPT_POST, true);
        //curl_setopt($channel1, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($channel1, CURLOPT_POSTFIELDS, json_encode($fields1));
        curl_setopt($channel1, CURLOPT_CONNECTTIMEOUT, 100);
        curl_setopt($channel1, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($channel1, CURLOPT_TIMEOUT, 1000);
        $response1 = json_decode(curl_exec($channel1));
        //if(strstr($response1->error,'IndexMissingException'))
        //{$ij=1;
        //} 
        $ij=$response1->hits->hits[0]->_id;
        if($ij!='' && $ij!=null && $response1->hits->total!=0 ){
        $endpointUrl2 = "http://$ip:9200/$indextype/denorm/$ij";
        $fields1=$adb->pquery("$queryel and $tableid=?",array($id));
        $fld=array();
        $fld['roles']=$roleid;
        $fld['changedvalues']=$lines;
        $fld['userchange']=$user;
        $fld['urlrecord']="<a href='index.php?module=$moduleName&action=DetailView&record=$id'>Details</a>";
        unset($fields1->fields[0]);
        $in=0;
        foreach($fields1->fields as $key => $value) {
            if( floatval($key)) {
                 unset($fields1->fields[$key]);
            }
            else {
            $fldlabel=$fldlabel1[$in];
            $fld["$fldlabel"]=$value;
            $in++;}
        }
        $channel11 = curl_init();
        //curl_setopt($channel1, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($channel11, CURLOPT_URL, $endpointUrl2);
        curl_setopt($channel11, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($channel11, CURLOPT_POST, true);
        curl_setopt($channel11, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($channel11, CURLOPT_POSTFIELDS, json_encode($fld));
        curl_setopt($channel11, CURLOPT_CONNECTTIMEOUT, 100);
        curl_setopt($channel11, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($channel11, CURLOPT_TIMEOUT, 1000);
        $response2 = curl_exec($channel11);
        }
        else {
        $endpointUrl2 = "http://$ip:9200/$indextype/denorm";
        $fields1=$adb->pquery("$queryel and $tableid=?",array($id));
        $fld=array();
        $fld['roles']=$roleid;
        $fld['changedvalues']=$lines;
        $fld['userchange']=$user;
        $fld['urlrecord']="<a href='index.php?module=$moduleName&action=DetailView&record=$id'>Details</a>";
        unset($fields1->fields[0]);
        $in=0;
        foreach($fields1->fields as $key => $value) {
            if( floatval($key)) {
                 unset($fields1->fields[$key]);
            }
            else {
            $fldlabel=$fldlabel1[$in];
            $fld["$fldlabel"]=$value;
            $in++;}
        }
        $channel11 = curl_init();
        //curl_setopt($channel1, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($channel11, CURLOPT_URL, $endpointUrl2);
        curl_setopt($channel11, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($channel11, CURLOPT_POST, true);
        //curl_setopt($channel11, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($channel11, CURLOPT_POSTFIELDS, json_encode($fld));
        curl_setopt($channel11, CURLOPT_CONNECTTIMEOUT, 100);
        curl_setopt($channel11, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($channel11, CURLOPT_TIMEOUT, 1000);
        $response23 = curl_exec($channel11); 
    }
    }     
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
