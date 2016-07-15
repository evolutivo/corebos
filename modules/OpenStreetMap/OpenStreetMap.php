<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('Smarty_setup.php');
include_once('include/utils/CommonUtils.php');
require_once('modules/OpenStreetMap/lib/utils.inc.php');
require('modules/OpenStreetMap/lib/GeoCoder.inc.php');
//require_once('modules/OpenStreetMap/lib/crXml.php');
global $adb, $app_strings, $mod_strings, $theme, $currentModule, $current_user;
$category = getParentTab();
$smarty = new vtigerCRM_Smarty();
$lang = substr($_SESSION['authenticated_user_language'],0,2);
$orginputshow = $inputshow = vtlib_purify($_REQUEST['show']);
if(empty($inputshow)) {
	list($radius,$zoom,$maptype,$location,$mapcenter,$tab)=evvt_getmapconfig();
	switch ($tab) {
		case 1: // ticket
			$defMod = 'HelpDesk';
			break;
		case 2: // event
			$defMod = 'Events';
			break;
		case 3: // Radius
			$defMod = 'Radius';
			break;
		case 0: // filter
		default: // filter
			$defMod = 'Accounts';
			break;
	}
	$orginputshow = $inputshow = $_REQUEST['show'] = $defMod;
}

$rsrch_radius = $rsrch_lat = $rsrch_lng = $rsrch_module = $rsrch_crmid = $rsrch_ename = '';

switch ($orginputshow) {
	case 'Radius':
		$rsrch_radius = vtlib_purify($_REQUEST['radrad']);
		$rsrch_lat = vtlib_purify($_REQUEST['radlat']);
		$rsrch_lng = vtlib_purify($_REQUEST['radlng']);
		$recordval = vtlib_purify($_REQUEST['recordval']);
		if (!empty($recordval) and (empty($rsrch_lat) or empty($rsrch_lng))) {
			$result = $adb->pquery('select lat,lng from vtiger_evvtmap inner join vtiger_crmentity on crmid = evvtmapid where deleted = 0 and evvtmapid = ?',array($recordval));
			if ($adb->num_rows($result)>0) {
				$info = $adb->fetch_array($result);
				$rsrch_lat = $info['lat'];
				$rsrch_lng = $info['lng'];
			}
		}
		$inputshow = $_REQUEST['show'] = "Accounts";
		break;
	case 'Config':
		evvt_savemapconfig();
		$inputshow = $_REQUEST['show'] = "Accounts";
		break;
}

list($radius,$zoom,$maptype,$location,$mapcenter,$tab)=evvt_getmapconfig();

if (empty($rsrch_radius)) $rsrch_radius = $radius;

if (!$_REQUEST['showAccounts'] && !$_REQUEST['showContacts'] && !$_REQUEST['showLeads']) {
  $_REQUEST['showAccounts'] = 1;
  $_REQUEST['showContacts'] = 1;
  $_REQUEST['showLeads'] = 1;
}

if (!is_admin($current_user)) {
	$users = getSubordinateUsersList();
} else {
	$users = array();
}
$users[] = $current_user->id;
if (is_admin($current_user)) {
  $query = "select * from vtiger_users u join vtiger_crmentity crm_u on crm_u.crmid=u.id and crm_u.deleted=0 where u.status='Active' order by u.user_name";
}
else {
  $usersString = implode(',', $users);
  $query = "select * from vtiger_users u join vtiger_crmentity crm_u on crm_u.crmid=u.id and crm_u.deleted=0 where u.status='Active' and id in ({$usersString}) order by u.user_name";
}
$users_options = '<option value="0">'.$app_strings['LBL_ALL'].'</option>';
$defaultUser = 0;
$res = $adb->pquery($query,array());
while ($row=$adb->getNextRow($res, false)) {
  if ($_REQUEST['user'] && $row['id']==$_REQUEST['user']) {
    $users_options .= "<option value=\"{$row['id']}\" selected>{$row['user_name']}</option>";
    $defaultUser = vtlib_purify($_REQUEST['user']);
  }
  else {
    $users_options .= "<option value=\"{$row['id']}\">{$row['user_name']}</option>";
  }
}

