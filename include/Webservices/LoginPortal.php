<?php
/*************************************************************************************************
 * Copyright 2012-2014 JPL TSolucio, S.L.  --  This file is a part of coreBOSCP.
* You can copy, adapt and distribute the work under the "Attribution-NonCommercial-ShareAlike"
* Vizsage Public License (the "License"). You may not use this file except in compliance with the
* License. Roughly speaking, non-commercial users may share and modify this code, but must give credit
* and share improvements. However, for proper details please read the full License, available at
* http://vizsage.com/license/Vizsage-License-BY-NC-SA.html and the handy reference for understanding
* the full license at http://vizsage.com/license/Vizsage-Deed-BY-NC-SA.html. Unless required by
* applicable law or agreed to in writing, any software distributed under the License is distributed
* on an  "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and limitations under the
* License terms of Creative Commons Attribution-NonCommercial-ShareAlike 3.0 (the License).
*************************************************************************************************/
include_once 'include/Webservices/AuthToken.php';

	function vtws_loginportal($username,$password) {
		$uname = 'portal';
		$user = new Users();
		$userId = $user->retrieve_user_id($uname);
		
		if (empty($userId)) {
			throw new WebServiceException(WebServiceErrorCode::$INVALIDUSERPWD,"User $uname does not exist");
		}
		global $adb, $log;
		$log->debug('Entering LoginPortal function with parameter username: '.$username);
		
		$ctors = $adb->pquery('select id
			from vtiger_portalinfo
			inner join vtiger_customerdetails on vtiger_portalinfo.id=vtiger_customerdetails.customerid
			inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_portalinfo.id
			where vtiger_crmentity.deleted=0 and user_name=? and user_password=?
			  and isactive=1 and vtiger_customerdetails.portal=1',array($username,$password));
		if ($ctors and $adb->num_rows($ctors)==1) {
			$user = $user->retrieveCurrentUserInfoFromFile($userId);
			if($user->status != 'Inactive') {
				$result = $adb->query("SELECT id FROM vtiger_ws_entity WHERE name = 'Contacts'");
				$ctowsid = $adb->query_result($result,0,'id');
				$ctocmrid = $adb->query_result($ctors,0,'id');
				$result = $adb->query("SELECT id FROM vtiger_ws_entity WHERE name = 'Users'");
				$wsid = $adb->query_result($result,0,'id');
				$accessinfo = vtws_getchallenge($uname);
				$sessionManager = new SessionManager();
				$sid = $sessionManager->startSession(null,false);
				if(!$sid){
					throw new WebServiceException(WebServiceErrorCode::$SESSIONIDINVALID,'Could not create session');
				}
				$sessionManager->set("authenticatedUserId", $userId);
				$accessinfo['sessionName'] = $sessionManager->getSessionId();
				$accessinfo['user'] = array(
					'id' => $wsid.'x'.$userId,
					'user_name' => $user->column_fields['user_name'],
					'accesskey' => $user->column_fields['accesskey'],
					'contactid' => $ctowsid.'x'.$ctocmrid,
				);
				return $accessinfo;
			} else {
				throw new WebServiceException(WebServiceErrorCode::$AUTHREQUIRED,'Given user is inactive');
			}
		}
		throw new WebServiceException(WebServiceErrorCode::$AUTHREQUIRED,"Given contact is inactive");
	}

        function vtws_logincustomerportal($username,$password) {
		
		global $adb, $log;
		$log->debug('Entering LoginPortal function with parameter username: '.$username);
//INNER JOIN vtiger_account ON vtiger_account.accountid = vtiger_contactdetails.accountid
		$ctors = $adb->pquery('select id,vtiger_account.accountid,cf_812
			from vtiger_portalinfo
			inner join vtiger_customerdetails on vtiger_portalinfo.id=vtiger_customerdetails.customerid
			inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_portalinfo.id
			INNER JOIN vtiger_contactdetails ON vtiger_crmentity.crmid = contactid
                        INNER JOIN vtiger_account ON vtiger_account.accountid = vtiger_contactdetails.accountid
                        INNER JOIN vtiger_accountscf ON vtiger_accountscf.accountid = vtiger_account.accountid
                        where vtiger_crmentity.deleted=0 and user_name=? and user_password=?
			  and isactive=1 and vtiger_customerdetails.portal=1',array($username,$password));
               if ($ctors and $adb->num_rows($ctors)==1) {
                   $type_account = $adb->query_result($ctors,0,'cf_812');
                   switch($type_account){
                       case 'SDF Italy';
                           $temp_user='italy.dealer';
                           $temp_grp='17';
                           break;
                       case 'SDF Germany';
                           $temp_user='germany.dealer';
                           $temp_grp='44';
                           break;
                       case 'SDF France';
                           $temp_user='france.dealer';
                           $temp_grp='38';
                           break;
                       case 'SDF UK';
                           $temp_user='england.dealer';
                           $temp_grp='41';
                           break;
                       case 'SDF Iberica';
                           $temp_user='spain.dealer';
                           $temp_grp='51';
                           break;
                       case 'SDF Export';
                           $temp_user='export.dealer';
                           $temp_grp='54';
                           break;
                  
 }
                    $uname = $temp_user;
                    $user = new Users();
                    $userId = $user->retrieve_user_id($uname);
                    if (empty($userId)) {
                            throw new WebServiceException(WebServiceErrorCode::$INVALIDUSERPWD,"User $uname does not exist");
                    }
//                    require_once("include/utils/GetUserGroups.php");
//                    $userGroupFocus=new GetUserGroups();
//                    $userGroupFocus->getAllUserGroups($userId);  
//                    $current_user_groups= $userGroupFocus->user_groups;
                    
			$user = $user->retrieveCurrentUserInfoFromFile($userId);
			if($user->status != 'Inactive') {
				$result = $adb->query("SELECT id FROM vtiger_ws_entity WHERE name = 'Contacts'");
				$ctowsid = $adb->query_result($result,0,'id');
                                $result_p = $adb->query("SELECT id FROM vtiger_ws_entity WHERE name = 'Accounts'");
				$ptowsid = $adb->query_result($result_p,0,'id');
				$ctocmrid = $adb->query_result($ctors,0,'id');
                                $accountid = $adb->query_result($ctors,0,'accountid');
				$result = $adb->query("SELECT id FROM vtiger_ws_entity WHERE name = 'Users'");
				$wsid = $adb->query_result($result,0,'id');
                                $result_grp = $adb->query("SELECT id FROM vtiger_ws_entity WHERE name = 'Groups'");
				$wsid_grp = $adb->query_result($result_grp,0,'id');
				$accessinfo = vtws_getchallenge($uname);
				$sessionManager = new SessionManager();
				$sid = $sessionManager->startSession(null,false);
				if(!$sid){
					throw new WebServiceException(WebServiceErrorCode::$SESSIONIDINVALID,'Could not create session');
				}
				$sessionManager->set("authenticatedUserId", $userId);
				$accessinfo['sessionName'] = $sessionManager->getSessionId();
				$accessinfo['user'] = array(
					'id' => $wsid.'x'.$userId,
                                        'groupid' => $wsid_grp.'x'.$temp_grp,
					'user_name' => $user->column_fields['user_name'],
					'accesskey' => $user->column_fields['accesskey'],
					'contactid' => $ctowsid.'x'.$ctocmrid,
                                        'patientid' => $ptowsid.'x'.$accountid,
				);
                                $logintime=date('Y-m-d H:i:s');
                                $logininfo = $adb->pquery("Insert into vtiger_portalinfologin (contactid,logintime)"
                                        . "  values(?,?)",
                                        array($ctocmrid,$logintime));
                                $adb->pquery("Update vtiger_contactdetails"
                                        . " set lastaccess=? "
                                        . " where contactid=?",
                                        array($logintime,$ctocmrid));
				return $accessinfo;
			} else {
				throw new WebServiceException(WebServiceErrorCode::$AUTHREQUIRED,'Given user is inactive');
			}
		}
		throw new WebServiceException(WebServiceErrorCode::$AUTHREQUIRED,"Given contact is inactive");
	}        
        
        function getlabelstranslated($totranslate, $portal_language, $module, $user){

                global $log,$adb,$default_language;
                $log->debug("Entering function vtws_gettranslation");
                $language = $portal_language;
                if (!is_array($totranslate)) $totranslate=array($totranslate);
                $mod_strings=array();
                $app_strings=array();
                // $app_strings
                $applanguage_used = $language;
                if (file_exists("include/language/$language.lang.php"))
                        @include("include/language/$language.lang.php");
                else {
                        $log->warn("Unable to find the application language file for language: ".$language);
                        $applanguage_used = $default_language;
                        if (file_exists("include/language/$default_language.lang.php"))
                                @include("include/language/$default_language.lang.php");
                        else
                                $applanguage_used=false;
                }

                // $mod_strings
                $modlanguage_used = '';
                if (!empty($module)) {
                        $modlanguage_used = $language;
                        if (file_exists("modules/$module/language/$language.lang.php")) {
                                @include("modules/$module/language/$language.lang.php");
                        } else {
                                $log->warn("Unable to find the module language file for language/module: $language/$module");
                                $modlanguage_used = $default_language;
                                if (file_exists("modules/$module/language/$default_language.lang.php"))
                                        @include("modules/$module/language/$default_language.lang.php");
                                else
                                        $modlanguage_used = false;
                        }
                }

                if (!$applanguage_used and !$modlanguage_used)
                  return $totranslate;  // We can't find language file so we return what we are given

                $translated=array();
                foreach ($totranslate as $key=>$str) {
                        if ($mod_strings[$str] != '')
                                $tr = $mod_strings[$str];
                        elseif ($app_strings[$str] != '')
                                $tr = $app_strings[$str];
                        elseif ($mod_strings[$key] != '')
                                $tr = $mod_strings[$key];
                        elseif ($app_strings[$key] != '')
                                $tr = $app_strings[$key];
                        else
                                $tr = $str;
                        $translated[$key] = $tr;
                }

                $log->debug("Leaving function vtws_gettranslation");
                return $translated;
        }

        function getTranslationFile($user_logged,$all_modules,$translate){

            $user=explode('x',$user_logged);
            global $log,$adb,$default_language;
            $togetlang= $adb->pquery("select cf_969 from vtiger_contactscf where contactid=?",
                            array($user[1]));
            $langportal1=  $adb->query_result($togetlang,0,0);
            $langportal=$langportal1;
            if($translate){
                @include("include/language/$langportal.lang.php");
                $all_translated_str=array();
                $all_translated_str=array_merge($all_translated_str, $app_list_strings['moduleList']); 
                for($c=0;$c<sizeof($all_modules);$c++){
                    for($i=0;$i<sizeof($all_modules[$c]['values']);$i++){
                        $module_arr=$all_modules[$c]['values'];
                        $modulename=$module_arr[$i]['item'];
                        @include("modules/$modulename/language/$langportal.lang.php");
                        $all_translated_str=array_merge($all_translated_str, $mod_strings); 
                    }
                }
                $langportal=  substr($langportal,0,2);
                $folder="/var/www/html/portal/app/locales/$langportal"; 
                if(!file_exists($folder)){
                    mkdir("/var/www/html/portal/app/locales/$langportal");
                }
                $fb = fopen($folder."/translation.json", "w") or die("can't open file");
                fwrite($fb,json_encode(array_map('myfunction',$all_translated_str)));
            }
            return $langportal;
        }

        function myfunction($v)
        {
            $t=htmlspecialchars($v);
            if(empty($t))
            $t=utf8_encode($v);
            return $t;
        }

?>
