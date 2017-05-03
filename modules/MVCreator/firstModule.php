<?php
// $rec = $_REQUEST['installationID'];
// $ai=$adb->query("select * from vtiger_accountinstallation join vtiger_crmentity on crmid=accountinstallationid 
//                 join vtiger_account on accountid=linktoacsd where accountinstallationid=$rec");
// $dbname=$adb->query_result($ai,0,"dbname");
//$acno=$adb->query_result($ai,0,"acin_no");
if(isset($_REQUEST['secModule']) && isset($_REQUEST['firstModule'])){ 
 $secModule = implode(',', array_keys(array_flip(explode(',', $_REQUEST['secModule'])))); 
 $modulesAllowed = '"'.$_REQUEST['firstModule'].'","'. str_replace(',', '","',$secModule).'"';   
 $query="SELECT * from vtiger_tab where isentitytype=1 and name<>'Faq' 
        and name<>'Emails' and name<>'Events' and name<>'Webmails' and name<>'SMSNotifier'
        and name<>'PBXManager' and name<>'Modcomments' and name<>'Calendar' and presence=0
        and name in ($modulesAllowed)";
}
else 
$query="SELECT * from vtiger_tab where isentitytype=1 and name<>'Faq' 
        and name<>'Emails' and name<>'Events' and name<>'Webmails' and name<>'SMSNotifier'
        and name<>'PBXManager' and name<>'Modcomments' and name<>'Calendar' and presence=0";
$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
        if($num_rows!=0){
	for($i=1;$i<=$num_rows;$i++)
	{
		$modul1 = $adb->query_result($result,$i-1,'name');

                $a.='<option value="'.$modul1.'">'.str_replace("'","",getTranslatedString($modul1)).'</option>';
        }
}
        echo $a;
?>
