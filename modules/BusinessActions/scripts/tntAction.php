<?php

ini_set('display_errors', 'On');
ini_set('error_reporting', 'On');
include_once('data/CRMEntity.php');
include_once('modules/Map/Map.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
require_once('modules/Actions/scripts/tntIntegration.php');
global $adb,$log,$current_user,$root_directory;

$log = & LoggerManager::getLogger("index");
$current_user = new Users();
$result = $current_user->retrieveCurrentUserInfoFromFile(1);
$request = array();

$recordid = $argv[1];
$mapid = $argv[2];
$recid = explode(',', $recordid);
for ($j = 0; $j < count($recid); $j++) {
    $recordid = $recid[$j];
        
    $mapfocus=  CRMEntity::getInstance("Map");
    $mapfocus->retrieve_entity_info($mapid,"Map");
    $sql=$mapfocus->getMapSQL();
    $result = $adb->pquery($sql,array($recordid));
    $nr=$adb->num_rows($result);
    $name1= $adb->query_result($result,0,'accountname');
    $projectno= $adb->query_result($result,0,'praticaid');
    $projectno=str_pad($projectno,12,"0",STR_PAD_LEFT);
    $commessa =$adb->query_result($result,0,'processtemplatename');
    //$bar=$commessa.'   '.$projectno;
    $bar=$projectno;
    $street=$adb->query_result($result,0,'ship_street');
    $code=$adb->query_result($result,0,'ship_code');
    $city=$adb->query_result($result,0,'ship_city');
    $state=$adb->query_result($result,0,'ship_state');
    $ship_country=$adb->query_result($result,0,'ship_country');

    $add_rasc=$adb->query_result($result,0,'indirizzo');
    $postcode_rasc=$adb->query_result($result,0,'cap');
    $name_rasc=$adb->query_result($result,0,'regionalascname');
    $country_rasc=$adb->query_result($result,0,'stato');
    $town_rasc=$adb->query_result($result,0,'citta');
    $province_rasc=$adb->query_result($result,0,'provincia');

    $islockable=$adb->query_result($result,0,'islockable');
    $productdesc=$adb->query_result($result,0,'productdesc');
    $description=$adb->query_result($result,0,'proj_desc');
    $original_date=$adb->query_result($result,0,'dataautorizzazione');
    
    $lockindex=$adb->query_result($result,0,'lockindex');
    $codicearticolo=$adb->query_result($result,0,'codicearticolo');
    $sitovertical=$adb->query_result($result,0,'sitovertical');
    $sitoprovenienza=$adb->query_result($result,0,'sitoprovenienza');
    $purchasecostdetail=$adb->query_result($result,0,'purchasecostdetail');

    $brnd=$adb->query_result($result,0,'linktorasc');
    $pesogr=$adb->query_result($result,0,'pesogr');
    $idpratica=$adb->query_result($result,0,'praticaid');
    $idorig=$adb->query_result($result,0,'idorig');
    $tnterror=$adb->query_result($result,0,'tnterror');
    
    if(strtolower($tnterror)=='m' || $tnterror=='1'){
        $action='M';
    }
    else{ // if it is the first time calling tnt OR recalling after error
        $action='I';
    }
    if($tnterror!='1'){
        $arr_tnt=tntIntegration($recordid,$street,$code,$name1,$ship_country,$city,$state
                ,$add_rasc,$postcode_rasc,$name_rasc,$country_rasc,$town_rasc,$province_rasc,
                $pesogr,$idpratica,$action,$sitoprovenienza,$purchasecostdetail);
        if(empty($arr_tnt) ||  !isset($arr_tnt) || $arr_tnt==null){
            $adb->pquery("Update vtiger_project"
                    . " set tnterror=?"
                    . " where vtiger_project.idorig=?",array('error',$idorig));
        }
        else if($arr_tnt['CODE']!='' && $arr_tnt['CODE']!=null){
            //update tnterror
            $adb->pquery("Update vtiger_project"
                    . " set tnterror=?"
                    . " where vtiger_project.idorig=?",array($arr_tnt['MESSAGE'],$idorig));
        }
        else{
            $adb->pquery("Update vtiger_project"
                    . " set tnterror=?"
                    . " where vtiger_project.idorig=?",array('1',$idorig));
//            $adb->pquery("Update vtiger_project"
//                    . " set tnterror=?"
//                    . " where vtiger_project.idorig=?",array('1',$idorig));
            

            }
    }
}
