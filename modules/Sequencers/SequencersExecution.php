<?php
/*************************************************************************************************
 * Copyright 2011-2013 TSolucio  --  This file is a part of vtMktDashboard.
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
*  Module       : Sequencers
*  Version      : 1.8
*  Author       : OpenCubed
*************************************************************************************************/
require_once 'modules/BusinessActions/BusinessActions.php';

class SequencersExecution extends Sequencers {

    function executeNewSequencer() {
        global $argv;
        $allactions = $this->column_fields['evo_actions'];
        $actions_list = explode(",", $allactions);
        for ($i = 0; $i < count($actions_list); $i++) {
            global $finalOutput;
            $actionid = $actions_list[$i];
            $actionObject = new ActionsExecution();
            echo $actionid;
            $actionObject->retrieve_entity_info($actionid, "BusinessActions");
            if ($i == 0) {
                $actionObject->cli_input = $argv;
            } else {
                $actionObject->cli_input = explode(" ", $finalOutput);
            }
            var_dump($actionObject->cli_input);
            $finalOutput = $actionObject->run();
            //$resultArray=explode(" ", $finalOutput);
            //$finalOutput=array_merge($actionObject->cli_input,$resultArray);
//            if ($i == count($actions_list) - 1)
            echo $finalOutput;
//            echo "Execution finished successfully!";
        }
    }

}

?>
