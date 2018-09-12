<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
global $adb, $log;
$return_id = vtlib_purify($_REQUEST['return_id']);
$record = vtlib_purify($_REQUEST['record']);
$return_module = urlencode(vtlib_purify($_REQUEST['return_module']));
$return_action = urlencode(vtlib_purify($_REQUEST['return_action']));

if ($return_action !='' && $return_module == 'PriceBooks' && $return_action == 'CallRelatedList') {
	$log->info('Products :: Deleting Price Book - Delete from PriceBook RelatedList');
	$adb->pquery('delete from vtiger_pricebookproductrel where pricebookid=? and productid=?', array($return_id, $record));
} else {
	$log->info('Products :: Deleting Price Book');
	$adb->pquery('delete from vtiger_pricebookproductrel where pricebookid=? and productid=?', array($record, $return_id));
}

header('Location: index.php?module='.$return_module."&action=$return_action&record=".urlencode($return_id));
?>