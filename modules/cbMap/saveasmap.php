<?php
/**
 * Created by PhpStorm.
 * User: edmondi kacaj
 * Date: 5/9/2017
 * Time: 10:35 AM
 */
//echo "edmondi kacaj";
//exit();
include_once("modules/cbMap/cbMap.php");
require_once('data/CRMEntity.php');
require_once('include/utils/utils.php');

global $root_directory, $log;
$selField1 = $_POST['selField1'];//stringa con tutte i campi scelti in selField1
$selField2 = $_POST['selField2'];//stringa con tutte i campi scelti in selField1
$nameview = $_POST['nameView'];//nome della vista
$MapID = $_POST['MapId'];
//echo 23;
//exit();
$QueryGenerate=$_POST['QueryGenerate'];
$campiSelezionati = $_POST['campiSelezionati'];
$nrmaps = count($campiSelezionati);
$optionValue = array();
$optgroup = array();
$firstmodule = $_POST['selField1'];
$secmodule = $_POST['selField2'];
$selTab1 = $_POST['selTab1'];
$selTab2 = $_POST['selTab2'];
global $adb;
$delimArr = explode("@", $defaultDelimiter);
//print_r($defaultDelimiter);
$xml = new DOMDocument("1.0");
$root = $xml->createElement("map");
$xml->appendChild($root);
//$name = $xml->createElement("name");
$target = $xml->createElement("Fmodule");
$targetid = $xml->createElement("FmoduleID");
$targetidText = $xml->createTextNode(array_values($firstmodule)[0]);
$targetid->appendChild($targetidText);
$targetname = $xml->createElement("FmoduleName");
$targetnameText = $xml->createTextNode(array_values($selTab1)[0]);
$targetname->appendChild($targetnameText);
$target->appendChild($targetid);
$target->appendChild($targetname);

$origin = $xml->createElement("Secmodule");
$originid = $xml->createElement("SecmoduleID");
$originText = $xml->createTextNode(array_values($secmodule)[0]);
$originid->appendChild($originText);
$originname = $xml->createElement("SecmoduleName");
$originnameText = $xml->createTextNode(array_values($selTab2)[0]);
$originname->appendChild($originnameText);
$origin->appendChild($originid);
$origin->appendChild($originname);
$fields = $xml->createElement("fields");

for ($i = 0; $i < $nrmaps; $i++) {
    //get target field name
    $orgFields = explode(":", $campiSelezionati[$i]);//explode(":", $orgArr[$i]);
    //print_r($orgFields);
    //exit();
    $field = $xml->createElement("field");
    $fieldname = $xml->createElement("fieldname");
    $fieldnameText = $xml->createTextNode($orgFields[1]);
    $fieldname->appendChild($fieldnameText);
    $field->appendChild($fieldname);
    $fieldID = $xml->createElement("fieldID");
    $fieldideText = $xml->createTextNode($orgFields[4]);
    $fieldID->appendChild($fieldideText);
    $field->appendChild($fieldID);
    //target module fields
    // $Orgfields = $xml->createElement("Orgfields");
    // $field->appendChild($Orgfields);
    // $targetArr[$i] mban fushat perkatese
    $trFields = explode(",", $targetArr[$i]);
//    if(count($trFields) > 1) {
    $fldnamearr = array();
    $fldidarr = array();
    for ($j = 0; $j < count($trFields); $j++) {
        $fld = explode(":", $trFields[$j]);
        $fldnamearr[] = $fld[1];
        $modid = $fld[2];
        $fldidarr = $fld[4];
        $type = $fld[5];
        if ($type == "related") {
            $OrgRelfield = $xml->createElement("Relfield");
            $OrgRelfieldName = $xml->createElement("RelfieldName");
            $OrgRelfieldNameText = $xml->createTextNode($fld[1]);
            $OrgRelModule = $xml->createElement("RelModule");
            $OrgRelModuleText = $xml->createTextNode($fld[2]);
            $OrgRelfieldName->appendChild($OrgRelfieldNameText);
            $OrgRelModule->appendChild($OrgRelModuleText);
            $OrgRelfield->appendChild($OrgRelfieldName);
            $OrgRelfield->appendChild($OrgRelModule);

            $linkfield = $xml->createElement("linkfield");
            $linkfieldText = $xml->createTextNode($fld[6]);
            $linkfield->appendChild($linkfieldText);
            $OrgRelfield->appendChild($linkfield);
            $Orgfields->appendChild($OrgRelfield);
        } else {
            $Orgfield = $xml->createElement("Orgfield");
            $OrgfieldName = $xml->createElement("OrgfieldName");
            $OrgfieldNameText = $xml->createTextNode($fld[1]);
            $OrgfieldName->appendChild($OrgfieldNameText);
            $Orgfield->appendChild($OrgfieldName);

            $OrgfieldID = $xml->createElement("OrgfieldID");
            $OrgfieldIDText = $xml->createTextNode($fld[4]);
            $OrgfieldID->appendChild($OrgfieldIDText);
            $Orgfield->appendChild($OrgfieldID);
        }
    }
    $del = $xml->createElement("delimiter");
    $delText = $xml->createTextNode($delimArr[$i]);
    $del->appendChild($delText);
    $fields->appendChild($field);
    $strTarField = implode($delimArr[$i], $fldnamearr);
    $strTarFldId = implode(",", $fldidarr);
}
$root->appendChild($target);
$root->appendChild($origin);
$root->appendChild($fields);
$xml->formatOutput = true;


if (empty($_POST["MapId"])){
//    echo $MapID;
//    exit();
    $log->debug("the field from post are not empty ");
    $focust = new cbMap();
    $focust->column_fields['assigned_user_id'] = 1;
    $focust->column_fields['mapname'] = $nameview;
    $focust->column_fields['content']=$QueryGenerate;
    $focust->column_fields['description'] = $xml->saveXML();
    $map_focus->column_fields['selected_fields'] ="\"".$QueryGenerate."\"";
    $focust->column_fields['maptype'] = "SQL";
    //echo "know we inicialize value for insert in database";
    $log->debug(" we inicialize value for insert in database ");

    if (!$focust->saveentity("cbMap")) {
        // echo "success!!! The map is created.";
         echo $focust->id;
        $log->debug("succes!! the map is created ");
    } else {
        // echo "Error!!! something went wrong.";
        //echo focus->id;
        $log->debug("Error!! something went wrong");
    }
}
else{

//    echo $MapID;
//    exit();
    include_once('modules/cbMap/cbMap.php');
    $map_focus = new cbMap();
    $map_focus->id = $MapID;
    $map_focus->retrieve_entity_info($MapID,"cbMap");
    $map_focus->column_fields['content']= $QueryGenerate;
    $map_focus->column_fields['description'] = $xml->saveXML();
    $map_focus->column_fields['selected_fields'] ="\"".$QueryGenerate."\"";
    $map_focus->mode = "edit";
    $map_focus->save("cbMap");
    echo $MapID;
//    $focust->id = $MapID;
//    $focust->retrieve_entity_info($MapID, "cbMap");
//    $map_focus->mode = "edit";
//    $focust->column_fields['content']=$QueryGenerate;
//    $focust->save("cbMap");
//    echo $MapID;
}

?>
