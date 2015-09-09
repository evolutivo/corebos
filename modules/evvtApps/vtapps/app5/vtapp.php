<?php
/***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  JPL TSolucio, S.L. Open Source
 * The Initial Developer of the Original Code is JPL TSolucio, S.L.
 * Portions created by JPL TSolucio, S.L. are Copyright (C) JPL TSolucio, S.L.
 * All Rights Reserved.
 ************************************************************************************/

// pie graph needs this, but we don't for this vtApp, so I put a stub
function save_image_map($filename,$image_map) {
	return true;
}

class vtAppcomTSolucioDemoGraph1 extends vtApp {
	
	var $hasedit = true;
	var $hasrefresh = true;
	var $hassize = true;
	var $candelete = false;
	var $wwidth = 450;
	var $wheight = 450;

	public function getContent($lang) {
		global $log,$current_user,$adb;
		$graph_title= $this->getvtAppTranslatedString('Title',$lang);
		$cnt_val=$this->getGraphValues($lang);
		$name_val=$this->getGraphLabels($lang);
		$target_val=$this->getGraphTargets($lang);
		$output='<div id="vtappchart1app'.$this->appid.'"></div><script>
		$("#vtappchart1app'.$this->appid.'").kendoChart({
		title: {
		text: "'.$graph_title.'"
		},
		legend: {
			position: "right"
		},
		seriesDefaults: {
			labels: {
				visible: true,
				format: "{0}%"
			}
		},
		series: [{
			type: "pie",
			data: [ {
				category: "'.$name_val[0].'",
				value: '.$cnt_val[0].',
				color: "#00ff00"
			}, {
				category: "'.$name_val[1].'",
				value: '.$cnt_val[1].',
				color: "#ffff00"
			}, {
				category: "'.$name_val[2].'",
				value: '.$cnt_val[2].',
				color: "#ff0000"
			}],
		}],
		tooltip: {
			visible: true,
			format: "{0}%"
		}
		});
		</script>';
		$users = explode('::', $adb->getone("select users from evvtapp_accountschart where userid={$current_user->id}"));
		$usernames = array();
		foreach($users as $userId) {
		  $usernames[] = getUserFullName($userId);
		}
		$output .= $this->getvtAppTranslatedString('Users:', $lang).' '.implode(', ', $usernames);
		return $output;
		//return pie_chart($cnt_val,$name_val,$this->getWidth()-155,$this->getHeight()-15,0,0,0,0,$graph_title,$target_val,$cache,$cache);
	}

	public function doResize($lang,$newWidth=0,$newHeight=0) {
		$this->evvtSaveWH($newWidth,$newHeight);
		return $this->getContent($lang);
	}

	public function evvtSaveWH($wwidth,$wheight) {
		global $adb,$current_user;
		$numrecs=$adb->getone('SELECT count(*) FROM vtiger_evvtappsuser WHERE appid='.$this->appid.' and userid='.$current_user->id);
		if ($numrecs==0) $this->evvtCreateUserApp();
		$adb->pquery("update vtiger_evvtappsuser set wwidth=?,wheight=? where appid=? and userid=?",
				array($wwidth,$wheight,$this->appid,$current_user->id));
	}

