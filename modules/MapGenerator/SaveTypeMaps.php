<?php
 
include_once ("modules/cbMap/cbMap.php");
require_once ('data/CRMEntity.php');
require_once ('include/utils/utils.php');

global $root_directory, $log;
$Data = array();

//  var_dump($_REQUEST, true);
// exit();

$MapName = $_POST['MapName']; // stringa con tutti i campi scelti in selField1
$MapType = "Mapping"; // stringa con tutti i campi scelti in selField1
$Data = $_POST['ListData']; // nome della vista

if (empty($MapName)) {
    echo "Missing the name of map Can't save";
    return;
}
if (empty($MapType)) {
    $MapType = "Mapping";
}

 if (!empty($Data)) {
     
     $DataDecode = json_decode($Data, true);
//      print_r($DataDecode);
//      exit();
     $countarray=(count($DataDecode)-1);
     $xml = new DOMDocument("1.0");
     $root = $xml->createElement("map");
     $xml->appendChild($root);
     //$name = $xml->createElement("name");
     $target = $xml->createElement("originmodule");
     $targetid = $xml->createElement("originid");
     $targetidText = $xml->createTextNode("");//$DataDecode[0]['FirstModuleval']
     $targetid->appendChild($targetidText);
     $targetname = $xml->createElement("originname");
     $targetnameText = $xml->createTextNode($DataDecode[0]['FirstModuleval']);
     $targetname->appendChild($targetnameText);
     $target->appendChild($targetid);
     $target->appendChild($targetname);
     
     $origin = $xml->createElement("targetmodule");
     $originid = $xml->createElement("targetid");
     $originText = $xml->createTextNode("");//$DataDecode[0]['SecondModuleval']
     $originid->appendChild($originText);
     $originname = $xml->createElement("targetname");
     $originnameText = $xml->createTextNode(preg_replace('/\s+/', '',explode(";",$DataDecode[0]['SecondModuleval'])[1]));
     $originname->appendChild($originnameText);
     $origin->appendChild($originid);
     $origin->appendChild($originname);
     $fields = $xml->createElement("fields");
    // $hw=0;
     for($i=0;$i<=$countarray;$i++){
         //get target field name
       
                 $field = $xml->createElement("field");
                 $fieldname = $xml->createElement("fieldname");
                 $fieldnameText = $xml->createTextNode(explode(':',$DataDecode[$i]['FirstFieldval'])[1] );
                 $fieldname->appendChild($fieldnameText);
                 $field->appendChild($fieldname);
                 
                 $fieldID = $xml->createElement("fieldID");
                 $fieldideText = $xml->createTextNode("");//$DataDecode[$i]['FirstFieldval']
                 $fieldID->appendChild($fieldideText);         
                 $field->appendChild($fieldID);
                // echo $i;
                 if ($DataDecode[$i]['SecondFieldtext']=="Default-Value")
                 {
                     $value = $xml->createElement("value");
                     $valueText = $xml->createTextNode($DataDecode[$i]['SecondFieldval']);
                     $value->appendChild($valueText);
                     $field->appendChild($value);
                 }         
                 //target module fields
                 $Orgfields = $xml->createElement("Orgfields");
                 $field->appendChild($Orgfields);
                
                 if ($DataDecode[$i]['SecondFieldtext']=="Default-Value")
                 {
                     $OrgRelfield= $xml->createElement("Orgfield");
                     
                     $OrgRelfieldName = $xml->createElement("OrgfieldName");
                     $OrgRelfieldNameText= $xml->createTextNode("");
                     $OrgRelfieldName->appendChild($OrgRelfieldNameText);
                     $OrgRelfield->appendChild($OrgRelfieldName); 
                    
                     $OrgfieldID = $xml->createElement("OrgfieldID");
                     $OrgfieldIDText= $xml->createTextNode("");
                     $OrgfieldID->appendChild($OrgfieldIDText);
                     $OrgRelfield->appendChild($OrgfieldID); 
                     
                     $Orgfields->appendChild($OrgRelfield);
                    
                 }else
                 {
                     $OrgRelfield= $xml->createElement("Orgfield");
                     
                     $OrgRelfieldName = $xml->createElement("OrgfieldName");
                     $OrgRelfieldNameText= $xml->createTextNode(explode(":",$DataDecode[$i]['SecondFieldval'])[1]);
                     $OrgRelfieldName->appendChild($OrgRelfieldNameText);
                     $OrgRelfield->appendChild($OrgRelfieldName);
                     
                     $OrgfieldID = $xml->createElement("OrgfieldID");
                     $OrgfieldIDText= $xml->createTextNode("");
                     $OrgfieldID->appendChild($OrgfieldIDText);
                     $OrgRelfield->appendChild($OrgfieldID);
                     
                     $Orgfields->appendChild($OrgRelfield);
                 }
                 
                 
                 $del = $xml->createElement("delimiter");
                 $delText= $xml->createTextNode("");
                 $del->appendChild($delText);
                 $Orgfields->appendChild($del);
                 $fields->appendChild($field);
         }
        
       
        
     
     //$root->appendChild($name);
     $root->appendChild($target);
     $root->appendChild($origin);
     $root->appendChild($fields);
     $xml->formatOutput = true;
     
     include_once('modules/cbMap/cbMap.php');
     $focust = new cbMap();
     $focust->column_fields['assigned_user_id'] = 1;
     $focust->column_fields['mapname'] = $MapName;
     $focust->column_fields['content']=$xml->saveXML();;
     $focust->column_fields['maptype'] =$MapType;
     $log->debug(" we inicialize value for insert in database ");
     if (!$focust->saveentity("cbMap")) {
         //echo $focust->id;
         echo "succes!! the map is created ";
         $log->debug("succes!! the map is created ");
     } else {
         //echo focus->id;
         echo "Error!! something went wrong";
         $log->debug("Error!! something went wrong");
     }
     
//     $ddd=$xml->saveXML();
//     $fp = fopen('C:\Users\Edmondi\Desktop\prova.xml', 'w');
//     fwrite($fp, $ddd);   
//     fwrite($fp, "I=".$hw); 
//     fclose($fp);
   
   
 }//end of if (! empty($Data)) 
