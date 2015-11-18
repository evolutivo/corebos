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
function calculateValue($opName, $arg1, $arg2) {
    switch ($opName):
        case 'NEQ': {
                return "$arg1" != "$arg2";
                break;
            }
        case 'EQ': {
                return "$arg1" == "$arg2";
                break;
            }
        case 'GTE': {
                return $arg1 >= $arg2;
                break;
            }
        case 'LTE': {
                return $arg1 <= $arg2;
                break;
            }
        case 'GTH': {
                return $arg1 > $arg2;
                break;
            }
        case 'LTH': {
                return $arg1 < $arg2;
                break;
            }
    endswitch;
}
$table = $request['tablein'];
$mapid = $request['mapid'];
//$table="massivelauncher_filterdata";
//$mapid=10160738;
//$operatorsArray=array("NEQ"=>"!=","EQ"=>"==","GTE"=>">=","LTE"=>"<=","GTH"=>">","LTH"=>"<");
//$table="massivelauncher_paserialeprice";
//$mapid=114059;
$mapfocus = CRMEntity::getInstance("Map");
$mapfocus->retrieve_entity_info($mapid, "Map");
$mapinfo = $mapfocus->readImportType();

$updateFld = $mapinfo['target'];
//print_r($updateFld);

$matchFld = $mapinfo['match'];
$options = $mapinfo['options'];

//Create table structure
$columns = array();
$export_table = "export_" . $table;
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
                    $conditions = $upVal['conditions'];
                    $c=0;
                    $foundC=false;
                   $nrConditions=count($conditions);
                    while($c<$nrConditions && ($foundC==false)){
                        $conditionArray=$conditions[$c];
                        $condRule=$conditionArray['cond'];
                        $codValue=explode(".",$conditionArray['value']);
                        $sourceModule=$codValue[0];
                        $sourceField=$codValue[1];
                      
                        $condOperator=explode("AND",$condRule);
                       // var_dump($condOperator);
                        $validatedCondition=true;
                        for($cop=0;$cop<count($condOperator);$cop++){
                            $condElements=$condOperator[$cop];
                            $condElements=explode(" ",$condElements);
                        $firstCond=explode(".",$condElements[0]);
                        $operatorCond=$condElements[1];
                        $secondCond=explode(".",$condElements[2]);
                        
                        $joinCond=$condElements[3];
                        if($sourceFirstElement=="csv"){
                            $firstFieldValue=$data[$fieldFirstElement];
                        }
                        elseif($sourceFirstElement=="module"){
                            $firstFieldValue=$focus->column_fields[$fieldFirstElement];
                        }
                        else{
                            $firstFieldValue=$fieldFirstElement;
                        }
                        $joinOp=$condElements[4];
                        $joinValue=$condElements[5];
                        
                        $sourceFirstElement=$firstCond[0];
                        $fieldFirstElement=$firstCond[1];
                        $sourceSecondElement=$secondCond[0];
                        $fieldSecondElement=$secondCond[1];
                        if($sourceFirstElement=="csv"){
                            $firstFieldValue=$data[$fieldFirstElement];
                        }
                         elseif($sourceFirstElement=="module"){
                            $firstFieldValue=$focus->column_fields[$fieldFirstElement];
                        }
                        else{
                            $firstFieldValue=$fieldFirstElement;
                        }
                        if($sourceSecondElement=="csv"){
                            $secondFieldValue=$data[$fieldSecondElement];
                        }
                        else{
                            $secondFieldValue=$focus->column_fields[$fieldSecondElement];
                        }
                        $validatedCondition *=call_user_func_array('calculateValue', array("$operatorCond","$firstFieldValue","$secondFieldValue"));
                        //$validatedCondition=call_user_func_array('calculateValue', array("$joinOp","$firstFieldValue","$secondFieldValue"));
                        }
                        if($validatedCondition){
                          $foundC=true;
                          if($sourceModule=="csv"){
                              $sourceValue=$data[$sourceField];
                          }
                          else{
                              $sourceValue=$focus->column_fields[$sourceField];
                          }
                        }
                        $c++;
                    }
                    if ($predefined == 'AUTONUM'){
                        $paramsValues[] = $el;
                    }
                    elseif (!empty($sourceValue)){
                        $paramsValues[] =$sourceValue;
                    }
                    elseif($nrConditions==0 && !empty($focus->column_fields[$upkey])){
                        $paramsValues[] = $focus->column_fields[$upkey];
                    }
                    else{
                        $paramsValues[] = $predefined;
                    }
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
