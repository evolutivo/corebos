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

function getResult($gc,$query,$idsarray=array()) {
	global $adb;
	$ret = array();
	$result = $adb->query($query); 
	while($row=$adb->fetch_array($result)) {
		$cname="<table>";
		$cnameQuery=$adb->query("Select contactid, concat(firstname,' ',lastname) as name,phone from vtiger_contactdetails where accountid = ".$row['id'].";");
		while ($contacts = $adb->fetch_array($cnameQuery)){
			$cname=$cname.'<tr><td><a href="index.php?module=Contacts&parenttab=Marketing&action=DetailView&record='.$contacts["contactid"].'" target="_blank">'.$contacts["name"]."</a></td><td> Phone : ".$contacts["phone"]."</td></tr><br/>";
		}
                $cname.="</table>";
		//in vtiger e' scambiato country con state
		$coord = $gc->getGeoCode($row['id'],strtolower($row['state']),strtolower($row['city']),$row['code'],strtolower($row['street']),strtolower($row['country']));
		$approx = "";
		if($coord) //add item to final result
		{
			$state = $row['state']?" (".strtoupper($row['state']).")":"";
			if($coord->approx)
				$approx = "<br/><span style='color: grey; font-size: smaller'>".getTranslatedString('Approx','evvtMap')."</span></br>";
			$content='';
			if ($type=="HelpDesk" or $type=="directticket") {
				$content=evvt_getTicketInformation($row['id']).'<br/>';
			}

			if (isset($idsarray[$row['id']])) {
			  $cvid = $idsarray[$row['id']];
			}
			else {
			  $cvid = 0;
			}
			$ret[$row['id']] = array(
						"name" => addslashes($row['name']).
							(empty($row['industry']) ? '' : "<br/>".addslashes($row['industry'])).
							(empty($row['email']) ? '' : "<br/>".addslashes($row['email'])),
							(empty($row['website']) ? '' : "<br/>".addslashes($row['website'])),
						"city" => addslashes(ucwords(strtolower($row['city']))),
						"phone" => addslashes(ucwords(strtolower($row['phone']))),
						"extra" => addslashes(ucwords(strtolower($row['street']."<br/>".$row['code']." ".$row['city'])).$state.$approx.$content),
						"lat" => $coord->latitude,
						"lng" => $coord->longitude,
						"vid"=> $cvid,
						"entityname"=>addslashes($row['name']),
						"entitytype"=>$row['etype'],
						"conname"=>$cname,
					);
		}
	}
	return $ret;
}

