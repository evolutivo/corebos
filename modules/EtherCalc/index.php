<?php
//ini_set('display_errors',On);
//error_reporting(E_ALL);
include_once('language/en_us.lang.php');
//echo "<font color=red>".HELLO_WORLD."</font>";
global $mod_strings;
//var_dump($mod_strings);
//exit;
require_once("Smarty_setup.php");

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD",$mod_strings);
$smarty->display("modules/EtherCalc/index.tpl");
?>
