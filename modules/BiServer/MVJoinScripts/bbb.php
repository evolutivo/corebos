<?php 
global $adb,$current_user;
include_once('include/utils/CommonUtils.php');
$current_user->id=1;
$tableName='';
$qString="CREATE TABLE bbb AS SELECT vtiger_account.accountname,vtiger_project.productdesc FROM vtiger_account INNER JOIN vtiger_project ON vtiger_account.accountid = vtiger_project.linktoaccountscontacts inner join vtiger_accountbillads on vtiger_account.accountid=vtiger_accountbillads.accountaddressid inner join vtiger_accountshipads on vtiger_account.accountid=vtiger_accountshipads.accountaddressid INNER JOIN vtiger_project ON vtiger_account.accountid = vtiger_project.linktoaccountscontacts where (( vtiger_project.productdesc <> '' and vtiger_account.accountname like '%eprice%' ) )";
$adb->query("drop table IF EXISTS $tableName");
$adb->query($qString);
