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

global $adb, $app_strings, $mod_strings, $theme, $currentModule, $current_user;
$mypath="modules/$currentModule";
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once('modules/evvtMap/lib/utils.inc.php');
include_once('include/utils/CommonUtils.php');
require('modules/evvtMap/lib/GeoCoder.inc.php');
$lang = substr($_SESSION['authenticated_user_language'],0,2);
$orginputshow = $inputshow = vtlib_purify($_REQUEST['show']);
function show_error_import($errmsg) {
	global $currentModule;
	echo '<div id="errorcontainer" style="padding:20px;"><div id="errormsg" style="color: #f85454; font-weight: bold; padding: 10px; border: 1px solid #FF0000; background: #FFFFFF; border-radius: 5px; margin-bottom: 10px;">'.getTranslatedString($errmsg,$currentModule).'</div></div>';
}
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

if (!isset($defaultUser)) {
  $defaultUser = $current_user->id;
}
?>
<script src="http://maps.google.com/maps/api/js?sensor=false&libraries=places&language=<?php echo $lang?>&key=AIzaSyDlKIKj_IWx5KhohfGQ_eFXaMh0f2fcdDw" type="text/javascript"></script>
<!--<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places"></script>-->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script src="modules/evvtMap/js/gm.js" type="text/javascript"></script>
<link rel="stylesheet" href="modules/evvtMap/css/ui-lightness/jquery-ui.min.css" />
<script src="modules/evvtMap/js/jquery-ui.min.js"></script>
<link rel="stylesheet" href="<?php echo $mypath; ?>/slickgrid/slick.grid.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $mypath; ?>/slickgrid/controls/slick.pager.css" type="text/css"/>
<style>
    .evvt-cell-title {
      font-weight: bold;
    }

    .evvt-cell-centered {
      text-align: center;
    }
        .slick-viewport {
      overflow-x: hidden !important;
	}
    
