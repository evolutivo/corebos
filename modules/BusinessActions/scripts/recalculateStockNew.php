<?php
function recalculateStockNew($request){
include_once('data/CRMEntity.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
include_once('modules/Stock/Stock.php');
require_once("modules/Users/Users.php");
global $adb,$log,$current_user;
$current_user = new Users();
$current_user->retrieveCurrentUserInfoFromFile(1);
/*if (isset($argv) && !empty($argv)) {
    for ($i = 1; $i < count($argv); $i++) {
        list($key, $value) = explode("=", $argv[$i]);
        $request[$key] = $value;
    }
}*/
$allrecords=$request['recordid'];
$allstocks=$request['stockid'];
$recid = explode(',', $allstocks);
$log->debug('edlira recal');
$log->Debug($recid);
for ($j = 0; $j < count($recid); $j++) {
 $stockid=$recid[$j];
 $stockQuery=$adb->pquery("SELECT SUM(iodet.quantityin-iodet.quantityout) as total FROM vtiger_inoutdetail iodet
               INNER JOIN vtiger_crmentity ce ON ce.crmid=iodet.inoutdetailid
               WHERE ce.deleted=0 AND iodet.linktostock=?",array($stockid));
 $totalQuantity=$adb->query_result($stockQuery,0,'total');
 $stockFocus=  CRMEntity::getInstance("Stock");
 $stockFocus->retrieve_entity_info($stockid,"Stock");
 $stockFocus->column_fields['quantityinstock']=$totalQuantity;
 $stockFocus->column_fields['assigned_user_id'] = $current_user->id;
 $stockFocus->mode='edit';
 $stockFocus->id=$stockid;
 $stockFocus->save("Stock");
}
$response['stockid']=$allstocks;
echo json_encode($response,true);
}
?>
