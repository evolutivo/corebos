<?php
$appId=$_POST['appId'];
$appName=$_POST['appName'];
$mypath='modules/evvtApps';
require_once('Smarty_setup.php');
require_once('include/utils/utils.php');
require_once($mypath.'/vtapps/baseapp/vtapp.php');
require_once($mypath.'/vtapps/app'.$appId.'/vtapp.php');
global $current_language,$current_user;
$app=new $appName($appId);
echo $app->getContent($current_language);
?>