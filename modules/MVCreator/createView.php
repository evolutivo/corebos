<?php
global $app_strings, $mod_strings, $current_language, $currentModule, $theme,$adb,$root_directory,$current_user;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once ('include/utils/utils.php');
require_once('Smarty_setup.php');
require_once('include/database/PearDatabase.php');
//require_once('database/DatabaseConnection.php');
require_once ('include/CustomFieldUtil.php');
require_once ('data/Tracker.php');
$smarty = new vtigerCRM_Smarty();
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$allacc=array();
$installations = array();
global $adb;
    $res = $adb->pquery("SELECT * from  vtiger_accountinstallation
                        INNER JOIN vtiger_crmentity ON crmid=accountinstallationid 
                        INNER JOIN vtiger_account on vtiger_accountinstallation.linktoacsd = vtiger_account.accountid
                        WHERE vtiger_crmentity.smownerid=?  
                        AND  deleted=0",array($current_user->id));

    $nr = $adb->num_rows($res);
    for($i=0;$i<$nr;$i++){
        $dbname = $adb->query_result($res,$i,'dbname');
        $acin_no = $adb->query_result($res,$i,'acin_no');
        $acinstallationname = $adb->query_result($res,$i,'acinstallationname');
        $accountinstallationid = $adb->query_result($res,$i,'accountinstallationid');
        $installations[$i]['dbname'] = $dbname;
        $installations[$i]['acin_no'] = $acin_no;
        $installations[$i]['acinstallationname'] = $acinstallationname;
        $installations[$i]['accountinstallationid'] = $accountinstallationid;
    }
    



//for($i=0;$i<$adb->num_rows($res);$i++){
//    $name=$adb->query_result($res,$i,"acinstallationname").' '.$adb->query_result($res,$i,"accountname");
//    $id=$adb->query_result($res,$i,"accountinstallationid");
//    $dbname = $adb->query_result($res,$i,'dbname');
//    $acin_no = $adb->query_result($res,$i,'acin_no');
//    $name1=str_replace("'","",$name);
//    $allacc[$id."-".$name1.'-'.$acin_no.$dbname]=$name;               
//}

//$smarty->assign("allacc",$allacc);
$smarty->assign("INSTALLATIONS", $installations);
if(isset($_REQUEST['todo'])){
if($_REQUEST['todo'] == "querygenerator")
$smarty->display('modules/MVCreator/createView.tpl');
else if($_REQUEST['todo'] == "FSscript")
$smarty->display('modules/MVCreator/FSscript.tpl');
else if($_REQUEST['todo'] == "createReportTable")
$smarty->display('modules/MVCreator/ReportTable.tpl');
else if($_REQUEST['todo'] == "createReportTable2")
$smarty->display('modules/MVCreator/ReportNameTable.tpl');
}