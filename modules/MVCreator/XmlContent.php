<?php
/**
 * Created by PhpStorm.
 * User: Edmond Kacaj
 * Date: 5/10/2017
 * Time: 2:20 PM
 */
require_once ('include/utils/utils.php');
require_once('Smarty_setup.php');
require_once('include/database/PearDatabase.php');
require_once('database/DatabaseConnection.php');
require_once ('include/CustomFieldUtil.php');
require_once ('data/Tracker.php');
global $app_strings, $mod_strings, $current_language, $currentModule, $theme,$adb,$root_directory,$current_user,$log,$adb;

if (isset($_POST['MapID'])){
    $mapid=$_POST['MapID'];
    $XmlConvertertoarray=array();
    $query=$adb->query("select description from vtiger_crmentity where crmid=$mapid");
    $description=$adb->query_result($query,0,"description");
    $movies = new SimpleXMLElement($description);
    $FirstSecondModule=array();
    $Fields=array();
    $FirstSecondModule[]=array(
        'FmoduleID'=>$movies->Fmodule[0]->FmoduleID,
        'FmoduleName'=>$movies->Fmodule[0]->FmoduleName,
        'SecmoduleID'=>$movies->Secmodule[0]->SecmoduleID,
        'SecmoduleName'=>$movies->Secmodule[0]->SecmoduleName,

    );
     foreach($movies->fields->field as $field) {
         $Fields[]=array(
                'fieldname' => $field->fieldname,
                'fieldID'=>$field->fieldID,
                );

     }

}
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$smarty = new vtigerCRM_Smarty();
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("FirstSecModule", $FirstSecondModule);
$smarty->assign("Fields", $Fields);
$smarty->assign("MapID", $mapid);
$output = $smarty->fetch('modules/MVCreator/createJoinCondition.tpl');
echo $output;
?>

