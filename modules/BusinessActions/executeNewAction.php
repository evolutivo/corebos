<?php
include_once('data/CRMEntity.php');
include_once('modules/Actions/Actions.php');
include_once('modules/Sequencers/Sequencers.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
include_once('modules/Actions/ActionsExecution.php');
include_once('modules/Sequencers/SequencersExecution.php');
global $adb, $log, $current_user,$root_directory;
ini_set('display_errors', 'off');

$module = 'Actions';
$actionid = $_REQUEST['actionid'];
$parameters = $_REQUEST['parameters'];

$res=shell_exec("cd $root_directory; php modules/Actions/runCLIAction.php $actionid $parameters");
echo $res;
?>
