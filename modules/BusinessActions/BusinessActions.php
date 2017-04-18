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
        
        function runBusinessLogic($record) {
        global $current_user, $adb, $log;
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
