<?php
function createspreadsheetdv($request){
//ini_set("display_errors","On");
require_once('include/database/PearDatabase.php');
require_once('modules/Spreadsheets/Spreadsheets.php');
//require_once('modules/Adocdetail/Adocdetail.php');
include_once('data/CRMEntity.php');
//include_once('modules/Map/Map.php');
require_once('include/utils/utils.php');
global $adb,$log,$current_user,$site_URL;
$recid=$request["recordid"];
$checkethercalcid=$adb->query("select ethercalcid from vtiger_spreadsheets where spreadsheetsid=$recid");
$ethidis=$adb->query_result($checkethercalcid,0,0);
if($ethidis!='' && !is_null($ethidis)){
	
	$exp1=explode("/",$ethidis);
	$exp2=explode("?",$exp1[6]);
	$exp3=explode("=",$exp2[1]);
	echo $exp3[1];
}
else {
$modflds=array();
$querysp=$adb->query("select * from vtiger_spreadsheets where spreadsheetsid=$recid");
$norows=$adb->num_rows($querysp);
$modulefields=$adb->query_result($querysp,0,'modulecolumns');
$othercolumns=$adb->query_result($querysp,0,'othercolumns');
$namecrm=$adb->query_result($querysp,0,'spreadsheetsname');
//echo $modulefields;
$getallfields=explode(",",$modulefields);
//echo count($getallfields);
for($z=0;$z<count($getallfields);$z++){
	$actualfield=explode("::",$getallfields[$z]);
	//echo $actualfield[1];
	array_push($modflds,$actualfield[1]);
}
include_once("modules/GlobalVariable/GlobalVariable.php");
global $current_user;
$current_user->id=1;
$ethercalcendpoint=GlobalVariable::getVariable('ecendpoint', '');
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

$newresp=substr($ethercalcendpoint,0,-1).substr($response,1);
$newresp1=substr($response,1);
$exp1=explode("/",$newresp);
echo $exp1[3];

$newtouse=$site_URL."/modules/Spreadsheets/newsp.php?id=".substr($response,1);
$adb->query("insert into ethercalc values (NULL,'$newresp','$namecrm',NOW(),'$recid')");
$thesettypeis='Spreadsheets';
$focusnew = CRMEntity::getInstance($thesettypeis);
$focusnew->retrieve_entity_info($recid,$thesettypeis);
$focusnew->id=$recid;
$focusnew->mode='edit';
$focusnew->column_fields['ethercalcid']=$newtouse;
$focusnew->save($thesettypeis);
}
}
?>
