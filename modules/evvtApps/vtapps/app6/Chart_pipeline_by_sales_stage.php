<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the 
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header$
 * Description:  returns HTML for client-side image map.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('include/utils/utils.php');
require_once('include/logging.php');
require_once("modules/Potentials/Charts.php");
require_once("modules/CustomView/CustomView.php");
global $app_list_strings, $current_language, $currentModule, $action, $adb;
$current_module_strings = return_module_language($current_language, 'Dashboard');
require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
require('user_privileges/user_privileges_'.$current_user->id.'.php');
$log = LoggerManager::getLogger('pipeline_by_sales_stage');

// Get _dom Arrays from Database
$comboFieldNames = Array('sales_stage'=>'sales_stage_dom');
$comboFieldArray = getComboArray($comboFieldNames);

$graph_paramsrs=$adb->query('select * from evvtapp_potentialgraph where userid='.$current_user->id);
if ($adb->num_rows($graph_paramsrs)==0) {
	$smartdate='custom';
	$date_start='2001-01-01';
	$date_end='2100-01-01';
	$sstage='';
	$users='';
} else {
	$graph_params=$adb->fetch_array($graph_paramsrs);
	$smartdate=$graph_params['dyndate'];
	if ($smartdate=='custom') {
		$date_start=$graph_params['startdate'];
		$date_end=$graph_params['enddate'];
	} else {
		$cv=new CustomView();
		$rngdates=$cv->getDateforStdFilterBytype($smartdate);
		$date_start=$rngdates[0];
		$date_end=$rngdates[1];
	}
	$sstage=$graph_params['sstage'];
	$users=$graph_params['users'];
}
if (empty($date_start) or $date_start=='0000-00-00')
	$date_start='2001-01-01';
if (empty($date_start) or $date_end=='0000-00-00')
	$date_end='2100-01-01';

$tempx = array();
$datax = array();
//get list of sales stage keys to display
if (!empty($sstage)) {
	$tempx = explode('::',$sstage);
}

//set $datax using selected sales stage keys 
if (count($tempx) > 0) {
	foreach ($tempx as $key) {
		$datax[$key] = $comboFieldArray['sales_stage_dom'][$key];
	}
} else {
	$datax = $comboFieldArray['sales_stage_dom'];
}

$ids = array();
//get list of user ids for which to display data
if (!empty($users)) {
	$ids = explode('::',$users);
} else {
	$ids = get_user_array(false);
	$ids = array_keys($ids);
}

//create unique prefix based on selected users for image files
$id_hash = '';
if (isset($ids)) {
	sort($ids);
	$id_hash = crc32(implode('',$ids));
}

$draw_this = new jpgraph();

// added for auto refresh
$refresh = true;

echo $draw_this->pipeline_by_sales_stage($datax, $date_start, $date_end, $ids, $cache_file_name, $refresh,$width,$height);
echo "<P><font size='1'><em>".getTranslatedString('LBL_SALES_STAGE_FORM_DESC','Dashboard')."</em></font></P>";
?>
