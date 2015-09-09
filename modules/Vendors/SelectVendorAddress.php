<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ('License'); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
require_once('Smarty_setup.php');

global $app_strings,$mod_strings,$current_user,$theme,$adb;
$image_path = 'themes/'.$theme.'/images/';
$smarty = new vtigerCRM_Smarty;
$smarty->assign('MOD', $mod_strings);
$smarty->assign('APP', $app_strings);
$smarty->assign('THEME', $theme);
$smarty->assign('IMAGE_PATH',$image_path);
$smarty->assign('BillAddressChecked',GlobalVariable::getVariable('Billing_Address_Checked', 'true'));
$smarty->assign('ShipAddressChecked',GlobalVariable::getVariable('Shipping_Address_Checked', 'false'));
$smarty->display('modules/Vendors/SetReturnAddress.tpl');
?>