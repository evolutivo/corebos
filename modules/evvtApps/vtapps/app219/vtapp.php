 <?php
        class test andrea extends vtApp {

                var $hasedit = true;
                var $hasrefresh = true;
                var $hassize = true;
                var $candelete = false;
                var $wwidth = 250;
                var $wheight = 350;

                public function getContent($lang) {


                        global $log,$current_user,$adb,$app_strings;
                        $graph_title= $this->getvtAppTranslatedString('Title',$lang);
                        require_once('Smarty_setup.php');
                        $smarty = new vtigerCRM_Smarty;
                        $smarty->template_dir = $this->apppath;
                        $smarty->assign("APP", $app_strings);

                        $i=strrpos($this->apppath,'/');
                        $p=substr($this->apppath,$i+1);
                        $smarty->assign('appid', $p);

                        $mod_name1=  getTabModuleName('6');
                        $smarty->assign('MODULE1',"$mod_name1");
                        
                        $mod_name2=  getTabModuleName('41');
                        $smarty->assign('MODULE2',"$mod_name2");
                        $mod_name3=  getTabModuleName('125');
                        $smarty->assign('MODULE3',"$mod_name3");
                        $smarty->assign('Profile',json_encode(array('13')));

                        $smarty->assign('OUTSIDE_ID','3106476');
                        $smarty->assign('SLAVE_ID','');
                        $smarty->assign('searchable_outside','related');
                        $smarty->assign('create_new_outside',false);
                        $smarty->assign('searchable_primary',true);
                        $smarty->assign('create_new_primary',true);

                        $smarty->assign('searchable_secondary',false);
                        $smarty->assign('create_new_secondary',true);
                        $smarty->assign('onopen_primary','autoshow');
                        $smarty->assign('onopen_secondary','autoshow');
                        $smarty->assign('onsave_secondary','autoshut');
                        $smarty->assign('onsave_primary','autoshut');
                        $smarty->assign('add_document',false);
                        $smarty->assign('edit_this',true);
                        $smarty->assign('go_to_dtlview',true);
                        $smarty->display('vtapp_form.tpl');
                }        
            }
            ?>