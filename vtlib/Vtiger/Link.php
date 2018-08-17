<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
include_once 'vtlib/Vtiger/Utils.php';
include_once 'vtlib/Vtiger/Utils/StringTemplate.php';
include_once 'vtlib/Vtiger/LinkData.php';

/**
 * Provides API to handle custom links
 * @package vtlib
 */
class Vtiger_Link {
	public $tabid;
	public $linkid;
	public $linktype;
	public $linklabel;
	public $linkurl;
	public $linkicon;
	public $sequence;
	public $status = false;
	public $handler_path;
	public $handler_class;
	public $handler;
	public $onlyonmymodule = false;

	// Ignore module while selection
	const IGNORE_MODULE = -1;

	/**
	 * Constructor
	 */
	public function __construct() {
	}

	/**
	 * Initialize this instance.
	 */
	public function initialize($valuemap) {
		$this->tabid          = $valuemap['tabid'];
		$this->linkid         = $valuemap['linkid'];
		$this->linktype       = $valuemap['linktype'];
		$this->linklabel      = $valuemap['linklabel'];
		$this->linkurl        = decode_html($valuemap['linkurl']);
		$this->linkicon       = decode_html($valuemap['linkicon']);
		$this->sequence       = $valuemap['sequence'];
		$this->status         = (isset($valuemap['status']) ? $valuemap['status'] : false);
		$this->handler_path   = $valuemap['handler_path'];
		$this->handler_class  = $valuemap['handler_class'];
		$this->handler        = $valuemap['handler'];
                $this->related_tab=$valuemap['related_tab'];
		$this->onlyonmymodule = $valuemap['onlyonmymodule'];
	}
        
        function initialize_BA($valuemap) {
		$this->tabid  = getTabid($valuemap['moduleactions']);
		$this->linkid = $valuemap['businessactionsid'];
		$this->linktype=$valuemap['elementtype_action'];
                $this->output_type =$valuemap['output_type'];
                $this->isnode =$valuemap['isnode'];
		$this->linklabel=$valuemap['reference'];
		$this->linkurl  =decode_html($valuemap['linkurl']);
                if(empty($this->linkurl)){
                    $this->linkurl="javascript:runJSONAction('".$this->linkid."','recordid=\$RECORD$','".$this->output_type."')";
                }
                if(($this->isnode==true || $this->isnode==1 || $this->isnode=='1') && empty($this->linkurl)){
                    $this->linkurl="javascript:runNodeAction('".$this->linkid."','recordid=\$RECORD$','".$this->output_type."')";
                }
                $this->linkicon =decode_html($valuemap['linkicon']);
                $newLinkicon=self::retrieveAttachment($this->linkid,$this->linkicon);
                if($newLinkicon!==''){
                    $this->linkicon=$newLinkicon;
                }
		$this->sequence =$valuemap['sequence'];
		$this->status   =$valuemap['actions_status'];
		$this->handler_path= $valuemap['handler_path'];
		$this->handler_class =$valuemap['handler_class'];
		$this->businessrules_action =$valuemap['businessrules_action'];
                $this->linktobrules =$valuemap['linktobrules'];
                $this->linktomapmodule =$valuemap['linktomapmodule'];
                $this->sequencers =$valuemap['sequencers'];
                $this->actions_type=$valuemap['actions_type'];
                $this->parameter1=$valuemap['parameter1'];
                $this->businessactionsid=$valuemap['businessactionsid'];
                $this->script_name=$valuemap['script_name'];
                $this->actions_block=$valuemap['actions_block'];
                $this->related_tab=$valuemap['related_tab'];
                $this->top_widget=$valuemap['top_widget'];
	}


