<?php
/*************************************************************************************************
 * Copyright 2014 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS Customizations.
* Licensed under the vtiger CRM Public License Version 1.1 (the "License"); you may not use this
* file except in compliance with the License. You can redistribute it and/or modify it
* under the terms of the License. JPL TSolucio, S.L. reserves all rights not expressly
* granted by the License. coreBOS distributed by JPL TSolucio S.L. is distributed in
* the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
* warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Unless required by
* applicable law or agreed to in writing, software distributed under the License is
* distributed on an "AS IS" BASIS, WITHOUT ANY WARRANTIES OR CONDITIONS OF ANY KIND,
* either express or implied. See the License for the specific language governing
* permissions and limitations under the License. You may obtain a copy of the License
* at <http://corebos.org/documentation/doku.php?id=en:devel:vpl11>
 *  Module       : EntittyLog
 *  Version      : 5.4.0
 *  Author       : LoggingConf
 *************************************************************************************************/

require_once('include/utils/CommonUtils.php');
global $adb,$log;
$tab_id= getTabid(vtlib_purify($_REQUEST['Screen']));
$fieldsarray=$_REQUEST['fieldstobeloggedModule'];
$elog=$_REQUEST['elog'];
$denorm=$_REQUEST['denorm'];
$norm=$_REQUEST['norm'];
if($elog=='true')
$type[]='entitylog';
if($denorm=='true')
$type[]='denormalized';
if($norm=='true')
$type[]='normalized';
$type1=implode(",",$type);
    //Updating the database
if($elog=='undefined' && $denorm=='undefined' && $norm=='undefined')
    break;
else
$update_query = "update vtiger_loggingconfiguration set fields=? , type='$type1' where tabid=?";
$update_params = array($fieldsarray, $tab_id);
$query=$adb->pquery($update_query, $update_params);
echo $query;
?>