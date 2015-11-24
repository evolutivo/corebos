<?php
function pdfGenerator($request){
include_once('data/CRMEntity.php');
include_once('modules/cbMap/cbMap.php');
include_once('modules/Documents/Documents.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
require_once('include/fpdm/fpdm.php');
require_once('config.inc.php'); 
global $adb,$log,$current_user,$site_URL;
// $str= $argv[1];
// $importantStuff = explode('recordid=', $str);
//
// $record = $importantStuff[1];

//$log = & LoggerManager::getLogger("index");
$current_user = new Users();
$result = $current_user->retrieveCurrentUserInfoFromFile(1);
//$request = array();
//if (isset($argv) && !empty($argv)) {
//    for ($i = 1; $i < count($argv); $i++) {
//        list($key, $value) = explode("=", $argv[$i]);
//        $request[$key] = $value;
//    }
//}
$recordid = $request['recordid'];
$us=$request['confirm'];
$log->debug("loro");
$log->debug($request);
//$mapid = $request['mapid'];
$recid = explode(',', $recordid);

$match="SELECT * 
FROM vtiger_adocdetail join vtiger_crmentity on crmid=adocdetailid
JOIN vtiger_adocmaster ON vtiger_adocmaster.adocmasterid = vtiger_adocdetail.adoctomaster
WHERE adoctomaster =$recid[0] and deleted=0";

 $count = $adb->query($match);  
 $num_rows = $adb->num_rows($count); 
 $b=$adb->query_result($count,0,'doctype');
 
 $docSettings="select * from vtiger_docsettings where causale='$b' and SUBSTRING_INDEX(righedaa,'..',-1)>=$num_rows and SUBSTRING_INDEX(righedaa,'..',1)<=$num_rows";
 
 $count1 = $adb->query($docSettings);
 $num_rows1=$adb->num_rows($count1);
 if($num_rows1!=0){
 //$pdfName=$adb->query_result($count1,0,'nometemp');
 $mapIDadd=$adb->query_result($count1,0,'linktomapdetail');
 $mapIDadm=$adb->query_result($count1,0,'linktomapmaster');
 $attach=$adb->query_result($count1,0,'autoattach');
 $tipo=$adb->query_result($count1,0,'tipo');
 $documentID=$adb->query_result($count1,0,'linktodocuments');
 $attachmentquery = $adb->pquery("SELECT attachmentsid,filename,path
                                FROM vtiger_notes
                                INNER JOIN vtiger_attachments ON filename=name
                                WHERE vtiger_notes.notesid=? ", array($documentID));
if ($adb->num_rows($attachmentquery) > 0) {
    $fileName = $adb->query_result($attachmentquery, 0, 'filename');
    $pathFile = $adb->query_result($attachmentquery, 0, 'path');
    $attachmentsid = $adb->query_result($attachmentquery, 0, 'attachmentsid');
    $pdfName=$pathFile.$attachmentsid."_".$fileName;
}
 }
 
  if($tipo=='Master Detail' || $tipo=='Piato'){
      
     $recordid = $adb->query_result($count,0,'adocmasterid');
     //filename
     $filename="DDT_".date('Y-m-d').".pdf";
$upload_file_path = decideFilePath();
$saveasfile=$upload_file_path."/$filename.pdf";

     $focus1 = CRMEntity::getInstance("cbMap");
     $focus1->retrieve_entity_info($mapIDadm, "cbMap");
   
     $origin_module = $focus1->getMapOriginModule();
     
     $target_module = $focus1->getMapTargetModule();
   
     $target_fields = $focus1->readMappingType();
     
  if($attach=='Sempre' || ($attach=='Scelta Utente' && $us==1)){

  $att_id = $adb->getUniqueID("vtiger_crmentity");
  $sql1 = "insert into vtiger_crmentity
    (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime)
    values(?, ?, ?, ?, ?, ?, ?)";
  
$params1 = array($att_id, 1, 1, " Attachment", '', date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
$adb->pquery($sql1, $params1);
$sql2="insert into vtiger_attachments(attachmentsid, name, description, type,path) values(?, ?, ?, ?,?)";
$params2 = array($att_id, $filename, '', 'application/pdf',$upload_file_path);
$result=$adb->pquery($sql2, $params2);
 
$document=new Documents();
$document->column_fields["notes_title"]="$filename";
$document->column_fields["filename"] =$filename;
$document->column_fields["filetype"] ="application/pdf";
$document->column_fields["filelocationtype"]="I";
$document->column_fields["fileversion"] = 1;
$document->column_fields["filestatus"] = 1;
$document->column_fields["filesize"]=169758;
//$document->column_fields["date"]=$date;
//$document->column_fields["folderid"]=$folder;
$document->column_fields["assigned_user_id"] = $current_user->id;
$document->saveentity("Documents");

$adb->pquery("Insert into vtiger_seattachmentsrel values (?,?)",array($document->id,$att_id));

    if($recordid!='')
    {
        $adb->pquery("Insert into vtiger_senotesrel
            values (?,?)",array($recordid,$document->id));
		
  }}
       include_once ("modules/$origin_module/$origin_module.php");
     $focus2 = CRMEntity::getInstance($origin_module);
     $focus2->retrieve_entity_info($recordid, $origin_module);
   
     $allparameters = array();

     foreach ($target_fields as $key => $value) {
         $foundValues = array();

         if (!empty($value['value'])) {
             $fields[$key] = $value['value'];

         } else {
             if (isset($value['listFields']) && !empty($value['listFields'])) {
                 for ($i = 0; $i < count($value['listFields']); $i++) {
                     $foundValues[] = $focus2->column_fields[$value['listFields'][$i]];
                 }

             }
             elseif (isset($value['relatedFields']) && !empty($value['relatedFields'])) {
                 for ($i = 0; $i < count($value['relatedFields']); $i++) {
                        $relInformation = $value['relatedFields'][$i];
                        $relModule = $relInformation['relmodule'];
                        $linkField = $relInformation['linkfield'];
                        $fieldName = $relInformation['fieldname'];
                        $otherid = $focus2->column_fields[$linkField];

                     if (!empty($otherid)) {
                         include_once "modules/$relModule/$relModule.php";
                         $otherModule = CRMEntity::getInstance($relModule);
                         $otherModule->retrieve_entity_info($otherid, $relModule);

                         $foundValues[] = $otherModule->column_fields[$fieldName];

                     }
                 }
             }

      $fields[$key] = implode($value['delimiter'], $foundValues);
      
         }
    }

//____________
for ($j = 0; $j < $num_rows; $j++)
{
if($tipo=='Master Detail'){      
$recordidADD = $adb->query_result($count,$j,'adocdetailid');
  
     $focus1 = CRMEntity::getInstance("cbMap");
     $focus1->retrieve_entity_info($mapIDadd, "cbMap");
     $origin_module = $focus1->getMapOriginModule();
     $target_module = $focus1->getMapTargetModule();   
     $target_fields = $focus1->readMappingType();
      
     include_once ("modules/$origin_module/$origin_module.php");
     $focus2 = CRMEntity::getInstance($origin_module);
     $focus2->retrieve_entity_info($recordidADD, $origin_module);
   
     $allparameters = array();

     foreach ($target_fields as $key => $value) {
         $key1=$key.($j+1);
         $foundValues = array();

         if (!empty($value['value'])) {
             $key = $value['value'];

         } else {
             if (isset($value['listFields']) && !empty($value['listFields'])) {
                 for ($i = 0; $i < count($value['listFields']); $i++) {
                     $foundValues[] = $focus2->column_fields[$value['listFields'][$i]];
                 }

             }
             elseif (isset($value['relatedFields']) && !empty($value['relatedFields'])) {
                 for ($i = 0; $i < count($value['relatedFields']); $i++) {
                        $relInformation = $value['relatedFields'][$i];
                        $relModule = $relInformation['relmodule'];
                        $linkField = $relInformation['linkfield'];
                        $fieldName = $relInformation['fieldname'];
                        $otherid = $focus2->column_fields[$linkField];

                     if (!empty($otherid)) {
                         include_once "modules/$relModule/$relModule.php";
                         $otherModule = CRMEntity::getInstance($relModule);
                         $otherModule->retrieve_entity_info($otherid, $relModule);

                         $foundValues[] = $otherModule->column_fields[$fieldName];

                     }
                 }
             }

      $fields[$key1] = implode($value['delimiter'], $foundValues);
     
      
         }
        
}}

}


$pdf=new FPDM($pdfName);
$pdf->Load($fields,true);
$pdf->Merge();
$filename=$att_id.'_'.$filename;
$vlera=$pdf->Output("$upload_file_path"."$filename","S");   
$saveasfile="$upload_file_path"."$filename";

$myfile=fopen($saveasfile,'w');
if($myfile)
 {
    fwrite($myfile,$vlera);   
 }
     
$log->debug('before_$response');
$response['pdfURL'] = "$site_URL/"."$upload_file_path"."$filename";
$log->debug('after_$response ');
$log->debug($response);
 echo json_encode($response, true);
 
  }
  //__________________________________________________________________________________
  else if ($tipo=='Multiple'){
     
 //___________________________________________________    
for ($t=0;$t<$num_rows;$t++)
 { 
     $recordid = $adb->query_result($count,0,'adocmasterid');
   

     $focus1 = CRMEntity::getInstance("cbMap");
     $focus1->retrieve_entity_info($mapIDadm, "cbMap");
   
     $origin_module = $focus1->getMapOriginModule();
     
     $target_module = $focus1->getMapTargetModule();
   
     $target_fields = $focus1->readMappingType();
   
       include_once ("modules/$origin_module/$origin_module.php");
     $focus2 = CRMEntity::getInstance($origin_module);
     $focus2->retrieve_entity_info($recordid, $origin_module);
   
     $allparameters = array();

     foreach ($target_fields as $key => $value) {
         $foundValues = array();

         if (!empty($value['value'])) {
             $key = $value['value'];

         } else {
             if (isset($value['listFields']) && !empty($value['listFields'])) {
                 for ($i = 0; $i < count($value['listFields']); $i++) {
                     $foundValues[] = $focus2->column_fields[$value['listFields'][$i]];
                 }

             }
             elseif (isset($value['relatedFields']) && !empty($value['relatedFields'])) {
                 for ($i = 0; $i < count($value['relatedFields']); $i++) {
                        $relInformation = $value['relatedFields'][$i];
                        $relModule = $relInformation['relmodule'];
                        $linkField = $relInformation['linkfield'];
                        $fieldName = $relInformation['fieldname'];
                        $otherid = $focus2->column_fields[$linkField];

                     if (!empty($otherid)) {
                         include_once "modules/$relModule/$relModule.php";
                         $otherModule = CRMEntity::getInstance($relModule);
                         $otherModule->retrieve_entity_info($otherid, $relModule);

                         $foundValues[] = $otherModule->column_fields[$fieldName];

                     }
                 }
             }

      $fields[$t][$key] = implode($value['delimiter'], $foundValues);
      
         }
    }
 $recordidDetail=$adb->query_result($count,$t,'adocdetailid'); 
 $filename="$t.DDT_".date('Y-m-d').".pdf";
 $upload_file_path = decideFilePath();
 $saveasfile=$upload_file_path."/$filename.pdf";
 if($attach=='Sempre' || ($attach=='Scelta Utente' && $us==1)){

  $att_id = $adb->getUniqueID("vtiger_crmentity");
  $sql1 = "insert into vtiger_crmentity
    (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime)
    values(?, ?, ?, ?, ?, ?, ?)";
  
$params1 = array($att_id, 1, 1, " Attachment", '', date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
$adb->pquery($sql1, $params1);
$sql2="insert into vtiger_attachments(attachmentsid, name, description, type,path) values(?, ?, ?, ?,?)";
$params2 = array($att_id, $filename, '', 'application/pdf',$upload_file_path);
$result=$adb->pquery($sql2, $params2);
 
$document=new Documents();
$document->column_fields["notes_title"]="$filename";
$document->column_fields["filename"] =$filename;
$document->column_fields["filetype"] ="application/pdf";
$document->column_fields["filelocationtype"]="I";
$document->column_fields["fileversion"] = 1;
$document->column_fields["filestatus"] = 1;
$document->column_fields["filesize"]=169758;
//$document->column_fields["date"]=$date;
//$document->column_fields["folderid"]=$folder;
$document->column_fields["assigned_user_id"] = $current_user->id;
$document->saveentity("Documents");

$adb->pquery("Insert into vtiger_seattachmentsrel values (?,?)",array($document->id,$att_id));

    if($recordid!='')
    {
        $adb->pquery("Insert into vtiger_senotesrel
            values (?,?)",array($recordid,$document->id));
		
  }
}
for ($j = 0; $j < count($recid); $j++)
{ 
    
     $recordid = $recid[$j];
     $focus1 = CRMEntity::getInstance("cbMap");
     $focus1->retrieve_entity_info($mapIDadd, "cbMap");
   
     $origin_module = $focus1->getMapOriginModule();
     $target_module = $focus1->getMapTargetModule();
     $target_fields = $focus1->readMappingType();
      
     include_once ("modules/$origin_module/$origin_module.php");
     $focus2 = CRMEntity::getInstance($origin_module);
     $log->debug('hasa123'.$recordidADD.$origin_module);
     
     $focus2->retrieve_entity_info($recordidDetail, $origin_module);

     $allparameters = array();

     foreach ($target_fields as $key => $value) {
         $foundValues = array();

         if (!empty($value['value'])) {
             $key = $value['value'];

         } else {
             if (isset($value['listFields']) && !empty($value['listFields'])) {
                 for ($i = 0; $i < count($value['listFields']); $i++) {
                     $foundValues[] = $focus2->column_fields[$value['listFields'][$i]];
                 }

             }
             elseif (isset($value['relatedFields']) && !empty($value['relatedFields'])) {
                 for ($i = 0; $i < count($value['relatedFields']); $i++) {
                        $relInformation = $value['relatedFields'][$i];
                        $relModule = $relInformation['relmodule'];
                        $linkField = $relInformation['linkfield'];
                        $fieldName = $relInformation['fieldname'];
                        $otherid = $focus2->column_fields[$linkField];

                     if (!empty($otherid)) {
                         include_once "modules/$relModule/$relModule.php";
                         $otherModule = CRMEntity::getInstance($relModule);
                         $otherModule->retrieve_entity_info($otherid, $relModule);

                         $foundValues[] = $otherModule->column_fields[$fieldName];

                     }
                                 
                 }
           
             }
   $log->debug('before $fields[key]');
   $key1=$key.'1';
   $fields[$t][$key1] = implode($value['delimiter'], $foundValues); 
       
                  }

             }      

 

 }

$pdf=new FPDM($pdfName);
$pdf->Load($fields[$t],true);
$pdf->Merge();
$filename=$att_id.'_'.$filename;
$upload_file_path = decideFilePath();
$saveasfile=$upload_file_path."/$filename.pdf";
$vlera=$pdf->Output("$upload_file_path"."$filename","S");   
$saveasfile="$upload_file_path"."$filename";
$myfile=fopen($saveasfile,'w');
if($myfile)
 {
    fwrite($myfile,$vlera);   
 }
 $cod=substr(md5("DDT_".date('Y-m-d')),6);
    $destPdf="DDT_".date('Y-m-d').'_'.$cod.'.zip';
    $log->debug('before_$response');
    $response['pdfURL'] = "$site_URL"."$upload_file_path"."$filename";

    $zip = new ZipArchive;
   $zip->open($destPdf,ZipArchive::CREATE);
    $zip->addFile("$upload_file_path/$filename","$filename");
   $zip->close();
//   global $root_directory;
//  exec("cd $root_directory; rm -rf $destPdf");
   //$fields=array();

   
}
  
    $response1['pdfURL'] = $destPdf;  
//    header('Content-Type: text/plain' );
//    header("Content-Disposition: attachment;filename=$response1");
//    header("Location: http://$site_URL/$response1");
   return $response1;
 
  }
}
?>