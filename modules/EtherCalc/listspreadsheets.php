<?php
ini_set("display_errors","On");
chdir("../..");
include_once("include/database/PearDatabase.php");
include_once("include/utils/utils.php");
global $adb;
$content=array();
$test = $adb->query("select * from ethercalc where 1");
$norows=$adb->num_rows($test);

for($i=0;$i<$norows;$i++){
  $content[$i]["id"]=$adb->query_result($test,$i,'id');
  $content[$i]['name']=$adb->query_result($test,$i,'name');
  $content[$i]['createdtime']=$adb->query_result($test,$i,'createdtime');
  $content[$i]['namecrm']=$adb->query_result($test,$i,'namecrm');
}

echo json_encode($content);
?>