</style>
<script src="<?php echo $mypath; ?>/slickgrid/lib/firebugx.js"></script>
<script src="<?php echo $mypath; ?>/slickgrid/lib/jquery.event.drag-2.0.min.js"></script>
<script src="<?php echo $mypath; ?>/slickgrid/slick.core.js"></script>
<script src="<?php echo $mypath; ?>/slickgrid/slick.formatters.js"></script>
<script src="<?php echo $mypath; ?>/slickgrid/slick.grid.js"></script>
<script src="<?php echo $mypath; ?>/slickgrid/slick.groupitemmetadataprovider.js"></script>
<script src="<?php echo $mypath; ?>/slickgrid/slick.dataview.js"></script>
<script src="<?php echo $mypath; ?>/slickgrid/controls/slick.pager.js"></script>
<script type="text/javascript">
var orginputshow='<?php echo $orginputshow; ?>';
function updateFilterCombo(elem) {
	var module = encodeURIComponent(elem.options[elem.options.selectedIndex].value);
	jQuery("#show").val(module);
	$("filterContainer").innerHTML= combos[module]+" "+$("userspantpl").innerHTML;  
}
function evvt_GetCoordinates() {
	var eid = jQuery('#parent_id').val();
	if (eid > 0) {
		jQuery.ajax({
			type: "POST",
			url: 'index.php?module=evvtMap&action=evvtMapAjax&file=getCoor',
			data: {
				crmid: eid
			}
		}).done(function( coord ) {
			crd = jQuery.parseJSON(coord);
			if (crd.length!=0) {
				jQuery('#radlat').val(crd.lat);
				jQuery('#radlng').val(crd.lng);
			}
		});
	}
}
var combos= new Array();
<?php
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
foreach($modules as $module) {
	$html = getCustomViewCheckboxes($viewid,$module);        
	echo "combos['$module'] = '$html';\n";
}
?>
jQuery(function() {
	jQuery("#tabs").tabs({
		beforeActivate: function(event, ui) {
			 // Panel that is currently active, about to be deactivated
			//ui.oldPanel
			// Tab associated with the currently active panel
			//ui.oldTab
			// Panel that is about to be activated
			//ui.newPanel
			// Tab associated with the panel that is about to be activated
			//ui.newTab
			// Index can be calculated if needed
			//ui.newTab.index()
			switch (ui.newTab.index()) {
			  case 0:
				  jQuery("#show").val(jQuery('#filtermodule').val());
                                  google.maps.event.clearListeners(map, 'click');
				  break;
			  case 1:
				  jQuery("#show").val('HelpDesk');
                                  google.maps.event.clearListeners(map, 'click');
				  break;
			  case 2:
				  jQuery("#show").val('Events');
                                  google.maps.event.clearListeners(map, 'click');
				  break;
			  case 3:
				  jQuery("#show").val('Radius');
                                  google.maps.event.clearListeners(map, 'click');
				  break;
			  case 4:
				  jQuery("#show").val('Config');
                                  google.maps.event.clearListeners(map, 'click');
				  break;
                          case 5:
                                //attach listener to map 
                              geocoder = new google.maps.Geocoder();                     
                              autocomplete = new google.maps.places.Autocomplete(document.getElementById('autocomplete'), {
                                  types: [ '(cities)' ],
                                  componentRestrictions: countryRestrict
                                });
                              places = new google.maps.places.PlacesService(map);
                              autocomplete.addListener('place_changed', onPlaceChanged);
                            // Add a DOM event listener to react when the user selects a country.
                            document.getElementById('country').addEventListener(
                                'change', setAutocompleteCountry);
                            google.maps.event.addListener(map, 'click', function(event) {
                            geocoder.geocode( {'latLng': event.latLng},
                              function(results, status) {
                            if(status == google.maps.GeocoderStatus.OK) {
                              if(results[0]) {
                                address = results[0].formatted_address;
                                addresscomponents = results[0].address_components;
                                console.log(addresscomponents);
                                var url = 'index.php?module=Accounts&action=EditView&return_module=Accounts&return_action=index&parenttab=Marketing';
                                var mappingaddr = ['bill_pobox','bill_street','bill_city','bill_state','bill_country','bill_code'];
                                   for(key=0; key< addresscomponents.length; key++){
                                    fulladdr = addresscomponents[key]
                                    componentvalue = fulladdr.long_name;
                                    componenttype = fulladdr.types[0];
                                    switch(componenttype){
                                        case 'street_number':
                                         componenttype = 'bill_pobox';
                                         break;
                                        case 'route':
                                         componenttype = 'bill_street';
                                         break;
                                        case 'locality':
                                          componenttype = 'bill_city';
                                         break;
                                        case 'administrative_area_level_3':
                                         componenttype = 'bill_city';
                                         break;
                                        case 'administrative_area_level_2':
                                         componenttype = 'bill_state';
                                         break;
                                        case 'country':
                                         componenttype = 'bill_country';
                                         break;
                                        case 'postal_code':
                                         componenttype = 'bill_code';
                                         break;
                                    }
                                    url += "&"+componenttype+"="+componentvalue;
                                }
//                                console.log(url);
                                window.open(url);
                              }
                              else {
                                address = "No results";
                              }
                            }
                            else {
                              address = status;
                            }
                          }
                             );
                           });
		             break;
			}
		}
	});
	jQuery("#rdotabs").tabs();
	updateFilterCombo($('filtermodule'));
<?php
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
echo "jQuery('#tabs').tabs('option', 'active', $seltab );";
?>
});
</script>   
<?php
$gc = new GeoCoder();
$result = $adb->pquery("select id,concat(first_name,' ',last_name) as name, address_city as city, address_postalcode as code, 
                        address_street as address, address_state as state, address_country as country 
                        from vtiger_users
                        where id=?",array($current_user->id)); 
$row = $adb->getNextRow($result, false);
$validate = trim($row['city'].$row['code'].$row['address'].$row['state'].$row['country']);
if (empty($validate)) {
	$result = $adb->query("select organizationname as name, country, city, code, address, state from vtiger_organizationdetails");
	$row = $adb->getNextRow($result, false);
}
$ename=$row['name'];
$country=$row['country'];
$city=$row['city'];
$pcode=$row['code'];
$address=$row['address'];
$state=$row['state'];
$from = $gc->getGeoCode($current_user->id,$state,$city,$pcode,$address,$country);
if($gc->status != 'OK') show_error_import($gc->status);

if ($_REQUEST['ids']) //priority to request paramater
{
    $results = getResults($inputshow,$_REQUEST['ids']);
    $allIDS=explode(',',$_REQUEST['ids']);
    $nottreated=array_diff($allIDS,array_keys($results));
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
//echo generateReport(array_values($nottreated),$inputshow);
?>
<div class='moduleName' style='padding: 10px'> <?php echo $app_strings['Tools']; ?> > <?php echo $mod_strings['evvtMap']; ?> > <a href='index.php?parenttab=Tools&action=index&module=evvtMap&show=<?php echo $inputshow; ?>'><?php echo $app_strings[$inputshow];?></a></div>
<div style='padding: 5px; margin: 10px; margin-top: 0px; padding-top: 0px;'>
<form action='index.php' method='POST' name="EditView">
<input type='hidden' name='module' value='<?php echo $currentModule; ?>'/>
<input type='hidden' name='action' value='index'/>
<input type='hidden' name='parenttab' value='Analytics'/>
<input type="hidden" name="user" value="<?php echo $defaultUser; ?>">
<input type='hidden' name='show' id='show' value='<?php echo $inputshow; ?>'/>
<div id="tabs">
<ul>
<li><a href="#tabs-1"><?php echo $mod_strings['Filters']; ?></a></li>
<li><a href="#tabs-2"><?php echo $mod_strings['Tickets']; ?></a></li>
<li><a href="#tabs-3"><?php echo $mod_strings['Visitas']; ?></a></li>
<li><a href="#tabs-4"><?php echo $mod_strings['Radius']; ?></a></li>
<li><a href="#tabs-5"><?php echo $mod_strings['Defaults']; ?></a></li>
<li><a href="#tabs-6"><?php echo $mod_strings['NewClient']; ?></a></li>
</ul>
<div id="tabs-1">
<?php
echo "<span style='float:left;'><span style='font-weight: bold ; font-size: 110%; margin-left: 6px;'>{$mod_strings['Show']}&nbsp;{$app_strings['LBL_MODULE']}:</span><br/><select onchange='updateFilterCombo(this)' name='filtermodule' id='filtermodule'>
<option value='Accounts' ".($inputshow=='Accounts'?'selected="selected"':'').">{$app_strings['Accounts']}</option>
<option value='Contacts' " . ($inputshow == 'Contacts' ? 'selected="selected"' : '') . ">{$app_strings['Contacts']}</option>
<option value='Leads' ".($inputshow=='Leads'?'selected="selected"':'').">{$app_strings['Leads']}</option>
</select></span>";
$start_date_val=!empty($_REQUEST['start_date'])?vtlib_purify($_REQUEST['start_date']):date('Y/m/d');
$end_date_val=!empty($_REQUEST['end_date'])?vtlib_purify($_REQUEST['end_date']):date('Y/m/d');
$checkedAccounts=($_REQUEST['showAccounts'])?'checked':'';
$checkedLeads=($_REQUEST['showLeads'])?'checked':'';
$checkedContacts=($_REQUEST['showContacts'])?'checked':'';
?>
<span id="userspantpl" style="display: none">
<span id="userspanfilter" style="margin-left:10px;border-left: solid 1px;padding-left: 3px;"><?php echo $app_strings['LBL_USER']; ?>:
<select name="user_select2" onchange="this.form.user.value=this.value;" style="margin-left:6px;"><?php echo $users_options; ?></select>
</span>
</span>
<span id='filterContainer' class='searchUIBasic small' style='margin-left: 8px;padding-top: 8px;padding-bottom: 8px;width: 88%;display:block;float:left;'></span>
</div>
<div id="tabs-2">
<span class='searchUIBasic small' style='margin-left: 8px;padding-top: 8px;padding-bottom: 8px;width: 98%;display:block;float:left;'>
&nbsp;<?php echo getCustomViewCheckboxes($viewid,'HelpDesk',true,'hd'); ?><br/>
<input type='hidden' name='reports_to_type' id='reports_to_type' value='Users'>
<input type='hidden' name='reports_to_id' id='reports_to_id' value='<?php echo vtlib_purify($_REQUEST['reports_to_id']); ?>'/>
&nbsp;&nbsp;<?php echo $mod_strings['RESTRICT_USER']; ?>:&nbsp;
<input type="text" value="<?php echo getUserFullName(vtlib_purify($_REQUEST['reports_to_id'])); ?>" style="border: 1px solid rgb(186, 186, 186);" readonly="" name="reports_to_id_display" id="reports_to_id_display"/> 
<img align="absmiddle" style="cursor: pointer;" onclick='window.open("index.php?module="+ document.EditView.reports_to_type.value +"&amp;action=Popup&amp;html=Popup_picker&amp;form=vtlibPopupView&amp;forfield=reports_to_id&amp;srcmodule=Maps&amp;forrecord=","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' title="Select" alt="Select" src="themes/softed/images/select.gif"/> 
<img align="absmiddle" style="cursor: pointer;" onclick="document.EditView.reports_to_id.value='0'; document.EditView.reports_to_id_display.value='';" title="Clear" alt="Clear" src="themes/images/clear_field.gif"/>
</span>
</div>
<div id="tabs-3">
<span style='font-weight: bold; margin-left: 6px; font-size: 110%; width: 98%;'>
<span id='date' class="searchUIBasic small"  style="margin-left: 10px;padding-top: 8px;padding-bottom: 8px;width: 97%;display:block;">
&nbsp;<?php echo $app_strings['LBL_START_DATE']?>: <input name="start_date" tabindex="5" id="jscal_field_start_date" type="text" style="border:1px solid #bababa;" size="11" maxlength="10" value="<?php echo $start_date_val;?>">
	<img style="vertical-align:middle;" src="<?php echo vtiger_imageurl('btnL3Calendar.gif', $theme); ?>" id="jscal_trigger_start_date">   
	<script type="text/javascript" id='massedit_calendar_start_date'>
		Calendar.setup ({
			inputField : "jscal_field_start_date", showsTime : false, button : "jscal_trigger_start_date", singleClick : true, step : 1
		})
	</script>    
&nbsp;<?php echo $app_strings['LBL_END_DATE']?>: <input name="end_date" tabindex="5" id="jscal_field_end_date" type="text" style="border:1px solid #bababa;" size="11" maxlength="10" value="<?php echo $end_date_val;?>">
	<img style="vertical-align:middle;" src="<?php echo vtiger_imageurl('btnL3Calendar.gif', $theme); ?>" id="jscal_trigger_end_date">   
	<script type="text/javascript" id='massedit_calendar_end_date'>
		Calendar.setup ({
			inputField : "jscal_field_end_date", showsTime : false, button : "jscal_trigger_end_date", singleClick : true, step : 1
		})
	</script>  
	<span id="userspan" style="margin-left:10px;border-left: solid 1px;padding-left: 3px;"> <?php echo $app_strings['LBL_USER'].':'; echo '<select name="user_select1" onchange="this.form.user.value=this.value;">'.$users_options.'</select>';?></span>
	&nbsp;&nbsp;&nbsp;&nbsp;    
	<?php echo $app_strings['Related to'];?>:&nbsp;
	<input type="checkbox" name="showAccounts" <?php echo $checkedAccounts; ?>/>&nbsp;<?php echo $app_strings['Accounts'];?>
	&nbsp;&nbsp;&nbsp;
	<input type="checkbox" name="showContacts" <?php echo $checkedContacts; ?>/>&nbsp;<?php echo $app_strings['Contacts'];?>
	&nbsp;&nbsp;&nbsp;
	<input type="checkbox" name="showLeads" <?php echo $checkedLeads; ?>/>&nbsp;<?php echo $app_strings['Leads'];?>
</span>
</span>
</div>
<div id="tabs-4">
<span class='searchUIBasic small' style='padding-top: 8px;padding-bottom: 8px;width: 98%;display:block;float:left;'>
&nbsp;<?php echo $app_strings['LBL_MODULE']; ?> <select name="modulefrom" id="moduleselected" class="small" onchange="this.form.parent_id.value=''; this.form.parent_name.value='';">
<option value='Accounts' <?php echo ($rsrch_module=='Accounts' ? 'selected' : '');?>><?php echo $app_strings['Accounts']; ?></option>
<option value='Contacts' <?php echo ($rsrch_module=='Contacts' ? 'selected' : '');?>><?php echo $app_strings['Contacts']; ?></option>
<option value='Leads' <?php echo ($rsrch_module=='Leads' ? 'selected' : '');?>><?php echo $app_strings['Leads']; ?></option>
</select>
<input id="parent_id" name="recordval" type="hidden" value="<?php echo $rsrch_crmid; ?>">
<input id="parent_name" name="recordval_display" readonly type="text" style="border:1px solid #bababa;" value="<?php echo $rsrch_ename; ?>">&nbsp;
<img id="entity"
     src="<?php echo vtiger_imageurl('select.gif',$theme); ?>" alt="Select" title="Select" align="absmiddle" style='cursor:hand;cursor:pointer'
     onClick='window.open("index.php?module="+document.getElementById("moduleselected").value+"&action=Popup&html=Popup_picker&form=vtlibPopupView&srcmodule=evvtMap","test",
"width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");'>
<input type="image" src="<?php echo vtiger_imageurl('clear_field.gif',$theme); ?>"
alt="Clear" title="Clear" LANGUAGE=javascript	onClick="this.form.recordval.value=''; this.form.recordval_display.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
<input type="image" src="modules/evvtMap/img/icon-coord.gif" width='20px'
alt="GetCoordinates" title="GetCoordinates" LANGUAGE=javascript	onClick="evvt_GetCoordinates(); return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;&nbsp;&nbsp;
<?php echo $mod_strings['Latitud']; ?>: <input type="text" id='radlat' name='radlat' value='<?php echo $rsrch_lat; ?>' class="small">&nbsp;&nbsp;
<?php echo $mod_strings['Longitud']; ?>: <input type="text" id='radlng' name='radlng' value='<?php echo $rsrch_lng; ?>' class="small">&nbsp;&nbsp;
&nbsp;&nbsp;<?php echo $mod_strings['Radius']; ?>: <input type="text" id='radrad' name='radrad' value='<?php echo $rsrch_radius; ?>' class="small" style="width:35px;">
</span>
</div>
<div id="tabs-5">
<label for="defradius" class="defaultLabel"><?php echo $mod_strings['defaultRadius']; ?> :</label> <input type="text" id='defradius' name='defradius' value='<?php echo $radius; ?>' class="small" style="width:35px;"><br/>
<label for="defzoom" class="defaultLabel"><?php echo $mod_strings['defaultZoom']; ?> :</label> <input type="text" id='defzoom' name='defzoom' value='<?php echo $zoom; ?>' class="small" style="width:35px;"><br/>
<label for="defmaptype" class="defaultLabel"><?php echo $mod_strings['defaultMapType']; ?> :</label> <select id='defmaptype' name='defmaptype' class="small"><option value="politico" <?php echo ($maptype=='politico' ? 'selected' : '');?>><?php echo $mod_strings['politico']; ?></option><option value="fisico" <?php echo ($maptype=='fisico' ? 'selected' : '');?>><?php echo $mod_strings['fisico']; ?></option></select><br/>
<?php /* Currently there is no support for default location and center
<label for="deflocation" class="defaultLabel"><?php echo $mod_strings['defaultLocation']; ?> :</label> <select id='deflocation' name='deflocation' class="small">
	<option value="defloc" <?php echo ($location=='defloc' ? 'selected' : '');?>><?php echo $mod_strings['defaultLocation']; ?></option>
	<option value="myloc" <?php echo ($location=='myloc' ? 'selected' : '');?>><?php echo $mod_strings['MyLocation']; ?></option>
	<option value="myadr" <?php echo ($location=='myadr' ? 'selected' : '');?>><?php echo $mod_strings['myaddress']; ?></option>
	<option value="mycpy" <?php echo ($location=='mycpy' ? 'selected' : '');?>><?php echo $mod_strings['TheCompany']; ?></option>
</select><br/>
<label for="defcenter" class="defaultLabel"><?php echo $mod_strings['defaultCenter']; ?> :</label> <select id='defcenter' name='defcenter' class="small">
	<option value="defloc" <?php echo ($mapcenter=='defloc' ? 'selected' : '');?>><?php echo $mod_strings['defaultLocation']; ?></option>
	<option value="myloc" <?php echo ($mapcenter=='myloc' ? 'selected' : '');?>><?php echo $mod_strings['MyLocation']; ?></option>
	<option value="myadr" <?php echo ($mapcenter=='myadr' ? 'selected' : '');?>><?php echo $mod_strings['myaddress']; ?></option>
	<option value="mycpy" <?php echo ($mapcenter=='mycpy' ? 'selected' : '');?>><?php echo $mod_strings['TheCompany']; ?></option>
</select><br/>
**/ ?>
<label for="deftab" class="defaultLabel"><?php echo $mod_strings['defaultTab']; ?> :</label> <select id='deftab' name='deftab' class="small">
	<option value="0" <?php echo ($tab==0 ? 'selected' : '');?>><?php echo $mod_strings['Filters']; ?></option>
	<option value="1" <?php echo ($tab==1 ? 'selected' : '');?>><?php echo $mod_strings['Tickets']; ?></option>
	<option value="2" <?php echo ($tab==2 ? 'selected' : '');?>><?php echo $mod_strings['Visitas']; ?></option>
	<option value="3" <?php echo ($tab==3 ? 'selected' : '');?>><?php echo $mod_strings['Radius']; ?></option>
</select>
</div>
<div id ="tabs-6">
  <div id="findhotels">
    Find hotels in:
  </div>
  <div id="locationField">
    <input id="autocomplete" placeholder="Enter a city" type="text" autocomplete="off">
  </div>
  <div id="controls">
    <select id="country" onchange="setAutocompleteCountry()">
      <option value="all">All</option>
      <option value="au">Australia</option>
      <option value="br">Brazil</option>
      <option value="ca">Canada</option>
      <option value="fr">France</option>
      <option value="de">Germany</option>
      <option value="mx">Mexico</option>
      <option value="nz">New Zealand</option>
      <option value="it">Italy</option>
      <option value="za">South Africa</option>
      <option value="es">Spain</option>
      <option value="pt">Portugal</option>
      <option value="us" selected="">U.S.A.</option>
      <option value="uk">United Kingdom</option>
    </select>
  </div>
</div>
<div style="clear:both;"></div>
<input type='submit' style="margin-left: 10px;margin-top: 4px;margin-bottom: 4px; clear: both;" value='<?php echo $app_strings['LBL_UPDATE'];?>'/>
</form>
</div>
<?php
$icons=array('blue-dot','red-dot','green-dot','yellow-dot','grey-dot','pink-dot','orange-dot','green-pin','blue-pin');
$customviews=$viewid;
$iconsviews=array();
$iconsviews[0]='purple-dot';
if($inputshow!='Events' and $orginputshow!='Radius'){
	for($i=0;$i<count($customviews);$i++) {
	    $iconsviews[$customviews[$i]]=$icons[$i];
	}
} else {
   $customviews=array($app_strings['LBL_FOUND']);
   $iconsviews[1]=$icons[0]; // Accounts
   $iconsviews[2]=$icons[1]; // Contacts
   $iconsviews[3]=$icons[2]; // Leads
}
$tr=json_encode($iconsviews);
?>
<div style='float: right;  padding-bottom:70px; padding-top:10px; width: 99%;'>
<div id="map_canvas" style="margin-left: 10px; margin-right: 10px; width: 69%; height: 1000px;  border: 1px solid black;  padding-bottom:40px; float: left"></div>
        <div style="align:right;width:27%;margin-left: 10px;margin-right: 10px;float:right;">
          <?php echo $mod_strings['Legend'];?>: 
            <div class="searchUIBasic small" style="display: block;position:relative;">
<?php
	if($inputshow!='Events' and $orginputshow!='Radius') {
		for($i=0;$i<count($customviews);$i++) {
			$name=getCVname($customviews[$i]);
			echo '<br/><img src="modules/evvtMap/img/'.$icons[$i].'.png">'.$name;
		}
	} else {
		echo '<br/><img src="modules/evvtMap/img/'.$icons[0].'.png">'.getTranslatedString('Accounts');
		echo '<br/><img src="modules/evvtMap/img/'.$icons[1].'.png">'.getTranslatedString('Contacts');
		echo '<br/><img src="modules/evvtMap/img/'.$icons[2].'.png">'.getTranslatedString('Leads');
	} 
	if($inputshow!='Events' and $orginputshow!='Radius')
		echo '<br/><img src="modules/evvtMap/img/purple-dot.png">'.$mod_strings['FoundMoreOnce'];
	echo '<br/><img src="modules/evvtMap/img/me-dot.png">'.$mod_strings['MyLocation'];
?>             
              <br>
            </div>
        </div>
	<div style="align:right;width:27%;margin-left: 10px;margin-right: 10px;float:right;"><br/></div>
	<div id="rdotabs"  style="align:right;width:26%;height:600px; overflow-y: scroll; margin-left: 10px;margin-right: 10px;float:right;">
	<ul>
	<li><a href="#rdotab"><?php echo $mod_strings['Results']; ?></a></li>
	<li><a href="#ruttab"><?php echo $mod_strings['Direction']; ?></a></li>
	<li><a href="#ruttab1"><?php echo $mod_strings['Direction between marks']; ?></a></li>
	</ul>
	<div id="rdotab">
	  <div style="margin-left: 10px;font-weight: bold;"><?php echo $mod_strings['RecordsFound'].": "; echo count($results);?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox id='setGrouping' onchange="setGrouping()">&nbsp;<?php echo getTranslatedString('GroupType',$currentModule); ?></div>
	  <div id="rdoGrid" style="width:98%;height:450px;margin: 15px auto;"></div>
	  <div id="pager" style="width:98%;height:20px;margin: 15px auto;"></div>
	</div>
	<div id="ruttab"><div id="desc" name="desc" ></div><br/><input type="button" class="crmbutton small edit"  value="<?php echo $mod_strings['Clear directions'] ?>" onClick="restore();"/><br/><div id="route"></div></div>
	<div id="ruttab1"><div id="desc1" name="desc1" ></div><br/><b>From: <i><div id="from"/></div></i></b><br/><b>To: <i><div id="to"/></div></i></b> <br/><input type="button" class="crmbutton small edit"  value="Directions between marks" onClick="showDirections();"/><br/><div id="route1"></div></div>
	</div>
</div>
<script type="text/javascript">
<?php echo "var module='".$inputshow."'\n"; ?>
var map;
var autocomplete;
var geocoder;
var countryRestrict = { 'country': 'us' };
var markersArray = [];
var directionDisplay;
var directionsService = new google.maps.DirectionsService();
var head_lbl = '<?php echo $mod_strings['MyLocation'] ?>' ;
var from_lbl = '<?php echo $mod_strings['From'] ?>' ; 
var to_lbl = '<?php echo $mod_strings['To'] ?>';
var direction_lbl = '<?php echo $mod_strings['Direction'] ?>';
var start_lbl = '<?php echo $mod_strings['Start'] ?>';
var end_lbl = '<?php echo $mod_strings['End'] ?>';
var reload_lbl = '<?php echo $mod_strings['Reload'] ?>';
var around_lbl = '<?php echo $mod_strings['Around'] ?>';
var drawcircle_lbl = '<?php echo $mod_strings['DrawCircle'] ?>';
var clearcircle_lbl = '<?php echo $mod_strings['ClearCircle'] ?>';
var latitude='<?php echo (empty($from->latitude) ? 0 : $from->latitude); ?>';
var longitude='<?php echo (empty($from->longitude) ? 0 : $from->longitude); ?>';
var iconstoviews= eval(<?php echo $tr; ?>); 
var baseDesc;
var defaultRadius = <?php echo $radius; ?>;
var radius; // Radius of circle in km.
var center; // LatLng of center point of circle
var draw_circle = null;  // object of google maps polygon for redrawing the circle
var from;
var basePos;
var baseAddress;
var baseName;
var baseCity;

function centerMap() {
  baseDesc = '<span style="font-weight: bold; font-size: 110%">'+baseName+'</span><br/><br/>'+baseAddress+'<br/>'+baseCity+'<br/><br>\n\
  <span style="float: left"><b>'+around_lbl+'</b>&nbsp;<input id="aroundfilter" size="5" name="aroundfilter" value="<?php echo $radius; ?>" onChange="DrawCircle(latitude,longitude,this,module);" style="width:35px">&nbsp;kms<br/>\
  <a href="index.php?module=evvtMap&file=update&action=evvtMapAjax&id=<?php echo -$current_user->id; ?>&show='+module+'">'+reload_lbl+'</a> / <a href="#" onclick="DrawCircle(latitude,longitude,$(\'aroundfilter\'),module);">'+drawcircle_lbl+'</a> / <a href="#" onclick="removeCircle();return false;">'+clearcircle_lbl+'</a> \n\</span>\n\
  ';

  if(from.lat() || from.lng())
	var center = new google.maps.LatLng(from.lat(),from.lng());
  else
	var center = new google.maps.LatLng(39.0,+0);  // TSolucio

  <?php
  echo "initialize(center,$zoom,'$maptype');"; 
  printResultLayer($results);
  ?>
  setupResultMarkers(resultLayer,module);
}

function DrawCircle(pointx,pointy,elem,module) {
    var rad=elem.value;
    var p =new google.maps.LatLng(pointx,pointy);
    rad *= 1600; // convert to meters if in miles
    if (draw_circle != null) {
        draw_circle.setMap(null);
    }   
    draw_circle = new google.maps.Circle({
        center: p,
        radius: rad,
        strokeColor: "#FF0000",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#FF0000",
        fillOpacity: 0.35,
        map: map
    });
	getDescriptionofList(pointx,pointy,rad/1600,module);
}

function removeCircle() {
    draw_circle.setMap(null);
}

var currentPosition=google.loader.ClientLocation;
if(currentPosition != null){
  from = new google.maps.LatLng(currentPosition.latitude,currentPosition.longitude);
  basePos = new google.maps.LatLng(currentPosition.latitude,currentPosition.longitude);
  baseAddress = currentPosition.address.country;
  baseName =  '<?php echo getUserFullName($current_user->id)?>';
  baseCity =  currentPosition.address.city;
} else if (<?php echo (empty($from) ? 'false' : 'true'); ?>) {
  from = new google.maps.LatLng(<?php echo (empty($from->latitude) ? '0' : $from->latitude); ?>,<?php echo (empty($from->longitude) ? '0' : $from->longitude); ?>);
  basePos = new google.maps.LatLng(<?php echo (empty($from->latitude) ? '0' : $from->latitude); ?>,<?php echo (empty($from->longitude) ? '0' : $from->longitude); ?>);
  baseAddress = '<?php echo addslashes($country); ?>';
  baseName = '<?php echo addslashes($ename); ?>';
  baseCity ='<?php echo addslashes($city); ?>';   
}
if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(
    function(position) {
      var latitude = position.coords.latitude;
      var longitude = position.coords.longitude;
      from = new google.maps.LatLng(latitude,longitude);
      basePos = new google.maps.LatLng(latitude,longitude);
      baseName =  '<?php echo getUserFullName($current_user->id)?>';
      baseAddress = '';
      baseCity = '';
      centerMap();
    },
    function() {},
    { enableHighAccuracy: true }
    );
}
centerMap();
//
// Slickgrid config starts here
//
function formatter(row, cell, value, columnDef, dataContext) {
    return value;
}
function collapseAllGroups() {
	  dataView.beginUpdate();
	  for (var i = 0; i < dataView.getGroups().length; i++) {
	    dataView.collapseGroup(dataView.getGroups()[i].value);
	  }
	  dataView.endUpdate();
	}

