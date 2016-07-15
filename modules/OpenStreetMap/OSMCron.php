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
require_once('include/utils/utils.php');
require_once('include/logging.php');
require_once('modules/evvtMap/lib/GeoCoder.inc.php');

global $adb, $log;

$current_user = Users::getActiveAdminUser();

$log =& LoggerManager::getLogger('evvtMapCron');
$log->info("Invoked evvtMapCron");

$lastrun = $adb->getone('select lastrun from vtiger_evvtmapdefaults where uid=0');
$gc = new GeoCoder();

// Get any updated records to check for new coordinates
$query = 'select contactaddressid as id, mailingcity as city, mailingzip as code, mailingcountry as country, mailingstate as state, mailingstreet as street, setype as etype
		from vtiger_contactaddress
		inner join vtiger_crmentity on crmid=contactaddressid and deleted = 0
		WHERE modifiedtime > ? and contactaddressid in (select evvtmapid from vtiger_evvtmap) ';
$query.= ' UNION select accountaddressid as id, bill_city as city, bill_code as code, bill_country as country, bill_state as state, bill_street as street, setype as etype
		from vtiger_accountbillads
		inner join vtiger_crmentity on crmid=accountaddressid and deleted = 0
		WHERE modifiedtime > ? and accountaddressid in (select evvtmapid from vtiger_evvtmap) ';
$query.= ' UNION select leadaddressid as id, city, code, country, state, lane as street, setype as etype
		from vtiger_leadaddress
		inner join vtiger_crmentity on crmid=leadaddressid and deleted = 0
		WHERE modifiedtime > ? and leadaddressid in (select evvtmapid from vtiger_evvtmap) ';
$result = $adb->pquery($query, array($lastrun,$lastrun,$lastrun));
if ($adb->num_rows($result) > 0) {
	$log->info("evvtMapCron: Total updated rows to locate: " . $adb->num_rows($result));
	$locations = Array();
	while($row = $adb->fetch_array($result)) {
		$locations[] = Array($row['id'],$row['state'],$row['city'],$row['code'],$row['street'],$row['country']);
	}
	$return = $gc->populateCache($locations);
	if ($return == "LIMIT_REACHED") {
		exit;
	}
}

// update last run information
$adb->query('update vtiger_evvtmapdefaults set lastrun = now() where uid=0');

// Get a list of new/uncalculated ids to get location data for
$query = 'select contactaddressid as id, mailingcity as city, mailingzip as code, mailingcountry as country, mailingstate as state, mailingstreet as street, setype as etype
		from vtiger_contactaddress
		inner join vtiger_crmentity on crmid=contactaddressid and deleted = 0
		WHERE contactaddressid not in (select evvtmapid from vtiger_evvtmap) ';
$query.= ' UNION select accountaddressid as id, bill_city as city, bill_code as code, bill_country as country, bill_state as state, bill_street as street, setype as etype
		from vtiger_accountbillads
		inner join vtiger_crmentity on crmid=accountaddressid and deleted = 0
		WHERE accountaddressid not in (select evvtmapid from vtiger_evvtmap) ';
$query.= ' UNION select leadaddressid as id, city, code, country, state, lane as street, setype as etype
		from vtiger_leadaddress
		inner join vtiger_crmentity on crmid=leadaddressid and deleted = 0
		WHERE leadaddressid not in (select evvtmapid from vtiger_evvtmap) ';
$result = $adb->pquery($query, array());
if ($adb->num_rows($result) > 0) {
	$log->info("evvtMapCron: Total new/uncalculated rows to locate: " . $adb->num_rows($result));
	$locations = Array();
	while($row = $adb->fetch_array($result)) {
		$locations[] = Array($row['id'],$row['state'],$row['city'],$row['code'],$row['street'],$row['country']);
	}
	$return = $gc->populateCache($locations);
	if ($return == "LIMIT_REACHED") {
		exit;
	}
}

// run a cleanup, checking for deleted records and removing them from the location table
$adb->query('delete vtiger_evvtmap from vtiger_evvtmap join vtiger_crmentity on crmid = evvtmapid where deleted = 1');

?>
