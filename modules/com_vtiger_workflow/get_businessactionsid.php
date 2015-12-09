<?php

require_once('include/logging.php');
require_once('include/database/PearDatabase.php');

global $log,$adb;
$id=$_REQUEST['record_id'];
$sel=$_REQUEST['sel_values'];
$query=$_REQUEST['query'];
$moduleName=$_REQUEST['moduleName'];
$ticked=false; 
$content=array();

if(!isset($sel))
{
$sql="Select businessactionsid,reference
        from vtiger_businessactions
        join vtiger_crmentity on crmid=businessactionsid
        where deleted=0  and moduleactions =?
        and  reference like '$query%'
        ";
        
    
    $result=$adb->pquery($sql,array($moduleName));
    for($i=0;$i<$adb->num_rows($result);$i++)
    {
       $act_id=$adb->query_result($result,$i,'businessactionsid');
       $content[]=array('id'=>"$act_id",
           'name'=>$adb->query_result($result,$i,'reference'));
    }
    echo json_encode($content);
}
else{
 
$evo_actions=$sel;
if($evo_actions!='')
{ 
    $arr_evo_actions=explode(',',$evo_actions);

    $sql="Select businessactionsid,reference
        from vtiger_businessactions
        join vtiger_crmentity on crmid=businessactionsid
        where deleted=0  and businessactionsid in (".  generateQuestionMarks($arr_evo_actions).")
            ORDER BY FIELD( businessactionsid, $evo_actions) 
        ";  

        $content=array();
        $result=$adb->pquery($sql,array($arr_evo_actions));
        for($i=0;$i<$adb->num_rows($result);$i++)
            {
               $act_id=$adb->query_result($result,$i,'businessactionsid');
                   if($evo_actions!=''){
                   $content[$i]['id']=$act_id;
                   $content[$i]['name']=$adb->query_result($result,$i,'reference');
                }
            }
}
echo json_encode($content);
}
