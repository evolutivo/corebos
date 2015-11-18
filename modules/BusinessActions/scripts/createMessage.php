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
require_once("modules/Users/Users.php");
require_once("modules/Actions/scripts/tntIntegration.php");
include 'modules/Actions/scripts/changeBarcodetempMessage.php';
require_once('modules/Project/messDocumentsProject.php');
require_once('modules/Actions/scripts/generatePDFMessage.php');
require_once("modules/Actions/scripts/emailServerConfig.php");

$current_user = new Users();
$current_user->retrieveCurrentUserInfoFromFile(1);

function create_Message($projId,$process_flow_id,$send_mail=false){
    global $adb,$log,$current_user,$app_strings,$root_directory;
    $date=date('Y-m-d');
    $logFile="logs/ticket_auth_$date.log";
    $qry=$adb->pquery("Select processtemplatename,
                accountname,vtiger_project.email as email_proj,
                vtiger_accountshipads . * ,
                vtiger_project . * , 
                vtiger_regionalareaservicecenter . * 
                from  vtiger_project 
                left join vtiger_processtemplate on  vtiger_processtemplate.processtemplateid = vtiger_project.commessa
                left join vtiger_account on  vtiger_account.accountid = vtiger_project.linktoaccountscontacts
                LEFT JOIN vtiger_accountbillads ON vtiger_account.accountid = vtiger_accountbillads.accountaddressid
                LEFT JOIN vtiger_accountshipads ON vtiger_account.accountid = vtiger_accountshipads.accountaddressid
                LEFT JOIN vtiger_regionalareaservicecenter ON vtiger_project.linktorasc = vtiger_regionalareaservicecenter.regionalareaservicecenterid
                where vtiger_project.projectid=?",array($projId));
    $name1=$adb->query_result($qry,0,'accountname');
    $commessa_project=$adb->query_result($qry,0,'commessa');
    $originalcomm=$adb->query_result($qry,0,'originalcomm');
    $spedizione_multipezzo=$adb->query_result($qry,0,'spedizione_multipezzo');
    $imagesarray=$adb->query_result($qry,0,'imagesarray');
    $street=$adb->query_result($qry,0,'ship_street');
    $code=$adb->query_result($qry,0,'ship_code');
    $city=$adb->query_result($qry,0,'ship_city');
    $state=$adb->query_result($qry,0,'ship_state');
    $ship_country=$adb->query_result($qry,0,'ship_country');

    $add_rasc=$adb->query_result($qry,0,'indirizzo');
    $postcode_rasc=$adb->query_result($qry,0,'cap');
    $name_rasc=$adb->query_result($qry,0,'regionalascname');
    $country_rasc=$adb->query_result($qry,0,'stato');
    $town_rasc=$adb->query_result($qry,0,'citta');
    $province_rasc=$adb->query_result($qry,0,'provincia');
    $pesogr=$adb->query_result($qry,0,'pesogr');
    $idpratica=$adb->query_result($qry,0,'praticaid');
    $idorig=$adb->query_result($qry,0,'idorig');
    $mailauttorizzazione=$adb->query_result($qry,0,'mailauttorizzazione');
    $sitovertical = $adb->query_result($qry,0,'sitovertical');
    $lockindex=$adb->query_result($qry,0,'lockindex');
    $sitoprovenienza = $adb->query_result($qry,0,'sitoprovenienza');
    $purchasecostdetail=$adb->query_result($qry,0,'purchasecostdetail');
    $tnterror=$adb->query_result($qry,0,'tnterror');
    $tnt_message_error=false;
    $actionId='';$mapId='';$messagesettingsid='';$manual='';$end_subst='';$causale='';
        
    if(empty($sitovertical) || $sitovertical=='' || $sitovertical=='0'){
        $sitovertical='eprice';
    }
    if($commessa_project=='10588642'){
        $qry_action=$adb->pquery("Select *
                    from vtiger_processflow 
                    join vtiger_actions on  vtiger_actions.actionsid= vtiger_processflow.mailer_action                
                    where vtiger_processflow.processflowid=?",array($process_flow_id));
                        $manual = $adb->query_result($qry_action,0,'manual');
                        $end_subst = $adb->query_result($qry_action,0,'end_subst');
                        $causale = $adb->query_result($qry_action,0,'causale');
                        $actionId = $adb->query_result($qry_action,0,'mailer_action');
                        $mapId = $adb->query_result($qry_action,0,'linktomapmodule');
                        
                        $focus_map = CRMEntity::getInstance("Map");
                        $focus_map->retrieve_entity_info($mapId, "Map");
                        $matching_fields = $focus_map->getMapMessageMailer();
                        $match1=$matching_fields['match_field'];
                        $match2=$matching_fields['match_field2'];
                        $matchqry='';
                        for($c1=0;$c1<sizeof($match1);$c1++){
                            $fields_match[]=$adb->query_result($qry,0,$match1[$c1]);
                            $matchqry.=" and vtiger_messagesettings.$match2[$c1]=? ";
                        }
                        
                        $qry_action2=$adb->pquery("Select *
                                from vtiger_messagesettings 
                                join vtiger_crmentity on crmid=messagesettingsid
                                where deleted=0 and vtiger_messagesettings.messagecause='$causale'
                                $matchqry ",$fields_match);
                        if($qry_action2 && $adb->num_rows($qry_action2)>0){
                            $messagesettingsid = $adb->query_result($qry_action2,0,'messagesettingsid');
                        }
                        else {                       
                            return 'Invio non possibile causa dato necessario mancante';
                        }
        }else{
        $qry_action=$adb->pquery("Select mailer_action,manual,messagesettingsid,linktomapmodule,causale,
                                    end_subst,lock_index
                    from vtiger_processflow 
                    join vtiger_actions on  vtiger_actions.actionsid= vtiger_processflow.mailer_action                
                    join vtiger_messagesettings on  concat(vtiger_actions.causale,'_$sitovertical')= vtiger_messagesettings.messagecause
                    join vtiger_crmentity on crmid=messagesettingsid
                    where deleted=0 and vtiger_processflow.processflowid=?",array($process_flow_id));
        $nr_possible_mess_set=$adb->num_rows($qry_action);
        if($nr_possible_mess_set>0){
            for($counting=0;$counting<$nr_possible_mess_set;$counting++){
                $lock_index=$adb->query_result($qry_action,$counting,'lock_index');
                list($first,$second)=explode('-',$lock_index);
                if($lockindex>=$first && $lockindex<=$second){
                        $actionId = $adb->query_result($qry_action,$counting,'mailer_action');
                        $mapId = $adb->query_result($qry_action,$counting,'linktomapmodule');
                        $messagesettingsid = $adb->query_result($qry_action,$counting,'messagesettingsid');
                        $manual = $adb->query_result($qry_action,$counting,'manual');
                        $end_subst = $adb->query_result($qry_action,$counting,'end_subst');
                        $causale = $adb->query_result($qry_action,$counting,'causale');
                }
            }
        }else{
                $qry_action=$adb->pquery("Select mailer_action,manual,messagesettingsid,linktomapmodule,causale,
                                        end_subst,lock_index
                    from vtiger_processflow 
                    join vtiger_actions on  vtiger_actions.actionsid= vtiger_processflow.mailer_action                
                    join vtiger_messagesettings on  concat(vtiger_actions.causale,'_eprice')= vtiger_messagesettings.messagecause
                    join vtiger_crmentity on crmid=messagesettingsid
                    where deleted=0 and vtiger_processflow.processflowid=?",array($process_flow_id));
                $nr_possible_mess_set=$adb->num_rows($qry_action);
                    for($counting=0;$counting<$nr_possible_mess_set;$counting++){
                    $lock_index=$adb->query_result($qry_action,$counting,'lock_index');
                    list($first,$second)=explode('-',$lock_index);
                    if($lockindex>=$first && $lockindex<=$second){
                            $actionId = $adb->query_result($qry_action,$counting,'mailer_action');
                            $mapId = $adb->query_result($qry_action,$counting,'linktomapmodule');
                            $messagesettingsid = $adb->query_result($qry_action,$counting,'messagesettingsid');
                            $manual = $adb->query_result($qry_action,$counting,'manual');
                            $end_subst = $adb->query_result($qry_action,$counting,'end_subst');
                            $causale = $adb->query_result($qry_action,$counting,'causale');
                    }
                }
        }
    }
    error_log(date('Y-m-d H:i').' inside createMessage 
',3,$logFile) ;
    error_log($projId.' 
'.$process_flow_id,3,$logFile) ;

    // related entities to Project (Accounts , ProcessTemplate)
    $processtemplatename = $adb->query_result($qry,0,'processtemplatename');
    $chiusurapraticacliente = $adb->query_result($qry,0,'chiusurapraticacliente');
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
    $sub_array=array('NOMECONTATTO','COGNOMECONTATTO','INDIRIZZOSPEDIZIONE','CODICESPEDIZIONE',
        'CITTASPEDIZIONE','PROVINCIASPEDIZIONE','INDIRIZZOFATTURAZIONE','CODICEFATTURAZIONE',
        'CITTAFATTURAZIONE','CODICEFISCALE');
    
    $log->debug('project3 '.$projId.' map '.$mapId.' action '.$actionId.' causale '.$causale);
    if($actionId!='' && $manual==0){
        
        $host  = getHostName();
        $pos=  strpos($host, 'madunina');
//        if($pos!==false){ // madunina
            $files_to_be_att=checkrelatedDocuments($projId);
            $is_danno=$files_to_be_att['is_danno'];
            if($is_danno && $causale=='send_documenti_danni'){
                $all_mandatory=$files_to_be_att['all_mandatory'];
                if(!$all_mandatory){
                    return 'Missing Mandatory Documents';
                }
            }
//        }
        
        
        if(strpos($causale,'ticket_authorized')!==false){
            if($tnterror=='1') {// if there is previous successfull call
            }
            elseif(strtolower($tnterror)=='m'){
                $action='M';
            }
            else{
                $action='I';
            } 
            
            if($tnterror!='1') {
            error_log($tnterror.' befor calling tnt 
',3,$logFile) ;
            $arr_tnt=tntIntegration($projId,$street,$code,$name1,$ship_country,$city,$state
                ,$add_rasc,$postcode_rasc,$name_rasc,$country_rasc,$town_rasc,$province_rasc,
                $pesogr,$idpratica,$action,$sitoprovenienza,$purchasecostdetail);
                if(empty($arr_tnt)  || !isset($arr_tnt) || $arr_tnt==null){
                    $adb->pquery("Update vtiger_project"
                            . " set tnterror=?"
                            . " where vtiger_project.idorig=?",array('error',$idorig));
                    if($sitoprovenienza=='SaldiPrivati' && $send_mail==true){//only for automatic mess crreation
                        $tnt_message_error=true;
                    }
                    else{
                        return 'error';
                    }
                }elseif($arr_tnt['CODE']!='' && $arr_tnt['CODE']!=null){
                    //update tnterror
                    $adb->pquery("Update vtiger_project"
                            . " set tnterror=?"
                            . " where vtiger_project.idorig=?",array($arr_tnt['MESSAGE'],$idorig));
                    if($sitoprovenienza=='SaldiPrivati' && $send_mail==true){//only for automatic mess crreation
                        $tnt_message_error=true;
                        error_log(' tnt returned code error
'.$arr_tnt['CODE'],3,$logFile) ;
                    }
                    else{
                        return $arr_tnt['MESSAGE'];
                    }
                }
                else{
                    $adb->pquery("Update vtiger_project"
                            . " set tnterror=?"
                            . " where vtiger_project.idorig=?",array('1',$idorig));
//                    $adb->pquery("Update vtiger_project"
//                            . " set tnterror=?"
//                            . " where vtiger_project.projectid=?",array('1',$projId));
                    $log->debug('tntcode '.$arr_tnt['MESSAGE']);
                    shell_exec("chmod -R 777 storage");
                    error_log(' tnt success before gen barcode
',3,$logFile) ;
                    generate_barcode($projId,$arr_tnt);
                    error_log(' tnt success after gen barcode
',3,$logFile) ;
                    shell_exec("cd $root_directory");$log->debug("barcodegenerated storage/$projId.pdf");
                    $result_idorig = $adb->pquery('Select *  '
                        . ' from vtiger_project'
                        . ' join vtiger_crmentity c1 on c1.crmid=projectid'
                        . ' where idorig=? and c1.deleted=0 and projectid <> ? '
                        . ' ',array($idorig,$projId));
                    $nr_pd=$adb->num_rows($result_idorig);
                    for($c_p=0;$c_p<$nr_pd;$c_p++){
                        $projectid=$adb->query_result($result_idorig,$c_p,'projectid');
                        shell_exec("cp /var/www/eprice/storage/Etichette_Reso_$projId.pdf  /var/www/eprice/storage/Etichette_Reso_$projectid.pdf");
                    $log->debug("eachproject storage/Etichette_Reso_$projectid.pdf");
                        
                    }
                    error_log(' all pdf barcode generated 
',3,$logFile) ;
                }   
            }
        }
        error_log(' causale 
'.$causale,3,$logFile) ;
        if(strpos($causale,'product_arrived')!==false && ($chiusurapraticacliente || $chiusurapraticacliente=='1' || $chiusurapraticacliente==1)){

        }
        else{
        $projfocus=  CRMEntity::getInstance("Project");
        $projfocus->retrieve_entity_info($projId,"Project");
//        $actionfocus=  CRMEntity::getInstance("Actions");
//        $actionfocus->retrieve_entity_info($actionId,"Actions");
//        $action_xml=$actionfocus->column_fields['description'];
//        if($projfocus->column_fields['islockable']=="S"){
//        $action_body_email=$actionfocus->column_fields['budymessage'];
//        }
//        else{
//        $action_body_email=$actionfocus->column_fields['description'];
//        }
        $msgsettingsfocus=  CRMEntity::getInstance("MessageSettings");
        $msgsettingsfocus->retrieve_entity_info($messagesettingsid,"MessageSettings");
        $action_xml=$msgsettingsfocus->column_fields['description'];
//        if($projfocus->column_fields['lockindex']<=4){
//            $action_body_email=$msgsettingsfocus->column_fields['budymessage'];
//        }
//        else{
            $action_body_email=$msgsettingsfocus->column_fields['description'];
//        }
        
        
        $mapid_mess_sett=$msgsettingsfocus->column_fields['map_entity'];
        $mapid_mess_sett_mittente=$msgsettingsfocus->column_fields['map_mittente'];
        if (getSalesEntityType($mapid_mess_sett) == 'Map') {
            $map_mess_sett = CRMEntity::getInstance("Map");
            $map_mess_sett->retrieve_entity_info($mapid_mess_sett, "Map");
            $sqlString = $map_mess_sett->getMapSQL();
            $queryExec = $adb->pquery($sqlString,array($projId));
            $messagerecipient = $adb->query_result($queryExec, 0,0);
            $msgrecipientemail = $adb->query_result($queryExec, 0,1);
            if($adb->query_result($queryExec, 0,2)){
                $msgrecipientemailcc = $adb->query_result($queryExec, 0,2);
            }
        } else if(getSalesEntityType($mapid_mess_sett) == 'Accounts') {
            $accid= $mapid_mess_sett;
            $accid_instance=  CRMEntity::getInstance("Accounts");
            $accid_instance->retrieve_entity_info($accid,"Accounts");
            $messagerecipient=$accid_instance->column_fields['accountname'];
            $msgrecipientemail=$accid_instance->column_fields['email1'];
        } else if(getSalesEntityType($mapid_mess_sett) == 'Project') {
            $project_id = $mapid_mess_sett;
            $project_instance=  CRMEntity::getInstance("Project");
            $project_instance->retrieve_entity_info($project_id,"Project");
            $messagerecipient=$project_instance->column_fields['projectname'];
            $msgrecipientemail=$project_instance->column_fields['email'];
        }

        if (getSalesEntityType($mapid_mess_sett_mittente) == 'Map') {
            $map_mess_sett = CRMEntity::getInstance("Map");
            $map_mess_sett->retrieve_entity_info($mapid_mess_sett_mittente, "Map");
            $sqlString = $map_mess_sett->getMapSQL();
            $queryExec = $adb->pquery($sqlString,array($projId));
            $messagerecipient_mittente = $adb->query_result($queryExec, 0,0);
            $msgrecipientemail_mittente = $adb->query_result($queryExec, 0,1); 
        } else if(getSalesEntityType($mapid_mess_sett_mittente) == 'Accounts') {
            $accid= $mapid_mess_sett_mittente;
            $accid_instance=  CRMEntity::getInstance("Accounts");
            $accid_instance->retrieve_entity_info($accid,"Accounts");
            $messagerecipient_mittente=$accid_instance->column_fields['accountname'];
            $msgrecipientemail_mittente=$accid_instance->column_fields['email1'];
        } else if(getSalesEntityType($mapid_mess_sett_mittente) == 'Project') {
            $project_id = $mapid_mess_sett_mittente;
            $project_instance=  CRMEntity::getInstance("Project");
            $project_instance->retrieve_entity_info($project_id,"Project");
            $messagerecipient_mittente=$project_instance->column_fields['projectname'];
            $msgrecipientemail_mittente=$project_instance->column_fields['email'];
        }
        //message column fields from  message settings fields here
        $focus = new Messages();
        $focus->column_fields['assigned_user_id'] = 1;
        $focus->column_fields['project'] = $projId;
        $focus->column_fields['name'] ='Message '.$causale.' '.$msgsettingsfocus->column_fields['idambitomsg'];
        $focus->column_fields['idtipoambito'] =$msgsettingsfocus->column_fields['idambitomsg'];
        $focus->column_fields['messagio'] =htmlspecialchars_decode($msgsettingsfocus->column_fields['description']); 
        $focus->column_fields['nomedestinatario'] =$messagerecipient;
        $focus->column_fields['recapitodestinatario'] =$msgrecipientemail;
        $focus->column_fields['blindcc'] =$msgrecipientemailcc;
        $focus->column_fields['nomemittente'] =$messagerecipient_mittente;
        $focus->column_fields['recapitomittente'] =$msgrecipientemail_mittente;
        $focus->column_fields['numeroordine'] =$projfocus->column_fields['salesordernumber'];
        if(strpos($causale,'product_arrived')!==false ){
        $focus->column_fields['subject'] =$projfocus->column_fields['sitovertical'].
                "  - Conferma ricevimento prodotto " .
                 $processtemplatename.' N. '.$projfocus->column_fields['praticaid']." - ordine N."
                .$projfocus->column_fields['salesordernumber'];   
        }
        else if($msgsettingsfocus->column_fields['idambitomsg']=='39'){
        $focus->column_fields['subject'] =$projfocus->column_fields['sitovertical'].
                " - Comunicazione segnalazione anomalia di consegna N." .
                 $projfocus->column_fields['idorig']." - ordine N."
                .$projfocus->column_fields['salesordernumber'];
        }
        else if($msgsettingsfocus->column_fields['idambitomsg']=='38'){
        $focus->column_fields['subject'] =$projfocus->column_fields['sitovertical'].
                 " - Autorizzazione reso per danno N.".$projfocus->column_fields['idorig'].
                 " - ordine N.".$projfocus->column_fields['salesordernumber'];      
        }
        else if($msgsettingsfocus->column_fields['idambitomsg']=='40'){
        $focus->column_fields['subject'] =$projfocus->column_fields['sitovertical'].
                 " - Richiesta di recesso N.".$projfocus->column_fields['idorig'].
                 " - ordine N.".$projfocus->column_fields['salesordernumber'];             
        }
        else if($msgsettingsfocus->column_fields['idambitomsg']=='80' && strpos($causale,'ticket_authorized')!==false){
        $focus->column_fields['subject'] =$projfocus->column_fields['sitovertical'].
                "  - Autorizzazione reso prodotto non funzionante N." .
                 $processtemplatename.' '.$projfocus->column_fields['praticaid']." - ordine N."
                .$projfocus->column_fields['salesordernumber'];          
        }
        else if($msgsettingsfocus->column_fields['idambitomsg']=='50' && strpos($causale,'ticket_authorized')!==false){
        $focus->column_fields['subject'] =$projfocus->column_fields['sitovertical'].
                "  - Richiesta di recesso " .
                $projfocus->column_fields['idorig'];          
        }
        else if($msgsettingsfocus->column_fields['idambitomsg']=='55' ){
        $focus->column_fields['subject'] =
                'ePRICE Segnalazione Danno LDV '.$projfocus->column_fields['letteravettura'].
                ' Ordine/TRA '.$projfocus->column_fields['salesordernumber'].
                ' Spedizione '.$projfocus->column_fields['numerospedizione'].
                ' Pratica '.$projfocus->column_fields['praticaid'];          
        }
        else if($msgsettingsfocus->column_fields['idambitomsg']=='51' ){
        $focus->column_fields['subject'] =
                'Lettera Claims '.$projfocus->column_fields['posizione'];          
        }
        else if($msgsettingsfocus->column_fields['idambitomsg']=='52' ){
        $focus->column_fields['subject'] =
                'SdaRimDox# '.$projfocus->column_fields['posizione'].
                ' CLI=EPRICE SRL PRA= '.$projfocus->column_fields['numeropraticavettore'];          
        }
        else if($msgsettingsfocus->column_fields['idambitomsg']=='53' ){
        $focus->column_fields['subject'] =
                ' ePRICE Segnalazione Danno LDV '.$projfocus->column_fields['letteravettura'].
                ' Ordine/TRA '.$projfocus->column_fields['salesordernumber'].
                ' Spedizione '.$projfocus->column_fields['numerospedizione'].
                ' Pratica '.$projfocus->column_fields['praticaid'];          
        }
        else
        {
        $focus->column_fields['subject'] =$projfocus->column_fields['sitovertical'].
                " - COMUNICAZIONE RELATIVA SEGNALAZIONE PRODOTTO NON FUNZIONANTE " .
                 $projfocus->column_fields['idorig']." - ordine N."
                .$projfocus->column_fields['salesordernumber'];                
        }

        if(strpos($causale,'product_arrived')!==false){
            $focus->column_fields['tipomessagio'] ='Product_Arrived';
        }
        elseif(strpos($causale,'ticket_authorized')!==false){
            $focus->column_fields['tipomessagio'] ='Ticket_Authorized';
        }
        elseif(strpos($causale,'rejected_request')!==false){
            $focus->column_fields['tipomessagio'] ='Rejected_Request';
        }
        else{
            $focus->column_fields['tipomessagio'] ='Others';
        }


        $mapfocus=  CRMEntity::getInstance("Map");
        $mapfocus->retrieve_entity_info($mapId,"Map");
        $mapMessageMailer=$mapfocus->getMapMessageMailer();
        
        $query_related_entities=" ";
        $query_related_fields=" ";
        $query_related_join=" ";
        $relatedtargetfield=$mapMessageMailer['relatedcolumnfield'];
        for($related_i=0;$related_i<sizeof($relatedtargetfield);$related_i++){
            $curr_f=$relatedtargetfield[$related_i];
            $curr_entity_f=$mapMessageMailer['relatedui10field'][$related_i];
            $related_tab=$adb->query_result($adb->pquery("select relmodule "
                    . " FROM vtiger_fieldmodulerel 
                        JOIN vtiger_field
                        ON vtiger_field.fieldid = vtiger_fieldmodulerel.fieldid
                        where vtiger_field.fieldname=? and module=?",array($curr_entity_f,'Project')),0,'relmodule');
            $rel_mod=$related_tab;//getTabName($related_tab);
            require_once("modules/$rel_mod/$rel_mod.php");
            $rel_foc=CRMEntity::getInstance("$rel_mod");
            $tabler=$rel_foc->table_name;
            $idr=$rel_foc->table_index;
            $query_related_fields.=($related_i==0 ? "$tabler.$curr_f" : ",$tabler.$curr_f");
            if(stristr($query_related_join,$tabler)=='')    
                $query_related_join.=" left join $tabler on $tabler.$idr = vtiger_project.$curr_entity_f"
                    . " left join vtiger_crmentity c$related_i on c$related_i.crmid = $tabler.$idr ";
        }
        error_log(' relatedfields query  
'."Select $query_related_fields "
                    . " from vtiger_project "
                    . " $query_related_join"
                    . " where projectid =$projId",3,$logFile) ;
        if(sizeof($relatedtargetfield)>0){
            $result_related_query=$adb->pquery("Select $query_related_fields "
                    . " from vtiger_project "
                    . " $query_related_join"
                    . " where projectid =?",array($projId));
            $log->debug('testing2 '."Select $query_related_fields "
                    . " from vtiger_project "
                    . " $query_related_join"
                    . " where projectid =");

            $relatedtargetconstant=$mapMessageMailer['relatedtargetconstant'];
            for($related_i=0;$related_i<sizeof($relatedtargetconstant);$related_i++){

                $val=$adb->query_result($result_related_query,0,$mapMessageMailer['relatedcolumnfield'][$related_i]);
                $action_xml=str_replace($relatedtargetconstant[$related_i],$val,$action_xml); 
                $action_body_email=str_replace($relatedtargetconstant[$related_i],$val,$action_body_email);
            }
        }

        // message body (description) replacing from the map related to PF action with the project fields
        $log->debug('desc2 '.$action_xml);
        for($i_map=0;$i_map<sizeof($mapMessageMailer['targetfield']);$i_map++){
            $dt_pos=strpos($mapMessageMailer['targetfield'][$i_map],'DATA');
            $log->debug('field_test '.$mapMessageMailer['targetfield'][$i_map].' '.$mapMessageMailer['columnfield'][$i_map].' '.$projfocus->column_fields[$mapMessageMailer['columnfield'][$i_map]]);      
            if($mapMessageMailer['targetfield'][$i_map]=='COMMESSA'){
                $action_xml=str_replace($mapMessageMailer['targetfield'][$i_map],$processtemplatename,$action_xml);
                $action_body_email=str_replace($mapMessageMailer['targetfield'][$i_map],$processtemplatename,$action_body_email);
            }
            elseif($mapMessageMailer['targetfield'][$i_map]=='DATACONSEGNA'){
                if($projfocus->column_fields[$mapMessageMailer['columnfield'][$i_map]]!=''){
                    $dt=date('d-m-Y',strtotime($projfocus->column_fields[$mapMessageMailer['columnfield'][$i_map]]));
            }
            else{
                    $dt='';
                }
                
                $action_body_email=str_replace($mapMessageMailer['targetfield'][$i_map],$dt,$action_body_email);
            }
            else{
                $val=$projfocus->column_fields[$mapMessageMailer['columnfield'][$i_map]];
    //            $val=str_replace("'", '' ,$val);
    //            $val=str_replace("&quot;", '' ,$val);
                $action_xml=str_replace($mapMessageMailer['targetfield'][$i_map],$val,$action_xml); 
                $action_body_email=str_replace($mapMessageMailer['targetfield'][$i_map],$val,$action_body_email);
                if($dt_pos!==false){
                    $action_body_email=str_replace($mapMessageMailer['targetfield'][$i_map],date('d-m-Y',strtotime($projfocus->column_fields[$mapMessageMailer['columnfield'][$i_map]])),$action_body_email);   
                }
                else
                {
                    $action_body_email=str_replace($mapMessageMailer['targetfield'][$i_map],$projfocus->column_fields[$mapMessageMailer['columnfield'][$i_map]],$action_body_email);     
                }
            }

            }
        // replacing message body (description) from the map related to PF action with the account fields related to the project
        for($i_sub=0;$i_sub<sizeof($sub_array);$i_sub++){  
            $action_xml=str_replace($sub_array[$i_sub],$$sub_array[$i_sub],$action_xml); 
            $action_body_email=str_replace($sub_array[$i_sub],$$sub_array[$i_sub],$action_body_email); 
            }
        $action_xml=str_replace('ADMINISTRATOR',$current_user->username,$action_xml);   
        $action_body_email=str_replace('ADMINISTRATOR',$current_user->username,$action_body_email);   
        $action_xml=str_replace('DATASCADENZAAUTORIZZAZIONE',  date('d-m-Y',strtotime($projfocus->column_fields['startdate']. " +15 days")),$action_xml);   
        $action_body_email=str_replace('DATASCADENZAAUTORIZZAZIONE',date('d-m-Y',strtotime($projfocus->column_fields['startdate']. " +15 days")),$action_body_email); 
        //DATAFATTURA - DATADIINIZIO
        $action_xml=str_replace('DIFFERENZAGGRICHIESTA',  abs(strtotime($projfocus->column_fields['invoicedate']) - strtotime($projfocus->column_fields['startdate'])),$action_xml);
        $action_body_email=str_replace('DIFFERENZAGGRICHIESTA',  abs(strtotime($projfocus->column_fields['invoicedate']) - strtotime($projfocus->column_fields['startdate'])),$action_body_email);   
        $action_xml=str_replace('DATACONSEGNA',date('d-m-Y',strtotime($projfocus->column_fields['dateincomingpartn'])),$action_xml);
        $action_body_email=str_replace('DATACONSEGNA',date('d-m-Y',strtotime($projfocus->column_fields['dateincomingpartn'])),$action_body_email);
        //if @diffGGReqFat < 15  isDoa = 'S' else isDoa = 'G'
        if(abs(strtotime($projfocus->column_fields['invoicedate']) - strtotime($projfocus->column_fields['startdate'])) < 15)
            $isdoa='S';
        else
            $isdoa='G';
        $action_xml=str_replace('ISDOA',  $isdoa ,$action_xml);  
        $action_body_email=str_replace('ISDOA',  $isdoa ,$action_body_email);  

    //    $action_xml=str_replace('"', '' ,$action_xml);  
    //    $action_body_email=str_replace('"', '' ,$action_body_email);  
    //    $action_xml=str_replace("'", '' ,$action_xml);  
    //    $action_body_email=str_replace("'", '' ,$action_body_email); 

    //    $focus->column_fields['msgdescription'] = htmlspecialchars_decode($action_body_email);
        //htmlspecialchars_decode($action_xml);//html_entity_decode
        $focus->column_fields['bodymessage_msg'] = htmlspecialchars_decode($action_body_email);
        $focus->save('Messages'); 
        if($process_flow_id==0){
            generatePDFMessage($projId,$causale,htmlspecialchars_decode($action_body_email));
        }
        $host  = getHostName();
        $pos=  strpos($host, 'madunina');
        
//        10905620
        //send mail from rendicontaGneral 
     error_log(' before send mail automatic
',3,$logFile) ;
        if(strpos($causale,'ticket_authorized')!==false && $send_mail 
                && (!$mailauttorizzazione ||  $mailauttorizzazione==0 || $mailauttorizzazione!=1) ){
            $log->debug('test22 ' .$msgrecipientemail);
            $attach_ddt='';
            $desc1='
                 <table border="0" cellpadding="0" cellspacing="0" style="margin:0; padding:0;" width="100%">
 <tbody>
  <tr>
   <td>
   <table align="center" bgcolor="#ffffffFFF" border="0" cellpadding="0" cellspacing="0" width="550">
    <tbody>
     <tr>
      <td align="center"><a href="#"><img alt="SaldiPrivati" border="0" src="http://resources.saldiprivati.com/Static/images/email/logo.png" style="display:block;" width="550" /> </a></td>
     </tr>
     <!-- prima intestazione--><!-- prima tabella --><!-- seconda intestazione--><!-- seconda tabella --><!-- terza tabella -->
     <tr>
      <td valign="top">
      <p><span style="font-family: Arial, Verdana, sans-serif; color: rgb(0, 0, 0); line-height: 1.6em;">Ciao,</span></p>

      <p><font color="#000000" style="font-size:13px; font-family: Arial, Verdana, sans-serif;">A causa di un problema tecnico temporaneo,<br />
      in questo momento non riusciamo ad inviarti l&rsquo;autorizzazione richiesta.<br />
      <strong>Non ti preoccupare:</strong>&nbsp;non appena risolto il problema, sar&agrave; nostra cura inviartela.</font></p>

      <p><font color="#000000" style="font-size:13px; font-family: Arial, Verdana, sans-serif;">​</font></p>
      </td>
     </tr>
     <tr>
      <td align="left"><font style="font-size:14px; font-family: Arial, Verdana, sans-serif; color:#4c3737;">Grazie per avere fatto shopping con noi! </font> <a href="#"> <img alt="Grazie per il tuo passaparola, Anna Trabucchi" border="0" src="http://resources.saldiprivati.com/Static/images/email/bottom.png" style="display:block;" width="550" /> </a></td>
     </tr>
     <tr>
      <td>
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
       <tbody>
        <tr>
         <td valign="top"><font color="#000000" style="font-size:13px; font-family: Arial, Verdana, sans-serif;"><b>Anna Trabucchi</b> Client Manager <b><a href="#" style="color:#000000; text-decoration:none">www.saldiprivati.com</a></b> </font></td>
         <td align="right" valign="top"><font color="#999999" style="font-size:13px; font-family: Arial, Verdana, sans-serif;"><a href="#" style="color:#999999; text-decoration:none">Contatti</a> | <a href="#" style="color:#999999; text-decoration:none">Condizioni di Vendita</a> | <a href="#" style="color:#999999; text-decoration:none">Privacy</a> </font></td>
        </tr>
       </tbody>
      </table>
      </td>
     </tr>
    </tbody>
   </table>
   </td>
  </tr>
 </tbody>
</table>';
            if($tnt_message_error==false){
                $attach_ddt='false';
                $desc1=$focus->column_fields['bodymessage_msg'];
            }
            send_email_action($focus->column_fields['tipomessagio'],$projId,$sitoprovenienza,'',
                $attach_ddt,$desc1,$messagesettingsid,$focus->column_fields['subject'],$msgrecipientemail
                    ,true,$focus->id);
            
            error_log(' after send mail automatic 
'.$projId.' '.$idorig,3,$logFile) ;

            if($sitoprovenienza=='SaldiPrivati'){
                $adb->pquery("Update vtiger_project"
                    . " set mailauttorizzazione=?"
                    . " where vtiger_project.idorig=?",array(1,$idorig));
            }
        }
        }
    }
    return true.'@@'.$messagesettingsid.'@@'.$sitoprovenienza.'@@'.$focus->id.'@@'.$causale;
}

$date=date('Y-m-d');
$logFile="logs/ticket_auth_$date.log";    
$log->debug('project_test_arg '.$argv[1].' project_test_rendic2 '.$projId.' '.$process_flow_id);
 if($rendic=='yes'){ // from rendiconta general
    
 }
 elseif($projId!='' && $process_flow_id!=''){  //from inside Rendiconta button
    error_log($projId.' wrong entry for $projid 
',3,$logFile) ;
    $projId = $projId;
    $process_flow_id=$process_flow_id;
    create_Message($projId,$process_flow_id,true);
 }
 elseif(isset($argv) && !empty($argv)) //as action
 { 
    error_log($argv.' wrong entry for $argv 
',3,$logFile) ;
    $projId_processflow = explode(',',$argv[1]);
    $projId = $projId_processflow[0];
    $process_flow_id=$projId_processflow[1];
    $r=create_Message($projId,$process_flow_id,false);
    echo $r;
 }
 
?>
