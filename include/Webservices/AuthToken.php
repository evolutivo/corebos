<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

function vtws_getchallenge($username){
	global $adb;

	$authToken = uniqid();
	$servertime = time();
	$expireTime = time()+(60*5);

	$user = new Users();
	$userid = $user->retrieve_user_id($username);
	if (empty($userid)) {
		throw new WebServiceException(WebServiceErrorCode::$AUTHREQUIRED,'Given user cannot be found');
	}

	$adb->query("LOCK TABLE vtiger_ws_userauthtoken WRITE");
	$get_token = $adb->pquery("SELECT * FROM vtiger_ws_userauthtoken WHERE userid = ?", array($userid) );

	if($adb->num_rows($get_token) == 1) {
		$user_data = $adb->fetchByAssoc($get_token, 0);
		$expired_time_unix = $user_data['expiretime'];
		$expiretime = new DateTime("@$expired_time_unix"); 

		$now = new DateTime();
		$diff = $expiretime->diff($now);

		if($diff->invert == 0) {

			$sql = "delete from vtiger_ws_userauthtoken where userid=?";
			$adb->pquery($sql,array($userid));

			$sql = "insert into vtiger_ws_userauthtoken(userid,token,expireTime) values (?,?,?)";
			$adb->pquery($sql,array($userid,$authToken,$expireTime));

		} else {
			$authToken = $user_data['token'];
			$expireTime = $expired_time_unix;
		}
	} else {
		$sql = "insert into vtiger_ws_userauthtoken(userid,token,expireTime) values (?,?,?)";
		$adb->pquery($sql,array($userid,$authToken,$expireTime));	
	}

	$adb->query("UNLOCK TABLES");
	return array("token"=>$authToken,"serverTime"=>$servertime,"expireTime"=>$expireTime);
}
