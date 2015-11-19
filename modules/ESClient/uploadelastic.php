<?php
/*************************************************************************************************
 * Copyright 2014 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS Customizations.
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
 *  Module       : ESClient
 *  Version      : 5.4.0
 *  Author       : OpenCubed
 *************************************************************************************************/

$file=vtlib_purify($_REQUEST['files']);
$ind=strtolower(str_replace(".csv","",$file));
$ip=GlobalVariable::getVariable('ip_elastic_server', '');
$r=0;
if (($handle = fopen("storage/$file", "r")) !== FALSE) {
// while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
$data = fgetcsv($handle, 1000, ",");
$datacol=$data;
//  if($r==1){
$num = count($data);
 $data = fgetcsv($handle, 1000, ",");
$dataval=$data;

for ($c=0; $c < $num; $c++) {
if(is_numeric($dataval[$c]) && strlen($dataval[$c])<10)
{$coltype='double';
$loggingFields[$datacol[$c]]=array("type"=>$coltype);
}
else if(strtotime($dataval[$c])!=false && strlen($dataval[$c])>='10')
{$coltype='date';
// if(substr($col[1],0,2)=='DT') 
$format='yyyy-MM-dd HH:mm:ss';
// else $format='yyyy-MM-dd';
$loggingFields[$datacol[$c]]=array("type"=>$coltype,"format"=>"$format");}
else {$coltype='string';
$loggingFields[$datacol[$c]]=array("type"=>$coltype);}
}

// }
//}
fclose($handle);
}

$endpointUrl2 = "http://$ip:9200/$ind/_mapping/import";

$channel11 = curl_init();
//curl_setopt($channel1, CURLOPT_HTTPHEADER, $headers);
curl_setopt($channel11, CURLOPT_URL, $endpointUrl2);
curl_setopt($channel11, CURLOPT_RETURNTRANSFER, true);
curl_setopt($channel11, CURLOPT_POST, false);
//curl_setopt($channel11, CURLOPT_CUSTOMREQUEST, "PUT");
//curl_setopt($channel11, CURLOPT_POSTFIELDS, json_encode($fields1->fields));
curl_setopt($channel11, CURLOPT_CONNECTTIMEOUT, 100);
curl_setopt($channel11, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($channel11, CURLOPT_TIMEOUT, 1000);
$response2 = json_decode(curl_exec($channel11));
if(count($response2->$ind->mappings->import)!=0)
{

$fields1=array("import"=>array("properties"=>$loggingFields, "dynamic_date_formats" => '[\'yyyy-MM-dd HH:mm:ss\', \'dd-MM-yyyy\', \'date_optional_time\']'));   
$endpointUrl2 = "http://$ip:9200/$ind/import/_mapping?ignore_conflicts=true";
$channel11 = curl_init();
//curl_setopt($channel1, CURLOPT_HTTPHEADER, $headers);
curl_setopt($channel11, CURLOPT_URL, $endpointUrl2);
curl_setopt($channel11, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($channel11, CURLOPT_POST, true);
curl_setopt($channel11, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($channel11, CURLOPT_POSTFIELDS, json_encode($fields1));
curl_setopt($channel11, CURLOPT_CONNECTTIMEOUT, 100);
curl_setopt($channel11, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($channel11, CURLOPT_TIMEOUT, 1000);
$response2 = json_decode(curl_exec($channel11));}

else{
$fields1=array("mappings"=>array("import"=>array("properties"=>$loggingFields, "dynamic_date_formats" => '[\'yyyy-MM-dd HH:mm:ss\', \'dd-MM-yyyy\', \'date_optional_time\']')));   
$endpointUrl2 = "http://$ip:9200/$ind";
$channel11 = curl_init();
//curl_setopt($channel1, CURLOPT_HTTPHEADER, $headers);
curl_setopt($channel11, CURLOPT_URL, $endpointUrl2);
curl_setopt($channel11, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($channel11, CURLOPT_POST, true);
curl_setopt($channel11, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($channel11, CURLOPT_POSTFIELDS, json_encode($fields1));
curl_setopt($channel11, CURLOPT_CONNECTTIMEOUT, 100);
curl_setopt($channel11, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($channel11, CURLOPT_TIMEOUT, 1000);
$response2 = json_decode(curl_exec($channel11));

}

if($response2->acknowledged==true)
$create=1;
else $create=0;
//insert into elastic
if($create==1){
$endpointUrl2 = "http://$ip:9200/$ind/import";
if (($handle = fopen("storage/$file", "r")) !== FALSE) {
while (($data2 = fgetcsv($handle, 1000, ",")) !== FALSE) {
$r++;
if($r!=1){

for ($c1=0; $c1 < $num; $c1++) {
$ctype= $loggingFields[$datacol[$c1]];
if($ctype['type']=='string')
$fields11["$datacol[$c1]"]=html_entity_decode(htmlentities(mb_convert_encoding($data2[$c1], 'UTF-8', 'ASCII'), ENT_SUBSTITUTE, "UTF-8")); 
else if ($ctype['type']=='date') 
$fields11["$datacol[$c1]"]=date("Y-m-d H:i:s",strtotime($data2[$c1]));
else $fields11["$datacol[$c1]"]=floatval($data2[$c1]);
}

$channel11 = curl_init();
//curl_setopt($channel1, CURLOPT_HTTPHEADER, $headers);
curl_setopt($channel11, CURLOPT_URL, $endpointUrl2);
curl_setopt($channel11, CURLOPT_RETURNTRANSFER, true);
curl_setopt($channel11, CURLOPT_POST, true);
//curl_setopt($channel11, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($channel11, CURLOPT_POSTFIELDS, json_encode($fields11));
curl_setopt($channel11, CURLOPT_CONNECTTIMEOUT, 100);
curl_setopt($channel11, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($channel11, CURLOPT_TIMEOUT, 1000);
$response23 = json_decode(curl_exec($channel11));
if($response23->created!=1) {echo 'false';
}
}
}

fclose($handle);
}

global $adb;
$fields=implode(",",$datacol);
$adb->pquery("Insert into vtiger_elastic_indexes(elasticname,status,fieldlabel,type) values(?,?,?,?)",array($ind,"Open",$fields,'import'));
}

echo $response23->created;

?>