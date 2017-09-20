<?php
$idis=$_REQUEST["id"];
chdir("../..");
include_once("include/database/PearDatabase.php");
global $adb,$site_URL;
$getname=$adb->query("select name from ethercalc where id=$idis");
$nameis=$adb->query_result($getname,0,0);
$dt=array();
$dt['spspreadsheet']=$nameis;
$namenew=explode("/",$nameis);
$dt['siteurl']=$site_URL."/modules/Spreadsheets/newsp.php?id=".$namenew[3];
echo json_encode($dt);
?>