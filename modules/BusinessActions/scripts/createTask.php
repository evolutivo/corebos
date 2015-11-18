<?php
global $adb,$log,$current_user,$app_strings;
require_once("config.inc.php");
require_once("include/database/PearDatabase.php");
require_once("include/utils/utils.php");
require_once('modules/ProjectTask/ProjectTask.php');
require_once('modules/Project/Project.php');
include_once('modules/Task/Task.php');
include_once('modules/MessageSettings/MessageSettings.php');
include_once('modules/Actions/Actions.php');
include_once('modules/Accounts/Accounts.php');

$log->debug('project_test_arg '.$argv[1].' project_test_rendic2 '.$projId.' '.$process_flow_id);
if(isset($argv) && !empty($argv)) //as action
 { 
    $projId_processflow = explode(',',$argv[1]);
    $projId = $projId_processflow[0];
    $process_flow_id=$projId_processflow[1];
 }
 else{  //from inside Rendiconta button
    $projId = $projId;
    $process_flow_id=$process_flow_id;
 }
    
    $qry_sequencer=$adb->pquery("Select evo_actions,manual
                from vtiger_processflow 
                join vtiger_sequencers on  vtiger_processflow.sequencer= vtiger_sequencers.sequencersid 
                where vtiger_processflow.processflowid=?",array($process_flow_id));
    $evo_actions=$adb->query_result($qry_sequencer,0,'evo_actions');$log->debug('evo_act '.$evo_actions);
    $arr_act=explode(',',$evo_actions);
    if($arr_act[0]=='') $arr_act[0]=$arr_act[1];$log->debug('evo_act1 '.$arr_act[0]);
    $qry_action=$adb->pquery("Select messagesettingsid,causale,linktomapmodule
                from vtiger_actions                
                join vtiger_messagesettings on  vtiger_actions.causale= vtiger_messagesettings.messagecause
                where vtiger_actions.actionsid=?",array($arr_act[0]));
    $qry=$adb->pquery("Select processtemplatename,
                accountname,vtiger_project.email as email_proj,
                nomecontatto,contactsurname,ship_street,ship_code,ship_city,ship_state,
                bill_street,bill_code,bill_city,fiscalcode,vtiger_account.email1 as email_acc,
                vtiger_account.phone
                from  vtiger_project 
                left join vtiger_processtemplate on  vtiger_processtemplate.processtemplateid = vtiger_project.commessa
                left join vtiger_account on  vtiger_account.accountid = vtiger_project.linktoaccountscontacts
                LEFT JOIN vtiger_accountbillads ON vtiger_account.accountid = vtiger_accountbillads.accountaddressid
                LEFT JOIN vtiger_accountshipads ON vtiger_account.accountid = vtiger_accountshipads.accountaddressid
                where vtiger_project.projectid=?",array($projId));
    $actionId = $arr_act[0];
    $mapId = $adb->query_result($qry_action,0,'linktomapmodule');
    $messagesettingsid = $adb->query_result($qry_action,0,'messagesettingsid');
    $manual = $adb->query_result($qry_sequencer,0,'manual');
    $causale = $adb->query_result($qry_action,0,'causale');
    // related entities to Project (Accounts , ProcessTemplate)
    $processtemplatename = $adb->query_result($qry,0,'processtemplatename');
    $NOMECONTATTO = $adb->query_result($qry,0,'nomecontatto');
    $COGNOMECONTATTO = $adb->query_result($qry,0,'contactsurname');
    $INDIRIZZOSPEDIZIONE = $adb->query_result($qry,0,'ship_street');
    $CODICESPEDIZIONE = $adb->query_result($qry,0,'ship_code');
    $CITTASPEDIZIONE = $adb->query_result($qry,0,'ship_city');
    $PROVINCIASPEDIZIONE = $adb->query_result($qry,0,'ship_state');
    $INDIRIZZOFATTURAZIONE = $adb->query_result($qry,0,'bill_street');
    $CODICEFATTURAZIONE = $adb->query_result($qry,0,'bill_code');
    $CITTAFATTURAZIONE = $adb->query_result($qry,0,'bill_city');
    $CODICEFISCALE = $adb->query_result($qry,0,'fiscalcode');
    $EMAIL_ACC = $adb->query_result($qry,0,'email_acc');
    $PHONE = $adb->query_result($qry,0,'phone');
    $sub_array=array('NOMECONTATTO','COGNOMECONTATTO','INDIRIZZOSPEDIZIONE','CODICESPEDIZIONE',
        'CITTASPEDIZIONE','PROVINCIASPEDIZIONE','INDIRIZZOFATTURAZIONE','CODICEFATTURAZIONE',
        'CITTAFATTURAZIONE','CODICEFISCALE');
    
    $log->debug('project56 '.$projId.' map '.$mapId.' action '.$actionId.' causale '.$causale);
    if($actionId!='' && $manual==0){
    
    $projfocus=  CRMEntity::getInstance("Project");
    $projfocus->retrieve_entity_info($projId,"Project");
    $actionfocus=  CRMEntity::getInstance("Actions");
    $actionfocus->retrieve_entity_info($actionId,"Actions");
    $action_xml=$actionfocus->column_fields['description'];
    $action_body_email=$actionfocus->column_fields['budymessage'];
    $msgsettingsfocus=  CRMEntity::getInstance("MessageSettings");
    $msgsettingsfocus->retrieve_entity_info($messagesettingsid,"MessageSettings");
    $mapid_mess_sett=$msgsettingsfocus->column_fields['map_entity'];
    $mapid_mess_sett_mittente=$msgsettingsfocus->column_fields['map_mittente'];
    $mess_subject=$msgsettingsfocus->column_fields['messagesubject'];
    $msgcommtype=$msgsettingsfocus->column_fields['msgcommtype'];
    $description=$msgsettingsfocus->column_fields['description'];
    
    //message column fields from  message settings fields here
    $focus = new Task();
    $focus->column_fields['assigned_user_id'] = $current_user->id;
    $focus->column_fields['canale'] = 'ePRICE';
    $focus->column_fields['sottocanale'] ='PostVendita';
    $focus->column_fields['motivo'] ='RMA';
    $focus->column_fields['nome'] =$NOMECONTATTO;
    $focus->column_fields['cognome'] =$COGNOMECONTATTO;
    $focus->column_fields['email'] =$EMAIL_ACC;
    $focus->column_fields['cellulare'] =$PHONE;
    $focus->column_fields['date_start'] =date('Y-m-d');
    $focus->column_fields['time_start'] =date('H:i');
    $focus->column_fields['taskpriority'] ='180';
    $focus->column_fields['taskname'] ='Pratica '.$projfocus->column_fields['projectname'].' '.$mess_subject;
    $focus->column_fields['note'] =$msgcommtype.' '.$description;
    $focus->column_fields['runo'] ='Pratica '.$projfocus->column_fields['projectname'].' '.$mess_subject;
    $focus->column_fields['rtre'] ='http://188.164.131.161/eprice/index.php?module=Project&action=DetailView&record='.$projId;
    $focus->column_fields['rquattro'] =$msgcommtype;
    $focus->column_fields['rcinque'] =$description;
    $focus->column_fields['tipoattivita'] ='Genesis Task';
    
    $focus->save('Task');  
    }
 
    
?>
