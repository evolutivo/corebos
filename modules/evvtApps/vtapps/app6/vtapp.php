<?php
/***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  JPL TSolucio, S.L. Open Source
 * The Initial Developer of the Original Code is JPL TSolucio, S.L.
 * Portions created by JPL TSolucio, S.L. are Copyright (C) JPL TSolucio, S.L.
 * All Rights Reserved.
 ************************************************************************************/

class vtAppcomTSolucioDemoGraph2 extends vtApp {
	
	var $hasedit = true;
	var $hasrefresh = true;
	var $hassize = true;
	var $candelete = false;
	var $wwidth = 450;
	var $wheight = 450;
	var $ewidth = 420;
	var $eheight = 300;

	public function getContent($lang) {
		global $log, $app_list_strings, $action;
		global $current_language, $current_user, $currentModule;
		$width = $this->getWidth()-20;
		$height = $this->getHeight()-60;
		$cache_file_name='cache/images/vtappbar'.$current_user->id.$this->appid.date("His").'.png';
		$files = glob('cache/images/vtappbar'.$current_user->id.$this->appid.'*');
		foreach($files as $file) unlink($file); // Keep useless cache under control
		ob_start();
		include_once $this->apppath.'/Chart_pipeline_by_sales_stage.php';
		$output=ob_get_contents();
		ob_end_clean();
		return $output;
	}

