<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

require_once 'include/utils/VtlibUtils.php';


class NgBlock {

    public $header = '';
	/**
	 * Invoked when special actions are performed on the module.
	 * @param String Module name
	 * @param String Event Type (module.postinstall, module.disabled, module.enabled, module.preuninstall)
	 */
	function vtlib_handler($modulename, $event_type) {
	
	}

	/**
	 * Transfer the comment records from one parent record to another.
	 * @param CRMID Source parent record id
	 * @param CRMID Target parent record id
	 */
	static function transferRecords($currentParentId, $targetParentId) {
		global $adb;
		$adb->pquery("UPDATE vtiger_modcomments SET related_to=? WHERE related_to=?", array($targetParentId, $currentParentId));
	}

	/**
	 * Get widget instance by name
	 */
	static function getWidget($id,$context) {
                global $adb;
		$result=$adb->pquery("Select id,pointing_block_name,module_name from vtiger_ng_block where id=?", array($id));
                $nr=$adb->num_rows($result);
                if ($nr>0) {
                    $module_name=$adb->query_result($result,0,'module_name');
                    $pointing_block_name=$adb->query_result($result,0,'pointing_block_name');
                    $lbl_block_name=array_search($context['header'],$context['MOD']);

                    if($lbl_block_name==$pointing_block_name || $context['header']==$pointing_block_name || 
                            ($context['CUSTOM_LINKS']['RELATEDVIEWWIDGET'] && $context['CUSTOM_LINKS']['RELATEDVIEWWIDGET'][0]->linktype=='RELATEDVIEWWIDGET') ){
			require_once dirname(__FILE__) . '/DetailViewBlockNg.php';
			return (new NgBlock_DetailViewBlockNgWidget($id));
	}
		}
		return false;
	}

	/**
	 * Add widget to other module.
	 * @param unknown_type $moduleNames
	 * @return unknown_type
	 */
	static function addWidgetTo($moduleNames, $widgetType='DETAILVIEWWIDGET', $widgetName='DetailViewBlockNgWidget',$sequence) {
		if (empty($moduleNames)) return;

		include_once 'vtlib/Vtiger/Module.php';

		if (is_string($moduleNames)) $moduleNames = array($moduleNames);

		$commentWidgetModules = array();
		foreach($moduleNames as $moduleName) {
			$module = Vtiger_Module::getInstance($moduleName);
			if($module) {
				$module->addLink($widgetType, $widgetName, "block://NgBlock:modules/NgBlock/NgBlock.php","",$sequence);
				$commentWidgetModules[] = $moduleName;
			}
		}
		if (count($commentWidgetModules) > 0) {
			$modCommentsModule = Vtiger_Module::getInstance('NgBlock');
			$modCommentsModule->addLink('HEADERSCRIPT', 'NgBlockHeaderScript', 'modules/NgBlock/NgBlock.js');
//			$modCommentsRelatedToField = Vtiger_Field::getInstance('related_to', $modCommentsModule);
//			$modCommentsRelatedToField->setRelatedModules($commentWidgetModules);
		}
	}

	/**
	 * Remove widget from other modules.
	 * @param unknown_type $moduleNames
	 * @param unknown_type $widgetType
	 * @param unknown_type $widgetName
	 * @return unknown_type
	 */
	static function removeWidgetFrom($moduleNames, $widgetType='DETAILVIEWWIDGET', $widgetName='DetailViewBlockNgWidget') {
		if (empty($moduleNames)) return;

		include_once 'vtlib/Vtiger/Module.php';

		if (is_string($moduleNames)) $moduleNames = array($moduleNames);

		$commentWidgetModules = array();
		foreach($moduleNames as $moduleName) {
			$module = Vtiger_Module::getInstance($moduleName);
			if($module) {
				$module->deleteLink($widgetType, $widgetName, "block://NgBlock:modules/NgBlock/NgBlock.php");
				$commentWidgetModules[] = $moduleName;
			}
		}
		
	}

	/**
	 * Wrap this instance as a model
	 */
	function getAsCommentModel() {
		return new ModComments_CommentsModel($this->column_fields);
	}

	function getListButtons($app_strings) {
		$list_buttons = Array();
		return $list_buttons;
	}
        
        function createElastic($elasticid,$elastic_type){
    
            global $adb;

            $query=$adb->pquery("SELECT *
                                  from  vtiger_elastic_indexes
                                  where elasticname = ?",array($elasticid));
            $nr_qry=$adb->num_rows($query);
            $indextype=$adb->query_result($query,0,'elasticname');            
            $typ=$elastic_type;

            $entries=Array();
            $tabid=  getTabid('Adocdetail');
            global $dbconfig;
            $ip='193.182.16.34';//GlobalVariable::getVariable('ip_elastic_server', '');//'193.182.16.34';//$dbconfig['ip_server'];
            $endpointUrl = "http://$ip:9200/$indextype/$typ/_search?pretty";
        //    $fields1 =array('query'=>array("term"=>array("adocdetailid"=>$id)),'sort'=>array('modifiedtime'=>array('order'=>'asc')));
            $channel1 = curl_init();
            curl_setopt($channel1, CURLOPT_URL, $endpointUrl);
            curl_setopt($channel1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($channel1, CURLOPT_POST, true);
            //curl_setopt($channel1, CURLOPT_CUSTOMREQUEST, "PUT");
            //curl_setopt($channel1, CURLOPT_POSTFIELDS, json_encode($fields1));
            curl_setopt($channel1, CURLOPT_CONNECTTIMEOUT, 100);
            curl_setopt($channel1, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($channel1, CURLOPT_TIMEOUT, 1000);
            $response1 = json_decode(curl_exec($channel1));
            $tot=$response1->hits->total;

            $endpointUrl = "http://$ip:9200/$indextype/$typ/_search?pretty&size=$tot";
        //    $fields1 =array('query'=>array("term"=>array("adocdetailid"=>$id)),'sort'=>array('modifiedtime'=>array('order'=>'asc')));
            $channel1 = curl_init();
            curl_setopt($channel1, CURLOPT_URL, $endpointUrl);
            curl_setopt($channel1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($channel1, CURLOPT_POST, true);
            //curl_setopt($channel1, CURLOPT_CUSTOMREQUEST, "PUT");
        //    curl_setopt($channel1, CURLOPT_POSTFIELDS, json_encode($fields1));
            curl_setopt($channel1, CURLOPT_CONNECTTIMEOUT, 100);
            curl_setopt($channel1, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($channel1, CURLOPT_TIMEOUT, 1000);
            $response1 = json_decode(curl_exec($channel1));

            foreach ($response1->hits->hits as $row) {
              $user = getUserName($row->_source->userchange);
              $update_log = explode(";",$row->_source->changedvalues);
              $source[] = $row->_source;
            }
            return (array)$source;
        }
        

}
?>
