<?php

function assignPickPay($request) {
    
    include_once('data/CRMEntity.php');
    require_once('include/utils/utils.php');
    require_once('include/database/PearDatabase.php');
    global $adb, $log, $current_user; 

    $recordid = $request['recordid'];
    $pos=strpos($recordid,',');
    if($pos!==false)
    {
       $allrecordsID=explode(',',$recordid);
    }
    else{
       $allrecordsID=array($recordid);
    }
    $pickpay = $request['pickpay'];
    $p=explode('x',$pickpay);
    $pickpay=$p[1];
    $res_pick=$adb->pquery("Select vettore"
                . " from vtiger_pickandpay "
                . " where pickandpayid=?", array($pickpay));
    $vettore=$adb->query_result($res_pick,0,'vettore');

    for($c_size=0;$c_size<sizeof($allrecordsID);$c_size++){
        $adb->pquery("Update vtiger_project"
                . " set pickandpay=?,"
                . " vettorepickpay=?"
                . " where projectid=?", array($pickpay,$vettore,$allrecordsID[$c_size]));
    }
     $response['recordid']=$recordid;
     return $response;
}
?>
