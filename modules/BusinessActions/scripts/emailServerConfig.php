<?php

function send_email_action($type_causale,$id,$sitoprovenienza,$ccmails,
        $attach_ddt,$desc,$msgsett,$subject,$emails,$automatic,$m_id){
    
    if(!class_exists('PHPMailerAutoload'))
        { require_once 'modules/Emails/PHPMailerAutoload.php'; }
    if(!class_exists('Actions'))
        { include_once('modules/Actions/Actions.php'); }
    if(!class_exists('Map'))
        { include_once('modules/Map/Map.php'); }
    if(!class_exists('Users'))
        { require_once("modules/Users/Users.php"); }
    if(!class_exists('Messages'))
        { include_once('modules/Messages/Messages.php'); }
    include_once('data/CRMEntity.php');
    require_once('include/utils/utils.php');
    global $adb,$root_directory,$log,$current_user;
    
    $date=date('Y-m-d');
    $logFile="logs/ticket_auth_$date.log";
    $current_user = new Users();
    $current_user->retrieveCurrentUserInfoFromFile(1);
    //rma.eprice@banzai.it
    $ids=array();
    if(strpos($id,';')!==false){
        $ids=explode(';',$id);
    }
    else
        $ids[0]=$id;
    $actionfocus=  CRMEntity::getInstance("Actions");
    if($sitoprovenienza=='SaldiPrivati'){
        $host  = getHostName();
        $pos=  strpos($host, 'madunina');
    //    if($pos!==false){ // madunina
    //        $actionfocus->retrieve_entity_info('10901211',"Actions");
    //    }
    //    else{
            $actionfocus->retrieve_entity_info('11054382',"Actions");
    //    }
    }
    else{
        $actionfocus->retrieve_entity_info('151321',"Actions");
    }

    $mapId=$actionfocus->column_fields['linktomapmodule']; //map of the Mail Server Config
    $mapfocus=  CRMEntity::getInstance("Map");
    $mapfocus->retrieve_entity_info($mapId,"Map");
    $mapMessageMailer=$mapfocus->getMapMessageMailer();

    for($i=0;$i<sizeof($mapMessageMailer['targetfield']);$i++){

    if($mapMessageMailer['targetfield'][$i]=='Username'){
        $Username=$mapMessageMailer['columnfield'][$i];   
    }
    if($mapMessageMailer['targetfield'][$i]=='Password'){
        $Password=$mapMessageMailer['columnfield'][$i];   
    }
    if($mapMessageMailer['targetfield'][$i]=='Reply'){
        $Reply=$mapMessageMailer['columnfield'][$i];   
    }
    if($mapMessageMailer['targetfield'][$i]=='Sender'){
        $Sender=$mapMessageMailer['columnfield'][$i];   
    }
    }

    for($count=0;$count<sizeof($ids);$count++){
        if(empty($ids[$count])) continue;        
        $mail = new PHPMailer;
        $mail->SMTPDebug = 3;                               // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $Username;                 // SMTP username
        $mail->Password = $Password;                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to
        $mail->From = $Username;
        $mail->FromName = $Sender;
        $mail->addReplyTo($Reply, 'ServizioClienti');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('a.celepija@studiosynthesis.biz');

        $mail->WordWrapgit  = 50;  // Set word wrap to 50 characters
        if($type_causale=='Ticket_Authorized'){
            $actionid=13433;
            $recordid=$id;
            $outputType="";
            $confirmVal='';
            $module='Actions';
            $focusAction=CRMEntity::getInstance($module);
            $focusAction->retrieve_entity_info($actionid,$module);
            $response=$focusAction->executeAction($recordid,$outputType,$confirmVal,'','');

            $_REQUEST['recordid']=$id;
            $_REQUEST['map']='13434'; // the map of createBarcodepdf Action
            $host  = getHostName();
            $pos=  strpos($host, 'madunina');
        //    if($pos===false){ // production
        //        include 'modules/Actions/scripts/createBarcodepdf.php';
        //    }
        //    else{
        //        include 'modules/Actions/scripts/changeBarcodetemp.php';
        //    }

            if($attach_ddt=='false')// Add attachments
            {
                if(file_exists('/var/www/eprice/storage/ModuloDiReso_'.$id.'.pdf')){
                    $mail->addAttachment('/var/www/eprice/storage/ModuloDiReso_'.$id.'.pdf'); 
                }
                elseif(file_exists("/var/www/eprice/storage/sovracollo_".$id.".pdf")){
                    $mail->addAttachment("/var/www/eprice/storage/sovracollo_".$id.".pdf");
                }
                else{
                    $mail->addAttachment("/var/www/eprice/storage/Etichette_Reso_".$id.".pdf");
                }
            }
        }
        if($attach_ddt=='false')// Add attachments
            {
                $messagesettingsid=$msgsett;
                $retrieve_notes=$adb->pquery("SELECT * 
                                FROM vtiger_notes 
                                JOIN vtiger_messagesettings ON vtiger_notes.messagesetting = vtiger_messagesettings.messagesettingsid
                                JOIN vtiger_seattachmentsrel ON vtiger_notes.notesid = vtiger_seattachmentsrel.crmid
                                JOIN vtiger_attachments ON vtiger_attachments.attachmentsid = vtiger_seattachmentsrel.attachmentsid
                                JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_notes.notesid

                                WHERE deleted =0
                                AND messagesettingsid =?",array($messagesettingsid));
                $count_doc=$adb->num_rows($retrieve_notes);
                for($c_doc=0;$c_doc<$count_doc;$c_doc++){
                    $att_id=$adb->query_result($retrieve_notes,$c_doc,'attachmentsid');
                    $path_of_doc=$adb->query_result($retrieve_notes,$c_doc,'path');
                    $name=$adb->query_result($retrieve_notes,$c_doc,'name');

                    $mail->addAttachment('/var/www/eprice/'.$path_of_doc.$att_id.'_'.$name); 
                }
            }
        $mail->isHTML(true); 
        // Set email format to HTML
        $emails_arr=explode(',',$emails);
        for($m=0;$m<sizeof($emails_arr);$m++){
            if(empty($emails_arr[$m])) continue;
            $mail->addAddress($emails_arr[$m]);     // Add a recipient 
        }
        $subject=$subject;
        $body=$desc;
        $mail->Subject =$subject;
        $mail->Body    =$body;
        for($cci=0;$cci<sizeof($ccmails);$cci++){
            if($ccmails[$cci]=='') continue;
            $mail->addCC($ccmails[$cci]);
        }
        if(!$mail->send()) {
            error_log(date('Y-m-d H:i').' problem in sending mail saldi 
',3,$logFile) ;
    //            echo 'Message could not be sent.';
    //            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
    //            echo 'Message has been sent';
        }
  }
}
