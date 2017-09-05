<?php

/*************************************************************************************************
 * Copyright 2014 Opencubed -- This file is a part of TSOLUCIO coreBOS customizations.
 * You can copy, adapt and distribute the work under the "Attribution-NonCommercial-ShareAlike"
 * Vizsage Public License (the "License"). You may not use this file except in compliance with the
 * License. Roughly speaking, non-commercial users may share and modify this code, but must give credit
 * and share improvements. However, for proper details please read the full License, available at
 * http://vizsage.com/license/Vizsage-License-BY-NC-SA.html and the handy reference for understanding
 * the full license at http://vizsage.com/license/Vizsage-Deed-BY-NC-SA.html. Unless required by
 * applicable law or agreed to in writing, any software distributed under the License is distributed
 * on an  "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the
 * License terms of Creative Commons Attribution-NonCommercial-ShareAlike 3.0 (the License).
 ***********************************************************************************************
 *  Module       : Perspectives
 *  Version      : 5.4.0
 *  Author       : axhemshahaj
 *************************************************************************************************/

if (isset($_REQUEST['installcmp'])) {

    $recordid = htmlspecialchars($_POST['recordid']);
    $filename = getFileNameById($recordid);
    $dir = 'perspectives';
    $source = realpath(__DIR__ . '/../..') . "/" . $dir . '/' . $filename;
    $destination = realpath(__DIR__ . '/../..') . "/" . $filename;

    //copy filename from dir to root folder 
    copy($source, $destination);
    $my_url = "http://" . $_SERVER['SERVER_NAME'];
    rename($filename, 'composer.json');

    //$output = shell_exec("/test.php '".$my_url."' '".$my_refer."'");
    //execute command view shell_exec()
    shell_exec($root_directory . ' php composer.phar install ');

    $return_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[SERVER_NAME]" . '/evolutivocore/index.php?module=Perspectives&parenttab=ptab&action=DetailView&record=' . $recordid . '';

    header("Location: {$return_link}");
}

/**
 * 
 * @global type $adb
 * @param int $recordId
 * @return string filename
 */
function getFileNameById($recordId) {
    global $adb;
    $query = 'SELECT perspective_file FROM vtiger_perspectives where perspectivesid=?';
    $result = $adb->pquery($query, array($recordId));
    if ($result and $adb->num_rows($result) > 0) {
        $res = $adb->query_result($result);
        return $res;
    } else {
        return NULL;
    }
}