	public function getEdit($lang) {
		global $app_strings,$current_user,$currentModule;
		$cal_lang = substr($lang,0,2);
		$cal_dateformat = parse_calendardate($app_strings['NTC_DATE_FORMAT']);
		$cal_dateformat = '%Y-%m-%d'; // fix providedd by Jlee for date bug in Dashboard
		ob_start();
		?>
		<form name='pipeline_by_sales_stage' id='pipeline_by_sales_stage' action="index.php" method="post" >
		<input type="hidden" name="module" value="evvtApps">
		<input type="hidden" name="action" value="evvtAppsAjax">
		<input type="hidden" name="file" value="vtappaction">
		<input type="hidden" name="vtappaction" value="dovtAppMethod">
		<input type="hidden" name="vtappmethod" value="savepgvars">
		<input type="hidden" name="class" value="vtAppcomTSolucioDemoGraph2">
		<input type="hidden" name="appid" value="<?php echo $this->appid; ?>">
		<table cellpadding="2" border="0"><tbody>
		<tr>
		<td valign='top' nowrap><?php echo $this->getvtAppTranslatedString('DynDate',$lang);?></td>
		<td valign='top' ><select name="pbss_smart_dates"><?php echo get_select_options_with_id($this->inteligentFilterCriteria(),$_SESSION['pbss_smart_dates']); ?></select></td>
		</tr>
		<tr>
		<td valign='top' nowrap><?php echo getTranslatedString('LBL_DATE_START','Dashboard')?> <br><em><?php echo $app_strings['NTC_DATE_FORMAT']?></em></td>
		
		<td valign='top' ><input class="text" name="pbss_date_start" size='12' maxlength='10' id='date_start' value='<?php if (isset($_SESSION['pbss_date_start'])) echo $_SESSION['pbss_date_start']?>'>  <img src="<?php echo vtiger_imageurl('calendar.gif', $theme) ?>" id="date_start_trigger"> </td>
		</tr><tr>
		<tr>
		<td valign='top' nowrap><?php echo getTranslatedString('LBL_DATE_END','Dashboard');?><br><em><?php echo $app_strings['NTC_DATE_FORMAT']?></em></td>
		<td valign='top' ><input class="text" name="pbss_date_end" size='12' maxlength='10' id='date_end' value='<?php if (isset($_SESSION['pbss_date_end'])) echo $_SESSION['pbss_date_end']?>'>  <img src="<?php echo vtiger_imageurl('calendar.gif', $theme) ?>" id="date_end_trigger"> </td>
		</tr><tr>
		<td valign='top' nowrap><?php echo getTranslatedString('LBL_SALES_STAGES','Dashboard');?></td>
		<td valign='top' ><select name="pbss_sales_stages[]" multiple size='3'><?php echo get_select_options_with_id($this->getSalesStageValues($lang),$_SESSION['pbss_sales_stages']); ?></select></td>
		</tr><tr>
		<td valign='top' nowrap><?php echo getTranslatedString('LBL_USERS','Dashboard');?></td>
		<?php if($is_admin==false && $profileGlobalPermission[2] == 1 && ($defaultOrgSharingPermission[getTabid('Potentials')] == 3 or $defaultOrgSharingPermission[getTabid('Potentials')] == 0)) { ?>
		<td valign='top'><select name="pbss_ids[]" multiple size='3'><?php echo get_select_options_with_id(get_user_array(FALSE, "Active", $current_user->id,'private'),$_SESSION['pbss_ids']); ?></select></td>
		<?php } else { ?>
		<td valign='top'><select name="pbss_ids[]" multiple size='3'><?php echo get_select_options_with_id(get_user_array(FALSE,"Active",$current_user->id),$_SESSION['pbss_ids']); ?></select></td>
		<?php } ?>
		</tr><tr>
		<td align="right"><br /> <input class="button" onclick="$.post('index.php', $('#pipeline_by_sales_stage').serialize());$('#vtappedit<?php echo $this->appid; ?>').data('kendoWindow').close()" type="button" title="<?php echo $app_strings['LBL_SELECT_BUTTON_TITLE']; ?>" accessKey="<?php echo $app_strings['LBL_SELECT_BUTTON_KEY']; ?>" value="<?php echo $app_strings['LBL_SELECT_BUTTON_LABEL']?>" /></td>
		</tr></table>
		</form>
		<script type="text/javascript">
		Calendar.setup ({
		inputField : "date_start", ifFormat : "<?php echo $cal_dateformat ?>", showsTime : false, button : "date_start_trigger", singleClick : true, step : 1
		});
		Calendar.setup ({
		inputField : "date_end", ifFormat : "<?php echo $cal_dateformat ?>", showsTime : false, button : "date_end_trigger", singleClick : true, step : 1
		});
		</script>
		<?php
		$output=ob_get_contents();
		ob_end_clean();
		return $output;
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

	private function inteligentFilterCriteria()	{
		return Array("custom"=>"".getTranslatedString('Custom','CustomView')."",
				"prevfy"=>"".getTranslatedString('Previous FY','CustomView')."",
				"thisfy"=>"".getTranslatedString('Current FY','CustomView')."",
				"nextfy"=>"".getTranslatedString('Next FY','CustomView')."",
				"prevfq"=>"".getTranslatedString('Previous FQ','CustomView')."",
				"thisfq"=>"".getTranslatedString('Current FQ','CustomView')."",
				"nextfq"=>"".getTranslatedString('Next FQ','CustomView')."",
				"yesterday"=>"".getTranslatedString('Yesterday','CustomView')."",
				"today"=>"".getTranslatedString('Today','CustomView')."",
				"tomorrow"=>"".getTranslatedString('Tomorrow','CustomView')."",
				"lastweek"=>"".getTranslatedString('Last Week','CustomView')."",
				"thisweek"=>"".getTranslatedString('Current Week','CustomView')."",
				"nextweek"=>"".getTranslatedString('Next Week','CustomView')."",
				"lastmonth"=>"".getTranslatedString('Last Month','CustomView')."",
				"thismonth"=>"".getTranslatedString('Current Month','CustomView')."",
				"nextmonth"=>"".getTranslatedString('Next Month','CustomView')."",
				"last7days"=>"".getTranslatedString('Last 7 Days','CustomView')."",
				"last30days"=>"".getTranslatedString('Last 30 Days','CustomView')."",
				"last60days"=>"".getTranslatedString('Last 60 Days','CustomView')."",
				"last90days"=>"".getTranslatedString('Last 90 Days','CustomView')."",
				"last120days"=>"".getTranslatedString('Last 120 Days','CustomView')."",
				"next30days"=>"".getTranslatedString('Next 30 Days','CustomView')."",
				"next60days"=>"".getTranslatedString('Next 60 Days','CustomView')."",
				"next90days"=>"".getTranslatedString('Next 90 Days','CustomView')."",
				"next120days"=>"".getTranslatedString('Next 120 Days','CustomView')."",
		);
	}

	private function getSalesStageValues($lang) {
		global $app_strings,$current_user,$currentModule;
		include_once 'modules/PickList/PickListUtils.php';
		$ssvals=getAllPickListValues('sales_stage');
		$trssvals=array();
		foreach ($ssvals as $ssval) {
			$trssvals[$ssval]=getTranslatedString($ssval,'Potentials');
		}
		return $trssvals;
	}

	public function postInstall() {
		global $adb;
		$adb->query('CREATE TABLE IF NOT EXISTS `evvtapp_potentialgraph` (
				`vtapppgid` int(11) NOT NULL AUTO_INCREMENT,
				`userid` int(11) NOT NULL,
				`dyndate` varchar(20) NOT NULL,
				`startdate` date NOT NULL,
				`enddate` date NOT NULL,
				`sstage` varchar(250) NOT NULL,
				`users` varchar(250) NOT NULL,
				PRIMARY KEY (`vtapppgid`),
				UNIQUE KEY `userid` (`userid`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');
	}

	public function savepgvars() {
		global $log,$current_user,$adb;
		$smartdate=vtlib_purify($_REQUEST['pbss_smart_dates']);
		$startdate=vtlib_purify($_REQUEST['pbss_date_start']);
		$enddate=vtlib_purify($_REQUEST['pbss_date_end']);
		$sstage=implode('::',$_REQUEST['pbss_sales_stages']);
		if (empty($_REQUEST['pbss_ids']))
			$users='';
		else
			$users=implode('::',$_REQUEST['pbss_ids']);
		$rdo=$adb->getOne('select count(*) from evvtapp_potentialgraph where userid='.$current_user->id);
		if ($rdo==0) {
			$adb->pquery('insert into evvtapp_potentialgraph 
					(userid,dyndate,startdate,enddate,sstage,users) values (?,?,?,?,?,?)',
					array($current_user->id,$smartdate,$startdate,$enddate,$sstage,$users));
		} else {
			$adb->pquery('update evvtapp_potentialgraph set
					dyndate=?,startdate=?,enddate=?,sstage=?,users=?
					where userid=?',
					array($smartdate,$startdate,$enddate,$sstage,$users,$current_user->id));
		}
	}

}

?>
