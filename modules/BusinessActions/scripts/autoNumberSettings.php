<?php

include_once('data/CRMEntity.php');
include_once('modules/Stock/Stock.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
global $adb, $log, $current_user;

//$recordid=$_REQUEST['recordid'];
//$mapid=$_REQUEST['map'];
if (isset($argv) && !empty($argv)) {
    $documentType = $argv[1];
    $module = $argv[2];
    $recordid = $argv[3];
    $fieldname=$argv[4];
}
include_once("modules/$module/$module.php");

$settingquery = pquery("SELECT * FROM vtiger_setting
               INNER JOIN vtiger_crmentity ce ON ce.crmid=vtiger_setting.settingid
               WHERE ce.deleted=0 AND vtiger_setting.tipo_documento=?", array($documentType));
$nrsetting = $adb->num_rows($settingquery);
if ($nrsetting > 0) {
    $settingid = $adb->query_result($settingquery, 0, 'settingid');
    $progressive = $adb->query_result($settingquery, 0, 'ultimo_no') + 1;
    $suffisso = $adb->query_result($settingquery, 0, 'suffisso');
}
if ($progressive / 10 < 1)
    $progressive_final = '000' . $progressive;
else if ($progressive / 100 < 1)
    $progressive_final = '00' . $progressive;
else if ($progressive / 1000 < 1)
    $progressive_final = '0' . $progressive;
else
    $progressive_final = $progressive;

$nr_doc = $suffisso . "-" . $progressive_final;


$focus=  CRMEntity::getInstance($module);
$focus->retrieve_entity_info($recordid,$module);
$focus->column_fields[$fieldname]=$nr_doc;
$focus->mode='edit';
$focus->id=$recordid;
$focus->save($module);

$settingFocus=  CRMEntity::getInstance("Setting");
$settingFocus->retrieve_entity_info($settingid,"Setting");
$settingFocus->column_fields['ultimo_no']=$progressive;
$settingFocus->column_fields['ultima_data']=date('Y-m-d');
$settingFocus->mode='edit';
$settingFocus->id=$settingid;
$settingFocus->save("Setting");


?>
