 <?php
  ini_set('display_errors','on');
require_once('include/database/PearDatabase.php');
require_once('include/utils/utils.php');
//maurizio.tesa@gmail.com
global $adb,$date_var,$current_user,$log,$root_directory;
 
$kaction=$_REQUEST['kaction'];
$content=array();

if($kaction=='retrieve'){ 
 
 $query=$adb->query("SELECT *
                          from  vtiger_scripts_security
                          join vtiger_scripts on scriptid=id
                          join vtiger_role on vtiger_role.roleid=vtiger_scripts_security.roleid
                          ");
  $nr_file=$adb->num_rows($query);
  
for($i=0;$i<$nr_file;$i++){
  
  $sec_id=$adb->query_result($query,$i,'sec_id');
  $scriptname=$adb->query_result($query,$i,'name');
  $scriptid=$adb->query_result($query,$i,'scriptid');
  $rolename=$adb->query_result($query,$i,'rolename');
  $roleid=$adb->query_result($query,$i,'roleid');
  $export=$adb->query_result($query,$i,'export_scr');
  $delete=$adb->query_result($query,$i,'delete_scr');
  $execute=$adb->query_result($query,$i,'execute_scr');
  $folder=$adb->query_result($query,$i,'folder');

  $content[$i]['id']=$sec_id;
  $content[$i]['name']=$scriptname;
  $content[$i]['scriptid']=$scriptid;
  $content[$i]['rolename']=$rolename;
  $content[$i]['roleid']=$roleid;
  $content[$i]['folder']=$folder;
  $content[$i]['export_scr']=($export==1 ? true : false);
  $content[$i]['delete_scr']=($delete==1 ? true : false);
  $content[$i]['execute_scr']=($execute==1 ? true : false);
      
}

echo json_encode($content);
}
elseif($kaction=='save'){
 
global $log,$adb;
$models=$_REQUEST['models'];
$model_values=array();
$model_values=json_decode($models);

$nr=count($model_values);
$mv=$model_values[$nr-1];
$query="Update vtiger_scripts_security"
     . " set scriptid=?,"
        . "  roleid=?,"
        . "  export_scr=?,"
        . "  delete_scr=?,"
        . "  execute_scr=? "
     . "  where sec_id=?";
$log->debug('klm6 '.$query);     
$query=$adb->pquery($query,array($mv->scriptid,$mv->roleid,($mv->export_scr == true ? 1 : 0),
    ($mv->delete_scr== true ? 1 : 0),($mv->execute_scr== true ? 1 : 0),$mv->id));
}
elseif($kaction=='delete'){
    
global $log,$adb;
$models=$_REQUEST['models'];
$model_values=array();
$model_values=json_decode($models);

$nr=count($model_values);
$mv=$model_values[$nr-1];

$query=$adb->pquery("Delete  from  vtiger_scripts_security
                          where sec_id = ?",array($mv->id));

}
elseif($kaction=='add'){
     global $log,$adb;
    $models=$_REQUEST['models'];
    $model_values=array();
    $model_values=json_decode($models);

    $nr=count($model_values);
    $mv=$model_values[$nr-1];
    $query="insert into  vtiger_scripts_security (scriptid,roleid,export_scr,delete_scr,execute_scr) "
         . " values (?,?,?,?,?) ";

    $query=$adb->pquery($query,array($mv->scriptid,$mv->roleid,$mv->export_scr,$mv->delete_scr,$mv->execute_scr));
}

elseif($kaction=='list_script'){ 
 
     $query=$adb->pquery("SELECT *
                          from  vtiger_scripts
                          where deleted_script <> 1",array());
      $nr_file=$adb->num_rows($query);

    for($i=0;$i<$nr_file;$i++){

      $scriptname=$adb->query_result($query,$i,'name');
      $scriptid=$adb->query_result($query,$i,'id');

      $content[$i]['id']=$scriptid;
      $content[$i]['name']=$scriptname;
    }

    echo json_encode($content);
}
elseif($kaction=='list_role'){ 
 
     $query=$adb->pquery("SELECT *
                          from  vtiger_role
                          ",array());
      $nr_file=$adb->num_rows($query);

    for($i=0;$i<$nr_file;$i++){

      $rolename=$adb->query_result($query,$i,'rolename');
      $roleid=$adb->query_result($query,$i,'roleid');

      $content[$i]['id']=$roleid;
      $content[$i]['name']=$rolename;
    }

    echo json_encode($content);
}

//* * * * * sh /var/www/html/teknema/cron/modules/com_vtiger_workflow/com_vtiger_workflow.sh
//20 * * * * cd /var/www/html/teknema; php fillexistingIds.php
//00 23 * * * cd /var/www/html/teknema/cron; php tpv_globale.php
//40 * * * * cd /var/www/html/teknema/cron; php spar_azienda_to_ticket.php
//00 3 * * * cd  /var/www/html/teknema/cron; php update_dayson.php
//10 3 * * * cd /var/www/html/teknema/cron; php giorniapertura.php
//15 3 * * * cd /var/www/html/teknema/cron; php giorni_apertura_non_businness.php
//20 3 * * * cd /var/www/html/teknema/; php calcoloSla_massive.php
//0 9,11,13,15,17 * * * cd /var/www/html/teknema; php hpreturn.php
//00 17 * * * sh /var/www/backup/back.sh
//30 */1 * * * cd /var/www/html/teknema; php modules/Project/uphpsm.php

//SHELL=/bin/bash
//PATH=/sbin:/bin:/usr/sbin:/usr/bin
//MAILTO=root
//HOME=/
//
//# For details see man 4 crontabs
//
//# Example of job definition:
//# .---------------- minute (0 - 59)
//# |  .------------- hour (0 - 23)
//# |  |  .---------- day of month (1 - 31)
//# |  |  |  .------- month (1 - 12) OR jan,feb,mar,apr ...
//# |  |  |  |  .---- day of week (0 - 6) (Sunday=0 or 7) OR sun,mon,tue,wed,thu,fri,sat
//# |  |  |  |  |
//# *  *  *  *  * user-name command to be executed
//
//
//30 08 * * *  root /var/www/jobs/notifica_OrdiniParts_spedite_0.1/notifica_OrdiniParts_spedite/notifica_OrdiniParts_spedite_run.sh
//
//00 17 * * * root /var/www/jobs/notifica_parti_nonSpedite/notifica_parti_nonSpedite/notifica_parti_nonSpedite_run.sh
//
//00 03 * * * root /usr/bin/php /var/www/html/teknema/cron/update_dayson.php


 ?>


