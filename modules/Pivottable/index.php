<?php
/*************************************************************************************************
 * Copyright 2012-2013 OpenCubed  --  
 * You can copy, adapt and distribute the work under the "Attribution-NonCommercial-ShareAlike"
 * Vizsage Public License (the "License"). You may not use this file except in compliance with the
 * License. Roughly speaking, non-commercial users may share and modify this code, but must give credit
 * and share improvements. However, for proper details please read the full License, available at
 * http://vizsage.com/license/Vizsage-License-BY-NC-SA.html and the handy reference for understanding
 * the full license at http://vizsage.com/license/Vizsage-Deed-BY-NC-SA.html. Unless required by
 * applicable law or agreed to in writing, any software distributed under the License is distributed
 * on an  "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the
 * License terms of Creative Commons Attribution-NonCommercial-ShareAlike 3.0 (the License).
 *************************************************************************************************
 *  Module       : Pivottable
 *  Version      : 1.8
 *  Author       : OpenCubed
 *************************************************************************************************/

require_once('Smarty_setup.php');
require_once('user_privileges/default_module_view.php');

global $mod_strings, $app_strings, $currentModule, $current_user, $theme, $singlepane_view;
require('user_privileges/user_privileges_'.$current_user->id.'.php');

include_once("modules/Pivottable/pivotfunc.php");
include_once("modules/Reports/Reports.php");
//include("modules/Reports/ReportRun.php");
global $adb,$current_user,$php_max_execution_time;

$cbAction=$_REQUEST['cbAction'];

