 <?php
  ini_set('display_errors','on');
require_once('include/database/PearDatabase.php');
require_once('include/utils/utils.php');
//maurizio.tesa@gmail.com
global $adb,$date_var,$current_user,$log,$root_directory;
 
$kaction=$_REQUEST['kaction'];
$content=array();

if($kaction=='retrieve'){ 
 
 $query=$adb->query("SELECT *
                          from  vtiger_scripts_security
                          join vtiger_scripts on scriptid=id
                          join vtiger_role on vtiger_role.roleid=vtiger_scripts_security.roleid
                          ");
  $nr_file=$adb->num_rows($query);
  
for($i=0;$i<$nr_file;$i++){
  
  $sec_id=$adb->query_result($query,$i,'sec_id');
  $scriptname=$adb->query_result($query,$i,'name');
  $scriptid=$adb->query_result($query,$i,'scriptid');
  $rolename=$adb->query_result($query,$i,'rolename');
  $roleid=$adb->query_result($query,$i,'roleid');
  $export=$adb->query_result($query,$i,'export_scr');
  $delete=$adb->query_result($query,$i,'delete_scr');
  $execute=$adb->query_result($query,$i,'execute_scr');
  $folder=$adb->query_result($query,$i,'folder');

  $content[$i]['id']=$sec_id;
  $content[$i]['name']=$scriptname;
  $content[$i]['scriptid']=$scriptid;
  $content[$i]['rolename']=$rolename;
  $content[$i]['roleid']=$roleid;
  $content[$i]['folder']=$folder;
  $content[$i]['export_scr']=($export==1 ? true : false);
  $content[$i]['delete_scr']=($delete==1 ? true : false);
  $content[$i]['execute_scr']=($execute==1 ? true : false);
      
}

echo json_encode($content);
}
elseif($kaction=='save'){
 
global $log,$adb;
$models=$_REQUEST['models'];
$model_values=array();
$model_values=json_decode($models);

$nr=count($model_values);
$mv=$model_values[$nr-1];
$query="Update vtiger_scripts_security"
     . " set scriptid=?,"
        . "  roleid=?,"
        . "  export_scr=?,"
        . "  delete_scr=?,"
        . "  execute_scr=? "
     . "  where sec_id=?";
$log->debug('klm6 '.$query);     
$query=$adb->pquery($query,array($mv->scriptid,$mv->roleid,($mv->export_scr == true ? 1 : 0),
    ($mv->delete_scr== true ? 1 : 0),($mv->execute_scr== true ? 1 : 0),$mv->id));
}
elseif($kaction=='delete'){
    
global $log,$adb;
$models=$_REQUEST['models'];
$model_values=array();
$model_values=json_decode($models);

$nr=count($model_values);
$mv=$model_values[$nr-1];

$query=$adb->pquery("Delete  from  vtiger_scripts_security
                          where sec_id = ?",array($mv->id));

}
elseif($kaction=='add'){
     global $log,$adb;
    $models=$_REQUEST['models'];
    $model_values=array();
    $model_values=json_decode($models);

    $nr=count($model_values);
    $mv=$model_values[$nr-1];
    $query="insert into  vtiger_scripts_security (scriptid,roleid,export_scr,delete_scr,execute_scr) "
         . " values (?,?,?,?,?) ";

    $query=$adb->pquery($query,array($mv->scriptid,$mv->roleid,$mv->export_scr,$mv->delete_scr,$mv->execute_scr));
}

elseif($kaction=='list_script'){ 
 
     $query=$adb->pquery("SELECT *
                          from  vtiger_scripts
                          where deleted_script <> 1",array());
      $nr_file=$adb->num_rows($query);

    for($i=0;$i<$nr_file;$i++){

      $scriptname=$adb->query_result($query,$i,'name');
      $scriptid=$adb->query_result($query,$i,'id');

      $content[$i]['id']=$scriptid;
      $content[$i]['name']=$scriptname;
    }

    echo json_encode($content);
}
elseif($kaction=='list_role'){ 
 
     $query=$adb->pquery("SELECT *
                          from  vtiger_role
                          ",array());
      $nr_file=$adb->num_rows($query);

    for($i=0;$i<$nr_file;$i++){

      $rolename=$adb->query_result($query,$i,'rolename');
      $roleid=$adb->query_result($query,$i,'roleid');

      $content[$i]['id']=$roleid;
      $content[$i]['name']=$rolename;
    }

    echo json_encode($content);
}