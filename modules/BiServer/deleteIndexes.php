<?php
global $adb;
$action = $_REQUEST['kaction'];
if($action == "read"){
$actionQuery = "Select * from vtiger_elastic_indexes";
$res = $adb->pquery($actionQuery, array());
for ($i = 0; $i < $adb->num_rows($res); $i++) {
    
    $actionsid = $adb->query_result($res, $i, 'id');
    $actionsname = $adb->query_result($res, $i, 'elasticname');
    $content[$i]['actionname'] = $actionsname;
    $content[$i]['id'] =  $actionsid;
}
$result = json_encode($content);
echo $result;
}
else if($action == "delete"){
$models=$_REQUEST['models'];
$model_values=array();
$model_values=json_decode($models);
$nr=count($model_values);
$mv=$model_values[$nr-1];
$actionQuery = "Delete from vtiger_elastic_indexes where id = ?";
$res = $adb->pquery($actionQuery, array($mv->id));
$ip = GlobalVariable::getVariable('ip_elastic_server', '');
$index = $mv->actionname;
$endpointUrl = "http://$ip:9200/$index";
$channel = curl_init();
curl_setopt($channel, CURLOPT_URL, $endpointUrl);
curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
curl_setopt($channel, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, 100);
curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($channel, CURLOPT_TIMEOUT, 1000);
$response = json_decode(curl_exec($channel));
}
?>
