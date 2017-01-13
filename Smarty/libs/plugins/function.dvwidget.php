<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {dvwidget} function plugin
 * Type:     function<br>
 * Name:     dvwidget<br>
 * Purpose:  fng tabs
 * @param Smarty_Internal_Template $template template object
 *
 * @return string|null
 */
function smarty_function_dvwidget(array $params, Smarty_Internal_Template $template) {
echo vtlib_process_widget($template->_tpl_vars['CUSTOM_LINK_DETAILVIEWWIDGET'], $template->_tpl_vars);   
}