if($cbAction=='retrieveMV'){
  
    $cbAppid=$_REQUEST['cbAppsid'];
    $reportid=$_REQUEST['reportid'];
    $recalc=$_REQUEST['recalc'];
//    if($recalc=='true'){ 
    createMV($reportid,$cbAppid);
//    }
    $result=$adb->pquery("Select *"
        . " from vtiger_cbApps "
        . " where cbappsid=?",array($cbAppid));
    $reports=array(
        'selectedColumnsX'=>explode(',',$adb->query_result($result,0,'selectedcolumnsx')),
        'selectedColumnsY'=>explode(',',$adb->query_result($result,0,'selectedcolumnsy')),
        'type'=>$adb->query_result($result,0,'type'),
        'aggregatorName'=>explode(',',$adb->query_result($result,0,'aggregatorname')),
        'vals'=>explode(',',$adb->query_result($result,0,'vals')));
    echo json_encode($reports);
}
elseif($cbAction=='retrieveElastic'){
  
    $cbAppid=$_REQUEST['cbAppsid'];
    $reportid=$_REQUEST['reportid'];
    $recalc=$_REQUEST['recalc'];
//    if($recalc=='true'){
    $d=createElastic($reportid,$cbAppid);
//    }
    $result=$adb->pquery("Select *"
        . " from vtiger_cbApps "
        . " where cbappsid=?",array($cbAppid));
    $reports=array(
        'selectedColumnsX'=>explode(',',$adb->query_result($result,0,'selectedcolumnsx')),
        'selectedColumnsY'=>explode(',',$adb->query_result($result,0,'selectedcolumnsy')),
        'type'=>$adb->query_result($result,0,'type'),
        'aggregatorName'=>explode(',',$adb->query_result($result,0,'aggregatorname')),
        'vals'=>explode(',',$adb->query_result($result,0,'vals')));
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
        'type'=>$adb->query_result($result,0,'type'),
        'aggregatorName'=>explode(',',$adb->query_result($result,0,'aggregatorname')),
        'vals'=>explode(',',$adb->query_result($result,0,'vals')));
    echo json_encode($reports);
}
elseif($cbAction=='updateReport'){
    
    $cbAppsid=$_REQUEST['cbAppsid'];
    $selectedX=$_REQUEST['selectedX'];
    $selectedY=$_REQUEST['selectedY'];
    $type=$_REQUEST['type'];
    $aggr=$_REQUEST['aggr'];
    $aggrdrop=$_REQUEST['aggrdrop'];

    $adb->pquery("Update vtiger_cbApps "
            . " set selectedColumnsX=?,"
            . " selectedColumnsY=?,"
            . " type=?,"
            . " aggregatorName=?,"
            . " vals=?"
            . " where cbappsid=?",array($selectedX,$selectedY,$type,$aggr,$aggrdrop,$cbAppsid));
    
}
elseif($cbAction=='saveasReport'){
    
    $cbAppsid=$_REQUEST['cbAppsid'];
    $reportid=$_REQUEST['reportid'];
    $reportname=$_REQUEST['reportname'];
    $reportdesc=$_REQUEST['reportdesc'];
    $selectedX=$_REQUEST['selectedX'];
    $selectedY=$_REQUEST['selectedY'];
    $type=$_REQUEST['type'];
    $aggr=$_REQUEST['aggr'];
    $aggrdrop=$_REQUEST['aggrdrop'];
    $piv_typ=$adb->pquery("Select pivot_type"
            . " from vtiger_cbApps "
            . "where cbappsid=?",array($cbAppsid));
    $pivot_type=$adb->query_result($piv_typ,0,'pivot_type');

    $adb->pquery("Insert into vtiger_cbApps (reportid,type,pivot_type,name_pivot,desc_pivot,selectedColumnsX,"
            . " selectedColumnsY,aggregatorName,vals)"
            . " values (".$reportid.",'$type','$pivot_type','$reportname','$reportdesc','$selectedX',"
            . " '$selectedY','$aggr','$aggrdrop')",array());
    $cbAppsid=$adb->query_result($adb->query("Select max(cbappsid) as lastid"
            . " from vtiger_cbApps "),0,'lastid');
    if($pivot_type=='report'){
        createReport($reportid,$cbAppsid);
    }
    echo $cbAppsid.'@@'.$pivot_type;
}
elseif($cbAction=='updateReportName'){
    
    $cbAppsid=$_REQUEST['cbAppsid'];
    $reportname=$_REQUEST['reportname'];
    $reportdesc=$_REQUEST['reportdesc'];
    $elastic_type=$_REQUEST['elastic_type'];

    $adb->pquery("Update vtiger_cbApps "
            . " set name_pivot=?,"
            . " desc_pivot=?,"
            . " elastic_type=?"
            . " where cbappsid=?",array($reportname,$reportdesc,$elastic_type,$cbAppsid));
    
}
elseif($cbAction=='newReport'){
    
    $reportid=$_REQUEST['reportid'];
    $reportname=$_REQUEST['reportname'];
    $reportdesc=$_REQUEST['reportdesc'];
        $adb->pquery("Insert into vtiger_cbApps (reportid,type,pivot_type,name_pivot,desc_pivot)"
            . " values (".$reportid.",'Table','report','$reportname','$reportdesc')",array());
    $cbAppsid=$adb->query_result($adb->query("Select max(cbappsid) as lastid"
            . " from vtiger_cbApps "),0,'lastid');
    createReport($reportid,$cbAppsid);
  echo $cbAppsid;
}
elseif($cbAction=='newMV'){
    
    $reportid=$_REQUEST['reportid'];
    $reportname=$_REQUEST['reportname'];
    $reportdesc=$_REQUEST['reportdesc'];
        $adb->pquery("Insert into vtiger_cbApps (reportid,type,pivot_type,name_pivot,desc_pivot)"
            . " values (".$reportid.",'Table','mv','$reportname','$reportdesc')",array());
    $cbAppsid=$adb->query_result($adb->query("Select max(cbappsid) as lastid"
            . " from vtiger_cbApps "),0,'lastid');
    createMV($reportid,$cbAppsid);
  echo $cbAppsid;
}
elseif($cbAction=='newElastic'){
    
    $reportid=$_REQUEST['reportid'];
    $reportname=$_REQUEST['reportname'];
    $reportdesc=$_REQUEST['reportdesc'];
    $elastic_type=$_REQUEST['elastic_type'];
        $adb->pquery("Insert into vtiger_cbApps (reportid,type,pivot_type,name_pivot,desc_pivot,elastic_type)"
            . " values (".$reportid.",'Table','elastic','$reportname','$reportdesc','$elastic_type')",array());
    $cbAppsid=$adb->query_result($adb->query("Select max(cbappsid) as lastid"
            . " from vtiger_cbApps "),0,'lastid');
    //createElastic($reportid,$cbAppid);
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
elseif($cbAction=='index_types'){
    
    $indextosearch=$_REQUEST['indextosearch'];
    $res=$adb->pquery("Select  * 
                from vtiger_elastic_indexes 
                where id=?",array($indextosearch));
    $indexname=$adb->query_result($res,0,'elasticname');
    $opt_type_elastic=listElasticTypes($indexname);
  echo json_encode($opt_type_elastic);
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
            'reportname'=>($adb->query_result($result,$count_rep,'name_pivot')!='' ? $adb->query_result($result,$count_rep,'name_pivot') : $focus->reportname),
            'desc_pivot'=>$adb->query_result($result,$count_rep,'desc_pivot'),
            'elastic_type'=>$adb->query_result($result,$count_rep,'elastic_type'),
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
            'reportname'=>($adb->query_result($result,$count_rep,'name_pivot')!='' ? $adb->query_result($result,$count_rep,'name_pivot') : $adb->query_result($result,$count_rep,'name')),
            'desc_pivot'=>$adb->query_result($result,$count_rep,'desc_pivot'),
            'elastic_type'=>$adb->query_result($result,$count_rep,'elastic_type'),
            'pivot_type'=>($adb->query_result($result,$count_rep,'pivot_type')=='' ? 'report' : $adb->query_result($result,$count_rep,'pivot_type')));
    }
    $result=$adb->pquery("Select *"
            . " from vtiger_cbApps "
            . " join vtiger_elastic_indexes on vtiger_elastic_indexes.id=vtiger_cbApps.reportid"
            . " where pivot_type='elastic' "
            ,array());
    for($count_rep=0;$count_rep<$adb->num_rows($result);$count_rep++){

        $reportid=$adb->query_result($result,$count_rep,'reportid');
        $reports[]=array(
            'cbAppsid'=>$adb->query_result($result,$count_rep,'cbappsid'),
            'reportid'=>$adb->query_result($result,$count_rep,'reportid'),
            'reportname'=>($adb->query_result($result,$count_rep,'name_pivot')!='' ? $adb->query_result($result,$count_rep,'name_pivot') : $adb->query_result($result,$count_rep,'elasticname')),
            'desc_pivot'=>$adb->query_result($result,$count_rep,'desc_pivot'),
            'elastic_type'=>$adb->query_result($result,$count_rep,'elastic_type'),
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
                $folder == 'language' || $folder == 'Deleted_scripts' || $folder == 'CSV_files'
                 || $folder == 'INDEXES') continue;

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
    $arr=listElastic();
    $arr_el=array();
    for($i_c=1;$i_c<sizeof($arr);$i_c++) {
        $indexname=$arr[$i_c];
        if(!empty($indexname) ){
            $res=$adb->pquery("Select  * 
                from vtiger_elastic_indexes 
                where elasticname=?",array($indexname));
            if($adb->num_rows($res)==0){
                $adb->pquery("Insert into 
                          vtiger_elastic_indexes (elasticname,status)
                          values('".$indexname."','open')
                          ",array());
            }           
        }
    }
    $adb->pquery("Delete from
                vtiger_elastic_indexes
                where elasticname not in (".  generateQuestionMarks($arr).")"
            ,array($arr_el));
    
    $res=$adb->pquery("Select  * 
                from vtiger_elastic_indexes",array());
    for($i_c=0;$i_c<$adb->num_rows($res);$i_c++) {
        $elasticname=$adb->query_result($res,$i_c,'elasticname');
        $id=$adb->query_result($res,$i_c,'id');
        $opt_elastic.='<option value="'.$id.'">'.$elasticname.'</option>';
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
    
    global $adb,$log;
    
    $query=$adb->pquery("SELECT *
                          from  vtiger_scripts
                          where id = ?",array($reportid));
    $nr_qry=$adb->num_rows($query);
    $filename=$adb->query_result($query,0,'name');
    $name='';
   
    $folder=$adb->query_result($query,0,'folder');   
//    if($folder !='Reports')
//    {
//        $f=substr($filename,0,strrpos($filename,'.'));
//        $tbl=strtolower($folder).'_'.$f;   
//    }
//    else
//    {
        $f=substr($filename,0,strrpos($filename,'.'));
        $arr=explode('_',$f);
        for($t=2;$t<sizeof($arr);$t++){
        $name.=$arr[$t].'_';
        }
        $log->debug('name '.$name);
        $tbl='mv_'.substr($name,0,strlen($name)-1);
//    }
    $log->debug('name2 '.$tbl);
    $sSQL="SELECT * FROM $tbl ";   

    $query=sqltojson_mv($sSQL,$reportid);
    //echo $query;
    createjson($query,$cbAppid);
    
}

function createElastic($reportid,$cbAppid){
    
    global $adb;
    
    $query=$adb->pquery("SELECT *
                          from  vtiger_elastic_indexes
                          where id = ?",array($reportid));
    $nr_qry=$adb->num_rows($query);
    $indextype=$adb->query_result($query,0,'elasticname');
    
    $query_inde=$adb->pquery("SELECT *
                          from  vtiger_cbApps
                          where cbappsid = ?",array($cbAppid));
    $elastic_type=$adb->query_result($query_inde,0,'elastic_type');
    $typ=$elastic_type;

    $entries=Array();
    $tabid=  getTabid('Adocdetail');
    global $dbconfig;
    $ip=GlobalVariable::getVariable('ip_elastic_server', '');//'193.182.16.34';//$dbconfig['ip_server'];
    $endpointUrl = "http://$ip:9200/$indextype/$typ/_search?pretty";
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
    
    $endpointUrl = "http://$ip:9200/$indextype/$typ/_search?pretty&size=$tot";
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
    createjson(json_encode((array) $source),$cbAppid); 
    
}
function listElastic() {
    $method = "GET";
    $search_host=GlobalVariable::getVariable('ip_elastic_server', '');
    $search_port="9200";
    $url = 'http://'.$search_host.':'.$search_port.'/_stats/_indices';
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_PORT, $search_port);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
    $result = curl_exec($ch);
    curl_close($ch);
    $ary = json_decode($result,true);
    $content=array();
    $i=0;
    foreach ( $ary["indices"] as $key=>$value){        
        $content[]=$key;
    }
    return $content;
}
function listElasticTypes($indextosearch) {
    $method = "GET";
    $search_host="193.182.16.34";//GlobalVariable::getVariable('ip_elastic_server', '');
    $search_port="9200";
    $url = 'http://'.$search_host.':'.$search_port."/$indextosearch".'/_mapping';
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_PORT, $search_port);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
    $result = curl_exec($ch);
    curl_close($ch);
    $ary = json_decode($result,true);
    $content=array();
    $i=0;
    foreach ( $ary as $key=>$value){
        foreach($value as $key2=>$value2){
            foreach($value2 as $key3=>$value3){
                $i++;
                $content[]=$key3;
            }
        }   
    }
    return $content;
}
