<?php
global $adb;
$content=array();
$accountId = $_REQUEST['filter']['filters'][0]['value'];
$result = $adb->pquery("SELECT * from  vtiger_accountinstallation  join
                        vtiger_account on vtiger_accountinstallation.linktoacsd = vtiger_account.accountid
                        where accountid = ?",array($accountId));
$num_rows=$adb->num_rows($result);
if($num_rows!=0){
    for($i=0;$i<$num_rows;$i++)
	{

$content[$i]['accInstId'] = $adb->query_result($result,$i,'accountinstallationid');
$content[$i]['accInstValue'] = $adb->query_result($result,$i,'acinstallationname');
}
echo json_encode($content);
}
?>