function getResults($type,$idarrays,$start_date='',$end_date='',$user='') { 
	$start_date=str_replace('/','-',$start_date);
	$end_date=str_replace('/','-',$end_date);
	$showAccounts=vtlib_purify($_REQUEST['showAccounts']);
	$showContacts=vtlib_purify($_REQUEST['showContacts']);
	$showLeads=vtlib_purify($_REQUEST['showLeads']);
	$user=vtlib_purify($_REQUEST['user']);
	$rptuser=vtlib_purify($_REQUEST['reports_to_id']);
	$gc = new GeoCoder();
	if($idarrays!='') $ids=implode(array_keys($idarrays),',');
	switch($type) {
//		case "Potentials":
//			$query = "select vtiger_account.accountid as id,accountname as name, bill_code as code, bill_city as city,bill_country as country,bill_state as state,bill_street as street from vtiger_potential join vtiger_accountbillads on vtiger_potential.related_to=vtiger_accountbillads.accountaddressid join vtiger_account on vtiger_account.accountid=vtiger_potential.related_to WHERE bill_code IS NOT NULL AND bill_city IS NOT NULL ";
//			if($ids)
//				$query .= "AND potentialid in ($ids) ";
//		break;
		case "Contacts":
			$query = "select concat(firstname,' ',lastname) as name, title as industry, email, '' as website, contactaddressid as id, mailingcity as city, mailingzip as code, mailingcountry as country, mailingstate as state, mailingstreet as street, setype as etype, phone
					 from vtiger_contactaddress
					 inner join vtiger_contactdetails on contactaddressid=contactid
					 inner join vtiger_crmentity on crmid=contactid ";
			if($ids)
				$query .= " WHERE contactid in ($ids)";
		break;
		case "HelpDesk":
			$query = 'select distinct accountid as id,accountname as name, bill_code as code, bill_city as city,bill_country as country,bill_state as state,bill_street as street, \'Accounts\' as etype
					 from vtiger_troubletickets
					 inner join vtiger_crmentity on crmid=ticketid 
					 inner join vtiger_account on parent_id=accountid
					 inner join vtiger_accountbillads on accountid=accountaddressid';
			if($rptuser!=0)
				$query.=" WHERE smownerid=$rptuser ";
			if($ids)
				$query.= ($rptuser!=0 ? ' and' : ' WHERE')." ticketid in ($ids) ";
			$query.=' UNION ';
			$query.="select distinct contactid as id,concat(firstname,' ',lastname) as name, mailingzip as code, mailingcity as city,mailingcountry as country,mailingstate as state,mailingstreet as street, 'Contacts' as etype
					 from vtiger_troubletickets
					 inner join vtiger_crmentity on crmid=ticketid 
					 inner join vtiger_contactdetails on parent_id=contactid
					 inner join vtiger_contactaddress on contactid=contactaddressid ";
			if($rptuser!=0)
				$query.=" WHERE smownerid=$rptuser ";
			if($ids)
				$query .= ($rptuser!=0 ? ' and' : ' WHERE')." ticketid in ($ids) ";
		break;
		case "Accounts":
			$query = "select
				accountname as name, industry, email1 as email, website, accountaddressid as id, bill_city as city, bill_code as code, bill_country as country, bill_state as state, bill_street as street, setype as etype,phone
				from vtiger_accountbillads
				join vtiger_account on accountaddressid=accountid
				join vtiger_crmentity on crmid=accountid";
			 if($user!=0)
				$queryUser="and smownerid=$user"; 
			if($ids)
				$query.= " WHERE accountid in ($ids) $queryUser";                        
		break;                
		case "Leads":
			 $query = "select 
				concat(firstname,' ',lastname,' - ', company) as name, industry, email, '' as website, leadaddressid as id, city, code, country, state, lane as street, setype as etype,phone
				from vtiger_leaddetails
				join vtiger_leadaddress on leadaddressid=leadid
				join vtiger_crmentity on crmid=leadid";
			if($user!=0) 
				$queryUser="and smownerid=$user";    
			if($ids)
				$query.= " WHERE leadaddressid in ($ids) $queryUser";                        
		break;
		case "Events":
			$query='select  distinct(name),industry, phone, email, website, id, code, city,country,state,street, etype
				from (';
			if ($showAccounts) {
				$query.= "select a.activityid as vid,accountname as name, industry, phone, email1 as email, website, accountaddressid as id, bill_code as code, bill_city as city,bill_country as country,bill_state as state,bill_street as street, 'Accounts' as etype
					from vtiger_activity a
					join vtiger_seactivityrel sa on sa.activityid=a.activityid
					join vtiger_crmentity ce on ce.crmid=a.activityid
					join vtiger_account on sa.crmid=accountid
					join vtiger_accountbillads on accountid=accountaddressid";
				if ($user!=0)
					$queryuser=" and ce.smownerid=$user";
				else
					$queryuser='';
				if (!empty($start_date) && empty($end_date)) 
					$query.=" WHERE a.date_start > '$start_date' $queryuser";  
				elseif(!empty($end_date) && empty($start_date)) 
					$query.=" WHERE a.due_date<'$end_date' $queryuser";  
				elseif(!empty($end_date) && !empty($start_date)) 
					$query .= " WHERE (a.date_start between '$start_date' and '$end_date') and (a.due_date between '$start_date' and '$end_date') $queryuser";
				else {
					if($user!=0) $query.=" WHERE ce.smownerid=$user";   
				}
			}
			if ($showLeads) {
				if ($showAccounts) $query.=' UNION ';
				$query .= "select a.activityid as vid,concat(firstname,' ',lastname,' - ', company) as name, industry,phone, email, '' as website, leadaddressid as id, city, code, country, state, lane as street, 'Leads' as etype
                            from vtiger_activity a
                            join vtiger_seactivityrel sa on sa.activityid=a.activityid
                            join vtiger_crmentity ce on ce.crmid=a.activityid
                            join vtiger_leaddetails on sa.crmid=leadid 
                            join vtiger_leadaddress on leadaddressid=leadid ";
				if ($user!=0)
					$queryuser=" and ce.smownerid=$user";
				else
					$queryuser='';
				if (!empty($start_date) && empty($end_date)) 
					$query.=" WHERE a.date_start > '$start_date' $queryuser";  
				elseif (!empty($end_date) && empty($start_date)) 
					$query.=" WHERE a.due_date<'$end_date' $queryuser";  
				elseif (!empty($end_date) && !empty($start_date)) 
					$query .= " WHERE (a.date_start between '$start_date' and '$end_date') and (a.due_date between '$start_date' and '$end_date') $queryuser";
				else {
					if($user!=0) $query.=" WHERE ce.smownerid=$user";   
				}
			}
			if ($showContacts) {
				if ($showAccounts or $showLeads) $query.=' UNION ';
				$query .= "select a.activityid as vid,concat(firstname,' ',lastname) as name, title as industry,phone, email, '' as website, contactaddressid as id, mailingcity as city, mailingzip as code, mailingcountry as country, mailingstate as state, mailingstreet as street, 'Contacts' as etype
                            from vtiger_activity a
                            join vtiger_cntactivityrel ca on ca.activityid=a.activityid
                            join vtiger_crmentity cn on cn.crmid=a.activityid
                            join vtiger_contactdetails on ca.contactid=vtiger_contactdetails.contactid
                            join vtiger_contactaddress on contactaddressid=vtiger_contactdetails.contactid ";
				if ($user!=0)
					$queryuser=" and cn.smownerid=$user";
				else
					$queryuser='';
				if (!empty($start_date) && empty($end_date))
					$query.=" WHERE a.date_start > '$start_date' $queryuser";
				elseif (!empty($end_date) && empty($start_date))
					$query.=" WHERE a.due_date<'$end_date' $queryuser";
				elseif (!empty($end_date) && !empty($start_date))
					$query .= " WHERE (a.date_start between '$start_date' and '$end_date') and (a.due_date between '$start_date' and '$end_date') $queryuser";
				else {
					if($user!=0) $query.=" WHERE cn.smownerid=$user";
				}
			}
			$query.=" ) as subquery";
			if(!$showLeads && !$showAccounts && !$showContacts) $query='';
			$idsarray=array();
		break;
		default:
			return array();
	}
	return getResult($gc,$query,$idarrays);
}
function doRadiusSearch($x,$y,$radius) {
	global $adb, $current_user;
	$gc = new GeoCoder();
	$nonAdminAccessControlQuery = getNonAdminAccessControlQuery("Contacts", $current_user);
	$query = "(SELECT m.evvtmapid as id,
			( 3959 * acos( cos( radians($x) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($y) ) + sin( radians($x) ) * sin( radians( lat ) ) ) ) AS distance,
			concat(cd.firstname,' ',cd.lastname) as name,cd.title as industry,cd.email, '' as website,ca.mailingcity as city, ca.mailingzip as code,
			ca.mailingcountry as country, ca.mailingstate as state, ca.mailingstreet as street, 'Contacts' as etype
			FROM vtiger_evvtmap m
			JOIN vtiger_contactdetails cd on m.evvtmapid=cd.contactid
			JOIN vtiger_contactaddress ca on ca.contactaddressid=cd.contactid
			join vtiger_crmentity on vtiger_crmentity.crmid=cd.contactid and vtiger_crmentity.deleted=0
			{$nonAdminAccessControlQuery}
			HAVING distance < $radius LIMIT 0 , 40)";
	$nonAdminAccessControlQuery = getNonAdminAccessControlQuery("Leads", $current_user);
	$query.= " UNION (SELECT m.evvtmapid as id,
			( 3959 * acos( cos( radians($x) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($y) ) + sin( radians($x) ) * sin( radians( lat ) ) ) ) AS distance,
			concat(l.firstname,' ',l.lastname, ' - ',l.company) as name,industry,l.email, '' as website,la.city as city, la.code as code,
			la.country as country, la.state as state, la.lane as street, 'Leads' as etype
			FROM vtiger_evvtmap m
			JOIN vtiger_leaddetails l on m.evvtmapid=l.leadid
			JOIN vtiger_leadaddress la on la.leadaddressid=l.leadid
			join vtiger_crmentity on vtiger_crmentity.crmid=l.leadid and vtiger_crmentity.deleted=0
			{$nonAdminAccessControlQuery}
			HAVING distance < $radius LIMIT 0 , 40)";
	$nonAdminAccessControlQuery = getNonAdminAccessControlQuery("Accounts", $current_user);
	$query.= " UNION (SELECT m.evvtmapid as id,
			( 3959 * acos( cos( radians($x) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($y) ) + sin( radians($x) ) * sin( radians( lat ) ) ) ) AS distance,
			a.accountname as name,industry,a.email1, a.website,ac.bill_city as city, ac.bill_code as code,
			ac.bill_country as country, ac.bill_state as state, ac.bill_street as street, 'Accounts' as etype
			FROM vtiger_evvtmap m
			JOIN vtiger_account a on m.evvtmapid=a.accountid
			JOIN vtiger_accountbillads ac on ac.accountaddressid=a.accountid
			join vtiger_crmentity on vtiger_crmentity.crmid=a.accountid and vtiger_crmentity.deleted=0
			{$nonAdminAccessControlQuery}
			HAVING distance < $radius LIMIT 0 , 40) ORDER BY distance";
	return getResult($gc,$query,array());
}
function generateReport($arrayofIds,$module){
    $report='';
    foreach($arrayofIds as $id){
        $report.='<a href="index.php?module='.$module.'&action=DetailView&record='.$id.'">'.$id.'</a><br/>';
    }
    return $report;
}
function printResultLayer($results)
{	
	echo "var resultLayer = {\n";
	foreach($results as $key=>$result) {
		$name = addcslashes($result['name'], "'\r\n\\");
		$city = addcslashes($result['city'], "'\r\n\\");
		echo "\t'{$key}': \n";
		echo "\t{\n";
		echo "\t'name': '{$name}', \n";
		echo "\t'city': '{$city}', \n";
		echo "\t'extra': '".str_replace(array("\r", "\r\n", "\n"), '', $result['extra'])."', \n";
		echo "\t'phone': '{$result['phone']}', \n";
		echo "\t'pos': [{$result['lat']},{$result['lng']}], \n";
		echo "\t'cvid': {$result['vid']}, \n";
		echo "\t'etype': '{$result['entitytype']}', \n";
		echo "\t'cname': '{$result['conname']}', \n";
		echo "\t},\n";
	}
	echo "	};\n";
}

