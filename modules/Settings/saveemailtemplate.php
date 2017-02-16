<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
require_once('include/utils/utils.php');

global $log;
$db = PearDatabase::getInstance();
$folderName = vtlib_purify($_REQUEST["foldername"]);
$templateName = vtlib_purify($_REQUEST["templatename"]);
$templateid = vtlib_purify($_REQUEST["templateid"]);
$description = vtlib_purify($_REQUEST["description"]);
$subject = vtlib_purify($_REQUEST["subject"]);
$body = fck_from_html($_REQUEST["body"]);
$emailfrom = vtlib_purify($_REQUEST["emailfrom"]);

if(isset($templateid) && $templateid !='')
{
	$log->info("the templateid is set");  
	$sql = "update vtiger_emailtemplates set foldername =?, templatename =?, subject =?, description =?, body =?, sendemailfrom=? where templateid =?";
	$params = array($folderName, $templateName, $subject, $description, $body, $emailfrom, $templateid);
	$adb->pquery($sql, $params);
 
	$log->info("about to invoke the detailviewemailtemplate file");  
	header("Location:index.php?module=Settings&action=detailviewemailtemplate&parenttab=Settings&templateid=".$templateid);
}
else
{
	$templateid = $db->getUniqueID('vtiger_emailtemplates');
	$sql = "insert into vtiger_emailtemplates (foldername, templatename, subject, description, body, deleted, templateid, sendemailfrom) values (?,?,?,?,?,?,?,?)";
	$params = array($folderName, $templateName, $subject, $description, $body, 0, $templateid, $emailfrom);
	$adb->pquery($sql, $params);

	 $log->info("added to the db the emailtemplate");
	header("Location:index.php?module=Settings&action=detailviewemailtemplate&parenttab=Settings&templateid=".$templateid);
}
?>