function expandAllGroups() {
  dataView.beginUpdate();
  for (var i = 0; i < dataView.getGroups().length; i++) {
    dataView.expandGroup(dataView.getGroups()[i].value);
  }
  dataView.endUpdate();
}

function clearGrouping() {
  dataView.groupBy(null);
}

function groupByModule() {
  dataView.groupBy(
      "entitymodule",
      function (g) {
        return "<?php echo getTranslatedString('LBL_MODULE'); ?>:  " + g.value + "  <span style='color:green'>(" + g.count + ")</span>";
      },
      function (a, b) {
        return a.value - b.value;
      }
  );
}

function setGrouping() {
	if (jQuery('#setGrouping').is(':checked')) {
		groupByModule();
	} else {
		clearGrouping();
	}
}

  var grid;
  var sortcol = "entityname";
  var sortdir = 1;
  var searchString = "";
  var dataView;
  var columns = [
    {id: "pinit", name: "", field: "pinit", width: 16, sortable: false, formatter: formatter},
    {id: "entityname", name: "<?php echo getTranslatedString('EntityName',$currentModule); ?>", field: "entityname", width: 160, sortable: true, formatter: formatter},
    {id: "entitymodule", name: "<?php echo getTranslatedString('EntityType',$currentModule); ?>", field: "entitymodule", width: 160, sortable: false, formatter: formatter},
  ];

  var options = {
    enableCellNavigation: true,
    enableColumnReorder: false,
    multiColumnSort: true
  };
  var evvtmapdata = [
<?php
	$count_result = count($results);
	$nu=0;
	foreach ($results as $rdok => $rdov) {
	 echo '{
		id: "evvtid_'.$rdok.'",
        pinit: "'.'<img src=\'modules/evvtMap/img/target-icon-mn.png\' width=\'16px\' onclick=\'evvtMap_CenterOn('.$rdov['lat'].','.$rdov['lng'].');\'>",
        entityname: "<a href=\'index.php?module='.$rdov['entitytype'].'&action=DetailView&record='.$rdok."'>".$rdov['entityname'].'</a>'.'",
		ename: "'.$rdov['entityname'].'",
        entitymodule: "'.getTranslatedString($rdov['entitytype']).'"}';
	  if (($nu+1)<$count_result) echo ',';
	  $nu++;
	}