	private function getGraphValues($lang) {
		global $log, $adb, $current_user;
		//return '2::3::1::1::2::1';
		$closedstates="'Held'";
		$userlistString = $adb->getone("select users from evvtapp_accountschart where userid={$current_user->id}");
		if (!empty($userlistString)) {
		  $userlistString = implode(',', explode('::', $userlistString));
		  $userwhereString = "and acccrm.smownerid in ({$userlistString})";
		}
		$accounts=$adb->getone("select count(*)
		  from vtiger_crmentity acccrm
		  where deleted=0 and setype='Accounts' {$userwhereString}");
		if ($accounts==0) {
		  $acccitaf = 0;
		  $acccitan = 0;
		  $acccitaa = 0;
		}
		else {
		$acccitaf=$adb->getone("select count(distinct acccrm.crmid)
				from vtiger_activity
				inner join vtiger_seactivityrel on vtiger_seactivityrel.activityid = vtiger_activity.activityid
				inner join vtiger_crmentity as acccrm on acccrm.crmid=vtiger_seactivityrel.crmid
				inner join vtiger_crmentity as actcrm on actcrm.crmid=vtiger_activity.activityid				
				where acccrm.deleted=0 and actcrm.deleted=0 and vtiger_activity.activitytype!='Emails'
				and date_start > now()
				and (vtiger_activity.status is null or vtiger_activity.status not in ('Completed'))
				  and (vtiger_activity.eventstatus is null or vtiger_activity.eventstatus not in ('Held'))
				and acccrm.setype='Accounts' {$userwhereString}");
		$acccitaa=$adb->getone("select count(distinct acccrm.crmid)
				from vtiger_activity
				inner join vtiger_seactivityrel on vtiger_seactivityrel.activityid = vtiger_activity.activityid
				inner join vtiger_crmentity as acccrm on acccrm.crmid=vtiger_seactivityrel.crmid
				inner join vtiger_crmentity as actcrm on actcrm.crmid=vtiger_activity.activityid				
				where acccrm.deleted=0 and actcrm.deleted=0 and vtiger_activity.activitytype!='Emails'
				and date_start <= now()
				and (vtiger_activity.status is null or vtiger_activity.status not in ('Completed'))
				  and (vtiger_activity.eventstatus is null or vtiger_activity.eventstatus not in ('Held'))
				and acccrm.setype='Accounts' {$userwhereString}");
		$acccita=$adb->getone("select count(distinct acccrm.crmid)
				from vtiger_activity
				inner join vtiger_seactivityrel on vtiger_seactivityrel.activityid = vtiger_activity.activityid
				inner join vtiger_crmentity as acccrm on acccrm.crmid=vtiger_seactivityrel.crmid
				inner join vtiger_crmentity as actcrm on actcrm.crmid=vtiger_activity.activityid
				where acccrm.deleted=0 and actcrm.deleted=0 and vtiger_activity.activitytype!='Emails'
				and (vtiger_activity.status is null or vtiger_activity.status not in ('Completed'))
				and (vtiger_activity.eventstatus is null or vtiger_activity.eventstatus not in ('Held'))
				and acccrm.setype='Accounts' {$userwhereString}");
		$acccitaf=round($acccitaf*100/$accounts,2);
		$acccitan=round(($accounts-$acccita)*100/$accounts,2);
		$acccitaa=round($acccitaa*100/$accounts,2);
		}
		return array(0=>$acccitaf,1=>$acccitaa,2=>$acccitan);
	}

	private function getGraphLabels($lang) {
		//return 'Partner::Public Relations::Cold Call::Direct Mail::Employee::--None--';
		return array(
		0=>$this->getvtAppTranslatedString('CitasFuturo',$lang),
		1=>$this->getvtAppTranslatedString('CitasAtrasadas',$lang),
		2=>$this->getvtAppTranslatedString('SinCitas',$lang)
		);
	}

	private function getGraphTargets($lang) {
		//return 'tsolucio.com::linkedin.com::google.es::vtigerspain.com::wtbr.com::hello.com';
		return ' :: :: ';
	}
	
	public function postInstall() {
		global $adb;
		$adb->query('CREATE TABLE IF NOT EXISTS `evvtapp_accountschart` (
				`vtapppgid` int(11) NOT NULL AUTO_INCREMENT,
				`userid` int(11) NOT NULL,
				`users` varchar(250) NOT NULL,
				PRIMARY KEY (`vtapppgid`),
				UNIQUE KEY `userid` (`userid`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');
	}
	
	public function getEdit($lang) {
		global $app_strings,$current_user,$currentModule,$adb;
		$users = explode('::', $adb->getone("select users from evvtapp_accountschart where userid={$current_user->id}"));
		ob_start();
		?>
		<form name='pipeline_by_sales_stage' id='pipeline_by_sales_stage' action="index.php" method="post" >
		<input type="hidden" name="module" value="evvtApps">
		<input type="hidden" name="action" value="evvtAppsAjax">
		<input type="hidden" name="file" value="vtappaction">
		<input type="hidden" name="vtappaction" value="dovtAppMethod">
		<input type="hidden" name="vtappmethod" value="savepgvars">
		<input type="hidden" name="class" value="vtAppcomTSolucioDemoGraph1">
		<input type="hidden" name="appid" value="<?php echo $this->appid; ?>">
		<table cellpadding="2" border="0"><tbody>
		<tr>
		<td valign='top' nowrap><?php echo getTranslatedString('LBL_USERS','Dashboard');?></td>
		<?php if($is_admin==false && $profileGlobalPermission[2] == 1 && ($defaultOrgSharingPermission[getTabid('Potentials')] == 3 or $defaultOrgSharingPermission[getTabid('Potentials')] == 0)) { ?>
		<td valign='top'><select name="pbss_ids[]" multiple size='3'><?php echo get_select_options_with_id(get_user_array(FALSE, "Active", $current_user->id,'private'), $users); ?></select></td>
		<?php } else { ?>
		<td valign='top'><select name="pbss_ids[]" multiple size='3'><?php echo get_select_options_with_id(get_user_array(FALSE,"Active",$current_user->id), $users); ?></select></td>
		<?php } ?>
		</tr><tr>
		<td align="right"><br /> <input class="button" onclick="$.post('index.php', $('#pipeline_by_sales_stage').serialize());$('#vtappedit<?php echo $this->appid; ?>').data('kendoWindow').close()" type="button" title="<?php echo $app_strings['LBL_SELECT_BUTTON_TITLE']; ?>" accessKey="<?php echo $app_strings['LBL_SELECT_BUTTON_KEY']; ?>" value="<?php echo $app_strings['LBL_SELECT_BUTTON_LABEL']?>" /></td>
		</tr></table>
		</form>
		<?php
		$output=ob_get_contents();
		ob_end_clean();
		return $output;
	}
	
	public function savepgvars() {
		global $log,$current_user,$adb;
		$users=implode('::',$_REQUEST['pbss_ids']);
		$rdo=$adb->getOne('select count(*) from evvtapp_accountschart where userid='.$current_user->id);
		if ($rdo==0) {
			$adb->pquery('insert into evvtapp_accountschart 
			  (userid,users) values (?,?)',
			  array($current_user->id,$users));
		} else {
			$adb->pquery('update evvtapp_accountschart set
			  users=?
			  where userid=?',
			  array($users,$current_user->id));
		}
	}
}
?>
