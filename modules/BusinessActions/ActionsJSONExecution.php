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
include('include/fpdm/fpdm.php');
class ActionsJSONExecution extends BusinessActions {
    public  $cli_input;
    private $inputParameters;
    private $outputParameters;
    
    function getActionInput() {
        $this->log->debug("Initializing the inputs variables");
        $mapid = $this->column_fields['iomap'];
        //$mapid = 131931;
        $mapfocus = CRMEntity::getInstance("cbMap");
        $mapfocus->retrieve_entity_info($mapid, "cbMap");
        $infields = $mapfocus->readInputFields();
        for ($j = 0; $j < count($infields); $j++) {
            $data[$infields[$j]] = "";
        }
        //default data needed to be retrieven from Action itself
        $data['mapid'] = $this->column_fields['linktomapmodule'];
        $actionParameters = $this->column_fields['stockparameters'];
        if(!empty($actionParameters)){
        $data = $this->processActionParameters($actionParameters, $data);
        }
//        $parameter1 = $this->column_fields['parameter1'];
//        $causale = $this->column_fields['causale'];
//        $businessrules_action = $this->column_fields['businessrules_action'];
        $this->inputParameters = $data;
        $this->log->debug("Ending the inputs variables...The variables will be");
        $this->log->debug($this->inputParameters);
        return $this->inputParameters;
    }
    function loadAllSelectedRecords($tablename){
        $result=array();
        $selectQuery=$this->db->pquery("SELECT * FROM $tablename WHERE selected=1",array());
        while($selectQuery && $row=$this->db->fetch_array($selectQuery)){
            $result[]=$row['crmid'];
        }
        $allValues=  implode(",", $result);
        return $allValues;
    }
    function processActionParameters($actionParameters,$array){
        $actionValues=explode(";",$actionParameters);
        foreach($actionValues as $paramElement){
            $paramArg=explode("=",$paramElement);
            $paramName=$paramArg[0];
            $paramVal=$paramArg[1];
            $array[$paramName]=$paramVal;
        }
        return $array;
    }

    function getActionOutput($data = array()) {
        $mapid=$this->column_fields['iomap'];
        //$mapid = 131931;
        $mapfocus = CRMEntity::getInstance("cbMap");
        $mapfocus->retrieve_entity_info($mapid, "cbMap");
        $outfields = $mapfocus->readOutputFields();
        for ($el = 0; $el < count($outfields); $el++) {
            $response[$outfields[$el]] = $data[$outfields[$el]];
        }
        $this->outputParameters = $response;

        return $this->returnJSONOutput();
    }
     function returnJSONOutput() {
        $output=array();
        foreach ($this->inputParameters as $k => $val) {
            $output[$k] =$val;
        }
        foreach ($this->outputParameters as $k => $val) {
            $output[$k] = $val;
        }
        $strres=json_encode($output,true);
        return $strres;
    }
      function returnActionJSONInput() {
        return json_encode($this->inputParameters);
    }

    function setActionParametersFromJSON() {
        $clirequest = array();
        $this->cli_input=  json_decode($this->cli_input);
        $this->log->debug("parameters taken as input");
        $this->log->debug($this->cli_input);
        foreach($this->cli_input as $key=>$value){
            if($key=='dashboardSelectedTable'){
                //Retrieve all selected records in one of Dashboards generated from DashboardBuilder
                $tablename=$value;
                $value=$this->loadAllSelectedRecords($tablename);
                $key="recordid";
            }
            $clirequest[$key] = $value;
            }
            $this->log->debug('parametrat jane');
            $this->log->debug($this->inputParameters);
           foreach ($this->inputParameters as $fieldname => $val) {
            $value = trim($clirequest[$fieldname]);
            $this->inputParameters[$fieldname] = $value;
            if(empty($value)){
            $this->inputParameters[$fieldname] =   $val;  
            }
        }
         $this->log->debug('Final initialised arguments');
         $this->log->debug($this->inputParameters);
        
    }
    function executeJSONAction($params) {
        $params=json_decode($params,true);
        $this->log->debug("Entering before script execution");
        $this->log->debug($params);
        $fullScriptPath = $this->column_fields['script_name'];
        $this->log->debug("action path name");
        $this->log->debug($fullScriptPath);
        $functionName = basename("$fullScriptPath");
        include_once "$fullScriptPath.php";    
        $data = $functionName($params);
        return $data;
    }
    function run(){
        $declaredInput = $this->getActionInput();
        $this->setActionParametersFromJSON();
        $returnActionInput=$this->returnActionJSONInput();
        $res = $this->executeJSONAction($returnActionInput);
        return $this->getActionOutput($res);
    }

}

?>
