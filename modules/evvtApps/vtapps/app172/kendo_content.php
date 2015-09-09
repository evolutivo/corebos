
<?php

global $adb,$current_language,$current_user,$root_directory;

require_once('Smarty_setup.php');

$smarty = new vtigerCRM_Smarty;
$smarty->template_dir = 'modules/evvtApps/vtapps/app52/';

$kaction=$_REQUEST['kaction'];
$profile=$_REQUEST['profile'];
$search_str=$_REQUEST['search_str'];

if($kaction=='display')
{

 $query=$adb->query(" 
          SELECT DISTINCT *
              from vtiger_account 
              join vtiger_accountbillads on accountaddressid=accountid
              join vtiger_crmentity on crmid=accountid
              where deleted = 0 
              and (bill_code like '%$search_str%' or bill_country like '%$search_str%' or accountname like '%$search_str%' )
          ");
      $count=$adb->num_rows($query);
    
      $query_field=$adb->query("SELECT vtiger_field.fieldname,vtiger_field.fieldlabel,visible
                          from vtiger_field
                          join vtiger_profile2field on vtiger_field.fieldid=vtiger_profile2field.fieldid
                          where vtiger_profile2field.tabid=6 and vtiger_profile2field.profileid=6 
                          and visible= 1");
      $count_field=$adb->num_rows($query_field);
      for($i=0;$i<$count;$i++){
          for($j=0;$j<$count_field;$j++){
              $fldname=$adb->query_result($query_field,$j,'fieldname');
              $content[$i][$fldname]=$adb->query_result($query,$i,$fldname);
                  } 
                  $content[$i]['id']=$adb->query_result($query,$i,'accountid');
      }
      
}


$smarty->assign("Delete",'dsfds');
return $smarty->display('vtapp_hostess.tpl');


