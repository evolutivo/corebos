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
global $currentModule,$adb;
$focus = CRMEntity::getInstance($currentModule);
$smarty = new vtigerCRM_Smarty();
$tool_buttons = Button_Check($currentModule);
if(isset($tool_buttons)==false) {
	$tool_buttons = Button_Check($currentModule);
}
$record = vtlib_purify($_REQUEST['record']);
$isduplicate = isset($_REQUEST['isDuplicate']) ? vtlib_purify($_REQUEST['isDuplicate']) : '';
$tabid = getTabid($currentModule);
$category = getParentTab($currentModule);

if($record != '') {
	$focus->id = $record;
	$focus->retrieve_entity_info($record, $currentModule);
	$focus->name=$focus->column_fields[$focus->list_link_field];
}
if($isduplicate == 'true') $focus->id = '';
$errormessageclass = isset($_REQUEST['error_msgclass']) ? vtlib_purify($_REQUEST['error_msgclass']) : '';
$errormessage = isset($_REQUEST['error_msg']) ? vtlib_purify($_REQUEST['error_msg']) : '';
$smarty->assign('ERROR_MESSAGE_CLASS', $errormessageclass);
$smarty->assign('ERROR_MESSAGE', $errormessage);
$focus->preViewCheck($_REQUEST, $smarty);
if($currentModule=='Task'){
$query="Select *
   from vtiger_cntactivityrel
   join vtiger_task on vtiger_cntactivityrel.activityid=vtiger_task.taskid
   join vtiger_contactdetails on vtiger_cntactivityrel.contactid=vtiger_contactdetails.contactid
   join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_task.taskid
   where deleted=0 and vtiger_cntactivityrel.activityid=".$record;
   $result=$adb->query($query);
   $num=$adb->num_rows($result);
   for($i=0;$i<$num;$i++)
   {
   $cnt_idlist .= $adb->query_result($result,$i,"contactid");
   $contName = $adb->query_result($result,$i,"lastname");
   $product.='<a href="index.php?module=Contacts&action=DetailView&record='.$adb->query_result($result,$i,"contactid").'" target="_blank">'.$contName.'</a><br>';
   }
   $smarty->assign("contact", $product);
        
   $id1=$focus->id;
   $q1 = $adb->query("select * from vtiger_task join vtiger_potential on taskid=linktotask where taskid=$id1");
   if($adb->num_rows($q1)== 0)
   $mod = 0;
   else{  
   $mod=1;
   }
   $smarty->assign('mod', $mod);
   
   $sql = 'select vtiger_users.user_name,vtiger_invitees.* from vtiger_invitees left join vtiger_users on vtiger_invitees.inviteeid=vtiger_users.id where activityid=?';
   $result = $adb->pquery($sql, array($focus->id));
   $num_rows=$adb->num_rows($result);
   $invited_users=Array();
   for($i=0;$i<$num_rows;$i++)
   {
   $userid=$adb->query_result($result,$i,'inviteeid');
   $username=$adb->query_result($result,$i,'user_name');
   $invited_users[$userid]=$username;
   }
   $smarty->assign("INVITEDUSERS",$invited_users);
}
if($currentModule=='Entitylog'){
   $querydata=$adb->pquery("Select finalstate,tabid from vtiger_entitylog where entitylogid=?",array($record));
   $vl=$adb->query_result($querydata,0,0);
   $tab_id=$adb->query_result($querydata,0,1);
   $moduleName=  getTabModuleName($tab_id);
   $update_log = explode(';',$vl);       
   foreach($update_log as $d){
              if(stristr($d,'fieldname='))
                $fldname=substr($d,strpos($d,'fieldname=')+10);
              if(stristr($d,'oldvalue='))
                 $oldvl=substr($d,strpos($d,'oldvalue=')+9);
              if(stristr($d,'newvalue'))
                $newvl=substr($d,strpos($d,'newvalue=')+9);
                  }     
   $query = "select fieldlabel from vtiger_field where tabid={$tab_id} and fieldname='{$fldname}'";
   $res = $adb->query($query);
   $fieldlabel = $adb->query_result($res, 0, 0);
   $line[] = $moduleName ." changed value of '". $fieldlabel."' FROM ". $oldvl ."  TO  ". $newvl;
   $focus->column_fields['finalstate']=implode(' ',$line);

   $focus->column_fields['user']=  getUserName($focus->column_fields['user']);
   $focus->column_fields['tabid']=  getTabName($focus->column_fields['tabid']);
}
// Identify this module as custom module.
$smarty->assign('CUSTOM_MODULE', $focus->IsCustomModule);
$smarty->assign('APP', $app_strings);
$smarty->assign('MOD', $mod_strings);
$smarty->assign('MODULE', $currentModule);
$smarty->assign('SINGLE_MOD', 'SINGLE_'.$currentModule);
$smarty->assign('CATEGORY', $category);
$smarty->assign('IMAGE_PATH', "themes/$theme/images/");
$smarty->assign('THEME', $theme);
$smarty->assign('ID', $focus->id);
$smarty->assign('RECORDID', $focus->id);
$smarty->assign('MODE', $focus->mode);
$smarty->assign('USE_ASTERISK', get_use_asterisk($current_user->id));

$recordName = array_values(getEntityName($currentModule, $focus->id));
$recordName = isset($recordName[0]) ? $recordName[0] : '';
$smarty->assign('NAME', $recordName);
$smarty->assign('UPDATEINFO',updateInfo($focus->id));

// Module Sequence Numbering
$mod_seq_field = getModuleSequenceField($currentModule);
if ($mod_seq_field != null) {
	$mod_seq_id = $focus->column_fields[$mod_seq_field['name']];
} else {
	$mod_seq_id = $focus->id;
}
$smarty->assign('MOD_SEQ_ID', $mod_seq_id);

