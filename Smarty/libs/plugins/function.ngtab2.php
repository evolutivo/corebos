<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {ngtab2} function plugin
 * Type:     function<br>
 * Name:     ngtab2<br>
 * Purpose:  ng tabs
 * @param Smarty_Internal_Template $template template object
 *
 * @return string|null
 */
function smarty_function_ngtab2(array $params, Smarty_Internal_Template $template) {
if(!empty($_REQUEST['ng_tab'])) {
        $template->assign("ng_tab", $_REQUEST['ng_tab']);
}
include_once('vtlib/Vtiger/Link.php');
$customlink_params = Array('MODULE'=>$template->getTemplateVars('MODULE'), 'RECORD'=>$template->getTemplateVars('ID'), 'ACTION'=>vtlib_purify('DetailView'));
$template->assign('CUSTOM_LINKS', Vtiger_Link::getAllByType(getTabid($template->getTemplateVars('MODULE')), Array('RELATEDVIEWWIDGET'), $customlink_params));   
}

