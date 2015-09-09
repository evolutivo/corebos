<?php

/* * *******************************************************************************

 * * The contents of this file are subject to the Evolutivo BPM License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * ****************************************************************************** */
$moduleid=  getTabid($_REQUEST['formodule']);
global $adb,$log,$mod_strings;
$log->debug('alb3 '.$moduleid.' '.$_REQUEST['formodule']);
$query=$adb->pquery("Select evvtappsid,appname from vtiger_evvtapps where moduleid=?",array($moduleid));
$number=$adb->num_rows($query);
if ($number>0) $output="Template: <select class='small' id='pdftemplate' name='pdftemplate'>";
for($i=0;$i<$number;$i++)
{
$output.="<option value='".$adb->query_result($query,$i,'evvtappsid')."'>".$adb->query_result($query,$i,'appname')."</option>";
}
if ($number==0) $output='Template';
else $output.="</select> <br/><br/>";
echo $output;
?>
