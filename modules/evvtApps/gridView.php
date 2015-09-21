<?php
/*************************************************************************************************
 * Copyright 2012-2013 OpenCubed  --  
 * You can copy, adapt and distribute the work under the "Attribution-NonCommercial-ShareAlike"
 * Vizsage Public License (the "License"). You may not use this file except in compliance with the
 * License. Roughly speaking, non-commercial users may share and modify this code, but must give credit
 * and share improvements. However, for proper details please read the full License, available at
 * http://vizsage.com/license/Vizsage-License-BY-NC-SA.html and the handy reference for understanding
 * the full license at http://vizsage.com/license/Vizsage-Deed-BY-NC-SA.html. Unless required by
 * applicable law or agreed to in writing, any software distributed under the License is distributed
 * on an  "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the
 * License terms of Creative Commons Attribution-NonCommercial-ShareAlike 3.0 (the License).
 *************************************************************************************************
 *  Module       : evvtApps
 *  Version      : 1.8
 *  Author       : OpenCubed
 *************************************************************************************************/
 
global $current_language,$current_user;
$mypath="modules/$currentModule";
include_once "$mypath/processConfig.php";
include_once "$mypath/vtapps/baseapp/vtapp.php";
include "$mypath/language/$current_language.lang.php";
global $adb;
$us=$current_user->id;
$mk=Array();
    $r=$adb->query("select * from vtiger_evvtapps join vtiger_evvtappsuser e on e.appid=evvtappsid join vtiger_user2role r on r.userid=e.userid join vtiger_users on id=r.userid where  id='$us' and widget in (1,2,3,4,5,6)");
$count=$adb->num_rows($r);

for($j=0;$j<$count;$j++){$id=$adb->query_result($r,$j,'evvtappsid');
$nm=$adb->query_result($r,$j,'appname');

    $mk[$j]="makePosition".$adb->query_result($r,$j,'widget')."('$id','$nm')";
}
$mk=implode(";",$mk);
//echo $mk;

echo"<script type=\"text/javascript\">
   function makeGrid(){

       $mk
    };
    function makePosition1(appId,appName){
        //jQuery(\"#position1\").html(\"<img src='modules/evvtApps/images/ajax-loader.gif'>\");
        var dataString = 'appId='+ appId+ '&appName='+ appName;
        jQuery.ajax({
            type: \"POST\",
            data: dataString,
            url:  \"index.php?module=evvtApps&action=evvtAppsAjax&file=makePosition\",
            success:function(makePosition1){
                jQuery(\"#position1\").html(makePosition1);

            }
        })
        return false;
    };
    function makePosition2(appId,appName){

        var dataString = 'appId='+ appId+ '&appName='+ appName;
        jQuery.ajax({
            type: \"POST\",
            data: dataString,
            url:  \"index.php?module=evvtApps&action=evvtAppsAjax&file=makePosition\",
            success:function(makePosition2){
                jQuery(\"#position2\").html(makePosition2);

            }
        })
        return false;
    };
    function makePosition3(appId,appName){

        var dataString = 'appId='+ appId+ '&appName='+ appName;
        jQuery.ajax({
            type: \"POST\",
            data: dataString,
            url:  \"index.php?module=evvtApps&action=evvtAppsAjax&file=makePosition\",
            success:function(makePosition3){
                jQuery(\"#position3\").html(makePosition3);

            }
        })
        return false;
    };

    function makePosition4(appId,appName){

        var dataString = 'appId='+ appId+ '&appName='+ appName;
        jQuery.ajax({
            type: \"POST\",
            data: dataString,
            url:  \"index.php?module=evvtApps&action=evvtAppsAjax&file=makePosition\",
            success:function(makePosition4){
                jQuery(\"#position4\").html(makePosition4);

            }
        })
        return false;
    };

      function makePosition5(appId,appName){

        var dataString = 'appId='+ appId+ '&appName='+ appName;
        jQuery.ajax({
            type: \"POST\",
            data: dataString,
            url:  \"index.php?module=evvtApps&action=evvtAppsAjax&file=makePosition\",
            success:function(makePosition5){
                jQuery(\"#position5\").html(makePosition5);

            }
        })
        return false;
    };
    function makePosition6(appId,appName){

        var dataString = 'appId='+ appId+ '&appName='+ appName;
        jQuery.ajax({
            type: \"POST\",
            data: dataString,
            url:  \"index.php?module=evvtApps&action=evvtAppsAjax&file=makePosition\",
            success:function(makePosition6){
                jQuery(\"#position6\").html(makePosition6);

            }
        })
        return false;
    };
    makeGrid();
</script>";
?>
<div id="gridContent" style="width:100%">
<div id="position1">1</div>
<div id="position2">2</div>
<div id="position3">3</div>
<div id="position4">4</div>
<div id="position5">5</div>
<div id="position6">6</div>


</div>
