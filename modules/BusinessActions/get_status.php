<?php

require_once('include/logging.php');
require_once('include/database/PearDatabase.php');

global $log,$adb;

$c_val=array();
$sql="Select *
        from vtiger_actions_block
        ";    
    $result=$adb->pquery($sql,array());
    for($i=0;$i<$adb->num_rows($result);$i++)
    {
       //$content[$i]['accountid']=$adb->query_result($result,$i,'accountid');
       $block_name=$adb->query_result($result,$i,'block_name');
       $c_val[]=array("str"=>$block_name,"name"=>$block_name);

    }

    //$c_val=json_encode($c_val);

echo json_encode($c_val);