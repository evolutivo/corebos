<?php
require_once('data/CRMEntity.php');
require_once('include/utils/CommonUtils.php');
require_once('include/ListView/ListView.php');
require_once('include/utils/utils.php');
require_once('modules/CustomView/CustomView.php');
require_once('Smarty_setup.php');
require_once('config.inc.php');
global $adb,$log,$app_strings,$current_user,$theme,$currentModule,$mod_strings,$image_path,$category;
$smarty = new vtigerCRM_Smarty();

$smarty->assign('MODULE', $currentModule);
$smarty->assign('SINGLE_MOD', getTranslatedString('SINGLE_'.$currentModule));
$smarty->assign('CATEGORY', $category);
$smarty->assign('IMAGE_PATH', $image_path);
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->display('modules/FormBuilder/index.tpl');
?>
