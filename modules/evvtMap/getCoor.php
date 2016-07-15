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
global $adb, $currentModule;
$rdo = array();
$mapid = vtlib_purify($_REQUEST['crmid']);
if (!empty($mapid)) {
	$result = $adb->pquery('select lat,lng from vtiger_evvtmap inner join vtiger_crmentity on crmid = evvtmapid where deleted = 0 and evvtmapid = ?',array($mapid));
	if ($adb->num_rows($result)>0) {
		$info = $adb->fetch_array($result);
		$rdo['lat'] = $info['lat'];
		$rdo['lng'] = $info['lng'];
	}
}
echo json_encode($rdo);
?>
