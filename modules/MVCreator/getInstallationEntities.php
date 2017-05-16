<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
global $log,$adb;

$accInstallation = $_REQUEST['installationID'];
//get parameters of installation
$accQuery=$adb->pquery("select * from vtiger_accountinstallation
                       where accountinstallationid=?",array($accInstallation));

$dbname = $adb->query_result($accQuery,0,"dbname");
$acno = $adb->query_result($accQuery,0,"acin_no");

$content=array();
$result = $adb->query("SELECT * from $acno$dbname.vtiger_tab where isentitytype=1 and presence=0");
//$result = $adb->query("SELECT * from $acno$dbname.vtiger_tab where isentitytype=1 and presence=0");
$num_rows=$adb->num_rows($result);
if($num_rows!=0){
for($i=0;$i<=$num_rows;$i++)
{
//$content[$i]['name'] = $adb->query_result($result,$i,'name');
//$content[$i]['tabid'] = getTranslatedString($adb->query_result($result,$i,'tabid')); 
  $name = getTranslatedString($adb->query_result($result,$i,'name'));
  $tabid = $adb->query_result($result,$i,'tabid');
  $res.='<option value="'.$tabid.'" id="'.$name.'">'.$name.'</option>';
}
//echo json_encode($content);
echo $res;
}

