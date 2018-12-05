<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
require_once 'data/CRMEntity.php';
require_once 'data/Tracker.php';
include_once 'vtlib/Vtiger/Utils/StringTemplate.php';
include_once 'vtlib/Vtiger/LinkData.php';

class BusinessActions extends CRMEntity {
	public $db;
	public $log;

	public $table_name = 'vtiger_businessactions';
	public $table_index = 'businessactionsid';
	public $column_fields = array();

	/** Indicator if this is a custom module or standard module */
	public $IsCustomModule = true;
	public $HasDirectImageField = false;

	/**
	 * Mandatory table for supporting custom fields.
	 */
	public $customFieldTable = array('vtiger_businessactionscf', 'businessactionsid');
	// related_tables variable should define the association (relation) between dependent tables
	// FORMAT: related_tablename => array(related_tablename_column[, base_tablename, base_tablename_column[, related_module]] )
	// Here base_tablename_column should establish relation with related_tablename_column
	// NOTE: If base_tablename and base_tablename_column are not specified, it will default to modules (table_name, related_tablename_column)
	// Uncomment the line below to support custom field columns on related lists
	// var $related_tables = array('vtiger_MODULE_NAME_LOWERCASEcf' => array('MODULE_NAME_LOWERCASEid', 'vtiger_MODULE_NAME_LOWERCASE',
	// 'MODULE_NAME_LOWERCASEid', 'MODULE_NAME_LOWERCASE'));

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	public $tab_name = array('vtiger_crmentity', 'vtiger_businessactions', 'vtiger_businessactionscf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	public $tab_name_index = array(
		'vtiger_crmentity' => 'crmid',
		'vtiger_businessactions' => 'businessactionsid',
		'vtiger_businessactionscf' => 'businessactionsid',
	);

	/**
	 * Mandatory for Listing (Related listview)
	 */
	public $list_fields = array(
		/* Format: Field Label => array(tablename => columnname) */
		// tablename should not have prefix 'vtiger_'
		'businessactions_no' => array('businessactions' => 'businessactions_no'),
		'linklabel' => array('businessactions' => 'linklabel'),
		'linktype' => array('businessactions' => 'elementtype_action'),
		'module_list' => array('businessactions' => 'module_list'),
		'active' => array('businessactions' => 'active'),
		'Assigned To' => array('crmentity' => 'smownerid'),
	);
	public $list_fields_name = array(
		/* Format: Field Label => fieldname */
		'businessactions_no' => 'businessactions_no',
		'linklabel' => 'linklabel',
		'linktype' => 'elementtype_action',
		'module_list' => 'module_list',
		'active' => 'active',
		'Assigned To' => 'assigned_user_id',
	);

	// Make the field link to detail view from list view (Fieldname)
	public $list_link_field = 'businessactions_no';

	// For Popup listview and UI type support
	public $search_fields = array(
		/* Format: Field Label => array(tablename => columnname) */
		// tablename should not have prefix 'vtiger_'
		'businessactions_no' => array('businessactions' => 'businessactions_no'),
		'linklabel' => array('businessactions' => 'linklabel'),
		'linktype' => array('businessactions' => 'elementtype_action'),
		'module_list' => array('businessactions' => 'module_list'),
		'active' => array('businessactions' => 'active'),
		'Assigned To' => array('crmentity' => 'smownerid'),
	);
	public $search_fields_name = array(
		/* Format: Field Label => fieldname */
		'businessactions_no' => 'businessactions_no',
		'linklabel' => 'linklabel',
		'linktype' => 'elementtype_action',
		'module_list' => 'module_list',
		'active' => 'active',
		'Assigned To' => 'assigned_user_id',
	);

	// For Popup window record selection
	public $popup_fields = array('businessactions_no');

	// Placeholder for sort fields - All the fields will be initialized for Sorting through initSortFields
	public $sortby_fields = array();

	// For Alphabetical search
	public $def_basicsearch_col = 'businessactions_no';

	// Column value to use on detail view record text display
	public $def_detailview_recname = 'businessactions_no';

	// Required Information for enabling Import feature
	public $required_fields = array('businessactions_no' => 1);

	// Callback function list during Importing
	public $special_functions = array('set_import_assigned_user');

	public $default_order_by = 'businessactions_no';
	public $default_sort_order = 'ASC';
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vtiger_field.fieldname values.
	public $mandatory_fields = array('createdtime', 'modifiedtime', 'businessactions_no');

