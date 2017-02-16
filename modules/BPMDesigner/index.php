<?php
require_once('Smarty_setup.php');
include_once 'modules/BPMDesigner/BPMDesigner.php';

global $root_directory,$adb,$theme,$current_user;
$kaction=$_REQUEST['kaction'];

if($kaction=='reload'){
    $processTemp=$_REQUEST['processTemp'];
    $content=loadContent($processTemp);
    echo json_encode($content);
}
else{
    $smarty=new vtigerCRM_Smarty;
    $processTemp=array();
    $query="Select * "
            . " from vtiger_processtemplate"
            . " join vtiger_crmentity on crmid=vtiger_processtemplate.processtemplateid"
            . " join vtiger_processflow on vtiger_processtemplate.processtemplateid=vtiger_processflow.linktoprocesstemplate"
            . " join vtiger_crmentity c2 on c2.crmid=vtiger_processflow.processflowid"
            . " where vtiger_crmentity.deleted=0 and c2.deleted=0"
            . " group by processtemplateid";
    $result=$adb->pquery($query,array());
    for($i=0;$i<$adb->num_rows($result);$i++){
        $processtemplateid=$adb->query_result($result,$i,'processtemplateid');
        $processtemplatename=$adb->query_result($result,$i,'processtemplatename');
        $processTemp[]=array('id'=>$processtemplateid,'name'=>$processtemplatename); 
    }
    $smarty->assign('PROCESSTEMP',$processTemp);
    $smarty->assign('PROCESSTEMP_ID',$processTemp[0]['id']);
    $smarty->display("modules/BPMDesigner/index.tpl");
}
function loadContent($procesTemp){ 
    global $adb;
    $items=array('nodes'=>array(),'edges'=>array(),'allnodes'=>array());
    $query="Select * "
            . " from vtiger_processflow"
            . " join vtiger_processflowcf on vtiger_processflow.processflowid=vtiger_processflowcf.processflowid"
            . " join vtiger_processtemplate on vtiger_processtemplate.processtemplateid=vtiger_processflow.linktoprocesstemplate"
            . " join vtiger_crmentity on crmid=vtiger_processflow.processflowid"
            . " where deleted=0 and linktoprocesstemplate=?";
    $result=$adb->pquery($query,array($procesTemp));
    for($i=0;$i<$adb->num_rows($result);$i++){
        $starttasksubstatus=$adb->query_result($result,$i,'starttasksubstatus');
        $end_subst=$adb->query_result($result,$i,'end_subst');
        $items['nodes'][]=array('data'=>array('id'=>$starttasksubstatus));
        $items['nodes'][]=array('data'=>array('id'=>$end_subst));
        $items['edges'][]=array('data'=>array('source'=>$starttasksubstatus,'target'=>$end_subst));
        if(!in_array($starttasksubstatus, $items['allnodes']))
            $items['allnodes'][]=$starttasksubstatus;
        if(!in_array($end_subst, $items['allnodes']))
            $items['allnodes'][]=$end_subst;
    }    
    return $items;
}
?>

