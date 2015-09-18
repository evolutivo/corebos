<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *                             ]
 */
function getShortcutUrl($id){
    global $adb;
    $sql = "select url from vtiger_shortcuts where visible=1 and id=? 
                                        order by sequence
                                        ";
    $result = $adb->pquery($sql, array($id));
    return $adb->query_result($result,0);
}
function getShortCutInformation($url){
    global $adb;
    $sql = "select label,parenttabid from vtiger_shortcuts where visible=1 and url=? 
                                        order by sequence
                                        ";
    $result = $adb->pquery($sql, array($url));
    $label=$adb->query_result($result,0);
    $menu= getParentTabName($adb->query_result($result,1));
    return array($label,$menu);
}
?>