	// Ignore module while selection
	const IGNORE_MODULE = -1;

	public function save_module($module) {
		if ($this->HasDirectImageField) {
			$this->insertIntoAttachment($this->id, $module);
		}
	}

	private static function convertToObject($tabid, $valuemap) {
		$link_obj = new Vtiger_Link();
		$link_obj->tabid = (string) $tabid;
		$link_obj->linkid = $valuemap['businessactionsid'];
		$link_obj->linktype       = $valuemap['elementtype_action'];
		$link_obj->linklabel      = $valuemap['linklabel'];
		$link_obj->linkurl        = decode_html($valuemap['linkurl']);
		$link_obj->linkicon       = decode_html($valuemap['linkicon']);
		$link_obj->sequence       = $valuemap['sequence'];
		$link_obj->status         = (isset($valuemap['status']) ? $valuemap['status'] : false);
		$link_obj->handler_path   = $valuemap['handler_path'];
		$link_obj->handler_class  = $valuemap['handler_class'];
		$link_obj->handler        = $valuemap['handler'];
		$link_obj->onlyonmymodule = $valuemap['onlyonmymodule'];
		return $link_obj;
	}

	/**
	 * Invoked when special actions are performed on the module.
	 * @param String Module name
	 * @param String Event Type (module.postinstall, module.disabled, module.enabled, module.preuninstall)
	 */
	public function vtlib_handler($modulename, $event_type) {
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
	 * Get all the link related to module based on type
	 * @param Integer Module ID
	 * @param mixed String or List of types to select
	 * @param Map Key-Value pair to use for formating the link url
	 * @param Integer User Id
	 * @param Integer Record Id
	 */
	public static function getAllByType($tabid, $type = false, $parameters = false, $userid = null, $recordid = null) {
		global $adb, $current_user, $currentModule;

		$accumulator = array();

		$module_sql = '';
		if ($tabid != self::IGNORE_MODULE) {
			$module_name = getTabModuleName($tabid);
			$module_sql = " AND (module_list = '".$module_name."' OR module_list LIKE '".$module_name." %' OR module_list LIKE '% ".$module_name." %' OR module_list LIKE '% ".$module_name."') ";
		}

		$multitype = false;

		if ($userid == null) {
			$userid = $current_user->id;
		}

		if ($recordid == null) {
			$recordid = '';
		}

		$type_sql = '';

		if ($type) {
			// Multiple link type selection
			if (is_array($type)) {
				$multitype = true;
				$type_sql = $adb->convert2Sql(' AND elementtype_action IN ('.Vtiger_Utils::implodestr('?', count($type), ',') .') ', $adb->flatten_array($type));
				if ($tabid == self::IGNORE_MODULE && !empty($currentModule)) {
					$module_sql = " AND ((onlyonmymodule AND (module_list = '".$currentModule."' OR module_list LIKE '".$currentModule." %' OR module_list LIKE '% ".$currentModule." %' OR module_list LIKE '% ".$currentModule."')) OR !onlyonmymodule) ";
				}
			} else {
				$type_sql = $adb->convert2Sql(' AND elementtype_action = ?', array($type));
			}
		}

		$query = 'SELECT businessactionsid,
				elementtype_action,
				linklabel,
				linkurl,
				linkicon,
				sequence,
				handler_path,
				handler_class,
				handler,
				onlyonmymodule,
				brmap,
				mandatory
			FROM vtiger_businessactions INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_businessactions.businessactionsid
			WHERE vtiger_crmentity.deleted = 0  AND active = 1 '.$module_sql.$type_sql;

		$orderby = ' ORDER BY elementtype_action, sequence';

		$role_condition = "EXISTS(SELECT 1
			FROM vtiger_user2role
			WHERE vtiger_user2role.userid=? AND vtiger_businessactions.acrole LIKE CONCAT('%', vtiger_user2role.roleid, '%')
		)";
		$role_condition = $adb->convert2Sql($role_condition, array($userid));

		$user_condition = $adb->convert2sql('vtiger_crmentity.smownerid = ?', array($userid));

		require_once 'include/utils/GetUserGroups.php';
		$UserGroups = new GetUserGroups();
		$UserGroups->getAllUserGroups($userid);

		$group_condition = '';
		if (count($UserGroups->user_groups)>0) {
			$groups = implode(',', $UserGroups->user_groups);
			$group_condition = 'OR vtiger_crmentity.smownerid IN ('.$groups.') ';
		}

		$where_ext = 'AND (mandatory=1 OR '.$role_condition.' OR '.$user_condition. ' '.$group_condition.')';
		$sql = $query.$where_ext.$orderby;

		$business_actions = $adb->query($sql);

		while ($row = $adb->fetch_array($business_actions)) {
			$accumulator[] = $row;
		}

		$strtemplate = new Vtiger_StringTemplate();
		if ($parameters) {
			foreach ($parameters as $key => $value) {
				$strtemplate->assign($key, $value);
			}
		}

		$result = array();
		if ($multitype) {
			foreach ($type as $t) {
				$result[$t] = array();
			}
		}

		foreach ($accumulator as $row) {
			/** Should the widget be shown */
			$return = cbEventHandler::do_filter('corebos.filter.link.show', array($row, $type, $parameters));
			if ($return == false) {
				continue;
			}

			//Get Vtiger_Link object
			$link = self::convertToObject($tabid, $row);

			if (!empty($row['handler_path']) && isInsideApplication($row['handler_path'])) {
				checkFileAccessForInclusion($row['handler_path']);
				require_once $row['handler_path'];
				$linkData = new Vtiger_LinkData($link, $current_user);
				$ignore = call_user_func(array($row['handler_class'], $row['handler']), $linkData);
				if (!$ignore) {
					self::log("Ignoring Link ... ".var_export($row, true));
					continue;
				}
			}

			if ($row['brmap'] > 0 && !coreBOS_Rule::evaluate($row['brmap'], $recordid)) {
				continue;
			}

			if ($parameters) {
				$link->linkurl = $strtemplate->merge($link->linkurl);
				$link->linkicon= $strtemplate->merge($link->linkicon);
			}

			if ($multitype) {
				$result[$link->linktype][] = $link;
			} else {
				$result[$link->linktype] = $link;
			}
		}

		return $result;
	}

