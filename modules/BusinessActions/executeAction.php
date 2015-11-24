<?php
include_once('data/CRMEntity.php');
include_once('modules/BusinessActions/BusinessActions.php');
include_once('modules/Sequencers/Sequencers.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
global $adb,$log,$current_user;
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
//error_reporting(0);
ini_set('display_errors','off');
$actionid=$_REQUEST['actionid'];
$recordid=$_REQUEST['recordid'];
$outputType=$_REQUEST['outputType'];
$confirmVal=$_REQUEST['confirmVal'];
$module=  getSalesEntityType($actionid);
if($module=='BusinessActions')
    $methodCalled="executeAction";
elseif($module=='Sequencers')
    $methodCalled="executeSequencer";

$focus=CRMEntity::getInstance($module);
$focus->retrieve_entity_info($actionid,$module);
$response=$focus->$methodCalled($recordid,$outputType,'',$confirmVal,$actionid);
echo $response;
?>
