<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {ngtabadoc} function plugin
 * Type:     function<br>
 * Name:     ngtabadoc<br>
 * Purpose:  ng tabs for adocmaster
 * @param Smarty_Internal_Template $template template object
 *
 * @return string|null
 */
function smarty_function_ngtabadoc(array $params, Smarty_Internal_Template $template) {
require_once('include/utils/UserInfoUtil.php');
global $current_user,$mod_strings;
$template->assign("ROLENAME", getRoleName($current_user->roleid));
$template->assign("MOD",$mod_strings); 
global $adb;
$productsquery=$adb->query("select productname from vtiger_products");
$prodname=array();
for($i=0;$i<$adb->num_rows($productsquery);$i++){
$prodname[$i]=$adb->query_result($productsquery,$i,'productname');
}
$template->assign('prodiri',$prodname);
}

