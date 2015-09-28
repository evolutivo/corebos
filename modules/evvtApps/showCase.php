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
?>
<div id="evvtCanvas" class="evvtCanvas">
<div id="showCase">
<?php
if (is_admin($current_user))
	$rsapps=$adb->pquery('select evvtappsid from vtiger_evvtapps
	 left join vtiger_evvtappsuser on appid=evvtappsid
	 where userid=? and evvtappsid!=1 and evvtappsid!=2 and evvtappsid!=3 order by visits DESC LIMIT 6',array($current_user->id));
else {
	for ($aid=1;$aid<4;$aid++) {  // We have to make sure the user has access to at least the base apps
		$apcnt=$adb->getone("select count(*) from vtiger_evvtappsuser where appid=$aid and userid=".$current_user->id);
		if ($apcnt==0) {
			$rs=$adb->pquery('INSERT INTO vtiger_evvtappsuser
		 (appid,userid,wtop,wleft,wwidth,wheight,wvisible,wenabled,canwrite,candelete,canhide,canshow)
		 VALUES (?,?,?,?,?,?,?,?,?,?,?,?)',
		 array($aid,$current_user->id,$window_top,$window_left,$window_width,$window_height,0,1,1,0,1,1));
		}
	}
	$rsapps=$adb->pquery('select evvtappsid from vtiger_evvtapps
	 inner join vtiger_evvtappsuser on appid=evvtappsid
	 where userid=? and wenabled and evvtappsid!=1 and evvtappsid!=2 and evvtappsid!=3 order by sortorder',array($current_user->id));
}
$numapps=$adb->num_rows($rsapps);
global $log;
for ($app=0;$app<$numapps;$app++) {
	$appid=$adb->query_result($rsapps,$app,'evvtappsid');
	$loadedclases=get_declared_classes();
	include_once "$mypath/vtapps/app$appid/vtapp.php";
	$newclass=array_diff(get_declared_classes(), $loadedclases);
	$newclass=array_pop($newclass);
        $log->debug('gil2 '.$appid.' '.$newclass);
	$newApp=new $newclass($appid);
	$rswincfg=$adb->query("select wvisible,canshow from vtiger_evvtappsuser where appid=$appid and userid=".$current_user->id);
	if ($adb->num_rows($rswincfg)>0) {
		$wincfg=$adb->fetch_array($rswincfg);
		$visible=$wincfg['wvisible'];
		$canshow=$wincfg['canshow'];
	} else {
		$visible=1;
		$canshow=1;
	}
	$divid="evvtapp$appid";
	$windiv="<div  vtappid='$appid' vtappclass='$newclass'
	       title='".$newApp->getTooltipDescription($current_language)."'";
	if ($canshow==1) {
	$windiv.=" onclick='evvtappsOpenWindow($appid,\"$newclass\",".$newApp->getAppInfo($current_language).','.$newApp->getEditInfo($current_language).")'";
	}
	$windiv.="><img src='".$newApp->getAppIcon()."'>".$newApp->getAppName($current_language)."</div>";
	echo $windiv.'<script language="javascript">';

	if ($visible==1) { // Open the visible widgets for the current user
	//echo "evvtappsOpenWindow($appid,'$newclass',".$newApp->getAppInfo($current_language).','.$newApp->getEditInfo($current_language).');';
	}
	echo '</script>';
}
// Now we do Trash Can, always at the end
$numdel=$adb->getone("select count(*) from vtiger_evvtappsuser where wenabled and candelete and userid=".$current_user->id);
if (is_admin($current_user) or $numdel>0) {
include_once "$mypath/vtapps/app1/vtapp.php";
$newApp=new vtAppcomTSolucioTrash(1);
?>
<script language="javascript">
$("#evvtapptrash").tipsy(<?php echo $tipsy_settings; ?>);
$("#evvtapptrash").kendoDropTarget({
	dragenter: deltargetOnDragEnter,
	dragleave: deltargetOnDragLeave,
	drop: droptargetTrashApp
});
$("#evvtapptrash").css('opacity',0.5);
</script>
<?php } ?>
</div>
</div> <!-- evvtCanvas -->
<script language="javascript">
$(window).unload( unloadCanvas );  // to catch user configuration before leaving
// vtApps javascript strings
var vtapps_strings = {
<?php
 foreach ($vtapps_js as $key=>$value) {
 	echo "$key: '".addslashes($value)."',";
 }
?>
evvtEmptyElementToCloseDefinitionCorrectly: 'void'
};
</script>
