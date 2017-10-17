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
		$result=$adb->pquery("Select id,pointing_block_name,top_widget from vtiger_ng_block where id=?", array($id));
                $nr=$adb->num_rows($result);
                if ($nr>0) {
                    $top_widget=$adb->query_result($result,0,'top_widget');
                    $pointing_block_name=$adb->query_result($result,0,'pointing_block_name');
                    $lbl_block_name=array_search($context['header'],$context['MOD']);

                    if($lbl_block_name==$pointing_block_name || $context['header']==$pointing_block_name  
                            ||($context['CUSTOM_LINKS']['RELATEDVIEWWIDGET'] && $context['CUSTOM_LINKS']['RELATEDVIEWWIDGET'][0]->linktype=='RELATEDVIEWWIDGET') 
                            || $top_widget=='1'){
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
        
        function createElastic($elastic_id,$elastic_type,$fromwhere,$size,$where,$pointing_field_name,$recid)
        {
            if(empty($fromwhere))$fromwhere=0;
            $ip=GlobalVariable::getVariable('ip_elastic_server', '');
            $indexname=$elastic_id;
            $index_type=$elastic_type;
            $condition='';
            $cond= array();
            foreach($where as $k=>$v){
                if(!empty($v))
                $cond[]="( $k : *$v* )";
            }
            if($pointing_field_name!='')
                $cond[]="( $pointing_field_name : *$recid* )";
            $condition=implode(' AND ',$cond);
            if(!empty($condition)){
            $fields =array('query'=>array("query_string"=>array("query"=>$condition)));
                $fields=json_encode($fields);
            }
            else{
                $fields=null;
            }
            $queryData = array('from' => $fromwhere,'size'=> $size) ;
            $endpointUrl2 = "http://$ip:9200/$indexname/$index_type".'/_search?'.http_build_query($queryData) ;
            $endpointUrlCount = "http://$ip:9200/$indexname/$index_type".'/_count';
            $response=$this->exeCurlPost($fields, $endpointUrl2);
            $responseCount=$this->exeCurlPost($fields, $endpointUrlCount);
            $total=$responseCount->count;
            $records=array();
            foreach ($response->hits->hits as $hit) {
                $hit->_source->elastic_id=$hit->_id;
                $us=getUserName($hit->_source->smownerid);
                if($us=='')
                {$us=getGroupName($hit->_source->smownerid);
                $us=$us[0];
                }
                $hit->_source->assigned_user_id=$us;
                if(strstr($indexname,'oldtasks')!=''){
                $tid=$hit->_source->taskid;
                mysql_connect("188.164.129.44","root","123456789");
                $q=mysql_db_query("subitoprod","select note from vtiger_task join vtiger_crmentity on crmid=taskid and deleted=0 where taskid=$tid");
                $row=mysql_fetch_array($q);

                $hit->_source->note=utf8_encode($row['note']);
               }
                global $current_user;
                $roleid=$current_user->roleid;
                $uid=$current_user->id;
                $roles=explode("::",$hit->_source->roles);
                if($current_user->is_admin=='on' || in_array($uid,$roles) || in_array($roleid,$roles))
                $records[] = $hit->_source;      
                else $total=$total-1;
            }
            return array('total'=>$total,'records'=>$records);
        }
         function exeCurlPost($fld, $endpointUrl2)
        {
            $channel11 = curl_init();
            curl_setopt($channel11, CURLOPT_URL, $endpointUrl2);
            curl_setopt($channel11, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($channel11, CURLOPT_POST, true);
            //curl_setopt($channel11, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($channel11, CURLOPT_POSTFIELDS, $fld);//json_encode($fld)
            curl_setopt($channel11, CURLOPT_CONNECTTIMEOUT, 100);
            curl_setopt($channel11, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($channel11, CURLOPT_TIMEOUT, 1000);
            $response = curl_exec($channel11);
            return json_decode($response);
        }
            
//        function createElastic($elasticid,$elastic_type){
//    
//            global $adb;
//
//            $query=$adb->pquery("SELECT *
//                                  from  vtiger_elastic_indexes
//                                  where elasticname = ?",array($elasticid));
//            $nr_qry=$adb->num_rows($query);
//            $indextype=$adb->query_result($query,0,'elasticname');            
//            $typ=$elastic_type;
//
//            $entries=Array();
//            $tabid=  getTabid('Adocdetail');
//            global $dbconfig;
//            $ip=GlobalVariable::getVariable('ip_elastic_server', '');
//            $endpointUrl = "http://$ip:9200/$indextype/$typ/_search?pretty";
//        //    $fields1 =array('query'=>array("term"=>array("adocdetailid"=>$id)),'sort'=>array('modifiedtime'=>array('order'=>'asc')));
////            $channel1 = curl_init();
////            curl_setopt($channel1, CURLOPT_URL, $endpointUrl);
////            curl_setopt($channel1, CURLOPT_RETURNTRANSFER, true);
////            curl_setopt($channel1, CURLOPT_POST, true);
////            //curl_setopt($channel1, CURLOPT_CUSTOMREQUEST, "PUT");
////            //curl_setopt($channel1, CURLOPT_POSTFIELDS, json_encode($fields1));
////            curl_setopt($channel1, CURLOPT_CONNECTTIMEOUT, 100);
////            curl_setopt($channel1, CURLOPT_SSL_VERIFYPEER, false);
////            curl_setopt($channel1, CURLOPT_TIMEOUT, 1000);
////            $response1 = json_decode(curl_exec($channel1));
////            $tot=$response1->hits->total;
//            $endpointUrl = "http://$ip:9200/$indextype/$typ/_search?pretty&size=10000";
//        //    $fields1 =array('query'=>array("term"=>array("adocdetailid"=>$id)),'sort'=>array('modifiedtime'=>array('order'=>'asc')));
//            $channel1 = curl_init();
//            curl_setopt($channel1, CURLOPT_URL, $endpointUrl);
//            curl_setopt($channel1, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($channel1, CURLOPT_POST, true);
//            //curl_setopt($channel1, CURLOPT_CUSTOMREQUEST, "PUT");
//        //    curl_setopt($channel1, CURLOPT_POSTFIELDS, json_encode($fields1));
//            curl_setopt($channel1, CURLOPT_CONNECTTIMEOUT, 100);
//            curl_setopt($channel1, CURLOPT_SSL_VERIFYPEER, false);
//            curl_setopt($channel1, CURLOPT_TIMEOUT, 1000);
//            $response1 = json_decode(curl_exec($channel1));
//            foreach ($response1->hits->hits as $row) {
//              $user = getUserName($row->_source->userchange);
//              $update_log = explode(";",$row->_source->changedvalues);
//              $source[] = $row->_source;
//            }
//            return (array)$source;
//        }
        
        function getEditCol($createcol) {
                require_once('modules/cbMap/cbMap.php');
                require_once('modules/BusinessRules/BusinessRules.php');
                global $current_user,$adb;
                $userProfileArr = getUserProfile($current_user->id);
                $arr=explode(',',$createcol);
                $columns=array();
                for($i=0;$i<sizeof($arr);$i++){
                    if(empty($arr[$i])) continue;
                    $brId=$arr[$i];
                    $focusBR = CRMEntity::getInstance("BusinessRules");
                    $focusBR->retrieve_entity_info($brId, "BusinessRules");
                    $mapid = $focusBR->column_fields['linktomap'];
                    $focusMap = CRMEntity::getInstance("cbMap");
                    $focusMap->retrieve_entity_info($mapid, "cbMap");
                    $profile = $focusMap->getMapProfile();
                    $target_fields = $focusMap->getMapTargetFields();
                    if(count(array_intersect($profile ,$userProfileArr)) != 0 
                            || in_array('', $profile)){
                        $columns=$target_fields;
                        break;
                    }
                }
		return $columns;
	}

}
?>
