<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('include/utils/utils.php');
require_once 'modules/cbMap/cbMap.php';
require_once 'include/utils/LightRendicontaUtils.php';
require_once 'include/utils/UserInfoUtil.php';
global $adb,$log,$current_user;

$kaction=$_REQUEST['kaction'];
$id=$_REQUEST['id']; 
$currentModule=  getSalesEntityType($id);
require_once "modules/$currentModule/$currentModule.php";
$focus_currMod= CRMEntity::getInstance($currentModule);
$focus_currMod->id=$id;
$focus_currMod->retrieve_entity_info($id,$currentModule);
$module_table=$focus_currMod->table_name;
$module_id=$focus_currMod->table_index;
$mapRendicontaConfig=rendicontaConfig($currentModule);
$statusfield=$mapRendicontaConfig['statusfield'];
$processtemp=$mapRendicontaConfig['processtemp'];
$processtemp_val=$focus_currMod->column_fields["$processtemp"];
$statusfield_val=$focus_currMod->column_fields["$statusfield"];
$assigned_user=$focus_currMod->column_fields["assigned_user_id"];
                
if($kaction=='retrieveProcessFlow'){
    if($processtemp_val!='' && $statusfield_val!='')
    {  
        $pfquery=$adb->pquery("SELECT pf.* ,pt.* 
                    FROM vtiger_processflow AS pf
                    JOIN vtiger_processtemplate AS pt ON pf.linktoprocesstemplate = pt.processtemplateid
                    JOIN vtiger_crmentity AS c ON c.crmid = pt.processtemplateid 
                    JOIN vtiger_crmentity AS c2 ON c2.crmid = pf.processflowid
                    WHERE processtemplateid = ? AND starttasksubstatus =? AND c.deleted =0 
                    AND c2.deleted =0",array($processtemp_val,$statusfield_val));
    }
    for($j=0;$j<$adb->num_rows($pfquery);$j++)
    {    
        $processflowsecurity=explode(' |##| ',$adb->query_result($pfquery,$j,'processflowsecurity'));
        $endsubst=$adb->query_result($pfquery,$j,'end_subst');
        $uid=$_SESSION["authenticated_user_id"];
        $roleid=$current_user->roleid;    
        $belong2role=in_array($roleid,$processflowsecurity);
        if(!$belong2role) continue;
        $exceptionAbb=makingException($id);
                
        $details='';$nextpfid='';
        
        $details[]=array('test'=>'notactive','id'=>'','text'=>'','qid'=>'','questiontype'=>'','nextcase'=>'','workflowtype'=>'');
   
        $startst=$adb->query_result($pfquery,$j,'starttasksubstatus');
        $processflowid=$adb->query_result($pfquery,$j,'processflowid');
        $livello=$adb->query_result($pfquery,$j,'livello')+10;
        $tempid=$adb->query_result($pfquery,$j,'linktoprocesstemplate');
        $pfcase=$adb->query_result($pfquery,$j,'processflowcase');
        $nextpf=$adb->query("Select * 
            from vtiger_processflow as pf 
            join vtiger_crmentity as c on pf.processflowid=c.crmid 
            where deleted=0 and 
            starttasksubstatus='".$startst."' "
            . " and end_subst='".$endst."' and processflowcase='".$pfcase."' "
            . " and linktoprocesstemplate=".$tempid);
        $nextpfid=$adb->query_result($nextpf,0,'processflowid');

        if($nextpfid==0 || $nextpfid=='')
        {
            $nextpfidq=$adb->query("Select * "
                    . " from vtiger_processflow as pf "
                    . " join vtiger_crmentity as c on pf.processflowid=c.crmid "
                    . " where deleted=0 and end_subst='".$endst."' "
                    . " and linktoprocesstemplate=".$tempid);
            $nextpfid=$adb->query_result($nextpfidq,0,'processflowid');

        }
        $mailer_action=$adb->query_result($pfquery,$j,'mailer_action');
        $act_name='';
        if($mailer_action!='')
        {
            $mess_name=$adb->pquery("Select reference from vtiger_businessactions "
                 . " where businessactionsid=?",array($mailer_action));
            $act_name=$adb->query_result($mess_name,0,'reference');
        }
        $col[]=array("name"=>$adb->query_result($pfquery,$j,'processflowname'). " ",
                    "casepf"=>$adb->query_result($pfquery,$j,'processflowcase'),
                    "cards"=>array(
                    array("title"=>  strtoupper($adb->query_result($pfquery,$j,'starttasksubstatus')),
                          "ptname"=>$endsubst,
                          'pfid'=>$processflowid,
                          "details"=> $details ,
                          "currentid"=> $adb->query_result($pfquery,$j,'processflowid'),
                          "mailer_action"=> $act_name,
                          "end_substatus"=> $adb->query_result($pfquery,$j,'end_subst'),
                            )
                    )
                );
        $col[]=array("name"=>$adb->query_result($pfquery,$j,'endtaskname'),
                        "cards"=>array(
                            array("title"=> strtoupper($adb->query_result($pfquery,$j,'end_subst')),
                                "ptname"=>$endsubst,
                                'pfid'=>$processflowid,
                            )
                        ));   
        
    }
    $arr=array("name"=> "Kanban Board",
                "numberOfColumns"=> 2,
                "columns"=>$col,
                       );
    echo json_encode($arr);
}
elseif($kaction=='retrieve'){
    
    $roleid=$current_user->roleid;
    $resp_f=array();
    $resp_fields=array();
    $target_picklist=array();
    $target_picklist_actual=array();
    $conditions=array();
    $mapFieldDependecy=array();
    $q_business_rule="Select businessrule,linktomap "
            . " from vtiger_businessrules"
            . " join vtiger_cbmap on vtiger_businessrules.linktomap=vtiger_cbmap.cbmapid"
            . " join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_cbmap.cbmapid"
            . " where module_rules=?"
            . " and maptype ='FieldDependency' and deleted=0";
    $res_business_rule=$adb->pquery($q_business_rule,array("$currentModule"));
    for ($m=0;$m<$adb->num_rows($res_business_rule);$m++)
    {
        $businessrule=$adb->query_result($res_business_rule,$m,'businessrule'); 
        $linktomap=$adb->query_result($res_business_rule,$m,'linktomap');  
        if(empty($linktomap)) continue;
        $mapfocus=  CRMEntity::getInstance("cbMap");
        $mapfocus->retrieve_entity_info($linktomap,"cbMap");
        $mapFieldDependecy[$m]=$mapfocus->getMapFieldDependecy();
        $resp_fields=$mapFieldDependecy[$m]['respfield'];
        $resp_values=$mapFieldDependecy[$m]['respvalue'];
        $target_pick=$mapFieldDependecy[$m]['target_picklist'];
        $target_picklist_values=$mapFieldDependecy[$m]['target_picklist_values']["$statusfield"];
        $conditions='';
        for($c_resp=0;$c_resp<sizeof($resp_fields);$c_resp++){
            $arr_val=explode('"',$resp_values[$c_resp]);
            if(in_array($focus_currMod->column_fields[$resp_fields[$c_resp]],$arr_val))
            {
                $conditions[$m]=1;
            }
            else
            {
                $conditions[$m]=0;//Creating  AND Logic Operator
                break;
            }
        }
        if($conditions[$m] && in_array("$statusfield",$target_pick )){
            $target_picklist_actual=$target_picklist_values;
            break;
        }
    } 
   
    $picklistValues = getAssignedPicklistValues("$statusfield",$roleid, $adb);
    $i=0;
    $options=array();
    $color='orange';
    if(!empty($picklistValues)){
        foreach($picklistValues as $order=>$pickListValue){
            if(empty($pickListValue)) continue;
//            $correspond_index=  array_search($pickListValue,$red_arr);
//            $correspond_index_no=  array_search($pickListValue,$orange_arr);
            if($focus_currMod->column_fields["$statusfield"]==$pickListValue)
            {
                $color='green';
            }
            if(  in_array($pickListValue,$target_picklist_actual) ) {
                 $options[$i]['value'] = getTranslatedString($pickListValue);
                 $options[$i]['valuereal'] = $pickListValue;
                 $options[$i]['color'] = $color;
                 $i++;   
            }
         }
    }
    echo json_encode($options);
}
elseif($kaction=='rendiconta'){
    
    $next_sub=$_REQUEST['next_sub'];
    $pfid=$_REQUEST['pfid'];

    $focus_currMod->mode = 'edit';
    $actual_substatus=$focus_currMod->column_fields["$statusfield"];
    $entity_field_arr=getEntityFieldNames("$currentModule");
    $entity_field=$entity_field_arr["fieldname"];//var_dump($entity_field);
    if (is_array($entity_field)) {
        $entityname=$entity_field[0];
    } 
    else {
        $entityname=$entity_field;
    }
    $entityname_val=$focus_currMod->column_fields["$entityname"]; 
    $dt_crm = $focus_currMod->column_fields["data_invio_crm"];
       
    $processlog=array('actual_substatus'=>$actual_substatus,
        'next_substatus'=>$next_sub,
        'current_user'=>$_SESSION['authenticated_user_id'],
        'entityname_val'=>$entityname_val,
        'entity_id'=>$id,
        'recordid'=>$id,
        'statusfield'=>$statusfield,
        'currentModule'=>$currentModule);
    updateStatusFld($processlog);
    //createProcessLog($processlog);
    if($currentModule=='SalesOrderMaster'){
        $processlog['current_date_crm'] =  $dt_crm;
    }
    $resp=pickFlow($pfid,$processlog);
    if(is_array($resp)){
        echo json_encode($resp,true);
    }
    else{
        echo $resp;
    }
    //putCaseAssigned($processlog);
//    plannedAlertSetting($process);
        
}
elseif($kaction=='generalInfo'){
    
    $uid=$_SESSION["authenticated_user_id"];
    $toBeSent='';
    $motivo_ordine          =$focus_currMod->column_fields["motivo_ordine"];
    $stato_amministrativo   =$focus_currMod->column_fields["stato_amministrativo"];
    $checkEroga=checkErogazione($id);
    if(empty($motivo_ordine) || $motivo_ordine== '' || $motivo_ordine == ' '
            || $motivo_ordine == null 
            || $motivo_ordine == '--Nessuno--'){
        $toBeSent="Prego inserire motivo annullamento";
    }
    else if($stato_amministrativo== 'Incassato' || $stato_amministrativo == 'Fatturato'){
        $toBeSent="Stato amministrativo non può essere $stato_amministrativo";
    }
    else if($checkEroga){
        $toBeSent="Verificare lo stato degli Item collegati";
    }    
    echo json_encode(array('toBeSent'=>$toBeSent ));
}

function makingException($somId){
    global $adb;
    $toReturn=false;
    $query="Select * "
            . " from vtiger_salesordermaster"
            . " join vtiger_accountdetails on vtiger_salesordermaster.salesordermasterid=vtiger_accountdetails.ordine_accdet"
            . " join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_accountdetails.accountdetailsid"
            . " where vtiger_salesordermaster.salesordermasterid=? "
            . " and deleted=0";
    $result=$adb->pquery($query,array("$somId"));
    $nrItems=$adb->num_rows($result);
    $nrAbb=0;
    for($i=0;$i<$nrItems;$i++){
        $tipo_prod=$adb->query_result($result,$i,'tipo_prod');
        if($tipo_prod=='ABB'){
            $nrAbb++;
        }
    }
    if($nrAbb==$nrItems && $nrItems!=0)
    {
        $toReturn=true;
    }
    return $toReturn;
}

function checkScontoInConferma($somId){
    global $adb;
    $toReturn=false;
    $query="Select * "
            . " from vtiger_salesordermaster"
            . " join vtiger_accountdetails on vtiger_salesordermaster.salesordermasterid=vtiger_accountdetails.ordine_accdet"
            . " join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_accountdetails.accountdetailsid"
            . " where vtiger_salesordermaster.salesordermasterid=? "
            . " and deleted=0 AND tipo_prod='ABB' ";
    $result=$adb->pquery($query,array("$somId"));
    $nrItems=$adb->num_rows($result);
    for($i=0;$i<$nrItems;$i++){
        $product_id=$adb->query_result($result,$i,'product_id');
        $sconto=$adb->query_result($result,$i,'sconto');
        $statoabbonamento=$adb->query_result($result,$i,'statoabbonamento');
        $queryProd="Select sconto_applicato "
                . " from vtiger_productmiscellanee"
                . " join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_productmiscellanee.productmiscellaneeid"
                . " where vtiger_productmiscellanee.productmiscellaneeid=? "
                . " and deleted=0";
        $resultProd=$adb->pquery($queryProd,array("$product_id"));
        $numProd=$adb->num_rows($resultProd);
        if($resultProd && $numProd>0){
            $sconto_applicato=$adb->query_result($resultProd,0,'sconto_applicato');
            if($sconto>$sconto_applicato && $statoabbonamento!=='Cancellato'){
                $toReturn=true;
                break;
            }
        }       
    }
    return $toReturn;
}

function checkScontoInLav($somId){
    global $adb;
    $toReturn=false;
    $query="Select * "
            . " from vtiger_salesordermaster"
            . " join vtiger_accountdetails on vtiger_salesordermaster.salesordermasterid=vtiger_accountdetails.ordine_accdet"
            . " join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_accountdetails.accountdetailsid"
            . " where vtiger_salesordermaster.salesordermasterid=? "
            . " and deleted=0 AND tipo_prod='ABB'";
    $result=$adb->pquery($query,array("$somId"));
    $nrItems=$adb->num_rows($result);
    $nrSconto=0;
    for($i=0;$i<$nrItems;$i++){
        $product_id=$adb->query_result($result,$i,'product_id');
        $sconto=  floatval($adb->query_result($result,$i,'sconto'));
        $statoabbonamento=$adb->query_result($result,$i,'statoabbonamento');
        $queryProd="Select sconto_applicato "
                . " from vtiger_productmiscellanee"
                . " join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_productmiscellanee.productmiscellaneeid"
                . " where vtiger_productmiscellanee.productmiscellaneeid=? "
                . " and deleted=0";
        $resultProd=$adb->pquery($queryProd,array("$product_id"));
        $numProd=$adb->num_rows($resultProd);
        if($resultProd && $numProd>0){
            $sconto_applicato=floatval($adb->query_result($resultProd,0,'sconto_applicato'));
            if($sconto<=$sconto_applicato){
                $nrSconto++;
            }
            else if($statoabbonamento==='Cancellato'){
                $nrSconto++;
            }
        }       
    }
    if($nrSconto==$nrItems && $nrItems!=0)
    {
        $toReturn=true;
    }
    return $toReturn;
}

function checkErogazione($somId){
    global $adb;
    $toReturn=false;
    $query="Select * "
            . " from vtiger_salesordermaster"
            . " join vtiger_accountdetails on vtiger_salesordermaster.salesordermasterid=vtiger_accountdetails.ordine_accdet"
            . " join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_accountdetails.accountdetailsid"
            . " where vtiger_salesordermaster.salesordermasterid=? "
            . " and deleted=0 and (statoabbonamento = 'Attesa erogazione' OR  statoabbonamento = 'In erogazione')";
    $result=$adb->pquery($query,array("$somId"));
    $nrItems=$adb->num_rows($result);
    if($nrItems>0)
    {
        $toReturn=true;
    }
    return $toReturn;
}

function checkADVInLav($somId){
    global $adb;
    $toReturn=false;
    $query="Select * "
            . " from vtiger_salesordermaster"
            . " join vtiger_accountdetails on vtiger_salesordermaster.salesordermasterid=vtiger_accountdetails.ordine_accdet"
            . " join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_accountdetails.accountdetailsid"
            . " where vtiger_salesordermaster.salesordermasterid=? "
            . " and deleted=0 AND ( tipo_prod='ADV' || tipo_prod='Mistermatic')";
    $result=$adb->pquery($query,array("$somId"));
    $nrItems=$adb->num_rows($result);
    $nrCancellato=0;
    for($i=0;$i<$nrItems;$i++){
        $statoabbonamento=$adb->query_result($result,$i,'statoabbonamento');
        if($statoabbonamento=='Cancellato'){
            $nrCancellato++;
        }
    }
    if($nrCancellato==$nrItems && $nrItems!=0)
    {
        $toReturn=true;
    }
    return $toReturn;
}

function checkCancellato($somId){
    global $adb;
    $toReturn=false;
    $query="Select * "
            . " from vtiger_salesordermaster"
            . " join vtiger_accountdetails on vtiger_salesordermaster.salesordermasterid=vtiger_accountdetails.ordine_accdet"
            . " join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_accountdetails.accountdetailsid"
            . " where vtiger_salesordermaster.salesordermasterid=? "
            . " and deleted=0";
    $result=$adb->pquery($query,array("$somId"));
    $nrItems=$adb->num_rows($result);
    $nrCancellato=0;
    
    for($i=0;$i<$nrItems;$i++){
        $statoabbonamento=$adb->query_result($result,$i,'statoabbonamento');
        if($statoabbonamento=='Cancellato'){
            $nrCancellato++;
        }
    }
    if($nrCancellato==$nrItems && $nrItems!=0)
    {
        $toReturn=true;
    }
    return $toReturn;
}