function getAllCvIdOfModule($module)
{
	global $adb,$log;
	$log->debug("Entering getAllCvIdOfModule($module)");
	$qry_res = $adb->pquery("select cvid from vtiger_customview where entitytype=?", array($module));
        $number=$adb->num_rows($qry_res);
        $allids=array();
        for($i=0;$i<$number;$i++)
        {
        $cvid = $adb->query_result($qry_res,$i,"cvid");
        array_push($allids,$cvid);
        }
	$log->debug("Exiting getAllCvIdOfModule($module)");
	return $allids;
}
function getCustomViewCheckBoxes($viewid, $customviewmodule, $markselected=true, $varprefix='')
{
        global $adb,$current_user;
        global $app_strings;
        $tabid = getTabid($customviewmodule);
        require('user_privileges/user_privileges_'.$current_user->id.'.php');

        $shtml_user = '';
        $shtml_pending = '';
        $shtml_public = '';
        $shtml_others = '';

        $selected = 'checked';
        if ($markselected == false) $selected = '';

        $ssql = "select vtiger_customview.*, vtiger_users.user_name from vtiger_customview inner join vtiger_tab on vtiger_tab.name = vtiger_customview.entitytype
                                left join vtiger_users on vtiger_customview.userid = vtiger_users.id ";
        $ssql .= " where vtiger_tab.tabid=?";
        $sparams = array($tabid);

        if($is_admin == false){
                $ssql .= " and (vtiger_customview.status=0 or vtiger_customview.userid = ? or vtiger_customview.status = 3 or vtiger_customview.userid in(select vtiger_user2role.userid from vtiger_user2role inner join vtiger_users on vtiger_users.id=vtiger_user2role.userid inner join vtiger_role on vtiger_role.roleid=vtiger_user2role.roleid where vtiger_role.parentrole like '".$current_user_parent_role_seq."::%'))";
                array_push($sparams, $current_user->id);
        }
        $ssql .= " ORDER BY viewname";
        $result = $adb->pquery($ssql, $sparams);
        while($cvrow=$adb->fetch_array($result))		
                {
                if($cvrow['viewname'] == 'All')
                {
                        $cvrow['viewname'] = $app_strings['COMBO_ALL'];
                }

                $option = '';
                $viewname = $cvrow['viewname'];
                if ($cvrow['status'] == CV_STATUS_DEFAULT || $cvrow['userid'] == $current_user->id) {
                        $disp_viewname = $viewname;
                } else {
                        $disp_viewname = $viewname . " [" . $cvrow['user_name'] . "] ";
                }

                $varname = $varprefix.'viewid';
                if($cvrow['setdefault'] == 1 && $viewid =='')
                {
                        $option = "<input $selected type=\"checkbox\" name=\"{$varname}[]\" id=\"{$varname}[]\" value=\"".$cvrow['cvid']."\">".$disp_viewname;				
                }
                else{
                if( is_array($viewid) and in_array($cvrow['cvid'],$viewid) )
                {
                        $option = "<input $selected type=\"checkbox\" name=\"{$varname}[]\" id=\"{$varname}[]\" value=\"".$cvrow['cvid']."\">".$disp_viewname;				
                }
                else
                {
                        $option = "<input type=\"checkbox\" name=\"{$varname}[]\" id=\"{$varname}[]\" value=\"".$cvrow['cvid']."\">".$disp_viewname;
                }
                }

                // Add the option to combo box at appropriate section
                if($option != '') {
                        if ($cvrow['status'] == CV_STATUS_DEFAULT || $cvrow['userid'] == $current_user->id) {
                                $shtml_user .= $option;
                        } elseif ($cvrow['status'] == CV_STATUS_PUBLIC) {
                                if ($shtml_public == '')
                                        $shtml_public = "<option disabled>--- " .$app_strings['LBL_PUBLIC']. " ---</option>";
                                $shtml_public .= $option;
                        } elseif ($cvrow['status'] == CV_STATUS_PENDING) {
                                if ($shtml_pending == '')
                                        $shtml_pending = "<option disabled>--- " .$app_strings['LBL_PENDING']. " ---</option>";
                                $shtml_pending .= $option;
                        } else {
                                if ($shtml_others == '')
                                        $shtml_others = "<option disabled>--- " .$app_strings['LBL_OTHERS']. " ---</option>";
                                $shtml_others .= $option;
                        }
                }
        }

        $shtml = $shtml_user;
        if ($is_admin == true) $shtml .= $shtml_pending;
        $shtml = $shtml . $shtml_public . $shtml_others;
        return $shtml;
}

