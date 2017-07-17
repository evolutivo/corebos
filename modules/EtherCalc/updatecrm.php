<?php
chdir("../..");
//ini_set("display_errors",'On');
include_once("include/database/PearDatabase.php");
include_once("include/utils/utils.php");
include_once("modules/GlobalVariable/GlobalVariable.php");
global $current_user;
$current_user->id=1;
$userName=GlobalVariable::getVariable('ecusername','');
$userAccessKey=GlobalVariable::getVariable('ecuseraccesskey', '');
$endpointUrl=GlobalVariable::getVariable('ecendpointurl', '');
$ethercalcendpoint=GlobalVariable::getVariable('ecendpoint', '');
$idnew=$_REQUEST['id'];
global $adb;
$query1=$adb->query("select name,spid,id from ethercalc where id=$idnew");
$name=$adb->query_result($query1,0,0);
$spid=$adb->query_result($query1,0,1);
$opspread=$adb->query_result($query1,0,2);
$getcolumns=$adb->query("select modulecolumns,othercolumns,module1 from vtiger_spreadsheets where spreadsheetsid=$spid");
$modcols=$adb->query_result($getcolumns,0,0);
$ocols=$adb->query_result($getcolumns,0,1);
$usemodule=$adb->query_result($getcolumns,0,2);
$modcolsnew=explode(",",$modcols);
$colsarr=array();
$othercols=explode(",",$ocols);
for($z=0;$z<count($modcolsnew);$z++){
	$expel=explode("::",$modcolsnew[$z]);
	$expelact=$expel[1];
	array_push($colsarr,$expelact);
}

$arr1dim=count($colsarr)+count($othercols);
$arr2dim=3+count($colsarr);
//echo $arr1dim;
//echo count($othercols);
//exit;
$namenew=$name.".csv.json";
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $namenew);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

$response = curl_exec($ch);
curl_close($ch);

$respdecoded=json_decode($response);
$allrows=array();
$arrvals=array();
$arrvalsall=array();
$arrvalsjson=array();
$alljson=array();
$k=0;
$h=0;
//var_dump($respdecoded);
//exit;
foreach($respdecoded as $key=>$value){
	if($key==0){
		$arrvals=array_values(array_slice($value,3,$arr1dim));
		$arrvalsjson=array_values(array_slice($value,$arr2dim));
		$arrvalsall=array_values($value);
	}
	else {    
		$z=0;
		$l=0;
	foreach(array_slice($value,3,$arr1dim) as $key1=>$value1){
		//echo $arrvals[$z];
		//echo '<br>';
		//echo $key1;
		//echo ' ';
		//echo $value1;
		//echo '<br>';
		$checkfld=$arrvals[$key1];

		$fldtype=$adb->query("select * from vtiger_field where fieldname='$checkfld' and (uitype=5 || uitype=6)");
		$fldtime=$adb->query("select * from vtiger_field where fieldname='$checkfld' and (uitype=2 || uitype=14)");
		if($adb->num_rows($fldtype)>0 && is_numeric($value1)){
			//echo 'checkfield'.$checkfld;
			//echo 'valore1'.$value1.'valore1';
			$valuenew1=($value1 - 25569) * 86400;
			//echo 'valuenew1'.$valuenew1;
			//echo 'vlera1'.$valuenew1;
			if(substr($valuenew1,1)!="-")
			$value1=gmdate("Y-m-d", $valuenew1);
			//echo 'vlerafundit'.$value1;
			//exit;
		}
		else if($adb->num_rows($fldtime)>0 && is_numeric($value1)){
			//echo "value1ketu";
			//echo $value1;
			//exit;
			$hourformatted=$value1;
			//echo $hourformatted;
			//echo float($hourformatted);
			$value1=gmdate('H:i:s', floor($hourformatted * 86400));
			//echo $value1;
			//exit;

		}
		$allrows[$k][$arrvals[$z]]=str_replace("#",",",$value1);
		$z++;
		//}
	}
	foreach(array_slice($value,$arr2dim) as $key2=>$value2){
        $alljson[$h][$arrvalsjson[$l]]=str_replace("#",",",$value2);
        $l++;
	}
	$k++;
	$h++;

    }
}
function generaspreadsheet($allstring,$ethercalcendpoint,$adb,$opspread){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ethercalcendpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch,CURLOPT_POSTFIELDS,$allstring);
$response=curl_exec($ch);
$newresp=substr($ethercalcendpoint,0,-1).substr($response,1);
echo $newresp;
$adb->query("update ethercalc set name='$newresp',createdtime=NOW() where id=$opspread");
}

