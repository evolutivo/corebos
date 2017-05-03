<?php
global $adb;
$content=array();
$query = "SELECT * FROM vtiger_account join vtiger_crmentity
        on vtiger_account.accountid=vtiger_crmentity.crmid
        where deleted=0";
$result = $adb->query($query);
$num_rows=$adb->num_rows($result);
if($num_rows!=0){
for($i=0;$i<=$num_rows;$i++)
{
$content[$i]['accountId'] = $adb->query_result($result,$i,'accountid');
$content[$i]['accountValue'] = getTranslatedString($adb->query_result($result,$i,'accountname')); 
}
echo json_encode($content);
}
?>