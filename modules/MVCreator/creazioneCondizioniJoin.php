<?php
$MapId = "";
if (isset($_POST['MapID'])) {
    $MapId = $_POST['MapID'];
}

//echo "<h2>".$MapId."</h2>";
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
$smarty->assign("MapID", $MapId);
$output = $smarty->fetch('modules/MVCreator/createJoinCondition.tpl');
echo $output;
?>