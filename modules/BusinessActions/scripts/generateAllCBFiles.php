<?php

require_once('include/utils/utils.php');
require_once('data/CRMEntity.php');
require_once('include/database/PearDatabase.php');
global $adb, $current_user, $log;

function generateAllCBFiles($request) {
    include_once('data/CRMEntity.php');
    require_once('include/utils/utils.php');
    require_once('include/database/PearDatabase.php');
    require_once("modules/Users/Users.php");
    require_once('modules/BusinessActions/BusinessActions.php');
    global $adb, $log, $current_user, $currentModule;
    $recordid = $request['recordid'];
    $cbmodule = $request['cbmodule'];
    $actionfocus = CRMEntity::getInstance("BusinessActions");
    $actionfocus->retrieve_entity_info($request['accid'], "BusinessActions");
    $viewtype = $actionfocus->column_fields['elementtype_action'];
    $causale = $actionfocus->column_fields['causale'];

    $recordid = rtrim($recordid, ";");

    if ($viewtype == 'LISTVIEWBASIC') {
        $fname = strtolower($cbmodule) . '_list_' . microtime_str();
    } else {
        $fname = strtolower($cbmodule) . '_single_' . microtime_str();
    }
    $fpath = 'cache/' . $fname . '.php';


    $generatedstring = '';
    $generatedstring .= generateDVCBUFileHeader($fname);
    $generatedstring .= generateDVCBUFile($recordid, $cbmodule, $causale, $viewtype);
    $generatedstring .= generateDVCBUFileFooter();

    file_put_contents($fpath, $generatedstring);
    chmod($fpath, 0777);
    if ($viewtype == 'DETAILVIEWBASIC' && $causale == 'create') {
        $headdesc = 'Create Record-' . $cbmodule . '-' . $recid;
    } elseif ($viewtype == 'DETAILVIEWBASIC' && $causale == 'update') {
        $headdesc = 'Update Record-' . $cbmodule . '-' . $recid;
    } elseif ($viewtype == 'LISTVIEWBASIC' && $causale == 'create') {
        $headdesc = 'Create List-' . $cbmodule . '-' . date('Y-m-d H:i:s');
    } elseif ($viewtype == 'LISTVIEWBASIC' && $causale == 'update') {
        $headdesc = 'Update List-' . $cbmodule . '-' . date('Y-m-d H:i:s');
    }
    generateXmlNodeDV($fname, $fpath, $current_user->user_name, $headdesc);
}

function generateXmlNodeDV($fname, $path, $uname, $desc) {
    include_once('vtlib/Vtiger/Zip.php');
    global $adb;
    $todir = 'cache';
    $xmlfilename = $fname . '.xml';
    $phpfilename = $fname . '.php';
    $xmlcfn = 'cache/' . $xmlfilename;
    $phpcfn = 'cache/' . $phpfilename;
    $path = 'build/changeSets/evolutivo/' . $fname . '.php';
    $zipfilename = "$todir/" . $fname . ".zip";
    $zip = new Vtiger_Zip($zipfilename);
    $w = new XMLWriter();
    $w->openMemory();
    $w->setIndent(true);
    $w->startDocument('1.0', 'UTF-8');
    $w->startElement("updatesChangeLog");

    $w->startElement("changeSet");
    if (!empty($uname)) {
        $w->startElement("author");
        $w->text($uname);
        $w->endElement();
    }
    if (!empty($desc)) {
        $w->startElement("description");
        $w->text($desc);
        $w->endElement();
    }
    $w->startElement("filename");
    $w->text($path);
    $w->endElement();
    $w->startElement("classname");
    $w->text($fname);
    $w->endElement();
    $w->startElement("systemupdate");
    $w->text('false');
    $w->endElement();
    $w->endElement();
    $bname = basename($phpcfn);
    $zip->addFile($phpcfn, $bname);

    $w->endElement();
    $fd = fopen($xmlcfn, 'w');
    $cbxml = $w->outputMemory(true);
    fwrite($fd, $cbxml);
    fclose($fd);
    $zip->addFile($xmlcfn, $xmlfilename);
    $zip->save();
    $zip->forceDownload($zipfilename);
    unlink($zipfilename);
    unlink($xmlcfn);
    unlink($phpcfn);
}

