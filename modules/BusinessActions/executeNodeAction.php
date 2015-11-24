<?php
include_once('data/CRMEntity.php');
include_once('modules/BusinessActions/BusinessActions.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
include_once('modules/BusinessActions/ActionsNodeExecution.php');
include_once('modules/Sequencers/Sequencers.php');
include_once('modules/Sequencers/SequencersNodeExecution.php');
global $adb, $log, $current_user,$root_directory;
ini_set('display_errors', 'off');

$module = 'BusinessActions';
$actionid = $_REQUEST['actionid'];
$parameters = explode(" ",$_REQUEST['parameters']);
$log->debug("parameters from request");
$log->debug($_REQUEST['parameters']);
if(isset($argv) && !empty($argv)){
    $actionid=$argv[1];   
    $parameters=$argv;
}
if (isset($parameters) && !empty($parameters)) {
    for ($i = 0; $i < count($parameters); $i++) {
        list($key, $value) = explode("=", $parameters[$i]);
        $request[$key] = $value;
    }
}
$request=json_encode($request,true);

include_once "modules/BusinessActions/runNodeAction.php";
echo runNodeAction($actionid,$request);

//$res=shell_exec("cd $root_directory; php modules/Actions/runCLIAction.php $actionid $parameters");
//echo $res;
?>
