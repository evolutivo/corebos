<?php
/*************************************************************************************************
 * Copyright 2014 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS Customizations.
* Licensed under the vtiger CRM Public License Version 1.1 (the "License"); you may not use this
* file except in compliance with the License. You can redistribute it and/or modify it
* under the terms of the License. JPL TSolucio, S.L. reserves all rights not expressly
* granted by the License. coreBOS distributed by JPL TSolucio S.L. is distributed in
* the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
* warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Unless required by
* applicable law or agreed to in writing, software distributed under the License is
* distributed on an "AS IS" BASIS, WITHOUT ANY WARRANTIES OR CONDITIONS OF ANY KIND,
* either express or implied. See the License for the specific language governing
* permissions and limitations under the License. You may obtain a copy of the License
* at <http://corebos.org/documentation/doku.php?id=en:devel:vpl11>
*************************************************************************************************/
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