	/**
	 * Add link given module
	 * @param Integer Module ID
	 * @param String Link Type (like DETAILVIEW). Useful for grouping based on pages.
	 * @param String Label to display
	 * @param String HREF value or URL to use for the link
	 * @param String ICON to use on the display
	 * @param Integer Order or sequence of displaying the link
	 */
	public static function addLink($tabid, $type, $label, $url, $iconpath = '', $sequence = 0, $handlerInfo = null, $onlyonmymodule = false) {
		global $adb;
		$module_name = getTabModuleName($tabid);

		$linkcheck = $adb->pquery(
			'SELECT businessactionsid
				FROM vtiger_businessactions INNER JOIN vtiger_crmentity
				WHERE vtiger_crmentity.crmid = vtiger_businessactions.businessactionsid
				AND vtiger_crmentity.deleted = 0
				AND module_list = ?
				AND elementtype_action = ?
				AND linkurl = ?
				AND linkicon = ?
				AND linklabel = ?',
			array($module_name, $type, $url, $iconpath, $label)
		);

		if (!$adb->num_rows($linkcheck)) {
			$newBA = new BusinessActions();

			$newBA->column_fields['linktype'] = $type;
			$newBA->column_fields['linklabel'] = $label;
			$newBA->column_fields['sequence'] = (int) $sequence;
			$newBA->column_fields['module_list'] = $module_name;
			$newBA->column_fields['onlyonmymodule'] = $onlyonmymodule;
			$newBA->column_fields['linkicon'] = $iconpath;
			$newBA->column_fields['active'] = 1;
                        $newBA->column_fields['reference']=$label;
                        $newBA->column_fields['linkurl']=html_entity_decode($url);
                        $newBA->column_fields['assigned_user_id'] = 1;
                        $newBA->column_fields['moduleactions'] = $module_name;
                        $newBA->column_fields['elementtype_action'] = $type;
                        $newBA->column_fields['actions_status'] = 'Active';

			if (!empty($handlerInfo)) {
				$newBA->column_fields['handler_path'] = (isset($handlerInfo['path']) ? $handlerInfo['path'] : '');
				$newBA->column_fields['handler_class'] = (isset($handlerInfo['class']) ? $handlerInfo['class'] : '');
				$newBA->column_fields['handler'] = (isset($handlerInfo['method']) ? $handlerInfo['method'] : '');
			}

			$newBA->save('BusinessActions');
		}
	}

