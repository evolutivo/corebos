<?php
//require_once("Zend/Json/Server.php");
//require_once("Zend/Json.php");

global $log,$adb;
require_once("config.inc.php");
include_once('data/CRMEntity.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');

$endpointUrl = "http://localhost/eprice/webservice.php";
//username of the user who is to logged in. 
$userName="admin";
$userAccessKey = "hTV1PHNvuR8rafkp";

//getchallenge request must be a GET request.
$url="$endpointUrl?operation=getchallenge&username=$userName";
$headers[] = 'Content-Type: application/json';
$channel = curl_init();

curl_setopt($channel, CURLOPT_HTTPHEADER, $headers);
curl_setopt($channel, CURLOPT_URL, $url);
curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
curl_setopt($channel, CURLOPT_POST, false);
curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, 100);
curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($channel, CURLOPT_TIMEOUT, 1000);
//decode the json encode response from the server.
$jsonResponse = Zend_JSON::decode(curl_exec($channel));

//decode the json encode response from the server.
//check for whether the requested operation was successful or not.
if($jsonResponse['success']==false) {
    //handle the failure case.
  echo $jsonResponse['error']['errorMsg'];
    die;}
//operation was successful get the token from the reponse.
$challengeToken = $jsonResponse['result']['token'];
//create md5 string concatenating user accesskey from my preference page 
//and the challenge token obtained from get challenge result. 
$generatedKey = md5($challengeToken.$userAccessKey);
curl_close($channel);
$fields1 =array('operation'=>'login','username'=>$userName,'accessKey'=>$generatedKey);

//$headers[] = 'Content-Type: application/json';
$channel1 = curl_init();
//curl_setopt($channel1, CURLOPT_HTTPHEADER, $headers);
curl_setopt($channel1, CURLOPT_URL, $endpointUrl);
curl_setopt($channel1, CURLOPT_RETURNTRANSFER, true);
curl_setopt($channel1, CURLOPT_POST, true);
curl_setopt($channel1, CURLOPT_POSTFIELDS, $fields1);
curl_setopt($channel1, CURLOPT_CONNECTTIMEOUT, 100);
curl_setopt($channel1, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($channel1, CURLOPT_TIMEOUT, 1000);
$response1 = curl_exec($channel1);

//decode the json encode response from the server.
$jsonResponse = Zend_JSON::decode($response1);

//operation was successful get the token from the reponse.
if($jsonResponse['success']==false){
    //handle the failure case.
       echo $jsonResponse['error']['errorMsg'];
    die;}

//login successful extract sessionId and userId from LoginResult to it can used for further calls.
$sessionId = $jsonResponse['result']['sessionName']; 
curl_close($channel1);
if(!empty($argv)){
 $scriptlogid=$argv[1];
 if($scriptlogid!=''){/*
 $query=$adb->pquery("select * from vtiger_scriptlog where scriptlogid=?",array($scriptlogid));
echo 'teste';$log->debug('dioniii');
 $scriptlogname=$adb->query_result($query,0,'scriptlog_name');
 $log->DEbug($scriptlogname+'adfsdsf');*/
     $log->debug('dioniii');
     require('modules/ScriptLog/ScriptLog.php');
     $focus=  CRMEntity::getInstance("ScriptLog");
     $focus->retrieve_entity_info($scriptlogid,"ScriptLog");
     $scriptlogname=$focus->column_fields['scriptlog_name'];
 if(stristr($scriptlogname,'GetDdt')!='' || stristr($scriptlogname,'GetStatiRip')!=''){
     if(stristr($scriptlogname,'GetDdt')!='')
             $ws_name='GetDdt';
     else if(stristr($scriptlogname,'GetStatiRip')!='')
             $ws_name='GetStatiRip';
     
 $json=$focus->column_fields['description'];
 $fields2=array("operation"=>"$ws_name","sessionName"=>$sessionId,'jsonstring'=>$json);
$channel2 = curl_init();
curl_setopt($channel2, CURLOPT_URL, $endpointUrl);
curl_setopt($channel2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($channel2, CURLOPT_POST, true);
curl_setopt($channel2, CURLOPT_POSTFIELDS, $fields2);
curl_setopt($channel2, CURLOPT_CONNECTTIMEOUT, 100);
curl_setopt($channel2, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($channel2, CURLOPT_TIMEOUT, 1000);
//query must be GET Request.
$response = curl_exec($channel2);
if(stristr($response,'success')!='')
        echo 'Executed successfully';
//decode the json encode response from the server.
$jsonResponse = Zend_JSON::decode($response);

//operation was successful get the token from the reponse.
if($jsonResponse['success']==false){
    //handle the failure case.
     echo $jsonResponse['error']['message'];
    die;}
//Array of vtigerObjects
$retrievedObjects = $jsonResponse['result'];
 }else{
     echo 'Can not execute this scriptlog';
 }
}

}


?>
