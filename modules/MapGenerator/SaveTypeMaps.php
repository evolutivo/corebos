<?php



// include_once("modules/cbMap/cbMap.php");
// require_once('data/CRMEntity.php');
// require_once('include/utils/utils.php');

// global $root_directory, $log;
$Data = array();
// var_dump($_REQUEST);
// exit();
$MapName = $_GET['MapName'];//stringa con tutti i campi scelti in selField1
echo "Edmondi MapName===".$MapName;
exit();
$MapType = $_POST['MapType'];//stringa con tutti i campi scelti in selField1
//$Data = $_POST['Data'];//nome della vista

// print_r($Data);
// echo "<br/>";
// echo "<br/>";
// echo "<br/>";
echo $MapName;
echo "<br/>";
echo "<br/>";
echo "<br/>";
echo $MapType;
echo "<br/>";
echo "<br/>";
 
exit();


// if($querysequence!=''){
//     $adb->query("update mvqueryhistory set active=0 where id='$queryid'");
//     $adb->query("update mvqueryhistory set active=1 where id='$queryid' and sequence='$querysequence'");}
//     $dd=str_replace("SELECT","",$QueryGenerate);
//     $withoutselect="\"".$dd."\"";
//     $onlyselect=explode("FROM",$withoutselect);
//     $campiSelezionati =explode(",",$onlyselect[0]);
//     $nrmaps = count($campiSelezionati);
//     $optionValue = array();
//     $optgroup = array();
   
//     global $adb;
//     $delimArr = explode("@", $defaultDelimiter);
//     $xml = new DOMDocument("1.0");
//     $root = $xml->createElement("map");
//     $xml->appendChild($root);
//     //$name = $xml->createElement("name");
//     $target = $xml->createElement("Fmodule");
//     $targetid = $xml->createElement("FmoduleID");
//     $targetidText = $xml->createTextNode(array_values($firstmodule)[0]);
//     $targetid->appendChild($targetidText);
//     $targetname = $xml->createElement("FmoduleName");
//     $targetnameText = $xml->createTextNode(array_values($selTab1)[0]);
//     $targetname->appendChild($targetnameText);
//     $target->appendChild($targetid);
//     $target->appendChild($targetname);
    
//     $origin = $xml->createElement("Secmodule");
//     $originid = $xml->createElement("SecmoduleID");
//     $originText = $xml->createTextNode(array_values($secmodule)[0]);
//     $originid->appendChild($originText);
//     $originname = $xml->createElement("SecmoduleName");
//     $originnameText = $xml->createTextNode(array_values($selTab2)[0]);
//     $originname->appendChild($originnameText);
//     $origin->appendChild($originid);
//     $origin->appendChild($originname);
//     $fields = $xml->createElement("fields");
    
//     for ($i = 1; $i < $nrmaps; $i++) {
//         //get target field name
//         $orgFields = explode(".", $campiSelezionati[$i]);//explode(":", $orgArr[$i]);
//         $field = $xml->createElement("field");
//         $fieldname = $xml->createElement("fieldname");
//         $fieldnameText = $xml->createTextNode($orgFields[1]);
//         $fieldname->appendChild($fieldnameText);
//         $field->appendChild($fieldname);
//         $fieldID = $xml->createElement("fieldID");
//         $fieldideText = $xml->createTextNode($orgFields[1]);
//         $fieldID->appendChild($fieldideText);
//         $field->appendChild($fieldID);
//         //target module fields
//         // $Orgfields = $xml->createElement("Orgfields");
//         // $field->appendChild($Orgfields);
//         // $targetArr[$i] mban fushat perkatese
//         $trFields = explode(",", $targetArr[$i]);
//         //    if(count($trFields) > 1) {
//         $fldnamearr = array();
//         $fldidarr = array();
//         for ($j = 0; $j < count($trFields); $j++) {
//             $fld = explode(":", $trFields[$j]);
//             $fldnamearr[] = $fld[1];
//             $modid = $fld[2];
//             $fldidarr = $fld[4];
//             $type = $fld[5];
//             if ($type == "related") {
//                 $OrgRelfield = $xml->createElement("Relfield");
//                 $OrgRelfieldName = $xml->createElement("RelfieldName");
//                 $OrgRelfieldNameText = $xml->createTextNode($fld[1]);
//                 $OrgRelModule = $xml->createElement("RelModule");
//                 $OrgRelModuleText = $xml->createTextNode($fld[2]);
//                 $OrgRelfieldName->appendChild($OrgRelfieldNameText);
//                 $OrgRelModule->appendChild($OrgRelModuleText);
//                 $OrgRelfield->appendChild($OrgRelfieldName);
//                 $OrgRelfield->appendChild($OrgRelModule);
                
//                 $linkfield = $xml->createElement("linkfield");
//                 $linkfieldText = $xml->createTextNode($fld[6]);
//                 $linkfield->appendChild($linkfieldText);
//                 $OrgRelfield->appendChild($linkfield);
//                 $Orgfields->appendChild($OrgRelfield);
//             } else {
//                 $Orgfield = $xml->createElement("Orgfield");
//                 $OrgfieldName = $xml->createElement("OrgfieldName");
//                 $OrgfieldNameText = $xml->createTextNode($fld[1]);
//                 $OrgfieldName->appendChild($OrgfieldNameText);
//                 $Orgfield->appendChild($OrgfieldName);
                
//                 $OrgfieldID = $xml->createElement("OrgfieldID");
//                 $OrgfieldIDText = $xml->createTextNode($fld[4]);
//                 $OrgfieldID->appendChild($OrgfieldIDText);
//                 $Orgfield->appendChild($OrgfieldID);
//             }
//         }
//         $del = $xml->createElement("delimiter");
//         $delText = $xml->createTextNode($delimArr[$i]);
//         $del->appendChild($delText);
//         $fields->appendChild($field);
//         $strTarField = implode($delimArr[$i], $fldnamearr);
//         $strTarFldId = implode(",", $fldidarr);
//     }
//     $root->appendChild($target);
//     $root->appendChild($origin);
//     $root->appendChild($fields);
//     $xml->formatOutput = true;
    
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