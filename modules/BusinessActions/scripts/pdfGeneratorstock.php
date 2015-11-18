<?php
function pdfGeneratorstock($request){

ini_set('display_errors', 'On');
ini_set('error_reporting', 'On');
include_once('data/CRMEntity.php');
include_once('modules/Map/Map.php');
include_once('modules/Documents/Documents.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
//require_once('include/fpdm/fpdm.php');
require_once('config.inc.php');
shell_exec("chmod 777 -R storage/ ");
global $adb,$log,$current_user,$site_URL;

// $str= $argv[1];
// $importantStuff = explode('recordid=', $str);
//
// $record = $importantStuff[1];

//$log = & LoggerManager::getLogger("index");
$current_user = new Users();
$result = $current_user->retrieveCurrentUserInfoFromFile(1);
global $adb, $log, $current_user;
//$request = array();
//if (isset($argv) && !empty($argv)) {
//    for ($i = 1; $i < count($argv); $i++) {
//        list($key, $value) = explode("=", $argv[$i]);
//        $request[$key] = $value;
//    }
//}
$recordid1 = $request['recordid'];
$recordid = $request['recordid'];
$us=$request['confirm'];
$pdfUrl_barcodes=$request['pdfURL_sequencer'];
$urls=array();

$pdfUrl_barcodes_array=explode(',',$pdfUrl_barcodes);
for($p=0;$p<sizeof($pdfUrl_barcodes_array);$p++){
    if($pdfUrl_barcodes_array[$p]!='')
        $urls[]="$pdfUrl_barcodes_array[$p]";
}
//$mapid = $request['mapid'];
$recid = explode(',', $recordid);

if(getSalesEntityType($recid[0])=='Project'){
 $match="select *, projectid as adocmasterid from vtiger_project join vtiger_crmentity on crmid=projectid where deleted=0
  and projectid in ($recordid)  ";
 //and substatus like '%pickandpay%'
 $mod='Project';
}
else{
$match="SELECT * 
FROM vtiger_adocdetail join vtiger_crmentity on crmid=adocdetailid
JOIN vtiger_adocmaster ON vtiger_adocmaster.adocmasterid = vtiger_adocdetail.adoctomaster
WHERE adoctomaster =$recid[0] and deleted=0";
$mod='Adocmaster';
}
 $count = $adb->query($match);  
 $num_rows = $adb->num_rows($count); 
 if($mod=='Project'){
 $b='Ricevuta';
 $sp=$adb->query_result($count,0,'sitoprovenienza');
 $cond=" and sitoprovenienza='$sp'";
 }
 else {
   $b=$adb->query_result($count,0,'causaleadm');
  $cond="and SUBSTRING_INDEX(righedaa,'..',-1)>=$num_rows and SUBSTRING_INDEX(righedaa,'..',1)<=$num_rows";
 }
 $docSettings="select * from vtiger_docsettings join vtiger_crmentity on crmid=docsettingsid where deleted=0 and causale='$b' $cond";
 if($b=='Ricevuta' && $adb->num_rows($adb->query($docSettings))==0) 
 {
  $docSettings="select * from vtiger_docsettings join vtiger_crmentity on crmid=docsettingsid where deleted=0 and causale='$b' and sitoprovenienza='ePrice'";   
 }
 $count1 = $adb->query($docSettings);
 $num_rows1=$adb->num_rows($count1);
 if($num_rows1!=0){
 $pdfName=$adb->query_result($count1,0,'nometemp');
 $mapIDadd=$adb->query_result($count1,0,'linktomapdetail');
 $mapIDadm=$adb->query_result($count1,0,'linktomapmaster');
 $mapgrpby=$adb->query_result($count1,0,'doc_group_by');
 $attach=$adb->query_result($count1,0,'autoattach');
 $tipo=$adb->query_result($count1,0,'tipo');
//map for group by field
  if (!empty($mapgrpby)) {
     $focus11 = CRMEntity::getInstance("Map");
     $focus11->retrieve_entity_info($mapgrpby, "Map");
     $targetgrp = $focus11->getMapSQL();
     
      $allelements = array("CURRENT_USER" => $current_user->id, "CURRENT_RECORD" => $recordid);
        foreach ($allelements as $elem => $value) {
            $pos_el = strpos($targetgrp, $elem);
            if ($pos_el) {
                $targetgrp = str_replace($elem, $value, $targetgrp);
            }
        }
        $queryExec = $adb->query($targetgrp);
        $nrFields = $adb->num_fields($queryExec);
        for ($f = 0; $f < $nrFields; $f++) {
            $fieldDetails = $adb->field_name($queryExec, $f);
            $fieldname = $fieldDetails->name;
            $groupFields[] = $fieldname;
        }
    } else {
        $groupFields[] = '';
    }
    $fieldnameStr = implode(",", $groupFields);
 }
 
  if($tipo=='Master Detail' || $tipo=='Piato'){
      
     $recordid = $adb->query_result($count,0,'adocmasterid');
     $praticaid = $adb->query_result($count,0,'praticaid');
     //filename
     $filename=$praticaid."_".date('Y-m-d')."_Ricevuta_Reso.pdf";
$upload_file_path = decideFilePath();
$saveasfile=$upload_file_path."/$filename.pdf";

     $focus1 = CRMEntity::getInstance("Map");
     $focus1->retrieve_entity_info($mapIDadm, "Map");
   
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
             $key = $value['value'];

         } else {
             if (isset($value['listFields']) && !empty($value['listFields'])) {
                 for ($i = 0; $i < count($value['listFields']); $i++) {
                      if($key=='data_ritiro')
                           $foundValues[] = date("d/m/Y",strtotime($focus2->column_fields[$value['listFields'][$i]]));
                          else
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
                       if($key=='data_ritiro')
                           $foundValues[] = date("d/m/Y",strtotime($otherModule->column_fields[$fieldName]));
                       else
                         $foundValues[] = $otherModule->column_fields[$fieldName];

                     }
                 }
             }

      $fields[$key] = implode($value['delimiter'], $foundValues);
      
         }
    }

    if($mod=='Project'){
  
 $count=$adb->query("select *, projectid as adocdetailid from vtiger_project join vtiger_crmentity on crmid=projectid where deleted=0
  and projectid in ($recordid1)  group by $fieldnameStr");
 $num_rows=$adb->num_rows($count);
 //and substatus like '%pickandpay%'
}
for ($j = 0; $j < $num_rows; $j++)
{
  $grpby=$adb->query_result($count,$j,"$fieldnameStr");
  $cc=$adb->query("select *, projectid as adocdetailid from vtiger_project join vtiger_crmentity on crmid=projectid where deleted=0
  and projectid in ($recordid1) and $fieldnameStr='$grpby' ");
  $fields["quantity".($j+1)]=$adb->num_rows($cc);

if($tipo=='Master Detail'){      
$recordidADD = $adb->query_result($count,$j,'adocdetailid');
  
     $focus1 = CRMEntity::getInstance("Map");
     $focus1->retrieve_entity_info($mapIDadd, "Map");
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
$pdf->set_modes('check',true); //Compare xref table offsets with objects offsets in the pdf file
$pdf->set_modes('halt',false);
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
$urls[]="$site_URL/"."$upload_file_path"."$filename";
$log->debug('after_$response ');
$log->debug($response);
if(sizeof($urls)<=1)
 return $response;
 
  }
  //__________________________________________________________________________________
  else if ($tipo=='Multiple'){
    if($mod=='Project'){
  
 $count=$adb->query("select *, projectid as adocmasterid,projectid as adocdetailid from vtiger_project join vtiger_crmentity on crmid=projectid where deleted=0
  and projectid in ($recordid1)   group by $fieldnameStr");
 $num_rows=$adb->num_rows($count);
 //and substatus like '%pickandpay%'
}   
 //___________________________________________________    
for ($t=0;$t<$num_rows;$t++)
 { 
     $recordid = $adb->query_result($count,0,'adocmasterid');
   $grpby=$adb->query_result($count,$t,"$fieldnameStr");
  $cc=$adb->query("select *, projectid as adocdetailid from vtiger_project join vtiger_crmentity on crmid=projectid where deleted=0
  and projectid in ($recordid1) and $fieldnameStr='$grpby'");
  $fields[$t]["quantity1"]=$adb->num_rows($cc);

     $focus1 = CRMEntity::getInstance("Map");
     $focus1->retrieve_entity_info($mapIDadm, "Map");
   
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
                      if($key=='data_ritiro')
                     $foundValues[] = date("d/m/Y",strtotime($focus2->column_fields[$value['listFields'][$i]]));
                      else 
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
if($key=='data_ritiro')
                           $foundValues[] = date("d/m/Y",strtotime($otherModule->column_fields[$fieldName]));
                       else
                         $foundValues[] = $otherModule->column_fields[$fieldName];

                     }
                 }
             }

      $fields[$t][$key] = implode($value['delimiter'], $foundValues);
      
         }
    }
 $recordidDetail=$adb->query_result($count,$t,'adocdetailid');
  $praticaid=$adb->query_result($count,$t,'praticaid'); 
 $filename="$t".$praticaid.'_'.date('Y-m-d')."_Ricevuta_Reso.pdf";
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
for ($j = 0; $j < 1; $j++)
{ 
    
     $recordid = $recid[$j];
     $focus1 = CRMEntity::getInstance("Map");
     $focus1->retrieve_entity_info($mapIDadd, "Map");
   
     $origin_module = $focus1->getMapOriginModule();
     $target_module = $focus1->getMapTargetModule();
     $target_fields = $focus1->readMappingType();
      
     include_once ("modules/$origin_module/$origin_module.php");
     $focus2 = CRMEntity::getInstance($origin_module);
     
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

$log->debug($fields[$t]);
$pdf=new FPDM($pdfName);
$pdf->set_modes('check',true); //Compare xref table offsets with objects offsets in the pdf file
$pdf->set_modes('halt',false);
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
 $cod=substr(md5("DDT_".date('Y-m-d H:i:s')),6);
    $destPdf=$praticaid."_".date('Y-m-d').'_Ricevuta_Reso_'.$cod.'.zip';
    $log->debug('before_$response');
    $response['pdfURL'] = "$site_URL"."$upload_file_path"."$filename";

    $zip = new ZipArchive;
   $zip->open($destPdf,ZipArchive::CREATE);
    $zip->addFile("$upload_file_path/$filename","$filename");
    $urls[]="$site_URL/$saveasfile";
   $zip->close();
//   global $root_directory;
//  exec("cd $root_directory; rm -rf $destPdf");
   //$fields=array();

   
}

    $response1['pdfURL'] = $site_URL."/".$destPdf;  
//    header('Content-Type: text/plain' );
//    header("Content-Disposition: attachment;filename=$response1");
//    header("Location: http://$site_URL/$response1");
   
 
  }
   
if(sizeof($urls)>0){

    $cod=substr(md5("DDT_".date('Y-m-d H:i:s')),6);
    $zip_location="DDT_Barcodes_".date('Y-m-d').'_'.$cod.'.zip';
    
    $zip_urls = new ZipArchive;
    $zip_urls->open($zip_location,ZipArchive::CREATE);
    for($fs=0;$fs<sizeof($urls);$fs++){
        $file_name_arr=explode("/",$urls[$fs]);
       $file_name=$file_name_arr[sizeof($file_name_arr)-1];
       $pdfUrl_barcodes_path_array=explode('eprice/',$urls[$fs]);
       $zip_urls->addFile("$pdfUrl_barcodes_path_array[1]","$file_name");
       }
    $zip_urls->close();
    $response1['pdfURL'] = $site_URL."/".$zip_location;  
}
 return $response1;
}
?>
