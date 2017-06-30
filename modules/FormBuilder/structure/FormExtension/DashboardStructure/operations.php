<?php

/* * ***********************************************************************************************
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
 * ************************************************************************************************
 *  Module       : FormExtension
 *  Version      : 1.9
 *  Author       : OpenCubed
 * *********************************************************************************************** */
require_once("modules/$currentModule/$currentModule.php");
global $adb,$log,$current_user;
ini_set('max_execution_time',100);
//ini_set('display_errors','On');
$focus=new $currentModule("$currentModule");

$type=$focus->type;
$kaction=$_REQUEST['kaction'];
$record=$_REQUEST['masterRecord'];
$masterModule=$_REQUEST['masterModule'];
$view=$_REQUEST['view'];

if($kaction=='getEntities'){
    $focus->record=$record;
    $focus->masterModule=$masterModule;
    $entities=$focus->getEntities($view);
    echo json_encode($entities);
}
if($kaction=='getEntitiesSession'){
    $getEntities=coreBOS_Session::get('getEntities');
    $temp=json_decode($getEntities,true);
    $temp['ConfigEntities']=coreBOS_Session::get('ConfigEntities');
    coreBOS_Session::set('getEntities',json_encode($temp));
    //$_SESSION['getEntities']=json_encode($temp);
    echo json_encode($temp);
}
elseif($kaction=='saveEntity'){
    $models=file_get_contents('php://input');
    $answers=json_decode($models,true);
    $focus->record=$record;
    $focus->masterModule=$masterModule;
    $response=$focus->handleInsert($answers['data'],$answers['config'],$answers['settings']);
    echo $response;
}
elseif($kaction=='updateEntity'){
    $models=file_get_contents('php://input');
    $answers=json_decode($models,true);
    $focus->record=$record;
    $focus->masterModule=$masterModule;
    $response=$focus->updateEntity($answers['data'],$answers['config'],$answers['settings']);
    echo $response;
}
elseif($kaction=='saveEntities'){
    $models=file_get_contents('php://input');
    $answers=json_decode($models,true);
    $focus->record=$record;
    $focus->masterModule=$masterModule;
    $response=$focus->handleMultiInsert($answers['data'],$answers['config'],$answers['settings'],$answers['steps'],$answers['documents']);
    echo $response;
}
elseif($kaction=='retrieveInfo'){
    $focus->record=$record;
    $focus->masterModule=$masterModule;
    $response=$focus->retrieveInfo($record);
    echo json_encode($response);
}
elseif($kaction=='retrieveInfoSpecific'){
    $record=$_REQUEST['recordSpecific'];
    $response=$focus->retrieveInfo($record);
    echo json_encode($response);
}
elseif($kaction=='retrieveDetailRecords'){
    $focus->record=$record;
    $focus->masterModule=$masterModule;
    $response=$focus->retrieveDetailRecords($record);
    echo json_encode($response);
}
elseif($kaction=='getFieldDependency'){
    $focus->record=$record;
    $focus->masterModule=$masterModule;
    $response=$focus->vtws_getFieldDep();
    echo json_encode($response);
    
}
elseif($kaction=='getFieldDependencyFather'){
    $focus->record=$record;
    $focus->masterModule=$masterModule;
    $moduleFather=$_REQUEST['moduleFather'];
    $response=$focus->vtws_getFieldDepFather($moduleFather);
    echo json_encode($response);
    
}
elseif($kaction=='getRelatedModules'){
    $focus->record=$record;
    $focus->masterModule=$masterModule;
    $models=file_get_contents('php://input');
    $data=json_decode($models,true);
    $widgetType=$data['data'];
    $response=$focus->getRelatedModules($masterModule,$record,$widgetType);
    echo json_encode($response);
    
} 
elseif($kaction=='doGetRelatedRecords'){
    $focus->record=$record;
    $focus->masterModule=$masterModule;
    $ngblockid=$_REQUEST['ngblockid'];
    $limit=$_REQUEST['limit'];
    $models=file_get_contents('php://input');
    $data=json_decode($models,true);
    $response=$focus->doGetRelatedRecords($ngblockid,$limit,$data['data']);
    echo json_encode($response);
    
} 
elseif($kaction=='retrieveActions'){
    $models=file_get_contents('php://input');
    $answers=json_decode($models,true);
    $focus->record=$record;
    $focus->masterModule=$masterModule;
    $step=$_REQUEST['actualStep'];
    $action_type=$_REQUEST['action_type'];
    $response=$focus->getActionsOfStep($answers['steps'],$step,$action_type);
    echo json_encode($response);
    
}
elseif($kaction=='getFatherDetailViewBlocks'){
    $models=file_get_contents('php://input');
    $answers=json_decode($models,true);
    $focus->record=$record;
    $focus->masterModule=$masterModule;
    $ngblockid=$_REQUEST['ngblockid'];
    $response=$focus->getFatherDetailViewBlocks($answers['config'],$ngblockid);
    echo json_encode($response);
    
}
elseif($kaction=='updateFather'){
    $models=file_get_contents('php://input');
    $answers=json_decode($models,true);
    $focus->record=$record;
    $focus->masterModule=$masterModule;
    $response=$focus->updateFather($answers['data']);
    echo json_encode($response);
    
}
elseif($kaction=='saveDocument'){
    $name=$_REQUEST['name'];
    $focus->record=$record;
    $focus->masterModule=$masterModule;
    $response=$focus->saveDocument($name);
    echo $response;
}

elseif($kaction=='retrieveAutoCompleteData'){
    $fld=$_REQUEST['field'];
    $val=$_REQUEST['val'];
    $cachedmap=$_REQUEST['cachedmap'];
    $focus->record=$record;
    $focus->masterModule=$masterModule;
    $response=$focus->retrieveAutoCompleteData($val,$fld,$cachedmap);
    echo json_encode($response);
}
elseif($kaction=='retrieveRole'){
    $record=$_REQUEST['recordSpecific'];
    $response=$focus->retrieveRole($record);
    echo json_encode($response);
}
elseif($kaction=='retrieveUserName'){
    $type=$_REQUEST['tipoutente'];
    $response=$focus->retrieveUserName($type);
    echo json_encode($response);
}