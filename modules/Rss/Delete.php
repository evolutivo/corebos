<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

global $adb;

if(!isset($_REQUEST['record']))
	die($mod_strings['ERR_DELETE_RECORD']);

$del_query = 'DELETE FROM vtiger_rss WHERE rssid=?';
$adb->pquery($del_query, array($_REQUEST['record']));

header("Location: index.php?module=".vtlib_purify($_REQUEST['return_module'])."&action=RssAjax&file=ListView&directmode=ajax");
?>