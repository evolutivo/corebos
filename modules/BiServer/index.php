<?php

global $root_directory,$theme,$current_user,$mod_strings;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

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

$smarty->assign("is_admin",$current_user->is_admin== 'on' ? true : false);
$smarty->assign("is_superadmin",$current_user->user_name== 'superadmin' ? true : false);
$smarty->assign("folders",$folders);
$smarty->display("modules/BiServer/ListTabs.tpl");


?>

