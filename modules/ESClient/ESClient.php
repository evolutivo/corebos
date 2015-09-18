<?php
require_once('Smarty_setup.php');
global $adb,$log,$app_strings,$current_user,$theme,$currentModule,$mod_strings,$image_path,$category;
$smarty = new vtigerCRM_Smarty();
$partial = $_Request['partial'];
$dir    = 'storage';
$files = scandir($dir);
//var_dump($files);
$sel='<select name="files" id="files">';
for($i=0;$i<count($files);$i++){
   if(strstr($files[$i],'.csv'))
   $sel.='<option value="'.$files[$i].'">'.$files[$i].'</option>';
}
$sel.='</select>';

echo $partial;
$smarty->assign("sel",$sel);
    $smarty->display("modules/ESClient/index.tpl");
?>
