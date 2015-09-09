 <?php
class testing extends vtApp {
	
	var $hasedit = true;
	var $hasrefresh = true;
	var $hassize = true;
	var $candelete = false;
	var $wwidth = 250;
	var $wheight = 350;

	public function getContent($lang) {
            
            
                global $log,$current_user,$adb;
		$graph_title= $this->getvtAppTranslatedString('Title',$lang);
                require_once('Smarty_setup.php');
                $smarty = new vtigerCRM_Smarty;
                $smarty->assign('MODULE1','Accounts');
                $smarty->assign('MODULE2','Contacts');
                $smarty->assign('Profile','0');
                $smarty->display('modules/evvtApps/vtapp_form.tpl');
	}        
    }
    ?>