<?php

/**
 * @Author: edmondi kacaj
 * @Date:   2018-01-05 17:23:10
 * @Last Modified by:   edmondi kacaj
 * @Last Modified time: 2018-01-05 17:26:57
 */

//saveImportBussinesMapping

include_once ("modules/cbMap/cbMap.php");
require_once ('data/CRMEntity.php');
require_once ('include/utils/utils.php');
require_once('All_functions.php');
require_once('Staticc.php');


global $root_directory, $log; 
$Data = array();

$MapName = $_POST['MapName']; // stringa con tutti i campi scelti in selField1
$MapType = "Import"; // stringa con tutti i campi scelti in selField1
$SaveasMapText = $_POST['SaveasMapText'];
$Data = $_POST['ListData'];
$MapID=explode(',', $_REQUEST['savehistory']); 
$mapname=(!empty($SaveasMapText)? $SaveasMapText:$MapName);
$idquery2=!empty($MapID[0])?$MapID[0]:md5(date("Y-m-d H:i:s").uniqid(rand(), true));


if (empty($SaveasMapText)) {
     if (empty($MapName)) {
            echo "Missing the name of map Can't save";
            return;
       }
}
if (empty($MapType))
{
    $MapType = "Import";
}

if (!empty($Data))
{
	$jsondecodedata=json_decode($Data);
	print_r($jsondecodedata);
}

