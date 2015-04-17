<?php
global $root_directory,$log;
//Default parameters
$defaultDelimiter = $_POST['delimiterVal'];
$rec = $_POST['accid'];
if(isset($_POST['orgmodH']))
     $orgmod = explode("$$",$_POST['orgmodH']);
else $orgmod = explode("$$",$_POST['orgmod']);

$orgmodID = $orgmod[0];
$mapid = $_REQUEST['mapid'];
$orgmodName = getTabModuleName($orgmodID);
if(isset($_POST['targetmodH']))
$targetmod = explode("$$",$_POST['targetmodH']);
else $targetmod = explode("$$",$_POST['targetmod']);
$targetmodID = $targetmod[0];
$targetmodName = getTabModuleName($targetmodID); 

$targetVal = $_POST['targetVal'];
$targetArr = explode("::",$targetVal);
$orgVal = $_POST['orgVal'];
$orgArr = explode(",",$orgVal);
$nrmaps = count($orgArr);
global $adb;
$adb->pquery("Update vtiger_map set origin=?,originname=?, target=?, 
              targetname=?, field1=?,field2=?,delimiter=? where mapid=?",
              array($orgmodID,$orgmodName,$targetmodID,$targetmodName,$orgVal,$targetVal,$defaultDelimiter,$mapid));
$delimArr = explode("@",$defaultDelimiter);
$xml = new DOMDocument("1.0");
$root = $xml->createElement("map");
$xml->appendChild($root);
//$name = $xml->createElement("name");
$target = $xml->createElement("originmodule");
$targetid = $xml->createElement("originid");
$targetidText = $xml->createTextNode($targetmodID);
$targetid->appendChild($targetidText);
$targetname = $xml->createElement("originname");
$targetnameText = $xml->createTextNode($targetmodName);
$targetname->appendChild($targetnameText);
$target->appendChild($targetid);
$target->appendChild($targetname);

$origin = $xml->createElement("targetmodule");
$originid = $xml->createElement("targetid");
$originText = $xml->createTextNode($orgmodID);
$originid->appendChild($originText);
$originname = $xml->createElement("targetname");
$originnameText = $xml->createTextNode($orgmodName);
$originname->appendChild($originnameText);
$origin->appendChild($originid);
$origin->appendChild($originname);
$fields = $xml->createElement("fields");

for($i = 0;$i < $nrmaps; $i++){
    //get target field name
    $orgFields = explode(":",$orgArr[$i]);
    $field = $xml->createElement("field"); 
    $fieldname = $xml->createElement("fieldname");
    $fieldnameText = $xml->createTextNode($orgFields[1]);
    $fieldname->appendChild($fieldnameText);
    $field->appendChild($fieldname);
    $fieldID = $xml->createElement("fieldID");
    $fieldideText = $xml->createTextNode($orgFields[6]);
    $fieldID->appendChild($fieldideText);
    $field->appendChild($fieldID);
    //target module fields
    $Orgfields = $xml->createElement("Orgfields");
    $field->appendChild($Orgfields);
    // $targetArr[$i] mban fushat perkatese
    $trFields = explode(",",$targetArr[$i]);
//    if(count($trFields) > 1) {
        $fldnamearr = array();
        $fldidarr = array();
        for($j=0;$j<count($trFields);$j++){
        $fld = explode(":",$trFields[$j]);
        $fldnamearr[] = $fld[1];
        $modid = $fld[2];
        $fldidarr = $fld[4];  
        $type = $fld[5];
        if($type == "related"){
        $OrgRelfield= $xml->createElement("Relfield");
        $OrgRelfieldName = $xml->createElement("RelfieldName");
        $OrgRelfieldNameText= $xml->createTextNode($fld[1]);
        $OrgRelModule= $xml->createElement("RelModule");
        $OrgRelModuleText= $xml->createTextNode($fld[2]);
        $OrgRelfieldName->appendChild($OrgRelfieldNameText);
        $OrgRelModule->appendChild($OrgRelModuleText);
        $OrgRelfield->appendChild($OrgRelfieldName);
        $OrgRelfield->appendChild($OrgRelModule);
        
        $linkfield = $xml->createElement("linkfield");
        $linkfieldText= $xml->createTextNode($fld[6]);
        $linkfield->appendChild($linkfieldText);
        $OrgRelfield->appendChild($linkfield);
        $Orgfields->appendChild($OrgRelfield);  
        }
        else
        {
        $Orgfield= $xml->createElement("Orgfield");
        $OrgfieldName = $xml->createElement("OrgfieldName");
        $OrgfieldNameText= $xml->createTextNode($fld[1]);
        $OrgfieldName->appendChild($OrgfieldNameText);
        $Orgfield->appendChild($OrgfieldName);
        
        $OrgfieldID = $xml->createElement("OrgfieldID");
        $OrgfieldIDText= $xml->createTextNode($fld[4]);
        $OrgfieldID->appendChild($OrgfieldIDText);
        $Orgfield->appendChild($OrgfieldID);
        $Orgfields->appendChild($Orgfield);
        }
        }
        $del = $xml->createElement("delimiter");
        $delText= $xml->createTextNode($delimArr[$i]);
        $del->appendChild($delText);
        $Orgfields->appendChild($del);
//    }
   $fields->appendChild($field);
   $strTarField = implode($delimArr[$i],$fldnamearr);
   $strTarFldId = implode(",",$fldidarr);
}
//$root->appendChild($name);
$root->appendChild($target);
$root->appendChild($origin);
$root->appendChild($fields);
$xml->formatOutput = true;

echo $xml->saveXML();
include_once('modules/Map/Map.php');
$map_focus = new Map();
$map_focus->id = $mapid;
$map_focus->retrieve_entity_info($mapid,"Map");
$map_focus->column_fields['content']= $xml->saveXML();
$map_focus->mode = "edit";
$map_focus->save("Map");
?>
