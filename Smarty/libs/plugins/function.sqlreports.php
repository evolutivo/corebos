<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {sqlreports} function plugin
 * Type:     function<br>
 * Name:     sqlreports<br>
 * Purpose:  sql reports for adocmaster
 * @param Smarty_Internal_Template $template template object
 *
 * @return string|null
 */
function smarty_function_sqlreports(array $params, Smarty_Internal_Template $template) {
$__oReportRun = $template->getTemplateVars('__REPORT_RUN_INSTANCE');
$__oReportRunReturnValue = $__oReportRun->GenerateReport("HTML", $__filterList, true);
if(is_array($__oReportRunReturnValue)) { $__oReportRun->GenerateReport("TOTALHTML", $__filterList, true); }			
}

