<?php

//ini_set('display_errors', 'on');
require_once("config.inc.php");
include_once('data/CRMEntity.php');
include_once('modules/Map/Map.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
require_once 'modules/ScriptLog/ScriptLog.php';
include_once('modules/Emails/mail.php');

global $adb, $log, $root_directory, $current_user;
$current_user = new Users();
$current_user->retrieveCurrentUserInfoFromFile(1);
if (isset($argv) && !empty($argv)) {
    $csvfile = $argv[1];
    $mapid = $argv[2];
}

$filename = "import/$csvfile";
$table = pathinfo($filename);
$table = "massivelauncher_" . $table['filename'];
$drop = "drop table if exists $table;";
$adb->query($drop);
$delimiter = ';';

$fp = fopen($filename, 'r');
$frow = fgetcsv($fp, 1000, $delimiter);

$allHeaders = implode(",", $frow);
$columns = "`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `selected` varchar(3) ";
foreach ($frow as $column) {
    $columns .= ", `$column` varchar(250)";
}
$create = "create table if not exists $table ($columns);";
$adb->query($create);

$file = $root_directory . $filename;
$irow=0;
while (($data = fgetcsv($fp, 1000, $delimiter)) !== FALSE) {
    //if($irow>0){
        $row_vals=implode("','",$data);
        $str="INSERT INTO $table  VALUES ('','','$row_vals')";
        echo $str;
        $adb->query($str);
   // }
    $irow++;
    }
$mapfocus = CRMEntity::getInstance("Map");
$mapfocus->retrieve_entity_info($mapid, "Map");
$mapinfo = $mapfocus->readImportType();

$updateFld = $mapinfo['target'];
$matchFld = $mapinfo['match'];
$options = $mapinfo['options'];

//var_dump($updateFld);exit;
$module = $mapfocus->getMapTargetModule();
include_once("modules/$module/$module.php");

$focus = CRMEntity::getInstance($module);
$customfld = $focus->customFieldTable;

$header = NULL;
$data = array();
$dataQuery = $adb->query("SELECT * FROM $table");
while ($dataQuery && $data = $adb->fetch_array($dataQuery)) {
    $id = $data['id'];
//if (file_exists($filename) && is_readable($filename)) {
//    while (($row = fgetcsv($file, 1000, $delimiter)) !== FALSE) {
    $index_q = "SELECT $focus->table_name.$focus->table_index
            FROM $focus->table_name
            INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid=$focus->table_name.$focus->table_index
            INNER JOIN $customfld[0] ON $customfld[0].$customfld[1]=$focus->table_name.$focus->table_index
            WHERE vtiger_crmentity.deleted=0 ";
//        $params = array();
//        if (!$header)
//            $header = $row;
//        else {
//            $data = array_combine($header, $row);
    foreach ($matchFld as $k => $v) {
        //echo $k."=>".$data[$v];
        $params[] = $data[$v];
        /* $index_q.=" AND $k=? "; */
        $index_q.=" AND $k LIKE '%" . $data[$v] . "%' ";
    }
    $params = array();
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
            if (!empty($index_result)) {
                $focus->retrieve_entity_info($index_result, $module);
                foreach ($updateFld as $upkey => $upVal) {
                    $predefined = $upVal['predefined'];
                    $value = $upVal['value'];
                    if ($predefined == 'AUTONUM')
                        $focus->column_fields[$upkey] = $el;
                    elseif (!empty($data[$value]))
                        $focus->column_fields[$upkey] = $data[$value];
                    else
                        $focus->column_fields[$upkey] = $predefined;
                }
                $focus->mode = 'edit';
                $focus->id = $index_result;
                $focus->saveentity($module);
                if (!empty($focus->id)) {
                    $adb->pquery("UPDATE $table SET selected=1 WHERE id=?", array($id));
                }
            }
        }
    }
//        }
}
$sql = "SELECT * FROM $table";
$exportfile=$root_directory."import/export_$table".date("Y-m-d")."_".date("H-i-s").".csv";
if(file_exists($exportfile))
shell_exec("rm -rf   $exportfile");
$fp = fopen($exportfile, 'w');
$query = $adb->pquery($sql, array());
$row1 = $adb->fetch_array($query);
$ex = array_keys($row1);
for ($i = 0; $i < sizeof($ex); $i++) {
    if ($ex[$i] != '' && is_string($ex[$i])) {
        $r[] = $ex[$i];
    }
}
fputcsv($fp, $r, ';');
$query = $adb->pquery($sql, array());
while ($row1=$adb->fetch_array($query)) {
    $r = array();
    for ($i = 0; $i < sizeof($row1); $i++) {
        $r[] = $row1[$i];
    }
    fputcsv($fp, $r, ';');
}
fclose($fp);
$data=file($exportfile);
//if($nr_updated>0){
  $focus=new ScriptLog();
  $focus->column_fields['scriptlog_name']=$table;
  $focus->column_fields['description']=implode(";",$data);;
  $focus->column_fields['assigned_user_id']=1;
  $focus->mode='';
  $focus->id='';
  $focus->save("ScriptLog");
//}
//$failedResultsQuery=$adb->query("SELECT * FROM $table  WHERE selected<>1");
//$nr_failed=$adb->num_rows($failedResultsQuery);

//}
echo "Import was lauched successfully here";
?>
