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
<link href="<?php echo $mypath; ?>/styles/evvtapps.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $mypath; ?>/styles/kendo.common.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $mypath; ?>/styles/kendo.default.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $mypath; ?>/styles/tipsy.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $mypath; ?>/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo $mypath; ?>/js/kendo.all.js" type="text/javascript"></script>
<script src="<?php echo $mypath; ?>/js/jquery.tipsy.js" type="text/javascript"></script>
<script src="<?php echo $mypath; ?>/js/evvtapps.js" type="text/javascript"></script>
<script src="<?php echo $mypath; ?>/js/kendo.web.js" type="text/javascript"></script>

<div id="evvtCanvas" class="evvtCanvas">
<?php
if (is_admin($current_user))
	$rsapps=$adb->pquery('select evvtappsid from vtiger_evvtapps
	 left join vtiger_evvtappsuser on appid=evvtappsid
	 where userid=? and evvtappsid!=1 order by sortorder',array($current_user->id));
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
	 where userid=? and wenabled and evvtappsid!=1 order by sortorder',array($current_user->id));
}
$numapps=$adb->num_rows($rsapps);
for ($app=0;$app<$numapps;$app++) {
	$appid=$adb->query_result($rsapps,$app,'evvtappsid');
	$loadedclases=get_declared_classes();
	include_once "$mypath/vtapps/app$appid/vtapp.php";
	$newclass=array_diff(get_declared_classes(), $loadedclases);
	$newclass=array_pop($newclass);
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
	$windiv="<div id='$divid' class='evvtappbox' vtappid='$appid' vtappclass='$newclass'
	       title='<b>".$newApp->getAppName($current_language)."</b><br>".$newApp->getTooltipDescription($current_language)."' ";
	if ($canshow==1) {
	$windiv.=" onclick='evvtappsOpenWindow($appid,\"$newclass\",".$newApp->getAppInfo($current_language).','.$newApp->getEditInfo($current_language).")'";
	}
	$windiv.="><img src='".$newApp->getAppIcon()."'></div>";
	echo $windiv.'<script language="javascript">';
	echo "$('#$divid').tipsy($tipsy_settings);";
	echo "$('#$divid').kendoDraggable({
                        hint: function() {
                        	var imgclone=$('#$divid').clone();
                        	//imgclone.css({marginLeft: '-40px', marginTop: '-40px'});  // This is to center the drag image on the cursor, but if I add this the drag doesn't work
                            return imgclone;
                        },
                        vtappid:$appid,
                        vtappclass:'$newclass'
                        });";
	echo "$('#$divid').kendoDropTarget({
							dragenter: sorttargetOnDragEnter,
						    dragleave: sorttargetOnDragLeave,
							drop: sorttargetOnDrop,
	                        vtappid:$appid,
	                        vtappclass:'$newclass'
                        });";
	echo $newApp->getCanvasJavascript($current_language); // for iconic/javascript apps and similar
	if ($visible==1) { // Open the visible widgets for the current user
	echo "evvtappsOpenWindow($appid,'$newclass',".$newApp->getAppInfo($current_language).','.$newApp->getEditInfo($current_language).');';
	}
	echo '</script>';
}
// Now we do Trash Can, always at the end
$numdel=$adb->getone("select count(*) from vtiger_evvtappsuser where wenabled and candelete and userid=".$current_user->id);
if (is_admin($current_user) or $numdel>0) {
include_once "$mypath/vtapps/app1/vtapp.php";
$newApp=new vtAppcomTSolucioTrash(1);
?>
<div id='evvtapptrash' class='evvtappbox' title='<b><?php echo $newApp->getAppName($current_language); ?></b><br><?php echo $newApp->getTooltipDescription($current_language); ?>'><img src='<?php echo $newApp->getAppIcon(); ?>'></div>
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