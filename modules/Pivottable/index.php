<?php

require_once('Smarty_setup.php');
require_once('user_privileges/default_module_view.php');

global $mod_strings, $app_strings, $currentModule, $current_user, $theme, $singlepane_view;
require('user_privileges/user_privileges_'.$current_user->id.'.php');

include_once("modules/Pivottable/pivotfunc.php");
include_once("modules/Reports/Reports.php");
include("modules/Reports/ReportRun.php");
global $adb,$current_user,$php_max_execution_time;

$cbAction=$_REQUEST['cbAction'];

if($cbAction=='retrieveMV'){
  
    $cbAppid=$_REQUEST['cbAppsid'];
    $reportid=$_REQUEST['reportid'];
    if($recalc=='true'){ 
        createMV($reportid,$cbAppid);
    }
    $result=$adb->pquery("Select *"
        . " from vtiger_cbApps "
        . " where cbappsid=?",array($cbAppid));
    $reports=array(
        'selectedColumnsX'=>explode(',',$adb->query_result($result,0,'selectedcolumnsx')),
        'selectedColumnsY'=>explode(',',$adb->query_result($result,0,'selectedcolumnsy')),
        'type'=>$adb->query_result($result,0,'type'));
    echo json_encode($reports);
}
elseif($cbAction=='retrieveElastic'){
  
    $cbAppid=$_REQUEST['cbAppsid'];
    $reportid=$_REQUEST['reportid'];

    $query=$adb->pquery("SELECT *
                          from  vtiger_loggingconfiguration
                          where configurationid = ?",array($reportid));
    $nr_qry=$adb->num_rows($query);
    $indextype=$adb->query_result($query,0,'indextype');

    $entries=Array();
    $tabid=  getTabid('Adocdetail');
    global $dbconfig;
    $ip='193.182.16.34';//$dbconfig['ip_server'];
    $endpointUrl = "http://$ip:9200/$indextype/denorm/_search?pretty";
//    $fields1 =array('query'=>array("term"=>array("adocdetailid"=>$id)),'sort'=>array('modifiedtime'=>array('order'=>'asc')));
    $channel1 = curl_init();
    curl_setopt($channel1, CURLOPT_URL, $endpointUrl);
    curl_setopt($channel1, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($channel1, CURLOPT_POST, true);
    //curl_setopt($channel1, CURLOPT_CUSTOMREQUEST, "PUT");
    //curl_setopt($channel1, CURLOPT_POSTFIELDS, json_encode($fields1));
    curl_setopt($channel1, CURLOPT_CONNECTTIMEOUT, 100);
    curl_setopt($channel1, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($channel1, CURLOPT_TIMEOUT, 1000);
    $response1 = json_decode(curl_exec($channel1));
    $tot=$response1->hits->total;
    
    $endpointUrl = "http://$ip:9200/$indextype/denorm/_search?pretty&size=$tot";
//    $fields1 =array('query'=>array("term"=>array("adocdetailid"=>$id)),'sort'=>array('modifiedtime'=>array('order'=>'asc')));
    $channel1 = curl_init();
    curl_setopt($channel1, CURLOPT_URL, $endpointUrl);
    curl_setopt($channel1, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($channel1, CURLOPT_POST, true);
    //curl_setopt($channel1, CURLOPT_CUSTOMREQUEST, "PUT");
//    curl_setopt($channel1, CURLOPT_POSTFIELDS, json_encode($fields1));
    curl_setopt($channel1, CURLOPT_CONNECTTIMEOUT, 100);
    curl_setopt($channel1, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($channel1, CURLOPT_TIMEOUT, 1000);
    $response1 = json_decode(curl_exec($channel1));

    foreach ($response1->hits->hits as $row) {
      $user = getUserName($row->_source->userchange);
      $update_log = explode(";",$row->_source->changedvalues);
      $source[] = $row->_source;
    }
//    var_dump(json_encode($source));
    createjson(json_encode((array) $source),$reportid); 
//    var_dump( json_encode((array) $source));
    $result=$adb->pquery("Select *"
        . " from vtiger_cbApps "
        . " where cbappsid=?",array($cbAppid));
    $reports=array(
        'selectedColumnsX'=>explode(',',$adb->query_result($result,0,'selectedcolumnsx')),
        'selectedColumnsY'=>explode(',',$adb->query_result($result,0,'selectedcolumnsy')),
        'type'=>$adb->query_result($result,0,'type'));
    echo json_encode($reports);
}
elseif($cbAction=='retrieveReport'){
  
    $cbAppid=$_REQUEST['cbAppsid'];
    $reportid=$_REQUEST['reportid'];
    $recalc=$_REQUEST['recalc'];
    if($recalc=='true'){
        $d=createReport($reportid,$cbAppid);
    }
    $result=$adb->pquery("Select *"
        . " from vtiger_cbApps "
        . " where cbappsid=?",array($cbAppid));
    $reports=array(
        'selectedColumnsX'=>explode(',',$adb->query_result($result,0,'selectedcolumnsx')),
        'selectedColumnsY'=>explode(',',$adb->query_result($result,0,'selectedcolumnsy')),
        'type'=>$adb->query_result($result,0,'type'));
    echo json_encode($reports);
}
elseif($cbAction=='updateReport'){
    
    $cbAppsid=$_REQUEST['cbAppsid'];
    $selectedX=$_REQUEST['selectedX'];
    $selectedY=$_REQUEST['selectedY'];
    $type=$_REQUEST['type'];

    $adb->pquery("Update vtiger_cbApps "
            . " set selectedColumnsX=?,"
            . " selectedColumnsY=?,"
            . " type=?"
            . " where cbappsid=?",array($selectedX,$selectedY,$type,$cbAppsid));
    
}
elseif($cbAction=='newReport'){
    
    $reportid=$_REQUEST['reportid'];
        $adb->pquery("Insert into vtiger_cbApps (reportid,type,pivot_type)"
            . " values (".$reportid.",'Table','report')",array());
    $cbAppsid=$adb->query_result($adb->query("Select max(cbappsid) as lastid"
            . " from vtiger_cbApps "),0,'lastid');
    createReport($reportid,$cbAppsid);
  echo $cbAppsid;
}
elseif($cbAction=='newMV'){
    
    $reportid=$_REQUEST['reportid'];
        $adb->pquery("Insert into vtiger_cbApps (reportid,type,pivot_type)"
            . " values (".$reportid.",'Table','mv')",array());
    $cbAppsid=$adb->query_result($adb->query("Select max(cbappsid) as lastid"
            . " from vtiger_cbApps "),0,'lastid');
    createMV($reportid,$cbAppsid);
  echo $cbAppsid;
}
elseif($cbAction=='newElastic'){
    
    $reportid=$_REQUEST['reportid'];
        $adb->pquery("Insert into vtiger_cbApps (reportid,type,pivot_type)"
            . " values (".$reportid.",'Table','elastic')",array());
    $cbAppsid=$adb->query_result($adb->query("Select max(cbappsid) as lastid"
            . " from vtiger_cbApps "),0,'lastid');
  echo $cbAppsid;
}
elseif($cbAction=='exportReport'){
    
    $reportid=$_REQUEST['reportid'];
    $cbAppsid=$_REQUEST['cbAppsid'];
    $getFile = file_get_contents('report'.$cbAppsid.'.json');
    $json_obj = json_decode($getFile,true);
    $fp = fopen('file'.$cbAppsid.'.csv', 'w');
    $header=  array_keys($json_obj[0]);
    fputcsv($fp, $header);
    foreach ($json_obj as $row) {
        fputcsv($fp, $row);
    }
    fclose($fp);
    
  echo 'file'.$cbAppsid.'.csv';
}
elseif($cbAction=='deleteReport'){
    
    $cbAppsid=$_REQUEST['cbAppsid'];
    $adb->pquery("Delete from vtiger_cbApps"
            . " where  cbappsid =?",array($cbAppsid));
}
else{
    $res_admin=$adb->pquery("Select is_admin from vtiger_users"
            . " where id=?",array($current_user->id));
    $isadmin=$adb->query_result($res_admin,0,'is_admin');
    $wh=' ';
    if($isadmin !='on'){
        $wh=' and  sharingtype="Public" ';
    }
    $reports=array();
    $result=$adb->pquery("Select *"
            . " from vtiger_cbApps "
            . " join vtiger_report on vtiger_report.reportid=vtiger_cbApps.reportid"
            . " where pivot_type='report' OR pivot_type='' OR pivot_type is null ".$wh
            ,array());
    for($count_rep=0;$count_rep<$adb->num_rows($result);$count_rep++){

        $reportid=$adb->query_result($result,$count_rep,'reportid');
        $focus=new ReportRun($reportid);
        $reports[]=array(
            'cbAppsid'=>$adb->query_result($result,$count_rep,'cbappsid'),
            'reportid'=>$adb->query_result($result,$count_rep,'reportid'),
            'reportname'=>$focus->reportname,
            'pivot_type'=>($adb->query_result($result,$count_rep,'pivot_type')=='' ? 'report' : $adb->query_result($result,$count_rep,'pivot_type')));
    }
    $result=$adb->pquery("Select *"
            . " from vtiger_cbApps "
            . " join vtiger_scripts on vtiger_scripts.id=vtiger_cbApps.reportid"
            . " where pivot_type = 'mv'"
            ,array());
    for($count_rep=0;$count_rep<$adb->num_rows($result);$count_rep++){

        $reportid=$adb->query_result($result,$count_rep,'reportid');
        $reports[]=array(
            'cbAppsid'=>$adb->query_result($result,$count_rep,'cbappsid'),
            'reportid'=>$adb->query_result($result,$count_rep,'reportid'),
            'reportname'=>$adb->query_result($result,$count_rep,'name'),
            'pivot_type'=>($adb->query_result($result,$count_rep,'pivot_type')=='' ? 'report' : $adb->query_result($result,$count_rep,'pivot_type')));
    }
    $result=$adb->pquery("Select *"
            . " from vtiger_cbApps "
            . " join vtiger_loggingconfiguration on vtiger_loggingconfiguration.configurationid=vtiger_cbApps.reportid"
            . " where pivot_type='elastic' "
            ,array());
    for($count_rep=0;$count_rep<$adb->num_rows($result);$count_rep++){

        $reportid=$adb->query_result($result,$count_rep,'reportid');
        $reports[]=array(
            'cbAppsid'=>$adb->query_result($result,$count_rep,'cbappsid'),
            'reportid'=>$adb->query_result($result,$count_rep,'reportid'),
            'reportname'=>$adb->query_result($result,$count_rep,'indextype'),
            'pivot_type'=>$adb->query_result($result,$count_rep,'pivot_type'));
    }
    $list_rep_res=$adb->query("Select *"
            . " from vtiger_report");
    $opt='';
    for($i=0;$i<$adb->num_rows($list_rep_res);$i++){
        $opt.='<option value="'.$adb->query_result($list_rep_res,$i,'reportid').'">'.$adb->query_result($list_rep_res,$i,'reportname').'</option>';
    }
    
    $current_role=$current_user->roleid;
    $srcfile=$root_directory.'modules/BiServer/';
    $files = scandir($srcfile);
    $i=0;
    $opt_mv='';
    foreach($files as $folder) {

        if($folder == '.' || $folder == '..' || $folder == '.svn' ||
                $folder == 'language' || $folder == 'Deleted_scripts' || $folder == 'CSV_files') continue;

        if(is_dir($root_directory.'modules/BiServer/'.$folder))
                {
                     $fol=$root_directory.'modules/BiServer/'.$folder.'/';
                     $folder_files = scandir($fol);

                     foreach($folder_files as $file) {
                         if($file == '.' || $file == '..' || $file == '.svn') continue;

                         $query=$adb->pquery("SELECT *
                                                  from  vtiger_scripts
                                                  where name = ?
                                                  ",array($file));
                          $nr_file=$adb->num_rows($query);

                          if($nr_file!=0){
                          $active=$adb->query_result($query,0,'active');
                          $period=$adb->query_result($query,0,'period');
                          $scriptid=$adb->query_result($query,0,'id');
                          $deleted_script=$adb->query_result($query,0,'deleted_script');
                          $query_sec=$adb->pquery("SELECT *
                                                  from  vtiger_scripts
                                                  join vtiger_scripts_security on id=scriptid
                                                  where name = ?
                                                  ",array($file));
                          $roleid=$adb->query_result($query_sec,0,'roleid');
                          $export_scr=$adb->query_result($query_sec,0,'export_scr');
                          $delete_scr=$adb->query_result($query_sec,0,'delete_scr');
                          $execute_scr=$adb->query_result($query_sec,0,'execute_scr');
                          if(  ($roleid!=$current_role &&  !$is_superadmin) || 
                                  ($deleted_script==1 && !$is_superadmin)  )
                              continue;
                          $opt_mv.='<option value="'.$scriptid.'">'.$file.'</option>';
                          $i=$i+1;
                          }
                    }

                }


        }
    $list_elastic_res=$adb->query("Select *"
            . " from vtiger_loggingconfiguration "
            . " where queryelastic <> '' ");
    $opt_elastic='';
    for($i=0;$i<$adb->num_rows($list_elastic_res);$i++){
        $opt_elastic.='<option value="'.$adb->query_result($list_elastic_res,$i,'configurationid').'">'.$adb->query_result($list_elastic_res,$i,'indextype').'</option>';
    }

    $smarty->assign('reports', json_encode($reports));
    $smarty->assign('options', $opt);
    $smarty->assign('options_mv', $opt_mv);
    $smarty->assign('options_elastic', $opt_elastic);
    $smarty->assign('isAdmin', $isadmin);
    $smarty->display('modules/Pivottable/index.tpl');
}

function createReport($reportid,$cbAppid){
    
    global $adb;
    $filtersql=false;
    $focus=new ReportRun($reportid);
    $modules_selected = array();
    $modules_selected[] = $focus->primarymodule;
    if(!empty($focus->secondarymodule)){
            $sec_modules = explode(":",$focus->secondarymodule);
            for($i=0;$i<count($sec_modules);$i++){
                    $modules_selected[] = $sec_modules[$i];
            }
    }
    // Update Reference fields list list
    $referencefieldres = $adb->pquery("SELECT tabid, fieldlabel, uitype from vtiger_field WHERE uitype in (10,101)", array());
    if($referencefieldres) {
            foreach($referencefieldres as $referencefieldrow) {
                    $uiType = $referencefieldrow['uitype'];
                    $modprefixedlabel = getTabModuleName($referencefieldrow['tabid']).' '.$referencefieldrow['fieldlabel'];
                    $modprefixedlabel = str_replace(' ','_',$modprefixedlabel);

                    if($uiType == 10 && !in_array($modprefixedlabel, $focus->ui10_fields)) {
                            $focus->ui10_fields[] = $modprefixedlabel;
                    } elseif($uiType == 101 && !in_array($modprefixedlabel, $focus->ui101_fields)) {
                            $focus->ui101_fields[] = $modprefixedlabel;
                    }
            }
    }	
    $sqlfor1=$focus->sGetSQLforReport($reportid,$filtersql);
    $sSQL = explode(" from ",$sqlfor1,2);
//    var_dump($sSQL);
    $rows = array();
    $sSQL1=explode(",",str_replace("select DISTINCT","",$sSQL[0]));
    for($i=0;$i<sizeof($sSQL1);$i++){
    if(!strstr($sSQL1[$i],"vtiger_crmentity.crmid AS '"))
    {$arr[$j]=$sSQL1[$i];
    $j++;
    }
    }
    $arr22=implode(",",$arr);

    $sSQL="select DISTINCT $arr22 from ".$sSQL[1];

    $query=sqltojson($sSQL,$reportid);
    //echo $query;
    createjson($query,$cbAppid); 
    return true;
}

function createMV($reportid,$cbAppid){
    
    global $adb;
    
    $query=$adb->pquery("SELECT *
                          from  vtiger_scripts
                          where id = ?",array($reportid));
    $nr_qry=$adb->num_rows($query);
    $filename=$adb->query_result($query,0,'name');
    $name='';
   
    $folder=$adb->query_result($query,0,'folder');   
    if($folder !='Reports')
    {
        $f=substr($filename,0,strrpos($filename,'.'));
        $tbl=strtolower($folder).'_'.$f;   
    }
    else
    {
        $f=substr($filename,0,strrpos($filename,'.'));
        $arr=explode('_',$f);
        for($t=2;$t<sizeof($arr);$t++){
        $name.=$arr[$t].'_';
        }
        $log->debug('name '.$name);
        $tbl='mv_'.substr($name,0,strlen($name)-1);
    }
    $log->debug('name2 '.$tbl);
    $sSQL="SELECT * FROM $tbl ";   

    $query=sqltojson_mv($sSQL,$reportid);
    //echo $query;
    createjson($query,$cbAppid);
    
}