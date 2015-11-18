<?php
include_once('data/CRMEntity.php');
include_once('modules/Map/Map.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
include_once('modules/Emails/mail.php');
include_once('modules/Users/Users.php');
global $adb, $log, $root_directory, $current_user;
$current_user = new Users();
$request = array();
if (isset($argv) && !empty($argv)) {
    for ($i = 1; $i < count($argv); $i++) {
        list($key, $value) = explode("=", $argv[$i]);
        $request[$key] = $value;
    }
}
$table = $request['tablein'];
$mapid = $request['mapid'];

$mapfocus = CRMEntity::getInstance("Map");
$mapfocus->retrieve_entity_info($mapid, "Map");
$mapinfo = $mapfocus->readImportType();

$updateFld = $mapinfo['target'];
$matchFld = $mapinfo['match'];
$options = $mapinfo['options'];

//Create table structure
$columns = array();
$export_table = "export_" . $table;
$log->debug("exportttttt".$export_table);
$log->debug("matchfield");
$log->debug($matchFld);

$drop = "DROP table if exists $export_table;";
$adb->query($drop);
foreach ($updateFld as $upkey => $upVal) {
    $value = $upVal['value'];
    $columns[] = " `$value` varchar(250)";
}
$create = "CREATE table if not exists $export_table (" . implode(",", $columns) . ");";

$adb->query($create);
$module = $mapfocus->getMapTargetModule();
include_once("modules/$module/$module.php");

$focus = CRMEntity::getInstance($module);
$customfld = $focus->customFieldTable;

$header = NULL;
$data = array();
$dataQuery = $adb->query("SELECT * FROM $table");
while ($dataQuery && $data = $adb->fetch_array($dataQuery)) {
    $id = $data['id'];
    $params = array();
    $index_q = "SELECT $focus->table_name.$focus->table_index
            FROM $focus->table_name
            INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid=$focus->table_name.$focus->table_index
            INNER JOIN $customfld[0] ON $customfld[0].$customfld[1]=$focus->table_name.$focus->table_index
            WHERE vtiger_crmentity.deleted=0 ";
    foreach ($matchFld as $k => $v) {
        $params[] = $data[$v];
        $index_q.=" AND $k =? ";
    }
    $index_query = $adb->pquery($index_q, $params);
    $nr_rows = $adb->num_rows($index_query);
    if ($nr_rows > 0) {
        $allids = array();
        if ($options['update'] == 'FIRST') {
            $allids[] = $adb->query_result($index_query, 0, $focus->table_index);
        } elseif ($options['update'] == 'LAST') {
            $allids[] = $adb->query_result($index_query, $nr_rows - 1, $focus->table_index);
        }
        if ($options['update'] == 'ALL') {
            for ($i = 0; $i < $nr_rows; $i++) {
                $allids[] = $adb->query_result($index_query, $i, $focus->table_index);
            }
        }
        for ($el = 0; $el < count($allids); $el++) {
            $index_result = $adb->query_result($index_query, $el, $focus->table_index);
            $paramsValues = array();
            if (!empty($index_result)) {
                $focus->retrieve_entity_info($index_result, $module);
                foreach ($updateFld as $upkey => $upVal) {
                    $predefined = $upVal['predefined'];
                    if ($predefined == 'AUTONUM')
                        $paramsValues[] = $el;
                    elseif (!empty($focus->column_fields[$upkey]))
                        $paramsValues[] = $focus->column_fields[$upkey];
                    else
                        $paramsValues[] = $predefined;
                }
                $adb->pquery("INSERT INTO $export_table VALUES (" . generateQuestionMarks($paramsValues) . ")", $paramsValues);
                //if (!empty($focus->id)) {
                $adb->pquery("UPDATE $table SET selected=1 WHERE id=?", array($id));
                //}
            }
        }
    }
}
$response['tablename'] = $export_table;
echo json_encode($response, true);
?>
