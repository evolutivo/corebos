<?php 
global $adb,$current_user;
include_once('include/utils/CommonUtils.php');
$current_user->id=1;
$tableName='';
$qString="CREATE TABLE epriceandrea AS SELECT vtiger_account.accountname,vtiger_project.productdesc,vtiger_project.originalcomm FROM vtiger_account INNER JOIN vtiger_project ON vtiger_account.accountid = vtiger_project.linktoaccountscontacts inner join vtiger_accountbillads on vtiger_account.accountid=vtiger_accountbillads.accountaddressid inner join vtiger_accountshipads on vtiger_account.accountid=vtiger_accountshipads.accountaddressid INNER JOIN vtiger_project ON vtiger_account.accountid = vtiger_project.linktoaccountscontacts where (( vtiger_project.originalcomm like '�nn%' ) )";
$adb->query("drop table IF EXISTS $tableName");
$adb->query($qString);