?> ];
  jQuery(".grid-header .ui-icon")
  .addClass("ui-state-default ui-corner-all")
  .mouseover(function (e) {
    jQuery(e.target).addClass("ui-state-hover")
  })
  .mouseout(function (e) {
	  jQuery(e.target).removeClass("ui-state-hover")
  });
  var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();
  dataView = new Slick.Data.DataView({groupItemMetadataProvider: groupItemMetadataProvider,inlineFilters: true});
  grid = new Slick.Grid("#rdoGrid", dataView, columns, options);
  // register the group item metadata provider to add expand/collapse group handlers
  grid.registerPlugin(groupItemMetadataProvider);
  var pager = new Slick.Controls.Pager(dataView, grid, jQuery("#pager"));

  grid.onSort.subscribe(function (e, args) {
      var cols = args.sortCols;
      dataView.sort(function (dataRow1, dataRow2) {
        for (var i = 0, l = cols.length; i < l; i++) {
          var field = 'ename';
          var sign = cols[i].sortAsc ? 1 : -1;
          var value1 = dataRow1[field].toLowerCase(), value2 = dataRow2[field].toLowerCase();
          var result = (value1 == value2 ? 0 : (value1 > value2 ? 1 : -1)) * sign;
          if (result != 0) {
            return result;
          }
        }
        return 0;
      });
      grid.invalidate();
      grid.render();
    });

  // wire up model events to drive the grid
  dataView.onRowCountChanged.subscribe(function (e, args) {
    grid.updateRowCount();
    grid.render();
  });

  dataView.onRowsChanged.subscribe(function (e, args) {
    grid.invalidateRows(args.rows);
    grid.render();
  });

  // initialize the model after all the events have been hooked up
  dataView.beginUpdate();
  dataView.setItems(evvtmapdata);
  dataView.collapseGroup(0);
  dataView.endUpdate();
  jQuery("#gridContainer").resizable();
</script>

<style scoped="scoped">
.section-box {
    padding: 10px;
    width: 500px;
    margin: 20px auto;
    border: 1px solid #bbb;
    -moz-box-shadow: 0 1px 1px rgba(0,0,0,0.25), inset 0 0 30px rgba(0,0,0,0.07);
    -webkit-box-shadow: 0 1px 1px rgba(0,0,0,0.25), inset 0 0 30px rgba(0,0,0,0.07);
    box-shadow: 0 1px 1px rgba(0,0,0,0.25), inset 0 0 30px rgba(0,0,0,0.07);
    -webkit-border-radius: 8px;
    -moz-border-radius: 8px;
    border-radius: 8px;
}

.section-box img, .k-item img {
    height: 90px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    margin: 5px;
}

.defaultLabel {
	width: 180px;
	display: block;
	float: left;
	font-weight: bold;
	vertical-align: bottom;
}
</style>
