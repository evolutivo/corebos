<?php
include_once('data/CRMEntity.php');
include_once('modules/Actions/Actions.php');
include_once('modules/Sequencers/Sequencers.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
include_once('modules/Actions/ActionsNodeExecution.php');
include_once('modules/Sequencers/SequencersNodeExecution.php');
global $adb, $log, $current_user;
ini_set('display_errors', 'off');

function runNodeAction($actionid,$arguments){
$actionObject = new ActionsNodeExecution();
$actionObject->retrieve_entity_info($actionid, 'Actions');
if ($actionObject->column_fields['actions_type'] == 'Sequencer') {
    $sequencerid = $actionObject->column_fields['sequencers'];
    $sequencerObject = new SequencersNodeExecution();
    $sequencerObject->retrieve_entity_info($sequencerid, "Sequencers");
    $finalOutput = $sequencerObject->executeNodeSequencer($arguments);
} else {
    $actionObject->cli_input = $arguments;
    $finalOutput = $actionObject->run();
}
return $finalOutput;
}
?>
