<?php

function wsExecution($methodName, $encodedArguments) {
    include 'modules/BusinessActions/ws_config.php';
    $endpointUrl = $node_URL . "testrests?operation=getchallenge&username=$userName";
//username of the user who is to logged in. 
//$fields1 =array('operation'=>'login','username'=>'admin','accessKey'=>'8a1648f04aa57170bd5021de220bd859');

    $headers[] = 'Content-Type: application/json';
    $channel1 = curl_init();
//curl_setopt($channel1, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($channel1, CURLOPT_URL, $endpointUrl);
    curl_setopt($channel1, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($channel1, CURLOPT_POST, false);
//curl_setopt($channel1, CURLOPT_POSTFIELDS, $fields1);
    curl_setopt($channel1, CURLOPT_CONNECTTIMEOUT, 100);
    curl_setopt($channel1, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($channel1, CURLOPT_TIMEOUT, 1000);
    $response1 = curl_exec($channel1);

//decode the json encode response from the server.
    $jsonResponse = json_decode($response1, true);
    $js = $jsonResponse['results'];
//operation was successful get the token from the reponse.
    if ($js['success'] == false) {
        //handle the failure case.
        echo $js['error']['errorMsg'];
        die;
    }

   $challengeToken = $js['result']['token'];

//create md5 string concatenating user accesskey from my preference page 
//and the challenge token obtained from get challenge result. 
    $generatedKey = md5($challengeToken . $userAccessKey);

    curl_close($channel1);

    $endpointUrl2 = $node_URL . "testrestlogin?operation=login&username=$userName&accessKey=$generatedKey";
//username of the user who is to logged in. 
//$fields1 =array('operation'=>'login','username'=>'admin','accessKey'=>'8a1648f04aa57170bd5021de220bd859');

    $headers[] = 'Content-Type: application/json';
    $channel1 = curl_init();
//curl_setopt($channel1, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($channel1, CURLOPT_URL, $endpointUrl2);
    curl_setopt($channel1, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($channel1, CURLOPT_POST, false);
//curl_setopt($channel1, CURLOPT_POSTFIELDS, $fields1);
    curl_setopt($channel1, CURLOPT_CONNECTTIMEOUT, 100);
    curl_setopt($channel1, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($channel1, CURLOPT_TIMEOUT, 1000);
    $response1 = curl_exec($channel1);

//decode the json encode response from the server.
    $jsonResponse = json_decode($response1, true);
    $js = $jsonResponse['results'];
//operation was successful get the token from the reponse.
    if ($js['success'] == false) {
        //handle the failure case.
        echo $js['error']['errorMsg'];
        die;
    }

    $sessionId = $js['result']['sessionName'];
    curl_close($channel1);

    $endpointUrl3 = $node_URL . "ddt/webservice?operation=doRunAction&argument=$encodedArguments&sessionName=$sessionId";
//username of the user who is to logged in. 
//$fields1 =array('operation'=>'login','username'=>'admin','accessKey'=>'8a1648f04aa57170bd5021de220bd859');

    $headers[] = 'Content-Type: application/json';
    $channel1 = curl_init();
//curl_setopt($channel1, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($channel1, CURLOPT_URL, $endpointUrl3);
    curl_setopt($channel1, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($channel1, CURLOPT_POST, false);
//curl_setopt($channel1, CURLOPT_POSTFIELDS, $fields1);
    curl_setopt($channel1, CURLOPT_CONNECTTIMEOUT, 100);
    curl_setopt($channel1, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($channel1, CURLOPT_TIMEOUT, 1000);
    echo $response1 = curl_exec($channel1);
    $js = json_decode($response1, true);
    $outputValue=$js["result"];
    return $js;
////decode the json encode response from the server.
//    $jsonResponse = json_decode($response1, true);
//    $js = $jsonResponse['results'];
////operation was successful get the token from the reponse.
//    if ($js['success'] == false) {
//        //handle the failure case.
//        echo $js['error']['errorMsg'];
//        die;
//    }
//    else{
//        return $js['result'];
//    }
//
//    curl_close($channel1);
}

?>
