<?php
chdir("../..");
include_once("include/database/PearDatabase.php");
$ethercalcendpoint="http://193.182.16.151:8000/_";
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
//echo 'aaaaaaaaaaaaa'.$response;
$newresp=substr($ethercalcendpoint,0,-1).substr($response,1);
echo $newresp;
//echo "insert into ethercalc values (NULL,'$newresp',NOW())";
$adb->query("insert into ethercalc values (NULL,'$newresp','',NOW(),'')");
?>