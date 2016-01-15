<?php

global $root_directory,$theme,$current_user;

$theme_path="themes/".$theme."/";

$image_path=$theme_path."images/";

global $mod_strings;

$smarty->assign("MOD",$mod_strings);
$srcfile=$root_directory.'modules/BiServer/';
$folders=array();  
$files = scandir($srcfile);
$i=0;
foreach($files as $folder) {

    if($folder == '.' || $folder == '..' || $folder == '.svn' || $folder == 'language') continue;

    if(is_dir($root_directory.'modules/BiServer/'.$folder))
            {
            $folders[]=$folder;
            }
    }

$content=array();
//Fill report dropdownlist
$query="SELECT reportname,reportid,queryid from vtiger_report";
$result = $adb->query($query);
$num_rows=$adb->num_rows($result);
if($num_rows!=0){
for($i=0;$i<=$num_rows;$i++)
{
 $reportId = $adb->query_result($result,$i,'reportid');
 $reportValue = getTranslatedString($adb->query_result($result,$i,'reportname'));
 $res.='<option value="'.$reportId.'">'.$reportValue.'</option>';
}
$smarty->assign("REPORTS",$res);
}
//Fill Map dropdownlist
$mapquery="SELECT mapname ,cbmapid from vtiger_cbmap join vtiger_crmentity
          on crmid = cbmapid where deleted =0 and maptype = ?";
$resultmaps = $adb->pquery($mapquery,array("SQL"));
$num_rows =$adb->num_rows($resultmaps);
if($num_rows!=0){
for($i=0;$i<$num_rows;$i++)
{
 $cbmapid = $adb->query_result($resultmaps,$i,'cbmapid');
 $mapValue = getTranslatedString($adb->query_result($resultmaps,$i,'mapname'));
 $resMap.='<option value="'.$cbmapid.'">'.$mapValue.'</option>';
}
$smarty->assign("MAPS",$resMap);
}

//Fill Map dropdownlist
$mapquery="SELECT mapname,cbmapid from vtiger_cbmap join vtiger_crmentity
          on crmid = cbmapid where deleted =0 and maptype = ? and map_logging=?";
$resultmaps = $adb->pquery($mapquery,array("SQL",1));
$num_rows =$adb->num_rows($resultmaps);
if($num_rows!=0){
for($i=0;$i<$num_rows;$i++)
{
 $cbmapid = $adb->query_result($resultmaps,$i,'cbmapid');
 $mapValue = getTranslatedString($adb->query_result($resultmaps,$i,'mapname'));
 $resLoggingMap.='<option value="'.$cbmapid.'">'.$mapValue.'</option>';
}
$smarty->assign("LOGGINGMAPS",$resLoggingMap);
}
$smarty->assign("is_admin",$current_user->is_admin== 'on' ? true : false);
$smarty->assign("is_superadmin",$current_user->user_name== 'superadmin' ? true : false);
$smarty->assign("folders",$folders);
$smarty->display("modules/BiServer/ListTabs.tpl");


?>

