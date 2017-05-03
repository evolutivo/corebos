<?php
global $log,$adb;

$accInstallation = $_REQUEST['installationID'];
//get parameters of installation
$accQuery=$adb->pquery("select * from vtiger_accountinstallation
                       where accountinstallationid=?",array($accInstallation));

$dbname = $adb->query_result($accQuery,0,"dbname");
$acno = $adb->query_result($accQuery,0,"acin_no");

$content=array();
$query="SELECT reportname,reportid,queryid from $acno$dbname.vtiger_report";
$result = $adb->query($query);
$num_rows=$adb->num_rows($result);
if($num_rows!=0){
for($i=0;$i<=$num_rows;$i++)
{
//$content[$i]['reportId'] = $adb->query_result($result,$i,'reportid');
 $reportId = $adb->query_result($result,$i,'reportid');
//$content[$i]['reportValue'] = getTranslatedString($adb->query_result($result,$i,'reportname')); 
 $reportValue = getTranslatedString($adb->query_result($result,$i,'reportname'));
 if((isset($_REQUEST['selectedview']) && $_REQUEST['selectedview'] == "clientreport") || (isset($_REQUEST['selectedview']) && $_REQUEST['selectedview'] == "clientreport2"))
  $res.='<option value="'.$reportId.'">'.$reportId.'_'.$reportValue.'</option>';
 else $res.='<option value="'.$reportId.'">'.$reportValue.'</option>';
}
//echo json_encode($content);
echo $res;
}
?>