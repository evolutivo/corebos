<?php
global $adb,$log,$current_user,$app_strings;
require_once("config.inc.php");
require_once("include/database/PearDatabase.php");
require_once("include/utils/utils.php");
require_once('modules/Project/Project.php');
include_once('modules/Messages/Messages.php');
include_once('modules/MessageSettings/MessageSettings.php');
include_once('modules/Actions/Actions.php');
include_once('modules/Accounts/Accounts.php');

function reinvia_Message($projId,$parameter){
    global $adb,$log,$current_user,$app_strings;

    if($parameter=='ticket_authorized'){
        $parameter ='Ticket_Authorized';
    }
    else{
        $parameter='Product_Arrived';
    }
    $qry=$adb->pquery("SELECT messagesid,sitoprovenienza
            FROM vtiger_project
            JOIN vtiger_messages ON vtiger_messages.project = vtiger_project.projectid
            JOIN vtiger_crmentity ON crmid = vtiger_messages.messagesid
            WHERE vtiger_project.projectid =? and tipomessagio=? and deleted=0",array($projId,$parameter));
    $nr_mess=$adb->num_rows($qry);      
    if($nr_mess>0){
        $messagesid = $adb->query_result($qry,0,'messagesid');
        $sitoprovenienza = $adb->query_result($qry,0,'sitoprovenienza');
        $focus_old = CRMEntity::getInstance('Messages');
        $focus_old->retrieve_entity_info($messagesid,'Messages');
        //message column fields from  message settings fields here
        require_once("modules/Users/Users.php");
        $current_user = new Users();
        $current_user->retrieveCurrentUserInfoFromFile(1);
        $focus = new Messages();
        $focus->column_fields['assigned_user_id'] = 1;
        $focus->column_fields['project'] = $projId;
        $focus->column_fields['name'] =$focus_old->column_fields['name'];
        $focus->column_fields['idtipoambito'] =$focus_old->column_fields['idtipoambito'];
        $focus->column_fields['messagio'] =$focus_old->column_fields['messagio']; 
        $focus->column_fields['nomedestinatario'] =$focus_old->column_fields['nomedestinatario'];
        $focus->column_fields['recapitodestinatario'] =$focus_old->column_fields['recapitodestinatario'];
        $focus->column_fields['nomemittente'] =$focus_old->column_fields['nomemittente'];
        $focus->column_fields['recapitomittente'] =$focus_old->column_fields['recapitomittente'];
        $focus->column_fields['numeroordine'] =$focus_old->column_fields['numeroordine'];
        $focus->column_fields['subject'] =$focus_old->column_fields['subject']; 
        $focus->column_fields['tipomessagio']=$focus_old->column_fields['tipomessagio'];
//        $focus->column_fields['msgdescription'] = htmlspecialchars_decode($focus_old->column_fields['msgdescription']);
        $focus->column_fields['bodymessage_msg'] = htmlspecialchars_decode($focus_old->column_fields['bodymessage_msg']);
        $focus->column_fields['senddate'] = date('Y-m-d');
        $focus->save('Messages'); 
//        $_REQUEST['type_causale']=$parameter;
//        $_REQUEST['rec']=$projId;
//        $_REQUEST['emails']=$focus_old->column_fields['recapitodestinatario'];
//        $_REQUEST['subject']=' '.$focus_old->column_fields['subject'];
//        $_REQUEST['desc']='<br/>'.$focus_old->column_fields['bodymessage_msg'];
//        $_REQUEST['attach_ddt']='false';
//        require_once("modules/Actions/scripts/emailServerConfig.php");
        echo "index.php?module=Emails&action=EmailsAjax&pmodule=Project&file=EditView&sendmail=true&idlist=$projId&map_id=has_map&field_lists=&msgsett=&sitoprovenienza=$sitoprovenienza&messid=$focus->id";
    }
    else{
        echo 'Non esiste alcun Messagio '.$parameter;
    }
}
 if(isset($argv) && !empty($argv)) //as action
 { 
    $projId = $argv[1];
    $parameter = $argv[3];
    reinvia_Message($projId,$parameter);
 }
?>
