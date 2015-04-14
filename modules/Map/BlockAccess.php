<?php
global $log,$adb, $mod_strings, $app_strings,$current_language;
//Default parameters

$defaultDelimiter = $_POST['delimiterVal'];
if(isset($_POST['orgmod']))
     $orgmod = explode("$$",$_POST['orgmod']);
else $orgmod = explode("$$",$_POST['orgmod']);

$orgmodID = $orgmod[0];
$mapid = $_REQUEST['mapid'];
$orgmodName = getTabModuleName($orgmodID);
if(isset($_POST['targetkeyconfig']))
$targetVal =implode(',', $_POST['targetkeyconfig']);
else
 $targetVal=$_POST['targetblock'];
$orgVal = $_POST['orgVal'];
$orgArr = explode(",",$orgVal);

global $adb;
$adb->pquery("Update vtiger_map set origin=?,originname=?,blocks=?  where mapid=?",
              array($orgmodID,$orgmodName,$targetVal,$mapid));

$xml = new DOMDocument("1.0");
$root = $xml->createElement("map");
$xml->appendChild($root);
$origin = $xml->createElement("originmodule");
$originid = $xml->createElement("originid");
$originText = $xml->createTextNode($orgmodID);
$originid->appendChild($originText);
$originname = $xml->createElement("originname");
$originnameText = $xml->createTextNode($orgmodName);
$originname->appendChild($originnameText);
$origin->appendChild($originid);
$origin->appendChild($originname);
$blocks = $xml->createElement("blocks");
$targetarr=array();
if(strpos($targetVal,',')!=false)
 $targetarr=explode(',',$targetVal);
else 
  $targetarr[]=$targetVal;

for($i = 0;$i < sizeof($targetarr); $i++){
    $block = $xml->createElement("block");
    $blockID = $xml->createElement("blockID");
    $fieldideText = $xml->createTextNode($targetarr[$i]);
    $blockID->appendChild($fieldideText);
    $block->appendChild($blockID);
    $blockname = $xml->createElement("blockname");
    if($targetarr[$i]==1000)
     $label='Execute';
     else
     {$labelq=$adb->pquery("select blocklabel from vtiger_blocks where blockid=?",array($targetarr[$i]));
     $label=getTranslatedString($adb->query_result($labelq,0,'blocklabel'),$orgmodName);
     }
    $blocknameText = $xml->createTextNode($label);
    $blockname->appendChild($blocknameText);
    $block->appendChild($blockname);
    $blocklabel = $xml->createElement("blocklabel");
    if($targetarr[$i]==1000)
    $blocklabelText=$xml->createTextNode('Execute');
     else
    $blocklabelText = $xml->createTextNode($adb->query_result($labelq,0,'blocklabel'));
    $blocklabel->appendChild($blocklabelText);
    $block->appendChild($blocklabel);
    $blocks->appendChild($block);
}

$root->appendChild($origin);
$root->appendChild($blocks);
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