function generateDVCBUFile($fid, $module, $causal, $view) {
    require_once('include/utils/utils.php');
    require_once('data/CRMEntity.php');
    require_once('include/database/PearDatabase.php');
    global $adb, $current_user, $log;
    $gen = "\n";
    $tab_c = '    ';
    if (!empty($module)) {
        require_once('modules/' . $module . '/' . $module . '.php');
        $entityparamsres = $adb->pquery("SELECT * FROM vtiger_entityname WHERE modulename=? ",array($module));
        $entityparams = array();
        if($adb->num_rows($entityparamsres)>0){
            $entityparams['tablename'] = $adb->query_result($entityparamsres,0,'tablename');
            $entityparams['fieldname'] = $adb->query_result($entityparamsres,0,'fieldname');
            $entityparams['entityidfield'] = $adb->query_result($entityparamsres,0,'entityidfield');
        }
        $relfields = get_uitype10_fields($module);
        if ($causal == 'create' && $view == 'DETAILVIEWBASIC') {
            $focusorig = CRMEntity::getInstance($module);
            $focusorig->retrieve_entity_info($fid, $module);
            $gen  = $tab_c . $tab_c . $tab_c . 'require_once(\'modules/' . $module . '/'. $module . '.php\');';
            $gen .= $tab_c . $tab_c . $tab_c . '$focus = new ' . $module . '();' . "\n";
            $gen .= $tab_c . $tab_c . $tab_c . '$focus->mode = "";' . "\n";

            foreach ($focusorig->column_fields as $key => $value) {
                $isinarr = in_array($key, $relfields);
                if ($key !== 'record_id' && !$isinarr) {
                    $gen .= $tab_c . $tab_c . $tab_c . '$focus->column_fields[\'' . $key . '\'] = \'' . addcslashes($value, "'") . '\';' . "\n";
                }
            }
            $gen .= $tab_c . $tab_c . $tab_c . '$focus->save("' . $module . '");' . "\n";
        } elseif ($causal == 'update' && $view == 'DETAILVIEWBASIC') {
            $focusorig = CRMEntity::getInstance($module);
            $focusorig->retrieve_entity_info($fid, $module);
            $entityidentifier = $focusorig->column_fields[$entityparams['fieldname']];
            
            $gen  = $tab_c . $tab_c . $tab_c . '$currentidres = $adb->pquery("SELECT ' . $entityparams['entityidfield'] . ' FROM ' . $entityparams['tablename'] . ' INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid=' . $entityparams['tablename'] . '.' . $entityparams['entityidfield'] . ' WHERE deleted=0 AND '. $entityparams['fieldname'] . '='. '\'' . $entityidentifier . '\' ",array()) ;' . "\n";
            $gen .= $tab_c . $tab_c . $tab_c . 'require_once(\'modules/' . $module . '/'. $module . '.php\');';
            $gen .= $tab_c . $tab_c . $tab_c . 'for($z = 0; $z < $adb->num_rows($currentidres); $z++) {' . "\n";
            $gen .= $tab_c . $tab_c . $tab_c . $tab_c . '$currentid = $adb->query_result($currentidres,0,0); ' . "\n";
            $gen .= $tab_c . $tab_c . $tab_c . $tab_c . '$focus = CRMEntity::getInstance("' . $module . '");' . "\n";
            $gen .= $tab_c . $tab_c . $tab_c . $tab_c . '$focus->mode = "edit";' . "\n";
            $gen .= $tab_c . $tab_c . $tab_c . $tab_c . '$focus->id = $currentid;' . "\n";
            $gen .= $tab_c . $tab_c . $tab_c . $tab_c . '$focus->retrieve_entity_info($currentid,' . '"' . $module . '");' . "\n";
            foreach ($focusorig->column_fields as $key => $value) {
                $isinarr = in_array($key, $relfields);
                if (!$isinarr) {
                    $gen .= $tab_c . $tab_c . $tab_c . $tab_c . '$focus->column_fields[\'' . $key . '\'] =\'' . addcslashes($value, "'") . '\';' . "\n";
                }
            }
            $gen .= $tab_c . $tab_c . $tab_c . $tab_c . '$focus->save("' . $module . '");' . "\n";
            $gen .= $tab_c . $tab_c . $tab_c . ' } ' . "\n";
        } elseif ($causal == 'create' && $view == 'LISTVIEWBASIC') {
            $ids = explode(";", $fid);
            $gen = "\n";
            $gen  = $tab_c . $tab_c . $tab_c . 'require_once(\'modules/' . $module . '/'. $module . '.php\');';
            for ($i = 0; $i < count($ids); $i++) {
                $foc = '$focus_' . $ids[$i];
                $focusorig = CRMEntity::getInstance($module);
                $focusorig->retrieve_entity_info($ids[$i], $module);
                $gen .= "\n";
                $gen .= $tab_c . $tab_c . $tab_c . $foc . ' = new ' . $module . '();' . "\n";
                $gen .= $tab_c . $tab_c . $tab_c . $foc . '->mode = "";' . "\n";

                foreach ($focusorig->column_fields as $key => $value) {
                    $isinarr = in_array($key, $relfields);
                    if ($key !== 'record_id' && !$isinarr) {
                        $gen .= $tab_c . $tab_c . $tab_c . $foc . '->column_fields[\'' . $key . '\'] = \'' . addcslashes($value, "'") . '\';' . "\n";
                    }
                }
                $gen .= $tab_c . $tab_c . $tab_c . $foc . '->save("' . $module . '");' . "\n";
                unset($focusorig);
            }
        } elseif ($causal == 'update' && $view == 'LISTVIEWBASIC') {
            $ids = explode(";", $fid);
            $gen = "\n";
            $gen  = $tab_c . $tab_c . $tab_c . 'require_once(\'modules/' . $module . '/'. $module . '.php\');';
            for ($i = 0; $i < count($ids); $i++) {
                $foc = '$focus_' . $ids[$i];
                $focusorig = CRMEntity::getInstance($module);
                $focusorig->retrieve_entity_info($ids[$i], $module);
                $entityidentifier = $focusorig->column_fields[$entityparams['fieldname']];
                $gen .= "\n";
                $gen .= $tab_c . $tab_c . $tab_c . '$currentidres = $adb->pquery("SELECT ' . $entityparams['entityidfield'] . ' FROM ' . $entityparams['tablename'] . ' INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid=' . $entityparams['tablename'] . '.' . $entityparams['entityidfield'] . ' WHERE deleted=0 AND '. $entityparams['fieldname'] . '='. '\'' . $entityidentifier . '\' ",array()) ;' . "\n";
                $gen .= $tab_c . $tab_c . $tab_c . 'for($z = 0; $z < $adb->num_rows($currentidres); $z++) {' . "\n";
                $gen .= $tab_c . $tab_c . $tab_c . $tab_c . '$currentid = $adb->query_result($currentidres,0,0); ' . "\n";
                $gen .= $tab_c . $tab_c . $tab_c . $tab_c . $foc . ' = CRMEntity::getInstance("' . $module . '");' . "\n";
                $gen .= $tab_c . $tab_c . $tab_c . $tab_c . $foc . '->mode = "edit";' . "\n";
                $gen .= $tab_c . $tab_c . $tab_c . $tab_c . $foc . '->id = $currentid;' . "\n";
                $gen .= $tab_c . $tab_c . $tab_c . $tab_c . $foc . '->retrieve_entity_info($currentid,' . '"' . $module . '");' . "\n";
                foreach ($focusorig->column_fields as $key => $value) {
                    $isinarr = in_array($key, $relfields);
                    if (!$isinarr) {
                        $gen .= $tab_c . $tab_c . $tab_c . $tab_c . $foc . '->column_fields[\'' . $key . '\'] =\'' . addcslashes($value, "'") . '\';' . "\n";
                    }
                }
                $gen .= $tab_c . $tab_c . $tab_c . $tab_c . $foc . '->save("' . $module . '");' . "\n";
                $gen .= $tab_c . $tab_c . $tab_c . ' } ' . "\n";
                unset($focusorig);
            }
        }
    }
    return $gen;
}

