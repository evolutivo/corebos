<?php
include_once('language/en_us.lang.php');
global $mod_strings;
require_once("Smarty_setup.php");
$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD",$mod_strings);
$smarty->display("modules/EtherCalc/index.tpl");
?>
