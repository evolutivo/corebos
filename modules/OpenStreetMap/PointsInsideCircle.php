<?php
/*************************************************************************************************
 * Copyright 2011-2013 JPL TSolucio, S.L.  --  This file is a part of evvtMap vtiger CRM extension.
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
 *************************************************************************************************
 *  Module       : evvtMap
 *  Version      : 5.4.0
 *  Author       : JPL TSolucio, S. L.
 *************************************************************************************************/
require_once 'modules/OpenStreetMap/lib/utils.inc.php';
global $adb, $modulename, $current_user;

$x=vtlib_purify($_REQUEST['pointx']);
$y=vtlib_purify($_REQUEST['pointy']);
$radius=vtlib_purify($_REQUEST['radius']);
$modulename=vtlib_purify($_REQUEST['modulename']);
$nonAdminAccessControlQuery = getNonAdminAccessControlQuery($modulename, $current_user);
$content='';
switch($modulename) {
case "Contacts":
$query = "SELECT m.evvtmapid,
    ( 3959 * acos( cos( radians($x) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($y) ) + sin( radians($x) ) * sin( radians( lat ) ) ) ) AS distance,
    concat(cd.firstname,' ',cd.lastname) as name,concat(cd.email,' ',cd.phone) as contactdata,concat(ca.mailingcity ,' ', ca.mailingzip,' ',
        ca.mailingcountry ,' ', ca.mailingstate ,' ', ca.mailingstreet) as address
        FROM vtiger_evvtmap m
        JOIN vtiger_contactdetails cd on m.evvtmapid=cd.contactid
        JOIN vtiger_contactaddress ca on ca.contactaddressid=cd.contactid
        join vtiger_crmentity on vtiger_crmentity.crmid=cd.contactid and vtiger_crmentity.deleted=0
        {$nonAdminAccessControlQuery}
        HAVING distance < $radius ORDER BY distance LIMIT 0 , 20";
    break;
case "Leads":
  $query = "SELECT m.evvtmapid,
    ( 3959 * acos( cos( radians($x) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($y) ) + sin( radians($x) ) * sin( radians( lat ) ) ) ) AS distance,
        concat(l.firstname,' ',l.lastname, ' - ',l.company)  as name,concat(l.email,' ',la.phone,' ; ',la.mobile) as contactdata,concat(la.city ,' ', la.code,' ',
        la.country ,' ', la.state ,' ', la.lane) as address
        FROM vtiger_evvtmap m
        JOIN vtiger_leaddetails l on m.evvtmapid=l.leadid
        JOIN vtiger_leadaddress la on la.leadaddressid=l.leadid
        join vtiger_crmentity on vtiger_crmentity.crmid=l.leadid and vtiger_crmentity.deleted=0
        {$nonAdminAccessControlQuery}
        HAVING distance < $radius ORDER BY distance LIMIT 0 , 20";
    break;
case "HelpDesk":
  	$query="SELECT m.evvtmapid,
    ( 3959 * acos( cos( radians($x) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($y) ) + sin( radians($x) ) * sin( radians( lat ) ) ) ) AS distance,
        a.accountname as name,concat(a.email1,' ',a.phone) as contactdata,concat(ac.bill_city ,' ', ac.bill_code,' ',
        ac.bill_country ,' ', ac.bill_state ,' ', ac.bill_street) as address
        FROM vtiger_evvtmap m
        JOIN vtiger_account a on m.evvtmapid=a.accountid
        JOIN vtiger_accountbillads ac on ac.accountaddressid=a.accountid
        HAVING distance < $radius ";
  	$query.=' UNION ';
  	$query.="SELECT m.evvtmapid,
  			( 3959 * acos( cos( radians($x) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($y) ) + sin( radians($x) ) * sin( radians( lat ) ) ) ) AS distance,
		    concat(cd.firstname,' ',cd.lastname) as name,concat(cd.email,' ',cd.phone) as contactdata,concat(ca.mailingcity ,' ', ca.mailingzip,' ',
	        ca.mailingcountry ,' ', ca.mailingstate ,' ', ca.mailingstreet) as address
	        FROM vtiger_evvtmap m
	        JOIN vtiger_contactdetails cd on m.evvtmapid=cd.contactid
	        JOIN vtiger_contactaddress ca on ca.contactaddressid=cd.contactid
  			HAVING distance < $radius ORDER BY distance LIMIT 0 , 20";
   	break;
case "Accounts":
default:
  $query = "SELECT m.evvtmapid,
    ( 3959 * acos( cos( radians($x) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($y) ) + sin( radians($x) ) * sin( radians( lat ) ) ) ) AS distance,
        a.accountname as name,concat(a.email1,' ',a.phone) as contactdata,concat(ac.bill_city ,' ', ac.bill_code,' ',
        ac.bill_country ,' ', ac.bill_state ,' ', ac.bill_street) as address
        FROM vtiger_evvtmap m
        JOIN vtiger_account a on m.evvtmapid=a.accountid
        JOIN vtiger_accountbillads ac on ac.accountaddressid=a.accountid
        join vtiger_crmentity on vtiger_crmentity.crmid=a.accountid and vtiger_crmentity.deleted=0
        {$nonAdminAccessControlQuery}
        HAVING distance < $radius ORDER BY distance LIMIT 0 , 20";
    break;
}
$res = $adb->pquery($query,array());
for($i=0;$i<$adb->num_rows($res);$i++) {
	$mapid = $adb->query_result($res,$i,'evvtmapid');
	$setype = getSalesEntityType($mapid);
	$content.='<br/><b><a href="index.php?action=DetailView&record='.$mapid.'&module='.$setype.'">'.$adb->query_result($res,$i,'name').'</a></b> '.$adb->query_result($res,$i,'contactdata').' '.$adb->query_result($res,$i,'address');
	if ($modulename=="HelpDesk" or $modulename=="directticket") {
		$content.=evvt_getTicketInformation($mapid);
	}
}
echo $content;
?>
