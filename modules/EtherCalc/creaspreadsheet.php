<?php
chdir("../..");
//ini_set('display_errors','On');
include_once("include/database/PearDatabase.php");
include_once("include/utils/utils.php");
include_once("modules/GlobalVariable/GlobalVariable.php");
global $current_user;
$current_user->id=1;
$ethercalcendpoint=GlobalVariable::getVariable('ecendpoint', '');
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ethercalcendpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
$allstring="";
curl_setopt($ch,CURLOPT_POSTFIELDS,$allstring);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: text/csv"
));
$response=curl_exec($ch);
$newresp=substr($ethercalcendpoint,0,-1).substr($response,1);
echo $newresp;
$adb->query("insert into ethercalc values (NULL,'$newresp','',NOW(),'')");
?>