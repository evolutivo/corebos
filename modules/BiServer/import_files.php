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

$file_zip=$_FILES["btn_import_files"]['tmp_name'];
move_uploaded_file($file_zip, '/var/www/mondial/Toolbox_Companion/'.$_FILES["btn_import_files"]['name']);

$name=explode("_",$file_zip);
$module=$name[2];
$field=$name[3];

shell_exec("unzip -o /var/www/mondial/Toolbox_Companion/".$_FILES["btn_import_files"]['name']." -d /var/www/mondial");
shell_exec('mysql -u root -pfaa646596 crm_mondial < /var/www/mondial/kendo_block_record.sql ');

$host  = $_SERVER['HTTP_HOST'];
header("Location: http://$host/mondial/index.php?module=Settings&action=listaction");
?>
