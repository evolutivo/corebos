<?php

function updateProject($request) {
    
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
    $notepickandpay = $request['notepickandpay'];
    $accesoriresi = $request['accesoriresi'];
    
    for($c_size=0;$c_size<sizeof($allrecordsID);$c_size++){
        $adb->pquery("Update vtiger_project"
                . " set notepickandpay=?,"
                . " accesoriresi=?"
                . " where projectid=?", array($notepickandpay,$accesoriresi,$allrecordsID[$c_size]));
    }
}
?>
