<?php
/***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  JPL TSolucio, S.L. Open Source
 * The Initial Developer of the Original Code is JPL TSolucio, S.L.
 * Portions created by JPL TSolucio, S.L. are Copyright (C) JPL TSolucio, S.L.
 * All Rights Reserved.
 ************************************************************************************/

class vtAppcomTSolucioListView extends vtApp {
	
	var $hasedit = true;
	var $hasrefresh = true;
	var $hassize = true;
	var $candelete = true;
	var $wwidth = 0;
	var $wheight = 490;
	var $haseditsize = true;
	var $ewidth = 0;
	var $eheight = 0;
	
	public function getContent($lang) {
		global $current_user,$adb,$log;
		$fields=array('accountname','industry','phone');
		$module='Accounts';
		$smarty = new vtigerCRM_Smarty;
		$smarty->template_dir = $this->apppath;
		$smarty->assign('appId',$this->appid);
		$smarty->assign('appClass',get_class($this));
		$kendocols=array();
		foreach ($fields as $field) {
			$kc=array(
					'field'=>$field,
					'title'=>getTranslatedString($field,$module),
					'encoded'=>'false'
			);
			$kendocols[]=$kc;
		}
		$smarty->assign('kendocols',$kendocols);
		$smarty->assign('actPasado',$this->getvtAppTranslatedString('actPasado', $lang));
		$smarty->assign('actFuturo',$this->getvtAppTranslatedString('actFuturo', $lang));
		$smarty->assign('sinCitas',$this->getvtAppTranslatedString('sinCitas', $lang));
		$showfuture=$adb->getOne('select showfuture from evvtapp_accountactlist where userid='.$current_user->id);
		switch ($showfuture) {
		  case 1:
			$smarty->assign('actFuturoChk','checked');
			$smarty->assign('actPasadoChk','');
			$smarty->assign('sinCitaChk','');
			break;
		  case 0:
			$smarty->assign('actFuturoChk','');
			$smarty->assign('actPasadoChk','checked');
			$smarty->assign('sinCitaChk','');
			break;
		  default:
			$smarty->assign('actFuturoChk','');
			$smarty->assign('actPasadoChk','');
			$smarty->assign('sinCitaChk','checked');
		}
		$output = $smarty->fetch('listview.tpl');
		$users = explode('::', $adb->getone("select users from evvtapp_accountactlist where userid={$current_user->id}"));
		$usernames = array();
		foreach($users as $userId) {
		  $usernames[] = getUserFullName($userId);
		}
		$output .= $this->getvtAppTranslatedString('Users:', $lang).' '.implode(', ', $usernames);
		return $output;
	}

