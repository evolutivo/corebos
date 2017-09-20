<?php  
require_once('include/utils/utils.php');
include_once('vtlib/Vtiger/Utils.php');

global $adb,$db_use,$log; 
$content=array();
$kaction=$_REQUEST['kaction'];

if($kaction=='savePF'){
    $processTemp=$_REQUEST['processTemp'];
    $start_status=$_REQUEST['start_status'];
    $end_status=$_REQUEST['end_status'];
    $focus = CRMEntity::getInstance("ProcessFlow");
    $focus->id='';
    $focus->column_fields['assigned_user_id']=1;
    $focus->column_fields['starttasksubstatus']=$start_status;
    $focus->column_fields['end_subst']=$end_status;
    $focus->column_fields['starttaskname']=$start_status;
    $focus->column_fields['endtaskname']=$end_status;
    $focus->column_fields['linktoprocesstemplate']=$processTemp;
    $focus->column_fields['processflowname']=$start_status.' -> '.$end_status;
    $focus->save("ProcessFlow"); 
}
elseif($kaction=='saveGraph'){
    global $adb;
    $models = $_REQUEST['models'];
    $mv = json_decode(file_get_contents('php://input'));
    for($i=0;$i<sizeof($mv->elements->nodes);$i++){
        $node=$mv->elements->nodes[$i];
        if ($node->data->id!=''){
            $x=$node->position->x;
            $y=$node->position->y;
            $query="Update vtiger_processflow"
            . " set positionstart=?"
            . " where starttasksubstatus=? ";
            $adb->pquery($query,array($x.','.$y,$node->data->id));
            $query="Update vtiger_processflow"
            . " set positionend=?"
            . " where  end_subst=?";
            $adb->pquery($query,array($x.','.$y,$node->data->id));
        }
    }
}
elseif($kaction=='deleteNode'){
    global $adb;
    $models = $_REQUEST['models'];
    $mv = json_decode($models);
    $node=$mv->elements->nodes[$i];
    if ($node->data->id!=''){
        $x=$node->position->x;
        $y=$node->position->y;
        $query="delete from  vtiger_processflow"
        . " where starttasksubstatus=? ";
        $adb->pquery($query,array($x.','.$y,$node->data->id));
        $query="Update vtiger_processflow"
        . " where  end_subst=?";
        $adb->pquery($query,array($x.','.$y,$node->data->id));
    }
}
elseif($kaction=='deleteEdge'){
    
}
?> 
