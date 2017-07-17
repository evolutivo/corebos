<?php
function createspreadsheetdv($request){
ini_set("display_errors","On");
require_once('include/database/PearDatabase.php');
require_once('modules/Adocmaster/Adocmaster.php');
require_once('modules/Adocdetail/Adocdetail.php');
include_once('data/CRMEntity.php');
include_once('modules/Map/Map.php');
require_once('include/utils/utils.php');
global $adb,$log,$current_user;
$recid=$request["recordid"];
$modflds=array();
$querysp=$adb->query("select * from vtiger_spreadsheets where spreadsheetsid=$recid");
$norows=$adb->num_rows($querysp);
$modulefields=$adb->query_result($querysp,0,'modulecolumns');
$othercolumns=$adb->query_result($querysp,0,'othercolumns');
$namecrm=$adb->query_result($querysp,0,'spreadsheetsname');
echo $modulefields;
$getallfields=explode(",",$modulefields);
echo count($getallfields);
for($z=0;$z<count($getallfields);$z++){
	$actualfield=explode("::",$getallfields[$z]);
	echo $actualfield[1];
	array_push($modflds,$actualfield[1]);
}

$ethercalcendpoint="http://localhost:8000/_";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ethercalcendpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
$allstring="crmid,createdtime,modifiedtime,".implode(",",$modflds).",".$othercolumns;
curl_setopt($ch,CURLOPT_POSTFIELDS,$allstring);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: text/csv"
));
$response=curl_exec($ch);
//echo 'aaaaaaaaaaaaa'.$response;
$newresp=substr($ethercalcendpoint,0,-1).substr($response,1);
echo $newresp;
//echo "insert into ethercalc values (NULL,'$newresp',NOW())";
$adb->query("insert into ethercalc values (NULL,'$newresp','$namecrm',NOW(),'$recid')");
}
?>