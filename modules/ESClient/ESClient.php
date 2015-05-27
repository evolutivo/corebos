<?php
require_once('Smarty_setup.php');
global $adb,$log,$app_strings,$current_user,$theme,$currentModule,$mod_strings,$image_path,$category;
$smarty = new vtigerCRM_Smarty();
$partial = $_Request['partial'];
echo $partial;

    $smarty->display("modules/ESClient/index.tpl");
?>
