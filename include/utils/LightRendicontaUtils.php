<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function rendicontaConfig($module){
    
    global $adb;
    
    $q_business_rule="Select businessrule,linktomap "
            . " from vtiger_businessrules"
            . " join vtiger_cbmap on vtiger_businessrules.linktomap=vtiger_cbmap.cbmapid"
            . " join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_cbmap.cbmapid"
            . " join vtiger_crmentity c2 on c2.crmid=vtiger_businessrules.businessrulesid"
            . " where module_rules=?"
            . " and maptype =? and vtiger_crmentity.deleted=0 and c2.deleted=0 ";
    $res_business_rule=$adb->pquery($q_business_rule,array($module,'RendicontaConfig'));
    $linktomap=$adb->query_result($res_business_rule,$m,'linktomap');  
    $mapfocus=  CRMEntity::getInstance("cbMap");
    $mapfocus->retrieve_entity_info($linktomap,"cbMap");
    $mapRendicontaConfig=$mapfocus->getRendicontaConfig();
    
    return $mapRendicontaConfig;
}
function updateStatusFld($data){
    
    global $current_user;
    $currentModule=$data['currentModule'];
    $id=$data['recordid'];
    $statusfield=$data['statusfield'];
    $next_sub=$data['next_substatus'];
    require_once "modules/$currentModule/$currentModule.php";
    $focus_currMod= CRMEntity::getInstance($currentModule);
    $focus_currMod->id=$id;
    $focus_currMod->retrieve_entity_info($id,$currentModule);
    $focus_currMod->mode = 'edit';   
    $focus_currMod->column_fields["$statusfield"]=$next_sub;
    $focus_currMod->column_fields["rendicontato_da"]=$current_user->id;
    $focus_currMod->column_fields['assigned_user_id']=$focus_currMod->column_fields['assigned_user_id'];
    $focus_currMod->save($currentModule);
}
function createProcessLog($data){
    
    require_once 'modules/ProcessLog/ProcessLog.php';
    global $adb;
    
    $focus = new ProcessLog;
    $focus->mode = '';
    $focus->column_fields['processlogname']=$data['entityname_val'].' from '.$data['actual_substatus'].' to '.$data['next_substatus'];
    $focus->column_fields['previoussubstatus']=$data['actual_substatus'];
    $focus->column_fields['nextsubstatus']=$data['next_substatus'];
    $focus->column_fields['time']=date('Y-m-d');
    $focus->column_fields['caserelprocess']=$data['recordid'];
    $focus->column_fields['assigned_user_id']=$data['current_user'];
    $focus->save('ProcessLog');
    calculateSLA($data);
}
function plannedAlertSetting($data){
    
    require_once('modules/PALaunch/PALaunch.php');
    $focus = new PALaunch();
    $parameters = json_encode(array($data['recordid']));
    $focus->create_palaunch('13924338', 5, $parameters, 2);
    
}
function pickFlow($pfid,$data){
    
    global $adb,$log;
    $pfquery=$adb->pquery("SELECT pf.* ,pt.* 
        FROM vtiger_processflow AS pf
        JOIN vtiger_processtemplate AS pt ON pf.linktoprocesstemplate = pt.processtemplateid
        WHERE processflowid = ? ",array($pfid));
    $sequencer=$adb->query_result($pfquery,0,'sequencer');
    $mailer_action=$adb->query_result($pfquery,0,'mailer_action');
    $data['tasksla']=$adb->query_result($pfquery,0,'tasksla');
    $data['alertsettingmodel']=$adb->query_result($pfquery,0,'alertsettingmodel');

    if($mailer_action!=''){
       $response=doAction($mailer_action,$data);
    }
    if($sequencer!=''){
       $response=doAction($sequencer,$data);
    }
    return $response;
}
function doAction($actionid,$param){
    
    global $adb,$log;
    include_once('modules/BusinessActions/runJSONAction.php');
    $response= runJSONAction($actionid,json_encode($param,true));
    return $response;
    
}

function putCaseAssigned($data){
    
    global $adb,$log;
    $uid=$_SESSION['authenticated_user_id'];
    $next_sub=$data['next_substatus'];
    $id=$data['recordid'];
    if(!empty($uid) && $next_sub=='closed')
    {        
        $adb->query("update vtiger_cases set cbcaseassign=creatorsecondlevel where casesid=$id");            
    }
    else if(!empty($uid) && $next_sub!='draft')
    {        
        $adb->query("update vtiger_cases set cbcaseassign='$uid' where casesid=$id");
    }
}

function calculateSLA($data){
    
    $pLogId=pickRightProcessLog($data);
    
}

function pickRightProcessLog($data){
    
    global $adb;
    $query="Select * "
            . " ";
    $currentModule=$data['currentModule'];
    $id=$data['recordid'];
    $statusfield=$data['statusfield'];
    $next_sub=$data['next_substatus'];
    require_once "modules/$currentModule/$currentModule.php";
    $focus_currMod= CRMEntity::getInstance($currentModule);
    $focus_currMod->id=$id;
    $focus_currMod->retrieve_entity_info($id,$currentModule);
    $focus_currMod->mode = 'edit';   
    $focus_currMod->column_fields["$statusfield"]=$next_sub;
    $focus_currMod->column_fields['assigned_user_id']=$focus_currMod->column_fields['assigned_user_id'];
    $focus_currMod->save($currentModule);
}

function s_datediff( $str_interval, $dt_menor, $dt_maior, $relative=false){

       if( is_string( $dt_menor)) $dt_menor = date_create( $dt_menor);
       if( is_string( $dt_maior)) $dt_maior = date_create( $dt_maior);

       $diff = date_diff( $dt_menor, $dt_maior, ! $relative);
       
       switch( $str_interval){
           case "y": 
               $total = $diff->y + $diff->m / 12 + $diff->d / 365.25; break;
           case "m":
               $total= $diff->y * 12 + $diff->m + $diff->d/30 + $diff->h / 24;
               break;
           case "d":
               $total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h/24 + $diff->i / 60;
               break;
           case "h": 
               $total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60;
               break;
           case "i": 
               $total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s/60;
               break;
           case "s": 
               $total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i)*60 + $diff->s;
               break;
          }
       if( $diff->invert)
               return -1 * $total;
       else    return $total;
   }