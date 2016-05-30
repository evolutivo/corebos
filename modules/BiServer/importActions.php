<?php
global $adb;
$dirImport="import";
$content=array();

$actionQuery = "SELECT * FROM  vtiger_businessactions 
                INNER JOIN vtiger_crmentity ce ON ce.crmid=vtiger_businessactions.businessactionsid
                WHERE ce.deleted=0 AND moduleactions=?
                AND actions_status=? ";
$res = $adb->pquery($actionQuery, array('BiServer', 'Active'));
for ($i = 0; $i < $adb->num_rows($res); $i++) {
    
    $actionsid = $adb->query_result($res, $i, 'businessactionsid');
    $actionsname = $adb->query_result($res, $i, 'reference');
    $content[$i]['actionname'] = $actionsname;
    $content[$i]['id'] =  $actionsid;
}
$result = json_encode($content);
echo $result;
?>
