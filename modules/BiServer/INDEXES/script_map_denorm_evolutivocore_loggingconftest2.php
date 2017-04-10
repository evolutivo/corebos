<?php 
include_once("include/utils/CommonUtils.php");
global $adb;

$reportIndexFields = array();
$reportIndexFields["accountid"]=array("type"=>"string","index"=>"not_analyzed");
$reportIndexFields["name"]=array("type"=>"string","index"=>"not_analyzed");
$reportIndexFields["no"]=array("type"=>"string","index"=>"not_analyzed");
$reportIndexFields["smowneridAccounts"]=array("type"=>"string","index"=>"not_analyzed");
$reportIndexFields["modifiedtimeAccounts"]=array("type"=>"date","format"=>"yyyy-MM-dd HH:mm:ss");
$fields1=array("mappings"=>array("denorm"=>array("properties"=>$reportIndexFields, "dynamic_date_formats" => "['yyyy-MM-dd HH:mm:ss', 'yyyy-MM-dd','dd-MM-yyyy', 'date_optional_time']")));
$selectedMapColumns = explode(",","accountid,name,no,smowneridAccounts,modifiedtimeAccounts");
$ip= "193.182.16.34";
$index= "evolutivocore_loggingconftest2";
$entityfield= "accountid";
$mapSql= "SELECT vtiger_account.accountid, vtiger_account.accountname, vtiger_account.account_no, vtiger_crmentity.smownerid, vtiger_crmentity.modifiedtime FROM vtiger_account  INNER JOIN vtiger_crmentity ON vtiger_account.accountid = vtiger_crmentity.crmid LEFT JOIN vtiger_users ON vtiger_crmentity.smownerid = vtiger_users.id LEFT JOIN vtiger_groups ON vtiger_crmentity.smownerid = vtiger_groups.groupid  WHERE vtiger_crmentity.deleted=0 AND vtiger_account.accountid > 0";
$endpointUrl = "http://$ip:9200/$index";
$channel = curl_init();
curl_setopt($channel, CURLOPT_URL, $endpointUrl);
curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
curl_setopt($channel, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, 100);
curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($channel, CURLOPT_TIMEOUT, 1000);
$response = json_decode(curl_exec($channel));
/**
*Index fields Structure 
*/
$endpointUrl = "http://$ip:9200/$index";
$channel = curl_init();
curl_setopt($channel, CURLOPT_URL, $endpointUrl);
curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
curl_setopt($channel, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($channel, CURLOPT_POSTFIELDS, json_encode($fields1));
curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, 100);
curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($channel, CURLOPT_TIMEOUT, 1000);
$response = json_decode(curl_exec($channel));
$fields = $adb->pquery($mapSql,array());
 for($i=0; $i< $adb->num_rows($fields); $i++)
{
for($j=0;$j< count($selectedMapColumns);$j++)
{
$type = $reportIndexFields[$selectedMapColumns[$j]]['type'];
if($type == "date" && ($adb->query_result($fields,$i,$j == "" || $adb->query_result($fields,$i,$j == null))))
$data[$selectedMapColumns[$j]] = "1970-01-01"; 
 else
$data[$selectedMapColumns[$j]] = $adb->query_result($fields,$i,$j) ;
}
generateBiServerScript($ip,$index,$entityfield,$adb->query_result($fields,$i,0),$data);
}
/**
* @param type $filename
* @param type $ip
* @param type $indextype
* @param type $entityfield
* @param type $recordId - Id of entity
*/
function generateBiServerScript($ip,$indextype,$entityfield,$recordId,$data){
global $adb;
$endpointUrl = "http://$ip:9200/$indextype/denorm/_search?pretty";
$fields =array("query"=>array("term"=>array("$entityfield"=>"$recordId")));
$channel = curl_init();
curl_setopt($channel, CURLOPT_URL, $endpointUrl);
curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
curl_setopt($channel, CURLOPT_POST, true);
curl_setopt($channel, CURLOPT_POSTFIELDS, json_encode($fields));
curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, 100);
curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($channel, CURLOPT_TIMEOUT, 1000);
$response = json_decode(curl_exec($channel));
$endpointUrl = "http://$ip:9200/$indextype/denorm";
$channel11 = curl_init();
curl_setopt($channel11, CURLOPT_URL, $endpointUrl);
curl_setopt($channel11, CURLOPT_RETURNTRANSFER, true);
curl_setopt($channel11, CURLOPT_POST, true);
curl_setopt($channel11, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($channel11, CURLOPT_CONNECTTIMEOUT, 100);
curl_setopt($channel11, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($channel11, CURLOPT_TIMEOUT, 1000);
$response = curl_exec($channel11);
}