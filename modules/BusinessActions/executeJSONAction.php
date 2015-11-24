<?php
include_once('data/CRMEntity.php');
include_once('modules/BusinessActions/BusinessActions.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
include_once('modules/BusinessActions/ActionsJSONExecution.php');
include_once('modules/Sequencers/Sequencers.php');
include_once('modules/Sequencers/SequencersJSONExecution.php');
global $adb, $log, $current_user,$root_directory;
ini_set('display_errors', 'off');

$module = 'BusinessActions';
$actionid = $_REQUEST['actionid'];
$parameters = explode(" ",$_REQUEST['parameters']);
$startPoint=0;
if(isset($argv) && !empty($argv)){
    $actionid=$argv[1];   
    $parameters=$argv;
    $startPoint=1;
}
if (isset($parameters) && !empty($parameters)) {
    for ($i = $startPoint; $i < count($parameters); $i++) {
        list($key, $value) = explode("=", $parameters[$i]);
        $request[$key] = $value;
    }
}
$request=json_encode($request,true);
include_once "modules/BusinessActions/runJSONAction.php";
echo runJSONAction($actionid,$request);

//$res=shell_exec("cd $root_directory; php modules/Actions/runCLIAction.php $actionid $parameters");
//echo $res;
?>
