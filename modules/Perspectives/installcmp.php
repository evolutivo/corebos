<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



ini_set('display_errors', '1');

if (isset($_REQUEST['installcmp'])) {

    $recordid = htmlspecialchars($_POST['recordid']);
    $filename = getFileNameById($recordid);
    $dir = 'perspectives';
    $source = realpath(__DIR__ . '/../..') . "/" . $dir . '/' . $filename;
    $destination = realpath(__DIR__ . '/../..') . "/" . $filename;

    //copy filename from dir to root folder 
    copy($source, $destination);
    $my_url = "http://" . $_SERVER['SERVER_NAME'];

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
