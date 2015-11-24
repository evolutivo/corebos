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
class ActionsExecution extends BusinessActions {
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
        $selectQuery=$this->db->pquery("SELECT * FROM $tablename WHERE selected=1");
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

        return $this->returnActionOutput();
    }

    function returnActionOutput() {
        $output=array();
        foreach ($this->inputParameters as $k => $val) {
            $output[] = $k . "=" . $val;
        }
        foreach ($this->outputParameters as $k => $val) {
            $output[] = $k . "=" . $val;
        }
        $strres=implode(" ", $output);
        return $strres;
    }
    
     function returnActionInput($delimiter=" ") {
        $input=array();
        $this->log->debug("input parameters before execution");
        $this->log->debug($this->inputParameters);
        foreach ($this->inputParameters as $k => $val) {
            $input[] = $k . "=" . $val;
        }
        return implode($delimiter, $input);
    }

    function setActionParametersFromCLI() {
        $clirequest = array();
        if (isset($argv) && !empty($argv)) {
            $this->cli_input=$argv;
            $startPoint=1;
        }
        else{
           $startPoint=0;
        }
        $this->log->debug("parameters taken as input");
        $this->log->debug($this->cli_input);
            for ($i = $startPoint; $i < count($this->cli_input); $i++) {
                list($key, $value) = explode("=", $this->cli_input[$i]);
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
            if(empty($value))
            $this->inputParameters[$fieldname] =   $val;  
        }
         $this->log->debug('Final initialised arguments');
            $this->log->debug($this->inputParameters);
        
    }
      function setActionParametersFromWS() {
        $clirequest = array();
        if (isset($argv) && !empty($argv)) {
            $this->cli_input=$argv;
        }
        $this->log->debug($this->cli_input);
            for ($i = 1; $i < count($this->cli_input); $i++) {
                list($key, $value) = explode("=", $this->cli_input[$i]);
                $clirequest[$key] = $value;
            }
           foreach ($this->inputParameters as $fieldname => $val) {
            $value = trim($clirequest[$fieldname]);
            $this->inputParameters[$fieldname] = $value;
            if(empty($value))
            $this->inputParameters[$fieldname] =   $val;  
        }
        
    }
    
    function setActionParametersFromRequest() {
        if (isset($_REQUEST['record'])) {
            $this->id = $_REQUEST['record'];
        }
        if (isset($_REQUEST['recordid'])) {
            $this->id = $_REQUEST['recordid'];
        }
        foreach ($this->inputParameters as $fieldname => $val) {
            $value = trim($_REQUEST[$fieldname]);
            $this->inputParameters[$fieldname] = $value;
        }
    }

    function executeNewAction($params) {
        $this->log->debug("Entering before script execution");
        $this->log->debug($params);
        $fullScriptPath = $this->column_fields['script_name'];
        $this->log->debug("action path name");
        $this->log->debug($fullScriptPath);
        $scriptResponse=shell_exec("php $fullScriptPath.php $params"); 
        $data=json_decode($scriptResponse,true);
        return $data;
    }
    
    function run($source="cli"){
        $declaredInput = $this->getActionInput();
        if($source == 'cli'){
             $this->setActionParametersFromCLI();
             $returnActionInput=$this->returnActionInput();
        }
        elseif($source == 'ws'){
             $this->setActionParametersFromWS();
             $returnActionInput=$this->returnActionInput("&");
        }
        else{
            $this->setActionParametersFromRequest();
            $returnActionInput=$this->returnActionInput();
        }
        $res = $this->executeNewAction($returnActionInput);
        return $this->getActionOutput($res);
    }

}

?>

