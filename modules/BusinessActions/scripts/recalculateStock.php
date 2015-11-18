<?php
include_once('data/CRMEntity.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
include_once('modules/Stock/Stock.php');
require_once("modules/Users/Users.php");
global $adb,$log,$current_user;
$current_user = new Users();
$current_user->retrieveCurrentUserInfoFromFile(1);
//$recordid=$_REQUEST['recordid'];
//$mapid=$_REQUEST['map'];
 if(isset($argv) && !empty($argv)){
  $allstocks = $argv[1];
 }
$recid = explode(',', $allstocks);
for ($j = 0; $j < count($recid); $j++) {
 $stockid=$recid[$j];
 $stockQuery=$adb->pquery("SELECT SUM(iodet.quantityin-iodet.quantityout) as totalQuantity FROM vtiger_inoutdetail iodet
               INNER JOIN vtiger_crmentity ce ON ce.crmid=iodet.inoutdetailid
               WHERE ce.deleted=0 AND iodet.linktostock=?",array($stockid));
 $totalQuantity=$adb->query_result($stockQuery,0,'totalQuantity');
 
 $stockFocus=  CRMEntity::getInstance("Stock");
 $stockFocus->retrieve_entity_info($stockid,"Stock");
 $stockFocus->column_fields['quantityinstock']=$totalQuantity;
 $stockFocus->column_fields['assigned_user_id'] = $current_user->id;
 $stockFocus->mode='edit';
 $stockFocus->id=$stockid;
 $stockFocus->save("Stock");
}
?>
