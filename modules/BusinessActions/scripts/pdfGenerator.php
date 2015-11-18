<?php
function pdfGenerator($request){
ini_set('display_errors', 'On');
ini_set('error_reporting', 'On');
include_once('data/CRMEntity.php');
include_once('modules/Map/Map.php');
include_once('modules/Documents/Documents.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
//require_once('include/fpdm/fpdm.php');
require_once('config.inc.php');

 
global $adb,$log,$current_user,$site_URL;

// $str= $argv[1];
// $importantStuff = explode('recordid=', $str);
//
// $record = $importantStuff[1];

//$log = & LoggerManager::getLogger("index");
$current_user = new Users();
$result = $current_user->retrieveCurrentUserInfoFromFile(1);
global $adb, $log, $current_user;
/*$request = array();
if (isset($argv) && !empty($argv)) {
    for ($i = 1; $i < count($argv); $i++) {
        list($key, $value) = explode("=", $argv[$i]);
        $request[$key] = $value;
    }
}*/
$recordid = $request['recordid'];
$us=$request['confirm'];
//$mapid = $request['mapid'];
$recid = explode(',', $recordid);
for($r=0;$r<count($recid);$r++){
    $fields=array();
$match="SELECT * 
FROM vtiger_adocdetail join vtiger_crmentity on crmid=adocdetailid
JOIN vtiger_adocmaster ON vtiger_adocmaster.adocmasterid = vtiger_adocdetail.adoctomaster
WHERE adoctomaster =$recid[$r] and deleted=0";

 $count = $adb->query($match);  
 $num_rows = $adb->num_rows($count); 
 $b=$adb->query_result($count,0,'causaleadm');
 $pp=$adb->query_result($count,0,'partnerprim');
if($b==''|| $b==0) 
$b=$adb->query_result($count,0,'docsetting');
 $docSettings="select * from vtiger_docsettings join vtiger_crmentity on crmid=docsettingsid where deleted=0 and causale='$b' and partnerprimario='$pp' and SUBSTRING_INDEX(righedaa,'..',-1)>=$num_rows and SUBSTRING_INDEX(righedaa,'..',1)<=$num_rows";
 
 $count1 = $adb->query($docSettings);
 $num_rows1=$adb->num_rows($count1);
 if($num_rows1!=0){
 $pdfName=$adb->query_result($count1,0,'nometemp');
 $mapIDadd=$adb->query_result($count1,0,'linktomapdetail');
 $mapIDadm=$adb->query_result($count1,0,'linktomapmaster');
  $mapgrpby=$adb->query_result($count1,0,'doc_group_by');
 $attach=$adb->query_result($count1,0,'autoattach');
 $tipo=$adb->query_result($count1,0,'tipo');
 $groupFields=array();
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
       $adocmasterno = $adb->query_result($count,0,'nrddtpp');
       $partnerprim = $adb->query_result($count,0,'partnerprim');
       if($partnerprim=='TEKNEMA') $pp='TN';
       else if($partnerprim=='TECNOASSIST') $pp='TA';
       else $pp='';
     //filename
     $filename="DDT_$pp_".$adocmasterno.'_'.date('Y-m-d').".pdf";
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
                      if($key=='issuedate' || $key=='datacreazione')
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

                         $foundValues[] = $otherModule->column_fields[$fieldName];

                     }
                 }
             }

      $fields[$key] = implode($value['delimiter'], $foundValues);
      
         }
    }

