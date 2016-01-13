<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
require_once('Smarty_setup.php');

class NgBlock_DetailViewBlockNgWidget {
	private $_name = 'DetailViewBlockNgWidget';
        private $id ;
	
	private $defaultCriteria = 'All';
	
	protected $context = false;
	protected $criteria= false;
	
	function __construct($id_ng) {
            $this->id = $id_ng;
	}
	
	function getFromContext($key, $purify=false) {
		if ($this->context) {
			$value = $this->context[$key];
			if ($purify && !empty($value)) {
				$value = vtlib_purify($value);
			}
			return $value;
		}
		return false;
	}
	
	function title() {
		return getTranslatedString('LBL_NGBLOCK_INFORMATION', 'NgBlock');
	}
	
	function name() {
		return $this->_name;
	}
	
	function setCriteria($newCriteria) {
		$this->criteria = $newCriteria;
	}
	
	function getViewer() {
		global $theme, $app_strings, $current_language;
		
		$smarty = new vtigerCRM_Smarty();
		$smarty->assign('APP', $app_strings);
		$smarty->assign('MOD', return_module_language($current_language,'NgBlock'));
		$smarty->assign('THEME', $theme);
		$smarty->assign('IMAGE_PATH', "themes/$theme/images/");
				
		return $smarty;
	}
	
	protected function getModels($parentRecordId, $criteria) {
		global $adb, $current_user;

		$moduleName = 'ModComments';
		if(vtlib_isModuleActive($moduleName)) {
			$entityInstance = CRMEntity::getInstance($moduleName);
			
			$queryCriteria  = '';
			switch($criteria) {
				case 'All': $queryCriteria = sprintf(" ORDER BY %s.%s DESC ", $entityInstance->table_name, $entityInstance->table_index); break;
				case 'Last5': $queryCriteria =  sprintf(" ORDER BY %s.%s DESC LIMIT 5", $entityInstance->table_name, $entityInstance->table_index) ;break;
				case 'Mine': $queryCriteria = ' AND vtiger_crmentity.smownerid=' . $current_user->id.sprintf(" ORDER BY %s.%s DESC ", $entityInstance->table_name, $entityInstance->table_index); break;
			}
			
			$query = $entityInstance->getListQuery($moduleName, sprintf(" AND %s.related_to=?", $entityInstance->table_name));
			$query .= $queryCriteria;
			$result = $adb->pquery($query, array($parentRecordId));
		
			$instances = array();
			if($adb->num_rows($result)) {
				while($resultrow = $adb->fetch_array($result)) {
					$instances[] = new ModComments_CommentsModel($resultrow);
				}
			}
		}
		return $instances;
	}
	
	function processItem($model) {
		$viewer = $this->getViewer();
		$viewer->assign('COMMENTMODEL', $model);
		return $viewer->fetch(vtlib_getModuleTemplate("ModComments","widgets/DetailViewBlockCommentItem.tpl"));
	}
	
