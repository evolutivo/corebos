<?php 
global $adb,$current_user;
include_once('include/utils/CommonUtils.php');
$current_user->id=1;
$tableName='';
$qString="CREATE TABLE exporttechnoassist AS SELECT vtiger_products.productname,vtiger_products.productcategory,vtiger_products.vendor_id,vtiger_products.serialno,vtiger_products.unit_price,vtiger_products.taxclass FROM vtiger_project INNER JOIN vtiger_account ON vtiger_project.linktoaccountscontacts = vtiger_account.accountid INNER JOIN vtiger_account ON vtiger_project.linktoaccountscontacts = vtiger_account.accountid inner join  vtiger_accountbillads on vtiger_account.accountid=vtiger_accountbillads.accountaddressid inner join  vtiger_accountshipads on vtiger_account.accountid=vtiger_accountshipads.accountaddressid INNER JOIN vtiger_account ON vtiger_project.linktoaccountscontacts = vtiger_account.accountid INNER JOIN vtiger_account ON vtiger_project.linktoaccountscontacts = vtiger_account.accountid INNER JOIN vtiger_account ON vtiger_project.linktoaccountscontacts = vtiger_account.accountid where (( vtiger_account.accountname like '%eprice%' ) )";
$adb->query("drop table IF EXISTS $tableName");
$adb->query($qString);
