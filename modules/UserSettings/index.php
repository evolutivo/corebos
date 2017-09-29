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
require_once("include/utils/utils.php");
global $current_language;
global $current_user;
include_once("modules/Settings/language/$current_language.lang.php");
// Users amd Access Management

$items = array();

$items[] = array(
	"img" => "ico-users.gif",
	"link" => "index.php?module=Users&action=index&parenttab=Settings",
	"label" => $mod_strings['LBL_USERS'],
	"description" => $mod_strings['LBL_USER_DESCRIPTION']
);

$items[] = array(
	"img" => "ico-roles.gif",
	"link" => "index.php?module=Settings&action=listroles&parenttab=Settings",
	"label" => $mod_strings['LBL_ROLES'],
	"description" => $mod_strings['LBL_ROLE_DESCRIPTION']
);

$items[] = array(
	"img" => "ico-profile.gif",
	"link" => "index.php?module=Settings&action=ListProfiles&parenttab=Settings",
	"label" => $mod_strings['LBL_PROFILES'],
	"description" => $mod_strings['LBL_PROFILE_DESCRIPTION']
);

$items[] = array(
	"img" => "ico-groups.gif",
	"link" => "index.php?module=Settings&action=listgroups&parenttab=Settings",
	"label" => $mod_strings['LBL_GROUPS'],
	"description" => $mod_strings['LBL_GROUP_DESCRIPTION']
);

$smarty = new vtigerCRM_Smarty();
$smarty->assign("ITEMS",$items);
$smarty->assign('ACCESSMANAG',$mod_strings['LBL_USER_MANAGEMENT']);

if(!UserSettingsPermissions() && !is_admin($current_user)){
$smarty->assign('APP', $app_strings);
if ($action==$module."Ajax") {
	$smarty->assign('PUT_BACK_ACTION', false);
}
$smarty->display('modules/Vtiger/OperationNotPermitted.tpl');
} else
$smarty->display("modules/UserSettings/index.tpl");