	function process($context = false) {
            global $adb,$mod_strings, $app_strings,$default_language,$current_user;
		$this->context = $context;
		$sourceRecordId =  $this->getFromContext('ID', true);
		
                $result=$adb->pquery("Select * from vtiger_ng_block where id=?", array($this->id));
                $pointing_module_name	=$adb->query_result($result,0,'pointing_module_name');
                $pointing_field_name=$adb->query_result($result,0,'pointing_field_name');
                $name=$adb->query_result($result,0,'name');
                $module_name=$adb->query_result($result,0,'module_name');
                $nr_page=$adb->query_result($result,0,'nr_page');
                $paginate=$adb->query_result($result,0,'paginate');
                $edit_record=$adb->query_result($result,0,'edit_record');
                $delete_record=$adb->query_result($result,0,'delete_record');
                $add_record=$adb->query_result($result,0,'add_record');
                $columns=$adb->query_result($result,0,'columns');
                $type=$adb->query_result($result,0,'type');
                $custom_widget_path=$adb->query_result($result,0,'custom_widget_path');
                $col= explode(",",$columns);
                $options= Array();
                for($j=0;$j<sizeof($col);$j++)
                {
                    if(empty($col[$j])) continue;
                    $re=$adb->pquery("Select fieldlabel,uitype,fieldname,typeofdata,relmodule "
                            . " from vtiger_field "
                            . " left join vtiger_fieldmodulerel on vtiger_fieldmodulerel.fieldid=vtiger_field.fieldid"
                            . " where columnname = ? OR fieldname=?",array($col[$j],$col[$j]));  
                    $tmp1= getTranslatedString($adb->query_result($re,0,'fieldlabel'),$pointing_module_name);
                    $uitype = $adb->query_result($re,0,'uitype');
                    $fieldname = $adb->query_result($re,0,'fieldname');
                    $columnName[] = $col[$j];
                    $fieldLabel[] = $tmp1;
                    $fieldUitype [] = $uitype;
                    if(in_array($uitype, array('15','16','55','33'))){
                        $res1=$adb->pquery("Select * from vtiger_$fieldname ",array());
                        for($count_options=0;$count_options<$adb->num_rows($res1);$count_options++)
                        {
                            $options[$col[$j]][$count_options]=$adb->query_result($res1,$count_options,$fieldname);
                        }
                    
                    }
                    elseif(in_array($uitype, array('26'))){
                        $res1=$adb->pquery("Select * from vtiger_attachmentsfolder ",array());
                        for($count_options=0;$count_options<$adb->num_rows($res1);$count_options++)
                        {
                            $options[$col[$j]][$count_options]=array(
                                'id'=>$adb->query_result($res1,$count_options,'folderid'),
                                'name'=>$adb->query_result($res1,$count_options,'foldername'));
                        }
                    
                    }
                    elseif($uitype=='53'){
                        $res1=$adb->pquery("Select id,concat(first_name,' ',last_name) as crmname,'User' as type from vtiger_users "
                                . " Union"
                                . " Select groupid as id,groupname as crmname,'Group' as type from vtiger_groups",array());
                        while ($emp=$adb->fetch_array($res1))  {
                               $options[$col[$j]][]=array('crmid'=>$emp['id'],
                                                          'crmname'=>$emp['crmname'],
                                                          'crmtype'=>$emp['type']);
                        }
                    
                    }
                    elseif($uitype=='10'){
                        $relmodule[$j] = $adb->query_result($re,0,'relmodule');                   
                    }
                    elseif($uitype=='57'){
                        $relmodule[$j] = 'Contacts';                   
                    }
                    elseif($uitype=='51'){
                        $relmodule[$j] = 'Accounts';                   
                    }
                }
                $blockURL="module=NgBlock&action=NgBlockAjax";
                $blockURL.="&file=ng_block_actions&id=".$sourceRecordId;
                $blockURL.="&ng_block_id=".$this->id;                

                $viewer = $this->getViewer();
		$viewer->assign('RECORD_ID', $sourceRecordId);
                $viewer->assign('NG_BLOCK_NAME', $name);
                $viewer->assign('NG_BLOCK_ID', $this->id);
                $viewer->assign('MODULE_NAME', $module_name);
                $viewer->assign('APP', $app_strings);
                include("modules/$pointing_module_name/language/$default_language.lang.php");
                include("modules/$module_name/language/$default_language.lang.php");
                $viewer->assign('MOD', $mod_strings);
                $viewer->assign('MOD_NG', $mod_strings);
                $viewer->assign('POINTING_MODULE', $pointing_module_name);
                $viewer->assign('POINTING_FIELDNAME', $pointing_field_name);
                $viewer->assign('NR_PAGE', $nr_page);
                $viewer->assign('PAGINATE', $paginate);
                $viewer->assign('EDIT_RECORD', $edit_record);
                $viewer->assign('DELETE_RECORD', $delete_record);
                $viewer->assign('ADD_RECORD', $add_record);
                $viewer->assign("COLUMN_NAME", $columnName);
                $viewer->assign("FIELD_LABEL", $fieldLabel);
                $viewer->assign("FIELD_UITYPE", $fieldUitype);
                $viewer->assign("Date_format", $current_user->date_format);
                $viewer->assign("COLUMN_NAME_JSON", json_encode($columnName));
                $viewer->assign("FIELD_UITYPE_JSON", json_encode($fieldUitype));
                $viewer->assign("OPTIONS", json_encode($options));
                $viewer->assign('ADD_RECORD', $add_record);
                $viewer->assign('REL_MODULE', $relmodule);
                $viewer->assign("blockURL",$blockURL);
                
                if($type=='Graph'){
                    $ret_temp=$viewer->fetch(vtlib_getModuleTemplate("NgBlock","DetailViewBlockNgGraph.tpl"));                    
                }
                elseif($type=='Text'){
                    $ret_temp=$viewer->fetch(vtlib_getModuleTemplate("NgBlock","DetailViewBlockNgText.tpl"));                    
                }
                elseif($type=='Elastic'){
                    $ret_temp=$viewer->fetch(vtlib_getModuleTemplate("NgBlock","DetailViewBlockNgJson.tpl"));                    
                }
                elseif($type=='Custom'){
                    $ret_temp=$viewer->fetch(vtlib_getModuleTemplate($module_name,$custom_widget_path));                    
                }
                else{
                    $ret_temp=$viewer->fetch(vtlib_getModuleTemplate("NgBlock","DetailViewBlockNg.tpl"));
                }
                return $ret_temp;
	}
	
}