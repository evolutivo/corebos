<?php



function vtws_GetDashboardInstance($instance){
    global $currentModule;
    $currentModule=$instance;
    global $adb,$app_strings,$mod_strings,$current_language,$theme,$current_user;
    require_once('data/CRMEntity.php');
    require_once('include/utils/CommonUtils.php');
    require_once('include/ListView/ListView.php');
    require_once('include/utils/utils.php');
    require_once('modules/CustomView/CustomView.php');
    require_once("modules/$instance/utils.php");

    require_once("modules/$instance/$instance.php");
    ini_set('max_execution_time',100);
    //ini_set('display_errors','On');
    $focus=new $instance();
    $table=$focus->table;
    $permitted_tabs=array();
    $allRoles=getRoleSubordinates($current_user->roleid);
    array_push($allRoles,$current_user->roleid);
    $querytabs=$adb->pquery("SELECT * FROM $table"."tabs dstabs
                            INNER JOIN $table". "entities ds_en ON ds_en.dsid=dstabs.id
                            WHERE ds_en.name=? AND ds_en.entity=? AND ds_en.deleted=0 
                            ORDER BY dstabs.tab_sequence",array($instance,'Tabs'));
    for($i=0;$i<$adb->num_rows($querytabs);$i++){
        $tabid=$adb->query_result($querytabs,$i,'id');
        //$roles=explode(",",$adb->query_result($querytabs,$i,'tab_roles'));
        $users=explode(",",$adb->query_result($querytabs,$i,'tab_users'));
        $roles=$adb->query_result($querytabs,$i,'tab_roles');
        $permitted_blocks=array();

      if(in_array($roles,$allRoles) || in_array($current_user->id,$users)){
          $tablabel=getTranslatedString($adb->query_result($querytabs,$i,'tab_label'));
          $permitted_tabs[$tabid]['info']=array('tab_label'=>$tablabel);
          $queryblocks=$adb->pquery("SELECT * FROM $table"."blocks dsblocks
                                     INNER JOIN $table". "entities ds_en ON ds_en.dsid=dsblocks.id
                                     WHERE ds_en.name=? AND ds_en.entity=? AND ds_en.deleted=0 AND block_tab=? 
                                     ORDER BY block_sequence",array($instance,'Blocks',$tabid));
          for($j=0;$j<$adb->num_rows($queryblocks);$j++){
            $blockid=$adb->query_result($queryblocks,$j,'id');

            $blockroles=explode(",",$adb->query_result($queryblocks,$j,'block_roles'));
            $blockusers=explode(",",$adb->query_result($queryblocks,$j,'block_users'));
             if(in_array($current_user->roleid,$blockroles) || in_array($current_user->id,$blockusers)){
                 $blockname=$adb->query_result($queryblocks,$j,'block_label');
                 $mandatory_fields=array();
                 $focus->id=$blockid;
                 $all_fields=$focus->getBlockFields();
                 $mandadory_fieldname=array();
                 $mandatory_fieldlabel=array();
                 foreach($all_fields as $index=>$info){
                     if($info['mandatory']==1){
                         $mandadory_fieldname[]=$info['fieldname'];
                         $mandatory_fieldlabel[]=  getTranslatedString($info['fieldlabel']);
                     }
                 }
                 $permitted_blocks[]=array('blockid'=>$blockid,'blockname'=>$blockname,
                     'blocklabel'=>  getTranslatedString($blockname),'fields'=>$all_fields,
                     'mandatory_fieldname'=>implode(",",$mandadory_fieldname),
                     'mandatory_fieldlabel'=>implode(",",$mandatory_fieldlabel) );
             }  

          }
          $permitted_tabs[$tabid]['blocks']=$permitted_blocks;
      }

    }

   return  $permitted_tabs;
}

function vtws_RetrieveResults($instance,$ds_blockid,$request_values){
    global $currentModule;
    $currentModule=$instance;
    global $adb,$app_strings,$mod_strings,$current_language,$theme,$current_user;
    require_once('data/CRMEntity.php');
    require_once('include/utils/CommonUtils.php');
    require_once('include/ListView/ListView.php');
    require_once('include/utils/utils.php');
    require_once('modules/CustomView/CustomView.php');
    require_once("modules/$instance/utils.php");
    require_once("modules/$instance/$instance.php");
    require_once("modules/$instance/crudSelected.php");
    
    $dashboardStructure = new $currentModule();
    $dashboardStructure->id = $ds_blockid;
    $table = $dashboardStructure->table;
    $searchTable = $table . "results_" . $ds_blockid . "_" . $current_user->id;
    $configTable = $searchTable . "_config";

    $adb->query("CREATE TABLE IF NOT EXISTS $searchTable (
      `userid` int(11) NOT NULL,
      `crmid` int(11) NOT NULL,
      `entity` char(15) NOT NULL,
      `selected` tinyint(4) NOT NULL,
      KEY `userid` (`userid`,`selected`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

    $adb->query("CREATE TABLE IF NOT EXISTS $configTable (
      `id` int(11) NOT NULL,
      `select_query` TEXT  NULL,
      `cond_query` TEXT NULL,
      `where_query` TEXT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

    $adb->query("DELETE from $configTable");

    $arr1=json_decode($request_values,true);
    $new_array = array('exec'=>'List');
    array_merge($arr1, $new_array);//array('filter_adocmaster'=>'All');
    $listQuery = $dashboardStructure->getListViewQuery($ds_blockid,'',$arr1);
    $resultQuery = $dashboardStructure->getResultQuery($ds_blockid, "", $configTable);
    // Searching...
    // delete previous search results
    $adb->query("DELETE from $searchTable where userid=" . $current_user->id);

    $insertQuery = "INSERT INTO $searchTable (userid, selected, crmid, entity) ";
    $result = $adb->query($insertQuery . ' ( ' . $listQuery . ' )');

    $fields = $resultQuery['fields'];
    $fieldArray = array();
    foreach ($fields as $fldElement) {
        $fldname[] = $fldElement['fieldname'];
        $fldlabel[] = $fldElement['fieldlabel'];
    }
    $tblname = $table . "results_" . $ds_blockid . "_" . $current_user->id;
    $configtable = $tblname . "_config";
    $records=crudSelected($ds_blockid,$tblname,$configtable,$arr1);
    //Parameters
    $blockParameters=$dashboardStructure->getBlockParameters();
    $qtot = "select count(*) from $searchTable where userid=" . $current_user->id;
    $total = $adb->query_result($adb->query($qtot), 0, 0);
    
    return array('total'=>$total,'records'=>$records['results'],
        'columns'=>$fldname,'fieldlabels'=>$fldlabel
            ,'blockParameters'=>$blockParameters);
}

?>
