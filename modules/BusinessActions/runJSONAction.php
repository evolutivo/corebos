<?php
include_once('data/CRMEntity.php');
include_once('modules/BusinessActions/BusinessActions.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
include_once('modules/BusinessActions/ActionsJSONExecution.php');
include_once('modules/Sequencers/Sequencers.php');
include_once('modules/Sequencers/SequencersJSONExecution.php');
global $adb, $log, $current_user;
ini_set('display_errors', 'off');

function runJSONAction($actionid,$arguments){
$actionObject = new ActionsJSONExecution();
$actionObject->retrieve_entity_info($actionid, 'BusinessActions');
if ($actionObject->column_fields['actions_type'] == 'Sequencer') {
    $sequencerid = $actionObject->column_fields['sequencers'];
    $sequencerObject = new SequencersJSONExecution();
    $sequencerObject->retrieve_entity_info($sequencerid, "Sequencers");
    $finalOutput = $sequencerObject->executeJSONSequencer($arguments);
} else {
    $actionObject->cli_input = $arguments;
    $finalOutput = $actionObject->run();
}
return $finalOutput;
}
?>
