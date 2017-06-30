<?php
/*************************************************************************************************
 * Copyright 2012-2014 JPL TSolucio, S.L.  --  This file is a part of coreBOSCP.
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
*************************************************************************************************/

function vtws_GetFormInstance($instance){
	require_once("modules/$instance/$instance.php");
        global $adb,$log,$current_user;
        
        $focus=new $instance("$instance");        
        $entities=$focus->getEntities();
        
        return $entities;
}

function vtws_SaveFormData($instance,$answers){  
        require_once("modules/$instance/$instance.php");
        global $adb,$log,$current_user;
        
        $focus=new $instance("$instance");
        $focus->getEntities();
        $response=$focus->insertToIndex($answers);
        
	return $response;
}

function vtws_RetrieveFormDataListView($instance,$fromwhere,$size,$where){  
        require_once("modules/$instance/$instance.php");
        global $adb,$log,$current_user;
        
        $focus=new $instance("$instance");
        $focus->getEntities();
        $response=$focus->RetrieveFormDataListView($fromwhere,$size,$where);
        
	return $response;
}

function vtws_RetrieveFormDataDetailView($instance,$id){  
        require_once("modules/$instance/$instance.php");
        global $adb,$log,$current_user;
        
        $focus=new $instance("$instance");
        $focus->getEntities();
        $response=$focus->RetrieveFormDataDetailView($id);
        
	return $response;
}

function vtws_RetrieveListSurveys($module,$param){  
        global $adb,$log,$current_user;
        $condition='';  $response=array();      
        if($param=='false'){
            $condition=' where  parameter = "" OR parameter is null ';
        }
        else{
            $condition=' where  parameter <> "" AND parameter is not null ';
        }
        $condition.=" AND type in ('TypeForm')";
        $result=$adb->query("Select * "
                . " from dashboardbuilder_extensions "
                . " join dashboardbuilder_entities on dashboardbuilder_extensions.name=dashboardbuilder_entities.name"
                . $condition);
        for($i=0;$i<$adb->num_rows($result);$i++){
            $form_ext_name=$adb->query_result($result,$i,'name');
            $index_type=$adb->query_result($result,$i,'index_type');
            $resp[]=array('surveyname'=>$form_ext_name,'surveydesc'=>$index_type);
        }
        $response['records']=$resp;
        $response['total']=sizeof($resp);
        
	return $response;
}

function vtws_saveEntity($instance,$answers,$masterModule){  
        require_once("modules/$instance/$instance.php");
        global $adb,$log,$current_user;
        
        $focus=new $instance("$instance");
        $focus->masterModule=$masterModule;
        $conf=$focus->getEntities();
        $response=$focus->handleInsert($answers['data'],$answers['config'],$answers['settings']);
        
	return $response;
}
function vtws_saveEntities($instance,$answers,$masterModule){  
        require_once("modules/$instance/$instance.php");
        global $adb,$log,$current_user;
        
        $focus=new $instance("$instance");
        $focus->masterModule=$masterModule;
        $conf=$focus->getEntities();
        $response=$focus->handleMultiInsert($answers['data'],$conf['ConfigEntities'],$conf['settings'],$conf['steps']);
        
	return $response;
}
function vtws_getFieldDependency($instance,$masterModule){
        require_once("modules/$instance/$instance.php");
        global $adb,$log,$current_user;
        
        $focus=new $instance("$instance");
        $focus->masterModule=$masterModule;
        $response=$focus->vtws_getFieldDep();
        
	return $response;
}
function vtws_retrieveActions($instance,$answers,$masterModule,$step,$action_type){ 
        require_once("modules/$instance/$instance.php");
        global $adb,$log,$current_user;
        
        $focus=new $instance("$instance");
        $focus->masterModule=$masterModule;
        $step=$_REQUEST['actualStep'];
        $action_type=$_REQUEST['action_type'];
        $response=$focus->getActionsOfStep($answers['steps'],$step,$action_type);
        
	return $response;
}

function vtws_getEntities($instance,$masterModule){ 
        require_once("modules/$instance/$instance.php");
        global $adb,$log,$current_user;
        
        $focus=new $instance("$instance");
        $focus->masterModule=$masterModule;
        $response=$focus->getEntities();
        
	return $response;
}

function vtws_retrieveInfo($instance,$record){ 
        require_once("modules/$instance/$instance.php");
        global $adb,$log,$current_user;
        
        $focus=new $instance("$instance");
        $focus->record=$record;
        $response=$focus->retrieveInfo($record);
        
	return $response;
}


?>