function generateDVCBUFileHeader($classname) {
    $tab_c = '    ';
    $gen = '<?php' . "\n";
    $gen .= 'class ' . $classname . ' extends cbupdaterWorker {' . "\n";
    $gen .= $tab_c . 'function applyChange() {' . "\n";
    $gen .= $tab_c . $tab_c . 'global $adb;' . "\n";
    $gen .= $tab_c . $tab_c . 'if ($this->isApplied()) {' . "\n";
    $gen .= $tab_c . $tab_c . '$this->sendMsg(\'Changeset \'.get_class($this).\' already applied!\');' . "\n";
    $gen .= $tab_c . $tab_c . '} else {' . "\n";

    return $gen;
}

function generateDVCBUFileFooter() {
    $tab_c = '    ';
    $gen = "\n";
    $gen = $tab_c . $tab_c . $tab_c . '$this->sendMsg(\'Changeset \'.get_class($this).\' applied!\');' . "\n";
    $gen .= $tab_c . $tab_c . $tab_c . '$this->markApplied();' . "\n";
    $gen .= $tab_c . $tab_c . '}' . "\n";
    $gen .= $tab_c . $tab_c . '$this->finishExecution();' . "\n";
    $gen .= $tab_c . '}' . "\n";
    $gen .= '}' . "\n";
    $gen .= '?>' . "\n";

    return $gen;
}

function microtime_str() {
    $str = '';
    list($usec, $sec) = explode(" ", microtime());
    $val = ((float) $usec + (float) $sec);
    $str = str_replace(".", "", (string) $val);
    return $str;
}

function get_uitype10_fields($module) {
    require_once('include/utils/utils.php');
    require_once('data/CRMEntity.php');
    require_once('include/database/PearDatabase.php');
    global $adb;
    $fieldlist = array();
    $relFieldsSQL = "SELECT DISTINCT fieldname FROM vtiger_field "
            . "INNER JOIN vtiger_fieldmodulerel ON vtiger_fieldmodulerel.fieldid = vtiger_field.fieldid "
            . "WHERE uitype = '10' AND vtiger_fieldmodulerel.module = ? ";
    $relFieldsSQLQuery = $adb->pquery($relFieldsSQL, array($module));
    while ($row = $adb->fetch_array($relFieldsSQLQuery)) {
        $fieldlist[] = $row['fieldname'];
    }
    return $fieldlist;
}
?>


