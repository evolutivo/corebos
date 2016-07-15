<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('modules/OpenStreetMap/lib/utils.inc.php');
include_once('include/utils/CommonUtils.php');

$inputshow = $moduleselected = $_POST['modulefilter'];
$selviewid = explode(",",$_POST['selviewid']);
$html = getCustomViewCheckboxes($selviewid,$moduleselected);        
echo $html;
?>
