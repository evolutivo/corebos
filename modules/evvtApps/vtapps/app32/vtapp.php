<?php
    require_once("modules/evvtApps/vtapps/baseapp/vtapp.php");

    class kibanadashboard extends vtApp {

	var $hasedit = true;
	var $hasrefresh = true;
	var $hassize = true;
	var $candelete = true;
	var $wwidth = 1100;
	var $wheight = 600;
	var $haseditsize = true;
	var $ewidth = 0;
	var $eheight = 0;
        
public function getContent($lang,$condit) {
global $adb,$current_language,$current_user;

		$smarty = new vtigerCRM_Smarty;
		$smarty->template_dir = $this->apppath;
             
return $smarty->display("ListViewKUI.tpl");
        }
}
?>