	/**
	 * Delete link of the module
	 * @param Integer Module ID
	 * @param String Link Type (like DETAILVIEW). Useful for grouping based on pages.
	 * @param String Display label
	 * @param String URL of link to lookup while deleting
	 */
	public static function deleteLink($tabid, $type, $label, $url = false) {
		global $adb;
		$module_name = getTabModuleName($tabid);

		if ($url) {
			$ba = $adb->pquery(
				'SELECT vtiger_businessactions.businessactionsid
					FROM vtiger_businessactions
					INNER JOIN vtiger_crmentity ON vtiger_businessactions.businessactionsid = vtiger_crmentity.crmid
						AND vtiger_crmentity.deleted = 0
						AND vtiger_businessactions.module_list = ?
						AND vtiger_businessactions.elementtype_action = ?
						AND vtiger_businessactions.linklabel = ?
						AND vtiger_businessactions.linkurl = ?',
				array($module_name, $type, $label, $url)
			);
		} else {
			$ba = $adb->pquery(
				'SELECT vtiger_businessactions.businessactionsid
					FROM vtiger_businessactions
					INNER JOIN vtiger_crmentity ON vtiger_businessactions.businessactionsid = vtiger_crmentity.crmid
						AND vtiger_crmentity.deleted = 0
						AND vtiger_businessactions.module_list = ?
						AND vtiger_businessactions.elementtype_action = ?
						AND vtiger_businessactions.linklabel = ?',
				array($module_name, $type, $label)
			);
		}

		$countba = $adb->num_rows($ba);

		for ($i = 0; $i < $countba; $i++) {
			$recordid = $adb->query_result($ba, $i, "businessactionsid");
			$focus = CRMEntity::getInstance('BusinessActions');
			DeleteEntity('BusinessActions', 'BusinessActions', $focus, $recordid, 0);
		}
	}

	/**
	 * Delete all links related to module
	 * @param Integer Module ID.
	 */
	public static function deleteAll($tabid) {

		global $adb;
		$module_name = getTabModuleName($tabid);

		$ba = $adb->pquery(
			'SELECT vtiger_businessactions.businessactionsid
				FROM vtiger_businessactions
				INNER JOIN vtiger_crmentity ON vtiger_businessactions.businessactionsid = vtiger_crmentity.crmid
					AND vtiger_crmentity.deleted = 0
					AND vtiger_businessactions.module_list = ?',
			array($module_name)
		);

		$countba = $adb->num_rows($ba);

		for ($i = 0; $i < $countba; $i++) {
			$recordid = $adb->query_result($ba, $i, 'businessactionsid');
			$focus = CRMEntity::getInstance('BusinessActions');
			DeleteEntity('BusinessActions', 'BusinessActions', $focus, $recordid, 0);
		}
	}

	public static function updateLink($tabId, $businessActionId, $linkInfo = array()) {
		if ($linkInfo && is_array($linkInfo)) {
			include_once 'include/Webservices/Revise.php';
			global $adb, $current_user;

			$module_name = getTabModuleName($tabId);
			$linkInfo['id'] = vtws_getEntityId('BusinessActions') . 'x' . $businessActionId;

			if (!empty($linkInfo['elementtype_action'])) {
				$linkInfo['linktype'] = $linkInfo['elementtype_action'];
			}

			if (!empty($linkInfo['module_list'])) {
				$linkInfo['module_list'] = $module_name;
			}

			if (isset($linkInfo['status'])) {
				$linkInfo['active'] = $linkInfo['status'];
			}

			$businessAction = $adb->pquery(
				'SELECT 1 
				FROM vtiger_businessactions
				INNER JOIN vtiger_crmentity ON vtiger_businessactions.businessactionsid = vtiger_crmentity.crmid
					AND vtiger_crmentity.deleted = 0
					AND vtiger_businessactions.module_list = ?
					AND vtiger_businessactions.businessactionsid = ?',
				array($module_name, $businessActionId)
			);

			if ($adb->num_rows($businessAction)) {
				vtws_revise($linkInfo, $current_user);
			}
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
        
        public static function executeAction($recordid,$outputType,$recarray=null,$confirmVal,$actionid,$palid){
            global $root_directory,$log;
            include_once('data/CRMEntity.php');
            $res_logic=$this->runBusinessLogic2($this->id);
            if($res_logic){
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
            }
            return $result;
        }
        
        public static function executeAction1($parameters){
            global $root_directory,$log;
            include_once('data/CRMEntity.php');
            $res_logic=$this->runBusinessLogic2($this->id);
            if($res_logic){
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
            }
            return $scriptName($parameters);
          }
        
        public static function runBusinessLogic($record) {
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
        
        public static function runBusinessLogic2($therecid) {
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
