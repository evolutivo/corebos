<?php
/*************************************************************************************************
 * Copyright 2015 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS customizations.
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
 *  Version      : 1.0
 *  Author       : JPL TSolucio, S. L.
 *************************************************************************************************/
require_once 'include/utils/utils.php';
require_once 'include/utils/CommonUtils.php';
global $adb, $log, $current_user;

$functiontocall = vtlib_purify($_REQUEST['functiontocall']);

switch ($functiontocall) {
	case 'getFieldAutocomplete':
		include_once 'include/Webservices/CustomerPortalWS.php';
		$searchinmodule = vtlib_purify($_REQUEST['searchinmodule']);
		$fields = vtlib_purify($_REQUEST['fields']);
		$returnfields = vtlib_purify($_REQUEST['returnfields']);
		$limit = vtlib_purify($_REQUEST['limit']);
		$filter = vtlib_purify($_REQUEST['filter']);
		if (is_array($filter)) {
			$term = $filter['filters'][0]['value'];
			$op = 'startswith';
		} else {
			$term = vtlib_purify($_REQUEST['term']);
			$op = 'startswith';
		}
		$retvals = getFieldAutocomplete($term, $op, $searchinmodule, $fields, $returnfields, $limit, $current_user);
		$ret = array();
		foreach ($retvals as $value) {
			$ret[] = array('crmid'=>$value['crmid'],'crmname'=>implode(',', $value['crmfields']));
		}
		break;
	case 'getReferenceAutocomplete':
		include_once 'include/Webservices/CustomerPortalWS.php';
		$searchinmodule = vtlib_purify($_REQUEST['searchinmodule']);
		$fields = vtlib_purify($_REQUEST['fields']);
		$returnfields = vtlib_purify($_REQUEST['returnfields']);
		$limit = vtlib_purify($_REQUEST['limit']);
		$filter = vtlib_purify($_REQUEST['filter']);
		if (is_array($filter)) {
			$term = $filter['filters'][0]['value'];
			$op = 'startswith';
		} else {
			$term = vtlib_purify($_REQUEST['term']);
			$op = 'startswith';
		}
		$ret = getReferenceAutocomplete($term, $op, $searchinmodule, $limit, $current_user);
		break;
        case 'getEvoReferenceAutocomplete':
		include_once 'include/Webservices/CustomerPortalWS.php';
		$searchinmodule =   vtlib_purify($_REQUEST['searchinmodule']);
		$fields =           vtlib_purify($_REQUEST['fields']);
		$returnfields =     vtlib_purify($_REQUEST['returnfields']);
		$limit =            vtlib_purify($_REQUEST['limit']);
		$filter =           vtlib_purify($_REQUEST['filter']);
                $field =            vtlib_purify($_REQUEST['field']);
		if (is_array($filter)) {
			$term = $filter['filters'][0]['value'];
			$op = 'contains';
		} else {
			$term = vtlib_purify($_REQUEST['term']);
			$op = 'contains';
		}
		$ret = getEvoReferenceAutocomplete($term, $op, $searchinmodule, $limit, $current_user,$field);
		break;
        case 'getEvoActualAutocomplete':
		include_once 'include/Webservices/CustomerPortalWS.php';
		$searchinmodule =   vtlib_purify($_REQUEST['searchinmodule']);
		$fields =           vtlib_purify($_REQUEST['fields']);
		$returnfields =     vtlib_purify($_REQUEST['returnfields']);
		$limit =            vtlib_purify($_REQUEST['limit']);
		$filter =           vtlib_purify($_REQUEST['filter']);
                $field =            vtlib_purify($_REQUEST['field']);
                $sel_values =       vtlib_purify($_REQUEST['sel_values']);
		if (is_array($filter)) {
			$term = $filter['filters'][0]['value'];
			$op = 'startswith';
		} else {
			$term = vtlib_purify($_REQUEST['term']);
			$op = 'startswith';
		}
		$ret = getEvoActualAutocomplete($sel_values, $op, $searchinmodule, $limit, $current_user,$field);
		break;
        case 'newEvoAutocomplete':
		include_once 'include/Webservices/CustomerPortalWS.php';
		$searchinmodule =   vtlib_purify($_REQUEST['searchinmodule']);
		$fields =           vtlib_purify($_REQUEST['fields']);
		$returnfields =     vtlib_purify($_REQUEST['returnfields']);
		$limit =            vtlib_purify($_REQUEST['limit']);
		$filter =           vtlib_purify($_REQUEST['filter']);
                $field =            vtlib_purify($_REQUEST['field']);
                $new_value =       vtlib_purify($_REQUEST['new_value']);
		if (is_array($filter)) {
			$term = $filter['filters'][0]['value'];
			$op = 'startswith';
		} else {
			$term = vtlib_purify($_REQUEST['term']);
			$op = 'startswith';
		}
		$ret = newEvoAutocomplete($new_value, $op, $searchinmodule, $limit, $current_user,$field);
		break;
	case 'ismoduleactive':
	default:
		$mod = vtlib_purify($_REQUEST['checkmodule']);
		$rdo = vtlib_isModuleActive($mod);
		$ret = array('isactive'=>$rdo);
		break;
}

echo json_encode($ret);
?>