<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
global $current_user, $currentModule, $singlepane_view;

checkFileAccessForInclusion("modules/$currentModule/$currentModule.php");
require_once("modules/$currentModule/$currentModule.php");

$search = isset($_REQUEST['search_url']) ? vtlib_purify($_REQUEST['search_url']) : '';

$focus = new $currentModule();
setObjectValuesFromRequest($focus);

$mode = vtlib_purify($_REQUEST['mode']);
$record=vtlib_purify($_REQUEST['record']);
if($mode) $focus->mode = $mode;
if($record)$focus->id  = $record;
if (isset($_REQUEST['inventory_currency'])) {
$focus->column_fields['currency_id'] = vtlib_purify($_REQUEST['inventory_currency']);
$cur_sym_rate = getCurrencySymbolandCRate(vtlib_purify($_REQUEST['inventory_currency']));
$focus->column_fields['conversion_rate'] = $cur_sym_rate['rate'];
}
if($_REQUEST['assigntype'] == 'U') {
	$focus->column_fields['assigned_user_id'] = $_REQUEST['assigned_user_id'];
} elseif($_REQUEST['assigntype'] == 'T') {
	$focus->column_fields['assigned_user_id'] = $_REQUEST['assigned_group_id'];
}
list($saveerror,$errormessage,$error_action,$returnvalues) = $focus->preSaveCheck($_REQUEST);
if ($saveerror) { // there is an error so we go back to EditView.
	$return_module=$return_id=$return_action='';
	if (!empty($_REQUEST['return_action'])) {
		$return_action = '&return_action='.vtlib_purify($_REQUEST['return_action']);
	}
	if (!empty($_REQUEST['return_module'])) {
		$return_action .= '&return_module='.vtlib_purify($_REQUEST['return_module']);
	}
	if (isset($_REQUEST['return_id']) and $_REQUEST['return_id'] != '') {
		$return_action = '&return_id='.vtlib_purify($_REQUEST['return_id']);
	}
	if (!empty($_REQUEST['activity_mode'])) {
		$return_action .= '&activity_mode='.vtlib_purify($_request['activity_mode']);
	}
	if (empty($_REQUEST['return_viewname'])) {
		$return_viewname = '0';
	} else {
		$return_viewname = vtlib_purify($_REQUEST['return_viewname']);
	}
	$field_values_passed.="";
	foreach($focus->column_fields as $fieldname => $val) {
		if(isset($_REQUEST[$fieldname])) {
			$field_values_passed.="&";
			if($fieldname == 'assigned_user_id') { // assigned_user_id already set correctly above
				$value = vtlib_purify($focus->column_fields['assigned_user_id']);
			} else {
				$value = vtlib_purify($_REQUEST[$fieldname]);
			}
			if (is_array($value)) $value = implode(' |##| ',$value); // for multipicklists
			$field_values_passed.=$fieldname."=".urlencode($value);
		}
	}
	$encode_field_values=base64_encode($field_values_passed);
	$error_module = $currentModule;
	$error_action = (empty($error_action) ? 'EditView' : $error_action);
	$errormessage = urlencode($errormessage);
	header("location: index.php?action=$error_action&module=$error_module&record=$record&return_viewname=$return_viewname".$search.$return_action.$returnvalues."&error_msg=$errormessage&save_error=true&encode_val=$encode_field_values");
	die();
}

$focus->save($currentModule);
$return_id = $focus->id;
global $adb;
if($currentModule=='Adocmaster'){
$but=vtlib_purify($_REQUEST['buttontemp']);
if($but){
$query = "SELECT * 
FROM vtiger_adocdetail
INNER JOIN vtiger_adocmaster ON vtiger_adocmaster.adocmasterid = vtiger_adocdetail.adoctomaster
INNER JOIN vtiger_products ON vtiger_adocdetail.adoc_product = vtiger_products.productid
WHERE adocmasterid =?";
$result = $adb->pquery($query,array($_REQUEST['return_pid']));
$name_nr = 1;
for($i=0;$i<$adb->num_rows($result);$i++)
{ 
$adocdetail = new Adocdetail();
$adocdetail->column_fields['adocdetailname'] = "ADOC";
$adocdetail->column_fields['nrline'] = $name_nr;
$adocdetail->column_fields['adoctomaster'] = $focus->id;
$adocdetail->column_fields['adoc_product'] = $adb->query_result($result,$i,'adoc_product');
$adocdetail->column_fields['adoc_stock'] = $adb->query_result($result,$i,'adoc_stock');
$adocdetail->column_fields['adoc_quantity']=$adb->query_result($result,$i,'adoc_quantity');
//$adocdetail->column_fields['adoc_price']= $price_tariff;
//$adocdetail->column_fields['total']= $unit_price;
$_REQUEST['assigntype'] = 'U';
$adocdetail->column_fields['assigned_user_id'] = $current_user->id;
$adocdetail->save("Adocdetail");
$name_nr++;
}
}
}
if($currentModule=='SQLReports'){
require_once 'modules/SQLReports/SQLReportsUtils.php';
if(!agc_checkSQLCode($_REQUEST['reportsql']))
{
echo ("BAD SQL STATEMENT DETECTED:<br>".$sqlCode.'<br><br>Terminating request.');
exit();
}
}
$parenttab = getParentTab();
if(!empty($_REQUEST['return_module'])) {
	$return_module = vtlib_purify($_REQUEST['return_module']);
} else {
	$return_module = $currentModule;
}
if(!empty($_REQUEST['return_action'])) {
	$return_action = vtlib_purify($_REQUEST['return_action']);
} else {
	$return_action = 'DetailView';
}
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != '') {
	$return_id = vtlib_purify($_REQUEST['return_id']);
}
//code added for returning back to the current view after edit from list view
if(empty($_REQUEST['return_viewname']) or $singlepane_view == 'true') {
	$return_viewname='0';
} else {
	$return_viewname=vtlib_purify($_REQUEST['return_viewname']);
}
if(isset($_REQUEST['activity_mode'])) {
	$return_action .= '&activity_mode='.vtlib_purify($_REQUEST['activity_mode']);
}

header("Location: index.php?action=$return_action&module=$return_module&record=$return_id&parenttab=$parenttab&viewname=$return_viewname&start=".vtlib_purify($_REQUEST['pagenumber']).$search);
?>