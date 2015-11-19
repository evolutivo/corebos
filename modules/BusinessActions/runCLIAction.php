<?php
include_once('data/CRMEntity.php');
include_once('modules/Actions/Actions.php');
include_once('modules/Sequencers/Sequencers.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
include_once('modules/Actions/ActionsExecution.php');
include_once('modules/Sequencers/SequencersExecution.php');
global $adb, $log, $current_user;
ini_set('display_errors', 'off');

$module = 'Actions';
$actionid = $argv[1];
$actionObject = new ActionsExecution();
$actionObject->retrieve_entity_info($actionid, $module);
if ($actionObject->column_fields['actions_type'] == 'Sequencer') {
    $sequencerid = $actionObject->column_fields['sequencers'];
    $sequencerObject = new SequencersExecution();
    $sequencerObject->retrieve_entity_info($sequencerid, "Sequencers");
    $finalOutput = $sequencerObject->executeNewSequencer();
} else {
    $actionObject->cli_input = $argv;
    $finalOutput = $actionObject->run();
}
echo $finalOutput;
?>