        /**
	 * Get Attachment path.
	 */
	public static function retrieveAttachment($linkid,$linkicon) {
		global $adb;
                $newLinkicon='';
                $attachments = $adb->pquery('select vtiger_crmentity.crmid,vtiger_attachments.path
                                from vtiger_seattachmentsrel
				inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_seattachmentsrel.attachmentsid
				inner join vtiger_attachments on vtiger_crmentity.crmid=vtiger_attachments.attachmentsid
				where vtiger_seattachmentsrel.crmid=? and vtiger_attachments.name=?', array($linkid,$linkicon));
		if($attachments && $adb->num_rows($attachments)>0){
                    $attachmentid = $adb->query_result($attachments,0,'crmid');
                    $path = $adb->query_result($attachments,0,'path');
                    $newLinkicon =$path."/".$attachmentid."_".$linkicon;
                }
                return $newLinkicon;
	}
        
	/**
	 * Get module name.
	 */
	public function module() {
		if (!empty($this->tabid)) {
			return getTabModuleName($this->tabid);
		}
		return false;
	}

	/**
	 * Get unique id for the insertion
	 */
	public static function __getUniqueId() {
		global $adb;
		return $adb->getUniqueID('vtiger_links');
	}

	/** Cache (Record) the schema changes to improve performance */
	private static $__cacheSchemaChanges = array();

	/**
	 * Initialize the schema (tables)
	 */
	private static function __initSchema() {
		if (empty(self::$__cacheSchemaChanges['vtiger_links'])) {
			if (!Vtiger_Utils::CheckTable('vtiger_links')) {
				Vtiger_Utils::CreateTable(
					'vtiger_links',
					'(linkid INT NOT NULL PRIMARY KEY,
					tabid INT, linktype VARCHAR(20), linklabel VARCHAR(30), linkurl VARCHAR(255), linkicon VARCHAR(100), sequence INT, status INT(1) NOT NULL DEFAULT 1)',
					true
				);
				Vtiger_Utils::ExecuteQuery(
					'CREATE INDEX link_tabidtype_idx on vtiger_links(tabid,linktype)'
				);
			}
			self::$__cacheSchemaChanges['vtiger_links'] = true;
		}
		global $adb;
		$lns=$adb->getColumnNames('vtiger_links');
		if (!in_array('onlyonmymodule', $lns)) {
			$adb->query('ALTER TABLE `vtiger_links` ADD `onlyonmymodule` BOOLEAN NOT NULL DEFAULT FALSE');
		}
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
		self::__initSchema();
		$checkres = $adb->pquery(
			'SELECT linkid FROM vtiger_links WHERE tabid=? AND linktype=? AND linkurl=? AND linkicon=? AND linklabel=?',
			array($tabid, $type, $url, $iconpath, $label)
		);
		if (!$adb->num_rows($checkres)) {
			$uniqueid = self::__getUniqueId();
			$sql = 'INSERT INTO vtiger_links (linkid,tabid,linktype,linklabel,linkurl,linkicon,sequence';
			$params = array($uniqueid, $tabid, $type, $label, $url, $iconpath, (int)$sequence);
			if (!empty($handlerInfo)) {
				$sql .= (', handler_path, handler_class, handler');
				$params[] = (isset($handlerInfo['path']) ? $handlerInfo['path'] : '');
				$params[] = (isset($handlerInfo['class']) ? $handlerInfo['class'] : '');
				$params[] = (isset($handlerInfo['method']) ? $handlerInfo['method'] : '');
			}
			$params[] = $onlyonmymodule;
			$sql .= (', onlyonmymodule) VALUES ('.generateQuestionMarks($params).')');
			$adb->pquery($sql, $params);
                        if(vtlib_isModuleActive('BusinessActions')){
                            require_once('modules/BusinessActions/BusinessActions.php');
                            $action=new BusinessActions();
                            $action->column_fields['reference']=$label;
                            $action->column_fields['linkurl']=html_entity_decode($url);
                            $action->column_fields['linkicon']=$iconpath;
                            $action->column_fields['sequence']=$sequence;
                            $action->column_fields['assigned_user_id'] = 1;
                            $action->column_fields['moduleactions'] = getTabModuleName($tabid);
                            $action->column_fields['elementtype_action'] = $type;
                            $action->column_fields['actions_status'] = 'Active';
                            $action->mode = '';
                            $action->save("BusinessActions"); 
                        }
			self::log("Adding Link ($type - $label) ... DONE");
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
		self::__initSchema();
		if ($url) {
			$adb->pquery(
				'DELETE FROM vtiger_links WHERE tabid=? AND linktype=? AND linklabel=? AND linkurl=?',
				array($tabid, $type, $label, $url)
			);
			self::log("Deleting Link ($type - $label - $url) ... DONE");
		} else {
			$adb->pquery(
				'DELETE FROM vtiger_links WHERE tabid=? AND linktype=? AND linklabel=?',
				array($tabid, $type, $label)
			);
			self::log("Deleting Link ($type - $label) ... DONE");
		}
	}

	/**
	 * Delete all links related to module
	 * @param Integer Module ID.
	 */
	public static function deleteAll($tabid) {
		global $adb;
		self::__initSchema();
		$adb->pquery('DELETE FROM vtiger_links WHERE tabid=?', array($tabid));
		self::log('Deleting Links ... DONE');
	}

	/**
	 * Get all the links related to module
	 * @param Integer Module ID.
	 */
	public static function getAll($tabid) {
		return self::getAllByType($tabid);
	}

	/**
	 * Get all the link related to module based on type
	 * @param Integer Module ID
	 * @param mixed String or List of types to select
	 * @param Map Key-Value pair to use for formating the link url
	 */
        public static function getAllByType($tabid, $type=false, $parameters=false) {
            global $adb, $current_user, $currentModule;
                if(vtlib_isModuleActive('BusinessActions')){
                $qry_actions="Select sequence from vtiger_businessactions join vtiger_crmentity on crmid=businessactionsid where deleted=0 limit 1";
                $res_actions=$adb->query($qry_actions);}
                if($adb->num_rows($res_actions)>0){
                    $instances=self::getAllByType_BA($tabid, $type, $parameters);
                }
                else{
                    $instances=self::getAllByType_Link($tabid, $type, $parameters);
                }
                return $instances;
        }
	public static function getAllByType_Link($tabid, $type=false, $parameters=false) {
		global $adb, $current_user;
		self::__initSchema();

		$multitype = false;
		$orderby = ' order by linktype,sequence'; //MSL
                $join_str='';
                if(vtlib_isModuleActive('NgBlock')){
                $qry_ngblock="Select * from vtiger_ng_block";
                $res_ngblock=$adb->query($qry_ngblock);}
                if($adb->num_rows($res_ngblock)>0){
                    $join_str= ' left join vtiger_ng_block on vtiger_ng_block.id=vtiger_links.linklabel';
                }
		if($type) {
			// Multiple link type selection?
			if (is_array($type)) {
				$multitype = true;

				if ($tabid === self::IGNORE_MODULE) {
					$sql = 'SELECT * FROM vtiger_links WHERE linktype IN ('.
						Vtiger_Utils::implodestr('?', count($type), ',') .') ';
					$params = $type;
					$permittedTabIdList = getPermittedModuleIdList();
					if (count($permittedTabIdList) > 0 && $current_user->is_admin !== 'on') {
						$sql .= ' and tabid IN ('.
							Vtiger_Utils::implodestr('?', count($permittedTabIdList), ',').')';
						$params[] = $permittedTabIdList;
					}
					if (!empty($currentModule)) {
						$sql .= ' and ((onlyonmymodule and tabid=?) or !onlyonmymodule) ';
						$params[] = getTabid($currentModule);
					}
					$result = $adb->pquery($sql . $orderby, array($adb->flatten_array($params)));
				} else {

					$result = $adb->pquery(
						'SELECT * FROM vtiger_links WHERE tabid=? AND linktype IN ('.
						Vtiger_Utils::implodestr('?', count($type), ',') .')' . $orderby,
						array($tabid, $adb->flatten_array($type))
					);
				}
			} else {
				// Single link type selection
				if ($tabid === self::IGNORE_MODULE) {
					$result = $adb->pquery('SELECT * FROM vtiger_links WHERE linktype=?' . $orderby, array($type));
				} else {
					$result = $adb->pquery('SELECT * FROM vtiger_links WHERE tabid=? AND linktype=?' . $orderby, array($tabid, $type));
				}
			}
		} else {
			$result = $adb->pquery('SELECT * FROM vtiger_links WHERE tabid=?' . $orderby, array($tabid));
		}

		$strtemplate = new Vtiger_StringTemplate();
		if ($parameters) {
			foreach ($parameters as $key => $value) {
				$strtemplate->assign($key, $value);
			}
		}

		$instances = array();
		if ($multitype) {
			foreach ($type as $t) {
				$instances[$t] = array();
			}
		}

		while ($row = $adb->fetch_array($result)) {
			/** Should the widget be shown */
			$return = cbEventHandler::do_filter('corebos.filter.link.show', array($row, $type, $parameters));
			if ($return == false) {
				continue;
			}
			$instance = new self();
			$instance->initialize($row);
			if (!empty($row['handler_path']) && isInsideApplication($row['handler_path'])) {
				checkFileAccessForInclusion($row['handler_path']);
				require_once $row['handler_path'];
				$linkData = new Vtiger_LinkData($instance, $current_user);
				$ignore = call_user_func(array($row['handler_class'], $row['handler']), $linkData);
				if (!$ignore) {
					self::log("Ignoring Link ... ".var_export($row, true));
					continue;
				}
			}
			if ($parameters) {
				$instance->linkurl = $strtemplate->merge($instance->linkurl);
				$instance->linkicon= $strtemplate->merge($instance->linkicon);
			}
			if ($multitype) {
				$instances[$instance->linktype][] = $instance;
			} else {
				$instances[$instance->linktype] = $instance;
			}
		}
		return $instances;
	}

        public static function getAllByType_BA($tabid, $type=false, $parameters=false) {
		global $adb,$log,$current_user;
                self::__initSchema();
                $module=  $parameters['MODULE'];
                $record=  $parameters['RECORD'];

		$multitype = false;
                $orderby = ' order by elementtype_action,sequence,sequence_ngblock'; //MSL
                $join_str='';
                if(Vtiger_Utils::CheckTable('vtiger_ng_block')){
                    $join_str= ' left join vtiger_ng_block on vtiger_ng_block.id=reference';
                }
		if($type) {
			// Multiple link type selection?
			if(is_array($type)) { 
				$multitype = true;
				if($tabid === self::IGNORE_MODULE) {
					$sql = 'SELECT * FROM vtiger_businessactions'
                                                . ' INNER JOIN vtiger_crmentity ce ON ce.crmid=vtiger_businessactions.businessactionsid'
                                                . $join_str
                                                . ' where ce.deleted=0  and actions_status="Active"'
                                                . ' and elementtype_action IN ('.
						Vtiger_Utils::implodestr('?', count($type), ',') .') ';
					$params = $type;
					$permittedModuleList = getPermittedModuleNames();
					if(count($permittedModuleList) > 0 && $current_user->is_admin !== 'on') {
						$sql .= ' and moduleactions IN ('.
							Vtiger_Utils::implodestr('?', count($permittedModuleList), ',').')';
						$params[] = $permittedModuleList;
					}
					$result = $adb->pquery($sql . $orderby, Array($adb->flatten_array($params)));
				} else {
					$result = $adb->pquery('SELECT * FROM vtiger_businessactions'
                                                . ' INNER JOIN vtiger_crmentity ce ON ce.crmid=vtiger_businessactions.businessactionsid'
                                                . $join_str
                                                . ' where ce.deleted=0  and actions_status="Active"'
                                                . ' and moduleactions=? and elementtype_action IN ('.
						Vtiger_Utils::implodestr('?', count($type), ',') .')' . $orderby,
							Array($module, $adb->flatten_array($type)));
				}
			} else {
				// Single link type selection
				if($tabid === self::IGNORE_MODULE) {
					$result = $adb->pquery('SELECT * FROM vtiger_businessactions'
                                                . ' INNER JOIN vtiger_crmentity ce ON ce.crmid=vtiger_businessactions.businessactionsid'
                                                . $join_str
                                                . ' where ce.deleted=0  and actions_status="Active"'
                                                . ' and elementtype_action =?' . $orderby, Array($type));
				} else {
					$result = $adb->pquery('SELECT * FROM vtiger_businessactions'
                                                . ' INNER JOIN vtiger_crmentity ce ON ce.crmid=vtiger_businessactions.businessactionsid'
                                                . $join_str
                                                . ' where ce.deleted=0  and actions_status="Active"'
                                                . ' and moduleactions=? and elementtype_action=? ' . $orderby , Array($module, $type));				
				}
			}
		} else {
			$result = $adb->pquery('SELECT * FROM vtiger_businessactions'
                                                . $join_str
                                                . ' INNER JOIN vtiger_crmentity ce ON ce.crmid=vtiger_businessactions.businessactionsid'
                                                . ' where ce.deleted=0  and actions_status="Active"'
                                                . ' and moduleactions=? ' . $orderby, Array($module));
		}
		$strtemplate = new Vtiger_StringTemplate();
		if($parameters) {
			foreach($parameters as $key=>$value) {
                            $strtemplate->assign($key, $value);
                        }
		}

		$instances = Array();
                $instance_block = Array();
		if($multitype) {
			foreach($type as $t) $instances[$t] = Array();
		}

		while($row = $adb->fetch_array($result)){
                        $return = cbEventHandler::do_filter('corebos.filter.link.show', array($row, $type, $parameters));
			if($return == false) continue;
			$instance = new self();
			$instance->initialize_BA($row);
			if(!empty($row['handler_path']) && isFileAccessible($row['handler_path'])) {
				checkFileAccessForInclusion($row['handler_path']);
				require_once $row['handler_path'];
				$linkData = new Vtiger_LinkData($instance, $current_user);
				$ignore = call_user_func(array($row['handler_class'], $row['handler']), $linkData);
				if(!$ignore) {
					self::log("Ignoring Link ... ".var_export($row, true));
					continue;
				}
			}
			if($parameters) {
				$instance->linkurl = $strtemplate->merge($instance->linkurl);
				$instance->linkicon= $strtemplate->merge($instance->linkicon);
			}
                        $res_logic=false;
                        include_once('modules/BusinessActions/BusinessActions.php');
                        $actionfocus=CRMEntity::getInstance("BusinessActions");
                        $actionfocus->retrieve_entity_info($instance->businessactionsid,"BusinessActions");                                
                            if($instance->linktobrules!='' && $instance->linktobrules!=0)
                            $res_logic=$actionfocus->runBusinessLogic($record); 
                            if($res_logic || $instance->linktobrules=='' || $instance->linktobrules==0){ // temporarly condition for showing actions not related to BR
                                if($multitype) {
                                        $instances[$instance->linktype][] = $instance;
                                } else {
                                        $instances[] = $instance;
                                }
                                $instance_block[$instance->actions_block]++;
                            }
                           
//                        }
                        
		}
                require_once 'include/utils/LightRendicontaUtils.php';
                if(isset($record) && $record!=null && $record !=''){
                    $resultPF=readPFActions($record);
                }
                if($resultPF){
                while($resultPF && $row = $adb->fetch_array($resultPF)){
                        $processflowsecurity=explode(' |##| ',$row['processflowsecurity']);
                        $roleid=$current_user->roleid;    
                        $belong2role=in_array($roleid,$processflowsecurity);
                        if(!$belong2role) continue;
                        $return = cbEventHandler::do_filter('corebos.filter.link.show', array($row, $type, $parameters));
			if($return == false) continue;
			$instance = new self();
			$instance->initialize_BA($row);
			if(!empty($row['handler_path']) && isFileAccessible($row['handler_path'])) {
				checkFileAccessForInclusion($row['handler_path']);
				require_once $row['handler_path'];
				$linkData = new Vtiger_LinkData($instance, $current_user);
				$ignore = call_user_func(array($row['handler_class'], $row['handler']), $linkData);
				if(!$ignore) {
					self::log("Ignoring Link ... ".var_export($row, true));
					continue;
				}
			}
			if($parameters) {
				$instance->linkurl = $strtemplate->merge($instance->linkurl);
				$instance->linkicon= $strtemplate->merge($instance->linkicon);
			}
                        $res_logic=false;
                        include_once('modules/BusinessActions/BusinessActions.php');
                        $actionfocus=CRMEntity::getInstance("BusinessActions");
                        $actionfocus->retrieve_entity_info($instance->businessactionsid,"BusinessActions");                                
                            if($instance->linktobrules!='' && $instance->linktobrules!=0)
                            $res_logic=$actionfocus->runBusinessLogic(); 
                            if($res_logic || $instance->linktobrules=='' || $instance->linktobrules==0){ // temporarly condition for showing actions not related to BR
                                if($multitype) {
                                        $instances[$instance->linktype][] = $instance;
                                } else {
                                        $instances[] = $instance;
                                }
                                $instance_block[$instance->actions_block]++;
                            }
		}
                }
                if($tabid != self::IGNORE_MODULE) {
                    $qry_block="select *  from  vtiger_actions_block "
                            . " where module_id = ?";
                    $res_block=$adb->pquery($qry_block,array($tabid));

                    for($i=0;$i<$adb->num_rows($res_block);$i++)
                    { 
                        $block_name=$adb->query_result($res_block,$i,'block_name');
                        if($instance_block[$block_name]!=0) {
                            $block_instances[] = $block_name; 
                        }
                    }
                    $instances['ActionBlock'] = $block_instances;
                }
                //var_dump($instances);
		return $instances;
	}
	/**
	 * Helper function to log messages
	 * @param String Message to log
	 * @param Boolean true appends linebreak, false to avoid it
	 * @access private
	 */
	private static function log($message, $delimit = true) {
		Vtiger_Utils::Log($message, $delimit);
	}

	/**
	 * Checks whether the user is admin or not
	 * @param Vtiger_LinkData $linkData
	 * @return Boolean
	 */
	public static function isAdmin($linkData) {
		$user = $linkData->getUser();
		return $user->is_admin == 'on' || $user->column_fields['is_admin'] == 'on';
	}

	public static function updateLink($tabId, $linkId, $linkInfo = array()) {
		if ($linkInfo && is_array($linkInfo)) {
			$db = PearDatabase::getInstance();
			$result = $db->pquery('SELECT 1 FROM vtiger_links WHERE tabid=? AND linkid=?', array($tabId, $linkId));
			if ($db->num_rows($result)) {
				$columnsList = $db->getColumnNames('vtiger_links');
				$isColumnUpdate = false;

				$sql = 'UPDATE vtiger_links SET ';
				foreach ($linkInfo as $column => $columnValue) {
					if (in_array($column, $columnsList)) {
						$columnValue = ($column == 'sequence') ? intval($columnValue) : $columnValue;
						$sql .= "$column='$columnValue',";
						$isColumnUpdate = true;
					}
				}

				if ($isColumnUpdate) {
					$sql = trim($sql, ',').' WHERE tabid=? AND linkid=?';
					$db->pquery($sql, array($tabId, $linkId));
				}
			}
		}
	}
}
?>
