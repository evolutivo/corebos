<?php
include_once ("modules/cbMap/cbMap.php");
require_once ('data/CRMEntity.php');
require_once ('include/utils/utils.php');

global $root_directory, $log;
$Data = array();

$MapName = $_POST['MapName']; // stringa con tutti i campi scelti in selField1
$MapType = $_POST['MapType']; // stringa con tutti i campi scelti in selField1
$Data = $_POST['Data']; // nome della vista

if (empty($MapName)) {
    echo "Missing the name of map Can't save";
    return;
}
if (empty($MapType)) {
    $MapType = "Mapping";
}

 if (! empty($Data)) {
     $DataDecode = json_decode($Data, true);
//      foreach ($DataDecode as $dddddd)
//          print_r($dddddd);
//      exit();
    
        $xml = new DOMDocument("1.0");
        $root = $xml->createElement("map");
        $xml->appendChild($root);
        
        // $name = $xml->createElement("name");
        $target = $xml->createElement("originmodule");
        $targetID = $xml->createElement("originid");
        $targetidText = $xml->createTextNode($DataDecode[0]['FirstModuleval']);
        $targetID->appendChild($targetidText);
        
        $targetname = $xml->createElement("originname");
        $targetnameText = $xml->createTextNode($DataDecode[0]['FirstModuletxt']);
        $targetname->appendChild($targetnameText);
       // $target->appendChild($targetid);
        $target->appendChild($targetname);
        
        $origin = $xml->createElement("targetmodule");
        $originid = $xml->createElement("targetid");
        $originText = $xml->createTextNode($DataDecode[0]['SecondModuleval']);
        $originid->appendChild($originText);
        
        $originname = $xml->createElement("targetname");
        $originnameText = $xml->createTextNode($DataDecode[0]['SecondModuletxt']);
        $originname->appendChild($originnameText);
       //$target->appendChild($originid);
        $origin->appendChild($originname);
        $fields = $xml->createElement("fields");        
        foreach($DataDecode as $datadecode ) {
           
               if ($datadecode["FirstModuletxt"]=="Default-Value")
               { 
                   
                        //get target field name
                    $field = $xml->createElement("field");
                    $fieldname = $xml->createElement("fieldname");
                    $fieldnameText = $xml->createTextNode($datadecode["FirstModuletxt"]);
                    $fieldname->appendChild($fieldnameText);
                    $field->appendChild($fieldname);
                    
                    $valueID = $xml->createElement("value");
                    $valueText = $xml->createTextNode($datadecode["SecondFieldval"]);
                    $valueID->appendChild($valueText);
                    $field->appendChild($valueID);
                    
                    $fieldID = $xml->createElement("fieldID");
                    $fieldideText = $xml->createTextNode($datadecode["FirstModuleval"]);
                    $fieldID->appendChild($fieldideText);
                    $field->appendChild($fieldID);
                    $Orgfields = $xml->createElement("Orgfields");
                     $fieldname = $xml->createElement("Orgfield");
                     $OrgfieldName = $xml->createElement("OrgfieldName");
                     $OrgfielText = $xml->createTextNode("");
                     $OrgfieldName->appendChild($OrgfielText);
                     $fieldname->appendChild($OrgfieldName);
                     $Orgfields->appendChild($fieldname);
                     $field->appendChild($Orgfields);
                    
               }else{
                  
                   $field = $xml->createElement("field");
                   $fieldname = $xml->createElement("fieldname");
                   $fieldnameText = $xml->createTextNode($datadecode["FirstModuletxt"]);
                   $fieldname->appendChild($fieldnameText);
                   $field->appendChild($fieldname);
                   
//                    $valueID = $xml->createElement("value");
//                    $valueText = $xml->createTextNode($datadecode["SecondFieldval"]);
//                    $valueID->appendChild($valueText);
//                    $field->appendChild($valueID);
                   
                   $fieldID = $xml->createElement("fieldID");
                   $fieldideText = $xml->createTextNode($datadecode["FirstModuleval"]);
                   $fieldID->appendChild($fieldideText);
                   $field->appendChild($fieldID);
                   
                   $Orgfields = $xml->createElement("Orgfields");
                   $fieldname = $xml->createElement("Orgfield");
                   
                   $OrgfieldName = $xml->createElement("OrgfieldName");
                   $OrgfielText = $xml->createTextNode($datadecode["SecondFieldtext"]);
                   $OrgfieldName->appendChild($OrgfielText);
                   $fieldname->appendChild($OrgfieldName);
                   
                   $OrgfieldName = $xml->createElement("OrgfieldID");
                   $OrgfielText = $xml->createTextNode($datadecode["SecondFieldval"]);
                   $OrgfieldName->appendChild($OrgfielText);
                   $fieldname->appendChild($OrgfieldName);
                   
                   $Orgfields->appendChild($fieldname);
                   $field->appendChild($Orgfields);
                   
               }       
//         $del = $xml->createElement("delimiter");
//         $delText = $xml->createTextNode($delimArr[$i]);
//         $del->appendChild($delText);
       
//         $strTarField = implode($delimArr[$i], $fldnamearr);
//         $strTarFldId = implode(",", $fldidarr);
    }
    $fields->appendChild($field);
//     $root->appendChild($target);
//     $root->appendChild($origin);
    $root->appendChild($fields);
    $xml->formatOutput = true;
     
    $ddd=$xml->saveXML();
    $fp = fopen('C:\Users\Edmondi\Desktop\lidn.xml', 'w');
    fwrite($fp, $ddd);
    fwrite($fp, 'mice');
    fclose($fp);
    print($xml->saveXML());  
    exit();
 }//end of if (! empty($Data)) 
//     $SaveasMapTextImput=$_POST['SaveasMapTextImput'];
//     if($SaveasMapTextImput=='')
//         $SaveasMapTextImput=$nameview;
//         if (empty($_POST["MapId"])){
//             $focust = new cbMap();
//             $focust->column_fields['assigned_user_id'] = 1;
//             $focust->column_fields['mapname'] = $SaveasMapTextImput;
//             $focust->column_fields['content']=$QueryGenerate;
//             $focust->column_fields['mvqueryid']=$queryid;
//             $focust->column_fields['description'] = $xml->saveXML();
//             $focust->column_fields['selected_fields'] =str_replace("  ","",$onlyselect[0])."\"";
//             $focust->column_fields['maptype'] = "SQL";
//             $log->debug(" we inicialize value for insert in database ");
//             if (!$focust->saveentity("cbMap")) {
//                 echo $focust->id;
//                 $log->debug("succes!! the map is created ");
//             } else {
//                 //echo focus->id;
//                 $log->debug("Error!! something went wrong");
//             }
//         }
//         else{
//             include_once('modules/cbMap/cbMap.php');
//             $map_focus = new cbMap();
//             $map_focus->id = $MapID;
//             $map_focus->retrieve_entity_info($MapID,"cbMap");
//             $map_focus->column_fields['content']= $QueryGenerate;
//             $map_focus->column_fields['mapname'] = $nameview;
//             $map_focus->column_fields['description'] = $xml->saveXML();
//             $map_focus->column_fields['selected_fields'] =str_replace("  ","",$onlyselect[0])."\"";
//             $map_focus->mode = "edit";
//             $map_focus->save("cbMap");
//             echo $MapID;
//             //    $focust->id = $MapID;
//             //    $focust->retrieve_entity_info($MapID, "cbMap");
//             //    $map_focus->mode = "edit";
//             //    $focust->column_fields['content']=$QueryGenerate;
//             //    $focust->save("cbMap");
//             //    echo $MapID;
//         }