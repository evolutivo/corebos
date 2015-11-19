<?php

require_once("config.inc.php");
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
$csvfile = $request['csvname'];
$filename = "import/$csvfile";
$table = pathinfo($filename);
$table = "massivelauncher_" . $table['filename'];
$drop = "DROP table if exists $table;";
$adb->query($drop);
$delimiter = ';';

$fp = fopen($filename, 'r');
$frow = fgetcsv($fp, 1000, $delimiter);

$allHeaders = implode(",", $frow);
$columns = "`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `selected` varchar(20) ";
foreach ($frow as $column) {
    $columns .= ", `$column` varchar(250)";
}
$create = "CREATE table if not exists $table ($columns);";
$adb->query($create);

$file = $root_directory . $filename;
$irow = 0;
while (($data = fgetcsv($fp, 1000, $delimiter)) !== FALSE) {
    //if($irow>0){
    $row_vals = implode("','", $data);
    $str = "INSERT INTO $table  VALUES ('','','$row_vals')";
    $adb->query($str);
    // }
    $irow++;
}
$response['tablein'] = $table;
echo json_encode($response, true);
?>
