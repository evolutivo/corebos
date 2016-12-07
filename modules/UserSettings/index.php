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

// Users amd Access Management

$items = array();

$items[] = array(
	"img" => "ico-users.gif",
	"link" => "index.php?module=Users&action=index&parenttab=Settings",
	"label" => "Users",
	"description" => 'Manage users who can access the application'
);

$items[] = array(
	"img" => "ico-roles.gif",
	"link" => "index.php?module=Settings&action=listroles&parenttab=Settings",
	"label" => "Roles",
	"description" => 'Set up hierarchy of roles and assign to the users'
);

$items[] = array(
	"img" => "ico-profile.gif",
	"link" => "index.php?module=Settings&action=ListProfiles&parenttab=Settings",
	"label" => "Profiles",
	"description" => 'Manage user-specific modules access to different Roles'
);

$items[] = array(
	"img" => "ico-groups.gif",
	"link" => "index.php?module=Settings&action=listgroups&parenttab=Settings",
	"label" => "Groups",
	"description" => 'Manage different types of teams based on roles, users, and profiles'
);

$smarty = new vtigerCRM_Smarty();
$smarty->assign("ITEMS",$items);
$smarty->display("modules/UserSettings/index.tpl");