/**
*Function to construct HTML select combo box
*@param $fieldname -- the field name :: Type string
*@param $tablename -- The table name :: Type string
*constructs html select combo box for combo field and returns it in string format.
* Copied from Calendar/CalendarCommon.php and modified
*/
function evvt_Map_getActFieldCombo($fieldname,$tablename,$selected)
{
        global $adb, $mod_strings,$current_user;
        require('user_privileges/user_privileges_'.$current_user->id.'.php');
        $combo = '';
        $js_fn = '';
        $combo .= '<select name="'.$fieldname.'" id="'.$fieldname.'" class=small '.$js_fn.'>';
        if($is_admin)
                $q = "select * from ".$tablename;
        else
        {
                $roleid=$current_user->roleid;
                $subrole = getRoleSubordinates($roleid);
                if(count($subrole)> 0)
                {
                        $roleids = $subrole;
                        array_push($roleids, $roleid);
                }
                else
                {
                        $roleids = $roleid;
                }

                if (count($roleids) > 1) {
                        $q="select distinct $fieldname from  $tablename inner join vtiger_role2picklist on vtiger_role2picklist.picklistvalueid = $tablename.picklist_valueid where roleid in (\"". implode($roleids,"\",\"") ."\") and picklistid in (select picklistid from $tablename) order by sortid asc";
                } else {
                        $q="select distinct $fieldname from $tablename inner join vtiger_role2picklist on vtiger_role2picklist.picklistvalueid = $tablename.picklist_valueid where roleid ='".$roleid."' and picklistid in (select picklistid from $tablename) order by sortid asc";
                }
        }
        $res = $adb->query($q);
        $noofrows = $adb->num_rows($res);
        $combo .= '<option value="any"'.(empty($selected)?' selected ':'').'>'.getTranslatedString('Any','Map').'</option>';
        for($i = 0; $i < $noofrows; $i++) {
                $value = $adb->query_result($res,$i,$fieldname);
                $combo .= '<option value="'.$value.'"'.($value==$selected?' selected ':'').'>'.getTranslatedString($value).'</option>';
        }

$combo .= '</select>';
return $combo;
}

