<?php
/*************************************************************************************************
* Copyright 2012-2013 OpenCubed  --  This file is a part of vtMktDashboard.
* You can copy, adapt and distribute the work under the "Attribution-NonCommercial-ShareAlike"
* Vizsage Public License (the "License"). You may not use this file except in compliance with the
* License. Roughly speaking, non-commercial users may share and modify this code, but must give credit
* and share improvements. However, for proper details please read the full License, available at
* http://vizsage.com/license/Vizsage-License-BY-NC-SA.html and the handy reference for understanding
* the full license at http://vizsage.com/license/Vizsage-Deed-BY-NC-SA.html. Unless required by
* applicable law or agreed to in writing, any software distributed under the License is distributed
* on an  "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and limitations under the
* License terms of Creative Commons Attribution-NonCommercial-ShareAlike 3.0 (the License).
*************************************************************************************************
*  Module       : BusinessActions
*  Version      : 1.8
*  Author       : OpenCubed
*************************************************************************************************/
include_once('data/CRMEntity.php');
include_once('modules/BusinessActions/BusinessActions.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
include_once('modules/BusinessActions/ActionsNodeExecution.php');
include_once('modules/Sequencers/Sequencers.php');
include_once('modules/Sequencers/SequencersNodeExecution.php');
global $adb, $log, $current_user;
ini_set('display_errors', 'off');

function runNodeAction($actionid,$arguments){
$actionObject = new ActionsNodeExecution();
$actionObject->retrieve_entity_info($actionid, 'BusinessActions');
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