//____________
     $count=$adb->query("SELECT * 
FROM vtiger_adocdetail join vtiger_crmentity on crmid=adocdetailid
JOIN vtiger_adocmaster ON vtiger_adocmaster.adocmasterid = vtiger_adocdetail.adoctomaster
WHERE adoctomaster =$recid[$r] and deleted=0 group by $fieldnameStr");
 $num_rows=$adb->num_rows($count);
for ($j = 0; $j < $num_rows; $j++)
{
    $grpby=$adb->query_result($count,$j,"$fieldnameStr");
  $cc=$adb->query("SELECT * 
FROM vtiger_adocdetail join vtiger_crmentity on crmid=adocdetailid
JOIN vtiger_adocmaster ON vtiger_adocmaster.adocmasterid = vtiger_adocdetail.adoctomaster
WHERE adoctomaster =$recid[$r] and deleted=0 and $fieldnameStr='$grpby' ");
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
//
//if($num_rows>=8 && $num_rows <=10){
//    $pdfName='include/fpdm/template10.pdf';
//}elseif($num_rows>10 && $num_rows <=15){
//    $pdfName='include/fpdm/template15.pdf';
//}
//else{
//    $pdfName='include/fpdm/template.pdf';
//}
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
 fclose($myfile);    
 
$log->debug('before_$response');
$response['pdfURL'] = "$site_URL/"."$upload_file_path"."$filename";
$log->debug('after_$response ');
$log->debug($response);
 //echo json_encode($response, true);
 //echo $response['pdfURL'];
 $files_to_zip[]="$upload_file_path"."$filename";
  }
  //__________________________________________________________________________________
  else if ($tipo=='Multiple'){
     
 //___________________________________________________   
       $count=$adb->query("SELECT * 
FROM vtiger_adocdetail join vtiger_crmentity on crmid=adocdetailid
JOIN vtiger_adocmaster ON vtiger_adocmaster.adocmasterid = vtiger_adocdetail.adoctomaster
WHERE adoctomaster =$recid[$r] and deleted=0 group by $fieldnameStr");
 $num_rows=$adb->num_rows($count);
for ($t=0;$t<$num_rows;$t++)
 { 
     $recordid = $adb->query_result($count,0,'adocmasterid');
    $grpby=$adb->query_result($count,$t,"$fieldnameStr");
  $cc=$adb->query("SELECT * 
FROM vtiger_adocdetail join vtiger_crmentity on crmid=adocdetailid
JOIN vtiger_adocmaster ON vtiger_adocmaster.adocmasterid = vtiger_adocdetail.adoctomaster
WHERE adoctomaster =$recid[$r] and deleted=0 and $fieldnameStr='$grpby'");
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
                      if($key=='issuedate' || $key=='datacreazione')
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

                         $foundValues[] = $otherModule->column_fields[$fieldName];

                     }
                 }
             }

      $fields[$t][$key] = implode($value['delimiter'], $foundValues);
      
         }
    }
 $recordidDetail=$adb->query_result($count,$t,'adocdetailid'); 
 $adocmasterno=$adb->query_result($count,$t,'nrddtpp');
 $partnerprim = $adb->query_result($count,$t,'partnerprim');
  if($partnerprim=='TEKNEMA') $pp='TN';
       else if($partnerprim=='TECNOASSIST') $pp='TA';
       else $pp='';
 $filename="$t.DDT_$pp_".$adocmasterno.'_'.date('Y-m-d').".pdf";
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
     $focus1 = CRMEntity::getInstance("Map");
     $focus1->retrieve_entity_info($mapIDadd, "Map");
   
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
 fclose($myfile);
 $cod=substr(md5("DDT_".date('Y-m-d')),6);
    $destPdf="DDT_$pp_".$adocmasterno.'_'.date('Y-m-d').'_'.$cod.'.zip';
    $log->debug('before_$response');
    $response['pdfURL'] = "$site_URL"."$upload_file_path"."$filename";

    $zip = new ZipArchive;
   $zip->open($destPdf,ZipArchive::CREATE);
    $zip->addFile("$upload_file_path/$filename","$filename");
   $zip->close();
   
   $files_to_zip[]="$upload_file_path/$filename";
//   global $root_directory;
//  exec("cd $root_directory; rm -rf $destPdf");
   //$fields=array();

   
}
  
   /* $response1['pdfURL'] = $destPdf;  
    header('Content-Type: text/plain' );
    header("Content-Disposition: attachment;filename=$response1");
    header("Location: http://$site_URL/$response1");
    //echo json_encode($response1,true);
 echo  $response1['pdfURL'] ;*/
  }
}
if(sizeof($files_to_zip)==1)
{    //echo "$site_URL/"."$files_to_zip[0]";
 $response1['pdfURL'] = "$site_URL/"."$files_to_zip[0]";
return $response1;
}else
{
     $cod=substr(md5("DDT_".date('Y-m-d H:i:s')),6);
    $destPdf="DDT_$pp_".$adocmasterno.'_'.date('Y-m-d').'_'.$cod.'.zip';
    $log->debug('before_$response');
 
    $zip = new ZipArchive;
    $zip->open($destPdf,ZipArchive::CREATE);
   
   for($f=0;$f<sizeof($files_to_zip);$f++){
       $file_name_arr=explode("/",$files_to_zip[$f]);
       $file_name=$file_name_arr[sizeof($file_name_arr)-1];
    $zip->addFile("$files_to_zip[$f]","$file_name");
   }
   $zip->close();
   $response1['pdfURL'] ="$site_URL/"."$destPdf"; 
/*    header('Content-Type: text/plain' );
    header("Content-Disposition: attachment;filename=$destPdf");
    header("Location: $site_URL/$destPdf");
    //echo json_encode($response1,true);
*/$log->Debug('aldis ' . $response1['pdfURL'] );
 return $response1 ;
   
}
}
?>
