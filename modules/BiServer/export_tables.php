<?php

/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/


require_once('include/logging.php');
require_once('include/database/PearDatabase.php');

include_once('config.inc.php');
global $log;
$userl	= 'root';
$passl	= 'Evostorm!';
        
$port='22';
$ip='50.28.53.123';
$pass='!Treuchia23';
$us='root';
$dbname='crm_mondial';
$dbpass='faa646596';
$dbus='root';
$acno='AcIn_13';

if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");
// log in at server1.example.com on port 22
if(!($con = ssh2_connect($ip, $port))){
    $log->debug('klm1 '.$dbus .'-p'.$dbpass .$dbname);
} else {
    // try to authenticate with username root, password secretpassword
    if(!ssh2_auth_password($con, $us, $pass)) {
        $log->debug('klm2 '.$dbus .'-p'.$dbpass .$dbname);
    } else {
//$res=$adb->query("select * from vtiger_accountinstallation join vtiger_crmentity on crmid=accountinstallationid join vtiger_account on accountid=linktoacsd join vtiger_accountservicedetails on linktoaccount=accountid join vtiger_service on serviceid=linktoservice where deleted=0 and servicename='vtappCreator' and accountinstallationid=$rec");
// if($adb->num_rows($res)!=0) $evv='vtiger_evvtapps';
//else $evv='';   
$log->debug('klm5 '.$dbus .'-p'.$dbpass .$dbname);

$tmp=exec("ls -a");
$log->debug('test4 '.$tmp);
shell_exec("mysqldump --add-drop-database -u root -pfaa646596 crm_mondial vtiger_field  vtiger_tab vtiger_fieldmodulerel vtiger_report vtiger_reportmodules vtiger_selectcolumn  vtiger_role vtiger_users vtiger_blocks  vtiger_entityname >/var/www/mondial/clienttables.sql");
shell_exec("mysqldump -u root -pfaa646596 crm_mondial vtiger_evvtapps vtappsrelation >/var/www/mondial/clienttables1.sql");
shell_exec("mysqldump -u root -pfaa646596 crm_mondial vtiger_kendo_block >/var/www/mondial/clienttables2.sql");
shell_exec("mysqldump -u root -pfaa646596 crm_mondial vtiger_import_maps >/var/www/mondial/clienttables3.sql");
////if(file_exists("/var/www/clienttables.sql")) vtiger_import_maps
//        unlink("/var/www/clienttables.sql");
ssh2_scp_send($con, "/var/www/mondial/clienttables.sql","/var/www/clienttables.sql");
ssh2_scp_send($con, "/var/www/mondial/clienttables1.sql","/var/www/clienttables1.sql");
ssh2_scp_send($con, "/var/www/mondial/clienttables2.sql","/var/www/clienttables2.sql");
ssh2_scp_send($con, "/var/www/mondial/clienttables2.sql","/var/www/clienttables3.sql");


$stream=ssh2_exec($con,"mysql -u $userl -p$passl $acno$dbname</var/www/clienttables.sql ");
$stream1=ssh2_exec($con,"mysql -u $userl -p$passl $acno$dbname</var/www/clienttables1.sql ");
$stream2=ssh2_exec($con,"mysql -u $userl -p$passl $acno$dbname</var/www/clienttables2.sql ");
$stream3=ssh2_exec($con,"mysql -u $userl -p$passl $acno$dbname</var/www/clienttables3.sql ");
  
   }
}
echo phpinfo();
?>
