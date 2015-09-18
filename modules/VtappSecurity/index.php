<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
global $theme;
$smarty = new vtigerCRM_Smarty;
$smarty->assign("NOGIF",vtiger_imageurl('no.gif', $theme));
$smarty->assign("PRVPRFSELECTED",vtiger_imageurl('prvPrfSelectedTick.gif', $theme)); 
$smarty->display("modules/VtappSecurity/listConfiguration.tpl");
?>
