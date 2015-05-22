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
 *  Module       : EntittyLog
 *  Version      : 5.4.0
 *  Author       : LoggingConf
 *************************************************************************************************/

require_once('include/utils/CommonUtils.php');
require_once('include/utils/utils.php');
require_once('modules/LoggingConf/LoggingUtils.php');
global $adb,$log;
$tab_id= getTabid(vtlib_purify($_REQUEST['Screen']));
$fieldsarray=$_REQUEST['fieldstobeloggedModule'];
$fieldselarray=$_REQUEST['fieldselasticModule'];


$elog=$_REQUEST['elog'];
$relmodule=$_REQUEST['relmodule'];
$denorm=$_REQUEST['denorm'];
$indextype=$_REQUEST['indextype'];
$norm=$_REQUEST['norm'];
if($elog=='true')
$type[]='entitylog';
if($denorm=='true')
$type[]='denormalized';
if($norm=='true')
$type[]='normalized';
$type1=implode(",",$type);

if($indextype=='' || $indextype==null)
{$random = strtolower(vtlib_purify($_REQUEST['Screen'])).substr( md5(rand()), 0, 7);
    $ind=$random;   
}
else $ind=$indextype;
global $dbconfig;
$ip= $dbconfig['ip_server'];
$create=1;
 if(in_array("denormalized",$type) || in_array("normalized",$type))  {
     $sqlFields=Array();
     $join=Array();
     $where=Array();
 $endpointUrl2 = "http://$ip:9200/$ind/_mapping/denorm";
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

$fields1=unserialize($fieldselarray);
foreach($fields1 as $field)
{
    if(is_numeric($field)){
    $col=getColumnname($field);
    if(substr($col[1],0,1)=='N')
    {$coltype='double';
        $loggingFields[$col[0].$_REQUEST['Screen']]=array("type"=>$coltype);
        
    }
    else if(substr($col[1],0,1)=='D')
    { $coltype='date';
    $loggingFields[$col[0].$_REQUEST['Screen']]=array("type"=>$coltype,"format"=>"yyyy-MM-dd HH:mm:ss");
    }
    else {$coltype='string';
    $loggingFields[$col[0].$_REQUEST['Screen']]=array("type"=>$coltype);}
    $table=$col[2];
    $sqlFields[$k]=$table.'.'.$col[0].' as '.$col[0].$_REQUEST['Screen'];
    $k++;
     }
}
$relmodule1=explode(";",$relmodule);
for($j=0;$j<count($relmodule1);$j++){
  if($relmodule1[$j]!='Nessuno'){
 $mod1=getTabid($relmodule1[$j]);
     $ent1=getEntityField($relmodule1[$j]);
    $tbl1=$ent1['tablename'];
    $id1=$ent1['entityid'];
    $relfield=getFieldFK($_REQUEST['Screen'],$relmodule1[$j]);
    $join[$j1]=" join $tbl1 on $tbl1.$id1=$relfield[1].$relfield[0] join vtiger_crmentity crm$relmodule1[$j]$j on crm$relmodule1[$j]$j.crmid=$tbl1.$id1 ";
    $where[$j1]=" and crm$relmodule1[$j]$j.deleted=0 ";
    $j1++;

 $fields2=array();
$query=$adb->pquery("Select fields from vtiger_loggingconfiguration where tabid=? and fields!=''",array($mod1));

$fieldserialized=$adb->query_result($query,0);
$fields2=unserialize($fieldserialized);
foreach($fields2 as $field2)
{
    if(is_numeric($field2)){
    $col=getColumnname($field2);
    if(substr($col[1],0,1)=='N')
    {$coltype='double';
    $loggingFields[$col[0].$relmodule1[$j]]=array("type"=>$coltype);
    }
    else if(substr($col[1],0,1)=='D')
    {$coltype='date';
    $loggingFields[$col[0].$relmodule1[$j]]=array("type"=>$coltype,"format"=>"yyyy-MM-dd HH:mm:ss");}
    else {$coltype='string';
    $loggingFields[$col[0].$relmodule1[$j]]=array("type"=>$coltype);}
    if($col[2]=='vtiger_crmentity') $table="crm$relmodule1[$j]$j";
    else $table=$col[2];
    $sqlFields[$k]=$table.'.'.$col[0].' as '.$col[0].$relmodule1[$j];
    $k++;
    }
}
}
}
    
$ent=getEntityField(vtlib_purify($_REQUEST['Screen']));
$tbl=$ent['tablename'];
$id=$ent['entityid'];
$sqlFields[$k]=$tbl.'.'.$id.' as '.$id.$_REQUEST['Screen'];
$sqlfld=implode(",",$sqlFields);
$join=implode(" ",$join);
$where=implode(" ",$where);
 $loggingFields[$id.$_REQUEST['Screen']]=array("type"=>"string");
$query1="select $sqlfld from $tbl join vtiger_crmentity on crmid=$id $join where vtiger_crmentity.deleted=0 $where ## $tbl.$id";

if(count($response2->$ind->mappings->denorm)!=0)
{

$fields1=array("denorm"=>array("properties"=>$loggingFields, "dynamic_date_formats" => '[\'yyyy-MM-dd HH:mm:ss\', \'dd-MM-yyyy\', \'date_optional_time\']'));   
$endpointUrl2 = "http://$ip:9200/$ind/denorm/_mapping?ignore_conflicts=true";
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
$fields111=array("norm"=>array("properties"=>$loggingFields, "dynamic_date_formats" => '[\'yyyy-MM-dd HH:mm:ss\', \'dd-MM-yyyy\', \'date_optional_time\']'));   
$endpointUrl21 = "http://$ip:9200/$ind/norm/_mapping?ignore_conflicts=true";
 $channel11 = curl_init();
//curl_setopt($channel1, CURLOPT_HTTPHEADER, $headers);
curl_setopt($channel11, CURLOPT_URL, $endpointUrl21);
curl_setopt($channel11, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($channel11, CURLOPT_POST, true);
curl_setopt($channel11, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($channel11, CURLOPT_POSTFIELDS, json_encode($fields111));
curl_setopt($channel11, CURLOPT_CONNECTTIMEOUT, 100);
curl_setopt($channel11, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($channel11, CURLOPT_TIMEOUT, 1000);
$response2 = json_decode(curl_exec($channel11));
}
else{
$fields1=array("mappings"=>array("denorm"=>array("properties"=>$loggingFields, "dynamic_date_formats" => '[\'yyyy-MM-dd HH:mm:ss\', \'dd-MM-yyyy\', \'date_optional_time\']'),"norm"=>array("properties"=>$loggingFields, "dynamic_date_formats" => '[\'yyyy-MM-dd HH:mm:ss\', \'dd-MM-yyyy\', \'date_optional_time\']')));   
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
//}
 }  
   
 if(($create)<1)
 $ind='';
     
    //Updating the database
if($elog=='undefined' && $denorm=='undefined' && $norm=='undefined')
    break;
else
$update_query = "update vtiger_loggingconfiguration set fields=? ,fieldselastic=? ,type=?,relmodules=? , indextype='$ind',queryelastic=? where tabid=?";
$update_params = array($fieldsarray,$fieldselarray,$type1,$relmodule,$query1, $tab_id);
$query=$adb->pquery($update_query, $update_params);
echo $query;
?>