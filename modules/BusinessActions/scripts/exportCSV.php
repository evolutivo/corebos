<?php
require_once 'modules/ScriptLog/ScriptLog.php';
include_once('modules/Users/Users.php');
$current_user = new Users();
$result = $current_user->retrieveCurrentUserInfoFromFile(1);
global $adb, $log, $root_directory, $current_user;
$request = array();
if (isset($argv) && !empty($argv)) {
    for ($i = 1; $i < count($argv); $i++) {
        list($key, $value) = explode("=", $argv[$i]);
        $request[$key] = $value;
    }
}
$table = $request['tablename'];

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

$response['filename']=$exportfile;
echo json_encode($response,true);
  
?>

