
<?php

require_once('modules/ProjectTask/ProjectTask.php');
require_once('modules/Messages/Messages.php');
require_once('modules/Documents/Documents.php');
require_once('include/database/PearDatabase.php');
require_once('include/utils/utils.php');
require_once("modules/Documents/Documents.php");

global $adb,$current_language,$current_user,$root_directory,$log,
        $app_strings, $mod_strings, $current_language, $currentModule, $theme;


$chiudi_str=$_REQUEST['chiudi_str'];
$id=$_REQUEST['project_id'];
$orario_inizio=$_REQUEST['orario_inizio'];
$orario_fine=$_REQUEST['orario_fine'];
$orario_inizio_time=$_REQUEST['orario_inizio_time'];
$orario_fine_time=$_REQUEST['orario_fine_time'];
$note=$_REQUEST['note'];
$tecnico=$_REQUEST['tecnico'];


$sql1 = "select * from vtiger_project"
        . " where projectid=?";
$res=$adb->pquery($sql1,array($id));
$projectname=$adb->query_result($res,0,'projectname');

           

    $focus = CRMEntity::getInstance("ProjectTask");
    $focus->column_fields['startdate']=$orario_inizio; 
    $focus->column_fields['enddate']=$orario_fine; 
    $focus->column_fields['time_start_pt']=$orario_inizio_time; 
    $focus->column_fields['time_end_pt']=$orario_fine_time; 
    $focus->column_fields['projecttaskname']=$projectname;
    $focus->column_fields['projectid']=$id;
    $focus->column_fields['assigned_user_id']=$current_user->id;
    $focus->save("ProjectTask"); 

    $focus = CRMEntity::getInstance("Messages");
    $focus->column_fields['name']=$projectname; 
    $focus->column_fields['messagecategory']='Descrizione intervento'; 
    $focus->column_fields['project']=$id; 
    $focus->column_fields['msgdescription']=$note;
    $focus->column_fields['assigned_user_id']=$current_user->id;
    $focus->save("Messages"); 
    
    if($_SESSION['file_upload_kendo_chiudi']!=''){
        
    $upload_file_path = decideFilePath();
    $att_id = $_SESSION['file_upload_kendo_chiudi_currid'];
    $file=$_SESSION['file_upload_kendo_chiudi'];

$sql1 = "insert into vtiger_crmentity
            (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime)
            values(?, ?, ?, ?, ?, ?, ?)";
        $params1 = array($att_id, 1, 1, " Attachment", '', $adb->formatDate($date_var, true), $adb->formatDate($date_var, true));
        $adb->pquery($sql1, $params1);
        $sql2="insert into vtiger_attachments(attachmentsid, name, description, type,path) values(?, ?, ?, ?,?)";
        $params2 = array($att_id, $file, '', 'application/pdf',$upload_file_path);
        $result=$adb->pquery($sql2, $params2);

        $document=new Documents();
        $document->column_fields["notes_title"]=$projectname;
        $document->column_fields["notecontent"] ="Description";
        $document->column_fields["filename"] =$file;
        $document->column_fields["filetype"] ="application/pdf";
        $document->column_fields["filelocationtype"]="I";
        $document->column_fields["fileversion"] = 1;
        $document->column_fields["filestatus"] = 1;
        $document->column_fields["filesize"]=169758;
        $document->column_fields["folderid"]=1;
        $document->column_fields["assigned_user_id"] = 1;
        $document->save("Documents");
        $log->debug('klm2 '.$id. ' '.$projectname);

        $adb->pquery("Insert into vtiger_seattachmentsrel
        values (?,?)",array($document->id,$att_id));

        $adb->pquery("Insert into vtiger_senotesrel
            values (?,?)",array($id,$document->id));
        
    }//if documento !=''
    
    if($chiudi_str=='chiudi')
        $stato='Merkur CHIUSA';
    else
        $stato='Merkur SOSPESA';
        
    $sql1 = "Update vtiger_project"
        . "  set  statuslavor=?,"
            . " tecnicirf=?"
            . " where projectid=?";
    $res=$adb->pquery($sql1,array($stato,$tecnico,$id));
 
