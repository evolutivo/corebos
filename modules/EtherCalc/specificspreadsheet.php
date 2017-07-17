<?php
$idis=$_REQUEST["id"];
chdir("../..");
include_once("include/database/PearDatabase.php");
global $adb;
$getname=$adb->query("select name from ethercalc where id=$idis");
$nameis=$adb->query_result($getname,0,0);
echo $nameis;
?>