	public function getListElements() {
		global $log,$adb,$current_user;
		$showfuture=$adb->getOne('select showfuture from evvtapp_accountactlist where userid='.$current_user->id);
		$userlistString = $adb->getone("select users from evvtapp_accountactlist where userid={$current_user->id}");
		if (!empty($userlistString)) {
		  $userlistString = implode(',', explode('::', $userlistString));
		  $userwhereString = "and acccrm.smownerid in ({$userlistString})";
		}
		if ($showfuture==1)
			$operator='>';
		else
			$operator='<=';
		$q="select distinct accountid,accountname,industry,vtiger_account.phone
 				from vtiger_activity
				inner join vtiger_seactivityrel on vtiger_seactivityrel.activityid = vtiger_activity.activityid
				inner join vtiger_crmentity as acccrm on acccrm.crmid=vtiger_seactivityrel.crmid and acccrm.deleted=0 and acccrm.setype='Accounts'
				inner join vtiger_crmentity as actcrm on actcrm.crmid=vtiger_activity.activityid and actcrm.deleted=0
				inner join vtiger_account on vtiger_account.accountid=acccrm.crmid				
				where date_start $operator now() and vtiger_activity.activitytype!='Emails'
				  and (vtiger_activity.status is null or vtiger_activity.status not in ('Completed'))
				  and (vtiger_activity.eventstatus is null or vtiger_activity.eventstatus not in ('Held')) {$userwhereString}";
		$tot=$adb->getone("select count(distinct accountid)
 				from vtiger_activity
				inner join vtiger_seactivityrel on vtiger_seactivityrel.activityid = vtiger_activity.activityid
				inner join vtiger_crmentity as acccrm on acccrm.crmid=vtiger_seactivityrel.crmid and acccrm.deleted=0 and acccrm.setype='Accounts'
				inner join vtiger_crmentity as actcrm on actcrm.crmid=vtiger_activity.activityid and actcrm.deleted=0
				inner join vtiger_account on vtiger_account.accountid=acccrm.crmid				
				where date_start $operator now() and vtiger_activity.activitytype!='Emails'
				  and (vtiger_activity.status is null or vtiger_activity.status not in ('Completed'))
				  and (vtiger_activity.eventstatus is null or vtiger_activity.eventstatus not in ('Held')) {$userwhereString}");
		if ($showfuture==2) {  // sin citas
			$q="select distinct accountid,accountname,industry,vtiger_account.phone
				from vtiger_account
				inner join vtiger_crmentity as acccrm on acccrm.crmid=vtiger_account.accountid and acccrm.deleted=0
				where not exists (select vtiger_activity.activityid
					from vtiger_activity
					inner join vtiger_seactivityrel on vtiger_seactivityrel.activityid = vtiger_activity.activityid
					inner join vtiger_crmentity as actcrm on actcrm.crmid=vtiger_activity.activityid and actcrm.deleted=0
					where vtiger_seactivityrel.crmid=accountid and vtiger_activity.activitytype!='Emails' and (vtiger_activity.status is null or vtiger_activity.status not in ('Completed'))
					and (vtiger_activity.eventstatus is null or vtiger_activity.eventstatus not in ('Held'))) {$userwhereString}";
			$tot=$adb->getone("select count(distinct accountid)
				from vtiger_account
				inner join vtiger_crmentity as acccrm on acccrm.crmid=vtiger_account.accountid and acccrm.deleted=0
				where not exists (select vtiger_activity.activityid
					from vtiger_activity
					inner join vtiger_seactivityrel on vtiger_seactivityrel.activityid = vtiger_activity.activityid
					inner join vtiger_crmentity as actcrm on actcrm.crmid=vtiger_activity.activityid and actcrm.deleted=0
					where vtiger_seactivityrel.crmid=accountid and vtiger_activity.activitytype!='Emails' and (vtiger_activity.status is null or vtiger_activity.status not in ('Completed'))
					and (vtiger_activity.eventstatus is null or vtiger_activity.eventstatus not in ('Held'))) {$userwhereString}");
		}
		if (isset($_REQUEST['sort'])) {
			$q.=' order by ';
			$ob='';
			foreach ($_REQUEST['sort'] as $sf) {
				$ob.=$sf['field'].' '.$sf['dir'].',';
			}
			$q.=trim($ob,',');
		}
		if (isset($_REQUEST['page']) and isset($_REQUEST['pageSize']))
			$q.=' limit '.(($_REQUEST['page']-1)*$_REQUEST['pageSize']).', '.$_REQUEST['pageSize'];
		$rs=$adb->query($q);
		$ret=array();
		while ($a=$adb->fetch_array($rs)) {
			$rec=array(
				'accountname'=>'<a href="index.php?module=Accounts&action=DetailView&record='.$a['accountid'].'">'.$a['accountname'].'</a>',
			    'industry'=>$a['industry'],
			    'phone'=>$a['phone']
			);
			$ret['results'][]=$rec;
		}
		$ret['total'][]=$tot;
		if ($tot==0) $ret['results'][]=array('accountname'=>getTranslatedString('LBL_NONE'),'industry'=>'','phone'=>'');
		return json_encode($ret);
	}

	public function postInstall() {
		global $adb;
		$adb->query('CREATE TABLE IF NOT EXISTS `evvtapp_accountactlist` (
				`vtappaactlistid` int(11) NOT NULL AUTO_INCREMENT,
				`userid` int(11) NOT NULL,
				`showfuture` boolean NOT NULL,
				`users` varchar(250) NOT NULL,
				PRIMARY KEY (`vtappaactlistid`),
				UNIQUE KEY `userid` (`userid`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');
	}
	
	public function savepgvars() {
		global $log,$current_user,$adb;
		$direction=vtlib_purify($_REQUEST['direction']);
		switch ($direction) {
			case 'future':
			 $direction=1;
			 break;
			case 'past':
			 $direction=0;
			 break;
			case 'sincita':
			 $direction=2;
			 break;
			default:
			 $direction=1;
		}
		$rdo=$adb->getOne('select count(*) from evvtapp_accountactlist where userid='.$current_user->id);
		if ($rdo==0) {
			$adb->pquery('insert into evvtapp_accountactlist
					(userid,showfuture) values (?,?)',
					array($current_user->id,$direction));
		} else {
			$adb->pquery('update evvtapp_accountactlist set
					showfuture=?
					where userid=?',
					array($direction,$current_user->id));
		}
	}
	
	public function savepgvars2() {
		global $log,$current_user,$adb;
		$users=implode('::',$_REQUEST['pbss_ids']);
		$rdo=$adb->getOne('select count(*) from evvtapp_accountactlist where userid='.$current_user->id);
		if ($rdo==0) {
		  $adb->pquery('insert into evvtapp_accountactlist 
		    (userid,users) values (?,?)',
		    array($current_user->id,$users));
		} else {
		  $adb->pquery('update evvtapp_accountactlist set
		    users=?
		    where userid=?',
		    array($users,$current_user->id));
		}
	}
	
	public function getEdit($lang) {
		global $app_strings,$current_user,$currentModule,$adb;
		$users = explode('::', $adb->getone("select users from evvtapp_accountactlist where userid={$current_user->id}"));
		ob_start();
		?>
		<form name='pipeline_by_sales_stage' id='pipeline_by_sales_stage' action="index.php" method="post" >
		<input type="hidden" name="module" value="evvtApps">
		<input type="hidden" name="action" value="evvtAppsAjax">
		<input type="hidden" name="file" value="vtappaction">
		<input type="hidden" name="vtappaction" value="dovtAppMethod">
		<input type="hidden" name="vtappmethod" value="savepgvars2">
		<input type="hidden" name="class" value="vtAppcomTSolucioListView">
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

}
?>
