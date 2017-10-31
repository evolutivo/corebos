<?php
global $app_strings, $mod_strings, $current_language, $currentModule, $theme, $adb, $root_directory, $current_user;
$theme_path = "themes/" . $theme . "/";
$image_path = $theme_path . "images/";
require_once ('include/utils/utils.php');
require_once ('Smarty_setup.php');
require_once ('include/database/PearDatabase.php');
// require_once('database/DatabaseConnection.php');
require_once ('include/CustomFieldUtil.php');
require_once ('data/Tracker.php');
$mapName=$_POST['NameView'];
if (isset($_POST['ObjectType']) && $_POST['ObjectType'] == "SQL") {
    $MapId = "";
    $queryid = md5(date("Y-m-d H:i:s") . uniqid(rand(), true));
    // echo "<h2>".$MapId."</h2>";
    
    $smarty = new vtigerCRM_Smarty();
    $smarty->assign("MOD", $mod_strings);
    $smarty->assign("APP", $app_strings);
    $smarty->assign("MapID", $MapId);
    $smarty->assign("queryid", $queryid);
    $smarty->assign("MapName", $mapName);
    $smarty->assign("NameView", $NameView);
    $output = $smarty->fetch('modules/MapGenerator/createJoinCondition.tpl');
    echo $output;
}else if (isset($_POST['ObjectType']) && $_POST['ObjectType'] == "Mapping") {
    $queryid=md5(date("Y-m-d H:i:s").uniqid(rand(), true));
    $smarty = new vtigerCRM_Smarty();
    $smarty->assign("MOD", $mod_strings);
    $smarty->assign("APP", $app_strings);
    $smarty->assign("MapID", $MapId);
    $smarty->assign("queryid", $queryid);
    $smarty->assign("NameView", $NameView);
    $smarty->assign("MapName", $mapName);
    $output = $smarty->fetch('modules/MapGenerator/MappingView.tpl');
    echo $output;
}else if (isset($_POST['ObjectType']) && $_POST['ObjectType'] == "MasterDetail") {
    $queryid=md5(date("Y-m-d H:i:s").uniqid(rand(), true));
    $smarty = new vtigerCRM_Smarty();
    $smarty->assign("MOD", $mod_strings);
    $smarty->assign("APP", $app_strings);
    $smarty->assign("MapID", $MapId);
    $smarty->assign("queryid", $queryid);
    $smarty->assign("NameView", $NameView);
    $smarty->assign("MapName", $mapName);
    $output = $smarty->fetch('modules/MapGenerator/MasterDetail.tpl');
    echo $output;
}else if (isset($_POST['ObjectType']) && $_POST['ObjectType'] == "ListColumns") {
    $queryid=md5(date("Y-m-d H:i:s").uniqid(rand(), true));
    $smarty = new vtigerCRM_Smarty();
    $smarty->assign("MOD", $mod_strings);
    $smarty->assign("APP", $app_strings);
    $smarty->assign("MapID", $MapId);
    $smarty->assign("queryid", $queryid);
    $smarty->assign("NameView", $NameView);
    $smarty->assign("MapName", $mapName);
    $output = $smarty->fetch('modules/MapGenerator/ListColumns.tpl');
    echo $output;
} 