function evvt_getTicketInformation($parentid) {
        global $adb;
        $sql="select ticketid,ticket_no,smownerid
        from vtiger_troubletickets
        inner join vtiger_crmentity on crmid=ticketid
        where deleted=0 and parent_id=$parentid and vtiger_troubletickets.status='In Progress' order by createdtime";
        $content='';
        $tkrs=$adb->query($sql);
        while ($tk=$adb->fetch_array($tkrs)) {
                $content.="<br/>&nbsp;&nbsp;-&nbsp;<a href='index.php?module=HelpDesk&action=DetailView&record=".$tk['ticketid']."'>".$tk['ticket_no']."</a>&nbsp;(".trim(getUserFullName($tk['smownerid'])).")";
        }
        return $content;
}

function evvt_savemapconfig() {
        global $adb,$current_user;
        $params = array(
                        vtlib_purify($_REQUEST['defradius']),
                        vtlib_purify($_REQUEST['defzoom']),
                        vtlib_purify($_REQUEST['defmaptype']),
                        vtlib_purify($_REQUEST['deflocation']),
                        vtlib_purify($_REQUEST['defcenter']),
                        vtlib_purify($_REQUEST['deftab']),
                        $current_user->id
                );
        $exist = $adb->getone('select count(*) as cnt from vtiger_evvtmapdefaults where uid='.$current_user->id);
        if ($exist>0) {
                $adb->pquery('update vtiger_evvtmapdefaults set radius=?,zoom=?,maptype=?,location=?,mapcenter=?,tab=? where uid=?',$params);
        } else {
                $adb->pquery('insert into vtiger_evvtmapdefaults (radius,zoom,maptype,location,mapcenter,tab,uid) values (?,?,?,?,?,?,?)',$params);
        }
}

function evvt_getmapconfig() {
		global $adb,$current_user;
		$cfgrs = $adb->query('select * from vtiger_evvtmapdefaults where uid='.$current_user->id);
		if ($adb->num_rows($cfgrs)==0) {
			$cfgrs = $adb->query('select * from vtiger_evvtmapdefaults where uid=0'); // global default values
		}
		$cfg = $adb->fetch_array($cfgrs);
		return array(
				$cfg['radius'],
				$cfg['zoom'],
				$cfg['maptype'],
				$cfg['location'],
				$cfg['mapcenter'],
				$cfg['tab']
			);
	}

?>
