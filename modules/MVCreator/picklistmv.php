
<?php
global $adb,$log;
$rec = $_REQUEST['val'];
$ai=$adb->query("select * from vtiger_accountinstallation 
                 join vtiger_crmentity on crmid=accountinstallationid 
                 join vtiger_account on accountid=linktoacsd 
                 where accountinstallationid = $rec");

$dbname=$adb->query_result($ai,0,"dbname");
$acno=$adb->query_result($ai,0,"acin_no");

$query="SELECT reportname,reportid,queryid from $acno$dbname.vtiger_report";
$result = $adb->query($query);
$num_rows=$adb->num_rows($result);
if($num_rows!=0){
   for($i=1;$i<=$num_rows;$i++)
    {
	$modul1 = $adb->query_result($result,$i-1,'reportid');
        $column = $adb->query_result($result,$i-1,'queryid');
        $fl= $adb->query_result($result,$i-1,'reportname');
        $a.='<option value="'.$modul1.' ">'.str_replace("'","",getTranslatedString($modul1.'_'.$fl)).'</option>';
    }
}
echo $a;
?>
