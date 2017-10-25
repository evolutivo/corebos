<?php
// SaveListColumns.php

include_once ("modules/cbMap/cbMap.php");
require_once ('data/CRMEntity.php');
require_once ('include/utils/utils.php');


global $root_directory, $log; 
$Data = array();

//  var_dump($_REQUEST, true);
// exit();

$MapName = $_POST['MapName']; // stringa con tutti i campi scelti in selField1
$MapType = "Master Detail"; // stringa con tutti i campi scelti in selField1
$Data = $_POST['alldata'];
$MapID=explode(',', $_REQUEST['savehistory']); 


if (empty($MapName)) {
	echo "Missing the Name of Map";
}

if (!empty($Data)) {
	
	$jsondecodedata=json_decode($Data);	

	print_r($jsondecodedata);

}