function callnew($url, $params, $type = "GET") {

    $is_post = 0;
    //var_dump($params);
    if ($type == "POST") {
        $is_post = 1;
        $post_data = $params;
    } else {
        $url = $url . "?" . http_build_query($params);
    }
    $ch = curl_init($url);
    if (!$ch) {
        die("Cannot allocate a new PHP-CURL handle");
    }
    if ($is_post) {
        curl_setopt($ch, CURLOPT_POST, $is_post);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);

    $return = null;
    if (curl_error($ch)) {
        $return = false;
    } else {
        $return = json_decode($data, true);
    }

    curl_close($ch);

    return $return;
}
$sessionData = callnew($endpointUrl, array("operation" => "getchallenge", "username" => $userName));
$challengeToken = $sessionData['result']['token'];
$generatedKey = md5($challengeToken . $userAccessKey);
$dataDetails = callnew($endpointUrl, array("operation" => "login", "username" => $userName, "accessKey" => $generatedKey), "POST");
$sessionid = $dataDetails['result']['sessionName'];
$allnewrows=implode(",",$arrvalsall)."\n";
$rowact="";
//var_dump($alljson);
//exit;
for($k=0;$k<count($allrows);$k++){
	//var_dump($allrows);
	//exit;
	$crthis=array_slice($allrows[$k],0,count($colsarr));
	if(!is_null($alljson[$k]) && $alljson[$k]!='')
	$crthis["description"]=json_encode($alljson[$k]);
	$crthisnew=json_encode($crthis);
	//var_dump($alljson[$k]);
	//exit;
	if($respdecoded[$k+1][0]==''){
	$createprojectquery = callnew($endpointUrl, array("operation" => "create", "sessionName" => $sessionid, "elementType" => $usemodule,"element"=>$crthisnew),"POST");
	var_dump($createprojectquery);
	//exit;
	$crmidact=$createprojectquery["result"]["id"];
	$createdtimeact=$createprojectquery["result"]["createdtime"];
	if($createdtimeact=='')
	$createdtimeact=$createprojectquery["result"]["CreatedTime"];	
	$modifiedtimeact=$createprojectquery["result"]["modifiedtime"];
	if($modifiedtimeact=='')
	$modifiedtimeact=$createprojectquery["result"]["ModifiedTime"];
	$rowact=$rowact.$crmidact.",".$createdtimeact.",".$modifiedtimeact.",".implode(",",$allrows[$k])."\n";
    }
    else {
    	$rowact=$rowact.$respdecoded[$k+1][0].",".$respdecoded[$k+1][1].",".$respdecoded[$k+1][2].",".implode(",",$allrows[$k])."\n";
    	$crthis["id"]=$respdecoded[$k+1][0];
    	$crthisnew=json_encode($crthis);
    	var_dump($crthisnew);
    	//exit;
    	 $updatequerytask = callnew($endpointUrl, array("operation" => "update", "sessionName" => $sessionid,"element"=>$crthisnew),"POST");
    }
}
echo "nefundfare";
var_dump($rowact);
echo "nefundfare";
if($rowact!=""){
$generastring=$allnewrows.$rowact;
generaspreadsheet($generastring,$ethercalcendpoint,$adb,$opspread);
}
?>