$validationArray = split_validationdataArray(getDBValidationData($focus->tab_name, $tabid));
$smarty->assign('VALIDATION_DATA_FIELDNAME',$validationArray['fieldname']);
$smarty->assign('VALIDATION_DATA_FIELDDATATYPE',$validationArray['datatype']);
$smarty->assign('VALIDATION_DATA_FIELDLABEL',$validationArray['fieldlabel']);
$smarty->assign('TODO_PERMISSION',CheckFieldPermission('parent_id','Calendar'));
$smarty->assign('EVENT_PERMISSION',CheckFieldPermission('parent_id','Events'));

$smarty->assign('EDIT_PERMISSION', isPermitted($currentModule, 'EditView', $record));
$smarty->assign('CHECK', $tool_buttons);

if(PerformancePrefs::getBoolean('DETAILVIEW_RECORD_NAVIGATION', true) && isset($_SESSION[$currentModule.'_listquery'])){
	$recordNavigationInfo = ListViewSession::getListViewNavigation($focus->id);
	VT_detailViewNavigation($smarty,$recordNavigationInfo,$focus->id);
} else {
	$smarty->assign('privrecord', '');
	$smarty->assign('nextrecord', '');
}

$smarty->assign('IS_REL_LIST', isPresentRelatedLists($currentModule));
$isPresentRelatedListBlock = isPresentRelatedListBlock($currentModule);
$smarty->assign('IS_RELBLOCK_LIST', $isPresentRelatedListBlock);
$singlepane_view = GlobalVariable::getVariable('Application_Single_Pane_View', 0, $currentModule);
$singlepane_view = empty($singlepane_view) ? 'false' : 'true';
$smarty->assign('SinglePane_View', $singlepane_view);
$smarty->assign('HASRELATEDPANES', 'false');
if($singlepane_view == 'true' or $isPresentRelatedListBlock) {
	$related_array = getRelatedLists($currentModule,$focus);
	$smarty->assign("RELATEDLISTS", $related_array);

	require_once('include/ListView/RelatedListViewSession.php');
	if(!empty($_REQUEST['selected_header']) && !empty($_REQUEST['relation_id'])) {
		RelatedListViewSession::addRelatedModuleToSession(vtlib_purify($_REQUEST['relation_id']),vtlib_purify($_REQUEST['selected_header']));
	}
	$open_related_modules = RelatedListViewSession::getRelatedModulesFromSession();
	$smarty->assign("SELECTEDHEADERS", $open_related_modules);
} else {
	$smarty->assign('RELATEDLISTS', array());
	$bmapname = $currentModule.'RelatedPanes';
	$cbMapid = GlobalVariable::getVariable('BusinessMapping_'.$bmapname, cbMap::getMapIdByName($bmapname));
	if ($cbMapid) {
		$cbMap = cbMap::getMapByID($cbMapid);
		$rltabs = $cbMap->RelatedPanes($focus->id);
		$smarty->assign('RLTabs', $rltabs['panes']);
		$smarty->assign('HASRELATEDPANES', 'true');
	}
}

if(isPermitted($currentModule, 'CreateView', $record) == 'yes')
	$smarty->assign('CREATE_PERMISSION', 'permitted');
if(isPermitted($currentModule, 'Delete', $record) == 'yes')
	$smarty->assign('DELETE', 'permitted');

$blocks = getBlocks($currentModule,'detail_view','',$focus->column_fields);
$smarty->assign('BLOCKS', $blocks);
$custom_blocks = getCustomBlocks($currentModule,'detail_view');
$smarty->assign('CUSTOMBLOCKS', $custom_blocks);
$smarty->assign('FIELDS',$focus->column_fields);
if (is_admin($current_user)) {
	$smarty->assign('hdtxt_IsAdmin',1);
} else {
	$smarty->assign('hdtxt_IsAdmin',0);
}

$smarty->assign("BLOCKINITIALSTATUS",$_SESSION['BLOCKINITIALSTATUS']);
// Gather the custom link information to display
include_once('vtlib/Vtiger/Link.php');
$customlink_params = Array('MODULE'=>$currentModule, 'RECORD'=>$focus->id, 'ACTION'=>vtlib_purify($_REQUEST['action']));
$smarty->assign('CUSTOM_LINKS', Vtiger_Link::getAllByType(getTabid($currentModule), Array('DETAILVIEWBASIC','DETAILVIEW','DETAILVIEWWIDGET'), $customlink_params));
if($isPresentRelatedListBlock) {
	$related_list_block = array();
	foreach ($blocks as $blabel => $binfo) {
		if (!empty($binfo['relatedlist'])) {
			foreach ($related_array as $rlabel => $rinfo) {
				if ($rinfo['relationId']==$binfo['relatedlist']) {
					$related_list_block[$binfo['relatedlist']] = array($rlabel=>$rinfo);
					break;
				}
			}
		}
	}
	$smarty->assign('RELATEDLISTBLOCK', $related_list_block);
}

// Hide Action Panel
$DEFAULT_ACTION_PANEL_STATUS = GlobalVariable::getVariable('Application_Action_Panel_Open',1);
$smarty->assign('DEFAULT_ACTION_PANEL_STATUS',($DEFAULT_ACTION_PANEL_STATUS ? '' : 'display:none'));

// Record Change Notification
$focus->markAsViewed($current_user->id);

$smarty->assign('DETAILVIEW_AJAX_EDIT', PerformancePrefs::getBoolean('DETAILVIEW_AJAX_EDIT', true));
?>
