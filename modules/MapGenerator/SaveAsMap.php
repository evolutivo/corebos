<?php
/*************************************************************************************************
 * Copyright 2015 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS Customizations.
 * Licensed under the vtiger CRM Public License Version 1.1 (the "License"); you may not use this
 * file except in compliance with the License. You can redistribute it and/or modify it
 * under the terms of the License. JPL TSolucio, S.L. reserves all rights not expressly
 * granted by the License. coreBOS distributed by JPL TSolucio S.L. is distributed in
 * the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Unless required by
 * applicable law or agreed to in writing, software distributed under the License is
 * distributed on an "AS IS" BASIS, WITHOUT ANY WARRANTIES OR CONDITIONS OF ANY KIND,
 * either express or implied. See the License for the specific language governing
 * permissions and limitations under the License. You may obtain a copy of the License
 * at <http://corebos.org/documentation/doku.php?id=en:devel:vpl11>
 *************************************************************************************************
 *  Module       : Business Map
 *  Version      : 1.0
 *  Author       : AT Consulting
 *************************************************************************************************/
include_once("modules/cbMap/cbMap.php");
require_once('data/CRMEntity.php');
require_once('include/utils/utils.php');

global $root_directory, $log;

$FirstModule=$_POST['FirstModul'];
$secmodule=$_POST['secmodule'];
$FirstModuleID=$_POST['selField1'];
$SecondModuleID=$_POST['selField2'];
$allvalues=$_POST['allvalues'];
$MapNAme=$_POST['nameView'];
$QueryGenerate=$_POST['QueryGenerate'];

if (!empty($FirstModule) && !empty($secmodule)) {
    echo $FirstModule;
    echo $secmodule;
    echo $FirstModuleID;
    echo $SecondModuleID;
    echo $allvalues;
    echo $MapNAme;
    echo $QueryGenerate;
}else
{
    
}







$addsqltag="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$addsqltag.="<map>\n<maptype>SQL</maptype>\n";
$addsqltag.="<sql>";
$addsqltag.=$QueryGenerate;
$addsqltag.="<sql>\n<return>";
$addsqltag.=explode(":",$_REQUEST['returnvalue'])[1];
$addsqltag.="\n</return> \n</map>";


$SaveasMapTextImput=$_POST['SaveasMapTextImput'];
if($SaveasMapTextImput=='') {$SaveasMapTextImput=$nameview;}

if (empty($_POST["MapId"])){
    $focust = new cbMap();
    $focust->column_fields['assigned_user_id'] = 1;
    $focust->column_fields['mapname'] = $SaveasMapTextImput;
    $focust->column_fields['content']=$addsqltag;
    $focust->column_fields['mvqueryid']=$queryid;
    $focust->column_fields['description'] = $xml->saveXML();
    $focust->column_fields['selected_fields'] =str_replace("  ","",$onlyselect[0])."\"";
    $focust->column_fields['maptype'] = "Condition Query";
    $log->debug(" we inicialize value for insert in database ");
    if (!$focust->saveentity("cbMap")) {
         echo $focust->id;
        $log->debug("succes!! the map is created ");
    } else {
        //echo focus->id;
        $log->debug("Error!! something went wrong");
    }
}
else{
    include_once('modules/cbMap/cbMap.php');
    $map_focus = new cbMap();
    $map_focus->id = $MapID;
    $map_focus->retrieve_entity_info($MapID,"cbMap");
    $map_focus->column_fields['content']= $addsqltag;
    // $map_focus->column_fields['mapname'] = $nameview;
    $map_focus->column_fields['description'] = $xml->saveXML();
    $map_focus->column_fields['selected_fields'] =str_replace("  ","",$onlyselect[0])."\"";
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