if (!isset($defaultUser) || $defaultUser == 0) {
  $defaultUser = $current_user->id;
}
switch ($orginputshow) {
	case 'HelpDesk':
		$seltab = 1;
		break;
	case 'Events':
		$seltab = 2;
		break;
	case 'Radius':
		$seltab = 3;
		break;
	case 'Config':
		$seltab = 4;
		break;
	default:
		$seltab = 0;
		break;
}


$modules = array("Accounts","Contacts","Leads");
if($inputshow!='Events'){
	$oCustomView = new CustomView($inputshow);
	$viewid=array();
	//identify current view
	if ($inputshow=='HelpDesk' and !empty($_REQUEST['hdviewid']))
		$viewid = vtlib_purify($_REQUEST['hdviewid']);
	elseif ($_REQUEST['viewid'])
		$viewid = vtlib_purify($_REQUEST['viewid']);
	else //go to default module view
		$viewid = array(intval($oCustomView->getViewId($inputshow)));
}
//Set map initial center
//get user address
$result = $adb->pquery("select id,concat(first_name,' ',last_name) as name, address_city as city, address_postalcode as code, 
                        address_street as address, address_state as state, address_country as country 
                        from vtiger_users
                        where id=?",array($current_user->id)); 
$row = $adb->getNextRow($result, false);
$validate = trim($row['city'].$row['code'].$row['address'].$row['state'].$row['country']);
//If user address is not correct  map will be centered on the company's address information located
if (empty($validate)) {
	$result = $adb->query("select organizationname as name, country, city, code, address, state from vtiger_organizationdetails");
	$row = $adb->getNextRow($result, false);
}

$ename=$row['name'];
$country=$row['country'];
$city=$row['city'];
$pcode=$row['code'];
$street=$row['address'];
$state=$row['state'];
$centerAddress = $state." ".$country." ".$city;
$fulladdress =  "$street, $pcode, $city, $country, $state";
//echo $fulladdress;
//$from = $gc->getGeoCode(-$current_user->id,$state,$city,$pcode,$address,$country);
//if($gc->status != 'OK') show_error_import($gc->status);
if ($_REQUEST['ids']) //priority to request paramater
{
//    $results = getResults($inputshow,$_REQUEST['ids']);
//    $allIDS=explode(',',$_REQUEST['ids']);
//    $nottreated=array_diff($allIDS,array_keys($results));
}
else //calculate ids using filters
{ 
    $queryGenerator = new QueryGenerator($inputshow, $current_user);
    $ids=array();  
    if($inputshow!='Events' and $orginputshow!='Radius'){
		if ($viewid) {
			for($i=0;$i<count($viewid);$i++) {
				$listquery = getListQuery($inputshow); 
				$query= $oCustomView->getModifiedCvListQuery($viewid[$i],$listquery,$inputshow);
				$list_result = $adb->pquery($query, array());
				while($row = $adb->fetch_array($list_result)) {
					if (isset($ids[$row["crmid"]])&& !empty($ids[$row["crmid"]]))
						$ids[$row["crmid"]] = 0;
					else
						$ids[$row["crmid"]] = $viewid[$i];
				}
			}
		}
		if(count($ids))           
			$results = getResults($inputshow,$ids); //retrive map results  
		else
			$results = array();
		$nottreated=array_diff(array_keys($ids),array_keys($results));
	} elseif ($orginputshow=='Radius') {
		if (empty($rsrch_lat) or empty($rsrch_lng)) {
			$results = array();
		} else {
			$results = doRadiusSearch($rsrch_lat,$rsrch_lng,$rsrch_radius);
		}
    } else {
		$start_date=vtlib_purify($_REQUEST['start_date']);
		$end_date=vtlib_purify($_REQUEST['end_date']);
		$user=vtlib_purify($_REQUEST['user']);
		$results = getResults($inputshow,'',$start_date,$end_date,$user);
	}

}

//Getting enitity location  based on fileter
//Add marker on the map foreach entity
$entitymarkers = printResultLayer($results);
//-----------DONE

$start_date_val=!empty($_REQUEST['start_date'])?vtlib_purify($_REQUEST['start_date']):date('Y/m/d');
$end_date_val=!empty($_REQUEST['end_date'])?vtlib_purify($_REQUEST['end_date']):date('Y/m/d');
$checkedAccounts=($_REQUEST['showAccounts'])?'checked':'';
$checkedLeads=($_REQUEST['showLeads'])?'checked':'';
$checkedContacts=($_REQUEST['showContacts'])?'checked':'';

$reports_to_id = vtlib_purify($_REQUEST['reports_to_id']);


$cvTicket = getCustomViewCheckboxes($viewid,'HelpDesk',true,'hd');

$icons=array('blue-dot','red-dot','green-dot','yellow-dot','grey-dot','pink-dot','orange-dot','green-pin','blue-pin');
$customviews=$viewid;
$iconsviews=array();
$iconsviews[0]='purple-dot';
$customviewname = array();
if($inputshow!='Events' and $orginputshow!='Radius'){
	for($i=0;$i<count($customviews);$i++) {
	    $iconsviews[$customviews[$i]]=$icons[$i];
            $customviewname[$icons[$i]] = getCVname($customviews[$i]);
	}
} else {
   $customviews=array($app_strings['LBL_FOUND']);
   $iconsviews[1]=$icons[0]; // Accounts
   $iconsviews[2]=$icons[1]; // Contacts
   $iconsviews[3]=$icons[2]; // Leads
}
if($currentModule == 'OpenStreetMap'){
	$mypath="modules/OpenStreetMap";
	$tr = json_encode($iconsviews);
	$report_id_display = getUserFullName(vtlib_purify($_REQUEST['reports_to_id']));
	$smarty->assign("THEME", $theme);
	$smarty->assign('MOD', $mod_strings);
	$smarty->assign('APP', $app_strings);
	$smarty->assign('MODULE', 'OpenStreetMap');
	$smarty->assign('CATEGORY', $category);
	$smarty->assign('IMAGE_PATH', "themes/$theme/images/");
	$smarty->assign('start_date_val',$start_date_val);
	$smarty->assign('end_date_val',$end_date_val);
	$smarty->assign('users_options',$users_options);
	$smarty->assign('checkedAccounts',$checkedAccounts);
	$smarty->assign('checkedLeads',$checkedLeads);
	$smarty->assign('checkedContacts',$checkedContacts);
	$smarty->assign('reports_to_id',$reports_to_id);
	$smarty->assign("report_id_display",$report_id_display);
	$smarty->assign("inputshow",$inputshow);
	$smarty->assign("cvTicket",$cvTicket);
	$smarty->assign("rsrch_radius",$rsrch_radius);
	$smarty->assign("rsrch_crmid",$rsrch_crmid);
	$smarty->assign("rsrch_ename",$rsrch_ename);
	$smarty->assign("seltab",$seltab);
	$smarty->assign("customviewname",$customviewname);
	$smarty->assign("icons",$icons);
	$smarty->assign("tab",$tab);
	$smarty->assign("viewid",implode(",",$viewid));
	$smarty->assign("centerAddress",$centerAddress);
	$smarty->assign('entitymarkers',$entitymarkers);
	$smarty->assign('iconcustomview',$tr);
	$smarty->assign('defaultRadius',$radius);
	$smarty->assign('fullUserName',getUserFullName($current_user->id));
	$smarty->assign("userOrgName",$ename);
	$smarty->assign("baseCity",$city);
	$smarty->assign("baseAddress",$country);
	$smarty->assign("defaultUser",$defaultUser);
	$smarty->assign("mypath",$mypath);
	$smarty->assign("results",json_encode($results));
	$smarty->assign("orginputshow",$orginputshow);
	$smarty->display(vtlib_getModuleTemplate('OpenStreetMap','index.tpl'));
}
?>
